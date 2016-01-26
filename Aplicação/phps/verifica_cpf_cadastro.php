<?php

include "funcoes.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";
$cpf = $_POST["cpf"];
$pessoa = $_POST["pessoa"];
$valor2 = $_POST["valor2"];
$oper = $_POST["oper"];
$cpf = str_replace('_', '', $cpf);
$cpf = str_replace('.', '', $cpf);
$cpf = str_replace('-', '', $cpf);
//echo "[$oper-$pessoa]";
if ($valor2 == 1) {
    if ($oper == "1") {
        $sql = "SELECT * FROM pessoas WHERE pes_cpf like '$cpf'";
    } else {
        $sql = "SELECT * FROM pessoas WHERE pes_cpf like '$cpf' and pes_codigo not in ($pessoa)";
    }
} else {
    $sql = "SELECT * FROM pessoas WHERE pes_cpf like '$cpf'";
}
if (!$query = mysql_query($sql))
    die("ERRO SQL" . mysql_error());
$linhas = mysql_num_rows($query);
echo $linhas;
?>
