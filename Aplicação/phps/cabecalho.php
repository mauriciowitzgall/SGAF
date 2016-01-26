<?php

include "controle/conexao.php";
//require("templates/Template.class.php");
$tpl8 = new Template("cabecalho.html");

if ($usuario_grupo == 7) {
    $tpl8->COOPERATIVA = "O USUÁRIO ROOT NÃO PERTENCE A NENHUMA COOPERATIVA";
    $tpl8->QUIOSQUE = "USUÁRIO ROOT";
    $tpl8->USUARIO_NOME = "";
    $tpl8->CODIGO_USUARIO = "";
} else {
    $tpl8->COOPERATIVA = "$usuario_cooperativanomecompleto ($usuario_cooperativaabreviacao)";
    $tpl8->QUIOSQUE = $usuario_quiosquenome;
    $tpl8->USUARIO_NOME = $usuario_nome;
    $tpl8->CODIGO_USUARIO = $usuario_codigo;
}

if ($usuario_grupo == 0) {
    //$tpl8->USUARIO_TIPO_ARQUIVO = "../geral/help.png";
    $tpl8->USUARIO_GRUPO = "Desconhecido";
} else {
    $tpl8->USUARIO_GRUPO = $permissao_nome;
}
if ($usuario_grupo != 7) {    
    $tpl8->block("BLOCK_MEUPERFIL");
}
if (($usuario_grupo==7)||($usuario_grupo==1)) {
    //$tpl8->block("BLOCK_CONFIGURACOES");
}

$tpl8->show();
?>
