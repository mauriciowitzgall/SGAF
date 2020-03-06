<?php

include "rel_topo.php";
include "cabecalho1.php";


////Pega os campo de filtro
$quiosque = $usuario_quiosque;
$datade = $_REQUEST["datade"];
$dataate = $_REQUEST["dataate"];
$consumidor = $_REQUEST["consumidor"];
$ocultarquitados= $_REQUEST["ocultarquitados"];
$classificacao = $_REQUEST["classificacao"];
$caderninho = 1;

//Campos de filtro
$tpl_campos = new Template("../templates/cadastro1.html");


//Periodos
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "";
$tpl_campos->TITULO = "Período";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_TAMANHO = "";
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "datade";
$tpl_campos->CAMPO_TAMANHO = "12";
$tpl_campos->CAMPO_VALOR = converte_data("$datade");
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->COLUNA_TAMANHO = "";
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
$tpl_campos->COLUNA_ALINHAMENTO = "";
$tpl_campos->block("BLOCK_LINHA");

//Consumidor
if ($consumidor!="") {

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
    $tpl_campos->CAMPO_TAMANHO = "50";
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
}


$tpl_campos->show();

//Listagem
$tpl_lista = new Template("../templates/lista2.html");
$tpl_lista->block("BLOCK_TABELA_CHEIA");



//Cabeçalho
$tpl_lista->TEXTO = "VENDA";
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
if ($consumidor=="") {
    $tpl_lista->TEXTO = "CONSUMIDOR";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
}
/*
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
*/
$tpl_lista->TEXTO = "LIQUIDO";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "PAGO";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "SALDO PENDENTE";
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
if ($quiosque != "")
    $sql_filtro = $sql_filtro . " and sai_quiosque=$quiosque ";
if ($consumidor != "")
    $sql_filtro = $sql_filtro . " and sai_consumidor=$consumidor ";
if ($caderninho != "")
    $sql_filtro = $sql_filtro . " and sai_areceber=$caderninho ";
if ($classificacao==1) $sql_orderby="sai_datacadastro,sai_horacadastro";
else $sql_orderby="pes_nome, sai_datacadastro,sai_horacadastro";


$sql = "    
SELECT *, (SELECT sum(saipag_valor) FROM saidas_pagamentos WHERE saipag_saida=sai_codigo) as totalpago
FROM saidas
left join pessoas on (sai_consumidor=pes_codigo)
WHERE sai_tipo=1
and sai_status=1
and sai_datacadastro between '$datade' and '$dataate'
$sql_filtro
order by $sql_orderby
";
$query = mysql_query($sql);
if (!$query)
    die("Erro 15:" . mysql_error());

while ($dados = mysql_fetch_assoc($query)) {
    $bruto = $dados["totalbruto"];
    $liquido = $dados["sai_totalcomdesconto"];
    $totalpago = $dados["totalpago"];
    $saldo=$liquido-$totalpago;
    
    if (($ocultarquitados==0)||(($ocultarquitados==1)&&($saldo!=0))) {
        $totalpago_total+=$totalpago;
        $liquido_total+=$liquido;
        $saldo_total+=$saldo;

    
        //Codigo
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = $dados["sai_codigo"];
        $tpl_lista->COLUNA_ALINHAMENTO = "center";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->COLUNA_COLSPAN = "";   
        $tpl_lista->CONTEUDO_LINK_ARQUIVO = "../saidas_ver.php?codigo=".$dados["sai_codigo"]."&ope=3&tiposaida=1&passo=1&modal=1";
        $tpl_lista->block("BLOCK_CONTEUDO_LINK_NOVAJANELA");
        $tpl_lista->block("BLOCK_CONTEUDO_LINK");            
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
        if ($consumidor=="") {
            $tpl_lista->COLUNA_COLSPAN = "";
            $tpl_lista->TEXTO = $dados["pes_nome"];
            $tpl_lista->COLUNA_ALINHAMENTO = "left";
            $tpl_lista->block("BLOCK_COLUNA_PADRAO");
            $tpl_lista->block("BLOCK_TEXTO");
            $tpl_lista->block("BLOCK_CONTEUDO");
            $tpl_lista->block("BLOCK_COLUNA");
        }

        /*
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
        */

        //Valor total com desconto
        $tpl_lista->COLUNA_COLSPAN = "";
        $totalcomdesconto_total+=$dados["sai_totalcomdesconto"];
        $tpl_lista->TEXTO = "R$ " . number_format($liquido, 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Total pago
        $tpl_lista->COLUNA_COLSPAN = "";    
        $tpl_lista->TEXTO = "R$ " . number_format($totalpago, 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Total pago
        $tpl_lista->COLUNA_COLSPAN = "";    
        $tpl_lista->TEXTO = "R$ " . number_format($saldo, 2, ',', '.');
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        $tpl_lista->block("BLOCK_LINHA");
    }
}

if (mysql_num_rows($query) == 0) {
    $tpl_lista->LINHA_NADA_COLSPAN = "100";
    $tpl_lista->block("BLOCK_LINHA_NADA");
} else {

    //Rodapé
    if ($consumidor=="") $tpl_lista->COLUNA_COLSPAN = "4";
    else $tpl_lista->COLUNA_COLSPAN = "3";
    $tpl_lista->TEXTO = "";
    $tpl_lista->COLUNA_ALINHAMENTO = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //liquido total
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($liquido_total, 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //total pago total
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($totalpago_total, 2, ',', '.');
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    if ($desconto_total > 0) {
        $tpl_lista->TEXTO_CLASSE = "texto_vermelho";
        $tpl_lista->block("BLOCK_TEXTO_CLASSE_EXTRA");
    }
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //saldo total
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($saldo_total, 2, ',', '.');
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