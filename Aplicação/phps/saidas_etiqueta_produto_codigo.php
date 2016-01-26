<?php
include "controle/conexao.php";
$etiqueta = $_POST[etiqueta];

//Divide o código da etiqueta em 2 pedaços
$produto = substr($etiqueta, 0, 6); //Os 6 primeiros digitos são referente ao produto
$lote = substr($etiqueta, 6, 14); //Os 8 demais digitos são referente ao lote

$produto=ltrim($produto,"0");
echo "$produto";
?>
