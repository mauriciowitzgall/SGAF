<?php

require "login_verifica.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$tipopessoa=$_POST["tipopessoa"];

$sql = "
SELECT DISTINCT
    pes_codigo,pes_nome
FROM
    pessoas
    JOIN mestre_pessoas_tipo on (pes_codigo=mespestip_pessoa)
WHERE
    pes_tipopessoa=$tipopessoa
    AND pes_cooperativa=$usuario_cooperativa
    AND mespestip_tipo=6
ORDER BY
    pes_nome
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
echo "<option value='0'>Cliente Geral</option>";
while ($dados = mysql_fetch_array($query)) {
    $codigo=$dados["pes_codigo"];
    $nome=$dados["pes_nome"];
    echo "<option value='$codigo'>$nome</option>";
}
?>
