<?php

$tpl = new Template("templates/listagem_2.html");

//Javascript
$tpl->JS_CAMINHO="";
$tpl->block("BLOCK_JS");

//-- FILTROS

//Form
$tpl->FORM_ONLOAD="";
$tpl->LINK_FILTRO="";

//Campo
$tpl->CAMPO_TITULO="";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_NOME="";
$tpl->CAMPO_VALOR="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->CAMPO_ONKEYUP="";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");

//Select
$tpl->SELECT_TITULO="";
$tpl->SELECT_NOME="";
$tpl->SELECT_TAMANHO="";
$tpl->block("BLOCK_SELECT_FOCO");
$tpl->block("BLOCK_SELECT_DESABILITADO");
$tpl->OPTION_VALOR="";
$tpl->OPTION_NOME="";
$tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->block("BLOCK_FILTRO_SELECT");

//Botão Personalizado
$tpl->LINK="";
$tpl->BOTAO_NOME="";
$tpl->block("BLOCK_AUTOFOCO");
$tpl->block("BLOCK_RODAPE_BOTAO_MODELO_DESABILITADO");
$tpl->block("BLOCK_RODAPE_BOTAO_MODELO");

$tpl->block("BLOCK_FILTRO_COLUNA");

//Botão Pesquisar, Reiniciar Pesquisa e Cadastrar
$tpl->LINK_CADASTRO="";
$tpl->BOTAO_CADASTRAR_NOME="";
$tpl->block("BLOCK_RODAPE_BOTAO_CADASTRAR_DESABILITADO");
$tpl->block("BLOCK_FILTRO_BOTAO_CAD");
$tpl->block("BLOCK_FILTRO_BOTOES");

$tpl->block("BLOCK_FILTRO");

// ----- LINHA ------
//Linha
$tpl->LINHA_CLASSE="";
$tpl->block("BLOCK_HR");

//Titulo
$tpl->LISTA_TITULO="";
$tpl->block("BLOCK_LISTA_TITULO");

// ------- TABELA LISTAGEM  ---------

//Cabeçalho da tabela
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="";
$tpl->block("BLOCK_LISTA_CABECALHO");


//Coluna padrão (pode chamar uma imagem ao lado mais de uma vez)
$tpl->LISTA_COLUNA_ALINHAMENTO="";
$tpl->LISTA_COLUNA_CLASSE="";
$tpl->LISTA_COLUNA_TAMANHO="";
$tpl->LISTA_COLUNA_VALOR="";
$tpl->LINK=""; //Imagem ao lado do texto com icone
$tpl->IMAGEM_TAMANHO="";
$tpl->IMAGEM_PASTA="";
$tpl->IMAGEM_NOMEARQUIVO="";
$tpl->IMAGEM_TITULO="";
$tpl->block("BLOCK_LISTA_COLUNA_ICONE"); 
$tpl->block("BLOCK_LISTA_COLUNA");


//Texto com Icone
$tpl->LISTA_COLUNA2_ALINHAMENTO=""; 
$tpl->LISTA_COLUNA2_VALOR="";
$tpl->LISTA_COLUNA2_ALINHAMENTO2=""; 
$tpl->LISTA_COLUNA2_CLASSE="";
$tpl->LISTA_COLUNA2_LINK="";
$tpl->IMAGEM_PASTA="";
$tpl->DESABILITADO="";
$tpl->block("BLOCK_LISTA_COLUNA2");

//Icone Imagem com texto na esquerda
$tpl->ICONES_TEXTO_ALINHAMENTO="";
$tpl->ICONES_TEXTO_CLASSE="";
$tpl->ICONES_TEXTO_TAMANHOCAMPO="";
$tpl->ICONES_TEXTO_ESTILO="";
$tpl->ICONES_TEXTO_VALOR="";
$tpl->block("BLOCK_LISTA_COLUNA_ICONES_TEXTO");
$tpl->IMAGEM_ALINHAMENTO="";
$tpl->LINK="";
$tpl->IMAGEM_TAMANHO="";
$tpl->IMAGEM_PASTA="";
$tpl->IMAGEM_NOMEARQUIVO="";
$tpl->IMAGEM_TITULO="";
$tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
$tpl->block("BLOCK_LISTA_COLUNA_ICONES");


//Outra operação
$tpl->COLUNA_CLASSE="";
$tpl->LINK="";
$tpl->CODIGO="";
$tpl->LINK_COMPLEMENTO="";
$tpl->ICONE_ARQUIVO="";
$tpl->OPERACAO_TITULO="";
$tpl->OPERACAO_NOME="";
$tpl->ICONE_NOME="";
$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");

//Imprimir
$tpl->LINK="";
$tpl->CODIGO="";
$tpl->LINK_COMPLEMENTO="";
$tpl->ICONE_ARQUIVO="";
$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_IMPRIMIR");
$tpl->ICONE_ARQUIVO="";
$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_IMPRIMIR_DESABILITADO");

//Detalhes
$tpl->LINK="";
$tpl->CODIGO="";
$tpl->LINK_COMPLEMENTO="";
$tpl->ICONE_ARQUIVO="";
$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DETALHES");
$tpl->ICONE_ARQUIVO="";
$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DETALHES_DESABILITADO");

//Editar
$tpl->LINK="";
$tpl->CODIGO="";
$tpl->LINK_COMPLEMENTO="";
$tpl->ICONE_ARQUIVO="";
$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR");
$tpl->ICONE_ARQUIVO="";
$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR_DESABILITADO");

//Excluir
$tpl->LINK="";
$tpl->CODIGO="";
$tpl->LINK_COMPLEMENTO="";
$tpl->ICONE_ARQUIVO="";
$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR");
$tpl->ICONE_ARQUIVO="";
$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR_DESABILITADO");

$tpl->block("BLOCK_LISTA"); 

//Nenhum Resultado
$tpl->block("BLOCK_LISTA_NADA");


//Paginação
$tpl->PASTA_ICONES="";
$tpl->PAGINAATUAL="";
$tpl->PAGINAS="";
$tpl->PASTA_ICONES="";
$tpl->block("BLOCK_PAGINACAO");

//Botões ao final
$tpl->LINK_VOLTAR="";
$tpl->block("BLOCK_RODAPE_BOTAO_VOLTAR_DESABILITADO");
$tpl->block("BLOCK_RODAPE_BOTAO_VOLTAR");
$tpl->block("BLOCK_RODAPE_BOTAO");
$tpl->block("BLOCK_RODAPE_BOTOES");

$tpl->show();


?>