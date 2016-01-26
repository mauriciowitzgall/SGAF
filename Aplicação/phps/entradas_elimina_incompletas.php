<?php

$sql = "SELECT ent_codigo FROM entradas WHERE ent_supervisor=$usuario_codigo and ent_status=2";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["ent_codigo"];
    //Verifica quantos itens tem dentro desta entrada
    $sql2 = "SELECT entpro_entrada FROM entradas_produtos WHERE entpro_entrada=$codigo";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro SQL: " . mysql_error());    
    $linhas2 = mysql_num_rows($query2);

    //Elimina as entradas do supervisor logado de status incompleta e que não possuem itens
    if ($linhas2 == 0) {
        $sql3 = "DELETE FROM entradas WHERE ent_codigo=$codigo";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro SQL: " . mysql_error());
    }
}
?>
