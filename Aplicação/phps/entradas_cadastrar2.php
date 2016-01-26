<?php

require "login_verifica.php";
if ($permissao_entradas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "entradas";
include "includes.php";

$cancelar = $_GET["cancelar"];
$salvar = $_GET["salvar"];
$entrada = $_GET["entrada"];
$operacao = $_GET["operacao"];

$erro = 0;

//Caso a operação seja SALVAR então apenas trocar o status da entrada para ATIVO
//e inserir produtos no estoque
if ($salvar == 1) {

    //Troca o status da entrada para ativo
    $sql = "UPDATE entradas SET ent_status='1' WHERE ent_codigo='$entrada'";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro de SQL5: " . mysql_error());


    //Calcular o total da entrada
    $sql = "SELECT round(sum(entpro_valtot),2) as total FROM entradas_produtos WHERE entpro_entrada=$entrada";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro de SQL1: " . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $total = $dados["total"];


    //Atualizar entrada colocando o valor total
    $sql2 = "UPDATE entradas SET ent_valortotal=$total WHERE ent_codigo=$entrada";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro de SQL4: " . mysql_error());

    
    //Adiciona os produtos no estoque        
    //Pega todos os produtos e seus dados da entrada em questão
    $sql13 = "
    SELECT
        ent_fornecedor,entpro_produto,entpro_quantidade,entpro_valorunitario,entpro_validade,entpro_status,entpro_numero
    FROM
        entradas_produtos
        join entradas on (ent_codigo=entpro_entrada) 
    WHERE
        ent_codigo=$entrada
    ";
    $query13 = mysql_query($sql13);
    if (!$query13)
        die("Erro de SQL 2:" . mysql_error());
    while ($dados13 = mysql_fetch_array($query13)) {
        $produto2 = $dados13['entpro_produto'];
        $fornecedor2 = $dados13['ent_fornecedor'];
        $qtd2 = $dados13['entpro_quantidade'];
        $valuni = $dados13['entpro_valorunitario'];
        $validade = $dados13['entpro_validade'];
        $status = $dados13['entpro_status'];
        $item_numero = $dados13['entpro_numero'];

        if ($status == '0') {

            //Verifica se o produto já existe no estoque, se sim insere, senão apenas incrementa
            $sql5 = "
            SELECT 
                *
            FROM 
                estoque
            WHERE 
                etq_quiosque=$usuario_quiosque and 
                etq_produto=$produto2 and 
                etq_fornecedor=$fornecedor2 and 
                etq_lote=$entrada
            ";
            $query5 = mysql_query($sql5);
            if (!$query5)
                die("Erro de SQL 5:" . mysql_error());
            $linhas5 = mysql_num_rows($query5);
            if ($linhas5 == 0) {

                //Interir no no estoque
                $sql16 = "
                INSERT INTO 
                    estoque (
                        etq_quiosque,
                        etq_produto,
                        etq_fornecedor,
                        etq_lote,
                        etq_quantidade,
                        etq_valorunitario,
                        etq_validade
                    )
                VALUES (
                    '$usuario_quiosque',
                    '$produto2',
                    '$fornecedor2',
                    '$entrada',
                    '$qtd2',
                    '$valuni',
                    '$validade'
                )";
                $query16 = mysql_query($sql16);
                if (!$query16)
                    die("Erro de SQL 3:" . mysql_error());
            } else {
                //Atualiza no estoque
                $sql8 = "
                UPDATE
                    estoque 
                SET 
                    etq_quantidade=(etq_quantidade+$qtd2)
                WHERE
                    etq_quiosque=$usuario_quiosque and
                    etq_produto=$produto2 and
                    etq_lote=$entrada 
                ";
                $query8 = mysql_query($sql8);
                if (!$query8)
                    die("Erro de SQL 8:" . mysql_error());
            }

            //Muda o status para 1, ou seja, já foi incrementado no estoque 
            $sql9 = "
            UPDATE
                entradas_produtos 
            SET 
                entpro_status=1
            WHERE
                entpro_entrada= $entrada and
                entpro_numero=$item_numero
            ";
            $query9 = mysql_query($sql9);
            if (!$query9)
                die("Erro de SQL 9:" . mysql_error());
        }
    }
}

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PESSOAS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "pessoas.png";
$tpl_titulo->show();

//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "entradas.php";
$tpl_notificacao->block("BLOCK_CONFIRMAR");
if ($operacao == 2)
    $tpl_notificacao->block("BLOCK_EDITADO");
else 
    $tpl_notificacao->block("BLOCK_CADASTRADO");

$tpl_notificacao->PERGUNTA="Deseja imprimir o comprovante de entrada?";
$tpl_notificacao->block("BLOCK_PERGUNTA");

$tpl_notificacao->BOTAOGERAL_DESTINO="entradas_imprimir.php?codigo=$entrada";
$tpl_notificacao->BOTAOGERAL_TIPO="button";
$tpl_notificacao->BOTAOGERAL_NOME="IMPRIMIR";
$tpl_notificacao->block("BLOCK_BOTAOGERAL_NOVAJANELA");
$tpl_notificacao->block("BLOCK_BOTAOGERAL");

$tpl_notificacao->BOTAOGERAL_DESTINO="entradas.php";
$tpl_notificacao->BOTAOGERAL_TIPO="button";
$tpl_notificacao->BOTAOGERAL_NOME="CONTINUAR";
$tpl_notificacao->block("BLOCK_BOTAOGERAL_AUTOFOCO");
$tpl_notificacao->block("BLOCK_BOTAOGERAL");


$tpl_notificacao->show();



include "rodape.php";
?>

