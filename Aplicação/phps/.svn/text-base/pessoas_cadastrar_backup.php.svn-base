
<?php include "includes.php"; ?>

<?php
$codigo = $_GET['codigo'];
$ver = $_GET['ver'];
$sql = "SELECT * FROM pessoas WHERE pes_codigo='$codigo'";
$query = mysql_query($sql);
while ($array = mysql_fetch_array($query)) {
    $nome = $array['pes_nome'];
    $cidade = $array['pes_cidade'];
    $cep = $array['pes_cep'];
    $bairro = $array['pes_bairro'];
    $vila = $array['pes_vila'];
    $endereco = $array['pes_endereco'];
    $numero = $array['pes_numero'];
    $telefone1 = $array['pes_fone1'];
    $telefone2 = $array['pes_fone2'];
    $email = $array['pes_email'];
    $obs = $array['pes_obs'];
    $chat = $array['pes_chat'];
}
?>

<table summary="" class="tabela1" border="0">
    <tr>
        <td width="35px"><img width="50px" src="<?php echo $icones; ?>pessoas.png" alt="" ></td>
        <td valign="bottom">
            <label class="titulo" > PESSOAS </label><br />
            <label class="subtitulo"> CADASTRO/EDIÇÃO </label>
        </td>
    </tr>
</table>
<hr align="left" class="linhacurta" >
<br />

<form action="pessoas_cadastrar2.php?codigo=<?php echo"$codigo"; ?>" method="post" name="form1">
    <table summary="" border="0" class="tabela1" cellpadding="4">
        <tr>
            <td align="right" width="200px"><b>Nome: <label class="obrigatorio">*</label></b></td>
            <td align="left" width=""><input type="text" name="nome" autofocus size="30" class="campo1" required value="<?php echo "$nome"; ?>" <?php if ($ver == 1)
    echo" disabled "; ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Cidade: <label class="obrigatorio"></label></b></td>
            <td align="left" width="">
                <select name="cidade" class="campo1" <?php if ($ver == 1)
    echo" disabled "; ?> >
                    <option value=""></option>		
                    <?php
                    $sql1 = "SELECT cid_codigo, cid_nome FROM cidades ORDER BY cid_nome";
                    $query1 = mysql_query($sql1);
                    while ($array1 = mysql_fetch_array($query1)) {
                        ?><option value="<?php echo"$array1[0]"; ?>" <?php if ($array1[0] == $cidade) {
                        echo "selected ";
                    } ?> ><?php echo"$array1[1]"; ?></option><?php
                }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Vila: </b></td>
            <td align="left" width=""><input type="text" name="vila" size="40" class="campo1" placeholder="Se houver"  value="<?php echo "$vila"; ?>" <?php if ($ver == 1)
                    echo" disabled "; ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Bairro: </b></td>
            <td align="left" width=""><input type="text" name="bairro" size="45" class="campo1" value="<?php echo "$bairro"; ?>" <?php if ($ver == 1)
                    echo" disabled "; ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Endereço: </b></td>
            <td align="left" width=""><input type="text" name="endereco" id="endereco" size="35" class="campo1" value="<?php echo "$endereco"; ?>"  <?php if ($ver == 1) {
                    echo" disabled ";
                } else { ?> placeholder="Nome da Rua" <?php } ?> >
                <input type="number" name="numero" size="8" class="campo1" value="<?php echo "$numero"; ?>"  <?php if ($ver == 1) {
                    echo" disabled ";
                } else { ?> placeholder="Nº" <?php } ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>CEP: </b></td>
            <td align="left" width=""><input type="text" name="cep" id="cep" size="15" class="campo1" value="<?php echo "$cep"; ?>"  <?php if ($ver == 1)
                    echo" disabled "; ?> > </td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Telefone 01: </b></td>
            <td align="left" width=""><input type="text" name="telefone1" id="telefone1"  size="15" class="campo1"  value="<?php echo "$telefone1"; ?>" <?php if ($ver == 1)
                    echo" disabled "; ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Telefone 02: </b></td>
            <td align="left" width=""><input type="text" name="telefone2" id="telefone2"  size="15" class="campo1"  value="<?php echo "$telefone2"; ?>" <?php if ($ver == 1)
                    echo" disabled "; ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>E-mail: </b></td>
            <td align="left" width=""><input type="email" name="email" id="email" size="45" class="campo1" value="<?php echo "$email"; ?>" <?php if ($ver == 1)
                    echo" disabled "; ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Tipo:  <label class="obrigatorio">*</label></b></td>
            <td align="left" width="">
                <?php
                $sql2 = "SELECT pestip_codigo, pestip_nome FROM pessoas_tipo";
                $query2 = mysql_query($sql2);
                while ($array2 = mysql_fetch_array($query2)) {
                    $codigo2 = $array2['pestip_codigo'];
                    $nome2 = $array2['pestip_nome'];
                    
                    //Aqui é verificado se o checkbox deve ser marcado ou não, ele verifica no banco se tem registro deste tipo com essa pessoa 
                    $sqlcheck = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_pessoa='$codigo' and mespestip_tipo='$codigo2'";
                    $query = mysql_query($sqlcheck);
                    if ($query) {                        
                    } else {
                        echo mysql_error();
                    }
                    $array = mysql_fetch_array($query);
                    if ($array) {
                        $check = " checked ";
                    } else {
                        $check = " ";
                    }
                    
                    //Se for cadastro mantem por padrão o tipo 'clinte' marcado
                    if (($codigo=="")&&($codigo2==5)) {
                        $check=" checked ";
                    }
                    
                    ?>
                    <input type="checkbox" <?php if ($ver == 1)
                        echo " disabled "; ?> value="<?php echo "$codigo2"; ?>" name="box[]" <?php echo "$check"; ?> ><label><?php echo "$nome2"; ?></label> <br /><?php
                }
                ?>

            </td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Chat: </b></td>
            <td align="left" width=""><input type="chat" name="chat" id="chat" size="45" class="campo1" value="<?php echo "$chat"; ?>" <?php if ($ver == 1)
        echo" disabled "; ?> ></td>
        </tr>
        <tr>
            <td align="right" width="200px"><b>Observação: </b></td>
            <td align="left" width=""><textarea cols="55" name="obs" id="obs" <?php if ($ver == 1)
        echo" disabled "; ?> class="textarea1" ><?php echo "$obs"; ?></textarea></td>
        </tr>
    </table>

    <br />
    <hr align="left" >
<?php
//Verifica links para atribuir ao botão voltar
$linkanterior = $_GET["link"];
$produto = $_GET["produto"];

if ($linkanterior == "") {
    $link_destino = "pessoas.php";
}
if ($linkanterior == "estoque_fornecedores.php") {
    $link_destino = $linkanterior . "?produto=$produto";
}
if ($linkanterior == "estoque_validade.php") {
    $link_destino = $linkanterior;
}
if ($linkanterior == "estoque_porfornecedor.php") {
    $link_destino = $linkanterior;
}

if ($ver == 1) {
    ?><a href="<?php echo $link_destino; ?>" class="link">&nbsp;
        <input type="button" value="VOLTAR" class="botao fonte3"></a> <?php } else { ?>
    <input type="hidden" name="nome2" value="<?php echo $nome ?>">
    <input type="submit" value="SALVAR" name="submit1" class="botao fonte3"> 
    <a href="pessoas.php" class="link">&nbsp;
        <input type="button" value="CANCELAR" class="botao fonte3"></a> <?php } ?>
</form>

<?php include "rodape.php"; ?>
