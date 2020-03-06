<?php

include "rel_topo.php";
include "cabecalho1.php";


////Pega os campo de filtro
$quiosque = $_REQUEST["quiosque"];
$valini = $_REQUEST["numini"];
$valfim = $_REQUEST["numfim"];

//print_r($_REQUEST);

//Campos de filtro
$tpl_campos = new Template("../templates/cadastro1.html");


//Numero Inicial
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Número Inicial";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "valini";
$tpl_campos->CAMPO_TAMANHO = "";
$tpl_campos->CAMPO_VALOR = "$valini";
$tpl_campos->CAMPO_QTDCARACTERES = "";
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");

//Numero Final
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Número Final";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "valfim";
$tpl_campos->CAMPO_TAMANHO = "";
$tpl_campos->CAMPO_VALOR = "$valfim";
$tpl_campos->CAMPO_QTDCARACTERES = "";
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");



$tpl_campos->show();

//Listagem
$tpl_lista = new Template("../templates/lista2.html");
$tpl_lista->block("BLOCK_TABELA_CHEIA");



//Cabeçalho
$tpl_lista->TEXTO = "COMANDAS NÃO LANÇADAS";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "10";
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
$contador = $valini;
while ($contador <= $valfim) {
    //echo "($contador)";
    $sql="  SELECT sai_id FROM saidas WHERE sai_id = $contador AND sai_quiosque=$usuario_quiosque";
    if (!$query=mysql_query($sql)) die("Erro 0:" . mysql_error());
    if ($linha = mysql_num_rows($query) > 0) echo ""; 
    else {
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = $contador;
        $tpl_lista->COLUNA_ALINHAMENTO = "center";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
        $cont++;
    }
    if ($cont%10 == 0) $tpl_lista->block("BLOCK_LINHA");
    $contador++;
    
}
$tpl_lista->block("BLOCK_LINHA");

if (mysql_num_rows($query) == 0) {
    $tpl_lista->LINHA_NADA_COLSPAN = "100";
    $tpl_lista->block("BLOCK_LINHA_NADA");
} 

$tpl_lista->block("BLOCK_CORPO");

$tpl_lista->block("BLOCK_LISTAGEM");
$tpl_lista->show();

include "rel_baixo.php";
?>