<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_entradas_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "entradas";
include "includes2.php";


$tpl = new Template("entradas_cadastrar.html");
$tpl->ICONES_CAMINHO = "$icones";
$tpl->SUBTITULO = "DETALHES";

//Pega todos os dados da entrada a partir do codigo
$entrada = $_GET['codigo'];
$sql = "SELECT * FROM entradas join pessoas on (ent_fornecedor=pes_codigo)WHERE ent_codigo=$entrada";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $fornecedor = $dados["ent_fornecedor"];
    $supervisor = $dados["ent_supervisor"];
}



//Codigo da Entrada
$tpl->SELECT_DESABILITADO = " disabled ";
$tpl->ENTRADA = $entrada;
$tpl->block("BLOCK_SELECT_ENTRADA");

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

//supervisor
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

$tpl->block("BLOCK_DATAHORA");


//Pega todos os dados da listagem de produtos da entrada
$sql = "
SELECT
    pro_nome, protip_nome, entpro_quantidade,pro_codigo,entpro_valorunitario,entpro_validade,entpro_local,ent_datacadastro,ent_horacadastro,entpro_numero,protip_sigla
FROM
    entradas_produtos
    join entradas on (ent_codigo=entpro_entrada) 
    join produtos on (entpro_produto=pro_codigo) 
    join produtos_tipo on (protip_codigo=pro_tipocontagem)
WHERE
    ent_codigo=$entrada
";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL" . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $tpl->ENTRADAS_NUMERO = $dados[9];;
    $tpl->ENTRADAS_PRODUTO_NOME = $dados[0];
    $tpl->ENTRADAS_LOCAL = $dados[6];
    $tpl->ENTRADAS_DATA = converte_data($dados[7]);
    $tpl->ENTRADAS_HORA = converte_hora($dados[8]);
    $tpl->SIGLA = $dados["protip_sigla"];
    $tpl->ENTRADAS_QTD = number_format($dados[2], 2, ',', '.');
    $tpl->ENTRADAS_VALORUNI = "R$ " . number_format($dados[4], 2, ',', '.');
    if ($dados['5'] != "0000-00-00")
        $tpl->ENTRADAS_VALIDADE = converte_data($dados['5']);
    else
        $tpl->ENTRADAS_VALIDADE = "";
    $tpl->ENTRADAS_VALOR_TOTAL = "R$ " . number_format($dados['2'] * $dados['4'], 2, ',', '.');
    $tpl->PRODUTO = $dados[3];
    $numero = $dados[9];

    $tpl->block("BLOCK_LISTA");
}

//Calcula o valor total geral da entrada
$sql8 = "SELECT round(sum(entpro_valorunitario*entpro_quantidade),2) FROM `entradas_produtos` WHERE entpro_entrada=$entrada";
$query8 = mysql_query($sql8);
while ($dados8 = mysql_fetch_array($query8)) {
    $tot8 = "R$ " . number_format($dados8[0], 2, ',', '.');
}
$tpl->TOTAL_ENTRADA = "$tot8";
$tpl->block("BLOCK_PASSO3");
$tpl->show();

?>
