<?php
include "controle/conexao.php";
//include "conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";

$produto = $_POST["produto"];

if ($produto!="") {
    $sql = "
    SELECT pro_referencia FROM produtos WHERE pro_codigo=$produto ";
    $query = mysql_query($sql);
    if (!$query) die("Erro: " . mysql_error());
    if (mysql_num_rows($query) == 0) {
        echo "";
    } else {
        $dados = mysql_fetch_assoc($query);
        $referencia = $dados["pro_referencia"];             
        echo "$referencia";
    }
} else {
    echo "";
}
?>
