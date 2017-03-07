<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if (($permissao_estoque_ver <> 1)||($usuario_grupo==5)) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina="estoque";
include "includes.php";

$tpl = new Template("estoque_fornecedores.html");
$tpl->ICONES_CAMINHO="$icones";
$produto=$_GET["produto"];
$qtd=0;
$valortot=0;

$sql="
SELECT
    pro_codigo, pro_nome, pes_codigo, pes_nome, sum(etq_quantidade) as qtd, protip_sigla, count(etq_fornecedor) as lotes, round(sum(etq_quantidade*etq_valorunitario),2) as total,pes_id,protip_codigo,pro_referencia, pro_tamanho, pro_cor, pro_descricao
FROM
    estoque
    join produtos on (pro_codigo=etq_produto)
    join pessoas on (etq_fornecedor=pes_codigo)
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
    join entradas on (etq_lote=ent_codigo)
WHERE
    etq_produto='$produto'  
    and ent_quiosque=$usuario_quiosque
GROUP BY
    pes_codigo
ORDER BY
    pes_nome

";
//Pagina��o
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Pagina��o:" . mysql_error());
$linhas = mysql_num_rows($query);
$valor_total_geral=0;
while ($dados= mysql_fetch_assoc($query)) {
    $valor_total_geral=$valor_total_geral+$dados["total"];
    $valor_total_qtd=$valor_total_qtd+$dados["qtd"];
}
$tpl->VALOR_TOTAL = "R$ ".number_format($valor_total_geral,2,',','.');            
$tpl->QTD_TOTAL = number_format($valor_total_qtd,2,',','.');            
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
if ($paginaatual==$paginas) {
    $tpl->block("BLOCK_LISTA_RODAPE");
}





$query= mysql_query($sql);
if (!$query) die("Erro: ".mysql_error());

while ($dados=  mysql_fetch_array($query))
{
    $tpl->PRODUTO_CODIGO=$dados['pro_codigo'];
    $nome= $dados['pro_nome'];
    $referencia= $dados['pro_referencia'];
    $tamanho= $dados['pro_tamanho'];
    $cor= $dados['pro_cor'];
    $descricao= $dados['pro_descricao'];
    $nome2=" $nome $tamanho $cor $descricao ";
    $tpl->PRODUTO_NOME=$nome2;
    $tpl->FORNECEDOR_CODIGO=$dados['pes_codigo'];
    $tpl->FORNECEDOR_ID=$dados['pes_id'];
    $tpl->FORNECEDOR=$dados['pes_nome'];
    $tipocontagem=$dados['protip_codigo'];
    if (($tipocontagem==2)||($tipocontagem==3))
        $tpl->QUANTIDADE= number_format($dados['qtd'],3,',','.');
    else 
        $tpl->QUANTIDADE= number_format($dados['qtd'],0,'','.');
    $tpl->SIGLA=$dados['protip_sigla'];   
    $tpl->LOTES=$dados['lotes'];   
    $tpl->VALOR="R$ ".number_format($dados['total'],2,',','.') ;
    $qtdtot=$qtdtot+$dados['qtd'];
    $valortot=$valortot+$dados['total'];
    $tpl->block("BLOCK_LISTA");      
}
$tpl->QTD_TOTAL=  number_format($qtdtot,2,',','.');
$tpl->VALOR_TOTAL="R$ ".number_format($valortot,2,',','.');
$tpl->show();

?>
