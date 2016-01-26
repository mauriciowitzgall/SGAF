<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


include "includes.php"; ?>

<table summary="" class="tabela1" border="0">
<tr>
	<td width="35px"><img width="50px" src="<?php echo $icones;?>produtos.png" alt="" ></td>
	<td valign="bottom">
		<label class="titulo" > PRODUTOS </label><br />
		<label class="subtitulo"> DELETAR/APAGAR </label>
	</td>
</tr>
</table>
<hr align="left" class="linhacurta" >
<br />

<?php

//Vericica se o produto ja foi usando em entradas
$sql="
    SELECT pro_codigo
    FROM produtos
    join entradas_produtos on (entpro_produto=pro_codigo)
    join saidas_produtos on (saipro_produto=pro_codigo)
";


$produtocodigo= $_GET['codigo'];
//$sql = "DELETE FROM produtos WHERE pro_codigo='$produtocodigo'";
$paginadestino="produtos.php";
?>

<table summary="" border="1" class="tabela1" cellpadding="4" align="center">
<tr valign="middle" align="center">
	<td valign="middle" align="right" class="celula1"><?php if(mysql_query($sql)) {?><img src="<?php echo $icones;?>confirmar.png" ><?php } else {?><img src="<?php echo $icones;?>erro.png" ><?php } ?></td><td class="celula2">
	<?php
		if(mysql_query($sql)) { echo "<b>Os dados foram apagados com sucesso!<b>"; }
		else { echo "<b>Não foi possivel apagar os dados!</b> <br>"; echo mysql_error(); }
	?>
	</td>
<tr>
    <td colspan="2" align="center" ><a class="link" href="<?php echo "$paginadestino"; ?>"><input type="button" value="CONTINUAR" class="botao fonte3" autofocus="1"></a></td>
</tr>
</table>




<br /><br />
<?php include "rodape.php"; ?>
