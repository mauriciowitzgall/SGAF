<?php

//Verifica se o usuário tem permissáo para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operadores_gerir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "OPERADORES";
$tpl_titulo->SUBTITULO = "LISTA DE OPERADORES DO CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixa_operadores.png";
$tpl_titulo->show();


if ($usacaixa!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}


$tpl = new Template("templates/listagem_2.html");
$tpl->ICONE_ARQUIVO = $icones;
$caixa = $_GET['caixa'];


//Campo Operador no Topo
$tpl->CAMPO_TITULO = "Caixa";
$sql = "SELECT cai_nome FROM caixas WHERE cai_codigo=$caixa";
$query = mysql_query($sql);
if (!$query)
    die("Erro1: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$tpl->CAMPO_VALOR = $dados['cai_nome'];
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Botão cadastrar/incluir operador
IF ($permissao_caixas_operadores_gerir == 1) {
    $tpl->LINK = "caixas_operadores_cadastrar.php?caixa=$caixa&operacao=cadastrar";
    $tpl->BOTAO_NOME = "INCLUIR OPERADOR";
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
}

$tpl->block("BLOCK_FILTRO_COLUNA");

$tpl->block("BLOCK_FILTRO");

//LISTAGEM INICIO
//Cabeçalho
$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "ID";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "NOME";
$tpl->block("BLOCK_LISTA_CABECALHO");


$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TELEFONE 01";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TELEFONE 02";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "E-MAIL";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "DATA INICIO ATIVIDADES";
$tpl->block("BLOCK_LISTA_CABECALHO");

if ($permissao_caixas_operadores_gerir == 1) {
    $tpl->CABECALHO_COLUNA_COLSPAN = "2";
    $tpl->CABECALHO_COLUNA_TAMANHO = "";
    $tpl->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}

//Inicio das tuplas da listagem
$sql = "
 SELECT DISTINCT
    *
FROM
    caixas_operadores
    JOIN pessoas on (caiope_operador=pes_codigo)
WHERE    
    caiope_caixa=$caixa
ORDER BY
    pes_nome";

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
while ($dados = mysql_fetch_assoc($query)) {
    $operador = $dados["caiope_operador"];

    //Coluna ID
    $tpl->LISTA_COLUNA_VALOR = $dados["pes_id"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Nome
    $tpl->LISTA_COLUNA_VALOR = $dados["pes_nome"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Fone1 
    $tpl->LISTA_COLUNA_VALOR = $dados["pes_fone1"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Fone2 
    $tpl->LISTA_COLUNA_VALOR = $dados["pes_fone2"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna E-mail
    $tpl->LISTA_COLUNA_VALOR = $dados["pes_email"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Data inicio das atividades
    if ($dados['quisup_datafuncao'] == "0000-00-00") {
        $tpl->LISTA_COLUNA_VALOR = "";
    } else {
        $datafuncao2= converte_datahora($dados['caiope_datafuncao']);
        $datafuncao2=  explode(" ", $datafuncao2);
        $datafuncao2= $datafuncao2[0];
        $tpl->LISTA_COLUNA_VALOR = $datafuncao2;
    }
    $tpl->block("BLOCK_LISTA_COLUNA");

    if ($permissao_caixas_operadores_gerir == 1) {
        //editar
        $tpl->CODIGO = "";
        $tpl->LINK = "caixas_operadores_cadastrar.php";
        $tpl->LINK_COMPLEMENTO = "operador=$operador&caixa=$caixa&operacao=editar";

        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR");

        //excluir
        $tpl->LINK = "caixas_operadores_deletar.php";
        $tpl->LINK_COMPLEMENTO = "operador=$operador&caixa=$caixa&operacao=excluir";

        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR");
    }
    $tpl->block("BLOCK_LISTA");
}

//Se não tem tuplas então mostrar a frase padrão cujo informa que não há registros
if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}

//BOTÕES    
$tpl->LINK_VOLTAR = "caixas.php";
$tpl->block("BLOCK_RODAPE_BOTAO_VOLTAR");
$tpl->block("BLOCK_RODAPE_BOTAO");

$tpl->block("BLOCK_RODAPE_BOTOES");

$tpl->show();
include "rodape.php";
?>
