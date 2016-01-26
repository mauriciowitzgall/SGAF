<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_quiosque_definircaixas <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativas";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "../pessoas2/caixa.png";
$tpl_titulo->show();

//Inicio da exclus�o de entradas
$quiosque = $_GET["quiosque"];
$caixa = $_GET["caixa"];

//Limpa o grupo de permissões do usu�rio da pessoa
$sql = "
UPDATE
    pessoas
SET
    pes_grupopermissoes=''           
WHERE
    pes_codigo = '$caixa'
";
if (!mysql_query($sql))
    die("Erro: " . mysql_error());

//Excluir a pessoa da fun��o de caixa
$sql2 = "DELETE FROM quiosques_caixas WHERE quicai_caixa='$caixa' and quicai_quiosque=$quiosque";
$query2 = mysql_query($sql2);
if (!$query2) {
    die("Erro SQL: " . mysql_error());
}

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->MOTIVO_COMPLEMENTO = "";
$tpl_notificacao->DESTINO = "caixas.php?quiosque=$quiosque";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_APAGADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();

include "rodape.php";
?>
