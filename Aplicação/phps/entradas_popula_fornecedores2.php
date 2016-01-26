<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";
require "login_verifica.php";
$tipopessoa = $_POST["tipopessoa"];
$tiponegociacao = 1;

if ($tipopessoa==1) { //pessoa física
    $sql_filtro=$sql_filtro." AND pes_tipopessoa=1 ";
} else if ($tipopessoa==2) { //pessoa jurídica
    $sql_filtro=$sql_filtro." AND pes_tipopessoa=2 ";
} else { //Todos tipos de pessoa
    
}

$sql = "
    SELECT DISTINCT pes_codigo,pes_nome        
    FROM pessoas 
    JOIN fornecedores_tiponegociacao ON (fortipneg_pessoa=pes_codigo)
    JOIN mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)
    JOIN entradas on (ent_fornecedor=pes_codigo)
    JOIN saidas_produtos on (saipro_lote=ent_codigo)
    WHERE pes_cooperativa=$usuario_cooperativa
    AND mespestip_tipo=5 
    and saipro_acertado=0
    and ent_tiponegociacao=$tiponegociacao
    $sql_filtro
    ORDER BY pes_nome
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
if (mysql_num_rows($query) > 0) {    
    echo "<option value=''>Selecione</option>";
    while ($dados = mysql_fetch_array($query)) {
        $codigo = $dados["pes_codigo"];
        $nome = $dados["pes_nome"];
        echo "<option value='$codigo'>$nome</option>";
    }
} else {
    echo "<option value=''>Não há registros</option>";
}
?>
