<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
$etiqueta = $_POST[etiqueta];

//Divide o código da etiqueta em 2 pedaços
$produto = substr($etiqueta, 0, 6); //Os 6 primeiros digitos são referente ao produto
$lote = substr($etiqueta, 6, 14); //Os 8 demais digitos são referente ao lote

$sql = "
    SELECT pes_codigo,pes_nome 
    FROM pessoas 
    JOIN entradas on (ent_fornecedor=pes_codigo)
    WHERE ent_codigo=$lote";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados= mysql_fetch_assoc($query)) {
    $codigo=$dados["pes_codigo"];
    $nome=$dados["pes_nome"];
    echo "<option value='$codigo'>$nome</option>";
}
?>
