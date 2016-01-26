<?php

include "rel_topo.php";
include "cabecalho1.php";


//Pega os campo de filtro
$fornecedor = $_POST["fornecedor"];
$datade = $_POST["datade"];
$datade_sembarras = desconverte_data($_POST["datade"]);
$dataate = $_POST["dataate"];
$dataate_sembarras = desconverte_data($_POST["dataate"]);
$acertados = $_POST["acertados"];
$ordenacao = $_POST["ordenacao"];
if ($ordenacao == "Fornecedor")
    $ordenacao = " pes_nome ";
else
    $ordenacao = "totalbruto desc";
//Campos de filtro
$tpl_campos = new Template("../templates/cadastro1.html");


//Campo Fornecedor
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Fornecedor";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "fornecedor";
$tpl_campos->CAMPO_TAMANHO = "";
if ($fornecedor != "") {
    $sql = "
        SELECT pes_nome 
        FROM pessoas
        WHERE pes_codigo=$fornecedor
    ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro 1:" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $fornecedor_nome = $dados["pes_nome"];
} else {
    $fornecedor_nome = "Todos";
}
$tpl_campos->CAMPO_VALOR = "$fornecedor_nome";
$tpl_campos->CAMPO_QTDCARACTERES = "";
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");

//Periodos
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "PerÃ­odo";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "datade";
$tpl_campos->CAMPO_VALOR = "$datade";
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->TEXTO_NOME = "";
$tpl_campos->TEXTO_ID = "";
$tpl_campos->TEXTO_CLASSE = "";
$tpl_campos->TEXTO_VALOR = " até ";
$tpl_campos->block("BLOCK_TEXTO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "dataate";
$tpl_campos->CAMPO_VALOR = "$dataate";
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");

//Somente Acertados
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Quais Vendas";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "quaisvendas";
$tpl_campos->CAMPO_TAMANHO = "";
if ($acertados == 0)
    $tpl_campos->CAMPO_VALOR = "Todas";
else
    $tpl_campos->CAMPO_VALOR = "Vendas Acertadas";
$tpl_campos->CAMPO_QTDCARACTERES = "";
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");

//Ordenação
if ($usuario_grupo != 5) {
    $tpl_campos->COLUNA_ALINHAMENTO = "right";
    $tpl_campos->COLUNA_TAMANHO = "200px";
    $tpl_campos->TITULO = "Ordenado por";
    $tpl_campos->block("BLOCK_TITULO");
    $tpl_campos->block("BLOCK_CONTEUDO");
    $tpl_campos->block("BLOCK_COLUNA");
    $tpl_campos->COLUNA_ALINHAMENTO = "left";
    $tpl_campos->COLUNA_TAMANHO = "600px";
    $tpl_campos->CAMPO_TIPO = "text";
    $tpl_campos->CAMPO_NOME = "ordenacao";
    $tpl_campos->CAMPO_TAMANHO = "";
    $tpl_campos->CAMPO_VALOR = $_POST["ordenacao"];
    $tpl_campos->CAMPO_QTDCARACTERES = "";
    $tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
    $tpl_campos->block("BLOCK_CAMPO_PADRAO");
    $tpl_campos->block("BLOCK_CAMPO");
    $tpl_campos->block("BLOCK_CONTEUDO");
    $tpl_campos->block("BLOCK_COLUNA");
    $tpl_campos->block("BLOCK_LINHA");
}




$tpl_campos->show();


//Listagem
$tpl_lista = new Template("../templates/lista2.html");
$tpl_lista->block("BLOCK_TABELA_CHEIA");

//Cabeçalho
$tpl_lista->TEXTO = "ID";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->COLUNA_ROWSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "FORNECEDOR";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->COLUNA_ROWSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "VALOR BRUTO";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->COLUNA_ROWSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "TAXAS";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";

if ($fornecedor != "")
    $sql_taxas_filtro = $sql_taxas_filtro . " and ent_fornecedor=$fornecedor";
if ($acertados == 1) {
    $sql_taxas = "
    SELECT DISTINCT acetax_taxa,tax_nome,acetax_referencia
    FROM acertos_taxas
    join acertos on (acetax_acerto=ace_codigo)
    join saidas_produtos on (saipro_acertado=ace_codigo)
    join saidas on (saipro_saida=sai_codigo)
    join entradas on (saipro_lote=ent_codigo)
    join taxas on (acetax_taxa=tax_codigo)
    WHERE ace_quiosque=$usuario_quiosque
    and sai_tipo=1    
    $sql_taxas_filtro
    ";
    $query_taxas = mysql_query($sql_taxas);
    if (!$query_taxas)
        die("Erro 1:" . mysql_error());
    $qtd_taxas = mysql_num_rows($query_taxas);
} else {
    $sql_taxas = "
    SELECT quitax_taxa,tax_nome,quitax_valor
    FROM quiosques_taxas
    join taxas on (quitax_taxa=tax_codigo)
    WHERE quitax_quiosque =$usuario_quiosque     
    ";
    $query_taxas = mysql_query($sql_taxas);
    if (!$query_taxas)
        die("Erro 2:" . mysql_error());
    $qtd_taxas = mysql_num_rows($query_taxas);
}
$qtd_taxas = $qtd_taxas * 2;
$tpl_lista->COLUNA_COLSPAN = "$qtd_taxas";
$tpl_lista->COLUNA_ROWSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "VALOR LIQ";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->COLUNA_ROWSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "%";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->COLUNA_ROWSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->LINHA_CLASSE = "tab_cabecalho";
$tpl_lista->block("BLOCK_LINHA_DINAMICA");
$tpl_lista->block("BLOCK_LINHA");
//Nome das taxas
while ($dados_taxas = mysql_fetch_array($query_taxas)) {
    $tpl_lista->TEXTO = $dados_taxas[1];
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->COLUNA_ROWSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->TEXTO = number_format($dados_taxas[2], 2, ',', '.') . "%";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->COLUNA_ROWSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
}
$tpl_lista->LINHA_CLASSE = "tab_cabecalho";
$tpl_lista->block("BLOCK_LINHA_DINAMICA");
$tpl_lista->block("BLOCK_LINHA");
$tpl_lista->block("BLOCK_CORPO");


//Linhas da listagem
$sql_filtro = "";
if ($fornecedor != "")
    $sql_filtro = $sql_filtro . " and ent_fornecedor=$fornecedor ";
if ($acertados == 1)
    $sql_filtro_from = $sql_filtro_from . " join acertos on (ace_codigo=saipro_acertado) ";

$sql = "
SELECT pes_id, pes_nome, round(sum(saipro_valortotal),2) as totalbruto
FROM saidas_produtos
join entradas on (saipro_lote=ent_codigo)
join pessoas  on (ent_fornecedor=pes_codigo)
join saidas on (saipro_saida=sai_codigo)
$sql_filtro_from
WHERE sai_tipo=1
and sai_quiosque=$usuario_quiosque
and sai_datacadastro between '$datade_sembarras' and '$dataate_sembarras'
$sql_filtro
GROUP BY ent_fornecedor
ORDER BY $ordenacao    
";
$query = mysql_query($sql);
if (!$query)
    die("Erro 1:" . mysql_error());
while ($dados_bruto = mysql_fetch_array($query)) {
    $bruto = $dados_bruto["totalbruto"];
    $bruto_total = $bruto_total + $bruto;
}
$bruto = 0;
$query = mysql_query($sql);
while ($dados = mysql_fetch_assoc($query)) {
    $bruto = $dados["totalbruto"];
    //ID
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["pes_id"];
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    //Fornecedor
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["pes_nome"];
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    //Valor Bruto
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($bruto, 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    //Taxas
    $query_taxas = mysql_query($sql_taxas);
    $taxa_valor_total = 0;
    while ($dados_taxas = mysql_fetch_array($query_taxas)) {
        $taxa_percentual = $dados_taxas[2];
        $taxa_valor = $bruto * $taxa_percentual / 100;
        $taxa_valor_total = $taxa_valor_total + $taxa_valor;
        $tpl_lista->COLUNA_COLSPAN = "2";
        $tpl_lista->TEXTO = "R$ " . number_format($taxa_valor, 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
    }
    //Valor Liquido
    $liquido = $bruto - $taxa_valor_total;
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($liquido, 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    //Percentagens 
    $percentual = $bruto / $bruto_total * 100;
    $percentual_total = $percentual_total + $percentual;
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = number_format($percentual, 2, ',', '.') . "%";
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->block("BLOCK_LINHA");
}

if (mysql_num_rows($query) == 0) {
    $tpl_lista->LINHA_NADA_COLSPAN = "100";
    $tpl_lista->block("BLOCK_LINHA_NADA");
} else {




//Rodapé
    $tpl_lista->COLUNA_COLSPAN = "2";
    $tpl_lista->TEXTO = "";
    $tpl_lista->COLUNA_ALINHAMENTO = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
//Rodapé Bruto Total
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($bruto_total, 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $query_taxas = mysql_query($sql_taxas);
    $taxa_valor_total = 0;
//Rodapé Taxas Total
    while ($dados_taxas = mysql_fetch_array($query_taxas)) {
        $taxa_percentual = $dados_taxas[2];
        $taxas_valor = $bruto_total * $taxa_percentual / 100;
        $taxas_valor_total = $taxas_valor_total + $taxas_valor;
        $tpl_lista->COLUNA_COLSPAN = "2";
        $tpl_lista->TEXTO = "R$ " . number_format($taxas_valor, 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
    }
//Rodapé Valor Liquido Total
    $liquido_total = $bruto_total - $taxas_valor_total;
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($liquido_total, 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
//Rodapé % Total
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = number_format($percentual_total, 2, ',', '.') . "%";
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    $tpl_lista->LINHA_CLASSE = "tab_cabecalho";
    $tpl_lista->block("BLOCK_LINHA_DINAMICA");
    $tpl_lista->block("BLOCK_LINHA");
}

$tpl_lista->block("BLOCK_CORPO");

$tpl_lista->block("BLOCK_LISTAGEM");
$tpl_lista->show();

include "rel_baixo.php";
?>