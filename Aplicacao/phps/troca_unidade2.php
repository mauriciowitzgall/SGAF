<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";

include "includes.php";



$cooperativa = $_POST['cooperativa'];
$grupopermissoes = $_POST['grupopermissoes'];
$descricao = $_POST['descricao'];
$quiosque = $_POST['quiosqueusuario'];
if ($quiosque == '')
    $quiosque= 0;


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TROCA QUIOSQUE";
$tpl_titulo->SUBTITULO = "ALTERAÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques_trocar.png";
$tpl_titulo->show();


//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "inicio.php";

$sql = "
UPDATE
    pessoas
SET            
    pes_quiosqueusuario=$quiosque,
    pes_cooperativa=$cooperativa,
    pes_grupopermissoes=$grupopermissoes
WHERE
    pes_codigo=$usuario_codigo
";
if (!mysql_query($sql))
    die("Erro: " . mysql_error());
$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_EDITADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();


//Eliminar caixaoperacaonumero da pessoas que esta trocando de quiosque
$sql="UPDATE pessoas SET pes_caixaoperacaonumero=null WHERE pes_codigo=$usuario_codigo";
if (!$query=mysql_query($sql)) die("Erro SQL limpa caixa padrao: " . mysql_error());
$dados = mysql_fetch_assoc($query);


include "revalidar_sessao.php";


?>

