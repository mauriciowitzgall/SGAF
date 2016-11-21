<script type="text/javascript" src="entradas_etiquetas_massa.js"></script>

<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_entradas_etiquetas <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}




$tipopagina = "entradas";
include "includes2.php";

$lote = $_GET["lote"];

//Pega os dados da entrada para popular os campos de cabeçalho (numero da entrada, fornecedor)
$sql = "
    SELECT 
        pes_nome
    FROM 
        entradas 
        join pessoas on (ent_fornecedor=pes_codigo)
    WHERE 
        ent_codigo=$lote   
";
$query = mysql_query($sql);
if (!$query)
    die("Erro1:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $fornecedor_nome = $dados["pes_nome"];
}

$data = desconverte_data($_POST["data"]);
$hora = $_POST["hora"];

$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ETIQUETAS";
$tpl_titulo->SUBTITULO = "IMPRIMIR ETIQUETAS EM MASSA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "etiquetas.png";
$tpl_titulo->show();



$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
//será usado apenas o form2 que é o form da listagem, por isso este nao tem destino definido
$tpl1->LINK_DESTINO = "";
$tpl1->LINK_TARGET = "";

//Lote
$tpl1->TITULO = "Lote";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "lote";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = $lote;
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Fornecedor
$tpl1->TITULO = "Fornecedor";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "fornecedor";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = $fornecedor_nome;
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");


//Etiqueta
$tpl1->TITULO = "Modelo de Etiqueta";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "modeloetiqueta";
$tpl1->SELECT_ID = "modeloetiqueta";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->SELECT_ONCHANGE="etiqueta_escolhida(this.value,$lote)";
$tpl1->block("BLOCK_SELECT_ONCHANGE");
$tpl1->OPTION_VALOR = "1";
$tpl1->OPTION_NOME = "Pequena";
//$tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
$tpl1->block("BLOCK_SELECT_OPTION");

/*$tpl1->OPTION_VALOR = "2";
$tpl1->OPTION_NOME = "Grande";
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->OPTION_VALOR = "3";
$tpl1->OPTION_NOME = "Granel";
$tpl1->block("BLOCK_SELECT_OPTION");*/
$tpl1->OPTION_VALOR = "4";
$tpl1->OPTION_NOME = "Compacta";
$tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
$tpl1->block("BLOCK_SELECT_OPTION");



$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

/*

//Botão Gerar
$tpl1->BOTAO_TIPO = "submit";
$tpl1->BOTAO_VALOR = "GERAR ETIQUETAS";
$tpl1->BOTAO_NOME = "GERAR ETIQUETAS";
$tpl1->BOTAO_FOCO = "autofocus";
$tpl1->block("BLOCK_BOTAO1_SEMLINK");
$tpl1->block("BLOCK_BOTAO1");

$tpl1->CAMPOOCULTO_NOME = "lote";
$tpl1->CAMPOOCULTO_VALOR = "$lote";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->CAMPOOCULTO_NOME = "numero";
$tpl1->CAMPOOCULTO_VALOR = "$numero";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

*/

$tpl1->show();


$tpl2 = new Template("templates/lista2.html");
$tpl2->block("BLOCK_TABELA_CHEIA");

//Destino Padrão. este é alterado conforme seleciona o tipo de etiqueta no select
$tpl2->FORM_LINK="entradas_etiqueta_compacta.php?lote=$lote&massa=1";
$tpl2->FORM_TARGET="";
$tpl2->FORM_NOME="form2";
$tpl2->block("BLOCK_FORM");

$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "Item";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "Produto";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "Quantidade";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "Quantidade Etiquetas";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->block("BLOCK_CABECALHO_LINHA");
$tpl2->block("BLOCK_CABECALHO");

//Listagem Linhas
$sql = "
    SELECT *
    FROM entradas_produtos    
    JOIN produtos on (entpro_produto=pro_codigo)
    JOIN produtos_tipo on (protip_codigo=pro_tipocontagem)
    WHERE entpro_entrada=$lote
    ORDER BY entpro_numero DESC
";
$query = mysql_query($sql);
if (!$query) die("Erro SQL 1: " . mysql_error());

while ($dados = mysql_fetch_assoc($query)) {
    $produto_nome = $dados["pro_nome"];
    $produto_referencia=$dados["pro_referencia"];
    $produto_tamanho=$dados["pro_tamanho"];
    $produto_cor=$dados["pro_cor"];
    $produto_descricao=$dados["pro_descricao"];
    $produto_nome2 = "$produto_nome $produto_referencia $produto_tamanho $produto_cor $produto_descricao";
    $numero=$dados["entpro_numero"];
    $qtd=$dados["entpro_quantidade"];
    $sigla=$dados["protip_sigla"];
    
    //Linha Item ou Número
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "$numero";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Linha Produto
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "left";
    $tpl2->TEXTO = "$produto_nome2";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Linha Quantidade
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "$qtd";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "left";
    $tpl2->TEXTO = "$sigla";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    
    //Linha Quantidade de etiquetas desejada
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->CAMPO_TIPO="text";
    $tpl2->CAMPO_NOME="qtddesejada[$numero]";
    $tpl2->CAMPO_ID="qtddesejada[$numero]";
    $tpl2->CAMPO_TAMANHO="";
    $tpl2->CAMPO_VALOR="$qtd";
    $tpl2->CAMPO_QTDCARACTERES="";
    $tpl2->CAMPO_ESTILO="width:70px";
    $tpl2->block("BLOCK_CAMPO_ESTILO");
    $tpl2->block("BLOCK_CAMPO_OBRIGATORIO");
    $tpl2->block("BLOCK_CAMPO_PADRAO");
    $tpl2->block("BLOCK_CAMPO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "left";
    $tpl2->TEXTO = "$sigla";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");    
        

    $tpl2->block("BLOCK_LINHA_PADRAO");
    $tpl2->block("BLOCK_LINHA");
}

$tpl2->block("BLOCK_CORPO");



$tpl2->block("BLOCK_LISTAGEM");

$tpl2->show();

// Botão gerar
$tpl4 = new Template("templates/botoes1.html");
$tpl4->block("BLOCK_QUEBRA_EMCIMA");
$tpl4->block("BLOCK_LINHAHORIZONTAL_EMCIMA");
$tpl4->COLUNA_TAMANHO = "100%";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_COLUNA");
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl4->block("BLOCK_BOTAOPADRAO_GERAR");
$tpl4->ONCLICK="javascript: document.form1.submit();";
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

$tpl4->block("BLOCK_LINHA");
$tpl4->block("BLOCK_BOTOES");

$tpl4->show();


//include "rodape2.php";
?>
