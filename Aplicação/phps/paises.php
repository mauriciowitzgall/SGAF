<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_paises_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "locais";
include "includes.php";

//Template de TÃ­tulo e Sub-tÃ­tulo
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PAISES";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "locais.png";
$tpl_titulo->show();

$tpl = new Template("templates/listagem.html");


//Filtro
$filtro_nome = $_POST["filtro_nome"];
$tpl->LINK_FILTRO = "paises.php";
$tpl->CAMPO_TITULO = "Nome";
$tpl->CAMPO_TAMANHO = "25";
$tpl->CAMPO_NOME = "filtro_nome";
$tpl->CAMPO_VALOR = $filtro_nome;
$tpl->CAMPO_QTD_CARACTERES = "";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");
$tpl->block("BLOCK_FILTRO");


//Lista cabeçalho
$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "PAIS";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "SIGLA";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "2";
$tpl->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Lista linhas
//Verifica quais filtros devem ser considerados no sql principal
$sql_filtro = "";
if ($filtro_nome <> "") {
    $sql_filtro = "and pai_nome like '%$filtro_nome%'";
}

$sql = "SELECT * FROM paises WHERE 1 $sql_filtro ORDER BY pai_nome";
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
    $codigo = $dados["pai_codigo"];
    $nome = $dados["pai_nome"];
    $sigla = $dados["pai_sigla"];
    $tpl->LISTA_LINHA_CLASSE = "";

    //Coluna Pais
    $tpl->LISTA_COLUNA_VALOR = $nome;
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Sigla
    $tpl->LISTA_COLUNA_VALOR = $sigla;
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna OperaçÃµes
    $tpl->CODIGO = $codigo;

    //editar  
    IF ($permissao_paises_editar==1) {
        $tpl->OPERACAO_NOME = "Editar";
        $tpl->LINK = "paises_cadastrar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=editar";
        $tpl->ICONE_ARQUIVO = $icones . "editar.png";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");        
    } else {  
        $tpl->OPERACAO_NOME = "VocÃª não tem permissão para editar paises! Contate um administrador!";
        $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";            
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");        
    }       
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_TODAS");      
 
    
    //excluir
    IF ($permissao_paises_excluir==1) {
        $tpl->OPERACAO_NOME = "Excluir";
        $tpl->LINK = "paises_deletar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=excluir";
        $tpl->ICONE_ARQUIVO = $icones . "excluir.png";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");        
    } else {  
        $tpl->OPERACAO_NOME = "VocÃª não tem permissão para excluir paises! Contate um administrador!";
        $tpl->ICONE_ARQUIVO = $icones . "excluir_desabilitado.png";            
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");        
    }       
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_TODAS"); 


    $tpl->block("BLOCK_LISTA");
} 
if (mysql_num_rows($query)==0) {
    $tpl->LISTANADA=4;
    $tpl->block("BLOCK_LISTA_NADA");
}

$tpl->LINK_CADASTRO = "paises_cadastrar.php";
$tpl->CADASTRAR_NOME = "CADASTRAR";
if ($permissao_paises_cadastrar==1) 
    $tpl->block("BLOCK_RODAPE_BOTOES");


$tpl->show();
include "rodape.php";
?>
