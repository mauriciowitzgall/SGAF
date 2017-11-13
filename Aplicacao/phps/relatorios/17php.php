<?php

include "rel_topo.php";
include "cabecalho1.php";


////Pega os campo de filtro
$descontomin = $_REQUEST["descontomin"];
$descontomin = str_replace(".", "", $descontomin);
$descontomin = str_replace(",", ".", $descontomin);
$datade = $_REQUEST["datade"];
$dataate = $_REQUEST["dataate"];

//Campos de filtro
$tpl_campos = new Template("../templates/cadastro1.html");

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
$tpl_campos->CAMPO_VALOR = converte_data("$datade");
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
$tpl_campos->CAMPO_VALOR = converte_data($dataate);
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");




//Desconto Minimo
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Desconto maior que";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "descontomino";
$tpl_campos->CAMPO_TAMANHO = "";
$tpl_campos->CAMPO_VALOR = $_REQUEST["descontomin"]. "%";
$tpl_campos->CAMPO_QTDCARACTERES = "";
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");


$tpl_campos->show();

//Listagem
$tpl_lista = new Template("../templates/lista2.html");
$tpl_lista->block("BLOCK_TABELA_CHEIA");



//Cabeçalho
$tpl_lista->TEXTO = "SAÍDA";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "COMAN.";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "DATA";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "CONS.";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");


$tpl_lista->TEXTO = "VAL. BRU.";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "11%";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "DESC.";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "TOT. COM <br>DESC.";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "11%";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "MET. PAG.";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "A REC.";
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
$sql = "    
SELECT *
FROM saidas
left join pessoas on (sai_consumidor=pes_codigo)
WHERE sai_tipo=1
and sai_status=1
and sai_datacadastro between '$datade' and '$dataate'
and sai_descontopercentual > $descontomin
ORDER BY sai_descontopercentual desc
";
$query = mysql_query($sql);
if (!$query)
    die("Erro 15:" . mysql_error());

while ($dados = mysql_fetch_assoc($query)) {
    

    $bruto = $dados["totalbruto"];
    
    
    //Codigo
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["sai_codigo"];
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA"); 
       
    //Comanda
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $dados["sai_id"];
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Data e hora
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = converte_data($dados["sai_datacadastro"]);
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = converte_hora($dados["sai_horacadastro"]);
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Consumidor
    $tpl_lista->COLUNA_COLSPAN = "";
    if ($sai_consumidor==0) $consumidor_nome="Cliente Geral";
    else $consumidor_nome=$dados["pes_nome"];
    $tpl_lista->TEXTO = $consumidor_nome;
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Valor Bruto
    $bruto_total+=$dados["sai_totalbruto"];
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($dados["sai_totalbruto"], 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Desconto
    $tpl_lista->COLUNA_COLSPAN = "";
    $desconto = $dados["sai_descontovalor"];
    $desconto_total+=$desconto;
    $tpl_lista->TEXTO = "R$ " . number_format($desconto, 2, ',', '.');
    //if ($desconto == 0) $tpl_lista->TEXTO_CLASSE = "";
    //else if ($desconto > 0) $tpl_lista->TEXTO_CLASSE = "texto_vermelho";
    //else $tpl_lista->TEXTO_CLASSE = "texto_azul";
    //$tpl_lista->block("BLOCK_TEXTO_CLASSE_EXTRA");
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->TEXTO = number_format($dados["sai_descontopercentual"], 2, ',', '.'). "%";
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");


    //Total com desconto
    $tpl_lista->COLUNA_COLSPAN = "";
    $totalcomdesconto_total+=($dados["sai_totalbruto"] - $dados["sai_descontovalor"]);
    $tpl_lista->TEXTO = "R$ " . number_format($dados["sai_totalbruto"] - $dados["sai_descontovalor"], 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");


    //Método de pagamento
    $metodo = $dados["sai_metpag"];
    $tpl_lista->COLUNA_COLSPAN = "";
    $sql_1 = "SELECT metpag_nome FROM metodos_pagamento WHERE metpag_codigo=$metodo";
    $query_1 = mysql_query($sql_1);
    if (!$query_1)
        die("Erro sql metodos de pagamento listagem item" . mysql_error());
    $dados_1 = mysql_fetch_array($query_1);
    $metodo_nome = $dados_1[0];
    $tpl_lista->TEXTO = "$metodo_nome";
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
        
    //Caderninho
    $tpl_lista->COLUNA_COLSPAN = "";
    $caderni = $dados["sai_areceber"];
    if ($caderni == 1)
    $tpl_lista->TEXTO = "Sim";
    else
    $tpl_lista->TEXTO = "Não";

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
    $tpl_lista->COLUNA_COLSPAN = "5";
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

    //Desconto total
    $tpl_lista->COLUNA_COLSPAN = "2";
    $tpl_lista->TEXTO = "R$ " . number_format($desconto_total, 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Total com desconto
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($totalcomdesconto_total, 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Rodapé
    $tpl_lista->COLUNA_COLSPAN = "3";
    $tpl_lista->TEXTO = "";
    $tpl_lista->COLUNA_ALINHAMENTO = "";
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