<?php $tipopagina="produtos"; 
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_categorias_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$modal=$_GET["modal"];
?>
<?php include "includes.php"; ?>
<script type="text/javascript" src="paginacao.js"></script>
<table summary="" class="" border="0">
<tr>
	<td width="35px"><img width="50px" src="<?php echo $icones;?>categorias.png" alt="" ></td>
	<td valign="bottom">
		<label class="titulo" > CATEGORIAS</label><br />
		<label class="subtitulo"> PESQUISA/LISTAGEM </label>
	</td>
</tr>
</table>
<hr align="left" class="linhacurta" >

<?php //filtro e ordenação
$filtronome=$_POST['filtronome'];
$filtroobs=$_POST['filtroobs'];
$filtroproprio = $_POST['filtroproprio'];
if ($filtroproprio=="") $filtroproprio=1;
if ($filtroproprio==1) $sql_filtro.=" and cat_quiosquequecadastrou=$usuario_quiosque ";
?>
<form action="categorias.php" name="form1" method="post">
<table summary="" class="tabelafiltro" border="0">
<tr>
	<td width="245px"><b>&nbsp;Nome:</b><br><input size="25" type="text" name="filtronome" class="campopadrao" value="<?php echo "$filtronome";?>"></td>
    <td width="15px"></td>
    <td><b>&nbsp;Próprio:</b><br>
        <select name="filtroproprio" class="campopadrao" >
            <option value="1" <?php if (($filtroproprio=="")||($filtroproprio==1)) echo " selected "?>>Sim</option>
            <option value="0" <?php if ($filtroproprio==0) echo " selected "?>>Todos</option>
        </select>
    </td>
</tr>
</table>
<br>
<table>
    <tr>
        <td>
            <input type="submit" class="botao fonte3" value="PESQUISAR">
        </td>
        <td>
            <a href="categorias.php" class="link">
                <input type="button" value="REINICIAR PESQUISA" class="botao fonte3">
            </a>
        </td>
        <td width="100%" align="right">
            <?php if ($permissao_categorias_cadastrar==1) {  ?>
            <a href="categorias_cadastrar.php?operacao=1" class="link"><input type="button" value="CADASTRAR" class="botaopadrao botaopadraocadastrar" autofocus="1"></a>
            <?php } else { ?>
<!--            <input type="button" value="CADASTRAR" class="botaopadrao botaopadraocadastrar" disabled>-->
            <?php } ?>
        </td>                
    </tr>
</table>    
<br>

<?php
 $sql=" SELECT *
			FROM produtos_categorias
			WHERE cat_nome like '%$filtronome%' and cat_cooperativa=$usuario_cooperativa $sql_filtro
			ORDER BY cat_nome";
 
 //Pagina��o
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Pagina��o:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se � a primeira vez que acessa a pagina ent�o come�ar na pagina 1
if (($paginaatual == "") || ($paginas < $paginaatual) || ($paginaatual <= 0)) {
    $paginaatual = 1;
}
$comeco = ($paginaatual - 1) * $por_pagina;
$sql = $sql . " LIMIT $comeco,$por_pagina ";



$query = mysql_query($sql);
$linhas = mysql_num_rows($query);
?>
<table border="1" class="tabela1" cellpadding="4" width="100%">
<tr valign="middle" class="tabelacabecalho1">
	<td width="">NOME</td>
	<td width="" align="center">OBSERVAÇÃO</td>
	<td width="" colspan="4">OPERAÇÕES </td>
</tr>

<?php
while($array=mysql_fetch_array($query))
{
	$codigo= $array['cat_codigo'];
	$nome= $array['cat_nome'];
    $obs= $array['cat_obs'];
	$quiosquequecadastrou= $array['cat_quiosquequecadastrou'];
?>

<tr class="lin">
   <td><?php echo "$nome";?></td>
   <td><?php echo "$obs";?>	</td>
   <td align="center" class="fundo1" width="35px">
        <img   width="15px" src="
        <?php 
        if (($quiosquequecadastrou==$usuario_quiosque)) { 
            echo "$icones\info_desabilitado.png ";
            $motivo="";
        } else  if ($quiosquequecadastrou==0) { 
            echo "$icones\info2.png"; 
            $motivo="Registrado pelos gestores da cooperativa!";
        } else {
            echo "$icones\info.png"; 
            $sql3="SELECT qui_nome FROM quiosques WHERE qui_codigo=$quiosquequecadastrou";
            if (!$query3=mysql_query($sql3)) die("Erro SQL3:" . mysql_error());
            $dados3=  mysql_fetch_assoc($query3);
            $quiosquequecadastrou_nome=$dados3["qui_nome"];
            $motivo="Registrado pelo quiosque: $quiosquequecadastrou_nome";

        }?>" 
        title="<?php echo $motivo; ?>" alt="Atenção"/> </a>
    </td>
	<td align="center" class="fundo1" width="35px">
            <?php if ($permissao_categorias_ver == 1) { ?>
            <a target="_blank" href="categorias_cadastrar.php?modal=1&codigo=<?php echo"$codigo";?>&ver=1" class="link1"><img   width="15px" src="<?php echo $icones;?>detalhes.png" title="Detalhes" alt="Detalhes"/> </a>
            <?php } else { ?>           
            <img   width="15px" src="<?php echo $icones;?>detalhes_desabilitado.png" title="Detalhes" alt="Detalhes"/> 
            <?php } ?>
        </td>
	<td align="center" class="fundo1" width="35px">
            <?php if ($permissao_categorias_cadastrar == 1) { ?>
            <a href="categorias_cadastrar.php?codigo=<?php echo"$codigo";?>" class="link1"><img   width="15px" src="<?php echo $icones;?>editar.png" title="Editar"  alt="Editar" /></a>
            <?php } else { ?>           
            <img   width="15px" src="<?php echo $icones;?>editar_desabilitado.png" title="Editar"  alt="Editar" />
            <?php } ?>
        </td>
	<td align="center" class="fundo1" width="35px"> 
            <?php  if ($permissao_categorias_excluir == 1) { ?>
            <a href="categorias_deletar.php?codigo=<?php echo"$codigo";?>" class="link1"><img  width="15px"  src="<?php echo $icones;?>excluir.png"  title="Excluir" alt="Excluir" /></a>
            <?php } else { ?>           
            <img  width="15px"  src="<?php echo $icones;?>excluir_desabilitado.png"  title="Excluir" alt="Excluir" />
            <?php } ?>
        </td>
	<?php
}
if ($linhas=="0")
{
	?> <tr><td colspan="30" align="center" class="errado"> <?php echo "Nenhum resultado!" ?> </td></tr> <?php
}
?>
</tr>
</table>
<table class="paginacao_fundo" width="100%" border="0">
    <tr valign="middle" align="center" class="paginacao_linha">
    <td align="right">
        <input onclick="paginacao_retroceder()" type="image" width="25px"   src="<?php echo $icones; ?>esquerda.png"  title="Anterior" alt="Anterior" />
    </td>
    <td width="170px">
        <input size="5" type="text" name="paginaatual" class="campopadrao" value="<?php echo $paginaatual; ?>">
        <span>/</span>
        <input disabled size="5" type="text" name="paginas" class="campopadrao" value="<?php echo $paginas; ?>">
    </td>
    <td align="left">
        <input onclick="paginacao_avancar()"  type="image" width="25px"   src="<?php echo $icones; ?>direita.png"  title="Pr�xima" alt="Pr�xima" />
    </td>
</tr>
</table>

