<?php

include "controle/conexao.php";
include "funcoes.php";


$produto = $_POST["produto"];
$fornecedor = $_POST["fornecedor"];
$entrada = $_POST["entrada"];

//Se n�o tiver nenhum produto selecionado ent�o deixar o campo em branco e n�o fazer nada
if (($produto == "") || ($fornecedor == "")) {
    echo "";
    exit;
}

//Verifica se o produto j� est� na lista
$sql = "
    SELECT entpro_valorunitario,entpro_validade ,entpro_valunicusto
    FROM `entradas_produtos` 
    WHERE entpro_entrada='$entrada' 
    AND entpro_produto='$produto'
";
$query = mysql_query($sql);
if (!$query)
    die("Erro de SQL 18:" . mysql_error());
$linhas = mysql_num_rows($query);
$dados=  mysql_fetch_assoc($query);
$valuni= number_format($dados["entpro_valorunitario"],2);
$valunicusto= number_format($dados["entpro_valunicusto"],2);
$validade = $dados["entpro_validade"];        

//Se estiver na lista ent�o n�o podemos ter um valor unit�rio diferente, para isso deve-se fazer uma nova entrada
if ($linhas > 0) {
    echo "$valuni|$validade|$valunicusto";
} else {
    echo "naoestanalista";
}
?>
