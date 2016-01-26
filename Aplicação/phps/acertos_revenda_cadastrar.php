<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($quiosque_revenda <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "negociacoes";
include "includes.php";
include "controller/classes.php";

$passo = $_REQUEST["passo"];
if (!$passo)
    $passo = 1;
//$passo = $obj->ehnumerico($passo

$dataini = $_REQUEST["dataini"];
$horaini = $_REQUEST["horaini"];
$datafim = $_REQUEST["datafim"];
$horafim = $_REQUEST["horafim"];
$dataatual = date("Y-m-d");
$horaatual = date("H:i");

if ($datafim == "")
    $datafim = date("Y-m-d");
if ($horafim == "")
    $horafim = date("H:i");
if (($datafim == "") || ($horafim == ""))
    $datafim_datetime = date("Y-m-d H:i");
else
    $datafim_datetime = $datafim . " " . $horafim;


$detprodutos = $_REQUEST["detprodutos"];
$detfornecedores = $_REQUEST["detfornecedores"];


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "FECHAMENTO DE REVENDAS";
$tpl_titulo->SUBTITULO = "CADASTRO DE FECHAMENTOS DE REVENDAS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "revenda.png";
$tpl_titulo->show();



$tpl = new Template("templates/cadastro1.html");
$tpl->FORM_NOME = "form1";
$tpl->FORM_TARGET = "";
$tpl->FORM_LINK = "acertos_revenda_cadastrar.php?passo=2";
$tpl->block("BLOCK_FORM");
$tpl->block("BLOCK_QUEBRA");


//Período
//Pegar data e hora inicial
//O sistema sempre pega a data e hora do ultimo fechamento, caso não tenha nenhum efetuado, pega-se a data e hora da primeira venda
$sql="SELECT max(fch_codigo) FROM fechamentos";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$dados=  mysql_fetch_array($query);
$ultimo = $dados[0];

if ($ultimo > 0) {
    $sql2="SELECT fch_datafim FROM fechamentos WHERE fch_codigo=$ultimo";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_array($query2);
    $dataini_datetime = $dados2[0];
}   
else {
    $sql2="SELECT min(sai_codigo) as primeirasaida
    FROM saidas
    JOIN saidas_produtos ON (saipro_saida=sai_codigo)
    JOIN entradas on (saipro_lote=ent_codigo)
    WHERE sai_quiosque=$usuario_quiosque
    AND saipro_fechado=0
    AND ent_tiponegociacao=2     
    AND sai_tipo=1
    AND sai_status=1 ";

    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_array($query2);
    
    $primeirasaida = $dados2[0];    

    $sql3 = "SELECT sai_datacadastro,sai_horacadastro FROM saidas WHERE sai_codigo=$primeirasaida ";
      $query3 = mysql_query($sql3);
    if (!$query3)
        die("Erro: " . mysql_error());
    $dados3 = mysql_fetch_assoc($query3);
    $dataini_datetime = $dados3['sai_datacadastro'] . " " . $dados3['sai_horacadastro'];

//Pegar data e hora final
//O sistema pega data atual como referencia para autopreencher os campos.
}

$dat = explode(' ', $dataini_datetime);
$dataini = $dat[0];
$horaini = $dat[1];
$horaini = explode(':', $horaini);
$horaini = $horaini[0] . ":" . $horaini[1];
$datafim_minimo = $dataini;
$horafim_minimo = $horaini;

//De
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "200px";
$tpl->TITULO = "De";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
//Data inicial
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "date";
$tpl->CAMPO_ESTILO="width:140px";
$tpl->block("BLOCK_CAMPO_ESTILO");
//$tpl->CAMPO_TAMANHO = "8";
$tpl->CAMPO_DICA = "";
$tpl->CAMPO_NOME = "dataini";
$tpl->CAMPO_ID = "dataini";
$tpl->CAMPO_AOCLICAR = "";
$tpl->CAMPO_ONKEYUP = "";
$tpl->CAMPO_ONBLUR = "";
$tpl->CAMPO_VALOR = $dataini;

//$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");

//Hora inicial
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "time";
//$tpl->CAMPO_TAMANHO = "6";
$tpl->CAMPO_ESTILO="width:80px";
$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->CAMPO_DICA = "";
$tpl->CAMPO_NOME = "horaini";
$tpl->CAMPO_ID = "horaini";
$tpl->CAMPO_AOCLICAR = "";
$tpl->CAMPO_ONKEYUP = "";
$tpl->CAMPO_ONBLUR = "";
$tpl->CAMPO_VALOR = $horaini;
//$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Até
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "200px";
$tpl->TITULO = "Até";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");

//Data final
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "date";
//$tpl->CAMPO_TAMANHO = "8";
$tpl->CAMPO_ESTILO="width:140px";
$tpl->block("BLOCK_CAMPO_ESTILO");
if ($passo == 2) {
    $tpl->CAMPO_VALOR =$datafim;
} else {
    $tpl->CAMPO_MAX = "$dataatual";
    $tpl->block("BLOCK_CAMPO_MAX");
    $tpl->CAMPO_VALOR = "$datafim";
    $tpl->CAMPO_MIN = "$datafim_minimo";
    $tpl->block("BLOCK_CAMPO_MIN");
}
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->CAMPO_DICA = "";
$tpl->CAMPO_NOME = "datafim";
$tpl->CAMPO_ID = "datafim";
$tpl->CAMPO_AOCLICAR = "";
$tpl->CAMPO_ONKEYUP = "";
$tpl->CAMPO_ONBLUR = "";
if ($passo == 2)
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");

//Hora final
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "time";
//$tpl->CAMPO_TAMANHO = "6";
$tpl->CAMPO_ESTILO="width:80px";
$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->CAMPO_VALOR = $horafim;
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->CAMPO_DICA = "";
$tpl->CAMPO_NOME = "horafim";
$tpl->CAMPO_ID = "horafim";
$tpl->CAMPO_AOCLICAR = "";
$tpl->CAMPO_ONKEYUP = "";
$tpl->CAMPO_ONBLUR = "";
if ($passo == 2)
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

if ($passo == 1) {

//Detalhes produtos (Sim ou Não)
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->TITULO = "Detalhes Produtos";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->SELECT_NOME = "detprodutos";
    $tpl->SELECT_ID = "detprodutos";
    $tpl->SELECT_TAMANHO = "";
//$tpl->SELECT_AOCLICAR="";
//$tpl->block("BLOCK_SELECT_AUTOFOCO");
//$tpl->block("BLOCK_SELECT_DESABILITADO");
    $tpl->block("BLOCK_SELECT_OBRIGATORIO");
    $tpl->block("BLOCK_SELECT_PADRAO");
    $tpl->OPTION_VALOR = "0";
    $tpl->OPTION_TEXTO = "Não";
    $tpl->block("BLOCK_OPTION");
    $tpl->OPTION_VALOR = "1";
    $tpl->OPTION_TEXTO = "Sim";
    $tpl->block("BLOCK_OPTION_SELECIONADO");
    $tpl->block("BLOCK_OPTION");
    $tpl->block("BLOCK_SELECT");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");

//Detalhes Fornecedores (SIM ou NAO)
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->TITULO = "Detalhes Fornecedores";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->SELECT_NOME = "detfornecedores";
    $tpl->SELECT_ID = "detfornecedores";
    $tpl->SELECT_TAMANHO = "";
//$tpl->SELECT_AOCLICAR="";
//$tpl->block("BLOCK_SELECT_AUTOFOCO");
//$tpl->block("BLOCK_SELECT_DESABILITADO");
    $tpl->block("BLOCK_SELECT_OBRIGATORIO");
    $tpl->block("BLOCK_SELECT_PADRAO");
    $tpl->OPTION_VALOR = "0";
    $tpl->OPTION_TEXTO = "Não";
    $tpl->block("BLOCK_OPTION");
    $tpl->OPTION_VALOR = "1";
    $tpl->OPTION_TEXTO = "Sim";
    $tpl->block("BLOCK_OPTION_SELECIONADO");
    $tpl->block("BLOCK_OPTION");
    $tpl->block("BLOCK_SELECT");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");


//Botão Continuar
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->COLUNA_ROWSPAN = "";
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->COLUNA_ROWSPAN = "";
    $tpl->BOTAO_TIPO = "submit";
    $tpl->BOTAO_VALOR = "CONTINUAR";
    $tpl->BOTAO_CLASSE = "botaopadraocadastrar";
    $tpl->block("BLOCK_BOTAO_PADRAO");
    $tpl->block("BLOCK_BOTAO");
    //$tpl->block("BLOCK_BR");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
}
$tpl->show();
echo "<br>";
if ($passo == 2) {

    //Verifica se tem produtos a serem acertados
    $sql = "
    SELECT saipro_produto
    FROM saidas_produtos
    JOIN entradas on (saipro_lote = ent_codigo)
    JOIN saidas on (sai_codigo = saipro_saida)
    WHERE saipro_fechado = 0
    AND ent_tiponegociacao = 2
    AND ent_quiosque = $usuario_quiosque
    AND sai_datahoracadastro BETWEEN '$dataini_datetime' AND '$datafim_datetime:59'

    AND sai_tipo = 1
    AND sai_status = 1
    ";

    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas == 0) {
        $tpl11 = new Template("templates/notificacao.html");
        $tpl11->ICONES = $icones;
        $tpl11->block("BLOCK_ATENCAO");
        $tpl11->MOTIVO = "Não há produtos de revendas vendidos no período selecionado à serem acertados.<br> Portanto não é possível realizar fechamento!";
        $tpl11->block("BLOCK_MOTIVO");
        $tpl11->DESTINO = "acertos_revenda.php";
        $tpl11->block("BLOCK_BOTAO");
        $tpl11->show();
        exit;
    }

    //Resumo
    $tpl = new Template("templates/tituloemlinha_2.html");
    $tpl->block("BLOCK_TITULO");
    $tpl->LISTA_TITULO = "RESUMO";
    //$tpl->block("BLOCK_QUEBRA2");
    $tpl->show();
    $tpl2 = new Template("templates/lista2.html");
    $tpl2->block("BLOCK_TABELA_CHEIA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "TOTAL VENDA";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "TOTAL CUSTO";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "TOTAL LUCRO";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "QTD. VENDAS";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "QTD. PRODUTOS";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "QTD. LOTES";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->block("BLOCK_CABECALHO_LINHA");
    $tpl2->block("BLOCK_CABECALHO");
    //Calcula os valores totais de todos os produtos vendidos
    $sql = "
    SELECT 
    sum(saipro_quantidade * (SELECT DISTINCT entpro_valorunitario FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto)) as 'tot',
    sum(saipro_quantidade * (SELECT DISTINCT entpro_valunicusto FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto)) as 'totcusto',        
    sum((saipro_quantidade * (SELECT DISTINCT entpro_valorunitario FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto))-(saipro_quantidade * (SELECT DISTINCT entpro_valunicusto FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto))) as 'lucro'
    FROM saidas_produtos
    JOIN produtos on (saipro_produto=pro_codigo)
    JOIN entradas on (saipro_lote=ent_codigo)
    JOIN saidas on (sai_codigo=saipro_saida)
    JOIN produtos_tipo ON (protip_codigo=pro_tipocontagem)
    WHERE saipro_fechado=0
    AND ent_tiponegociacao=2     
    AND ent_quiosque=$usuario_quiosque
    AND sai_tipo=1
    AND sai_datahoracadastro BETWEEN '$dataini_datetime' AND '$datafim_datetime:59'
    AND sai_status=1            
    ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $dados = mysql_fetch_array($query);
    $venda_total = $dados[0];
    $custo_total = $dados[1];
    $lucro_total = $dados[2];
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "R$ " . number_format($venda_total, 2, ',', '.');
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "R$ " . number_format($custo_total, 2, ',', '.');
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "R$ " . number_format($lucro_total, 2, ',', '.');
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Calcula a QTD de VENDAS
    $sql3 = "
    SELECT count(DISTINCT saipro_saida)
    FROM saidas_produtos    
    JOIN entradas on (saipro_lote=ent_codigo)
    JOIN saidas on (sai_codigo=saipro_saida)
    WHERE saipro_fechado=0
    AND sai_quiosque=$usuario_quiosque
    AND ent_tiponegociacao=2     
    AND sai_tipo=1
    AND sai_datahoracadastro BETWEEN '$dataini_datetime' AND '$datafim_datetime:59'    
    AND sai_status=1         
    ";
    $query3 = mysql_query($sql3);
    if (!$query3)
        die("Erro: " . mysql_error());
    $dados3 = mysql_fetch_array($query3);
    $qtdvendas = $dados3[0];
    //Calcula a QTD de Produtos
    $sql3 = "
    SELECT count(DISTINCT saipro_produto)
    FROM saidas_produtos    
    JOIN entradas on (saipro_lote=ent_codigo)
    JOIN saidas on (sai_codigo=saipro_saida)
    WHERE saipro_fechado=0
    AND sai_quiosque=$usuario_quiosque    
    AND ent_tiponegociacao=2     
    AND sai_tipo=1
    AND sai_datahoracadastro BETWEEN '$dataini_datetime' AND '$datafim_datetime:59'
    AND sai_status=1         
    ";
    $query3 = mysql_query($sql3);
    if (!$query3)
        die("Erro: " . mysql_error());
    $dados3 = mysql_fetch_array($query3);
    $qtdprodutos = $dados3[0];
    //Calcula a QTD DE LOTES
    $sql3 = "
    SELECT count(DISTINCT ent_codigo)
    FROM saidas_produtos    
    JOIN entradas on (saipro_lote=ent_codigo)
    JOIN saidas on (sai_codigo=saipro_saida)
    WHERE saipro_fechado=0
    AND sai_quiosque=$usuario_quiosque    
    AND ent_tiponegociacao=2     
    AND sai_tipo=1
    AND sai_datahoracadastro BETWEEN '$dataini_datetime' AND '$datafim_datetime:59'
    AND sai_status=1         
    ";
    $query3 = mysql_query($sql3);
    if (!$query3)
        die("Erro: " . mysql_error());
    $dados3 = mysql_fetch_array($query3);
    $qtdlotes = $dados3[0];
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->TEXTO = "$qtdvendas";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->TEXTO = "$qtdprodutos";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->TEXTO = "$qtdlotes";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->block("BLOCK_LINHA");
    $tpl2->block("BLOCK_CORPO");
    $tpl2->block("BLOCK_LISTAGEM");
    $tpl2->show();

    echo "<br>";


    //Taxas    
    $tpl = new Template("templates/tituloemlinha_2.html");
    $tpl->block("BLOCK_TITULO");
    $tpl->LISTA_TITULO = "TAXAS";
    $tpl->show();
    $tpl = new Template("templates/cadastro1.html");
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->TITULO = "Lucro/Taxa Administrativa";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "text";
    $tpl->CAMPO_DICA = "";
    $tpl->CAMPO_NOME = "lucro";
    $tpl->CAMPO_ID = "lucro";
    $tpl->CAMPO_AOCLICAR = "";
    $tpl->CAMPO_ONKEYUP = "";
    $tpl->CAMPO_ONBLUR = "";
    $tpl->CAMPO_VALOR = "R$ " . number_format($lucro_total, 2, ',', '.');
    $tpl->CAMPO_TAMANHO = "16";
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_CAMPO_PADRAO");
    $tpl->block("BLOCK_CAMPO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
    $tpl->show();
    $tpl2 = new Template("templates/lista2.html");
    //$tpl2->block("BLOCK_TABELA_CHEIA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "NOME DA TAXA";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "%";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "TOTAL";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->block("BLOCK_CABECALHO_LINHA");
    $tpl2->block("BLOCK_CABECALHO");
    $sql = "
    SELECT tax_nome, quitax_valor
    FROM quiosques_taxas
    JOIN taxas on (quitax_taxa=tax_codigo)
    WHERE tax_cooperativa=$usuario_cooperativa
    AND tax_quiosque in (0,$usuario_quiosque)
    AND tax_tiponegociacao=2
    ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $taxas = 0;
    $saldo_total = 0;
    while ($dados = mysql_fetch_assoc($query)) {
        $taxa_nome = $dados["tax_nome"];
        $taxa_valor = $dados["quitax_valor"];
        $taxas = $taxas + $taxa_valor;
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "";
        $tpl2->TEXTO = "$taxa_nome";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = number_format($taxa_valor, 2, ',', '') . " %";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $saldo = $taxa_valor * $lucro_total / 100;
        $tpl2->TEXTO = "R$ " . number_format($saldo, 2, ',', '.');
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->block("BLOCK_LINHA");
        $tpl2->block("BLOCK_CORPO");
    }
    $tpl2->block("BLOCK_LINHA_SUBTOTAL");
    $tpl2->COLUNA_TAMANHO = "200px";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "Quiosque";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->COLUNA_TAMANHO = "100px";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $taxa_quiosque = 100 - $taxas;
    $tpl2->TEXTO = number_format($taxa_quiosque, 2, ',', '.') . " %";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->COLUNA_TAMANHO = "150px";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $saldo_total = $taxa_quiosque * $lucro_total / 100;
    $tpl2->TEXTO = "R$ " . number_format($saldo_total, 2, ',', '.');
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->block("BLOCK_LINHA");
    $tpl2->block("BLOCK_CORPO");
    $tpl2->block("BLOCK_LISTAGEM");
    $tpl2->show();




    //Detalhes Produtos
    if ($detprodutos == 1) {
        echo "<br>";
        $tpl = new Template("templates/tituloemlinha_2.html");
        $tpl->block("BLOCK_TITULO");
        $tpl->LISTA_TITULO = "DETALHES PRODUTOS";
        //$tpl->block("BLOCK_QUEBRA2");
        $tpl->show();
        $tpl2 = new Template("templates/lista2.html");
        $tpl2->block("BLOCK_TABELA_CHEIA");
        $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
        $tpl2->CABECALHO_COLUNA_TAMANHO = "";
        $tpl2->CABECALHO_COLUNA_NOME = "PRODUTO";
        $tpl2->block("BLOCK_CABECALHO_COLUNA");
        $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
        $tpl2->CABECALHO_COLUNA_TAMANHO = "";
        $tpl2->CABECALHO_COLUNA_NOME = "QUANTIDADE";
        $tpl2->block("BLOCK_CABECALHO_COLUNA");
        $tpl2->CABECALHO_COLUNA_COLSPAN = "";
        $tpl2->CABECALHO_COLUNA_TAMANHO = "";
        $tpl2->CABECALHO_COLUNA_NOME = "TOTAL VENDA";
        $tpl2->block("BLOCK_CABECALHO_COLUNA");
        $tpl2->CABECALHO_COLUNA_COLSPAN = "";
        $tpl2->CABECALHO_COLUNA_TAMANHO = "";
        $tpl2->CABECALHO_COLUNA_NOME = "TOTAL CUSTO";
        $tpl2->block("BLOCK_CABECALHO_COLUNA");
        $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
        $tpl2->CABECALHO_COLUNA_TAMANHO = "";
        $tpl2->CABECALHO_COLUNA_NOME = "TOTAL LUCRO";
        $tpl2->block("BLOCK_CABECALHO_COLUNA");
        $tpl2->block("BLOCK_CABECALHO_LINHA");
        $tpl2->block("BLOCK_CABECALHO");
        //Calcula todos os valores para cada produtos vendido
        $sql = "
        SELECT pro_codigo, 
        pro_nome,
        sum(saipro_quantidade) as 'saipro_quantidade',
        saipro_lote,
        saipro_valorunitario,
        protip_sigla,
        protip_codigo,
        (SELECT DISTINCT entpro_valorunitario FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto) as 'valuni',
        (SELECT DISTINCT entpro_valunicusto FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto) as 'valunicusto',
        sum(saipro_quantidade * (SELECT DISTINCT entpro_valorunitario FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto)) as 'tot',
        sum(saipro_quantidade * (SELECT DISTINCT entpro_valunicusto FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto)) as 'totcusto',
        sum((saipro_quantidade * (SELECT DISTINCT entpro_valorunitario FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto))-(saipro_quantidade * (SELECT DISTINCT entpro_valunicusto FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto))) as 'lucro'
        FROM saidas_produtos
        JOIN produtos on (saipro_produto=pro_codigo)
        JOIN entradas on (saipro_lote=ent_codigo)
        JOIN saidas on (sai_codigo=saipro_saida)
        JOIN produtos_tipo ON (protip_codigo=pro_tipocontagem)
        WHERE saipro_fechado=0
        AND ent_tiponegociacao=2     
        AND ent_quiosque=$usuario_quiosque
        AND sai_tipo=1
        AND sai_datahoracadastro BETWEEN '$dataini_datetime' AND '$datafim_datetime:59'
        AND sai_status=1   
        GROUP BY saipro_produto
        ";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro: " . mysql_error());
        while ($dados = mysql_fetch_assoc($query)) {
            $produto = $dados['pro_codigo'];
            $produto_nome = $dados['pro_nome'];
            $qtd = $dados['saipro_quantidade'];
            $sigla = $dados['protip_sigla'];
            $valuni = $dados['valuni'];
            $valunicusto = $dados['valunicusto'];
            $tot = $dados['tot'];
            $totcusto = $dados['totcusto'];
            $lucro = $dados['lucro'];
            $tipocontagem = $dados['protip_codigo'];
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = "$produto";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "left";
            $tpl2->TEXTO = "$produto_nome";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            if ($tipocontagem == 2)
                $tpl2->TEXTO = number_format($qtd, 3, ',', '.');
            else
                $tpl2->TEXTO = number_format($qtd, 0, '', '.');
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "left";
            $tpl2->TEXTO = "$sigla";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = "R$ " . number_format($tot, 2, ',', '.');
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = "R$ " . number_format($totcusto, 2, ',', '.');
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = "R$ " . number_format($lucro, 2, ',', '.');
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "left";
            $saldo = number_format($lucro / $lucro_total * 100, 2, ',', '') . " %";
            $tpl2->TEXTO = "$saldo";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->block("BLOCK_LINHA");
        }
        //Subtotal rodapé
        $tpl2->block("BLOCK_LINHA_SUBTOTAL");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = "";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "left";
        $tpl2->TEXTO = "";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = "";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "left";
        $tpl2->TEXTO = "";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = "R$ " . number_format($venda_total, 2, ',', '.');
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = "R$ " . number_format($custo_total, 2, ',', '.');
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = "R$ " . number_format($lucro_total, 2, ',', '.');
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "left";
        $tpl2->TEXTO = "100 %";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->block("BLOCK_LINHA");
        $tpl2->block("BLOCK_CORPO");
        $tpl2->block("BLOCK_LISTAGEM");
        $tpl2->show();
    }



    //Detalhes Fornecedor
    if ($detfornecedores == 1) {
        echo "<br>";
        $tpl = new Template("templates/tituloemlinha_2.html");
        $tpl->block("BLOCK_TITULO");
        $tpl->LISTA_TITULO = "DETALHES FORNECEDORES";
        $tpl->show();
        $tpl2 = new Template("templates/lista2.html");
        $tpl2->block("BLOCK_TABELA_CHEIA");
        $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
        $tpl2->CABECALHO_COLUNA_TAMANHO = "";
        $tpl2->CABECALHO_COLUNA_NOME = "FORNECEDOR";
        $tpl2->block("BLOCK_CABECALHO_COLUNA");
        $tpl2->CABECALHO_COLUNA_COLSPAN = "";
        $tpl2->CABECALHO_COLUNA_TAMANHO = "";
        $tpl2->CABECALHO_COLUNA_NOME = "TOTAL VENDA";
        $tpl2->block("BLOCK_CABECALHO_COLUNA");
        $tpl2->CABECALHO_COLUNA_COLSPAN = "";
        $tpl2->CABECALHO_COLUNA_TAMANHO = "";
        $tpl2->CABECALHO_COLUNA_NOME = "TOTAL CUSTO";
        $tpl2->block("BLOCK_CABECALHO_COLUNA");
        $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
        $tpl2->CABECALHO_COLUNA_TAMANHO = "";
        $tpl2->CABECALHO_COLUNA_NOME = "TOTAL LUCRO";
        $tpl2->block("BLOCK_CABECALHO_COLUNA");
        $tpl2->block("BLOCK_CABECALHO_LINHA");
        $tpl2->block("BLOCK_CABECALHO");
        $sql = "
        SELECT pes_codigo, 
        pes_nome,
        sum(saipro_quantidade * (SELECT DISTINCT entpro_valorunitario FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto)) as 'tot',
        sum(saipro_quantidade * (SELECT DISTINCT entpro_valunicusto FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto)) as 'totcusto',
        sum((saipro_quantidade * (SELECT DISTINCT entpro_valorunitario FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto))-(saipro_quantidade * (SELECT DISTINCT entpro_valunicusto FROM entradas_produtos WHERE entpro_entrada=saipro_lote and entpro_produto=saipro_produto))) as 'lucro'
        FROM saidas_produtos
        JOIN entradas on (saipro_lote=ent_codigo)
        JOIN pessoas on (pes_codigo=ent_fornecedor)
        JOIN saidas on (sai_codigo=saipro_saida)
        WHERE saipro_fechado=0
        AND ent_tiponegociacao=2     
        AND ent_quiosque=$usuario_quiosque
        AND sai_datahoracadastro BETWEEN '$dataini_datetime' AND '$datafim_datetime:59'
        AND sai_tipo=1
        AND sai_status=1
        GROUP BY pes_codigo
        ";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro: " . mysql_error());
        while ($dados = mysql_fetch_assoc($query)) {
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = $dados['pes_codigo'];
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = $dados['pes_nome'];
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = "R$ " . number_format($dados['tot'], 2, ',', '.');
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = "R$ " . number_format($dados['totcusto'], 2, ',', '.');
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = "R$ " . number_format($dados['lucro'], 2, ',', '.');
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $saldo = number_format($dados['lucro'] / $lucro_total * 100, 2, ',', '') . " %";
            $tpl2->TEXTO = $saldo;
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->block("BLOCK_LINHA");
            $tpl2->block("BLOCK_CORPO");
        }
        //Subtotal rodapé
        $tpl2->block("BLOCK_LINHA_SUBTOTAL");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "";
        $tpl2->TEXTO = "";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "";
        $tpl2->TEXTO = "";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = "R$ " . number_format($venda_total, 2, ',', '.');
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = "R$ " . number_format($custo_total, 2, ',', '.');
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = "R$ " . number_format($lucro_total, 2, ',', '.');
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "";
        $saldo = "100 %";
        $tpl2->TEXTO = $saldo;
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
        $tpl2->block("BLOCK_LINHA");
        $tpl2->block("BLOCK_CORPO");

        $tpl2->block("BLOCK_LISTAGEM");
        $tpl2->block("BLOCK_FIMFORM");
        $tpl2->show();
    }
}
$tpl2 = new Template("templates/lista2.html");
$tpl2->block("BLOCK_FIMFORM");
$tpl2->show();


//Botões
$tpl = new Template("templates/cadastro1.html");
$tpl->FORM_NOME = "form2";
$tpl->FORM_TARGET = "";
$tpl->FORM_LINK = "acertos_revenda_cadastrar2.php";
$tpl->block("BLOCK_FORM");
$tpl->CAMPOOCULTO_NOME = "dataini";
$tpl->CAMPOOCULTO_VALOR = $dataini;
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "datafim";
$tpl->CAMPOOCULTO_VALOR = "$datafim";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "horaini";
$tpl->CAMPOOCULTO_VALOR = "$horaini";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "horafim";
$tpl->CAMPOOCULTO_VALOR = "$horafim";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "totalvenda";
$tpl->CAMPOOCULTO_VALOR = "$venda_total";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "totalcusto";
$tpl->CAMPOOCULTO_VALOR = "$custo_total";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "totallucro";
$tpl->CAMPOOCULTO_VALOR = "$lucro_total";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "totaltaxaquiosque";
$tpl->CAMPOOCULTO_VALOR = "$saldo_total";
$tpl->block("BLOCK_CAMPOOCULTO");
$totaltaxas = $lucro_total - $saldo_total;
$tpl->CAMPOOCULTO_NOME = "totaltaxas";
$tpl->CAMPOOCULTO_VALOR = "$totaltaxas";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "qtdvendas";
$tpl->CAMPOOCULTO_VALOR = "$qtdvendas";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "qtdprodutos";
$tpl->CAMPOOCULTO_VALOR = "$qtdprodutos";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "qtdfornecedores";
$tpl->CAMPOOCULTO_VALOR = "$qtdprodutos";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->CAMPOOCULTO_NOME = "qtdlotes";
$tpl->CAMPOOCULTO_VALOR = "$qtdlotes";
$tpl->block("BLOCK_CAMPOOCULTO");
$tpl->show();

echo "<br>";
$tpl = new Template("templates/botoes1.html");
$tpl->block("BLOCK_LINHAHORIZONTAL_EMCIMA");
if ($passo == 2) {
    //$tpl->COLUNA_LINK_ARQUIVO = "acertos_revenda_cadastrar2.php";
    //$tpl->block("BLOCK_COLUNA_LINK");
    //$tpl->BOTAO_TECLA="";
    $tpl->BOTAO_TIPO = "submit";
    $tpl->BOTAO_VALOR = "CONFIRMAR FECHAMENTO";
    //$tpl->BOTAO_NOME=""; 
    //$tpl->BOTAO_ID="";
    //$tpl->BOTAO_DICA="";
    //$tpl->BOTAO_ONCLICK="";
    //$tpl->BOTAOPADRAO_CLASSE="";
    //$tpl->BOTAO_CLASSE="";
    //$tpl->block("BLOCK_BOTAO_DINAMICO   ");                 
    //$tpl->block("BLOCK_BOTAO_DESABILITADO"); 
    //$tpl->block("BLOCK_BOTAO_AUTOFOCO"); 
    $tpl->block("BLOCK_BOTAO_PADRAO");
    $tpl->block("BLOCK_BOTAO");
    $tpl->block("BLOCK_COLUNA");
}
if (($passo == 1) || ($passo == 2)) {
    $tpl->COLUNA_LINK_ARQUIVO = "acertos_revenda.php";
    $tpl->block("BLOCK_COLUNA_LINK");
    $tpl->block("BLOCK_BOTAOPADRAO_SIMPLES");
    $tpl->block("BLOCK_BOTAOPADRAO_VOLTAR");
    $tpl->block("BLOCK_BOTAOPADRAO");
    $tpl->block("BLOCK_COLUNA");
}
$tpl->block("BLOCK_LINHA");
$tpl->block("BLOCK_BOTOES");
$tpl->block("BLOCK_FECHARFORM");
$tpl->show();

include "rodape.php";
?>
