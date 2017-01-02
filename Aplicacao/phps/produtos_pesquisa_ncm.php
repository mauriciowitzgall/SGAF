<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$id = $_POST["id"];

$sql = "
    SELECT *
    FROM nfe_ncm 
    WHERE ncm_id like '$id'
";        
if (!$query = mysql_query($sql)) die("Erro SQL:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["ncm_codigo"];
    $nome = $dados["ncm_nome"];
    echo "$nome^$codigo";
}
$qtdcaracteres = strlen($id);
if (($qtdcaracteres==8)&&($nome=="")) {
    echo "Referência Inválida";
}
?>
