<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_taxas_aplicar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativas";
include "includes.php";


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TAXAS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO DE TAXAS DO QUIOSQUE";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "taxas.png";
$tpl_titulo->show();

//Pega todos os dados da tabela (Necessário caso seja uma edição)
$taxa = $_GET['taxa'];
$quiosque = $_GET['quiosque'];
$operacao = $_GET['operacao'];

$sql = "SELECT qui_cooperativa,qui_nome,qui_taxaadm FROM quiosques WHERE qui_codigo=$quiosque";
$query = mysql_query($sql);
if (!$query)
    die("Erro1: " . mysql_error());
$array = mysql_fetch_assoc($query); 
$coo=$array["qui_cooperativa"];
$quiosque_nome=$array["qui_nome"];
$taxaadm=$array["qui_taxaadm"];

if ($taxa != "") {
    
    $sql = "SELECT * FROM quiosques_taxas WHERE quitax_taxa='$taxa' AND quitax_quiosque=$quiosque";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro2:" . mysql_error());
    $array = mysql_fetch_assoc($query); 
    $taxavalor= number_format($array['quitax_valor'],2,',','');
    
    
    ;
}

//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "quiosque_taxas_cadastrar2.php";

//Quiosque
$tpl1->TITULO = "Quiosque";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "quiosque";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$quiosque_nome";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Taxa
$tpl1->TITULO = "Taxa";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "taxa";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "taxa";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
$sql = "
SELECT DISTINCT
    tax_codigo,tax_nome
FROM
    taxas
WHERE
    tax_cooperativa=$coo
ORDER BY
    tax_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: 5" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["tax_codigo"];
    $tpl1->OPTION_NOME = $dados["tax_nome"];
    if ($taxa == $dados["tax_codigo"]) {
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    }
    $tpl1->block("BLOCK_SELECT_OPTION");
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Taxa Administrativa
$tpl1->TITULO = "Taxa Administrativa.";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "6";
$tpl1->CAMPO_NOME = "taxaadm";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "qtd";
$tpl1->CAMPO_TAMANHO = "8";
$tpl1->CAMPO_VALOR = $taxaadm;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->CAMPO_ONKEYUP = "mascara_quantidade()";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->TEXTO="%";
$tpl1->block("BLOCK_TEXTO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Valor Referencia da Taxa (%)
$tpl1->TITULO = "Valor/Ref.";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "6";
$tpl1->CAMPO_NOME = "taxavalor";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "qtd";
$tpl1->CAMPO_TAMANHO = "8";
$tpl1->CAMPO_VALOR = $taxavalor;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->CAMPO_ONKEYUP = "mascara_quantidade()";
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO");
$tpl1->TEXTO="%";
$tpl1->block("BLOCK_TEXTO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

$tpl1->CAMPOOCULTO_VALOR=$quiosque;
$tpl1->CAMPOOCULTO_NOME="quiosque";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->CAMPOOCULTO_VALOR=$operacao;
$tpl1->CAMPOOCULTO_NOME="operacao";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//BOTOES
if (($operacao == "editar") || ($operacao == "cadastrar")) {
    //Botão Salvar
    $tpl1->block("BLOCK_BOTAO_SALVAR");

    //Botão Cancelar
    if ($codigo != $usuario_codigo) {
        $tpl1->BOTAO_LINK = "quiosque_taxas.php?quiosque=$quiosque";
        $tpl1->block("BLOCK_BOTAO_CANCELAR");
    }
    
} else {
    //Botão Voltar
    $tpl1->block("BLOCK_BOTAO_VOLTAR");
}
$tpl1->block("BLOCK_BOTOES");

$tpl1->show();

include "rodape.php";
?>
