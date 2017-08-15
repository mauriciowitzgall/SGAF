
<?php

$tipopagina = "pagamentos";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

?> 
<script type="text/javascript" src="saidas_pagamentos_cadastrar.js"></script> 
<?php 

$saida=$_GET["saida"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PAGAMENTOS";
$tpl_titulo->SUBTITULO = "CADASTRO DE PAGAMENTOS DE UMA VENDA À RECEBER";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "saidas_pagamentos3.png";
$tpl_titulo->show();

$tpl = new Template("templates/cadastro1.html");

$tpl->FORM_LINK="saidas_pagamentos_cadastrar2.php?saida=$saida"; 
$tpl->FORM_TARGET="";
$tpl->FORM_NOME="form1";  
$tpl->block("BLOCK_FORM");


$sql="SELECT * FROM saidas LEFT JOIN pessoas on sai_consumidor = pes_codigo WHERE sai_codigo=$saida";
if (!$query=mysql_query($sql)) die("Erro SQL Filtros que mostram dados da saída: " . mysql_error());
$dados=mysql_fetch_assoc($query);
$consumidor_nome=$dados["pes_nome"];
$datavenda=$dados["sai_datacadastro"];
$horavenda=$dados["sai_horacadastro"];
$qtd_parcelas=$dados["sai_qtdparcelas"];

//Venda
$tpl->COLUNA_TAMANHO = "200px";
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->TITULO = "Venda";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "Venda";
$tpl->CAMPO_VALOR = "$saida";
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Data da Venda
$tpl->COLUNA_TAMANHO = "200px";
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->TITULO = "Data da Venda";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "datavenda";
$tpl->CAMPO_VALOR = converte_data($datavenda)." ".substr($horavenda,0,5);
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Consumidor
$tpl->COLUNA_TAMANHO = "200px";
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->TITULO = "Consumidor";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "consumidor";
$tpl->CAMPO_VALOR = "$consumidor_nome";
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Quantidade de parcelas
$tpl->COLUNA_TAMANHO = "200px";
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->TITULO = "Qtd. Parcelas";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "qtd_parcelas";
$tpl->CAMPO_VALOR = "$qtd_parcelas"."x";
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");



//Método Pagamento
$tpl->COLUNA_TAMANHO = "200px";
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->TITULO = "Met. Pagamento";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->SELECT_NOME="metpag";
$tpl->SELECT_ID="metpag";
$tpl->SELECT_TAMANHO="";               
$tpl->SELECT_AOTROCAR="";
$tpl->SELECT_AOCLICAR=""; 
$tpl->block("BLOCK_SELECT_AUTOFOCO");
//$tpl->block("BLOCK_SELECT_DESABILITADO");
$tpl->block("BLOCK_SELECT_OBRIGATORIO");
$tpl->block("BLOCK_SELECT_PADRAO");          
//$tpl->SELECT_CLASSE="";
//$tpl->block("BLOCK_SELECT_DINAMICO");            
//$tpl->block("BLOCK_OPTION_PADRAO");
//$tpl->block("BLOCK_OPTION_TODOS");
$sql="SELECT * FROM metodos_pagamento ORDER BY metpag_codigo";
if (!$query=mysql_query($sql)) die("Erro SQL " . mysql_error());
while ($dados=mysql_fetch_assoc($query)) {
	$metpag_codigo=$dados["metpag_codigo"];
	$metpag_nome=$dados["metpag_nome"];
	$tpl->OPTION_VALOR="$metpag_codigo";
	$tpl->OPTION_TEXTO="$metpag_nome";
	//$tpl->block("BLOCK_OPTION_SELECIONADO");
	$tpl->block("BLOCK_OPTION");
}
$tpl->block("BLOCK_SELECT");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");


//Valor
$tpl->COLUNA_TAMANHO = "200px";
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->TITULO = "Valor";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->CAMPO_ESTILO="width:120px;";
$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "valor";
$tpl->CAMPO_VALOR = "$valor";
$tpl->block("BLOCK_CAMPO_PADRAO");
//$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");


//Observação
$tpl->COLUNA_TAMANHO = "200px";
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->TITULO = "Observação";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->CAMPO_ESTILO="width:300px;";
$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "obs";
$tpl->CAMPO_VALOR = "$obs";
$tpl->block("BLOCK_CAMPO_PADRAO");
//$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");


$tpl->block("BLOCK_BR2");
$tpl->show();

$tpl4 = new Template("templates/botoes1.html");

$tpl4->block("BLOCK_LINHAHORIZONTAL_EMCIMA");

//Botão Salvar
$tpl4->COLUNA_LINK_ARQUIVO = "";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "left";
//$tpl4->block("BLOCK_BOTAOPADRAO_DESABILITADO");
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl4->block("BLOCK_BOTAOPADRAO_SALVAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Botão Cancelar
$tpl4->COLUNA_LINK_ARQUIVO = "saidas_pagamentos.php?saida=$saida";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "left";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl4->block("BLOCK_BOTAOPADRAO_CANCELAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");
$tpl4->block("BLOCK_LINHA");
$tpl4->block("BLOCK_BOTOES");


$tpl4->show();

?>