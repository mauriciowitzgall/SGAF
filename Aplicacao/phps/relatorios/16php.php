<?php

include "rel_topo.php";
include "cabecalho1.php";

//Listagem
$tpl_lista = new Template("../templates/lista2.html");
$tpl_lista->block("BLOCK_TABELA_CHEIA");


//Cabeçalho
$tpl_lista->TEXTO = "COMANDAS REPETIDAS";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "20%";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "NÚMERO DE REPETIÇÕES";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "20%";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->LINHA_CLASSE = "tab_cabecalho";
$tpl_lista->block("BLOCK_LINHA_DINAMICA");
$tpl_lista->block("BLOCK_LINHA");
$tpl_lista->block("BLOCK_CORPO");


//Linhas da listagem
$cont=0;
$sql="  SELECT sai_id, count(sai_id) as qtd_repeticoes FROM saidas WHERE sai_quiosque=$usuario_quiosque GROUP BY sai_id";
if (!$query=mysql_query($sql)) die("Erro 0:" . mysql_error());
while ($dados=mysql_fetch_assoc($query)) {
    $qtd_repeticoes=$dados["qtd_repeticoes"];
    $comanda=$dados["sai_id"];
    if ($qtd_repeticoes>1) {

        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = "$comanda";
        $tpl_lista->COLUNA_ALINHAMENTO = "center";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = "$qtd_repeticoes";
        $tpl_lista->COLUNA_ALINHAMENTO = "center";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        $tpl_lista->block("BLOCK_LINHA");
    }
}
    

if (mysql_num_rows($query) == 0) {
    $tpl_lista->LINHA_NADA_COLSPAN = "100";
    $tpl_lista->block("BLOCK_LINHA_NADA");
} 

$tpl_lista->block("BLOCK_CORPO");

$tpl_lista->block("BLOCK_LISTAGEM");
$tpl_lista->show();

include "rel_baixo.php";
?>