<?php

require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "saidas";

include "includes.php";
//include "funcoes.php";

//Verifica se o usuário é um caixa e não tem caixa aberto, se sim não pode acessar as vendas
if (($usuario_caixa_operacao=="")&&($usuario_grupo==4)) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$saida = $_POST["saida"];
$passo = $_POST["passo"];
$tiposai = $_REQUEST["tiposai"];
$descper = number_format($_POST["descper"],2,'.','');
$descval = number_format(dinheiro_para_numero($_POST["descval"]),2,'.','');
$total = dinheiro_para_numero($_POST["total2"]);
$total = number_format($total,2,'.','');

$dinheiro = number_format(dinheiro_para_numero($_POST["dinheiro"]),2,'.','');
$troco = number_format($dinheiro - $total,2,'.','');
$areceber = $_REQUEST["areceber"];
$metodopag = $_REQUEST["metodopag"];

//print_r($_REQUEST);


//Valor bruto
$sql = "SELECT * FROM saidas JOIN saidas_produtos ON (saipro_saida=sai_codigo) WHERE sai_codigo=$saida";
$query = mysql_query($sql);
if (!$query) {
    die("Erro de SQL: " . mysql_error());
}
$valbru = 0;
while ($dados = mysql_fetch_assoc($query)) {
    $total_item = $dados["saipro_valortotal"];
    $valbru = $valbru + $total_item;
}
/*
echo "<br>valbru=$valbru<br>";
echo "descper=$descper<br>";
echo "descval=$descval<br>";
echo "total=$total<br>";
echo "dinheiro=$dinheiro<br>";
echo "troco=$troco<br>";
*/

if (($dinheiro <= $total) && ($passo == 2)) {

    echo "
        <script language='javaScript'>
            window.location.href='saidas_cadastrar3.php?troco_devolvido=0&passo=2&saida=$saida&total2=$total&descper2=$descper&descval2=$descval&dinheiro2=$dinheiro&troco2=$troco&troco_devolvido=$troco_devolvido&valbru2=$valbru&areceber2=$areceber&metodopag2=$metodopag&tiposai=$tiposai'
        </script>";   
}


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "SAÍDAS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "saidas.png";
$tpl_titulo->show();

//Inicio do Template Principal
$tpl = new Template("saidas_cadastrar2.html");


$tpl->VALBRU_VALOR = "R$ " . number_format($valbru, 2, ',', '.');
$tpl->VALBRU2_VALOR = $valbru;


//A receber
$tpl->AREC_OPTION_VALOR = "1";
$tpl->AREC_OPTION_TEXTO = "Sim";
if ($areceber == 1)
    $tpl->AREC_OPTION_SELECIONADO = " selected ";
else
    $tpl->AREC_OPTION_SELECIONADO = "  ";
$tpl->block("BLOCK_AREC_OPTION");
$tpl->AREC_OPTION_VALOR = "0";
$tpl->AREC_OPTION_TEXTO = "Não";
if ($areceber == 0)
    $tpl->AREC_OPTION_SELECIONADO = " selected ";
else
    $tpl->AREC_OPTION_SELECIONADO = " ";
$tpl->block("BLOCK_AREC_OPTION");


//Método de pagamento
$sql = "SELECT * FROM metodos_pagamento ORDER BY metpag_codigo";
$query = mysql_query($sql);
if (!$query)
    die("Erro de SQL: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $tpl->METPAG_OPTION_VALOR = $dados[0];
    $tpl->METPAG_OPTION_TEXTO = $dados[1];
    if ($metodopag == $dados[0])
        $tpl->METPAG_OPTION_SELECIONADO = " selected ";
    else
        $tpl->METPAG_OPTION_SELECIONADO = "  ";
    $tpl->block("BLOCK_METPAG_OPTION");
}



switch ($passo) {
    case '1':
        $tpl->LINK = "saidas_cadastrar2.php?tiposai=$tiposai";
        $tpl->DESCPER_VALOR = "0,00";
        $tpl->DESCPER2_VALOR = "0";
        $tpl->DESCVAL_VALOR = "R$ 0,00";
        $tpl->DESCVAL2_VALOR = "0";
        $tpl->TOTAL_VALOR = "R$ " . number_format($valbru, 2, ',', '.');
        $tpl->TOTAL2_VALOR = number_format($valbru, 2, '.', '');
        
        $tpl->DINHEIRO_VALOR = "";
        $passo = 2;
        break;
    case '2':
        $tpl->LINK = "saidas_cadastrar3.php?tiposai=$tiposai";
        $tpl->block("BLOCK_PASSO2");
        $tpl->block("BLOCK_OCULTOS2");


        $tpl->DESCPER_VALOR = number_format($descper,2,',','');
        $tpl->DESCPER2_VALOR = $descper;
        $tpl->DESCPER_DESABILITADO = "disabled";
        $tpl->DESCVAL_VALOR = "R$ ".number_format($descval,2,',','.');
        $tpl->DESCVAL2_VALOR = $descval;
        $tpl->DESCVAL_DESABILITADO = "disabled";
        $tpl->TOTAL_VALOR = "R$ " . number_format($total, 2, ',', '.');
        $tpl->TOTAL2_VALOR = $total;
        $tpl->DINHEIRO_VALOR = "R$ " . number_format($dinheiro, 2, ',', '.');
        $tpl->DINHEIRO2_VALOR = $dinheiro;
        $tpl->DINHEIRO_DESABILITADO = "disabled";

        $tpl->AREC_DESABILITADO = "disabled";
        $tpl->METPAG_DESABILITADO = "disabled";
        
        //Calcula o troco
        $tpl->TROCO_VALOR = "R$ ".  number_format($troco,2,',','.');
        $tpl->TROCO2_VALOR = $troco;
        $tpl->METPAG_VALOR = "$metodopag";
        $tpl->AREC_VALOR = "$areceber";



        break;
}

$tpl->PASSO = $passo;
$tpl->SAIDA = $saida;

$tpl->show();


include "rodape.php";
//Verificar se existe no estoque todas as quantidades dos produtos solicitados
//Reitar 
?>