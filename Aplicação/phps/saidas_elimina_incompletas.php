<?php
include "controle/conexao.php";
//Varre a tabela saidas na procura de registros com status incompleto, ou seja, saidas corrompidas ou não salvas
$sql = "
    SELECT * 
    FROM saidas 
    WHERE sai_status=2 
    and sai_vendedor=$usuario_codigo
    and sai_quiosque=$usuario_quiosque
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $saida = $dados['sai_codigo'];

    //Verifica se esta saída possui pelo menos um produto inserido
    $sql2 = "
    SELECT saipro_produto 
    FROM saidas_produtos
    WHERE saipro_saida=$saida  
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro Qtd Produtos: " . mysql_error());
    $linhas2 = mysql_num_rows($query2);
    if ($linhas2 == 0) { //Se a saída não contém produtos então dá para deletá-la
        $sql3 = "
        DELETE FROM saidas 
        WHERE sai_codigo=$saida
        ";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro Del Saida: " . mysql_error());
    }
}
?>



