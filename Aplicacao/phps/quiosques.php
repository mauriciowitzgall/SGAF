<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_quiosque_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "quiosques2";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "QUIOSQUES";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques.png";
$tpl_titulo->show();

$tpl = new Template("templates/listagem_2.html");

//FILTRO INICIO
$filtro_cooperativa = $_POST["filtro_cooperativa"];
$filtro_quiosque = $_POST["filtro_quiosque"];
$filtro_cidade = $_POST["filtro_cidade"];
$tpl->LINK_FILTRO = "quiosques.php";

//Filtro Quiosque
$tpl->CAMPO_TITULO = "Quiosque";
$tpl->CAMPO_TAMANHO = "40";
$tpl->CAMPO_NOME = "filtro_quiosque";
$tpl->CAMPO_VALOR = $filtro_quiosque;
$tpl->CAMPO_QTD_CARACTERES = "70";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Cooperativa
$tpl->SELECT_TITULO = "Cooperativa";
$tpl->SELECT_NOME = "filtro_cooperativa";
$tpl->SELECT_TAMANHO = "";
$sql = "
SELECT DISTINCT
    coo_codigo,coo_abreviacao
FROM
    cooperativas   
    join quiosques on (qui_cooperativa=coo_codigo)
ORDER BY
    coo_abreviacao
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl->OPTION_VALOR = $dados["coo_codigo"];
    $tpl->OPTION_NOME = $dados["coo_abreviacao"];
    if ($dados["coo_codigo"] == $filtro_cooperativa) {
        $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
    }
    $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
}
$tpl->block("BLOCK_SELECT_OPTIONPADRAO");
$tpl->block("BLOCK_FILTRO_SELECT");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Cidade
$tpl->SELECT_TITULO = "Cidade";
$tpl->SELECT_NOME = "filtro_cidade";
$tpl->SELECT_TAMANHO = "";
$sql = "
SELECT DISTINCT
    cid_codigo,cid_nome
FROM
    cidades
    join quiosques on (qui_cidade=cid_codigo)
ORDER BY
    cid_nome
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl->OPTION_VALOR = $dados["cid_codigo"];
    $tpl->OPTION_NOME = $dados["cid_nome"];
    if ($dados["cid_codigo"] == $filtro_cidade) {
        $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
    }
    $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
}
$tpl->block("BLOCK_SELECT_OPTIONPADRAO");
$tpl->block("BLOCK_FILTRO_SELECT");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Fim

if ($permissao_quiosque_cadastrar == 1) {
    //Verifica se há pelo menos uma cooperativa cadastrada
    $sql8 = "SELECT coo_codigo FROM cooperativas";
    $query8 = mysql_query($sql8);
    if (!$query8)
        die("Erro8: " . mysql_error());
    $linhas_coop = mysql_num_rows($query8);
    if ($linhas_coop > 0) {
        $tpl->LINK_CADASTRO = "quiosques_cadastrar.php?operacao=cadastrar";
        $tpl->BOTAO_CADASTRAR_NOME = "CADASTRAR";
        $tpl->block("BLOCK_FILTRO_BOTAO_CAD");
    } else {
        $tpl->LINK_CADASTRO = "";
        $tpl->BOTAO_CADASTRAR_NOME = "CADASTRAR";
        $tpl->block("BLOCK_RODAPE_BOTAO_CADASTRAR_DESABILITADO");
        $tpl->block("BLOCK_FILTRO_BOTAO_CAD");
    }
}
$tpl->block("BLOCK_FILTRO_BOTOES");
$tpl->block("BLOCK_FILTRO");



//LISTAGEM INICIO
//Cabeçalho
$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "NOME";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "CIDADE";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "25px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "DATA ULT. INT.";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "100px";
$tpl->CABECALHO_COLUNA_COLSPAN = "2";
$tpl->CABECALHO_COLUNA_NOME = "SUPERVISORES";
$tpl->block("BLOCK_LISTA_CABECALHO");


$tpl->CABECALHO_COLUNA_TAMANHO = "100px";
$tpl->CABECALHO_COLUNA_COLSPAN = "2";
$tpl->CABECALHO_COLUNA_NOME = "TAXAS";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "15px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TIPO NEG.";
$tpl->block("BLOCK_LISTA_CABECALHO");

$oper = 3;
$oper_tamanho = 150;
$tpl->CABECALHO_COLUNA_COLSPAN = "$oper";
$tpl->CABECALHO_COLUNA_TAMANHO = "$oper_tamanho";
$tpl->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Lista linhas
//Verifica quais filtros devem ser considerados no sql principal
$sql_filtro = "";
if ($filtro_quiosque <> "") {
    $sql_filtro = $sql_filtro . " and qui_nome like '%$filtro_quiosque%'";
}
if ($filtro_cooperativa <> "") {
    $sql_filtro = $sql_filtro . " and qui_cooperativa =$filtro_cooperativa ";
}
if ($filtro_cidade <> "") {
    $sql_filtro = $sql_filtro . " and qui_cidade = $filtro_cidade ";
}
if ($permissao_quiosque_definircooperativa == 0) {
    $sql_filtro = $sql_filtro . " and qui_cooperativa = $usuario_cooperativa ";
}


if (($usuario_grupo!=1)&&($usuario_grupo!=2)) {
    $sql_filtro = $sql_filtro . "and qui_codigo in ($usuario_quiosque)";
}

//Inicio das tuplas
$sql = "
SELECT
    *
FROM
    quiosques
    JOIN cidades on (qui_cidade=cid_codigo)
    JOIN cooperativas on (qui_cooperativa=coo_codigo)
WHERE
    qui_cooperativa= $usuario_cooperativa 
    $sql_filtro 
ORDER BY
    qui_nome";

//Paginação
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Pagina��o:" . mysql_error());
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


$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["qui_codigo"];
    $quiosque_coop = $dados["qui_cooperativa"];
    $cooperativa_nome = $dados["coo_abreviacao"];
    $nome = $dados["qui_nome"];
    $cidade_nome = $dados["cid_nome"];
    $endereco = $dados["qui_endereco"];
    $fone1 = $dados["qui_fone1"];


    //Coluna Nome
    $tpl->LISTA_COLUNA2_ALINHAMENTO = "left";
    $tpl->LISTA_COLUNA_VALOR = $nome;
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Cidade
    $tpl->LISTA_COLUNA2_ALINHAMENTO = "left";
    $tpl->LISTA_COLUNA_VALOR = $cidade_nome;
    $tpl->block("BLOCK_LISTA_COLUNA");

    $tpl->IMAGEM_PASTA = $icones;

    //Data Última Interação
    $sql2=" 
    SELECT max(dt) FROM (
    
    SELECT max(concat(ace_data,' ',ace_hora)) as dt
    FROM acertos
    WHERE ace_quiosque=$codigo

    UNION

    SELECT max(concat(ent_datacadastro,' ',ent_horacadastro)) as dt
    FROM entradas
    WHERE ent_quiosque=$codigo

    UNION

    SELECT max(concat(fch_datacadastro,' ',fch_horacadastro)) as dt
    FROM fechamentos
    WHERE fch_quiosque=$codigo

    UNION

    SELECT max(concat(sai_datacadastro,' ',sai_horacadastro)) as dt
    FROM saidas
    WHERE sai_quiosque=$codigo

    ) interacoes
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2=  mysql_fetch_array($query2);
    $dataultimainteracao=$dados2[0];
    $tpl->LISTA_COLUNA2_ALINHAMENTO = "left";
    $tpl->LISTA_COLUNA_VALOR = converte_datahora($dataultimainteracao);
    $tpl->block("BLOCK_LISTA_COLUNA");  
    
    
    //Coluna Supervisores
    $tpl->LISTA_COLUNA2_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA2_ALINHAMENTO2 = "left";
    $sqltot = "SELECT * FROM quiosques_supervisores WHERE quisup_quiosque=$codigo";
    $querytot = mysql_query($sqltot);
    if (!$querytot)
        die("Erro: " . mysql_error());
    $supervisores = mysql_num_rows($querytot);
    if ($permissao_quiosque_versupervisores == 1) {
        $tpl->LISTA_COLUNA2_LINK = "supervisores.php?quiosque=$codigo";
        $tpl->DESABILITADO = "";
    } else {
        $tpl->LISTA_COLUNA2_LINK = "#";
        $tpl->DESABILITADO = "_desabilitado";
    }
    $tpl->LISTA_COLUNA2_VALOR = "($supervisores)";
    $tpl->block("BLOCK_LISTA_COLUNA2");


    //Coluna Taxas
    $tpl->LISTA_COLUNA2_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA2_ALINHAMENTO2 = "left";
    $sqltot = "SELECT * FROM quiosques_taxas WHERE quitax_quiosque=$codigo";
    $querytot = mysql_query($sqltot);
    if (!$querytot)
        die("Erro: " . mysql_error());
    $taxastot = mysql_num_rows($querytot);
    if ($permissao_quiosque_vertaxas == 1) {
        $tpl->LISTA_COLUNA2_LINK = "quiosque_taxas.php?quiosque=$codigo";
        $tpl->DESABILITADO = "";
    } else {
        $tpl->LISTA_COLUNA2_LINK = "#";
        $tpl->DESABILITADO = "_desabilitado";
    }
    $tpl->LISTA_COLUNA2_VALOR = "($taxastot)";
    $tpl->block("BLOCK_LISTA_COLUNA2");

    //Tipo de negociação    
    $icone_tamanho = "18px";
    $sql2 = "SELECT * FROM quiosques_tiponegociacao WHERE quitipneg_quiosque=$codigo";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: 8" . mysql_error());
    $tpl->LINK = "#";
    $tpl->IMAGEM_TAMANHO = $icone_tamanho;
    $tpl->IMAGEM_PASTA = "$icones";
    $tipo_consignacao=0;
    $tipo_revenda=0;
    while ($dados2 = mysql_fetch_assoc($query2)) {
        $tipo2 = $dados2["quitipneg_tipo"];
        if ($tipo2 == 1)
            $tipo_consignacao = 1;
        if ($tipo2 == 2)
            $tipo_revenda = 1;
    }    
    $tpl->IMAGEM_TITULO = "Consignação";
    $tpl->IMAGEM_NOMEARQUIVO = "consignacao_desabilitado.png";
    if ($tipo_consignacao == 1) {
        $tpl->IMAGEM_NOMEARQUIVO = "consignacao.png";
    }
    $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    $tpl->IMAGEM_TITULO = "Revenda";
    $tpl->IMAGEM_NOMEARQUIVO = "revenda_desabilitado.png";
    if ($tipo_revenda == 1) {
        $tpl->IMAGEM_NOMEARQUIVO = "revenda.png";
    }
    $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    $tpl->IMAGEM_ALINHAMENTO="center";
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES");

    

    
    
    
    //Coluna Operações    
    $tpl->ICONE_ARQUIVO = $icones;
    $tpl->CODIGO = $codigo;
    //detalhes    
    if ($permissao_quiosque_ver == 1) {
        $tpl->LINK = "quiosques_cadastrar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=ver";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DETALHES");
    }
    

    //editar
    if ((($permissao_quiosque_editar == 1) && ($codigo == $usuario_quiosque)) || (($usuario_grupo == 1) && ($usuario_cooperativa == $quiosque_coop))) {
        $tpl->LINK = "quiosques_cadastrar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=editar";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR");
    } else {
        $tpl->LINK = "";
        $tpl->LINK_COMPLEMENTO = "";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR_DESABILITADO");
    }
    //deletar
    if ((($permissao_quiosque_excluir == 1) && ($codigo == $usuario_quiosque)) || ($usuario_grupo == 1)) {
        $tpl->LINK = "quiosques_deletar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=excluir&passo=1";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR");
    } else {
        $tpl->LINK = "";
        $tpl->LINK_COMPLEMENTO = "";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR_DESABILITADO");
    }
    $tpl->block("BLOCK_LISTA");
}
if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}


$tpl->show();
include "rodape.php";
?>
