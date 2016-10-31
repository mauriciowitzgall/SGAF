<?php

include "controle/conexao.php";
$produto = $_POST["produto"];
$lote = $_POST["lote"];
$qtd = $_POST["qtd"];

if (($produto <> "") AND ($lote <> "")) {
    $sql = "
SELECT
    entpro_local
FROM 
    entradas_produtos
WHERE
    entpro_entrada='$lote' and
    entpro_produto='$produto'    
";
    $query = mysql_query($sql);
    if (!$query) {
        die("Erro de SQL:" . mysql_error());
    }
    while ($dados = mysql_fetch_assoc($query)) {
        $local = $dados["entpro_local"];        
    }
    echo "($local)";
} else {
    echo "";
}
?>
