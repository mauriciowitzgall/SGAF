<?php

include "controle/conexao.php";

$cooperativa = $_POST["cooperativa"];
$pessoa = $_POST["pessoa"];

//Verifica se a pessoa é administrador
$sql8 = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_tipo=1 and mespestip_pessoa=$pessoa";
$query8 = mysql_query($sql8);
if (!$query8)
    die("Erro 40:" . mysql_error());
$linhas8 = mysql_num_rows($query8);

//Verifica se o usuário é presidente de alguma cooperativa
$sql5 = "SELECT coo_codigo FROM cooperativas WHERE coo_presidente=$pessoa";
$query5 = mysql_query($sql5);
if (!$query5)
    die("Erro 40:" . mysql_error());
$linhas5 = mysql_num_rows($query5);

//Se o usuário logado for presidente ou administrador mostrar apenas a opção 'todas' para quiosques
if ($linhas5 > 0) {
    echo "<option value=''>Todos</option>";
    exit;
} 

if ($linhas8 > 0) {
        echo "<option value=''>Todos</option>";
    
}
$sql = "
    SELECT DISTINCT
        qui_nome, qui_codigo 
    FROM
        quiosques 
    WHERE 
        qui_cooperativa=$cooperativa
    ";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
if (mysql_num_rows($query) > 0) {    
    while ($dados = mysql_fetch_array($query)) {
        $codigo = $dados["qui_codigo"];
        $nome = $dados["qui_nome"];
        echo "<option value='$codigo'>$nome</option>";
    }
} else {
    echo "<option value=''>Não há registros</option>";
}
?>
