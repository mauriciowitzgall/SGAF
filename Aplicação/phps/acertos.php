<?php

$titulopagina = "Acertos Listagem";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_acertos_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "negociacoes";
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ACERTOS DE CONSIGNAÇÕES ";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "consignacao.png";
$tpl_titulo->show();


$tpl = new Template("templates/listagem_2.html");
$tpl->FORM_ONLOAD = "";

//FILTRO INICIO
$filtro_codigo = $_POST["filtro_codigo"];
$filtro_supervisor = $_POST["filtro_supervisor"];
$filtro_fornecedor = $_POST["filtro_fornecedor"];
if ($usuario_grupo == 5) {
    $filtro_fornecedor = $usuario_codigo;
}


$filtro_data1 = $_POST["filtro_data1"];
$filtro_data2 = $_POST["filtro_data2"];
$tpl->LINK_FILTRO = "acertos.php";

//Filtro ID
$tpl->CAMPO_TITULO = "Codigo";
$tpl->CAMPO_TAMANHO = "10";
$tpl->CAMPO_NOME = "filtro_codigo";
$tpl->CAMPO_VALOR = $filtro_codigo;
$tpl->CAMPO_QTD_CARACTERES = "20";
$tpl->CAMPO_ONKEYUP = "pessoas_filtro_codigo()";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

if ($usuario_grupo != 5) {
//Filtro Supervisor
    $tpl->SELECT_TITULO = "Supervisor";
    $tpl->SELECT_NOME = "filtro_supervisor";
    $tpl->SELECT_TAMANHO = "";
    $sql = "
SELECT DISTINCT
    pes_codigo,pes_nome
FROM
    pessoas    
    join acertos on (ace_supervisor=pes_codigo)
WHERE   
   ace_quiosque=$usuario_quiosque 
";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $tpl->OPTION_VALOR = $dados["pes_codigo"];
        $tpl->OPTION_NOME = $dados["pes_nome"];
        if ($dados["pes_codigo"] == $filtro_supervisor) {
            $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
        }
        $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
    }
    $tpl->block("BLOCK_FILTRO_SELECT");
    $tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Fornecedor
    $tpl->SELECT_TITULO = "Fornecedor";
    $tpl->SELECT_NOME = "filtro_fornecedor";
    $tpl->SELECT_TAMANHO = "";
    $sql = "
SELECT DISTINCT
    pes_codigo,pes_nome
FROM
    pessoas            
    join acertos on (ace_fornecedor=pes_codigo)
WHERE
   ace_quiosque=$usuario_quiosque
ORDER BY
   pes_nome
";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $tpl->OPTION_VALOR = $dados["pes_codigo"];
        $tpl->OPTION_NOME = $dados["pes_nome"];
        if ($dados["pes_codigo"] == $filtro_fornecedor) {
            $tpl->block("BLOCK_FILTRO_SELECT_OPTION_SELECIONADO");
        }
        $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
    }
    $tpl->block("BLOCK_FILTRO_SELECT");
    $tpl->block("BLOCK_FILTRO_COLUNA");
}

if ($usuario_grupo != 5) {
    if (($permissao_acertos_cadastrar == 1)&&($usuario_quiosque!=0)) {
        $tpl->LINK_CADASTRO = "acertos_cadastrar.php?operacao=cadastrar";
        $tpl->BOTAO_CADASTRAR_NOME = "REALIZAR NOVO ACERTO";
        $tpl->block("BLOCK_FILTRO_BOTAO_CAD");
    }
} else {
    $tpl->LINK_CADASTRO = "acertos_cadastrar.php?operacao=simular";
    $tpl->BOTAO_CADASTRAR_NOME = "SIMULAR PRÓXIMO ACERTO";
    $tpl->block("BLOCK_FILTRO_BOTAO_CAD");
}
$tpl->block("BLOCK_FILTRO_BOTOES");
$tpl->block("BLOCK_FILTRO");
//Filtro Fim
//LISTAGEM INICIO
//Cabeçalho
$tpl->CABECALHO_COLUNA_TAMANHO = "50px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "CODIGO";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "2";
$tpl->CABECALHO_COLUNA_NOME = "DATA";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "SUPERVISOR";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "FORNECEDOR";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "3";
$tpl->CABECALHO_COLUNA_NOME = "PERÍODO";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "BRUTO";
$tpl->block("BLOCK_LISTA_CABECALHO");
/*
$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TAXAS";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TOTAL";
$tpl->block("BLOCK_LISTA_CABECALHO");

//$tpl->CABECALHO_COLUNA_TAMANHO = "";
//$tpl->CABECALHO_COLUNA_COLSPAN = "";
//$tpl->CABECALHO_COLUNA_NOME = "PAGO";
//$tpl->block("BLOCK_LISTA_CABECALHO");
$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "PENDENTE";
$tpl->block("BLOCK_LISTA_CABECALHO");
*/

$oper = 0;
$oper_tamanho = 0;
if ($permissao_entradas_ver == 1) {
    $oper = $oper + 2;
    $oper_tamanho = $oper_tamanho + 100;
}
if ($permissao_entradas_editar == 1) {
    $oper++;
    $oper_tamanho = $oper_tamanho + 50;
}
if ($permissao_entradas_excluir == 1) {
    $oper++;
    $oper_tamanho = $oper_tamanho + 50;
}

$tpl->CABECALHO_COLUNA_COLSPAN = "$oper";
$tpl->CABECALHO_COLUNA_TAMANHO = "$oper_tamanho";
$tpl->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Lista linhas
//Verifica quais filtros devem ser considerados no sql principal
$sql_filtro = "";
if ($filtro_codigo <> "") {
    $sql_filtro = $sql_filtro . " and ace_codigo = $filtro_codigo";
}
if ($filtro_supervisor <> "") {
    $sql_filtro = $sql_filtro . " and ace_supervisor = $filtro_supervisor";
}
if ($filtro_fornecedor <> "") {
    $sql_filtro = $sql_filtro . " and ace_fornecedor = $filtro_fornecedor ";
}

//Inicio das tuplas
$sql = "
SELECT DISTINCT
    ace_codigo,ace_data,ace_hora,ace_supervisor,ace_fornecedor,ace_valorbruto,ace_valortaxas,ace_valorpendente,ace_valortotal,ace_valorpago,ace_trocodevolvido,ace_quiosque,ace_dataini,ace_datafim
FROM 
    acertos 
    join pessoas on (ace_fornecedor=pes_codigo or ace_supervisor=pes_codigo)
WHERE
    ace_quiosque=$usuario_quiosque
    $sql_filtro
ORDER BY
    ace_codigo DESC
";

//Paginação
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se é a primeira vez que acessa a pagina ent�o come�ar na pagina 1
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
$cont = 0;
while ($dados = mysql_fetch_assoc($query)) {
    $cont++;
    $codigo = $dados["ace_codigo"];
    $data = $dados["ace_data"];
    $hora = $dados["ace_hora"];
    $supervisor = $dados["ace_supervisor"];
    $fornecedor = $dados["ace_fornecedor"];
    $valorbruto = $dados["ace_valorbruto"];
    $valortaxas = $dados["ace_valortaxas"];
    $valorpendente = $dados["ace_valorpendente"];
    $valortotal = $dados["ace_valortotal"];
    $valorpago = $dados["ace_valorpago"];
    $trocodevolvido = $dados["ace_trocodevolvido"];
    $datade = $dados["ace_dataini"];
    $dataate = $dados["ace_datafim"];

    //Coluna C�digo
    $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA_VALOR = $dados["ace_codigo"];
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Data
    $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA_VALOR = converte_data($data);
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Hora
    $tpl->LISTA_COLUNA_ALINHAMENTO = "";
    $tpl->LISTA_COLUNA_VALOR = $hora;
    $tpl->block("BLOCK_LISTA_COLUNA");


    //Coluna Supervidor
    $sql2 = "SELECT pes_codigo,pes_nome FROM pessoas WHERE pes_codigo=$supervisor";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $supervidor_nome = $dados2["pes_nome"];
    $tpl->LISTA_COLUNA_ALINHAMENTO = "";
    $tpl->LISTA_COLUNA_VALOR = $supervidor_nome;
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Fornecedor
    $sql2 = "SELECT pes_codigo,pes_nome FROM pessoas WHERE pes_codigo=$fornecedor";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro: " . mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $fornecedor_nome = $dados2["pes_nome"];
    $tpl->LISTA_COLUNA_ALINHAMENTO = "";
    $tpl->LISTA_COLUNA_VALOR = $fornecedor_nome;
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Período De Até
    $datade2=  converte_data($datade);
    $dataate2=  converte_data($dataate);
    $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA_VALOR = "$datade2";
    $tpl->block("BLOCK_LISTA_COLUNA");
    $tpl->LISTA_COLUNA_ALINHAMENTO = "center";
    $tpl->LISTA_COLUNA_VALOR = " até ";
    $tpl->block("BLOCK_LISTA_COLUNA");
    //Período De Até
    $tpl->LISTA_COLUNA_ALINHAMENTO = "left";
    $tpl->LISTA_COLUNA_VALOR = "$dataate2";
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Valor Bruto
    $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA_VALOR = "R$ " . number_format($valorbruto, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");

    /*
    //Coluna Valor Taxas
    $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA_VALOR = "R$ " . number_format($valortaxas, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Valor Total
    $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA_VALOR = "R$ " . number_format($valortotal, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");

    //Coluna Valor Pago
    //$tpl->LISTA_COLUNA_ALINHAMENTO = "right";
    //$tpl->LISTA_COLUNA_VALOR = "R$ " . number_format($valorpago, 2, ',', '.');
    //$tpl->block("BLOCK_LISTA_COLUNA");
    //Coluna Valor Pendente
    $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
    $tpl->LISTA_COLUNA_VALOR = "R$ " . number_format($valorpendente, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");
*/

    //Coluna Opera�ões    
    $tpl->ICONE_ARQUIVO = $icones;
    $tpl->CODIGO = $codigo;

    if ($permissao_acertos_ver == 1) {
        //imprimir
        $tpl->LINK = "acertos_cadastrar3.php";
        $tpl->LINK_COMPLEMENTO = "operacao=imprimir";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_IMPRIMIR");

        //detalhes
        $tpl->LINK = "acertos_cadastrar3.php";
        $tpl->LINK_COMPLEMENTO = "operacao=ver";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DETALHES");
    }
    //excluir
    if ($permissao_acertos_excluir == 1) {
        $tpl->LINK = "acertos_deletar.php";
        $tpl->LINK_COMPLEMENTO = "operacao=excluir&fornecedor=$fornecedor";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR");
    }


    $tpl->block("BLOCK_LISTA");
}


if ($cont == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}

$tpl->show();
include "rodape.php";
?>
