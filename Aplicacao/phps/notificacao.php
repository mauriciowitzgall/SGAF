<table summary="" border="1" class="tabela1" cellpadding="4" align="center">
<tr valign="middle" align="center">
	<td valign="middle" align="right" class="celula1"><?php if(mysql_query($sql)) {?><img src="<?php echo $icones;?>confirmar.png" ><?php } else {?><img src="<?php echo $icones;?>erro.png" ><?php } ?></td><td class="celula2">
	<?php
		if(mysql_query($sql)) { echo "<b>Os dados foram salvos com sucesso!<b>"; }
		else { echo "<b>Não foi possivel inserir os dados!</b> <br>"; echo mysql_error(); }
	?>
	</td>
<tr>
   <td colspan="2" align="center" ><a class="link" href="<?php echo "$paginadestino"; ?>"><input type="button" value="Continuar" class="botao"></a></td>
</tr>
</table>