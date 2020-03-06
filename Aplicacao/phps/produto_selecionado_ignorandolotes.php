<?php

require "login_verifica.php";

$produto = $_POST["produto"];;

$sql="
    SELECT sum(etq_quantidade) as qtd ,min(etq_lote) as lote
    FROM estoque 
    WHERE etq_produto=$produto
    AND etq_quiosque=$usuario_quiosque
";


if (!$query = mysql_query($sql)) die("Erro: " . mysql_error());
$dados = mysql_fetch_array($query);
$qtd = $dados["qtd"];
$lote = $dados["lote"];
echo "$qtd/$lote";

?>
