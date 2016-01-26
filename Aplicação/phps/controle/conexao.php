<?php 
   
   $hostname = "localhost";
   $db = "sgaf_online";
   $user = "root";
   $pass = "";
   
   $link = mysql_connect($hostname, $user, $pass);
   if (!$link) {
       echo "Não foi possivel conectar ao Banco de Dados! Descrição do erro:".mysql_error();
       exit;
   }
   mysql_select_db($db, $link);

 ?>
