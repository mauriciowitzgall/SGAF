<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operacoes_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "ENTRADAS E SAÍDAS DE CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixa_entradasaida.png";
$tpl_titulo->show();


$usacaixa=usamodulocaixa($usuario_quiosque);
if ($usacaixa!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$id = $_GET["codigo"];
$caixa = $_GET["caixa"];
$numero = $_GET["numero"];
//Verifica se o caixa está aberto. Se sim impedir a exclusão
$sql = "SELECT cai_situacao FROM caixas WHERE cai_codigo=$caixa";
$query = mysql_query($sql);
if (!$query)  die("Erro SQL1" . mysql_error());
$dados=  mysql_fetch_assoc($query);
$situacao=$dados["cai_situacao"];

if ($situacao==2) {
    $tpl6->block("BLOCK_ERRO");
    $tpl6->block("BLOCK_NAOAPAGADO");
    //$tpl6->block("BLOCK_MOTIVO_EMUSO");
    $tpl6->MOTIVO = "";    
    $tpl6->MOTIVO_COMPLEMENTO = "O Caixa está fechado, para gerar uma entrada/saida de caixa é necessário que o caixa esteja aberto!";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

//Deleta
$sql3 = "DELETE FROM caixas_entradassaidas WHERE caientsai_id=$id";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL desativar caixa:" . mysql_error());
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "caixas_entradassaidas.php?caixaoperacao=$numero";
$tpl6->block("BLOCK_BOTAO");



$tpl6->show();
include "rodape.php";
?>
