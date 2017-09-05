<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_trocar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

$caixa=$_GET["codigo"];
$numero=$_GET["numero"];
$operacao=$_GET["operacao"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "ALTERAR CAIXA PADRÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixa_trocar.png";
$tpl_titulo->show();


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

//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "caixas.php";

if ($operacao=="desassociar") {
    $sql2="UPDATE pessoas SET pes_caixaoperacaonumero=null WHERE pes_codigo=$usuario_codigo";
} else {
    $sql2="UPDATE pessoas SET pes_caixaoperacaonumero=$numero WHERE pes_codigo=$usuario_codigo";
}

//Atualiza o caixa do usuario
$query2 = mysql_query($sql2);
if (!$query2) die("Erro de SQL 4:" . mysql_error());

$tpl_notificacao->MOTIVO_COMPLEMENTO = "";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->MOTIVO_COMPLEMENTO = "Caixa padrão alteradro com sucesso!";
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();



?>

