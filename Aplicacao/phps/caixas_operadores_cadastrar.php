<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operadores_gerir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "OPERADORES DE CAIXA";
$tpl_titulo->SUBTITULO = "CADASTRO OPERADORES DE CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixa_operadores.png";
$tpl_titulo->show();


$usacaixa=usamodulocaixa($usuario_quiosque);
if ($usacaixa!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}


//Pega todos os dados da tabela (Necessário caso seja uma edição)
$caixa = $_GET['caixa'];
$operador = $_GET['operador'];
$operacao = $_GET['operacao'];
$dataatual= date ("Y-m-d");

$sql = "SELECT cai_nome FROM caixas WHERE cai_codigo=$caixa";
$query = mysql_query($sql);
if (!$query)
    die("Erro1: " . mysql_error());
$array = mysql_fetch_assoc($query); 
$caixa_nome=$array["cai_nome"];

if ($operador != "") {
    
    $sql = "SELECT * FROM caixas_operadores WHERE caiope_operador='$operador'";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro 2:" . mysql_error());
    $array = mysql_fetch_assoc($query); 
    $datafuncao=  $array['caiope_datafuncao'];
}

//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "caixas_operadores_cadastrar2.php";

//Caixa
$tpl1->TITULO = "Caixa";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "caixa";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$caixa_nome";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Operador
$tpl1->TITULO = "Operador";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "operador";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "operador";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
if ($operacao<>"editar")
    $sql_filtro=$sql_filtro." and pes_codigo not in (SELECT caiope_operador FROM caixas_operadores WHERE caiope_caixa=$caixa)";

$sql = "
SELECT DISTINCT
    pes_codigo,pes_nome
FROM
    pessoas
    join mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)
WHERE
    mespestip_tipo=4 and
    pes_cooperativa=$usuario_cooperativa
    $sql_filtro 
ORDER BY
    pes_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: 5" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["pes_codigo"];
    $tpl1->OPTION_NOME = $dados["pes_nome"];
    if ($operador == $dados["pes_codigo"]) {
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    }
    $tpl1->block("BLOCK_SELECT_OPTION");
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Data função
$tpl1->TITULO = "Data Função";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "date";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "datafuncao";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$datafuncao2=  explode(" ", $datafuncao);
$datafuncao2= $datafuncao2[0];
$tpl1->CAMPO_VALOR = "$datafuncao2";
$tpl1->CAMPO_ESTILO="width:140px";
$tpl1->block("BLOCK_CAMPO_ESTILO");
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

$tpl1->CAMPOOCULTO_VALOR=$caixa;
$tpl1->CAMPOOCULTO_NOME="caixa2";
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
        $tpl1->BOTAO_LINK = "caixas_operadores.php?caixa=$caixa";
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
