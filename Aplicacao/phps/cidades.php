<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_cidades_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "locais";
include "includes.php";

//Template de TÃ­tulo e Sub-tÃ­tulo
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CIDADES";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "locais.png";
$tpl_titulo->show();


$tpl = new Template("cidades.html");
$tpl->ICONES_CAMINHO = "$icones";
$tpl->LINK_NOME = "cidades";
$filtronome = $_POST["filtronome"];
$tpl->FILTRO_NOME = $filtronome;


if ($permissao_cidades_cadastrar == 1)
    $tpl->block("BLOCK_BOTAO_CADASTRAR");

    



//SQL principal
$sql = "
SELECT 
    *
FROM
    cidades
    join estados on (cid_estado=est_codigo)
    join paises on (est_pais=pai_codigo)
WHERE
    cid_nome like '%$filtronome%'
ORDER BY
    cid_nome
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
$tpl->PAGINAS = "$paginas";
$tpl->PAGINAATUAL = "$paginaatual";
$tpl->PASTA_ICONES = "$icones";
$tpl->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";


$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());

$linhas = mysql_num_rows($query);
if ($linhas != "") {
    while ($dados = mysql_fetch_array($query)) {
        $tpl->NOME = $dados['cid_nome'];
        $tpl->ESTADO = $dados['est_sigla'];
        $tpl->PAIS = $dados['pai_nome'];
        $tpl->CODIGO = $dados['cid_codigo'];
        if ($permissao_cidades_cadastrar == 1)
            $tpl->block("BLOCK_EDITAR");
        else
            $tpl->block("BLOCK_EDITAR_DESABILITADO");

        if ($permissao_cidades_excluir == 1)
            $tpl->block("BLOCK_EXCLUIR");
        else
            $tpl->block("BLOCK_EXCLUIR_DESABILITADO");



        $tpl->block("BLOCK_LISTA");
    }
} else {
    $tpl->block("BLOCK_LISTA_NADA");
}

$tpl->show();
include "rodape.php";
?>
