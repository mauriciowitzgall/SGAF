<?php

require "login_verifica.php";
if ($permissao_entradas_cancelar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "entradas";
include "includes.php";

$entrada = $_GET["codigo"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ENTRADAS";
$tpl_titulo->SUBTITULO = "CANCELAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "entradas.png";
$tpl_titulo->show();


$tpl = new Template("templates/notificacao.html");
$tpl->ICONES = $icones;
$tpl->DESTINO = "entradas.php";

//Deleta os itens do estoque
$sql2 = "DELETE FROM estoque WHERE etq_lote=$entrada";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro SQL3: " . mysql_error());

//Deleta os itens da entrada
$sql3 = "DELETE FROM entradas_produtos WHERE entpro_entrada=$entrada";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL4: " . mysql_error());

//Deleta a entrada
$sql = "DELETE FROM entradas WHERE ent_codigo=$entrada";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL2: " . mysql_error());
$tpl->block("BLOCK_CONFIRMAR");
$tpl->LINK = "entradas.php";
$tpl->block("BLOCK_APAGADO");
$tpl->block("BLOCK_BOTAO");


$tpl->show();

include "rodape.php";
?>
