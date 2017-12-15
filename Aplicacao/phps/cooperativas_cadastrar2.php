<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_cooperativa_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativa"; //Submenu
include "includes.php";
//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "COOPERATIVAS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "cooperativas.png";
$tpl_titulo->show();

$codigo = $_POST["codigo"];
$nome = $_POST["nome"];
$abreviacao = $_POST["abreviacao"];
$erro = 0;


//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "troca_unidade.php";

//VALIDA��ES
//Verifica se já existe registros com o mesmo nome    
//Nome Completo
$sql = "SELECT * FROM cooperativas WHERE coo_nomecompleto='$nome'";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
$nomenobanco = $_POST["nomenobanco"];
if ($nome == $nomenobanco)
    $linhatot = 1;
else
    $linhatot = 0;
if ($linhas > $linhatot) {
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome completo da cooperativa";
    $tpl_notificacao->block("BLOCK_ERRO");
    $tpl_notificacao->block("BLOCK_NAOEDITADO");
    $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
    $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
    $tpl_notificacao->show();
    $erro = 1;
}

//Se n�o houver erro ent�o cadastrar ou atualizar
if ($erro == 0) {
//Caso a operação seja CADASTRO    
    if ($codigo == "") {
        //Inserir registro
        $sql = "
        INSERT INTO
            cooperativas (coo_nomecompleto,coo_abreviacao)
        VALUES 
            ('$nome','$abreviacao');
        ";
        if (!mysql_query($sql))
            die("Erro: " . mysql_error());
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_CADASTRADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    } else { //Caso a operação seja EDIÇÃO
        
        //Atualiza os dados da cooperativa
        $sql = "
        UPDATE
            cooperativas
        SET
            coo_nomecompleto='$nome',
            coo_abreviacao='$abreviacao'
        WHERE
            coo_codigo = '$codigo'
        ";
        if (!mysql_query($sql))
            die("Erro: " . mysql_error());
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->block("BLOCK_ATENCAO");
        $tpl_notificacao->block("BLOCK_EDITADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
}
include "rodape.php";
?>
