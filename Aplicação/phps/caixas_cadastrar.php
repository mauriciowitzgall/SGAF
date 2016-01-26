<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_quiosque_definircaixas <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativas";
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "CADASTRO DE CAIXAS DO QUIOSQUE";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "../pessoas2/caixa.png";
$tpl_titulo->show();

//Pega todos os dados da tabela (Necess�rio caso seja uma edi��o)
$caixa = $_GET['codigo'];
$quiosque = $_GET['quiosque'];
$operacao = $_GET['operacao'];

$sql = "SELECT qui_cooperativa,qui_nome FROM quiosques WHERE qui_codigo=$quiosque";
$query = mysql_query($sql);
if (!$query)
    die("Erro1: " . mysql_error());
$array = mysql_fetch_assoc($query); 
$coo=$array["qui_cooperativa"];
$quiosque_nome=$array["qui_nome"];

if ($caixa != "") {
    
    $sql = "SELECT * FROM quiosques_caixas WHERE quicai_caixa='$caixa'";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro2:" . mysql_error());
    $array = mysql_fetch_assoc($query); 
    $datafuncao=  converte_data($array['quicai_datafuncao']);
}

//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "caixas_cadastrar2.php";

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





//caixa
$tpl1->TITULO = "Caixa";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "caixa";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "caixa";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
$sql = "
SELECT DISTINCT
    pes_codigo,pes_nome
FROM
    pessoas
    join mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)
WHERE
    mespestip_tipo=4 and
    pes_cooperativa=$coo
ORDER BY
    pes_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: 5" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["pes_codigo"];
    $tpl1->OPTION_NOME = $dados["pes_nome"];
    if ($caixa == $dados["pes_codigo"]) {
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    }
    $tpl1->block("BLOCK_SELECT_OPTION");
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Data fun��o
$tpl1->TITULO = "Data Função";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "datafuncao";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "calendario";
$tpl1->CAMPO_TAMANHO = "8";
$tpl1->CAMPO_VALOR = $datafuncao;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO");
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
    //Bot�o Salvar
    $tpl1->block("BLOCK_BOTAO_SALVAR");

    //Bot�o Cancelar
    if ($codigo != $usuario_codigo) {
        $tpl1->BOTAO_LINK = "caixas.php?quiosque=$quiosque";
        $tpl1->block("BLOCK_BOTAO_CANCELAR");
    }
    
} else {
    //Bot�o Voltar
    $tpl1->block("BLOCK_BOTAO_VOLTAR");
}
$tpl1->block("BLOCK_BOTOES");

$tpl1->show();

include "rodape.php";
?>
