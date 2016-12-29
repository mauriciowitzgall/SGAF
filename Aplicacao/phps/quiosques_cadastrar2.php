<?php
require "login_verifica.php";
$codigo = $_POST['codigo'];
if ($codigo == "") {
    if ($permissao_quiosque_cadastrar <> 1) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
} else {
    if ($permissao_quiosque_editar <> 1) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
}

if (($permissao_quiosque_cadastrar==1)&&($codigo!=$usuario_quiosque)) $tipopagina = "quiosques2";
else $tipopagina = "quiosques";

include "includes.php";
//print_r($_REQUEST);

$erro = 0;
$nome = $_POST['nome'];
$cidade = $_POST['cidade'];
$cep = $_POST['cep'];
$bairro = $_POST['bairro'];
$vila = $_POST['vila'];
$endereco = $_POST['endereco'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$referencia = $_POST['referencia'];
$fone1 = $_POST['fone1'];
$fone2 = $_POST['fone2'];
$email = $_POST['email'];
$obs = $_POST['obs'];
$datacadastro = date("Y-m-d");
$horacadastro = date("H:i:s");
$dataedicao = date("Y-m-d");
$horaedicao = date("H:i:s");
$paginadestino = "quiosques.php";
$tiponegociacao = $_POST["box"];
if ($permissao_quiosque_definircooperativa == 1) {
    $cooperativa = $_POST['cooperativa'];
} else {
    $cooperativa = $usuario_cooperativa;
}



//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "QUIOSQUES";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques.png";
$tpl_titulo->show();

//Verifica se foi selecionado pelo menos um tipo de negociacao
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



if ($codigo == "") {
    
    $idunico=  uniqid();
    $sql = " INSERT INTO quiosques (qui_nome,qui_cidade,qui_cep,qui_bairro,qui_vila,qui_endereco,qui_numero,qui_complemento,qui_referencia,qui_fone1,qui_fone2,qui_email,qui_obs,qui_datacadastro,qui_horacadastro,qui_cooperativa,qui_usuario,qui_idunico)
	VALUES ('$nome','$cidade','$cep','$bairro','$vila','$endereco','$numero','$complemento','$referencia','$fone1','$fone2','$email','$obs','$datacadastro','$horacadastro','$cooperativa','$usuario_codigo','$idunico');";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro SQL");    
    $ultimo = mysql_insert_id();
    $quiosque = $ultimo;
    foreach ($tiponegociacao as $tiponegociacao) {
        $sql2 = "
    INSERT INTO 
        quiosques_tiponegociacao (
            quitipneg_quiosque,
            quitipneg_tipo
        ) 
    VALUES (
        '$quiosque',
        '$tiponegociacao'
    )";
        if (!mysql_query($sql2))
            die("Erro7: " . mysql_error());
    }
    //Deve-se definir uma configuração padrão inicial para o quiosque cadastrado 
    $sql7 = " INSERT INTO quiosques_configuracoes (quicnf_quiosque,quicnf_usamodulofiscal)
	VALUES ($ultimo,0);";
    if (!$query7= mysql_query($sql7))  die("Erro SQL Configuração quiosque".mysql_error());    
    
    

    
    
} else {

    $sql2 = "SELECT qui_cooperativa FROM quiosques WHERE qui_codigo=$codigo";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro SQL2");
    $dados2 = mysql_fetch_array($query2);
    $cooperativa_banco = $dados2[0];
    if ($cooperativa != $cooperativa_banco) {
        $erro = 1;
    }

    $sql = "UPDATE quiosques SET 
    qui_nome='$nome',
    qui_cidade='$cidade',
    qui_cep='$cep',
    qui_bairro='$bairro',
    qui_vila='$vila',
    qui_endereco='$endereco',
    qui_numero='$numero',
    qui_complemento='$complemento',
    qui_referencia='$referencia',
    qui_fone1='$fone1',
    qui_fone2='$fone2',
    qui_email='$email',
    qui_cooperativa='$cooperativa',
    qui_dataedicao='$dataedicao',
    qui_horaedicao='$horaedicao',
    qui_usuario='$usuario_codigo',
    qui_obs='$obs'
    WHERE qui_codigo = '$codigo'
    ";
    if (!mysql_query($sql))
            die("Erro17: " . mysql_error());
    
    //Deleta os tipos de negociação para depois incluir de novo no novo formato
    $sqldel = " DELETE FROM quiosques_tiponegociacao WHERE quitipneg_quiosque='$codigo'";
    if (!mysql_query($sqldel))
        die("Erro9: " . mysql_error());
    foreach ($tiponegociacao as $tiponegociacao) {
        $sql2 = "
            INSERT INTO quiosques_tiponegociacao (
                quitipneg_quiosque,
                quitipneg_tipo
            ) VALUES (
                '$codigo',
                '$tiponegociacao'
            )";
        if (!mysql_query($sql2))
            die("Erro78: " . mysql_error());
    }
}
?>
<table summary="" border="1" class="tabela1" cellpadding="4" align="center">
    <tr valign="middle" align="center">
        <td valign="middle" align="right" class="celula1"><?php if ($erro == 0) { ?><img src="<?php echo $icones; ?>confirmar.png" ><?php } else { ?><img src="<?php echo $icones; ?>erro.png" ><?php } ?></td><td class="celula2">
            <?php
            if ($erro == 0) {
                echo "<b>Os dados foram salvos com sucesso!<b>";
            } else if ($erro == 1) {
                echo "Houve alteração de cooperativa, o sistema deve executar um script que faz a migração de um quiosque de uma cooperativa para outra. Esse processo é bem cauteloso, deve-se fazer uma analise aprofundada! para realizar esse proceso de forma automatizada!<b><br>A alteração dos dados do quiosque foi cancelada!</b> <br>";
            }
            ?>
        </td>
    <tr>
        <td colspan="2" align="center" ><a class="link" href="<?php echo "$paginadestino"; ?>"><input autofocus="" type="button" value="CONTINUAR" class="botao fonte3"></a></td>
    </tr>
</table>

<br /><br />
<?php include "rodape.php"; ?>

