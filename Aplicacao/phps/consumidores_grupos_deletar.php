<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_consumidores_grupos_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "pessoas";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "GRUPOS DE CONSUMIDORES";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones2";
$tpl_titulo->NOME_ARQUIVO_ICONE = "consumidor.png";
$tpl_titulo->show();



$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$codigo = $_GET["codigo"];


//Deleta 
$sql3 = "DELETE FROM consumidores_grupos WHERE congru_codigo=$codigo";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL:" . mysql_error());
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "consumidores_grupos.php";
$tpl6->block("BLOCK_BOTAO");



$tpl6->show();
include "rodape.php";
?>
