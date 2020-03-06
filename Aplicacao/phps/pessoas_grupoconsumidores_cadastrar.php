<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_pessoas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "pessoas";
include "includes.php";

$operacao=$_GET["operacao"];
$codigo=$_GET["codigo"];
$pessoa = $_REQUEST['pessoa'];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PESSOAS  ";
$tpl_titulo->SUBTITULO = "VINCULAR GRUPOS DE CONSUMIDORES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "grupoconsumidores.png";
$tpl_titulo->show();


if ($usagrupoconsumidores!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR USO DE GRUPO DE CONSUMIDORES</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

$tpl->LINK_DESTINO="pessoas_grupoconsumidores_cadastrar2.php?pessoa=$pessoa";
$tpl->LINK_TARGET="";

//Grupo
$tpl->TITULO="Grupo";
$tpl->block("BLOCK_TITULO");
$tpl->SELECT_NOME="grupo";
$tpl->SELECT_ID="";
$tpl->SELECT_TAMANHO="";
$tpl->SELECT_ONCHANGE="";
$tpl->block("BLOCK_SELECT_ONCHANGE"); 
$tpl->block("BLOCK_SELECT_NORMAL"); 
$tpl->block("BLOCK_SELECT_OBRIGATORIO");
$sql="SELECT * FROM consumidores_grupos ORDER BY congru_nome DESC";
if (!$query=mysql_query($sql)) die("Erro SQL 3: " . mysql_error());
$tpl->block("BLOCK_SELECT_OPTION_PADRAO"); //Selecione
while ($dados = mysql_fetch_assoc($query)) {
    $tpl->OPTION_VALOR=$dados["congru_codigo"];    
    $tpl->OPTION_NOME=$dados["congru_nome"];
    $tpl->block("BLOCK_SELECT_OPTION");
}
$tpl->block("BLOCK_SELECT");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


if ($operacao=="ver") {
    $tpl->block("BLOCK_BOTAO_VOLTAR");

} else {
    
    //Botão Salvar
    $tpl->block("BLOCK_BOTAO_SALVAR");

    //Botão Cancelar
    $tpl->BOTAO_LINK="pessoas_grupoconsumidores.php?pessoa=$pessoa";
    $tpl->block("BLOCK_BOTAO_CANCELAR");
} 

$tpl->block("BLOCK_BOTOES");

$tpl->show();
include "rodape.php";
?>
