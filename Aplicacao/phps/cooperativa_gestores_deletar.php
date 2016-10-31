<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_cooperativa_gestores_gerir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "quiosques";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "GESTORES";
$tpl_titulo->SUBTITULO = "LISTA DE GESTORES DA COOPERATIVA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "cooperativa_gestores.png";
$tpl_titulo->show();

//Inicio da exclusão
$cooperativa = $usuario_cooperativa;
$gestor = $_GET["gestor"];

//Limpa o grupo de permissões do usuário da pessoa
$sql = "
UPDATE
    pessoas
SET
    pes_grupopermissoes=''           
WHERE
    pes_codigo = '$gestor'
";
if (!mysql_query($sql))
    die("Erro: " . mysql_error());

//Excluir a pessoa da funnção de gestor
$sql2 = "DELETE FROM cooperativa_gestores WHERE cooges_gestor='$gestor' and cooges_cooperativa=$cooperativa";
$query2 = mysql_query($sql2);
if (!$query2) {
    die("Erro SQL: " . mysql_error());
}

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->MOTIVO_COMPLEMENTO = "";
$tpl_notificacao->DESTINO = "cooperativa_gestores.php";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_APAGADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();

include "rodape.php";
?>
