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

<script type="text/javascript" src="produtos_cadastrar.js"></script>

<?php
$codigo = $_GET['codigo'];
$ver = $_GET['ver'];
$operacao = $_GET['operacao'];
$modal=$_GET['modal'];

$sql = "SELECT * 
    FROM produtos 
    LEFT JOIN nfe_ncm on (pro_ncm=ncm_codigo)
    LEFT JOIN nfe_cfop on (pro_cfop=cfop_codigo)
    WHERE pro_codigo='$codigo'
";
$query = mysql_query($sql);
while ($array = mysql_fetch_array($query)) {
    $nome = $array['pro_nome'];
    $tipo = $array['pro_tipocontagem'];
    $categoria = $array['pro_categoria'];
    $descricao = $array['pro_descricao'];
    $marca = $array['pro_marca'];
    $recipiente = $array['pro_recipiente'];
    $volume = $array['pro_volume'];
    $pesoliquido = number_format($array['pro_pesoliquido'],3);
    $composicao = $array['pro_composicao'];
    $codigounico = $array['pro_codigounico'];
    $industrializado = $array['pro_industrializado'];
    $subproduto=$array['pro_podesersubproduto'];
    $tamanho=$array['pro_tamanho'];
    $cor=$array['pro_cor'];
    $referencia=$array['pro_referencia'];
    $ncm_codigo=$array['pro_ncm'];
    $ncm=$array['ncm_id'];
    $cfop_codigo=$array['pro_cfop'];
    $cfop=$array['cfop_id'];
    $ipi=$array['pro_ipi'];
    $pis=$array['pro_pis'];
    $cofins=$array['pro_cofins'];
    $origem_codigo=$array['pro_origem'];
    $dadosfiscais=$array['pro_dadosfiscais'];
    $incluirnanfe=$array['pro_incluirnanfe'];
    $controlarestoque=$array['pro_controlarestoque'];
    $evendido=$array['pro_evendpido'];
    $valunicusto=$array['pro_valunicusto'];
    $valunivenda=$array['pro_valunivenda'];
    if ($subproduto=="") $subproduto=0;
}

if ($origem_codigo=="") $origem_codigo=0;

$sql2 = "SELECT * FROM quiosques_configuracoes WHERE quicnf_quiosque='$usuario_quiosque'";
if (!$query2 = mysql_query($sql2)) die ("Erro SQL Quiosque Configuracões: ".mysql_error());
While ($dados2=  mysql_fetch_assoc($query2)) {
    $usamodulofiscal=$dados2["quicnf_usamodulofiscal"];
    $crt=$dados2["quicnf_crtnfe"];
    $usamoduloestoque=$usaestoque;
}

//Se estiver parametrizado nas configuracoes do quiosque que ele usa módulo fiscal, logo por padrão os dados fiscais devem ser preenchidos
if ($operacao==1) {
    if ($usamodulofiscal==1) {
        $dadosfiscais=1;
        $incluirnanfe=1;
    } else {
        $dadosfiscais=0;
        $incluirnanfe=0;
    }  
} 

if ($ipi=="") $ipi="0,00";
if ($pis=="") $pis="0,00";
if ($cofins=="") $cofins="0,00";


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
<form action="produtos_cadastrar2.php?modal=<?php echo $modal; ?>&codigo=<?php echo"$codigo"; ?>" method="post" name="form1" id="form1">
    <input type="hidden" name="codigo_produto" value="<?php echo $codigo; ?>">
    <input type="hidden" name="usaestoque" value="<?php echo $usaestoque; ?>">
    <input type="hidden" name="valorvendazero" value="<?php echo $valorvendazero; ?>">
    

    <table summary="" border="0" class="tabela1" cellpadding="4">
        <tr>
            <td align="right" width="200px"><b>Nome: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress=""  id="capitalizar" type="text" name="nome" autofocus size="45" class="campopadrao" required value="<?php echo "$nome"; ?>" <?php if ($ver == 1) echo" disabled "; ?> ></td>
        </tr>
       <tr id="id_referencia">
            <td align="right" width="200px"><b>Referência: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeyup="referencia_valida_caracteres_especiais(this.value)"  id="referencia" type="text" name="referencia"  size="35" class="campopadrao" onblur="verifica_referencia_valida(this.value);"  value="<?php echo "$referencia"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder=""> 
             <img src="../imagens/icones/geral/confirmar2.png" id="referencia_icone" name="referencia_icone" width="12px" title="" alt="">            
                
            <span id="span_ref" class="dicacampo"> Ex: numero do pedido/cardápio</span></td>
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
                <b>Código barras EAN: <label class="obrigatorio"></label>
                </b>
            </td>
            <td align="left" width="">
                <input  onkeypress=""  id="capitalizar" type="text" name="codigounico" maxlength="13" size="18" class="campopadrao"  value="<?php echo "$codigounico"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder="">
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
        
       <tr id="id_pesoliquido">
            <td align="right" width="200px"><b>Peso liquido: <label class=""></label></b></td>
            <td align="left" width=""><input  onkeypress="" placeholder="0,000" id="" type="text" name="pesoliquido" id="pesoliquido" size="18" class="campopadrao"  value="<?php echo "$pesoliquido"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder="" <?php if ($obrigacadastroprodutopesoliquido==1) echo " required ";  ?>><span class="dicacampo"> litros</span></td>
       </tr>        
       <tr id="id_volume">
            <td align="right" width="200px"><b>Volume: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress=""  id="capitalizar" type="text" name="volume"  size="15" class="campopadrao"  value="<?php echo "$volume"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder=""><span class="dicacampo"> Ex: 150g ou 200ml</span></td>
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
                <a class="link" href="#"><img id="atualizar_recipiente" src="../imagens/icones/geral/atualizar.png" width="12px" onclick="atualiza_recipientes()"></a>
                <a href="produtos_recipientes_cadastrar.php?modal=1&operacao=cadastrar" target="_blank" class="link">
                    <img id="atualizar_recipiente" src="../imagens/icones/geral/add.png" width="12px">
                </a>                
            </td>
        </tr>
       <tr id="id_tamanho">
            <td align="right" width="200px"><b>Tamanho: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress=""  id="capitalizar" type="text" name="tamanho"  size="12" class="campopadrao"  value="<?php echo "$tamanho"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder=""><span class="dicacampo"> Ex: P, M, 44, 46, 10x20cm</span></td>
        </tr>
       <tr id="id_cor">
            <td align="right" width="200px"><b>Cor: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress=""  id="capitalizar" type="text" name="cor"  size="18" class="campopadrao"  value="<?php echo "$cor"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder=""><span class="dicacampo"> Ex: Amarelo, Branco com listras pretas... (Use sempre cores no masculino!)</span></td>
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

        <?php if ($usavendas==1) { ?>
        <tr  id="linha_evendido">
            <td align="right" width="200px"><b>É Vendido? <label class="obrigatorio"></label></b></td>
            <td align="left" valign="bottom">
                <?php 
                    if ($evendido=="") {
                        if ($usavendas==1) $evendido=1;
                        if ($usavendas==0) $evendido=0;
                    }
                ?>

                <select name="evendido" class="campopadrao" required="required" <?php if ($ver == 1) echo" disabled "; ?> onchange="verifica_evendido();">
                    <option value="0" <?php if ($evendido==0) echo " selected ";  ?>>Não</option>
                    <option value="1" <?php if ($evendido==1) echo " selected ";  ?>>Sim</option>
                </select>
                <span class="dicacampo"> </span>
            </td>
        </tr> 
        <?php } ?>       

        <tr id="linha_tiponeg">  
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
                    $tiponeg = $dados2["mesprotip_tipo"];
                    //echo "($tipo)";
                    if ($tiponeg == 1)
                        $consignacao_marcado = "checked";
                    if ($tiponeg == 2)
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



        <?php  if ($usaproducao==1) { ?>
        <tr>
            <td align="right" width="200px"><b>Sub-produto / Matéria-Prima: <label class="obrigatorio"></label></b></td>
            <td align="left" valign="bottom">
                <select name="subproduto" class="campopadrao" required="required" <?php if ($ver == 1) echo" disabled "; ?> >
                    <option value="0" <?php if ($subproduto==0) echo "selected"; else echo ""; ?>>Não</option>		
                    <option value="1" <?php if ($subproduto==1) echo "selected"; else echo ""; ?>>Sim</option>
                </select>
            </td>
        </tr> 
        <?php } ?>
     
        <?php if ($usamodulofiscal==1) { ?>      
        <tr>
            <td align="right" width="200px"><b>Dados Fiscais: <label class="obrigatorio"></label></b></td>
            <td align="left" valign="bottom">
                <select name="dadosfiscais" onchange="dados_fiscais(this.value)" class="campopadrao" required="required" <?php if ($ver == 1) echo" disabled "; ?> >
                    <option value="0" <?php if ($dadosfiscais==0) echo " selected ";  ?>>Não</option>
                    <option value="1" <?php if ($dadosfiscais==1) echo " selected ";  ?>>Sim</option>
                </select>
                <span class="dicacampo"> </span>
            </td>
        </tr>  
        <tr id="linha_incluirnfe">
            <td align="right" width="200px"><b>Incluir na NFE: <label class="obrigatorio"></label></b></td>
            <td align="left" valign="bottom">
                <select name="incluirnanfe" onchange="" class="campopadrao" required="required" <?php if ($ver == 1) echo" disabled "; ?> >
                    <option value="0" <?php if ($incluirnanfe==0) echo " selected ";  ?>>Não</option>
                    <option value="1" <?php if ($incluirnanfe==1) echo " selected ";  ?>>Sim</option>
                </select>
                <span class="dicacampo"> </span>
            </td>
        </tr>  
        <?php  } ?>         
        <tr id="linha_ncm">
            <td align="right" width="200px"><b>NCM: <label class="obrigatorio"></label></b>
                <img width="12px" class="" src="<?php echo $icones; ?>info.png" alt="Nomenclatura Comum do Merscosul" title="Nomenclatura Comum do Merscosul">
            </td>
            <td align="left" width="">
                <input  
                    id="nfencm" 
                    name="nfencm"  
                    type="text" 
                    size="16"
                    maxlength="8"
                    class="campopadrao"  
                    value="<?php echo "$ncm"; ?>" 
                    <?php if ($ver == 1) echo" disabled "; ?> 
                    onkeyup="pesquisa_ncm(this.value)"
                    onblur="pesquisa_ncm(this.value)"
                    placeholder=""
                    <?php if ($dadosfiscais==1) echo " required ";   ?>
                >
                <a class="link" href="ncm.pdf" target="_blank">
                    <img width="12px" src="<?php echo $icones; ?>procurar.png" alt="" >
                </a>
                <label id="label_ncm"></label>
                <input type="hidden" name="nfencm_codigo" value="<?php echo $ncm_codigo; ?>">
            </td>
        </tr>        
        <tr  id="linha_cfop">
            <td align="right" width="200px"><b>CFOP Padrão:<label class="obrigatorio"></label></b>
                <img width="12px" src="<?php echo $icones; ?>info.png" alt="Código Fiscal de Operações e Prestações" title="Código Fiscal de Operações e Prestações">
            </td>
            <td align="left" valign="bottom">
                <input  
                    id="nfecfop" 
                    name="nfecfop"  
                    type="text" 
                    size="10"
                    maxlength="5"
                    class="campopadrao"  
                    value="<?php echo "$cfop"; ?>" 
                    <?php if ($ver == 1) echo" disabled "; ?> 
                    onkeyup="pesquisa_cfop(this.value);"
                    onblur="pesquisa_cfop(this.value);"
                    onkeypress=""
                    placeholder=""
                    <?php if ($dadosfiscais==1) echo " required ";   ?>
                >
                <a class="link" href="produtos_cfop.php" target="_blank">
                    <img width="12px" src="<?php echo $icones; ?>procurar.png" alt="" >
                </a>
                <label id="label_cfop"></label>
                <input type="hidden" name="nfecfop_codigo" value="<?php echo $cfop_codigo; ?>">
            </td>
        </tr>        
        <tr id="linha_icms">
            <td align="right" width="200px"><b>ICMS atual: <label class="obrigatorio"></label></b>
                <img width="12px" class="" src="<?php echo $icones; ?>info.png" alt="Imposto Sobre Circulação De Mercadorias e Serviços" title="Código Fiscal de Operações e Prestações">
            </td>
            <td align="left" width=""> 
                <input  
                    onkeypress=""  
                    id="nfeicms" 
                    type="text" 
                    name="nfeicms" 
                    onclick="" 
                    onkeyup="" 
                    size="6" 
                    class="campopadrao" 
                    disabled 
                    value="<?php echo number_format($icms_atual,2,',',''); ?>" 
                    placeholder=""
                >
                <span class="dicacampo">%</span></td>
        </tr>
        
        <tr id="linha_ipi">
            <td align="right" width="200px"><b>IPI: <label class="obrigatorio"></label></b>
                <img width="12px" class="" src="<?php echo $icones; ?>info.png" alt="Imposto sobre Produto Industrializado" title="Imposto sobre Produto Industrializado">
            </td>
            <td align="left" width=""> 
                <input  
                    onkeypress=""  
                    id="nfeipi" 
                    type="text" 
                    name="nfeipi" 
                    onclick="select()" 
                    onkeyup="formato_porcentagem()" 
                    size="6" 
                    class="campopadrao"
                    <?php if (($simplesnacional==1)||($ver==1)) echo " disabled "; ?>
                    value="<?php echo number_format($ipi,2,',',''); ?>" 
                    placeholder=""
                    <?php if (($dadosfiscais==1)&&($simplesnacional==0)) echo " required "; else echo " ";?> 
                >
                <span class="dicacampo">%</span></td>
        </tr>
        <tr id="linha_pis">
            <td align="right" width="200px"><b>PIS: <label class="obrigatorio"></label></b>
                <img width="12px" class="" src="<?php echo $icones; ?>info.png" alt="Programa de Integração Social" title="Programa de Integração Social">
            </td>
            <td align="left" width=""> 
                <input  
                    onkeypress=""  
                    id="nfepis" 
                    type="text" 
                    name="nfepis" 
                    onclick="select()" 
                    onkeyup="formato_porcentagem()" 
                    size="6" 
                    class="campopadrao"  
                    <?php if (($simplesnacional==1)||($ver==1)) echo " disabled "; ?>
                    value="<?php echo number_format($pis,2,',',''); ?>" 
                    placeholder=""
                    <?php if (($dadosfiscais==1)&&($simplesnacional==0)) echo " required "; else echo " ";?> 
                >
                <span class="dicacampo">%</span></td>
        </tr>
        <tr id="linha_cofins">
            <td align="right" width="200px"><b>COFINS: <label class="obrigatorio"></label></b>
                <img width="12px" class="" src="<?php echo $icones; ?>info.png" alt="Contribuição para o Financiamento da Seguridade Social" title="Contribuição para o Financiamento da Seguridade Social">
            </td>
            <td align="left" width=""> 
                <input  
                    onkeypress=""  
                    id="nfecofins" 
                    type="text" 
                    name="nfecofins" 
                    onclick="select()" 
                    onkeyup="formato_porcentagem()" 
                    size="6" 
                    class="campopadrao"  
                    <?php if (($simplesnacional==1)||($ver==1)) echo " disabled "; ?>
                    value="<?php echo number_format($cofins,2,',',''); ?>"
                    placeholder=""
                    <?php if (($dadosfiscais==1)&&($simplesnacional==0)) echo " required "; else echo " ";?>  
                >
                <span class="dicacampo">%</span></td>
        </tr>
        
        <tr id="linha_origem">
            <td align="right" width="200px"><b>Origem: <label class="obrigatorio"></label></b></td>
            <td align="left" width="">
                <input  
                    id="nfeorigem" 
                    name="nfeorigem"  
                    type="text" 
                    size="3"
                    maxlength="1"
                    class="campopadrao"  
                    value="<?php echo "$origem_codigo"; ?>" 
                    <?php if ($ver == 1) echo" disabled "; ?> 
                    onkeyup="pesquisa_origem(this.value)"
                    onblur="pesquisa_origem(this.value)"
                    placeholder=""
                    <?php if ($dadosfiscais==1) echo " required ";   ?>
                >
                <a class="link" href="produtos_origem.php" target="_blank">
                    <img width="12px" src="<?php echo $icones; ?>procurar.png" alt="" >
                </a>
                <label id="label_origem"></label>
            </td>
        </tr> 

        
        <tr id="linha_controlarestoque">
            <td align="right" width="200px"><b>Controlar Estoque <label class="obrigatorio"></label></b></td>
            <td align="left" valign="bottom">
                <?php 
                    if ($controlarestoque=="") {
                        if ($usamoduloestoque==1) $controlarestoque=1;
                        if ($usamoduloestoque==0) $controlarestoque=0;
                    }
                ?>

                <select name="controlarestoque" class="campopadrao" required="required" <?php if ($ver == 1) echo" disabled "; ?> onchange="verifica_controlarestoque();">
                    <option value="0" <?php if ($controlarestoque==0) echo " selected ";  ?>>Não</option>
                    <option value="1" <?php if ($controlarestoque==1) echo " selected ";  ?>>Sim</option>
                </select>
                <span class="dicacampo"> </span>
            </td>
        </tr>  

          

       <tr id="valunicusto_linha">
            <td align="right" width="200px"><b>Valor Unitário Custo: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress="" placeholder="R$ 0,00" id="" type="text" name="valunicusto" id="valunicusto" size="18" class="campopadrao"  value="<?php echo "$valunicusto"; ?>" <?php if ($ver == 1) echo" disabled "; ?> placeholder=""><span class="dicacampo"></span></td>
        </tr>
       <tr id="valunivenda_linha">
            <td align="right" width="200px"><b>Valor Unitário Venda: <label class="obrigatorio"></label></b></td>
            <td align="left" width=""><input  onkeypress="" onkeyup="" placeholder="R$ 0,00" id="" type="text" name="valunivenda" id="valunivenda" size="18" class="campopadrao"  value="<?php echo "$valunivenda"; ?>" <?php if ($ver == 1) echo" disabled "; ?>required  placeholder=""><span class="dicacampo"></span></td>
        </tr>


    </table>

    <input type="hidden" name="cooperativa_oculto" value="<?php echo $usuario_cooperativa ?>">
    <input type="hidden" name="nfecrt" value="<?php echo $crt; ?>">
    
    <br />
    <hr align="left" >
    <?php
    
    /*
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
    }*/

    if ($ver == 1) {
            ?><a href="javascript:window.close(0);" class="link">&nbsp;<input type="button" value="FECHAR" class="botao fonte3"></a>
        <?php } else {
            ?><input type="hidden" name="link" value="<?php echo $linkanterior; ?>">
            <input type="submit" value="SALVAR" name="submit1" class="botao fonte3"> 
            <?php if ($modal==1) { ?><a href="javascript:window.close(0);" class="link">&nbsp; <input type="button" value="FECHAR" class="botao fonte3"><?php } else { ?>  <a href="produtos.php?modal=<?php echo $modal; ?>" class="link">&nbsp;  <input type="button" value="CANCELAR" class="botao fonte3"> <?php }?>
            
            
            </a> 
    <?php } ?>
    <input type="hidden" name="nome2" value="<?php echo "$nome"; ?>">
    <?php
    if (($linhas8 > 0) || ($ver == 1)) { ?>
        <input type="hidden" name="tipo" value="<?php echo "$tipo"; ?>">
        

        <?php }    ?>

<input type="hidden" name="usavendas" value="<?php echo "$usavendas"; ?>">
</form>



<?php include "rodape.php"; ?>
