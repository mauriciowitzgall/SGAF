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
    $sql_filtro= $sql_filtro." and ((pro_nome like '%$filtro_produto_nome%')or(pro_referencia like '%$filtro_produto_nome%')or(pro_tamanho like '%$filtro_produto_nome%')or(pro_cor like '%$filtro_produto_nome%')or(pro_descricao like '%$filtro_produto_nome%'))";
}
$filtro_produto_tamanho = $_POST["filtroprodutotamanho"];
if (!empty($filtro_produto_tamanho)) {
    $sql_filtro= $sql_filtro." and pro_tamanho like '%$filtro_produto_tamanho%'";
}
$filtro_produto_cor = $_POST["filtroprodutocor"];
if (!empty($filtro_produto_cor)) {
    $sql_filtro= $sql_filtro." and pro_cor like '%$filtro_produto_cor%'";
}
$filtro_produto_referencia = $_POST["filtroprodutoreferencia"];
if (!empty($filtro_produto_referencia)) {
    $sql_filtro= $sql_filtro." and pro_referencia like '%$filtro_produto_referencia%'";
}
$filtro_produto_descricao = $_POST["filtroprodutodescricao"];
if (!empty($filtro_produto_descricao)) {
    $sql_filtro= $sql_filtro." and pro_descricao like '%$filtro_produto_descricao%'";
}
$filtro_categoria = $_POST["filtrocategoria"];
if (!empty($filtro_categoria)) {
    $sql_filtro= $sql_filtro." and pro_categoria=$filtro_categoria";
}
$filtro_marca = $_POST["filtromarca"];
if (!empty($filtro_marca)) {
    $sql_filtro= $sql_filtro." and pro_marca like '%$filtro_marca%'";
}
if ($usuario_grupo==5) {
    $sql_filtro= $sql_filtro." and etq_fornecedor=$usuario_codigo ";
}


//Filtro produto
$tpl->PRODUTO_NOME = $filtro_produto_nome;
$tpl->PRODUTO_TAMANHO = $filtro_produto_tamanho;
$tpl->PRODUTO_MARCA = $filtro_marca;
$tpl->PRODUTO_COR = $filtro_produto_cor;
$tpl->PRODUTO_REFERENCIA = $filtro_produto_referencia;
$tpl->PRODUTO_DESCRICAO = $filtro_produto_descricao;


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

//Verifica qual é a ordenação padrão das configuracões do quiosque
$sql2 = "SELECT * FROM quiosques_configuracoes WHERE quicnf_quiosque=$usuario_quiosque";
if (!$query2= mysql_query($sql2)) die("Erro: " . mysql_error());
$dados2=  mysql_fetch_assoc($query2);
$classificacaopadraoestoque=$dados2["quicnf_classificacaopadraoestoque"];
if ($classificacaopadraoestoque==1) { //Por Nome do produto
    $sql_ordenacao = "pro_nome, pro_referencia,pro_tamanho,pro_cor,pro_descricao";
} else if ($classificacaopadraoestoque==2) { //Por Referencia do produto
    $sql_ordenacao = "pro_referencia,pro_nome,pro_tamanho,pro_cor,pro_descricao";
} else {
    $sql_ordenacao = "pro_nome"; 
}


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
    sum(etq_quantidade*etq_valorunitario)/sum(etq_quantidade) as valunimedia,
    etq_produto,
    pro_referencia,
    pro_tamanho,
    pro_cor,
    pro_descricao
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
    pro_codigo
ORDER BY
    $sql_ordenacao 
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
        $nome= $dados['pro_nome'];
        $referencia= $dados['pro_referencia'];
        $tamanho= $dados['pro_tamanho'];
        $cor= $dados['pro_cor'];
        $descricao= $dados['pro_descricao'];
        $nome2=" $nome $tamanho $cor $descricao ";
        $tpl->PRODUTO = $nome2;
        $tpl->PRODUTO_CODIGO = $dados['pro_codigo'];
        $tpl->PRODUTO_REFERENCIA = $dados['pro_referencia'];
        $tpl->MEDIA = "R$ ".number_format($dados['valunimedia'],2,',','.');
        $tipocontagem=$dados['protip_codigo'];
        if (($tipocontagem==2)||($tipocontagem==3))
            $tpl->QUANTIDADE = number_format($dados['qtd'], 3, ',', '.');
        else
            $tpl->QUANTIDADE = number_format($dados['qtd'], 0, '', '.');
        $tpl->SIGLA = $dados['protip_sigla'];
        //Se o usu�rio for um fornecedor ent�o n�o mostrar algumas colunas
        
        $tpl->CATEGORIA = $dados['cat_nome'];
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
