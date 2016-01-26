<?php
$codigo = $_GET["codigo"];

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
$sql = "
    SELECT relper_relatorio
    FROM relatorios_permissao
    WHERE relper_relatorio=$codigo
    and relper_grupo=$usuario_grupo
";
$query = mysql_query($sql);
if (!$query)
    die("Erro:" . mysql_error());
$dados = mysql_fetch_assoc($query);
$linhas = mysql_num_rows($query);
if (($linhas == 0) && ($usuario_grupo != 1)) {
    //header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "relatorios";
include "includes.php";
?><script type="text/javascript" src="relatorios/<?php echo $codigo; ?>.js"></script><?php
$operacao = $_GET["operacao"];
if (empty($operacao))
    $operacao = "cadastrar";


//TÃTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "RELATÓRIOS";
$tpl_titulo->SUBTITULO = "FILTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "relatorios.png";
$tpl_titulo->show();

$tpl = new Template("templates/cadastro1.html");

$sql = "
    SELECT rel_nome,rel_descricao
    FROM relatorios
    WHERE rel_codigo=$codigo
";
$query = mysql_query($sql);
if (!$query)
    die("Erro1: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$nome = $dados["rel_nome"];
$descricao = $dados["rel_descricao"];

//Numero
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "200px";
$tpl->TITULO = "Nº";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "numero";
$tpl->CAMPO_VALOR = "$codigo";
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");

//Nome do relatório
$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "";
$tpl->TITULO = "Nome do relatório";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "left";
$tpl->CAMPO_TAMANHO = "80";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_NOME = "nome";
$tpl->CAMPO_VALOR = "$nome";
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");


//Descrição
/* $tpl->COLUNA_ALINHAMENTO = "right";
  $tpl->COLUNA_TAMANHO = "";
  $tpl->TITULO = "Descrição";
  $tpl->block("BLOCK_TITULO");
  $tpl->block("BLOCK_CONTEUDO");
  $tpl->block("BLOCK_COLUNA");
  $tpl->COLUNA_ALINHAMENTO = "";
  $tpl->TEXTAREA_QTDCARACTERES = "";
  $tpl->TEXTAREA_NOME = "descricao";
  $tpl->TEXTAREA_TAMANHO = "80";
  $tpl->TEXTAREA_TEXTO = "$descricao";
  $tpl->block("BLOCK_TEXTAREA_PADRAO");
  $tpl->block("BLOCK_TEXTAREA_DESABILITADO");
  $tpl->block("BLOCK_TEXTAREA");
  $tpl->block("BLOCK_CONTEUDO");
  $tpl->block("BLOCK_COLUNA");
  $tpl->block("BLOCK_LINHA"); */

$tpl->show();


//TÃ­tulo Filtro
$tpl2_tit = new Template("templates/tituloemlinha_2.html");
$tpl2_tit->LISTA_TITULO = "FILTROS";
$tpl2_tit->block("BLOCK_QUEBRA1");
$tpl2_tit->block("BLOCK_TITULO");
$tpl2_tit->block("BLOCK_QUEBRA2");
$tpl2_tit->show();


//Inclui o filtro do relatório em questão
$tpl_rel = new Template("templates/cadastro1.html");
$tpl_rel->FORM_NOME = "form1";
$tpl_rel->FORM_TARGET = "_blank";
$tpl_rel->FORM_LINK = "relatorios/" . $codigo . "php.php";
$tpl_rel->block("BLOCK_FORM");
$relatorio = "relatorios/" . $codigo . ".php";

if (!@file_exists("$relatorio")) {
    echo '<br><b>Este relatório está em construção ou não existe. Contate um administrador para resolver este problema!<b>';
} else {
    include("$relatorio");

    //Campo oculto código do relatório
    $tpl_rel->CAMPOOCULTO_NOME = "codigo";
    $tpl_rel->CAMPOOCULTO_VALOR = "$codigo";
    $tpl_rel->block("BLOCK_CAMPOOCULTO");

//Botão Gerar
    $tpl_rel->COLUNA_ALINHAMENTO = "right";
    $tpl_rel->COLUNA_TAMANHO = "200px";
    $tpl_rel->COLUNA_ROWSPAN = "";
    $tpl_rel->block("BLOCK_COLUNA");
    //$tpl_rel->BOTAO_TECLA = "";
    $tpl_rel->COLUNA_ALINHAMENTO = "left";
    $tpl_rel->COLUNA_TAMANHO = "";
    $tpl_rel->COLUNA_ROWSPAN = "";
    $tpl_rel->BOTAO_TIPO = "submit";
    $tpl_rel->BOTAO_VALOR = "GERAR";
    //$tpl_rel->BOTAO_NOME = "";
    //$tpl_rel->BOTAO_ID = "";
    //$tpl_rel->BOTAO_DICA = "";
    //$tpl_rel->BOTAO_ONCLICK = "";
    //$tpl_rel->BOTAO_CLASSE = "";
    $tpl_rel->block("BLOCK_BOTAO_PADRAO");
    //$tpl_rel->BOTAO_CLASSE = "";
    //$tpl_rel->block("BLOCK_BOTAO_DINAMICO");
    //$tpl_rel->block("BLOCK_BOTAO_DESABILITADO");
    //$tpl_rel->block("BLOCK_BOTAO_AUTOFOCO");
    $tpl_rel->block("BLOCK_BOTAO");
    //$tpl_rel->block("BLOCK_BR");
    $tpl_rel->block("BLOCK_CONTEUDO");
    $tpl_rel->block("BLOCK_COLUNA");
    $tpl_rel->block("BLOCK_LINHA");
    $tpl_rel->show();
}

//Botão Voltar
$tpl2 = new Template("templates/botoes1.html");
$tpl2->block("BLOCK_LINHAHORIZONTAL_EMCIMA");
$tpl2->block("BLOCK_COLUNA_LINK_VOLTAR");
$tpl2->block("BLOCK_COLUNA_LINK");
$tpl2->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl2->block("BLOCK_BOTAOPADRAO_VOLTAR");
$tpl2->block("BLOCK_BOTAOPADRAO");
$tpl2->block("BLOCK_COLUNA");

$tpl2->block("BLOCK_LINHA");
$tpl2->block("BLOCK_BOTOES");

$tpl2->show();
?>