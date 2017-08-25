<?php
$tipopagina = "caixas";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "LISTAGEM DE CAIXAS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixas.png";
$tpl_titulo->show();



$usacaixa=usamodulocaixa($usuario_quiosque);
if ($usacaixa!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

$tpl = new Template("templates/listagem_2.html");

//Botão Cadastrar
if ($usuario_grupo<>4) {
    $tpl->LINK="caixas_cadastrar.php?operacao=cadastrar";
    $tpl->BOTAO_NOME="CADASTRAR";
    $tpl->block("BLOCK_AUTOFOCO");
    //$tpl->block("BLOCK_RODAPE_BOTAO_MODELO_DESABILITADO");
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
    $tpl->block("BLOCK_FILTRO");
}

//Nome
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="NOME";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Local
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="LOCAL";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Operadores
if ($usuario_grupo<>4) {
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="2";
    $tpl->CABECALHO_COLUNA_NOME="OPERADORES";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}

//ALT.
if ($permissao_caixas_trocar==1) {
    $tpl->CABECALHO_COLUNA_TAMANHO="50px";
    $tpl->CABECALHO_COLUNA_COLSPAN="";
    $tpl->CABECALHO_COLUNA_NOME="ALT.";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}

//Situação
$tpl->CABECALHO_COLUNA_TAMANHO="100px";
$tpl->CABECALHO_COLUNA_COLSPAN="2";
$tpl->CABECALHO_COLUNA_NOME="SITUAÇÃO";
$tpl->block("BLOCK_LISTA_CABECALHO");


//FLUXO
if ($usuario_grupo<>4) {
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="";
    $tpl->CABECALHO_COLUNA_NOME="OPER.";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}

//Operacoes
if ($usuario_grupo==4) 
    $opetamanho=2;
else 
    $opetamanho=5;
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="$opetamanho";
    $tpl->CABECALHO_COLUNA_NOME="OPERAÇÕES";
    $tpl->block("BLOCK_LISTA_CABECALHO");

//Se o usuário logado for operador de caixa pode ver apenas os caixa que é operador
if ($usuario_grupo==4) {
    $sql_filtro=$sql_filtro. " 
    AND cai_codigo in (
        SELECT DISTINCT cai_codigo from caixas 
        JOIN caixas_operadores on (caiope_caixa=cai_codigo) 
        WHERE caiope_operador=$usuario_codigo
        and cai_quiosque=$usuario_quiosque
    )";
}
    

$sql="SELECT * FROM caixas WHERE cai_quiosque=$usuario_quiosque and cai_status=1 $sql_filtro order by cai_nome";

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


while ($dados=  mysql_fetch_assoc($query)) {
    $codigo= $dados["cai_codigo"];
    $nome= $dados["cai_nome"];
    $quiosque= $dados["cai_quiosque"];
    $situacao= $dados["cai_situacao"];
    $local= $dados["cai_local"];
    $datahoracadastro= $dados["cai_datahoracadastro"];
    
    //Verifica qual foi a última operação do caixa
    $sql2="SELECT max(caiopo_numero) FROM caixas_operacoes WHERE caiopo_caixa=$codigo";
    if (!$query2=mysql_query($sql2)) die("Erro SQL 22: " . mysql_error());
    $dados2=mysql_fetch_array($query2);
    $numero_ultimo=$dados2[0];
    
    //if ($situacao==1) $tpl->TR_CLASSE="tab_linhas_amarelo";
    //else $tpl->TR_CLASSE="tab_linhas_amarelo";

    
    //Nome
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR="$nome";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    
    
    //Local
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR="$local";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Operadores
    if ($usuario_grupo<>4) {
        $sql2="SELECT * FROM caixas_operadores WHERE caiope_caixa=$codigo";
        if (!$query2=mysql_query($sql2)) die("Erro SQL: ".mysql_error());
        $qtd_operadores = $dados2 = mysql_num_rows($query2);
        $tpl->LISTA_COLUNA2_ALINHAMENTO="right";
        $tpl->LISTA_COLUNA2_VALOR="($qtd_operadores)";
        $tpl->LISTA_COLUNA2_ALINHAMENTO2="left"; 
        $tpl->LISTA_COLUNA2_LINK="caixas_operadores.php?caixa=$codigo";
        $tpl->IMAGEM_PASTA="$icones";
        $tpl->block("BLOCK_LISTA_COLUNA2");
    }
    
    //Alterar Caixa Padrão
    if ($permissao_caixas_trocar==1) {
        if ($codigo==$usuario_caixa) {
           $tpl->IMAGEM_ALINHAMENTO="center";
           $tpl->LINK="caixas_trocar.php?operacao=desassociar";
           $tpl->IMAGEM_TAMANHO="15px";
           $tpl->IMAGEM_PASTA="$icones";
           $tpl->IMAGEM_NOMEARQUIVO="caixa_desassociar.png";
           $tpl->IMAGEM_TITULO="Desassociar Caixa";
           $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
           $tpl->block("BLOCK_LISTA_COLUNA_ICONES");            
        } else {
            if ($situacao==1) {
                $tpl->IMAGEM_ALINHAMENTO="center";
                $tpl->LINK="caixas_trocar.php?codigo=$codigo&numero=$numero_ultimo";
                $tpl->IMAGEM_TAMANHO="15px";
                $tpl->IMAGEM_PASTA="$icones";
                $tpl->IMAGEM_NOMEARQUIVO="caixa_associar.png";
                $tpl->IMAGEM_TITULO="Alterar Caixa Padrão";
                $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
                $tpl->block("BLOCK_LISTA_COLUNA_ICONES");         
            } else if ($situacao==2) {
                $tpl->IMAGEM_ALINHAMENTO="center";
                $tpl->LINK="";
                $tpl->IMAGEM_TAMANHO="15px";
                $tpl->IMAGEM_PASTA="$icones";
                $tpl->IMAGEM_NOMEARQUIVO="caixa_associar2.png";
                $tpl->IMAGEM_TITULO="Alterar Caixa Padrão";
                $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
                $tpl->block("BLOCK_LISTA_COLUNA_ICONES");               
            }
        }
            
        
        
    }
    

    //Situação
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="50px";
    $tpl->ICONES_TEXTO_ALINHAMENTO="right";
    $tpl->ICONES_TEXTO_CLASSE="";
    $nuncaoperado="";
    if ($numero_ultimo != "") {
        $sql3="SELECT * FROM caixas_operacoes JOIN caixas on cai_codigo=caiopo_caixa JOIN pessoas on caiopo_operador=pes_codigo WHERE caiopo_numero=$numero_ultimo";
        if (!$query3=mysql_query($sql3)) die("Erro SQL 21: " . mysql_error());
            $dados3=mysql_fetch_assoc($query3);
            $situacao_atual=$dados3["cai_situacao"];
            $operador_atual=$dados3["caiopo_operador"];
            $operador_atual_nome=$dados3["pes_nome"];
            if ($situacao_atual==2)
                $tpl->ICONES_TEXTO_VALOR="";
            else
                $tpl->ICONES_TEXTO_VALOR="$operador_atual_nome";
    } else {
        $situacao_atual="2";
        $nuncaoperado=1;
        $tpl->ICONES_TEXTO_ALINHAMENTO="right";
        $tpl->ICONES_TEXTO_TAMANHOCAMPO="";
        $tpl->ICONES_TEXTO_VALOR="Nunca Operado";
    }
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES_TEXTO");
    
    $tpl->IMAGEM_ALINHAMENTO="center";
    $tpl->LINK="";
    $tpl->IMAGEM_TAMANHO="15px";
    $tpl->IMAGEM_PASTA="$icones";
    if ($situacao==1) {
        $tpl->IMAGEM_NOMEARQUIVO="caixa_aberto3.png";
        $tpl->IMAGEM_TITULO="Aberto";
    } else {
        $tpl->IMAGEM_NOMEARQUIVO="caixa_fechado3.png";
        $tpl->IMAGEM_TITULO="Fechado";
    }
    $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES");  
    
    /*$sql2="SELECT  caisit_nome,caisit_codigo FROM caixas_situacao WHERE caisit_codigo=$situacao";
    if (!$query2=mysql_query($sql2)) die("Erro SQL: ".mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $situacao_nome=$dados2["caisit_nome"];
    $tpl->ICONES_TEXTO_ALINHAMENTO="left";
    $tpl->ICONES_TEXTO_TAMANHOCAMPO="";
    if ($dados2["caisit_codigo"]==1) {
        $tpl->ICONES_TEXTO_CLASSE="tabelalinhaazul";
    } else {
        $tpl->ICONES_TEXTO_CLASSE="tabelalinhavermelha";
    }
    if ($nuncaoperado==1) $tpl->ICONES_TEXTO_VALOR="";
    else $tpl->ICONES_TEXTO_VALOR="$situacao_nome";
    $tpl->block("BLOCK_LISTA_COLUNA_ICONES_TEXTO");*/
    
    
    //FLUXO
    if ($usuario_grupo<>4) {
        if (($numero_ultimo=="")) {
            $tpl->IMAGEM_ALINHAMENTO="center";
            $tpl->LINK="";
            $tpl->IMAGEM_TAMANHO="15px";
            $tpl->IMAGEM_PASTA="$icones";
            $tpl->IMAGEM_NOMEARQUIVO="caixa_fluxo2.png";
            $tpl->IMAGEM_TITULO="Operações";
            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
            $tpl->block("BLOCK_LISTA_COLUNA_ICONES");           
        } else {
            $tpl->IMAGEM_ALINHAMENTO="center";
            $tpl->LINK="caixas_operacoes.php?codigo=$codigo";
            $tpl->IMAGEM_TAMANHO="15px";
            $tpl->IMAGEM_PASTA="$icones";
            $tpl->IMAGEM_NOMEARQUIVO="caixa_fluxo.png";
            $tpl->IMAGEM_TITULO="Operações";
            $tpl->block("BLOCK_LISTA_COLUNA_IMAGEM");
            $tpl->block("BLOCK_LISTA_COLUNA_ICONES");
        }
    }
    
    
    //Abrir Caixa
    if (($situacao_atual==2)&&($usuario_caixa_operacao=="")) {
        $tpl->LINK="caixas_operacoes_abrir.php";
        $tpl->CODIGO="$codigo";
        $tpl->LINK_COMPLEMENTO="";
        $tpl->COLUNA_CLASSE="tab_operacao";
        $tpl->ICONE_NOME="caixas_abrir.png";
        $tpl->ICONE_ARQUIVO="$icones";
        $tpl->OPERACAO_NOME="Abrir Caixa";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");
    } else {
        $tpl->LINK="";
        $tpl->CODIGO="";
        $tpl->LINK_COMPLEMENTO="";
        $tpl->COLUNA_CLASSE="tab_operacao";
        $tpl->ICONE_NOME="caixas_abrir2.png";
        $tpl->ICONE_ARQUIVO="$icones";
        $tpl->OPERACAO_NOME="Abrir Caixa";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");      
    }
    //Encerrar Caixa
    if (($situacao_atual==1)&&(((($usuario_grupo==4)&&($operador_atual==$usuario_codigo))||(($usuario_grupo==1)||($usuario_grupo==3))))) {
        $tpl->LINK="caixas_operacoes_encerrar.php";
        $tpl->COLUNA_CLASSE="tab_operacao";
        $tpl->CODIGO="$numero_ultimo";
        $tpl->LINK_COMPLEMENTO="";
        $tpl->ICONE_NOME="caixas_encerrar.png";
        $tpl->ICONE_ARQUIVO="$icones";
        $tpl->OPERACAO_NOME="Encerrar Caixa";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");
        
    }  else {
        $tpl->LINK="";
        $tpl->COLUNA_CLASSE="tab_operacao";
        $tpl->CODIGO="";
        $tpl->LINK_COMPLEMENTO="";
        $tpl->ICONE_NOME="caixas_encerrar2.png";
        $tpl->OPERACAO_NOME="Encerrar Caixa";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");       
    }
    
    
    
    if ($usuario_grupo<>4) {
        //Detalhes
        $tpl->LINK="caixas_cadastrar.php";
        $tpl->CODIGO="$codigo";
        $tpl->LINK_COMPLEMENTO="operacao=ver";
        $tpl->ICONE_ARQUIVO="$icones";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DETALHES");

        //Editar
        $tpl->LINK="caixas_cadastrar.php";
        $tpl->CODIGO="$codigo";
        $tpl->LINK_COMPLEMENTO="operacao=editar";
        $tpl->ICONE_ARQUIVO="$icones";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR");
        //$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR_DESABILITADO");

        //Excluir
        if ($situacao==2) {
            $tpl->LINK="caixas_deletar.php";
            $tpl->CODIGO="$codigo";
            $tpl->LINK_COMPLEMENTO="operacao=excluir";
            $tpl->ICONE_ARQUIVO="$icones";
            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR");
            $tpl->ICONE_ARQUIVO="";
        } else {
            $tpl->ICONE_ARQUIVO="$icones";
            $tpl->ICONE_ARQUIVO="$icones";
            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR_DESABILITADO");
        }
    }
        
    $tpl->block("BLOCK_LISTA"); 
}
if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}


$tpl->show();

include "rodape.php";

?>