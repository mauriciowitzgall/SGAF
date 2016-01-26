<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";

$codigounico = $_POST[etiqueta2];
$sql = "SELECT pro_codigo FROM produtos WHERE pro_codigounico=$codigounico";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$produto = $dados['pro_codigo'];

echo $sql = "
    SELECT DISTINCT pes_codigo,pes_nome 
    FROM pessoas 
    JOIN entradas on (ent_fornecedor=pes_codigo)
    JOIN entradas_produtos on (entpro_entrada=ent_codigo)
    WHERE entpro_produto=$produto";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
IF ($linhas == 0) {
    echo "<option value=''>Não há registros</option>";
    exit;
} else {
    echo "<option value=''>Todos</option>";
    while ($dados = mysql_fetch_assoc($query)) {
        $codigo = $dados["pes_codigo"];
        $nome = $dados["pes_nome"];
        echo "<option value='$codigo'>$nome</option>";
    }
}
?>
