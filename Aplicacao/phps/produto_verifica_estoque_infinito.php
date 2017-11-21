<?php

require "login_verifica.php";

$produto = $_POST["produto"];;

$sql="
    SELECT *
    FROM produtos 
    WHERE pro_codigo=$produto
";


if (!$query = mysql_query($sql)) die("Erro: " . mysql_error());
$dados = mysql_fetch_array($query);
$controlarestoque=$dados["pro_controlarestoque"];
$valunicusto=$dados["pro_valunicusto"];
$valunivenda=$dados["pro_valunivenda"];
$controlarestoque=$dados["pro_controlarestoque"];
echo "$controlarestoque|$valunicusto|$valunivenda";

?>
