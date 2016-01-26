<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";

$tiponegociacao = $_POST["tiponegociacao"];
$quiosque = $_POST["quiosque"];
echo $_POST["quiosque"];
if ($tiponegociacao=="") {
    echo "<option value=''></option>";
    exit;
}

if ($usuario_grupo==3)
    $sql_filtro.=" AND tax_quiosque=$quiosque";
$sql = "
    SELECT *      
    FROM taxas 
    WHERE tax_tiponegociacao=$tiponegociacao
    AND (tax_quiosque = 0  OR tax_quiosque=$quiosque)
    AND tax_codigo not in (SELECT quitax_taxa FROM quiosques_taxas WHERE quitax_taxa=tax_codigo AND quitax_quiosque=$quiosque)    
    $sql_filtro
    ORDER BY tax_nome
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
if (mysql_num_rows($query) > 0) {
    echo "<option value=''>Selecione</option>";
    while ($dados = mysql_fetch_array($query)) {
        $codigo = $dados["tax_codigo"];
        $nome = $dados["tax_nome"];
        echo "<option value='$codigo'>$nome</option>";
    }
} else {
    echo "<option value=''>Não há registros</option>";
}
?>
