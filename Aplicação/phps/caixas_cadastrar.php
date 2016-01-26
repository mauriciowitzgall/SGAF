<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

$operacao=$_GET["operacao"];
$codigo=$_GET["codigo"];
if ($operacao=="ver")
//Pega os dados para popular os campos com os dados atuais do caixa
$sql="SELECT * FROM caixas WHERE cai_codigo=$codigo";
if (!$query= mysql_query($sql)) die("ERRO SQL: ".mysql_error ());
while($dados=  mysql_fetch_assoc($query)) {
    $nome=$dados["cai_nome"];
    $local=$dados["cai_local"];
}



//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixas.png";
$tpl_titulo->show();

$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

$tpl->LINK_DESTINO="caixas_cadastrar2.php";
$tpl->LINK_TARGET="";

//Nome
$tpl->TITULO="Nome";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="nome";

$tpl->CAMPO_VALOR="$nome";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_FOCO");
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
//$tpl->CAMPO_ESTILO="";
//$tpl->block("BLOCK_CAMPO_ESTILO");
if ($operacao=="ver")
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Local
$tpl->TITULO="Local";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="local";
$tpl->CAMPO_VALOR="$local";
$tpl->CAMPO_TAMANHO="30";
$tpl->CAMPO_QTD_CARACTERES="";
//$tpl->block("BLOCK_CAMPO_FOCO");
//$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
//$tpl->CAMPO_ESTILO="";
//$tpl->block("BLOCK_CAMPO_ESTILO");
if ($operacao=="ver")
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


// PARA EDIÇAO
//Operação
$tpl->CAMPOOCULTO_VALOR="$operacao";
$tpl->CAMPOOCULTO_NOME="operacao";
$tpl->block("BLOCK_CAMPOSOCULTOS");
//Codigo
$tpl->CAMPOOCULTO_VALOR="$codigo";
$tpl->CAMPOOCULTO_NOME="codigo";
$tpl->block("BLOCK_CAMPOSOCULTOS");


if ($operacao=="ver") {
    $tpl->block("BLOCK_BOTAO_VOLTAR");

} if ($operacao=="cadastrar") {
    //Botão Salvar
    $tpl->block("BLOCK_BOTAO_SALVAR");

    //Botão Cancelar
    $tpl->BOTAO_LINK="caixas.php";
    $tpl->block("BLOCK_BOTAO_CANCELAR");
}

$tpl->block("BLOCK_BOTOES");

$tpl->show();
include "rodape.php";
?>
