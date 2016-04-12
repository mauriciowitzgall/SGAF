<?php

include "funcoes.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$cnpj = $_POST["cnpj"];

$cnpj = str_replace('_', '', $cnpj);
$cnpj = str_replace('.', '', $cnpj);
$cnpj = str_replace('-', '', $cnpj);
$cnpj = str_replace('/', '', $cnpj);

$sql = "SELECT * FROM pessoas WHERE pes_cnpj like '$cnpj'";
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
