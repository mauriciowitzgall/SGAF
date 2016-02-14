<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_estoque_qtdide_definir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "estoque";
include "includes.php";


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ESTOQUE";
$tpl_titulo->SUBTITULO = "DEFINIR QUANTIDADE MÍNIMA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "estoque.png";
$tpl_titulo->show();

$produto = $_POST['produto'];
$qtdide = $_POST['qtdide'];
$qtdide = str_replace('.', '', $qtdide);
$qtdide = str_replace(',', '.', $qtdide);



//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "estoque_qtdideal.php";

//Verifica se a o produto possui uma quantidade ideal definida
$sql = "
    SELECT qtdide_produto
    FROM quantidade_ideal
    WHERE qtdide_produto=$produto
    and qtdide_quiosque=$usuario_quiosque
";
$query = mysql_query($sql);
if (!$query)
    die("Erro no campo produto" . mysql_error());
$linhas = mysql_num_rows($query);

//Verifica se a o valor do campo qtdideal é zero ou maior
if ($qtdide == 0) {
    if ($linhas > 0) {
        //Remover o produto da tabela de estoque ideal
        $sql = "
            DELETE FROM quantidade_ideal 
            WHERE qtdide_quiosque= $usuario_quiosque
            and qtdide_produto=$produto
        ";
        if (!mysql_query($sql))
            die("Erro: " . mysql_error());
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_EDITADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    } else {
        //Não faz nada
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_EDITADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();        
    }
} else {
    if ($linhas > 0) {
        //Atualizar quantidade ideal
        $sql = "
            UPDATE quantidade_ideal
            SET qtdide_quantidade='$qtdide'
            WHERE qtdide_quiosque='$usuario_quiosque'
            and qtdide_produto='$produto'
        ";
        if (!mysql_query($sql))
            die("Erro: " . mysql_error());
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_EDITADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    } else {
        //Inserir produto no estoque do quiosque
        $sql2 = "
            INSERT INTO quantidade_ideal (
                qtdide_quiosque,
                qtdide_produto,
                qtdide_quantidade
            ) VALUES (
                '$usuario_quiosque',
                '$produto',
                '$qtdide'
            )
        ";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro de SQL:" . mysql_error());
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_CADASTRADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }
}
?>

