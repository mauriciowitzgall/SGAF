<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_cooperativa_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


include "includes.php";
$tipopagina = "cooperativas";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "COOPERATIVAS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "cooperativas.png";
$tpl_titulo->show();

//Pega todos os dados da tabela (Necessário caso seja uma edição ou visulização de detalhes)
$codigo = $_GET["codigo"];
$operacao = $_GET["operacao"];
$sql = "SELECT * FROM cooperativas WHERE coo_codigo='$codigo'";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $nome = $dados["coo_nomecompleto"];
    $abreviacao = $dados["coo_abreviacao"];
    $presidente = $dados["coo_presidente"];
}

//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "cooperativas_cadastrar2.php";
$tpl1->JS_CAMINHO = "cooperativas_cadastrar.js";
$tpl1->block("BLOCK_JS");

//Nome Completo
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->TITULO = "Nome Completo";
$tpl1->CAMPO_NOME = "nome";
$tpl1->CAMPO_TAMANHO = "55";
$tpl1->CAMPO_VALOR = $nome;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_NORMAL");
//$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO_FOCO");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl1->block("BLOCK_TITULO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Nome Abreviado
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->TITULO = "Nome Abreviado";
$tpl1->CAMPO_NOME = "abreviacao";
$tpl1->CAMPO_TAMANHO = "30";
$tpl1->CAMPO_VALOR = $abreviacao;
$tpl1->CAMPO_QTD_CARACTERES = 30;
//$tpl1->block("BLOCK_CAMPO_FOCO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
//$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl1->block("BLOCK_TITULO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

if ($presidente!="") {

    //Presidente
    $tpl1->TITULO = "Presidente";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->SELECT_NOME = "presidente";
    $tpl1->SELECT_TAMANHO = "";
    $tpl1->block("BLOCK_SELECT_NORMAL");
    $tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
    $sql = "SELECT * FROM pessoas JOIN mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo) WHERE mespestip_tipo=2 and pes_cooperativa=$codigo ORDER BY pes_nome";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $tpl1->OPTION_VALOR = $dados["pes_codigo"];
        $tpl1->OPTION_NOME = $dados["pes_nome"];
        if ($presidente == $dados["pes_codigo"]) {
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        }
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");
}


//BOTOES
if (($operacao == "editar") || ($operacao == "cadastrar")) {
    //Botão Salvar
    $tpl1->block("BLOCK_BOTAO_SALVAR");

    //Botão Cancelar
    if ($codigo != $usuario_codigo) {
        $tpl1->BOTAO_LINK = "cooperativas.php";
        $tpl1->block("BLOCK_BOTAO_CANCELAR");
    }
} else {
    //Botão Voltar
    $tpl1->block("BLOCK_BOTAO_VOLTAR");
}
$tpl1->block("BLOCK_BOTOES");



//Campos ocultos do formulario caso seja uma edição
if ($codigo != "") {
    //Codigo
    $tpl1->CAMPOOCULTO_NOME = "codigo";
    $tpl1->CAMPOOCULTO_VALOR = "$codigo";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");

    //Nome
    $tpl1->CAMPOOCULTO_NOME = "nomenobanco";
    $tpl1->CAMPOOCULTO_VALOR = "$nome";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");

    //Abreviacao
    $tpl1->CAMPOOCULTO_NOME = "abreviacaonobanco";
    $tpl1->CAMPOOCULTO_VALOR = "$abreviacao";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
}

$tpl1->show();


include "rodape.php";
?>
