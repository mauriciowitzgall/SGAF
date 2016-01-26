<?php  
require "login_verifica.php";
if ($permissao_quiosque_cadastrar<>1) {
    header("Location: permissoes_semacesso.php");
    exit;
} 
include "includes.php";


$erro=0;
$codigo = $_POST['codigo']; 
$nome = $_POST['nome'];
$cidade = $_POST['cidade'];
$cep = $_POST['cep'];
$bairro = $_POST['bairro'];
$vila = $_POST['vila'];
$endereco = $_POST['endereco'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$referencia = $_POST['referencia'];
$fone1 = $_POST['fone1'];
$fone2 = $_POST['fone2'];
$email = $_POST['email'];
$obs = $_POST['obs'];
$datacadastro = date("Y-m-d");
$horacadastro = date("h:i:s");
$dataedicao = date("Y-m-d");
$horaedicao = date("h:i:s");
$paginadestino="quiosques.php";
if ($permissao_quiosque_definircooperativa==1) {
    $cooperativa=$_POST['cooperativa'];    
} else {
    $cooperativa=$usuario_cooperativa;    
}  



//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "QUIOSQUES";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques.png";
$tpl_titulo->show();

if($codigo=="")
{		
	$sql = " INSERT INTO quiosques (qui_nome,qui_cidade,qui_cep,qui_bairro,qui_vila,qui_endereco,qui_numero,qui_complemento,qui_referencia,qui_fone1,qui_fone2,qui_email,qui_obs,qui_datacadastro,qui_horacadastro,qui_cooperativa,qui_usuario)
	VALUES ('$nome','$cidade','$cep','$bairro','$vila','$endereco','$numero','$complemento','$referencia','$fone1','$fone2','$email','$obs','$datacadastro','$horacadastro','$cooperativa','$usuario_codigo');";
}
else
{
	$sql = "UPDATE quiosques SET 
	qui_nome='$nome',
	qui_cidade='$cidade',
	qui_cep='$cep',
	qui_bairro='$bairro',
	qui_vila='$vila',
	qui_endereco='$endereco',
	qui_numero='$numero',
	qui_complemento='$complemento',
	qui_referencia='$referencia',
	qui_fone1='$fone1',
	qui_fone2='$fone2',
	qui_email='$email',
	qui_cooperativa='$cooperativa',
	qui_dataedicao='$dataedicao',
	qui_horaedicao='$horaedicao',
        qui_usuario='$usuario_codigo',
        qui_obs='$obs'
	WHERE qui_codigo = '$codigo'";
}
if (mysql_query($sql)) { } else { echo $erro=1; $sqlerro=mysql_error(); }
?>
<table summary="" border="1" class="tabela1" cellpadding="4" align="center">
<tr valign="middle" align="center">
	<td valign="middle" align="right" class="celula1"><?php if( $erro==0) { ?><img src="<?php echo $icones;?>confirmar.png" ><?php } else {?><img src="<?php echo $icones;?>erro.png" ><?php } ?></td><td class="celula2">
	<?php
		if($erro==0) { echo "<b>Os dados foram salvos com sucesso!<b>"; }
		else { echo "<b>Não foi possivel salvar os dados!</b> <br>"; echo $sqlerro; }
	?>
	</td>
<tr>
    <td colspan="2" align="center" ><a class="link" href="<?php echo "$paginadestino"; ?>"><input autofocus="" type="button" value="CONTINUAR" class="botao fonte3"></a></td>
</tr>
</table>

<br /><br />
<?php include "rodape.php"; ?>

