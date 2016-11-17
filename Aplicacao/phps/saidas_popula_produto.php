<?php

require "login_verifica.php";
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$sql = "
SELECT DISTINCT
    pro_codigo,pro_nome,pro_referencia, pro_tamanho, pro_cor, pro_descricao
FROM
    produtos
    join estoque on (etq_produto=pro_codigo)    
WHERE
    etq_quiosque=$usuario_quiosque
ORDER BY
    pro_nome, pro_referencia, pro_tamanho, pro_cor, pro_descricao
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
echo "<option value=''>Selecione</option>";
while ($dados = mysql_fetch_array($query)) {
    $codigo=$dados["pro_codigo"];
    $nome= $dados['pro_nome'];
    $referencia= $dados['pro_referencia'];
    $tamanho= $dados['pro_tamanho'];
    $cor= $dados['pro_cor'];
    $descricao= $dados['pro_descricao'];
    $nome2=" $nome $referencia $tamanho $cor $descricao ";
    echo "<option value='$codigo'>$nome2</option>";
}
?>
