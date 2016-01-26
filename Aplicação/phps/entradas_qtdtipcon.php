<?php
include "controle/conexao.php";

$produto = $_POST[produto]; // echo "Produto: $produto";

$sql = "SELECT protip_sigla FROM produtos join produtos_tipo on (pro_tipocontagem=protip_codigo) WHERE pro_codigo='$produto'";
$query = mysql_query($sql);
if ($query) {
    while ($dados = mysql_fetch_array($query)) {
        $tipocontagem = $dados[0];
        echo "<b>por $tipocontagem</b>";
    }
} else {
    echo mysql_error();
}
?>
