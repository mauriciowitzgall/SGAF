<?php
include "controle/conexao.php";
//include "conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";

$produto = $_POST["produto"];
$fornecedor = $_POST["fornec"];

$sql = "
SELECT  DISTINCT
    etq_lote,entpro_local
FROM
    estoque
    JOIN pessoas ON (etq_fornecedor=pes_codigo)
    JOIN entradas ON (etq_lote=ent_codigo)
    JOIN entradas_produtos ON (entpro_entrada=ent_codigo)    
WHERE
    etq_produto=$produto and
    entpro_produto=$produto and
    ent_fornecedor=$fornecedor and
    ent_quiosque=$usuario_quiosque
ORDER BY
    etq_lote";

if (!$query = mysql_query($sql)) die("Erro: " . mysql_error());
$linhas=mysql_num_rows($query);
if ($linhas == 0) {
    echo "0";
} else if ($linhas==1) {
    $dados = mysql_fetch_assoc($query);
    $lote = $dados["etq_lote"];   
    echo "$lote";
} else {
    //echo "Tem mais de um lote";
    echo "0";
}
?>
