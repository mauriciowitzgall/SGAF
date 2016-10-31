<?php
include "controle/conexao.php";
$etiqueta = $_POST["etiqueta"];
$produto = substr($etiqueta, 0, 6);
$lote = substr($etiqueta, 6, 14);
$lote=ltrim($lote,"0");
echo $lote;
?>
