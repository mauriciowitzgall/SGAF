<?php
$tipopagina = "pessoas";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_pessoas_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

$pessoa=$_REQUEST["pessoa"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PESSOAS  ";
$tpl_titulo->SUBTITULO = "GRUPOS DE CONSUMIDORES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "grupoconsumidores.png";
$tpl_titulo->show();



if ($usagrupoconsumidores!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR USO DE GRUPOS DE CONSUMIDORES</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

$tpl = new Template("templates/listagem_2.html");



//Consumidor
$sql="SELECT * FROM pessoas WHERE pes_codigo=$pessoa";
$query = mysql_query($sql);
if (!$query)  die("Erro Nome Consumidor" . mysql_error());
$dados=mysql_fetch_assoc($query);
$tpl->CAMPO_TITULO = "Consumidor";
$tpl->CAMPO_VALOR = $dados['pes_nome'];
$tpl->CAMPO_TAMANHO = "20";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Botão Cadastrar
if ($usuario_grupo<>4) {
    $tpl->LINK="pessoas_grupoconsumidores_cadastrar.php?pessoa=$pessoa";
    $tpl->LINK_COMPLEMENTO="";
    $tpl->BOTAO_NOME="CADASTRAR";
    $tpl->block("BLOCK_AUTOFOCO");
    //$tpl->block("BLOCK_RODAPE_BOTAO_MODELO_DESABILITADO");
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
    $tpl->block("BLOCK_FILTRO");
}

//GRUPO
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="GRUPO";
$tpl->block("BLOCK_LISTA_CABECALHO");


//Operacoes
$tpl->CABECALHO_COLUNA_TAMANHO="80px";
$tpl->CABECALHO_COLUNA_COLSPAN="1";
$tpl->CABECALHO_COLUNA_NOME="OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");



$sql="SELECT * FROM pessoas_grupoconsumidores join consumidores_grupos on (congru_codigo=pesgrucon_grupo) WHERE pesgrucon_pessoa=$pessoa order by congru_nome";

//Paginação
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se � a primeira vez que acessa a pagina ent�o come�ar na pagina 1
if (($paginaatual == "") || ($paginas < $paginaatual) || ($paginaatual <= 0)) {
    $paginaatual = 1;
}
$comeco = ($paginaatual - 1) * $por_pagina;
$tpl->PAGINAS = "$paginas";
$tpl->PAGINAATUAL = "$paginaatual";
$tpl->PASTA_ICONES = "$icones";
$tpl->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";


while ($dados=  mysql_fetch_assoc($query)) {
    $grupo_codigo= $dados["congru_codigo"];
    $grupo_nome= $dados["congru_nome"];    
    
        
    //Grupo
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR="$grupo_nome";
    $tpl->block("BLOCK_LISTA_COLUNA");
           

    //Excluir
    $tpl->LINK="pessoas_grupoconsumidores_deletar.php";
    $tpl->CODIGO="$pessoa";
    $tpl->LINK_COMPLEMENTO="operacao=excluir&pessoa=$pessoa&grupo=$grupo_codigo";
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