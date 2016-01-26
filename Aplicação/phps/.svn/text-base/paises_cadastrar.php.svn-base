<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_paises_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "locais";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PAISES";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "locais.png";
$tpl_titulo->show();

//Pega todos os dados da tabela (Necessário caso seja uma edição)
$codigo=$_GET["codigo"];
$sql = "SELECT * FROM paises WHERE pai_codigo='$codigo'";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while  ($dados = mysql_fetch_array($query)) {
    $nome = $dados["pai_nome"];
    $pais = $dados["pai_pais"];
    $sigla = $dados["pai_sigla"];    
}

//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes.html");
$tpl1->LINK_DESTINO = "paises_cadastrar2.php";

//Campo Nome do paises
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->TITULO = "Nome";
$tpl1->ASTERISCO = "*";
$tpl1->CAMPO_NOME = "pais";
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
$tpl1->CAMPO_QTD_CARACTERES = "3";
$tpl1->CAMPO_NOME = "sigla";
$tpl1->CAMPO_TAMANHO = "8";
$tpl1->CAMPO_VALOR = $sigla;
$tpl1->CAMPO_DESABILITADO = "";
$tpl1->CAMPO_OBRIGATORIO = "required";
$tpl1->block("BLOCK_TITULO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_ITEM");

//Botões Inferiores 
$tpl1->LINK_CANCELAR = "paises.php";
$tpl1->CODIGO=$codigo;
$tpl1->block("BLOCK_BOTOES");

//Campos ocultos do formulario
$tpl1->CAMPOOCULTO_NOME="nomeregistrado";
$tpl1->CAMPOOCULTO_VALOR="$nome";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->show();


include "rodape.php";
?>
