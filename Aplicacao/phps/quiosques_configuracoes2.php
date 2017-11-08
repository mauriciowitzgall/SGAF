<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
$tipopagina="quiosque_configuracao";
require "login_verifica.php";
include "includes.php";

//print_r($_REQUEST);

$quiosque = $_POST['quiosque'];
$usamodulofiscal = $_POST['usamodulofiscal'];
$crtnfe = $_POST['crtnfe'];
$cstnfe = $_POST['cstnfe'];
$csosnnfe = $_POST['csosnnfe'];
$serienfe = $_POST['serienfe'];
$tipoimpressaodanfe = $_POST['tipoimpressaodanfe'];
$ambientenfe = $_POST['ambientenfe'];
$versaonfe = $_POST['versaonfe'];
$razaosocial = $_POST['razaosocial'];
$tipopessoanfe = $_POST['tipopessoanfe'];
$usacomanda = $_POST['usacomanda'];
$usacaixa = $_POST['usacaixa'];
$usavendas = $_POST['usavendas'];
$usaproducao = $_POST['usaproducao'];
$usaestoque = $_POST['usaestoque'];
$usavendaporcoes = $_POST['usavendaporcoes'];
$classificacaopadraoestoque = $_POST['classificacaopadraoestoque'];
$devolucoessobrevendas = $_POST['devolucoessobrevendas'];
$pagamentosparciais = $_POST['pagamentosparciais'];
$permiteedicaoclientenavenda = $_POST['permiteedicaoclientenavenda'];
$cpf = $_POST['cpf'];
$cpf = str_replace(".","", $cpf);
$cpf = str_replace("-","", $cpf);
$cnpj = $_POST['cnpj'];
$cnpj = str_replace(".","", $cnpj);
$cnpj = str_replace("/","", $cnpj);
$cnpj = str_replace("-","", $cnpj);
$ie = $_POST['ie'];
$ie = str_replace(".","", $ie);
$ie = str_replace("/","", $ie);
$ie = str_replace("-","", $ie);
$im = $_POST['im'];
$im = str_replace(".","", $im);
$im = str_replace("/","", $im);
$im = str_replace("-","", $im);

$endereco = $_POST['endereco'];
$endereco_numero = $_POST['endereco_numero'];
$bairro = $_POST['bairro'];
$cep = $_POST['cep'];


if ($usuario_grupo==1) {
    $ultimanfe = $_POST['ultimanfe'];
    $complemento.=" , quicnf_ultimanfe='$ultimanfe'"; 
}



//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "QUIOSQUE";
$tpl_titulo->SUBTITULO = "CONFIGURACOES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques_configuracoes.png";
$tpl_titulo->show();


//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "quiosques_configuracoes.php";

$sql = "
UPDATE
    quiosques_configuracoes
SET            
    quicnf_usamodulofiscal='$usamodulofiscal',
    quicnf_crtnfe='$crtnfe',
    quicnf_cstnfe='$cstnfe',
    quicnf_csosnnfe='$csosnnfe',
    quicnf_serienfe='$serienfe',
    quicnf_tipoimpressaodanfe='$tipoimpressaodanfe',
    quicnf_ambientenfe='$ambientenfe',
    quicnf_usacomanda='$usacomanda',
    quicnf_classificacaopadraoestoque='$classificacaopadraoestoque',
    quicnf_devolucoessobrevendas='$devolucoessobrevendas',
    quicnf_pagamentosparciais='$pagamentosparciais',
    quicnf_permiteedicaoclientenavenda=$permiteedicaoclientenavenda,
    quicnf_versaonfe='$versaonfe',
    quicnf_usamodulocaixa='$usacaixa',
    quicnf_usamodulovendas='$usavendas',
    quicnf_usamoduloproducao='$usaproducao',
    quicnf_usamoduloestoque='$usaestoque',
    quicnf_usavendaporcoes='$usavendaporcoes'
    $complemento    
WHERE
    quicnf_quiosque=$quiosque
";
if (!mysql_query($sql)) die("Erro SQL quiosques_configuracoes: " . mysql_error());

$sql2 = "
UPDATE
    quiosques
SET            
    qui_razaosocial='$razaosocial',
    qui_cnpj='$cnpj',
    qui_ie='$ie',
    qui_cpf='$cpf',
    qui_tipopessoa='$tipopessoanfe',
    qui_endereco='$endereco',
    qui_numero='$endereco_numero',
    qui_bairro='$bairro',
    qui_cep='$cep',
    qui_im='$im'
WHERE
    qui_codigo=$quiosque
";
if (!mysql_query($sql2)) die("Erro SQL quiosques: " . mysql_error());




$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_EDITADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();

?>
