<?php
include "controle/conexao.php";
$etiqueta = $_POST["etiqueta"];

//Divide o c�digo da etiqueta em 2 peda�os
$produto = substr($etiqueta, 0, 6); //Os 6 primeiros digitos s�o referente ao produto
$lote = substr($etiqueta, 6, 14); //Os 8 demais digitos s�o referente ao lote

$produto=ltrim($produto,"0");
echo "$produto";
?>
