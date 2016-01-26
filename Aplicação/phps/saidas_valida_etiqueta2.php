<?php

include "controle/conexao.php";
$produto = $_POST[etiqueta2];

//Verifica se o produto e o lote existem no banco
//Produto
$sql = "SELECT pro_codigo,pro_nome FROM produtos WHERE pro_codigounico=$produto";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);

//Efetua a verifica��o se os dados da estique est�o corretos
if ($linhas == 0)  {
    echo "invalida";
} else {    
    echo "$produto";
}
?>
