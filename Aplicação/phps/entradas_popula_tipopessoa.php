<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";
require "login_verifica.php";
$tiponegociacao = $_POST["tiponegociacao"];

$sql = "
    SELECT DISTINCT pestippes_codigo,pestippes_nome
    FROM pessoas_tipopessoa
    JOIN pessoas ON (pes_tipopessoa=pestippes_codigo)
    JOIN mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)
    JOIN fornecedores_tiponegociacao ON (fortipneg_pessoa=pes_codigo)
    WHERE pes_cooperativa=$usuario_cooperativa
    AND mespestip_tipo=5    
    AND fortipneg_tiponegociacao=$tiponegociacao
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
if (mysql_num_rows($query) > 0) {    
    echo "<option value=''>Todos</option>";
    while ($dados = mysql_fetch_array($query)) {
        $codigo = $dados["pestippes_codigo"];
        $nome = $dados["pestippes_nome"];
        echo "<option value='$codigo'>$nome</option>";
    }
} else {
    echo "<option value=''>Não há registros</option>";
}
?>
