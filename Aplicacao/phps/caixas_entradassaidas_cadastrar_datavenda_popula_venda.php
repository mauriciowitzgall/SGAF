<?php

require "login_verifica.php";

include "funcoes.php";

$consumidor = $_POST["consumidor"];
$datavenda = $_POST["datavenda"];

$sql="
    SELECT DISTINCT sai_codigo
    FROM saidas 
    WHERE sai_areceber=1 
    and sai_quiosque=$usuario_quiosque
    and sai_consumidor=$consumidor 
    and sai_datacadastro='$datavenda'
    ORDER BY sai_codigo DESC
";
if (!$query=mysql_query($sql)) die("Erro SQL 89: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    echo "<option value=''>Não há registros</option>";
} else {
    echo "<option value=''>Escolha</option>";
    while ($dados = mysql_fetch_assoc($query)) {
        $codigo=$dados["sai_codigo"];
        echo "<option value='$codigo'>$codigo</option>";
    }
}

?>
