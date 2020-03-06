<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";


$porcao = $_POST["porcao"];
$sql = "
    SELECT propor_quantidade
    FROM produtos_porcoes
    WHERE propor_codigo=$porcao
";
    
$query = mysql_query($sql);
if (!$query)  die("Erro: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$qtd=$dados["propor_quantidade"];
$qtd=  number_format($qtd,3,'.','');
echo "$qtd";


?>
