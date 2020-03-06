<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";
include "funcoes.php";
require "login_verifica.php";


$paravenda = $_POST["paravenda"];
$tiponegociacao = $_POST["tiponegociacao"];


if ($paravenda==-1) {
        $filtro_produto_tabela=" ";
        $filtro_produto_valor= " ";
    } else if ($paravenda==1) {
        $filtro_produto_tabela=" JOIN mestre_produtos_tipo ON (mesprotip_produto=pro_codigo) ";
        $filtro_produto_valor= " AND mesprotip_tipo=$tiponegociacao AND pro_evendido=1 ";
    } else if ($paravenda==0) {
         $filtro_produto_tabela=" ";
         $filtro_produto_valor= " AND pro_evendido=0 ";           
    }
$sql = "
    SELECT *
    FROM produtos 
    $filtro_produto_tabela
    join produtos_tipo on pro_tipocontagem=protip_codigo
    left JOIN produtos_recipientes on (prorec_codigo=pro_recipiente)
    WHERE pro_cooperativa='$usuario_cooperativa'
    AND pro_controlarestoque=1 
    $filtro_produto_valor
    ORDER BY pro_nome, pro_tamanho, pro_cor, pro_descricao
";

$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    echo ""; //Não há registros
} else {
    echo "<option value=''>Selecione</option>";
    while ($dados = mysql_fetch_assoc($query)) {
    	$pro_codigo=$dados["pro_codigo"];
		$pro_nome=$dados["pro_nome"];
        $pro_recipiente=$dados["prorec_nome"];
        $pro_volume=$dados["pro_volume"];
        $pro_marca=$dados["pro_marca"];
        $pro_sigla=$dados["protip_sigla"];
        $pro_referencia=$dados["pro_referencia"];
        $pro_tamanho=$dados["pro_tamanho"];
        $pro_cor=$dados["pro_cor"];
        $pro_descricao=$dados["pro_descricao"];
        $pro_nome2="$pro_nome $pro_tamanho $pro_cor $pro_descricao";
            //pro_codigo,pro_nome,prorec_nome,pro_volume,pro_marca,protip_sigla
            $tpl->OPTION2_TEXTO = "";

        echo "<option value='$pro_codigo'>$pro_nome2 $pro_marca $pro_recipiente $pro_volume ($pro_sigla)</option>";
    }
}

?>
