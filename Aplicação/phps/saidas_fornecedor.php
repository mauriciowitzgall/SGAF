<?php

include "controle/conexao.php";

$lote = $_POST["lote"];

$sql = "
SELECT
   *
FROM 
   entradas   
   JOIN pessoas ON (ent_fornecedor=pes_codigo)
WHERE
   ent_codigo='$lote'     
";
$query = mysql_query($sql);
if ($query) {
    while ($dados = mysql_fetch_array($query)) {
        $fornecedor_nome = $dados['pes_nome'];        
        echo "$fornecedor_nome";
    }
} else {
    echo mysql_error();
}
?>
