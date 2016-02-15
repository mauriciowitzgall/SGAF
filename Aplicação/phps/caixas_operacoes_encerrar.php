<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operacoes_encerrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "caixas";
include "includes.php";



$operacao=$_GET["operacao"];
$numero=$_REQUEST["codigo"];
$datahoraatual=date("Y-m-d H:i:s");

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

//Verifica produtos vendidos pelo caixa em questão
$sql="
SELECT ROUND(sum(sai_totalcomdesconto),2) as totalvendido,
ROUND(sum((sai_totalliquido-sai_totalcomdesconto)),2) as totalsaldotroco ,
ROUND(sum(sai_totalbruto),2) as totalbruto ,
ROUND(sum((sai_totalcomdesconto-sai_totalbruto)),2) as totaldescontovendas ,
ROUND(sum(sai_totalliquido),2) as totalliquido
FROM saidas 
WHERE sai_caixaoperacaonumero=$numero
and sai_areceber = 0
";
if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$totalvendido=$dados["totalvendido"];
$totalsaldotroco=$dados["totalsaldotroco"];
$totalliquido=$dados["totalliquido"];
$totaldescontovendas=$dados["totaldescontovendas"];
$totalbruto=$dados["totalbruto"];

//Total a receber
$sql="
SELECT ROUND(sum(sai_totalliquido),2) as totalliquido
FROM saidas 
WHERE sai_caixaoperacaonumero=$numero
AND sai_areceber=1
";
if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$totaareceber=$dados["totalliquido"];

//Total liquido no Cartão
$sql="
SELECT ROUND(sum(sai_totalliquido),2) as totalliquido
FROM saidas 
WHERE sai_caixaoperacaonumero=$numero
AND sai_metpag in (2,3)
AND sai_areceber=0
";
if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$totalliquidocartao=$dados["totalliquido"];

//Total liquido sem cartão
$sql="
SELECT ROUND(sum(sai_totalliquido),2) as totalliquido
FROM saidas 
WHERE sai_caixaoperacaonumero=$numero
AND sai_metpag not in (2,3)
AND sai_areceber=0
";
if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$totalliquidosemcartao=$dados["totalliquido"];

//Total Entrada
$sql="
SELECT ROUND(sum(caientsai_valor),2) as entradatotal
FROM caixas_entradassaidas 
WHERE caientsai_numerooperacao=$numero
AND caientsai_tipo=1
";
if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$entradatotal=$dados["entradatotal"];

//Total Saida
$sql="
SELECT ROUND(sum(caientsai_valor),2) as saidatotal
FROM caixas_entradassaidas 
WHERE caientsai_numerooperacao=$numero
AND caientsai_tipo=2
";
if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$saidatotal=$dados["saidatotal"];

$saldo_entradassaidas=$entradatotal-$saidatotal;
$valoresperado=$valorinicial+$totalliquido+$saldo_entradassaidas;


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS OPERACOES";
$tpl_titulo->SUBTITULO = "ENCERRAR CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixas_encerrar.png";
$tpl_titulo->show();

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
$sql="SELECT cai_nome FROM caixas WHERE cai_codigo=$caixa";
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
$tpl->CAMPO_VALOR=  "R$ ".number_format($totalliquido,2,",",".");
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
$tpl->CAMPO_VALOR=  "R$ ".number_format($totalliquidosemcartao,2,",",".");
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
$tpl->CAMPO_VALOR=  "R$ ".number_format($totalliquidocartao,2,",",".");
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
$tpl->CAMPO_VALOR=  "R$ ".number_format($entradatotal,2,",",".");
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
$tpl->CAMPO_VALOR=  "R$ ".number_format($saidatotal,2,",",".");
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
$tpl->CAMPO_VALOR=  "R$ ".number_format($saldo_entradassaidas,2,",",".");
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
$tpl->CAMPO_NOME="valorfinal";
$tpl->CAMPO_VALOR=  "";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Diferença
$tpl->TITULO="Diferença";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="diferenca";
$tpl->CAMPO_ONBLUR="popula_diferenca(this.value)";
$tpl->CAMPO_VALOR=  "";
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
$tpl->SELECT_NOME="supervisor";
$tpl->SELECT_ID="";
$tpl->SELECT_TAMANHO="";
$tpl->SELECT_ONCHANGE="";
$tpl->block("BLOCK_SELECT_NORMAL"); //Classe campopadrao
$tpl->block("BLOCK_SELECT_OBRIGATORIO");
$sql="SELECT * 
FROM quiosques_supervisores
JOIN pessoas on (pes_codigo=quisup_supervisor)
JOIN quiosques on (quisup_quiosque=qui_codigo)
WHERE qui_codigo=$usuario_quiosque";
if (!$query=mysql_query($sql)) die("Erro SQL 3: " . mysql_error());
$tpl->block("BLOCK_SELECT_OPTION_PADRAO"); //Selecione
while ($dados = mysql_fetch_assoc($query)) {
    
    $tpl->OPTION_VALOR=$dados["pes_codigo"];
    $tpl->block("BLOCK_SELECT_OPTION_SELECIONADO");
    $tpl->OPTION_NOME=$dados["pes_nome"];
    $tpl->block("BLOCK_SELECT_OPTION");
}
$tpl->block("BLOCK_SELECT");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Senha
$tpl->TITULO="Senha do Supervisor";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="password";
$tpl->CAMPO_NOME="senha";
$tpl->CAMPO_VALOR=  "";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO_HISTORICO_DESATIVADO"); //autocomplete do navegador desligado
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Campo oculto
$tpl->CAMPOOCULTO_VALOR="$caixa";
$tpl->CAMPOOCULTO_NOME="caixa2";
$tpl->block("BLOCK_CAMPOSOCULTOS");



//Total Bruto Vendido
$tpl->CAMPOOCULTO_VALOR="$totalbruto";
$tpl->CAMPOOCULTO_NOME="totalbruto2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Total Desconto nas Vendas
$tpl->CAMPOOCULTO_VALOR="$totaldescontovendas";
$tpl->CAMPOOCULTO_NOME="totaldescontovendas2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Total Saldo Troco
$tpl->CAMPOOCULTO_VALOR="$totalsaldotroco";
$tpl->CAMPOOCULTO_NOME="totalsaldotroco2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Total Liquido Vendido
$tpl->CAMPOOCULTO_VALOR="$totalliquido";
$tpl->CAMPOOCULTO_NOME="totalliquido2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Total Liquido Vendido sem cartão
$tpl->CAMPOOCULTO_VALOR="$totalliquidosemcartao";
$tpl->CAMPOOCULTO_NOME="totalliquidosemcartao2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Total Liquido Vendido com cartão
$tpl->CAMPOOCULTO_VALOR="$totalliquidocartao";
$tpl->CAMPOOCULTO_NOME="totalliquidocartao2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Total Entradas
$tpl->CAMPOOCULTO_VALOR="$entradatotal";
$tpl->CAMPOOCULTO_NOME="entradastotal2";
$tpl->block("BLOCK_CAMPOSOCULTOS");


//Valor Saídas
$tpl->CAMPOOCULTO_VALOR="$saidatotal";
$tpl->CAMPOOCULTO_NOME="saidastotal2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Saldo Entradas Saídas
$tpl->CAMPOOCULTO_VALOR="$saldo_entradassaidas";
$tpl->CAMPOOCULTO_NOME="saldo_entradassaidas2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Valor Esperado
$tpl->CAMPOOCULTO_VALOR="$valoresperado";
$tpl->CAMPOOCULTO_NOME="valoresperado2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Diferença
$tpl->CAMPOOCULTO_VALOR="";
$tpl->CAMPOOCULTO_NOME="diferenca2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Operação 
$tpl->CAMPOOCULTO_VALOR="encerrar";
$tpl->CAMPOOCULTO_NOME="operacao2";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Numero da operação  de caixa
$tpl->CAMPOOCULTO_VALOR="$numero";
$tpl->CAMPOOCULTO_NOME="numero";
$tpl->block("BLOCK_CAMPOSOCULTOS");


//Alterar o quiosque padrão de todos usuários que tem o quiosque em questão definido




//Botão Salvar
$tpl->block("BLOCK_BOTAO_SALVAR");

//Botão Cancelar
$tpl->BOTAO_LINK="caixas.php";
$tpl->block("BLOCK_BOTAO_CANCELAR");

$tpl->block("BLOCK_BOTOES");

$tpl->show();
include "rodape.php";
?>
