<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";

$operacao = $_POST["operacao"];
$codigo = $_POST["codigo"];


//Se o usuario estiver alterando seu pr�prio cadastro passa caso contr�rio verifica se tem permiss�o para alterar dados de pessoas
if ($usuario_codigo != $codigo) {
    if ($permissao_pessoas_cadastrar <> 1) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
}
include "includes.php";

//print_r($_REQUEST);

$id = $_POST['id'];
$cpf = $_POST['cpf'];
$cpf = limpa_cpf($cpf);
$tipopessoa = $_POST['tipopessoa'];
if ($tipopessoa == 1)
    $nome = ucwords(strtolower($_POST['nome']));
else
    $nome = $_POST['nome'];
$cidade = $_POST['cidade'];
$vila = ucwords(strtolower($_POST['vila']));
$bairro = ucwords(strtolower($_POST['bairro']));
$endereco = ucwords(strtolower($_POST['endereco']));
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$referencia = $_POST['referencia'];
$cep = $_POST['cep'];
$fone1 = $_POST['fone1'];
$fone2 = $_POST['fone2'];
$email = $_POST['email'];
$chat = $_POST['chat'];
$tipo = $_POST['box'];
$cooperativa = $_POST['cooperativa'];
$obs = $_POST['obs'];
$possuiacesso = $_POST['possuiacesso'];
$senhaatual = $_POST['senhaatual'];
$senha1 = $_POST['senha'];
$senha2 = $_POST['senha2'];
$grupopermissoes = $_POST['grupopermissoes'];
$quiosqueusuario = $_POST['quiosqueusuario'];
$data = date("Y/m/d");
$hora = date("h:i:s");
$cnpj = $_POST['cnpj'];
$cnpj = str_replace('_', '', $cnpj);
$cnpj = str_replace('.', '', $cnpj);
$cnpj = str_replace('-', '', $cnpj);
$cnpj = str_replace('/', '', $cnpj);
$ramal1 = $_POST['fone1ramal'];
$ramal2 = $_POST['fone2ramal'];
$tiponegociacao = $_POST['box2'];
$pessoacontato = $_POST['pessoacontato'];
$categoria = $_POST['categoria'];


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PESSOAS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "pessoas.png";
$tpl_titulo->show();

//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
if ($codigo != $usuario_codigo)
    $tpl_notificacao->DESTINO = "pessoas.php";
else
    $tpl_notificacao->DESTINO = "login_sair.php";


if ($operacao == "editar") {


    if ($possuiacesso == 1) {
        //Se o usu�rio preencher qualquer um dos campos de senha ent�o entende-se que ele deseja alterar a senha
        if (($senha1 != "") OR ($senha2 != "") OR ($senhaatual != "")) {
            if ($senhaatual != "") {
                //Verificar se a senha atual foi preenchida corretamente
                if ($senhaatual == "") {
                    $tpl_notificacao->MOTIVO_COMPLEMENTO = "Se você deseja atualizar a senha desta pessoa então é necessário <b>preencher a senha atual</b> para indentificar autoria do usu�rio logado.<br> Caso contrário, volte, e não digite nada em nenhuma das senhas!";
                    $tpl_notificacao->block("BLOCK_ERRO");
                    $tpl_notificacao->block("BLOCK_NAOEDITADO");
                    $tpl_notificacao->block("BLOCK_MOTIVO_FALTADADOS");
                    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
                    $tpl_notificacao->show();
                    exit;
                } else {
                    //Verificar se o valor digitado na senha atual corresponde a senha do banco
                    $sql = "SELECT pes_senha FROM pessoas WHERE pes_codigo=$codigo";
                    $query = mysql_query($sql);
                    if (!$query)
                        die("Erro0: " . mysql_error());
                    $dados = mysql_fetch_assoc($query);
                    $senhabanco = $dados["pes_senha"];
                    //Se as senhas correspondem ent�o a senha atual est� validada, conprova identidade do usuario!
                    if (md5($senhaatual) != $senhabanco) {
                        $tpl_notificacao->MOTIVO_COMPLEMENTO = "A 'Senha Atual' digitada não correponde a senha do usuário logado! Volte, digite a senha correta!";
                        $tpl_notificacao->block("BLOCK_ERRO");
                        $tpl_notificacao->block("BLOCK_NAOEDITADO");
                        $tpl_notificacao->block("BLOCK_MOTIVO_FALTADADOS");
                        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
                        $tpl_notificacao->show();
                        exit;
                    }
                }
            }

            //Verifica se os 2 campos de senha1 e senha2 foram preenchidos corretamente.            
            if (($senha1 == "") OR ($senha2 == "")) {
                $tpl_notificacao->MOTIVO_COMPLEMENTO = "Se você deseja atualizar a senha da pessoa em questão, é necessário digitar uma nova senha e re-digitá-la em seguida. Esta senha não pode ser nula/vazia!";
                $tpl_notificacao->block("BLOCK_ERRO");
                $tpl_notificacao->block("BLOCK_NAOEDITADO");
                $tpl_notificacao->block("BLOCK_MOTIVO_FALTADADOS");
                $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
                $tpl_notificacao->show();
                exit;
            } else {
                //Verificar se senha1 e senha2 correspondem
                if ($senha1 == $senha2) {
                    //Verifica se a senha tem no mínimo 3 digitos                    
                    if (strlen($senha1) < 3) {
                        $tpl_notificacao->MOTIVO_COMPLEMENTO = "A nova senha deve ter no mínimo 3 dígitos! Volte e crie uma senha maior!";
                        $tpl_notificacao->block("BLOCK_ERRO");
                        $tpl_notificacao->block("BLOCK_NAOEDITADO");
                        $tpl_notificacao->block("BLOCK_MOTIVO_FALTADADOS");
                        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
                        $tpl_notificacao->show();
                        exit;
                    } else {
                        //Alterar a senha no banco                                                
                        $senha1md5 = md5($senha1);
                        $sql1 = "UPDATE pessoas SET pes_senha='$senha1md5' WHERE pes_codigo=$codigo";
                        $query1 = mysql_query($sql1);
                        if (!$query1)
                            die("Erro1: " . mysql_error());
                        //echo "Senha alterada com sucesso!!!";
                    }
                } else {
                    $tpl_notificacao->MOTIVO_COMPLEMENTO = "A nova senha n�o corresponde com senha re-digitada. Para sua seguran�a, volte e digite a nova senha novamente!";
                    $tpl_notificacao->block("BLOCK_ERRO");
                    $tpl_notificacao->block("BLOCK_NAOEDITADO");
                    $tpl_notificacao->block("BLOCK_MOTIVO_FALTADADOS");
                    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
                    $tpl_notificacao->show();
                    exit;
                }
            }
        }
    } else { //Caso o usu�rio n�o tenha mais usu�rio
        //Alterar a senha no banco                                                
        $sql1 = "UPDATE pessoas SET pes_senha='', pes_grupopermissoes='', pes_quiosqueusuario='' WHERE pes_codigo=$codigo";
        $query1 = mysql_query($sql1);
        if (!$query1)
            die("Erro2: " . mysql_error());
        //echo "a senha, grupo de permiss�o e o quiosque do usu�rio foram zerados!";
    }
}

//Se o usu�rio est� editando seu pr�prio cadastro ent�o n�o � alterado/considerado o Tipo
if ($codigo != $usuario_codigo) {
    //Verifica se foi selecionado pelo meno um campos de tipo de pessoa
    if (empty($tipo)) {
        $tpl_notificacao->block("BLOCK_ERRO");
        if ($operacao == "cadastrar")
            $tpl_notificacao->block("BLOCK_NAOCADASTRADO");
        else
            $tpl_notificacao->block("BLOCK_NAOEDITADO");
        $tpl_notificacao->FALTADADOS_MOTIVO = "É obrigatório selecionar pelo menos um tipo de pessoa!";
        $tpl_notificacao->block("BLOCK_MOTIVO_FALTADADOS");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
        exit;
    }
}


//Verifica se existe uma pessoa com o mesmo ID
if ($operacao == "cadastrar") {
    $sql = "SELECT pes_id FROM pessoas WHERE pes_id='$id' and pes_cooperativa=$cooperativa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro3: " . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "ID";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOCADASTRADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
        exit;
    }
} else {
    //Pega o ID da pessoa que est� sendo editada
    $sql2 = "SELECT pes_id FROM pessoas WHERE pes_codigo=$codigo and pes_cooperativa=$cooperativa";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro4: " . mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $idnobanco = $dados2['pes_id'];
    if ($idnobanco != $id) {
        $sql3 = "SELECT pes_id FROM pessoas WHERE pes_id='$id' and pes_cooperativa=$cooperativa";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro5: " . mysql_error());
        $linhas3 = mysql_num_rows($query3);
        if ($linhas3 > 0) {
            $tpl_notificacao->MOTIVO_COMPLEMENTO = "ID";
            $tpl_notificacao->block("BLOCK_ERRO");
            $tpl_notificacao->block("BLOCK_NAOEDITADO");
            $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
            $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
            $tpl_notificacao->show();
            exit;
        }
    }
}
//ECHO "FAZER UMA VERIFICA��O QUE VERIFICA SE O ID DIGITADO J� EST� SENDO USADO POR OUTRA PESSOA, SE SIM ENT�O TRATAR DA MESMA FORMA QUE EST� SENDO TRATADO O NOME DE PESSOA DUPLICADO!";
//Verifica se existe uma pessoa com o mesmo nome cadastrada
//Se for um cadastro ent�o n�o pode ter nenhum registro com o mesmo nome
if ($operacao == "cadastrar") {
    $sql = "SELECT * FROM pessoas WHERE pes_nome='$nome' and pes_cooperativa=$cooperativa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro3: " . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome da pessoa";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOCADASTRADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
        exit;
    }
} else {
    $sql2 = "SELECT pes_nome FROM pessoas WHERE pes_codigo=$codigo and pes_cooperativa=$cooperativa";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro4: " . mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $nomenobanco = $dados2['pes_nome'];
    if ($nomenobanco != $nome) {
        $sql3 = "SELECT * FROM pessoas WHERE pes_nome='$nome' and pes_cooperativa=$cooperativa";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro5: " . mysql_error());
        $linhas3 = mysql_num_rows($query3);
        if ($linhas3 > 0) {
            $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome da pessoa";
            $tpl_notificacao->block("BLOCK_ERRO");
            $tpl_notificacao->block("BLOCK_NAOEDITADO");
            $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
            $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
            $tpl_notificacao->show();
            exit;
        }
    }
}

//Verifica se foi selecionado pelo menos um tipo de negociacao
/* foreach ($tipo as $tipo) {    
  if ($tipo == 5) {
  if (empty($tiponegociacao)) {
  $tpl_notificacao = new Template("templates/notificacao.html");
  $tpl_notificacao->ICONES = $icones;
  $tpl_notificacao->MOTIVO_COMPLEMENTO = "É necessário selecionar pelo menos um tipo de negociação!";
  //$tpl_notificacao->DESTINO = "produtos.php";
  $tpl_notificacao->block("BLOCK_ERRO");
  $tpl_notificacao->block("BLOCK_NAOEDITADO");
  //$tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
  $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
  $tpl_notificacao->show();
  exit;
  }
  }
  } */



//Insere no banco ou atualiza
if ($operacao == "cadastrar") {
    $sql = "
    INSERT INTO 
        pessoas (
            pes_id,
            pes_cpf,
            pes_nome,
            pes_cidade,
            pes_vila,
            pes_bairro,
            pes_endereco,
            pes_numero,
            pes_complemento,
            pes_referencia,
            pes_cep,
            pes_fone1,
            pes_fone2,
            pes_email,
            pes_chat,
            pes_cooperativa,
            pes_obs,
            pes_possuiacesso,        
            pes_datacadastro,
            pes_horacadastro,
            pes_tipopessoa,
            pes_categoria,
            pes_cnpj,
            pes_fone1ramal,
            pes_fone2ramal,
            pes_pessoacontato,
            pes_usuarioquecadastrou,
            pes_quiosquequecadastrou
        )
    VALUES (
        '$id',
        '$cpf',
        '$nome',
        '$cidade',
        '$vila',
        '$bairro',
        '$endereco',
        '$numero',
        '$complemento',
        '$referencia',
        '$cep',
        '$fone1',
        '$fone2',
        '$email',
        '$chat',
        '$cooperativa',
        '$obs',
        '0',
        '$data',
        '$hora',
        '$tipopessoa',
        '$categoria',
        '$cnpj',
        '$ramal1',
        '$ramal2',
        '$pessoacontato',
        '$usuario_codigo',
        '$usuario_quiosque'
    )";
    if (!mysql_query($sql))
        die("Erro6: " . mysql_error());

    //Cria o vinculo do tipo com a pessoa
    $ultimo = mysql_insert_id();
    $pessoa = $ultimo;
    foreach ($tipo as $tipo) {
        $sql2 = "
    INSERT INTO 
        mestre_pessoas_tipo (
            mespestip_pessoa,
            mespestip_tipo
        ) 
    VALUES (
        '$pessoa',
        '$tipo'
    )";
        if (!mysql_query($sql2))
            die("Erro7: " . mysql_error());
    }
    foreach ($tiponegociacao as $tiponegociacao) {
        $sql4 = "
    INSERT INTO 
        fornecedores_tiponegociacao (
            fortipneg_pessoa,
            fortipneg_tiponegociacao
        ) 
    VALUES (
        '$pessoa',
        '$tiponegociacao'
    )";
        if (!mysql_query($sql4))
            die("Erro7: " . mysql_error());
    }




    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_CADASTRADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
} else { //Caso seja um edi��o
    //Atualiza dados da pessoa
    $sql = "
    UPDATE 
        pessoas
    SET 
        pes_id='$id',
        pes_cpf='$cpf',
        pes_nome='$nome',
        pes_cidade='$cidade',
        pes_cep='$cep',
        pes_bairro='$bairro',
        pes_vila='$vila',
        pes_endereco='$endereco',
        pes_numero='$numero',
        pes_complemento='$complemento',
        pes_referencia='$referencia',                
        pes_fone1='$fone1',
        pes_fone2='$fone2',
        pes_email='$email',
        pes_chat='$chat',
        pes_dataedicao='$data',
        pes_horaedicao='$hora',
        pes_obs='$obs',                
        pes_cooperativa='$cooperativa',
        pes_possuiacesso='$possuiacesso',
        pes_grupopermissoes='$grupopermissoes',
        pes_quiosqueusuario='$quiosqueusuario',
        pes_tipopessoa='$tipopessoa',
        pes_categoria='$categoria',
        pes_cnpj='$cnpj',
        pes_fone1ramal='$ramal1',
        pes_fone2ramal='$ramal2',
        pes_pessoacontato='$pessoacontato'
    WHERE 
        pes_codigo = '$codigo'
    ";
    if (!mysql_query($sql))
        die("Erro8: " . mysql_error());

    //Se o usu�rio est� editando seu pr�prio cadastro ent�o n�o � alterado o Tipo
    if ($codigo != $usuario_codigo) {
        //� necess�rio deletar todos os registros de relacionamento de 'Tipo' desta pessoa para depois fazer uma nova inser��o com os novos 'tipos'
        //Aqui abaixo � feito a remo��o dos relacionamentos atuais            
        $sqldel = "
        DELETE FROM 
            mestre_pessoas_tipo 
        WHERE 
            mespestip_pessoa='$codigo'
        ";
        if (!mysql_query($sqldel))
            die("Erro9: " . mysql_error());

        //Aqui � feito a nova inser��o dos novos relacionamentos                
        foreach ($tipo as $tipo) {

            $sql2 = "
            INSERT INTO 
                mestre_pessoas_tipo (
                    mespestip_pessoa,
                    mespestip_tipo
                )
            VALUES (
                '$codigo',
                '$tipo'
            )";
            if (!mysql_query($sql2))
                die("Erro10: " . mysql_error());
        }

        //Deleta os tipo de negociação e insere denovo
        //apaga somente os tipos que o quiosque pode manipular
        $sql11 = "SELECT quitipneg_tipo FROM quiosques_tiponegociacao WHERE quitipneg_quiosque=$usuario_quiosque";
        $query11 = mysql_query($sql11);
        if (!$query11)
            die("Erro: " . mysql_error());
        while ($dados11 = mysql_fetch_assoc($query11)) {
            $tipon = $dados11["quitipneg_tipo"];
            if ($tipon == 1)
                $quiosque_consignacao = 1;
            IF ($tipon == 2)
                $quiosque_revenda = 1;
        }

        if ($usuario_quiosque == 0) {
            $sqldel = "
            DELETE FROM fornecedores_tiponegociacao 
            WHERE fortipneg_pessoa='$codigo'            
            ";            
        } else {
            $sqldel = "
            DELETE FROM fornecedores_tiponegociacao 
            WHERE fortipneg_pessoa='$codigo' 
            AND fortipneg_tiponegociacao in (
                SELECT quitipneg_tipo 
                FROM quiosques_tiponegociacao 
                WHERE quitipneg_quiosque=$usuario_quiosque
            )
            ";
        }

        if (!mysql_query($sqldel))
            die("Erro91: " . mysql_error());
        foreach ($tiponegociacao as $tiponegociacao) {
            $sql4 = "
            INSERT INTO 
            fornecedores_tiponegociacao (
                fortipneg_pessoa,
                fortipneg_tiponegociacao
            ) 
            VALUES (
            '$codigo',
            '$tiponegociacao'
            )";
            if (!mysql_query($sql4))
                die("Erro71: " . mysql_error());
        }
    }
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}





include "rodape.php";
?>

