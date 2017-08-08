
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
            //include "conexao_tipo.php";
            if ($modal!=1) {
                include "cabecalho.php";
                include "menu.php";
            }
            include "js/mascaras.php";
            ?>
            <div class="corpo">


