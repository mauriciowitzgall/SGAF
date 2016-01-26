<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "produtos";
include "includes.php";

//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PRODUTOS";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos.png";
$tpl_titulo->show();

$produtocodigo = $_GET['codigo'];

//Vericica se o produto ja foi usando em entradas
$sql = "
    SELECT pro_codigo
    FROM produtos
    join entradas_produtos on (entpro_produto=pro_codigo)    
    WHERE pro_codigo=$produtocodigo
";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao = new Template("templates/notificacao.html");
    $tpl_notificacao->ICONES = $icones;
    $tpl_notificacao->DESTINO = "produtos.php";
    $tpl_notificacao->FALTADADOS_MOTIVO = "entradas";
    $tpl_notificacao->block("BLOCK_MOTIVO_FALTADADOS");
    $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
    exit;
}

//Vericica se o produto ja foi usando em saidas
$sql = "
    SELECT pro_codigo
    FROM produtos
    join saidas_produtos on (saipro_produto=pro_codigo)    
    WHERE pro_codigo=$produtocodigo
";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao = new Template("templates/notificacao.html");
    $tpl_notificacao->ICONES = $icones;
    $tpl_notificacao->DESTINO = "produtos.php";
    $tpl_notificacao->FALTADADOS_MOTIVO = "saidas";
    $tpl_notificacao->block("BLOCK_MOTIVO_FALTADADOS");
    $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
    exit;
}


//Excluir a taxa do quiosque
$sql = "DELETE FROM produtos WHERE pro_codigo=$produtocodigo";
//$query = mysql_query($sql);
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: " . mysql_error());
}
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "produtos.php";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_APAGADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();
?>
