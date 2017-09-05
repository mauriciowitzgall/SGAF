<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixas.png";
$tpl_titulo->show();

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

//RESUMO
//Na exclusão de taxas deve-se verifica se teve algum acerto que utilizou a taxa em quest�o. Tamb�m n�o � permitido
//excluir taxas que estão vínculadas a algum quiosque


$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$codigo = $_GET["codigo"];

//Verifica se o caixa está aberto. Se sim impedir a exclusão
$sql = "SELECT cai_situacao FROM caixas WHERE cai_codigo=$codigo";
$query = mysql_query($sql);
if (!$query)  die("Erro SQL1" . mysql_error());
$dados=  mysql_fetch_assoc($query);
$situacao=$dados["cai_situacao"];
if ($situacao==1) {
    $tpl6->block("BLOCK_ERRO");
    $tpl6->block("BLOCK_NAOAPAGADO");
    //$tpl6->block("BLOCK_MOTIVO_EMUSO");
    $tpl6->MOTIVO = "";    
    $tpl6->MOTIVO_COMPLEMENTO = "O Caixa está aberto, feche-o primeiro antes de excluir o caixa";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

//Deleta o caixa. Na realidade apenas desativa
$sql3 = "UPDATE caixas SET cai_status=0 WHERE cai_codigo=$codigo";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL desativar caixa:" . mysql_error());
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "caixas.php";
$tpl6->block("BLOCK_BOTAO");



$tpl6->show();
include "rodape.php";
?>
