<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "produtos";
include "includes.php";

$modal=$_GET['modal'];
$operacao=$_GET["operacao"];
$codigo=$_GET["codigo"];
//Pega os dados para popular os campos com os dados atuais 
if (($operacao=="editar")||($operacao=="ver")) {
    $sql="SELECT * FROM produtos_recipientes WHERE prorec_codigo=$codigo";
    if (!$query= mysql_query($sql)) die("ERRO SQL: ".mysql_error ());
    while($dados=  mysql_fetch_assoc($query)) {
        $nome=$dados["prorec_nome"];
        $recipienteglobal=$dados["prorec_global"];
    }
} else { //cadastro novo
    $nome="";
}


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "RECIPIENTES";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "recipientes.png";
$tpl_titulo->show();




if ($recipienteglobal==1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Você só pode editar recipientes não globais, ou seja, os cadastrados por vocês mesmo!";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

$tpl->LINK_DESTINO="produtos_recipientes_cadastrar2.php?modal=$modal";
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

} else if (($operacao=="cadastrar")||($operacao=="editar")) {
    
    //Botão Salvar
    $tpl->block("BLOCK_BOTAO_SALVAR");

    //Botão Cancelar
    if ($modal==1) $tpl->BOTAO_LINK="javascript:window.close(0);";
    else $tpl->BOTAO_LINK="produtos_recipientes.php";
    $tpl->block("BLOCK_BOTAO_CANCELAR");
} 

$tpl->block("BLOCK_BOTOES");

$tpl->show();
include "rodape.php";
?>
