<?php

require "login_verifica.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$sql = "
SELECT DISTINCT
    pro_codigo,pro_nome
FROM
    produtos
    join estoque on (etq_produto=pro_codigo)    
WHERE
    etq_quiosque=$usuario_quiosque
ORDER BY
    pro_nome
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
echo "<option value=''>Selecione</option>";
while ($dados = mysql_fetch_array($query)) {
    $codigo=$dados["pro_codigo"];
    $nome=$dados["pro_nome"];
    echo "<option value='$codigo'>$nome</option>";
}
?>
