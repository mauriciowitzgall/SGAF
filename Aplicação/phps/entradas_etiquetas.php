<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_entradas_etiquetas <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

//Verifica se a pagina deve ser redirecionada para a pagina da etiqueta escolhida
if (isset($_POST) && isset($_POST["modeloetiqueta"])) {
    $lote = $_POST["lote"];
    $numero = $_POST["numero"];
    $qtd_etiquetas = $_POST["qtd_etiquetas"];
    $modeloetiqueta = $_POST["modeloetiqueta"];
    //echo "lote: $lote numero:$numero qtd_etiquetas: $qtd_etiquetas modeloetiquetas: $modeloetiqueta";    
    switch ($_POST["modeloetiqueta"]) {
        case "1":
            echo "<script>window.location = 'entradas_etiquetinha.php?lote=$lote&numero=$numero&qtd_etiquetas=$qtd_etiquetas';</script>";
            break;
        case "2":
            echo "<script>window.location = 'entradas_etiquetao.php?lote=$lote&numero=$numero&qtd_etiquetas=$qtd_etiquetas';</script>";
            break;
        case "3":
            echo "<script>window.location = 'entradas_etiquetagranel.php?lote=$lote&numero=$numero&qtd_etiquetas=$qtd_etiquetas';</script>";
            break;
        default:
            echo "<script>window.location = 'inicio.php';</script>";
            break;
    }
}

$tipopagina = "entradas";
include "includes2.php";

$lote = $_GET["lote"];
$numero = $_GET["numero"];

$sql = "
    SELECT 
        pes_nome,pro_nome,entpro_quantidade,protip_sigla,protip_codigo
    FROM 
        entradas 
        join entradas_produtos on (ent_codigo=entpro_entrada) 
        join pessoas on (ent_fornecedor=pes_codigo)
        join produtos on (pro_codigo=entpro_produto)
        join produtos_tipo on (protip_codigo=pro_tipocontagem)
    WHERE 
        entpro_entrada=$lote and
        entpro_numero=$numero        
";
$query = mysql_query($sql);
if (!$query)
    die("Erro1:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $fornecedor_nome = $dados["pes_nome"];
    $produto_nome = $dados["pro_nome"];
    $qtd = $dados["entpro_quantidade"];
    $tipo_contagem = $dados["protip_codigo"];
    $sigla = $dados["protip_sigla"];
}

$data = desconverte_data($_POST["data"]);
$hora = $_POST["hora"];

$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ETIQUETAS";
$tpl_titulo->SUBTITULO = "IMPRIMIR ETIQUETAS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "etiquetas.png";
$tpl_titulo->show();


$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "entradas_etiquetas.php";
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

//Produto
$tpl1->TITULO = "Produto";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "produto";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = $produto_nome;
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Quantidade
$tpl1->TITULO = "Quantidade";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "quantidade";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = $qtd . " " . $sigla;
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Etiqueta
$tpl1->TITULO = "Modelo de Etiqueta: ";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "modeloetiqueta";
$tpl1->SELECT_ID = "modeloetiqueta";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->OPTION_VALOR = "1";
$tpl1->OPTION_NOME = "Pequena";
$tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->OPTION_VALOR = "2";
$tpl1->OPTION_NOME = "Grande";
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->OPTION_VALOR = "3";
$tpl1->OPTION_NOME = "Granel";
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Quantidade de Etiquetas
$tpl1->TITULO = "Quantas etiquetas?";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "number";
$tpl1->CAMPO_NOME = "qtd_etiquetas";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
if ($tipo_contagem == 1) { //ser for por por 'unidade'
    $qtd_etiquetas = $qtd;
} else {
    $qtd_etiquetas = 1;
}
$tpl1->CAMPO_VALOR = $qtd_etiquetas;
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

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





$tpl1->show();

//include "rodape2.php";
?>
