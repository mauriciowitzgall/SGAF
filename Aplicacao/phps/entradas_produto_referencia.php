<?php

include "controle/conexao.php";


$referencia = $_POST["referencia"];
$paravenda=$_POST["paravenda"];




//Verifica se existe algum produto com esta referencia
$sql = "
	SELECT *
	FROM produtos 
	WHERE pro_referencia='$referencia'

";
if (!$query=mysql_query($sql)) die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas>0) {
    while ($dados= mysql_fetch_assoc($query)) {
        $codigo=$dados["pro_codigo"];
	    $nome= $dados['pro_nome'];
	    $referencia= $dados['pro_referencia'];
	    $tamanho= $dados['pro_tamanho'];
	    $cor= $dados['pro_cor'];
	    $descricao= $dados['pro_descricao'];
	    if ($referencia!="") { $nome2="$nome ($referencia)"; }
    	else { $nome2="$nome"; }
        $controlarestoque=$dados["pro_controlarestoque"];
        $evendido=$dados["pro_evendido"];
		if ($controlarestoque==0) {
			echo "PRODUTO_SEM_CONTROLE_DE_ESTOQUE";
		} else {
	    	if ($paravenda!=-1) {
		    	if (($paravenda==1)&&($evendido==0)) echo "PRODUTO_NAO_PARAMETRIZADO_PARA_VENDA";
		        else if (($paravenda==0)&&($evendido==1)) echo "PRODUTO_PARAMETRIZADO_PARA_VENDA";
		        else echo "$codigo|$nome";
	    	} else {
	    		echo "$codigo|$nome2";
	    	}
		}
    }
} else {
    echo "0";
}

?>
