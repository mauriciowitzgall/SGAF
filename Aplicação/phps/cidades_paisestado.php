<?php

include "controle/conexao.php";
include "conexao_tipo.php";

$pais = $_POST["pais"];
if ($pais == "") {
    echo "<option value=''>Selecione</option>";
} else {
    $sql = "
    SELECT DISTINCT
        est_codigo,est_nome
    FROM
        estados
        join paises on (est_pais=pai_codigo)        
    WHERE
        est_pais=$pais
    ORDER BY
        est_nome";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    if (mysql_num_rows($query) > 0) {
        echo "<option value=''>Selecione</option>";
        while ($dados = mysql_fetch_array($query)) {
            $codigo = $dados["est_codigo"];
            $nome = $dados["est_nome"];
            echo "<option value='$codigo'>$nome</option>";
        }
    } else {
        echo "<option value=''>Não há registros</option>";
    }
}
?>
