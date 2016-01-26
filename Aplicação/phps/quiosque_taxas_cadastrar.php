<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_taxas_aplicar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "quiosques";
include "includes.php";
include "controller/classes.php";


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TAXAS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO DE TAXAS DO QUIOSQUE";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques_taxas.png";
$tpl_titulo->show();

//Verifica se há taxas cadastradas
if (($usuario_grupo == 1) || ($usuario_grupo == 2))
    $sql = "SELECT tax_codigo FROM taxas WHERE tax_cooperativa=$usuario_cooperativa";
else
    $sql = "SELECT tax_codigo FROM taxas WHERE tax_quiosque=$usuario_quiosque";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas == 0) {
    echo "<br><br>";
    $tpl = new Template("templates/notificacao.html");
    $tpl->ICONES = $icones;
    $tpl->MOTIVO_COMPLEMENTO = "Para vincular uma taxa a um quiosque é necessário ter cadastrado alguma taxa! <b>Você não possui nenhuma taxa cadastrada!</b><br>Clique no botão abaixo para ir para a tela que cadastra taxas!";
    $tpl->block("BLOCK_ATENCAO");
    $tpl->DESTINO = "taxas_cadastrar.php?operacao=cadastrar";
    $tpl->block("BLOCK_BOTAO");
    $tpl->show();
    exit;
}

//Pega todos os dados da tabela (Necessário caso seja uma edição)
$taxa = $_GET['taxa'];
$quiosque = $_GET['qui'];
$operacao = $_GET['ope'];

//Pega os tipos de negociação do quiosque
$obj = new banco();
$sql11 = "SELECT quitipneg_tipo FROM quiosques_tiponegociacao WHERE quitipneg_quiosque=$quiosque";
$query11 = mysql_query($sql11);
$quiosque2_consignacao=0;
$quiosque2_revenda=0;
while ($dados11 = mysql_fetch_array($query11)) {
    $tipon = $dados11[0];
    if ($tipon == 1)
        $quiosque2_consignacao = 1;
    IF ($tipon == 2)
        $quiosque2_revenda = 1;
}


$sql = "SELECT qui_cooperativa,qui_nome FROM quiosques WHERE qui_codigo=$quiosque";
$query = mysql_query($sql);
if (!$query)
    die("Erro1: " . mysql_error());
$array = mysql_fetch_assoc($query);
$quiosque_nome = $array["qui_nome"];

//Pega todos dados necessários quando é edição ou ver
if ($operacao != 1) {
    $sql = "SELECT * FROM quiosques_taxas JOIN taxas on (tax_codigo=quitax_taxa) WHERE quitax_taxa='$taxa' AND quitax_quiosque=$quiosque";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro2:" . mysql_error());
    $array = mysql_fetch_assoc($query);
    $taxavalor = number_format($array['quitax_valor'], 2, ',', '');
    $tiponegociacao = $array['tax_tiponegociacao'];
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

//Tipo de negociacao
$tpl1->TITULO = "Tipo de Negociação";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "tiponegociacao";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "tiponegociacao";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_ESTILO = "width:150px;";
$tpl1->block("BLOCK_SELECT_ESTILO");
$tpl1->SELECT_ONCHANGE = "quiosque_taxas_popula_taxa(this.value);";
$tpl1->block("BLOCK_SELECT_ONCHANGE");
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
if ($operacao!=1)
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
if (($quiosque2_consignacao == 1) && ($quiosque2_revenda == 1))
    $filtro_tipos.="1,2";
if (($quiosque2_consignacao == 1) && ($quiosque2_revenda == 0))
    $filtro_tipos.="1";
if (($quiosque2_consignacao == 0) && ($quiosque2_revenda == 1))
    $filtro_tipos.="2";
if ($usuario_grupo == 3)
    $sql_filtro.= " AND tax_quiosque=$usuario_quiosque";
$sql = "
SELECT DISTINCT tipneg_codigo,tipneg_nome
FROM tipo_negociacao
JOIN taxas on (tax_tiponegociacao=tipneg_codigo)
WHERE tax_cooperativa=$usuario_cooperativa
AND tax_tiponegociacao IN ($filtro_tipos)
$sql_filtro
ORDER BY tipneg_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: 5" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["tipneg_codigo"];
    $tpl1->OPTION_NOME = $dados["tipneg_nome"];
    if ($tiponegociacao == $dados["tipneg_codigo"]) {
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    }
    $tpl1->block("BLOCK_SELECT_OPTION");
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Taxa
$tpl1->TITULO = "Taxa";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "taxa";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "taxa";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_ESTILO = "width:150px;";
$tpl1->block("BLOCK_SELECT_ESTILO");
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
//$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
if ($operacao!=1)
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
if ($operacao == 2) {
    $sql = "
    SELECT DISTINCT tax_codigo,tax_nome
    FROM taxas
    WHERE tax_cooperativa=$usuario_cooperativa
    AND tax_quiosque IN (0,$quiosque)    
    AND tax_tiponegociacao=$tiponegociacao
    ORDER BY tax_nome";
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
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Valor Referencia da Taxa (%)
$tpl1->TITULO = "Porcentagem";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "6";
$tpl1->CAMPO_NOME = "taxavalor";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "qtd";
$tpl1->CAMPO_TAMANHO = "4";
$tpl1->CAMPO_VALOR = $taxavalor;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->CAMPO_ONKEYUP = "mascara_quantidade()";
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO");
$tpl1->TEXTO_ID="permax";
if ($operacao == 2) {
    $sql2 = "
    SELECT sum(quitax_valor) 
    FROM quiosques_taxas 
    JOIN taxas on (quitax_taxa=tax_codigo)
    WHERE quitax_quiosque=$quiosque
    AND tax_tiponegociacao=$tiponegociacao
";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: 15" . mysql_error());
    $dados2 = mysql_fetch_array($query2);
    $percentual_max = 100 - $dados2[0] + $taxavalor;
    $tpl1->TEXTO = "% (Máximo $percentual_max %)";
    $tpl1->block("BLOCK_TEXTO");
} else {
    $tpl1->TEXTO = "%";
    $tpl1->block("BLOCK_TEXTO");
}
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

$tpl1->CAMPOOCULTO_VALOR = $quiosque;
$tpl1->CAMPOOCULTO_NOME = "quiosque2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->CAMPOOCULTO_VALOR = $taxavalor;
$tpl1->CAMPOOCULTO_NOME = "percent";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->CAMPOOCULTO_VALOR = $taxa;
$tpl1->CAMPOOCULTO_NOME = "taxa";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->CAMPOOCULTO_VALOR = $operacao;
$tpl1->CAMPOOCULTO_NOME = "operacao";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//BOTOES
if (($operacao == "2") || ($operacao == "1")) {
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
