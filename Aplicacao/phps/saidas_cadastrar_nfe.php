<?php

//print_r($_REQUEST);

//Verifica se o usuário pode acessar a tela
require "login_verifica.php";
$saida = $_GET["saida"];

$tipopagina = "saidas";
include "includes.php";


if (($usavendas!=1)||($usamodulofiscal!=1)) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas ou emitir notas fiscais solicite a um administrador para <br><b>HABILITAR MÓDULO VENDAS COM EMISSAO DE NOTAS FISCAIS</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
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



?>
