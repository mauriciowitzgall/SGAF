<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operacoes_abrir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

$operacao=$_GET["operacao"];
$caixa=$_GET["codigo"];
$datahoraatual=date("Y-m-d H:i:s");
$horaatual=date("H:i:s");
$dataatual=date("Y-m-d");


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS OPERACOES";
$tpl_titulo->SUBTITULO = "ABRIR CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixas_abrir.png";
$tpl_titulo->show();


$usacaixa=usamodulocaixa($usuario_quiosque);
if ($usacaixa!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

$tpl->JS_CAMINHO="caixas_operacoes_abrir.js"; 
$tpl->block("BLOCK_JS");

$tpl->LINK_DESTINO="caixas_operacoes_abrir2.php";
$tpl->LINK_TARGET="";

//Caixa
$tpl->TITULO="Caixa";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="nome";
$sql="SELECT cai_nome FROM caixas WHERE cai_codigo=$caixa";
if (!$query=mysql_query($sql)) die("Erro SQL 1: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$caixa_nome=$dados["cai_nome"];
$tpl->CAMPO_VALOR="$caixa_nome";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Operador
$tpl->TITULO="Operador";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="operador";
$tpl->CAMPO_VALOR="$usuario_nome";
$tpl->CAMPO_TAMANHO="30";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
//$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Data Abertura
$tpl->TITULO="Data Abertura";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="date";
$tpl->CAMPO_NOME="dataini";
$tpl->CAMPO_VALOR="$dataatual";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->CAMPO_ESTILO="width:140px";
$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
//$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->CAMPO_TIPO="time";
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->CAMPO_NOME="horaini";
$tpl->CAMPO_VALOR="$horaatual";
$tpl->CAMPO_ESTILO="width:110px";
$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
//$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Valor inicial
$tpl->TITULO="Valor Inicial";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="valorinicial";
$tpl->CAMPO_VALOR="";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_OBRIGATORIO"); //classe padrao
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Campo oculto
$tpl->CAMPOOCULTO_VALOR="$caixa";
$tpl->CAMPOOCULTO_NOME="caixa2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Campo oculto
$tpl->CAMPOOCULTO_VALOR="cadastrar";
$tpl->CAMPOOCULTO_NOME="operacao";
$tpl->block("BLOCK_CAMPOSOCULTOS");


//Botão Salvar
$tpl->block("BLOCK_BOTAO_SALVAR");

//Botão Cancelar
$tpl->BOTAO_LINK="caixas.php";
$tpl->block("BLOCK_BOTAO_CANCELAR");

$tpl->block("BLOCK_BOTOES");

$tpl->show();
include "rodape.php";
?>
