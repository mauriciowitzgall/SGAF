<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
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
$filtro_produto = $_POST["filtroproduto"];
if (empty($filtro_produto)) {
    $filtro_produto = "%";
}
$filtro_categoria = $_POST["filtrocategoria"];
if (empty($filtro_categoria)) {
    $filtro_categoria = "%";
}

//Filtro produto
$sql_produto = "
    SELECT DISTINCT
        etq_produto,pro_codigo,pro_nome
    FROM
        estoque
        join produtos on (etq_produto=pro_codigo)  
    WHERE
        etq_quiosque=$usuario_quiosque 
    ORDER BY 
        pro_nome
";
$query_produto = mysql_query($sql_produto);
if (!$query_produto) {
    DIE("Erro1 SQL:" . mysql_error());
}
while ($dados_produto = mysql_fetch_array($query_produto)) {
    $produto_codigo = $dados_produto['pro_codigo'];
    $tpl->PRODUTO_CODIGO = $produto_codigo;
    $tpl->PRODUTO_NOME = $dados_produto['pro_nome'];
    if ($produto_codigo == $filtro_produto) {
        $tpl->PRODUTO_SELECIONADO = " selected ";
    } else {
        $tpl->PRODUTO_SELECIONADO = " ";
    }
    $tpl->block("BLOCK_FILTRO_PRODUTO");
}

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
    etq_produto like '$filtro_produto' and
    pro_categoria like '$filtro_categoria' and
    etq_quiosque=$usuario_quiosque 
    $sql_filtro 
GROUP BY
    pro_nome
ORDER BY
    pro_nome
";

//Paginação
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
//Se é a primeira vez que acessa a pagina então começar na pagina 1
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
    //Se o usuário for um fornecedor então não mostrar algumas colunas
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
        //Se o usuário for um fornecedor então não mostrar algumas colunas
        
        if ($usuario_grupo != 5) {
            $valortot = $dados['valortot'];
            $tpl->VALOR_TOTAL = "R$ " . number_format($valortot, 2, ',', '.');
            $tpl->block("BLOCK_LISTA_TOTAL");
            $tpl->block("BLOCK_LISTA_FORNECEDORES");
        }                   
        $tpl->CATEGORIA = $dados['cat_nome'];
        $tpl->PRODUTO_CODIGO = $dados['pro_codigo'];
        $produto = $dados['pro_codigo'];
        $fornecedor = $dados['etq_fornecedor'];
        $sqltot = "SELECT DISTINCT etq_fornecedor FROM estoque WHERE etq_produto=$produto ";
        $tot = mysql_num_rows(mysql_query($sqltot));
        
        $tpl->QTD_FORNECEDORES = $tot;

        $tpl->block("BLOCK_LISTA");
    }
} else {
    $tpl->block("BLOCK_LISTA_NADA");
}

$tpl->show();
include "rodape.php";
?>
