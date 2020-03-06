<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$id = $_POST["id"];

$sql = "
    SELECT *
    FROM nfe_cst
    WHERE cst_id like '$id'
";        
if (!$query = mysql_query($sql)) die("Erro SQL:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $nome = $dados["cst_nome"];
    $codigo = $dados["cst_codigo"];
    echo "$nome/$codigo";
}
$qtdcaracteres = strlen($id);
if (($qtdcaracteres==8)&&($nome=="")) {
    echo "Referência Inválida";
}
?>
