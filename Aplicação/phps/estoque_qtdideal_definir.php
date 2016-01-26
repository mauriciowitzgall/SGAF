<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_estoque_qtdide_definir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "estoque";
include "includes.php";

//TÃTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ESTOQUE";
$tpl_titulo->SUBTITULO = "DEFINIR QUANTIDADE MÃNIMA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "estoque.png";
$tpl_titulo->show();

$produto = $_GET["codigo"];
if (!$qtdide = $_GET["qtdide"])
    $qtdide="0,00";
//FORMULáRIO
$tpl = new Template("templates/cadastro1.html");
$tpl->FORM_NOME = "";
$tpl->FORM_TARGET = "";
$tpl->FORM_LINK = "estoque_qtdideal_definir2.php";
$tpl->block("BLOCK_FORM");

//Produto
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "150px";
$tpl->TITULO = "Produto";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "produto";
$tpl->CAMPO_ID = "produto";
$sql = "
SELECT pro_nome,protip_sigla
FROM produtos 
join produtos_tipo on (pro_tipocontagem=protip_codigo)
WHERE pro_codigo=$produto";
$query = mysql_query($sql);
if (!$query)
    die("Erro no campo produto" . mysql_error());
$dados = mysql_fetch_array($query);
$produto_nome = $dados[0];
$sigla = $dados[1];
$tpl->CAMPO_VALOR = "$produto_nome";
$tpl->CAMPO_TAMANHO = "30";
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Quantidade Ideal
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "50px";
$tpl->TITULO = "Quantidade Ideal";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "qtdide";
$tpl->CAMPO_ID = "qtd";
$tpl->CAMPO_ONKEYUP = "mascara_quantidade()";
$tpl->CAMPO_VALOR = number_format($qtdide,2,',','.');
$tpl->CAMPO_TAMANHO = "8";
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_AUTOFOCO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->TEXTO_NOME = "sigla";
$tpl->TEXTO_ID = "";
$tpl->TEXTO_CLASSE = "";
$tpl->TEXTO_VALOR = "$sigla";
$tpl->block("BLOCK_TEXTO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Campo Código
$tpl->CAMPOOCULTO_NOME = "produto";
$tpl->CAMPOOCULTO_VALOR = "$produto";
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
$tpl2->COLUNA_LINK_ARQUIVO = "estoque_qtdideal.php";
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
