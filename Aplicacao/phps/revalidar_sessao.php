<?php

//Quebrar sessão, como se fosse sair do sistema
session_start();
session_destroy();

//Reconstruir toda a sessão
session_cache_expire(180);
session_start();


include "controle/conexao.php";
include "controle/conexao_tipo.php";


$sql = "
SELECT 
    pes_cpf,
    pes_cnpj,
    pes_codigo,
    pes_nome, 
    pes_senha,
    pes_grupopermissoes,
    pes_cooperativa,
    pes_quiosqueusuario,
    pes_cidade,
    cid_estado,
    est_pais
FROM 
    pessoas
    left join cidades on (pes_cidade=cid_codigo)
    left join estados on (cid_estado=est_codigo)
WHERE 
    pes_codigo=$usuario_codigo
";
$resultado = mysql_query($sql) or die("Erro Consulta reconstruir sessão:" . mysql_error());
$dados = mysql_fetch_assoc($resultado);
$_SESSION["usuario_codigo"] = $dados["pes_codigo"];
$usuario_cpf= $_SESSION["usuario_cpf"] = $dados["pes_cpf"];
$_SESSION["usuario_cnpj"] = $dados["pes_cnpj"];
$usuario_nome = $_SESSION["usuario_nome"] = stripslashes($dados["pes_nome"]);
$usuario_grupo = $_SESSION["usuario_grupo"] = $dados["pes_grupopermissoes"];
$usuario_cooperativa = $_SESSION["usuario_cooperativa"] = $dados["pes_cooperativa"];
$_SESSION["usuario_quiosque"] = $dados["pes_quiosqueusuario"];
$_SESSION["usuario_cidade"] = $dados["pes_cidade"];
$_SESSION["usuario_estado"] = $dados["cid_estado"];
$_SESSION["usuario_pais"] = $dados["est_pais"];

//echo "<br><br>TROCA UNIDADE: ($usuario_cpf)($usuario_nome)($usuario_grupo)($usuario_cooperativa)<br><br>";

//Define o nome do quiosque
$sql2 = "SELECT qui_codigo,qui_nome FROM quiosques WHERE qui_codigo=$quiosque";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro de sql 2:" . mysql_error());
$dados2 = mysql_fetch_assoc($query2);
$_SESSION["usuario_quiosquenome"] = $dados2["qui_nome"];
$_SESSION["usuario_quiosque"] = $dados["pes_quiosqueusuario"];

//Define o nome da cooperativa
$sql3 = "SELECT coo_codigo,coo_abreviacao,coo_nomecompleto FROM cooperativas WHERE coo_codigo=$cooperativa";
$query3 = mysql_query($sql3);
if (!$query3)
    die("Erro de sql 2:" . mysql_error());
$dados3 = mysql_fetch_assoc($query3);
$_SESSION["usuario_cooperativaabreviacao"] = $dados3["coo_abreviacao"];
$_SESSION["usuario_cooperativanomecompleto"] = $dados3["coo_nomecompleto"];


header("Location: ../inicio.html");

?>