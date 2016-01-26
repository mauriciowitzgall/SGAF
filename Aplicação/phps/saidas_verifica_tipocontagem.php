<?php

include "controle/conexao.php";
$produto = $_POST[produto];
$sql= "
    SELECT pro_tipocontagem
    FROM produtos
    WHERE pro_codigo=$produto
";
$query = mysql_query($sql);
if (!$query) {
    die("Erro de SQL:" . mysql_error());
}
$dados = mysql_fetch_assoc($query);
$tipocontagem=$dados["pro_tipocontagem"];
echo $tipocontagem;
?>
