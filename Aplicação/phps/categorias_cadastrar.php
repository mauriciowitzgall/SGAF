<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if (($permissao_categorias_cadastrar <> 1) &&  ($permissao_categorias_ver<>1)){
    header("Location: permissoes_semacesso.php"); 
    exit;
}

include "includes.php"; ?>

<?php 
$codigo=$_GET['codigo']; 
$ver=$_GET['ver'];
$sql="SELECT * FROM produtos_categorias WHERE cat_codigo='$codigo'";
$query=mysql_query($sql);
while($array = mysql_fetch_array($query))
{
	$nome= $array['cat_nome']; 
	$obs= $array['cat_obs'];
}
?>
<script type="text/javascript" src="js/capitular.js"></script>
<table summary="" class="tabela1" border="0">
<tr>
	<td width="35px"><img width="50px" src="<?php echo $icones;?>produtos.png" alt="" ></td>
	<td valign="bottom">
		<label class="titulo" > CATEGORIAS</label><br />
		<label class="subtitulo"> CADASTRO/EDIÇÃO</label>
	</td>
</tr>
</table>
<hr align="left" class="linhacurta" >
<br />

<form action="categorias_cadastrar2.php?codigo=<?php echo"$codigo";?>" method="post" name="form1">
<table summary="" border="0" class="tabela1" cellpadding="4">
<tr>
	<td align="right" width="200px"><b>Nome: <label class="obrigatorio">*</label></b></td>
	<td align="left" width=""><input onkeypress="capitalize()"  id="capitalizar" type="text" name="nome" autofocus size="30" class="campo1" required value="<?php echo "$nome"; ?>" <?php if ($ver==1) echo" disabled ";?> ></td>
</tr>
</tr>
<tr>
	<td align="right" width="200px"><b>Observação:</b></td>
	<td align="left" width=""><textarea class="textarea1" cols="55" name="obs" <?php if ($ver==1) echo" disabled ";?> ><?php echo "$obs";?></textarea></td>
</tr>
</table>

<br />
<hr align="left" >
<?php
//Verifica o link destino do botão voltar
$linkanterior=$_GET["link"];
if ($linkanterior=="") { $link_destino="categorias.php"; } else {
    $link_destino=$linkanterior;
}

if ($ver==1){ ?><a href="<?php echo $link_destino; ?>" class="link">&nbsp;<input type="button" value="VOLTAR" class="botao fonte3"></a> <?php }
else { ?><input type="hidden" name="link" value="<?php echo $linkanterior; ?>"><input type="submit" value="SALVAR" name="submit1" class="botao fonte3"> <a href="produtos.php" class="link">&nbsp;<input type="button" value="CANCELAR" class="botao fonte3"></a> <?php } ?>
<input type="hidden" name="nome2" value="<?php echo "$nome"; ?>">


</form>

<?php include "rodape.php"; ?>
