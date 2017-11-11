<?php



//Numero Inicial
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "200px";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->TITULO = "Numero Inicial";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "numini";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->SELECT_CLASSE = " campo_tamanho_6 ";
$tpl_rel->block("BLOCK_SELECT_PADRAO");

$sql2 = "
    SELECT sai_id,sai_codigo
    FROM saidas
    WHERE sai_quiosque=$usuario_quiosque
    ORDER BY sai_id 
";
$query2 = mysql_query($sql2);
if (!$query2) die("Erro2:" . mysql_error());
while ($dados2 = mysql_fetch_assoc($query2)) {
    $tpl_rel->OPTION_VALOR = $dados2["sai_id"];
    $tpl_rel->OPTION_TEXTO = $dados2["sai_id"];
    $tpl_rel->block("BLOCK_OPTION");
}
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");


//Numero Final
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "200px";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->TITULO = "Numero final";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "numfim";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->SELECT_CLASSE = " campo_tamanho_6 ";
$tpl_rel->block("BLOCK_SELECT_PADRAO");

$sql2 = "
    SELECT sai_id,sai_codigo
    FROM saidas
    WHERE sai_quiosque=$usuario_quiosque
    ORDER BY sai_id 
";
$query2 = mysql_query($sql2);
if (!$query2) die("Erro2:" . mysql_error());
while ($dados2 = mysql_fetch_assoc($query2)) {
    $tpl_rel->OPTION_VALOR = $dados2["sai_id"];
    $tpl_rel->OPTION_TEXTO = $dados2["sai_id"];
    $tpl_rel->block("BLOCK_OPTION");
}
$tpl_rel->block("BLOCK_SELECT");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->block("BLOCK_LINHA");


?>

