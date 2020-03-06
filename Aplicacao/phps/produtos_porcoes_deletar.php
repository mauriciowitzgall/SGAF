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
$tpl_titulo->SUBTITULO = "DELETAR PORÇÕES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos_porcoes.png";
$tpl_titulo->show();

$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$codigo = $_GET["codigo"];
$produto = $_GET["produto"];
//print_r($_REQUEST);

//Deleta
$sql3 = "DELETE FROM produtos_porcoes WHERE propor_codigo=$codigo";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL:" . mysql_error());
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "produtos_porcoes.php?produto=$produto";
$tpl6->block("BLOCK_BOTAO");



$tpl6->show();
include "rodape.php";
?>
