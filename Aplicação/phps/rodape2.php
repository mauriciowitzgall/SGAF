<?php 
$tpl = new Template("rodape2.html");
$sql= "
SELECT
    *
FROM
    quiosques
    JOIN cidades on (qui_cidade=cid_codigo)
    JOIN cooperativas on (qui_cooperativa=coo_codigo)
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$cooperativa_nome = $dados["coo_abreviacao"];
$nome = $dados["qui_nome"];
$cidade_nome = $dados["cid_nome"];
$endereco = $dados["qui_endereco"];
$fone1 = $dados["qui_fone1"];
$fone2 = $dados["qui_fone2"];
$email = $dados["qui_email"];

$tpl->QUIOSQUE=$usuario_quiosquenome;
$tpl->CIDADE="$cidade_nome";
if ($endereco!="")
    $tpl->ENDERECO=" \ "."$endereco";
$tpl->TELEFONE1="$fone1";
if ($fone2!="")
    $tpl->TELEFONE2=" \ "."$fone2";
if ($email!="")
$tpl->EMAIL=" \ "."$email";      
        
        
$tpl->show();




?>