<?php

require "login_verifica.php";
if ($permissao_entradas_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "entradas";
include "includes.php";

$entrada = $_GET["codigo"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ENTRADAS";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "entradas.png";
$tpl_titulo->show();

//Inicio da exclusão de entradas
$tpl = new Template("templates/notificacao.html");
$tpl->ICONES = $icones;
$tpl->DESTINO = "entradas.php"; 

//Verifica se ja foi efetuado saídas quaisquer para o lote/entrada em questão
$sql3 = "SELECT * FROM saidas_produtos WHERE saipro_lote=$entrada";
$query3 = mysql_query($sql3);
if (!$query3) {
    die("Erro SQL: " . mysql_error());
}
$linhas = mysql_num_rows($query3);

//Se já houve saídas referentes a esta entrada então não pode-se exclui-la
if ($linhas > 0) {        
    $tpl->block("BLOCK_ERRO");
    $tpl->block("BLOCK_NAOAPAGADO");
    $tpl->MOTIVO_COMPLEMENTO = "Já aconteceram vendas de produtos pertencentes a esta entrada/lote";    
    $tpl->block("BLOCK_BOTAO_VOLTAR");  
} else {
    
    //Excluir do estoque todos os produtos da entrada
    $sql = "DELETE FROM estoque WHERE etq_lote=$entrada";
    $query = mysql_query($sql);
    if (!$query) {
        die("Erro SQL: " . mysql_error());
    }
    //Excluir intens da entrada primeiro
    $sql1 = "DELETE FROM entradas_produtos WHERE entpro_entrada='$entrada'";
    $query1 = mysql_query($sql1);
    $query1 = mysql_query($sql1);
    if (!$query1) {
        die("Erro SQL: " . mysql_error());
    }
    //Excluir entrada
    $sql2 = "DELETE FROM entradas WHERE ent_codigo='$entrada'";
    $query2 = mysql_query($sql2);
    $query2 = mysql_query($sql2);
    if (!$query2) {
        die("Erro SQL: " . mysql_error());
    }
    $tpl->block("BLOCK_CONFIRMAR");
    $tpl->block("BLOCK_APAGADO");  
    $tpl->block("BLOCK_BOTAO");  
}

$tpl->show();

include "rodape.php";
?>
