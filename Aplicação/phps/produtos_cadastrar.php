<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
$ver = $_GET['ver'];
if ($ver == 1) {
    if ($permissao_produtos_ver <> 1)
        header("Location: permissoes_semacesso.php");
} else {
    if ($permissao_produtos_cadastrar <> 1)
        header("Location: permissoes_semacesso.php");
}

$tipopagina = "produtos";
include "includes.php";
?>

<?php
$codigo = $_GET['codigo'];
$ver = $_GET['ver'];
$sql = "SELECT * FROM produtos WHERE pro_codigo='$codigo'";
$query = mysql_query($sql);
while ($array = mysql_fetch_array($query)) {
    $nome = $array['pro_nome'];
    $tipo = $array['pro_tipocontagem'];
    $categoria = $array['pro_categoria'];
    $descricao = $array['pro_descricao'];
    $marca = $array['pro_marca'];
    $recipiente = $array['pro_recipiente'];
    $volume = $array['pro_volume'];
    $composicao = $array['pro_composicao'];
    $codigounico = $array['pro_codigounico'];
}
?>
<script type="text/javascript" src="js/capitular.js"></script>


<table summary="" class="" border="0">
    <tr>
        <td width="35px"><img width="50px" src="<?php echo $icones; ?>produtos.png" alt="" ></td>
        <td valign="bottom">
            <label class="titulo" > PRODUTOS </label><br />
            <label class="subtitulo"> CADASTRO/EDIÇÃO </label>
        </td>
    </tr>
</table>
<hr align="left" class="linhacurta" >
<br />
<?php
//Se não houverem categorias cadastras o sistema sugere primeiro fazer isto
$sql = "SELECT cat_codigo FROM produtos_categorias WHERE cat_cooperativa=$usuario_cooperativa";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas == 0) {
    echo "<br>";
    $tpl = new Template("templates/notificacao.html");
    $tpl->ICONES = $icones;
    $tpl->MOTIVO_COMPLEMENTO = "Você deve cadastrar uma categoria antes de cadastrar um produto! <br>Clique no botão abaixo para ir para tela de cadastro de categorias!";
    $tpl->block("BLOCK_ATENCAO");
    $tpl->DESTINO = "categorias_cadastrar.php?operacao=cadastrar";
    $tpl->block("BLOCK_BOTAO");
    $tpl->show();
    exit;
}
?>
<form action="produtos_cadastrar2.php?codigo=<?php echo"$codigo"; ?>" method="post" name="form1">
    <table summary="" border="0" class="tabela1" cellpadding="4">
        <tr>
            <td align="right" width="200px"><b>Nome: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress=""  id="capitalizar" type="text" name="nome" autofocus size="45" class="campopadrao" required value="<?php echo "$nome"; ?>" <?php if ($ver == 1) echo" disabled "; ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Marca: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress=""  id="capitalizar" type="text" name="marca"  size="30" class="campopadrao"  value="<?php echo "$marca"; ?>" <?php if ($ver == 1) echo" disabled "; ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Volume: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress=""  id="capitalizar" type="text" name="volume"  size="15" class="campopadrao"  value="<?php echo "$volume"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder=""><span class="dicacampo">Ex: 150g ou 200ml</span></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Recipiente / Embalagem: <label class="obrigatorio"></label></b></td>
            <td align="left" width="">
                <select name="recipiente" id="tipo" class="campopadrao"  onchange="" 
                <?php              
                if ($ver == 1)
                    echo " disabled ";
                ?> >
                    <option value=""> - </option>		
                    <?php
                    $sql1 = "SELECT * FROM produtos_recipientes ORDER BY prorec_nome";
                    $query1 = mysql_query($sql1);
                    while ($array1 = mysql_fetch_array($query1)) {
                        ?><option value="<?php echo"$array1[0]"; ?>" <?php
                    if ($array1[0] == $recipiente) {
                        echo "selected ";
                    }
                        ?> ><?php echo"$array1[1]"; ?></option><?php
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Tipo de Contagem: <label class="obrigatorio"></label></b></td>
            <td align="left" width="">
                <select name="tipo" id="tipo" class="campopadrao" required="required" onchange="sigla()" 
                <?php
                $sql8 = "
                SELECT entpro_produto
                FROM entradas_produtos
                WHERE entpro_produto=$codigo
            ";
                $query8 = mysql_query($sql8);
                $linhas8 = mysql_num_rows($query8);
                if (($linhas8 > 0) || ($ver == 1))
                    echo " disabled ";
                ?> >
                    <option value="">Selecione</option>		
                    <?php
                    $sql1 = "SELECT * FROM produtos_tipo ";
                    $query1 = mysql_query($sql1);
                    while ($array1 = mysql_fetch_array($query1)) {
                        ?><option value="<?php echo"$array1[0]"; ?>" <?php
                    if ($array1[0] == $tipo) {
                        echo "selected ";
                    }
                        ?> ><?php echo"$array1[1]"; ?></option><?php
                        }
                    ?>
                </select>
                <input type="hidden" name="tipo2" value="<?php echo $tipo; ?>"> 
            </td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Categoria: <label class="obrigatorio"></label></b></td>
            <td align="left" width="">
                <select name="categoria" class="campopadrao" required="required" <?php if ($ver == 1) echo" disabled "; ?> >
                    <option value="">Selecione</option>		
                    <?php
                    $sql1 = "SELECT * FROM produtos_categorias WHERE cat_cooperativa=$usuario_cooperativa ORDER BY cat_nome";
                    $query1 = mysql_query($sql1);
                    while ($array1 = mysql_fetch_array($query1)) {
                        ?><option value="<?php echo"$array1[0]"; ?>" <?php
                    if ($array1[0] == $categoria) {
                        echo "selected ";
                    }
                        ?> ><?php echo"$array1[1]"; ?></option><?php
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right" width="200px">
                <b>Código Único (barras): <label class="obrigatorio"></label>
                </b>
            </td>
            <td align="left" width="">
                <input  onkeypress=""  id="capitalizar" type="text" name="codigounico" maxlength="13" size="15" class="campopadrao"  value="<?php echo "$codigounico"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder="">
                <span class="dicacampo">Ex: 150g ou 200ml</span>
            </td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Composição / Ingredientes:</b></td>
            <td align="left" width=""><textarea class="textarea1" cols="55" name="composicao" <?php if ($ver == 1) echo" disabled "; ?> ><?php echo "$composicao"; ?></textarea></td>
        </tr>

        <tr>
            <td align="right" width="200px"><b>Descrição:</b></td>
            <td align="left" width=""><textarea class="textarea1" cols="55" name="descricao" <?php if ($ver == 1) echo" disabled "; ?> ><?php echo "$descricao"; ?></textarea></td>
        </tr>

        <tr>  
            <td align="right" width="200px">

                <b>
                    <span class="titulo1">
                        Tipo de negociação:
                    </span>
                    <label class="obrigatorio"></label>
                </b>
            </td>  


            <?php
            //Tipo de negociação
            if ($codigo != "") {

                $sql2 = "SELECT * FROM mestre_produtos_tipo WHERE mesprotip_produto=$codigo";
                $query2 = mysql_query($sql2);
                if (!$query2)
                    die("ERRO SQL:" . mysql_error());
                while ($dados2 = mysql_fetch_assoc($query2)) {
                    $tipo = $dados2["mesprotip_tipo"];
                    //echo "($tipo)";
                    if ($tipo == 1)
                        $consignacao_marcado = "checked";
                    if ($tipo == 2)
                        $revenda_marcado = "checked";
                }
            }
            if ($ver == 1)
                $desabilitado = " disabled ";
            ?>
            <td align="left" width="" class="">            
                <span class="" id="">
                    <input type="checkbox" value="1" name="box[1]" <?php echo $consignacao_marcado;
            echo $desabilitado; ?>><label>Consignação</label>
                    <br>
                </span>
                <span class="" id="">
                    <input type="checkbox" value="2" name="box[2]" <?php echo $revenda_marcado;
            echo $desabilitado; ?>><label>Revenda</label>
                    <br>
                </span>
            </td>        
        </tr>


    </table>

    <br />
    <hr align="left" >
    <?php
//Verifica o link destino do bot�o voltar
    $linkanterior = $_GET["link"];
    $fornecedor = $_GET["fornecedor"];
    if ($linkanterior == "") {
        $link_destino = "produtos.php";
    }
    if ($linkanterior == "estoque.php") {
        $link_destino = $linkanterior;
    }
    if ($linkanterior == "estoque_validade.php") {
        $link_destino = $linkanterior;
    }
    if ($linkanterior == "estoque_porfornecedor_produto.php") {
        $link_destino = $linkanterior . "?fornecedor=$fornecedor";
    }

    if ($ver == 1) {
        ?><a href="<?php echo $link_destino; ?>" class="link">&nbsp;<input type="button" value="VOLTAR" class="botao fonte3"></a> <?php } else {
        ?><input type="hidden" name="link" value="<?php echo $linkanterior; ?>">
        <input type="submit" value="SALVAR" name="submit1" class="botao fonte3"> <a href="produtos.php" class="link">&nbsp;<input type="button" value="CANCELAR" class="botao fonte3"></a> <?php } ?>
    <input type="hidden" name="nome2" value="<?php echo "$nome"; ?>">
    <?php
    if (($linhas8 > 0) || ($ver == 1)) {
        ?><input type="hidden" name="tipo" value="<?php echo "$tipo"; ?>"><?php
}
    ?>
</form>

<?php include "rodape.php"; ?>
