<?php

require "login_verifica.php";
if ($permissao_entradas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "entradas";
include "includes.php";


//Pega todos os dados do formulário
$cancelar = $_GET["cancelar"];
$salvar = $_GET["salvar"];
$entrada = $_GET["entrada"];
$operacao = $_GET["operacao"];




    //Avalia se é necessário realizar alguma retirada de subrprodutos do estoque
    //Procura todos os lotes que tem no estoque dos subprodutos
    $sql="
        SELECT entpro_produto, prosub_subproduto, etq_lote, etq_quantidade
        FROM entradas_produtos 
        JOIN produtos_subproduto  on entpro_produto=prosub_produto
        JOIN estoque on prosub_subproduto=etq_produto
        WHERE entpro_entrada=$entrada
    ";
    if (!$query = mysql_query($sql)) die("Erro de SQL subproduto 1:" . mysql_error());
    while ($dados=  mysql_fetch_assoc($query)) {
        $produto=$dados["entpro_produto"];
        $subproduto=$dados["prosub_subproduto"];
        $lote=$dados["etq_lote"];
        $qtdemestoque=$dados["etq_quantidade"];
        
        
        $qtddigitada_nome="qtddigitada_".$produto."_"."$subproduto"."_"."$lote";
        $qtddigitada=$_POST["$qtddigitada_nome"];
        $qtddigitada = str_replace('.', '', $qtddigitada);
        $qtddigitada = str_replace(',', '.', $qtddigitada);
        if ($qtddigitada) { //Se a quantidade digitada for nula significa que ele não selecionou este lote.
            
            //Inserir na tabela de entradas_subprodutos o lotes selecionados
            $sql2="
                INSERT INTO entradas_subprodutos (
                    entsub_entrada,
                    entsub_produto,
                    entsub_subproduto,
                    entsub_lote,
                    entsub_quantidade
                ) VALUES (
                    $entrada,
                    $produto,
                    $subproduto,
                    $lote,
                    $qtddigitada
                )
            ";
            if (!$query2 = mysql_query($sql2)) die("Erro de SQL subproduto 23:" . mysql_error());

            
            //Retirar do estoque os subprodutos
            $sql3="
                UPDATE estoque 
                SET etq_quantidade=etq_quantidade-$qtddigitada 
                    WHERE etq_produto=$subproduto 
                    AND etq_lote=$lote 
                    AND etq_quiosque=$usuario_quiosque
            ";
            if (!$query3 = mysql_query($sql3)) die("Erro de SQL subproduto 23:" . mysql_error());
            
            //Verifica a quantida atual do estoque
            $sql4 = "SELECT etq_quantidade FROM estoque WHERE etq_quiosque=$usuario_quiosque and etq_produto=$subproduto and etq_lote=$lote";
            if (!$query4 = mysql_query($sql4)) { die("Erro de SQL7:" . mysql_error()); }
            $dados4 = mysql_fetch_assoc($query4);
            $qtdatual = $dados4["etq_quantidade"];
            if ($qtdatual==0) { //Se a quantidade ficou zerada no estoque então pagar o item
                $sql5="DELETE FROM estoque WHERE etq_quiosque=$usuario_quiosque and etq_produto=$subproduto and etq_lote=$lote";
                if (!$query5 = mysql_query($sql5)) { die("Erro de SQL5:" . mysql_error()); }
            }
           
        }
        
        
    }


$erro = 0;

//print_r($_REQUEST);



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

            //Verifica se o produto já existe no estoque, se sim insere, sendo apenas incrementa
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
    
    //aaa
    
    
}

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ENTRADAS";
$tpl_titulo->SUBTITULO = "RETIRAR DO ESTOQUE";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "entradas.png";
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

