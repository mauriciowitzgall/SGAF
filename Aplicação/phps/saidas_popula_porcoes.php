<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";


$produto = $_POST["produto"];
$sql = "
SELECT 
    propor_nome,propor_codigo,propor_quantidade
FROM
    produtos_porcoes
WHERE
    propor_produto=$produto
ORDER BY
    propor_nome ASC";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    echo ""; //Não há registros
} else {
    echo "<option value=''>Selecione</option>";
    while ($dados = mysql_fetch_assoc($query)) {
        $codigo = $dados["propor_codigo"];             
        $nome = $dados["propor_nome"];             
        echo "<option value='$codigo'>$nome</option>";
    }
}
 



?>
