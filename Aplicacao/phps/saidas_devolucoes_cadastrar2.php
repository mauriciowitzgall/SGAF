<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "saidas";
include "includes.php";

print_r("$_REQUEST");


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "VENDAS DEVOLUÇÕES";
$tpl_titulo->SUBTITULO = "CADASTRAR UMA NOVA DEVOLUÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "devolucoes.png";
$tpl_titulo->show();


exit;

//Pegar todos os dados digitados


//Gravar o registro da nova devolução


//Inserir no estoque os itens devolvidos


//Gerar saída de caixa


//Mostrar notificação informando que foi inserido no estoque o produto e o dinheiro saiu do caixa. E perguntar se o cliente deseja gerar nota fiscal caso use o módulo fiscal.





//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "taxas.php";


//Se a operação for cadastro então
if ($operacao == 'cadastrar') {
    //Verifica se já existe uma taxa com mesmo nome    
    $sql = "SELECT tax_nome FROM taxas WHERE tax_nome='$taxanome'";
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
            taxas (
                tax_nome,
                tax_descricao,
                tax_cooperativa,
                tax_quiosque,
                tax_tiponegociacao
            )
        VALUES (
            '$taxanome',
            '$descricao',
            '$usuario_cooperativa',
            '$quiosque',
            '$tiponegociacao'
        )";
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

//Se a operação for edição então
if ($operacao == 'editar') {
    //Verifica se já existe registro com o mesmo nome
    $sql = "SELECT tax_nome FROM taxas WHERE tax_codigo='$codigo'";
    $query = mysql_query($sql);
    $dados = mysql_fetch_assoc($query);
    $nome_banco = $dados["tax_nome"];
    if (strtolower($taxanome) != strtolower($nome_banco)) {
        $sql2 = "SELECT tax_nome FROM taxas WHERE tax_nome='$taxanome'";
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
        taxas
    SET            
        tax_nome='$taxanome',
        tax_descricao='$descricao',
        tax_tiponegociacao='$tiponegociacao',
        tax_quiosque='$quiosque'
    WHERE
        tax_codigo='$codigo'
    ";
    if (!mysql_query($sql))
        die("Erro: " . mysql_error());
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}
?>

