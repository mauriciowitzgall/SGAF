<?php
$tipopagina = "";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

$saida=$_GET["codigo"];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "VENDAS DEVOLUÇÕES";
$tpl_titulo->SUBTITULO = "LISTA DE DEVOLUÇÕES DE UMA VENDA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "devolucoes.png";
$tpl_titulo->show();


$tpl = new Template("templates/listagem_2.html");

//Pega dados da venda para populas os campos de filtro desabilitados
$sql="SELECT * FROM saidas LEFT JOIN pessoas on sai_consumidor = pes_codigo WHERE sai_codigo=$saida";
if (!$query=mysql_query($sql)) die("Erro SQL Filtros que mostram dados da saída: " . mysql_error());
$dados=mysql_fetch_assoc($query);
$consumidor_nome=$dados["pes_nome"];
$datavenda=$dados["sai_datacadastro"];
$horavenda=$dados["sai_horacadastro"];
$descper=$dados["sai_descontopercentual"];
$totalbruto=$dados["sai_totalbruto"];
$tiposaida=$dados["sai_tipo"];

//Campo Filtro Código da venda
$tpl->CAMPO_TITULO = "Venda";
$tpl->CAMPO_VALOR = $saida;
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Campo Filtro Data da Venda
$tpl->CAMPO_TITULO = "Data da Venda";
$tpl->CAMPO_VALOR = converte_data($datavenda)." ".substr($horavenda,0,5);
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Campo Filtro Consumidor Nome
$tpl->CAMPO_TITULO = "Consumidor";
if ($consumidor_nome=="") $consumidor_nome="Cliente Geral";
$tpl->CAMPO_VALOR = $consumidor_nome;
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Campo Filtro Consumidor Nome
$tpl->CAMPO_TITULO = "Desconto";
$tpl->CAMPO_VALOR = str_replace(".", ",", $descper)."%";
$tpl->CAMPO_TAMANHO = "";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Botão Cadastrar nova Devolução
//Se todos os itens foram devolvidos então não pode mais registrar devoluções
$sql="SELECT sum(saidevpro_valtot) as total_devolvido FROM saidas_devolucoes_produtos WHERE saidevpro_saida=$saida";
if (!$query=mysql_query($sql)) die("Erro SQL: " . mysql_error());
$dados=mysql_fetch_assoc($query);
$total_devolvido=$dados["total_devolvido"];
if ($total_devolvido==$totalbruto) {
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO_DESABILITADO");
    $tpl->LINK = "";
    $tpl->BOTAO_NOME = "NOVA DEVOLUÇÃO";
    $tpl->BOTAO_TITULO = "Já foi devolvido todos os produtos!";
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
    $tpl->block("BLOCK_FILTRO_COLUNA");

} else {
    $tpl->LINK = "saidas_devolucoes_cadastrar.php?saida=$saida";
    $tpl->BOTAO_NOME = "NOVA DEVOLUÇÃO";
    $tpl->BOTAO_TITULO = "";
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
}
$tpl->block("BLOCK_FILTRO");


//INICIO DA LISTAGEM 

//Numero
$tpl->CABECALHO_COLUNA_TAMANHO="30px";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="Nº";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Data 
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="DATA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Operador
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="OPERADOR";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor inicial e final
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="2";
$tpl->CABECALHO_COLUNA_NOME="QTD. ITENS DEVOLVIDOS";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Nota Fiscal Emitida
if ($usamodulofiscal==1) {
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="";
    $tpl->CABECALHO_COLUNA_NOME="NFE";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}

//Remover
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="";
    $tpl->CABECALHO_COLUNA_NOME="OPERAÇÕES";
    $tpl->block("BLOCK_LISTA_CABECALHO");

//SQL Principal
$sql="
    SELECT * 
    FROM saidas_devolucoes 
    LEFT JOIN pessoas on (saidev_usuario=pes_codigo)
    WHERE saidev_saida=$saida 
    ORDER BY saidev_numero DESC
";


//PAGINAÇÃO
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
    $numero= $dados["saidev_numero"];
    $data= $dados["saidev_data"];
    $usuario= $dados["saidev_usuario"];
    $usuario_nome= $dados["pes_nome"];



    //Nº
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$numero";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Data
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  converte_datahora($data);
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Usuário
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  "$usuario_nome";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Quantidade de Itens devolvidos
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $sql2="SELECT count(saidevpro_itemdev) as qtd_itens FROM saidas_devolucoes_produtos WHERE saidevpro_saida=$saida AND saidevpro_numerodev=$numero";
    if (!$query2=mysql_query($sql2)) die("Erro SQL Pegar total de itens: " . mysql_error());
    $dados2=mysql_fetch_assoc($query2);
    $qtd_itens=$dados2["qtd_itens"];    
    $tpl->LISTA_COLUNA_VALOR= "($qtd_itens)";
    $tpl->block("BLOCK_LISTA_COLUNA");
    $tpl->IMAGEM_ALINHAMENTO="left";
    $tpl->IMAGEM_TAMANHO="15px";
    $tpl->IMAGEM_PASTA="$icones";
    $tpl->IMAGEM_TITULO="Ver";
    if ($qtd_itens>0) {
        $tpl->LINK="saidas_devolucoes_produtos.php?codigo=$numero";
        $tpl->IMAGEM_NOMEARQUIVO="procurar.png";
    } else {
        $tpl->LINK=" ";
        $tpl->IMAGEM_NOMEARQUIVO="procurar_desabilitado.png";
    }
    $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES"); 
        
    //NFE 
    if ($usamodulofiscal==1) {
        $tpl->IMAGEM_ALINHAMENTO="center";
        $tpl->IMAGEM_TAMANHO="18px";
        $tpl->IMAGEM_PASTA="$icones";
        $tpl->IMAGEM_TITULO="Nota Fiscal";
        //Verificar se foi emitido nota
        $sql3="SELECT * FROM nfe_vendas WHERE nfe_numero=$saida  AND nfe_devolucao=$numero";
        if (!$query3 = mysql_query($sql3)) die("Erro NFE Emitida: (((" . mysql_error().")))");
        $linhas3 = mysql_num_rows($query3);
        $dados3=mysql_fetch_assoc($query3);
        $numero_nota=$dados3["nfe_codigo"];
        if ($linhas3==0) $temnota=0; else  $temnota=1;
        if ($temnota==1) {
            $tpl->LINK="saidas_cadastrar_nfe_ver.php?numero_nota=$numero_nota";
            $tpl->LINK_TARGET="_blank";
            $tpl->IMAGEM_NOMEARQUIVO="nfe_ver3.png";
        } else {
            $tpl->LINK_TARGET="";
            $tpl->LINK="saidas_cadastrar_nfe.php?codigo=$saida&ope=3";
            $tpl->IMAGEM_NOMEARQUIVO="nfe_gerar3.png";
        }
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
        $tpl->block("BLOCK_LISTA_COLUNA_ICONES"); 
    }  

    //Remover devolução
    $tpl->IMAGEM_ALINHAMENTO="center";
    $tpl->LINK="saidas_devolucoes_deletar.php?devolucao=$numero&saida=$saida";
    $tpl->IMAGEM_TAMANHO="12px";
    $tpl->IMAGEM_PASTA="$icones";
    $tpl->IMAGEM_TITULO="Remover";
    //Verificar se foi emitido nota
    $sql3="SELECT * FROM nfe_vendas WHERE nfe_numero=$saida  AND nfe_devolucao=$numero";
    if (!$query3 = mysql_query($sql3)) die("Erro Botão remover devolucao: (((" . mysql_error().")))");
    $linhas3 = mysql_num_rows($query3);
    if ($linhas3==0) $temnota=0; else  $temnota=1;
    if ($temnota==1) {
        $tpl->IMAGEM_NOMEARQUIVO="remover_desabilitado.png"; 
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM_SEMLINK");
    }
    else {
        $tpl->IMAGEM_NOMEARQUIVO="remover.png";
        $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    }
    
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES"); 
   
    $tpl->block("BLOCK_LISTA"); 
    $cont++;
}

if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}

//Botão Voltar
$tpl->LINK_VOLTAR="saidas_ver.php?codigo=$saida&ope=3&tiposaida=$tiposaida&passo=1";
$tpl->block("BLOCK_RODAPE_BOTAO_VOLTAR");
$tpl->block("BLOCK_RODAPE_BOTAO");
$tpl->block("BLOCK_RODAPE_BOTOES");


$tpl->show();

include "rodape.php";

?>