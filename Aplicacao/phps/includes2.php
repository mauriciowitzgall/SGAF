<?php
require("templates/Template.class.php");
?>
<html>
    <head>
        <title>SGAF</title>
        <meta http-equiv="Content-Type" content="text/xhtml; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="classes.css" />
        <link rel="stylesheet" type="text/css" href="templates/geral.css" />
        <style >
            .relpagina  {
                font-family: Arial;
                font-size: 10pt;
                color: black;
                width: 800px;
                border: 1px transparent solid; 
            }

            .relcorpo  {
                padding:0px;	
            }
        </style>
      
    </head>
    <body>        
        <div class="relpagina">
            <?php
            include "controle/conexao.php";
            include "controle/conexao_tipo.php";
            include "funcoes.php";
            ?>
            <div class="relcorpo">