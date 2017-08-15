<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "devolucoes";
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "VENDAS DEVOLUÇÕES";
$tpl_titulo->SUBTITULO = "CADASTRAR UMA NOVA DEVOLUÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "devolucoes.png";
$tpl_titulo->show();

$datahoraatual=date("Y-m-d H:i:s");

//Pegar todos os dados digitados
//print_r($_REQUEST);
$saida=$_GET["saida"];
$valtot=$_REQUEST["campooculto_valtot"];
    

//Gravar o registro da nova devolução
//Inserindo o registro da devolução
$sql="
    INSERT INTO saidas_devolucoes (
        saidev_saida,
        saidev_usuario,
        saidev_valtot
    ) VALUES (
        $saida,
        $usuario_codigo,
        $valtot
    )
";

if (!$query = mysql_query($sql)) die("Erro ao inserir devolucão nova:" . mysql_error());

$devolucao=mysql_insert_id();
//Inserir o registro dos produtos da devolução. 
//Para isso basta varrer a saida tooda, e quando encontrar um item que deve ser devolvido realizar a inseção deste. 
$sql2="SELECT * FROM saidas_produtos WHERE saipro_saida=$saida";
//echo "<br><br>gerado NOVA DEVOLUCAO <br><br>";
if (!$query2 = mysql_query($sql2)) die("Erro so CONSULTAR PRODUTOS DAS SAIDAS" . mysql_error());
while ($dados2=mysql_fetch_assoc($query2)) {
    $itemvenda=$dados2["saipro_codigo"];
    $nome="qtddigitada_".$itemvenda;
    $qtddigitada=$_POST["$nome"];
    $produto=$dados2["saipro_produto"];
    $lote=$dados2["saipro_lote"];
    $valuniitem=$dados2["saipro_valorunitario"];
    $valtotitem=$valuniitem*$qtddigitada;

    if ($qtddigitada>0) {
        //Inserir este item na devolução
        $sql3="
            INSERT INTO saidas_devolucoes_produtos (
                saidevpro_numerodev,
                saidevpro_saida,
                saidevpro_itemsaida,
                saidevpro_produto,
                saidevpro_lote,
                saidevpro_qtddevolvida,
                saidevpro_valuni,
                saidevpro_valtot
            ) VALUES (
                $devolucao,
                $saida,
                $itemvenda,
                $produto,
                $lote,
                $qtddigitada,
                $valuniitem,
                $valtotitem
            )
        ";
        if (!$query3 = mysql_query($sql3)) die("Erro ao itens da devolucao:" . mysql_error());
        //echo "<br><br>inserido <b>ITEM NOVO na DEVOLUCAO</b> <br><br>";


        //Inserir no estoque o item devolvidos
        //Verifica se o produto existe no estoque ou se foi eliminado por ter valor 0
        $sql9 = "
        SELECT
            *
        FROM
            estoque
        WHERE
            etq_quiosque=$usuario_quiosque and
            etq_produto=$produto and
            etq_lote=$lote
        ";
        $query9 = mysql_query($sql9);
        if (!$query9) {
            die("Erro de SQL 1:" . mysql_error());
        }
        $linhas9 = mysql_num_rows($query9);
        if ($linhas9 > 0) { //O produto existe no estoque
            //Atualiza a quantidade no estoque            
            $sql_repor = "
            UPDATE
                estoque 
            SET 
                etq_quantidade=(etq_quantidade+$qtddigitada)
            WHERE
                etq_quiosque=$usuario_quiosque and
                etq_produto=$produto and
                etq_lote=$lote 
            ";
            $query_repor = mysql_query($sql_repor);
            if (!$query_repor) {
                die("Erro de SQL2:" . mysql_error());
            }





           // echo "<br><br>ATUALIZADO ESTOQUE DE PRODUTO EXISTENTE NO ESTOQUE<br><br>";

        } else { //O produto não existe mais no estoque, vamos inserir
            //Pegar os demais dados necessários para inserir no estoque
            $sql = "SELECT * FROM `entradas_produtos` join entradas on (entpro_entrada=ent_codigo) WHERE entpro_entrada=$lote AND entpro_produto=$produto";
            $query = mysql_query($sql);
            if (!$query) {
                die("Erro de SQL 9:" . mysql_error());
            }
            while ($dados = mysql_fetch_assoc($query)) {
                $validade = $dados["entpro_validade"];
                $valuni = $dados["entpro_valorunitario"];
                $fornecedor = $dados["ent_fornecedor"];
            }
            //Interir o produto no estoque
            //echo "<br><br>inseriu no estoque<br><br>";
            $sql16 = "INSERT INTO estoque (etq_quiosque,etq_produto,etq_fornecedor,etq_lote,etq_quantidade,etq_valorunitario,etq_validade)
            VALUES ('$usuario_quiosque','$produto','$fornecedor','$lote','$qtddigitada','$valuni','$validade')";
            $query16 = mysql_query($sql16);
            if (!$query16) {
                die("Erro de SQL4 (inserir no estoque): " . mysql_error());
            }
            //echo "<br><br>INSERIDO NO ESTOQUE COMO NOVO PRODUTO<br><br>";

        }


    } 
}



        

//Gerar saída de caixa
//Verifica qual é o numero da operação de caixa do cliente logado
$sql="
    SELECT cai_codigo,cai_nome,caiopo_numero
    FROM pessoas 
    JOIN caixas_operacoes on (caiopo_numero=pes_caixaoperacaonumero) 
    JOIN caixas on (caiopo_caixa=cai_codigo)
    WHERE pes_codigo=$usuario_codigo
";
$query = mysql_query($sql);
if (!$query) die("Erro de SQL Cabeçalho:" . mysql_error());
$dados=mysql_fetch_array($query);
$usuario_caixa=$dados[0];
$usuario_caixa_nome=$dados[1];
$usuario_caixa_operacao=$dados[2];
$caixaoperacao=$dados[2];


$sql8 = "
INSERT INTO 
    caixas_entradassaidas (
        caientsai_tipo,
        caientsai_valor,
        caientsai_datacadastro,
        caientsai_descricao,
        caientsai_usuarioquecadastrou,
        caientsai_numerooperacao
    )
VALUES (
    '2',
    '$valtot',
    '$datahoraatual',
    'Gerado automaticamente a partir da devolução numero: $devolucao, saida: $saida',  
    '$usuario_codigo',    
    '$caixaoperacao'    
)";
if (!$query8= mysql_query($sql8)) die("Erro de SQL:" . mysql_error());
$ultimo=  mysql_insert_id();




//Mostrar notificação informando que foi inserido no estoque o produto e o dinheiro saiu do caixa. E perguntar se o cliente deseja gerar nota fiscal caso use o módulo fiscal.
$tpl = new Template("templates/notificacao.html");
$tpl->ICONES = $icones;
//$tpl->MOTIVO_COMPLEMENTO = "";
$tpl->block("BLOCK_CONFIRMAR");
$tpl->LINK = "saidas_devolucoes.php?codigo=$saida";
$tpl->MOTIVO = "
    <br>Devolução registrada com sucesso! <br><br>
    Os itens devolvidos foram <b>adicionados ao estoque</b> novamente<br>
    Foi <b>gerado uma saída de caixa</b> no valor total devolvido.<br>
";
$tpl->block("BLOCK_MOTIVO");
$tpl->PERGUNTA = "Deseja gerar nota fiscal?";
//$tpl->block("BLOCK_PERGUNTA");
//$tpl->NAO_LINK = "saidas.php";
//$tpl->block("BLOCK_BOTAO_NAO_LINK");
//$tpl->block("BLOCK_BOTAO_SIMNAO");
$tpl->DESTINO="saidas_devolucoes.php?codigo=$saida";
$tpl->block("BLOCK_BOTAO");
$tpl->show();


?>

