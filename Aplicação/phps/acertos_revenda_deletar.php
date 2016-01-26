<?php

require "login_verifica.php";
if ($quiosque_revenda <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "negociacoes";
include "includes.php";
include "controller/classes.php";

//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "FECHAMENTO DE REVENDAS";
$tpl_titulo->SUBTITULO = "EXCLUSÃO DE FECHAMENTOS DE REVENDAS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "revenda.png";
$tpl_titulo->show();

/*
  RESUMO
  Na exclusão de fechamentos devemos alterar todos aqueles produtos que foram vendidos e fechandos para 'não fechandos' ou 'nulos'. Feitos isso
  podemos então excluir o fechamento, mas devemos lembrar de excluir primeiro as taxas do fechamento para depois excluir
  o fechamento em questão, dessa forma não deixamos lixo no banco.
  Ao excluir devemos considerar algumas regras
 */

$codigo = $_REQUEST["codigo"];


//Altera todos os produtos vendidos que foram acertados para 'n�o acertados'
$sql = "
    UPDATE
    saidas_produtos   
    SET 
    saipro_fechado='0'
    WHERE 
    saipro_fechado='$codigo'
    ";

$query = mysql_query($sql);
if (!$query)
    die("Erro SQL: " . mysql_error());


//Deleta todoa as taxas do fechamento para depois excluir o fechamento
$sql = "
    DELETE FROM fechamentos_taxas 
    WHERE fchtax_fechamento=$codigo
";

$query = mysql_query($sql);
if (!$query)
    die("Erro SQL: " . mysql_error());


//Deleta o fechamento
$sql = "
    DELETE FROM fechamentos 
    WHERE fch_codigo=$codigo
";

$query = mysql_query($sql);
if (!$query)
    die("Erro SQL: " . mysql_error());

$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;
$tpl6->block("BLOCK_CONFIRMAR");
$tpl6->block("BLOCK_APAGADO");
$tpl6->DESTINO = "acertos_revenda.php";
$tpl6->block("BLOCK_BOTAO");
$tpl6->show();


include "rodape.php";
?>
