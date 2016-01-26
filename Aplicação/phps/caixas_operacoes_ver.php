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
$saldovendas=$dados["caiopo_saldovendas"];
$valorfinal=$dados["caiopo_valorfinal"];
$diferenca=$dados["caiopo_diferenca"];
$supervisor=$dados["caiopo_supervisor"];



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

//Total Vendido
$tpl->TITULO="Total Vendido";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalvendido";
$tpl->CAMPO_VALOR=  "R$ ".number_format($totalvendas,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Total Saldo Troco
$tpl->TITULO="Total Saldo Troco";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalsaldotroco";
$tpl->CAMPO_VALOR=  "R$ ".number_format($totaltroco,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Total Saldo Vendas
$tpl->TITULO="Total Saldo Vendas";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalsaldotroco";
$tpl->CAMPO_VALOR=  "R$ ".number_format($saldovendas,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Total Esperado
$tpl->TITULO="Total Esperado";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="totalesperado";
$valoresperado=$valorinicial+$saldovendas;
$tpl->CAMPO_VALOR=  "R$ ".number_format($valoresperado,2,",",".");
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Valor Final
$tpl->TITULO="Valor Final do Caixa";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="valorfinal2";
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
$tpl->CAMPO_NOME="diferenca2";
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
$tpl->CAMPO_NOME="supervisor";
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
