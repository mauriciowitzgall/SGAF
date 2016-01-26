<?php

$sql = "SELECT * FROM saidas";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $saida = $dados["sai_codigo"];

    $sql2 = "SELECT * from saidas_produtos WHERE saipro_saida=$saida";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro SQL" . mysql_error());
    $linhas2 = mysql_num_rows($query2);
    if ($linhas2 == 0) {
        $sql3 = "DELETE FROM saidas WHERE sai_codigo=$saida";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro SQL" . mysql_error());
    }
}
?>
