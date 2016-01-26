<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_quiosque_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
}

$tipopagina = "quiosques";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "QUIOSQUES";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques.png";
$tpl_titulo->show();

//Inicio da exclus�o
$codigo = $_GET["codigo"];
$operacao = $_GET["operacao"];

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->DESTINO = "quiosques.php";
$tpl_notificacao->ICONES = $icones;


//Verifica se h� caixas
$sql = "SELECT * FROM quiosques_caixas WHERE quicai_quiosque=$codigo";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL1: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "Caixas";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}       

//Verifica se h� supervisores
$sql = "SELECT * FROM quiosques_supervisores WHERE quisup_quiosque=$codigo";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL2: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "supervisores";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}       
            
//Verifica se h� entradas
$sql = "SELECT * FROM entradas WHERE ent_quiosque=$codigo";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "entradas";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}
 
//Verifica se h� saidas
$sql = "SELECT * FROM saidas WHERE sai_quiosque=$codigo";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "saidas";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}
 
//Verifica se h� usu�rios
$sql = "SELECT * FROM pessoas WHERE pes_quiosqueusuario=$codigo";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "usuarios";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}
 
//Verifica se h� taxas
$sql = "SELECT * FROM quiosques_taxas WHERE quitax_quiosque=$codigo";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "taxas";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}

//Pode deletar o quiosque
$sql3 = "DELETE FROM quiosques WHERE qui_codigo='$codigo'";
$query3 = mysql_query($sql3);
if (!$query3) {
    die("Erro SQL: " . mysql_error());
}
$sql3 = "DELETE FROM quiosques_tiponegociacao WHERE quitipneg_quiosque='$codigo'";
$query3 = mysql_query($sql3);
if (!$query3) {
    die("Erro SQL: " . mysql_error());
}
$tpl_notificacao->MOTIVO_COMPLEMENTO = "";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_APAGADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();
                    

include "rodape.php";
?>
