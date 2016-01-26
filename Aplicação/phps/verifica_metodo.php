<?php

include "funcoes.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$metodo = $_POST["metodo"];
$cpf = $_POST["cpf"];
$cpf = str_replace('_', '', $cpf);
$cpf = str_replace('.', '', $cpf);
$cpf = str_replace('-', '', $cpf);

if ($metodo == 1) {
    //echo "pergunta";
    $sql = "SELECT pes_perguntasecreta FROM pessoas WHERE pes_cpf like '$cpf'";
    if (!$query = mysql_query($sql))
        die("ERRO SQL" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $pergunta = $dados['pes_perguntasecreta'];
    echo $pergunta;
} else if ($metodo == 2) {
    //echo "email";
    $sql = "SELECT pes_email FROM pessoas WHERE pes_cpf like '$cpf'";
    if (!$query = mysql_query($sql))
        die("ERRO SQL" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $email = $dados['pes_email'];
    echo $email;
} else {
    echo "erro grave no arquivo verifica_metodo.php";
}
?>
