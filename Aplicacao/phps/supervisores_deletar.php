<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_quiosque_definirsupervisores <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$quiosque = $_GET["quiosque"];

if (($permissao_quiosque_cadastrar==1)&&($quiosque!=$usuario_quiosque)) $tipopagina = "quiosques2";
else $tipopagina = "quiosques";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "SUPERVISORES";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosque_supervisores.png";
$tpl_titulo->show();

//Inicio da exclus�o de entradas
$supervisor = $_GET["supervisor"];

//Limpa o grupo de permissões do usu�rio da pessoa
$sql = "
UPDATE
    pessoas
SET
    pes_grupopermissoes=''           
WHERE
    pes_codigo = '$supervisor'
";
if (!mysql_query($sql))
    die("Erro: " . mysql_error());

//Excluir a pessoa da fun��o de supervisor
$sql2 = "DELETE FROM quiosques_supervisores WHERE quisup_supervisor='$supervisor' and quisup_quiosque=$quiosque";
$query2 = mysql_query($sql2);
if (!$query2) {
    die("Erro SQL: " . mysql_error());
}

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->MOTIVO_COMPLEMENTO = "";
$tpl_notificacao->DESTINO = "supervisores.php?quiosque=$quiosque";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_APAGADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();

include "rodape.php";
?>
