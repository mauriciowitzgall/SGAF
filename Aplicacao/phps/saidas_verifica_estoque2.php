<?php

include "controle/conexao.php";
$produto = $_POST["produto"];
$lote = $_POST["lote"];

if (($produto <> "") AND ($lote <> "")) {
     $sql = "
SELECT
    etq_quantidade,protip_sigla,protip_nome
FROM 
    estoque
    join produtos on (etq_produto=pro_codigo)
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
WHERE
    etq_lote='$lote' and
    etq_produto='$produto'    
";
    $query = mysql_query($sql);
    if (!$query) {
        die("Erro de SQL:" . mysql_error());
    }
    $dados = mysql_fetch_array($query);
    $qtdnoestoque = $dados["etq_quantidade"];
    $nomecontagem = $dados["protip_nome"];
    $sigla = $dados["protip_sigla"];
    $tipo_nome = $dados["protip_nome"];
    echo $qtdnoestoque;
} else {
    echo "";
}
?>
