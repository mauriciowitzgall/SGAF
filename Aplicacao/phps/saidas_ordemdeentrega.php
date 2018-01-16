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

$sql="
    SELECT * 
    FROM saidas
    LEFT JOIN metodos_pagamento on (metpag_codigo=sai_metpag)
    LEFT JOIN pessoas on (sai_consumidor=pes_codigo)
    left join cidades on (sai_entrega_cidade=cid_codigo)
    WHERE sai_codigo=$saida
";
if (!$query=mysql_query($sql)) die("SQL ERROR: ".mysql_error());
$dados= mysql_fetch_assoc($query);
$saida_datacastro_convertido=converte_datahora($dados["sai_datacadastro"]). " ".converte_hora($dados["sai_horacadastro"]);
$saida_dataentrega_convertido=converte_datahora($dados["sai_dataentrega"]);
$areceber=$dados["sai_areceber"];
if ($areceber==1) $metpag_nome="À Receber";
else $metpag_nome=$dados["metpag_nome"];
$valbru=$dados["sai_totalbruto"];
$desconto=$dados["sai_descontovalor"];
$valliq=$dados["sai_totalcomdesconto"];
$consumidor_nome=$dados["pes_nome"];
$consumidor_cpf=$dados["pes_cpf"];
$consumidor_cnpj=$dados["pes_cnpj"];
$consumidor_tipopessoa=$dados["pes_tipopessoa"];
if ($consumidor_tipopessoa==1) $consumidor_documento=mask($consumidor_cpf,"###.###.###-##");
else $consumidor_documento=mask($consumidor_cnpj,"##.###.###/####-##");

$consumidor_documento="($consumidor_documento)";
$entrega_endereco=$dados["sai_entrega_endereco"];
$entrega_endereco_numero=$dados["sai_entrega_endereco_numero"];
$entrega_bairro=$dados["sai_entrega_bairro"];
$entrega_endereco_referencia=$dados["sai_entrega_endereco_referencia"];
$entrega_fone1=$dados["sai_entrega_fone1"];
$entrega_fone2=$dados["sai_entrega_fone2"];
$obs=$dados["sai_obs"];
$entrega_cidade=$dados["cid_nome"];

/*
//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ENTREGAS";
$tpl_titulo->SUBTITULO = "DETALHES DA ENTREGA";
$tpl_titulo->NOME_ARQUIVO_ICONE = "entregas.png";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->show();
*/


$cont=1;
while ($cont<=2) {

    //DADOS GERAL DA ENTREGA
    $tpl2 = new Template("templates/lista1.html");
    $tpl2->TABELA_BORDA="0";
    $tpl2->block(BLOCK_TABELA_CHEIA);

    $tpl2->LISTA_CLASSE = "tab_linhas2";
    $tpl2->block("BLOCK_LISTA_CLASSE");
    
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_COLSPAN = "8";
    $tpl2->CABECALHO_COLUNA_NOME = "<b>$usuario_quiosque_nome</b> $usuario_quiosque_fone1 / $usuario_quiosque_fone2";
    $tpl2->block("BLOCK_LISTA_CABECALHO");
    $tpl2->block("BLOCK_LISTA");

    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Consumidor:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$consumidor_nome";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Nº Venda:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$saida";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Data Venda:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$saida_datacastro_convertido";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Data Entrega:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$saida_dataentrega_convertido";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->block("BLOCK_LISTA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Método Pag.:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$metpag_nome";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Bruto:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "R$ ". number_format($valbru,2,',','.');
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Desconto:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR =  "R$ ". number_format($desconto,2,',','.');
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Total Final:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR =  "R$ ". number_format($valliq,2,',','.');
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->block("BLOCK_LISTA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Endereço:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "3";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$entrega_endereco, $entrega_endereco_numero";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Bairro:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$entrega_bairro";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Cidade:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$entrega_cidade";
    $tpl2->block("BLOCK_LISTA_COLUNA");    
    $tpl2->block("BLOCK_LISTA"  );
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Observação:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "3";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$obs";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Telefone 1:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$entrega_fone1";
    $tpl2->block("BLOCK_LISTA_COLUNA");
        $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>Telefone 2:</b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "$entrega_fone2";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->block("BLOCK_LISTA");

    $tpl2->block("BLOCK_LISTA1");
    $tpl2->show();


    //Termos de responsabilidade
    $tpl2 = new Template("templates/lista1.html");
    $tpl2->TABELA_BORDA="0";
    $tpl2->block(BLOCK_TABELA_CHEIA);
    $tpl2->LISTA_CLASSE = "tab_linhas2";
    $tpl2->block("BLOCK_LISTA_CLASSE");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<b>TERMO DE COMPROMISSO:</b> Reconheço por este pedido que possuo a título de emprestimo os equipamentos listados abaixo da empresa <b>$usuario_quiosque_razaosocial </b>situada em <b>$usuario_quiosque_endereco, $usuario_quiosque_endereco_numero</b> na cidade de <b>$usuario_quiosque_cidade_nome </b>";
    $tpl2->block("BLOCK_LISTA_COLUNA");  
    $tpl2->block("BLOCK_LISTA");
    $tpl2->block("BLOCK_LISTA1");
    $tpl2->show();


    //LISTA DE PRODUTOS
    $tpl2 = new Template("templates/lista1.html");
    $tpl2->TABELA_BORDA="1";
    $tpl2->block(BLOCK_TABELA_CHEIA);

    //Cabecalho
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
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
        $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
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
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tipocontagem = $dados2["protip_codigo"];
        if (($tipocontagem == 2)||($tipocontagem==3))
            $tpl2->LISTA_COLUNA_VALOR = number_format($dados2['saipro_quantidade'], 3, ',', '.');
        else
            $tpl2->LISTA_COLUNA_VALOR = number_format($dados2['saipro_quantidade'], 0, '', '.');
        $tpl2->block("BLOCK_LISTA_COLUNA");

        $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl2->LISTA_COLUNA_TAMANHO = "";
        $tpl2->LISTA_COLUNA_CLASSE = "";
        $tpl2->LISTA_COLUNA_VALOR = $dados2["protip_sigla"];
        $tpl2->block("BLOCK_LISTA_COLUNA");


        if ($usavendaporcoes==1) {
            //Nome da Porção
            $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl2->LISTA_COLUNA_TAMANHO = "";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tpl2->LISTA_COLUNA_VALOR = $dados2["propor_nome"];
            $tpl2->block("BLOCK_LISTA_COLUNA");
            //Quantidade de Proção
            $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl2->LISTA_COLUNA_TAMANHO = "";
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

    

    //Assinaturas
    $tpl2 = new Template("templates/lista1.html");
    $tpl2->TABELA_BORDA="0";
    $tpl2->block(BLOCK_TABELA_CHEIA);
    $tpl2->LISTA_CLASSE = "tab_linhas";
    $tpl2->block("BLOCK_LISTA_CLASSE");
    $tpl2->LISTA_COLUNA_COLSPAN = "2";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "Sendo de minha total responsabilidade a manutenção e entrega no mesmo estado de conservação que recebi.<br> CIENTE DO TERMO, FIRMO ABAIXO:";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->block("BLOCK_LISTA");


    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "center";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<br>____________________________ <br> Entregador";
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_COLSPAN = "";
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "center";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = "<br>____________________________ <br> Consumidor $consumidor_documento";
    $tpl2->block("BLOCK_LISTA_COLUNA");    
    $tpl2->block("BLOCK_LISTA");
    $tpl2->block("BLOCK_LISTA1");
    $tpl2->show();


    //LINHA PONTINHADA
    $tpl2 = new Template("templates/lista1.html");
    $tpl2->TABELA_BORDA="1";
    $tpl2->block(BLOCK_TABELA_CHEIA);
    if ($cont==1) {
        $tpl2->LINHAHORINZONTAL_ALINHAMENTO="";
        $tpl2->LINHAHORINZONTAL_CLASSE="linha_pontilhada";
        $tpl2->block("BLOCK_LINHAHORIZONTAL_EMBAIXO");
        //$tpl2->block("BLOCK_QUEBRA1");
    }
    $tpl2->block("BLOCK_LISTA1");
    $tpl2->show();


    $cont++;



}





?>