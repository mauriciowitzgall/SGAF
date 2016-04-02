<?php

require "login_verifica.php";

$subproduto=$_POST["subproduto"];

$sql = "SELECT DISTINCT etq_lote FROM estoque WHERE etq_produto=$subproduto and etq_quiosque=$usuario_quiosque";
if (!$query=mysql_query($sql)) die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
   $resultado.=$dados[0].",";
}
$resultado=substr($resultado,0,-1);
echo $resultado;
?>
