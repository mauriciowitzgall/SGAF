<?php


include "controle/conexao.php";
include "controle/conexao_tipo.php";
include "funcoes.php";

$fornecedor = $_POST["fornecedor"];
$usuario_quiosque = $_POST["quiosque"];

//Pegar data e hora inicial
//O sistema sempre pega a data e hora do ultimo acerto, caso não tenha nenhum efetuado, pega-se a data e hora da primeira venda
$sql = "SELECT max(ace_codigo) FROM acertos WHERE ace_fornecedor=$fornecedor";
$query = mysql_query($sql);
if (!$query)
    die("ERRO GRAVE SQL:" . mysql_error());
$dados = mysql_fetch_array($query);
$ultimo = $dados[0];
if ($ultimo > 0) {
    $sql2 = "
        SELECT ace_datafim 
        FROM acertos
        WHERE ace_codigo=$ultimo        
        AND ace_fornecedor=$fornecedor
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("ERRO GRAVE SQL:" . mysql_error());
    $dados2 = mysql_fetch_array($query2);
    $dataini_datetime = $dados2[0];
} else {
    $sql3 = "
        SELECT min(sai_codigo) as primeirasaida
        FROM saidas
        JOIN saidas_produtos ON (saipro_saida=sai_codigo)
        JOIN entradas on (saipro_lote=ent_codigo)
        WHERE sai_quiosque=$usuario_quiosque
        AND saipro_acertado=0
        AND ent_tiponegociacao=1     
        AND sai_tipo=1
        AND sai_status=1 
        AND ent_fornecedor=$fornecedor
    ";
    $query3 = mysql_query($sql3);
    if (!$query3)
        die("ERRO GRAVE SQL:" . mysql_error());
    $dados3 = mysql_fetch_array($query3);
    $primeirasaida = $dados3['primeirasaida'];
    //echo "($primeirasaida)";
    if ($primeirasaida == "") {
        echo "nao_gerou_venda_consignada";
        exit;
    }
    $sql4 = "
    SELECT sai_datacadastro,sai_horacadastro
    FROM saidas
    WHERE sai_codigo=$primeirasaida
    ";
    $query4 = mysql_query($sql4);
    if (!$query4)
        die("ERRO GRAVE SQL:" . mysql_error());
    $dados4 = mysql_fetch_array($query4);    
    $dataini_datetime = $dados4['sai_datacadastro'] . " " . $dados4['sai_horacadastro'];
//Pegar data e hora final
//O sistema pega data atual como referencia para autopreencher os campos.
}
$dat = explode(' ', $dataini_datetime);
$dataini = $dat[0];
$horaini = $dat[1];
$horaini = explode(':', $horaini);
$horaini = $horaini[0] . ":" . $horaini[1];
echo "$dataini";
?> 
