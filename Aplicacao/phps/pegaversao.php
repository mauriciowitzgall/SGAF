<?php
include "controle/conexao.php";
include "controle/conexao_tipo.php";

$sql="SELECT cnf_versao FROM configuracoes WHERE cnf_codigo=1";
if (!$query=mysql_query($sql)) die("Erro SQL:" . mysql_error());
$dados = mysql_fetch_assoc($query);
echo $dados["cnf_versao"];
?>
