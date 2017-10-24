<?php
require "login_verifica.php";


$marca = trim($_POST["marca"]);
$tiponegociacao = $_POST["tiponegociacao"];

if ($tiponegociacao==0) {
    $filtro_tabela="  ";
    if ($usavendas==1) $filtro=" and pro_evendido=0 ";
    $filtro_valor=" $filtro";
} else {
    $filtro_tabela=" join mestre_produtos_tipo on (mesprotip_produto=pro_codigo) ";
    if ($usavendas==1) $filtro=" AND pro_evendido=1";
    $filtro_valor=" and mesprotip_tipo=$tiponegociacao $filtro";
}

$sql = "
SELECT DISTINCT pro_codigo,pro_nome,prorec_nome,pro_volume,pro_marca,protip_sigla,pro_referencia,pro_tamanho,pro_cor,pro_descricao
FROM produtos 
$filtro_tabela
join produtos_tipo on pro_tipocontagem=protip_codigo
left JOIN produtos_recipientes on (prorec_codigo=pro_recipiente)
WHERE pro_marca='$marca'
and pro_cooperativa=$usuario_cooperativa
$filtro_valor
ORDER BY pro_referencia, pro_nome, pro_tamanho, pro_cor, pro_descricao
";


$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
echo "<option value=''>Selecione</option>";
while ($dados= mysql_fetch_assoc($query)) {
    $codigo=$dados["pro_codigo"];
    $nome=$dados["pro_nome"];
    $recipiente=$dados["prorec_nome"];
    $volume=$dados["pro_volume"];
    $marca=$dados["pro_marca"];
    $sigla=$dados["protip_sigla"];
    $referencia=$dados["pro_referencia"];
    $tamanho=$dados["pro_tamanho"];
    $cor=$dados["pro_cor"];
    $descricao=$dados["pro_descricao"];
    echo "<option value='$codigo'>$referencia $nome $tamanho $cor $descricao</option>";
}
?>
