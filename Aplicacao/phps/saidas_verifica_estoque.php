<?php
require "login_verifica.php";
include "controle/conexao.php";


$produto = $_POST["produto"];
$lote = $_POST["lote"];
$ignorarlotes = $_POST["ignorarlotes"];


if ($ignorarlotes==1) {
    $sql="
        SELECT sum(etq_quantidade) as qtd, protip_codigo
        FROM estoque 
        join produtos on (etq_produto=pro_codigo)
        join produtos_tipo on (pro_tipocontagem=protip_codigo)            
        WHERE etq_produto=$produto
        AND etq_quiosque=$usuario_quiosque
    ";
} else {
    $sql = "
        SELECT etq_quantidade as qtd ,protip_codigo
        FROM estoque
        join produtos on (etq_produto=pro_codigo)
        join produtos_tipo on (pro_tipocontagem=protip_codigo)
        WHERE etq_lote=$lote 
        and etq_produto=$produto        
    ";
}
;
if (!$query = mysql_query($sql)) die("Erro de SQL:" . mysql_error());
$dados = mysql_fetch_array($query);
$qtdnoestoque = $dados["qtd"];
$contagem = $dados["protip_codigo"];
if (($contagem==2)||($contagem==3)) 
    echo $qtdnoestoque = number_format($qtdnoestoque, 3, '.', '');
else 
    echo $qtdnoestoque = number_format($qtdnoestoque, 0, '', '');

?>
