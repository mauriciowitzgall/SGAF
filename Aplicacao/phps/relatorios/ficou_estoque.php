<?php

//Verifica se o usuário tem permissão para acessar este conteúdo


require "../login_verifica.php";
if ($permissao_acertos_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "acertos";
include "../includes2.php";

$fornecedor = $_GET["codigo"];



//--------------------------FICOU NO ESTOQUE -------------------------------------
$tpl3 = new Template("templates/lista1.html");
$tpl2->TABELA_BORDA="1";
//TÃ­tulo
$tpl3_tit = new Template("templates/tituloemlinha_1.html");
$tpl3_tit->LISTA_TITULO = "FICOU NO ESTOQUE";
$tpl3_tit->block("BLOCK_TITULO");
$tpl3_tit->show();

//Ficou no estoque - Listagem - Cabeçalho   
$tpl3->CABECALHO_COLUNA_TAMANHO = "";
$tpl3->CABECALHO_COLUNA_COLSPAN = "2";
$tpl3->CABECALHO_COLUNA_NOME = "PRODUTO";
$tpl3->block(BLOCK_LISTA_CABECALHO);
$tpl3->CABECALHO_COLUNA_TAMANHO = "150px";
$tpl3->CABECALHO_COLUNA_COLSPAN = "2";
$tpl3->CABECALHO_COLUNA_NOME = "QUANTIDADE";
$tpl3->block(BLOCK_LISTA_CABECALHO);
$tpl3->CABECALHO_COLUNA_TAMANHO = "150px";
$tpl3->CABECALHO_COLUNA_COLSPAN = "";
$tpl3->CABECALHO_COLUNA_NOME = "VAL. UNI.";
$tpl3->block(BLOCK_LISTA_CABECALHO);
$tpl3->CABECALHO_COLUNA_TAMANHO = "150px";
$tpl3->CABECALHO_COLUNA_COLSPAN = "";
$tpl3->CABECALHO_COLUNA_NOME = "VALOR TOTAL";
$tpl3->block(BLOCK_LISTA_CABECALHO);
$tpl3->CABECALHO_COLUNA_TAMANHO = "";
$tpl3->CABECALHO_COLUNA_COLSPAN = "";
$tpl3->CABECALHO_COLUNA_NOME = "LOTE";
$tpl3->block(BLOCK_LISTA_CABECALHO);
$tpl3->CABECALHO_COLUNA_TAMANHO = "";
$tpl3->CABECALHO_COLUNA_COLSPAN = "";
$tpl3->CABECALHO_COLUNA_NOME = "DIAS P/ VENCIMENTO";
$tpl3->block(BLOCK_LISTA_CABECALHO);

//Ficou no estoque - Listagem - Linhas
$sql7 = "
    SELECT
        etq_produto,sum(etq_quantidade) as qtdsum, round(sum(etq_quantidade*etq_valorunitario),2) as totsum 
    FROM 
        estoque
        join produtos on (etq_produto=pro_codigo)
        join produtos_tipo on (pro_tipocontagem=protip_codigo)
    WHERE
        etq_fornecedor=$fornecedor and
        etq_quiosque=$usuario_quiosque
    GROUP BY
        pro_codigo
    ";
$query7 = mysql_query($sql7);
if (!$query7)
    die("Erro7" . mysql_error());

while ($dados7 = mysql_fetch_assoc($query7)) {
    $prod = $dados7["etq_produto"];
    $qtdsum = $dados7["qtdsum"];
    $totsum = $dados7["totsum"];

    $sql8 = "
        SELECT
            * 
        FROM 
            estoque
            join produtos on (etq_produto=pro_codigo)
            join produtos_tipo on (pro_tipocontagem=protip_codigo)
        WHERE
            etq_fornecedor=$fornecedor and
            etq_produto=$prod and
            etq_quiosque= $usuario_quiosque
        ORDER BY
            pro_nome
        ";
    $query8 = mysql_query($sql8);
    if (!$query8)
        die("Erro8" . mysql_error());

    while ($dados8 = mysql_fetch_assoc($query8)) {
        $produto = $dados8["etq_produto"];
        $produto_nome = $dados8["pro_nome"];
        $sigla = $dados8["protip_sigla"];

        $tpl3->LISTA_CLASSE = "tab_linhas2";
        $tpl3->block(BLOCK_LISTA_CLASSE);

        //Produto
        $tpl3->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl3->LISTA_COLUNA_CLASSE = "";
        $tpl3->LISTA_COLUNA_COLSPAN = "";
        $tpl3->LISTA_COLUNA_VALOR = $dados8["pro_codigo"];
        $tpl3->block("BLOCK_LISTA_COLUNA");
        $tpl3->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl3->LISTA_COLUNA_CLASSE = "";
        $tpl3->LISTA_COLUNA_COLSPAN = "";
        $tpl3->LISTA_COLUNA_VALOR = $dados8["pro_nome"];
        $tpl3->block("BLOCK_LISTA_COLUNA");

        //Quantidade
        $tpl3->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl3->LISTA_COLUNA_CLASSE = "";
        $tpl3->LISTA_COLUNA_COLSPAN = "";
        $qtd = $dados8["etq_quantidade"];
        $tpl3->LISTA_COLUNA_VALOR = number_format($qtd, 2, ",", ".");
        $tpl3->block("BLOCK_LISTA_COLUNA");

        //Quantidade Tipo de Contagem Sigla
        $tpl3->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl3->LISTA_COLUNA_CLASSE = "";
        $tpl3->LISTA_COLUNA_COLSPAN = "";
        $tpl3->LISTA_COLUNA_VALOR = $sigla;
        $tpl3->block("BLOCK_LISTA_COLUNA");

        //Valor Unitario
        $tpl3->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl3->LISTA_COLUNA_CLASSE = "";
        $tpl3->LISTA_COLUNA_COLSPAN = "";
        $tpl3->LISTA_COLUNA_VALOR = "R$ " . number_format($dados8["etq_valorunitario"], 2, ",", ".");
        $tpl3->block("BLOCK_LISTA_COLUNA");

        //Total
        $tpl3->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl3->LISTA_COLUNA_CLASSE = "";
        $tpl3->LISTA_COLUNA_COLSPAN = "";
        $tot = $dados8["etq_valorunitario"] * $dados8["etq_quantidade"];
        $tpl3->LISTA_COLUNA_VALOR = "R$ " . number_format($tot, 2, ",", ".");
        $tpl3->block("BLOCK_LISTA_COLUNA");

        //Lote
        $tpl3->LISTA_COLUNA_ALINHAMENTO = "center";
        $tpl3->LISTA_COLUNA_CLASSE = "";
        $tpl3->LISTA_COLUNA_COLSPAN = "";
        $tpl3->LISTA_COLUNA_VALOR = $dados8["etq_lote"];
        $tpl3->block("BLOCK_LISTA_COLUNA");

        //Validade, dias para vencer
        $tpl3->LISTA_COLUNA_ALINHAMENTO = "center";
        $tpl3->LISTA_COLUNA_CLASSE = "";
        $tpl3->LISTA_COLUNA_COLSPAN = "";
        $validade = $dados8["etq_validade"];
        if ($validade == "0000-00-00") {
            $validade_saldo = "Indefinido";
        } else {
            $dataatual = date("Y-m-d");
            $validade_saldo = diferenca_data($dataatual, $validade, 'D');
            if ($validade_saldo == 0) {
                $validade_saldo = "hoje";
            } else if ($validade_saldo == 1) {
                $validade_saldo = "amanhã";
            }
        }
        $tpl3->LISTA_COLUNA_VALOR = $validade_saldo;
        $tpl3->block("BLOCK_LISTA_COLUNA");

        $tpl3->block("BLOCK_LISTA");
    }
    //Sub-total do Produto
    $tpl3->LISTA_CLASSE = "tab_linhas2 tab_subtotal";
    $tpl3->block("BLOCK_LISTA_CLASSE");

    $tpl3->LISTA_COLUNA_ALINHAMENTO = "";
    $tpl3->LISTA_COLUNA_COLSPAN = "";
    $tpl3->LISTA_COLUNA_VALOR = "";
    $tpl3->block("BLOCK_LISTA_COLUNA");
    $tpl3->LISTA_COLUNA_ALINHAMENTO = "";
    $tpl3->LISTA_COLUNA_COLSPAN = "";
    $tpl3->LISTA_COLUNA_VALOR = "";
    $tpl3->block("BLOCK_LISTA_COLUNA");
    $tpl3->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl3->LISTA_COLUNA_COLSPAN = "";
    $tpl3->LISTA_COLUNA_VALOR = number_format($qtdsum, 2, ',', '.');
    $tpl3->block("BLOCK_LISTA_COLUNA");
    $tpl3->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl3->LISTA_COLUNA_COLSPAN = "";
    $tpl3->LISTA_COLUNA_VALOR = $sigla;
    $tpl3->block("BLOCK_LISTA_COLUNA");
    $tpl3->LISTA_COLUNA_ALINHAMENTO = "";
    $tpl3->LISTA_COLUNA_COLSPAN = "";
    $tpl3->LISTA_COLUNA_VALOR = "";
    $tpl3->block("BLOCK_LISTA_COLUNA");
    $tpl3->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl3->LISTA_COLUNA_COLSPAN = "";
    $tpl3->LISTA_COLUNA_VALOR = "R$ " . number_format($totsum, 2, ',', '.');
    $tpl3->block("BLOCK_LISTA_COLUNA");
    $tpl3->LISTA_COLUNA_ALINHAMENTO = "";
    $tpl3->LISTA_COLUNA_COLSPAN = "";
    $tpl3->LISTA_COLUNA_VALOR = "";
    $tpl3->block("BLOCK_LISTA_COLUNA");
    $tpl3->LISTA_COLUNA_ALINHAMENTO = "";
    $tpl3->LISTA_COLUNA_COLSPAN = "";
    $tpl3->LISTA_COLUNA_VALOR = "";
    $tpl3->block("BLOCK_LISTA_COLUNA");
    $tpl3->block("BLOCK_LISTA");
    $qtdsum2 = $qtdsum2 + $qtdsum;
    $totsum2 = $totsum2 + $totsum;
}

//Total Geral
$tpl3->LISTA_CLASSE = "tab_linhas2 tab_totgeral";
$tpl3->block("BLOCK_LISTA_CLASSE");

$tpl3->LISTA_COLUNA_ALINHAMENTO = "";
$tpl3->LISTA_COLUNA_COLSPAN = "2";
$tpl3->LISTA_COLUNA_VALOR = "";
$tpl3->block("BLOCK_LISTA_COLUNA");
$tpl3->LISTA_COLUNA_ALINHAMENTO = "right";
$tpl3->LISTA_COLUNA_COLSPAN = "";
$tpl3->LISTA_COLUNA_VALOR = number_format($qtdsum2, 2, ',', '.');
$tpl3->block("BLOCK_LISTA_COLUNA");
$tpl3->LISTA_COLUNA_ALINHAMENTO = "left";
$tpl3->LISTA_COLUNA_COLSPAN = "";
$tpl3->LISTA_COLUNA_VALOR = $sigla;
$tpl3->block("BLOCK_LISTA_COLUNA");
$tpl3->LISTA_COLUNA_ALINHAMENTO = "";
$tpl3->LISTA_COLUNA_COLSPAN = "";
$tpl3->LISTA_COLUNA_VALOR = "";
$tpl3->block("BLOCK_LISTA_COLUNA");
$tpl3->LISTA_COLUNA_ALINHAMENTO = "right";
$tpl3->LISTA_COLUNA_COLSPAN = "";
$tpl3->LISTA_COLUNA_VALOR = "R$ " . number_format($totsum2, 2, ',', '.');
$tpl3->block("BLOCK_LISTA_COLUNA");
$tpl3->LISTA_COLUNA_ALINHAMENTO = "";
$tpl3->LISTA_COLUNA_COLSPAN = "2";
$tpl3->LISTA_COLUNA_VALOR = "";
$tpl3->block("BLOCK_LISTA_COLUNA");
$tpl3->block("BLOCK_LISTA");

$tpl3->block("BLOCK_LISTA1");
$tpl3->show();


include "../rodape.php";

?>
