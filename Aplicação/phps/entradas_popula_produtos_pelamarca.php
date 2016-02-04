<?php
require "login_verifica.php";


$marca = trim($_POST[marca]);
$tiponegociacao = $_POST["tiponegociacao"];

$sql = "
SELECT DISTINCT pro_codigo,pro_nome 
FROM produtos 
join mestre_produtos_tipo on (mesprotip_produto=pro_codigo)
WHERE pro_marca='$marca'
and pro_cooperativa=$usuario_cooperativa
and mesprotip_tipo=$tiponegociacao 
ORDER BY pro_nome
";
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
