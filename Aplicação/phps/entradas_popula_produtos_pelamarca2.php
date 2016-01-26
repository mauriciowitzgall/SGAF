<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";
require "login_verifica.php";

$tiponegociacao=$_POST["tiponeg"];

$sql = "
        SELECT pro_codigo,pro_nome 
        FROM produtos 
        JOIN mestre_produtos_tipo ON (mesprotip_produto=pro_codigo)
        WHERE pro_cooperativa='$usuario_cooperativa' 
        AND mesprotip_tipo=$tiponegociacao
        ORDER BY pro_nome
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
echo "<option value=''>Selecione</option>";
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["pro_codigo"];
    $nome = $dados["pro_nome"];
    echo "<option value='$codigo'>$nome</option>";
}
?>
