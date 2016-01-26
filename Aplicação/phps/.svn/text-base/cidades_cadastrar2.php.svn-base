<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_cidades_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "locais";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CIDADES";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "locais.png";
$tpl_titulo->show();

$codigo = $_POST["codigo"];
$nome = $_POST["nome"];
$nome2 = $_POST["nome2"];
$estado = $_POST['estado'];

 
//Conforme a operação deve-se inserir novo registro ou atualizar os existentes
if ($codigo == "") { //Caso a operação seja CADASTRO    
    //Verifica se já existe registros com o mesmo nome
    $sql = "SELECT * FROM cidades WHERE cid_nome='$nome'";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome da cidade";
        $tpl_notificacao->DESTINO = "cidades.php";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOCADASTRADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
    } else {
        $sql = "
        INSERT INTO
            cidades (cid_nome,cid_estado)
        VALUES 
            ('$nome','$estado');
        ";
        if (!mysql_query($sql))
            die("Erro: " . mysql_error());
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->DESTINO = "cidades.php";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_CADASTRADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
} else { //Caso a operação seja EDIÇÂO
    //Verifica se já existe registros com o mesmo nome    
    $sql = "SELECT * FROM cidades WHERE cid_nome='$nome'";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($nome == $nome2)
        $linhatot = 1;
    else
        $linhatot = 0;
    if ($linhas > $linhatot) {
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome";
        $tpl_notificacao->DESTINO = "cidades.php";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOEDITADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
    } else {
        $sql = "
    UPDATE
        cidades
    SET
        cid_nome='$nome',
	cid_estado='$estado'	
    WHERE
        cid_codigo = '$codigo'
    ";
        if (!mysql_query($sql))
            die("Erro: " . mysql_error());
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->DESTINO = "cidades.php";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_EDITADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
}

include "rodape.php";
?>
