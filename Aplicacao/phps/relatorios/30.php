<?php


//Categoria
$tpl_rel->TITULO = "Categoria";
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_VALIGN = "";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");

$sql_rel="SELECT * from produtos_categorias where cat_cooperativa=$usuario_cooperativa";
if (!$query_rel = mysql_query($sql_rel)) die("Erro filtro categorias:" . mysql_error());
while ($dados_rel = mysql_fetch_assoc($query_rel)) {
    $tpl_rel->COLUNA_ALINHAMENTO = "left";
    $tpl_rel->COLUNA_TAMANHO = "";
    $tpl_rel->COLUNA_ROWSPAN = "";
    $tpl_rel->CAMPO_TIPO = "checkbox";
    $categoria_codigo=$dados_rel["cat_codigo"];
    $categoria_nome=$dados_rel["cat_nome"];
    $tpl_rel->CAMPO_NOME = "categoria[]";
    $tpl_rel->CAMPO_TAMANHO = "";
    $tpl_rel->CAMPO_VALOR = "$categoria_codigo";
    $tpl_rel->CAMPO_CLASSE = "";
    $tpl_rel->block("BLOCK_CAMPO_PADRAO");
    $tpl_rel->block("BLOCK_CAMPO");
    $tpl_rel->TEXTO_NOME = "";
    $tpl_rel->TEXTO_VALOR = "$categoria_nome";
    $tpl_rel->block("BLOCK_TEXTO");
    $tpl_rel->block("BLOCK_BR");
    $tpl_rel->block("BLOCK_CONTEUDO");
}
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");


//Contagem as cegas
$tpl_rel->TITULO = "Contagem às cegas";
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "contagemascegas";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$tpl_rel->OPTION_VALOR = 1;
$tpl_rel->OPTION_TEXTO = "Sim";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_OPTION_SELECIONADO");
$tpl_rel->OPTION_VALOR = 0;
$tpl_rel->OPTION_TEXTO = "Não";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");


?>

