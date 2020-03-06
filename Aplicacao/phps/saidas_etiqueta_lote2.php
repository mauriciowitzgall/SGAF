<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";
$etiqueta = $_POST["etiqueta"];
$produto = substr($etiqueta, 0, 6);
$lote = substr($etiqueta, 6, 14);
$lote = ltrim($lote, "0");
echo "<option value='$lote'>$lote</option>";

$codigounico = $_POST["etiqueta2"];
$sql = "SELECT pro_codigo FROM produtos WHERE pro_codigounico=$codigounico";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$produto = $dados['pro_codigo'];

$sql = "
    SELECT DISTINCT ent_codigo, entpro_validade 
    FROM entradas 
    JOIN entradas_produtos on (ent_codigo=entpro_entrada) 
    WHERE entpro_produto=$produto
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
IF ($linhas == 0) {
    echo "<option value=''>Não há registros</option>";   
} else {    
    while ($dados = mysql_fetch_assoc($query)) {
        $codigo = $dados["ent_codigo"];
        $validade = $dados["entpro_validade"];
        echo "<option value='$codigo'>$codigo ($validade)</option>";
    }
}
?>
