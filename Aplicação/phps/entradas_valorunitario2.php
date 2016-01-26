<?php

include "controle/conexao.php";
include "funcoes.php";


$produto = $_POST[produto];
$fornecedor = $_POST[fornecedor];
$entrada = $_POST[entrada];

//Se não tiver nenhum produto selecionado então deixar o campo em branco e não fazer nada
if (($produto == "") || ($fornecedor == "")) {
    echo "";
    exit;
}

//Verifica se o produto já está na lista
$sql = "SELECT entpro_valorunitario,entpro_validade FROM `entradas_produtos` WHERE entpro_entrada='$entrada' and entpro_produto='$produto'";
$query = mysql_query($sql);
if (!$query)
    die("Erro de SQL 18:" . mysql_error());
$linhas = mysql_num_rows($query);
$dados=  mysql_fetch_assoc($query);
$valuni= number_format($dados["entpro_valorunitario"],2);
$validade = converte_data($dados["entpro_validade"]);        

//Se estiver na lista então não podemos ter um valor unitário diferente, para isso deve-se fazer uma nova entrada
if ($linhas > 0) {
    echo "$valuni|$validade";
} else {
    echo "naoestanalista";
}
?>
