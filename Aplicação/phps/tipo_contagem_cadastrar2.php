<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_tipocontagem_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "produtos";
include "includes.php";

//Template de TÃ­tulo e Sub-tÃ­tulo
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TIPO DE CONTAGEM";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos.png";
$tpl_titulo->show();

$codigo = $_POST["codigo"];
$nome = ucwords(strtolower($_POST["nome"]));
$nome2 = ucwords(strtolower($_POST["nome2"]));
$sigla = $_POST['sigla'];


//Conforme a operação deve-se inserir novo registro ou atualizar os existentes
if ($codigo == "") { //Caso a operação seja CADASTRO    
    //Verifica se já existe registros com o mesmo nome
    $sql = "SELECT * FROM produtos_tipo WHERE protip_nome='$nome' and protip_cooperativa=$usuario_cooperativa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome";
        $tpl_notificacao->DESTINO = "tipo_contagem.php";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOCADASTRADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
    } else {
        $sql = "
        INSERT INTO
            produtos_tipo (protip_nome,protip_sigla,protip_cooperativa)
        VALUES 
            ('$nome','$sigla','$usuario_cooperativa');
        ";
        if (!mysql_query($sql))
            die("Erro: " . mysql_error());
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->DESTINO = "tipo_contagem.php";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_CADASTRADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
} else { //Caso a operação seja EDIÃ‡Ã‚O
    //Verifica se já existe registros com o mesmo nome    
    $sql = "SELECT * FROM produtos_tipo WHERE protip_nome='$nome'";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($nome == $nome2)
        $linhatot = 1;
    else
        $linhatot = 0;
    if ($linhas > $linhatot) {
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome";
        $tpl_notificacao->DESTINO = "tipo_contagem.php";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOEDITADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
    } else {
        $sql = "
    UPDATE
        produtos_tipo
    SET
        protip_nome='$nome',
	protip_sigla='$sigla'
    WHERE
        protip_codigo = '$codigo'
    ";
        if (!mysql_query($sql))
            die("Erro: " . mysql_error());
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->DESTINO = "tipo_contagem.php";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_EDITADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
}

include "rodape.php";
?>
