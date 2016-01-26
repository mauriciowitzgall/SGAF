<?php

//Fornecedor
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "200px";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->TITULO = "Fornecedor";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");

$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->COLUNA_TAMANHO = "";
$tpl_rel->COLUNA_ROWSPAN = "";
$tpl_rel->SELECT_NOME = "fornecedor";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->block("BLOCK_SELECT_PADRAO");

$sql2 = "
    SELECT DISTINCT pes_codigo,pes_nome
    FROM pessoas        
    join mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)     
    join entradas on (ent_fornecedor=pes_codigo)
    join saidas_produtos on (saipro_lote=ent_codigo)
    WHERE mespestip_tipo=5    
    and ent_quiosque=$usuario_quiosque   
    ORDER BY pes_nome
";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro2:" . mysql_error());

if ($usuario_grupo == 5) {
    $tpl_rel->OPTION_VALOR = $usuario_codigo;
    $tpl_rel->OPTION_TEXTO = $usuario_nome;
    $tpl_rel->block("BLOCK_OPTION");
} else {
    $tpl_rel->block("BLOCK_OPTION_TODOS");
    while ($dados2 = mysql_fetch_assoc($query2)) {
        $tpl_rel->OPTION_VALOR = $dados2["pes_codigo"];
        $tpl_rel->OPTION_TEXTO = $dados2["pes_nome"];
        if ($fornecedor == $dados2["pes_codigo"])
            $tpl_rel->block("BLOCK_OPTION_SELECIONADO");
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
$tpl_rel->CAMPO_TIPO = "text";
$tpl_rel->CAMPO_NOME = "datade";
$tpl_rel->CAMPO_ID = "data_1";
$tpl_rel->CAMPO_TAMANHO = "8";
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
$tpl_rel->CAMPO_TIPO = "text";
$tpl_rel->CAMPO_NOME = "dataate";
$tpl_rel->CAMPO_ID = "data_2";
$tpl_rel->CAMPO_TAMANHO = "8";
$dataatual = date("d/m/Y");
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


//Somente Acertados
$tpl_rel->COLUNA_ALINHAMENTO = "right";
$tpl_rel->COLUNA_TAMANHO = "200px";
$tpl_rel->TITULO = "Quais vendas";
$tpl_rel->block("BLOCK_TITULO");
$tpl_rel->block("BLOCK_CONTEUDO");
$tpl_rel->block("BLOCK_COLUNA");
$tpl_rel->COLUNA_ALINHAMENTO = "left";
$tpl_rel->block("BLOCK_SELECT_PADRAO");
$tpl_rel->SELECT_NOME = "acertados";
$tpl_rel->SELECT_TAMANHO = "";
$tpl_rel->OPTION_VALOR = "0";
$tpl_rel->OPTION_TEXTO = "Todas";
$tpl_rel->block("BLOCK_OPTION_SELECIONADO");
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->OPTION_VALOR = "1";
$tpl_rel->OPTION_TEXTO = "Vendas Acertadas";
$tpl_rel->block("BLOCK_OPTION");
$tpl_rel->block("BLOCK_SELECT");
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
    $tpl_rel->OPTION_VALOR = "Fornecedor";
    $tpl_rel->OPTION_TEXTO = "Fornecedor";
    $tpl_rel->block("BLOCK_OPTION_SELECIONADO");
    $tpl_rel->block("BLOCK_OPTION");
    $tpl_rel->OPTION_VALOR = "Valor Total";
    $tpl_rel->OPTION_TEXTO = "Valor Total";
    $tpl_rel->block("BLOCK_OPTION");
    $tpl_rel->block("BLOCK_SELECT");
    $tpl_rel->block("BLOCK_CONTEUDO");
    $tpl_rel->block("BLOCK_COLUNA");
    $tpl_rel->block("BLOCK_LINHA");
}
?>

