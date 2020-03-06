<?php



//Tipo de relatório
$tpl_rel->TITULO = "Tipo de relatório";
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "tiporel";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$tpl_rel->OPTION_VALOR = 1;
$tpl_rel->OPTION_TEXTO = "Sintético";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->OPTION_VALOR = 2;
$tpl_rel->OPTION_TEXTO = "Analítico";
$tpl_rel->block("BLOCK_OPTION_SELECIONADO");
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");

//Classificação
$tpl_rel->TITULO = "Classificação";
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "classificacao";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$tpl_rel->OPTION_VALOR = 1;
$tpl_rel->OPTION_TEXTO = "Data de cadastro";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->OPTION_VALOR = 2;
$tpl_rel->OPTION_TEXTO = "Quantidade de Vendas";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->OPTION_VALOR = 3;
$tpl_rel->OPTION_TEXTO = "Data da última venda";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->OPTION_VALOR = 4;
$tpl_rel->OPTION_TEXTO = "Usuário que cadastrou";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->OPTION_VALOR = 5;
$tpl_rel->block("BLOCK_OPTION_SELECIONADO");
$tpl_rel->OPTION_TEXTO = "Nome do consumidor";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");




?>

