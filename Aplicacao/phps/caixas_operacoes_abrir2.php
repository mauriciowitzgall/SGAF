<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operacoes_abrir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

$caixa=$_GET["codigo"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS OPERACOES";
$tpl_titulo->SUBTITULO = "ABRIR CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixas_abrir.png";
$tpl_titulo->show();

if ($usacaixa!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}


$operacao = $_POST['operacao'];
$caixa = $_POST['caixa2']; //Para edicão
$operador = $usuario_codigo;
$valorinicial = $_POST['valorinicial'];
$valorinicial = dinheiro_para_numero($valorinicial);
$dataini = $_POST['dataini'];
$horaini = $_POST['horaini'];
$datahoraatual=date("Y-m-d H:i:s");
$datahoraabertura=$dataini." ".$horaini;

//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
if ($usuario_grupo==4)
    $tpl_notificacao->DESTINO = "saidas_cadastrar.php?tiposaida=1";
else
    $tpl_notificacao->DESTINO = "caixas_operacoes.php?codigo=$caixa";



//Se a operação for cadastro então
if ($operacao == 'cadastrar') {
    //Verifica se a situação atual do caixa é fechado para poder abrir  
    $sql = "SELECT cai_situacao FROM caixas WHERE cai_codigo='$caixa'";
    $query = mysql_query($sql);
    $dados =  mysql_fetch_assoc($query);
    $situacao = $dados["cai_situacao"];
    if ($situacao==1) {
        $tpl_notificacao->block("BLOCK_ERRO");
        //$tpl_notificacao->block("BLOCK_NAOCADASTRADO");
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "Este caixa já está aberto!";
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
        exit;
    } else {
        //Insere novo registro
        $sql2 = "
        INSERT INTO 
            caixas_operacoes (
                caiopo_caixa,
                caiopo_datahoraabertura,
                caiopo_operador,
                caiopo_valorinicial
            )
        VALUES (
            '$caixa',
            '$datahoraabertura',
            '$operador',
            '$valorinicial'
        )";
        $query2 = mysql_query($sql2);
        if (!$query2) die("Erro de SQL 1:" . mysql_error());
        $numero=  mysql_insert_id();
        
        //Altera a situação do caixa para Aberto
        $sql2="UPDATE caixas SET cai_situacao=1 WHERE cai_codigo=$caixa";
        $query2 = mysql_query($sql2);
        if (!$query2) die("Erro de SQL 2:" . mysql_error());
        
        
        //Atualiza o numero da operação caixa do usuario
        $sql2="UPDATE pessoas SET pes_caixaoperacaonumero=$numero WHERE pes_codigo=$usuario_codigo";
        $query2 = mysql_query($sql2);
        if (!$query2) die("Erro de SQL 4:" . mysql_error());
        
        
        
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "Caixa aberto com sucesso!";
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
}


?>

