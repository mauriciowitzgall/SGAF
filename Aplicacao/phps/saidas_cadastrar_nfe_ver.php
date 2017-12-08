<?php

//print_r($_REQUEST);

//Verifica se o usuário pode acessar a tela
require "login_verifica.php";
$saida = $_GET["codigo"];
$numero_nota = $_GET["numero_nota"];

$tipopagina = "saidas";
include "includes2.php";

$tpl = new Template("templates/botoes1.html");

//Fechar
$tpl->COLUNA_LINK_ARQUIVO = "";
$tpl->block("BLOCK_COLUNA_LINK_FECHAR");                 
$tpl->block("BLOCK_COLUNA_LINK");                 
$tpl->block("BLOCK_BOTAOPADRAO_SIMPLES");                 
$tpl->block("BLOCK_BOTAOPADRAO_FECHAR");                 
$tpl->block("BLOCK_BOTAOPADRAO");                 
//$tpl->block("BLOCK_BOTAO_DESABILITADO"); 
//$tpl->block("BLOCK_BOTAO_AUTOFOCO"); 
//$tpl->block("BLOCK_BOTAO_PADRAO");
$tpl->block("BLOCK_COLUNA");


//Cancelar Nota
$tpl->COLUNA_LINK_ARQUIVO = "saidas_cadastrar_nfe.php?codigo=$saida&ope=2";
$tpl->block("BLOCK_COLUNA_LINK");
//$tpl->BOTAO_TECLA="";
$tpl->BOTAO_TIPO = "button";
$tpl->BOTAO_VALOR = "CANCELAR NOTA FISCAL";
//$tpl->BOTAO_NOME=""; 
//$tpl->BOTAO_ID="";
//$tpl->BOTAO_DICA="";
//$tpl->BOTAO_ONCLICK="";
//$tpl->BOTAOPADRAO_CLASSE="";
$tpl->BOTAO_CLASSE="botao botaovermelho fonte3";
$tpl->block("BLOCK_BOTAO_DINAMICO");                 
//$tpl->block("BLOCK_BOTAO_DESABILITADO"); 
//$tpl->block("BLOCK_BOTAO_AUTOFOCO"); 
//$tpl->block("BLOCK_BOTAO_PADRAO");
$tpl->block("BLOCK_BOTAO");
$tpl->block("BLOCK_COLUNA");


$tpl->block("BLOCK_LINHA");
$tpl->block("BLOCK_BOTOES");
$tpl->block("BLOCK_FECHARFORM");
$tpl->block("BLOCK_LINHAHORIZONTAL_EMBAIXO");
$tpl->show();

?>
<iframe src="pdf" height="100%" width="100%"></iframe>


<?php
include "rodape.php";


?>
