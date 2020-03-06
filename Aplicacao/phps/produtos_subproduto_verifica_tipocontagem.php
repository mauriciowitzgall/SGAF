<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";
require "login_verifica.php";

$produto = $_POST["produto"];

$sql = "
    SELECT  protip_codigo,protip_sigla    
    FROM produtos_tipo 
    JOIN produtos on (pro_tipocontagem=protip_codigo)
    WHERE pro_codigo=$produto
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$dados = mysql_fetch_array($query);
$tipocontagem = $dados[0];
$tipocontagem_sigla = $dados[1];

echo "$tipocontagem|$tipocontagem_sigla";
?>
