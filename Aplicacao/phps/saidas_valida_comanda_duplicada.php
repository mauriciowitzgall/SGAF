<?php

include "controle/conexao.php";

$comanda = $_POST["comanda"];
$saida = $_POST["saida"];

if ($saida>0) $sql_filtro = " AND sai_codigo not in ($saida) ";
else $sql_filtro="";
$sql = "SELECT sai_id FROM saidas WHERE sai_id=$comanda $sql_filtro";
$query = mysql_query($sql);
if (!$query) die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);

if ($linhas > 0) {
    echo "emuso";
} else {
    echo "liberado";
}
?>
