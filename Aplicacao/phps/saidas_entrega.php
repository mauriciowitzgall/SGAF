<?php

//Verifica permissão tela
require "login_verifica.php";


//Menu
$tipopagina = "";
include "includes.php";


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
$dataentrega=$dados["sai_dataentrega"];
$dataatual = date("Y-m-d");
$saldo = diferenca_data($dataatual, $dataentrega, 'D');
//echo "$dataatual / $dataentrega = $saldo";



$tpl = new Template("templates/notificacao.html");
$tpl->ICONES = $icones;
//$tpl->MOTIVO_COMPLEMENTO = "";
$tpl->COLUNA_TAMANHO="100%";
$tpl->ICONE_ALINHAMENTO="center";
$tpl->PASTA="$icones";

if (($situacao_atual == 0)&&($saldo<0)) { //Entrega atrasada > concluida
	$tpl->ARQUIVO="entrega_atrasada_para_concluida.png";
	$tpl->MOTIVO = "A entrega ainda não foi realizada! <br>";
	$tpl->PERGUNTA = "<br> CONFIRMAR ENTREGA?";     
} else if (($situacao_atual == 0)&&($saldo>=0)) { //Entrega pendente > concluida
	$tpl->ARQUIVO="entrega_pendente_para_concluida.png";
	$tpl->MOTIVO = "A entrega ainda não foi realizada! <br>";
	$tpl->PERGUNTA = "<br> CONFIRMAR ENTREGA?";
} else if (($situacao_atual == 1)&&($saldo<0)) { //entrega concluida > atrasada
	$tpl->ARQUIVO="entrega_concluida_para_atrasada.png";
	$tpl->MOTIVO = "A entrega já está concluída! <br>";
	$tpl->PERGUNTA = "<br> CANCELAR ENTREGA?";	
} else if (($situacao_atual == 1)&&($saldo>=0)) { //entrega concluida > pendente
	$tpl->ARQUIVO="entrega_concluida_para_pendente.png";
	$tpl->MOTIVO = "A entrega já está concluída! <br>";
	$tpl->PERGUNTA = "<br> CANCELAR ENTREGA?";
}
     

$tpl->TITULO="";
$tpl->block("BLOCK_MOTIVO");
$tpl->block("BLOCK_ICONE_PERSONALIZADO");
$tpl->LINK = "saidas_entrega2.php?saida=$saida";
$tpl->block("BLOCK_PERGUNTA");
$tpl->NAO_LINK = "saidas.php";
$tpl->block("BLOCK_BOTAO_NAO_LINK");
$tpl->block("BLOCK_BOTAO_SIMNAO");
$tpl->show();
exit;
            







?>

