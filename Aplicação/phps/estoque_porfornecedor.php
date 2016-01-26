<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_estoque_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}



$tipopagina = "estoque";
include "includes.php";
$tpl = new Template("estoque_porfornecedor.html");
$tpl->ICONES_CAMINHO = "$icones";

//Inicio do FILTRO
$filtro_fornecedor = $_POST["filtrofornecedor"];
if ($usuario_grupo != 5) {

    //Filtro fornecedor
    $sql1 = "
    SELECT DISTINCT
        pes_nome,pes_codigo
    FROM
        estoque
        join pessoas on (etq_fornecedor=pes_codigo) 
    WHERE
        etq_quiosque=$usuario_quiosque
    ORDER BY
        pes_nome
";
    $query1 = mysql_query($sql1);
    if (!$query1) {
        DIE("Erro SQL:" . mysql_error());
    }
    while ($dados1 = mysql_fetch_array($query1)) {
        $fornecedor_codigo = $dados1['pes_codigo'];
        $tpl->FORNECEDOR_CODIGO = $fornecedor_codigo;
        $tpl->FORNECEDOR_NOME = $dados1['pes_nome'];
        if ($fornecedor_codigo == $filtro_fornecedor) {
            $tpl->FORNECEDOR_SELECIONADO = " selected ";
        } else {
            $tpl->FORNECEDOR_SELECIONADO = " ";
        }
        $tpl->block("BLOCK_FILTRO_FORNECEDOR");
    }
    $tpl->block("BLOCK_FILTRO");        
} else {
    $filtro_fornecedor=$usuario_codigo;
}



if ($filtro_fornecedor != "") {
    $sql_filtro= $sql_filtro. " and etq_fornecedor= $filtro_fornecedor ";
}

//SQL principal
$sql = "
SELECT DISTINCT
    pes_nome,pes_codigo,pes_id,round(sum(etq_quantidade*etq_valorunitario),2) as tot
FROM
    estoque
    join pessoas on (etq_fornecedor=pes_codigo)    
WHERE
    etq_quiosque='$usuario_quiosque' 
    $sql_filtro
GROUP BY
    pes_codigo
ORDER BY 
    pes_nome
";




//Paginação
//Executa primeiro o SQL sem a paginação para pegar o valor total do estoque 
//que será mostrado no rodapé da ultima pagina
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
while ($dados = mysql_fetch_assoc($query)) {
    $valor_total_geral = $valor_total_geral + $dados["tot"];
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
    $tpl->block("BLOCK_LISTA_RODAPE");
}







$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas != "") {
    while ($dados = mysql_fetch_array($query)) {
        $tpl->FORNECEDOR = $dados['pes_nome'];
        $tpl->FORNECEDOR_COD = $dados['pes_codigo'];
        $tpl->FORNECEDOR_ID = $dados['pes_id'];

        //Conta quantos produtos cada fornecedor possui
        $fornecedor = $dados['pes_codigo'];
        $sql2 = "SELECT etq_produto FROM `estoque` WHERE etq_fornecedor=$fornecedor GROUP BY etq_produto";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro: " . mysql_error());
        $linhas2 = mysql_num_rows($query2);
        $tpl->QTD_PRODUTOS = $linhas2;

        //Calcula o valor total dos produtos cada fornecedor possui
        $fornecedor = $dados['pes_codigo'];
        $sql2 = "SELECT round(sum(etq_valorunitario*etq_quantidade),2) as tot FROM `estoque` WHERE etq_fornecedor=$fornecedor ";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro: " . mysql_error());
        $dados2 = mysql_fetch_assoc($query2);
        $tpl->VALOR_TOTAL = "R$ " . number_format($dados2["tot"], 2, ',', '.');
        $tpl->block("BLOCK_LISTA");
    }
}
else {
    $tpl->block("BLOCK_LISTA_NADA");
}

$tpl->show();
include "rodape.php";
?>
