<?php


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



//Consumidor
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "200px";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->TITULO = "Consumidor";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "consumidor";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->SELECT_CLASSE = " campo_tamanho_6 ";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$sql_filtro = "";
if (($usuario_grupo == 1) || ($usuario_grupo == 2)) {
    
} else {
    $sql_filtro.=" and sai_quiosque=$usuario_quiosque ";
}
$sql2 = "
    SELECT DISTINCT pes_codigo,pes_nome
    FROM pessoas
    JOIN saidas on (pes_codigo=sai_consumidor)
    WHERE pes_cooperativa=$usuario_cooperativa
    $sql_filtro
    ORDER BY pes_nome
";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro4:" . mysql_error());
$tpl_rel->block("BLOCK_OPTION_TODOS");
while ($dados2 = mysql_fetch_assoc($query2)) {
    $tpl_rel->OPTION_VALOR = $dados2["pes_codigo"];
    $tpl_rel->OPTION_TEXTO = $dados2["pes_nome"];
    $tpl_rel->block("BLOCK_OPTION");
}
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
$tpl_rel->OPTION_TEXTO = "Data";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_OPTION_SELECIONADO");
$tpl_rel->OPTION_VALOR = 2;
$tpl_rel->OPTION_TEXTO = "Consumidor";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");


//Ocultar quitados
$tpl_rel->TITULO = "Ocultar quitados";
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "ocultarquitados";
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

