<?php

require "login_verifica.php";
if ($permissao_saidas_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "pagamentos";
include "includes2.php";

$pagamento = $_GET["numero"];
$saida = $_GET["saida"];



//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PAGAMENTOS";
$tpl_titulo->SUBTITULO = "EXCLUSÃO DE PAGAMENTOS DE UMA VENDA À RECEBER";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "saidas_pagamentos3.png";
$tpl_titulo->show();


$datahoraatual=date("Y-m-d H:i:s");


//Inicio da exclusão das saidas
$tpl = new Template("templates/notificacao.html");
$tpl->ICONES = $icones;
$tiposaida = $_GET["tiposaida"];
$tpl->DESTINO = "saidas_pagamentos.php?saida=$saida";





//Elimina o item de pagamento
$sql_del = "DELETE FROM saidas_pagamentos WHERE saipag_codigo = $pagamento";
if (!$query_del = mysql_query($sql_del)) die("Erro de SQL1:" . mysql_error());
//echo "<br>Removeu ITEM de PAGAMENTO<br>";
    
   

//Eliminar o registro de entrada de caixa gerado automaticamente
$sql_del = "DELETE FROM caixas_entradassaidas WHERE caientsai_saidapagamento=$pagamento";
if (!$query_del = mysql_query($sql_del)) die("Erro de SQL2:" . mysql_error());
//echo "<br>Removeu a entrada de caixa<br>";      


//Avalia se com esta remoção o consumidor aainda fica devendo algo, se sim então atualizar a venda dizendo que não está quitado 
$sql="SELECT sum(saipag_valor) as total_pago FROM saidas_pagamentos WHERE saipag_saida=$saida";
if (!$query = mysql_query($sql)) die("Erro de SQL3:" . mysql_error());
$dados=mysql_fetch_assoc($query);
$total_pago=$dados["total_pago"];


$sql="SELECT * FROM saidas WHERE sai_codigo=$saida";
if (!$query = mysql_query($sql)) die("Erro de SQL4:" . mysql_error());
$dados=mysql_fetch_assoc($query);
$totalliquido=$dados["sai_totalliquido"];


if ($total_pago >= $totalliquido) { //O consumidor não deve nada, permanece quitado.

} else { //Com esta remoção o consumidor fica devendo dinheiro, logo não esta mais quitado
	$sql="UPDATE saidas SET sai_areceberquitado=0 WHERE sai_codigo=$saida" ;
	if (!$query = mysql_query($sql)) die("Erro de SQL5:" . mysql_error());
}


$tpl->block("BLOCK_CONFIRMAR");
$tpl->block("BLOCK_APAGADO");
$tpl->block("BLOCK_BOTAO");
$tpl->show();

include "rodape.php";
?>
