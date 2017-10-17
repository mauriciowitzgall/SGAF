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
$tpl_titulo->TITULO = "RECIPIENTES";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "recipientes.png";
$tpl_titulo->show();

//só pode excluir recipientes nao globais
if ($operação=='editar') {
    $sql="SELECT * FROM produtos_recipientes WHERE prorec_codigo=$codigo";
    if (!$query= mysql_query($sql)) die("ERRO SQL: ".mysql_error ());
    while($dados=  mysql_fetch_assoc($query)) {
        $recipienteglobal=$dados["prorec_global"];
    }
    if ($recipienteglobal==1) {
        $tpl6 = new Template("templates/notificacao.html");
        $tpl6->block("BLOCK_ERRO");
        $tpl6->ICONES = $icones;
        //$tpl6->block("BLOCK_NAOAPAGADO");
        $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Você só pode excluir recipientes não globais, ou seja, os cadastrados por vocês mesmo!";
        $tpl6->block("BLOCK_MOTIVO");
        $tpl6->block("BLOCK_BOTAO_VOLTAR");
        $tpl6->show();
        exit;
    }
}

//RESUMO
//Na exclusão de taxas deve-se verifica se teve algum acerto que utilizou a taxa em quest�o. Tamb�m n�o � permitido
//excluir taxas que estão vínculadas a algum quiosque


$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$codigo = $_GET["codigo"];


//Deleta 
$sql3 = "DELETE FROM produtos_recipientes WHERE prorec_codigo=$codigo";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL:" . mysql_error());
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "produtos_recipientes.php";
$tpl6->block("BLOCK_BOTAO");



$tpl6->show();
include "rodape.php";
?>
