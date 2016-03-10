<?php
include "controle/conexao.php";
$codigounico = $_POST["etiqueta2"];
$codigounico=ltrim($codigounico,"0");
$sql = "SELECT pro_codigo FROM produtos WHERE pro_codigounico=$codigounico";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$dados = mysql_fetch_assoc($query);
echo $produto = $dados['pro_codigo'];

?>
