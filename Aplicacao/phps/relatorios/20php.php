<?php

include "rel_topo.php";
include "cabecalho1.php";

$datade = $_REQUEST["datade"];
$dataate = $_REQUEST["dataate"];



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

//Listagem
$tpl_lista = new Template("../templates/lista2.html");
$tpl_lista->block("BLOCK_TABELA_CHEIA");

//Linhas da listagem
$sql=" 
    SELECT cat_nome, cat_codigo, pro_nome, sum(saipro_quantidade) as qtd, sum(saipro_valortotal) as tot, pro_tipocontagem as tipcon, pro_referencia
    FROM saidas
    JOIN saidas_produtos on (saipro_saida=sai_codigo)
    JOIN produtos on (pro_codigo=saipro_produto)
    JOIN produtos_categorias on (pro_categoria=cat_codigo)
    WHERE sai_status=1
    and sai_tipo=1
    and sai_datacadastro  > '$datade'
    and sai_datacadastro < '$dataate'
    GROUP BY saipro_produto
    ORDER BY cat_nome, pro_nome

";
if (!$query=mysql_query($sql)) die("Erro 0:" . mysql_error());
$primeiro=1;
$cont=0;
$ultimo=0;
$rows=mysql_num_rows($query); 
$mudou=0;
while ($dados=mysql_fetch_assoc($query)) {

    //Verifica se mudou a categoria
    $categoria_nova = $dados["cat_codigo"];
    if ($categoria_velha!=$categoria_nova) $mudou=1;
    else $mudou=0;
    $categoria_velha = $dados["cat_codigo"];    

        
    if (($mudou==1)&&($primeiro==0)) {
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

        $tpl_lista->block("BLOCK_TABELA_CHEIA");
        $tpl_lista->block("BLOCK_LISTAGEM");
        $total_geral=0;
    }


    if ($mudou==1) {
        //Título da Categoria
        $tpl_lista->LISTA_TITULO = $dados["cat_nome"];
        $tpl_lista->DIV_CLASSE = "";
        $tpl_lista->SPAN_CLASSE = "titemlin1_span";
        $tpl_lista->block("BLOCK_QUEBRA1");
        $tpl_lista->block("BLOCK_QUEBRA1");
        //$tpl_lista->block("BLOCK_LINHA1");
        $tpl_lista->block("BLOCK_TITULO");
        $tpl_lista->block("BLOCK_QUEBRA2");

        //Cabeçalho
        $tpl_lista->TEXTO = "PRODUTO";
        $tpl_lista->COLUNA_ALINHAMENTO = "center";
        $tpl_lista->COLUNA_TAMANHO = "60%";
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        $tpl_lista->TEXTO = "QUANTIDADE";
        $tpl_lista->COLUNA_ALINHAMENTO = "center";
        $tpl_lista->COLUNA_TAMANHO = "20%";
        $tpl_lista->COLUNA_COLSPAN = "";
        $tpl_lista->block("BLOCK_COLUNA_PADRAO");
        $tpl_lista->block("BLOCK_TEXTO");
        $tpl_lista->block("BLOCK_CONTEUDO");
        $tpl_lista->block("BLOCK_COLUNA");

        $tpl_lista->TEXTO = "TOTAL";
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
    }
    

    $tpl_lista->COLUNA_COLSPAN = "";
    $referencia=$dados["pro_referencia"];
    $produto_nome=$dados["pro_nome"];
    if ($referencia!="") $nome2="$produto_nome ($referencia)";
    else $nome2="$produto_nome";
    $tpl_lista->TEXTO = $nome2;
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->COLUNA_COLSPAN = "";
    $tipcon=$dados["tipcon"];
    if (($tipcon==2)||($tipcon==3)) $tpl_lista->TEXTO = number_format($dados["qtd"],3, ',', '.');
    else $tpl_lista->TEXTO = $dados["qtd"];

    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "R$ ". number_format($dados["tot"], 2, ',', '.');
    $total_geral+=$dados["tot"];
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    $tpl_lista->block("BLOCK_LINHA");
    
    $tpl_lista->block("BLOCK_CORPO");

    //Verifica se é a ultima vez que o laço está repetindo
    $cont++;
    if ($rows==$cont) $ultimo=1;

    //Chama a listagem apenas se mudou de categoria ou se está no ultimo produto
    if ($ultimo==1) {
        
        $tpl_lista->block("BLOCK_TABELA_CHEIA");
        $tpl_lista->block("BLOCK_LISTAGEM");
    }
    $primeiro=0;
}
//$tpl_lista->block("BLOCK_LISTAGEM");

$tpl_lista->show();


include "rel_baixo.php";
?>