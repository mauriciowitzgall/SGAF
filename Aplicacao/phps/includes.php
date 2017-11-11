
<?php

  
include "acentuacao.html";
require("templates/Template.class.php");

error_reporting(E_ERROR | E_PARSE);

//Pasta a partir da raiz, onde ficará os arquivos do sistema
$pastasistema = 'sgaf'; 
$raiz = $_SERVER["DOCUMENT_ROOT"] ."/".$pastasistema;
$modal=$_GET["modal"];
?>
<html>
    <head>
        <title><?php $titulopagina ?></title>         
        <link rel="stylesheet" type="text/css" href="classes.css" />
        <link rel="stylesheet" type="text/css" href="templates/geral.css">        
        <script language="JavaScript" src="js/shortcut.js"></script>
        <script language="JavaScript" src="atalhos_teclado.js"></script>
        <script language="JavaScript" src="funcoes.js"></script>        
        <script type="text/javascript" src="js/jquery-1.3.2.js"></script>
        <script src="js/jquery.maskedinput-1.1.4.pack.js" type="text/javascript"></script>
        <script src="mascaras.js" type="text/javascript"></script>       
        <link href="js/_style/jquery.click-calendario-1.0.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/_scripts/jquery.click-calendario-1.0-min.js"></script>		
        <script type="text/javascript" src="js/_scripts/exemplo-calendario.js"></script>        
        <script type="text/javascript" src="js/jquery.price_format.1.5.js"></script>
        <script type="text/javascript" src="login_atualizarsessao.js"></script>
    </head>
    
    <body bgcolor="">        
        <div class="pagina" >
            <?php
            include "controle/conexao.php";
            //include "controle/conexao_tipo.php";
            require_once "funcoes.php";
            $sql="SELECT * FROM quiosques_configuracoes WHERE quicnf_quiosque=$usuario_quiosque";
            if (!$query= mysql_query($sql)) die("Erro SQL INCLUDES: " . mysql_error());
            while ($dados=  mysql_fetch_assoc($query)) {    
                $usaestoque=$dados["quicnf_usamoduloestoque"];
                $usaproducao=$dados["quicnf_usamoduloproducao"];
                $usavendas=$dados["quicnf_usamodulovendas"];
                $usacaixa=$dados["quicnf_usamodulocaixa"];
                $usavendaporcoes=$dados["quicnf_usavendaporcoes"];
                $usacomanda=$dados["quicnf_usacomanda"];
                $usapagamentosparciais=$dados["quicnf_pagamentosparciais"];
                $usamulticaixas=$dados["quicnf_multicaixas"];
                $permiteedicaoreferencianavenda=$dados["quicnf_permiteedicaoreferencianavenda"];
                $permiteedicaoreferencianavenda=$dados["quicnf_permiteedicaoreferencianavenda"];
                $fazacertos=$dados["quicnf_fazacertos"];
                $fazfechamentos=$dados["quicnf_fazfechamentos"];
            }

            //include "conexao_tipo.php";
            if ($modal!=1) {
                include "cabecalho.php";
                include "menu.php";
            }
            include "js/mascaras.php";


            
            ?>
            <div class="corpo">


