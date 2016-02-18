<?php
$tipopagina = "produtos";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

$produto=$_GET["produto"];
if ($produto=="") { echo "Não foi recebido parametro do produto"; exit;  }

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PRODUTOS";
$tpl_titulo->SUBTITULO = "LISTA DE PORÇÕES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos_porcoes.png";
$tpl_titulo->show();

$tpl = new Template("templates/listagem_2.html");

//TOPO Produto 
$tpl->CAMPO_TITULO = "Produto";
$sql = "SELECT pro_nome,protip_nome  FROM produtos JOIN produtos_tipo on (pro_tipocontagem=protip_codigo) WHERE pro_codigo=$produto";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL 11: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$tpl->CAMPO_VALOR = $dados['pro_nome'];
$tpl->CAMPO_TAMANHO = "35";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//TOPO Tipo contagem
$tpl->CAMPO_TITULO = "Tipo de Contagem";
$tpl->CAMPO_VALOR = $dados['protip_nome'];
$tpl->CAMPO_TAMANHO = "20";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Botão Cadastrar
IF ($permissao_produtos_cadastrar == 1) {
    $tpl->LINK = "produtos_porcoes_cadastrar.php?produto=$produto&operacao=cadastrar";
    $tpl->BOTAO_NOME = "CADASTRAR";
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
    $tpl->block("BLOCK_FILTRO");
}



//Numero
$tpl->CABECALHO_COLUNA_TAMANHO="30px";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="Nº";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Nome da porção
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="NOME DA PORÇÃO";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Quantidade
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="2";
$tpl->CABECALHO_COLUNA_NOME="QUANTIDADE";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor Unitário Referencial
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="VALOR UNITÁRIO REFERENCIAL";
$tpl->block("BLOCK_LISTA_CABECALHO");


//Operacoes
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="3";
$tpl->CABECALHO_COLUNA_NOME="OPERAÇOES";
$tpl->block("BLOCK_LISTA_CABECALHO");

$sql="
    SELECT * 
    FROM produtos_porcoes 
    JOIN produtos on (pro_codigo=propor_produto) 
    JOIN produtos_tipo on (protip_codigo=pro_tipocontagem)
    WHERE pro_codigo=$produto $sql_filtro 
    ORDER BY propor_nome ASC
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
$tpl->PAGINAS = "$paginas";
$tpl->PAGINAATUAL = "$paginaatual";
$tpl->PASTA_ICONES = "$icones";
$tpl->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";


//Inicio tuplas
$cont=0;
while ($dados=  mysql_fetch_assoc($query)) {
    $numero= $dados["propor_codigo"];
    $nome= $dados["propor_nome"];
    $quantidade= $dados["propor_quantidade"];
    $valuniref= $dados["propor_valuniref"];
    $usuarioquecadastrou= $dados["propor_usuarioquecadastrou"];
    $datacadastro= $dados["propor_datacadastro"];
    $tipocontagem= $dados["pro_tipocontagem"];
    $tipocontagem_sigla= $dados["protip_sigla"];
    
    //Tupla Nº
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$numero";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Tupla Nome
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$nome";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Tupla Quantidade
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    if (($tipocontagem==2)||($tipocontagem==3)) {
        $tpl->LISTA_COLUNA_VALOR=  number_format($quantidade,3,',','.');
    } else {
        $tpl->LISTA_COLUNA_VALOR=  number_format($quantidade,0,'','.');
    }
    $tpl->block("BLOCK_LISTA_COLUNA");
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  "$tipocontagem_sigla";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Tupla Valor unitário referencial
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "R$ ". number_format($valuniref,2,',','.');
    $tpl->block("BLOCK_LISTA_COLUNA");


    //Detalhes
    $tpl->LINK="produtos_porcoes_cadastrar.php";
    $tpl->CODIGO="$numero";
    $tpl->LINK_COMPLEMENTO="operacao=ver&produto=$produto";
    $tpl->ICONE_ARQUIVO="$icones";
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DETALHES");

    //Editar
    $tpl->LINK="produtos_porcoes_cadastrar.php";
    $tpl->CODIGO="$numero";
    $tpl->LINK_COMPLEMENTO="operacao=editar&produto=$produto";
    $tpl->ICONE_ARQUIVO="$icones";
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR");
    //$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR_DESABILITADO");

    //Excluir
    $tpl->LINK="produtos_porcoes_deletar.php";
    $tpl->CODIGO="$numero";
    $tpl->LINK_COMPLEMENTO="operacao=excluir&produto=$produto";
    $tpl->ICONE_ARQUIVO="$icones";
    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR");
    $tpl->ICONE_ARQUIVO="";    
    
    
    
    $tpl->block("BLOCK_LISTA"); 
    $cont++;
}

if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}

//Botão Voltar
$tpl->LINK_VOLTAR="produtos.php";
$tpl->block("BLOCK_RODAPE_BOTAO_VOLTAR");
$tpl->block("BLOCK_RODAPE_BOTAO");
$tpl->block("BLOCK_RODAPE_BOTOES");


$tpl->show();

include "rodape.php";

?>