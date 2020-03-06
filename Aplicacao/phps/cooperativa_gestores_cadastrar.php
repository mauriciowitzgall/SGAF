<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_cooperativa_gestores_gerir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativa";
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "GESTORES";
$tpl_titulo->SUBTITULO = "LISTA DE GESTORES DA COOPERATIVA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "cooperativa_gestores.png";
$tpl_titulo->show();

$gestor = $_GET['codigo'];
$operacao = $_GET['operacao'];
$cooperativa=$usuario_cooperativa;
    
//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "cooperativa_gestores_cadastrar2.php";

//Cooperativa
$tpl1->TITULO = "Cooperativa";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "cooperativa";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$usuario_cooperativaabreviacao";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");


//Gestor
$tpl1->TITULO = "Gestor";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "gestor";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "gestor";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
if ($operacao=="editar") {
    $sql_filtro = " OR pes_codigo = $gestor ";
}
$sql = "
SELECT DISTINCT
    pes_codigo,pes_nome
FROM
    pessoas
    join mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)
WHERE
    mespestip_tipo=2
    and pes_cooperativa=$cooperativa
    and pes_codigo not in (SELECT cooges_gestor FROM cooperativa_gestores WHERE cooges_cooperativa=$cooperativa) 
    $sql_filtro
ORDER BY
    pes_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: 5" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["pes_codigo"];
    $tpl1->OPTION_NOME = $dados["pes_nome"];
    if ($gestor == $dados["pes_codigo"]) {
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    }
    $tpl1->block("BLOCK_SELECT_OPTION");
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

$tpl1->CAMPOOCULTO_VALOR=$operacao;
$tpl1->CAMPOOCULTO_NOME="operacao";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//BOTOES
if (($operacao == "editar") || ($operacao == "cadastrar")) {
    //Botão Salvar
    $tpl1->block("BLOCK_BOTAO_SALVAR");

    //Botão Cancelar
    if ($codigo != $usuario_codigo) {
        $tpl1->BOTAO_LINK = "cooperativa_gestores.php";
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
