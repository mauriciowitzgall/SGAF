<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_estoque_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "estoque";
include "includes.php";

$tpl = new Template("estoque_validade.html");
$tpl->ICONES_CAMINHO = "$icones";

$filtro_produto = $_POST["filtroproduto"];
$filtro_produto_nome = $_POST["filtroprodutonome"];
if (!empty($filtro_produto_nome)) 
    $sql_filtro= $sql_filtro." and pro_nome like '%$filtro_produto_nome%'";
$filtro_fornecedor = $_POST["filtrofornecedor"];
if (($usuario_grupo == 5) && ($filtro_fornecedor != $usuario_codigo)) {
    $filtro_fornecedor = $usuario_codigo;
}

//Filtro produto
$tpl->PRODUTO_NOME="$filtro_produto_nome";    

//Filtro fornecedor
if ($usuario_grupo!=5) {
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
        $tpl->block("BLOCK_FILTRO_FORNECEDOR_OPTION");
    }
    $tpl->block("BLOCK_FILTRO_FORNECEDOR");
}


$tpl->block("BLOCK_FILTRO");

if ($filtro_fornecedor != "")
    $sql_filtro = $sql_filtro . " and etq_fornecedor= '$filtro_fornecedor' ";
if ($filtro_produto != "")
    $sql_filtro = $sql_filtro . " and etq_produto='$filtro_produto' ";


//Listagem
$sql = "
SELECT 
    pro_nome,
    pro_codigo,
    pes_nome,
    pes_codigo,
    pes_id,
    etq_lote,
    protip_codigo,
    etq_quantidade,
    protip_sigla,
    etq_validade
FROM
    estoque
    join produtos on (pro_codigo=etq_produto)    
    join pessoas on (etq_fornecedor=pes_codigo)
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
WHERE
    pro_cooperativa=$usuario_cooperativa and
    etq_quiosque=$usuario_quiosque and 
    etq_validade not in (0000-00-00)
    $sql_filtro
ORDER BY
    etq_validade
";

//Pagina��o
$query = mysql_query($sql);
if (!$query)
    die("@@@@Erro SQL Principal Pagina��o:" . mysql_error());
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


$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas != "") {
    while ($dados = mysql_fetch_array($query)) {
        $tpl->PRODUTO = $dados['pro_nome'];
        $tpl->PRODUTO_CODIGO = $dados['pro_codigo'];
        $tpl->FORNECEDOR = $dados['pes_nome'];
        $tpl->FORNECEDOR_CODIGO = $dados['pes_codigo'];
        $tpl->FORNECEDOR_ID = $dados['pes_id'];
        $tpl->LOTE = $dados['etq_lote'];
        $tipocontagem=$dados['protip_codigo'];
        if ($tipocontagem==2)
            $tpl->QTD = number_format($dados['etq_quantidade'], 3, ',', '.');
        else
            $tpl->QTD = number_format($dados['etq_quantidade'], 0, '', '.');
        $tpl->SIGLA = $dados['protip_sigla'];
        
        if ($dados['etq_validade'] == "0000-00-00")
            $tpl->DATAVENCIMENTO = "";
        else
            $tpl->DATAVENCIMENTO = converte_data($dados['etq_validade']);
        //Testa a validade do produto
        $dataatual = date("Y-m-d");
        $validade = $dados['etq_validade'];
        if ($validade == "0000-00-00") {
            $tpl->VALIDADE = "indefinido";
        } else {
            $saldo = diferenca_data($dataatual, $validade, 'D') . " dias";
            if ($saldo == 0)
                $tpl->VALIDADE = "hoje";
            else if ($saldo == 1)
                $tpl->VALIDADE = "amanhã";
            else
                $tpl->VALIDADE = $saldo;
        }

        if ($saldo < 0) {
            $tpl->VENCEU = "tabelalinhavermelha";
        } else {
            $tpl->VENCEU = "";
        }

        $tpl->block("BLOCK_LISTA");
    }
} else {
    $tpl->block("BLOCK_LISTA_NADA");
}

$tpl->show();
include "rodape.php";
?>
