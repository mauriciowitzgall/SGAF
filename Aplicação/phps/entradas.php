<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_entradas_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "entradas";
include "includes.php";



//include "entradas_excluir_vazias.php";
?>
<script type="text/javascript" src="paginacao.js"></script>


<body onload="valida_filtro_entradas_numero()">
    <table summary="" class="tabela1" border="0" >
        <tr>
            <td width="35px"><img width="50px" src="<?php echo $icones; ?>entradas.png" alt="" ></td>
            <td valign="bottom">
                <label class="titulo" > ENTRADAS</label><br />
                <label class="subtitulo"> PESQUISA/LISTAGEM </label>
            </td>
        </tr>
    </table>
    <hr align="left" class="linhacurta" >

    <?php
    //filtro e ordenação
    $filtronumero = $_POST['filtronumero'];
    $filtrofornecedor = $_POST['filtrofornecedor'];
    $filtrosupervisor = $_POST['filtrosupervisor'];
    $filtrotipo = $_POST['filtrotipo'];
    $filtroproduto = $_POST['filtroproduto'];
    ?>
    <form action="entradas.php" method="post" name="formfiltro">
        <table summary="" class="tabelafiltro" border="0">
            <tr>
                <td><b>&nbsp;Nº:</b><br><input size="10" type="text" onkeyup="valida_filtro_entradas_numero()" name="filtronumero" class="campofiltro" value="<?php echo "$filtronumero"; ?>"></td>
                <td width="15px"></td>

                <?php if ($usuario_grupo != 5) { ?>                    
                    <td><b>&nbsp;Fornecedor:</b><br>
                        <select name="filtrofornecedor" class="campofiltro">
                            <option value="">Todos</option> 
                            <?php
                            $sql2 = "SELECT DISTINCT pes_codigo,pes_nome FROM mestre_pessoas_tipo join pessoas on (pes_codigo=mespestip_pessoa) join entradas on (ent_fornecedor=pes_codigo)
			WHERE mespestip_tipo=5 and ent_quiosque='$usuario_quiosque' ORDER BY pes_nome";
                            $query2 = mysql_query($sql2);
                            while ($dados2 = mysql_fetch_array($query2)) {
                                ?> <option value="<?php echo "$dados2[0]"; ?>" <?php
                        if ($filtrofornecedor == $dados2[0]) {
                            echo" selected ";
                        }
                                ?>><?php echo "$dados2[1]"; ?></option><?php } ?>
                        </select>
                    </td>
                    <td width="15px"></td>
                    <td ><b>&nbsp;Supervisor:</b><br>
                        <select name="filtrosupervisor" class="campofiltro" >
                            <option value="">Todos</option> 
                            <?php
                            $sql3 = "SELECT DISTINCT pes_codigo,pes_nome
			FROM pessoas 			
                        join entradas on (ent_supervisor=pes_codigo)
			WHERE ent_quiosque='$usuario_quiosque' ORDER BY pes_nome";
                            $query3 = mysql_query($sql3);
                            while ($dados3 = mysql_fetch_array($query3)) {
                                ?> <option value="<?php echo "$dados3[0]"; ?>" <?php
                        if ($filtrosupervisor == $dados3[0]) {
                            echo" selected ";
                        }
                                ?>><?php echo "$dados3[1]"; ?></option><?php } ?>
                        </select>
                    </td>
                    <td width="15px"></td>
                    <td ><b>&nbsp;Produtos:</b><br>
                        <select name="filtroproduto" class="campofiltro" >
                            <option value="">Todos</option> 
                            <?php
                            $sql3 = "SELECT DISTINCT pro_codigo,pro_nome
			FROM produtos
                        join entradas_produtos on (entpro_produto=pro_codigo)
			WHERE pro_cooperativa='$usuario_cooperativa' ORDER BY pro_nome";
                            $query3 = mysql_query($sql3);
                            while ($dados3 = mysql_fetch_array($query3)) {
                                ?> <option value="<?php echo "$dados3[0]"; ?>" <?php
                        if ($filtroproduto == $dados3[0]) {
                            echo" selected ";
                        }
                                ?>><?php echo "$dados3[1]"; ?></option><?php } ?>
                        </select>
                    </td>
                    <td width="15px"></td>                
                <?php } ?>
            </tr>
        </table><br>
        <table>
            <tr>
                <td>
                    <input type="submit" class="botao fonte3" value="PESQUISAR">
                </td>
                <td>
                    <a href="entradas.php" class="link">
                        <input type="button" value="REINICIAR PESQUISA" class="botao fonte3">
                    </a>
                </td>
                <td width="100%" align="right">
                    <?php if (($permissao_entradas_cadastrar == 1)&&($usuario_quiosque!=0)) { ?>
                        <a href="entradas_cadastrar.php?operacao=1" class="link"><input type="button" value="REGISTRAR ENTRADA" class="botaopadrao botaopadraocadastrar fonte3" autofocus="1"></a>
                    <?php } ?>
                        
                </td>                
            </tr>
        </table>




        <?php
        $sql_filtro = "";

        if ($filtronumero != "")
            $sql_filtro = $sql_filtro . " and ent_codigo=$filtronumero";
        if ($filtrosupervisor != "")
            $sql_filtro = $sql_filtro . " and ent_supervisor=$filtrosupervisor";
        if ($filtroproduto != "") {
            $sql_filtro = $sql_filtro . " and entpro_produto=$filtroproduto";
            $sql_filtro_from = "join entradas_produtos on (entpro_entrada=ent_codigo)";
        }
        

        if ($usuario_grupo == 5) {
            $sql_filtro = $sql_filtro . " and ent_fornecedor=$usuario_codigo";
        } else {
            if ($filtrofornecedor != "")
                $sql_filtro = $sql_filtro . " and ent_fornecedor=$filtrofornecedor";
        }

$sql = "
    SELECT DISTINCT
            ent_codigo,ent_datacadastro,ent_horacadastro,ent_fornecedor,ent_supervisor,ent_tipo,ent_status,ent_valortotal
    FROM 
            entradas              
            $sql_filtro_from
    WHERE 
            ent_quiosque = '$usuario_quiosque'
            $sql_filtro
    ORDER BY 
            ent_status DESC, ent_codigo DESC 
    ";


        //Paginação
        $query = mysql_query($sql);
        if (!$query)
            die("Erro SQL Principal Paginação:" . mysql_error());
        $linhas = mysql_num_rows($query);
        $por_pagina = $usuario_paginacao;
        $paginaatual = $_POST["paginaatual"];
        $paginas = ceil($linhas / $por_pagina);
        if (($paginaatual == "") || ($paginas < $paginaatual) || ($paginaatual <= 0)) {
            $paginaatual = 1;
        }
        $comeco = ($paginaatual - 1) * $por_pagina;
        $sql = $sql . " LIMIT $comeco,$por_pagina ";






        $query = mysql_query($sql);
        if (!$query)
            die("Erro SQL Principal" . mysql_error());
        $linhas = mysql_num_rows($query);
        ?>
        <br>
        <table border="1" class="tabela1" cellpadding="4" width="100%">
            <tr valign="middle" class="tabelacabecalho1">
                <td width="35px">LOTE</td>
                <td width="" colspan="2">DATA</td>            
                <td width="">FORNECEDOR</td>
                <td width="">SUPERVISOR</td>
                <td width="">QTD. PROD.</td>            
                <td width="">ITENS</td>            
                <td width="">TOTAL</td>            
                <td width="">SIT.</td>            
                <?php 
                $oper=0;
                $oper_tamanho=0;
                if ($permissao_entradas_ver == 1) {
                    $oper=$oper+1;
                    $oper_tamanho=$oper_tamanho+50;
                }
                if ($permissao_entradas_editar == 1) {
                    $oper++;
                    $oper_tamanho=$oper_tamanho+50;   
                }
                              
                ?>                
                <td width="<?php echo $oper_tamanho."px"; ?>" colspan="<?php echo $oper; ?>">OPERAÇÕES </td>
            </tr>

            <?php
            while ($array = mysql_fetch_array($query)) {
                $codigo = $array['ent_codigo'];
                $data = $array['ent_datacadastro'];
                $hora = $array['ent_horacadastro'];
                $fornecedor = $array['ent_fornecedor'];
                $supervisor = $array['ent_supervisor'];
                $tipo = $array['ent_tipo'];
                $status = $array['ent_status'];
                $total = $array['ent_valortotal'];

                //Verifica se ja foi efetuado saídas quaisquer para o lote/entrada em questão
                $sql3 = "SELECT * FROM saidas_produtos WHERE saipro_lote=$codigo";
                $query3 = mysql_query($sql3);
                if (!$query3) {
                    die("Erro SQL: " . mysql_error());
                }
                $linhas3 = mysql_num_rows($query3);

                //Se já houve saídas referentes a esta entrada então não pode-se exclui-la
                if ($linhas3 > 0) {
                    $editar_ocultar = 1;
                    $editar_ocultar_motivo = "Foi vendido produtos desta entrada";
                } else {
                    $editar_ocultar = 0;
                    $editar_ocultar_motivo = "";
                }
                ?>

                <tr class="lin <?php
            if ($status == 2) {
                if ($usuario_codigo == $supervisor) {
                    echo "tabelalinhafundovermelho negrito";
                } else {
                    $dataatual = date("Y-m-d");
                    $horaatual = date("H:i:s");
                    $tempo1 = $data . "_" . $hora;
                    $tempo2 = $dataatual . "_" . $horaatual;
                    $total_segundos = diferenca_entre_datahora($tempo1, $tempo2);
                    if ($total_segundos > 5400) {
                        echo "tabelalinhafundovermelho negrito";
                    } else {
                        echo "tabelalinhafundoamarelo negrito";
                    }
                }
            } else {
                echo "";
            }
                ?>">

                    <td align="right" ><?php echo "$codigo"; ?></td>
                    <td width="80px" align="right"><?php echo converte_data($data); ?></td>
                    <td width="35px"><?php echo converte_hora($hora); ?></td>
                    <td>
                        <a href="pessoas_cadastrar.php?codigo=<?php echo $fornecedor; ?>&operacao=ver" class="link">
                            <?php
                            $sql2 = "SELECT pes_codigo,pes_nome FROM pessoas WHERE pes_codigo=$fornecedor";
                            $query2 = mysql_query($sql2);
                            while ($array2 = mysql_fetch_array($query2)) {
                                echo "$array2[1]";
                            }
                            ?>
                        </a>
                    </td>             

                    <!-- COLUNA SUPERVISOR -->
                    <td>
                        <a href="pessoas_cadastrar.php?codigo=<?php echo $supervisor; ?>&operacao=ver" class="link">
                            <?php
                            $sql2 = "SELECT pes_codigo,pes_nome FROM pessoas WHERE pes_codigo=$supervisor";
                            $query2 = mysql_query($sql2);
                            while ($array2 = mysql_fetch_array($query2)) {
                                echo "$array2[1]";
                            }
                            ?>
                        </a>
                    </td>

                    <!-- COLUNA QTD PROD -->
                    <td align="center"><?php
                        $sql8 = "SELECT DISTINCT entpro_produto FROM entradas_produtos WHERE entpro_entrada=$codigo";
                        $query8 = mysql_query($sql8);
                        echo $linhas8 = mysql_num_rows($query8);
                            ?>    
                    </td>               

                    <!-- COLUNA ITENS -->
                    <td align="center"><?php
                    $sql8 = "SELECT entpro_entrada FROM entradas_produtos WHERE entpro_entrada=$codigo";
                    $query8 = mysql_query($sql8);
                    echo $linhas8 = mysql_num_rows($query8);
                            ?>    
                    </td>               


                    <!-- COLUNA TOTAL -->
                    <td align="right"><b><?php
                    $total = number_format($total, 2, ',', '.');
                    echo "R$ $total";
                            ?></b>
                    </td>

                    <!-- COLUNA SITUACAO -->    
                    <td align="center">                     
                        <?php
                        if ($status == 2) {
                            if ($usuario_codigo == $supervisor) {
                                $imagem = "bandeira3_vermelha.png";
                                $titulo = "Incompleta";
                            } else {
                                $dataatual = date("Y-m-d");
                                $horaatual = date("H:i:s");
                                $tempo1 = $data . "_" . $hora;
                                $tempo2 = $dataatual . "_" . $horaatual;
                                $total_segundos = diferenca_entre_datahora($tempo1, $tempo2);
                                if ($total_segundos > 5400) {
                                    $imagem = "bandeira3_vermelha.png";
                                    $titulo = "Incompleta";                                    
                                } else {
                                    $imagem = "bandeira3_laranja.png";
                                    $titulo = "Esta entrada está em andamento por outro usuário! Este usuário tem 01:30 (uma hora e meia) para finalizá-la caso contrário ela passará a ser 'Incompleta' e você poderá finalizá-la!";
                                    $editar_ocultar = 1;
                                    $editar_ocultar_motivo = "Esta entrada está em andamento por outro usuário! Este usuário tem 01:30 (uma hora e meia) para finalizá-la caso contrário ela passará a ser 'Incompleta' e você poderá finalizá-la!";
                                }
                            }
                        } else {
                            $imagem = "bandeira3_verde.png";
                            $titulo = "Completo";
                        }
                        ?>    
                        <img width="15px" src="<?php echo $icones . $imagem; ?>" title="<?php echo $titulo; ?>" alt="<?php echo $titulo; ?>"/>
                    </td>

                <!-- COLUNA VER -->                
                <?php if ($permissao_entradas_ver == 1) { ?>
                    <td align="center" class="fundo1" width="35px">             
                        <a href="entradas_ver.php?codigo=<?php echo"$codigo"; ?>" class="link1">
                            <img   width="15px" src="<?php echo $icones; ?>detalhes.png" title="Detalhes" alt="Detalhes"/> 
                        </a>
                    </td>
                <?php  } ?>                

                <!-- COLUNA EDITAR -->                
                <?php if ($permissao_entradas_editar == 1) {
                    if ($editar_ocultar != 1) { ?>
                        <td align="center" class="fundo1" width="35px">             
                            <a href="entradas_cadastrar.php?codigo=<?php echo"$codigo"; ?>&operacao=2" class="link1">
                                <img   width="15px" src="<?php echo $icones; ?>editar.png" title="Editar"  alt="Editar" />
                            </a>
                        </td>
                <?php } else { ?>
                        <td align="center" class="fundo1" width="35px">             
                             <img  width="15px"  src="<?php echo $icones; ?>editar_desabilitado.png"  title="<?php echo $editar_ocultar_motivo; ?>" alt="Editar" />
                        </td>                    
                   <?php }
                } ?>                

                </tr>

            <?php } ?>
            <?php if ($linhas == "0") { ?>
                <tr>
                    <td colspan="10" align="center" class="errado">
                        <?php echo "Nenhum resultado!" ?>
                    </td>
                </tr>
            <?php } ?>
            </tr>
        </table>
        <table class="paginacao_fundo" width="100%" border="0">
            <tr valign="middle" align="center">
                <td align="right">
                    <input onclick="paginacao_retroceder()" type="image" width="25px"   src="<?php echo $icones; ?>esquerda.png"  title="Anterior" alt="Anterior" />
                </td>
                <td width="170px">
                    <input size="5" type="text" name="paginaatual" class="campofiltro" value="<?php echo "$paginaatual"; ?>">
                    <span>/</span>
                    <input disabled size="5" type="text" name="paginas" class="campofiltro" value="<?php echo "$paginas"; ?>">
                </td>
                <td align="left">
                    <input onclick="paginacao_avancar()"  type="image" width="25px"   src="<?php echo $icones; ?>direita.png"  title="Próxima" alt="Próxima" />
                </td>
            </tr>
        </table>
    </form>
</body>
