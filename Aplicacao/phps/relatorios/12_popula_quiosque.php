<?php

include "../conexao.php";
include "../conexao_tipo.php";

$codigo = $_POST["cooperativa"];
if ($codigo == "") {
    echo "<option value=''>Todos</option>";
} else {
    $sql = "
    SELECT qui_codigo,qui_nome
    FROM quiosques
    WHERE qui_cooperativa=$codigo
    ORDER BY qui_nome
    ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    if (mysql_num_rows($query) > 0) {
        echo "<option value=''>Todos</option>";
        while ($dados = mysql_fetch_array($query)) {
            $codigo = $dados[0];
            $nome = $dados[1];
            echo "<option value='$codigo'>$nome</option>";
        }
    } else {
        echo "<option value=''>Não há registros</option>";
    }
}
?>
