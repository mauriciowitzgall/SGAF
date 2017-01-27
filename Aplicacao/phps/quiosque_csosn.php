<title>CSOSN</title>
<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
include "includes2.php"; 


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PRODUTOS";
$tpl_titulo->SUBTITULO = "CSOSN (Código de Situação da Operação - Simples Nacional)";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos.png";
$tpl_titulo->show();

//Listagem
$tpl_lista = new Template("templates/lista2.html");
$tpl_lista->block("BLOCK_TABELA_CHEIA");


//Cabeçalho
$tpl_lista->TEXTO = "ID";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->TEXTO = "Nome";
$tpl_lista->COLUNA_ALINHAMENTO = "center";
$tpl_lista->COLUNA_TAMANHO = "";
$tpl_lista->COLUNA_COLSPAN = "";
$tpl_lista->block("BLOCK_COLUNA_PADRAO");
$tpl_lista->block("BLOCK_TEXTO");
$tpl_lista->block("BLOCK_CONTEUDO");
$tpl_lista->block("BLOCK_COLUNA");
$tpl_lista->LINHA_CLASSE = "tab_cabecalho";
$tpl_lista->block("BLOCK_LINHA_DINAMICA");
$tpl_lista->block("BLOCK_LINHA");
$tpl_lista->block("BLOCK_CORPO");


//Tuplas
set_time_limit(0);
$sql = "SELECT * FROM nfe_csosn ORDER BY csosn_id ";
if (!$query = mysql_query($sql)) die("Erro SQL:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $codigo = $dados["csosn_codigo"];
    $id = $dados["csosn_id"];
    $nome = $dados["csosn_nome"];

    
    //ID
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "$id";
    $tpl_lista->COLUNA_ALINHAMENTO = "right";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");
    
    //Nome
    $tpl_lista->COLUNA_COLSPAN = "";
    $tpl_lista->TEXTO = "$nome";
    $tpl_lista->COLUNA_ALINHAMENTO = "left";
    $tpl_lista->block("BLOCK_COLUNA_PADRAO");
    $tpl_lista->block("BLOCK_TEXTO");
    $tpl_lista->block("BLOCK_CONTEUDO");
    $tpl_lista->block("BLOCK_COLUNA");

    $tpl_lista->block("BLOCK_LINHA");
}

if (mysql_num_rows($query) == 0) {
    $tpl_lista->LINHA_NADA_COLSPAN = "100";
    $tpl_lista->block("BLOCK_LINHA_NADA");
}
$tpl_lista->block("BLOCK_CORPO");
$tpl_lista->block("BLOCK_LISTAGEM");
$tpl_lista->show();

$tpl = new Template("templates/botoes1.html");
  
$tpl->block(BLOCK_LINHAHORIZONTAL_EMCIMA);

$tpl->COLUNA_TAMANHO="";
$tpl->COLUNA_ALINHAMENTO = "center";                
$tpl->block("BLOCK_COLUNA_LINK_FECHAR"); 
$tpl->COLUNA_LINK_CLASSE="link";
$tpl->block("BLOCK_COLUNA_LINK");  

      
$tpl->block("BLOCK_BOTAOPADRAO_SIMPLES");
$tpl->block("BLOCK_BOTAOPADRAO_FECHAR");
$tpl->block("BLOCK_BOTAOPADRAO");  
$tpl->block("BLOCK_COLUNA");
$tpl->block("BLOCK_LINHA");
$tpl->block("BLOCK_BOTOES");

$tpl->show();


?>
