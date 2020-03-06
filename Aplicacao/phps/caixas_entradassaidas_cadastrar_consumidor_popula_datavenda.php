<?php

require "login_verifica.php";

include "funcoes.php";

$consumidor = $_POST["consumidor"];

$sql="
    SELECT DISTINCT sai_datacadastro 
    FROM pessoas 
    JOIN saidas on (sai_consumidor=pes_codigo) 
    WHERE sai_areceber=1 
    and sai_consumidor=$consumidor 
    and sai_quiosque=$usuario_quiosque
    ORDER BY sai_datacadastro DESC
";
if (!$query=mysql_query($sql)) die("Erro SQL 89: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    echo "<option value=''>Não há registros</option>";
} else {
    echo "<option value=''>Escolha</option>";
    while ($dados = mysql_fetch_assoc($query)) {
        $data=$dados["sai_datacadastro"];
        $data2=  converte_data($data);
        echo "<option value='$data'>$data2</option>";
    }
}

?>
