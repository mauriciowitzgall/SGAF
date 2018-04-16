<?php

require "login_verifica.php";
if ($permissao_saidas_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "saidas";
include "includes.php";

$saida = $_GET["codigo"];


//Por padrão não pode excluir, deve fazer algumas validações
$excluir = 0;


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "SAIDAS";
$tpl_titulo->SUBTITULO = "DELETAR/APAGAR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "saidas.png";
$tpl_titulo->show();

//Inicio da exclus�o das saidas
$tpl = new Template("templates/notificacao.html");
$tpl->ICONES = $icones;
$tiposaida = $_GET["tiposaida"];
if ($tiposaida == 3)
    $tpl->DESTINO = "saidas_devolucao.php";
else
    $tpl->DESTINO = "saidas.php";


//Se for um caixa só pode deletar as vendas que ele fez 
if ($usuario_grupo == 4) {
    //Verifica se a saida que está sendo deletada é dele
    $sql = "SELECT sai_codigo FROM saidas JOIN caixas_operacoes on (sai_caixaoperacaonumero=caiopo_numero) WHERE caiopo_operador=$usuario_codigo and sai_codigo=$saida";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro de SQL (1):" . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas == 0) { //Se for 0 é porque não é dele
        $tpl6 = new Template("templates/notificacao.html");
        $tpl6->block("BLOCK_ERRO");
        $tpl6->ICONES = $icones;
        $tpl6->block("BLOCK_NAOAPAGADO");
        $tpl6->MOTIVO = "Você não pode deletar uma venda que não tenha sido feita por você!";
        $tpl6->block("BLOCK_MOTIVO");
        $tpl6->block("BLOCK_BOTAO_VOLTAR");
        $tpl6->show();
        $saidausuariopermissao = 0;
        exit;
    } else {
        $saidausuariopermissao = 1;
    }
} else if (($usuario_grupo == 1) || ($usuario_grupo == 3)) {
    $saidausuariopermissao = 1;
} else {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    $tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para excluir Saídas!";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $saidausuariopermissao = 0;
    exit;
}

//Verificar se foi emitido nota e se possui devolucoes,  se sim então não permitir a eliminação da venda
$sql="SELECT sai_nfe,nfe_numero FROM saidas LEFT JOIN nfe on (sai_nfe=nfe_codigo) WHERE sai_codigo=$saida";
if (!$query = mysql_query($sql)) die("<br>Erro SQL saida consulta: ".mysql_error());
$dados=mysql_fetch_assoc($query);
$nfe_da_venda=$dados["sai_nfe"];
$nfe_numero=$dados["nfe_numero"];
if ($nfe_da_venda!="") $temnota=1; else $temnota=0;

if ($temnota==1) {
    $temnota=1;
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    $tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não pode apagar uma venda/saída que possui nota fiscal gerada!";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

//Verifica se há devoluções
$sql="SELECT * FROM saidas_devolucoes WHERE saidev_saida=$saida";
if (!$query = mysql_query($sql)) die("Erro BOTÃO ELIMINAR VENDA 2: (((" . mysql_error().")))");
$linhas = mysql_num_rows($query);
if ($linhas>0) {
    $temdevolucao=1; 
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    $tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não pode apagar uma venda/saída que possui devoluções. <br>Apague estas primeiro antes de excluir a venda!";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;    
} else { 
    $temdevolucao=0;
}


if(($temnota==0)&&($saidausuariopermissao==1)&&($temdevolucao==0)) { 
    $excluir=1;
}


if ($excluir = 1) { //Devolver para o estoque, e excluir da saida
    //Carrega informações dos produtos da Saída
    $sql2 = "SELECT * FROM `saidas_produtos` WHERE saipro_saida=$saida";
    $query2 = mysql_query($sql2);
    if (!$query2) {
        die("Erro de SQL (1):" . mysql_error());
    } while ($dados2 = mysql_fetch_assoc($query2)) {
        $produto = $dados2["saipro_produto"];
        $lote = $dados2["saipro_lote"];
        $qtd = $dados2["saipro_quantidade"];
        $acertado = $dados2["saipro_acertado"];
        if ($acertado != "0") {
            $tpl->block("BLOCK_ERRO");
            $tpl->block("BLOCK_NAOAPAGADO");
            $tpl->MOTIVO = "Este Saída possui produtos que já foram acertados com o fornecedor!";
            $tpl->block("BLOCK_MOTIVO");
            $tpl->block("BLOCK_BOTAO");
            $tpl->show();
            exit;
        }

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
            die("Erro de SQL(2):" . mysql_error());
        }
        $linhas9 = mysql_num_rows($query9);
        if ($linhas9 > 0) { //O produto existe no estoque
            //Atualiza a quantidade no estoque
            $sql_repor = "
        UPDATE
            estoque 
        SET 
            etq_quantidade=(etq_quantidade+$qtd)
        WHERE
            etq_quiosque=$usuario_quiosque and
            etq_produto=$produto and
            etq_lote=$lote 
        ";
            $query_repor = mysql_query($sql_repor);
            if (!$query_repor) {
                die("Erro de SQL(3):" . mysql_error());
            }
        } else { //O produto n�o existe mais no estoque, vamos inserir
            //Pegar os demais dados necess�rios para inserir no estoque
            $sql = "SELECT * FROM `entradas_produtos` join entradas on (entpro_entrada=ent_codigo) WHERE entpro_entrada=$lote";
            $query = mysql_query($sql);
            if (!$query) {
                die("Erro de SQL(4):" . mysql_error());
            }
            while ($dados = mysql_fetch_assoc($query)) {
                $validade = $dados["entpro_validade"];
                $valuni = $dados["entpro_valorunitario"];
                $fornecedor = $dados["ent_fornecedor"];
            }
            //Interir o produto no estoque
            $sql16 = "INSERT INTO estoque (etq_quiosque,etq_produto,etq_fornecedor,etq_lote,etq_quantidade,etq_valorunitario,etq_validade)
			VALUES ('$usuario_quiosque','$produto','$fornecedor','$lote','$qtd','$valuni','$validade')";
            $query16 = mysql_query($sql16);
            if (!$query16) {
                die("Erro de SQL (inserir no estoque): " . mysql_error());
            }
        }

        //Elimina o produto da Saída
        $sql_del = "DELETE FROM saidas_produtos WHERE saipro_saida=$saida and saipro_produto=$produto";
        $query_del = mysql_query($sql_del);
        if (!$query_del) {
            die("Erro de SQL(5):" . mysql_error());
        }
    }

    //Eliminar a saida
    $sql = "DELETE FROM saidas WHERE sai_codigo=$saida";
    $query = mysql_query($sql);
    if (!$query) {
        die("Erro de SQL(6):" . mysql_error());
    }
}


$tpl->block("BLOCK_CONFIRMAR");
$tpl->block("BLOCK_APAGADO");
$tpl->block("BLOCK_BOTAO");
$tpl->show();

include "rodape.php";
?>
