<?php

include "rel_topo.php";
include "cabecalho1.php";


////Pega os campo de filtro
$quiosque = $usuario_quiosque;
$contagemascegas = $_REQUEST["contagemascegas"];
$i=0;
foreach($_POST['categoria'] as $value) {
    if ($i==0) $sql_where_categorias="$value";
    else $sql_where_categorias.=",$value";
    $i++;
}
//print_r($_REQUEST);


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
$tpl_lista->TEXTO = "SISTEMA";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "RESERVADO";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "FÍSICO ESPERADO";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "2";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "FISICO CONTADO ";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "DIFERENÇA";
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
FROM produtos
join produtos_categorias on (pro_categoria=cat_codigo)
join produtos_tipo on (pro_tipocontagem=protip_codigo)
WHERE cat_cooperativa=$usuario_cooperativa
and cat_codigo in ($sql_where_categorias)
order by pro_nome
";
if (!$query = mysql_query($sql))  die("Erro SQL Principal:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $produto_codigo = $dados["pro_codigo"];
    $produto_nome = $dados["pro_nome"];
    $produto_referencia = $dados["pro_referencia"];
    $produto_tipocontagem = $dados["pro_tipocontagem"];
    $produto_tipocontagem_sigla = $dados["protip_sigla"];
    

    //Produto
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $produto_referencia;
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $produto_nome;
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Sistema
    $tpl_lista->COLUNA_COLSPAN = "";
    $sql_sistema="SELECT sum(etq_quantidade) as qtd FROM estoque WHERE etq_quiosque=$usuario_quiosque and etq_produto=$produto_codigo";
    if (!$query_sistema = mysql_query($sql_sistema))  die("Erro SQL coluna sistema:" . mysql_error());
    $dados_sistema = mysql_fetch_assoc($query_sistema); 
    $sistema=$dados_sistema["qtd"];  
    if (($produto_tipocontagem==2)||($produto_tipocontagem==3)) $tpl_lista->TEXTO = number_format($sistema,3,",",".");
    else $tpl_lista->TEXTO = number_format($sistema,0,",",".");
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $produto_tipocontagem_sigla;
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");


    //Reservado
    $tpl_lista->COLUNA_COLSPAN = "";
    $sql2="
        SELECT sum(saipro_quantidade) as qtd_reservado
        FROM saidas_produtos 
        JOIN saidas on (sai_codigo=saipro_saida)
        join produtos on (saipro_produto=pro_codigo)
        WHERE sai_quiosque=$usuario_quiosque
        and sai_entrega=1
        and sai_entrega_concluida=0
        and sai_dataentrega >= '$dataatual'
        and saipro_produto=$produto_codigo
    ";
    if (!$query2 = mysql_query($sql2))  die("Erro SQL2:" . mysql_error());
    $dados2 = mysql_fetch_assoc($query2); 
    $reservado=$dados2["qtd_reservado"];  
    if (($produto_tipocontagem==2)||($produto_tipocontagem==3)) $tpl_lista->TEXTO = number_format($reservado,3,",",".");
    else $tpl_lista->TEXTO = number_format($reservado,0,",",".");
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $produto_tipocontagem_sigla;
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");


    //Físico esperado
    $fisico_esperado=$sistema-$reservado;
    if (($produto_tipocontagem==2)||($produto_tipocontagem==3)) $tpl_lista->TEXTO = number_format($fisico_esperado,3,",",".");
    else $tpl_lista->TEXTO = number_format($fisico_esperado,0,",",".");
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = $produto_tipocontagem_sigla;
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Físico contato
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "";
    $tpl_lista->COLUNA_ALINHAMENTO = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    //Diferença
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "";
    $tpl_lista->COLUNA_ALINHAMENTO = "";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->COLUNA_COLSPAN = "";              
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
}

$tpl_lista->block("BLOCK_CORPO");

$tpl_lista->block("BLOCK_LISTAGEM");
$tpl_lista->show();

include "rel_baixo.php";
?>