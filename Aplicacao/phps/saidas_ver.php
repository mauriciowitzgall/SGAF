<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
$tiposaida = $_GET["tiposaida"];
if ($tiposaida == 1) {
    if ($permissao_saidas_ver <> 1) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
} else {
    if ($permissao_saidas_ver_devolucao <> 1) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
}
$tipopagina = "saidas";


$ope = $_GET["ope"];
$tiposaida = $_GET["tiposaida"];
$saida = $_GET["codigo"];
$botaofechar = $_GET["botaofechar"];
if ($ope == 4) {
    include "includes2.php";
} else {
    include "includes.php";
}

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
if ($tiposaida == 1) {
    $tpl_titulo->TITULO = "VENDAS";
    $tpl_titulo->SUBTITULO = "DETALHES DA VENDA";
    $tpl_titulo->NOME_ARQUIVO_ICONE = "vendas.png";
} else {
    $tpl_titulo->TITULO = "SAÍDAS";
    $tpl_titulo->SUBTITULO = "DETALHES DO AJUSTE";
    $tpl_titulo->NOME_ARQUIVO_ICONE = "saidas.png";
}
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->show();

//Pega valores
$sql = "
SELECT 
    *
FROM
    saidas
    join saidas_tipo on (saitip_codigo=sai_tipo)
    left join saidas_motivo on (saimot_codigo=sai_saidajustificada)
    left join pessoas on (sai_consumidor=pes_codigo)
    left join cidades on (cid_codigo=pes_cidade)
    left join estados on (cid_estado=est_codigo)
    LEFT JOIN saidas_pagamentos on (sai_codigo=saipag_saida)
    left join metodos_pagamento on (sai_metpag=metpag_codigo)
WHERE       
    sai_codigo=$saida
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$dados = mysql_fetch_assoc($query);
if ($dados["sai_consumidor"] == 0)
    $consumidor_nome = "Cliente Geral";
else
    $consumidor_nome = $dados["pes_nome"];
$tipo_nome = $dados["saitip_nome"];
$descricao = $dados["sai_descricao"];
$data = $dados["sai_datacadastro"];
$hora = $dados["sai_horacadastro"];
$numero = $dados["sai_codigo"];
$status_venda=$dados["sai_status"];
$totalbruto = $dados["sai_totalbruto"];
$areceber = $dados["sai_areceber"];
$numero_comanda = $dados["sai_id"];
$metpaga_nome=$dados["metpag_nome"];
$metpaga=$dados["metpag_codigo"];
$caixaoperacao=$dados["sai_caixaoperacaonumero"];
$entrega=$dados["sai_entrega"];
$entrega_dataentrega=$dados["sai_dataentrega"];
$horaentrega=$dados["sai_horaentrega"];
$horaentrega = $horaentrega[0].$horaentrega[1].$horaentrega[2].$horaentrega[3].$horaentrega[4];
$entrega_horaentrega=$horaentrega;
$entrega_conluida=$dados["sai_entrega_concluida"];
$obs=$dados["sai_obs"];
$endereco=$dados["sai_entrega_endereco"];
$endereco_numero=$dados["sai_entrega_endereco_numero"];
$bairro=$dados["sai_entrega_bairro"];
$fone1=$dados["sai_entrega_fone1"];
$fone2=$dados["sai_entrega_fone2"];
$cidade=$dados["cid_nome"];
$estado=$dados["est_sigla"];

//DADOS GERAIS DA VENDA
$tpl1_tit = new Template("templates/tituloemlinha_1.html");
$tpl1_tit->LISTA_TITULO = "DADOS GERAIS DA VENDA";
$tpl1_tit->block("BLOCK_QUEBRA1");
$tpl1_tit->block("BLOCK_TITULO");
$tpl1_tit->show();

$tpl = new Template("templates/cadastro1.html");

//Numero
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "200px";
$tpl->TITULO = "Nº";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "numero";
$tpl->CAMPO_VALOR = "$numero";
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");


if ($tiposaida == 1) {
    
    //Numero da comanda
    if ($usacomanda==1) {
        $tpl->COLUNA_ALINHAMENTO = "right";
        $tpl->COLUNA_TAMANHO = "200px";
        $tpl->TITULO = "Nº Comanda/ID";
        $tpl->block("BLOCK_TITULO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->COLUNA_ALINHAMENTO = "left";
        $tpl->COLUNA_TAMANHO = "";
        $tpl->CAMPO_TIPO = "text";
        $tpl->CAMPO_NOME = "numero";
        $tpl->CAMPO_VALOR = "$numero_comanda";
        $tpl->block("BLOCK_CAMPO_PADRAO");
        $tpl->block("BLOCK_CAMPO_DESABILITADO");
        $tpl->block("BLOCK_CAMPO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->block("BLOCK_LINHA");
    }

    //Data e Hora
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "200px";
    $tpl->TITULO = "Data";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "date";
    $tpl->CAMPO_NOME = "data";
    $tpl->CAMPO_VALOR = "$data";
    $tpl->block("BLOCK_CAMPO_PADRAO");
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_CAMPO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "time";
    $tpl->CAMPO_NOME = "hora";
    $tpl->CAMPO_VALOR = "$hora";
    $tpl->block("BLOCK_CAMPO_PADRAO");
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_CAMPO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");

    //Consumidor
    if ($identificacaoconsumidorvenda!=3) {
        $tpl->COLUNA_ALINHAMENTO = "right";
        $tpl->COLUNA_TAMANHO = "200px";
        $tpl->TITULO = "Consumidor";
        $tpl->block("BLOCK_TITULO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->COLUNA_ALINHAMENTO = "left";
        $tpl->COLUNA_TAMANHO = "";
        $tpl->CAMPO_TIPO = "text";
        $tpl->CAMPO_NOME = "consumidor";
        $tpl->CAMPO_VALOR = "$consumidor_nome";
        $tpl->block("BLOCK_CAMPO_PADRAO");
        $tpl->block("BLOCK_CAMPO_DESABILITADO");
        $tpl->block("BLOCK_CAMPO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->block("BLOCK_LINHA");
    }

    //Numero Caixa Operação
    if ($usacaixa==1) {
        $tpl->COLUNA_ALINHAMENTO = "right";
        $tpl->COLUNA_TAMANHO = "200px";
        $tpl->TITULO = "Operação de caixa";
        $tpl->block("BLOCK_TITULO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->COLUNA_ALINHAMENTO = "left";
        $tpl->COLUNA_TAMANHO = "";
        $tpl->CAMPO_TIPO = "text";
        $tpl->CAMPO_NOME = "caixaoperacao";
        $tpl->CAMPO_VALOR = "$caixaoperacao";
        $tpl->block("BLOCK_CAMPO_PADRAO");
        $tpl->block("BLOCK_CAMPO_DESABILITADO");
        $tpl->block("BLOCK_CAMPO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->block("BLOCK_LINHA");
    }
}

if ($tiposaida == 3) {

    if ($dados["sai_saidajustificada"] != 0) {
        
        //Motivo
        $motivo = $dados["saimot_nome"];
        $tpl->COLUNA_ALINHAMENTO = "right";
        $tpl->COLUNA_TAMANHO = "200px";
        $tpl->TITULO = "Motivo";
        $tpl->block("BLOCK_TITULO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->COLUNA_ALINHAMENTO = "left";
        $tpl->COLUNA_TAMANHO = "";
        $tpl->CAMPO_TIPO = "text";
        $tpl->CAMPO_NOME = "motivo";
        $tpl->CAMPO_VALOR = "$motivo";
        $tpl->block("BLOCK_CAMPO_PADRAO");
        $tpl->block("BLOCK_CAMPO_DESABILITADO");
        $tpl->block("BLOCK_CAMPO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->block("BLOCK_LINHA");

        //Descrição ou Observação
        $tpl->COLUNA_ALINHAMENTO = "right";
        $tpl->COLUNA_TAMANHO = "200px";
        $tpl->TITULO = "Descrição";
        $tpl->block("BLOCK_TITULO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->COLUNA_ALINHAMENTO = "left";
        $tpl->COLUNA_TAMANHO = "";
        $tpl->TEXTAREA_TAMANHO = "60";
        $descricao_linhas=strlen($descricao)-strlen(str_replace("\n", "", $descricao))+1;
        $tpl->TEXTAREA_LINHAS = "$descricao_linhas";
        $tpl->TEXTAREA_NOME = "descricao";
        $tpl->TEXTAREA_TEXTO = "$descricao";
        //$tpl->block("BLOCK_TEXTAREA_PADRAO");
        $tpl->block("BLOCK_TEXTAREA_DESABILITADO");
        $tpl->block("BLOCK_TEXTAREA");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->block("BLOCK_LINHA");
    }
}
$tpl->show();



//PRODUTOS VENDIDOS
$tpl2_tit = new Template("templates/tituloemlinha_1.html");
$tpl2_tit->LISTA_TITULO = "PRODUTOS";
$tpl2_tit->block("BLOCK_QUEBRA1");
$tpl2_tit->block("BLOCK_TITULO");
$tpl2_tit->show();


$tpl2 = new Template("templates/lista1.html");
$tpl2->TABELA_BORDA="1";
$tpl2->block(BLOCK_TABELA_CHEIA);

//Cabecalho
$tpl2->CABECALHO_COLUNA_TAMANHO = "30px";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "Nº";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_NOME = "PRODUTO";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "LOTE";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_NOME = "QUANTIDADE";
$tpl2->block(BLOCK_LISTA_CABECALHO);
if ($usavendaporcoes==1) {
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "PORÇÃO";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "QTD. PORÇÃO";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
}
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "VALOR UNIT.";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "VALOR TOTAL";
$tpl2->block(BLOCK_LISTA_CABECALHO);

//Mostra todos os produtos da saida em quest�o
$sql2 = "
SELECT 
    saipro_codigo,pro_nome,pes_nome,saipro_lote,saipro_quantidade,protip_sigla,protip_codigo,saipro_valorunitario,saipro_valortotal,pro_referencia,pro_tamanho,pro_cor,pro_descricao,pro_codigo,sai_totalcomdesconto,metpag_nome,saipro_porcao_quantidade, propor_nome
FROM 
    saidas
    join saidas_produtos on (saipro_saida=sai_codigo)
    join produtos on (saipro_produto=pro_codigo)
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
    left join entradas on (saipro_lote=ent_codigo)
    left join pessoas on (ent_fornecedor=pes_codigo)
    left join metodos_pagamento on (sai_metpag=metpag_codigo)
    left join produtos_porcoes on (saipro_porcao=propor_codigo)
WHERE
    sai_codigo=$saida
";
if (!$query2 = mysql_query($sql2)) die("Erro43" . mysql_error());
$total = 0;

while ($dados2 = mysql_fetch_assoc($query2)) {
    $tpl2->LISTA_CLASSE = "tab_linhas2";
    $tpl2->block("BLOCK_LISTA_CLASSE");

    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = $dados2["saipro_codigo"];
    $tpl2->block("BLOCK_LISTA_COLUNA");

    $nome= $dados2['pro_nome'];
    $produto_codigo= $dados2['pro_codigo'];
    $referencia= $dados2['pro_referencia'];
    $tamanho= $dados2['pro_tamanho'];
    $cor= $dados2['pro_cor'];
    $descricao= $dados2['pro_descricao'];
    $nome2="$nome $tamanho $cor $descricao ";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $numeroreferencia=$produto_codigo;
    if ($referencia!="") $numeroreferencia.=" ($referencia)";

    $tpl1->LISTA_PRODUTO_REFERENCIA = $numeroreferencia;
    $tpl2->LISTA_COLUNA_VALOR = $numeroreferencia;
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = $nome2;
    $tpl2->block("BLOCK_LISTA_COLUNA");

    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    if ($dados2["saipro_lote"]==0) $tpl2->LISTA_COLUNA_VALOR = "---";
    else $tpl2->LISTA_COLUNA_VALOR = $dados2["saipro_lote"];
    $tpl2->block("BLOCK_LISTA_COLUNA");

    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_TAMANHO = "100px";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tipocontagem = $dados2["protip_codigo"];
    if (($tipocontagem == 2)||($tipocontagem==3))
        $tpl2->LISTA_COLUNA_VALOR = number_format($dados2['saipro_quantidade'], 3, ',', '.');
    else
        $tpl2->LISTA_COLUNA_VALOR = number_format($dados2['saipro_quantidade'], 0, '', '.');
    $tpl2->block("BLOCK_LISTA_COLUNA");

    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_TAMANHO = "50px";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = $dados2["protip_sigla"];
    $tpl2->block("BLOCK_LISTA_COLUNA");


    if ($usavendaporcoes==1) {
        //Nome da Porção
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "100px";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = $dados2["propor_nome"];
        $tpl2->block("BLOCK_LISTA_COLUNA");
        //Quantidade de Proção
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "50px";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = $dados2["saipro_porcao_quantidade"];
        $tpl2->block("BLOCK_LISTA_COLUNA");    
    }



    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($dados2['saipro_valorunitario'], 2, ',', '.');
    $tpl2->block("BLOCK_LISTA_COLUNA");

    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($dados2['saipro_valortotal'], 2, ',', '.');
    $tpl2->block("BLOCK_LISTA_COLUNA");

    $total = $total + $dados2['saipro_valortotal'];
    $total_comdesconto = $dados2['sai_totalcomdesconto'];
    $tpl2->block("BLOCK_LISTA");
}
//Rodap� da listagem
$tpl2->LISTA_CLASSE = "tabelarodape1";

$tpl2->LISTA_CLASSE = "tabelarodape1";
$tpl2->block("BLOCK_LISTA_CLASSE");
$tpl2->LISTA_COLUNA_VALOR = " ";
$tpl2->block("BLOCK_LISTA_COLUNA");
$tpl2->LISTA_COLUNA_VALOR = " ";
$tpl2->block("BLOCK_LISTA_COLUNA");
$tpl2->LISTA_COLUNA_VALOR = " ";
$tpl2->block("BLOCK_LISTA_COLUNA");
$tpl2->LISTA_COLUNA_VALOR = " ";
$tpl2->block("BLOCK_LISTA_COLUNA");
$tpl2->LISTA_COLUNA_VALOR = " ";
if ($usavendaporcoes==1) {
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_VALOR = " ";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_VALOR = " ";
} 
$tpl2->block("BLOCK_LISTA_COLUNA");
$tpl2->LISTA_COLUNA_VALOR = " ";
$tpl2->block("BLOCK_LISTA_COLUNA");
$tpl2->LISTA_COLUNA_VALOR = " ";
$tpl2->block("BLOCK_LISTA_COLUNA");
$tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($total, 2, ",", ".");
$tpl2->block("BLOCK_LISTA_COLUNA");
$tpl2->block("BLOCK_LISTA");

$tpl2->block("BLOCK_LISTA1");
$tpl2->show();


//DADOS FINANCEIROS
if ($tiposaida!=3) {


    $tpl3_tit = new Template("templates/tituloemlinha_1.html");
    $tpl3_tit->LISTA_TITULO = "DADOS FINANCEIROS DA VENDA";
    $tpl3_tit->block("BLOCK_QUEBRA1");
    $tpl3_tit->block("BLOCK_TITULO");
    $tpl3_tit->show();

    $tpl3 = new Template("templates/cadastro1.html");

    //Total Bruto
    //Titulo
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Valor Total";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->COLUNA_TAMANHO = "";
    //Campo
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "valortotal";

    $tpl3->CAMPO_VALOR = "R$ " . number_format($totalbruto, 2, ',', '.');
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");

    //Desconto
    //Titulo
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Desconto";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    //Campos de desconto
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "";
    //Porcentagem
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_TAMANHO = "8";
    $tpl3->CAMPO_NOME = "descontopercentual";
    $descontopercentual =  number_format($dados["sai_descontopercentual"], 2, ',', '.');
    $tpl3->CAMPO_VALOR = $descontopercentual . " % ";
    $descontopercentual=str_replace(",", "." , $descontopercentual);
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    //Dinheiro
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "valortotal";
    $tpl3->CAMPO_TAMANHO = "18";
    $descontovalor = $dados["sai_descontovalor"];
    $tpl3->CAMPO_VALOR = "R$ " . number_format($descontovalor, 2, ',', '.');
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");

    //Total com desconto
    //Titulo
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Total com Desconto";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->COLUNA_TAMANHO = "";
    //Campo
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "totalcomdesconto";
    $totalcomdesconto = $dados["sai_totalcomdesconto"];
    $tpl3->CAMPO_VALOR = "R$ " . number_format($totalcomdesconto, 2, ',', '.');
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");


    //Método de Pagamento
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Metodo Pagamento";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->COLUNA_TAMANHO = "";
    $tpl3->CAMPO_TAMANHO = " 30";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "metpag";
    if ($areceber==1) $tpl3->CAMPO_VALOR = "À receber";
    else $tpl3->CAMPO_VALOR = "$metpaga_nome";
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");




    if ($areceber != 1) {

        //Valor Recebido
        //Titulo
        $tpl3->COLUNA_ALINHAMENTO = "right";
        $tpl3->COLUNA_TAMANHO = "200px";
        $tpl3->TITULO = "Valor Recebido";
        $tpl3->block("BLOCK_TITULO");
        $tpl3->block("BLOCK_CONTEUDO");
        $tpl3->block("BLOCK_COLUNA");
        $tpl3->COLUNA_ALINHAMENTO = "";
        $tpl3->COLUNA_TAMANHO = "";
        //Campo
        $tpl3->CAMPO_TIPO = "text";
        $tpl3->CAMPO_NOME = "valorecebido";
        $valorecebido = $dados["sai_valorecebido"];
        $tpl3->CAMPO_VALOR = "R$ " . number_format($valorecebido, 2, ',', '.');
        $tpl3->block("BLOCK_CAMPO_PADRAO");
        $tpl3->block("BLOCK_CAMPO_DESABILITADO");
        $tpl3->block("BLOCK_CAMPO");
        $tpl3->block("BLOCK_CONTEUDO");
        $tpl3->block("BLOCK_COLUNA");
        $tpl3->block("BLOCK_LINHA");


        if (($metpaga==6)||($metpaga==7)) { //Dinheiro + Cartão Crédito/Débido
            //mostra os campos recebidodinheiro e recebidocartao


            //Recebido em dinheiro
            $tpl3->COLUNA_ALINHAMENTO = "right";
            $tpl3->COLUNA_TAMANHO = "200px";
            $tpl3->TITULO = "Recebido em dinheiro";
            $tpl3->block("BLOCK_TITULO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->COLUNA_ALINHAMENTO = "";
            $tpl3->COLUNA_TAMANHO = "";
            $tpl3->CAMPO_TIPO = "text";
            $tpl3->CAMPO_NOME = "recebidodinheiro";
            $recebidodinheiro = $dados["sai_recebidodinheiro"];
            $tpl3->CAMPO_VALOR = "R$ " . number_format($recebidodinheiro, 2, ',', '.');
            $tpl3->block("BLOCK_CAMPO_PADRAO");
            $tpl3->block("BLOCK_CAMPO_DESABILITADO");
            $tpl3->block("BLOCK_CAMPO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->block("BLOCK_LINHA");

            //Recebido em Cartão
            $tpl3->COLUNA_ALINHAMENTO = "right";
            $tpl3->COLUNA_TAMANHO = "200px";
            $tpl3->TITULO = "Recebido em cartão";
            $tpl3->block("BLOCK_TITULO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->COLUNA_ALINHAMENTO = "";
            $tpl3->COLUNA_TAMANHO = "";
            $tpl3->CAMPO_TIPO = "text";
            $tpl3->CAMPO_NOME = "recebidocartao";
            $recebidocartao = $dados["sai_recebidocartao"];
            $tpl3->CAMPO_VALOR = "R$ " . number_format($recebidocartao, 2, ',', '.');
            $tpl3->block("BLOCK_CAMPO_PADRAO");
            $tpl3->block("BLOCK_CAMPO_DESABILITADO");
            $tpl3->block("BLOCK_CAMPO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->block("BLOCK_LINHA");

        }

        if ($metpaga == 1) {

            //Troco
            //Titulo
            $tpl3->COLUNA_ALINHAMENTO = "right";
            $tpl3->COLUNA_TAMANHO = "200px";
            $tpl3->TITULO = "Troco";
            $tpl3->block("BLOCK_TITULO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->COLUNA_ALINHAMENTO = "";
            $tpl3->COLUNA_TAMANHO = "";
            //Campo
            $tpl3->CAMPO_TIPO = "text";
            $tpl3->CAMPO_NOME = "totalcomdesconto";
            $troco = $dados["sai_troco"];
            $tpl3->CAMPO_VALOR = "R$ " . number_format($troco, 2, ',', '.');
            $tpl3->block("BLOCK_CAMPO_PADRAO");
            $tpl3->block("BLOCK_CAMPO_DESABILITADO");
            $tpl3->block("BLOCK_CAMPO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->block("BLOCK_LINHA");

            //Troco Devolvido
            //Titulo
            $tpl3->COLUNA_ALINHAMENTO = "right";
            $tpl3->COLUNA_TAMANHO = "200px";
            $tpl3->TITULO = "Troco Devolvido";
            $tpl3->block("BLOCK_TITULO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->COLUNA_ALINHAMENTO = "";
            $tpl3->COLUNA_TAMANHO = "";
            //Campo
            $tpl3->CAMPO_TIPO = "text";
            $tpl3->CAMPO_NOME = "trocodevolvido";
            $trocodevolvido = $dados["sai_trocodevolvido"];
            $tpl3->CAMPO_VALOR = "R$ " . number_format($trocodevolvido, 2, ',', '.');
            $tpl3->block("BLOCK_CAMPO_PADRAO");
            $tpl3->block("BLOCK_CAMPO_DESABILITADO");
            $tpl3->block("BLOCK_CAMPO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->block("BLOCK_LINHA");
        }

        if (($metpaga == 1)||($metpaga==6)||($metpaga==7)) {

            //Desconto For�ado
            //Titulo
            $tpl3->COLUNA_ALINHAMENTO = "right";
            $tpl3->COLUNA_TAMANHO = "200px";
            $tpl3->TITULO = "Desconto Forçado";
            $tpl3->block("BLOCK_TITULO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->COLUNA_ALINHAMENTO = "";
            $tpl3->COLUNA_TAMANHO = "";
            //Campo
            $tpl3->CAMPO_TIPO = "text";
            $tpl3->CAMPO_NOME = "descontoforcado";
            $descontoforcado = $dados["sai_descontoforcado"];
            $tpl3->CAMPO_VALOR = "R$ " . number_format($descontoforcado, 2, ',', '.');
            $tpl3->block("BLOCK_CAMPO_PADRAO");
            $tpl3->block("BLOCK_CAMPO_DESABILITADO");
            $tpl3->block("BLOCK_CAMPO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->block("BLOCK_LINHA");

            //Acrescimo For�ado
            //Titulo
            $tpl3->COLUNA_ALINHAMENTO = "right";
            $tpl3->COLUNA_TAMANHO = "200px";
            $tpl3->TITULO = "Acréscimo Forçado";
            $tpl3->block("BLOCK_TITULO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->COLUNA_ALINHAMENTO = "";
            $tpl3->COLUNA_TAMANHO = "";
            //Campo
            $tpl3->CAMPO_TIPO = "text";
            $tpl3->CAMPO_NOME = "acrescimoforcado";
            $acrescimoforcado = $dados["sai_acrescimoforcado"];
            $tpl3->CAMPO_VALOR = "R$ " . number_format($acrescimoforcado, 2, ',', '.');
            $tpl3->block("BLOCK_CAMPO_PADRAO");
            $tpl3->block("BLOCK_CAMPO_DESABILITADO");
            $tpl3->block("BLOCK_CAMPO");
            $tpl3->block("BLOCK_CONTEUDO");
            $tpl3->block("BLOCK_COLUNA");
            $tpl3->block("BLOCK_LINHA");

        }

        //Total Liquido
        //Titulo
        $tpl3->COLUNA_ALINHAMENTO = "right";
        $tpl3->COLUNA_TAMANHO = "200px";
        $tpl3->TITULO = "Total Liquido";
        $tpl3->block("BLOCK_TITULO");
        $tpl3->block("BLOCK_CONTEUDO");
        $tpl3->block("BLOCK_COLUNA");
        $tpl3->COLUNA_ALINHAMENTO = "";
        $tpl3->COLUNA_TAMANHO = "";
        //Campo
        $tpl3->CAMPO_TIPO = "text";
        $tpl3->CAMPO_NOME = "totalliquido";
        $totalliquido = $dados["sai_totalliquido"];
        $tpl3->CAMPO_VALOR = "R$ " . number_format($totalliquido, 2, ',', '.');
        $tpl3->block("BLOCK_CAMPO_PADRAO");
        $tpl3->block("BLOCK_CAMPO_DESABILITADO");
        $tpl3->block("BLOCK_CAMPO");
        $tpl3->block("BLOCK_CONTEUDO");
        $tpl3->block("BLOCK_COLUNA");
        $tpl3->block("BLOCK_LINHA");
    }
    $tpl3->show();
}

//Se for venda A RECEBER, mostrar todas as entradas de caixa relacionada a esta venda

if (($areceber==1)&&($usapagamentosparciais==1)) {


    //PAGAMENTOS PARCIAIS 
    $tpl2_tit = new Template("templates/tituloemlinha_1.html");
    $tpl2_tit->LISTA_TITULO = "PAGAMENTOS";
    $tpl2_tit->block("BLOCK_QUEBRA1");
    $tpl2_tit->block("BLOCK_TITULO");
    $tpl2_tit->show();
    $tpl2 = new Template("templates/lista1.html");
    $tpl2->TABELA_BORDA="1";
    $tpl2->block(BLOCK_TABELA_CHEIA);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "DATA";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "MET. PAGAMENTO";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "DESCRIÇÃO";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "VALOR";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $sql2 = "
        SELECT *
        FROM saidas_pagamentos
        join saidas on (sai_codigo=saipag_saida)
        join metodos_pagamento on (saipag_metpagamento=metpag_codigo)
        WHERE sai_codigo=$saida
        ORDER BY saipag_data DESC
    ";

    if (!$query2 = mysql_query($sql2)) die("Erro48" . mysql_error());
    $linhas2=mysql_num_rows($query2);


    if ($linhas2==0) {
        $tpl2->block("BLOCK_LISTA_NADA");
    } else {
        $tempagamentos=1;

        $pag_total=0;
        while ($dados2 = mysql_fetch_assoc($query2)) {
            $tpl2->LISTA_CLASSE = "tab_linhas2";
            $tpl2->block("BLOCK_LISTA_CLASSE");

            $pag_data=$dados2["saipag_data"];
            $pag_caixaoperacao=$dados2["saipag_caixaoperacao"];
            $pag_obs=$dados2["saipag_obs"];
            $pag_valor=$dados2["saipag_valor"];
            $pag_metpag=$dados2["saipag_metpagamento"];
            $pag_metpag_nome=$dados2["metpag_nome"];
            $pag_total+=$pag_valor;

            $tpl2->LISTA_COLUNA_COLSPAN = "";

            $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl2->LISTA_COLUNA_TAMANHO = "";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tpl2->LISTA_COLUNA_VALOR = converte_datahora($pag_data);
            $tpl2->block("BLOCK_LISTA_COLUNA");

            $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl2->LISTA_COLUNA_TAMANHO = "";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tpl2->LISTA_COLUNA_VALOR = "$pag_metpag_nome";
            $tpl2->block("BLOCK_LISTA_COLUNA");

            $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl2->LISTA_COLUNA_TAMANHO = "";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tpl2->LISTA_COLUNA_VALOR = "$pag_obs";
            $tpl2->block("BLOCK_LISTA_COLUNA");

            $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
            $tpl2->LISTA_COLUNA_TAMANHO = "";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($pag_valor, 2, ",", ".");
            $tpl2->block("BLOCK_LISTA_COLUNA");

            $tpl2->block("BLOCK_LISTA");
        }
        //Rodapé
        $tpl2->LISTA_CLASSE = "tabelarodape1";
        $tpl2->block("BLOCK_LISTA_CLASSE");
        $tpl2->LISTA_COLUNA_COLSPAN = "3";
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_VALOR = " TOTAL PAGO";
        $tpl2->block("BLOCK_LISTA_COLUNA");
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($pag_total, 2, ",", ".");
        $tpl2->block("BLOCK_LISTA_COLUNA");
        $tpl2->block("BLOCK_LISTA");
    }

        $tpl2->block("BLOCK_LISTA1");
        $tpl2->show();
}


//Se houver devoluções mostrar
$sql18="
    SELECT * 
    FROM saidas_devolucoes_produtos
    JOIN saidas_devolucoes on saidevpro_numerodev=saidev_numero
    JOIN saidas on saidev_saida=sai_codigo
    JOIN produtos on saidevpro_produto=pro_codigo 
    LEFT JOIN pessoas on sai_consumidor = pes_codigo 
    WHERE saidev_saida=$saida
    ORDER BY saidev_numero DESC
    ";
if (!$query18 = mysql_query($sql18)) die("Erro CONSULTA DEVOLUCOES:" . mysql_error()."");
$linhas18=mysql_num_rows($query18);
if ($linhas18>0) $temdevolucoes=1; else $temdevolucoes=0;
if ($temdevolucoes==1) {
    $tpl2_tit = new Template("templates/tituloemlinha_1.html");
    $tpl2_tit->LISTA_TITULO = "DEVOLUÇOES";
    $tpl2_tit->block("BLOCK_QUEBRA1");
    $tpl2_tit->block("BLOCK_TITULO");
    $tpl2_tit->show();

    
    $tpl2 = new Template("templates/lista1.html");
    $tpl2->TABELA_BORDA="1";
    $tpl2->block(BLOCK_TABELA_CHEIA);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "Nº DEV.";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "DATA ";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "ITEM";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
    $tpl2->CABECALHO_COLUNA_NOME = "PRODUTO";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "LOTE";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "QTD. DEVOLVIDA";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "VAL.UNI.";
    $tpl2->block(BLOCK_LISTA_CABECALHO);
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "VAL. TOT.";
    $tpl2->block(BLOCK_LISTA_CABECALHO); 
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_NOME = "VAL. TOT. COM DESC.";
    $tpl2->block(BLOCK_LISTA_CABECALHO); 

    $total_devolvido=0;
    while ($dados18=mysql_fetch_assoc($query18)) {

        $devolucao=$dados18["saidevpro_numerodev"];
        $data= $dados18["saidev_data"];
        $item= $dados18["saidevpro_itemdev"];
        $item_venda= $dados18["saidevpro_itemsaida"];
        $produto= $dados18["pro_nome"];
        $produto_codigo= $dados18["pro_codigo"];
        $produto_referencia= $dados18["pro_referencia"];
        $lote= $dados18["saidevpro_lote"];
        $qtddevolvida= $dados18["saidevpro_qtddevolvida"];
        $valuni= $dados18["saidevpro_valuni"];
        $sql0="SELECT saipro_porcao FROM saidas_produtos WHERE saipro_saida=$saida AND saipro_codigo=$item_venda";
        if (!$query0 = mysql_query($sql0)) die("Erro 0:" . mysql_error()."");
        $dados0=mysql_fetch_assoc($query0);
        $porcao=$dados0["saipro_porcao"];
        if ($porcao>0) {
            $sql1="SELECT * FROM produtos_porcoes WHERE propor_codigo=$porcao";
            if (!$query1 = mysql_query($sql1)) die("Erro1:" . mysql_error());
            $dados1=  mysql_fetch_assoc($query1);
            $porcao_qtdref=$dados1["propor_quantidade"];
            $porcao_valuniref=$dados1["propor_valuniref"];
            $valuni=$porcao_valuniref/$porcao_qtdref;
            //echo "$porcao_qtdref / $porcao_valuniref";
        }  
        $valtot= $dados18["saidevpro_valtot"];
        $usuario= $dados18["saidev_usuario"];
        $usuario_nome= $dados18["pes_nome"];
        $valtot_comdesconto=$valtot*(100-$descontopercentual)/100;
        $total_devolvido+=$valtot_comdesconto;


        //Nº Devolução
        $tpl2->LISTA_COLUNA_COLSPAN = "";
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = "$devolucao";
        $tpl2->block("BLOCK_LISTA_COLUNA");

        //Data
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = converte_datahora($data);
        $tpl2->block("BLOCK_LISTA_COLUNA");

        //Item da Devolução
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = "$item";
        $tpl2->block("BLOCK_LISTA_COLUNA");

        //Produto
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        if ($produto_referencia=="") $codref="$produto_codigo";
        else  $codref="$produto_codigo ($produto_referencia)";
        $tpl2->LISTA_COLUNA_VALOR = "$codref";
        $tpl2->block("BLOCK_LISTA_COLUNA");
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = "$produto";
        $tpl2->block("BLOCK_LISTA_COLUNA");

        //Lote
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = "$lote";
        $tpl2->block("BLOCK_LISTA_COLUNA");

        //QTD Devolvida
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = "$qtddevolvida";
        $tpl2->block("BLOCK_LISTA_COLUNA");

        //Valor Unitário
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";       
        $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($valuni, 2, ',', '.');
        $tpl2->block("BLOCK_LISTA_COLUNA");

        //Valor Total
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($valtot, 2, ',', '.');
        $tpl2->block("BLOCK_LISTA_COLUNA");

        //Valor Total com Desconto
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        
        $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($valtot_comdesconto, 2, ',', '.');
        $tpl2->block("BLOCK_LISTA_COLUNA");




        $tpl2->block("BLOCK_LISTA");


    }
    //Rodapé
    $tpl2->LISTA_CLASSE = "tabelarodape1";
    $tpl2->block("BLOCK_LISTA_CLASSE");
    $tpl2->LISTA_COLUNA_COLSPAN = "9";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_VALOR = "TOTAL DEVOLVIDO";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($total_devolvido, 2, ",", ".");
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->block("BLOCK_LISTA");

    $tpl2->block("BLOCK_LISTA1");
    $tpl2->show();
}





//Se houver ENTREGAS mostrar
if ($entrega==1) {

    $tpl3_tit = new Template("templates/tituloemlinha_1.html");
    $tpl3_tit->LISTA_TITULO = "DETALHES DA ENTREGA";
    $tpl3_tit->block("BLOCK_QUEBRA1");
    $tpl3_tit->block("BLOCK_TITULO");
    $tpl3_tit->show();

    $tpl3 = new Template("templates/cadastro1.html");

    //Data da Entrega
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Data da entrega";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_TAMANHO = "15";
    $tpl3->CAMPO_NOME = "dataentrega";
    $tpl3->CAMPO_VALOR = converte_data($entrega_dataentrega);
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_TAMANHO = "15";
    $tpl3->CAMPO_NOME = "horaentrega";
    $tpl3->CAMPO_VALOR = $entrega_horaentrega;
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");    
    $tpl3->block("BLOCK_LINHA");

    //Situação
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Situação";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "   ";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_TAMANHO = "20";
    $tpl3->CAMPO_NOME = "entrega_situacao";
    if ($entrega_conluida==1) $tpl3->CAMPO_VALOR = "Entrega concluída";
    else $tpl3->CAMPO_VALOR = "Entrega pendente";
    $descontopercentual=str_replace(",", "." , $descontopercentual);
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");


    //Endereço
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Endereço";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->COLUNA_TAMANHO = "";
    $tpl3->CAMPO_TAMANHO = "40";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "endereco";
    $tpl3->CAMPO_VALOR = "$endereco, $endereco_numero";
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");


    //Bairro
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Bairro";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->COLUNA_TAMANHO = "";
    $tpl3->CAMPO_TAMANHO = "40";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "bairro";
    $tpl3->CAMPO_VALOR = "$bairro";
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");


    //Cidade
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Cidade";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->COLUNA_TAMANHO = "";
    $tpl3->CAMPO_TAMANHO = "40";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "cidade";
    $tpl3->CAMPO_VALOR = "$cidade, $estado";
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");


    //Telefone 1
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Telefone 1";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->COLUNA_TAMANHO = "";
    $tpl3->CAMPO_TAMANHO = "15";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "fone1";
    $tpl3->CAMPO_VALOR = "$fone1";
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");


    //Telefone 2
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Telefone 2";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->COLUNA_TAMANHO = "";
    $tpl3->CAMPO_TAMANHO = "15";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "fone2";
    $tpl3->CAMPO_VALOR = "$fone2";
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");


    //Observação
    $tpl3->COLUNA_ALINHAMENTO = "right";
    $tpl3->COLUNA_TAMANHO = "200px";
    $tpl3->TITULO = "Observação";
    $tpl3->block("BLOCK_TITULO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->COLUNA_ALINHAMENTO = "";
    $tpl3->COLUNA_TAMANHO = "";
    $tpl3->CAMPO_TAMANHO = "50";
    $tpl3->CAMPO_TIPO = "text";
    $tpl3->CAMPO_NOME = "obs";
    $tpl3->CAMPO_VALOR = "$obs";
    $tpl3->block("BLOCK_CAMPO_PADRAO");
    $tpl3->block("BLOCK_CAMPO_DESABILITADO");
    $tpl3->block("BLOCK_CAMPO");
    $tpl3->block("BLOCK_CONTEUDO");
    $tpl3->block("BLOCK_COLUNA");
    $tpl3->block("BLOCK_LINHA");

    $tpl3->show();
    
}


/*

//Saldo Pagamentos Pendentes Finais
$tpl1_tit = new Template("templates/tituloemlinha_1.html");
$tpl1_tit->LISTA_TITULO = "RESUMO FINANCEIRO";
$tpl1_tit->block("BLOCK_QUEBRA1");
$tpl1_tit->block("BLOCK_TITULO");
$tpl1_tit->show();

$tpl = new Template("templates/cadastro1.html");


//Total da Venda
if (($temdevolucoes==1)||($tempagamentos==1)) {
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "250px";
    $tpl->TITULO = "TOTAL DA VENDA";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "text";
    $tpl->CAMPO_NOME = "totalvenda";
    $tpl->CAMPO_VALOR =  "R$ " . number_format($total, 2, ",", ".");
    $tpl->block("BLOCK_CAMPO_PADRAO");
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_CAMPO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
}

//Desconto
if ($totalcomdesconto!=$totalbruto) {
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "250px";
    $tpl->TITULO = "DESCONTO";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "text";
    $tpl->CAMPO_NOME = "descontovalor";
    $tpl->CAMPO_VALOR =  "R$ " . number_format($descontovalor, 2, ",", ".");
    $tpl->block("BLOCK_CAMPO_PADRAO");
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_CAMPO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
}

//Total Liquido
if ($totalcomdesconto!=$totalbruto) {
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "250px";
    $tpl->TITULO = "TOT. LIQUIDO";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "text";
    $tpl->CAMPO_NOME = "descontovalor";
    $totalliquido=$total-$descontovalor;
    $tpl->CAMPO_VALOR =  "R$ " . number_format($totalliquido, 2, ",", ".");
    $tpl->block("BLOCK_CAMPO_PADRAO");
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_CAMPO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
}

//Total Devolvido
if ($temdevolucoes==1) {
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->TITULO = "TOTAL DEVOLVIDO";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "text";
    $tpl->CAMPO_NOME = "total_devolvido";
    $tpl->CAMPO_VALOR =  "R$ " . number_format($total_devolvido, 2, ",", ".");
    $tpl->block("BLOCK_CAMPO_PADRAO");
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_CAMPO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");


}

//Total Pago
if ($areceber==1) {
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->TITULO = "TOTAL PAGO";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "text";
    $tpl->CAMPO_NOME = "totalvenda";
    $tpl->CAMPO_VALOR =  "R$ " . number_format($pag_total, 2, ",", ".");
    $tpl->block("BLOCK_CAMPO_PADRAO");
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_CAMPO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
}


//Saldo Final
if (($tempagamentos==1)||($temdevolucoes==1)) {
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->TITULO = "SALDO À RECEBER";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "left";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->CAMPO_TIPO = "text";
    $tpl->CAMPO_NOME = "saldofinal";
    $saldofinal=$total_comdesconto-$pag_total;
    if ($temdevolucoes==0) $saldofinal-=$descontovalor;
    $tpl->CAMPO_VALOR =  "R$ " . number_format($saldofinal, 2, ",", ".");
    $tpl->block("BLOCK_CAMPO_PADRAO");
    $tpl->block("BLOCK_CAMPO_DESABILITADO");
    $tpl->block("BLOCK_CAMPO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
}
$tpl->show();

*/




//BOTÕES 

if ($ope != 4) { // 4=imprimir
    $tpl4 = new Template("templates/botoes1.html");
    
    //Botão Fechar
    $tpl4->COLUNA_LINK_ARQUIVO = "";
    $tpl4->block("BLOCK_LINHAHORIZONTAL_EMCIMA");
    $tpl4->block("BLOCK_COLUNA_LINK_FECHAR");
    $tpl4->block("BLOCK_COLUNA_LINK");
    $tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
    $tpl4->block("BLOCK_BOTAOPADRAO_FECHAR");
    $tpl4->block("BLOCK_BOTAOPADRAO_AUTOFOCO");
    $tpl4->block("BLOCK_BOTAOPADRAO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl4->block("BLOCK_COLUNA");
    
    //Botão Imprimir
    $tpl4->COLUNA_LINK_ARQUIVO = "saidas_ver.php?codigo=$saida&tiposaida=$tiposaida&ope=4";
    $tpl4->COLUNA_LINK_TARGET = "_blank";
    $tpl4->block("BLOCK_COLUNA_LINK");
    $tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
    $tpl4->block("BLOCK_BOTAOPADRAO_IMPRIMIR");
    $tpl4->block("BLOCK_BOTAOPADRAO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl4->block("BLOCK_COLUNA");

    //Botão Devoluções
    if (($usadevolucoes==1)&&($status_venda==1)&&($tiposaida!=3)) {
        $sql12="SELECT count(saidev_numero) as qtd_devolucoes FROM saidas_devolucoes WHERE saidev_saida=$saida";
        if (!$query12=mysql_query($sql12)) die("Erro de SQL12:" . mysql_error());
        $dados12=mysql_fetch_assoc($query12);
        $qtd_devolucoes=$dados12["qtd_devolucoes"];  
        if ($qtd_devolucoes>0) $qtd_devolucoes_texto="($qtd_devolucoes)";  else $qtd_devolucoes_texto=""; 
        $tpl4->COLUNA_TAMANHO="";
        $tpl4->COLUNA_ALINHAMENTO  ="";                
        $tpl4->COLUNA_LINK_ARQUIVO="saidas_devolucoes.php?codigo=$saida";
        $tpl4->COLUNA_LINK_CLASSE="";
        $tpl4->COLUNA_LINK_TARGET="";
        $tpl4->block(BLOCK_COLUNA_LINK);  
        $tpl4->BOTAO_CLASSE="botao botaoamarelo fonte3";
        $tpl4->block(BLOCK_BOTAO_DINAMICO); 
        $tpl4->BOTAO_TECLA="";
        $tpl4->BOTAO_TIPO="button";
        $tpl4->BOTAO_VALOR ="DEVOLUÇÕES $qtd_devolucoes_texto";
        $tpl4->BOTAO_NOME="DEVOLUÇÕES";
        $tpl4->BOTAO_ID="";
        $tpl4->BOTAO_DICA="";
        $tpl4->BOTAO_ONCLICK="";
        $tpl4->BOTAOPADRAO_CLASSE="botao botaoamarelo fonte3";
        $tpl4->block(BLOCK_BOTAO);         
        $tpl4->ONCLICK="";
        $tpl4->block(BLOCK_COLUNA);
    }

    //Botão Pagamentos
    if (($areceber==1)&&($status_venda==1)&&($usapagamentosparciais==1)) {
        $tpl4->COLUNA_TAMANHO="";
        $tpl4->COLUNA_ALINHAMENTO  ="";                
        $tpl4->COLUNA_LINK_ARQUIVO="saidas_pagamentos.php?saida=$saida";
        $tpl4->COLUNA_LINK_CLASSE="";
        $tpl4->COLUNA_LINK_TARGET="";
        $tpl4->block(BLOCK_COLUNA_LINK);  
        $tpl4->BOTAO_CLASSE="botao botaoamarelo fonte3";
        $tpl4->block(BLOCK_BOTAO_DINAMICO); 
        $tpl4->BOTAO_TECLA="";
        $tpl4->BOTAO_TIPO="button";
        $sql12="SELECT count(saipag_codigo) as qtd_pagamentos FROM saidas_pagamentos WHERE saipag_saida=$saida";
        if (!$query12=mysql_query($sql12)) die("Erro de SQL12:" . mysql_error());
        $dados12=mysql_fetch_assoc($query12);
        $qtd_pagamentos=$dados12["qtd_pagamentos"];  
        if ($qtd_pagamentos>0) $qtd_pagamentos_texto="($qtd_pagamentos)";
        $tpl4->BOTAO_VALOR ="PAGAMENTOS $qtd_pagamentos_texto";
        $tpl4->BOTAO_NOME="pagamentos";
        $tpl4->BOTAO_ID="";
        $tpl4->BOTAO_DICA="";
        $tpl4->BOTAO_ONCLICK="";
        $tpl4->BOTAOPADRAO_CLASSE="botao botaoamarelo fonte3";
        $tpl4->block(BLOCK_BOTAO);         
        $tpl4->ONCLICK="";
        $tpl4->block(BLOCK_COLUNA);
    }


    //Botão Cancelar Nota
    //Verificar se foi emitido nota
    $sql="SELECT sai_nfe,nfe_numero FROM saidas LEFT JOIN nfe on (sai_nfe=nfe_codigo) WHERE sai_codigo=$saida";
    if (!$query = mysql_query($sql)) die("<br>Erro SQL saida consulta: ".mysql_error());
    $dados=mysql_fetch_assoc($query);
    $nfe_da_venda=$dados["sai_nfe"];
    $nfe_numero=$dados["nfe_numero"];
    if ($nfe_da_venda!="") $temnota=1; else $temnota=0;
    if (($temnota==1)&&($usamodulofiscal==1)) {
        $tpl4->COLUNA_TAMANHO="";
        $tpl4->COLUNA_ALINHAMENTO  ="";                
        $tpl4->COLUNA_LINK_ARQUIVO="nfe_cancelar.php?codigo=$saida&finalidade=2&nfe_numero=$nfe_numero&nfe_codigo=$nfe_codigo";
        $tpl4->COLUNA_LINK_CLASSE="";
        $tpl4->COLUNA_LINK_TARGET="";
        $tpl4->block(BLOCK_COLUNA_LINK);  
        $tpl4->BOTAO_CLASSE="botao botaovermelho fonte3";
        $tpl4->block(BLOCK_BOTAO_DINAMICO); 
        $tpl4->BOTAO_TECLA="";
        $tpl4->BOTAO_TIPO="button"; 
        $tpl4->BOTAO_VALOR ="CANCELAR NOTA";
        $tpl4->BOTAO_NOME="cancelarnota";
        $tpl4->BOTAO_ID="";
        $tpl4->BOTAO_DICA="";
        $tpl4->BOTAO_ONCLICK="";
        $tpl4->BOTAOPADRAO_CLASSE="botao botaoamarelo fonte3";
        $tpl4->block(BLOCK_BOTAO);         
        $tpl4->ONCLICK="";
        $tpl4->block(BLOCK_COLUNA);
    }

    //Botão Entregas
    if ($entrega==1) {
        $tpl4->COLUNA_TAMANHO="";
        $tpl4->COLUNA_ALINHAMENTO  ="";                
        $tpl4->COLUNA_LINK_ARQUIVO="saidas_entrega.php?saida=$saida";
        $tpl4->COLUNA_LINK_CLASSE="";
        $tpl4->COLUNA_LINK_TARGET="";
        $tpl4->block(BLOCK_COLUNA_LINK); 
        if ($entrega_conluida==1) {
            $tpl4->BOTAO_CLASSE="botao botaovermelho fonte3";
            $tpl4->BOTAO_VALOR ="CANCELAR ENTREGA";
        } else {
            $tpl4->BOTAO_VALOR ="CONFIRMAR ENTREGA";
            $tpl4->BOTAO_CLASSE="botao botaoverde fonte3";
        }
        $tpl4->block(BLOCK_BOTAO_DINAMICO); 
        $tpl4->BOTAO_TECLA="";
        $tpl4->BOTAO_TIPO="button";
        $tpl4->BOTAO_NOME="entrega";
        $tpl4->BOTAO_ID="";
        $tpl4->BOTAO_DICA="";
        $tpl4->BOTAO_ONCLICK="";
        $tpl4->block(BLOCK_BOTAO);         
        $tpl4->ONCLICK="";
        $tpl4->block(BLOCK_COLUNA);
    }



    $tpl4->block(BLOCK_LINHA);
    $tpl4->block(BLOCK_BOTOES);
    $tpl4->block("BLOCK_LINHA");
    $tpl4->block("BLOCK_BOTOES");
    $tpl4->show();
} 
if ($botaofechar==1) {
    $tpl4 = new Template("templates/botoes1.html");
    //Botão Fechar
    $tpl4->block("BLOCK_LINHAHORIZONTAL_EMCIMA");
    $tpl4->block("BLOCK_COLUNA_LINK_FECHAR");
    $tpl4->COLUNA_LINK_ARQUIVO = "";
    $tpl4->block("BLOCK_COLUNA_LINK");
    $tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
    $tpl4->block("BLOCK_BOTAOPADRAO_FECHAR");
    $tpl4->block("BLOCK_BOTAOPADRAO_AUTOFOCO");
    $tpl4->block("BLOCK_BOTAOPADRAO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl4->block("BLOCK_COLUNA");  
    $tpl4->block("BLOCK_LINHA");
    $tpl4->block("BLOCK_BOTOES");
    $tpl4->show();
}

?>