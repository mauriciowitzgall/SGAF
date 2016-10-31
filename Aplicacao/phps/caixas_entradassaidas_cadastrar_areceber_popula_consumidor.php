<?php

require "login_verifica.php";

include "funcoes.php";

$areceber = $_POST["areceber"];

$sql="
    SELECT DISTINCT pes_codigo,pes_nome 
    FROM pessoas 
    JOIN saidas on (sai_consumidor=pes_codigo) 
    WHERE sai_areceber=1 
    and sai_quiosque=$usuario_quiosque
    ORDER BY pes_nome";
if (!$query=mysql_query($sql)) die("Erro SQL 89: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    echo "<option value=''>Não há registros</option>";
} else {
    echo "<option value=''>Escolha</option>";
    while ($dados = mysql_fetch_assoc($query)) {
        $codigo=$dados["pes_codigo"];
        $nome=$dados["pes_nome"];
        if ($codigo==0) {
            echo "<option value='$codigo'>Cliente Geral</option>";
        } else {
            echo "<option value='$codigo'>$nome</option>";
        }
    }
}

?>
