<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_quiosque_definirsupervisores <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativas";
include "includes.php";

//Template de TÃ­tulo e Sub-tÃ­tulo
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "SUPERVISORES";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "../pessoas2/supervisor.png";
$tpl_titulo->show();

//Inicio da exclusão de entradas
$quiosque = $_GET["quiosque"];
$supervisor = $_GET["supervisor"];

//Limpa o grupo de permissÃµes do usuário da pessoa
$sql = "
UPDATE
    pessoas
SET
    pes_grupopermissoes=''           
WHERE
    pes_codigo = '$supervisor'
";
if (!mysql_query($sql))
    die("Erro: " . mysql_error());

//Excluir a pessoa da função de supervisor
$sql2 = "DELETE FROM quiosques_supervisores WHERE quisup_supervisor='$supervisor' and quisup_quiosque=$quiosque";
$query2 = mysql_query($sql2);
if (!$query2) {
    die("Erro SQL: " . mysql_error());
}

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->MOTIVO_COMPLEMENTO = "";
$tpl_notificacao->DESTINO = "supervisores.php?quiosque=$quiosque";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_APAGADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();

include "rodape.php";
?>
