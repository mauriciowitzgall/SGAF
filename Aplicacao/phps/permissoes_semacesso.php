<?php
require "login_verifica.php";
include "includes.php";

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "saidas.php";

$tpl_notificacao->block("BLOCK_ATENCAO");
$tpl_notificacao->block("BLOCK_NAOTEMPERMISSAO");
$tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
$tpl_notificacao->show();
?>
