<?php

include "controle/conexao.php";
include "funcoes.php";

$produto = $_POST["produto"];
$fornecedor = $_POST["fornecedor"];

//Se n�o tiver nenhum produto selecionado ent�o deixar o campo em branco e n�o fazer nada
if (($produto == "") || ($fornecedor == "")) {
    echo "";
    exit;
}


//Verifica qual � a ultima entrada que o fornecedor em quest�o realizou com o produto em quest�o
$sql1 = "
SELECT 
    MAX( ent_codigo ) as entrada
FROM 
    entradas 
    join entradas_produtos on (ent_codigo=entpro_entrada)
WHERE 
    ent_fornecedor = $fornecedor and 
    entpro_produto = $produto
";
$query1 = mysql_query($sql1);
if (!$query1)
    die("Erro de SQL 22:" . mysql_error());
$dados1 = mysql_fetch_assoc($query1);
$entrada_ultima = $dados1["entrada"];

//Se o fornecedor nunca efetuou entradas com este produto ent�o o valor unit�rio � nulo
if ($entrada_ultima == "") {
    echo "0|";
} else {
    //Verifica qual � o produto inserido dentro da entrada
    $sql2 = "
    SELECT 
        MAX( entpro_numero ) as numero_maior
    FROM     
        entradas_produtos
    WHERE 
        entpro_entrada=$entrada_ultima and    
        entpro_produto=$produto
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro de SQL 23:" . mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $numero_maior = $dados2["numero_maior"];
    //echo "($numero_maior) ";

    //Verifica qual o valor unit�rio daquele fornecedor e produto na ultima entrada que teve esse produto desse fornecedor
    $sql = "
    SELECT 
        entpro_valorunitario, entpro_validade, entpro_valunicusto
    FROM        
        entradas_produtos
    WHERE   
        entpro_entrada ='$entrada_ultima' and
        entpro_numero='$numero_maior'
    ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro de SQL 31:" . mysql_error());
    $dados = mysql_fetch_array($query);
    $valuni = $dados['entpro_valorunitario'];
    $valuni = money_format('%i', $valuni); 
    $valunicusto = $dados['entpro_valunicusto'];
    $valunicusto = money_format('%i', $valunicusto);
    $validade = $dados["entpro_validade"];
    echo "$valuni|$validade|$valunicusto";
    
}
?>
