<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_relatorios_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativas";
include "includes.php";

//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "RELATÓRIOS";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "relatorios.png";
$tpl_titulo->show();

$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$codigo = $_GET["codigo"];

$sql = "DELETE FROM relatorios_permissao WHERE relper_relatorio=$codigo";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL deletar relatorio permissao:" . mysql_error());
$sql3 = "DELETE FROM relatorios WHERE rel_codigo=$codigo";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL deletar relatorio:" . mysql_error());
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "relatorios.php";
$tpl6->block("BLOCK_BOTAO");



$tpl6->show();
include "rodape.php";
?>
