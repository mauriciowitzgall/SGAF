<?php
$tipopagina = "saidas";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

$numero_devolucao=$_GET["codigo"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "VENDAS DEVOLUÇÕES";
$tpl_titulo->SUBTITULO = "LISTA DE PRODUTOS DEVOLVIDOS DE UMA DEVOLUÇVENDA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "vendas.png";
$tpl_titulo->show();


$tpl = new Template("templates/listagem_2.html");

//Pega dados da venda e devolucoes para popular os campos de filtro desabilitados
$sql="
    SELECT * 
    FROM saidas_devolucoes
    JOIN saidas on saidev_saida=sai_codigo
    LEFT JOIN pessoas on sai_consumidor = pes_codigo 
    WHERE saidev_numero=$numero_devolucao
";
        
if (!$query=mysql_query($sql)) die("Erro SQL Filtros que mostram dados da devolucao: " . mysql_error());
$dados=mysql_fetch_assoc($query);
$data_devolucao=$dados["saidev_data"];
$saida=$dados["saidev_saida"];
$consumidor_nome=$dados["pes_nome"];


//Campo Filtro Numero da devolução
$tpl->CAMPO_TITULO = "Número";
$tpl->CAMPO_VALOR = $numero_devolucao;
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Campo Filtro Data da devolução
$tpl->CAMPO_TITULO = "Data";
$tpl->CAMPO_VALOR = converte_datahora($data_devolucao);
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Campo Filtro Código da venda
$tpl->CAMPO_TITULO = "Venda";
$tpl->CAMPO_VALOR = $saida;
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Campo Filtro Consumidor Nome
$tpl->CAMPO_TITULO = "Consumidor";
if ($consumidor_nome=="") $consumidor_nome="Cliente Geral";
$tpl->CAMPO_VALOR = $consumidor_nome;
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


$tpl->block("BLOCK_FILTRO");


//INICIO DA LISTAGEM 

//Item da devolucao
$tpl->CABECALHO_COLUNA_TAMANHO="30px";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="Nº ITEM";
$tpl->block("BLOCK_LISTA_CABECALHO");

//ITEM DA VENDA
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="ITEM DA VENDA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//PRODUTO
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="PRODUTO";
$tpl->block("BLOCK_LISTA_CABECALHO");

//PRODUTO LOTE
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="LOTE";
$tpl->block("BLOCK_LISTA_CABECALHO");

//QTD DEVOLVIDA
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="QTD. DEVOLVIDA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor Unitário
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="Valor Unitário";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor Final
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="Valor Final";
$tpl->block("BLOCK_LISTA_CABECALHO");


//SQL Principal
$sql="
    SELECT * 
    FROM saidas_devolucoes_produtos
    JOIN saidas_devolucoes on saidevpro_numerodev=saidev_numero
    JOIN saidas on saidev_saida=sai_codigo
    JOIN produtos on saidevpro_produto=pro_codigo 
    LEFT JOIN pessoas on sai_consumidor = pes_codigo 
    WHERE saidevpro_numerodev=$numero_devolucao
    ORDER BY saidevpro_itemdev DESC
";


//PAGINAÇÃO
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se � a primeira vez que acessa a pagina ent�o come�ar na pagina 1
if (($paginaatual == "") || ($paginas < $paginaatual) || ($paginaatual <= 0)) {
    $paginaatual = 1;
}
$comeco = ($paginaatual - 1) * $por_pagina;
$tpl->PAGINAS = "$paginas";
$tpl->PAGINAATUAL = "$paginaatual";
$tpl->PASTA_ICONES = "$icones";
$tpl->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";

$cont=0;
while ($dados=  mysql_fetch_assoc($query)) {
    $data= $dados["saidev_data"];
    $item= $dados["saidevpro_itemdev"];
    $item_venda= $dados["saidevpro_itemsaida"];
    $produto= $dados["pro_nome"];
    $lote= $dados["saidevpro_lote"];
    $qtddevolvida= $dados["saidevpro_qtddevolvida"];
    $valuni= $dados["saidevpro_valuni"];
    $valtot= $dados["saidevpro_valtot"];
    $usuario= $dados["saidev_usuario"];
    $usuario_nome= $dados["pes_nome"];


    //Nº ITEM
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$item";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Nº Item da Venda
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  $item_venda;
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Produto
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  $produto;
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Lote
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  $lote;
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Quantidade devolvida
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  $qtddevolvida;
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Valor Unitário
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  "R$ " . number_format($valuni, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Valor Total
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=   "R$ " . number_format($valtot, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");

    $tot=$tot+$valtot;
   
   
    $tpl->block("BLOCK_LISTA"); 
    $cont++;
}

if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}


//1
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");
//2
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");
//3
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");
//4
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");
//5
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");
//6
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");
//7 Total final
$tpl->RODAPE_COLUNA_NOME= "R$ " . number_format($tot, 2, ',', '.');
$tpl->RODAPE_COLUNA_ALINHAMENTO="right";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");


$tpl->block("BLOCK_RODAPE_LINHA");
$tpl->block("BLOCK_RODAPE");


//Botão Voltar
$tpl->LINK_VOLTAR="saidas_devolucoes.php?codigo=$saida";
$tpl->block("BLOCK_RODAPE_BOTAO_VOLTAR");
$tpl->block("BLOCK_RODAPE_BOTAO");
$tpl->block("BLOCK_RODAPE_BOTOES");


$tpl->show();

include "rodape.php";

?>