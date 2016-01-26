<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($quiosque_revenda <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "negociacoes";
include "includes.php";


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "FECHAMENTO DE REVENDAS";
$tpl_titulo->SUBTITULO = "LISTAGEM DE FECHAMENTOS DE REVENDAS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "revenda.png";
$tpl_titulo->show();


//FILTRO
$tpl_filtro = new Template("templates/filtro1.html");


$tpl_filtro->FORM_ONLOAD = "valida_filtro_acertos_revenda_numero(); valida_filtro_acertos_revenda_datas();";
$tpl_filtro->FORM_LINK = "acertos_revenda.php";
$tpl_filtro->FORM_NOME = "form_filtro";
$tpl_filtro->block("BLOCK_FORM");

$filtro_numero = $_POST["filtro_numero"];
$filtro_supervisor = $_POST["filtro_supervisor"];
$filtro_dataini = $_POST["filtro_dataini"];
$filtro_horaini = $_POST["filtro_horaini"];
$filtro_datafim = $_POST["filtro_datafim"];
$filtro_horafim = $_POST["filtro_horafim"];

//Filtro Numero
$tpl_filtro->CAMPO_TITULO = "Numero";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "text";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_numero";
$tpl_filtro->CAMPO_ONKEYUP = "valida_filtro_acertos_revenda_numero()";
$tpl_filtro->CAMPO_TAMANHO = "8";
$tpl_filtro->CAMPO_VALOR = $filtro_numero;
$tpl_filtro->block("BLOCK_CAMPO_FILTRO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

//Filtro Data inicial
$tpl_filtro->CAMPO_TITULO = "De";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "date";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_dataini";
$tpl_filtro->CAMPO_ONKEYUP = "valida_filtro_acertos_revenda_datas();";
$tpl_filtro->CAMPO_TAMANHO = "8";
$tpl_filtro->CAMPO_VALOR = $filtro_dataini;
$tpl_filtro->block("BLOCK_CAMPO_FILTRO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
/* //Filtro Hora inicial
  //$tpl_filtro->block("BLOCK_CAMPO_TITULO_VAZIO");
  $tpl_filtro->CAMPO_TIPO = "time";
  $tpl_filtro->CAMPO_QTDCARACTERES = "";
  $tpl_filtro->CAMPO_NOME = "filtro_horaini";
  $tpl_filtro->CAMPO_ONKEYUP = "valida_filtro_acertos_revenda_datas();";
  $tpl_filtro->CAMPO_TAMANHO = "6";
  $tpl_filtro->CAMPO_VALOR = $filtro_horaini;
  $tpl_filtro->block("BLOCK_CAMPO_FILTRO");
  $tpl_filtro->block("BLOCK_CAMPO");
  $tpl_filtro->block("BLOCK_ESPACO"); */
$tpl_filtro->block("BLOCK_COLUNA");

//Filtro Data final
$tpl_filtro->CAMPO_TITULO = "Até";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "date";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_datafim";
$tpl_filtro->CAMPO_ONKEYUP = "valida_filtro_acertos_revenda_datas();";
$tpl_filtro->CAMPO_TAMANHO = "6";
$tpl_filtro->CAMPO_VALOR = $filtro_datafim;
$tpl_filtro->block("BLOCK_CAMPO_FILTRO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
/* //Filtro Hora final
  //$tpl_filtro->block("BLOCK_CAMPO_TITULO_VAZIO");
  $tpl_filtro->CAMPO_TIPO = "time";
  $tpl_filtro->CAMPO_QTDCARACTERES = "";
  $tpl_filtro->CAMPO_NOME = "filtro_horafim";
  $tpl_filtro->CAMPO_ONKEYUP = "valida_filtro_acertos_revenda_datas();";
  $tpl_filtro->CAMPO_TAMANHO = "6";
  $tpl_filtro->CAMPO_VALOR = $filtro_horafim;
  $tpl_filtro->block("BLOCK_CAMPO_FILTRO");
  $tpl_filtro->block("BLOCK_CAMPO");
  $tpl_filtro->block("BLOCK_ESPACO"); */
$tpl_filtro->block("BLOCK_COLUNA");


//Supervisor
$tpl_filtro->CAMPO_TITULO = "Supervisor";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->SELECT_NOME = "filtro_supervisor";
$tpl_filtro->SELECT_ID = "";
$tpl_filtro->SELECT_TAMANHO = "";
$tpl_filtro->block("BLOCK_SELECT_FILTRO");
$tpl_filtro->block("BLOCK_OPTION_PADRAO");
$sql = "
    SELECT DISTINCT pes_codigo,pes_nome 
    FROM pessoas
    JOIN fechamentos on (pes_codigo=fch_supervisor)
    ORDER BY pes_nome 
";
if (!$query = mysql_query($sql))
    die("Erro SQL 0: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["pes_codigo"];
    if ($codigo == $filtro_supervisor)
        $tpl_filtro->block("BLOCK_OPTION_SELECIONADO");
    $tpl_filtro->OPTION_VALOR = $dados["pes_codigo"];
    $tpl_filtro->OPTION_TEXTO = $dados["pes_nome"];
    $tpl_filtro->block("BLOCK_OPTION");
}
$tpl_filtro->block("BLOCK_SELECT");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");


$tpl_filtro->block("BLOCK_LINHA");
$tpl_filtro->block("BLOCK_FILTRO_CAMPOS");
$tpl_filtro->block("BLOCK_QUEBRA");
$tpl_filtro->show();


$tpl4 = new Template("templates/botoes1.html");

//Bot�o Pesquisar
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl4->block("BLOCK_BOTAOPADRAO_PESQUISAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Botão Limpar
$tpl4->COLUNA_LINK_ARQUIVO = "acertos_revenda.php";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl4->block("BLOCK_BOTAOPADRAO_LIMPAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Botão Cadastrar
$tpl4->COLUNA_LINK_ARQUIVO = "acertos_revenda_cadastrar.php";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "100%";
$tpl4->COLUNA_ALINHAMENTO = "right";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->BOTAO_TIPO = "BUTTON";
$tpl4->BOTAO_VALOR = "REALIZAR NOVO FECHAMENTO";
$tpl4->BOTAOPADRAO_CLASSE = "botaopadraocadastrar";
$tpl4->block("BLOCK_BOTAO_PADRAO");
$tpl4->block("BLOCK_BOTAOPADRAO_AUTOFOCO");
$tpl4->block("BLOCK_BOTAO");
$tpl4->block("BLOCK_COLUNA");



$tpl4->block("BLOCK_LINHA");
$tpl4->block("BLOCK_BOTOES");
$tpl4->block("BLOCK_LINHAHORIZONTAL_EMBAIXO");
$tpl4->show();


//Listagem
$tpl2 = new Template("templates/lista2.html");
$tpl2->block("BLOCK_TABELA_CHEIA");


$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "Nº";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "DE";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "ATÉ";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "SUPERVISOR";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
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
$tpl2->CABECALHO_COLUNA_NOME = "TOTAL LUCRO.";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "3";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "OPERAÇÃO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->block("BLOCK_CABECALHO_LINHA");
$tpl2->block("BLOCK_CABECALHO");

//Validação de filtros
$sql_filtro = "";
if (!empty($filtro_numero))
    $sql_filtro = " and fch_codigo = $filtro_numero ";
if (!empty($filtro_supervisor))
    $sql_filtro = " and fch_supervisor = $filtro_supervisor ";
if ((!empty($filtro_dataini)) || (!empty($filtro_datafim)))
    $sql_filtro = " and fch_dataini >= '$filtro_dataini' and fch_datafim <= '$filtro_datafim' ";

$sql = "
    SELECT *
    FROM fechamentos   
    JOIN pessoas on (fch_supervisor=pes_codigo)
    WHERE fch_quiosque=$usuario_quiosque
    $sql_filtro
    ORDER BY fch_codigo DESC
";

//Paginação
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se � a primeira vez que acessa a pagina ent�o come�ar na pagina 1
if (($paginaatual == "") || ($paginas < $paginaatual) || ($paginaatual <= 0)) {
    $paginaatual = 1;
}
$comeco = ($paginaatual - 1) * $por_pagina;
$tpl2->PAGINAS = "$paginas";
$tpl2->PAGINAATUAL = "$paginaatual";
$tpl2->PASTA_ICONES = "$icones";
$tpl2->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";

$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    $tpl2->LINHA_NADA_COLSPAN = "11";
    $tpl2->block("BLOCK_LINHA_NADA");
}

//Listagem Linhas
$sql8= "SELECT max(fch_codigo) FROM fechamentos";
$query8 = mysql_query($sql8);
if (!$query8)
    die("Erro SQL: " . mysql_error());
$dados8=  mysql_fetch_array($query8);
$ultimo=$dados8[0];
while ($dados = mysql_fetch_assoc($query)) {
    $numero = $dados["fch_codigo"];
    $datahora = explode(" ", $dados["fch_dataini"]);
    $dataini = $datahora[0];
    $horaini = $datahora[1];
    $datahora = explode(" ", $dados["fch_datafim"]);
    $datafim = $datahora[0];
    $horafim = $datahora[1];
    $supervisor_nome = $dados["pes_nome"];
    $totalvenda = $dados["fch_totalvenda"];
    $totalcusto = $dados["fch_totalcusto"];
    $totallucro = $dados["fch_totallucro"];

    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "$numero";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = converte_data($dataini);
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = $horaini;
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = converte_data($datafim);
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = $horafim;
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$supervisor_nome";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "R$ " . number_format($totalvenda, 2, ',', '.');
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "R$ " . number_format($totalcusto, 2, ',', '.');
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "R$ " . number_format($totallucro, 2, ',', '.');
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Operações
    $tpl2->ICONES_CAMINHO = $icones;

    //Operação Imprimir
    $tpl2->COLUNA_TAMANHO = "35px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->block("BLOCK_CONTEUDO_LINK_NOVAJANELA");
    $tpl2->CONTEUDO_LINK_ARQUIVO = "acertos_revenda_cadastrar3.php?codigo=$numero&ope=5";
    $tpl2->block("BLOCK_CONTEUDO_LINK");
    $tpl2->block("BLOCK_OPERACAO_IMPRIMIR_HABILITADO");
    $tpl2->block("BLOCK_OPERACAO_IMPRIMIR");
    $tpl2->block("BLOCK_OPERACAO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA_OPERACAO");
    $tpl2->block("BLOCK_COLUNA");

    //Operação Ver
    $tpl2->COLUNA_TAMANHO = "35px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->CONTEUDO_LINK_ARQUIVO = "acertos_revenda_cadastrar3.php?codigo=$numero&ope=4";
    $tpl2->block("BLOCK_CONTEUDO_LINK");
    $tpl2->block("BLOCK_OPERACAO_DETALHES_HABILITADO");
    $tpl2->block("BLOCK_OPERACAO_DETALHES");
    $tpl2->block("BLOCK_OPERACAO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA_OPERACAO");
    $tpl2->block("BLOCK_COLUNA");

    //Operação Excluir    
    $tpl2->COLUNA_TAMANHO = "35px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->ICONES_CAMINHO = $icones;    
    if ($numero == $ultimo) {
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_HABILITADO");
        $tpl2->CONTEUDO_LINK_ARQUIVO = "acertos_revenda_deletar.php?codigo=$numero";
        $tpl2->block("BLOCK_CONTEUDO_LINK");
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULOPADRAO");
    } else {
        //$tpl2->CONTEUDO_LINK_ARQUIVO = "";
        //$tpl2->block("BLOCK_CONTEUDO_LINK");     
        $tpl2-> ICONES_TITULO="Você só pode excluir o ultimo fechamento!";
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_DESABILITADO");
    $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULO");
    }
    $tpl2->block("BLOCK_OPERACAO_EXCLUIR");
    $tpl2->block("BLOCK_OPERACAO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA_OPERACAO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->block("BLOCK_LINHA_PADRAO");
    $tpl2->block("BLOCK_LINHA");
}

$tpl2->block("BLOCK_CORPO");
$tpl2->block("BLOCK_LISTAGEM");

$tpl2->show();




include "rodape.php";
?>
