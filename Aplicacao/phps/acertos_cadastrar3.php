<?php

$titulopagina = "Acertos Cadastro/Edição";

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if (($permissao_acertos_cadastrar == 0) && ($permissao_acertos_ver == 0)) {
    header("Location: permissoes_semacesso.php");
    exit;
}


//Verifica se o usu�rio logado � um fornecedor
$codigo = $_GET["codigo"];
$sql_for = "
SELECT ace_fornecedor
FROM acertos 
join pessoas on (pes_codigo=ace_fornecedor)
WHERE ace_codigo='$codigo'
";
$query_for = mysql_query($sql_for);
if (!$query_for)
    die("Erro Fornecedor:" . mysql_error());
$dados_for = mysql_fetch_assoc($query_for);
$for = $dados_for['ace_fornecedor'];
if (($usuario_grupo == 5) && ($for != $usuario_codigo)) {
    header("Location: permissoes_semacesso.php");
}

$tipopagina = "acertos";
$operacao = $_GET["operacao"];
if ($operacao == "ver")
    include "includes.php";
else if ($operacao == "imprimir") {
    include "includes2.php";
    //include "relatorio_cabecalho_1.php"; 
} else {
    echo "Erro Grave";
    exit;
}


$codigo = $_GET["codigo"];

$sql = "
SELECT 
    *
FROM 
    acertos 
    join pessoas on (pes_codigo=ace_fornecedor)
WHERE 
    ace_codigo='$codigo'
";
$query = mysql_query($sql);
if (!$query)
    die("Erro1:" . mysql_error());
$dados = mysql_fetch_assoc($query);
$data = $dados["ace_data"];
$data = converte_data($data);
$hora = $dados["ace_hora"];
$supervisor = $dados["ace_supervisor"];
$fornecedor = $dados["ace_fornecedor"];
$fornecedor_nome = $dados["pes_nome"];
$valorbruto = $dados["ace_valorbruto"];
$valortaxas = $dados["ace_valortaxas"];
$valorpendente = $dados["ace_valorpendente"];
$valorpendenteanterior = $dados["ace_valorpendenteanterior"];
$valortotal = $dados["ace_valortotal"];
$valorpago = $dados["ace_valorpago"];
$trocodevolvido = $dados["ace_trocodevolvido"];



//--------------------TEMPLATE TÍTULO PRINCIPAL--------------------
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ACERTOS DE CONSIGNAÇÕES";
$tpl_titulo->SUBTITULO = "DETALHES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "consignacao.png";
$tpl_titulo->show();


//--------------------TEMPLATE FORNECEDOR E BOT�O --------------------
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "acertos_cadastrar.php";

//Fornecedor
$tpl1->TITULO = "Fornecedor";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "fornecedor";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$fornecedor_nome";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Data
$tpl1->TITULO = "Data";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "data";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$data";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Hora
$tpl1->TITULO = "Hora";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "hora";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$hora";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//N�
$tpl1->TITULO = "Nº";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "numero";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$codigo";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

$tpl1->show();


//--------------------------TOTAL VENDIDO-------------------------------------
$tpl2 = new Template("templates/lista1.html");
$tpl2->TABELA_BORDA="1";
//Título
$tpl2_tit = new Template("templates/tituloemlinha_1.html");
$tpl2_tit->LISTA_TITULO = "VENDAS";
$tpl2_tit->block("BLOCK_QUEBRA1");
$tpl2_tit->block("BLOCK_TITULO");
$tpl2_tit->show();

//Cabecalho
$tpl2->CABECALHO_COLUNA_TAMANHO = "200px";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "PRODUTO";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "200px";
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_NOME = "QUANTIDADE";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "150px";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "VALOR UNIT. MÉDIO";
$tpl2->block(BLOCK_LISTA_CABECALHO);
$tpl2->CABECALHO_COLUNA_TAMANHO = "150px";
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_NOME = "VALOR BRUTO";
$tpl2->block(BLOCK_LISTA_CABECALHO);

//Mostra todos os produtos que foram acertados referente ao acerto em quest�o
$sql = "
    SELECT pro_nome, round(sum(saipro_quantidade),2) as qtd, protip_sigla, avg(saipro_valorunitario) as valuni, round(sum(saipro_valortotal),2) as total
FROM 
    saidas_produtos
    join produtos on (saipro_produto=pro_codigo)
    join produtos_tipo on (pro_tipocontagem=protip_codigo)
    join entradas on (saipro_lote=ent_codigo)
WHERE
   saipro_acertado=$codigo
GROUP BY 
    saipro_produto
";

$query = mysql_query($sql);
if (!$query)
    die("Erro43" . mysql_error());
$total_bruto = 0;
while ($dados = mysql_fetch_assoc($query)) {
    $tpl2->LISTA_CLASSE = "tab_linhas2";
    $tpl2->block("BLOCK_LISTA_CLASSE");

    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = $dados["pro_nome"];

    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = number_format($dados["qtd"], 2, ',', '.');

    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = $dados["protip_sigla"];

    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($dados["valuni"], 2, ',', '.');

    $tpl2->block("BLOCK_LISTA_COLUNA");
    $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl2->LISTA_COLUNA_CLASSE = "";
    $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($dados["total"], 2, ',', '.');
    $tpl2->block("BLOCK_LISTA_COLUNA");

    $total_bruto = $total_bruto + $dados["total"];
    $tpl2->block("BLOCK_LISTA");
}
//Rodap� da lisagem
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
$tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($total_bruto, 2, ",", ".");
$tpl2->block("BLOCK_LISTA_COLUNA");
$tpl2->block("BLOCK_LISTA");

$tpl2->block("BLOCK_LISTA1");
$tpl2->show();



//--------------------------TAXAS-------------------------------------
$tpl5 = new Template("templates/lista1.html");
$tpl5->TABELA_BORDA="1";
//Título
$tpl5_tit = new Template("templates/tituloemlinha_1.html");
$tpl5_tit->LISTA_TITULO = "TAXAS";
$tpl5_tit->block("BLOCK_QUEBRA1");
$tpl5_tit->block("BLOCK_TITULO");
$tpl5_tit->show();

//Cabecalho
$tpl5->CABECALHO_COLUNA_TAMANHO = "200px";
$tpl5->CABECALHO_COLUNA_COLSPAN = "";
$tpl5->CABECALHO_COLUNA_NOME = "TAXAS";
$tpl5->block(BLOCK_LISTA_CABECALHO);
$tpl5->CABECALHO_COLUNA_TAMANHO = "100px";
$tpl5->CABECALHO_COLUNA_COLSPAN = "2";
$tpl5->CABECALHO_COLUNA_NOME = "VAL. REF.";
$tpl5->block(BLOCK_LISTA_CABECALHO);
$tpl5->CABECALHO_COLUNA_TAMANHO = "150px";
$tpl5->CABECALHO_COLUNA_COLSPAN = "";
$tpl5->CABECALHO_COLUNA_NOME = "VALOR";
$tpl5->block(BLOCK_LISTA_CABECALHO);

//Mostra todas as taxas cobradas neste acerto
$sql = "
    SELECT * 
    FROM acertos_taxas 
    join taxas on (tax_codigo=acetax_taxa)    
    WHERE acetax_acerto=$codigo
    and tax_tiponegociacao=1    
";
$query = mysql_query($sql);
if (!$query)
    die("Erro43" . mysql_error());
$taxas=0;
while ($dados = mysql_fetch_assoc($query)) {

    $valtax = $dados["acetax_valor"];
    $tpl5->LISTA_CLASSE = "tab_linhas2";
    $tpl5->block("BLOCK_LISTA_CLASSE");

    $tpl5->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl5->LISTA_COLUNA_CLASSE = "";
    $tpl5->LISTA_COLUNA_VALOR = $dados["tax_nome"];
    $tpl5->block("BLOCK_LISTA_COLUNA");

    $tpl5->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl5->LISTA_COLUNA_CLASSE = "";    
    $tpl5->LISTA_COLUNA_VALOR = number_format($dados["acetax_referencia"], 2, ',', '.');
    $tpl5->block("BLOCK_LISTA_COLUNA");

    $tpl5->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl5->LISTA_COLUNA_CLASSE = "";
    $tpl5->LISTA_COLUNA_VALOR = "%";
    $tpl5->block("BLOCK_LISTA_COLUNA");

    $tpl5->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl5->LISTA_COLUNA_CLASSE = "";

    $tpl5->LISTA_COLUNA_VALOR = "R$ " . number_format($dados["acetax_valor"], 2, ',', '.');
    $tpl5->block("BLOCK_LISTA_COLUNA");
    $taxas=$taxas+ $dados["acetax_referencia"];
    $valtaxtot = $valtaxtot + $valtax;
    $tpl5->block("BLOCK_LISTA");
}
//Rodap� da lisagem
$tpl5->LISTA_CLASSE = "tabelarodape1";
$tpl5->block("BLOCK_LISTA_CLASSE");
$tpl5->LISTA_COLUNA_ALINHAMENTO = "left";
$tpl5->LISTA_COLUNA_VALOR = "Fornecedor";
$tpl5->block("BLOCK_LISTA_COLUNA");
$taxa_fornecedor = 100 - $taxas;
$tpl5->LISTA_COLUNA_ALINHAMENTO = "right";
$tpl5->LISTA_COLUNA_VALOR = number_format($taxa_fornecedor, 2, ',', '.');
$tpl5->block("BLOCK_LISTA_COLUNA");
$tpl5->LISTA_COLUNA_ALINHAMENTO = "left";
$tpl5->LISTA_COLUNA_VALOR = "%";
$tpl5->block("BLOCK_LISTA_COLUNA");
$tpl5->LISTA_COLUNA_ALINHAMENTO = "right";
$valor_fornecedor = $total_bruto - $valtaxtot;
$tpl5->LISTA_COLUNA_VALOR = "R$ " . number_format($valor_fornecedor, 2, ",", ".");
$tpl5->block("BLOCK_LISTA_COLUNA");
$tpl5->block("BLOCK_LISTA");

$tpl5->block("BLOCK_LISTA1");
$tpl5->show();



//--------------------------DADOS FINANCEIROS DO ACERTO-----------------------------------
//Título
$tpl4_tit = new Template("templates/tituloemlinha_1.html");
$tpl4_tit->LISTA_TITULO = "DADOS FINANCEIROS DO ACERTO";
$tpl4_tit->block("BLOCK_QUEBRA1");
$tpl4_tit->block("BLOCK_TITULO");
$tpl4_tit->show();



$tpl4 = new Template("templates/cadastro_edicao_detalhes_2.html");

//Total Bruto
$tpl4->TITULO = "Total Bruto";
$tpl4->block("BLOCK_TITULO");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->CAMPO_TIPO = "text";
$tpl4->CAMPO_NOME = "bruto";
$tpl4->CAMPO_DICA = "";
$tpl4->CAMPO_ID = "";
$tpl4->CAMPO_TAMANHO = "";
$tpl4->CAMPO_VALOR = "R$ " . number_format($valorbruto, 2, ',', '.');
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->block("BLOCK_CAMPO_NORMAL");
$tpl4->block("BLOCK_CAMPO_DESABILITADO");
$tpl4->block("BLOCK_CAMPO");
$tpl4->block("BLOCK_CONTEUDO");
$tpl4->block("BLOCK_ITEM");

//Taxa Total
$tpl4->TITULO = "Total de Taxas";
$tpl4->block("BLOCK_TITULO");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->CAMPO_TIPO = "text";
$tpl4->CAMPO_NOME = "valtaxtot";
$tpl4->CAMPO_DICA = "";
$tpl4->CAMPO_ID = "";
$tpl4->CAMPO_TAMANHO = "";
$tpl4->CAMPO_VALOR = "R$ " . number_format($valortaxas, 2, ',', '.');
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->block("BLOCK_CAMPO_NORMAL");
$tpl4->block("BLOCK_CAMPO_DESABILITADO");
$tpl4->block("BLOCK_CAMPO");
$tpl4->block("BLOCK_CONTEUDO");
$tpl4->block("BLOCK_ITEM");


//Valor Pendente
$tpl4->TITULO = "Valor Pendente (Ant.)";
$tpl4->block("BLOCK_TITULO");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->CAMPO_TIPO = "text";
$tpl4->CAMPO_NOME = "valpenant";
$tpl4->CAMPO_DICA = "";
$tpl4->CAMPO_ID = "";
$tpl4->CAMPO_TAMANHO = "";
$tpl4->CAMPO_VALOR = "R$ " . number_format($valorpendenteanterior, 2, ",", ".");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->block("BLOCK_CAMPO_NORMAL");
$tpl4->block("BLOCK_CAMPO_DESABILITADO");
$tpl4->block("BLOCK_CAMPO");
$tpl4->block("BLOCK_CONTEUDO");
$tpl4->block("BLOCK_ITEM");

//Valor Total
$tpl4->TITULO = "Valor Total";
$tpl4->block("BLOCK_TITULO");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->CAMPO_TIPO = "text";
$tpl4->CAMPO_NOME = "valtot";
$tpl4->CAMPO_DICA = "";
$tpl4->CAMPO_ID = "";
$tpl4->CAMPO_TAMANHO = "";
$tpl4->CAMPO_VALOR = "R$ " . number_format($valortotal, 2, ",", ".");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->block("BLOCK_CAMPO_NORMAL");
$tpl4->block("BLOCK_CAMPO_DESABILITADO");
$tpl4->block("BLOCK_CAMPO");
$tpl4->block("BLOCK_CONTEUDO");
$tpl4->block("BLOCK_ITEM");

//Valor Pago
$tpl4->TITULO = "Valor Pago";
$tpl4->block("BLOCK_TITULO");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->CAMPO_TIPO = "text";
$tpl4->CAMPO_NOME = "valpago";
$tpl4->CAMPO_DICA = "";
$tpl4->CAMPO_ID = "valpago";
$tpl4->CAMPO_TAMANHO = "";
$tpl4->CAMPO_VALOR = "R$ " . number_format($valorpago, 2, ",", ".");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->block("BLOCK_CAMPO_DESABILITADO");
$tpl4->block("BLOCK_CAMPO_NORMAL");
$tpl4->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl4->block("BLOCK_CAMPO");
$tpl4->block("BLOCK_CONTEUDO");
$tpl4->block("BLOCK_ITEM");

//Valor Pendente
$tpl4->TITULO = "Valor Pendente (Prox.)";
$tpl4->block("BLOCK_TITULO");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->CAMPO_TIPO = "text";
$tpl4->CAMPO_NOME = "valpen";
$tpl4->CAMPO_DICA = "";
$tpl4->CAMPO_ID = "valpen";
$tpl4->CAMPO_TAMANHO = "";
$tpl4->CAMPO_VALOR = "R$ " . number_format($valorpendente, 2, ",", ".");
$tpl4->CAMPO_QTD_CARACTERES = "";
$tpl4->block("BLOCK_CAMPO_DESABILITADO");
$tpl4->block("BLOCK_CAMPO_NORMAL");
$tpl4->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl4->block("BLOCK_CAMPO");
$tpl4->block("BLOCK_CONTEUDO");
$tpl4->block("BLOCK_ITEM");

//Bot�o Voltar
if ($operacao != 'imprimir') {
    $tpl4->block("BLOCK_BOTAO_VOLTAR");
    $tpl4->block("BLOCK_BOTOES");
}

$tpl4->show();

if ($operacao == "ver")
    include "rodape.php";
else if ($operacao == "imprimir") {
    //include "rodape2.php";
} else {
    echo "Erro Grave";
    exit;
}
?>
