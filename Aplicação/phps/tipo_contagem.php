<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_tipocontagem_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}



$tipopagina = "produtos";
include "includes.php";

//Template de TÃ­tulo e Sub-tÃ­tulo
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TIPO DE CONTAGEM";
$tpl_titulo->SUBTITULO = "LISTA DE TIPOS DE CONTAGEM DE PRODUTOS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos.png";
$tpl_titulo->show();


$tpl = new Template("tipo_contagem.html");
$tpl->ICONES_CAMINHO = "$icones";
$filtronome = $_POST["filtronome"];
$tpl->FILTRO_NOME = $filtronome;

if ($permissao_tipocontagem_cadastrar == 1)
    $tpl->block("BLOCK_BOTAO_CADASTRAR");


//SQL principal
$sql = "
SELECT 
    *
FROM
    produtos_tipo    
WHERE
    (protip_cooperativa='0' OR protip_cooperativa=$usuario_cooperativa) AND
    protip_nome like '%$filtronome%'
ORDER BY
    protip_nome
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
        $tpl->NOME = $dados['protip_nome'];
        $tpl->SIGLA = $dados['protip_sigla'];
        $tpl->CODIGO = $dados['protip_codigo'];

        if ($dados["protip_quiosque"] != '0') {
            if (($permissao_tipocontagem_cadastrar == 1)&&($dados['protip_codigo']!=1)&&($dados['protip_codigo']!=2))
                $tpl->block("BLOCK_EDITAR");
            else
                $tpl->block("BLOCK_EDITAR_DESABILITADO");
            
            if (($permissao_tipocontagem_excluir == 1)&&($dados['protip_codigo']!=1)&&($dados['protip_codigo']!=2))
                $tpl->block("BLOCK_EXCLUIR");
            else
                $tpl->block("BLOCK_EXCLUIR_DESABILITADO");

            
            
        }
        $tpl->block("BLOCK_LISTA");
    }
} else {
    $tpl->block("BLOCK_LISTA_NADA");
}

$tpl->show();
include "rodape.php";
?>
