<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_relatorios_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "relatorios";
include "includes.php";
$operacao = $_GET["operacao"];
if (empty($operacao))
    $operacao = "cadastrar";



//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "RELATÓRIOS";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "relatorios.png";
$tpl_titulo->show();



//FORMULÁRIO
$tpl = new Template("templates/cadastro1.html");
$codigo = $_GET["codigo"];


if (!empty($codigo)) {
    $sql = "SELECT rel_codigo,rel_nome,rel_descricao FROM relatorios WHERE rel_codigo=$codigo";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro SQL Leitura Editar:" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $relatorionome = $dados["rel_nome"];
    $descricao = $dados["rel_descricao"];
}

$tpl->FORM_NOME = "";
$tpl->FORM_TARGET = "";
$tpl->FORM_LINK = "relatorios_cadastrar2.php";
$tpl->block("BLOCK_FORM");

//Nome
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "150px";
$tpl->COLUNA_ROWSPAN = "";
$tpl->TITULO = "Nome do Relatório";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "relatorionome";
$tpl->CAMPO_VALOR = "$relatorionome";
$tpl->CAMPO_TAMANHO = "30";
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_AUTOFOCO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA_PADRAO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Descrição
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "";
$tpl->COLUNA_ROWSPAN = "";
$tpl->TITULO = "Descrição";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->TEXTAREA_QTDCARACTERES = "";
$tpl->TEXTAREA_NOME = "descricao";
$tpl->TEXTAREA_TAMANHO = "50";
$tpl->TEXTAREA_TEXTO = "$descricao";
$tpl->block("BLOCK_TEXTAREA_PADRAO");
$tpl->block("BLOCK_TEXTAREA");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA_PADRAO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");


//Grupos de Permissões
$tpl->LINHA_CLASSE = "";
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "150px";
$sql = "
    SELECT gruper_codigo,gruper_nome
    FROM grupo_permissoes  
    WHERE gruper_codigo not in (7,1)
    ORDER BY gruper_codigo
";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL" . mysql_error());
$linhas = mysql_num_rows($query);
$tpl->COLUNA_ROWSPAN = "";
$tpl->TITULO = "Grupo de Permissões";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
While ($dados = mysql_fetch_assoc($query)) {
    $cod = $dados["gruper_codigo"];
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "checkbox";
    $tpl->CAMPO_NOME = "box[$cod]";
    $tpl->CAMPO_VALOR = "$cod";
    $tpl->CAMPO_TAMANHO = "";
    if ($operacao == 'editar') {
        $sql3 = "
                SELECT *
                FROM relatorios_permissao
                WHERE relper_relatorio=$codigo
                and relper_grupo=$cod
            ";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro SQL" . mysql_error());
        $linhas3 = mysql_num_rows($query3);
        if ($linhas3 > 0)
            $tpl->block("BLOCK_CAMPO_MARCADO");
    }
    $tpl->block("BLOCK_CAMPO");
    $tpl->TEXTO_NOME = $dados["gruper_nome"];
    $tpl->TEXTO_CLASSE = "";
    $tpl->TEXTO_VALOR = $dados["gruper_nome"];
    $tpl->block("BLOCK_TEXTO");
    $tpl->block("BLOCK_BR");
    $tpl->block("BLOCK_CONTEUDO");
}
$tpl->block("BLOCK_COLUNA_PADRAO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

$tpl->CAMPOOCULTO_NOME = "operacao";
$tpl->CAMPOOCULTO_VALOR = "$operacao";
$tpl->block("BLOCK_CAMPOOCULTO");

//Campo Código
$tpl->CAMPOOCULTO_NOME = "codigo";
$tpl->CAMPOOCULTO_VALOR = "$codigo";
$tpl->block("BLOCK_CAMPOOCULTO");

$tpl->show();

$tpl2 = new Template("templates/botoes1.html");
$tpl2->block("BLOCK_LINHAHORIZONTAL_EMCIMA");

//Salvar
$tpl2->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl2->block("BLOCK_BOTAOPADRAO_SALVAR");
$tpl2->block("BLOCK_BOTAOPADRAO");
$tpl2->block("BLOCK_COLUNA");

//Cancelar
$tpl2->COLUNA_LINK_ARQUIVO = "relatorios.php";
$tpl2->block("BLOCK_COLUNA_LINK");
$tpl2->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl2->block("BLOCK_BOTAOPADRAO_CANCELAR");
$tpl2->block("BLOCK_BOTAOPADRAO");
$tpl2->block("BLOCK_COLUNA");

$tpl2->block("BLOCK_LINHA");
$tpl2->block("BLOCK_BOTOES");

$tpl2->show();
?>
