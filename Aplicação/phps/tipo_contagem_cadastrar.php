<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_tipocontagem_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "produtos";
include "includes.php";

//Template de TÃ­tulo e Sub-tÃ­tulo
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TIPO DE CONTAGEM";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos.png";
$tpl_titulo->show();

//Pega todos os dados da tabela (Necessário caso seja uma edição)
$codigo=$_GET["codigo"];
$sql = "SELECT * FROM produtos_tipo WHERE protip_codigo='$codigo'";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while  ($dados = mysql_fetch_array($query)) {
    $nome = $dados["protip_nome"];
    $sigla = $dados["protip_sigla"];
}


//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes.html");
$tpl1->LINK_DESTINO = "tipo_contagem_cadastrar2.php";
$tpl1->TITULO = "Nome";
$tpl1->ASTERISCO = "*";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_NOME = "nome";
$tpl1->CAMPO_TAMANHO = "35";
$tpl1->CAMPO_VALOR = $nome;
$tpl1->CAMPO_DESABILITADO = "";
$tpl1->CAMPO_OBRIGATORIO = "required";
$tpl1->CAMPO_ID = "capitalizar";
$tpl1->CAMPO_ONKEYPRESS = "capitalize()";

$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_ITEM");
$tpl1->TITULO = "Sigla";
$tpl1->ASTERISCO = "*";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_NOME = "sigla";
$tpl1->CAMPO_TAMANHO = "15";
$tpl1->CAMPO_VALOR = $sigla;
$tpl1->CAMPO_DESABILITADO = "";
$tpl1->CAMPO_OBRIGATORIO = "required";
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_ITEM");
$tpl1->LINK_CANCELAR = "tipo_contagem.php";
$tpl1->block("BLOCK_BOTOES");
$tpl1->CODIGO=$codigo;
$tpl1->NOME=$nome;
$tpl1->show();


include "rodape.php";
?>
