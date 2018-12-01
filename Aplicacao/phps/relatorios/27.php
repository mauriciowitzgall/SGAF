<?php

//

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




//Motivos
$tpl_rel->TITULO = "Motivos";
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "motivo";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$tpl_rel->OPTION_VALOR = 0;
$tpl_rel->OPTION_TEXTO = "Todos";
$tpl_rel->block("BLOCK_OPTION_SELECIONADO");
$tpl_rel->block("BLOCK_OPTION");

$sql1="SELECT distinct saimot_codigo, saimot_nome FROM saidas_motivo join saidas on saimot_codigo=sai_saidajustificada order by saimot_nome";
if (!$query1=mysql_query($sql1)) die("Erro 1:" . mysql_error());
while ($dados1=mysql_fetch_assoc($query1)) {
	$tpl_rel->OPTION_VALOR = $dados1["saimot_codigo"];
	$tpl_rel->OPTION_TEXTO =  $dados1["saimot_nome"];
	$tpl_rel->block("BLOCK_OPTION");
}

$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");


//Mostrar Ano
$tpl_rel->TITULO = "Mostrar ano";
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "mostraano";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$tpl_rel->OPTION_VALOR = 1;
$tpl_rel->OPTION_TEXTO = "Sim";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->OPTION_VALOR = 0;
$tpl_rel->OPTION_TEXTO = "Não";
$tpl_rel->block("BLOCK_OPTION_SELECIONADO");
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");


//Mostrar Mês
$tpl_rel->TITULO = "Mostrar mês";
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "mostrames";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$tpl_rel->OPTION_VALOR = 1;
$tpl_rel->OPTION_TEXTO = "Sim";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->OPTION_VALOR = 0;
$tpl_rel->OPTION_TEXTO = "Não";
$tpl_rel->block("BLOCK_OPTION_SELECIONADO");
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");


//Mostrar Dia
$tpl_rel->TITULO = "Mostrar dia";
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "mostradia";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$tpl_rel->OPTION_VALOR = 1;
$tpl_rel->OPTION_TEXTO = "Sim";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->OPTION_VALOR = 0;
$tpl_rel->OPTION_TEXTO = "Não";
$tpl_rel->block("BLOCK_OPTION_SELECIONADO");
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");

?>

