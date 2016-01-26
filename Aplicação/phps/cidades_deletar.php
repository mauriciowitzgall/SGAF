<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_cidades_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "locais";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CIDADES";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "locais.png";
$tpl_titulo->show();

//Inicio da exclusão de entradas
$codigo = $_GET["codigo"];

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "cidades.php";

//Verifica se a cidade esta sendo usada em quiosque
$sql = "SELECT * FROM quiosques WHERE qui_cidade=$codigo";
$query = mysql_query($sql);
if (!$query) {
    die("Erro SQL: 3" . mysql_error());
}
$linhas = mysql_num_rows($query);
if ($linhas > 0) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "quiosques";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOAPAGADO");
    $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
} else {
    //Verifica se a cidade esta sendo usada em pessoas
    $sql = "SELECT * FROM pessoas WHERE pes_cidade=$codigo";
    $query = mysql_query($sql);
    if (!$query) {
        die("Erro SQL: 4".mysql_error());
    }
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "pessoas";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOAPAGADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_EMUSO");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
    } else {
        //Pode excluir
        $sql2 = "DELETE FROM cidades WHERE cid_codigo='$codigo'";
        $query2 = mysql_query($sql2);
        if (!$query2) {
            die("Erro SQL: 5" . mysql_error());
        }
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_APAGADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
}
include "rodape.php";
?>
