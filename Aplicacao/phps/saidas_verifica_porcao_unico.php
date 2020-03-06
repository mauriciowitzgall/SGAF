<?php
include "controle/conexao.php";
//include "conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";
$produto = $_POST["produto"];
$sql = "
    SELECT propor_codigo 
    FROM produtos_porcoes 
    WHERE propor_produto=$produto
";

if (!$query = mysql_query($sql)) die("Erro: " . mysql_error());
$linhas=mysql_num_rows($query);
if ($linhas == 0) {
    echo "naotem";
} else if ($linhas==1) {
    $dados=mysql_fetch_assoc($query);
    $porcao=$dados["propor_codigo"];
    echo "$porcao";
} else {
    //echo "Tem mais de um lote";
    echo "temvarios";
}
?>
