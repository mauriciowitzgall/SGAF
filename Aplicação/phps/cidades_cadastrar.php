<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_cidades_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "locais";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CIDADES";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "locais.png";
$tpl_titulo->show();

//Pega todos os dados da tabela (Necessário caso seja uma edição)
$codigo = $_GET["codigo"];
$sql = "SELECT * FROM cidades join estados on (cid_estado=est_codigo) WHERE cid_codigo='$codigo'";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $nome = $dados["cid_nome"];
    $estado = $dados["cid_estado"];
    $pais = $dados["est_pais"];
}

//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes.html");
$tpl1->LINK_DESTINO = "cidades_cadastrar2.php";
$tpl1->LINK_ATUAL = "cidades_cadastrar.php";

//Chama javascript
$tpl1->JS_CAMINHO = "cidades_cadastrar.js";
$tpl1->block("BLOCK_JS");

//Nome
$tpl1->TITULO = "Nome";
$tpl1->ASTERISCO = "*";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_NOME = "nome";
$tpl1->CAMPO_TAMANHO = "35";
$tpl1->CAMPO_VALOR = $nome;
$tpl1->CAMPO_ID = "capitalizar";
$tpl1->CAMPO_ONKEYPRESS = "capitalize()";
$tpl1->CAMPO_DESABILITADO = "";
$tpl1->CAMPO_OBRIGATORIO = "required";
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_ITEM");

//Pais
$tpl1->TITULO = "Pais";
$tpl1->ASTERISCO = "*";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "pais";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_DESABILITADO = "";
$tpl1->SELECT_OBRIGATORIO = " required ";
$sql = "
SELECT DISTINCT
    pai_codigo,pai_nome
FROM
    paises
    join estados on (est_pais=pai_codigo)    
ORDER BY
    pai_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
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

//Estado
$tpl1->TITULO = "Estado";
$tpl1->ASTERISCO = "*";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "estado";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_DESABILITADO = "";
$tpl1->SELECT_OBRIGATORIO = "required";
$sql = "SELECT * FROM estados ORDER BY est_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $tpl1->OPTION_VALOR = $dados["est_codigo"];
    $tpl1->OPTION_NOME = $dados["est_nome"];    
    //Se a operação for editar então mostrar os options, e o option em questão selecinado
    if ($codigo != "") { 
        if ($estado == $dados["est_codigo"]) {
            $tpl1->OPTION_SELECIONADO = " selected ";
        } else {
            $tpl1->OPTION_SELECIONADO = " ";
        }
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_ITEM");

//Botões
$tpl1->LINK_CANCELAR = "cidades.php";
$tpl1->block("BLOCK_BOTOES");
$tpl1->CODIGO = $codigo;
$tpl1->NOME = $nome;
$tpl1->show();


include "rodape.php";
?>
