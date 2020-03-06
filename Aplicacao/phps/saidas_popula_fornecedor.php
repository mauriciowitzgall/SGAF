<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";

$produto = $_POST["produto"];
$sql = "
SELECT DISTINCT
    pes_codigo,pes_nome
FROM
    estoque
    JOIN entradas ON (etq_lote=ent_codigo)
    JOIN pessoas on (ent_fornecedor=pes_codigo)
    JOIN entradas_produtos ON (entpro_entrada=ent_codigo)    
WHERE
    etq_produto=$produto and
    entpro_produto=$produto and
    ent_quiosque=$usuario_quiosque
ORDER BY
    ent_fornecedor";
$query = mysql_query($sql);
if (!$query) die("Erro: " . mysql_error());
$linhas=mysql_num_rows($query);
if ($linhas == 0) {
    echo "0";
} else {
    if ($linhas==1) $selecionado= " selected ";
    echo "<option value=''>Selecione</option>";
    while ($dados = mysql_fetch_assoc($query)) {
        $fornecedor = $dados["pes_codigo"];             
        $fornecedor_nome = $dados["pes_nome"];             
        echo "<option value='$fornecedor' $selecionado >$fornecedor_nome</option>";
    }

}
?>
