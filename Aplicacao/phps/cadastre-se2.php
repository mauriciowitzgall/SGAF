<title>SGAF Cadastre-se</title>
<head>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br"></html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="classes.css" />
    <link rel="stylesheet" type="text/css" href="templates/geral.css">
        <script type="text/javascript" src="js/jquery-1.3.2.js"></script>
        <script src="js/jquery.maskedinput-1.1.4.pack.js" type="text/javascript"></script>
        <script src="mascaras.js" type="text/javascript"></script>    
        <script src="funcoes.js" type="text/javascript"></script>    
        <script type="text/javascript">            
            window.onload = function(){
            };
        </script>
</head>
<body>        
    <div class="pagina" >    
        <?php
        include "controle/conexao.php";
        include "funcoes.php";
        require("templates/Template.class.php");

        $dataatual = date("Y-m-d");
        $horaatual = date("H:i:s");

        $nome = ucwords(strtolower($_POST['nome']));
        $bairro = ucwords(strtolower($_POST['bairro']));
        $endereco = ucwords(strtolower($_POST['endereco']));
        $cpf = $_POST["cpf"];
        $cpf = limpa_cpf($cpf);
        $sigla = $_POST["sigla"];
        $data_nasc = $_POST["data"];
        $cep = $_POST["cep"];
        $pais = $_POST["pais"];
        $estado = $_POST["estado"];
        $cidade = $_POST["cidade"];
        $numero = $_POST["numero"];
        $complemento = $_POST["complemento"];
        $pontoref = $_POST["pontoref"];
        $telefone1 = $_POST["telefone1"];
        $telefone2 = $_POST["telefone2"];
        $email = strtolower($_POST["email"]);
        $nomepv = ucwords(strtolower($_POST['nomepv']));
        $telefone1pv = $_POST["telefone1pv"];
        $telefone1pvramal = $_POST["telefone1pvramal"];
        $telefone2pv = $_POST["telefone2pv"];
        $telefone2pvramal = $_POST["telefone2pvramal"];
        $emailpv = strtolower($_POST["emailpv"]);
        $mesmoendereco = $_POST["mesmoendereco"];
        $ceppv = $_POST["ceppv"];
        $cidadepv = $_POST["cidadepv"];
        $bairropv = ucwords(strtolower($_POST["bairropv"]));
        $enderecopv = ucwords(strtolower($_POST["enderecopv"]));
        $numeropv = $_POST["numeropv"];
        $complementopv = $_POST["complementopv"];
        $pontorefpv = $_POST["pontorefpv"];
        $possuicooperativa = $_POST["possuicooperativa"];
        $cooperativa = ucwords(strtolower($_POST["cooperativa"]));
        $cooperativa_sigla = strtoupper($_POST["cooperativa_sigla"]);
        $senha = $_POST["senha"];
        $senha2 = $_POST["senha2"];
        $email_senha = $_POST["email_senha"];
        $check_perguntasecreta = $_POST["check_perguntasecreta"];
        if (($pergunta != "") && ($resposta != ""))
            $check_perguntasecreta = 1;
        $pergunta = $_POST["pergunta"];
        $resposta = $_POST["resposta"];
        $check_termo = $_POST["check_termo"];

        //print_r($_REQUEST);
        //Verifica se este cpf já está cadastrado
        $sql = "SELECT * FROM pessoas WHERE pes_cpf like '$cpf'";
        if (!$query = mysql_query($sql))
            die("ERRO SQL" . mysql_error());
        $linhas = mysql_num_rows($query);
        if ($linhas > 0) {
            $tpl = new Template("templates/notificacao.html");
            $tpl->ICONES = "../imagens/icones/geral/";
            $tpl->ICONE_ARQUIVO = "erro.png";
            $tpl->TITULO = "NÃO CADASTRADO!";
            $tpl->block("BLOCK_TITULO");
            $tpl->MOTIVO = "A pessoa que possui o CPF digitado já está cadastrado no sistema.<br>
                Cadastre uma nova pessoa, ou então use nossos métodos de recuperação de senha para voltar a acessar o sistema com este cpf.";
            $tpl->MOTIVO_COMPLEMENTO = "";
            $tpl->block("BLOCK_MOTIVO");
            $tpl->BOTAOGERAL_DESTINO = "esqueciminhasenha.php";
            $tpl->BOTAOGERAL_TIPO = "button";
            $tpl->BOTAOGERAL_NOME = "TENTAR RECUPERAR SENHA";
            $tpl->block("BLOCK_BOTAOGERAL");
            $tpl->BOTAOGERAL_DESTINO = "../index.html";
            $tpl->BOTAOGERAL_TIPO = "button";
            $tpl->BOTAOGERAL_NOME = "RETORNAR PARA INICIO";
            $tpl->block("BLOCK_BOTAOGERAL");

            $tpl->show();
        } else {
            //Cadastrar cooperativa
            //Se o usuário não pertence a nenhuma cooperativa então criar uma cooperativa com o mesmo nome que o quiosque

            if ($possuicooperativa == 0) {
                $cooperativa = $nomepv;
                $cooperativa_sigla = $nomepv;
            }
            if ($mesmoendereco == 1) {
                $cidadepv = $cidade;
                $bairropv = $bairro;
                $enderecopv = $endereco;
                $numeropv = $numero;
                $complementopv = $complemento;
                $ceppv = $cep;
            }



            $sql = "
      INSERT INTO
      cooperativas (coo_nomecompleto, coo_abreviacao)
      VALUES
      ('$cooperativa','$cooperativa_sigla');
      ";
            if (!mysql_query($sql))
                die("Erro Cooperativa Inserir: " . mysql_error());

            //Cadastrar o quiosque dentro da cooperativa
            $cooperativa_ultimo = mysql_insert_id();
            $idunico=  uniqid();
            $sql2 = "
                INSERT INTO quiosques (
                qui_nome,
                qui_cidade,
                qui_cep,
                qui_bairro,
                qui_endereco,
                qui_numero,
                qui_complemento,
                qui_referencia,
                qui_fone1,
                qui_fone1ramal,
                qui_fone2,
                qui_fone2ramal,
                qui_email,
                qui_datacadastro,
                qui_horacadastro,
                qui_cooperativa,
                qui_idunico
                ) VALUES (
                '$nomepv',
                '$cidadepv',
                '$ceppv',
                '$bairropv',
                '$enderecopv',
                '$numeropv',
                '$complementopv',
                '$pontorefpv',
                '$telefone1pv',
                '$telefone1pvramal',
                '$telefone2pv',
                '$telefone2pvramal',
                '$emailpv',
                '$dataatual',
                '$horaatual',
                '$cooperativa_ultimo',
                '$idunico'
                );
            ";
            if (!mysql_query($sql2))
                die("Erro Quiosque Inserir: " . mysql_error());
            $quiosque_ultimo = mysql_insert_id();
            
            $sql3="
                INSERT INTO
                quiosques_tiponegociacao (
                    quitipneg_quiosque,                
                    quitipneg_tipo                
                ) VALUES (
                    '$quiosque_ultimo',
                    '1'    
                )
            ";
            if (!mysql_query($sql3))
                die("Erro Quiosque Tipo Negociação Consignação: " . mysql_error());            
            
            $sql4="
                INSERT INTO
                quiosques_tiponegociacao (
                    quitipneg_quiosque,                
                    quitipneg_tipo                
                ) VALUES (
                    '$quiosque_ultimo',
                    '2'    
                )
            ";
            if (!mysql_query($sql4))
                die("Erro Quiosque Tipo Negociação Revenda: " . mysql_error());            


             //Deve-se definir uma configuração padrão inicial para o quiosque cadastrado 
            $sql7 = "INSERT INTO quiosques_configuracoes (quicnf_quiosque)
            VALUES ($quiosque_ultimo);";
            if (!$query7= mysql_query($sql7))  die("Erro SQL Configuração quiosque".mysql_error());    


            //Inserir pessoa
            $senha = md5($senha);
            $sql = "
                INSERT INTO
                pessoas (
                pes_nome,
                pes_cidade,
                pes_bairro,
                pes_endereco,
                pes_numero,
                pes_complemento,
                pes_referencia,
                pes_cep,
                pes_fone1,
                pes_fone2,
                pes_email,
                pes_cooperativa,
                pes_possuiacesso,
                pes_datacadastro,
                pes_horacadastro,
                pes_senha,
                pes_cpf,
                pes_grupopermissoes,
                pes_quiosqueusuario,
                pes_email_senha,
                pes_usarperguntasecreta,
                pes_perguntasecreta,
                pes_respostasecreta,
                pes_tipopessoa,
                pes_id
                )
                VALUES (
                '$nome',
                '$cidade',
                '$bairro',
                '$endereco',
                '$numero',
                '$complemento',
                '$pontoref',
                '$cep',
                '$telefone1',
                '$telefone2',
                '$email',
                '$cooperativa_ultimo',
                '1',
                '$dataatual',
                '$horaatual',
                '$senha',
                '$cpf',
                '3',
                '$quiosque_ultimo',
                '$email_senha',
                '$check_perguntasecreta',
                '$pergunta',
                '$resposta',
                '1',
                '1'

                )";
            if (!mysql_query($sql))
                die("Erro6: " . mysql_error());
            $pessoa_ultimo = mysql_insert_id();

            //Cria o vinculo do tipo com a pessoa
            //Define a pessoa com os tipos: supervisor,fornecedor e consumidor
            $sql = "
      INSERT INTO mestre_pessoas_tipo (
      mespestip_pessoa,
      mespestip_tipo
      ) VALUES (
      '$pessoa_ultimo',
      '5'
      )";
            if (!mysql_query($sql))
                die("Erro Pessoas Tipo 5: " . mysql_error());
            $sql = "
      INSERT INTO mestre_pessoas_tipo (
      mespestip_pessoa,
      mespestip_tipo
      ) VALUES (
      '$pessoa_ultimo',
      '6'
      )";
            if (!mysql_query($sql))
                die("Erro Pessoas Tipo 6: " . mysql_error());
            $sql = "
      INSERT INTO mestre_pessoas_tipo (
      mespestip_pessoa,
      mespestip_tipo
      ) VALUES (
      '$pessoa_ultimo',
      '3'
      )";
            if (!mysql_query($sql))
                die("Erro Pessoas Tipo 3: " . mysql_error());


            //Inserir a pessoa como supervisor do quiosque
            $sql = "
      INSERT INTO
      quiosques_supervisores (
      quisup_quiosque,
      quisup_supervisor,
      quisup_datafuncao
      )
      VALUES (
      '$quiosque_ultimo',
      '$pessoa_ultimo',
      '$dataatual'
      )";
            $query = mysql_query($sql);
            if (!$query)
                die("Erro Inserir Supervisor:" . mysql_error());

            $tpl = new Template("templates/notificacao.html");
            $tpl->ICONES = "../imagens/icones/geral/";

            //Cadastro realizado com sucesso
            $tpl->TITULO = "CADASTRADO!";
            $tpl->ICONE_ARQUIVO = "confirmar.png";
            $tpl->block("BLOCK_TITULO");
            $tpl->MOTIVO = "            
            <b><br>Bem Vindo ao SGAF (Sistema Gestor de Agricultura Familiar)</b><br>
            <br>Para utilizar o sistema basta acessar o endereço: <b>http://titotec.com.br/SGAF/</b> informar seu CPF e a senha recém cadastrada. <br><br>
        ";
            $tpl->MOTIVO_COMPLEMENTO = "";
            $tpl->block("BLOCK_MOTIVO");
            $tpl->BOTAOGERAL_DESTINO = "../index.html";
            $tpl->BOTAOGERAL_TIPO = "button";
            $tpl->BOTAOGERAL_NOME = "ENTRAR NO SISTEMA";
            //$tpl->block("BLOCK_BOTAOGERAL_NOVAJANELA");
            $tpl->block("BLOCK_BOTAOGERAL");

            $tpl->show();
        }
        ?>
    </div>        
</body>
</html>



