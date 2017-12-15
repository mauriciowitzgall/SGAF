<script language="JavaScript" src="saidas_devolucoes_cadastrar.js"></script>
<?php

$tipopagina = "saidas";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

$saida=$_GET["codigo"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "VENDAS DEVOLUÇÕES";
$tpl_titulo->SUBTITULO = "CADASTRAR UMA NOVA DEVOLUÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "devolucoes.png";
$tpl_titulo->show();


$saida=$_GET["saida"];


$tpl = new Template("templates/listagem_2.html");
$tpl->LINK_FILTRO = "saidas_devolucoes_cadastrar2.php?saida=$saida";

//Pega dados da venda para popular os campos de filtro desabilitados
$sql="SELECT * FROM saidas LEFT JOIN pessoas on sai_consumidor = pes_codigo WHERE sai_codigo=$saida";
if (!$query=mysql_query($sql)) die("Erro SQL Filtros que mostram dados da saída: " . mysql_error());
$dados=mysql_fetch_assoc($query);
$consumidor_nome=$dados["pes_nome"];
$datavenda=$dados["sai_datacadastro"];
$horavenda=$dados["sai_horacadastro"];
$descper=$dados["sai_descontopercentual"];

//Saída / Venda
$tpl->CAMPO_TITULO = "Saída / Venda";
$tpl->CAMPO_NOME = "cabecalho_saida";
$tpl->CAMPO_VALOR = $saida;
$tpl->CAMPO_TAMANHO = "9";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Campo Filtro Data da Venda
$tpl->CAMPO_TITULO = "Data da Venda";
$tpl->CAMPO_VALOR = converte_data($datavenda)." ".substr($horavenda,0,5);
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Campo Filtro Consumidor Nome
$tpl->CAMPO_TITULO = "Consumidor";
$tpl->CAMPO_NOME = "consumidor";
if ($consumidor_nome=="") $consumidor_nome="Cliente Geral";
$tpl->CAMPO_VALOR = $consumidor_nome;
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Campo Filtro Desconto
$tpl->CAMPO_TITULO = "Desconto";
$tpl->CAMPO_NOME = "descper";
$tpl->CAMPO_VALOR = str_replace(".", ",", $descper)."%";
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

$tpl->block("BLOCK_FILTRO");

//Item
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="ITEM DA VENDA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Produto
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="PRODUTO";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Lote
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="LOTE";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Quantidade Venda
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="QTD ORIG. VENDA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Quantidade Devolvida
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="QTD JÁ DEVOLVIDA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Quantidade a devolver
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="QTD Á DEVOLVER";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor Unitário
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="VALOR UNIT.";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor Total
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="VALOR TOT.";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor Total com Desconto
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="VAL. TOT. COM DESC.";
$tpl->block("BLOCK_LISTA_CABECALHO");



//Mostra todos os itens da venda
$sql3="
    SELECT *
    FROM saidas_produtos
    JOIN produtos on pro_codigo=saipro_produto
    JOIN produtos_tipo on (pro_tipocontagem=protip_codigo) 
    WHERE saipro_saida=$saida
    ORDER BY saipro_codigo
";

$query3 = mysql_query($sql3);
if (!$query3) die("Erro3:" . mysql_error());
$cont=0;
while ($dados3=  mysql_fetch_assoc($query3)) {
    $produto_nome=$dados3["pro_nome"];
    $itemvenda=$dados3["saipro_codigo"];
    $qtdvenda=$dados3["saipro_quantidade"];
    $produto_tipocontagem_sigla=$dados3["protip_sigla"];
    $valuni=$dados3["saipro_valorunitario"];
    $lote=$dados3["saipro_lote"];
    $descper=$dados3["sai_descontopercentual"];
    

    /*if ($qtd_emestoque==0) {
        $tpl->TR_CLASSE="lin tabelalinhafundovermelho negrito";
    } else {
        $tpl->TR_CLASSE="";
    } */


    //Item Venda
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_COLSPAN="";
    $tpl->LISTA_COLUNA_ROWSPAN="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="20px";
    $tpl->LISTA_COLUNA_VALOR= "$itemvenda ";
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Produto
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_COLSPAN="";
    $tpl->LISTA_COLUNA_ROWSPAN="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$produto_nome";
    $tpl->block("BLOCK_LISTA_COLUNA");
    //Produto
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_COLSPAN="";
    $tpl->LISTA_COLUNA_ROWSPAN="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$lote";
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Quantidade Original da Venda
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_COLSPAN="";
    $tpl->LISTA_COLUNA_ROWSPAN="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$qtdvenda";
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Quantidade Já Devolvida
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_COLSPAN="";
    $tpl->LISTA_COLUNA_ROWSPAN="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $sql2="SELECT sum(saidevpro_qtddevolvida) FROM saidas_devolucoes_produtos WHERE saidevpro_saida=$saida AND saidevpro_itemsaida=$itemvenda";
    if (!$query2 = mysql_query($sql2)) die("Erro2:" . mysql_error());
    $dados=mysql_fetch_array($query2);
    $qtddevolvida=$dados[0];
    if ($qtddevolvida=="") $qtddevolvida=0;
    $tpl->LISTA_COLUNA_VALOR= "$qtddevolvida";
    $tpl->block("BLOCK_LISTA_COLUNA");


    //Quantidade Digitada
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    $tpl->LISTA_COLUNA_COLSPAN="";
    $tpl->LISTA_COLUNA_ROWSPAN="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="95px";
    $nome="qtddigitada_"."$itemvenda";
    $id=$nome;
    $qtdlimite=$qtdvenda - $qtddevolvida;
    if ($qtdlimite==0) $desabilita=" disabled "; else $desabilita="";
    $nome_valuni="valuni_"."$itemvenda";
    $nome_valtot="valtot_"."$itemvenda";
    $nome_valtot_comdesconto="valtot_comdesconto_"."$itemvenda";
    $tpl->LISTA_COLUNA_VALOR= "<input type='number' pattern='[0-9]+$' min='0' max='$qtdlimite' name='$nome' id='$id' $desabilita class='campopadrao' style='width:70px' onblur='verifica_qtd_digitada(this, $itemvenda)' > / $qtdlimite $produto_tipocontagem_sigla";
    $tpl->block("BLOCK_LISTA_COLUNA");


    //Val Uni.
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_COLSPAN="";
    $tpl->LISTA_COLUNA_ROWSPAN="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "<span name='$nome_valuni' id='$nome_valuni'> R$ " . number_format($valuni, 2, ',', '.')."</span>";
    $tpl->block("BLOCK_LISTA_COLUNA");


    //Val Tot.
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_COLSPAN="";
    $tpl->LISTA_COLUNA_ROWSPAN="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "<span name='$nome_valtot' id='$nome_valtot'> - </span>";
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Val Tot. com Desconto
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_COLSPAN="";
    $tpl->LISTA_COLUNA_ROWSPAN="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "<span name='$nome_valtot_comdesconto' id='$nome_valtot_comdesconto'> - </span>";
    $tpl->block("BLOCK_LISTA_COLUNA");
    

    $tpl->block("BLOCK_LISTA"); 

}
//1
$tpl->RODAPE_COLUNA_TAMANHO="";
$tpl->RODAPE_COLUNA_COLSPAN="";
$tpl->RODAPE_COLUNA_ROWSPAN="";
$tpl->RODAPE_COLUNA_ALINHAMENTO="";
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->RODAPE_SPAN_NOME="";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");       
//2
$tpl->RODAPE_COLUNA_TAMANHO="";
$tpl->RODAPE_COLUNA_COLSPAN="";
$tpl->RODAPE_COLUNA_ROWSPAN="";
$tpl->RODAPE_COLUNA_ALINHAMENTO="";
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->RODAPE_SPAN_NOME="";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");       
//3
$tpl->RODAPE_COLUNA_TAMANHO="";
$tpl->RODAPE_COLUNA_COLSPAN="";
$tpl->RODAPE_COLUNA_ROWSPAN="";
$tpl->RODAPE_COLUNA_ALINHAMENTO="";
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->RODAPE_SPAN_NOME="";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");       
//4
$tpl->RODAPE_COLUNA_TAMANHO="";
$tpl->RODAPE_COLUNA_COLSPAN="";
$tpl->RODAPE_COLUNA_ROWSPAN="";
$tpl->RODAPE_COLUNA_ALINHAMENTO="";
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->RODAPE_SPAN_NOME="";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");       
//5
$tpl->RODAPE_COLUNA_TAMANHO="";
$tpl->RODAPE_COLUNA_COLSPAN="";
$tpl->RODAPE_COLUNA_ROWSPAN="";
$tpl->RODAPE_COLUNA_ALINHAMENTO="";
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->RODAPE_SPAN_NOME="";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");       
//6
$tpl->RODAPE_COLUNA_TAMANHO="";
$tpl->RODAPE_COLUNA_COLSPAN="";
$tpl->RODAPE_COLUNA_ROWSPAN="";
$tpl->RODAPE_COLUNA_ALINHAMENTO="";
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->RODAPE_SPAN_NOME="";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");  
//7
$tpl->RODAPE_COLUNA_TAMANHO="";
$tpl->RODAPE_COLUNA_COLSPAN="";
$tpl->RODAPE_COLUNA_ROWSPAN="";
$tpl->RODAPE_COLUNA_ALINHAMENTO="";
$tpl->RODAPE_COLUNA_NOME=" ";
$tpl->RODAPE_SPAN_NOME="";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");       
//8
$tpl->RODAPE_COLUNA_TAMANHO="";
$tpl->RODAPE_COLUNA_COLSPAN="";
$tpl->RODAPE_COLUNA_ROWSPAN="";
$tpl->RODAPE_COLUNA_ALINHAMENTO="right";
$tpl->RODAPE_COLUNA_NOME=" - ";
$tpl->RODAPE_SPAN_NOME="valtot";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");     
//9
$tpl->RODAPE_COLUNA_TAMANHO="";
$tpl->RODAPE_COLUNA_COLSPAN="";
$tpl->RODAPE_COLUNA_ROWSPAN="";
$tpl->RODAPE_COLUNA_ALINHAMENTO="right";
$tpl->RODAPE_COLUNA_NOME=" - ";
$tpl->RODAPE_SPAN_NOME="valtot_comdesconto";
$tpl->block("BLOCK_RODAPE_CONTEUDO");
$tpl->block("BLOCK_RODAPE_COLUNA");       



$tpl->block("BLOCK_RODAPE_LINHA"); 
$tpl->block("BLOCK_RODAPE");


//botão oculto valor total da devolucao
$tpl->CAMPOOCULTO_NOME="valorvendazero";
$tpl->CAMPOOCULTO_VALOR="$valorvendazero";
$tpl->block("BLOCK_CAMPOSOCULTOS");
$tpl->CAMPOOCULTO_NOME="campooculto_valtot";
$tpl->CAMPOOCULTO_VALOR="";
$tpl->block("BLOCK_CAMPOSOCULTOS");
//botão oculto valor total da devolucao
$tpl->CAMPOOCULTO_NOME="campooculto_valtot_comdesconto";
$tpl->CAMPOOCULTO_VALOR="";
$tpl->block("BLOCK_CAMPOSOCULTOS");


if (mysql_num_rows($query3) == 0) $tpl->block("BLOCK_LISTA_NADA");

    $tpl->show();

echo "<hr class='linha'>";






$tpl4 = new Template("templates/botoes1.html");
$tpl4->COLUNA_TAMANHO = "824px";
$tpl4->block("BLOCK_COLUNA");
$tpl4->block("BLOCK_BOTOES");


//Botão Cancelar
$tpl4->COLUNA_LINK_ARQUIVO = "saidas_devolucoes.php?codigo=$saida";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "100px";
$tpl4->COLUNA_ALINHAMENTO = "right";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl4->block("BLOCK_BOTAOPADRAO_CANCELAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");
$tpl4->block("BLOCK_BOTOES");


//Botão Continuar
$tpl4->COLUNA_LINK_ARQUIVO = "";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "100px";
$tpl4->COLUNA_ALINHAMENTO = "right";
$tpl4->block("BLOCK_BOTAOPADRAO_DESABILITADO");
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl4->block("BLOCK_BOTAOPADRAO_CONTINUAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");
$tpl4->block("BLOCK_LINHA");
$tpl4->block("BLOCK_BOTOES");
$tpl4->block("BLOCK_FECHARFORM");


$tpl4->show();










?>