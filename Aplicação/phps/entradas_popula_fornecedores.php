<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";
require "login_verifica.php";
$tipopessoa = $_POST["tipopessoa"];
$tiponegociacao = $_POST["tiponegociacao"];

if ($tipopessoa==1) { //pessoa física
    $sql_filtro=$sql_filtro." AND pes_tipopessoa=1 ";
} else if ($tipopessoa==2) { //pessoa jurídica
    $sql_filtro=$sql_filtro." AND pes_tipopessoa=2 ";
} else { //Todos tipos de pessoa
    
}
if ($tiponegociacao!='undefined')
    $sql_filtro= $sql_filtro." AND fortipneg_tiponegociacao=$tiponegociacao";

echo $sql = "
    SELECT pes_codigo,pes_nome        
    FROM pessoas 
    JOIN fornecedores_tiponegociacao ON (fortipneg_pessoa=pes_codigo)
    JOIN mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)
    WHERE pes_cooperativa=$usuario_cooperativa
    AND mespestip_tipo=5        
    $sql_filtro
    ORDER BY pes_nome
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
if (mysql_num_rows($query) > 0) {    
    while ($dados = mysql_fetch_array($query)) {
        $codigo = $dados["pes_codigo"];
        $nome = $dados["pes_nome"];
        echo "<option value='$codigo'>$nome</option>";
    }
} else {
    echo "<option value=''>Não há registros</option>";
}
?>
