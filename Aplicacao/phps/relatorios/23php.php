<?php

include "rel_topo.php";
include "cabecalho1.php";

$datade = $_REQUEST["datade"];
$dataate = $_REQUEST["dataate"];
$mostrar = $_REQUEST["mostrar"];
$dataatual = date("Y-m-d");

$ordenar = $_REQUEST["ordenar"];
if ($ordenar=="produto") $ordenacao="pro_referencia";
else if ($ordenar=="consumidor") $ordenacao="pes_codigo";
else $ordenacao="erro";

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

//Mostrar 
$tpl_campos->COLUNA_ALINHAMENTO = "";
$tpl_campos->COLUNA_TAMANHO = "40%";
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "";
$tpl_campos->TITULO = "Mostrara";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "mostrar";
if ($mostrar==1) $mostrar_nome="Apenas não devolvidos";
else $mostrar_nome="Devolvidos e não devolvidos";
$tpl_campos->CAMPO_VALOR = $mostrar_nome;
$tpl_campos->block("BLOCK_CAMPO_DESABILITADO");
$tpl_campos->block("BLOCK_CAMPO_PADRAO");
$tpl_campos->block("BLOCK_CAMPO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");

//Ordenação 
$tpl_campos->COLUNA_ALINHAMENTO = "";
$tpl_campos->COLUNA_TAMANHO = "40%";
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "right";
$tpl_campos->COLUNA_TAMANHO = "";
$tpl_campos->TITULO = "Classificar/Ordenar por";
$tpl_campos->block("BLOCK_TITULO");
$tpl_campos->block("BLOCK_CONTEUDO");
$tpl_campos->block("BLOCK_COLUNA");
$tpl_campos->COLUNA_ALINHAMENTO = "left";
$tpl_campos->CAMPO_TIPO = "text";
$tpl_campos->CAMPO_NOME = "ordenar";
if ($ordenar=="produto") $ordenacao_nome="Produto";
else $ordenacao_nome="Consumidor";
$tpl_campos->CAMPO_VALOR = $ordenacao_nome;
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



$tpl_lista->TEXTO = "CONSUMIDOR";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "30%";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "VENDA";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "30%";
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

$tpl_lista->TEXTO = "PRODUTO";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "3";
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");

if ($mostrar!=1) {
    $tpl_lista->TEXTO = "QTD DA VENDA";
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA"); 


    $tpl_lista->TEXTO = "QTD DEVOLVIDA";
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->COLUNA_TAMANHO = "";
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA"); 
}

$tpl_lista->TEXTO = "SALDO DEVEDOR";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA"); 

$tpl_lista->TEXTO = "TEMPO FORA";
$tpl_lista->COLUNA_ALINHAMENTO = "left";
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



$sql=" 
SELECT sai_codigo, saipro_codigo, sai_datacadastro, sai_horacadastro, pes_nome, pro_referencia, pro_nome, saipro_quantidade, (
    SELECT sum(saidevpro_qtddevolvida)
    FROM saidas_devolucoes_produtos 
    WHERE saidevpro_saida=sai_codigo
    and saidevpro_itemsaida=saipro_codigo
) as qtd_devolvida,
saipro_quantidade-(
    SELECT sum(saidevpro_qtddevolvida)
    FROM saidas_devolucoes_produtos 
    WHERE saidevpro_saida=sai_codigo
    and saidevpro_itemsaida=saipro_codigo
) as saldo_devedor
FROM saidas_produtos
JOIN saidas on (sai_codigo=saipro_saida)
join produtos on (pro_codigo=saipro_produto)
join pessoas on (pes_codigo=sai_consumidor)
WHERE sai_datacadastro between '$datade' and  '$dataate'
and pro_categoria=92 
order by $ordenacao

";
$acumulado=0;
if (!$query=mysql_query($sql)) die("Erro 0:" . mysql_error());
while ($dados=mysql_fetch_assoc($query)) {

    $quantidade = $dados["saipro_quantidade"];
    $qtd_devolvida = $dados["qtd_devolvida"];
    $saldo_devedor = $quantidade-$qtd_devolvida;
    $data_venda=$dados["sai_datacadastro"];

    if ((($saldo_devedor>0)&&($mostrar==1))||($mostrar==2)) {
    
        
        //Consumidor
        $tpl_lista->COLUNA_COLSPAN = "";        
        $tpl_lista->TEXTO = $dados["pes_nome"];
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Venda Numero
        $tpl_lista->COLUNA_COLSPAN = ""; 
        $tpl_lista->TEXTO = $dados["sai_codigo"];
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->CONTEUDO_LINK_ARQUIVO = "../saidas_ver.php?codigo=".$dados["sai_codigo"]."&ope=3&tiposaida=1&passo=1&modal=1";
                $tpl_lista->block("BLOCK_CONTEUDO_LINK_NOVAJANELA");
        $tpl_lista->block("BLOCK_CONTEUDO_LINK");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        
        //Venda Data e Hora
        $tpl_lista->COLUNA_COLSPAN = ""; 
        $tpl_lista->TEXTO = converte_data($dados["sai_datacadastro"])." ".converte_hora($dados["sai_horacadastro"]);
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        //Produto referencia
        $tpl_lista->COLUNA_COLSPAN = ""; 
        $tpl_lista->TEXTO = $dados["pro_referencia"];
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");    

        //Produto nome
        $tpl_lista->COLUNA_COLSPAN = ""; 
        $tpl_lista->TEXTO = $dados["pro_nome"];
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA"); 

        if ($mostrar!=1) {

            //Quantidade
            $tpl_lista->COLUNA_COLSPAN = ""; 
            
            $tpl_lista->TEXTO = $quantidade;
            $tpl_lista->COLUNA_ALINHAMENTO = "left";
            $tpl_lista->block("BLOCK_COLUNA_PADRAO");
            $tpl_lista->block("BLOCK_TEXTO");
            $tpl_lista->block("BLOCK_CONTEUDO");
            $tpl_lista->block("BLOCK_COLUNA"); 

            //Quantidade devolvida
            $tpl_lista->COLUNA_COLSPAN = ""; 
            
            if ($qtd_devolvida == "") $qtd_devolvida=0;
            $tpl_lista->TEXTO = $qtd_devolvida;
            $tpl_lista->COLUNA_ALINHAMENTO = "left";
            $tpl_lista->block("BLOCK_COLUNA_PADRAO");
            $tpl_lista->block("BLOCK_TEXTO");
            $tpl_lista->block("BLOCK_CONTEUDO");
            $tpl_lista->block("BLOCK_COLUNA");
        } 

        //Saldo devedor
        $tpl_lista->COLUNA_COLSPAN = "";    

        $tpl_lista->TEXTO = $saldo_devedor;
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA"); 

        //Tempo fora
        $tpl_lista->COLUNA_COLSPAN = "";   
        $tpl_lista->TEXTO = diferenca_data($data_venda, $dataatual, 'D') . " dias";
        $tpl_lista->COLUNA_ALINHAMENTO = "left";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA"); 

        $tpl_lista->COLUNA_COLSPAN = "";  
        
        $tpl_lista->block("BLOCK_LINHA");
    }
}



    
if (mysql_num_rows($query) == 0) {
    $tpl_lista->LINHA_NADA_COLSPAN = "100";
    $tpl_lista->block("BLOCK_LINHA_NADA");
} 

$tpl_lista->block("BLOCK_CORPO");

$tpl_lista->block("BLOCK_LISTAGEM");
$tpl_lista->show();



include "rel_baixo.php";
?>