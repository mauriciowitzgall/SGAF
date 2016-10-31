<?php

require "login_verifica.php";

$entrada=$_POST["entrada"];

$sql = "
    SELECT entpro_numero,entpro_produto,prosub_subproduto
    FROM entradas_produtos
    JOIN produtos_subproduto on (entpro_produto=prosub_produto)
    WHERE entpro_entrada= $entrada and entpro_retiradodoestoquesubprodutos=0
";
if (!$query=mysql_query($sql)) die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
   $resultado.="$dados[0]_$dados[1]_$dados[2]".",";
}
$resultado=substr($resultado,0,-1);
echo $resultado;
?>
