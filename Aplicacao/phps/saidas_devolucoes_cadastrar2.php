<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "devolucoes";
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "VENDAS DEVOLUÇÕES";
$tpl_titulo->SUBTITULO = "CADASTRAR UMA NOVA DEVOLUÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "devolucoes.png";
$tpl_titulo->show();

$datahoraatual=date("Y-m-d H:i:s");

//Pegar todos os dados digitados
//print_r($_REQUEST);
$saida=$_GET["saida"];
$valtot=$_REQUEST["campooculto_valtot"];
$valtot_comdesconto=$_REQUEST["campooculto_valtot_comdesconto"];
$valliq=$valtot_comdesconto;
    

//Verifica os valores totais da venda
$sql3 = "
    SELECT *
    FROM saidas
    WHERE sai_codigo=$saida
";
if (!$query3 = mysql_query($sql3)) die("Erro3" . mysql_error());
$sai_total = 0;
$dados3=mysql_fetch_assoc($query3); 
$total_venda_comdesconto= $dados3["sai_totalcomdesconto"];
$total_venda_bruto= $dados3["sai_totalbruto"];
$venda_descontovalor= $dados3["sai_descontovalor"];
$venda_descontopercentual= $dados3["sai_descontopercentual"];
$areceber= $dados3["sai_areceber"];



//Gravar o registro da nova devolução
//Inserindo o registro da devolução
$sql="
    INSERT INTO saidas_devolucoes (
        saidev_saida,
        saidev_usuario,
        saidev_valtot,
        saidev_valliq
    ) VALUES (
        $saida,
        $usuario_codigo,
        $valtot,
        $valliq
    )
";

if (!$query = mysql_query($sql)) die("Erro ao inserir devolucão nova:" . mysql_error());

$devolucao=mysql_insert_id();
//Inserir o registro dos produtos da devolução. 
//Para isso basta varrer a saida tooda, e quando encontrar um item que deve ser devolvido realizar a inseção deste. 
$sql2="SELECT * FROM saidas_produtos WHERE saipro_saida=$saida";
//echo "<br><br>gerado NOVA DEVOLUCAO <br><br>";
if (!$query2 = mysql_query($sql2)) die("Erro so CONSULTAR PRODUTOS DAS SAIDAS" . mysql_error());
while ($dados2=mysql_fetch_assoc($query2)) {
    $itemvenda=$dados2["saipro_codigo"];
    $nome="qtddigitada_".$itemvenda;
    $qtddigitada=$_POST["$nome"];
    $produto=$dados2["saipro_produto"];
    $lote=$dados2["saipro_lote"];
    $valuniitem=$dados2["saipro_valorunitario"];
    $valtotitem=$valuniitem*$qtddigitada;
    $valtotliq=$valtotitem*(100-$venda_descontopercentual)/100;

    if ($qtddigitada>0) {
        //Inserir este item na devolução
        $sql3="
            INSERT INTO saidas_devolucoes_produtos (
                saidevpro_numerodev,
                saidevpro_saida,
                saidevpro_itemsaida,
                saidevpro_produto,
                saidevpro_lote,
                saidevpro_qtddevolvida,
                saidevpro_valuni,
                saidevpro_valtot,
                saidevpro_valtotliq
            ) VALUES (
                $devolucao,
                $saida,
                $itemvenda,
                $produto,
                $lote,
                $qtddigitada,
                $valuniitem,
                $valtotitem,
                $valtotliq
            )
        ";
        if (!$query3 = mysql_query($sql3)) die("Erro ao itens da devolucao:" . mysql_error());
        //echo "<br><br>inserido <b>ITEM NOVO na DEVOLUCAO</b> <br><br>";


        //Inserir no estoque o item devolvidos
        //Verifica se o produto existe no estoque ou se foi eliminado por ter valor 0
        if ($usaestoque==1) {


            $sql9 = "
            SELECT
                *
            FROM
                estoque
            WHERE
                etq_quiosque=$usuario_quiosque and
                etq_produto=$produto and
                etq_lote=$lote
            ";
            $query9 = mysql_query($sql9);
            if (!$query9) {
                die("Erro de SQL 1:" . mysql_error());
            }
            $linhas9 = mysql_num_rows($query9);
            if ($linhas9 > 0) { //O produto existe no estoque
                //Atualiza a quantidade no estoque            
                $sql_repor = "
                UPDATE
                    estoque 
                SET 
                    etq_quantidade=(etq_quantidade+$qtddigitada)
                WHERE
                    etq_quiosque=$usuario_quiosque and
                    etq_produto=$produto and
                    etq_lote=$lote 
                ";
                
                if (!$query_repor = mysql_query($sql_repor)) die("Erro de SQL2:" . mysql_error());
                


            // echo "<br><br>ATUALIZADO ESTOQUE DE PRODUTO EXISTENTE NO ESTOQUE<br><br>";
            } else { //O produto não existe mais no estoque, vamos inserir
                //Pegar os demais dados necessários para inserir no estoque
                $sql = "SELECT * FROM `entradas_produtos` join entradas on (entpro_entrada=ent_codigo) WHERE entpro_entrada=$lote AND entpro_produto=$produto";
                $query = mysql_query($sql);
                if (!$query) {
                    die("Erro de SQL 9:" . mysql_error());
                }
                while ($dados = mysql_fetch_assoc($query)) {
                    $validade = $dados["entpro_validade"];
                    $valuni = $dados["entpro_valorunitario"];
                    $fornecedor = $dados["ent_fornecedor"];
                }
                //Interir o produto no estoque
                //echo "<br><br>inseriu no estoque<br><br>";
                $sql16 = "INSERT INTO estoque (etq_quiosque,etq_produto,etq_fornecedor,etq_lote,etq_quantidade,etq_valorunitario,etq_validade)
                VALUES ('$usuario_quiosque','$produto','$fornecedor','$lote','$qtddigitada','$valuni','$validade')";
                if (!$query16 = mysql_query($sql16)) die("Erro de SQL4 (inserir no estoque): " . mysql_error());
                
                //echo "<br><br>INSERIDO NO ESTOQUE COMO NOVO PRODUTO<br><br>";
            }
        }

    } 
}



        

//Gerar saída de caixa
//Avaliar se tem devoluções na venda ou  pagamentos a receber, se tiver então calcular o saldo a receber, mostrar o resumo na tela e então gerar a saída de caixa no valor correto. Se não tiver devoluções ou pagamentos então gerar uma saída de caixa no valor total da devolução.


//Verifica qual é o numero da operação de caixa do cliente logado
if ($usacaixa==1) {
    $sql="
        SELECT cai_codigo,cai_nome,caiopo_numero
        FROM pessoas 
        JOIN caixas_operacoes on (caiopo_numero=pes_caixaoperacaonumero) 
        JOIN caixas on (caiopo_caixa=cai_codigo)
        WHERE pes_codigo=$usuario_codigo
    ";
    $query = mysql_query($sql);
    if (!$query) die("Erro de SQL Cabeçalho:" . mysql_error());
    $dados=mysql_fetch_array($query);
    $usuario_caixa=$dados[0];
    $usuario_caixa_nome=$dados[1];
    $usuario_caixa_operacao=$dados[2];
    $caixaoperacao=$dados[2];
}



//Verifica se tem pagamentos, e calcula o total de pagamentos recebidos
if ($areceber==1) {
    $sql2 = "
        SELECT *
        FROM saidas_pagamentos
        join saidas on (sai_codigo=saipag_saida)
        join metodos_pagamento on (saipag_metpagamento=metpag_codigo)
        WHERE sai_codigo=$saida
        ORDER BY saipag_data DESC
    ";
    if (!$query2 = mysql_query($sql2)) die("Erro48" . mysql_error());
    $linhas2=mysql_num_rows($query2);
    $tempagamentos=0;
    $pag_total=0;
    while ($dados2=mysql_fetch_assoc($query2)) {
        $tempagamentos=1;
        $valor=$dados2["saipag_valor"];
        $pag_total+=$valor;
    }
} else {
    $pag_total=$total_venda_comdesconto;
}
//echo "<br><br>Pagamentos total: $pag_total<br><br>";



//Calcula o valor total devolvido para pode gerar o saldo final que deve ser pago ao cliente
$sql18="
    SELECT * 
    FROM saidas_devolucoes_produtos
    JOIN saidas_devolucoes on saidevpro_numerodev=saidev_numero
    JOIN saidas on saidev_saida=sai_codigo
    JOIN produtos on saidevpro_produto=pro_codigo 
    LEFT JOIN pessoas on sai_consumidor = pes_codigo 
    WHERE saidev_saida=$saida 
    AND saidev_numero not in ($devolucao)
    ORDER BY saidevpro_itemdev DESC
    ";
if (!$query18 = mysql_query($sql18)) die("Erro CONSULTA DEVOLUCOES:" . mysql_error()."");
$linhas18=mysql_num_rows($query18);
if ($linhas18>0) $temdevolucoesanteiores=1; else $temdevolucoesanteiores=0;
$dev_total=0;
while ($dados18=mysql_fetch_assoc($query18)) {
    $val=$dados18["saidevpro_valtot"];
    $dev_total+=$val;
}


//echo "<br>Bruto: $total_venda_bruto";
//echo "<br>Desconto: $venda_descontovalor / $venda_descontopercentual %";
//echo "<br>Liquido: $total_venda_comdesconto";
//echo "<br><br> Esta Devolução: $valtot";
$valtot_comdesconto=$valtot*(100-$venda_descontopercentual)/100;
//echo "<br>Esta Devolução com Desconto: $valtot_comdesconto";
//echo "<br><br> Total Pago: $pag_total";
$pendente=$total_venda_comdesconto-$pag_total;
//echo "<br>Pagamentos Pendente: $pendente";
$dev_total_comdesconto=$dev_total*(100-$venda_descontopercentual)/100;
//echo "<br><br> Devoluções Anteriores com Desconto: $dev_total_comdesconto";
$saldo=$valtot_comdesconto-$pendente; //-$dev_total_comdesconto;

//Se houver valores pedente deve-se abater o saldo pedente no dinheiro que será devolvido.
//Sendo assim, é necessário  realizar o pagamento por parte do cliente para registrar o valor abatido.
if ($pendente>0) {
    if ($saldo<0) $abatido=$valtot_comdesconto; else $abatido=$pendente;
    $sql="
        INSERT INTO saidas_pagamentos (
            saipag_saida,
            saipag_valor,
            saipag_obs,
            saipag_metpagamento
        ) VALUES (
            $saida,
            $abatido,
            'Registro Automático para ABATIMENTO da DEVOLUÇÃO: $devolucao',
            '5'
        )
    ";

    if (!$query = mysql_query($sql)) die("Erro SQL 1:" . mysql_error());
    $pag_ultimo=mysql_insert_id();
    //echo "<br><br>FOI GERADO UM PAGAMENTO NO VALOR DEVOLVIDO PARA ABATER O SALDO DEVEDOR.<br><br>";
    $abatido_mostra="R$ ".number_format($abatido, 2, ',', '.');
    if ($usapagamentosparciais==1) $filtro_pagamento="<br>- Foi gerado um <b>PAGAMENTO</b> no valor de <b>$abatido_mostra </b>para abatimento do saldo devedor!<br>";
    else $filtro_pagamento="";


    //Atualiza devolução registrando o numero do pagamento, isso é necessário para quando excluir a devolução excluir também o pagamento
    $sql="UPDATE saidas_devolucoes SET saidev_pagamento='$pag_ultimo' WHERE saidev_numero=$devolucao";
    if (!$query = mysql_query($sql)) die("Erro SQL 2:" . mysql_error());


    //Gera entrada de caixa a partir do pagamento abatido
    if ($usacaixa==1) {
        $sql8 = "
        INSERT INTO 
            caixas_entradassaidas (
                caientsai_tipo,
                caientsai_valor,
                caientsai_datacadastro,
                caientsai_descricao,
                caientsai_usuarioquecadastrou,
                caientsai_numerooperacao,
                caientsai_saidapagamento
            )
        VALUES (
            '1',
            '$abatido',
            '$datahoraatual',
            'Registro Automático para ABATIMENTO da DEVOLUÇÃO: $devolucao, na VENDA: $saida',  
            '$usuario_codigo',    
            '$caixaoperacao',
            '$pag_ultimo'    
        )";
        if (!$query8= mysql_query($sql8)) die("Erro de SQL:" . mysql_error());
        $filtro_caixa_entrada="<br>- Foi gerado uma <b>ENTRADA DE CAIXA</b> no caixa no valor abatido!<br>";
    }

}




if ($saldo> 0) {
    $total_adevolver=$saldo;
    $filtro_pagar="<br>Você deve <b>DEVOLVER</b> ao cliente: <br><span class='fonte5'>R$ ".number_format($total_adevolver,2,",",".")."</span><br><br>";
    $total_areceber=0;
} else if ($saldo<0) {
    $total_adevolver=0;
    $total_areceber=$saldo*-1;
} else {
    $total_adevolver=0;
    $total_areceber=0;
}
//echo "<br><br><b>SALDO:</b> $saldo<br><br>";
//echo "<br><b>SALDO A RECEBER:</b> $total_areceber<br><br>";
//echo "<br><b>VALOR A DEVOLVER: $total_adevolver</b> <br><br>";
//echo "<br><br><b>GERAR ENTRADA/SAIDA DE CAIXA!";


//Sempre deve ser gerado uma saída de caixa no valor da devolução. 
if ($usacaixa==1) {
    $sql8 = "
    INSERT INTO 
        caixas_entradassaidas (
            caientsai_tipo,
            caientsai_valor,
            caientsai_datacadastro,
            caientsai_descricao,
            caientsai_usuarioquecadastrou,
            caientsai_numerooperacao,
            caientsai_saidadevolucao
        )
    VALUES (
        '2',
        '$valtot_comdesconto',
        '$datahoraatual',
        'Gerado automaticamente a partir da DEVOLUÇÃO nº $devolucao da venda nº $saida',  
        '$usuario_codigo',    
        '$caixaoperacao',
        '$devolucao'    
    )";
    if (!$query8= mysql_query($sql8)) die("Erro de SQL:" . mysql_error());
    $filtro_caixa_saida="<br>- Foi gerado uma <b>SAÍDA DE CAIXA</b> no valor da devolução!<br>";
    
}



//Mostrar notificação informando que foi inserido no estoque o produto. Se foi gerado algum pagamento. Se o dinheiro saiu/entrou do caixa. 
$tpl = new Template("templates/notificacao.html");
$tpl->ICONES = $icones;
//$tpl->MOTIVO_COMPLEMENTO = "";
$tpl->block("BLOCK_CONFIRMAR");
$tpl->LINK = "saidas_devolucoes.php?codigo=$saida";

$total_venda_bruto_mostra="R$ ".number_format($total_venda_bruto, 2, ',', '.');
$venda_descontovalor_mostra=number_format($venda_descontovalor, 2, ',', '.');
$venda_descontopercentual_mostra=number_format($venda_descontopercentual, 2, ',', '.');
$total_venda_comdesconto_mostra="R$ ".number_format($total_venda_comdesconto, 2, ',', '.');
$valtot_mostra="R$ ".number_format($valtot, 2, ',', '.');
$valtot_comdesconto_mostra="R$ ".number_format($valtot_comdesconto, 2, ',', '.');
$pag_total_mostra="R$ ".number_format($pag_total, 2, ',', '.');
$pendente_mostra="R$ ".number_format($pendente, 2, ',', '.');
$total_areceber_mostra="R$ ".number_format($total_areceber, 2, ',', '.');
$total_adevolver_mostra="R$ ".number_format($total_adevolver, 2, ',', '.');


if ($venda_descontovalor>0) {
    $filtro_comdesconto="
        Desconto da Venda: $venda_descontovalor_mostra / $venda_descontopercentual_mostra % <br>
        Valor da Venda (com desconto): $total_venda_comdesconto_mostra<br>
    ";
    $filtro_valordestadevolucao = "Valor Desta Devolução com o Desconto: $valtot_comdesconto_mostra  <br>";
} else {
    $filtro_comdesconto="";
    $filtro_valordestadevolucao="";
}

if ($areceber==1) {
    $filtro_areceber="
    Total Já Pago: $pag_total_mostra <br>
    Saldo Devedor Pendente: $pendente_mostra <br>
    Total a Receber Atual: $total_areceber_mostra <br>
    Total a Devolver: $total_adevolver_mostra <br>    
    ";
} else {
    $filtro_areceber="";
}


if ($usaestoque==1) {
    $filtro_estoque="- Os itens devolvidos foram <b>ADICIONADOS AO ESTOQUE</b> novamente<br>";
} else $filtro_estoque="";

$tpl->MOTIVO = "
    Devolução registrada com sucesso! <br>
    
    <br><b>RESUMO:</b><br>
    Valor da Venda: $total_venda_bruto_mostra <br>
    $filtro_comdesconto
    Valor Desta Devolução: $valtot_mostra <br>
    $filtro_valordestadevolucao
    $filtro_areceber
    $filtro_pagamento
    $filtro_caixa_entrada
    $filtro_pagar
    $filtro_caixa_saida
    $filtro_estoque
    <br>
";
$tpl->block("BLOCK_MOTIVO");
if ($usamodulofiscal==1) {
    $tpl->LINK="saidas_cadastrar_nfe.php?codigo=$saida&ope=3";
    $tpl->LINK_TARGET="";
    $tpl->PERGUNTA = "Deseja gerar nota fiscal?";
    $tpl->block("BLOCK_PERGUNTA");
    $tpl->NAO_LINK = "saidas_devolucoes.php?codigo=$saida";
    $tpl->block("BLOCK_BOTAO_NAO_LINK");
    $tpl->block("BLOCK_BOTAO_SIMNAO");
} else {
    $tpl->DESTINO="saidas_devolucoes.php?codigo=$saida";
    $tpl->block("BLOCK_BOTAO");
}
$tpl->show();


?>

