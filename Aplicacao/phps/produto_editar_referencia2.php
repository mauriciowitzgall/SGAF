<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "produtos";
include "includes.php";


$codigo = $_REQUEST['codigo']; 
$referencia_nova = $_POST['referencia_nova'];
$datahoraatual=date("Y-m-d H:i:s");
$permitevendaeditarreferencia=$permiteedicaoreferencianavenda;

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
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

if (($codigo=="")||($referencia_nova=="")) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Produto não selecionado, ou referencia nova nao digitada!</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "produtos.php";

$sql = "
UPDATE
    produtos
SET            
    pro_referencia='$referencia_nova'
WHERE
    pro_codigo='$codigo'
";
if (!mysql_query($sql)) die("Erro SQL UPDATE: " . mysql_error());
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_EDITADO");
$tpl_notificacao->block("BLOCK_BOTAO_FECHAR");
$tpl_notificacao->show();

?>

