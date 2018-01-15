<?php

require "login_verifica.php";
if ($permissao_saidas_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "saidas";
include "includes2.php";

$devolucao = $_GET["devolucao"];
$saida = $_GET["saida"];
$recebido = $_GET["recebido"];
$datahoraatual=date("Y-m-d H:i:s");


//Por padrão não pode excluir, deve fazer algumas validações
$excluir = 0;


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "DEVOLUÇÕES";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "devolucoes.png";
$tpl_titulo->show();


//Inicio da exclusão das saidas
$tpl = new Template("templates/notificacao.html");
$tpl->ICONES = $icones;
$tiposaida = $_GET["tiposaida"];
$tpl->DESTINO = "saidas_devolucoes.php?codigo=$saida";

$excluir=1;

//Verifica se usa devoluções
if ($usadevolucoes!=1) {
    $excluir=0;
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    $tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Seu estabelecimento não está configurado para usar DEVOLUÇÕES SOBRE VENDAS.";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();   
    exit; 
}


//Se Foi emitido nota fiscal não pode excluir a devolucão
//Verificar se foi emitido nota
if ($usamodulofiscal==1) {
    $sql3="SELECT * FROM nfe_vendas WHERE nfe_numero=$saida";
    if (!$query3 = mysql_query($sql3)) die("Erro remover devolucao: (((" . mysql_error().")))");
    $linhas3 = mysql_num_rows($query3);
    if ($linhas3==0) $temnota=0; else  $temnota=1;
    if ($temnota==1) {
        $excluir=0;
        $tpl6 = new Template("templates/notificacao.html");
        $tpl6->block("BLOCK_ERRO");
        $tpl6->ICONES = $icones;
        $tpl6->block("BLOCK_NAOAPAGADO");
        $tpl6->MOTIVO = "Você não pode excluir devoluções que foi emitido nota fiscal!";
        $tpl6->block("BLOCK_MOTIVO");
        $tpl6->block("BLOCK_BOTAO_VOLTAR");
        $tpl6->show();   
        exit; 
    }
}

//Ao tentar excluir uma devolução deve-se receber de volta do cliente o dinheiro pago a ele (caso tenha sido pago). 
//Calcula o valor que deve ser recebido do cliete (valor este que foi definido quando a devolução foi realizada).
$sql="SELECT * FROM saidas_devolucoes JOIN saidas on (sai_codigo=saidev_saida) WHERE saidev_numero=$devolucao";
if (!$query = mysql_query($sql)) die("Erro SQL 11: " . mysql_error());
$dados=mysql_fetch_assoc($query);
$devolucao_liquido=$dados["saidev_valliq"];
$saida_liquido=$dados["sai_totalliquido"];
$devolucao_pagamento=$dados["saidev_pagamento"];
if ($usapagamentosparciais==1) {
    if ($devolucao_pagamento>0)  $filtro_compagamento="AND saipag_codigo not in ($devolucao_pagamento)";
    $sql="SELECT sum(saipag_valor) as 'totalpago' FROM saidas_pagamentos WHERE saipag_saida=$saida $filtro_compagamento";
    if (!$query = mysql_query($sql)) die("Erro SQL 13: " . mysql_error());
    $dados=mysql_fetch_assoc($query);
    $totalpago=$dados["totalpago"];
} else {
    $totalpago=0;
}
$saldo=$devolucao_liquido - ($saida_liquido-$totalpago);
$saldo_mostra= "R$ " . number_format($saldo, 2, ',', '.');
if (($recebido!=1)&&($saldo>0)) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->ICONES = $icones;
    $tpl6->block("BLOCK_CONFIRMAR");
    //$tpl6->block("BLOCK_CADASTRADO");    
    $tpl6->MOTIVO = "Para pode excluir esta devolução você deve receber de volta do cliente o valor pago a ele quando foi registrado esta devolução!<br> Se você não pagou nada ao cliente ";
    $tpl6->LINK = "saidas_devolucoes_deletar.php?devolucao=$devolucao&recebido=1&saida=$saida";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->PERGUNTA = "Você recebeu de volta o valor de: <br><span class='fonte5'>$saldo_mostra</span>";
    $tpl6->block("BLOCK_PERGUNTA");
    $tpl6->NAO_LINK = "saidas_devolucoes.php?codigo=$saida";
    $tpl6->LINK_TARGET = "";
    $tpl6->block("BLOCK_BOTAO_NAO_LINK");
    $tpl6->block("BLOCK_BOTAO_SIMNAO");
    $tpl6->show();
    $excluir=0;
    exit;

} 


//Todas validações feitas, PODE Excluir
if ($excluir = 1) { //Devolver para o estoque, e excluir da saida

    //Realizar a retirada do estoque dos itens devolvidos
    //Consulta todos os itens da devolução para fazer a remoção do estoque um por um
    $sql2="SELECT * FROM saidas_devolucoes_produtos JOIN saidas_devolucoes on (saidevpro_numerodev=saidev_numero) WHERE saidevpro_numerodev=$devolucao";
    if (!$query2 = mysql_query($sql2)) die("Erro 3" . mysql_error());
    while ($dados2=mysql_fetch_assoc($query2)) {
        $itemvenda=$dados2["saidevpro_itemsaida"];
        $qtd=$dados2["saidevpro_qtddevolvida"];
        $produto=$dados2["saidevpro_produto"];
        $lote=$dados2["saidevpro_lote"];
        $valuni=$dados2["saidevpro_valuni"];
        $valtot=$dados2["saidevpro_valtot"];
        $itemdev=$dados2["saidevpro_itemdev"];
        $pagamento_abatido=$dados2["saidev_pagamento"];

        
        //Atualiza a quantidade no estoque            
        if ($usaestoque==1) {
            $qtd = str_replace('.', '', $qtd);
            $qtd = str_replace(',', '.', $qtd);
            $sql_repor = "
            UPDATE
                estoque 
            SET 
                etq_quantidade=(etq_quantidade-$qtd)
            WHERE
                etq_quiosque=$usuario_quiosque and
                etq_produto=$produto and
                etq_lote=$lote 
            ";
            if (!$query_repor = mysql_query($sql_repor)) die("Erro de SQL2:" . mysql_error());
            //echo "<br>Atualizou o estoque<br>";
        
        
            //Verifica a quantida atual do estoque, se for zero exlui o item do estoque
            $sql4 = "SELECT etq_quantidade FROM estoque WHERE etq_quiosque=$usuario_quiosque and etq_produto=$produto and etq_lote=$lote";
            if (!$query4 = mysql_query($sql4)) { die("Erro de SQL7:" . mysql_error()); }
            $dados4 = mysql_fetch_assoc($query4);
            $qtdatual = $dados4["etq_quantidade"];
            if ($qtdatual==0) { //Se a quantidade ficou zerada no estoque então pagar o item
                $sql5="DELETE FROM estoque WHERE etq_quiosque=$usuario_quiosque and etq_produto=$produto and etq_lote=$lote";
                if (!$query5 = mysql_query($sql5)) die("Erro de SQL6:" . mysql_error()); 
                //echo "<br>Removeu do estoque por possuir quantidade ZERO<br>";           
            }

        }

        //Elimina o item da devolução que já foi removido do estoque
        $sql_del = "DELETE FROM saidas_devolucoes_produtos WHERE saidevpro_itemdev = $itemdev AND saidevpro_numerodev=$devolucao";
        if (!$query_del = mysql_query($sql_del)) die("Erro de SQL7:" . mysql_error());
        //echo "<br>Removeu ITEM da devolucao<br>";
        
    } 


    //Se o cliente devolveu o valor dado a ele durante o registro dessa devolução então deve-se gerar uma entrada de caixa e não apagar a saida de caixa anterior devido a isso poder acontecer em outros dias com operacoes de caixa diferente.
    //Pega o nuemro da ultima operação de caixa do caixa do usuario
    if ($usacaixa==1) {


        $sql="SELECT max(caiopo_numero) FROM caixas_operacoes WHERE caiopo_caixa=$usuario_caixa";
        if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
        $dados=mysql_fetch_array($query);
        $caixaoperacao=$dados[0];

        //Gerar saída de caixa
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
            '1',
            '$valorsaidadecaixa',
            '$datahoraatual',
            'Gerado automaticamente a partir da EXCLUSÃO da uma DEVOLUÇÃO nº $devolucao da venda nº $saida',  
            '$usuario_codigo',    
            '$caixaoperacao'
                
        )";
        if (!$query8= mysql_query($sql8)) die("Erro de SQL22:" . mysql_error());
    }



    //Se houver pagamentos automaticos efetuados para abatimento de alguma devolução, então excluí-los. Mas antes deve-ser gerar uma saida de caixa relaciona a este pagamento que será excluído.
    if ($pagamento_abatido>0) {
        
        if ($usacaixa==1) {


            $sql="SELECT * FROM caixas_entradassaidas WHERE caientsai_saidapagamento=$pagamento_abatido";
            if (!$query = mysql_query($sql)) die("Erro SQL 9: " . mysql_error());
            //$linhas=mysql_num_rows($query);
            //if ($linhas>0) $tementradadecaixa=1; else $tementradadecaixa=0;
            $dados=mysql_fetch_assoc($query);
            $valorentradacaixa=$dados["caientsai_valor"];
            //$valorentradadecaixa_mostra= "R$ " . number_format($valorentradacaixa, 2, ',', '.');



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
                    '$valorentradacaixa',
                    '$datahoraatual',
                    'Gerado automaticamente a partir da EXCLUSÃO de um PAGAMENTO gerado pela DEVOLUÇÃO nº $devolucao da venda nº $saida',  
                    '$usuario_codigo',    
                    '$caixaoperacao'
            )";
            if (!$query8= mysql_query($sql8)) die("Erro de SQL21:" . mysql_error());       
        }

        //Apagar o Pagamento.
        if ($usapagamentosparciais==1) {
            $sql_del = "DELETE FROM saidas_pagamentos WHERE saipag_codigo = $pagamento_abatido";
            if (!$query_del = mysql_query($sql_del)) die("Erro de SQL9:" . mysql_error());
        }

    }



    //Eliminar o registro da devolução
    $sql_del = "DELETE FROM saidas_devolucoes WHERE saidev_numero = $devolucao";
    if (!$query_del = mysql_query($sql_del)) die("Erro de SQL8:" . mysql_error());
    
    
}


$tpl->block("BLOCK_CONFIRMAR");
$tpl->block("BLOCK_APAGADO");
$tpl->block("BLOCK_BOTAO");
$tpl->show();

include "rodape.php";
?>
