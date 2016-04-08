<?php

require "login_verifica.php";
if ($permissao_entradas_cancelar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "entradas";
include "includes.php";

$entrada = $_GET["codigo"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ENTRADAS";
$tpl_titulo->SUBTITULO = "CANCELAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "entradas.png";
$tpl_titulo->show();


$tpl = new Template("templates/notificacao.html");
$tpl->ICONES = $icones;
$tpl->DESTINO = "entradas.php";

//Deleta os itens do produto do estoque
$sql2 = "DELETE FROM estoque WHERE etq_lote=$entrada";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro SQL3: " . mysql_error());

//Incluir novamente os subprodutos no estoque

//Verifica todos os sub-produtos da entrada que foram retirados do estoqu
$sql5="
    SELECT *
    FROM entradas_subprodutos 
    JOIN entradas_produtos on (entpro_entrada=entsub_entrada and entpro_numero=entsub_item)
    JOIN entradas on (entpro_entrada=ent_codigo)
    WHERE entsub_entrada=$entrada
";
if (!$query5 = mysql_query($sql5)) die("Erro SQL 5: " . mysql_error());
while ($dados5=  mysql_fetch_assoc($query5)) {
    
    $produto=$dados5["entpro_produto"];
    $subproduto=$dados5["entsub_subproduto"];
    $item=$dados5["entsub_item"];
    $lote=$dados5["entsub_lote"];
    $fornecedor=$dados5["ent_fornecedor"];
    $qtd=$dados5["entsub_quantidade"];
    $valuni=$dados5["entpro_valunicusto"];
    $validade=$dados5["entpro_validade"];
    
    
    //Verifica se o subproduto já existe no estoque, se sim incrementa, senão faz novo registro no estoque
    $sql6 = "
        SELECT *
        FROM estoque
        WHERE etq_produto=$subproduto
        and etq_lote=$lote
    ";
    if (!$query6 = mysql_query($sql6)) die("Erro SQL 6: " . mysql_error());
    $linhas6 = mysql_num_rows($query6);
    if ($linhas6 == 0) { //não existe no estoque o subproduto  e o lote

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
            '$subproduto',
            '$fornecedor',
            '$lote',
            '$qtd',
            '$valuni',
            '$validade'
        )";
        $query16 = mysql_query($sql16);  if (!$query16) die("Erro de SQL 16:" . mysql_error());
    } else {
        //Atualiza no estoque
        $sql8 = "
            UPDATE estoque 
            SET etq_quantidade=(etq_quantidade+$qtd)
            WHERE etq_produto=$subproduto
            and etq_lote=$lote 
        ";
        $query8 = mysql_query($sql8);  if (!$query8) die("Erro de SQL 8:" . mysql_error());

    }
    
}





//Deleta os itens da entrada
$sql3 = "DELETE FROM entradas_produtos WHERE entpro_entrada=$entrada";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro SQL4: " . mysql_error());

//Deleta os itens dos sub-produtos da entrada
$sql7 = "DELETE FROM entradas_subprodutos WHERE entsub_entrada=$entrada";
$query7 = mysql_query($sql3);
if (!$query7)
    die("Erro SQL7: " . mysql_error());

//Deleta a entrada
$sql = "DELETE FROM entradas WHERE ent_codigo=$entrada";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL2: " . mysql_error());
$tpl->block("BLOCK_CONFIRMAR");
$tpl->LINK = "entradas.php";
$tpl->block("BLOCK_APAGADO");
$tpl->block("BLOCK_BOTAO");


$tpl->show();

include "rodape.php";
?>
