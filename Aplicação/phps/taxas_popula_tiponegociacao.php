<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";
require "login_verifica.php";

$quiosque = $_POST["quiosque"];
if ($quiosque==" ") {
    $sql = "
    SELECT *  
    FROM tipo_negociacao
    ORDER BY tipneg_nome
    ";   
} else {    
    $sql = "
    SELECT *  
    FROM tipo_negociacao
    JOIN quiosques_tiponegociacao on (quitipneg_tipo=tipneg_codigo)
    WHERE quitipneg_quiosque=$quiosque
    ORDER BY tipneg_nome
";
}
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
if (mysql_num_rows($query) > 0) {    
    echo "<option value=''>Selecione</option>";
    while ($dados = mysql_fetch_array($query)) {
        $codigo = $dados["tipneg_codigo"];
        $nome = $dados["tipneg_nome"];
        echo "<option value='$codigo'>$nome</option>";
    }
} else {
    echo "<option value=''>Não há registros</option>";
}
?>
