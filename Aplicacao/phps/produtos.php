
<?php
//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_produtos_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "produtos";
?>
<?php include "includes.php"; 

$usamoduloproducao=usamoduloproducao($usuario_quiosque); 
$usavendaporcoes=usavendaporcoes($usuario_quiosque); 


?>
<script type="text/javascript" src="paginacao.js"></script>

<table summary="" class="" border="0">
    <tr>
        <td width="35px"><img width="50px" src="<?php echo $icones; ?>produtos.png" alt="" ></td>
        <td valign="bottom">
            <label class="titulo" > PRODUTOS </label><br />
            <label class="subtitulo"> PESQUISA/LISTAGEM </label>
        </td>
    </tr>
</table>
<hr align="left" class="linhacurta" >

<?php
//filtro e ordena��o
$filtrocodigo = $_POST['filtrocodigo'];
$filtronome = $_POST['filtronome'];
$filtrocategoria = $_POST['filtrocategoria'];
$filtromarca = $_POST['filtromarca'];
$filtrotamanho = $_POST['filtrotamanho'];
$filtrocor = $_POST['filtrocor'];
$filtroreferencia = $_POST['filtroreferencia'];
$filtrodescricao = $_POST['filtrodescricao'];
$filtroproprio = $_POST['filtroproprio'];
$filtroean = $_POST['filtroean'];
if ($filtroproprio=="") $filtroproprio=1;
?>
<form action="produtos.php" name="form1" method="post">
    <table summary="" class="tabelafiltro" border="0">
        <tr>
            <td><b>&nbsp;Código:</b><br>
                <input size="10"  type="text" name="filtrocodigo" class="campopadrao" value="<?php echo "$filtrocodigo"; ?>"></td>
            <td width="15px"></td>
            <td><b>&nbsp;Nome:</b><br><input size="25" type="text" name="filtronome" class="campopadrao" value="<?php echo "$filtronome"; ?>"></td>
            <td width="15px"></td>
            <td><b>&nbsp;Marca:</b><br><input size="15" type="text" name="filtromarca" class="campopadrao" value="<?php echo "$filtromarca"; ?>"></td>
            <td width="15px"></td>
            <td><b>&nbsp;Tamanho:</b><br><input size="12" type="text" name="filtrotamanho" class="campopadrao" value="<?php echo "$filtrotamanho"; ?>"></td>
            <td width="15px"></td>
            <td><b>&nbsp;Cor:</b><br><input size="15" type="text" name="filtrocor" class="campopadrao" value="<?php echo "$filtrocor"; ?>"></td>
            <td width="15px"></td>
            <td><b>&nbsp;Referência:</b><br><input size="20" type="text" name="filtroreferencia" class="campopadrao" value="<?php echo "$filtroreferencia"; ?>"></td>
            <td width="15px"></td>
        </tr>
    </table><br>
    <table summary="" class="tabelafiltro" border="0">
        <tr>
            <td><b>&nbsp;Descrição:</b><br><input size="30" type="text" name="filtrodescricao" class="campopadrao" value="<?php echo "$filtrodescricao"; ?>"></td>
            <td width="15px"></td>
            
            <td><b>&nbsp;Categoria:</b><br>
                <select name="filtrocategoria" class="campopadrao" >
                    <option value="" >Todos</option> 
                    <?php
                    $sql_categoria = "SELECT DISTINCT cat_codigo,cat_nome FROM produtos_categorias  join produtos on (pro_categoria=cat_codigo) WHERE cat_cooperativa=$usuario_cooperativa ORDER BY cat_nome";
                    $query_categoria = mysql_query($sql_categoria);
                    while ($dados_categoria = mysql_fetch_array($query_categoria)) {
                        ?>
                        <option value="<?php echo "$dados_categoria[0]"; ?>" <?php
                    if ($filtrocategoria == $dados_categoria[0]) {
                        echo" selected ";
                    }
                    ?>><?php echo "$dados_categoria[1]"; ?></option><?php } ?>
                </select>
            </td>
            <td width="15px"></td>
            <td><b>&nbsp;Próprio:</b><br>
                <select name="filtroproprio" class="campopadrao" >
                    <option value="1" <?php if (($filtroproprio=="")||($filtroproprio==1)) echo " selected "?>>Sim</option>
                    <option value="0" <?php if ($filtroproprio==0) echo " selected "?>>Todos</option>
                </select>
            </td>
            <td width="15px"></td>
            <td><b>&nbsp;Código Barras (EAN):</b><br><input size="20" type="text" name="filtroean" class="campopadrao" value="<?php echo "$filtroean";
             ?>"></td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td>
                <input type="submit" class="botao fonte3" value="PESQUISAR">
            </td>
            <td>
                <a href="produtos.php" class="link">
                    <input type="button" value="REINICIAR PESQUISA" class="botao fonte3">
                </a>
            </td>
            <td width="100%" align="right">
                <?php if ($permissao_produtos_cadastrar == 1) { ?>
                    <a href="produtos_cadastrar.php?operacao=1" class="link"><input type="button" value="CADASTRAR PRODUTO" class="botaopadrao botaopadraocadastrar" autofocus="1"></a>
<?php } else { ?>
    <!--                <input type="button" value="CADASTRAR" class="botaopadrao botaopadraocadastrar" disabled>-->
<?php } ?>
            </td>                
        </tr>
    </table>    
    <br>

    <?php
    
    //Verifica qual é a ordenação padrão das configuracões do quiosque
    $sql2 = "SELECT * FROM quiosques_configuracoes WHERE quicnf_quiosque=$usuario_quiosque";
    if (!$query2= mysql_query($sql2)) die("Erro: " . mysql_error());
    $dados2=  mysql_fetch_assoc($query2);
    $classificacaopadraoestoque=$dados2["quicnf_classificacaopadraoestoque"];
    if ($classificacaopadraoestoque==1) { //Por Nome do produto
        $sql_ordenacao = "pro_nome, pro_referencia,pro_tamanho,pro_cor,pro_descricao";
    } else if ($classificacaopadraoestoque==2) { //Por Referencia do produto
        $sql_ordenacao = "pro_referencia,pro_nome,pro_tamanho,pro_cor,pro_descricao";
    } else {
        $sql_ordenacao = "pro_nome"; 
    }    
    if ($filtrocodigo != "")
        $sql_filtro = $sql_filtro . " and pro_codigo=$filtrocodigo";
    if ($filtronome != "")
        $sql_filtro = $sql_filtro . " and ((pro_nome like '%$filtronome%') or (pro_referencia like '%$filtronome%') or (pro_tamanho like '%$filtronome%') or (pro_cor like '%$filtronome%') or (pro_descricao like '%$filtronome%'))";
    if ($filtrocategoria != "")
        $sql_filtro = $sql_filtro . " and pro_categoria = $filtrocategoria";
    if ($filtromarca != "")
        $sql_filtro = $sql_filtro . " and pro_marca like '%$filtromarca%'";
    if ($filtrotamanho != "")
        $sql_filtro = $sql_filtro . " and pro_tamanho like '%$filtrotamanho%'";
    if ($filtrocor != "")
        $sql_filtro = $sql_filtro . " and pro_cor like '%$filtrocor%'";
    if ($filtroreferencia != "")
        $sql_filtro = $sql_filtro . " and pro_referencia like '%$filtroreferencia%'";
    if ($filtrodescricao != "")
        $sql_filtro = $sql_filtro . " and pro_descricao like '%$filtrodescricao%'";
    if ($filtroean != "")
        $sql_filtro = $sql_filtro . " and pro_codigounico  = '$filtroean'";
    if ($filtroproprio==1) $sql_filtro.=" and pro_quiosquequecadastrou=$usuario_quiosque ";

    $sql = "
SELECT *
FROM produtos
left join produtos_recipientes on (pro_recipiente=prorec_codigo)
WHERE pro_cooperativa=$usuario_cooperativa
$sql_filtro
ORDER BY $sql_ordenacao
";

//Paginação
    $query = mysql_query($sql);
    if (!$query)
        die("Erro SQL Principal Paginação:" . mysql_error());
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
            <td width="30px">COD.</td>
            <!-- <td width="" colspan="2">DATA</td> -->
            <td width="30px">REF</td>
            <td width="100px">NOME</td>
            <td colspan="" align="center">MARCA</td>
            <td colspan="" align="center">REC.</td>
            <td colspan="" align="center">VOL.</td>
            <?php if (($usavendaporcoes==1)&&($usavendas==1)) { ?>
            <td width="10px" align="center" colspan="2">PORÇÕES</td>	
            <?php } ?>
            <?php if (($usamoduloproducao==1)) { ?>            
            <td width="10px" align="center" colspan="2">SUB-PRODUTOS</td>   
            <?php } ?>

            <?php
            $oper = 1;
            $oper_tamanho = 0;
            if ($permissao_produtos_editar == 1) {
                $oper = $oper + 1;
                $oper_tamanho = $oper_tamanho + 50;
            }
            if ($permissao_produtos_excluir == 1) {
                $oper = $oper + 1;
                $oper_tamanho = $oper_tamanho + 50;
            }
            if ($permissao_produtos_ver == 1) {
                $oper = $oper + 1;
                $oper_tamanho = $oper_tamanho + 50;
            }
            ?>                
            <td width="<?php echo $oper_tamanho . "px"; ?>" colspan="<?php echo $oper; ?>">OPERAÇÕES </td>
        </tr>

        <?php
        while ($array = mysql_fetch_array($query)) {
            $codigo = $array['pro_codigo'];
            $nome = $array['pro_nome'];
            $tipo = $array['pro_tipocontagem'];
            $categoria = $array['pro_categoria'];
            $recipiente = $array['prorec_nome'];
            $volume = $array['pro_volume'];
            $marca = $array['pro_marca'];
            $referencia = $array['pro_referencia'];
            $tamanho = $array['pro_tamanho'];
            $cor = $array['pro_cor'];
            $descricao = $array['pro_descricao'];
            $nome2="$nome $tamanho $cor $descricao";
            $data = converte_data($array['pro_datacriacao']);
            $hora = converte_hora($array['pro_horacriacao']);
            $usuarioquecadastrou=$array['pro_usuarioquecadastrou'];
            $quiosquequecadastrou=$array['pro_quiosquequecadastrou'];
            
            $sql9="SELECT count(propor_codigo) as qtdporcoes FROM produtos_porcoes WHERE propor_produto=$codigo";
            if (!$query9=  mysql_query($sql9)) die ("Erro SQL 2:".  mysql_error());
            $dados9=  mysql_fetch_assoc($query9);
            $qtdporcoes=$dados9["qtdporcoes"];
            if ($qtdporcoes=="") $qtdporcoes=0;
            
            ?>

            <tr class="lin">
                <td align="right"><?php echo "$codigo"; ?></td>
                <!-- 
                <td align="right"><?php echo "$data"; ?></td>
                <td align="left"><?php echo "$hora"; ?></td>
                -->

                <td align="right"><?php echo "$referencia"; ?></td>
                <td><?php echo "$nome2"; ?></td>
                <td><?php echo "$marca"; ?></td>
                <td><?php echo "$recipiente"; ?></td>
                <td><?php echo "$volume"; ?></td>
               
                <?php if (($usavendaporcoes==1)&&($usavendas==1)) { ?>
                <td width="" align="right"><b><?php echo "($qtdporcoes)"; ?> </b></td>            
                <td width="" align="left">
                    <a href="produtos_porcoes.php?produto=<?php echo "$codigo"; ?>">
                    <img width="18px" src="../imagens/icones/geral/procurar.png" title="Produrar" alt="Procurar">
                    </a>
                </td>
                <?php } ?>
                <?php if ($usamoduloproducao==1) { 
                    $sql2 = "SELECT count(*) as qtd FROM produtos_subproduto WHERE prosub_produto=$codigo";
                    $query2 = mysql_query($sql2);
                    $array2 = mysql_fetch_array($query2);
                    $qtdsubproduto=$array2["qtd"];
                ?>
                <td width="" align="right"><b><?php echo "($qtdsubproduto)"; ?> </b></td>            
                <td width="" align="left">
                    <a href="produtos_subprodutos.php?produto=<?php echo "$codigo"; ?>">
                    <img width="18px" src="../imagens/icones/geral/procurar.png" title="Produrar" alt="Procurar">
                    </a>
                </td>
                <?php } ?>            
                
              


                <td align="center" class="fundo1" width="35px">
                    <img   width="15px" src="
                    <?php 
                    if (($quiosquequecadastrou==$usuario_quiosque)) { 
                        echo "$icones\atencao2.png ";
                        $motivo="";
                    } else  if ($quiosquequecadastrou==0) { 
                        $motivo="Produto registrado pelos gestores da cooperativa!";
                    } else {
                        echo "$icones\atencao.png"; 
                        $sql3="SELECT qui_nome FROM quiosques WHERE qui_codigo=$quiosquequecadastrou";
                        if (!$query3=mysql_query($sql3)) die("Erro SQL3:" . mysql_error());
                        $dados3=  mysql_fetch_assoc($query3);
                        $quiosquequecadastrou_nome=$dados3["qui_nome"];
                        $motivo="Produto registrado pelo quiosque: $quiosquequecadastrou_nome";

                    }?>" 
                    title="<?php echo $motivo; ?>" alt="Atenção"/> </a>
                </td>
            <?php if ($permissao_produtos_ver == 1) { ?>
                <td align="center" class="fundo1" width="35px">
                    <a href="produtos_cadastrar.php?modal=1&codigo=<?php echo"$codigo"; ?>&ver=1" class="link1" target="_blank">
                        <img   width="15px" src="<?php echo $icones; ?>detalhes.png" title="Detalhes" alt="Detalhes"/> 
                    </a>
                </td>
<?php } ?>
            <?php if ($permissao_produtos_editar == 1) { ?>                
                <td align="center" class="fundo1" width="35px">
                    <a href="produtos_cadastrar.php?codigo=<?php echo"$codigo"; ?>" class="link1"><img   width="15px" src="<?php echo $icones; ?>editar.png" title="Editar"  alt="Editar" /></a> 
                </td>
<?php } ?>  
            <?php if ($permissao_produtos_excluir == 1) { ?>                
                <td align="center" class="fundo1" width="35px"> 
                    <a href="produtos_deletar.php?codigo=<?php echo"$codigo"; ?>" class="link1"><img  width="15px"  src="<?php echo $icones; ?>excluir.png"  title="Excluir" alt="Excluir" /></a> 
                </td>
                <?php } ?> 
                <?php
            }
            if ($linhas == "0") {
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
</form>    



