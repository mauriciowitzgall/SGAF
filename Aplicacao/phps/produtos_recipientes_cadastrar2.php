<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "produtos";
include "includes.php";


$operacao = $_POST['operacao'];
$codigo = $_POST['codigo']; //Para edicão
$nome = $_POST['nome']; //Para edicão
$modal=$_GET['modal'];
$datahoraatual=date("Y-m-d H:i:s");


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "RECIPIENTES";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "recipientes.png";
$tpl_titulo->show();

//print_r($_REQUEST);
//só pode editar recipientes nao globais
if ($operação=='editar') {
    $sql="SELECT * FROM produtos_recipientes WHERE prorec_codigo=$codigo";
    if (!$query= mysql_query($sql)) die("ERRO SQL: ".mysql_error ());
    while($dados=  mysql_fetch_assoc($query)) {
        $recipienteglobal=$dados["prorec_global"];
    }
    if ($recipienteglobal==1) {
        $tpl6 = new Template("templates/notificacao.html");
        $tpl6->block("BLOCK_ERRO");
        $tpl6->ICONES = $icones;
        //$tpl6->block("BLOCK_NAOAPAGADO");
        $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Você só pode editar recipientes não globais, ou seja, os cadastrados por vocês mesmo!";
        $tpl6->block("BLOCK_MOTIVO");
        $tpl6->block("BLOCK_BOTAO_VOLTAR");
        $tpl6->show();
        exit;
    }
}

//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
if ($modal==1) $tpl_notificacao->DESTINO = "javascript:window.close(0);";
else $tpl_notificacao->DESTINO = "produtos_recipientes.php";


//Se a operação for cadastro então
if ($operacao == 'cadastrar') {
    //Verifica se já existe um registro com mesmo nome    
    $sql = "SELECT prorec_nome FROM produtos_recipientes WHERE prorec_nome='$nome'";
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
            produtos_recipientes (
                prorec_nome,
                prorec_global
            )
        VALUES (
            '$nome',
            '0'    
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

}

//Se a operação for edição então
if ($operacao == 'editar') {
    //Verifica se já existe registro com o mesmo nome
    $sql = "SELECT prorec_nome FROM produtos_recipientes WHERE prorec_codigo='$codigo'";
    $query = mysql_query($sql);
    $dados = mysql_fetch_assoc($query);
    $nome_banco = $dados["tax_nome"];

    if (strtolower($nome) != strtolower($nome_banco)) {
        $sql2 = "SELECT prorec_nome FROM produtos_recipientes WHERE prorec_nome='$nome'";
        $query2 = mysql_query($sql2);
        if (mysql_num_rows($query2) > 0) {
            $tpl_notificacao->block("BLOCK_ERRO");
            $tpl_notificacao->block("BLOCK_NAOCADASTRADO");
            $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
            $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
            $tpl_notificacao->show();
            exit;
        }
    }

    $sql = "
    UPDATE
        produtos_recipientes
    SET            
        prorec_nome='$nome'
    WHERE
        prorec_codigo='$codigo'
    ";
    if (!mysql_query($sql))
        die("Erro: " . mysql_error());
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}
?>

