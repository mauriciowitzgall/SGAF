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

//Verifica quantos itens tem dentro da entrada
$sql = "SELECT entpro_entrada FROM entradas_produtos WHERE entpro_entrada=$entrada";
$query = mysql_query($sql);
$linhas = mysql_num_rows($query);
if (!$query)
    die("Erro SQL1: " . mysql_error());

//Se não há nenhum produto na entrada então excluir direto
if ($linhas == 0) {
    $sql = "DELETE FROM entradas WHERE ent_codigo=$entrada";
    $query = mysql_query($sql);
    if (!$query) 
        die("Erro SQL2: " . mysql_error());    
    
    $tpl->block("BLOCK_CONFIRMAR");
    $tpl->LINK="entradas.php";    
    $tpl->block("BLOCK_APAGADO");    
    $tpl->block("BLOCK_BOTAO");      
} else { //Se há produto pedir confirmação
    $tpl->block("BLOCK_ATENCAO");
    $tpl->LINK="entradas_cancelar2.php?codigo=$entrada";    
    $tpl->MOTIVO="Se você cancelar esta entrada todos os produtos adicionados nela serão removidos também!";
    $tpl->block("BLOCK_MOTIVO");    
    $tpl->PERGUNTA="Tem certeza que deseja cancelar esta entrada?";
    $tpl->block("BLOCK_PERGUNTA");
    $tpl->block("BLOCK_BOTAO_NAO_VOLTAR");
    $tpl->block("BLOCK_BOTAO_SIMNAO");
}

$tpl->show();

include "rodape.php";
?>
