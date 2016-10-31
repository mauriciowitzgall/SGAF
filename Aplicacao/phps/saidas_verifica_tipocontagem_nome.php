<?php

include "controle/conexao.php";
$produto = $_POST["produto"];
if ($produto == "") {
    echo "";
} else {
    $sql = "
    SELECT protip_nome,protip_sigla
    FROM produtos
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
    WHERE pro_codigo=$produto
";
    $query = mysql_query($sql);
    if (!$query) {
        die("Erro de SQL:" . mysql_error());
    }
    $dados = mysql_fetch_assoc($query);
    $tipocontagem_nome = $dados["protip_sigla"];
    echo $tipocontagem_nome;
}
?>
