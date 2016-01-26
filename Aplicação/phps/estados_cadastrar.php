<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_estados_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "locais";
include "includes.php";

//Template de TÃ­tulo e Sub-tÃ­tulo
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ESTADOS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "locais.png";
$tpl_titulo->show();

//Pega todos os dados da tabela (Necessário caso seja uma edição)
$codigo=$_GET["codigo"];
$sql = "SELECT * FROM estados WHERE est_codigo='$codigo'";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while  ($dados = mysql_fetch_array($query)) {
    $nome = $dados["est_nome"];
    $pais = $dados["est_pais"];
    $sigla = $dados["est_sigla"];    
}


//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes.html");
$tpl1->LINK_DESTINO = "estados_cadastrar2.php";

//Campo Nome do estado
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->TITULO = "Nome";
$tpl1->ASTERISCO = "*";
$tpl1->CAMPO_NOME = "estado";
$tpl1->CAMPO_TAMANHO = "35";
$tpl1->CAMPO_VALOR = $nome;
$tpl1->CAMPO_ID = "capitalizar";
$tpl1->CAMPO_ONKEYPRESS = "capitalize()";
$tpl1->CAMPO_DESABILITADO = "";
$tpl1->CAMPO_OBRIGATORIO = "required";
$tpl1->block("BLOCK_TITULO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_ITEM");

//Campo Sigla
$tpl1->TITULO = "Sigla";
$tpl1->ASTERISCO = "*";
$tpl1->CAMPO_QTD_CARACTERES = "2";
$tpl1->CAMPO_NOME = "sigla";
$tpl1->CAMPO_TAMANHO = "2";
$tpl1->CAMPO_VALOR = $sigla;
$tpl1->CAMPO_DESABILITADO = "";
$tpl1->CAMPO_OBRIGATORIO = "required";
$tpl1->block("BLOCK_TITULO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_ITEM");


//Select Pais
$tpl1->TITULO = "Pais";
$tpl1->ASTERISCO = "*";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "pais";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_DESABILITADO = "";
$tpl1->SELECT_OBRIGATORIO = " required ";
$sql = "SELECT * FROM paises ORDER BY pai_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["pai_codigo"];
    $tpl1->OPTION_NOME = $dados["pai_nome"];
    if ($pais == $dados["pai_codigo"]) {
        $tpl1->OPTION_SELECIONADO = " selected ";
    } else {
        $tpl1->OPTION_SELECIONADO = " ";
    }
    $tpl1->block("BLOCK_SELECT_OPTION");
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_ITEM");

//BotÃµes Inferiores 
$tpl1->LINK_CANCELAR = "estados.php";
$tpl1->CODIGO=$codigo;
$tpl1->NOME=$nome;
$tpl1->block("BLOCK_BOTOES");
$tpl1->show();


include "rodape.php";
?>
