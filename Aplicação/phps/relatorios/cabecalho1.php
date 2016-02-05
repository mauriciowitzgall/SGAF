<?php

//Cabeçalho
$tpl = new Template("../templates/cabecalho1.html");
$codigo = $_POST["codigo"];
$tpl->NUMERO = $codigo;
$data = date("d/m/y");
$hora = date("H:i");
$tpl->DATA = "$data";
$tpl->HORA = "$hora";
$sql = "
    SELECT rel_nome
    FROM relatorios
    WHERE rel_codigo=$codigo
";
$query = mysql_query($sql);
if (!$query)
    die("Erro1: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$tpl->NOME = $dados["rel_nome"];
$tpl->COOPERATIVA = $usuario_cooperativaabreviacao;
$tpl->QUIOSQUE = $usuario_quiosquenome;
$tpl->show();

?>
