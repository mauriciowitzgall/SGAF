<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";


$cooperativa = $_POST["cooperativa"];
$sql = "SELECT * FROM  produtos_categorias WHERE cat_cooperativa=$cooperativa ORDER BY cat_nome ASC";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    echo ""; //Não há registros
} else {
    echo "<option value=''>Selecione</option>";
    while ($dados = mysql_fetch_assoc($query)) {
        $codigo = $dados["cat_codigo"];             
        $nome = $dados["cat_nome"];             
        echo "<option value='$codigo'>$nome</option>";
    }
}

?>
