<?php

$tpl = new Template("templates/notificacao.html");

//Icone personalizado
$tpl->ICONES="";
$tpl->ICONE_ARQUIVO="";
$tpl->TITULO="";
$tpl->block("BLOCK_TITULO");

//Icone Contirmado
$tpl->ICONES="";
$tpl->block("BLOCK_CONFIRMAR");

//Icone Erro
$tpl->ICONES=""; //Pasta
$tpl->block("BLOCK_ERRO");

//Icone Atenção
$tpl->ICONES=""; //Pasta
$tpl->block("BLOCK_ATENCAO");

// ----- LINHA FRASES PRONTAS ------

//Cadastrado Editado ou Excluido 
$tpl->block("BLOCK_APAGADO");
$tpl->block("BLOCK_NAOAPAGADO");
$tpl->block("BLOCK_CADASTRADO");
$tpl->block("BLOCK_NAOCADASTRADO");
$tpl->block("BLOCK_EDITADO");
$tpl->block("BLOCK_NAOEDITADO");

//Já existe um registro cadastrado que possui o(a) mesmo(a)
$tpl->block("BLOCK_MOTIVO_JAEXISTE");

//Este registro está sendo usando em
$tpl->block("BLOCK_MOTIVO_EMUSO");

//Falta Dados
$tpl->FALTADADOS_MOTIVO="";
$tpl->block("BLOCK_MOTIVO_FALTADADOS");

//Não tem permissão para acessar este conteudo
$tpl->block("BLOCK_NAOTEMPERMISSAO");

//Motivo
$tpl->MOTIVO="";
$tpl->block("BLOCK_MOTIVO");

$tpl->MOTIVO_COMPLEMENTO="";

//Linha Pergunta
$tpl->PERGUNTA="";
$tpl->block("BLOCK_PERGUNTA");


// ----- LINHA BOTÕES ------

//Botão Geral
$tpl->BOTAOGERAL_DESTINO="";
$tpl->block("BLOCK_BOTAOGERAL_NOVAJANELA");
$tpl->BOTAOGERAL_TIPO="";
$tpl->BOTAOGERAL_NOME="";
$tpl->block("BLOCK_BOTAOGERAL_AUTOFOCO");
$tpl->block("BLOCK_BOTAOGERAL");

//Botão Continuar
$tpl->DESTINO="";
$tpl->block("BLOCK_BOTAO");

//Botão voltar
$tpl->block("BLOCK_BOTAO_VOLTAR");

//Botão Voltar com Link
$tpl->VOLTAR2_LINK="";
$tpl->block("BLOCK_BOTAO_VOLTAR2");

//Botões Sim e Não
$tpl->NAO_LINK=""; //Não
$tpl->block("BLOCK_BOTAO_NAO_LINK");
$tpl->block("BLOCK_BOTAO_NAO_VOLTAR"); //Voltar do navegador
$tpl->LINK=""; //Sim
$tpl->LINK_TARGET=""; // _self _blank 
$tpl->block("BLOCK_BOTAO_SIMNAO");

//Botão Continuar com Formulário
$tpl->FORM_DESTINO="";
$tpl->CAMPOOCULTO_NOME="";
$tpl->CAMPOOCULTO_VALOR="";
$tpl->block("BLOCK_BOTAO_FORM_CAMPOOCULTO");
$tpl->block("BLOCK_BOTAO_FORM");



?>
