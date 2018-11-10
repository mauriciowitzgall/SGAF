<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_consumidores_grupos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "pessoas";
include "includes.php";

$modal=$_GET['modal'];
$operacao=$_GET["operacao"];
$codigo=$_GET["codigo"];
//Pega os dados para popular os campos com os dados atuais 
if (($operacao=="editar")||($operacao=="ver")) {
    $sql="SELECT * FROM consumidores_grupos WHERE congru_codigo=$codigo";
    if (!$query= mysql_query($sql)) die("ERRO SQL: ".mysql_error ());
    while($dados=  mysql_fetch_assoc($query)) {
        $nome=$dados["congru_nome"];        
    }
} else { //cadastro novo
    $nome="";
}


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "GRUPOS DE CONSUMIDORES";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones2";
$tpl_titulo->NOME_ARQUIVO_ICONE = "consumidor.png";
$tpl_titulo->show();


$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

$tpl->LINK_DESTINO="consumidores_grupos_cadastrar2.php?modal=$modal";
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
    else $tpl->BOTAO_LINK="consumidores_grupos.php";
    $tpl->block("BLOCK_BOTAO_CANCELAR");
} 

$tpl->block("BLOCK_BOTOES");

$tpl->show();
include "rodape.php";
?>
