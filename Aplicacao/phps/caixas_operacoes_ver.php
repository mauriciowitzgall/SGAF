<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operacoes_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "caixas";
include "includes.php";

$operacao=$_GET["operacao"];
$numero=$_REQUEST["codigo"];

//Pega dados atuais do caixa aberto
$sql="
    SELECT * 
    FROM caixas_operacoes 
    JOIN caixas on cai_codigo=caiopo_caixa 
    JOIN pessoas on pes_codigo=caiopo_operador 
    WHERE caiopo_numero=$numero
";
if (!$query=mysql_query($sql)) die("Erro SQL 1: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$caixa_nome=$dados["cai_nome"];
$operador_nome=$dados["pes_nome"];
$datahoraabertura=$dados["caiopo_datahoraabertura"];
$datahora2=explode(" ",$datahoraabertura);
$dataabertura=$datahora2[0];
$horaabertura=$datahora2[1];
$valorinicial=$dados["caiopo_valorinicial"];
$datahoraencerramento=$dados["caiopo_datahoraencerramento"];
$totalvendas=$dados["caiopo_totalvendas"];
$totaltroco=$dados["caiopo_totaltroco"];
$totalsaldotroco=$dados["caiopo_saldovendas"];
$valorfinal=$dados["caiopo_valorfinal"];
$diferenca=$dados["caiopo_diferenca"];
$totalbruto=$dados["caiopo_totalbruto"];
$liquido=$dados["caiopo_liquido"];
$liquidosemcartao=$dados["caiopo_liquidosemcartao"];
$liquidocartao=$dados["caiopo_liquidocartao"];
$entradastotal=$dados["caiopo_entradastotal"];
$saidastotal=$dados["caiopo_saidastotal"];
$saldoentradassaidas=$dados["caiopo_saldoentradassaidas"];
$totaldescontovendas=$dados["caiopo_totaldescontovendas"];
$supervisor=$dados["caiopo_supervisor"];
$valoresperado=$dados["caiopo_valoresperado"];



//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS OPERACOES";
$tpl_titulo->SUBTITULO = "ENCERRAR CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixas_encerrar.png";
$tpl_titulo->show();


if ($usacaixa!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}


$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

$tpl->JS_CAMINHO="caixas_operacoes_encerrar.js"; 
$tpl->block("BLOCK_JS");

$tpl->LINK_DESTINO="caixas_operacoes_encerrar2.php";
$tpl->LINK_TARGET="";

//Caixa
$tpl->TITULO="Caixa";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="nome";
$tpl->CAMPO_VALOR="$caixa_nome";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Operador
$tpl->TITULO="Operador";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="operador";
$tpl->CAMPO_VALOR="$operador_nome";
$tpl->CAMPO_TAMANHO="30";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
//$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Data Abertura
$tpl->TITULO="Data Abertura";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="date";
$tpl->CAMPO_NOME="dataini";
$tpl->CAMPO_VALOR="$dataabertura";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->CAMPO_ESTILO="width:140px";
$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->CAMPO_TIPO="time";
$tpl->CAMPO_NOME="horaini";
$tpl->CAMPO_VALOR="$horaabertura";
$tpl->CAMPO_ESTILO="width:110px";
$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Valor inicial
$tpl->TITULO="Valor Inicial";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="valorinicial";
$tpl->CAMPO_VALOR=  "R$ ".number_format($valorinicial,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Vendas Título
$tpl->TEXTO_ID="";
$tpl->TEXTO="<br> ";
$tpl->block("BLOCK_TEXTO");
$tpl->TEXTO_ID="";
$tpl->TEXTO="<b>Vendas</b>";
$tpl->block("BLOCK_TEXTO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Total Bruto
$tpl->TITULO="Total Bruto Vendido Recebido";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalbruto";
$tpl->CAMPO_VALOR=  "R$ ".number_format($totalbruto,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Total Vendido Desconto
$tpl->TITULO="Total Desc. nas Vendas Recebidas";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totaldescontovendas";
$tpl->CAMPO_VALOR=  "R$ ".number_format($totaldescontovendas,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Total Saldo Troco
$tpl->TITULO="Total Saldo Troco das Vendas";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalsaldotroco";
$tpl->CAMPO_VALOR=  "R$ ".number_format($totalsaldotroco,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Total Liquido Vendido
$tpl->TITULO="Total Liquido Vendido";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalliquido";
$tpl->CAMPO_VALOR=  "R$ ".number_format($liquido,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Total liquido sem cartão
$tpl->TITULO="Total Liquido Sem Cartão ";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalliquidosemcartao";
$tpl->CAMPO_VALOR=  "R$ ".number_format($liquidosemcartao,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Total liquido no cartão
$tpl->TITULO="Total Liquido No Cartão ";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalliquidocartao";
$tpl->CAMPO_VALOR=  "R$ ".number_format($liquidocartao,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Entradas e Saídas Título
$tpl->TEXTO_ID="";
$tpl->TEXTO="<br> ";
$tpl->block("BLOCK_TEXTO");
$tpl->TEXTO_ID="";
$tpl->TEXTO="<b>Fluxo de caixa</b>";
$tpl->block("BLOCK_TEXTO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Total Entradas
$tpl->TITULO="Total Entradas";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalentradas";
$tpl->CAMPO_VALOR=  "R$ ".number_format($entradastotal,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Total Saidas
$tpl->TITULO="Total Saídas";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalsaidas";
$tpl->CAMPO_VALOR=  "R$ ".number_format($saidastotal,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Saldo Entradas/Saídas
$tpl->TITULO="Saldo Entradas/Saídas";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="saldoentradassaidas";
$tpl->CAMPO_VALOR=  "R$ ".number_format($saldoentradassaidas,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Entradas e Saídas Título
$tpl->TEXTO_ID="";
$tpl->TEXTO="<br> ";
$tpl->block("BLOCK_TEXTO");
$tpl->TEXTO_ID="";
$tpl->TEXTO="<b></b>";
$tpl->block("BLOCK_TEXTO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Total Esperado
$tpl->TITULO="Total Esperado";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalesperado";
$tpl->CAMPO_VALOR=  "R$ ".number_format($valoresperado,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Valor final do caixa
$tpl->TITULO="Valor Final Caixa";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="valorfinaldocaixa";
$tpl->CAMPO_VALOR=  "R$ ".number_format($valorfinal,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Diferença
$tpl->TITULO="Diferença";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="diferencadocaixa";
$tpl->CAMPO_ONBLUR="popula_diferenca(this.value)";
$tpl->CAMPO_VALOR=  "R$ ".number_format($diferenca,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");
//Supervisor
$tpl->TITULO="Supervisor";
$tpl->block("BLOCK_TITULO");
if ($supervisor!="") {
    $sql="SELECT pes_nome FROM pessoas WHERE pes_codigo=$supervisor";
    if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $supervisor_nome=$dados["pes_nome"];
}
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="supervisordocaixa";
$tpl->CAMPO_ONBLUR="";
$tpl->CAMPO_VALOR=  "$supervisor_nome";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Botão Voltar
$tpl->block("BLOCK_BOTAO_VOLTAR");

$tpl->block("BLOCK_BOTOES");

$tpl->show();
include "rodape.php";
?>
