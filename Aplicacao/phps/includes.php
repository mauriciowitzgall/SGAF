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
        <link rel="icon" type="imagem/ico" href="../imagens/icones/sgaf.ico" />
        <title><?php $titulopagina ?></title>         
        <link rel="stylesheet" type="text/css" href="classes.css" />
        <link rel="stylesheet" type="text/css" href="templates/geral.css">        
        <script language="JavaScript" src="js/shortcut.js"></script>
        <script language="JavaScript" src="atalhos_teclado.js"></script>
        <script language="JavaScript" src="funcoes.js"></script>        
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script src="js/jquery.maskedinput-1.1.4.pack.js" type="text/javascript"></script>
        <script src="mascaras.js" type="text/javascript"></script>       
        <link href="js/_style/jquery.click-calendario-1.0.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/_scripts/jquery.click-calendario-1.0-min.js"></script>		
        <script type="text/javascript" src="js/_scripts/exemplo-calendario.js"></script>        
        <script type="text/javascript" src="js/jquery.price_format.1.5.js"></script>
        <script type="text/javascript" src="login_atualizarsessao.js"></script>
        <script type="text/javascript" src="js/jquery.mask.min.js"></script>
    </head>
    <body bgcolor="">        
        <div class="pagina" >
            <?php
            include "controle/conexao.php";
            //include "controle/conexao_tipo.php";
            require_once "funcoes.php";
            include "includes_dadosglobais.php";  


            //include "conexao_tipo.php";
            if ($modal!=1) {
                include "cabecalho.php";
                include "menu.php";
            }
            include "js/mascaras.php";
    
            ?>
            <input name="usuario_quiosque_pais" id="usuario_quiosque_pais" type="hidden" value="<?php echo $usuario_quiosque_pais; ?>">
            <input name="usuario_quiosque_estado" id="usuario_quiosque_estado" type="hidden" value="<?php echo $usuario_quiosque_estado; ?>">
            <input name="usuario_quiosque_cidade" id="usuario_quiosque_cidade" type="hidden" value="<?php echo $usuario_quiosque_cidade; ?>">
            <input name="configuracao_fazentregas" id="configuracao_fazentregas" type="hidden" value="<?php echo $fazentregas; ?>">
            <div class="corpo">