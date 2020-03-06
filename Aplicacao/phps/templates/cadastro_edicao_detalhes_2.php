<?php

$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

//Javascript
$tpl->JS_CAMINHO=""; 
$tpl->block("BLOCK_JS");

//--------------------------------

//Linha
$tpl->LINK_DESTINO=""; //Destino do formulário
$tpl->LINK_TARGET=""; //_self _blank

//Titulo da linha
$tpl->LINHA_CLASSE="";
$tpl->block("BLOCK_LINHA_CLASSE");
$tpl->LINHA_ID="";
$tpl->block("BLOCK_LINHA_ID");
$tpl->COLUNA_CLASSE="";
$tpl->block("BLOCK_COLUNA_CLASSE");
$tpl->TITULO_ID="";
$tpl->block("BLOCK_TITULO_ID");
$tpl->TITULO="";
$tpl->ASTERISCO=""; //Simbolo do obrigatório
$tpl->block("BLOCK_TITULO");

//Link
$tpl1->LINK=".php";
$tpl1->LINK_TARGET="_blank";
$tpl1->LINK_NOME="";
$tpl1->LINK_ID="";
$tpl1->block("BLOCK_LINK");


//Campo
$tpl->CAMPO_TIPO="";
$tpl->CAMPO_NOME="";
$tpl->CAMPO_ID="";
$tpl->CAMPO_TAMANHO="";
$tpl->CAMPO_VALOR="";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->CAMPO_ONKEYUP="";
$tpl->CAMPO_ONKEYDOWN="";
$tpl->CAMPO_ONKEYPRESS="";
$tpl->CAMPO_ONBLUR="";
$tpl->CAMPO_ONCLICK="";
$tpl->CAMPO_DICA="";
$tpl->block("BLOCK_CAMPO_AUTOSELECIONAR"); //Clicou seleciona conteudo
$tpl->block("BLOCK_CAMPO_HISTORICO_DESATIVADO"); //autocomplete do navegador desligado
$tpl->block("BLOCK_CAMPO_FOCO");
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO_NORMAL_OCULTO"); //Campo text que não aparece na tela
$tpl->block("BLOCK_CAMPO_PASSWORD");
$tpl->block("BLOCK_CAMPO_PASSWORD_OBRIGATORIO");
$tpl->CAMPO_ESTILO="";
$tpl->block("BLOCK_CAMPO_ESTILO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_SOMENTELEITURA");
$tpl->block("BLOCK_CAMPO");


//Select
$tpl->SELECT_NOME="";
$tpl->SELECT_ID="";
$tpl->SELECT_TAMANHO="";
$tpl->SELECT_ONCHANGE="";
$tpl->block("BLOCK_SELECT_ONCHANGE");
$tpl->block("BLOCK_SELECT_FOCO"); //autofocus
$tpl->block("BLOCK_SELECT_NORMAL"); //Classe campopadrao
$tpl->block("BLOCK_SELECT_OBRIGATORIO");
$tpl->block("BLOCK_SELECT_DESABILITADO");
$tpl->SELECT_ESTILO="";
$tpl->block("BLOCK_SELECT_ESTILO");

$tpl->block("BLOCK_SELECT_OPTION_PADRAO"); //Selecione
$tpl->block("BLOCK_SELECT_OPTION_PADRAO2"); //Todos
$tpl->block("BLOCK_SELECT_OPTION_PADRAO3"); //Nulo
$tpl->OPTION_VALOR="";
$tpl->block("BLOCK_SELECT_OPTION_SELECIONADO");
$tpl->OPTION_NOME="";
$tpl->block("BLOCK_SELECT_OPTION");
$tpl->block("BLOCK_SELECT");

//Checkbox
$tpl->CHECKBOX_SPAN_CLASSE="";
$tpl->CHECKBOX_SPAN_ID="";
$tpl->CHECKBOX_VALOR="";
$tpl->CHECKBOX_NOME="";
$tpl->CHECKBOX_ID="";
$tpl->CHECKBOX_ONCLICK="";
$tpl->block("BLOCK_CHECKBOX_ONCLICK");
$tpl->block("BLOCK_CHECKBOX_SELECIONADO");
$tpl->block("BLOCK_CHECKBOX_DESABILITADO");
$tpl->LABEL_NOME="";
$tpl->CHECKBOX_ICONE_MENSAGEM="";
$tpl->CHECKBOX_ICONE_MENSAGEM="";
$tpl->CHECKBOX_ICONE_ARQUIVO="";
$tpl->block("BLOCK_CHECKBOX_ICONE");
$tpl->block("BLOCK_CHECKBOX");


//TextArea
$tpl->block("BLOCK_TEXTAREA_DESABILITADO");
$tpl->block("BLOCK_TEXTAREA_OBRIGATORIO");
$tpl->TEXTAREA_NOME="";
$tpl->TEXTAREA_TAMANHO="";
$tpl->TEXTAREA_TEXTO="";
$tpl->block("BLOCK_TEXTAREA");

//Imagem
$tpl->COMPLEMENTO_ICONE_ARQUIVO="";
$tpl->COMPLEMENTO_ICONE_MENSAGEM="";
$tpl->block("BLOCK_COMPLEMENTO_ICONE");

//Texto com SPAN
$tpl->SPAN_NOME="";
$tpl->SPAN_ID="";
$tpl->SPAN_CONTEUDO="";
$tpl->block("BLOCK_COMPLEMENTO");

// Texto
$tpl->TEXTO_ID="";
$tpl->TEXTO="";
$tpl->block("BLOCK_TEXTO");


$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//------------------

//Botão com link
$tpl->BOTAO1_LINK="";
$tpl->LINK_TARGET="";
$tpl->BOTAO_TIPO="";
$tpl->BOTAO_VALOR="";
$tpl->BOTAO_NOME="";
$tpl->BOTAO_FOCO="";
$tpl->block("BLOCK_BOTAO1_COMLINK");

//Botão sem link
$tpl->BOTAO_TIPO="";
$tpl->BOTAO_VALOR="";
$tpl->BOTAO_NOME="";
$tpl->BOTAO_FOCO="";
$tpl->block("BLOCK_BOTAO1_SEMLINK");

$tpl->block("BLOCK_BOTAO1");



// Campos ocultos
$tpl->CAMPOOCULTO_VALOR="";
$tpl->CAMPOOCULTO_NOME="";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//---------------------------------
// Botões Rodapé

//Botão Principal ou Submit
$tpl->BOTAO_LINK="";
$tpl->BOTAO_TIPO="";
$tpl->BOTAO_VALOR="";
$tpl->BOTAO_NOME="";
$tpl->BOTAO_ONCLICK="";
$tpl->block("BLOCK_BOTAO");

//Botão Salvar
$tpl->block("BLOCK_BOTAO_SALVAR");

//Botão Geral
$tpl->BOTAO_NOME="";
$tpl->block("BLOCK_BOTAO_GERAL");

//botão Cancelar
$tpl->BOTAO_LINK="";
$tpl->block("BLOCK_BOTAO_CANCELAR");

//Botão com LINK
$tpl->BOTAO_NOME="";
$tpl->BOTAO_LINK="";
$tpl->block("BLOCK_BOTAO_VARIADO");

//Voltar
$tpl->block("BLOCK_BOTAO_VOLTAR");

$tpl->block("BLOCK_BOTOES");

$tpl->show();


?>