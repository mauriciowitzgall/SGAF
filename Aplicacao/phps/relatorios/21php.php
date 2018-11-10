<?php

include "rel_topo.php";
include "cabecalho1.php";

$datade = $_REQUEST["datade"];
$dataate = $_REQUEST["dataate"];
$consideracligeral = $_REQUEST["consideracligeral"];



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




$tpl_campos->show();

//TÃ­tulo Filtro
$tpl2_tit = new Template("../templates/tituloemlinha_2.html");
$tpl2_tit->LISTA_TITULO = "FATURAMENTO POR CONSUMIDOR CURVA ABC";
$tpl2_tit->block("BLOCK_QUEBRA1");
$tpl2_tit->block("BLOCK_TITULO");
$tpl2_tit->block("BLOCK_QUEBRA2");
$tpl2_tit->show();


$sql="
    select sum(sai_totalcomdesconto) as tot
    from saidas
    where sai_datacadastro between '$datade' and '$dataate'
    and sai_status=1
    and sai_tipo=1    
";
if (!$query=mysql_query($sql)) die("Erro TOT:" . mysql_error());
$dados=mysql_fetch_array($query);
$total_geral=$dados[0];
$a1_maximo=$total_geral*0.40;
$a2_maximo=$total_geral*0.80;
$b1_maximo=$total_geral*0.875; 
$b2_maximo=$total_geral*0.95;

//Listagem
$tpl_lista = new Template("../templates/lista2.html");
$tpl_lista->block("BLOCK_TABELA_CHEIA");

//Cabeçalho
$tpl_lista->TEXTO = "CONSUMIDOR";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "FATURAMENTO";
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
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "ABC";
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
$cont=0;
if ($consideracligeral==1) $filtro_consideracligeral="AND sai_consumidor not in (0)";

$sql=" 
    select pes_nome, sum(sai_totalcomdesconto) as total
    from saidas
    left join pessoas on (sai_consumidor=pes_codigo)
    where sai_datacadastro between '$datade' and '$dataate'
    and sai_status=1
    and sai_tipo=1   
    $filtro_consideracligeral 
    group by pes_codigo
    order by total desc

";
$acumulado=0;
if (!$query=mysql_query($sql)) die("Erro 0:" . mysql_error());
while ($dados=mysql_fetch_assoc($query)) {

    $total=$dados["total"];    
    
    $tpl_lista->COLUNA_COLSPAN = "";
    if ($dados["pes_nome"]=="") $nome="Cliente Geral";
    else $nome=$dados["pes_nome"];
    $tpl_lista->TEXTO = $nome;
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ ". number_format($total, 2, ',', '.');    
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    $tpl_lista->COLUNA_COLSPAN = "";    
    $acumulado=$acumulado+$total;
    
    if ($acumulado<=$a1_maximo) {
        $abc="A+";
        $texto_classe="texto_verde_negrito"; 
    } else if (($acumulado > $a1_maximo)&&($acumulado <= $a2_maximo)) { 
        $abc="A-"; 
        $texto_classe="texto_verde_negrito"; 
    } else if (($acumulado > $a2_maximo)&&($acumulado <= $b1_maximo)) {
        $abc="B+";
        $texto_classe="texto_amarelo_negrito";         
    } else if (($acumulado > $b1_maximo)&&($acumulado <= $b2_maximo)) {
        $abc="B-";
        $texto_classe="texto_amarelo_negrito"; 
    } else {
        $abc="C";
        $texto_classe="texto_vermelho_negrito"; 
    }
    //echo "Acumulado: $acumulado / Amax: $a_maximo / Bmax: $b_maximo <br>";
    $percentual=$total/$total_geral*100;
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->TEXTO = number_format($percentual, 2, ',', '.'). "%";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");    
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");   
    $percentual_acumulado=$percentual_acumulado + $percentual;
    $tpl_lista->TEXTO = number_format($percentual_acumulado, 2, ',', '.'). "%";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA"); 
    $tpl_lista->TEXTO = "$abc"; 
    $tpl_lista->TEXTO_CLASSE="$texto_classe";
    $tpl_lista->block("BLOCK_TEXTO_CLASSE_EXTRA");
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");    

    $tpl_lista->block("BLOCK_LINHA");
}

//Se considerar cliente geral então incrementar na ultima linha da listagem
if ($consideracligeral==1) {


    $sql=" 
        select sum(sai_totalcomdesconto) as total
        from saidas
        left join pessoas on (sai_consumidor=pes_codigo)
        where sai_datacadastro between '$datade' and '$dataate'
        and sai_status=1
        and sai_tipo=1   
        and sai_consumidor=0
        group by pes_codigo  
    ";
    $acumulado=0;
    if (!$query=mysql_query($sql)) die("Erro 3:" . mysql_error());
    while ($dados=mysql_fetch_assoc($query)) {
        $cligeral_valor=$dados["total"];
    }
    $cligeral_percentual=$cligeral_valor/$total_geral*100;
    
    $tpl_lista->COLUNA_COLSPAN = "";
    $nome="Cliente Geral";    
    $tpl_lista->TEXTO = $nome;
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ ". number_format($cligeral_valor, 2, ',', '.');    
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");    

    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->TEXTO = number_format($cligeral_percentual, 2, ',', '.'). "%";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");    
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");   
    $percentual_acumulado=$percentual_acumulado + $cligeral_percentual;
    $tpl_lista->TEXTO = number_format($percentual_acumulado, 2, ',', '.'). "%";
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA"); 
    $tpl_lista->TEXTO = "C"; 
    $tpl_lista->TEXTO_CLASSE="texto_vermelho_negrito";
    $tpl_lista->block("BLOCK_TEXTO_CLASSE_EXTRA");
    $tpl_lista->COLUNA_ALINHAMENTO = "center";
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
    $tpl_lista->COLUNA_COLSPAN = "";
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