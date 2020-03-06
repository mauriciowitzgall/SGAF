<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";


$estado = $_POST["estado"];
$cidade = $_POST["cidade"];

//Se a variavel veio nula ent�o mostrar a op��o padr�o
if ($estado == "") {
    echo "<option value=''>Selecione</option>";
} else {
    $sql = "
    SELECT DISTINCT
        cid_codigo,cid_nome
    FROM
        cidades 
        join estados on (cid_estado=est_codigo)
        join paises on (est_pais=pai_codigo)
    WHERE
        cid_estado=$estado
    ORDER BY
        cid_nome
    ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    if (mysql_num_rows($query) == 0) {
        echo "<option value=''>Não há registros</option>";
    } else {
        echo "<option value=''>Selecione</option>";
        while ($dados = mysql_fetch_array($query)) {
            $codigo = $dados["cid_codigo"];
            $nome = $dados["cid_nome"];
            if ($cidade==$codigo) $selecionado=" selected "; else $selecionado=" "; 
            echo "<option $selecionado value='$codigo'>$nome</option>";
        }
    }
}
?>
