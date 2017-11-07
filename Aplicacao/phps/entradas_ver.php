<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_entradas_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "entradas";
include "includes.php";


$tpl = new Template("entradas_cadastrar.html");
$tpl->ICONES_CAMINHO = "$icones";
$tpl->SUBTITULO = "DETALHES";

//Pega todos os dados da entrada a partir do codigo
$entrada = $_GET['codigo'];
$sql = "
    SELECT * 
    FROM entradas 
    join pessoas on (ent_fornecedor=pes_codigo)
    WHERE ent_codigo=$entrada
";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL" . mysql_error());
$dados = mysql_fetch_assoc($query);
$fornecedor = $dados["ent_fornecedor"];
$supervisor = $dados["ent_supervisor"];
$tiponegociacao = $dados["ent_tiponegociacao"];
$data = $dados["ent_datacadastro"];
$hora = $dados["ent_horacadastro"];

if (($usuario_grupo == 5) && ($usuario_codigo != $fornecedor)) {
    header("Location: permissoes_semacesso.php");
}


$tpl->block("BLOCK_CABECALHO_OPERACAO");

//Codigo da Entrada
$tpl->SELECT_DESABILITADO = " disabled ";
$tpl->ENTRADA = $entrada;
$tpl->block("BLOCK_SELECT_ENTRADA");

//Tipo negociação
$sql = "
    SELECT tipneg_codigo, tipneg_nome 
    FROM tipo_negociacao
    JOIN entradas ON (ent_tiponegociacao=tipneg_codigo)
    WHERE ent_codigo=$entrada
";
$query = mysql_query($sql);
//$tpl->SELECT_DESABILITADO = " disabled ";
while ($dados = mysql_fetch_array($query)) {
    $tpl->OPTION_VALOR = $dados[0];
    $tpl->OPTION_TEXTO = $dados[1];
    if ($dados[0] == $tiponegociacao) {
        $tpl->OPTION_SELECIONADO = " SELECTED ";
    } else {
        $tpl->OPTION_SELECIONADO = "";
    }
    $tpl->block("BLOCK_OPTIONS_TIPONEGOCIACAO");
}
$tpl->block("BLOCK_SELECT_TIPONEGOCIACAO");


//Fornecedor
$sql = "SELECT pes_codigo,pes_nome FROM pessoas inner join mestre_pessoas_tipo on (pes_codigo=mespestip_pessoa) WHERE pes_codigo=$fornecedor";
$query = mysql_query($sql);
$tpl->SELECT_DESABILITADO = " disabled ";
while ($dados = mysql_fetch_array($query)) {
    $tpl->OPTION_VALOR = $dados[0];
    $tpl->OPTION_TEXTO = "$dados[1]";
    if ($dados[0] == $fornecedor) {
        $tpl->OPTION_SELECIONADO = " SELECTED ";
    } else {
        $tpl->OPTION_SELECIONADO = "";
    }
    $tpl->block("BLOCK_OPTIONS_FORNECEDOR");
}
$tpl->block("BLOCK_SELECT_FORNECEDOR");


//Supervisor
$sql = "SELECT pes_codigo,pes_nome FROM pessoas inner join mestre_pessoas_tipo on (pes_codigo=mespestip_pessoa) WHERE pes_codigo=$supervisor";
$query = mysql_query($sql);
$tpl->SELECT_DESABILITADO = " disabled ";
while ($dados = mysql_fetch_array($query)) {
    $tpl->OPTION_VALOR = $dados[0];
    $tpl->OPTION_TEXTO = "$dados[1]";
    if ($dados[0] == $supervisor) {
        $tpl->OPTION_SELECIONADO = " SELECTED ";
    } else {
        $tpl->OPTION_SELECIONADO = "";
    }
    $tpl->block("BLOCK_OPTIONS_SUPERVISOR");
}
$tpl->block("BLOCK_SUPERVISOR");

$tpl->block("BLOCK_ENTER");
$tpl->block("BLOCK_HR");


$tpl->ENTRADAS_DATA="$data";
$tpl->ENTRADAS_HORA="$hora";
$tpl->block("BLOCK_DATA_DESABILITADA");
$tpl->block("BLOCK_HORA_DESABILITADA");
$tpl->block("BLOCK_DATAHORA");



//Cabeçalho
$tpl->block("BLOCK_VENDA_VALUNI_CABECALHO");
if ($tiponegociacao==2) {
    $tpl->block("BLOCK_CUSTO_CABECALHO");
    $tpl->block("BLOCK_SUBPRODUTOS_CABECALHO");
}
$tpl->block("BLOCK_VENDA_CABECALHO");
$tpl->OPER_COLSPAN="2";


//Pega todos os dados da listagem de produtos da entrada
$sql = "
SELECT
    pro_nome, 
    protip_nome, 
    entpro_quantidade,
    pro_codigo,
    entpro_valorunitario,
    entpro_validade,
    entpro_local,
    ent_datacadastro,
    ent_horacadastro,
    entpro_numero,
    protip_sigla,
    protip_codigo,
    ent_tiponegociacao,
    entpro_valunicusto,
    entpro_temsubprodutos,
    entpro_retiradodoestoquesubprodutos,
    pro_referencia
FROM
    entradas_produtos
    join entradas on (ent_codigo=entpro_entrada) 
    join produtos on (entpro_produto=pro_codigo) 
    join produtos_tipo on (protip_codigo=pro_tipocontagem)
WHERE
    ent_codigo=$entrada
ORDER BY 
    entpro_numero DESC
";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL" . mysql_error());


while ($dados = mysql_fetch_array($query)) {
    $numero = $dados[9];
    $validade = $dados[5];
    $produto_codigo=$dados[3];
    $lote=$entrada;
    $tpl->ENTRADAS_NUMERO = $dados[9];
    $tpl->PRODUTO = $dados[3];
    $numeroreferencia=$dados[3];
    if ($dados[16]!="") $numeroreferencia.=" ($dados[16])";
    $tpl->ENTRADAS_PRODUTO = $numeroreferencia;
    $tpl->ENTRADAS_PRODUTO_NOME = $dados[0];
    $tpl->ENTRADAS_DATA = converte_data($dados[7]);
    $tpl->ENTRADAS_HORA = converte_hora($dados[8]);
    $tpl->SIGLA = $dados["protip_sigla"];
    $tipocontagem = $dados["protip_codigo"];
    if (($tipocontagem == 2)||($tipocontagem==3))
        $tpl->ENTRADAS_QTD = number_format($dados[2], 3, ',', '.');
    else
        $tpl->ENTRADAS_QTD = number_format($dados[2], 0, '', '.');
    $tpl->ENTRADAS_VALORUNI = "R$ " . number_format($dados[4], 2, ',', '.');
    if ($tiponegociacao == 2) {
        $tpl->ENTRADAS_VALORUNI_CUSTO = "R$ " . number_format($dados['entpro_valunicusto'], 2, ',', '.');
        $tpl->ENTRADAS_VALOR_TOTAL_CUSTO = "R$ " . number_format($dados['entpro_quantidade'] * $dados['entpro_valunicusto'], 2, ',', '.');
        $tpl->block("BLOCK_CUSTO");
        
        $tpl->block("BLOCK_VENDA_VALUNI");

    } else if ($tiponegociacao == 1) {
        $totalvenda = $dados[4] * $dados[2];
        $tpl->block("BLOCK_VENDA_VALUNI");
    }

    
    $tpl->ENTRADAS_VALIDADE = converte_data($validade);
    
    //Subprodutos
    if ($tiponegociacao==2) {
        
    
        $subprodutos_retirado_do_estoque=$dados["entpro_retiradodoestoquesubprodutos"];
        $temsubprodutos2=$dados["entpro_temsubprodutos"];
        if ($temsubprodutos2==1) { //mostra icone
            $tpl->NOMEARQUIVO="subproduto.png";
            $tpl->TITULO="Este é um produto composto (possui sub-produtos)";

            if ($subprodutos_retirado_do_estoque==1) {
                $tpl->SUBPRODUTOS_NOMEICONEARQUIVO="saidas.png";
                $tpl->SUBPRODUTOS_TITULO="Os subprodutos foram retirados do estoque";
                $tpl->SUBPRODUTOS_ALINHAMENTO="right";
                $tpl->SUBPRODUTOS_NOMEICONEARQUIVO_VER="procurar.png";
                $tpl->block("BLOCK_SUBPRODUTOS");
                $tpl->block("BLOCK_SUBPRODUTOS_VER");
                $tpl->SUBPRODUTOS_COLSPAN="";
            }
            else if ($subprodutos_retirado_do_estoque==2) {
                $tpl->SUBPRODUTOS_NOMEICONEARQUIVO="saidas2.png";
                $tpl->SUBPRODUTOS_NOMEICONEARQUIVO_VER="procurar_desabilitado.png";
                $tpl->SUBPRODUTOS_TITULO="Optou-se por não realizar a retirada dos sub-produtos do estoque";
                $tpl->SUBPRODUTOS_COLSPAN="";
                $tpl->SUBPRODUTOS_ALINHAMENTO="right";
                $tpl->block("BLOCK_SUBPRODUTOS");
                $tpl->block("BLOCK_SUBPRODUTOS_VER");
            } else { //não foi deicido ainda o que ferá se vai tirar do estoque ou não
                $tpl->SUBPRODUTOS_COLSPAN="2";
                $tpl->SUBPRODUTOS_ALINHAMENTO="center";
                $tpl->SUBPRODUTOS_NOMEICONEARQUIVO="atencao.png";
                $tpl->SUBPRODUTOS_TITULO="Ainda não decidiu-se se será realizado a retirada dos sub-produtos do estoque";
                $tpl->block("BLOCK_SUBPRODUTOS");


            }
        } else { //não mostra icone
            $tpl->NOMEARQUIVO="subproduto2.png";
            $tpl->TITULO="Este é não é um produto composto.";
            $tpl->SUBPRODUTOS_COLSPAN="2";

        }
        $tpl->block("BLOCK_SUBPRODUTOS_TEM");    
        $tpl->block("BLOCK_SUBPRODUTOS_MOSTRAR");    
    }
    
    $tpl->IMPRIMIR_LINK = "entradas_etiquetas.php?lote=$entrada&numero=$numero";
    $tpl->IMPRIMIR = $icones . "etiquetas.png";

    $tpl->block("BLOCK_LISTA_OPERACAO_ETIQUETAS");
    

    $tpl->ICONES_ARQUIVO="vendas.png";
    $tpl->ICONES_CAMINHO="$icones";
    $tpl->ICONES_TITULO="Ver Vendas";
    $tpl->VERVENDAS_PRODUTO="$produto_codigo";
    $tpl->VERVENDAS_LOTE="$lote";
    $tpl->block("BLOCK_LISTA_OPERACAO_VERVENDAS");

    $tpl->block("BLOCK_LISTA_OPERACAO");
    $tpl->block("BLOCK_LISTA");
}

//RODAPÉ

if ($tiponegociacao == 2) {
    $tpl->block("BLOCK_CUSTO_RODAPE");
    $tpl->block("BLOCK_SUBPRODUTOS_RODAPE");
    $tpl->block("BLOCK_VENDA_VALUNI_RODAPE");
    
} else {
    $tpl->block("BLOCK_VENDA_VALUNI_RODAPE");
}


//Calcula o total de venda
if ($tiponegociacao == 1) {
    $sql11 = "SELECT round(sum(entpro_valtot),2) FROM entradas_produtos WHERE entpro_entrada=$entrada";
    $query11 = mysql_query($sql11);
    $dados11 = mysql_fetch_array($query11);
    $tot11 = "R$ " . number_format($dados11[0], 2, ',', '.');
    $tpl->TOTAL_VENDA = "$tot11";
    //$tpl->block("BLOCK_VENDA_RODAPE");
}

//Calcula o valor total de custo geral da entrada
$sql9 = "SELECT round(sum(entpro_valunicusto*entpro_quantidade),2) FROM entradas_produtos WHERE entpro_entrada=$entrada";
$query9 = mysql_query($sql9);
while ($dados9 = mysql_fetch_array($query9)) {
    $tot9 = "R$ " . number_format($dados9[0], 2, ',', '.');
}
$tpl->TOTAL_CUSTO = "$tot9";


//Calcula o valor total de lucro da entrada
/*
 * $sql10 = "SELECT round(sum((entpro_valorunitario*entpro_quantidade)-(entpro_valunicusto*entpro_quantidade)),2) FROM entradas_produtos WHERE entpro_entrada=$entrada";
  $query10 = mysql_query($sql10);
  while ($dados10 = mysql_fetch_array($query10)) {
  $tot10 = "R$ " . number_format($dados10[0], 2, ',', '.');
  }
  $tpl->TOTAL_LUCRO = "$tot10";
 */

//Rodapé imprimir etiqueta
$tpl->block("BLOCK_LISTA_NADA_OPERACAO");
//Rodapé ver vendas
$tpl->block("BLOCK_LISTA_NADA_OPERACAO");



$tpl->block("BLOCK_BOTAO_VOLTAR");
$tpl->block("BLOCK_BOTAO_IMPRIMIR");
/* if ($tiponegociacao == 2)
  $tpl->block("BLOCK_BOTAO_IMPRIMIR_CUSTO"); */
$tpl->block("BLOCK_PASSO3");




$tpl->show();
include "rodape.php";
?>
