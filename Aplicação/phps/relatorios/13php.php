<?php

include "rel_topo.php";
include "cabecalho1.php";


////Pega os campo de filtro
$quiosque = $_REQUEST["quiosque"];
$datade = $_REQUEST["datade"];
$dataate = $_REQUEST["dataate"];
$caixa = $_REQUEST["caixa"];
$consumidor = $_REQUEST["consumidor"];
$metpag = $_REQUEST["metpag"];
$caderninho = $_REQUEST["caderninho"];

//Campos de filtro
$tpl_campos = new Template("../templates/cadastro1.html");


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



//caixa
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Caixa";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "caixa";
$tpl_campos->CAMPO_TAMANHO = "";
if ($caixa != "") {
    $sql = "
        SELECT pes_nome 
        FROM pessoas
        WHERE pes_codigo=$caixa
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


//Consumidor
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Consumidor";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "consumidor";
$tpl_campos->CAMPO_TAMANHO = "";
if ($consumidor != "") {
    $sql = "
        SELECT pes_nome 
        FROM pessoas
        WHERE pes_codigo=$consumidor
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


//Método de Pagamento
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Método de Pagamento";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "metpag";
$tpl_campos->CAMPO_TAMANHO = "";
if ($metpag != "") {
    $sql = "
        SELECT metpag_nome
        FROM metodos_pagamento
        WHERE metpag_codigo=$metpag
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


//Caderninho
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "200px";
$tpl_campos->TITULO = "Caderninho";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "600px";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "caderninho";
$tpl_campos->CAMPO_TAMANHO = "";
if ($caderninho == "")
    $nome = "Todos";
else if ($caderninho == 1)
    $nome = "Sim";
else
    $nome = "Não";
$tpl_campos->CAMPO_VALOR = "$nome";
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
$tpl_lista->TEXTO = "DATA";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "VAL. BRU.";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "70px";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "DESC.";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "70px";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "TOT.";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "70px";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
if ($caderninho == 0) {

    $tpl_lista->TEXTO = "VAL. REC.";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "70px";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->TEXTO = "TROCO";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "70px";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->TEXTO = "TROCO DEV.";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "70px";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->TEXTO = "FALTA TROCO";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "70px";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->TEXTO = "TOT. LIQ.";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "70px";
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
    /*
      $tpl_lista->TEXTO = "CADERN.";
      $tpl_lista->COLUNA_ALINHAMENTO = "center";
      $tpl_lista->COLUNA_TAMANHO = "";
      $tpl_lista->COLUNA_COLSPAN = "";
      $tpl_lista->block("BLOCK_COLUNA_PADRAO");
      $tpl_lista->block("BLOCK_TEXTO");
      $tpl_lista->block("BLOCK_CONTEUDO");
      $tpl_lista->block("BLOCK_COLUNA"); */
}
$tpl_lista->LINHA_CLASSE = "tab_cabecalho";
$tpl_lista->block("BLOCK_LINHA_DINAMICA");
$tpl_lista->block("BLOCK_LINHA");
$tpl_lista->block("BLOCK_CORPO");


//Linhas da listagem
$sql_filtro = "";
if ($quiosque != "")
    $sql_filtro = $sql_filtro . " and sai_quiosque=$quiosque ";
if ($caixa != "")
    $sql_filtro = $sql_filtro . " and sai_caixa=$caixa ";
if ($consumidor != "")
    $sql_filtro = $sql_filtro . " and sai_consumidor=$consumidor ";
if ($metpag != "")
    $sql_filtro = $sql_filtro . " and sai_metpag=$metpag ";
if ($caderninho != "")
    $sql_filtro = $sql_filtro . " and sai_areceber=$caderninho ";

$sql = "    
SELECT *
FROM saidas
WHERE sai_tipo=1
and sai_status=1
and sai_datacadastro between '$datade' and '$dataate'
$sql_filtro
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
    $desconto = $dados["sai_totalbruto"] - $dados["sai_totalcomdesconto"];
    $desconto_total+=$desconto;
    $tpl_lista->TEXTO = "R$ " . number_format($desconto, 2, ',', '.');
    if ($desconto == 0)
        $tpl_lista->TEXTO_CLASSE = "";
    else if ($desconto > 0)
        $tpl_lista->TEXTO_CLASSE = "texto_vermelho";
    else
        $tpl_lista->TEXTO_CLASSE = "texto_azul";
    $tpl_lista->block("BLOCK_TEXTO_CLASSE_EXTRA");
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Valor total com desconto
    $tpl_lista->COLUNA_COLSPAN = "";
    $totalcomdesconto_total+=$dados["sai_totalcomdesconto"];
    $tpl_lista->TEXTO = "R$ " . number_format($dados["sai_totalcomdesconto"], 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    if ($caderninho == 0) {
        //Valor Recebido
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = "R$ " . number_format($dados["sai_valorecebido"], 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Troco
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = "R$ " . number_format($dados["sai_troco"], 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Troco devolvido
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = "R$ " . number_format($dados["sai_trocodevolvido"], 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Falta de troco
        $tpl_lista->COLUNA_COLSPAN = "";
        $faltadetroco = -$dados["sai_descontoforcado"] + $dados["sai_acrescimoforcado"];
        $faltadetroco_total+=$faltadetroco;
        if ($faltadetroco == 0) {
            $tpl_lista->TEXTO_CLASSE = "";
        } else if ($faltadetroco > 0) {
            $tpl_lista->TEXTO_CLASSE = "texto_azul";
        } else {
            $tpl_lista->TEXTO_CLASSE = "texto_vermelho";
        }
        $faltadetroco_abs = abs($faltadetroco);
        $tpl_lista->TEXTO = "R$ " . number_format($faltadetroco_abs, 2, ',', '.');
        $tpl_lista->block("BLOCK_TEXTO_CLASSE_EXTRA");
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Liquido
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = "R$ " . number_format($dados["sai_totalliquido"], 2, ',', '.');
        $liquido_total+=$dados["sai_totalliquido"];
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
        /*
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
         */
    }
    $tpl_lista->block("BLOCK_LINHA");
}

if (mysql_num_rows($query) == 0) {
    $tpl_lista->LINHA_NADA_COLSPAN = "100";
    $tpl_lista->block("BLOCK_LINHA_NADA");
} else {

    //Rodapé
    $tpl_lista->COLUNA_COLSPAN = "3";
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

    //Rodapé Desconto
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($desconto_total, 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    if ($desconto_total > 0) {
        $tpl_lista->TEXTO_CLASSE = "texto_vermelho";
        $tpl_lista->block("BLOCK_TEXTO_CLASSE_EXTRA");
    }
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
    if ($caderninho == 0) {
        $tpl_lista->COLUNA_COLSPAN = "3";
        $tpl_lista->TEXTO = "";
        $tpl_lista->COLUNA_ALINHAMENTO = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Falta de troco
        $tpl_lista->COLUNA_COLSPAN = "";
        if ($faltadetroco_total == 0) {
            $tpl_lista->TEXTO_CLASSE = "";
        } else if ($faltadetroco_total > 0) {
            $tpl_lista->TEXTO_CLASSE = "texto_azul";
        } else {
            $tpl_lista->TEXTO_CLASSE = "texto_vermelho";
        }
        $tpl_lista->TEXTO = "R$ " . number_format($faltadetroco_total, 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_TEXTO_CLASSE_EXTRA");
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Liquido
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = "R$ " . number_format($liquido_total, 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
        $tpl_lista->COLUNA_COLSPAN = "3";
        $tpl_lista->TEXTO = "";
        $tpl_lista->COLUNA_ALINHAMENTO = "";

        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

    }
    $tpl_lista->LINHA_CLASSE = "tab_cabecalho";
    $tpl_lista->block("BLOCK_LINHA_DINAMICA");
    $tpl_lista->block("BLOCK_LINHA");
}

$tpl_lista->block("BLOCK_CORPO");

$tpl_lista->block("BLOCK_LISTAGEM");
$tpl_lista->show();

include "rel_baixo.php";
?>