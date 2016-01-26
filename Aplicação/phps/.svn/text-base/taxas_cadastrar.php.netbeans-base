<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_taxas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "taxas";
include "includes.php";
$operacao = $_GET["operacao"];
if (empty($operacao))
    $operacao = "cadastrar";



//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TAXAS";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "taxas.png";
$tpl_titulo->show();



//FORMULÁRIO
$tpl = new Template("templates/cadastro1.html");

$codigo = $_GET["codigo"];
if (!empty($codigo)) {
    $sql = "SELECT tax_codigo,tax_nome,tax_descricao FROM taxas WHERE tax_codigo=$codigo";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro SQL Leitura Editar:" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $taxanome = $dados["tax_nome"];
    $descricao = $dados["tax_descricao"];
}

$tpl->FORM_NOME = "";
$tpl->FORM_TARGET = "";
$tpl->FORM_LINK = "taxas_cadastrar2.php";
$tpl->block("BLOCK_FORM");

//Taxa
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "150px";
$tpl->TITULO = "Nome da Taxa";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "taxanome";
$tpl->CAMPO_VALOR = "$taxanome";
$tpl->CAMPO_TAMANHO = "30";
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_AUTOFOCO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Descrição
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "";
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
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Campo Oculto Operação
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
$tpl->block("BLOCK_CONTEUDO");
$tpl2->block("BLOCK_COLUNA");

//Cancelar
$tpl2->COLUNA_LINK_ARQUIVO = "taxas.php";
$tpl2->block("BLOCK_COLUNA_LINK");
$tpl2->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl2->block("BLOCK_BOTAOPADRAO_CANCELAR");
$tpl2->block("BLOCK_BOTAOPADRAO");
$tpl->block("BLOCK_CONTEUDO");
$tpl2->block("BLOCK_COLUNA");

$tpl2->block("BLOCK_LINHA");
$tpl2->block("BLOCK_BOTOES");

$tpl2->show();
?>
