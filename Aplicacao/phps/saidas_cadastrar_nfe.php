<?php

//print_r($_REQUEST);

//Verifica se o usuário pode acessar a tela
require "login_verifica.php";
$saida = $_GET["codigo"];
if ($saida=="") $saida=$_GET["saida"];

$tipopagina = "saidas";
include "includes.php";

//Pega dados do banco para popular os campos da tela
$sql="SELECT * 
    FROM quiosques_configuracoes 
    WHERE quicnf_quiosque=$usuario_quiosque
";
 if (!$query= mysql_query($sql)) die("Erro: " . mysql_error());
 while ($dados=  mysql_fetch_assoc($query)) {
     $tipoimpressaodanfe=$dados["quicnf_tipoimpressaodanfe"];
 }

//Verifica dados adicionais necessários para realizar as validações
$sql="SELECT * FROM saidas WHERE sai_codigo=$saida";
if (!$query= mysql_query($sql)) die("Erro PEGA DADOS PARA VALIDACOES: " . mysql_error());
$dados= mysql_fetch_assoc($query);
$consumidor=$dados["sai_consumidor"];




//Verifica tem o módulo fiscal ativo
if (($usavendas!=1)||($usamodulofiscal!=1)) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas ou emitir notas fiscais solicite a um administrador para <br><b>HABILITAR MÓDULO VENDAS COM EMISSAO DE NOTAS FISCAIS</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    $naopodegerar=1;
    exit;
}


//Verifica se o consumidor pessoa jurídica tem o campo contribuinteicms preenchido
//Isso acontece na seguinte situação: digamos que o quiosque não usa módulo fiscal, ele cadastrar várias pessoas, depois parametriza para usar. Assim os que já estão cadastrados não tem essa informação definida em seus cadastros.
$sql="SELECT * FROM pessoas WHERE pes_codigo=$consumidor";
if (!$query= mysql_query($sql)) die("Erro contribuinteicms: " . mysql_error());
$dados= mysql_fetch_assoc($query);
$contribuinteicms=$dados["pes_contribuinte_icms"];
$tipopessoa=$dados["pes_tipopessoa"];
if (($contribuinteicms==0)&&($tipopessoa==2)) { //É pessoa jurídica e não tem a informação se é contribuindo ou não
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ATENCAO");
    $tpl6->ICONES = $icones;
    $tpl6->MOTIVO = "<Br>Consumidor sem a informação: <b>Contribuinte ICMS</b>. <br> É obrigatório quando o consumidor for uma pessoa jurídica). <br><br>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    $naopodegerar=1;
    exit;
}

//Se utiliza módulo fiscal calcula o valor do ICMS

/*
if ($usamodulofiscal==1) {

    //Verifica qual é o faturamento dos ultimos 12 meses
    $sql="SELECT sum(nfefat_valor) as fatanual FROM (SELECT nfefat_valor FROM nfe_faturamento WHERE nfefat_quiosque=$usuario_quiosque ORDER BY nfefat_codigo DESC LIMIT 12) as subt;";
    if (!$query = mysql_query($sql)) die("Erro SQL Faturamento Anual: ".mysql_error());
    $dados=mysql_fetch_assoc($query);
    $fatanual=$dados["fatanual"];
    //echo "Faturamento Anual: ($fatanual)";

    //Verifica se é do Simples Nacional
    if ($fatanual<=3600000) {    
        //Verifica qual é o valor do ICMS a partir da tabela de calculo pronta 
        $sql_simplesnacional = "SELECT nfesn_icms FROM nfe_simplesnacional WHERE nfesn_de <= $fatanual AND nfesn_ate >= $fatanual";
        if (!$query_simplesnacional = mysql_query($sql_simplesnacional)) {

          
            echo "<br><br><br><br>";
            $tpl_notificacao = new Template("templates/notificacao.html");

            if ($modal==1) $tpl_notificacao->DESTINO = "javascript:window.close(0);";
            else $tpl_notificacao->DESTINO = "#"; 

            $tpl_notificacao->ICONES = $icones;
            $tpl_notificacao->MOTIVO = "<br>Você está utilizando o <b>módulo fiscal</b> <br><br> É necessário informar ao sistema o <b>faturamento</b> de sua empresa dos <b>utlimos 12 meses</b> para calcular o valor do imposto ICMS corretamente. <br><br>Entre em contato com o suporte.<br><br>";
            $tpl_notificacao->block("BLOCK_MOTIVO");
            $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
            //$tpl_notificacao->block("BLOCK_CONFIRMAR");
            $tpl_notificacao->block("BLOCK_ATENCAO");
            $tpl_notificacao->block("BLOCK_BOTAO");
            $tpl_notificacao->show();
            $naopodegerar=1;

            exit;
        }

        $dados_simplesnacional=  mysql_fetch_assoc($query_simplesnacional);
        $icms_atual=$dados_simplesnacional["nfesn_icms"];
        $simplesnacional=1;
    } else {
        $icms_atual="???";
        $simplesnacional=0;
    }

}
*/



//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "NOTA FISCAL";
$tpl_titulo->SUBTITULO = "GERAÇÃO DE NOTA FISCAL";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "nfe_gerar3.png";
$tpl_titulo->show();

$saida = $_GET['codigo'];
 
//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "saidas_cadastrar_nfe_gerar.php?saida=$saida";

//Venda
$tpl1->TITULO = "Venda";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "venda";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$saida";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");


$tpl1->TITULO = "Indicador de Presença";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "indicadorpresenca";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "indicadorpresenca";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
$tpl1->OPTION_VALOR = "0";
$tpl1->OPTION_NOME = "Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste)";
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->OPTION_VALOR = "1";
$tpl1->OPTION_NOME = "Operação presencial";
$tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->OPTION_VALOR = "2";
$tpl1->OPTION_NOME = "Operação não presencial";
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->OPTION_VALOR = "3";
$tpl1->OPTION_NOME = "Operação não presencial, Teleatendimento;";
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->OPTION_VALOR = "4";
$tpl1->OPTION_NOME = "NFC-e em operação com entrega a domicílio";
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->OPTION_VALOR = "9";
$tpl1->OPTION_NOME = "Operação não presencial, outros.";
$tpl1->block("BLOCK_SELECT_OPTION");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");


//Tipo de impressão DANFE
$tpl1->TITULO = "DANFE Impressão";
$tpl1->block("BLOCK_TITULO");
$tpl1->LINHA_ID="linha_tipoimpressaodanfe";
$tpl1->block("BLOCK_LINHA_ID");
$tpl1->SELECT_NOME = "tipoimpressaodanfe";
$tpl1->SELECT_ID = "tipoimpressaodanfe";
$tpl1->SELECT_TAMANHO = "";
//$tpl1->SELECT_ONCHANGE = "";
//$tpl1->block("BLOCK_SELECT_ONCHANGE");
$sql2="SELECT * FROM nfe_danfeimpressao ORDER BY danfe_codigo";
if (!$query2= mysql_query($sql2)) die("Erro: " . mysql_error());
if ($usamodulofiscal=='1') $tpl1->block("BLOCK_SELECT_OBRIGATORIO");
while ($dados2=  mysql_fetch_assoc($query2)) {
    $danfe_codigo=$dados2["danfe_codigo"];
    if ($tipoimpressaodanfe==$danfe_codigo) $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    $danfe_nome=$dados2["danfe_nome"];
    $tpl1->OPTION_VALOR = "$danfe_codigo";
    $tpl1->OPTION_NOME = "$danfe_nome";    
    $tpl1->block("BLOCK_SELECT_OPTION");
 }
$tpl1->block("BLOCK_SELECT_NORMAL");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");



//BOTOES
if ($naopodegerar!=1) {
    $tpl1->BOTAO_TIPO = "submit";
    $tpl1->BOTAO_VALOR = "GERAR NOTA";
    $tpl1->BOTAO_NOME = "GERAR NOTA";
    $tpl1->BOTAO_FOCO = "";
    $tpl1->block("BLOCK_BOTAO1_SEMLINK");
    $tpl1->block("BLOCK_BOTAO1");
} else {
    //Botão Voltar
    $tpl1->block("BLOCK_BOTAO_VOLTAR");
}


$tpl1->block("BLOCK_BOTOES");

$tpl1->show();

include "rodape.php";





?>
