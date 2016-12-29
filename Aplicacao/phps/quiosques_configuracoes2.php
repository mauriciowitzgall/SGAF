<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
$tipopagina="quiosque_configuracao";
require "login_verifica.php";
include "includes.php";


$quiosque = $_POST['quiosque'];
$usamodulofiscal = $_POST['usamodulofiscal'];
$crtnfe = $_POST['crtnfe'];
$serienfe = $_POST['serienfe'];
$tipoimpressaodanfe = $_POST['tipoimpressaodanfe'];
$ambientenfe = $_POST['ambientenfe'];
$versaonfe = $_POST['versaonfe'];

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
$tpl_notificacao->DESTINO = "quiosques.php";

$sql = "
UPDATE
    quiosques_configuracoes
SET            
    quicnf_usamodulofiscal='$usamodulofiscal',
    quicnf_crtnfe='$crtnfe',
    quicnf_serienfe='$serienfe',
    quicnf_tipoimpressaodanfe='$tipoimpressaodanfe',
    quicnf_ambientenfe='$ambientenfe',
    quicnf_versaonfe='$versaonfe'
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
