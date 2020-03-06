<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_consumidores_grupos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "pessoas";
include "includes.php";


$operacao = $_POST['operacao'];
$codigo = $_POST['codigo']; //Para edicão
$nome = $_POST['nome']; //Para edicão
$datahoraatual=date("Y-m-d H:i:s");


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "GRUPOS DE CONSUMIDORES";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones2";
$tpl_titulo->NOME_ARQUIVO_ICONE = "consumidor.png";
$tpl_titulo->show();

//print_r($_REQUEST);

//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
if ($modal==1) $tpl_notificacao->DESTINO = "javascript:window.close(0);";
else $tpl_notificacao->DESTINO = "consumidores_grupos.php";


//Se a operação for cadastro então
if ($operacao == 'cadastrar') {
    //Verifica se já existe um registro com mesmo nome    
    $sql = "SELECT congru_nome FROM consumidores_grupos WHERE congru_nome='$nome'";
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
            consumidores_grupos (
                congru_nome                
            )
        VALUES (
            '$nome'    
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
    $sql = "SELECT congru_nome FROM consumidores_grupos WHERE congru_codigo='$codigo'";
    $query = mysql_query($sql);
    $dados = mysql_fetch_assoc($query);
    $nome_banco = $dados["congru_nome"];

    if (strtolower($nome) != strtolower($nome_banco)) {
        $sql2 = "SELECT congru_nome FROM consumidores_grupos WHERE congru_nome='$nome'";
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
        consumidores_grupos
    SET            
        congru_nome='$nome'
    WHERE
        congru_codigo='$codigo'
    ";
    if (!mysql_query($sql))
        die("Erro: " . mysql_error());
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}
?>

