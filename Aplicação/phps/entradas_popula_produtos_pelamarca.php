<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$marca = trim($_POST[marca]);


echo $sql = "SELECT pro_codigo,pro_nome FROM produtos WHERE pro_marca='$marca' ORDER BY pro_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
echo "<option value=''>Selecione</option>";
while ($dados= mysql_fetch_assoc($query)) {
    $codigo=$dados["pro_codigo"];
    $nome=$dados["pro_nome"];
    echo "<option value='$codigo'>$nome</option>";
}
?>
