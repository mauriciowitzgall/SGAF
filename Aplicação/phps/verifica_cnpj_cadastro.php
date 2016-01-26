<?php

include "funcoes.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";
$cnpj = $_POST["cnpj"];
$pessoa = $_POST["pessoa"];
$oper = $_POST["oper"];
$cnpj = str_replace('_', '', $cnpj);
$cnpj = str_replace('.', '', $cnpj);
$cnpj = str_replace('-', '', $cnpj);
$cnpj = str_replace('/', '', $cnpj);
//echo "[$oper-$pessoa]";
if ($oper == "1") {
    $sql = "SELECT * FROM pessoas WHERE pes_cnpj like '$cnpj'";
} else {
    $sql = "SELECT * FROM pessoas WHERE pes_cnpj like '$cnpj' and pes_codigo not in ($pessoa)";
}
if (!$query = mysql_query($sql))
        die("ERRO SQL" . mysql_error());
    $linhas = mysql_num_rows($query);
    echo $linhas;

?>
