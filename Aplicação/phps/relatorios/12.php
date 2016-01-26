<?php

//Cooperativa
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "200px";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->TITULO = "Cooperativa";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "cooperativa";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
if (($usuario_grupo == 2) || ($usuario_grupo == 3)) {
    $sql_filtro = " and coo_codigo=$usuario_cooperativa";
}
$sql2 = "
    SELECT *
    FROM cooperativas
    WHERE 1 $sql_filtro
    ORDER BY coo_abreviacao
";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro2:" . mysql_error());
if ($usuario_grupo == 1)
    $tpl_rel->block("BLOCK_OPTION_TODOS");
while ($dados2 = mysql_fetch_assoc($query2)) {
    $tpl_rel->OPTION_VALOR = $dados2["coo_codigo"];
    $tpl_rel->OPTION_TEXTO = $dados2["coo_abreviacao"];
    $tpl_rel->block("BLOCK_OPTION");
}
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");

//Quiosque
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "200px";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->TITULO = "Quiosque";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "quiosque";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
if (($usuario_grupo == 1) || ($usuario_grupo == 2))
    $tpl_rel->block("BLOCK_OPTION_TODOS");
if ($usuario_grupo == 2) {
    $sql_filtro = " and qui_cooperativa=$usuario_cooperativa";
} else if ($usuario_grupo == 3) {
    $sql_filtro = " and qui_codigo=$usuario_quiosque";
}
if ($usuario_grupo != 1) {

    $sql2 = "
    SELECT *
    FROM quiosques
    WHERE 1 $sql_filtro
    ORDER BY qui_nome
";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro2:" . mysql_error());

    while ($dados2 = mysql_fetch_assoc($query2)) {
        $tpl_rel->OPTION_VALOR = $dados2["qui_codigo"];
        $tpl_rel->OPTION_TEXTO = $dados2["qui_nome"];
        $tpl_rel->block("BLOCK_OPTION");
    }
}
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");


//Produto
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "200px";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->TITULO = "Produto";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "produto";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$tpl_rel->block("BLOCK_OPTION_TODOS");
if ($usuario_grupo == 2)
    $sql_filtro = " and coo_codigo=$usuario_cooperativa ";
else if ($usuario_grupo == 3)
    $sql_filtro = " and qui_codigo=$usuario_quiosque ";
if (($usuario_grupo != 1) && ($usuario_grupo != 2)) {
    $sql2 = "
    SELECT DISTINCT pro_codigo,pro_nome
    FROM produtos
    join saidas_produtos on (saipro_produto=pro_codigo)
    join saidas on (saipro_saida=sai_codigo)
    join quiosques on (sai_quiosque=qui_codigo)
    join cooperativas on (qui_cooperativa=coo_codigo)
    WHERE sai_tipo=1 $sql_filtro
    ORDER BY pro_nome
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro2:" . mysql_error());
    while ($dados2 = mysql_fetch_assoc($query2)) {
        $tpl_rel->OPTION_VALOR = $dados2["pro_codigo"];
        $tpl_rel->OPTION_TEXTO = $dados2["pro_nome"];
        $tpl_rel->block("BLOCK_OPTION");
    }
}

$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");

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
$tpl_rel->CAMPO_ID = "data_1";
$tpl_rel->CAMPO_TAMANHO = "11";
$tpl_rel->CAMPO_VALOR = "";
$tpl_rel->CAMPO_QTDCARACTERES = "8";
$tpl_rel->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl_rel->block("BLOCK_CAMPO_HISTORICODESATIVADO");
//$tpl_rel->block("BLOCK_CAMPO_DESABILITADO");
$tpl_rel->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl_rel->block("BLOCK_CAMPO_PADRAO");
$tpl_rel->block("BLOCK_CAMPO");

$tpl_rel->TEXTO_NOME = "";
$tpl_rel->TEXTO_ID = "";
$tpl_rel->TEXTO_CLASSE = "";
$tpl_rel->TEXTO_VALOR = " até ";
$tpl_rel->block("BLOCK_TEXTO");
$tpl_rel->block("BLOCK_CONTEUDO");

$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->CAMPO_TIPO = "date";
$tpl_rel->CAMPO_NOME = "dataate";
$tpl_rel->CAMPO_ID = "data_2";
$tpl_rel->CAMPO_TAMANHO = "11";
$dataatual = date("Y-m-d");
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


//Ordenado por
if ($usuario_grupo != 5) {

    $tpl_rel->COLUNA_ALINHAMENTO = "right";
    $tpl_rel->COLUNA_TAMANHO = "200px";
    $tpl_rel->TITULO = "Ordenado por";
    $tpl_rel->block("BLOCK_TITULO");
    $tpl_rel->block("BLOCK_CONTEUDO");
    $tpl_rel->block("BLOCK_COLUNA");
    $tpl_rel->COLUNA_ALINHAMENTO = "left";
    $tpl_rel->block("BLOCK_SELECT_PADRAO");
    $tpl_rel->SELECT_NOME = "ordenacao";
    $tpl_rel->OPTION_VALOR = "Nome de produto";
    $tpl_rel->OPTION_TEXTO = "Nome de produto";
    $tpl_rel->block("BLOCK_OPTION_SELECIONADO");
    $tpl_rel->block("BLOCK_OPTION");
    $tpl_rel->OPTION_VALOR = "Mais vendidos";
    $tpl_rel->OPTION_TEXTO = "Mais vendidos";
    $tpl_rel->block("BLOCK_OPTION");
    $tpl_rel->block("BLOCK_SELECT");
    $tpl_rel->block("BLOCK_CONTEUDO");
    $tpl_rel->block("BLOCK_COLUNA");
    $tpl_rel->block("BLOCK_LINHA");
}
?>

