
<?php 
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "produtos"; ?>
<?php include "includes.php"; ?>
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
//filtro e ordenação
$filtrocodigo = $_POST['filtrocodigo'];
$filtronome = $_POST['filtronome'];
$filtrocategoria = $_POST['filtrocategoria'];
?>
<form action="produtos.php" name="form1" method="post">
    <table summary="" class="tabelafiltro" border="0">
        <tr>
            <td><b>&nbsp;Código:</b><br>
                <input size="25" type="number" name="filtrocodigo" class="campofiltro" value="<?php echo "$filtrocodigo"; ?>"></td>
            <td width="15px"></td>
            <td><b>&nbsp;Nome:</b><br><input size="25" type="text" name="filtronome" class="campofiltro" value="<?php echo "$filtronome"; ?>"></td>
            <td width="15px"></td>
            <td><b>&nbsp;Categoria:</b><br>
                <select name="filtrocategoria" class="campofiltro" >
                    <option value="" >Todos</option> 
                    <?php
                    $sql_categoria = "SELECT DISTINCT cat_codigo,cat_nome FROM produtos_categorias  join produtos on (pro_categoria=cat_codigo) WHERE cat_cooperativa=$usuario_cooperativa ORDER BY cat_nome";
                    $query_categoria = mysql_query($sql_categoria);
                    while ($dados_categoria = mysql_fetch_array($query_categoria)) {
                        ?>
                        <option value="<?php echo "$dados_categoria[0]"; ?>" <?php if ($filtrocategoria == $dados_categoria[0]) {
                            echo" selected ";
                        } ?>><?php echo "$dados_categoria[1]"; ?></option><?php } ?>
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
                <a href="produtos.php" class="link">
                    <input type="button" value="REINICIAR PESQUISA" class="botao fonte3">
                </a>
            </td>
            <td width="100%" align="right">
                <?php if ($permissao_produtos_cadastrar==1) {  ?>
                <a href="produtos_cadastrar.php?operacao=1" class="link"><input type="button" value="CADASTRAR PRODUTO" class="botaopadrao botaopadraocadastrar" autofocus="1"></a>
                <?php } else { ?>
<!--                <input type="button" value="CADASTRAR" class="botaopadrao botaopadraocadastrar" disabled>-->
                <?php } ?>
            </td>                
        </tr>
    </table>    
    <br>

<?php
if ($filtrocodigo!="")
    $sql_filtro=$sql_filtro." and pro_codigo=$filtrocodigo";
if ($filtronome!="")
    $sql_filtro=$sql_filtro." and pro_nome like '%$filtronome%'";
if ($filtrocategoria!="")
    $sql_filtro=$sql_filtro." and pro_categoria = $filtrocategoria";


$sql = "
SELECT
	*
FROM
	produtos
WHERE	
	pro_cooperativa=$usuario_cooperativa
        $sql_filtro
ORDER BY
	pro_nome
";

//Paginação
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se é a primeira vez que acessa a pagina então começar na pagina 1
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
        <td width="35px">CODIGO</td>
        <td width="" colspan="2">DATA</td>
        <td width="">NOME</td>
        <td colspan="2" align="center">TIPO</td>
        <td width="" align="center">CATEGORIA</td>	
        <?php
        $oper=0;
        $oper_tamanho=0;
        if ($permissao_produtos_editar == 1) {
            $oper=$oper+1;            
            $oper_tamanho=$oper_tamanho+50;
        }  
        if ($permissao_produtos_excluir == 1) {
            $oper=$oper+1;            
            $oper_tamanho=$oper_tamanho+50;
        }
        if ($permissao_produtos_ver == 1)  {
            $oper=$oper+1;           
            $oper_tamanho=$oper_tamanho+50;
        }
?>                
        <td width="<?php echo $oper_tamanho."px"; ?>" colspan="<?php echo $oper; ?>">OPERAÇÕES </td>
    </tr>

    <?php
    while ($array = mysql_fetch_array($query)) {
        $codigo =  number_format($array['pro_codigo'], 0, ',', '.');
        $nome = $array['pro_nome'];
        $tipo = $array['pro_tipocontagem'];
        $categoria = $array['pro_categoria'];
        $data = converte_data($array['pro_datacriacao']);
        $hora = converte_hora($array['pro_horacriacao']);
        ?>
    
        <tr class="lin">
            <td align="right"><?php echo "$codigo"; ?></td>
            <td align="right"><?php echo "$data"; ?></td>
            <td align="left"><?php echo "$hora"; ?></td>
            
            
            <td><?php echo "$nome"; ?></td>
            <td align="right" width="150px"><?php
            $sql2 = "SELECT * FROM produtos_tipo WHERE protip_codigo=$tipo";
            $query2 = mysql_query($sql2);
            while ($array2 = mysql_fetch_array($query2)) {
                $nometipo = $array2['protip_nome'];
                $siglatipo = $array2['protip_sigla'];
                echo "$nometipo";
            }
            ?>
            </td>
            <td width="35px"><?php echo " $siglatipo"; ?>
            </td>            
            <td><?php
            $sql3 = "SELECT * FROM produtos_categorias WHERE cat_codigo=$categoria";
            $query3 = mysql_query($sql3);
            while ($array3 = mysql_fetch_array($query3)) {
                $nomecat = $array3 ['cat_nome'];
                echo "$nomecat";
            }
            ?>
            </td>
            <?php if ($permissao_produtos_ver == 1) { ?>
            <td align="center" class="fundo1" width="35px">
                <a href="produtos_cadastrar.php?codigo=<?php echo"$codigo"; ?>&ver=1" class="link1"><img   width="15px" src="<?php echo $icones; ?>detalhes.png" title="Detalhes" alt="Detalhes"/> </a>
            </td>
            <?php   } ?>
            <?php if ($permissao_produtos_editar == 1) { ?>                
            <td align="center" class="fundo1" width="35px">
                <a href="produtos_cadastrar.php?codigo=<?php echo"$codigo"; ?>" class="link1"><img   width="15px" src="<?php echo $icones; ?>editar.png" title="Editar"  alt="Editar" /></a> 
            </td>
            <?php } ?>  
            <?php if ($permissao_produtos_excluir == 1) { ?>                
            <td align="center" class="fundo1" width="35px"> 
                <a href="produtos_deletar.php?codigo=<?php echo"$codigo"; ?>" class="link1"><img  width="15px"  src="<?php echo $icones; ?>excluir.png"  title="Excluir" alt="Excluir" /></a> 
            </td>
            <?php }  ?> 
    <?php
}
if ($linhas == "0") {
    ?> <tr><td colspan="10" align="center" class="errado"> <?php echo "Nenhum resultado!" ?> </td></tr> <?php
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
        <input size="5" type="text" name="paginaatual" class="campofiltro" value="<?php echo $paginaatual; ?>">
        <span>/</span>
        <input disabled size="5" type="text" name="paginas" class="campofiltro" value="<?php echo $paginas; ?>">
    </td>
    <td align="left">
        <input onclick="paginacao_avancar()"  type="image" width="25px"   src="<?php echo $icones; ?>direita.png"  title="Próxima" alt="Próxima" />
    </td>
</tr>
</table>
</form>    



