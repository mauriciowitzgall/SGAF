<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_estoque_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "estoque";
include "includes.php";
$tpl = new Template("estoque.html");
$tpl->ICONES_CAMINHO = "$icones";


//Inicio do FILTRO
$filtro_produto_nome = $_POST["filtroprodutonome"];
if (!empty($filtro_produto_nome)) {
    $sql_filtro= $sql_filtro." and pro_nome like '%$filtro_produto_nome%'";
}
$filtro_categoria = $_POST["filtrocategoria"];
if (!empty($filtro_categoria)) {
    $sql_filtro= $sql_filtro." and pro_categoria=$filtro_categoria";
}
if ($usuario_grupo==5) {
    $sql_filtro= $sql_filtro." and etq_fornecedor=$usuario_codigo ";
}

$filtro_tiponegociacao = $_POST["filtrotiponegociacao"];
if (!empty($filtro_tiponegociacao)) {
    $sql_filtro= $sql_filtro." and etq_produto not in (
        SELECT mesprotip_produto 
        FROM  mestre_produtos_tipo
        JOIN produtos on (etq_produto=pro_codigo)
        WHERE mesprotip_tipo=$filtro_tiponegociacao
    )"; 
   
}

//Filtro produto
$tpl->PRODUTO_NOME = $filtro_produto_nome;


//Filtro categoria
$sql_categoria = "
    SELECT DISTINCT
        cat_codigo,cat_nome
    FROM
        estoque
        join produtos on (pro_codigo=etq_produto)
        join produtos_categorias on (pro_categoria=cat_codigo) 
    WHERE
        etq_quiosque=$usuario_quiosque 
    ORDER BY 
        cat_nome
";
$query_categoria = mysql_query($sql_categoria);
if (!$query_categoria) {
    DIE("Erro2 SQL:" . mysql_error());
}
while ($dados_categoria = mysql_fetch_array($query_categoria)) {
    $categoria_codigo = $dados_categoria['cat_codigo'];
    $tpl->CATEGORIA_CODIGO = $categoria_codigo;
    $tpl->CATEGORIA_NOME = $dados_categoria['cat_nome'];
    if ($categoria_codigo == $filtro_categoria) {
        $tpl->CATEGORIA_SELECIONADA = " selected ";
    } else {
        $tpl->CATEGORIA_SELECIONADA = " ";
    }
    $tpl->block("BLOCK_FILTRO_CATEGORIA");
}


//Filtro tiponegociacao
$sql6 = "
    SELECT DISTINCT tipneg_codigo,tipneg_nome
    FROM tipo_negociacao    
    JOIN mestre_produtos_tipo on (mesprotip_tipo=tipneg_codigo)
    join produtos on (mesprotip_produto=pro_codigo)
    join estoque on (etq_produto=pro_codigo)
    WHERE etq_quiosque=$usuario_quiosque     
";
$query6 = mysql_query($sql6);
if (!$query6) {
    DIE("Erro2 SQL:" . mysql_error());
}
while ($dados6 = mysql_fetch_array($query6)) {    
    $tpl->TIPONEGOCIACAO_CODIGO = $dados6[0];    
    $tpl->TIPONEGOCIACAO_NOME = $dados6[1];
    if ($dados6[0] == $filtro_tiponegociacao) {
        $tpl->TIPONEGOCIACAO_SELECIONADA = " selected ";
    } else {
        $tpl->TIPONEGOCIACAO_SELECIONADA = " ";
    }    
    $tpl->block("BLOCK_FILTRO_TIPONEGOCIACAO");
}





$tpl->block("BLOCK_FILTRO");

//Inicio da tabela de listagem
//SQL principal
$sql = "
SELECT DISTINCT
    pro_nome,
    sum(etq_quantidade) as qtd,
    protip_sigla, cat_nome,
    pes_nome,
    pro_codigo,
    protip_nome,
    protip_codigo,
    pro_cooperativa,
    sum(etq_quantidade*etq_valorunitario) as valortot,    
    round(avg(etq_valorunitario),2) as valunimedia
FROM
    estoque
    join produtos on (pro_codigo=etq_produto)
    join produtos_categorias on (cat_codigo=pro_categoria)
    join pessoas on (etq_fornecedor=pes_codigo)
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
WHERE
    pro_cooperativa='$usuario_cooperativa' and   
    etq_quiosque=$usuario_quiosque 
    $sql_filtro 
GROUP BY
    pro_nome
ORDER BY
    pro_nome
";

//Paginacão
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
while ($dados = mysql_fetch_assoc($query)) {
    $valor_total_geral = $valor_total_geral + $dados["valortot"];
}
$tpl->VALOR_TOTAL_GERAL = "R$ " . number_format($valor_total_geral, 2, ',', '.');
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
if ($paginaatual == $paginas) {
    if ($usuario_grupo != 5) {
        $tpl->block("BLOCK_LISTA_RODAPE_FORNECEDORES");
        $tpl->block("BLOCK_LISTA_RODAPE_TOTAL");           
    }   
    $tpl->block("BLOCK_LISTA_RODAPE");
}



$query = mysql_query($sql);
if (!$query)
    die("Erro3: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas != "") {
    //Se o usu�rio for um fornecedor ent�o n�o mostrar algumas colunas
    if ($usuario_grupo != 5) {
        $tpl->block("BLOCK_LISTA_CABECALHO_FORNECEDORES");
        $tpl->block("BLOCK_LISTA_CABECALHO_TOTAL");
    }
    while ($dados = mysql_fetch_array($query)) {
        $tpl->PRODUTO = $dados['pro_nome'];
        $tpl->PRODUTO_CODIGO = $dados['pro_codigo'];
        $tpl->MEDIA = "R$ ".number_format($dados['valunimedia'],2,',','.');
        $tipocontagem=$dados['protip_codigo'];
        if ($tipocontagem==2)
            $tpl->QUANTIDADE = number_format($dados['qtd'], 3, ',', '.');
        else
            $tpl->QUANTIDADE = number_format($dados['qtd'], 0, '', '.');
        $tpl->SIGLA = $dados['protip_sigla'];
        //Se o usu�rio for um fornecedor ent�o n�o mostrar algumas colunas
        
        $tpl->CATEGORIA = $dados['cat_nome'];
        $tpl->PRODUTO_CODIGO = $dados['pro_codigo'];
        $produto = $dados['pro_codigo'];
        $fornecedor = $dados['etq_fornecedor'];
        $sqltot = "
            SELECT DISTINCT etq_fornecedor 
            FROM estoque 
            JOIN entradas on (etq_lote=ent_codigo)
            WHERE etq_produto=$produto 
            AND ent_quiosque=$usuario_quiosque
        ";
        $tot = mysql_num_rows(mysql_query($sqltot));        
        $tpl->QTD_FORNECEDORES = $tot;

        if ($usuario_grupo != 5) {
            $valortot = $dados['valortot'];
            $tpl->VALOR_TOTAL = "R$ " . number_format($valortot, 2, ',', '.');
            $tpl->block("BLOCK_LISTA_TOTAL");
            $tpl->block("BLOCK_LISTA_FORNECEDORES");
        }                   
        
        
        $tpl->block("BLOCK_LISTA");
    }
} else {
    $tpl->block("BLOCK_LISTA_NADA");
}

$tpl->show();
include "rodape.php";
?>
