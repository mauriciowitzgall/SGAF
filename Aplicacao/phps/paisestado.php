<?php

include "controle/conexao.php";
include "conexao_tipo.php";

$pais = $_POST["pais"];
$estado = $_POST["estado"];

if ($pais == "") {
    echo "<option value=''>Selecione</option>";
} else {
    $sql = "
    SELECT DISTINCT
        est_codigo,est_nome,est_sigla
    FROM
        estados
        join paises on (est_pais=pai_codigo)
        join cidades on (cid_estado=est_codigo)
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
            $nome = $dados["est_sigla"];
            if ($estado==$codigo) $selecionado=" selected "; else $selecionado="";
            echo "<option $selecionado value='$codigo'>$nome</option>";
        }
    } else {
        echo "<option value=''>Não há registros</option>";
    }
}
?>
