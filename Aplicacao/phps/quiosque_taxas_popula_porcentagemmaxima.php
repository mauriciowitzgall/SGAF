<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";
require "login_verifica.php";

$tiponegociacao = $_POST["tiponegociacao"];
$quiosque = $_POST["quiosque"];
$percent = $_POST["percent"];
if ($tiponegociacao == "") {
    echo "% ";
    exit;
}

$sql = "
    SELECT  sum(quitax_valor)     
    FROM taxas 
    JOIN quiosques_taxas on (quitax_taxa=tax_codigo)
    WHERE tax_tiponegociacao=$tiponegociacao
    AND quitax_quiosque=$quiosque
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$dados = mysql_fetch_array($query);
$max = $dados[0];
$max= 100 - $max + $percent;

echo "% (Máximo $max %)";
?>
