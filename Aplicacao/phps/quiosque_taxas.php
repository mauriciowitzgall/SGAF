<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_quiosque_vertaxas <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "quiosques";
include "includes.php";
include "controller/classes.php";

$quiosque = $_GET["quiosque"];


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "TAXAS";
$tpl_titulo->SUBTITULO = "LISTA DE TAXAS GLOBAIS DO QUIOSQUE";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques_taxas.png";
$tpl_titulo->show();

$tpl = new Template("templates/cadastro1.html");
$tpl->FORM_NOME = "form1";
//$tpl->FORM_TARGET = "";
//$tpl->FORM_LINK = "";
//$tpl->block("BLOCK_FORM");
//Filtro Quiosque Desabilitado

$sql = "select qui_nome from quiosques where qui_codigo=$quiosque";
$query = mysql_query($sql);
if (!$query)
    die("Erro1: " . mysql_error());
$array = mysql_fetch_assoc($query); 
$quiosque_nome=$array["qui_nome"];

$tpl->COLUNA_ALINHAMENTO = "right";
$tpl->COLUNA_TAMANHO = "100px";
$tpl->TITULO = "Quiosque";
$tpl->block("BLOCK_TITULO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->COLUNA_ALINHAMENTO = "";
$tpl->COLUNA_TAMANHO = "";
$tpl->CAMPO_TIPO = "text";
$tpl->CAMPO_DICA = "";
$tpl->CAMPO_NOME = "quiosque";
$tpl->CAMPO_ID = "quiosque";
$tpl->CAMPO_AOCLICAR = "";
$tpl->CAMPO_ONKEYUP = "";
$tpl->CAMPO_ONBLUR = "";
$tpl->CAMPO_VALOR = "$quiosque_nome";
$tpl->CAMPO_TAMANHO = "35";
//$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_DESABILITADO");
$tpl->block("BLOCK_CAMPO_PADRAO");
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->TEXTO_NOME = "";
$tpl->TEXTO_ID = "";
$tpl->TEXTO_CLASSE = "dicacampo";
$tpl->TEXTO_VALOR = "";
$tpl->block("BLOCK_TEXTO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");
$tpl->show();
echo "<br>";

//Pega os tipos de negociação do quiosque
$sql11 = "SELECT quitipneg_tipo FROM quiosques_tiponegociacao WHERE quitipneg_quiosque=$quiosque";
$query11 = mysql_query($sql11);
if (!$query11)
    die("Erro1: " . mysql_error()); 
$quiosque2_consignacao=0;
$quiosque2_revenda=0;
while ($dados11 = mysql_fetch_array($query11)) {
    $tipon = $dados11[0];
    if ($tipon == 1)
        $quiosque2_consignacao = 1;
    IF ($tipon == 2)
        $quiosque2_revenda = 1;
}

if ($quiosque2_consignacao == 1) {


//Título Consignação
    $tpl = new Template("templates/tituloemlinha_2.html");
    $tpl->block("BLOCK_TITULO");
    $tpl->LISTA_TITULO = "CONSIGNAÇÃO";
    $tpl->block("BLOCK_QUEBRA2");
    $tpl->show();


//Texto descritivo Dados
    $tpl = new Template("templates/cadastro1.html");
    $tpl->FORM_NOME = "form1";
    $tpl->COLUNA_COLSPAN = "2";
    $tpl->TEXTO_NOME = "";
    $tpl->TEXTO_ID = "";
    $tpl->TEXTO_CLASSE = "";
    $tpl->TEXTO_VALOR = "Neste modelo, o fornecedor deixa o produto para vender no quiosque. Em um dia específico do mês o fornecedor vai ao quiosque para realizar um acerto, que nada mais é, do que contar todos os produtos que foram vendidos desde o ultimo acerto, onde um percentual desse valor é descontado. Aqui abaixo é possível incluir quantas taxas forem desejadas afim de no momento do acerto o sistema realizar tais descontos";
    $tpl->block("BLOCK_TEXTO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
    $tpl->show();


    //Botão Cadastrar
    $tpl4 = new Template("templates/botoes1.html");
    $tpl4->COLUNA_TAMANHO = "1000px";
    $tpl4->COLUNA_ALINHAMENTO = "right";
    $tpl4->BOTAO_TIPO = "BUTTON";
    $tpl4->BOTAO_VALOR = "INCLUIR TAXA CONSIGNAÇÃO";
    $tpl4->BOTAOPADRAO_CLASSE = "botaopadraocadastrar";
    if (($permissao_taxas_aplicar==1)&&($quiosque==$usuario_quiosque)) {
        $tpl4->COLUNA_LINK_ARQUIVO = "quiosque_taxas_cadastrar.php?ope=1&qui=$quiosque&tipo=1";
        $tpl4->COLUNA_LINK_TARGET = "";
        $tpl4->block("BLOCK_COLUNA_LINK");
    } else {
        $tpl4->block("BLOCK_BOTAO_DESABILITADO");
    }
    $tpl4->block("BLOCK_BOTAO_PADRAO");
    $tpl4->block("BLOCK_BOTAOPADRAO_AUTOFOCO");
    $tpl4->block("BLOCK_BOTAO");
    $tpl4->block("BLOCK_COLUNA");
    $tpl4->block("BLOCK_LINHA");
    $tpl4->block("BLOCK_BOTOES");
    $tpl4->block("BLOCK_LINHAHORIZONTAL_EMBAIXO");
    $tpl4->show();    
    

//Lista Consinação
    $tpl2 = new Template("templates/lista2.html");
    $tpl2->block("BLOCK_TABELA_CHEIA");

    $tpl2->CABECALHO_COLUNA_COLSPAN = "3";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "TAXA";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "TIPO";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "% DESCONTADO";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");

    $tpl2->block("BLOCK_CABECALHO_LINHA");
    $tpl2->block("BLOCK_CABECALHO");

    $sql = "
    SELECT *
    FROM quiosques_taxas
    JOIN taxas ON (tax_codigo=quitax_taxa)
    WHERE quitax_quiosque=$quiosque
    AND tax_tiponegociacao=1
    ORDER BY tax_quiosque
";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro1: " . mysql_error());
    $taxa_percentual_acumulado = 0;
    if (mysql_num_rows($query) == 0) {
        $tpl2->LINHA_NADA_COLSPAN = "11";
        $tpl2->block("BLOCK_LINHA_NADA");
    } else {
        while ($dados = mysql_fetch_array($query)) {


            $taxa_nome = $dados["tax_nome"];
            $taxa_cooperativa = $dados["tax_cooperativa"];
            $taxa_quiosque = $dados["tax_quiosque"];
            $taxa_percentual = $dados["quitax_valor"];
            $taxa_codigo = $dados["tax_codigo"];
            $taxa_descricao = $dados["tax_descricao"];

            //Taxa
            $tpl2->COLUNA_TAMANHO = "50px";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = "$taxa_codigo";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "200px";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = "$taxa_nome";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "500px";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = "$taxa_descricao";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");


            //Tipo de taxa
            $tpl2->COLUNA_TAMANHO = "50px";
            $tpl2->COLUNA_ALINHAMENTO = "center";
            $tpl2->ICONE_TAMANHO = "18px";
            $tpl2->ICONE_CAMINHO = "$icones";
            if ($taxa_quiosque == 0) {
                $tpl2->ICONE_NOMEARQUIVO = "cooperativas.png";
                $tpl2->ICONE_DICA = "Taxa Global. Todos quiosques desta cooperativa";
                $tpl2->ICONE_NOMEALTERNATIVO = "Global";
            } else {
                $tpl2->ICONE_NOMEARQUIVO = "quiosques.png";
                $tpl2->ICONE_DICA = "Taxa Específica. Apenas este quiosque";
                $tpl2->ICONE_NOMEALTERNATIVO = "Específica";
            }

            $tpl2->block("BLOCK_ICONE");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");



            //Percentual
            $tpl2->COLUNA_TAMANHO = "150px";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = number_format($taxa_percentual, 2, ',', '') . " %";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");


            //Operações
            $tpl2->ICONES_CAMINHO = $icones;

            //Operação Excluir  
            $tpl2->COLUNA_TAMANHO = "35px";
            $tpl2->COLUNA_ALINHAMENTO = "center";
            $tpl2->ICONES_CAMINHO = $icones;
            if ($permissao_taxas_aplicar==1) {
                $tpl2->block("BLOCK_OPERACAO_EXCLUIR_HABILITADO");
                $tpl2->CONTEUDO_LINK_ARQUIVO = "quiosque_taxas_deletar.php?qui=$quiosque&taxa=$taxa_codigo";
                $tpl2->block("BLOCK_CONTEUDO_LINK");
                $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULOPADRAO");
            } else {
                //$tpl2->CONTEUDO_LINK_ARQUIVO = "";
                //$tpl2->block("BLOCK_CONTEUDO_LINK");     
                $tpl2->ICONES_TITULO = "Somente um gestor ou administrador pode excluir essa taxa!";
                $tpl2->block("BLOCK_OPERACAO_EXCLUIR_DESABILITADO");
                $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULO");
            }
            $tpl2->block("BLOCK_OPERACAO_EXCLUIR");
            $tpl2->block("BLOCK_OPERACAO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA_OPERACAO");
            $tpl2->block("BLOCK_COLUNA");


            $tpl2->block("BLOCK_LINHA_PADRAO");
            $tpl2->block("BLOCK_LINHA");
            $taxa_percentual_acumulado+=$taxa_percentual;
        }
    }

    $tpl2->block("BLOCK_CORPO");
    //Rodapé
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "";
    $tpl2->RODAPE_COLUNA_COLSPAN = "";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $tpl2->RODAPE_COLUNA_NOME = "";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "left";
    $tpl2->RODAPE_COLUNA_COLSPAN = "";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $tpl2->RODAPE_COLUNA_NOME = "Fornecedor";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "";
    $tpl2->RODAPE_COLUNA_COLSPAN = "";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $tpl2->RODAPE_COLUNA_NOME = "";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    //Tipo de taxa
    $tpl2->RODAPE_COLUNA_TAMANHO = "50px";
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "center";
    $tpl2->ICONE_RODAPE_TAMANHO = "18px";
    $tpl2->ICONE_RODAPE_CAMINHO = "$icones";
    $tpl2->ICONE_RODAPE_NOMEARQUIVO = "quiosques.png";
    $tpl2->ICONE_RODAPE_DICA = "Taxa Específica. Apenas este quiosque";
    $tpl2->ICONE_RODAPE_NOMEALTERNATIVO = "Específica";
    $tpl2->block("BLOCK_RODAPE_ICONE");
    $tpl2->block("BLOCK_RODAPE_CONTEUDO");
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "right";
    $tpl2->RODAPE_COLUNA_COLSPAN = "";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $taxa_percentual_quiosque = 100 - $taxa_percentual_acumulado;
    $tpl2->RODAPE_COLUNA_NOME = number_format($taxa_percentual_quiosque, 2, ',', '') . " %";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "";
    $tpl2->RODAPE_COLUNA_COLSPAN = "2";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $tpl2->RODAPE_COLUNA_NOME = "";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->block("BLOCK_RODAPE_LINHA");
    $tpl2->block("BLOCK_RODAPE");
    $tpl2->block("BLOCK_LISTAGEM");

    $tpl2->block("BLOCK_QUEBRA");
    $tpl2->block("BLOCK_LINHAHORIZONTAL_EMBAIXO");

    $tpl2->show();
}


if ($quiosque2_revenda == 1) {


    //Título REVENDAS
    $tpl = new Template("templates/tituloemlinha_2.html");
    $tpl->block("BLOCK_TITULO");
    $tpl->LISTA_TITULO = "REVENDAS";
    $tpl->block("BLOCK_QUEBRA2");
    $tpl->show();


    //Texto descritivo Dados
    $tpl = new Template("templates/cadastro1.html");
    $tpl->FORM_NOME = "form1";
    $tpl->COLUNA_COLSPAN = "2";
    $tpl->TEXTO_NOME = "";
    $tpl->TEXTO_ID = "";
    $tpl->TEXTO_CLASSE = "";
    $tpl->TEXTO_VALOR = "Neste modelo, cada produto em uma entrada tem um valor de custo e um valor de venda, a diferença entre estes valores chamamos de lucro ou taxa administrativa. Aqui é onde é possível inserir taxas que serão descontadas a partir do total da taxa administrativa gerada entre duas datas quaisquer.";
    $tpl->block("BLOCK_TEXTO");
    $tpl->block("BLOCK_CONTEUDO");
    $tpl->block("BLOCK_COLUNA");
    $tpl->block("BLOCK_LINHA");
    $tpl->show();

    
    //Botão Cadastrar
    $tpl4 = new Template("templates/botoes1.html");
    $tpl4->COLUNA_TAMANHO = "1000px";
    $tpl4->COLUNA_ALINHAMENTO = "right";
    $tpl4->BOTAO_TIPO = "BUTTON";
    $tpl4->BOTAO_VALOR = "INCLUIR TAXA REVENDA";
    $tpl4->BOTAOPADRAO_CLASSE = "botaopadraocadastrar";
    if ($permissao_taxas_aplicar==1) {
        $tpl4->COLUNA_LINK_ARQUIVO = "quiosque_taxas_cadastrar.php?ope=1&qui=$quiosque&tipo=2";
        $tpl4->COLUNA_LINK_TARGET = "";
        $tpl4->block("BLOCK_COLUNA_LINK");
    } else {
        $tpl4->block("BLOCK_BOTAO_DESABILITADO");
    }
    $tpl4->block("BLOCK_BOTAO_PADRAO");
    $tpl4->block("BLOCK_BOTAOPADRAO_AUTOFOCO");
    $tpl4->block("BLOCK_BOTAO");
    $tpl4->block("BLOCK_COLUNA");
    $tpl4->block("BLOCK_LINHA");
    $tpl4->block("BLOCK_BOTOES");
    $tpl4->block("BLOCK_LINHAHORIZONTAL_EMBAIXO");
    $tpl4->show();    

    //Lista Revendas
    $tpl2 = new Template("templates/lista2.html");
    $tpl2->block("BLOCK_TABELA_CHEIA");

    $tpl2->CABECALHO_COLUNA_COLSPAN = "3";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "TAXA";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "TIPO";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "% DESCONTADO";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->CABECALHO_COLUNA_COLSPAN = "";
    $tpl2->CABECALHO_COLUNA_TAMANHO = "";
    $tpl2->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
    $tpl2->block("BLOCK_CABECALHO_COLUNA");
    $tpl2->block("BLOCK_CABECALHO_LINHA");
    $tpl2->block("BLOCK_CABECALHO");

    $sql = "
    SELECT *
    FROM quiosques_taxas
    JOIN taxas ON (tax_codigo=quitax_taxa)
    WHERE quitax_quiosque=$quiosque
    AND tax_tiponegociacao=2
    ORDER BY tax_quiosque
";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro1: " . mysql_error());
    $taxa_percentual_acumulado = 0;

    if (mysql_num_rows($query) == 0) {
        $tpl2->LINHA_NADA_COLSPAN = "11";
        $tpl2->block("BLOCK_LINHA_NADA");
    } else {


        while ($dados = mysql_fetch_array($query)) {


            $taxa_nome = $dados["tax_nome"];
            $taxa_cooperativa = $dados["tax_cooperativa"];
            $taxa_quiosque = $dados["tax_quiosque"];
            $taxa_percentual = $dados["quitax_valor"];
            $taxa_codigo = $dados["tax_codigo"];

            //Taxa
            $tpl2->COLUNA_TAMANHO = "50px";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = "$taxa_codigo";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "200px";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = "$taxa_nome";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "500px";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = "$taxa_descricao";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");


            //Tipo de taxa
            $tpl2->COLUNA_TAMANHO = "50px";
            $tpl2->COLUNA_ALINHAMENTO = "center";
            $tpl2->ICONE_TAMANHO = "18px";
            $tpl2->ICONE_CAMINHO = "$icones";
            if ($taxa_quiosque == 0) {
                $tpl2->ICONE_NOMEARQUIVO = "cooperativas.png";
                $tpl2->ICONE_DICA = "Taxa Global. Todos quiosques desta cooperativa";
                $tpl2->ICONE_NOMEALTERNATIVO = "Global";
            } else {
                $tpl2->ICONE_NOMEARQUIVO = "quiosques.png";
                $tpl2->ICONE_DICA = "Taxa Específica. Apenas este quiosque";
                $tpl2->ICONE_NOMEALTERNATIVO = "Específica";
            }
            $tpl2->block("BLOCK_ICONE");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");



            //Percentual
            $tpl2->COLUNA_TAMANHO = "150px";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = number_format($taxa_percentual, 2, ',', '') . " %";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");


            //Operações
            $tpl2->ICONES_CAMINHO = $icones;
            
            //Operação Excluir  
            $tpl2->COLUNA_TAMANHO = "35px";
            $tpl2->COLUNA_ALINHAMENTO = "center";
            $tpl2->ICONES_CAMINHO = $icones;
            if ($permissao_taxas_aplicar==1) {
                $tpl2->block("BLOCK_OPERACAO_EXCLUIR_HABILITADO");
                $tpl2->CONTEUDO_LINK_ARQUIVO = "quiosque_taxas_deletar.php?qui=$quiosque&taxa=$taxa_codigo";
                $tpl2->block("BLOCK_CONTEUDO_LINK");
                $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULOPADRAO");
            } else {
                //$tpl2->CONTEUDO_LINK_ARQUIVO = "";
                //$tpl2->block("BLOCK_CONTEUDO_LINK");     
                $tpl2->ICONES_TITULO = "Somente um gestor ou administrador pode excluir essa taxa!";
                $tpl2->block("BLOCK_OPERACAO_EXCLUIR_DESABILITADO");
                $tpl2->block("BLOCK_OPERACAO_EXCLUIR_TITULO");
            }
            $tpl2->block("BLOCK_OPERACAO_EXCLUIR");
            $tpl2->block("BLOCK_OPERACAO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA_OPERACAO");
            $tpl2->block("BLOCK_COLUNA");


            $tpl2->block("BLOCK_LINHA_PADRAO");
            $tpl2->block("BLOCK_LINHA");
            $taxa_percentual_acumulado+=$taxa_percentual;
        }
    }

    $tpl2->block("BLOCK_CORPO");
//Rodapé
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "";
    $tpl2->RODAPE_COLUNA_COLSPAN = "";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $tpl2->RODAPE_COLUNA_NOME = "";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "left";
    $tpl2->RODAPE_COLUNA_COLSPAN = "";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $tpl2->RODAPE_COLUNA_NOME = "Quiosque";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "";
    $tpl2->RODAPE_COLUNA_COLSPAN = "";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $tpl2->RODAPE_COLUNA_NOME = "";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
//Tipo de taxa
    $tpl2->RODAPE_COLUNA_TAMANHO = "50px";
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "center";
    $tpl2->ICONE_RODAPE_TAMANHO = "18px";
    $tpl2->ICONE_RODAPE_CAMINHO = "$icones";
    $tpl2->ICONE_RODAPE_NOMEARQUIVO = "quiosques.png";
    $tpl2->ICONE_RODAPE_DICA = "Taxa Específica. Apenas este quiosque";
    $tpl2->ICONE_RODAPE_NOMEALTERNATIVO = "Específica";
    $tpl2->block("BLOCK_RODAPE_ICONE");
    $tpl2->block("BLOCK_RODAPE_CONTEUDO");
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "right";
    $tpl2->RODAPE_COLUNA_COLSPAN = "";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $taxa_percentual_quiosque = 100 - $taxa_percentual_acumulado;
    $tpl2->RODAPE_COLUNA_NOME = number_format($taxa_percentual_quiosque, 2, ',', '') . " %";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->RODAPE_COLUNA_ALINHAMENTO = "";
    $tpl2->RODAPE_COLUNA_COLSPAN = "2";
    $tpl2->RODAPE_COLUNA_TAMANHO = "";
    $tpl2->RODAPE_COLUNA_NOME = "";
    $tpl2->block("BLOCK_RODAPE_COLUNA");
    $tpl2->block("BLOCK_RODAPE_LINHA");
    $tpl2->block("BLOCK_RODAPE");

    $tpl2->block("BLOCK_LISTAGEM");
    $tpl2->show();
}

//BOTÕES DO FINAL DO FORMULÁRIO
$tpl2 = new Template("templates/botoes1.html");
$tpl2->block("BLOCK_LINHAHORIZONTAL_EMCIMA");
//Voltar
$tpl2->COLUNA_LINK_ARQUIVO = "quiosques.php";
$tpl2->block("BLOCK_COLUNA_LINK");
$tpl2->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl2->block("BLOCK_BOTAOPADRAO_VOLTAR");
$tpl2->block("BLOCK_BOTAOPADRAO");
$tpl2->block("BLOCK_COLUNA");
$tpl2->block("BLOCK_LINHA");
$tpl2->block("BLOCK_BOTOES");
$tpl2->show();

include "rodape.php";
?>
