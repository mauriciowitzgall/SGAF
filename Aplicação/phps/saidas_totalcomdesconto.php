<?php

include "controle/conexao.php";
include "funcoes.php";

$valbru=$_POST["valbru"];
$descper=$_POST["descper"];


$valbru=explode(" ", $valbru);
$valbru=$valbru[1];
$valbru=str_replace('.','', $valbru);
$valbru=str_replace(',','.', $valbru);


$descper=str_replace('.','', $descper);
$descper=str_replace(',','.', $descper);


$total=$valbru*(1-$descper/100);
$total=number_format($total,2,',','.');
echo $total;

?>
