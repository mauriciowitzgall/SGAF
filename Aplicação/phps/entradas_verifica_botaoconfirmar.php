<?php

require "login_verifica.php";

$entrada=$_POST["entrada"];

$sql = "
    SELECT entpro_produto,prosub_subproduto
    FROM entradas_produtos
    JOIN produtos_subproduto on (entpro_produto=prosub_produto)
    WHERE entpro_entrada= $entrada
";
if (!$query=mysql_query($sql)) die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
   $resultado.="$dados[0]_$dados[1]".",";
}
$resultado=substr($resultado,0,-1);
echo $resultado;
?>
