<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_taxas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "taxas";
include "includes.php";
$operacao = $_GET["operacao"];
if (empty($operacao))
    $operacao = "cadastrar";



//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TAXAS";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "taxas.png";
$tpl_titulo->show();



//FORMUL�RIO
$tpl = new Template("templates/cadastro1.html");

$codigo = $_GET["codigo"];
if (!empty($codigo)) {
    $sql = "SELECT tax_codigo,tax_nome,tax_descricao,tax_tiponegociacao,tax_quiosque FROM taxas WHERE tax_codigo=$codigo";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro SQL Leitura Editar:" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $taxanome = $dados["tax_nome"];
    $descricao = $dados["tax_descricao"];
    $tiponegociacao = $dados["tax_tiponegociacao"];
    $quiosque = $dados["tax_quiosque"];
}

$tpl->FORM_NOME = "";
$tpl->FORM_TARGET = "";
$tpl->FORM_LINK = "taxas_cadastrar2.php";
$tpl->block("BLOCK_FORM");

//Taxa
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "150px";
$tpl->TITULO = "Nome da Taxa";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "taxanome";
$tpl->CAMPO_VALOR = "$taxanome";
$tpl->CAMPO_TAMANHO = "30";
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_AUTOFOCO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Descri��o
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "";
$tpl->TITULO = "Descrição";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->TEXTAREA_QTDCARACTERES = "";
$tpl->TEXTAREA_NOME = "descricao";
$tpl->TEXTAREA_TAMANHO = "50";
$tpl->TEXTAREA_TEXTO = "$descricao";
$tpl->block("BLOCK_TEXTAREA_PADRAO");
$tpl->block("BLOCK_TEXTAREA");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Observação
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "";
$tpl->TITULO = "Observação";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->TEXTO_NOME = "Observação";
//$tpl->_ID="";
//$tpl->TEXTO_CLASSE="";
$tpl->TEXTO_VALOR = "
    Aqui é apenas o cadastro da taxa, para incluir uma taxa em um ponto de venda vá a tela de 'Quiosques'! <br>
    Os supervisores podem cadastrar, editar e excluir apenas taxas referentes ao quiosque que supervisionam!<br>
    Os presidentes podem cadastrar, editar e excluir taxas para todos os pontos de venda da cooperativa/grupo!
";
$tpl->block("BLOCK_TEXTO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Quiosques
if ($usuario_grupo != 3) {
    $tpl->COLUNA_ALINHAMENTO = "right";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->TITULO = "Quiosque(s)";
    $tpl->block("BLOCK_TITULO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->COLUNA_ALINHAMENTO = "";
    $tpl->COLUNA_TAMANHO = "";
    $tpl->SELECT_NOME = "quiosque";
    $tpl->SELECT_ID = "quiosque";
    $tpl->SELECT_TAMANHO = "";
//$tpl->SELECT_AOCLICAR="";
//$tpl->block("BLOCK_SELECT_AUTOFOCO");
//$tpl->block("BLOCK_SELECT_DESABILITADO");
    $tpl->block("BLOCK_SELECT_OBRIGATORIO");
    $tpl->block("BLOCK_SELECT_PADRAO");
    if (($usuario_grupo == 2) || ($usuario_grupo == 1)) {
        $tpl->OPTION_VALOR = " ";
        $tpl->OPTION_TEXTO = "Todos os quiosques";
        $tpl->block("BLOCK_OPTION_SELECIONADO");
        $tpl->block("BLOCK_OPTION");
    }
    if (($usuario_grupo == 1)) {
        $sql = " 
        SELECT qui_codigo,qui_nome
        FROM quiosques
        WHERE qui_cooperativa=$usuario_cooperativa
        ORDER BY qui_nome
    ";
        $tpl->SELECT_AOTROCAR = "popula_quiosque_tiponegociacao(this.value)";
    } else {
        $sql = " 
        SELECT qui_codigo,qui_nome
        FROM quiosques
        WHERE qui_codigo=$usuario_quiosque
        ORDER BY qui_nome
    ";
        $tpl->SELECT_AOTROCAR = "";
    }
    if ($usuario_grupo != 2) {

//$tpl->block("BLOCK_SELECT_DINAMICO");
//$tpl->block("BLOCK_OPTION_PADRAO");
//$tpl->block("BLOCK_OPTION_TODOS");
        $query = mysql_query($sql);
        if (!$query)
            die("Erro SQL QUIOSQUE:" . mysql_error());
        while ($dados = mysql_fetch_array($query)) {
            $tpl->OPTION_VALOR = $dados[0];
            if ($codigotaxa == $dados[0])
                $tpl->block("BLOCK_OPTION_SELECIONADO");
            $tpl->OPTION_TEXTO = $dados[1];
            $tpl->block("BLOCK_OPTION");
        }
    }
    $tpl->block("BLOCK_SELECT");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
}



//Tipo de negociação
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "";
$tpl->TITULO = "Tipo de Negociação";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->SELECT_NOME = "tiponegociacao";
$tpl->SELECT_ID = "tiponegociacao";
$tpl->SELECT_TAMANHO = "";
$tpl->SELECT_AOTROCAR = "";
//$tpl->SELECT_AOCLICAR="";
//$tpl->block("BLOCK_SELECT_AUTOFOCO");
//$tpl->block("BLOCK_SELECT_DESABILITADO");
$tpl->block("BLOCK_SELECT_OBRIGATORIO");
$tpl->block("BLOCK_SELECT_PADRAO");
if (($usuario_grupo == 2) || ($usuario_grupo == 1)) {
    $sql = " SELECT * FROM tipo_negociacao ORDER BY tipneg_nome";
} else {
    $sql = "
        SELECT * 
        FROM tipo_negociacao
        JOIN quiosques_tiponegociacao ON ( quitipneg_tipo = tipneg_codigo ) 
        WHERE quitipneg_quiosque =1
        ORDER BY tipneg_nome
    ";
}
//$tpl->block("BLOCK_SELECT_DINAMICO");
$tpl->block("BLOCK_OPTION_PADRAO");
//$tpl->block("BLOCK_OPTION_TODOS");
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL QUIOSQUE:" . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $tpl->OPTION_VALOR = $dados[0];
    if ($tiponegociacao == $dados[0])
        $tpl->block("BLOCK_OPTION_SELECIONADO");
    $tpl->OPTION_TEXTO = $dados[1];
    $tpl->block("BLOCK_OPTION");
}
$tpl->block("BLOCK_SELECT");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");




//Campo Oculto Operação
$tpl->CAMPOOCULTO_NOME = "operacao";
$tpl->CAMPOOCULTO_VALOR = "$operacao";
$tpl->block("BLOCK_CAMPOOCULTO");

//Campo Código
$tpl->CAMPOOCULTO_NOME = "codigo";
$tpl->CAMPOOCULTO_VALOR = "$codigo";
$tpl->block("BLOCK_CAMPOOCULTO");

$tpl->show();

$tpl2 = new Template("templates/botoes1.html");
$tpl2->block("BLOCK_LINHAHORIZONTAL_EMCIMA");

//Salvar
$tpl2->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl2->block("BLOCK_BOTAOPADRAO_SALVAR");
$tpl2->block("BLOCK_BOTAOPADRAO");
$tpl->block("BLOCK_CONTEUDO");
$tpl2->block("BLOCK_COLUNA");

//Cancelar
$tpl2->COLUNA_LINK_ARQUIVO = "taxas.php";
$tpl2->block("BLOCK_COLUNA_LINK");
$tpl2->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl2->block("BLOCK_BOTAOPADRAO_CANCELAR");
$tpl2->block("BLOCK_BOTAOPADRAO");
$tpl->block("BLOCK_CONTEUDO");
$tpl2->block("BLOCK_COLUNA");

$tpl2->block("BLOCK_LINHA");
$tpl2->block("BLOCK_BOTOES");

$tpl2->show();
?>
