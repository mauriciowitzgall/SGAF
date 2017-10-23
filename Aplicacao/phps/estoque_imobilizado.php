<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_estoque_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "estoque";
include "includes.php";
$tpl = new Template("estoque_imobilizado.html");
$tpl->ICONES_CAMINHO = "$icones";


//Inicio do FILTRO
$filtro_produto_nome = $_POST["filtroprodutonome"];
if (!empty($filtro_produto_nome)) {
    $sql_filtro= $sql_filtro." and ((pro_nome like '%$filtro_produto_nome%')or(pro_referencia like '%$filtro_produto_nome%')or(pro_tamanho like '%$filtro_produto_nome%')or(pro_cor like '%$filtro_produto_nome%')or(pro_descricao like '%$filtro_produto_nome%'))";
}

//Filtro produto
$tpl->PRODUTO_NOME = $filtro_produto_nome;
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
    etq_quantidade,
    protip_sigla, 
    cat_nome,
    pro_codigo,
    protip_nome,
    protip_codigo,
    pro_cooperativa,
    etq_produto,
    pro_referencia,
    pro_tamanho,
    pro_cor,
    pro_descricao,
    sum(etq_quantidade) as qtdtotal,
    sum(entpro_valunicusto*entpro_quantidade)/sum(etq_quantidade) as valunicustomedio
FROM
    estoque
    join produtos on (pro_codigo=etq_produto)
    join produtos_categorias on (cat_codigo=pro_categoria)
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
    join entradas on (etq_lote=ent_codigo)
    join entradas_produtos on (entpro_entrada=ent_codigo AND entpro_produto=etq_produto)
WHERE
    etq_fornecedor=0 and
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
$tpl->block("BLOCK_LISTA_RODAPE");



$query = mysql_query($sql);
if (!$query)
    die("Erro3: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas != "") {
    //Se o usu�rio for um fornecedor ent�o n�o mostrar algumas colunas
    if ($usuario_grupo != 5) {
        //$tpl->block("BLOCK_LISTA_CABECALHO_FORNECEDORES");
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
        $tpl->MEDIA = "R$ ".number_format($dados['valunicustomedio'],2,',','.');
        $tipocontagem=$dados['protip_codigo'];
        if (($tipocontagem==2)||($tipocontagem==3))
            $tpl->QUANTIDADE = number_format($dados['qtdtotal'], 3, ',', '.');
        else
            $tpl->QUANTIDADE = number_format($dados['qtdtotal'], 0, '', '.');
        $tpl->SIGLA = $dados['protip_sigla'];
              


        $valortot = $dados['qtdtotal'] *  $dados['valunicustomedio'] ;
        $tpl->VALOR_TOTAL = "R$ " . number_format($valortot, 2, ',', '.');
        $tpl->block("BLOCK_LISTA_TOTAL");
        $totcusto+=$valortot;
        
        $tpl->block("BLOCK_LISTA");
    }
} else {
    $tpl->block("BLOCK_LISTA_NADA");
}

$tpl->VALOR_TOTAL_GERAL = "R$ " . number_format($totcusto, 2, ',', '.');

$tpl->show();
include "rodape.php";
?>
