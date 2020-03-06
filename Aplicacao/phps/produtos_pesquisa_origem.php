<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$codigo = $_POST["codigo"];

$sql = "
    SELECT ori_nome
    FROM nfe_origem
    WHERE ori_codigo like '$codigo'
";        
if (!$query = mysql_query($sql)) die("Erro SQL:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $nome = $dados["ori_nome"];
    echo "$nome";
}
$qtdcaracteres = strlen($codigo);
if (($qtdcaracteres==1)&&($nome=="")) {
    echo "Referência Inválida";
}
?>
