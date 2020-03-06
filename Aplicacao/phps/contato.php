<?php

require "login_verifica.php";

include "includes.php";
//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "SUPORTE";
$tpl_titulo->SUBTITULO = "PRECISA DE AJUDA?";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "contato.png";
$tpl_titulo->show();

$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl->LINK_DESTINO="contato2.php";


$tpl->TITULO="Usuário";
$tpl->block("BLOCK_TITULO");

//Campo
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="usuario";
$tpl->CAMPO_ID="usuario";
$tpl->CAMPO_VALOR="$usuario_nome";
//$tpl->CAMPO_QTD_CARACTERES="";

$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO_DESABILITADO");
//$tpl->block("BLOCK_CAMPO_SOMENTELEITURA");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


$tpl->TITULO="Em que podemos ajudar?";
$tpl->block("BLOCK_TITULO");
$tpl->TEXTAREA_NOME="descricao";
$tpl->TEXTAREA_TAMANHO="80";
$tpl->TEXTAREA_TEXTO="";
$tpl->block("BLOCK_TEXTAREA_OBRIGATORIO");
$tpl->block("BLOCK_TEXTAREA");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

$tpl->TITULO="Emergência / Serviços";
$tpl->block("BLOCK_TITULO");
$tpl->TEXTO="(51) 8517-1790";
$tpl->block("BLOCK_TEXTO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

$tpl->BOTAO_TIPO="submit";
$tpl->BOTAO_VALOR="ENVIAR";
$tpl->BOTAO_NOME="ENVIAR";
$tpl->block("BLOCK_BOTAO");


//Voltar
$tpl->block("BLOCK_BOTAO_VOLTAR");

$tpl->block("BLOCK_BOTOES");

$tpl->show();



include "rodape.php";
    

?>


