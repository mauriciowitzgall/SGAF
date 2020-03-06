<?php

include "funcoes.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$fone = $_POST["fone"];

//$fone = str_replace('_', '', $fone);
//$fone = str_replace('.', '', $fone);
//$fone = str_replace('-', '', $fone);

$sql = "SELECT * FROM pessoas WHERE pes_fone1 like '$fone'";
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
