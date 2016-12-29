
<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$id = $_POST["id"];

$sql = "
    SELECT *
    FROM nfe_cfop
    WHERE cfop_id like '$id'
";        
if (!$query = mysql_query($sql)) die("Erro SQL:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $descricao = $dados["cfop_descricao"];
    $codigo = $dados["cfop_codigo"];
    echo "$descricao/$codigo";
}
$qtdcaracteres = strlen($id);
if (($qtdcaracteres==5)&&($descricao=="")) {
    echo "Referência Inválida";
}
?>
