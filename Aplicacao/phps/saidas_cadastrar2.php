 <?php

require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "saidas";

include "includes.php";
//include "funcoes.php";

if ($usavendas!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO VENDAS</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}


//Verifica se o usuário é um caixa e não tem caixa aberto, se sim não pode acessar as vendas
if (($usuario_caixa_operacao=="")&&($usuario_grupo==4)) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$saida = $_POST["saida"];
$passo = $_POST["passo"];
$tiposai = $_REQUEST["tiposai"];
$descper=$_POST["descper"];
$descper = str_replace('.', '', $descper);
$descper = str_replace(',', '.', $descper);
$descper = number_format($descper,2,'.','');
$descval = number_format(dinheiro_para_numero($_POST["descval"]),2,'.','');
$total = dinheiro_para_numero($_POST["total2"]);
$total = number_format($total,2,'.','');

$dinheiro = number_format(dinheiro_para_numero($_POST["dinheiro"]),2,'.','');
$recebidodinheiro = number_format(dinheiro_para_numero($_POST["recebidodinheiro"]),2,'.','');
$recebidocartao = number_format(dinheiro_para_numero($_POST["recebidocartao"]),2,'.','');
$cartaobandeira = $_POST["cartaobandeira"];
if ($dinheiro==0) $dinheiro = $recebidodinheiro + $recebidocartao;
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

if ((($dinheiro <= $total) && ($passo == 2))||(($metodopag==6)||($metodopag==7))) {

    echo "
        <script language='javaScript'>
            window.location.href='saidas_cadastrar3.php?troco_devolvido=0&passo=2&saida=$saida&total2=$total&descper2=$descper&descval2=$descval&dinheiro2=$dinheiro&troco2=$troco&troco_devolvido=$troco_devolvido&valbru2=$valbru&areceber2=$areceber&metodopag2=$metodopag&tiposai=$tiposai&recebidodinheiro=$recebidodinheiro&recebidocartao=$recebidocartao&cartaobandeira=$cartaobandeira'
        </script>";   
}


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
if ($tiposai == 1) {
    $tpl_titulo->TITULO = "VENDAS";
    $tpl_titulo->SUBTITULO = "REALIZAÇÃO DE VENDAS";
    $tpl_titulo->NOME_ARQUIVO_ICONE = "vendas.png";
} else {
    $tpl_titulo->TITULO = "SAÍDAS";
    $tpl_titulo->SUBTITULO = "RETIRADAS DE ESTOQUE";
    $tpl_titulo->NOME_ARQUIVO_ICONE = "saidas.png";
}
$tpl_titulo->ICONES_CAMINHO = "$icones";
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
$query = mysql_query($sql); if (!$query)  die("Erro de SQL: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $tpl->METPAG_OPTION_VALOR = $dados[0];
    $tpl->METPAG_OPTION_TEXTO = $dados[1];
    if ($metodopag == $dados[0]) $tpl->METPAG_OPTION_SELECIONADO = " selected ";
    else $tpl->METPAG_OPTION_SELECIONADO = "  ";
    $tpl->block("BLOCK_METPAG_OPTION");
}



//Cartão Bandeira
$sql = "SELECT * FROM cartoes_bandeira ORDER BY carban_codigo";
$query = mysql_query($sql);
if (!$query) die("Erro de SQL Cartão Bandeira: " . mysql_error());
$tpl->CARTAOBANDEIRA_OPTION_VALOR = "";
$tpl->CARTAOBANDEIRA_OPTION_TEXTO = "Selecione";  
$tpl->block("BLOCK_CARTAOBANDEIRA_OPTION");
while ($dados = mysql_fetch_array($query)) {
    $tpl->CARTAOBANDEIRA_OPTION_VALOR = $dados[0];
    $tpl->CARTAOBANDEIRA_OPTION_TEXTO = $dados[1];
    if ($cartaobandeira == $dados[0])
        $tpl->CARTAOBANDEIRA_OPTION_SELECIONADO = " selected ";
    else
        $tpl->CARTAOBANDEIRA_OPTION_SELECIONADO = "  ";
    $tpl->block("BLOCK_CARTAOBANDEIRA_OPTION");
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

$tpl->PERMITEVENDASARECEBER = $permitevendasareceber;
$tpl->PASSO = $passo;
$tpl->SAIDA = $saida;

$tpl->show();


include "rodape.php";
//Verificar se existe no estoque todas as quantidades dos produtos solicitados
//Reitar 
?>
