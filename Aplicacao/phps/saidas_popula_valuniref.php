<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";


$porcao = $_POST["porcao"];
$sql = "
    SELECT propor_valuniref
    FROM produtos_porcoes
    WHERE propor_codigo=$porcao
";
    
$query = mysql_query($sql);
if (!$query)  die("Erro: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$qtd=$dados["propor_valuniref"];
$qtd=  number_format($qtd,2,'.','');
echo "$qtd";


?>
