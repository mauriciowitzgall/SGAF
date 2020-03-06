<?php

include "rel_topo.php";
include "cabecalho1.php";

$datade = $_REQUEST["datade"];
$dataate = $_REQUEST["dataate"];
$mostraano = $_REQUEST["mostraano"];
$mostrames = $_REQUEST["mostrames"];
$mostradia = $_REQUEST["mostradia"];
$motivo = $_REQUEST["motivo"];
$tiporel = $_REQUEST["tiporel"];


//FILTROS DESABILITADOS
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
$tpl_campos->CAMPO_VALOR = converte_data("$datade");
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
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
$tpl_campos->block("BLOCK_LINHA");
$tpl_campos->show();


//Linhas da listagem
$cont=0;
$rodape_colspan=0;


if (($mostraano==1)||($tiporel==2)) {
    $sql_ano_select="year(sai_datacadastro) as ano ,";
    $sql_ano_groupby=",year(sai_datacadastro)";
    $rodape_colspan=$rodape_colspan+1;
}


if (($mostrames==1)||($tiporel==2)) {
    $sql_mes_select="month(sai_datacadastro) as mes ,";
    $sql_mes_groupby=" ,month(sai_datacadastro)";
    $rodape_colspan=$rodape_colspan+1;
}


if (($mostradia==1)||($tiporel==2)) {
    $sql_dia_select="day(sai_datacadastro) as dia,";
    $sql_dia_groupby=",day(sai_datacadastro)";
    $rodape_colspan=$rodape_colspan+1;
}


if ($tiporel=="2") {
    $sql_saida_select="sai_horacadastro, sai_codigo, pes_nome , ";
    $sql_saida_groupby=",sai_codigo";
    $rodape_colspan=$rodape_colspan+3;
}

if ($motivo>0) {
    $sql_where = $sql_where . " and sai_saidajustificada=$motivo ";    
}

if ($tiposaida==1) {

} else if ($tiposaida==2) {
    $sql_where= $sql_where . " and sai_tipo=1 ";
} else if ($tiposaida==3) {
    $sql_where=$sql_where . " and sai_tipo=3 ";
}


$sql_quebra1=" 
    SELECT DISTINCT saimot_codigo,saimot_nome
    FROM saidas
    join saidas_motivo on (saimot_codigo=sai_saidajustificada)
    join saidas_produtos on (saipro_saida=sai_codigo)
    where sai_datacadastro between '$datade' and '$dataate'  
    and sai_quiosque=$usuario_quiosque  
    and sai_status=1
    $sql_where 
";
if (!$query_quebra1=mysql_query($sql_quebra1)) die("Erro quebra 1:" . mysql_error());
while ($dados_quebra1=mysql_fetch_assoc($query_quebra1)) {
    $motivo_codigo=$dados_quebra1["saimot_codigo"];
    $motivo_nome= $dados_quebra1["saimot_nome"];

    $tpl2_tit = new Template("../templates/tituloemlinha_3.html");
    $tpl2_tit->LISTA_TITULO = "$motivo_nome";
    $tpl2_tit->block("BLOCK_QUEBRA1");
    $tpl2_tit->block("BLOCK_TITULO");
    $tpl2_tit->show();


    //INICIO DA LISTAGEM

    //Listagem
    $tpl_lista = new Template("../templates/lista2.html");
    $tpl_lista->block("BLOCK_TABELA_CHEIA");

    //Motivo
    /*
    $tpl_lista->TEXTO = "Motivo";
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    */



    if (($mostraano==1)||($tiporel==2)) {
        $tpl_lista->TEXTO = "ANO";
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->COLUNA_TAMANHO = "";
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
    }

    if (($mostrames==1)||($tiporel==2)) {
        $tpl_lista->TEXTO = "MÊS";
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->COLUNA_TAMANHO = "";
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
    }


    if (($mostradia==1)||($tiporel==2)) {
        $tpl_lista->TEXTO = "DIA";
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->COLUNA_TAMANHO = "";
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");    
    }


    if ($tiporel==2) {
        $tpl_lista->TEXTO = "HORA";
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->COLUNA_TAMANHO = "";
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");  
        $tpl_lista->TEXTO = "USUARIO";
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->COLUNA_TAMANHO = "";
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");   
        $tpl_lista->TEXTO = "VENDA";
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->COLUNA_TAMANHO = "";
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");                     
    }    


    //Total Liquido
    $tpl_lista->TEXTO = "QTD. LIQ.";
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");


    //Fim do cabeçalho
    $tpl_lista->LINHA_CLASSE = "tab_cabecalho";
    $tpl_lista->block("BLOCK_LINHA_DINAMICA");
    $tpl_lista->block("BLOCK_LINHA");
    $tpl_lista->block("BLOCK_CORPO");



    $sql=" 
        SELECT $sql_ano_select $sql_mes_select $sql_dia_select $sql_saida_select saimot_nome ,round(sum(saipro_totalpesoliquido),3) as totalpesoliquido
        FROM saidas
        join saidas_motivo on (saimot_codigo=sai_saidajustificada)
        join saidas_produtos on (saipro_saida=sai_codigo)
        left join pessoas on (sai_usuarioquecadastrou=pes_codigo)
        where sai_datacadastro between '$datade' and '$dataate'    
        and sai_status=1
        and saimot_codigo=$motivo_codigo
        $sql_where 
        group by sai_saidajustificada $sql_ano_groupby $sql_mes_groupby $sql_dia_groupby $sql_saida_groupby    

    ";
    $acumulado=0;
    if (!$query=mysql_query($sql)) die("Erro 0:" . mysql_error());
    while ($dados=mysql_fetch_assoc($query)) {

        $totalpesoliquido=$dados["totalpesoliquido"]; 


        //Motivo
        /*
        $tpl_lista->COLUNA_COLSPAN = "";        
        $tpl_lista->TEXTO = $dados["saimot_nome"];
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");
        */


        //Ano
        if ($mostraano==1) {
            $tpl_lista->COLUNA_COLSPAN = "";        
            $tpl_lista->TEXTO = $dados["ano"];
            $tpl_lista->COLUNA_ALINHAMENTO = "right";
            $tpl_lista->block("BLOCK_COLUNA_PADRAO");
            $tpl_lista->block("BLOCK_TEXTO");
            $tpl_lista->block("BLOCK_CONTEUDO");
            $tpl_lista->block("BLOCK_COLUNA");
        }

        //Mês
        if ($mostrames) {
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
        }

            
        //Dia
        if ($mostradia==1) {
            $tpl_lista->COLUNA_COLSPAN = "";        
            $tpl_lista->TEXTO = $dados["dia"];
            $tpl_lista->COLUNA_ALINHAMENTO = "right";
            $tpl_lista->block("BLOCK_COLUNA_PADRAO");
            $tpl_lista->block("BLOCK_TEXTO");
            $tpl_lista->block("BLOCK_CONTEUDO");
            $tpl_lista->block("BLOCK_COLUNA");
        }

            
        //Colunas do analítico
        if ($tiporel==2) {
            $tpl_lista->COLUNA_ALINHAMENTO = "right";
            $tpl_lista->COLUNA_COLSPAN = ""; 
            $hora = explode(':', $dados["sai_horacadastro"]);
            $hora = $hora[0] . ":" . $hora[1];       
            $tpl_lista->TEXTO = $hora;
            $tpl_lista->block("BLOCK_COLUNA_PADRAO");
            $tpl_lista->block("BLOCK_TEXTO");
            $tpl_lista->block("BLOCK_CONTEUDO");
            $tpl_lista->block("BLOCK_COLUNA");
            $tpl_lista->COLUNA_COLSPAN = "";        
            $tpl_lista->TEXTO = $dados["pes_nome"];
            $tpl_lista->block("BLOCK_COLUNA_PADRAO");
            $tpl_lista->block("BLOCK_TEXTO");
            $tpl_lista->block("BLOCK_CONTEUDO");
            $tpl_lista->block("BLOCK_COLUNA");
            $tpl_lista->COLUNA_COLSPAN = "";   
             $tpl_lista->CONTEUDO_LINK_ARQUIVO = "../saidas_ver.php?codigo=".$dados["sai_codigo"]."&ope=3&tiposaida=1&passo=1&modal=1";
            $tpl_lista->block("BLOCK_CONTEUDO_LINK_NOVAJANELA");
            $tpl_lista->block("BLOCK_CONTEUDO_LINK");        
            $tpl_lista->TEXTO = $dados["sai_codigo"];
            $tpl_lista->block("BLOCK_COLUNA_PADRAO");
            $tpl_lista->block("BLOCK_TEXTO");
            $tpl_lista->block("BLOCK_CONTEUDO");
            $tpl_lista->block("BLOCK_COLUNA");                        
        }        

        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = number_format($totalpesoliquido, 3, ',', '.');    
        $tpl_lista->COLUNA_ALINHAMENTO = "right";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        $tpl_lista->COLUNA_COLSPAN = "";       
        $total_geral+=$totalpesoliquido;
        $total_geral_final+=$totalpesoliquido;

        $tpl_lista->block("BLOCK_LINHA");
    }

        
    if (mysql_num_rows($query) == 0) {
        $tpl_lista->LINHA_NADA_COLSPAN = "100";
        $tpl_lista->block("BLOCK_LINHA_NADA");
    } else {

        //Rodapé    
        $tpl_lista->COLUNA_COLSPAN = "$rodape_colspan";
        $tpl_lista->TEXTO = "";
        $tpl_lista->COLUNA_ALINHAMENTO = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Total 
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->TEXTO = number_format($total_geral, 3, ',', '.');
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

    $total_geral=0;

}



include "rel_baixo.php";
?>