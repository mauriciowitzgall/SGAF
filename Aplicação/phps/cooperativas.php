<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_cooperativa_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativas";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "COOPERATIVA";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "cooperativas.png";
$tpl_titulo->show();

$tpl = new Template("templates/listagem.html");

//Filtro Inicio
$filtro_nome = $_POST["filtro_nome"];
$filtro_abreviacao = $_POST["filtro_abreviacao"];
$tpl->LINK_FILTRO = "cooperativas.php";

//Filtro Abreviacao
$tpl->CAMPO_TITULO = "Abreviação";
$tpl->CAMPO_TAMANHO = "25";
$tpl->CAMPO_NOME = "filtro_abreviacao";
$tpl->CAMPO_VALOR = $filtro_abreviacao;
$tpl->CAMPO_QTD_CARACTERES = "";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Nome
$tpl->CAMPO_TITULO = "Nome";
$tpl->CAMPO_TAMANHO = "25";
$tpl->CAMPO_NOME = "filtro_nome";
$tpl->CAMPO_VALOR = $filtro_nome;
$tpl->CAMPO_QTD_CARACTERES = "";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Fim
$tpl->block("BLOCK_FILTRO");

//Listagem Inicio
//Cabeçalho
$tpl->CABECALHO_COLUNA_TAMANHO = "150px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "ABREVIAÇÃO";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "450px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "NOME COMPLETO";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "250px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "PRESIDENTE";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "100px";
$tpl->CABECALHO_COLUNA_COLSPAN = "2";
$tpl->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Lista linhas
//Verifica quais filtros devem ser considerados no sql principal
$sql_filtro = "";

if ($filtro_nome <> "") {
    $sql_filtro = $sql_filtro . " and coo_nomecompleto like '%$filtro_nome%'";
}
if ($filtro_abreviacao <> "") {
    $sql_filtro = $sql_filtro . " and coo_abreviacao like '%$filtro_abreviacao%' ";
}

//Listagem
$sql = "SELECT * FROM cooperativas WHERE 1 $sql_filtro ORDER BY coo_nomecompleto";
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
$tpl->PAGINAS="$paginas";
$tpl->PAGINAATUAL="$paginaatual";
$tpl->PASTA_ICONES="$icones";
$tpl->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";



$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $codigo = $dados["coo_codigo"];
    $nome = $dados["coo_nomecompleto"];
    $abreviacao = $dados["coo_abreviacao"];
    $presidente = $dados["coo_presidente"];
    $tpl->LISTA_LINHA_CLASSE = "";

    //Coluna Abreviação
    $tpl->LISTA_COLUNA_VALOR = $abreviacao;
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Cooperativa Nome Completo
    $tpl->LISTA_COLUNA_VALOR = $nome;
    $tpl->block("BLOCK_LISTA_COLUNA");


    //Coluna Presidente
    $sql2 = "SELECT * FROM pessoas WHERE pes_codigo=$presidente ORDER BY pes_nome";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_array($query2);
    $tpl->LISTA_COLUNA_VALOR = $dados2["pes_nome"];
    $tpl->block("BLOCK_LISTA_COLUNA");


    //Coluna Operações
    $tpl->CODIGO = $codigo;
    //editar  
    IF ($permissao_cooperativa_editar==1) {
        $tpl->OPERACAO_NOME = "Editar";
        $tpl->LINK = "cooperativas_cadastrar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=editar";
        $tpl->ICONE_ARQUIVO = $icones . "editar.png";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");        
    } else {  
        $tpl->OPERACAO_NOME = "Você não tem permissão para editar cooperativas! Contate um administrador!";
        $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";            
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");        
    }       
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_TODAS");        
    //excluir
    IF ($permissao_cooperativa_excluir==1) {
        $tpl->OPERACAO_NOME = "Excluir";
        $tpl->LINK = "cooperativas_deletar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=excluir";
        $tpl->ICONE_ARQUIVO = $icones . "excluir.png";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");        
    } else {  
        $tpl->OPERACAO_NOME = "Você não tem permissão para excluir cooperativas! Contate um administrador!";
        $tpl->ICONE_ARQUIVO = $icones . "excluir_desabilitado.png";            
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");        
    }       
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_TODAS");        
       

    $tpl->block("BLOCK_LISTA");
}
$tpl->LINK_CADASTRO = "cooperativas_cadastrar.php?operacao=cadastrar";
$tpl->CADASTRAR_NOME = "CADASTRAR";
if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}


if ($permissao_cooperativa_cadastrar != 1)
    $tpl->block("BLOCK_RODAPE_BOTOES_DESABILITADOS");
$tpl->block("BLOCK_RODAPE_BOTOES");


$tpl->show();
include "rodape.php";
?>
