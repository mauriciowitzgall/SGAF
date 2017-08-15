<?php

require "login_verifica.php";
if ($permissao_saidas_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "pagamentos";
include "includes.php";

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


$tpl->block("BLOCK_CONFIRMAR");
$tpl->block("BLOCK_APAGADO");
$tpl->block("BLOCK_BOTAO");
$tpl->show();

include "rodape.php";
?>
