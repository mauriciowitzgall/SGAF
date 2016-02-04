<?php
//Verifica se o usuário tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_categorias_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

include "includes.php";


$codigo = $_GET['codigo']; 
$nome = $_POST['nome'];
$obs=$_POST['obs'];
$erro=0;
$data=date("Y/m/d"); 
$hora=date ("H:i:s"); 
?>

<table summary="" class="tabela1" border="0">
<tr>
	<td width="35px"><img width="50px" src="<?php echo $icones;?>produtos.png" alt="" ></td>
	<td valign="bottom">
		<label class="titulo" > CATEGORIAS </label><br />
		<label class="subtitulo"> CADASTRO/EDIÇÃO
	</td>
</tr>
</table>
<hr align="left" class="linhacurta" >
<br />

<?php 
if($codigo=="") //caso seja um cadastro novo fazer isso
{		
	$sql = " INSERT INTO produtos_categorias (cat_nome,cat_obs,cat_cooperativa)
	VALUES ('$nome','$obs','$usuario_cooperativa');	";
	if (mysql_query($sql))	{ }
	else
	{
		$erro=1;
		$sqlerro=mysql_error();
	}
}
else //Caso seja uma altera��o de um registro fazer isso
{
	$sql = "UPDATE produtos_categorias SET 
	cat_nome='$nome',
	cat_obs='$obs'
	WHERE cat_codigo = '$codigo'";

	if (mysql_query($sql))	{ }
	else
	{
		$erro=1;
		$sqlerro=mysql_error();
	}	
}
$paginadestino="categorias.php";
?>

<table summary="" border="1" class="tabela1" cellpadding="4" align="center">
<tr valign="middle" align="center">
	<td valign="middle" align="right" class="celula1"><?php if($erro==0) {?><img src="<?php echo $icones;?>confirmar.png" ><?php } else {?><img src="<?php echo $icones;?>erro.png" ><?php } ?></td><td class="celula2">
	<?php

		if($erro=='0') { echo "<b>Os dados foram salvos com sucesso!<b>"; }
		else { echo "<b>Não foi possivel salvar os dados!</b> <br>"; echo "$sqlerro "; }
	?>
	</td>
<tr>
   <td colspan="2" align="center" >
   	<?php if ($erro==0) { ?><a class="link" href="<?php echo "$paginadestino"; ?>"><input type="button" value="CONTINUAR" autofocus class="botao fonte3"></a> <?php } 
		else { ?> <button class="botao fonte3" onclick=javascript:window.history.back()>VOLTAR</button> <?php } ?>
   </td>
</tr>
</table>

<br /><br />
<?php include "rodape.php"; ?>

