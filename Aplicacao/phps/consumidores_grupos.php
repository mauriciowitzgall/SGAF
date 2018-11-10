<?php
$tipopagina = "pessoas";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_consumidores_grupos_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "GRUPOS DE CONSUMIDORES";
$tpl_titulo->SUBTITULO = "LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones2";
$tpl_titulo->NOME_ARQUIVO_ICONE = "consumidor.png";
$tpl_titulo->show();


$tpl = new Template("templates/listagem_2.html");


//Botão Cadastrar
if ($usuario_grupo<>4) {
    $tpl->LINK="consumidores_grupos_cadastrar.php?operacao=cadastrar";
    $tpl->BOTAO_NOME="CADASTRAR";
    $tpl->block("BLOCK_AUTOFOCO");
    //$tpl->block("BLOCK_RODAPE_BOTAO_MODELO_DESABILITADO");
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
    $tpl->block("BLOCK_FILTRO");
}

//Codigo
$tpl->CABECALHO_COLUNA_TAMANHO="100px";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="Nº";
$tpl->block("BLOCK_LISTA_CABECALHO");


//Nome
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="NOME";
$tpl->block("BLOCK_LISTA_CABECALHO");


//Operacoes
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="2";
$tpl->CABECALHO_COLUNA_NOME="OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");


$sql="SELECT * FROM consumidores_grupos WHERE 1 $sql_filtro order by congru_codigo";

$query = mysql_query($sql);
while ($dados=  mysql_fetch_assoc($query)) {
    $codigo= $dados["congru_codigo"];
    $nome= $dados["congru_nome"];    
    
    //Codigo
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR="$codigo";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Nome
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR="$nome";
    $tpl->block("BLOCK_LISTA_COLUNA");
    

    //Editar
    $tpl->LINK="consumidores_grupos_cadastrar.php";
    $tpl->CODIGO="$codigo";
    $tpl->LINK_COMPLEMENTO="operacao=editar";
    $tpl->ICONE_ARQUIVO="$icones";
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR");
    

    //Excluir
    $tpl->LINK="consumidores_grupos_deletar.php";
    $tpl->CODIGO="$codigo";
    $tpl->LINK_COMPLEMENTO="operacao=excluir";
    $tpl->ICONE_ARQUIVO="$icones";
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR");
    $tpl->ICONE_ARQUIVO="";
    
            
    $tpl->block("BLOCK_LISTA"); 
}
if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}


$tpl->show();

include "rodape.php";

?>