<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_pessoas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "pessoas";
include "includes.php";


$grupo = $_POST['grupo']; 
$datahoraatual=date("Y-m-d H:i:s");
$pessoa = $_REQUEST['pessoa']; 

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PESSOAS  ";
$tpl_titulo->SUBTITULO = "VINCULAR GRUPOS DE CONSUMIDORES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "grupoconsumidores.png";
$tpl_titulo->show();


if ($usagrupoconsumidores!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR USO DE GRUPO DE CONSUMIDORES</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}


//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "pessoas_grupoconsumidores.php?pessoa=$pessoa";



//Verifica se o grupo selecionado já está na lista de grupos deste consumidor
$sql = "SELECT * FROM pessoas_grupoconsumidores WHERE pesgrucon_pessoa=$pessoa and pesgrucon_grupo=$grupo";
$query = mysql_query($sql);
if (mysql_num_rows($query) > 0) {
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOCADASTRADO");
    $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    exit;
} else {
    //Insere novo registro
    $sql2 = "
    INSERT INTO 
        pessoas_grupoconsumidores (
            pesgrucon_pessoa,
            pesgrucon_grupo
        )
    VALUES (
        '$pessoa',
        '$grupo'
        
    )";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro de SQL:" . mysql_error());
    $ultimo=  mysql_insert_id();
    
    $tpl_notificacao->block("BLOCK_CADASTRADO");
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}




?>

