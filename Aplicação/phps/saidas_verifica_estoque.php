<?php

include "controle/conexao.php";
$produto = $_POST[produto];
$lote = $_POST[lote];

if ($lote == 0) {
    echo "";
} else {
    $sql = "
    SELECT
        etq_quantidade,protip_codigo
    FROM 
        estoque
        join produtos on (etq_produto=pro_codigo)
        join produtos_tipo on (pro_tipocontagem=protip_codigo)
    WHERE
        etq_lote=$lote and
        etq_produto=$produto        
    ";
    $query = mysql_query($sql);
    if (!$query) {
        die("Erro de SQL:" . mysql_error());
    }
    $dados = mysql_fetch_array($query);
    $qtdnoestoque = $dados["etq_quantidade"];
    $contagem = $dados["protip_codigo"];
    if (($contagem==2)||($contagem==3)) 
        echo $qtdnoestoque = number_format($qtdnoestoque, 3, ',', '.');
    else 
        echo $qtdnoestoque = number_format($qtdnoestoque, 0, '', '.');
}
?>
