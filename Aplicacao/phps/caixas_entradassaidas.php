<?php
$tipopagina = "caixas";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operacoes_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

$numero=$_GET["caixaoperacao"];


//Pega dados da operação
$sql="
    SELECT * FROM caixas_operacoes 
    JOIN caixas on cai_codigo=caiopo_caixa 
    JOIN pessoas on (caiopo_operador=pes_codigo)
    JOIN caixas_situacao on (caisit_codigo=cai_situacao)
    WHERE caiopo_numero=$numero";
if (!$query=mysql_query($sql)) die("Erro SQL 22: " . mysql_error());
$dados=mysql_fetch_assoc($query);
$caixa=$dados["cai_codigo"];
$caixa_nome=$dados["cai_nome"];
$operador=$dados["caiopo_operador"];
$operador_nome=$dados["pes_nome"];
$valorfinal=$dados["caiopo_valorfinal"];
if ($valorfinal=="") $situacao=1; else $situacao=2;


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "ENTRADAS E SAÍDAS DE CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixa_entradasaida.png";
$tpl_titulo->show();


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

//Campo Caixa no Topo
$tpl->CAMPO_TITULO = "Caixa";
$tpl->CAMPO_VALOR = $caixa_nome;
$tpl->CAMPO_TAMANHO = "30";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

// Numero no Topo
$tpl->CAMPO_TITULO = "Número";
$tpl->CAMPO_VALOR = $numero;
$tpl->CAMPO_TAMANHO = "12";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");

// Reponsável pelo caixa no Topo
$tpl->CAMPO_TITULO = "Responsável";
$tpl->CAMPO_VALOR = $operador_nome;
$tpl->CAMPO_TAMANHO = "30";
$tpl->block("BLOCK_FILTRO_CAMPO_DESABILITADO");
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Botão cadastar entrada/saida
if ($situacao==1) {
    $tpl->LINK = "caixas_entradassaidas_cadastrar.php?operacao=cadastrar&caixaoperacao=$numero";
    $tpl->BOTAO_NOME = "CADASTRAR ENTRADA/SAÍDA";
    //$tpl->block("BLOCK_RODAPE_BOTAO_MODELO_DESABILITADO");
    //$tpl->BOTAO_TITULO = "Você só pode gerar entradas e saídas em caixas abertos!";
    $tpl->block("BLOCK_AUTOFOCO");
    $tpl->block("BLOCK_RODAPE_BOTAO_MODELO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
}

$tpl->block("BLOCK_FILTRO");

//ID
$tpl->CABECALHO_COLUNA_TAMANHO="30px";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="ID";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Data Abertura
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="2";
$tpl->CABECALHO_COLUNA_NOME="DATA";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Valor
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="2";
$tpl->CABECALHO_COLUNA_NOME="VALOR";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Descrição
$tpl->CABECALHO_COLUNA_TAMANHO="";
$tpl->CABECALHO_COLUNA_COLSPAN="";
$tpl->CABECALHO_COLUNA_NOME="DESCRIÇÃO";
$tpl->block("BLOCK_LISTA_CABECALHO");




//Operacoes
if ($situacao==1) {
    $tpl->CABECALHO_COLUNA_TAMANHO="";
    $tpl->CABECALHO_COLUNA_COLSPAN="2";
    $tpl->CABECALHO_COLUNA_NOME="OPERAÇÕES";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}

$sql="
    SELECT * FROM caixas_entradassaidas
    JOIN caixas_tipo on (caientsai_tipo=caitip_codigo)
    LEFT JOIN saidas_pagamentos on (caientsai_saidapagamento=saipag_codigo)
    WHERE caientsai_numerooperacao=$numero
    $sql_filtro 
    ORDER BY caientsai_id DESC
";


$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());


$cont=0;
while ($dados=  mysql_fetch_assoc($query)) {
    $tipo= $dados["caientsai_tipo"];
    $tipo_nome= $dados["caitip_nome"];
    $valor= $dados["caientsai_valor"];
    $datacadastro= $dados["caientsai_datacadastro"];
    $descricao= $dados["caientsai_descricao"];
    $areceber= $dados["caientsai_areceber"];
    $venda= $dados["caientsai_venda"];
    $usuarioquecadastrou= $dados["caientsai_usuarioquecadastrou"];
    $id= $dados["caientsai_id"];
    $saidapagamento=$dados["caientsai_saidapagamento"];
    if ($saidapagamento>0) $tempagamento=1; else $tempagamento=0;
    $saidadevolucao=$dados["caientsai_saidadevolucao"];
    if ($saidadevolucao>0) $temdevolucao=1; else $temdevolucao=0;

    //ID
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$id";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    //Data
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $data=converte_datahora($datacadastro);
    $data2 = explode (" ",$data);
    $data= $data2[0];
    $hora= converte_hora($data2[1]);
    $tpl->LISTA_COLUNA_VALOR="$data";
    $tpl->block("BLOCK_LISTA_COLUNA");
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$hora" ;
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    
    //Valor e Tipo
    $tpl->LISTA_COLUNA_ALINHAMENTO="right";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR="";
    $tpl->LINK="";
    $tpl->IMAGEM_TAMANHO="15px";
    $tpl->IMAGEM_PASTA="$icones";
    if (($tipo==1)) {
        $tpl->IMAGEM_NOMEARQUIVO="caixa_entrada3.png";
        $tpl->IMAGEM_TITULO="Entrada";
    } else if ($tipo==2){
        $tpl->IMAGEM_NOMEARQUIVO="caixa_saida3.png";
        $tpl->IMAGEM_TITULO="Saída";
    }    
    $tpl->block("BLOCK_LISTA_COLUNA_ICONE"); 
    $tpl->block("BLOCK_LISTA_COLUNA");  
    $tpl->LISTA_COLUNA_ALINHAMENTO="left";
    if ($tipo==1)
        $tpl->LISTA_COLUNA_CLASSE="tabelalinhaverde";
    else 
        $tpl->LISTA_COLUNA_CLASSE="tabelalinhavermelha";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR=  "R$ ".number_format($valor,2,',','.');
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    
    //Descrição
    $tpl->LISTA_COLUNA_ALINHAMENTO="";
    $tpl->LISTA_COLUNA_CLASSE="";
    $tpl->LISTA_COLUNA_TAMANHO="";
    $tpl->LISTA_COLUNA_VALOR= "$descricao";
    $tpl->block("BLOCK_LISTA_COLUNA");
    
    
    
    //Operações
    $tpl->ICONE_ARQUIVO="$icones";
    
    if ($situacao==1) {
        //Editar
        //$tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR_DESABILITADO");
        $tpl->LINK="caixas_entradassaidas_cadastrar.php";
        $tpl->CODIGO="$id";
        $tpl->LINK_COMPLEMENTO="operacao=editar&caixaoperacao=$numero";
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EDITAR");

        //Excluir
        if ($tempagamento==1) {
            $tpl->NAOEXCLUIR_MOTIVO="Você não pode excluir este lançamento porque ele está vinculado a um PAGAMENTO!";
            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR_DESABILITADO");
        } else if ($temdevolucao==1) {
            $tpl->NAOEXCLUIR_MOTIVO="Você não pode excluir este lançamento porque ele está vinculado a uma DEVOLUÇÃO!";
            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR_DESABILITADO");
        } else{
            $tpl->LINK="caixas_entradassaidas_deletar.php";
            $tpl->CODIGO="$id";
            $tpl->LINK_COMPLEMENTO="operacao=excluir&caixa=$caixa&numero=$numero";
            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_EXCLUIR");
        }
    }

    $tpl->block("BLOCK_LISTA"); 
    $cont++;
}

if (mysql_num_rows($query) == 0) {
    $tpl->block("BLOCK_LISTA_NADA");
}

//Botão Voltar
if ($usuario_grupo!=4) {
    $tpl->LINK_VOLTAR="caixas_operacoes.php?codigo=$caixa";
    $tpl->block("BLOCK_RODAPE_BOTAO_VOLTAR");
    $tpl->block("BLOCK_RODAPE_BOTAO");
    $tpl->block("BLOCK_RODAPE_BOTOES");
}


$tpl->show();

include "rodape.php";

?>