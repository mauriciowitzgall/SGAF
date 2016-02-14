<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_entradas_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "entradas";
include "includes.php";


$tpl = new Template("entradas_cadastrar.html");
$tpl->ICONES_CAMINHO = "$icones";
$tpl->SUBTITULO = "DETALHES";

//Pega todos os dados da entrada a partir do codigo
$entrada = $_GET['codigo'];
$sql = "
    SELECT * 
    FROM entradas 
    join pessoas on (ent_fornecedor=pes_codigo)
    WHERE ent_codigo=$entrada
";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL" . mysql_error());
$dados = mysql_fetch_assoc($query);
$fornecedor = $dados["ent_fornecedor"];
$supervisor = $dados["ent_supervisor"];
$tiponegociacao = $dados["ent_tiponegociacao"];

if (($usuario_grupo == 5) && ($usuario_codigo != $fornecedor)) {
    header("Location: permissoes_semacesso.php");
}


$tpl->block("BLOCK_CABECALHO_OPERACAO");

//Codigo da Entrada
$tpl->SELECT_DESABILITADO = " disabled ";
$tpl->ENTRADA = $entrada;
$tpl->block("BLOCK_SELECT_ENTRADA");

//Tipo negociação
$sql = "
    SELECT tipneg_codigo, tipneg_nome 
    FROM tipo_negociacao
    JOIN entradas ON (ent_tiponegociacao=tipneg_codigo)
    WHERE ent_codigo=$entrada
";
$query = mysql_query($sql);
//$tpl->SELECT_DESABILITADO = " disabled ";
while ($dados = mysql_fetch_array($query)) {
    $tpl->OPTION_VALOR = $dados[0];
    $tpl->OPTION_TEXTO = $dados[1];
    if ($dados[0] == $tiponegociacao) {
        $tpl->OPTION_SELECIONADO = " SELECTED ";
    } else {
        $tpl->OPTION_SELECIONADO = "";
    }
    $tpl->block("BLOCK_OPTIONS_TIPONEGOCIACAO");
}
$tpl->block("BLOCK_SELECT_TIPONEGOCIACAO");


//Fornecedor
$sql = "SELECT pes_codigo,pes_nome FROM pessoas inner join mestre_pessoas_tipo on (pes_codigo=mespestip_pessoa) WHERE pes_codigo=$fornecedor";
$query = mysql_query($sql);
$tpl->SELECT_DESABILITADO = " disabled ";
while ($dados = mysql_fetch_array($query)) {
    $tpl->OPTION_VALOR = $dados[0];
    $tpl->OPTION_TEXTO = "$dados[1]";
    if ($dados[0] == $fornecedor) {
        $tpl->OPTION_SELECIONADO = " SELECTED ";
    } else {
        $tpl->OPTION_SELECIONADO = "";
    }
    $tpl->block("BLOCK_OPTIONS_FORNECEDOR");
}
$tpl->block("BLOCK_SELECT_FORNECEDOR");


//Supervisor
$sql = "SELECT pes_codigo,pes_nome FROM pessoas inner join mestre_pessoas_tipo on (pes_codigo=mespestip_pessoa) WHERE pes_codigo=$supervisor";
$query = mysql_query($sql);
$tpl->SELECT_DESABILITADO = " disabled ";
while ($dados = mysql_fetch_array($query)) {
    $tpl->OPTION_VALOR = $dados[0];
    $tpl->OPTION_TEXTO = "$dados[1]";
    if ($dados[0] == $supervisor) {
        $tpl->OPTION_SELECIONADO = " SELECTED ";
    } else {
        $tpl->OPTION_SELECIONADO = "";
    }
    $tpl->block("BLOCK_OPTIONS_SUPERVISOR");
}
$tpl->block("BLOCK_SUPERVISOR");

$tpl->block("BLOCK_ENTER");
$tpl->block("BLOCK_HR");

$tpl->block("BLOCK_DATAHORA");

//Cabeçalho
if ($tiponegociacao == 1) {
    $tpl->block("BLOCK_VENDA_VALUNI_CABECALHO");
    $tpl->block("BLOCK_VENDA_CABECALHO");
} else if ($tiponegociacao == 2) {
    $tpl->block("BLOCK_CUSTO_CABECALHO");
    $tpl->block("BLOCK_VENDA_CABECALHO");
}



//Pega todos os dados da listagem de produtos da entrada
$sql = "
SELECT
    pro_nome, 
    protip_nome, 
    entpro_quantidade,
    pro_codigo,
    entpro_valorunitario,
    entpro_validade,
    entpro_local,
    ent_datacadastro,
    ent_horacadastro,
    entpro_numero,
    protip_sigla,
    protip_codigo,
    ent_tiponegociacao,
    entpro_valunicusto
FROM
    entradas_produtos
    join entradas on (ent_codigo=entpro_entrada) 
    join produtos on (entpro_produto=pro_codigo) 
    join produtos_tipo on (protip_codigo=pro_tipocontagem)
WHERE
    ent_codigo=$entrada
ORDER BY 
    entpro_numero DESC
";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL" . mysql_error());


while ($dados = mysql_fetch_array($query)) {
    $validade = $dados[5];
    $tpl->ENTRADAS_NUMERO = $dados[9];
    $tpl->ENTRADAS_PRODUTO = $dados[3];
    $tpl->ENTRADAS_PRODUTO_NOME = $dados[0];
    $tpl->ENTRADAS_DATA = converte_data($dados[7]);
    $tpl->ENTRADAS_HORA = converte_hora($dados[8]);
    $tpl->SIGLA = $dados["protip_sigla"];
    $tipocontagem = $dados["protip_codigo"];
    if (($tipocontagem == 2)||($tipocontagem==3))
        $tpl->ENTRADAS_QTD = number_format($dados[2], 3, ',', '.');
    else
        $tpl->ENTRADAS_QTD = number_format($dados[2], 0, '', '.');
    if ($tiponegociacao == 2) {
        $tpl->ENTRADAS_VALORUNI_CUSTO = "R$ " . number_format($dados['entpro_valunicusto'], 2, ',', '.');
        $tpl->ENTRADAS_VALOR_TOTAL_CUSTO = "R$ " . number_format($dados['entpro_quantidade'] * $dados['entpro_valunicusto'], 2, ',', '.');
        $tpl->block("BLOCK_CUSTO");
    } else if ($tiponegociacao == 1) {
        $totalvenda = $dados[4] * $dados[2];
        $tpl->ENTRADAS_VALORUNI = "R$ " . number_format($dados[4], 2, ',', '.');
        $tpl->block("BLOCK_VENDA_VALUNI");
    }

    $tpl->ENTRADAS_VENDA_TOTAL = "R$ " . number_format($dados['2'] * $dados['4'], 2, ',', '.');
    $tpl->block("BLOCK_VENDA");

    $tpl->PRODUTO = $dados[3];
    $numero = $dados[9];
    $tpl->ENTRADAS_VALIDADE = converte_data($validade);
    $tpl->IMPRIMIR_LINK = "entradas_etiquetas.php?lote=$entrada&numero=$numero";
    $tpl->IMPRIMIR = $icones . "etiquetas.png";

    $tpl->block("BLOCK_LISTA_OPERACAO_ETIQUETAS");
    $tpl->block("BLOCK_LISTA_OPERACAO");
    $tpl->block("BLOCK_LISTA");
}

if ($tiponegociacao == 2) {
    $tpl->block("BLOCK_CUSTO_RODAPE");
}

$tpl->block("BLOCK_VENDA_VALUNI_RODAPE");

//Calcula o total de venda
if ($tiponegociacao == 1) {
    $sql11 = "SELECT round(sum(entpro_valtot),2) FROM entradas_produtos WHERE entpro_entrada=$entrada";
    $query11 = mysql_query($sql11);
    $dados11 = mysql_fetch_array($query11);
    $tot11 = "R$ " . number_format($dados11[0], 2, ',', '.');
    $tpl->TOTAL_VENDA = "$tot11";
    $tpl->block("BLOCK_VENDA_RODAPE");
}

//Calcula o valor total de custo geral da entrada
$sql9 = "SELECT round(sum(entpro_valunicusto*entpro_quantidade),2) FROM entradas_produtos WHERE entpro_entrada=$entrada";
$query9 = mysql_query($sql9);
while ($dados9 = mysql_fetch_array($query9)) {
    $tot9 = "R$ " . number_format($dados9[0], 2, ',', '.');
}
$tpl->TOTAL_CUSTO = "$tot9";


//Calcula o valor total de lucro da entrada
/*
 * $sql10 = "SELECT round(sum((entpro_valorunitario*entpro_quantidade)-(entpro_valunicusto*entpro_quantidade)),2) FROM entradas_produtos WHERE entpro_entrada=$entrada";
  $query10 = mysql_query($sql10);
  while ($dados10 = mysql_fetch_array($query10)) {
  $tot10 = "R$ " . number_format($dados10[0], 2, ',', '.');
  }
  $tpl->TOTAL_LUCRO = "$tot10";
 */

//Icone imprimir etiqueta
$tpl->block("BLOCK_LISTA_NADA_OPERACAO");

$tpl->block("BLOCK_BOTAO_VOLTAR");
$tpl->block("BLOCK_BOTAO_IMPRIMIR");
/* if ($tiponegociacao == 2)
  $tpl->block("BLOCK_BOTAO_IMPRIMIR_CUSTO"); */
$tpl->block("BLOCK_PASSO3");




$tpl->show();
include "rodape.php";
?>
