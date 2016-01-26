<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_taxas_aplicar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativas";
include "includes.php";

//TÃTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TAXAS";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "taxas.png";
$tpl_titulo->show();

//Inicio da exclusão de entradas
$quiosque = $_GET["quiosque"];
$taxa = $_GET["taxa"];


//Excluir a taxa do quiosque
$sql2 = "DELETE FROM quiosques_taxas WHERE quitax_taxa='$taxa' and quitax_quiosque=$quiosque";
$query2 = mysql_query($sql2);
if (!$query2) {
    die("Erro SQL: " . mysql_error());
}

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->MOTIVO_COMPLEMENTO = "";
$tpl_notificacao->DESTINO = "quiosque_taxas.php?quiosque=$quiosque";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_APAGADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();


?>
