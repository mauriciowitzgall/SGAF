<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_taxas_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativas";
include "includes.php";

//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TAXAS";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "taxas.png";
$tpl_titulo->show();

//RESUMO
//Na exclusão de taxas deve-se verifica se teve algum acerto que utilizou a taxa em quest�o. Tamb�m n�o � permitido
//excluir taxas que estão vínculadas a algum quiosque


$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$codigo = $_GET["codigo"];

//Verifica se houve acertos com a taxa em questo
$sql = "SELECT acetax_taxa FROM acertos_taxas WHERE acetax_taxa=$codigo";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL taxa acertos:" . mysql_error());
if (mysql_num_rows($query) > 0) {
    $tpl6->block("BLOCK_ERRO");
    $tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->block("BLOCK_MOTIVO_EMUSO");
    $tpl6->MOTIVO = "acertos";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

//Verifica se algum quiosque tem setado essa taxa
$sql = "SELECT quitax_taxa FROM quiosques_taxas WHERE quitax_taxa=$codigo";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL taxa quiosque:" . mysql_error());
if (mysql_num_rows($query) > 0) {
    $tpl6->block("BLOCK_ERRO");
    $tpl6->block("BLOCK_NAOAPAGADO");
    //$tpl6->block("BLOCK_MOTIVO_EMUSO");
    $tpl6->MOTIVO = "Não é possível excluir esta taxa por que ela está vinculada a um ou mais quiosques!";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}


//Deleta a taxa
$sql3 = "DELETE FROM taxas WHERE tax_codigo=$codigo";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL deletar taxa:" . mysql_error());
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "taxas.php";
$tpl6->block("BLOCK_BOTAO");



$tpl6->show();
include "rodape.php";
?>
