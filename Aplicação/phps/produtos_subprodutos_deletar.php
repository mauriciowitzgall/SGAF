<?php
$tipopagina = "produtos";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PRODUTOS";
$tpl_titulo->SUBTITULO = "DELETAR SUB-PRODUTO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "subproduto.png";
$tpl_titulo->show();

$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$codigo = $_GET["codigo"];
$produto = $_GET["produto"];
$subproduto = $_GET["subproduto"];
//print_r($_REQUEST);

//Verifica se veio corretamente o produto e o subproduto
if (($produto=="")||($produto=="0")||($subproduto=="")||($subproduto=="0")) {
    $tpl_notificacao = new Template("templates/notificacao.html");
    $tpl_notificacao->ICONES = $icones;
    $tpl_notificacao->DESTINO = "#";
    $tpl_notificacao->block("BLOCK_ERRO");
    //$tpl_notificacao->block("BLOCK_NAOCADASTRADO");
    $tpl_notificacao->MOTIVO_COMPLEMENTO="Problema interno! Contato o Suporte!";
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;   
}


//Deleta
$sql3 = "DELETE FROM produtos_subproduto WHERE prosub_produto=$produto and prosub_subproduto=$subproduto";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL:" . mysql_error());
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "produtos_subprodutos.php?produto=$produto";
$tpl6->block("BLOCK_BOTAO");



$tpl6->show();
include "rodape.php";
?>
