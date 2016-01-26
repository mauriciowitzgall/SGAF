<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_estoque_qtdide_definir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "estoque";
include "includes.php";


//TÍTULO GERAL DA PAGINA
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ESTOQUE";
$tpl_titulo->SUBTITULO = "POR QUANTIDADE IDEAL";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "estoque.png";
$tpl_titulo->show();

//FILTRO
$tpl_filtro = new Template("templates/filtro1.html");
$tpl_filtro->JS_CAMINHO="estoque_qtdideal.js";
$tpl_filtro->block("BLOCK_JS");
$tpl_filtro->FORM_ONLOAD = "";
$tpl_filtro->FORM_LINK = "estoque_qtdideal.php";
$tpl_filtro->FORM_NOME = "form_filtro";
$tpl_filtro->block("BLOCK_FORM");

$filtro_codigoproduto = $_POST["filtro_codigoproduto"];
$filtro_nomeproduto = $_POST["filtro_nomeproduto"];

//Filtro Código do produto
$tpl_filtro->CAMPO_TITULO = "Produto";
$tpl_filtro->block("BLOCK_CAMPO_TITULO");
$tpl_filtro->CAMPO_TIPO = "text";
$tpl_filtro->CAMPO_QTDCARACTERES = "4";
$tpl_filtro->CAMPO_NOME = "filtro_codigoproduto";
$tpl_filtro->CAMPO_TAMANHO = "4";
$tpl_filtro->CAMPO_DICA = "Nº";
$tpl_filtro->CAMPO_ONKEYUP = "valida_filtro_produto_codigo()";
$tpl_filtro->CAMPO_VALOR = $filtro_codigoproduto;
$tpl_filtro->block("BLOCK_CAMPO_PADRAO");
$tpl_filtro->block("BLOCK_CAMPO");
$tpl_filtro->CAMPO_TIPO = "text";
$tpl_filtro->CAMPO_QTDCARACTERES = "";
$tpl_filtro->CAMPO_ONKEYUP = "valida_filtro_produto_nome()";
$tpl_filtro->CAMPO_NOME = "filtro_nomeproduto";
$tpl_filtro->CAMPO_TAMANHO = "40";
$tpl_filtro->CAMPO_DICA = "Nome";
$tpl_filtro->CAMPO_VALOR = $filtro_nomeproduto;
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
$tpl4->COLUNA_LINK_ARQUIVO = "estoque_qtdideal.php";
$tpl4->COLUNA_LINK_TARGET = "";
$tpl4->COLUNA_TAMANHO = "";
$tpl4->COLUNA_ALINHAMENTO = "";
$tpl4->block("BLOCK_COLUNA_LINK");
$tpl4->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl4->block("BLOCK_BOTAOPADRAO_LIMPAR");
$tpl4->block("BLOCK_BOTAOPADRAO");
$tpl4->block("BLOCK_COLUNA");

$tpl4->block("BLOCK_LINHA");
$tpl4->block("BLOCK_BOTOES");
$tpl4->block("BLOCK_LINHAHORIZONTAL_EMBAIXO");
$tpl4->show();

//LISTAGEM
$tpl2 = new Template("templates/lista2.html");
$tpl2->block("BLOCK_TABELA_CHEIA");

$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "PRODUTO.";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "2";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "QUANTIDADE";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "3";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "QUANTIDADE IDEAL";
$tpl2->block("BLOCK_CABECALHO_COLUNA");
$tpl2->CABECALHO_COLUNA_COLSPAN = "";
$tpl2->CABECALHO_COLUNA_TAMANHO = "";
$tpl2->CABECALHO_COLUNA_NOME = "SALDO";
$tpl2->block("BLOCK_CABECALHO_COLUNA");

$tpl2->block("BLOCK_CABECALHO_LINHA");
$tpl2->block("BLOCK_CABECALHO");

 $sql_filtro = "";
  if (!empty($filtro_codigoproduto))
  $sql_filtro = " and pro_codigo=$filtro_codigoproduto ";
  if (!empty($filtro_nomeproduto))
  $sql_filtro = " and pro_nome like '%$filtro_nomeproduto%'";
 

$sql = "
SELECT
    pro_codigo, 
    pro_nome, (
        SELECT SUM( etq_quantidade ) 
        FROM estoque
        WHERE etq_produto = pro_codigo
        and etq_quiosque=$usuario_quiosque
    ) as qtd, (
        SELECT qtdide_quantidade
        FROM quantidade_ideal
        WHERE qtdide_quiosque =$usuario_quiosque
        AND qtdide_produto = pro_codigo
    ) as qtdide, (
        SELECT SUM( etq_quantidade ) 
        FROM estoque
        WHERE etq_produto = pro_codigo
        and etq_quiosque=$usuario_quiosque
    ) / ( 
        SELECT qtdide_quantidade
        FROM quantidade_ideal
        WHERE qtdide_quiosque =$usuario_quiosque
        AND qtdide_produto = pro_codigo 
    ) *100 as saldo,
    protip_sigla
FROM produtos, quantidade_ideal, produtos_tipo
WHERE qtdide_quiosque =$usuario_quiosque
AND qtdide_produto = pro_codigo
AND pro_cooperativa =$usuario_cooperativa
and pro_tipocontagem=protip_codigo
$sql_filtro
ORDER BY saldo
";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Parte1:" . mysql_error());
$linhas = mysql_num_rows($query);
$sql5 = "
SELECT
    pro_codigo, 
    protip_codigo,
    pro_nome, (
        SELECT SUM( etq_quantidade ) 
        FROM estoque
        WHERE etq_produto = pro_codigo
        and etq_quiosque=$usuario_quiosque
    ) AS qtd,
    protip_sigla
FROM produtos
join produtos_tipo on (pro_tipocontagem=protip_codigo)
WHERE pro_cooperativa =$usuario_cooperativa
AND pro_codigo NOT IN (
    SELECT qtdide_produto
    FROM quantidade_ideal
    WHERE qtdide_quiosque =$usuario_quiosque
)
$sql_filtro
ORDER BY pro_nome
";
$query5 = mysql_query($sql5);
if (!$query5)
    die("Erro SQL Parte2:" . mysql_error());
$linhas5 = mysql_num_rows($query5);

if ((mysql_num_rows($query) == 0) && (mysql_num_rows($query5) == 0)) {
    $tpl2->LINHA_NADA_COLSPAN = "8";
    $tpl2->block("BLOCK_LINHA_NADA");
} else {
    $cont = 0;
    for ($i = 1; $i <= 2; $i++) {
        if ($i == 1)
            $query1 = $query;
        if ($i == 2)
            $query1 = $query5;
        while ($dados = mysql_fetch_assoc($query1)) {
            $codigo_produto = $dados["pro_codigo"];
            $nome_produto = $dados["pro_nome"];
            $qtd = $dados["qtd"];
            $qtdide = $dados["qtdide"];
            $sigla = $dados["protip_sigla"];
            $saldo = $dados["saldo"];
            $tipocontagem = $dados["protip_codigo"];

            if ($i == 1) {
                if (($saldo>=0)&&($saldo<=25)) {
                    $tpl2->LINHA_CLASSE = "tab_linhas_vermelho1";
                } else if (($saldo>25)&&($saldo<=50)) {
                    $tpl2->LINHA_CLASSE = "tab_linhas_vermelho2";
                } else if (($saldo>50)&&($saldo<=75)) {
                    $tpl2->LINHA_CLASSE = "tab_linhas_vermelho3";
                } else if (($saldo>75)&&($saldo<=100)) {
                    $tpl2->LINHA_CLASSE = "tab_linhas_vermelho4";
                } else if (($saldo>100)&&($saldo<=125)) {
                    $tpl2->LINHA_CLASSE = "tab_linhas_azul1";
                } else if (($saldo>125)&&($saldo<=150)) {
                    $tpl2->LINHA_CLASSE = "tab_linhas_azul2";
                } else if (($saldo>150)&&($saldo<=175)) {
                    $tpl2->LINHA_CLASSE = "tab_linhas_azul3";
                } else if (($saldo>175)&&($saldo<=200)) {
                    $tpl2->LINHA_CLASSE = "tab_linhas_azul4";
                } else {
                    $tpl2->LINHA_CLASSE = "tab_linhas";
                }
                
                $tpl2->block("BLOCK_LINHA_DINAMICA");
            } else {
                $tpl2->block("BLOCK_LINHA_PADRAO");
            }

            //Produto
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = "$codigo_produto";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = "$nome_produto";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");

            //Quantidade   
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            if ($tipocontagem==2)
                $tpl2->TEXTO = number_format($qtd, 3, ',', '.');
            else 
                $tpl2->TEXTO = number_format($qtd, 0, '', '.');
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = "$sigla";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");

            //Quantidade Ideal           
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            $tpl2->TEXTO = number_format($qtdide, 2, ',', '.');
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "";
            $tpl2->TEXTO = "$sigla";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");

            $tpl2->COLUNA_TAMANHO = "50px";
            $tpl2->COLUNA_ALINHAMENTO = "center";
            $tpl2->CONTEUDO_LINK_ARQUIVO = "estoque_qtdideal_definir.php?codigo=$codigo_produto&qtdide=$qtdide";
            $tpl2->block("BLOCK_CONTEUDO_LINK");
            $tpl2->ICONE_TAMANHO = "15px";
            $tpl2->ICONE_CAMINHO = "$icones";
            $tpl2->ICONE_NOMEARQUIVO = "atualizar.png";
            $tpl2->ICONE_DICA = "Definir Quantidade Ideal";
            $tpl2->ICONE_NOMEALTERNATIVO = "";
            $tpl2->block("BLOCK_ICONE");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");

            //Saldo
            $tpl2->COLUNA_TAMANHO = "";
            $tpl2->COLUNA_ALINHAMENTO = "right";
            if (($qtd!=0) && ($qtdide!=0))
                $saldo = $qtd / $qtdide * 100;
            else
                $saldo=0;
            $tpl2->TEXTO = number_format($saldo, 2, ',', '.') . "%";
            $tpl2->block("BLOCK_TEXTO");
            $tpl2->block("BLOCK_CONTEUDO");
            $tpl2->block("BLOCK_COLUNA");


            $tpl2->block("BLOCK_LINHA");
        }
    }
}

$tpl2->block("BLOCK_CORPO");
$tpl2->block("BLOCK_LISTAGEM");

$tpl2->show();




include "rodape.php";
?>
