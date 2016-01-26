<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_taxas_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "cooperativas";
include "includes.php";


//TÍTULO GERAL DA PAGINA
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TAXAS  ";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "taxas.png";
$tpl_titulo->show();



//FILTRO
$tpl_filtro = new Template("templates/filtro1.html");
$tpl_filtro->FORM_ONLOAD = "";
$tpl_filtro->FORM_LINK = "taxas.php";
$tpl_filtro->FORM_NOME = "form_filtro";
$tpl_filtro->block("BLOCK_FORM");

$filtro_codigo = $_POST["filtro_codigo"];
$filtro_nometaxa = $_POST["filtro_nometaxa"];

//Filtro Código da Taxa
$tpl_filtro->CAMPO_TITULO = "Código";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "number";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_codigo";
$tpl_filtro->CAMPO_TAMANHO = "";
$tpl_filtro->CAMPO_VALOR = $filtro_codigo;
$tpl_filtro->block("BLOCK_CAMPO_PADRAO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

//Filtro Nome da Taxa
$tpl_filtro->CAMPO_TITULO = "Nome da Taxa";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "text";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_nometaxa";
$tpl_filtro->CAMPO_TAMANHO = "";
$tpl_filtro->CAMPO_VALOR = $filtro_nometaxa;
$tpl_filtro->block("BLOCK_CAMPO_PADRAO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

$tpl_filtro->block("BLOCK_LINHA");
$tpl_filtro->block("BLOCK_FILTRO_CAMPOS");
$tpl_filtro->block("BLOCK_QUEBRA");
$tpl_filtro->show();

$tpl4 = new Template("templates/botoes1.html");

//Botão Pesquisar
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl4->block("BLOCK_BOTAOPADRAO_PESQUISAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Botão Limpar filtro
$tpl4->COLUNA_LINK_ARQUIVO = "taxas.php";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl4->block("BLOCK_BOTAOPADRAO_LIMPAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Botão Cadastrar
if ($permissao_taxas_cadastrar == 1) {

    $tpl4->COLUNA_LINK_ARQUIVO = "taxas_cadastrar.php";
    $tpl4->COLUNA_LINK_TARGET = "";
    $tpl4->COLUNA_TAMANHO = "100%";
    $tpl4->COLUNA_ALINHAMENTO = "right";
    $tpl4->block("BLOCK_COLUNA_LINK");
    $tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
    $tpl4->block("BLOCK_BOTAOPADRAO_AUTOFOCO");
    $tpl4->block("BLOCK_BOTAOPADRAO_CADASTRAR");
    $tpl4->block("BLOCK_BOTAOPADRAO");
    $tpl4->block("BLOCK_COLUNA");
}

$tpl4->block("BLOCK_LINHA");
$tpl4->block("BLOCK_BOTOES");
$tpl4->block("BLOCK_LINHAHORIZONTAL_EMBAIXO");
$tpl4->show();

//LISTAGEM
$tpl2 = new Template("templates/lista2.html");
$tpl2->block("BLOCK_TABELA_CHEIA");

$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "COD.";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "TAXA";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "DESCRIÇÃO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");

if ($permissao_taxas_cadastrar == 1) {
    $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "OPERAÇÃO";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
}
$tpl2->block("BLOCK_CABECALHO_LINHA");
$tpl2->block("BLOCK_CABECALHO");
$sql_filtro = "";
if (!empty($filtro_codigo))
    $sql_filtro = " and tax_codigo=$filtro_codigo ";
if (!empty($filtro_nometaxa))
    $sql_filtro = " and tax_nome like '%$filtro_nometaxa%'";

$sql = "
SELECT 
    tax_codigo,
    tax_nome,
    tax_descricao 
FROM 
    taxas 
WHERE 
    tax_cooperativa=$usuario_cooperativa
    $sql_filtro 
ORDER BY 
    tax_nome
";
//Paginação
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se é a primeira vez que acessa a pagina então começar na pagina 1
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
    die("Erro SQL principal da Listagem: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    $tpl2->LINHA_NADA_COLSPAN = "4";
    $tpl2->block("BLOCK_LINHA_NADA");
}

while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["tax_codigo"];
    $nome = $dados["tax_nome"];
    $descricao = $dados["tax_descricao"];


    //Código
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$codigo";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Nome
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$nome";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Descrição
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$descricao";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");


    //Operações
    $tpl2->ICONES_CAMINHO = $icones;
    if ($permissao_taxas_cadastrar == 1) {
        //Operação Editar
        $tpl2->COLUNA_TAMANHO = "50px";
        $tpl2->COLUNA_ALINHAMENTO = "center";
        $tpl2->CONTEUDO_LINK_ARQUIVO = "taxas_cadastrar.php?operacao=editar&codigo=$codigo";
        $tpl2->block("BLOCK_CONTEUDO_LINK");
        $tpl2->block("BLOCK_OPERACAO_EDITAR_HABILITADO");
        $tpl2->block("BLOCK_OPERACAO_EDITAR_TITULOPADRAO");
        $tpl2->block("BLOCK_OPERACAO_EDITAR");
        $tpl2->block("BLOCK_OPERACAO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA_OPERACAO");
        $tpl2->block("BLOCK_COLUNA");

        //Operação Excluir
        $tpl2->COLUNA_TAMANHO = "50px";
        $tpl2->COLUNA_ALINHAMENTO = "center";
        $tpl2->ICONES_CAMINHO = $icones;
        $tpl2->CONTEUDO_LINK_ARQUIVO = "taxas_deletar.php?operacao=excluir&codigo=$codigo";
        $tpl2->block("BLOCK_CONTEUDO_LINK");
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_HABILITADO");
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULOPADRAO");
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR");
        $tpl2->block("BLOCK_OPERACAO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA_OPERACAO");
        $tpl2->block("BLOCK_COLUNA");
    }
    $tpl2->block("BLOCK_LINHA_PADRAO");
    $tpl2->block("BLOCK_LINHA");
}
$tpl2->block("BLOCK_CORPO");
$tpl2->block("BLOCK_LISTAGEM");

$tpl2->show();




include "rodape.php";
?>
