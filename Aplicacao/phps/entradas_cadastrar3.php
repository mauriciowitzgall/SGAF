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
$retirar_subprodutos = $_GET["retirar_subprodutos"];


//print_r($_REQUEST);

//Avalia se é necessário realizar alguma retirada de subrprodutos do estoque

//Pega todos os itens da lista de produtos da entrada que possuem subprodutos. 
$sql9="
    SELECT DISTINCT entpro_numero
    FROM  entradas_produtos
    JOIN produtos_subproduto on prosub_produto=entpro_produto
    WHERE entpro_retiradodoestoquesubprodutos=0 
    and entpro_entrada=$entrada
";
if (!$query9 = mysql_query($sql9)) die("Erro de SQL subproduto 1:" . mysql_error());
while ($dados9=  mysql_fetch_assoc($query9)) { //Para cada item da entrada gerar uma retirada de estoque
    $item=$dados9["entpro_numero"];

    //Procura todos os lotes que tem no estoque dos subprodutos
    $sql="
        SELECT entpro_produto, prosub_subproduto, etq_lote, etq_quantidade
        FROM entradas_produtos 
        JOIN produtos_subproduto  on entpro_produto=prosub_produto
        JOIN estoque on prosub_subproduto=etq_produto
        WHERE entpro_entrada=$entrada and entpro_numero=$item and entpro_status=0
    ";
    if (!$query = mysql_query($sql)) die("Erro de SQL subproduto 1:" . mysql_error());

    while ($dados=  mysql_fetch_assoc($query)) {
        $produto=$dados["entpro_produto"];
        $subproduto=$dados["prosub_subproduto"];
        $lote=$dados["etq_lote"];
        $qtdemestoque=$dados["etq_quantidade"];


        $qtddigitada_nome="qtddigitada_"."$item"."_".$produto."_"."$subproduto"."_"."$lote";
        $qtddigitada=$_POST["$qtddigitada_nome"];
        $qtddigitada = str_replace('.', '', $qtddigitada);
        $qtddigitada = str_replace(',', '.', $qtddigitada);
        if ($qtddigitada) { //Se a quantidade digitada tiver algum valor significa que o usuario selecionou um lote
            
            
            
            //Verifica se já existe nas entradas_subprodutos o item que será inserido. Se sim é porque o usuário deu refresh na tela
            $sql2v="
                SELECT * 
                FROM entradas_subprodutos 
                WHERE entsub_entrada=$entrada
                AND entsub_item=$item
                AND entsub_produto=$produto
                AND entsub_subproduto=$subproduto
                AND entsub_lote=$lote
            ";
            if (!$query2v = mysql_query($sql2v)) die("Erro de SQL subproduto 21:" . mysql_error());
            $linhas2v=mysql_num_rows($query2v);
            if ($linhas2v > 0) {
                echo "Você está tentando fazer uma retirada de um subproduto do estoque de um item que já foi retirado. Não atualize a tela do navegador, isso pode gerar muitas duplicações no sistema e consequentemente diferenças no estoque.";
                exit;
            }         

            //Inserir na tabela de entradas_subprodutos o lotes selecionados
            $sql2="
                INSERT INTO entradas_subprodutos (
                    entsub_entrada,
                    entsub_item,
                    entsub_produto,
                    entsub_subproduto,
                    entsub_lote,
                    entsub_quantidade
                ) VALUES (
                    $entrada,
                    $item,
                    $produto,
                    $subproduto,
                    $lote,
                    $qtddigitada
                )
            ";
            if (!$query2 = mysql_query($sql2)) die("Erro de SQL subproduto 24:" . mysql_error());
            //echo "(inserido na entrada_subprodutos: $qtddigitada_nome) <br>";

            
            //Retirar do estoque os subprodutos
            $sql3="
                UPDATE estoque 
                SET etq_quantidade=etq_quantidade-$qtddigitada 
                    WHERE etq_produto=$subproduto 
                    AND etq_lote=$lote 
            ";
            if (!$query3 = mysql_query($sql3)) die("Erro de SQL subproduto 25:" . mysql_error());

            //Verifica a quantida atual do estoque, se for zero exlui o item do estoque
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
    //Atualiza item da entrada informando que já foi retirado do estoque os subprodutos
    $sql33="
        UPDATE entradas_produtos
        SET entpro_retiradodoestoquesubprodutos=$retirar_subprodutos
            WHERE entpro_entrada=$entrada
            AND entpro_numero=$item
    ";
    if (!$query33 = mysql_query($sql33)) die("Erro de SQL subproduto 33:" . mysql_error());
}






//$erro = 0;

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

