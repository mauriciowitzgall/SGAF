<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_entradas_etiquetas <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "entradas";
include "includes.php";

$entrada = $_GET["codigo"];

//Template de TÃ­tulo e Sub-tÃ­tulo
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ENTRADAS";
$tpl_titulo->SUBTITULO = "IMPRESSÃƒO DE ETIQUETAS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "entradas.png";
$tpl_titulo->show();

$tpl = new Template("entradas_imprimir_etiquetas.html");

$sql = "SELECT * FROM entradas join pessoas on (ent_fornecedor=pes_codigo) WHERE ent_codigo=$entrada";
$query = mysql_query($sql);
if (!$query)
    die("Erro de SQL 1:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $fornecedor_nome = $dados["pes_nome"];
    $data = $dados["ent_datacadastro"];
    $hora = $dados["ent_horacadastro"];
    $produto = $dados["entpro_produto"];

    $tpl->ENTRADAS_CODIGO = $entrada;
    $tpl->ENTRADAS_DATA = converte_data($data);
    $tpl->ENTRADAS_HORA = converte_hora($hora);
    $tpl->ENTRADAS_FORNECEDOR = $fornecedor_nome;
    //Listagem
    $i = 1;
    $sql2 = "
    SELECT
        *
    FROM
        entradas_produtos
        join produtos on (entpro_produto=pro_codigo)
        join produtos_tipo on (pro_tipocontagem=protip_codigo)
    WHERE
        entpro_entrada=$entrada
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro de SQL 1:" . mysql_error());
    while ($dados2 = mysql_fetch_assoc($query2)) {
        $tpl->ENTRADAS_NUMERO = $i++;
        $tpl->ENTRADAS_PRODUTO = $dados2["entpro_produto"];
        $tpl->ENTRADAS_PRODUTO_NOME = $dados2["pro_nome"];
        $tpl->ENTRADAS_QTD = number_format($dados2['entpro_quantidade'], 2, ',', '') . " " . $dados2['protip_sigla'];
        $tpl->ENTRADAS_LOCAL = $dados2["entpro_local"];
        $tpl->PRODUTO_COD = $dados2["entpro_produto"];
        $tpl->block("BLOCK_LISTA");
    }
}
$tpl->show();






