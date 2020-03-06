<?php

include "controle/conexao.php";
require "login_verifica.php";

$referencia = $_POST["referencia"];

//Verifica se existe algum produto com esta referencia
$sql = "SELECT * FROM produtos WHERE pro_referencia='$referencia' AND pro_evendido=1 and pro_cooperativa=$usuario_cooperativa";
if (!$query=mysql_query($sql)) die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas>0) {
    while ($dados= mysql_fetch_assoc($query)) {
	$codigo=$dados["pro_codigo"];
    $nome= $dados['pro_nome'];
    $referencia= $dados['pro_referencia'];
    $tamanho= $dados['pro_tamanho'];
    $cor= $dados['pro_cor'];
    $descricao= $dados['pro_descricao'];
    $nome2="$nome $tamanho $cor $descricao ($referencia)";
        echo "$codigo|$nome2";
    }
} else {
    echo "0";
}

?>
