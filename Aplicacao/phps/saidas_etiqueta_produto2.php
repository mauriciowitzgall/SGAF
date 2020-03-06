<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$codigounico = $_POST["etiqueta2"];
$sql = "SELECT pro_codigo FROM produtos WHERE pro_codigounico=$codigounico";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$dados= mysql_fetch_assoc($query);
$produto=$dados['pro_codigo'];


$sql = "SELECT * FROM produtos WHERE pro_codigo=$produto ORDER BY pro_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados= mysql_fetch_assoc($query)) {
	$codigo=$dados["pro_codigo"];
    $nome= $dados['pro_nome'];
    $referencia= $dados['pro_referencia'];
    $tamanho= $dados['pro_tamanho'];
    $cor= $dados['pro_cor'];
    $descricao= $dados['pro_descricao'];
    $nome2="$nome $tamanho $cor $descricao ($referencia)";
    echo "<option value='$codigo'>$nome2</option>";
}
?>
