<?php

include "controle/conexao.php";
$produto = $_POST["etiqueta2"];

//Produto
$sql = "SELECT pro_codigo,pro_nome FROM produtos WHERE pro_codigounico=$produto";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);


if ($linhas == 0)  {
    echo "invalida";
} else {    
    echo "$produto";
}
?>
