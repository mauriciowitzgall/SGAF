<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";


$operacao = $_POST['operacao'];
$codigo = $_POST['codigo']; //Para edicão
$nome = $_POST['nome'];
$local = $_POST['local'];
$datahoraatual=date("Y-m-d H:i:s");


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixas.png";
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

//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "caixas.php";


//Se a operação for cadastro então
if ($operacao == 'cadastrar') {
    //Verifica se já existe um caixa com mesmo nome    
    $sql = "SELECT cai_nome FROM caixas WHERE cai_nome='$nome' and cai_quiosque=$usuario_quiosque";
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
            caixas (
                cai_nome,
                cai_local,
                cai_quiosque,
                cai_situacao,
                cai_datahoracadastro,
                cai_status
            )
        VALUES (
            '$nome',
            '$local',
            '$usuario_quiosque',
            '2',
            '$datahoraatual',
            '1'    
        )";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro de SQL:" . mysql_error());
        $ultimo=  mysql_insert_id();
        
        $tpl_notificacao->block("BLOCK_CADASTRADO");
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        
        //Se o usuário cadastrou o primeiro caixa então sugerir/orientar a abrir o caixa
        $tpl_notificacao->MOTIVO_COMPLEMENTO="Para efetuar vendas é necessário que você abra o caixa.";
        $tpl_notificacao->PERGUNTA="Gostaria de abrir o caixa criado?";
        $tpl_notificacao->block("BLOCK_PERGUNTA");
        $tpl_notificacao->LINK="caixas_operacoes_abrir.php?codigo=$ultimo"; //Sim
        $tpl_notificacao->NAO_LINK="caixas.php"; //Não
        $tpl_notificacao->block("BLOCK_BOTAO_NAO_LINK");
        $tpl_notificacao->block("BLOCK_BOTAO_SIMNAO");
        $tpl_notificacao->show();
    }

}

//Se a operação for edição então
if ($operacao == 'editar') {
    //Verifica se já existe registro com o mesmo nome
    $sql = "SELECT cai_nome FROM caixas WHERE cai_codigo='$codigo'";
    $query = mysql_query($sql);
    $dados = mysql_fetch_assoc($query);
    $nome_banco = $dados["cai_nome"];

    if (strtolower($nome) != strtolower($nome_banco)) {
        $sql2 = "SELECT cai_nome FROM caixas WHERE cai_nome='$nome'";
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
        caixas
    SET            
        cai_nome='$nome',
        cai_local='$local'
    WHERE
        cai_codigo='$codigo'
    ";
    if (!mysql_query($sql))
        die("Erro: " . mysql_error());
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}
?>

