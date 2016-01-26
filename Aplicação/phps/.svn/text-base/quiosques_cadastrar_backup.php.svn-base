
<?php include "includes.php"; ?>

<?php 
$codigoquiosque=$_GET['codigo'];
$ver=$_GET['ver'];
$sql="SELECT * FROM quiosques WHERE qui_codigo='$codigoquiosque'";
$query=mysql_query($sql);
while($array = mysql_fetch_array($query))
		{
			$nome= $array['qui_nome']; 
			$endereco= $array['qui_endereco'];
			$numero= $array['qui_numero'];
			$cidade= $array['qui_cidade'];
			$vila= $array['qui_vila'];
			$bairro= $array['qui_bairro'];
			$cep= $array['qui_cep'];
			$telefone1= $array['qui_fone1'];
			$telefone2= $array['qui_fone2'];
			$contatos= $array['qui_contatos'];
			$email= $array['qui_email'];
			$site= $array['qui_link'];
			$datacadastro= $array['qui_datacadastro'];
			$horacadastro= $array['qui_horacadastro'];
			$dataedicao= $array['qui_dataedicao'];
			$horaedicao= $array['qui_horaedicao'];	
			$presidente= $array['qui_presidente'];		
		}

?>

<table summary="" class="tabela1" border="0">
<tr>
	<td width="35px"><img width="50px" src="<?php echo $icones;?>quiosques.png" alt="" ></td>
	<td valign="bottom">
		<label class="titulo"> QUIOSQUES </label><br />
		<label class="subtitulo"> CADASTRO/EDIÇÃO </label>
	</td>
</tr>
</table>
<hr align="left" class="linhacurta" >
<br />

<form action="quiosques_cadastrar2.php?codigo=<?php echo"$codigoquiosque";?>" method="post" name="form1">
<table summary="" border="0" class="tabela1" cellpadding="4">
<tr>
	<td align="right" width="200px"><b>Nome: <label class="obrigatorio">*</label></b></td>
	<td align="left" width=""><input type="text" name="nome" autofocus size="30" class="campo1" required value="<?php echo "$nome"; ?>" <?php if ($ver==1) echo" disabled ";?> ></td>
</tr>
<tr>
	<td align="right" width="200px"><b>Cidade: <label class="obrigatorio">*</label></b></td>
	<td align="left" width="">
		<select name="cidade" class="campo1" required="required" <?php if ($ver==1) echo" disabled ";?> >
		<option value=""></option>		
		<?php
		$sql1="SELECT cid_codigo, cid_nome FROM cidades ORDER BY cid_nome";		
		$query1=mysql_query($sql1);
		while($array1 = mysql_fetch_array($query1))
		{
			?><option value="<?php echo"$array1[0]";?>" <?php if($array1[0]==$cidade) { echo "selected ";} ?> ><?php echo"$array1[1]";?></option><?php
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td align="right" width="200px"><b>Vila: </b></td>
	<td align="left" width=""><input type="text" name="vila" size="40" class="campo1" placeholder="Se houver"  value="<?php echo "$vila"; ?>" <?php if ($ver==1) echo" disabled ";?> ></td>
</tr>

<tr>
	<td align="right" width="200px"><b>Bairro: </b></td>
	<td align="left" width=""><input type="text" name="bairro" size="45" class="campo1" value="<?php echo "$bairro"; ?>" <?php if ($ver==1) echo" disabled ";?> ></td>
</tr>
<tr>
	<td align="right" width="200px"><b>Endereço: <label class="obrigatorio">*</label></b></td>
	<td align="left" width=""><input type="text" name="endereco" id="endereco" size="35" class="campo1" required value="<?php echo "$endereco"; ?>"  <?php if ($ver==1) { echo" disabled ";} else { ?> placeholder="Nome da Rua" <?php }?> >
	<input type="text" name="numero" size="8" class="campo1" value="<?php echo "$numero"; ?>"  <?php if ($ver==1) { echo" disabled ";} else { ?> placeholder="Nº" <?php }?> ></td>
</tr>
<tr>
	<td align="right" width="200px"><b>CEP: </b></td>
	<td align="left" width=""><input type="text" name="cep" id="cep" size="15" class="campo1" value="<?php echo "$cep"; ?>"  <?php if ($ver==1) echo" disabled ";?> > </td>
</tr>
<tr>
	<td align="right" width="200px"><b>Telefone 01: </b></td>
	<td align="left" width=""><input type="text" name="telefone1" id="telefone1"  size="15" class="campo1"  value="<?php echo "$telefone1"; ?>" <?php if ($ver==1) echo" disabled ";?> ></td>
</tr>
<tr>
	<td align="right" width="200px"><b>Telefone 02: </b></td>
	<td align="left" width=""><input type="text" name="telefone2" id="telefone2"  size="15" class="campo1"  value="<?php echo "$telefone2"; ?>" <?php if ($ver==1) echo" disabled ";?> ></td>
</tr>
<tr>
	<td align="right" width="200px"><b>Contatos: </b></td>
<td align="left" width=""><input type="text" name="contatos" size="30" class="campo1"  value="<?php echo "$contatos"; ?>" <?php if ($ver==1) echo" disabled ";?> ></td>
</tr>
<tr>
	<td align="right" width="200px"><b>E-mail: </b></td>
	<td align="left" width=""><input type="email" name="email" id="email" size="45" class="campo1" value="<?php echo "$email"; ?>" <?php if ($ver==1) echo" disabled ";?> ></td>
</tr>
<tr>
	<td align="right" width="200px"><b>Site: </b></td>
	<td align="left" width=""><input type="url" name="site" id="site" size="45" class="campo1" value="<?php if ($site=="") { echo "http://"; } else { echo "$site"; } ?>" <?php if ($ver==1) echo" disabled ";?> ></td>
</tr>
<tr>
	<td align="right" width="200px"><b>Cooperativa:  <label class="obrigatorio"></label></b></td>
	<td align="left" width="">
		<select name="cooperativa" class="campo1"  <?php if ($ver==1) echo" disabled ";?> >
		<option value="">Selecione</option>
		<?php
		if ($permissao_quiosque_definircooperativa==1) {
                    $sql2="SELECT * FROM cooperativas";
                } else { 
                     $sql2="SELECT * FROM cooperativas WHERE coo_codigo=$usuario_cooperativa";
                }		
		$query2=mysql_query($sql2);
		while($array2 = mysql_fetch_array($query2))
		{
			?><option value="<?php echo"$array2[0]";?>" <?php if($array2[0]==$presidente) { echo "selected ";} ?>  ><?php echo"$array2[1]";?></option><?php
		}
		?>
		</select> <label class="fonte1"> Presitentes, Vendedores ou Administradores</label> 
	</td>
</tr>
</table>

<br />
<hr align="left" >
<?php
if ($ver==1){ ?><a href="quiosques.php" class="link">&nbsp;<input type="button" value="VOLTAR" class="botao fonte3"></a> <?php }
else { ?><input type="submit" value="SALVAR" name="submit1" class="botao fonte3"> <a href="quiosques.php" class="link">&nbsp;<input type="button" value="CANCELAR" class="botao fonte3"></a> <?php } ?>
</form>

<?php include "rodape.php"; ?>
