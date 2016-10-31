<?php

require "login_verifica.php";

$tiponegociacao=$_POST["tiponeg"];

$sql = "
        SELECT pro_codigo,pro_nome,prorec_nome,pro_volume,pro_marca,protip_sigla
        FROM produtos 
        JOIN mestre_produtos_tipo ON (mesprotip_produto=pro_codigo)
        join produtos_tipo on pro_tipocontagem=protip_codigo
        left JOIN produtos_recipientes on (prorec_codigo=pro_recipiente)
        WHERE pro_cooperativa='$usuario_cooperativa' 
        AND mesprotip_tipo=$tiponegociacao
        ORDER BY pro_nome
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
echo "<option value=''>Selecione</option>";
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["pro_codigo"];
    $nome = $dados["pro_nome"];
    $recipiente=$dados["prorec_nome"];
    $volume=$dados["pro_volume"];
    $marca=$dados["pro_marca"];
    $sigla=$dados["protip_sigla"];
    echo "<option value='$codigo'>$nome $marca $recipiente $volume ($sigla)</option>";
}
?>
