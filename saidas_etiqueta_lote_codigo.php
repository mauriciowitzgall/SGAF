<?php
include "controle/conexao.php";
$etiqueta = $_POST["etiqueta"];
$produto = substr($etiqueta, 0, 6);
$lote = substr($etiqueta, 6, 14);
echo trim($lote);
?>
