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
if ($ope == 4) {
    include "includes2.php";
} else {
    include "includes.php";
}

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "SAIDAS  ";
$tpl_titulo->SUBTITULO = "DETALHES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "saidas.png";
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

$totalbruto = $dados["sai_totalbruto"];
$areceber = $dados["sai_areceber"];



//DADOS GERAIS DA VENDA
$tpl1_tit = new Template("templates/tituloemlinha_1.html");
$tpl1_tit->LISTA_TITULO = "DADOS GERAIS DA VENDA";
$tpl1_tit->block("BLOCK_QUEBRA1");
$tpl1_tit->block("BLOCK_TITULO");
$tpl1_tit->show();

$tpl = new Template("templates/cadastro1.html");

if ($tiposaida == 1) {

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

if ($tiposaida == 3) {

    if ($dados["sai_saidajustificada"] != 0) {
        //Motivo
        $motivo = $dados["saimot_nome"];
        //Titulo
        $tpl->COLUNA_ALINHAMENTO = "right";
        $tpl->COLUNA_TAMANHO = "200px";
        $tpl->TITULO = "Motivo";
        $tpl->block("BLOCK_TITULO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->COLUNA_ALINHAMENTO = "left";
        $tpl->COLUNA_TAMANHO = "";
        //Campo
        $tpl->CAMPO_TIPO = "text";
        $tpl->CAMPO_NOME = "motivo";
        $tpl->CAMPO_VALOR = "$motivo";
        $tpl->block("BLOCK_CAMPO_PADRAO");
        $tpl->block("BLOCK_CAMPO_DESABILITADO");
        $tpl->block("BLOCK_CAMPO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->block("BLOCK_LINHA");


        //Descri��o
        //Titulo
        $tpl->COLUNA_ALINHAMENTO = "right";
        $tpl->COLUNA_TAMANHO = "200px";
        $tpl->TITULO = "Descrição";
        $tpl->block("BLOCK_TITULO");
        $tpl->block("BLOCK_CONTEUDO");
        $tpl->block("BLOCK_COLUNA");
        $tpl->COLUNA_ALINHAMENTO = "left";
        $tpl->COLUNA_TAMANHO = "";
        $tpl->TEXTAREA_TAMANHO = "60";
        $tpl->TEXTAREA_NOME = "descricao";
        $tpl->TEXTAREA_TEXTO = "$descricao";
        $tpl->block("BLOCK_TEXTAREA_PADRAO");
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
$tpl2->block(BLOCK_TABELA_CHEIA);

//Cabecalho
$tpl2->CABECALHO_COLUNA_TAMANHO = "100px";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "Nº";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "PRODUTO";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "FORNECEDOR";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "LOTE";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_NOME = "QUANTIDADE";
$tpl2->block(BLOCK_LISTA_CABECALHO);
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
    saipro_codigo,pro_nome,pes_nome,saipro_lote,saipro_quantidade,protip_sigla,protip_codigo,saipro_valorunitario,saipro_valortotal
FROM 
    saidas
    join saidas_produtos on (saipro_saida=sai_codigo)
    join produtos on (saipro_produto=pro_codigo)
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
    join entradas on (saipro_lote=ent_codigo)
    join pessoas on (ent_fornecedor=pes_codigo)
WHERE
    sai_codigo=$saida
";

$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro43" . mysql_error());
$total = 0;

while ($dados2 = mysql_fetch_assoc($query2)) {
    $tpl2->LISTA_CLASSE = "tab_linhas2";
    $tpl2->block("BLOCK_LISTA_CLASSE");

    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_VALOR = $dados2["saipro_codigo"];
    $tpl2->block("BLOCK_LISTA_COLUNA");

    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = $dados2["pro_nome"];
    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = $dados2["pes_nome"];
    $tpl2->block("BLOCK_LISTA_COLUNA");

    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_TAMANHO = "";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = $dados2["saipro_lote"];
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
$tpl3->CAMPO_TAMANHO = "5";
$tpl3->CAMPO_NOME = "descontopercentual";
$descontopercentual = $dados["sai_descontopercentual"];
$tpl3->CAMPO_VALOR = $descontopercentual . " % ";
$tpl3->block("BLOCK_CAMPO_PADRAO");
$tpl3->block("BLOCK_CAMPO_DESABILITADO");
$tpl3->block("BLOCK_CAMPO");
//Dinheiro
$tpl3->CAMPO_TIPO = "text";
$tpl3->CAMPO_NOME = "valortotal";
$tpl3->CAMPO_TAMANHO = "15";
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

if ($ope != 4) {

    $tpl4 = new Template("templates/botoes1.html");

    //Bot�o Voltar
    $tpl4->block("BLOCK_LINHAHORIZONTAL_EMCIMA");
    $tpl4->block("BLOCK_COLUNA_LINK_VOLTAR");
    $tpl4->COLUNA_LINK_ARQUIVO = "";
    $tpl4->block("BLOCK_COLUNA_LINK");
    $tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
    $tpl4->block("BLOCK_BOTAOPADRAO_VOLTAR");
    $tpl4->block("BLOCK_BOTAOPADRAO_AUTOFOCO");
    $tpl4->block("BLOCK_BOTAOPADRAO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl4->block("BLOCK_COLUNA");

    //Bot�o Imprimir
    $tpl4->COLUNA_LINK_ARQUIVO = "saidas_ver.php?codigo=$saida&tiposaida=$tiposaida&ope=4";
    $tpl4->COLUNA_LINK_TARGET = "_blank";
    $tpl4->block("BLOCK_COLUNA_LINK");
    $tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
    $tpl4->block("BLOCK_BOTAOPADRAO_IMPRIMIR");
    $tpl4->block("BLOCK_BOTAOPADRAO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl4->block("BLOCK_COLUNA");

    $tpl4->block("BLOCK_LINHA");
    $tpl4->block("BLOCK_BOTOES");
    $tpl4->show();
}
?>
<!--sai_totalbruto, 
sai_descontopercentual, 
sai_descontovalor, 
sai_totalcomdesconto, 
sai_valorecebido, 
sai_troco, 
sai_trocodevolvido, 
sai_descontoforcado, 
sai_acrescimoforcado, 
sai_totalliquido,-->