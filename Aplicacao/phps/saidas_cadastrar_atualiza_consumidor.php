<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";

$tipopessoa=$_POST["tipopessoa"];

$sql = "
    SELECT *
    FROM pessoas
    join mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)
    join pessoas_tipo on (mespestip_tipo=pestip_codigo)
    WHERE mespestip_tipo=6 
    and pes_cooperativa=$usuario_cooperativa
    and pes_tipopessoa=$tipopessoa
    ORDER BY pes_nome
";
$query = mysql_query($sql); if (!$query) die("Erro: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    echo ""; //Não há registros
} else {
    echo "<option value=''>Selecione</option>";
    while ($dados = mysql_fetch_assoc($query)) {
        $codigo = $dados["pes_codigo"];             
        $nome = $dados["pes_nome"];             
        echo "<option value='$codigo'>$nome</option>";
    }
}

?>
