<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_estados_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "locais";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ESTADOS";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "locais.png";
$tpl_titulo->show();


//Inicio do html de estados
$tpl = new Template("estados.html");
$tpl->ICONES_CAMINHO = "$icones";
$tpl->LINK_NOME = "estados";
$filtronome = $_POST["filtronome"];
$tpl->FILTRO_NOME = $filtronome;

$filtropais = $_POST["filtropais"];


$sql = "
SELECT DISTINCT
    pai_codigo,pai_nome
FROM
    estados    
    join paises on (est_pais=pai_codigo)
ORDER BY
    est_nome
";


$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas != "") {
    while ($dados = mysql_fetch_assoc($query)) {
        $tpl->PAIS_CODIGO = $dados["pai_codigo"];
        $tpl->PAIS_NOME = $dados["pai_nome"];
        if ($dados["pai_codigo"] == $filtropais) {
            $tpl->PAIS_SELECIONADO = " selected ";
        } else {
            $tpl->PAIS_SELECIONADO = "";
        }
        $tpl->block("BLOCK_FILTRO_PAIS");
    }
}


//SQL principal
//Verifica se foi deve conciderar o pais na busca
$filtro_pais_sql = "";
if ($filtropais <> "") {
    $filtro_pais_sql = "and est_pais = '$filtropais'";
}
//Concatena o sql principal com os filtros se necessário    
$sql = "
SELECT 
    *
FROM
    estados    
    join paises on (est_pais=pai_codigo)
WHERE
    est_nome like '%$filtronome%' $filtro_pais_sql
ORDER BY
    est_nome
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
$tpl->PAGINAS="$paginas";
$tpl->PAGINAATUAL="$paginaatual";
$tpl->PASTA_ICONES="$icones";
$tpl->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";


$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());

$linhas = mysql_num_rows($query);
if ($linhas != "") {
    while ($dados = mysql_fetch_assoc($query)) {
        $tpl->CODIGO = $dados['est_codigo'];
        $tpl->NOME = $dados['est_nome'];
        $tpl->SIGLA = $dados['est_sigla'];
        $tpl->PAIS = $dados['pai_nome'];
        if ($permissao_estados_cadastrar == 1)
            $tpl->block("BLOCK_EDITAR");
        else
            $tpl->block("BLOCK_EDITAR_DESABILITADO");

        if ($permissao_estados_excluir == 1)
            $tpl->block("BLOCK_EXCLUIR");
        else
            $tpl->block("BLOCK_EXCLUIR_DESABILITADO");
        $tpl->block("BLOCK_LISTA");
    }
} else {
    $tpl->block("BLOCK_LISTA_NADA");
    
}

if ($permissao_estados_cadastrar==1) {
    $tpl->block("BLOCK_BOTAO");
} 


$tpl->show();
include "rodape.php";
?>
