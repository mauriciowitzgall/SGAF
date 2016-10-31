<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_caixas_operadores_gerir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "OPERADORES";
$tpl_titulo->SUBTITULO = "LISTA DE OPERADORES DO CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones2";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixa.png";
$tpl_titulo->show();


//Inicio da exclusão de entradas
$caixa = $_GET["caixa"];
$operador = $_GET["operador"];

//Limpa o grupo de permissões do usuário da pessoa
$sql = "
UPDATE
    pessoas
SET
    pes_grupopermissoes=0           
WHERE
    pes_codigo = '$operador'
";
if (!mysql_query($sql))
    die("Erro: " . mysql_error());

//Excluir a pessoa da função de supervisor
$sql2 = "DELETE FROM caixas_operadores WHERE caiope_operador='$operador' and caiope_caixa=$caixa";
$query2 = mysql_query($sql2);
if (!$query2) {
    die("Erro SQL: " . mysql_error());
}

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->MOTIVO_COMPLEMENTO = "";
$tpl_notificacao->DESTINO = "caixas_operadores.php?caixa=$caixa";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_APAGADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();

include "rodape.php";
?>
