<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
$etiqueta = $_POST[etiqueta];
$produto = substr($etiqueta, 0, 6);
$lote = substr($etiqueta, 6, 14);
$lote=ltrim($lote,"0");
echo "<option value='$lote'>$lote</option>";
?>
