<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
$tipopagina="quiosque_configuracao";
require "login_verifica.php";
include "includes.php";

$usamodulofiscal = $_POST['usamodulofiscal'];
$quiosque = $_POST['quiosque'];


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "QUIOSQUE";
$tpl_titulo->SUBTITULO = "CONFIGURACOES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques_configuracoes.png";
$tpl_titulo->show();


//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "inicio.php";

$sql = "
UPDATE
    quiosques_configuracoes
SET            
    quicnf_usamodulofiscal=$usamodulofiscal
WHERE
    quicnf_quiosque=$quiosque
";
if (!mysql_query($sql))
    die("Erro: " . mysql_error());
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_EDITADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();

?>
