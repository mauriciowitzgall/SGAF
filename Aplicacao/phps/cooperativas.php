<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_cooperativa_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativa";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "COOPERATIVA";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "cooperativas.png";
$tpl_titulo->show();

$tpl = new Template("templates/listagem.html");

//Filtro Inicio
$filtro_nome = $_POST["filtro_nome"];
$filtro_abreviacao = $_POST["filtro_abreviacao"];
$tpl->LINK_FILTRO = "cooperativas.php";

//Filtro Abreviacao
$tpl->CAMPO_TITULO = "Abreviação";
$tpl->CAMPO_TAMANHO = "25";
$tpl->CAMPO_NOME = "filtro_abreviacao";
$tpl->CAMPO_VALOR = $filtro_abreviacao;
$tpl->CAMPO_QTD_CARACTERES = "";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Nome
$tpl->CAMPO_TITULO = "Nome";
$tpl->CAMPO_TAMANHO = "25";
$tpl->CAMPO_NOME = "filtro_nome";
$tpl->CAMPO_VALOR = $filtro_nome;
$tpl->CAMPO_QTD_CARACTERES = "";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Fim
$tpl->block("BLOCK_FILTRO");



//Listagem Inicio
//Cabeçalho
$tpl->CABECALHO_COLUNA_TAMANHO = "150px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "ABREVIAÇÃO";
$tpl->block("BLOCK_LISTA_CABECALHO");


$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "QTD. QUIOSQUES ";
$tpl->block("BLOCK_LISTA_CABECALHO");


$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "QTD. PESSOAS ";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "QTD. PRODUTOS ";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "QTD. ENTRADAS ";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "QTD. SAIDAS ";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "150px";
$tpl->CABECALHO_COLUNA_COLSPAN = "2";
$tpl->CABECALHO_COLUNA_NOME = "DATA ULT. INT.";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "2";
$tpl->CABECALHO_COLUNA_NOME = "GESTORES";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "100px";
$tpl->CABECALHO_COLUNA_COLSPAN = "2";
$tpl->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Lista linhas
//Verifica quais filtros devem ser considerados no sql principal
$sql_filtro = "";

if ($filtro_nome <> "") {
    $sql_filtro = $sql_filtro . " and coo_nomecompleto like '%$filtro_nome%'";
}
if ($filtro_abreviacao <> "") {
    $sql_filtro = $sql_filtro . " and coo_abreviacao like '%$filtro_abreviacao%' ";
}

//Listagem
$sql = "SELECT * FROM cooperativas WHERE 1 $sql_filtro ORDER BY coo_nomecompleto";
//Pagina��o
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



$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
while ($dados = mysql_fetch_array($query)) {
    $codigo = $dados["coo_codigo"];
    $nome = $dados["coo_nomecompleto"];
    $abreviacao = $dados["coo_abreviacao"];
    $tpl->LISTA_LINHA_CLASSE = "";

    //Coluna Abreviação
    $tpl->LISTA_COLUNA_VALOR = $abreviacao;
    $tpl->block("BLOCK_LISTA_COLUNA_CONTEUDO");
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Quantidade de quiosques
    $sql2="select count(qui_codigo) from quiosques where qui_cooperativa=$codigo";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_array($query2);    
    $qtdquiosques=$dados2[0];
    $tpl->LISTA_COLUNA_VALOR = $qtdquiosques;
    $tpl->LISTA_COLUNA_ALINHAMENTO = "center";
    $tpl->block("BLOCK_LISTA_COLUNA_CONTEUDO");
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Quantidade de pessoas
    $sql2="select count(pes_codigo) from pessoas where pes_cooperativa=$codigo";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_array($query2); 
    $qtdpessoas=$dados2[0];
    $tpl->LISTA_COLUNA_VALOR = $qtdpessoas;
    $tpl->LISTA_COLUNA_ALINHAMENTO = "center";
    $tpl->block("BLOCK_LISTA_COLUNA_CONTEUDO");
    $tpl->block("BLOCK_LISTA_COLUNA");
    

    //Coluna Quantidade de produtos
    $sql2="select count(pro_codigo) from produtos where pro_cooperativa=$codigo";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_array($query2); 
    $qtdprodutos=$dados2[0];
    $tpl->LISTA_COLUNA_VALOR = $qtdprodutos;
    $tpl->LISTA_COLUNA_ALINHAMENTO = "center";
    $tpl->block("BLOCK_LISTA_COLUNA_CONTEUDO");
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Quantidade de Entradas
    $sql2="
    select count(ent_codigo) from entradas 
    join quiosques on ent_quiosque=qui_codigo
    where qui_cooperativa=$codigo     
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_array($query2); 
    $qtdentradas=$dados2[0];
    $tpl->LISTA_COLUNA_VALOR = $qtdentradas;
    $tpl->LISTA_COLUNA_ALINHAMENTO = "center";
    $tpl->block("BLOCK_LISTA_COLUNA_CONTEUDO");
    $tpl->block("BLOCK_LISTA_COLUNA");

    
    //Coluna Quantidade de Saidas
    $sql2="
    select count(sai_codigo) from saidas 
    join quiosques on sai_quiosque=qui_codigo
    where qui_cooperativa=$codigo     
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_array($query2); 
    $qtdsaidas=$dados2[0];
    $tpl->LISTA_COLUNA_VALOR = "$qtdsaidas";
    $tpl->LISTA_COLUNA_ALINHAMENTO = "center";
    $tpl->block("BLOCK_LISTA_COLUNA_CONTEUDO");
    $tpl->block("BLOCK_LISTA_COLUNA");
  
    
    //Data Ultima interação
    $sql2="
    SELECT max(dt) FROM (

    SELECT max(concat(ace_data,' ',ace_hora)) as dt
    FROM acertos
    join quiosques on ace_quiosque=qui_codigo
    WHERE qui_cooperativa=$codigo

    UNION

    SELECT max(concat(ent_datacadastro,' ',ent_horacadastro)) as dt
    FROM entradas
    join quiosques on ent_quiosque=qui_codigo
    WHERE qui_cooperativa=$codigo

    UNION

    SELECT max(concat(fch_datacadastro,' ',fch_horacadastro)) as dt
    FROM fechamentos
    join quiosques on fch_quiosque=qui_codigo
    WHERE qui_cooperativa=$codigo

    UNION

    SELECT max(concat(sai_datacadastro,' ',sai_horacadastro)) as dt
    FROM saidas
    join quiosques on sai_quiosque=qui_codigo
    WHERE qui_cooperativa=$codigo

    UNION

    SELECT max(concat(pes_datacadastro,' ',pes_horacadastro)) as dt
    FROM pessoas
    WHERE pes_cooperativa=$codigo

    UNION

    SELECT max(concat(pro_datacriacao,' ',pro_horacriacao)) as dt
    FROM produtos
    WHERE pro_cooperativa=$codigo

    ) interacoes  
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_array($query2); 
    $dataultimainteracao=$dados2[0]; 
    $dataultimainteracao_convertido = converte_datahora($dataultimainteracao);
    $datahora=  explode(" ",$dataultimainteracao_convertido);
    $data = $datahora[0];
    $hora = $datahora[1];
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_VALOR = $data;
    $tpl->block("BLOCK_LISTA_COLUNA");
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    $tpl->LISTA_COLUNA_VALOR = $hora;
    $tpl->block("BLOCK_LISTA_COLUNA_CONTEUDO");
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Coluna Gestores
    $tpl->LISTA_COLUNA2_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA2_ALINHAMENTO2 = "left";
    $tpl->IMAGEM_PASTA=$icones;
    $sqltot = "SELECT * FROM cooperativa_gestores WHERE cooges_cooperativa=$codigo";
    $querytot = mysql_query($sqltot);
    if (!$querytot)
        die("Erro: " . mysql_error());
    $gestores = mysql_num_rows($querytot);
    if ($permissao_cooperativa_gestores_gerir == 1) {
        $tpl->LISTA_COLUNA2_LINK = "cooperativa_gestores.php";
        $tpl->DESABILITADO = "";
    } else {
        $tpl->LISTA_COLUNA2_LINK = "#";
        $tpl->DESABILITADO = "_desabilitado";
    }
    $tpl->LISTA_COLUNA2_VALOR = "($gestores)";
    $tpl->block("BLOCK_LISTA_COLUNA_CONTEUDO");
    $tpl->block("BLOCK_LISTA_COLUNA2");
    
    
    //Coluna Operações
    $tpl->CODIGO = $codigo;
    //editar  
    IF ($permissao_cooperativa_editar == 1) {
        $tpl->OPERACAO_NOME = "Editar";
        $tpl->LINK = "cooperativas_cadastrar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=editar";
        $tpl->ICONE_ARQUIVO = $icones . "editar.png";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");
    } else {
        $tpl->OPERACAO_NOME = "Você não tem permissão para editar cooperativas! Contate um administrador!";
        $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");
    }
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_TODAS");
    //excluir
    IF ($permissao_cooperativa_excluir == 1) {
        $tpl->OPERACAO_NOME = "Excluir";
        $tpl->LINK = "cooperativas_deletar.php?codigo=$codigo&passo=1";
        $tpl->LINK_COMPLEMENTO = "operacao=excluir";
        $tpl->ICONE_ARQUIVO = $icones . "excluir.png";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");
    } else {
        $tpl->OPERACAO_NOME = "Você não tem permissão para excluir cooperativas! Contate um administrador!";
        $tpl->ICONE_ARQUIVO = $icones . "excluir_desabilitado.png";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");
    }
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_TODAS");
    $tpl->block("BLOCK_LISTA_COLUNA_CONTEUDO");


    $tpl->block("BLOCK_LISTA");
}
$tpl->LINK_CADASTRO = "cooperativas_cadastrar.php?operacao=cadastrar";
$tpl->CADASTRAR_NOME = "CADASTRAR";
if (mysql_num_rows($query) == 0) {
    $tpl->LISTANADA = "30";
    $tpl->block("BLOCK_LISTA_NADA");
}


if ($permissao_cooperativa_cadastrar != 1)
    $tpl->block("BLOCK_RODAPE_BOTOES_DESABILITADOS");
$tpl->block("BLOCK_RODAPE_BOTOES");


$tpl->show();
include "rodape.php";
?>
