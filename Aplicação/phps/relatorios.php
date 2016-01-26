<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_relatorios_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "relatorios";
include "includes.php";


//TÍTULO GERAL DA PAGINA
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "RELATÓRIOS  ";
$tpl_titulo->SUBTITULO = "PEQUISA/LISTAGEM";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "relatorios.png";
$tpl_titulo->show();



//FILTRO
$tpl_filtro = new Template("templates/filtro1.html");
$tpl_filtro->FORM_ONLOAD = "";
$tpl_filtro->FORM_LINK = "relatorios.php";
$tpl_filtro->FORM_NOME = "form_filtro";
$tpl_filtro->block("BLOCK_FORM");

$filtro_numero = $_POST["filtro_numero"];
$filtro_nome = $_POST["filtro_nome"];
$filtro_descricao = $_POST["filtro_descricao"];

//Filtro Numero
$tpl_filtro->CAMPO_TITULO = "Nº";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "number";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_numero";
$tpl_filtro->CAMPO_TAMANHO = "";
$tpl_filtro->CAMPO_VALOR = $filtro_numero;
$tpl_filtro->block("BLOCK_CAMPO_PADRAO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

//Filtro Nome
$tpl_filtro->CAMPO_TITULO = "Nome";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "text";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_nome";
$tpl_filtro->CAMPO_TAMANHO = "";
$tpl_filtro->CAMPO_VALOR = $filtro_nome;
$tpl_filtro->block("BLOCK_CAMPO_PADRAO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->block("BLOCK_ESPACO");
$tpl_filtro->block("BLOCK_COLUNA");

//Filtro Descrição
$tpl_filtro->CAMPO_TITULO = "Descrição";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "text";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_NOME = "filtro_descricao";
$tpl_filtro->CAMPO_TAMANHO = "";
$tpl_filtro->CAMPO_VALOR = $filtro_descricao;
$tpl_filtro->block("BLOCK_CAMPO_PADRAO");
$tpl_filtro->block("BLOCK_CAMPO");
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
$tpl4->COLUNA_LINK_ARQUIVO = "relatorios.php";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl4->block("BLOCK_BOTAOPADRAO_LIMPAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

//Botão Cadastrar
if ($permissao_relatorios_cadastrar == 1) {

    $tpl4->COLUNA_LINK_ARQUIVO = "relatorios_cadastrar.php";
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

$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "30px";
$tpl2->CABECALHO_COLUNA_NOME = "Nº";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "250px";
$tpl2->CABECALHO_COLUNA_NOME = "NOME";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "DESCRIÇÃO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
if ($permissao_relatorios_cadastrar == 1) {
    $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "180px";
    $tpl2->CABECALHO_COLUNA_NOME = "DATA CADASTRO";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
}
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "80px";
$tpl2->CABECALHO_COLUNA_NOME = "GRUPO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");

$oper = 1;
$oper_tamanho = 50;
if ($permissao_relatorios_editar == 1) {
    $oper++;
    $oper_tamanho = $oper_tamanho + 50;
}
if ($permissao_relatorios_excluir == 1) {
    $oper++;
    $oper_tamanho = $oper_tamanho + 50;
}


$tpl2->CABECALHO_COLUNA_COLSPAN = "$oper";
$tpl2->CABECALHO_COLUNA_TAMANHO = "$oper_tamanho"."px";
$tpl2->CABECALHO_COLUNA_NOME = "OPERAÇÃO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->block("BLOCK_CABECALHO_LINHA");
$tpl2->block("BLOCK_CABECALHO");

$sql_filtro = "";
if (!empty($filtro_numero))
    $sql_filtro = " and rel_codigo=$filtro_numero ";
if (!empty($filtro_nome))
    $sql_filtro = " and rel_nome like '%$filtro_nome%'";
if (!empty($filtro_descricao))
    $sql_filtro = " and rel_descricao like '%$filtro_descricao%'";
if ($usuario_grupo <> 1) {
    $sql_from = " JOIN relatorios_permissao on (relper_relatorio=rel_codigo) ";
    $sql_filtro = " and relper_grupo=$usuario_grupo";
}


$sql = "
SELECT DISTINCT rel_codigo, rel_nome, rel_descricao, rel_datacadastro,rel_horacadastro
FROM relatorios $sql_from 
WHERE 1 $sql_filtro 
ORDER BY rel_datacadastro DESC
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
    $tpl2->LINHA_NADA_COLSPAN = 4+$oper;
    $tpl2->block("BLOCK_LINHA_NADA");
}

while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["rel_codigo"];
    $nome = $dados["rel_nome"];
    $descricao = $dados["rel_descricao"];
    $data = converte_data($dados["rel_datacadastro"]);
    $hora = $dados["rel_horacadastro"];

    //Código
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$codigo";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Nome
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$nome";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Descrição
    $tpl2->COLUNA_TAMANHO = "";
    $tpl2->COLUNA_ALINHAMENTO = "";
    $tpl2->TEXTO = "$descricao";
    $tpl2->block("BLOCK_TEXTO");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Data Cadastro
    if ($permissao_relatorios_cadastrar == 1) {
        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "right";
        $tpl2->TEXTO = "$data";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");

        $tpl2->COLUNA_TAMANHO = "";
        $tpl2->COLUNA_ALINHAMENTO = "left";
        $tpl2->TEXTO = "$hora";
        $tpl2->block("BLOCK_TEXTO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA");
    }

    //Grupo de Permissões
    $tpl2->COLUNA_TAMANHO = "80px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->ICONE_TAMANHO = "12px";
    $tpl2->ICONE_CAMINHO = "$icones2";
    $sql2 = "
        SELECT gruper_codigo,gruper_nome
        FROM grupo_permissoes  
        WHERE gruper_codigo not in (7,1)
        ORDER BY gruper_codigo
    ";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro SQL" . mysql_error());
    while ($dados2 = mysql_fetch_assoc($query2)) {
        $tipo = $dados2["gruper_codigo"];
        if ($tipo == 2) {
            $sql3 = "
                SELECT *
                FROM relatorios_permissao
                WHERE relper_relatorio=$codigo
                and relper_grupo=$tipo
            ";
            $query3 = mysql_query($sql3);
            if (!$query3)
                die("Erro SQL" . mysql_error());
            $linhas3 = mysql_num_rows($query3);
            if ($linhas3 > 0)
                $tpl2->ICONE_NOMEARQUIVO = "presidente.png";
            else
                $tpl2->ICONE_NOMEARQUIVO = "presidente2.png";
            $tpl2->ICONE_DICA = "Presidente";
            $tpl2->ICONE_NOMEALTERNATIVO = "Presidente";
        } else if ($tipo == 3) {
            $sql3 = "
                SELECT *
                FROM relatorios_permissao
                WHERE relper_relatorio=$codigo
                and relper_grupo=$tipo
            ";
            $query3 = mysql_query($sql3);
            if (!$query3)
                die("Erro SQL" . mysql_error());
            $linhas3 = mysql_num_rows($query3);
            if ($linhas3 > 0)
                $tpl2->ICONE_NOMEARQUIVO = "supervisor.png";
            else
                $tpl2->ICONE_NOMEARQUIVO = "supervisor2.png";
            $tpl2->ICONE_DICA = "Supervisor";
            $tpl2->ICONE_NOMEALTERNATIVO = "Supervisor";
        } else if ($tipo == 4) {
            $sql3 = "
                SELECT *
                FROM relatorios_permissao
                WHERE relper_relatorio=$codigo
                and relper_grupo=$tipo
            ";
            $query3 = mysql_query($sql3);
            if (!$query3)
                die("Erro SQL" . mysql_error());
            $linhas3 = mysql_num_rows($query3);
            if ($linhas3 > 0)
                $tpl2->ICONE_NOMEARQUIVO = "vendedor.png";
            else
                $tpl2->ICONE_NOMEARQUIVO = "vendedor2.png";
            $tpl2->ICONE_DICA = "Vendedor";
            $tpl2->ICONE_NOMEALTERNATIVO = "Vendedor";
        } else if ($tipo == 5) {
            $sql3 = "
                SELECT *
                FROM relatorios_permissao
                WHERE relper_relatorio=$codigo
                and relper_grupo=$tipo
            ";
            $query3 = mysql_query($sql3);
            if (!$query3)
                die("Erro SQL" . mysql_error());
            $linhas3 = mysql_num_rows($query3);
            if ($linhas3 > 0)
                $tpl2->ICONE_NOMEARQUIVO = "fornecedor.png";
            else
                $tpl2->ICONE_NOMEARQUIVO = "fornecedor2.png";
            $tpl2->ICONE_DICA = "Fornecedor";
            $tpl2->ICONE_NOMEALTERNATIVO = "Fornecedor";
        }
        $tpl2->block("BLOCK_ICONE");
        $tpl2->block("BLOCK_CONTEUDO");
    }    
    $tpl2->block("BLOCK_COLUNA_ICONES");    
    $tpl2->block("BLOCK_COLUNA");

    //Operações
    $tpl2->ICONES_CAMINHO = $icones;

    //Icone Gerar
    $tpl2->COLUNA_TAMANHO = "35px";
    $tpl2->COLUNA_ALINHAMENTO = "center";
    $tpl2->block("BLOCK_COLUNA_OPERACAO");
    $tpl2->CONTEUDO_LINK_ARQUIVO = "relatorios_filtro.php?codigo=$codigo";
    //$tpl2->block("BLOCK_CONTEUDO_LINK_NOVAJANELA");
    $tpl2->block("BLOCK_CONTEUDO_LINK");
    $tpl2->ICONE_TAMANHO = "12px";
    $tpl2->ICONE_CAMINHO = "$icones";
    $tpl2->ICONE_NOMEARQUIVO = "relatorios2.png";
    $tpl2->ICONE_DICA = "Gerar";
    $tpl2->ICONE_NOMEALTERNATIVO = "Gerar";
    $tpl2->block("BLOCK_ICONE");
    $tpl2->block("BLOCK_CONTEUDO");
    $tpl2->block("BLOCK_COLUNA");

    //Operação Editar
    if ($permissao_relatorios_editar == 1) {
        $tpl2->COLUNA_TAMANHO = "35px";
        $tpl2->COLUNA_ALINHAMENTO = "center";
        $tpl2->CONTEUDO_LINK_ARQUIVO = "relatorios_cadastrar.php?operacao=editar&codigo=$codigo";
        $tpl2->block("BLOCK_CONTEUDO_LINK");
        $tpl2->block("BLOCK_OPERACAO_EDITAR_HABILITADO");
        $tpl2->block("BLOCK_OPERACAO_EDITAR_TITULOPADRAO");
        $tpl2->block("BLOCK_OPERACAO_EDITAR");
        $tpl2->block("BLOCK_OPERACAO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA_OPERACAO");
        $tpl2->block("BLOCK_COLUNA");
    }

    //Operação Excluir
    if ($permissao_relatorios_excluir == 1) {
        $tpl2->COLUNA_TAMANHO = "35px";
        $tpl2->COLUNA_ALINHAMENTO = "center";
        $tpl2->ICONES_CAMINHO = $icones;
        $tpl2->CONTEUDO_LINK_ARQUIVO = "relatorios_deletar.php?operacao=excluir&codigo=$codigo";
        $tpl2->block("BLOCK_CONTEUDO_LINK");
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_HABILITADO");
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULOPADRAO");
        $tpl2->block("BLOCK_OPERACAO_EXCLUIR");
        $tpl2->block("BLOCK_OPERACAO");
        $tpl2->block("BLOCK_CONTEUDO");
        $tpl2->block("BLOCK_COLUNA_OPERACAO");
        $tpl2->block("BLOCK_COLUNA");
    }

    $tpl2->block("BLOCK_LINHA_PADRAO");
    $tpl2->block("BLOCK_LINHA");
}

$tpl2->block("BLOCK_CORPO");
$tpl2->block("BLOCK_LISTAGEM");

$tpl2->show();




include "rodape.php";
?>
