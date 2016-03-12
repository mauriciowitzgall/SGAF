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
    $industrializado = $array['pro_industrializado'];
}

$sql2="SELECT quitipneg_tipo FROM quiosques_tiponegociacao WHERE quitipneg_quiosque=$usuario_quiosque";
if (!$query2 = mysql_query($sql2)) die("Erro SQL2: ".mysql_error());
$tiponegquicon=0;
$tiponegquirev=0;
while ($dados2 = mysql_fetch_array($query2)) {
    if ($dados2["quitipneg_tipo"]==1) $tiponegquicon=1;
    if ($dados2["quitipneg_tipo"]==2) $tiponegquirev=1;
}

?>
<script type="text/javascript" src="js/capitular.js"></script>
<script type="text/javascript">

function atualiza_categorias () {
    $.post("produtos_cadastrar_atualiza_categorias.php",{
        cooperativa:<?php echo $usuario_cooperativa; ?>
    },function(valor2){
        //alert(valor2);
        $("select[name=categoria]").html(valor2);
    });    
}
    
    window.onload = function(){
    //industrializado
    ind=$("select[name=industrializado]").val();
    if (ind==0) {
        $("tr[id=id_marca]").hide(); 
        $("tr[id=id_codigounico]").hide(); 
    } else {
        $("tr[id=id_marca]").show(); 
        $("tr[id=id_codigounico]").show(); 
    }
    
    //tipo de contagem    
    tipocon=$("select[name=tipo]").val();
    if ((tipocon==2)||(tipocon==3)) {
        $("tr[id=id_volume]").hide(); 
        $("tr[id=id_recipiente]").hide(); 
    } else {
        $("tr[id=id_volume]").show(); 
        $("tr[id=id_recipiente]").show(); 
        
    }
    
    
    
    
}

</script>

 
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
    //echo "<br>";
    $tpl = new Template("templates/notificacao.html");
    $tpl->ICONES = $icones;
    $tpl->MOTIVO_COMPLEMENTO = "Você deve cadastrar uma categoria antes de cadastrar um produto! <br>Clique no botão abaixo para ir para tela de cadastro de categorias!";
    $tpl->block("BLOCK_ATENCAO");
    $tpl->BOTAOGERAL_DESTINO = "categorias_cadastrar.php?operacao=cadastrar";
    $tpl->BOTAOGERAL_TIPO="button";
    $tpl->BOTAOGERAL_NOME="CADASTRAR CATEGORIA";
    $tpl->block("BLOCK_BOTAOGERAL_AUTOFOCO");
    $tpl->block("BLOCK_BOTAOGERAL");
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
            <td align="right" width="200px"><b>Produto industrializado <label class="obrigatorio"></label></b></td>
            <td align="left" width="">
                <select name="industrializado" id="industrializado" class="campopadrao" required onchange="produto_industrializado(this.value)" <?php if ($ver==1) echo " disabled "; ?>>
                    <option>Selecione</option>
                    <option value="1" <?php if ($industrializado==1) echo " selected "; ?>>Sim</option>
                    <option value="0" <?php if ($industrializado==0) echo " selected "; ?>>Não</option>
                </select>
            </td>
        </tr>
        <tr id="id_marca">
            <td align="right" width="200px"><b>Marca: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress=""  id="capitalizar" type="text" name="marca"  size="30" class="campopadrao"  value="<?php echo "$marca"; ?>" <?php if ($ver == 1) echo" disabled "; ?> ></td>
        </tr>
        <tr id="id_codigounico">
            <td align="right" width="200px">
                <b>Código Único (barras): <label class="obrigatorio"></label>
                </b>
            </td>
            <td align="left" width="">
                <input  onkeypress=""  id="capitalizar" type="text" name="codigounico" maxlength="13" size="15" class="campopadrao"  value="<?php echo "$codigounico"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder="">
            </td>
        </tr>
        <tr>
           <td align="right" width="200px"><b>Tipo de Contagem: <label class="obrigatorio"></label></b></td>
           <td align="left" width="">
               <select name="tipo" id="tipo" class="campopadrao" required="required" onchange="sigla();tipo_contagem(this.value);" 
               <?php
               //Se tiver alguma entrada com este produto não dá mais para editar o tipo de contagem
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
                   if ((empty($tipo))&&($array1[0]==1)) echo " selected "; 
                       ?> ><?php echo"$array1[1]"; ?></option><?php
                       }
                   ?>
               </select>
               <?php if ($linhas8>0) {?>
               <img src="../imagens/icones/geral/info.png" width="12px" title="Não é possível editar o tipo de contagem porque este produto possui entradas" alt="Informação">            
               <?php } ?>
           </td>
       </tr>
        
       <tr id="id_volume">
            <td align="right" width="200px"><b>Volume: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress=""  id="capitalizar" type="text" name="volume"  size="15" class="campopadrao"  value="<?php echo "$volume"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder=""><span class="dicacampo">Ex: 150g ou 200ml</span></td>
        </tr>
        <tr id="id_recipiente">
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
            <td align="right" width="200px"><b>Categoria: <label class="obrigatorio"></label></b></td>
            <td align="left" valign="bottom">
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
                <a class="link" href="#"><img id="atualizar_categoria" src="../imagens/icones/geral/atualizar.png" width="12px" onclick="atualiza_categorias()"></a>
                <a href="categorias_cadastrar.php?modal=1" target="_blank" class="link">
                    <img id="atualizar_categoria" src="../imagens/icones/geral/add.png" width="12px">
                </a>
                
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
            //Se o quiosque tem apenas um tipo de negociação então este deve vir marcado por padrão
            if (($tiponegquicon==1)&&($tiponegquirev==0)) {
                $consignacao_marcado=" checked ";
                $revenda_marcado="  ";
            }
            if (($tiponegquicon==0)&&($tiponegquirev==1)) {
                $consignacao_marcado="  ";
                $revenda_marcado=" checked ";
            }
            
            
            if ($ver == 1)
                $desabilitado = " disabled ";
            ?>
            <td align="left" width="" class="">            
                <?php if ($tiponegquicon==1) { ?>
                <span class="" id="">
                    <input type="checkbox" value="1" name="box[1]" <?php echo $consignacao_marcado; echo $desabilitado; ?>>
                    <label>Consignação</label>
                    <br>
                </span>
                <?php } 
                if ($tiponegquirev==1) { ?>
                <span class="" id="">
                    <input type="checkbox" value="2" name="box[2]" <?php echo $revenda_marcado; echo $desabilitado; ?>>
                    <label>Revenda</label>
                    <br>
                </span>
                <?php } ?>
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
