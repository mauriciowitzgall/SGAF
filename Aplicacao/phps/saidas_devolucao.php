<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_saidas_ver_devolucao <> 1) {
    echo $permissao_saidas_ver_devolucao;
    //header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "saidas";
include "includes.php";


//TÍTULO GERAL DA PAGINA
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "SAIDAS  ";
$tpl_titulo->SUBTITULO = "LISTAGEM DE AJUSTES DE SAIDAS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "saidas.png";
$tpl_titulo->show();


//FILTRO
$tpl_filtro = new Template("templates/filtro1.html");
$tpl_filtro->FORM_ONLOAD = "";
$tpl_filtro->FORM_LINK = "saidas_devolucao.php";
$tpl_filtro->FORM_NOME = "form_filtro";
$tpl_filtro->block("BLOCK_FORM");

$filtro_numero = $_POST["filtro_numero"];
$filtro_motivo = $_POST["filtro_motivo"];
$filtro_descricao = $_POST["filtro_descricao"];
$filtro_supervisor = $_POST["filtro_supervisor"];
$filtro_fornecedor = $_POST["filtro_fornecedor"];


//Filtro Numero
$tpl_filtro->CAMPO_TITULO = "Numero";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "text";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_numero";
$tpl_filtro->CAMPO_ONKEYUP = "valida_filtro_saidas_devolucao_numero()";
$tpl_filtro->CAMPO_TAMANHO = "";
$tpl_filtro->CAMPO_VALOR = $filtro_numero;
$tpl_filtro->block("BLOCK_CAMPO_FILTRO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

//Motivo
$tpl_filtro->CAMPO_TITULO = "Motivo";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->SELECT_NOME = "filtro_motivo";
$tpl_filtro->SELECT_ID = "";
$tpl_filtro->SELECT_TAMANHO = "";
$tpl_filtro->block("BLOCK_SELECT_FILTRO");
$tpl_filtro->block("BLOCK_OPTION_PADRAO");
$sql = "
    SELECT DISTINCT saimot_codigo,saimot_nome 
    FROM saidas_motivo 
    join saidas on (sai_saidajustificada=saimot_codigo)
    WHERE sai_tipo=3
    ORDER BY saimot_nome 
";
if (!$query = mysql_query($sql))
    die("Erro SQL 0: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["saimot_codigo"];
    if ($codigo == $filtro_motivo)
        $tpl_filtro->block("BLOCK_OPTION_SELECIONADO");
    $tpl_filtro->OPTION_VALOR = $dados["saimot_codigo"];
    $tpl_filtro->OPTION_TEXTO = $dados["saimot_nome"];
    $tpl_filtro->block("BLOCK_OPTION");
}
$tpl_filtro->block("BLOCK_SELECT");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

//Filtro Descri��o
$tpl_filtro->CAMPO_TITULO = "Descrição";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "text";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_descricao";
$tpl_filtro->CAMPO_TAMANHO = "";
$tpl_filtro->CAMPO_VALOR = $filtro_descricao;
$tpl_filtro->block("BLOCK_CAMPO_FILTRO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

//Supervisor
$tpl_filtro->CAMPO_TITULO = "Supervisor";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->SELECT_NOME = "filtro_supervisor";
$tpl_filtro->SELECT_ID = "";
$tpl_filtro->SELECT_TAMANHO = "";
$tpl_filtro->block("BLOCK_SELECT_FILTRO");
$tpl_filtro->block("BLOCK_OPTION_PADRAO");
$sql = "
    SELECT DISTINCT pes_codigo,pes_nome 
    FROM saidas
    JOIN caixas_operacoes on (sai_caixaoperacaonumero=caiopo_numero)
    JOIN pessoas on (caiopo_operador=pes_codigo)
    WHERE sai_tipo=3
    ORDER BY pes_nome 
";
if (!$query = mysql_query($sql))
    die("Erro SQL 0: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["pes_codigo"];
    if ($codigo == $filtro_supervisor)
        $tpl_filtro->block("BLOCK_OPTION_SELECIONADO");
    $tpl_filtro->OPTION_VALOR = $dados["pes_codigo"];
    $tpl_filtro->OPTION_TEXTO = $dados["pes_nome"];
    $tpl_filtro->block("BLOCK_OPTION");
}
$tpl_filtro->block("BLOCK_SELECT");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

//Forncedor
$tpl_filtro->CAMPO_TITULO = "Fornecedor";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->SELECT_NOME = "filtro_fornecedor";
$tpl_filtro->SELECT_ID = "";
$tpl_filtro->SELECT_TAMANHO = "";
$tpl_filtro->block("BLOCK_SELECT_FILTRO");
$tpl_filtro->block("BLOCK_OPTION_PADRAO");
$sql = "
SELECT DISTINCT pes_codigo, pes_nome 
FROM saidas_produtos
JOIN saidas on (saipro_saida=sai_codigo)
JOIN entradas on (saipro_lote=ent_codigo)
JOIN pessoas on (ent_fornecedor=pes_codigo)
WHERE pes_cooperativa=$usuario_cooperativa 
and sai_tipo=3
ORDER BY pes_nome";
if (!$query = mysql_query($sql))
    die("Erro SQL 0: " . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["pes_codigo"];
    if ($codigo == $filtro_fornecedor)
        $tpl_filtro->block("BLOCK_OPTION_SELECIONADO");
    $tpl_filtro->OPTION_VALOR = $dados["pes_codigo"];
    $tpl_filtro->OPTION_TEXTO = $dados["pes_nome"];
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

//Bot�o Pesquisar
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_BOTAOPADRAO_SUBMIT");
$tpl4->block("BLOCK_BOTAOPADRAO_PESQUISAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Bot�o Limpar
$tpl4->COLUNA_LINK_ARQUIVO = "saidas_devolucao.php";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl4->block("BLOCK_BOTAOPADRAO_LIMPAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Bot�o Cadastrar
if (($permissao_saidas_cadastrar_devolucao == 1) && ($usuario_quiosque != 0)) {
    $tpl4->COLUNA_LINK_ARQUIVO = "saidas_cadastrar.php?tiposaida=3";
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


//Listagem
$tpl2 = new Template("templates/lista2.html");
$tpl2->block("BLOCK_TABELA_CHEIA");


$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "Nº";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "DATA";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "23%";
$tpl2->CABECALHO_COLUNA_NOME = "MOTIVO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "30%";
$tpl2->CABECALHO_COLUNA_NOME = "DESCRIÇÃO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "SUPERVISOR";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "3";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "OPERAÇÃO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->block("BLOCK_CABECALHO_LINHA");
$tpl2->block("BLOCK_CABECALHO");

//Valida��o de filtros
$sql_filtro = "";
if (!empty($filtro_descricao))
    $sql_filtro = " and sai_descricao like '%$filtro_descricao%' ";
if (!empty($filtro_numero))
    $sql_filtro = " and sai_codigo = $filtro_numero ";
if (!empty($filtro_motivo))
    $sql_filtro = " and sai_saidajustificada = $filtro_motivo ";
if (!empty($filtro_supervisor))
    $sql_filtro = " and sai_caixa = $filtro_supervisor ";
if (!empty($filtro_fornecedor))
    $sql_filtro = " and ent_fornecedor = $filtro_fornecedor ";



$sql = "
    SELECT DISTINCT sai_codigo,sai_datacadastro,sai_horacadastro,saimot_nome,pes_nome,sai_totalbruto,sai_descricao,sai_status,pes_codigo
    FROM saidas
    JOIN saidas_motivo on (sai_saidajustificada=saimot_codigo)
    left join caixas_operacoes on (sai_caixaoperacaonumero=caiopo_numero)
    left JOIN pessoas on (caiopo_operador=pes_codigo)
    left JOIN saidas_produtos on (saipro_saida=sai_codigo)
    left JOIN entradas on (ent_codigo=saipro_lote)
    WHERE sai_tipo=3
    $sql_filtro
    ORDER BY sai_codigo DESC
";

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
$tpl2->PAGINAS = "$paginas";
$tpl2->PAGINAATUAL = "$paginaatual";
$tpl2->PASTA_ICONES = "$icones";
$tpl2->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";

$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal: " . mysql_error());
if (mysql_num_rows($query) == 0) {
    $tpl2->LINHA_NADA_COLSPAN = "11";
    $tpl2->block("BLOCK_LINHA_NADA");
}

//Listagem Linhas
while ($dados = mysql_fetch_assoc($query)) {
    $numero = $dados["sai_codigo"];
    $data = $dados["sai_datacadastro"];
    $hora = $dados["sai_horacadastro"];
    $motivo_nome = $dados["saimot_nome"];
    $descricao = $dados["sai_descricao"];
    $supervisor = $dados["pes_codigo"];
    $supervisor_nome = $dados["pes_nome"];
    $totalbruto = $dados["sai_totalbruto"];
    $status = $dados["sai_status"];
    $editar_ocultar = 0;

    //C�digo
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = "$numero";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Data do cadastro
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "right";
    $tpl2->TEXTO = converte_data($data);
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Hora do cadastro
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = converte_hora($hora);
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Motivo
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$motivo_nome";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Descri��o
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$descricao";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Supervisor
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$supervisor_nome";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");



    //Opera�ões
    $tpl2->ICONES_CAMINHO = $icones;



    //Opera��o Ver
    $tpl2->COLUNA_TAMANHO = "35px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->CONTEUDO_LINK_ARQUIVO = "saidas_ver.php?codigo=$numero&tiposaida=3&ope=3&modal=1&passo=1";
    $tpl2->block("BLOCK_CONTEUDO_LINK_NOVAJANELA");
    $tpl2->block("BLOCK_CONTEUDO_LINK");
    $tpl2->block("BLOCK_OPERACAO_DETALHES_HABILITADO");
    $tpl2->block("BLOCK_OPERACAO_DETALHES");
    $tpl2->block("BLOCK_OPERACAO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA_OPERACAO");
    $tpl2->block("BLOCK_COLUNA");

    //Opera��o Editar
    $tpl2->COLUNA_TAMANHO = "35px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->CONTEUDO_LINK_ARQUIVO = "saidas_cadastrar.php?operacao=2&tiposaida=3&codigo=$numero";
    $tpl2->block("BLOCK_CONTEUDO_LINK");
    if ($permissao_saidas_editar_devolucao == 1) {
        if ($editar_ocultar == 1) {
            $tpl2->ICONES_TITULO = "$editar_ocultar_motivo";
            $tpl2->block("BLOCK_OPERACAO_EDITAR_TITULO");
            $tpl2->block("BLOCK_OPERACAO_EDITAR_DESABILITADO");
        } else {
            $tpl2->block("BLOCK_OPERACAO_EDITAR_HABILITADO");
            $tpl2->block("BLOCK_OPERACAO_EDITAR_TITULOPADRAO");
        }
    } else {
        $tpl2->block("BLOCK_OPERACAO_EDITAR_TITULOPADRAO");
        $tpl2->block("BLOCK_OPERACAO_EDITAR_DESABILITADO");
    }
    $tpl2->block("BLOCK_OPERACAO_EDITAR");
    $tpl2->block("BLOCK_OPERACAO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA_OPERACAO");
    $tpl2->block("BLOCK_COLUNA");

    //Opera��o Excluir    
    $tpl2->COLUNA_TAMANHO = "35px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->ICONES_CAMINHO = $icones;
    $tpl2->CONTEUDO_LINK_ARQUIVO = "saidas_deletar.php?codigo=$numero&tiposaida=3";
    $tpl2->block("BLOCK_CONTEUDO_LINK");
    if ($permissao_saidas_excluir_devolucao == 1) {
        if ($editar_ocultar == 1) {
            $tpl2->ICONES_TITULO = "$editar_ocultar_motivo";
            $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULO");
            $tpl2->block("BLOCK_OPERACAO_EXCLUIR_DESABILITADO");
        } else {
            $tpl2->block("BLOCK_OPERACAO_EXCLUIR_HABILITADO");
            $tpl2->block("BLOCK_OPERACAO_EDITAR_TITULOPADRAO");
        }
    } else {
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_DESABILITADO");
        $tpl2->block("BLOCK_OPERACAO_EDITAR_TITULOPADRAO");
    }
    $tpl2->block("BLOCK_OPERACAO_EXCLUIR");
    $tpl2->block("BLOCK_OPERACAO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA_OPERACAO");
    $tpl2->block("BLOCK_COLUNA");

    //Cor de fundo da linha
    if ($status == 2) {
        if ($usuario_codigo == $fornecedor) {
            $tpl2->LINHA_CLASSE = "tab_linhas tab_linhas_vermelho negrito";
            $tpl2->block("BLOCK_LINHA_DINAMICA");
        } else {
            $dataatual = date("Y-m-d");
            $horaatual = date("H:i:s");
            $tempo1 = $data . "_" . $hora;
            $tempo2 = $dataatual . "_" . $horaatual;
            $total_segundos = diferenca_entre_datahora($tempo1, $tempo2);
            if ($total_segundos > 5400) {
                $tpl2->LINHA_CLASSE = "tab_linhas tab_linhas_vermelho negrito";
                $tpl2->block("BLOCK_LINHA_DINAMICA");
            } else {
                $tpl2->LINHA_CLASSE = "tab_linhas tab_linhas_amarelo negrito";
                $tpl2->block("BLOCK_LINHA_DINAMICA");
            }
        }
    } else {
        $tpl2->block("BLOCK_LINHA_PADRAO");
    }



    $tpl2->block("BLOCK_LINHA");
}

$tpl2->block("BLOCK_CORPO");
$tpl2->block("BLOCK_LISTAGEM");

$tpl2->show();




include "rodape.php";
?>
