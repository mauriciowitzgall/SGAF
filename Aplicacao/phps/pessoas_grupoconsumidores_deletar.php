<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_pessoas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "pessoas";
include "includes.php";


$grupo = $_REQUEST['grupo']; 
$datahoraatual=date("Y-m-d H:i:s");
$pessoa = $_REQUEST['pessoa']; 

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PESSOAS  ";
$tpl_titulo->SUBTITULO = "VINCULAR GRUPOS DE CONSUMIDORES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "grupoconsumidores.png";
$tpl_titulo->show();


if ($usagrupoconsumidores!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR USO DE GRUPO DE CONSUMIDORES</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

//print_r($_REQUEST);

$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

//Deleta
$sql3 = "DELETE FROM pessoas_grupoconsumidores WHERE pesgrucon_pessoa=$pessoa and pesgrucon_grupo=$grupo";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL:" . mysql_error());
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "pessoas_grupoconsumidores.php?pessoa=$pessoa";
$tpl6->block("BLOCK_BOTAO");



$tpl6->show();
include "rodape.php";
?>
