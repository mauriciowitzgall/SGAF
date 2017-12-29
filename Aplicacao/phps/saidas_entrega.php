<?php

//Verifica permissão tela
require "login_verifica.php";


//Menu
$tipopagina = "";
include "includes2.php";


//Pega dados
$saida = $_GET["saida"];

//Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ENTREGAS";
$tpl_titulo->SUBTITULO = "SITUAÇÃO DA ENTREGA";
$tpl_titulo->NOME_ARQUIVO_ICONE = "entregas.png";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->show();

//Pega dados
$sql="SELECT * FROM saidas WHERE sai_codigo=$saida AND sai_quiosque=$usuario_quiosque";
if (!$query = mysql_query($sql)) die("Erro SQL 1: " . mysql_error()."");
$dados=mysql_fetch_assoc($query);
$entrega=$dados["sai_entrega"];
$situacao_atual=$dados["sai_entrega_concluida"];
if ($situacao_atual==1) $situacao_nova=0;
if ($situacao_atual==0) $situacao_nova=1;
if (($fazentregas==1)&&($entrega==1)) {
	
	//Atualiza situação
	$sql="UPDATE saidas SET sai_entrega_concluida=$situacao_nova WHERE sai_codigo=$saida";
	if (!$query = mysql_query($sql)) die("Erro SQL 2: " . mysql_error()."");	

	//Notificação
	$tpl6 = new Template("templates/notificacao.html");
	$tpl6->COLUNA_TAMANHO="100%";
	$tpl6->ICONE_ALINHAMENTO="center";
	$tpl6->PASTA="$icones";
	if ($situacao_nova==0) $tpl6->ARQUIVO="entrega_atrasada.png";
	else $tpl6->ARQUIVO="entrega_realizada.png";
	$tpl6->TITULO="	";
	$tpl6->block("BLOCK_ICONE_PERSONALIZADO");
	$tpl6->ICONES = $icones;
	//$tpl6->block("BLOCK_NAOAPAGADO");
	if ($situacao_nova==0) $tpl6->MOTIVO = "<br>Esta entrega foi <b>CANCELADA</b>!<br><br>";
	else $tpl6->MOTIVO = "<br>Entrega <b>CONCLUÍDA</b>!<br><br>";
	$tpl6->block("BLOCK_MOTIVO");
	$tpl6->block("BLOCK_BOTAO_FECHAR");
	$tpl6->show();

		
}







?>

