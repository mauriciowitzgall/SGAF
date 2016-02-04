<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_relatorios_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "relatorios";
include "includes.php";

$operacao = $_POST['operacao'];
$codigo = $_POST['codigo'];
$relatorionome = $_POST['relatorionome'];
$descricao = $_POST['descricao'];
$box = $_POST['box'];
$data = date("Y-m-d");
$hora = date("H:i:s");

//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "RELAT�RIOS";
if ($operacao == 'cadastrar')
    $tpl_titulo->SUBTITULO = "CADASTRO";
else
    $tpl_titulo->SUBTITULO = "EDI��O";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "relatorios.png";
$tpl_titulo->show();



//OPERA��ES
//Estrutura da notifica��o
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "relatorios.php";

//Se a opera��o for cadastro ent�o
if ($operacao == 'cadastrar') {
    //Verifica se j� existe um relatorio com mesmo nome    
    $sql = "SELECT rel_nome FROM relatorios WHERE rel_nome='$relatorionome'";
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
            relatorios (
                rel_nome,
                rel_descricao,
                rel_datacadastro,
                rel_horacadastro
            )
        VALUES (
            '$relatorionome',
            '$descricao',
            '$data',
            '$hora'
        )";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro de SQL:" . mysql_error());
        $ultimo = mysql_insert_id();        
        foreach ($box as $box) {
            $sql2 = "
                INSERT INTO relatorios_permissao 
                ( relper_relatorio, relper_grupo ) 
                VALUES ('$ultimo','$box')
            ";
            if (!mysql_query($sql2))
                die("Erro7: " . mysql_error());
        }


        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_CADASTRADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
}

//Se a opera��o for edi��o ent�o
if ($operacao == 'editar') {
    //Verifica se j� existe registro com o mesmo nome
    $sql = "SELECT rel_nome FROM relatorios WHERE rel_codigo='$codigo'";
    $query = mysql_query($sql);
    $dados = mysql_fetch_assoc($query);
    $nome_banco = $dados["rel_nome"];
    if (strtolower($relatorionome) != strtolower($nome_banco)) {
        $sql2 = "SELECT rel_nome FROM relatorios WHERE rel_nome='$relatorionome'";
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
        relatorios
    SET            
        rel_nome='$relatorionome',
        rel_descricao='$descricao'
    WHERE
        rel_codigo='$codigo'
    ";
    if (!mysql_query($sql))
        die("Erro: " . mysql_error());
    //Remove os relacionamento e faz novamente
    $sqldel = "
        DELETE FROM 
            relatorios_permissao
        WHERE 
            relper_relatorio='$codigo'
        ";
    if (!mysql_query($sqldel))
        die("Erro9: " . mysql_error());

    //Aqui � feito a nova inser��o dos novos relacionamentos                
    foreach ($box as $box) {
        $sql2 = "
            INSERT INTO  relatorios_permissao 
            ( relper_relatorio, relper_grupo )
            VALUES ( '$codigo','$box' )
        ";
        if (!mysql_query($sql2))
            die("Erro10: " . mysql_error());
    }





    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}
?>

