
<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_taxas_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "quiosques";
include "includes.php";



//TÍTULO GERAL DA PAGINA
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TAXAS  ";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques_taxas.png";
$tpl_titulo->show();



//FILTRO
$tpl_filtro = new Template("templates/filtro1.html");
$tpl_filtro->FORM_ONLOAD = "";
$tpl_filtro->FORM_LINK = "taxas.php";
$tpl_filtro->FORM_NOME = "form_filtro";
$tpl_filtro->block("BLOCK_FORM");

$filtro_codigo = $_POST["filtro_codigo"];
$filtro_nometaxa = $_POST["filtro_nometaxa"];
$filtro_quiosque = $_POST["filtro_quiosque"];



//Filtro Código da Taxa
$tpl_filtro->CAMPO_TITULO = "Código";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "number";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_codigo";
$tpl_filtro->CAMPO_TAMANHO = "";
$tpl_filtro->CAMPO_VALOR = $filtro_codigo;
$tpl_filtro->block("BLOCK_CAMPO_PADRAO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

//Filtro Nome da Taxa
$tpl_filtro->CAMPO_TITULO = "Nome da Taxa";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "text";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_nometaxa";
$tpl_filtro->CAMPO_TAMANHO = "";
$tpl_filtro->CAMPO_VALOR = $filtro_nometaxa;
$tpl_filtro->block("BLOCK_CAMPO_PADRAO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");


//Quiosque
$tpl_filtro->CAMPO_TITULO = "Quiosque";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->SELECT_NOME = "filtro_quiosque";
$tpl_filtro->SELECT_ID = "";
$tpl_filtro->SELECT_TAMANHO = "";
$tpl_filtro->block("BLOCK_SELECT_FILTRO");
//$tpl_filtro->block("BLOCK_OPTION_PADRAO_SELECIONADO");
$tpl_filtro->block("BLOCK_OPTION_PADRAO");
$tpl_filtro->OPTION_VALOR = "Grupo";
$tpl_filtro->OPTION_TEXTO = "Grupo";
if ($filtro_quiosque=='Grupo')
    $tpl_filtro->block("BLOCK_OPTION_SELECIONADO"); 
$tpl_filtro->block("BLOCK_OPTION");

$sql = "
    SELECT DISTINCT qui_codigo,qui_nome
    FROM quiosques 
    JOIN taxas on (tax_quiosque=qui_codigo)   
    WHERE tax_cooperativa = $usuario_cooperativa
    ORDER BY qui_nome
";

if (!$query = mysql_query($sql))
    die("Erro SQL 0: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["qui_codigo"];    
    if ($codigo == $filtro_quiosque) {
        $tpl_filtro->block("BLOCK_OPTION_SELECIONADO"); 
    }
    $tpl_filtro->OPTION_VALOR = $dados["qui_codigo"];
    $tpl_filtro->OPTION_TEXTO = $dados["qui_nome"];
    $tpl_filtro->block("BLOCK_OPTION");
}
$tpl_filtro->block("BLOCK_SELECT");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

$tpl_filtro->block("BLOCK_LINHA");
$tpl_filtro->block("BLOCK_FILTRO_CAMPOS");
$tpl_filtro->block("BLOCK_QUEBRA");



$tpl_filtro->show();

$tpl4 = new Template("templates/botoes1.html");

//Botão Pesquisar
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl4->block("BLOCK_BOTAOPADRAO_PESQUISAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Botão Limpar filtro
$tpl4->COLUNA_LINK_ARQUIVO = "taxas.php";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl4->block("BLOCK_BOTAOPADRAO_LIMPAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Botão Cadastrar
if ($permissao_taxas_cadastrar == 1) {

    $tpl4->COLUNA_LINK_ARQUIVO = "taxas_cadastrar.php";
    $tpl4->COLUNA_LINK_TARGET = "";
    $tpl4->COLUNA_TAMANHO = "100%";
    $tpl4->COLUNA_ALINHAMENTO = "right";
    $tpl4->block("BLOCK_COLUNA_LINK");
    $tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
    $tpl4->block("BLOCK_BOTAOPADRAO_AUTOFOCO");
    $tpl4->block("BLOCK_BOTAOPADRAO_CADASTRAR");
    $tpl4->block("BLOCK_BOTAOPADRAO");
    $tpl4->block("BLOCK_COLUNA");
}

$tpl4->block("BLOCK_LINHA");
$tpl4->block("BLOCK_BOTOES");
$tpl4->block("BLOCK_LINHAHORIZONTAL_EMBAIXO");
$tpl4->show();

//LISTAGEM
$tpl2 = new Template("templates/lista2.html");
$tpl2->block("BLOCK_TABELA_CHEIA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_TAMANHO = "300px";
$tpl2->CABECALHO_COLUNA_NOME = "TAXA";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "70px";
$tpl2->CABECALHO_COLUNA_NOME = "TIPO NEG.";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "DESCRIÇÃO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_TAMANHO = "180px";
$tpl2->CABECALHO_COLUNA_NOME = "QUIOSQUE";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
if ($permissao_taxas_cadastrar == 1) {
    $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
}
$tpl2->block("BLOCK_CABECALHO_LINHA");
$tpl2->block("BLOCK_CABECALHO");
$sql_filtro = "";
if (!empty($filtro_codigo))
    $sql_filtro = " $sql_filtro.and tax_codigo=$filtro_codigo ";
if (!empty($filtro_nometaxa))
    $sql_filtro = $sql_filtro." and tax_nome like '%$filtro_nometaxa%' ";

if (($filtro_quiosque <> "") && ($filtro_quiosque <>'Global'))
    $sql_filtro = $sql_filtro." and tax_quiosque = $filtro_quiosque ";
if ($filtro_quiosque == 'Global') 
    $sql_filtro = $sql_filtro." and tax_quiosque = 0";

if (($usuario_quiosque!=0)) { //Gestores e Administradores veem tudo, os supervisores veem apenas as globais+taxas do seu quiosque
    $sql_filtro_quiosque.= " and (tax_quiosque=0 OR tax_quiosque=$usuario_quiosque) ";
}
$sql = "
SELECT 
    *
FROM 
    taxas 
    join tipo_negociacao on (tax_tiponegociacao=tipneg_codigo)
    left join quiosques on (tax_quiosque = qui_codigo)
WHERE 
    tax_cooperativa=$usuario_cooperativa
    $sql_filtro_quiosque
    $sql_filtro 
ORDER BY 
    qui_nome,tax_tiponegociacao,tax_nome
";
//Paginação
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se é a primeira vez que acessa a pagina então começar na pagina 1
if (($paginaatual == "") || ($paginas < $paginaatual) || ($paginaatual <= 0)) {
    $paginaatual = 1;
}
$comeco = ($paginaatual - 1) * $por_pagina;
$tpl2->PAGINAS = "$paginas";
$tpl2->PAGINAATUAL = "$paginaatual";
$tpl2->PASTA_ICONES = "$icones";
$tpl2->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";


$query = mysql_query($sql);
if (!$query)
    die("Erro SQL principal da Listagem: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    $tpl2->LINHA_NADA_COLSPAN = "5";
    $tpl2->block("BLOCK_LINHA_NADA");
}

while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["tax_codigo"];
    $nome = $dados["tax_nome"];
    $descricao = $dados["tax_descricao"];
    $tax_quiosque = $dados["tax_quiosque"];
    $tax_cooperativa = $dados["tax_cooperativa"];
    $quiosque_nome = $dados["qui_nome"];
    $tiponegociacao = $dados["tax_tiponegociacao"];

    //Taxa
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "$codigo";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "left";
    $tpl2->TEXTO = "$nome";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Tipo de negociação
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->block("BLOCK_COLUNA_ICONES");
    //$tpl2->CONTEUDO_LINK_ARQUIVO = "";
    //$tpl2->block("BLOCK_CONTEUDO_LINK_NOVAJANELA");
    //$tpl2->block("BLOCK_CONTEUDO_LINK");
    $tpl2->ICONE_TAMANHO = "18px";
    $tpl2->ICONE_CAMINHO = "$icones";
    if ($tiponegociacao == 1) {
        $tpl2->ICONE_NOMEARQUIVO = "consignacao.png";
        $tpl2->ICONE_DICA = "Consignação";
        $tpl2->ICONE_NOMEALTERNATIVO = "Consignação";
    } else if ($tiponegociacao == 2) {
        $tpl2->ICONE_NOMEARQUIVO = "revenda.png";
        $tpl2->ICONE_DICA = "Revenda";
        $tpl2->ICONE_NOMEALTERNATIVO = "Revenda";
    } else {
        $tpl2->ICONE_NOMEARQUIVO = "";
        $tpl2->ICONE_DICA = "";
        $tpl2->ICONE_NOMEALTERNATIVO = "";
    }
    $tpl2->block("BLOCK_ICONE");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Descrição
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$descricao";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");


    //Tipo de taxa (quiosque ou cooperativa)
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->block("BLOCK_COLUNA_ICONES");
    //$tpl2->CONTEUDO_LINK_ARQUIVO = "";
    //$tpl2->block("BLOCK_CONTEUDO_LINK_NOVAJANELA");
    //$tpl2->block("BLOCK_CONTEUDO_LINK");
    $tpl2->ICONE_TAMANHO = "15px";
    $tpl2->ICONE_CAMINHO = "$icones";
    if ($quiosque_nome == "") {
        $tpl2->ICONE_NOMEARQUIVO = "cooperativas.png";
        $tpl2->ICONE_DICA = "Taxa global, para todos os quiosques da cooperativa";
        $tpl2->ICONE_NOMEALTERNATIVO = "Cooperativa";
    } else {
        $tpl2->ICONE_NOMEARQUIVO = "quiosques.png";
        $tpl2->ICONE_DICA = "Taxa específica, apenas para este quiosque";
        $tpl2->ICONE_NOMEALTERNATIVO = "Quiosques";
    }
    $tpl2->block("BLOCK_ICONE");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");


    //Quiosque
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "";
        if ($quiosque_nome == "")
            $quiosque_nome = "Grupo";
        $tpl2->TEXTO = "$quiosque_nome";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");


    //Operações
    $tpl2->ICONES_CAMINHO = $icones;
    //Operação Editar
    $tpl2->COLUNA_TAMANHO = "50px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    if (($permissao_taxas_excluir == 1) && (($usuario_grupo == 1) || (($usuario_grupo == 2) && ($tax_quiosque == 0)) || (($usuario_grupo == 3) && ($tax_quiosque == $usuario_quiosque)))) {
        $tpl2->CONTEUDO_LINK_ARQUIVO = "taxas_cadastrar.php?operacao=editar&codigo=$codigo";
        $tpl2->block("BLOCK_CONTEUDO_LINK");
        $tpl2->block("BLOCK_OPERACAO_EDITAR_HABILITADO");
        $tpl2->block("BLOCK_OPERACAO_EDITAR_TITULOPADRAO");
    } else {
        $tpl2->block("BLOCK_OPERACAO_EDITAR_DESABILITADO");
        $tpl2->ICONES_TITULO = "Apenas os supervisores podem excluir taxas específicas, e gestores ou administradores porem excluir/editar taxas globais (da cooperativa)";
        $tpl2->block("BLOCK_OPERACAO_EDITAR_TITULO");
    }
    $tpl2->block("BLOCK_OPERACAO_EDITAR");
    $tpl2->block("BLOCK_OPERACAO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA_OPERACAO");
    $tpl2->block("BLOCK_COLUNA");

    //Operação Excluir
    $tpl2->COLUNA_TAMANHO = "50px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->ICONES_CAMINHO = $icones;

    if (($permissao_taxas_editar == 1) && (($usuario_grupo == 1) || (($usuario_grupo == 2) && ($tax_quiosque == 0)) || (($usuario_grupo == 3) && ($tax_quiosque == $usuario_quiosque)))) {
        $tpl2->CONTEUDO_LINK_ARQUIVO = "taxas_deletar.php?operacao=excluir&codigo=$codigo";
        $tpl2->block("BLOCK_CONTEUDO_LINK");
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_HABILITADO");
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULOPADRAO");
    } else {
        $tpl2->CONTEUDO_LINK_ARQUIVO = "";
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_DESABILITADO");
        $tpl2->ICONES_TITULO = "Apenas os supervisores podem excluir taxas específicas, e gestores ou administradores porem excluir/editar taxas globais (da cooperativa)";
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULO");
    }
    $tpl2->block("BLOCK_OPERACAO_EXCLUIR");
    $tpl2->block("BLOCK_OPERACAO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA_OPERACAO");
    $tpl2->block("BLOCK_COLUNA");

    $tpl2->block("BLOCK_LINHA_PADRAO");
    $tpl2->block("BLOCK_LINHA");
}
$tpl2->block("BLOCK_CORPO");
$tpl2->block("BLOCK_LISTAGEM");

$tpl2->show();




include "rodape.php";
?>
