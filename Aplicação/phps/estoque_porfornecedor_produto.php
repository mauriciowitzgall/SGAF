<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_estoque_ver <> 1) {
    header("Location: permissoes_semacesso.php");    
    exit;
}



$tipopagina = "estoque";
include "includes.php";
$tpl = new Template("estoque_porfornecedor_produto.html");
$tpl->ICONES_CAMINHO = "$icones";

$valor_total_geral=0;
$fornecedor_codigo=$_GET["fornecedor"];
if (($usuario_grupo==5)&&($fornecedor_codigo!=$usuario_codigo)) {
    $fornecedor_codigo=$usuario_codigo;
}


$sql="SELECT pes_nome FROM pessoas WHERE pes_codigo='$fornecedor_codigo'";
$query= mysql_query($sql);
if (!$query) die("Erro de SQL".mysql_error ());
while ($dados=mysql_fetch_array($query)) {
    $tpl->FORNECEDOR_NOME=$dados["pes_nome"];
}

//SQL principal
$sql1 = "
SELECT 
    pro_nome, sum(etq_quantidade) as qtdtot, round(sum(etq_valorunitario*etq_quantidade),2) as valtot, protip_sigla,etq_fornecedor,etq_produto, count(etq_fornecedor) as lotes, protip_codigo
FROM
    estoque
    join produtos on (etq_produto=pro_codigo)
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
WHERE
    etq_quiosque='$usuario_quiosque'  and
    etq_fornecedor= '$fornecedor_codigo'
GROUP BY
    etq_produto
ORDER BY
    pro_nome
";


//Pagina��o
$query = mysql_query($sql1);
if (!$query)
    die("Erro SQL Principal Pagina��o:" . mysql_error());
$linhas = mysql_num_rows($query);
$valor_total_geral=0;
while ($dados= mysql_fetch_assoc($query)) {
    $valor_total_geral=$valor_total_geral+$dados["valtot"];
}
$tpl->VALOR_TOTAL_GERAL = "R$ ".number_format($valor_total_geral,2,',','.');            
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
$sql1 = $sql1 . " LIMIT $comeco,$por_pagina ";
if ($paginaatual==$paginas) {
    $tpl->block("BLOCK_LISTA_RODAPE");
}$tpl->QUANTIDADE_TOTAL = number_format($dados1["qtdtot"],3,',','.');


$query1 = mysql_query($sql1);
if (!$query1)
    die("Erro: " . mysql_error());
$linhas1 = mysql_num_rows($query1);
if ($linhas1 != "") {
    $cont=0;
    while ($dados1 = mysql_fetch_array($query1)) {
        $cont++;
        $tpl->PRODUTO_NOME = $dados1['pro_nome'];
        $tpl->PRODUTO_CODIGO = $dados1['etq_produto'];
        $tpl->FORNECEDOR_CODIGO = $dados1['etq_fornecedor'];
        $tpl->LOTES = $dados1['lotes'];
        $tipocontagem=$dados1['protip_codigo'];
        if (($tipocontagem==2)||($tipocontagem==3))
            $tpl->QUANTIDADE_TOTAL = number_format($dados1["qtdtot"],3,',','.');
        else 
            $tpl->QUANTIDADE_TOTAL = number_format($dados1["qtdtot"],0,'','.');
        $tpl->SIGLA = $dados1['protip_sigla'];
        $tpl->VALOR_TOTAL = "R$ ".number_format($dados1["valtot"],2,',','.');        
        $tpl->block("BLOCK_LISTA");
    }
    
}
else {    
    $tpl->block("BLOCK_LISTA_NADA");
}

if ($usuario_grupo!=5) 
    $tpl->block("BLOCK_VOLTAR");  

$tpl->show();
include "rodape.php";
?>
