<?php

require "login_verifica.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$consumidor=$_POST["consumidor"];


$sql = "
    SELECT *
    FROM pessoas
    left join cidades on (pes_cidade=cid_codigo)
    left join estados on (est_codigo=cid_estado)
    WHERE pes_codigo=$consumidor
";
$query = mysql_query($sql); if (!$query) die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $codigo=$dados["pes_codigo"];
    $nome= $dados['pes_nome'];
    $fone1= $dados['pes_fone1'];
    $fone2= $dados['pes_fone2'];
    $endereco= $dados['pes_endereco'];
    $bairro= $dados['pes_bairro'];
    $cidade= $dados['pes_cidade'];
    $estado= $dados['cid_estado'];
    $pais= $dados['est_pais'];

    echo "$codigo|$nome|$fone1|$fone2|$endereco|$bairro|$cidade|$estado|$pais";
}
?>
