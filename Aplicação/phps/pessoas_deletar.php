<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_pessoas_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "pessoas";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PESSOAS";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "pessoas.png";
$tpl_titulo->show();

//Inicio da exclusão de entradas
$codigo = $_GET["codigo"];

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->DESTINO = "pessoas.php";
$tpl_notificacao->ICONES = $icones;


//Verifica se ele é presidente
$sql = "SELECT * FROM cooperativas WHERE coo_presidente=$codigo";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "Esta pessoa é presidente da sua cooperativa";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}

//Verifica se o esta pessoa é supervisor de algum quiosque
$sql3 = "SELECT DISTINCT qui_nome FROM quiosques join quiosques_supervisores on (qui_codigo=quisup_quiosque) WHERE quisup_supervisor=$codigo";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro: 2" . mysql_error());
$linhas3 = mysql_num_rows($query3);
if ($linhas3 > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "Esta pessoa é supervisor de algum quiosque";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}


//Verifica se o esta pessoa é vendedor de algum quiosque
$sql2 = "SELECT * FROM quiosques_vendedores WHERE quiven_vendedor=$codigo";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro: 1" . mysql_error());
$linhas2 = mysql_num_rows($query2);
if ($linhas2 > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "Esta pessoa é vendedor de algum quiosque";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}


//Verifica se o esta pessoa é fornecedor de algum quiosque
$sql4 = "SELECT DISTINCT qui_nome FROM quiosques join entradas on (ent_quiosque=qui_codigo) WHERE ent_fornecedor=$codigo";
$query4 = mysql_query($sql4);
if (!$query4)
    die("Erro: 3" . mysql_error());
$linhas4 = mysql_num_rows($query4);
if ($linhas4 > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "Esta pessoa é fornecedor de algum quiosque";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}


//Verifica se ele é ja participou de entradas como fornecedor ou vendedor
$sql = "SELECT * FROM entradas WHERE ent_supervisor=$codigo OR ent_fornecedor=$codigo";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "Esta pessoa já realizou operações como vendedor ou fornecedor de algum quiosque";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}

//Verifica se ele é ja participou de saidas como consumidor ou vendedor
$sql = "SELECT * FROM saidas WHERE sai_vendedor=$codigo OR sai_consumidor=$codigo";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: " . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "Esta pessoa já participou de alguma saída como vendedor ou consumidor de algum quiosque!";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
}

//Deleta primeiro as referencias dessa pessoa
$sql2 = "DELETE FROM mestre_pessoas_tipo WHERE mespestip_pessoa='$codigo'";
$query2 = mysql_query($sql2);
if (!$query2) {
    die("Erro SQL: " . mysql_error());
}
$sql3 = "DELETE FROM pessoas WHERE pes_codigo='$codigo'";
$query3 = mysql_query($sql3);
if (!$query3) {
    die("Erro SQL: " . mysql_error());
}
$tpl_notificacao->MOTIVO_COMPLEMENTO = "";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_APAGADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();

include "rodape.php";
?>
