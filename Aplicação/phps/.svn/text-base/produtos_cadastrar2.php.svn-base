<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

include "includes.php";

$codigo = $_GET['codigo'];
$nome = ucwords(strtolower($_POST['nome']));
$nome2 = ucwords(strtolower($_POST['nome2']));
$tipo = $_POST['tipo'];
$categoria = $_POST['categoria'];
$descricao = $_POST['descricao'];
$data = date("Y/m/d");
$hora = date("h:i:s");

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PRODUTOS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos.png";
$tpl_titulo->show();



if ($codigo == "") { //caso seja um cadastro novo fazer isso
    //Verifica se tem produtos com o mesmo nome
    $sql = "SELECT * FROM produtos WHERE pro_nome='$nome' and pro_cooperativa=$usuario_cooperativa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro1: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
                $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome";
        $tpl_notificacao->DESTINO = "produtos.php";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOEDITADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
    } else {
        $sql = "INSERT INTO produtos (pro_nome,pro_tipocontagem,pro_categoria,pro_descricao,pro_datacriacao,pro_horacriacao,pro_cooperativa)
	VALUES ('$nome','$tipo','$categoria','$descricao','$data','$hora',$usuario_cooperativa);";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro2: " . mysql_error());
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->DESTINO = "produtos.php";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_CADASTRADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
} else { //Caso seja uma alteração de um registro fazer isso
    //Verifica se já existe registros com o mesmo nome    
    $sql = "SELECT * FROM produtos WHERE pro_nome='$nome' and pro_cooperativa=$usuario_cooperativa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro3: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($nome == $nome2)
        $linhatot = 1;
    else
        $linhatot = 0;
    if ($linhas > $linhatot) {
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome";
        $tpl_notificacao->DESTINO = "produto.php";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOEDITADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
    } else {
        $sql = "UPDATE produtos SET 
	pro_nome='$nome',
	pro_tipocontagem='$tipo',
	pro_categoria='$categoria',
	pro_descricao='$descricao',
	pro_dataedicao='$data',
	pro_horaedicao='$hora',
	pro_cooperativa='$usuario_cooperativa'
	WHERE pro_codigo = '$codigo'
    ";
        if (!mysql_query($sql))
            die("Erro: " . mysql_error());
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->DESTINO = "produtos.php";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_EDITADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
}
$paginadestino = "produtos.php";


include "rodape.php";
?>

