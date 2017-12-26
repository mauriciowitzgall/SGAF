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
            $sql="
                SELECT * 
                FROM quiosques_configuracoes 
                JOIN quiosques on (quicnf_quiosque=qui_codigo)
                JOIN cidades on (qui_cidade=cid_codigo)
                JOIN estados on (cid_estado=est_codigo) 
                WHERE quicnf_quiosque=$usuario_quiosque
            ";
            if (!$query= mysql_query($sql)) die("Erro SQL INCLUDES: " . mysql_error());
            while ($dados=  mysql_fetch_assoc($query)) {    
                $usuario_quiosque_pais=$dados["est_pais"];
                $usuario_quiosque_estado=$dados["cid_estado"];
                $usuario_quiosque_cidade=$dados["qui_cidade"];
                $usaestoque=$dados["quicnf_usamoduloestoque"];
                $usamodulofiscal=$dados["quicnf_usamodulofiscal"];
                $usaproducao=$dados["quicnf_usamoduloproducao"];
                $usavendas=$dados["quicnf_usamodulovendas"];
                $usacaixa=$dados["quicnf_usamodulocaixa"];
                $usadevolucoes=$dados["quicnf_devolucoessobrevendas"];
                $usavendaporcoes=$dados["quicnf_usavendaporcoes"];
                $usacomanda=$dados["quicnf_usacomanda"];
                $usapagamentosparciais=$dados["quicnf_pagamentosparciais"];
                $usamulticaixas=$dados["quicnf_multicaixas"];
                $permiteedicaoreferencianavenda=$dados["quicnf_permiteedicaoreferencianavenda"];
                $fazacertos=$dados["quicnf_fazacertos"];
                $fazfechamentos=$dados["quicnf_fazfechamentos"];
                $permitevendasareceber=$dados["quicnf_vendasareceber"];
                $usacodigobarrasinterno=$dados["quicnf_usacodigobarrasinterno"];
                $usaean=$dados["quicnf_usaean"];
                $gerirestoqueideal=$dados["quicnf_gerirestoqueideal"];
                $ignorarlotes=$dados["quicnf_ignorarlotes"];
                $geririmobilizado=$dados["quicnf_geririmobilizado"];
                $controlavalidade=$dados["quicnf_controlavalidade"];
                $valorvendazero=$dados["quicnf_valorvendazero"];
                $obsnavenda=$dados["quicnf_obsnavenda"];
                $obsnaentrada=$dados["quicnf_obsnaentrada"];
                $fazentregas=$dados["quicnf_fazentregas"];
                $usaprateleira=$dados["quicnf_usaprateleira"];
                $identificacaoconsumidorvenda=$dados["quicnf_identificacaoconsumidorvenda"];
            }
            $sql="SELECT * FROM quiosques_tiponegociacao WHERE quitipneg_quiosque=$usuario_quiosque";
            if (!$query= mysql_query($sql)) die("Erro SQL INCLUDES: " . mysql_error());
            $quiosque_consignacao=0;
            $quiosque_revenda=0;
            while ($dados=  mysql_fetch_assoc($query)) {  
                if ($dados["quitipneg_tipo"]==1) $quiosque_consignacao=1;
                if ($dados["quitipneg_tipo"]==2) $quiosque_revenda=1;
            }  


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
            <div class="corpo">