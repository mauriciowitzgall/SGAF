<?php

include "controle/conexao.php";


$referencia = $_POST["referencia"];

//Verifica se existe algum produto com esta referencia
$sql = "SELECT pro_codigo,pro_nome FROM produtos WHERE pro_referencia='$referencia'";
if (!$query=mysql_query($sql)) die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas>0) {
    while ($dados= mysql_fetch_assoc($query)) {
        $codigo=$dados["pro_codigo"];
        $nome=$dados["pro_nome"];
        echo "$codigo|$nome";
    }
} else {
    echo "0";
}

?>
