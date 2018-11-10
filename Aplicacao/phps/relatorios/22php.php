<?php

include "rel_topo.php";
include "cabecalho1.php";

$datade = $_REQUEST["datade"];
$dataate = $_REQUEST["dataate"];
$mostradiadasemana = $_REQUEST["mostradiadasemana"];



//FILTROS DESABILITADOS
//Campos de filtro
$tpl_campos = new Template("../templates/cadastro1.html");

//Periodos
$tpl_campos->COLUNA_ALINHAMENTO = "";
$tpl_campos->COLUNA_TAMANHO = "40%";
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "";
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
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "center";
$tpl_campos->COLUNA_TAMANHO = "";
$tpl_campos->TEXTO_NOME = "";
$tpl_campos->TEXTO_ID = "";
$tpl_campos->TEXTO_CLASSE = "";
$tpl_campos->TEXTO_VALOR = " até ";
$tpl_campos->block("BLOCK_TEXTO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
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
$tpl_campos->COLUNA_TAMANHO = "40%";
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->block("BLOCK_LINHA");

//Mostrar dias da semana
$tpl_campos->COLUNA_ALINHAMENTO = "";
$tpl_campos->COLUNA_TAMANHO = "40%";
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "";
$tpl_campos->TITULO = "Dia da Semana";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "mostradiadasemana";
if ($mostradiadasemana==1) $mostradiadasemana_nome="Sim";
else $mostradiadasemana_nome="Não";
$tpl_campos->CAMPO_VALOR = $mostradiadasemana_nome;
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
$tpl_lista->TEXTO = "ANO";
$tpl_lista->COLUNA_ALINHAMENTO = "right";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "MÊS";
$tpl_lista->COLUNA_ALINHAMENTO = "right";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

if ($mostradiadasemana==1) {
    $tpl_lista->TEXTO = "DIA DA SEMANA";
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");    
}

$tpl_lista->TEXTO = "TOTAL";
$tpl_lista->COLUNA_ALINHAMENTO = "right";
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
$cont=0;


//Se mostrar dias da semana então considerar no sql
if ($mostradiadasemana==1) {
    $sql_diasemana_select="dayofweek(sai_datacadastro) as diadasemana, ";
    $sql_diasemana_groupby=", dayofweek(sai_datacadastro)";
}

$sql=" 
    select year(sai_datacadastro) as ano ,month(sai_datacadastro) as mes , $sql_diasemana_select sum(sai_totalcomdesconto) as total 
    from saidas
    where sai_datacadastro between '$datade' and '$dataate'
    and sai_status=1
    and sai_tipo=1 
    group by year(sai_datacadastro),month(sai_datacadastro) $sql_diasemana_groupby

";
$acumulado=0;
if (!$query=mysql_query($sql)) die("Erro 0:" . mysql_error());
while ($dados=mysql_fetch_assoc($query)) {

    $total=$dados["total"];    
    
    //Ano
    $tpl_lista->COLUNA_COLSPAN = "";        
    $tpl_lista->TEXTO = $dados["ano"];
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Mês
    $tpl_lista->COLUNA_COLSPAN = ""; 
    if ($dados["mes"]==1) $mes_nome="Janeiro";
    if ($dados["mes"]==2) $mes_nome="Fevereiro";
    if ($dados["mes"]==3) $mes_nome="Março";
    if ($dados["mes"]==4) $mes_nome="Abril";
    if ($dados["mes"]==5) $mes_nome="Maio";
    if ($dados["mes"]==6) $mes_nome="Junho";
    if ($dados["mes"]==7) $mes_nome="Julho";
    if ($dados["mes"]==8) $mes_nome="Agosto";
    if ($dados["mes"]==9) $mes_nome="Setembro";
    if ($dados["mes"]==10) $mes_nome="Outubro";
    if ($dados["mes"]==11) $mes_nome="Novemebro";
    if ($dados["mes"]==12) $mes_nome="Dezembro";    

    $tpl_lista->TEXTO = $mes_nome;
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Dia da semana
    if ($mostradiadasemana==1) {
        $tpl_lista->COLUNA_COLSPAN = ""; 
        if ($dados["diadasemana"]==1) $diadasemana_nome="Domingo";
        if ($dados["diadasemana"]==2) $diadasemana_nome="Segunda";
        if ($dados["diadasemana"]==3) $diadasemana_nome="Terça";
        if ($dados["diadasemana"]==4) $diadasemana_nome="Quarta";
        if ($dados["diadasemana"]==5) $diadasemana_nome="Quinta";
        if ($dados["diadasemana"]==6) $diadasemana_nome="Sexta";
        if ($dados["diadasemana"]==7) $diadasemana_nome="Sábado";
        $tpl_lista->TEXTO = $diadasemana_nome;
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");    
    }

    $tpl_lista->COLUNA_COLSPAN = "";
    $total=$dados["total"];
    $tpl_lista->TEXTO = "R$ ". number_format($total, 2, ',', '.');    
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    $tpl_lista->COLUNA_COLSPAN = "";       
    $total_geral+=$total;

    $tpl_lista->block("BLOCK_LINHA");
}



    
if (mysql_num_rows($query) == 0) {
    $tpl_lista->LINHA_NADA_COLSPAN = "100";
    $tpl_lista->block("BLOCK_LINHA_NADA");
} else {

    //Rodapé
    if ($mostradiadasemana==1) $tpl_lista->COLUNA_COLSPAN = "3";
    else $tpl_lista->COLUNA_COLSPAN = "2";
    $tpl_lista->TEXTO = "";
    $tpl_lista->COLUNA_ALINHAMENTO = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Total 
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ " . number_format($total_geral, 2, ',', '.');
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