<?php

require "login_verifica.php";
if ($permissao_saidas_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "saidas";
include "includes.php";

$devolucao = $_GET["devolucao"];
$saida = $_GET["saida"];


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



$excluir=0;


//Se Foi emitido nota fiscal não pode excluir a devolucão
//Verificar se foi emitido nota
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



//Todas validações fetas, PODE Excluir
if ($excluir = 1) { //Devolver para o estoque, e excluir da saida
    

    //Realizar a retirada do estoque dos itens devolvidos
    //Consulta todos os itens da devolução para fazer a remoção do estoque um por um
     $sql2="SELECT * FROM saidas_devolucoes_produtos WHERE saidevpro_numerodev=$devolucao";
    if (!$query2 = mysql_query($sql2)) die("Erro 3" . mysql_error());
    while ($dados2=mysql_fetch_assoc($query2)) {
        $itemvenda=$dados2["saidevpro_itemsaida"];
        $qtd=$dados2["saidevpro_qtddevolvida"];
        $produto=$dados2["saidevpro_produto"];
        $lote=$dados2["saidevpro_lote"];
        $valuni=$dados2["saidevpro_valuni"];
        $valtot=$dados2["saidevpro_valtot"];
        $itemdev=$dados2["saidevpro_itemdev"];


        
        //Atualiza a quantidade no estoque            
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
            if (!$query5 = mysql_query($sql5)) die("Erro de SQL5:" . mysql_error()); 
            //echo "<br>Removeu do estoque por possuir quantidade ZERO<br>";           
        }


        //Elimina o item da devolução que já foi removido do estoque
        $sql_del = "DELETE FROM saidas_devolucoes_produtos WHERE saidevpro_itemdev = $itemdev AND saidevpro_numerodev=$devolucao";
        if (!$query_del = mysql_query($sql_del)) die("Erro de SQL5:" . mysql_error());
        //echo "<br>Removeu ITEM da devolucao<br>";
        
    }    

    //Eliminar o registro da devolução
    $sql_del = "DELETE FROM saidas_devolucoes WHERE saidev_numero = $devolucao";
    if (!$query_del = mysql_query($sql_del)) die("Erro de SQL5:" . mysql_error());
    //echo "<br>Removeu a devolução<br>";      
    
    
}


$tpl->block("BLOCK_CONFIRMAR");
$tpl->block("BLOCK_APAGADO");
$tpl->block("BLOCK_BOTAO");
$tpl->show();

include "rodape.php";
?>
