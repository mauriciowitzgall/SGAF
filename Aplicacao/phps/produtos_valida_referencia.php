<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
require "login_verifica.php";


$refer = $_POST["ref"];
$produto = $_POST["produto"];
if ($produto!="") $filtro=" AND pro_codigo not in ($produto) ";

$sql = "
    SELECT *
    FROM produtos 
    WHERE pro_cooperativa='$usuario_cooperativa'
    AND pro_referencia='$refer'
    $filtro
";

if (!$query=mysql_query($sql)) die("Erro: " . mysql_error());
if (mysql_num_rows($query) > 0) {
	echo "1";    
} else {
	echo "0";
}


?>
