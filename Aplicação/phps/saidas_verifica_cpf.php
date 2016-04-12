<?php

include "funcoes.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$cpf = $_POST["cpf"];

$cpf = str_replace('_', '', $cpf);
$cpf = str_replace('.', '', $cpf);
$cpf = str_replace('-', '', $cpf);

$sql = "SELECT * FROM pessoas WHERE pes_cpf like '$cpf'";
if (!$query = mysql_query($sql)) die("ERRO SQL" . mysql_error());
$linhas = mysql_num_rows($query);
$dados=  mysql_fetch_assoc($query);
$codigo=$dados["pes_codigo"];
$nome=$dados["pes_nome"];
if ($linhas==0) {
    echo "naocadastrado";
}
else {
    echo "$codigo";
}
?>
