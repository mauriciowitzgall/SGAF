<?php


//Períodos
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "200px";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->TITULO = "Período";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->CAMPO_TIPO = "date";
$tpl_rel->CAMPO_NOME = "datade";
//$tpl_rel->CAMPO_ID = "data_1";
$tpl_rel->CAMPO_TAMANHO = "";
$tpl_rel->CAMPO_VALOR = "";
$tpl_rel->CAMPO_QTDCARACTERES = "8";
$tpl_rel->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl_rel->block("BLOCK_CAMPO_HISTORICODESATIVADO");
//$tpl_rel->block("BLOCK_CAMPO_DESABILITADO");
$tpl_rel->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl_rel->CAMPO_CLASSE = " campo_tamanho_5 ";
$tpl_rel->block("BLOCK_CAMPO_PADRAO");
$tpl_rel->block("BLOCK_CAMPO");
$tpl_rel->TEXTO_NOME = "";
$tpl_rel->TEXTO_ID = "";
$tpl_rel->TEXTO_CLASSE = "";
$tpl_rel->TEXTO_VALOR = " até ";
$dataatual = date("Y-m-d");
$tpl_rel->CAMPO_MAX="$dataatual";
$tpl_rel->block("BLOCK_CAMPO_MAX");
$tpl_rel->block("BLOCK_TEXTO");
$tpl_rel->block("BLOCK_CONTEUDO");

$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->CAMPO_TIPO = "date";
$tpl_rel->CAMPO_NOME = "dataate";
//$tpl_rel->CAMPO_ID = "data_2";
$tpl_rel->CAMPO_TAMANHO = "8";
$tpl_rel->CAMPO_VALOR = "$dataatual";
$tpl_rel->CAMPO_QTDCARACTERES = "8";
$tpl_rel->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl_rel->block("BLOCK_CAMPO_HISTORICODESATIVADO");
//$tpl_rel->block("BLOCK_CAMPO_DESABILITADO");
$tpl_rel->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl_rel->block("BLOCK_CAMPO_PADRAO");
$tpl_rel->block("BLOCK_CAMPO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");



//Desconto maior que
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->TITULO = "Desconto maior que";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->CAMPO_TIPO = "text";
//$tpl_rel->CAMPO_ESTILO="";
//$tpl_rel->block("BLOCK_CAMPO_ESTILO");
$tpl_rel->CAMPO_TAMANHO = "8";
$tpl_rel->CAMPO_DICA = "";
$tpl_rel->CAMPO_NOME = "descontomin";
$tpl_rel->CAMPO_ID = "descontomin";
$tpl_rel->CAMPO_AOCLICAR = "";
$tpl_rel->CAMPO_ONKEYUP = "";
$tpl_rel->CAMPO_ONBLUR = "";
$tpl_rel->CAMPO_VALOR = "0";
$tpl_rel->block("BLOCK_CAMPO_OBRIGATORIO");
//$tpl_rel->block("BLOCK_CAMPO_DESABILITADO");
$tpl_rel->block("BLOCK_CAMPO_PADRAO");
$tpl_rel->block("BLOCK_CAMPO");
$tpl_rel->TEXTO_VALOR = " % ";
$tpl_rel->block("BLOCK_TEXTO");

$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");




?>

