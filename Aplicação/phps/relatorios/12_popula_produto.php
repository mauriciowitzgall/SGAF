<?php

include "../conexao.php";
include "../conexao_tipo.php";

$codigo = $_POST["quiosque"];
if ($codigo == "") {
    echo "<option value=''>Todos</option>";
} else {
    $sql = "
    SELECT DISTINCT pro_codigo,pro_nome
    FROM produtos
    join saidas_produtos on (saipro_produto=pro_codigo)
    join saidas on (saipro_saida=sai_codigo)
    join quiosques on (sai_quiosque=qui_codigo)
    join cooperativas on (qui_cooperativa=coo_codigo)
    WHERE sai_quiosque=$codigo
    ORDER BY pro_nome
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
