<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "saidas";
include "includes.php";

$codigo=$_GET["codigo"];
$permitevendaeditarreferencia=$permiteedicaoreferencianavenda;


if ($codigo=="") {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Produto não selecionado!</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_FECHAR");
    $tpl6->show();
    exit;
}


//Pega os dados para popular os campos com os dados atuais
$sql="SELECT * FROM produtos WHERE pro_codigo=$codigo";
if (!$query= mysql_query($sql)) die("ERRO SQL: ".mysql_error ());
while($dados=  mysql_fetch_assoc($query)) {
    $produto_nome=$dados["pro_nome"];
    $referencia=$dados["pro_referencia"];
}


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PRODUTOS";
$tpl_titulo->SUBTITULO = "ATUALIZAR REFERENCIA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos.png";
$tpl_titulo->show();


if ($permitevendaeditarreferencia!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>PERMITIR EDITAR REFERENCIA DURANTE VENDA.</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_FECHAR");
    $tpl6->show();
    exit;
}




$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

$tpl->LINK_DESTINO="produto_editar_referencia2.php?modal=1&codigo=$codigo";
$tpl->LINK_TARGET="";

//Nome
$tpl->TITULO="Produto";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="produto";
$tpl->CAMPO_VALOR="$produto_nome";
$tpl->CAMPO_TAMANHO="60";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Referencia
$tpl->TITULO="Referência Atual";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="referencia";
$tpl->CAMPO_VALOR="$referencia";
$tpl->CAMPO_TAMANHO="30";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Local
$tpl->TITULO="Referência Nova";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="referencia_nova";
$tpl->CAMPO_VALOR="$referencia_nova";
$tpl->CAMPO_TAMANHO="30";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_FOCO");
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
//$tpl->CAMPO_ESTILO="";
//$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Botão Salvar
$tpl->block("BLOCK_BOTAO_SALVAR");
$tpl->block("BLOCK_BOTOES");

$tpl->show();


include "rodape.php";
?>
