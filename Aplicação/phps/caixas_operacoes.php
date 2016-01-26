<?php
$tipopagina = "caixas";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operacoes_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

$caixa=$_GET["codigo"];

//Pega dados da ultima operação
$sql="SELECT max(caiopo_numero) FROM caixas_operacoes WHERE caiopo_caixa=$caixa";
if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
$dados=mysql_fetch_array($query);
$numero_ultimo=$dados[0];
$sql="SELECT * FROM caixas_operacoes JOIN caixas on cai_codigo=caiopo_caixa WHERE caiopo_numero=$numero_ultimo";
if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
$dados=mysql_fetch_assoc($query);
$situacao_ultimo=$dados["cai_situacao"];
$operador_ultimo=$dados["caiopo_operador"];


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS OPERAÇÕES";
$tpl_titulo->SUBTITULO = "HISTÓRICO DE OPERAÇÕES DE CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixa_fluxo.png";
$tpl_titulo->show();

$tpl = new Template("templates/listagem_2.html");

//Campo Caixa no Topo
$tpl->CAMPO_TITULO = "Caixa";
$sql = "SELECT cai_nome FROM caixas WHERE cai_codigo=$caixa";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL 11: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$tpl->CAMPO_VALOR = $dados['cai_nome'];
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Botão cadastrar/abrir/encerrar caixa
$sql="SELECT cai_situacao FROM caixas WHERE cai_codigo=$caixa";
if (!$query=mysql_query($sql)) die("Erro SQL 2: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$situacao=$dados["cai_situacao"];
IF (($permissao_caixas_operacoes_abrir == 1)&&($situacao==2)) {
    $tpl->LINK = "caixas_operacoes_abrir.php?codigo=$caixa";
    $tpl->BOTAO_NOME = "ABRIR CAIXA";
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
} else if (($permissao_caixas_operacoes_encerrar == 1)&&($situacao==1)) {
    if (((($usuario_grupo==4)&&($operador_ultimo==$usuario_codigo))) || ($usuario_grupo==1) || ($usuario_grupo==3)) {
        $tpl->LINK = "caixas_operacoes_encerrar.php?codigo=$numero_ultimo";
        $tpl->BOTAO_NOME = "ENCERRAR CAIXA";
        $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");    
    }
}
$tpl->block("BLOCK_FILTRO_COLUNA");
$tpl->block("BLOCK_FILTRO");



//Numero
$tpl->CABECALHO_COLUNA_TAMANHO="30px";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="Nº";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Data Abertura
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="2";
$tpl->CABECALHO_COLUNA_NOME="DATAS DE ABERTURA E ENCERRAMENTO";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Operador
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="OPERADOR";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor inicial e final
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="2";
$tpl->CABECALHO_COLUNA_NOME="VALOR INICIAL E FINAL ";
$tpl->block("BLOCK_LISTA_CABECALHO");

/*
//Total Vendas
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="TOTAL VENDAS";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Saldo Troco
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="SALDO TROCO";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Saldo Vendas
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="SALDO VENDAS";
$tpl->block("BLOCK_LISTA_CABECALHO");
*/


//Diferença 
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="DIFERENÇA";
$tpl->block("BLOCK_LISTA_CABECALHO");


//Vendas
$tpl->CABECALHO_COLUNA_TAMANHO="30px";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="VENDAS";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Situação
$tpl->CABECALHO_COLUNA_TAMANHO="30px";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="SIT.";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Operacoes
if ($usuario_grupo<>4) {
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="1";
    $tpl->CABECALHO_COLUNA_NOME="OPERAÇÕES";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}  

$sql="SELECT * FROM caixas_operacoes WHERE caiopo_caixa=$caixa $sql_filtro ORDER BY caiopo_numero DESC";

//Paginação
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

$cont=0;
while ($dados=  mysql_fetch_assoc($query)) {
    $numero= $dados["caiopo_numero"];
    $dataini= $dados["caiopo_datahoraabertura"];
    $datafim= $dados["caiopo_datahoraencerramento"];
    $operador= $dados["caiopo_operador"];
    $valorinicial= $dados["caiopo_valorinicial"];
    $totalvendas= $dados["caiopo_totalvendas"];
    $totaltroco= $dados["caiopo_totaltroco"];
    $saldovendas= $dados["caiopo_saldovendas"];
    $diferenca= $dados["caiopo_diferenca"];
    $valorfinal= $dados["caiopo_valorfinal"];
    
    //Nº
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$numero";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Data Abertura
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  converte_datahora($dataini);
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Data encerramento
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    if (($situacao==1)&&($cont==0))
        $tpl->LISTA_COLUNA_VALOR=  "";
    else 
        $tpl->LISTA_COLUNA_VALOR=  converte_datahora($datafim);
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Operador
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $sql2="SELECT pes_nome FROM pessoas WHERE pes_codigo=$operador";
    if (!$query2=mysql_query($sql2)) die("Erro SQL 2: " . mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $operador_nome=$dados2["pes_nome"];   
    $tpl->LISTA_COLUNA_VALOR=$operador_nome;
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Valor Inicial do caixa
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "R$ " . number_format($valorinicial, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");
        
    //Valor Final
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    if (($situacao==1)&&($cont==0))
        $tpl->LISTA_COLUNA_VALOR=  "";
    else 
        $tpl->LISTA_COLUNA_VALOR=  "R$ " . number_format($valorfinal, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    /*
    //Total Vendas
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    if ($situacao==1)
        $tpl->LISTA_COLUNA_VALOR=  "";
    else 
        $tpl->LISTA_COLUNA_VALOR=  "R$ " . number_format($totalvendas, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Saldo Troco
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    if ($situacao==1)
        $tpl->LISTA_COLUNA_VALOR=  "";
    else 
        $tpl->LISTA_COLUNA_VALOR=  "R$ " . number_format($totaltroco, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Saldo Vendas
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    if (($situacao==1)&&($cont==0))
        $tpl->LISTA_COLUNA_VALOR=  "";
    else 
        $tpl->LISTA_COLUNA_VALOR=  "R$ " . number_format($saldovendas, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");
    */
    
    //Diferença
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    if ($diferenca > 0) {
        $tpl->LISTA_COLUNA_CLASSE="tabelalinhaazul";
    } else if ($diferenca<0) {
        $tpl->LISTA_COLUNA_CLASSE="tabelalinhavermelha";
    } else {
        $tpl->LISTA_COLUNA_CLASSE="";
    }
    $tpl->LISTA_COLUNA_TAMANHO="";
    if (($situacao==1)&&($cont==0))
        $tpl->LISTA_COLUNA_VALOR=  "";
    else 
        $tpl->LISTA_COLUNA_VALOR=  "R$ " . number_format($diferenca, 2, ',', '.');
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    
    //Ver Saidas
    $tpl->IMAGEM_ALINHAMENTO="center";
    $tpl->LINK="saidas.php?filtro_caixaoperacao=$numero";
    $tpl->IMAGEM_TAMANHO="15px";
    $tpl->IMAGEM_PASTA="$icones";
    $tpl->IMAGEM_NOMEARQUIVO="saidas_caixaoperacao.png";
    $tpl->IMAGEM_TITULO="Ver Vendas relacionadas";
    $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES"); 
    
    //Situação
    $sql2="SELECT caisit_nome FROM caixas_situacao WHERE caisit_codigo=$situacao";
    if (!$query2=mysql_query($sql2)) die("Erro SQL: ".mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $situacao_nome=$dados2["caisit_nome"];
    $tpl->IMAGEM_ALINHAMENTO="center";
    $tpl->LINK="";
    $tpl->IMAGEM_TAMANHO="15px";
    $tpl->IMAGEM_PASTA="$icones";
    if ($cont==0) {
        if ($situacao==1)
            $tpl->IMAGEM_NOMEARQUIVO="bandeira2_verde.png";
        else 
            $tpl->IMAGEM_NOMEARQUIVO="bandeira2_vermelha.png";
    } else {
        $tpl->IMAGEM_NOMEARQUIVO="bandeira2_vermelha.png";
    }
        
    $tpl->IMAGEM_TITULO="Aberto";
    $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES"); 
    
    
    //Detalhes
    if ($usuario_grupo<>4) {
        //Detalhes
        $tpl->LINK="caixas_operacoes_ver.php";
        $tpl->CODIGO="$numero";
        $tpl->LINK_COMPLEMENTO="operacao=ver";
        $tpl->ICONE_ARQUIVO="$icones";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DETALHES");

    }
    $tpl->block("BLOCK_LISTA"); 
    $cont++;
}

if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}

//Botão Voltar
$tpl->LINK_VOLTAR="caixas.php";
$tpl->block("BLOCK_RODAPE_BOTAO_VOLTAR");
$tpl->block("BLOCK_RODAPE_BOTAO");
$tpl->block("BLOCK_RODAPE_BOTOES");


$tpl->show();

include "rodape.php";

?>