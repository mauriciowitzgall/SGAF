<?php

include "rel_topo.php";
include "cabecalho1.php";


////Pega os campo de filtro
$cooperativa = $_POST["cooperativa"];
$quiosque = $_POST["quiosque"];
$produto = $_POST["produto"];
//echo "coo: $cooperativa qui:$quiosque pro:$produto";
$datade = $_POST["datade"];
$dataate = $_POST["dataate"];
$datade_combarra = converte_data($datade);
$dataate_combarra = converte_data($dataate);
$ordenacao = $_POST["ordenacao"];
if ($ordenacao == "Nome de produto")
    $ordenacao = " pro_nome ";
else
    $ordenacao = "totalbruto desc";
//Campos de filtro
$tpl_campos = new Template("../templates/cadastro1.html");


//Cooperativa
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Cooperativa";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "cooperativa";
$tpl_campos->CAMPO_TAMANHO = "";
if ($cooperativa != "") {
    $sql = "
        SELECT coo_abreviacao
        FROM cooperativas
        WHERE coo_codigo=$cooperativa
    ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro 8:" . mysql_error());
    $dados = mysql_fetch_array($query);
    $nome = $dados[0];
} else {
    $nome = "Todos";
}
$tpl_campos->CAMPO_VALOR = "$nome";
$tpl_campos->CAMPO_QTDCARACTERES = "";
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");

//Quiosque
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Quiosque";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "quiosque";
$tpl_campos->CAMPO_TAMANHO = "";
if ($quiosque != "") {
    $sql = "
        SELECT qui_nome 
        FROM quiosques
        WHERE qui_codigo=$quiosque
    ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro 8:" . mysql_error());
    $dados = mysql_fetch_array($query);
    $nome = $dados[0];
} else {
    $nome = "Todos";
}
$tpl_campos->CAMPO_VALOR = "$nome";
$tpl_campos->CAMPO_QTDCARACTERES = "";
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");

//Campo Produto
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Produto";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "produto";
$tpl_campos->CAMPO_TAMANHO = "";
if ($produto != "") {
    $sql = "
        SELECT pro_nome 
        FROM produtos
        WHERE pro_codigo=$produto
    ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro 11:" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $produto_nome = $dados["pro_nome"];
} else {
    $produto_nome = "Todos";
}
$tpl_campos->CAMPO_VALOR = "$produto_nome";
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
$tpl_campos->TITULO = "Período";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "datade";
$tpl_campos->CAMPO_VALOR = "$datade_combarra";
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
$tpl_campos->CAMPO_VALOR = "$dataate_combarra";
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
$tpl_lista->TEXTO = "PRODUTO";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "VALOR BRUTO";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "%";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->LINHA_CLASSE = "tab_cabecalho";
$tpl_lista->block("BLOCK_LINHA_DINAMICA");
$tpl_lista->block("BLOCK_LINHA");
$tpl_lista->block("BLOCK_CORPO");


//Linhas da listagem
$sql_filtro = "";
if ($cooperativa != "")
    $sql_filtro = $sql_filtro . " and coo_codigo=$cooperativa ";
if ($quiosque != "")
    $sql_filtro = $sql_filtro . " and qui_codigo=$quiosque ";
if ($produto != "")
    $sql_filtro = $sql_filtro . " and saipro_produto=$produto ";

$sql = "
SELECT pro_codigo, pro_nome, round(sum(saipro_valortotal),2) as totalbruto
FROM saidas_produtos
join saidas on (saipro_saida=sai_codigo)
join quiosques on (sai_quiosque=qui_codigo)
join cooperativas on (qui_cooperativa=coo_codigo)
join produtos on (saipro_produto=pro_codigo)
$sql_filtro_from
WHERE sai_tipo=1
and sai_datacadastro between '$datade' and '$dataate'
$sql_filtro
GROUP BY saipro_produto
ORDER BY $ordenacao    
";
$query = mysql_query($sql);
if (!$query)
    die("Erro 12:" . mysql_error());
while ($dados_bruto = mysql_fetch_array($query)) {
    $bruto = $dados_bruto["totalbruto"];
    $bruto_total = $bruto_total + $bruto;
}
$bruto = 0;
$query = mysql_query($sql);
while ($dados = mysql_fetch_assoc($query)) {
    $bruto = $dados["totalbruto"];
    //Codigo
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["pro_codigo"];
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    //Produto
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["pro_nome"];
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

    //Percentagens 
    $percentual = $bruto / $bruto_total * 100;
    $percentual_total = $percentual_total + $percentual;
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = number_format($percentual, 3, ',', '.') . "%";
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
    
    //Rodapé % Total
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = number_format($percentual_total,3, ',', '.') . "%";
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