<?php

//print_r($_REQUEST);

//Verifica se o usuário pode acessar a tela
require "login_verifica.php";
$saida = $_GET["codigo"];
$ignorar_vendas_incompletas = $_REQUEST["ignorar_vendas_incompletas"];
$ignorar_vendas_areceber = $_REQUEST["ignorar_vendas_areceber"];
$tiposaida = $_REQUEST["tiposaida"];
if ($tiposaida == 1) {
    if ($permissao_saidas_cadastrar <> 1) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
} else {
    if ($permissao_saidas_cadastrar_devolucao <> 1) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
}
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}


//Verifica se algum produto desta Saída já foi acertado, se sim não permitir que continue
if ($saida != "") {
    $sql22 = "SELECT saipro_acertado FROM `saidas_produtos` WHERE saipro_saida=$saida and saipro_acertado !=0";
    $query22 = mysql_query($sql22);
    if (!$query22)
        die("Erro de SQL (22):" . mysql_error());
    $linhas22 = mysql_num_rows($query22);
    if ($linhas22 > 0) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
}

$tipopagina = "saidas";
include "includes.php";

//Verifica se usa módulo de vendas
if (($usavendas!=1)&&($tiposaida!=3)) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO VENDAS</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

//Verifica se tem devoluções
if (($usadevolucoes==1)&&($operacao==2)) {
    $sql18="
        SELECT * 
        FROM saidas_devolucoes_produtos
        JOIN saidas_devolucoes on saidevpro_numerodev=saidev_numero
        WHERE saidev_saida=$saida
        ";
    if (!$query18 = mysql_query($sql18)) die("Erro CONSULTA DEVOLUCOES 2:" . mysql_error()."");
    $linhas18=mysql_num_rows($query18);
    if ($linhas18>0) $temdevolucoes=1; else $temdevolucoes=0;
    if (($usadevolucoes==1)&&($temdevolucoes==1)) {
        $tpl6 = new Template("templates/notificacao.html");
        $tpl6->block("BLOCK_ERRO");
        $tpl6->ICONES = $icones;
        //$tpl6->block("BLOCK_NAOAPAGADO");
        $tpl6->MOTIVO = "Esta venda possui DEVOLUÇÕES<br>Portanto não pode ser alterada<br>";
        $tpl6->block("BLOCK_MOTIVO");
        $tpl6->block("BLOCK_BOTAO_FECHAR");
        $tpl6->show();
        exit;
    }
}



//Verifica se o usuário é um caixa e não tem caixa aberto, se sim não pode acessar as vendas
if (($usuario_caixa_operacao=="")&&($usuario_grupo==4)) {
    header("Location: permissoes_semacesso.php");
    exit;
}

//CONTROLE DA OPERAÇÃO
$dataatual = date("Y/m/d");
$horaatual = date("H:i:s");
$operacao = $_GET["operacao"]; //Operação 1=Cadastrar 2=Editar 3=Ver

//Verifica se permite edicão de cliente na venda
//Só existe uma excessão que permite editar o cliente sem que esteja parametrizado para isso, que é quando é feita uma venda a receber sem identificar o consumidor, ao final da venda é sugerido para editar a venda e alterar o consumidor!
$editarconsumidor=$_GET["editarconsumidor"];
if ($editarconsumidor==1) {
    $identificacaoconsumidorvenda=1; //Por CPF
}
$pega_dados_do_banco=0;


//Se a venda possui entrega, então atualizar o endereço atual como endereçodo consumidor
if ($_REQUEST["entrega"]==1) {
    $consumidor=$_POST["consumidor"];
    if (($consumidor>0)&&($fazentregas==1)) {
        $entrega_endereco_2=$_POST["endereco"];
        $entrega_endereco_numero_2=$_POST["endereco_numero"];
        $entrega_bairro_2=$_POST["bairro"];
        $entrega_fone1_2=$_POST["fone1"];
        $entrega_fone2_2=$_POST["fone2"];
        $entrega_cidade_2=$_POST["cidade"];
        $sql12= "UPDATE pessoas SET pes_endereco='$entrega_endereco_2', pes_numero='$entrega_endereco_numero_2', pes_bairro='$entrega_bairro_2', pes_fone1='$entrega_fone1_2', pes_fone2='$entrega_fone2_2', pes_cidade=$entrega_cidade_2 WHERE pes_codigo=$consumidor";
        if (!$query12 = mysql_query($sql12)) die("<br>Erro12:" . mysql_error());
    }

}

//Atualiza dados de entrega




$retirar_produto = $_GET["retirar_produto"];
//Se for eliminação de um produto ja da lista então pegar por get
if ($retirar_produto == '1') {
    $consumidor = $_GET["consumidor"];
    $entrega = $_GET["entrega"];
    $id = $_GET["id"];
    $tiposaida = $_GET["tiposaida"];
    $saida = $_GET["saida"];
    $saipro = $_GET["saipro"];
    $passo = $_GET["passo"];
    $lote = $_GET["lote"];
    $qtd = $_GET["qtd"];
    $produto = $_GET["produto"];
    $tipopessoa = $_GET["tipopessoa"];
    $obs = $_GET["obs"];

    $pega_dados_do_banco=1;


} else { 
    if ($operacao == 2) { // Se for edição pega os dados principais da venda para popular campos
        $saida = $_GET["codigo"];
        $sql = "
            SELECT * 
            FROM saidas 
            left join pessoas on (sai_consumidor=pes_codigo)
            left join cidades on (pes_cidade=cid_codigo)
            left join estados on (cid_estado=est_codigo)
            WHERE sai_codigo=$saida
        ";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro de SQL98:" . mysql_error());
        while ($dados = mysql_fetch_assoc($query)) {
            $consumidor = $dados["sai_consumidor"];
            $consumidor_cpf = $dados["pes_cpf"];
            $consumidor_cnpj = $dados["pes_cnpj"];
            $consumidor_fone = $dados["pes_fone1"];
            $tipopessoa = $dados["pes_tipopessoa"];
            $id = $dados["sai_id"];
            $tiposaida = $dados["sai_tipo"];
            $motivo = $dados["sai_saidajustificada"];
            $descricao = $dados["sai_descricao"];
            $areceber = $dados["sai_areceber"];
            $obs=$dados["sai_obs"];
            //Pega dados da entrega do banco
            $pega_dados_do_banco=1;
        }
    } else { //Caso seja uma venda nova, cadastro

        $consumidor = $_REQUEST["consumidor"];
        $saida = $_REQUEST["saida"];

        $operacao=1;
        $passo = $_REQUEST["passo"];  

        //Se ja tem saida, significa que ja passou o cadastro do consumidor, comandas ou entregas, ou seja, passo 2.
        if (($saida>0)) {
            $pega_dados_do_banco=1;
        } else {
            
            $cliente_nome = $_POST["cliente_nome"];
            $tipopessoa = $_POST["tipopessoa"];
            $consumidor_cpf=$_POST["cpf"];
            $consumidor_cnpj=$_POST["cnpj"];
            $consumidor_fone=$_POST["fone"];
            $entrega=$_REQUEST["entrega"];
            $obs=$_REQUEST["obs"];
            $entrega_dataentrega=$_POST["dataentrega"];
            $entrega_endereco=$_POST["endereco"];
            $entrega_endereco_numero=$_POST["endereco_numero"];
            $entrega_bairro=$_POST["bairro"];
            $entrega_cidade=$_POST["cidade"];
            $entrega_estado=$_POST["estado"];
            $entrega_pais=$_POST["pais"];
            $entrega_fone1=$_POST["fone1"];
            $entrega_fone2=$_POST["fone2"];
            if ($entrega_pais=="") $entrega_pais=$usuario_quiosque_pais;
            if ($entrega_estado=="") $entrega_estado=$usuario_quiosque_estado;
            if ($entrega_cidade=="") $entrega_cidade=$usuario_quiosque_cidade;  


        }

        if (($consumidor!="")&&($consumidor!=0)) { //foi selecionado uma pessoa
            $sql0="SELECT pes_cpf, pes_cnpj,pes_tipopessoa FROM pessoas WHERE pes_codigo=$consumidor";
            if (!$query0 = mysql_query($sql0)) die("Erro 0: " . mysql_error());
            $dados0=  mysql_fetch_assoc($query0);
            $consumidor_cpf=$dados0["pes_cpf"];
            $consumidor_cnpj=$dados0["pes_cnpj"];
            $consumidor_fone=$dados0["pes_fone1"];
            $tipopessoa = $dados0["pes_tipopessoa"];
        } 
        if ($tipopessoa=="") { //Pro padrão a pessoa é fisica, cpf
            $tipopessoa=1;
        }
         
    }
    

    $id = $_REQUEST["id"];
    $tiposaida = $_GET["tiposaida"];
    if ($operacao==1) $motivo = $_REQUEST["motivo"];
    if ($operacao==1) $descricao = $_POST["descricao"];
    $saipro = "";
    $porcao = $_POST["porcao"];
    if ($porcao=="") $porcao=0;
    $lote = $_REQUEST["lote"];
    $lote2 = $_REQUEST["lote2"];
    $produto = $_POST["produto"];
    $produto2 = $_POST["produto2"];
    if ($produto2 != "") {
        $produto = $produto2;
    }
    if ($lote2 != "") {
        $lote = $lote2;
    }
    //Quantidade de porcoes
    $porcao_qtd = $_POST["porcao_qtd"];
    if ($porcao_qtd=="") $porcao_qtd=0;

    //Pega valor unitário padrão do produto para incremento ou retirada de estoque
    $valunietq = $_POST["valuni2"];
    $valunietq = explode(" ", $valunietq);
    $valunietq = $valunietq[1];
    $valunietq = str_replace('.', '', $valunietq);
    $valunietq = str_replace(',', '.', $valunietq);

    //Pega o valor unitário que que deverá ser usado na saida. 
    //É necessário isto porque quando usa-se porcão o valor unitário pode ser diferente do valor unitário padrão do produto
    $valunisai = $_POST["valuni3"];
    $valunisai = explode(" ", $valunisai);
    $valunisai = $valunisai[1];
    $valunisai = str_replace('.', '', $valunisai);
    $valunisai = str_replace(',', '.', $valunisai);

    //Pega o valor total a ser gravado na saída (no estoque não tem valor total)
    $valtotsai = $_POST["valtot"];
    $valtotsai = explode(" ", $valtotsai);
    $valtotsai = $valtotsai[1];
    $valtotsai = str_replace('.', '', $valtotsai);
    $valtotsai = str_replace(',', '.', $valtotsai);

    //Pega quantidade para ser retirada ou incrementada no estoque
    if ($porcao==0) $qtd = $_POST["qtd"];
    else $qtd = $_POST["qtd2"];
    $qtd = str_replace('.', '', $qtd);
    $qtd = str_replace(',', '.', $qtd);
    $qtdnoestoque = $_POST["qtdnoestoque"];
    
}

//Se a saida tiver algum valor significa que já foi registrada a mesma, e portanto não há mais a necessidade de pegar os dados por post para poder gravar a mesma, pegamos do banco, pois já temos um consumidor vinculado a venda e estes não serão modificados.
if ($pega_dados_do_banco==1) {
    $sql = "
        SELECT * 
        FROM saidas 
        left join pessoas on (sai_consumidor=pes_codigo)
        left join cidades on (pes_cidade=cid_codigo)
        left join estados on (cid_estado=est_codigo)
        WHERE sai_codigo=$saida
    ";
    $query = mysql_query($sql); if (!$query) die("Erro de SQL98:" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $entrega=$dados["sai_entrega"];
        $entrega_dataentrega=$dados["sai_dataentrega"];
        $entrega_endereco=$dados["sai_entrega_endereco"];
        $entrega_endereco_numero=$dados["sai_entrega_endereco_numero"];
        $entrega_bairro=$dados["sai_entrega_bairro"];
        $entrega_cidade=$dados["sai_entrega_cidade"];
        $entrega_estado=$dados["cid_estado"];
        $entrega_pais=$dados["est_pais"];
        $entrega_fone1=$dados["sai_entrega_fone1"];
        $entrega_fone2=$dados["sai_entrega_fone2"];
        $obs=$dados["sai_obs"];

    }
}



$passo= $_REQUEST["passo"];


//Verificar se é uma edição, se sim então atualiza comanda e consumidor
if (($operacao==2)&&($passo==2)&&($tiposaida!=3)&&(($usacomanda==1)||($identificacaoconsumidorvenda!=3)||($obsnavenda==1)||($fazentregas==1))) {
    $id_novo=$_REQUEST["id"];
    if ($id_novo=="") $id_novo=$id;
    $obs=$_POST["obs"];
    $consumidor=$_REQUEST["consumidor"];

    //Atualiza consumidor e obs
    $sql11= "UPDATE saidas SET sai_consumidor=$consumidor, sai_id=$id_novo, sai_obs='$obs' WHERE sai_codigo=$saida";
    if (!$query11 = mysql_query($sql11)) die("<br>Erro11:" . mysql_error());


    //Atualiza dados de entrega
    if ( $fazentregas==1 ) {
        if ( $_REQUEST["entrega"] != $entrega ) $entrega=$_REQUEST["entrega"];
        if ( $_REQUEST["dataentrega"] != $entrega_dataentrega ) $entrega_dataentrega=$_REQUEST["dataentrega"];
        if ($entrega==0) $entrega_dataentrega="0000-00-00";
        
        $sql11= "UPDATE saidas SET sai_dataentrega=$entrega_dataentrega, sai_entrega=$entrega WHERE sai_codigo=$saida";
        if (!$query11 = mysql_query($sql11)) die("<br>Erro12:" . mysql_error());       


    }
}



//echo "valunietq:$valunietq valunisai:$valunisai valtotsai:$valtotsai";


if ($produto != "") {
    $sql7 = "SELECT sai_codigo FROM saidas WHERE sai_codigo=$saida";
    $query7 = mysql_query($sql7);
    if (!$query7)
        die("Erro de SQL 55: " . mysql_error());
    $linhas7 = mysql_num_rows($query7);
    if ($linhas7 == 0) {
        $tpl = new Template("templates/notificacao.html");
        $tpl->ICONES = $icones;
        $tpl->MOTIVO_COMPLEMENTO = "<b>Quando realizar uma venda não abra várias janelas ou abas em seu navegador! Não entre no sistema com o mesmo usuário em mais de um computador ao mesmo tempo</b>
            Por motivos de segurança esta venda foi cancelada, você deve iniciá-la novamente! Contato um administrador para saber mais!";
        $tpl->block("BLOCK_ATENCAO");
        $tpl->DESTINO = "saidas.php";
        $tpl->block("BLOCK_BOTAO");
        $tpl->show();
        exit;
    }
}
//Só permite que seja efetuado alguma venda se algum caixa estiver aberto
if (($produto != "")&&($usacaixa==1)) {
    $sql7 = "SELECT sai_codigo FROM saidas WHERE sai_codigo=$saida";
    $query7 = mysql_query($sql7);
    if (!$query7)
        die("Erro de SQL 55: " . mysql_error());
    $linhas7 = mysql_num_rows($query7);
    if ($linhas7 == 0) {
        $tpl = new Template("templates/notificacao.html");
        $tpl->ICONES = $icones;
        $tpl->MOTIVO_COMPLEMENTO = "<b>Quando realizar uma venda não abra várias janelas ou abas em seu navegador! Não entre no sistema com o mesmo usuário em mais de um computador ao mesmo tempo</b>
            Por motivos de segurança esta venda foi cancelada, você deve iniciá-la novamente! Contato um administrador para saber mais!";
        $tpl->block("BLOCK_ATENCAO");
        $tpl->DESTINO = "saidas.php";
        $tpl->block("BLOCK_BOTAO");
        $tpl->show();
        exit;
    }
}


//echo "retirar: $retirar_produto - consumidor: $consumidor - tiposaida: $tiposaida - saida: $saida - saipro: $saipro - passo:$passo<br>";
//echo "<br> <br>lote e produto: ($lote - $produto) <br>lote2 e produto2: ($lote2 - $produto2)<br> valuni:$valuni - qtd:$qtd - valtot:$valtot";
//CONTROLE DO PASSO


if ($tiposaida == "") {
    $tiposaida = 1;
}

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
if ($tiposaida == 1) {
    $tpl_titulo->TITULO = "VENDAS";
    $tpl_titulo->SUBTITULO = "REALIZAÇÃO DE VENDAS";
    $tpl_titulo->NOME_ARQUIVO_ICONE = "vendas.png";
} else {
    $tpl_titulo->TITULO = "SAÍDAS";
    $tpl_titulo->SUBTITULO = "RETIRADAS DE ESTOQUE";
    $tpl_titulo->NOME_ARQUIVO_ICONE = "saidas.png";
}
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->show();

//Verifica se há produtos cadastrados para poder fazer uma saida
if ($usaestoque==1) {
    $sql = "SELECT pro_codigo FROM produtos WHERE pro_cooperativa=$usuario_cooperativa";
    $query = mysql_query($sql); if (!$query) die("Erro o: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas == 0) {
        $tpl = new Template("templates/notificacao.html");
        $tpl->ICONES = $icones;
        $tpl->MOTIVO_COMPLEMENTO = "Para gerar uma venda ou devolução <b>é necessário que se tenha produtos cadastrados</b>. <br>Clique no botão abaixo para ir para a tela de cadastro de produtos";
        $tpl->block("BLOCK_ATENCAO");
        $tpl->DESTINO = "produtos_cadastrar.php?operacao=cadastrar";
        $tpl->block("BLOCK_BOTAO");
        $tpl->show();
        exit;
    }
}

//Inicio do formulário de saidas
$tpl1 = new Template("saidas_cadastrar.html");
$tpl1->LINK_DESTINO = "saidas_cadastrar.php?tiposaida=$tiposaida&operacao=$operacao&codigo=$saida&id=$id&editarconsumidor=$editarconsumidor";
$tpl1->LINK_ATUAL = "saidas_cadastrar.php?tiposaida=$tiposaida&operacao=$operacao&codigo=$saida&id=$id&editarconsumidor=$editarconsumidor";
$tpl1->ICONES_CAMINHO = $icones;

$tpl1->JS_CAMINHO = "saidas_cadastrar.js";
$tpl1->block("BLOCK_JS");

$tpl1->TR_ID="";
$tpl1->PASSO="$passo";


//Se for para deletar uma produto da lista
if ($retirar_produto == '1') { //Se o usuário clicou no excluir produto da lista
    //Antes de atualizar o estoque e remover item das saidas verificar se o item da saida que est� querendo deletar j� n�o foi deletado
    //(isso acontece quando o usu�rio pressiona F5 depois de clicar no bot�o remover item)        
    $sql_f5 = "
        SELECT * 
        FROM saidas_produtos
        join produtos on (saipro_produto=pro_codigo)
        WHERE saipro_saida=$saida
        AND saipro_codigo=$saipro
    ";
    
    if (!$query_f5 = mysql_query($sql_f5)) die("Erro de SQL F5 Remover item:" . mysql_error());
    $dados_f5=mysql_fetch_assoc($query_f5);
    $prod_controlar_estoque=$dados_f5["pro_controlarestoque"];

    $linhas_f5 = mysql_num_rows($query_f5);
    if ($linhas_f5 > 0) {

        if ($prod_controlar_estoque==1) {

            //Verifica se há itens relacionados que devem ser excluídos juntos. Itens conjuntos.
            $sql8="
                SELECT saipro_codigo
                FROM saidas_produtos 
                WHERE saipro_itemconjunto=$saipro
                AND saipro_saida=$saida
            ";
            if (!$query8=mysql_query($sql8)) die("Erro de SQL verifica itens conjuntos:" . mysql_error());
        

            while ($dados8=mysql_fetch_assoc($query8)) {

                //Para cada item relacionado realizar o incremento no estoque
                $item=$dados8["saipro_codigo"];
                //echo "<br><br>($item)";
                $sql66="SELECT * FROM saidas_produtos WHERE saipro_saida=$saida and saipro_codigo=$item";            
                if (!$query66=mysql_query($sql66)) die("Erro 66:" . mysql_error());
                $dados66=mysql_fetch_assoc($query66);
                $produto_usar=$dados66["saipro_produto"];
                $lote_usar=$dados66["saipro_lote"];
                $qtd_usar=$dados66["saipro_quantidade"];



                //Devolver para o estoque, e excluir o produto da saida
                //Verifica se o produto existe no estoque ou se foi eliminado por ter valor 0
                $sql9 = "
                SELECT
                    *
                FROM
                    estoque
                WHERE
                    etq_quiosque=$usuario_quiosque and
                    etq_produto=$produto_usar and
                    etq_lote=$lote_usar
                ";
                if (!$query9 = mysql_query($sql9)) die("Erro de SQL 109:" . mysql_error());
                
                 $linhas9 = mysql_num_rows($query9);
                if ($linhas9 > 0) { //O produto existe no estoque
                    //Atualiza a quantidade no estoque            
                    //$qtd = str_replace('.', '', $qtd);
                    //$qtd = str_replace(',', '.', $qtd);
                    $sql_repor = "
                    UPDATE
                        estoque 
                    SET 
                        etq_quantidade=(etq_quantidade+$qtd_usar)
                    WHERE
                        etq_quiosque=$usuario_quiosque and
                        etq_produto=$produto_usar and
                        etq_lote=$lote_usar
                    ";
                    if (!$query_repor = mysql_query($sql_repor)) die("Erro de SQL2:" . mysql_error());
                    
                } else { //O produto não existe mais no estoque, vamos inserir
                    //Pegar os demais dados necessários para inserir no estoque
                    $sql = "SELECT * FROM `entradas_produtos` join entradas on (entpro_entrada=ent_codigo) WHERE entpro_entrada=$lote_usar";
                    if (!$query = mysql_query($sql))  die("Erro de SQL3:" . mysql_error());
                    
                    while ($dados = mysql_fetch_assoc($query)) {
                        $validade = $dados["entpro_validade"];
                        $valuni = $dados["entpro_valorunitario"];
                        $fornecedor = $dados["ent_fornecedor"];
                    }
                    //Interir o produto no estoque
                    //echo "<br><br>inseriu no estoque<br><br>";
                    $sql16 = "INSERT INTO estoque (etq_quiosque,etq_produto,etq_fornecedor,etq_lote,etq_quantidade,etq_valorunitario,etq_validade)
                    VALUES ('$usuario_quiosque','$produto_usar','$fornecedor','$lote_usar','$qtd_usar','$valuni','$validade')";
                    if (!$query16 = mysql_query($sql16)) die("Erro de SQL4 (inserir no estoque): " . mysql_error());
                    
                }

                //Elimina o item (e se houver itens conjuntos tb)
                $sql_del = "DELETE FROM saidas_produtos WHERE saipro_saida=$saida and saipro_codigo=$item";
                if (!$query_del = mysql_query($sql_del))  die("Erro de SQL5:" . mysql_error());

            }
        } else {
            //Elimina o protudo da Saída
            $sql_del = "DELETE FROM saidas_produtos WHERE saipro_saida=$saida and saipro_codigo=$saipro";
            if (!$query_del = mysql_query($sql_del))  die("Erro de SQL55:" . mysql_error());

        }
        
        //Atualiza o status para incompleto
         $sql_status = "UPDATE saidas SET sai_status=2 WHERE sai_codigo=$saida";
         if (!$query_status = mysql_query($sql_status)) die("Erro de SQL Status: " . mysql_error());
    }
} else {  //Independente se for cadastrou ou edição, só inserir produto na saida se vier os dados do produto e lote etc. dos campos
    if (($saida != "") && ($produto != "")) { //Produto selecionado, portanto incluir este na saida
        
        $produto_controlar_estoque=$_REQUEST["produto_controlar_estoque"];
        if ($produto_controlar_estoque==1) { //O produto tem controle de estoque, então deve-se decrementa-lo do estoque.

        

            if ($lote!="") { //Quando o cliente não ignora lotes, ou seja, seleciona fornecedor e lote na hora da venda

                //Verifica a quantida atual do estoque
                $sql = "SELECT etq_quantidade FROM estoque WHERE etq_quiosque=$usuario_quiosque and etq_produto=$produto and etq_lote=$lote";
                if (!$query = mysql_query($sql))  die("Erro de SQL7:" . mysql_error());
                while ($dados = mysql_fetch_assoc($query)) {
                    $qtdatual = $dados["etq_quantidade"];
                }

                //Calculando a quantidade final
                $qtdfinal = $qtdatual - $qtd;
                //echo "qtdfinal = $qtdatual - $qtd;";

                //Se a quantidade final do estoque ficar negativa ent�o n�o permitir seja inserido a saida deste produto e nem atualizado o estoque        
                //(Isso acontece quando o usu�rio inclui um produto na lista e pressiona F5)
                if ($qtdfinal >= 0) {
                    //Inserindo os produtos na Saída
                    $sql_saida_produto = "
                    INSERT INTO saidas_produtos (
                        saipro_saida, saipro_produto, saipro_lote, saipro_quantidade, saipro_valorunitario,saipro_valortotal,saipro_porcao,saipro_porcao_quantidade
                    )
                    VALUES (
                        '$saida','$produto','$lote','$qtd','$valunisai','$valtotsai',$porcao,$porcao_qtd
                    )        
                    ";
                    $query_saida_produto = mysql_query($sql_saida_produto);
                    if (!$query_saida_produto) {
                        die("Erro de SQL61: " . mysql_error());
                    }

                    //Atualiza o item relacionado caso o sistema ignore lotes.
                    $sql44="SELECT max(saipro_codigo) as item_maior FROM saidas_produtos WHERE saipro_saida=$saida";
                    if (!$query44 = mysql_query($sql44)) die("Erro de SQL44: " . mysql_error());
                    $dados44=mysql_fetch_assoc($query44);
                    $item_cadastrado=$dados44["item_maior"];
                    $sql45 = "
                        UPDATE saidas_produtos   
                        SET saipro_itemconjunto='$item_cadastrado'
                        WHERE saipro_codigo='$item_cadastrado'
                        AND saipro_saida=$saida
                    ";
                    if (!$query45 = mysql_query($sql45)) die("Erro SQL: 45" . mysql_error());


                    //Atualiza o status para incompleto
                     $sql_status = "UPDATE saidas SET sai_status=2 WHERE sai_codigo=$saida";
                    $query_status = mysql_query($sql_status);
                    if (!$query_status)
                        die("Erro de SQL Status: " . mysql_error());


                    //Retirando do estoque           
                    $sql_retirar = "
                    UPDATE
                        estoque 
                    SET 
                        etq_quantidade=$qtdfinal
                    WHERE
                        etq_quiosque=$usuario_quiosque and
                        etq_produto=$produto and
                        etq_lote=$lote 
                    ";
                    $query_retirar = mysql_query($sql_retirar);
                    if (!$query_retirar) {
                        die("Erro de SQL8:" . mysql_error());
                    }

                    //Se a quantidade do etoque zerou ent�o eliminar o produto do estoque
                    if ($qtdfinal == "0") {
                         $sql = "DELETE FROM estoque WHERE etq_quiosque=$usuario_quiosque and etq_produto=$produto and etq_lote=$lote";
                        $query = mysql_query($sql);
                        if (!$query) {
                            die("Erro de SQL9:" . mysql_error());
                        }
                    }
                } 
            } else { //Não tem lote, portanto provavelmente o quiosque está parametrizado para ignorar lotes.
                if ($ignorarlotes==1) {
                    //Verifica a quantidade existente em cada lote. Quando encontrar decrementar o ultimo e se faltar descrementar o próximo lote (peneultimo) e assim por diante, até que a quantidade desejada a ser retirada do estoque seja suprida.
                    $sql5="
                        SELECT etq_lote,etq_quantidade,etq_valorunitario
                        FROM estoque 
                        WHERE etq_produto=$produto
                        AND etq_quiosque=$usuario_quiosque
                        ORDER BY etq_lote ASC
                    ";
                    if (!$query5 = mysql_query($sql5)) die("Erro de SQL:" . mysql_error());
                    $qtd_aretirar=$qtd;
                    $conta=0;
                    $qtd_porcao_aretirar=0;
                    $valuni=0;
                    while ($dados5 = mysql_fetch_array($query5)) { //Repete enquanto tem não tem quantidade suficiente para registrar a retirada do estoque ignorando o lote.
                        $conta++;
                        $valuni_estoque=$dados5["etq_valorunitario"];
                        $lote_estoque=$dados5["etq_lote"];
                        $qtd_estoque=$dados5["etq_quantidade"];
                        $saldo=$qtd_estoque-$qtd_aretirar;
                        $valuni=$valuni_estoque;
                        //echo "($porcao_qtd / $valuni3)";
                        if ($porcao_qtd>0) {
                            $valuni=$valunisai;
                        }

                       
                        if ($conta==1) {
                            //$valuni=$valuni_estoque;
                            $lote_principal=$lote_estoque;
                        } 

                        //Atualiza o status para incompleto
                        $sql_status = "UPDATE saidas SET sai_status=2 WHERE sai_codigo=$saida";
                        if (!$query_status = mysql_query($sql_status)) die("Erro de SQL Status: " . mysql_error());




                        if ($saldo <=0) { //Tirar toda a quantidade do lote atual, e continuar retirando no próximo lote
                            //echo "Limpar lote: $lote_estoque ($qtd_aretirar / $qtd_estoque) <br>";
                            

                            if (($saldo==0)&&($conta==1)) $lote_principal="";
                            $qtd_porcao_aretirar=($porcao_qtd*$qtd_estoque)/$qtd;
                            $qtd_porcao_aretirar=number_format($qtd_porcao_aretirar, 2, '.', '');
                            if ($porcao_qtd>0) $valtot= $valuni * $qtd_porcao_aretirar;
                            else $valtot= $valuni * $qtd_estoque;
                            
                            //Insere o registro nas saídas
                            $sql_saida_produto = "
                            INSERT INTO saidas_produtos (
                                saipro_saida, saipro_produto, saipro_lote, saipro_quantidade, saipro_valorunitario,saipro_valortotal,saipro_porcao,saipro_porcao_quantidade,saipro_loteconjunto
                            )
                            VALUES (
                                '$saida','$produto','$lote_estoque','$qtd_estoque','$valuni','$valtot',$porcao,$qtd_porcao_aretirar,'$lote_principal'
                            )        
                            ";
                            if (!$query_saida_produto = mysql_query($sql_saida_produto)) die("Erro de SQL68: " . mysql_error());

                            //Atualiza o item relacionado caso o sistema ignore lotes.
                            //echo "<br><br>".
                            $sql44="SELECT max(saipro_codigo) as item_maior FROM saidas_produtos WHERE saipro_saida=$saida";
                            if (!$query44 = mysql_query($sql44)) die("Erro de SQL44: " . mysql_error());
                            $dados44=mysql_fetch_assoc($query44);
                            $item_cadastrado=$dados44["item_maior"];
                            if ($conta==1) $item_principal=$item_cadastrado;
                            //echo "<br>".
                            $sql45 = "
                                UPDATE saidas_produtos   
                                SET saipro_itemconjunto='$item_principal'
                                WHERE saipro_codigo='$item_cadastrado'
                                AND saipro_saida=$saida
                            ";
                            if (!$query45 = mysql_query($sql45)) die("Erro SQL: 45" . mysql_error());

                            //Tirar do estoque todo o lote atual
                            //Eliminar o produto do estoque pois a quantidade zerou
                            $sql = "DELETE FROM estoque WHERE etq_quiosque=$usuario_quiosque and etq_produto=$produto and etq_lote=$lote_estoque";
                            if (!$query = mysql_query($sql)) die("Erro de SQL10:" . mysql_error());

                            //Calcula a quantidade restante para usar no proximo lote   
                            $qtd_aretirar=$qtd_aretirar - $qtd_estoque;

                        } else { //Atualizar o estoque neste lote com o restante da quantidade a retirar
                            //echo "Atualziar lote: $lote_estoque ($qtd_aretirar / $qtd_estoque)<br>";
                            if ($conta==1) {
                                $lote_principal="";
                            }
                            
                            $qtd_porcao_aretirar=($porcao_qtd*$qtd_aretirar)/$qtd;
                            $qtd_porcao_aretirar=number_format($qtd_porcao_aretirar, 2, '.', '');
                            if ($porcao_qtd>0) $valtot= $valuni * $qtd_porcao_aretirar;
                            else $valtot= $valuni * $qtd_aretirar;                      


                            //Insere o registro nas saídas
                            //echo "<br><br>".
                            $sql_saida_produto = "
                            INSERT INTO saidas_produtos (
                                saipro_saida, saipro_produto, saipro_lote, saipro_quantidade, saipro_valorunitario,saipro_valortotal,saipro_porcao,saipro_porcao_quantidade,saipro_loteconjunto
                            )
                            VALUES (
                                '$saida','$produto','$lote_estoque','$qtd_aretirar','$valuni','$valtot',$porcao,$qtd_porcao_aretirar,'$lote_principal'
                            )        
                            ";
                            if (!$query_saida_produto = mysql_query($sql_saida_produto)) die("Erro de SQL64: " . mysql_error());

                            $sql44="SELECT max(saipro_codigo) as item_maior FROM saidas_produtos WHERE saipro_saida=$saida";
                            if (!$query44 = mysql_query($sql44)) die("Erro de SQL44: " . mysql_error());
                            $dados44=mysql_fetch_assoc($query44);
                            $item_cadastrado=$dados44["item_maior"];
                            if ($conta==1) $item_principal=$item_cadastrado;
                            //echo "<br>".
                            $sql45 = "
                                UPDATE saidas_produtos   
                                SET saipro_itemconjunto='$item_principal'
                                WHERE saipro_codigo='$item_cadastrado'
                                AND saipro_saida=$saida
                            ";
                            if (!$query45 = mysql_query($sql45)) die("Erro SQL: 45" . mysql_error());

                            //Retirando do estoque           
                            $sql_retirar = "
                            UPDATE
                                estoque 
                            SET 
                                etq_quantidade=etq_quantidade-$qtd_aretirar
                            WHERE
                                etq_quiosque=$usuario_quiosque and
                                etq_produto=$produto and
                                etq_lote=$lote_estoque
                            ";
                            if (!$query_retirar = mysql_query($sql_retirar))  die("Erro de SQL8:" . mysql_error());
                        }
                        if ($saldo>=0) break;

                    }
                }

            }
        } else { //O produto não possui controle de estoque, ou seja, é estoque infinito
            //Inserir nos produtos,  não tem necessidade de atualizar estoque
            
            //echo "inserir sem retirar do estoque";
            $produto=trim($produto);
            $sql_saida_produto = "
            INSERT INTO saidas_produtos (
                saipro_saida, saipro_produto, saipro_lote, saipro_quantidade, saipro_valorunitario,saipro_valortotal,saipro_porcao,saipro_porcao_quantidade
            )
            VALUES (
                '$saida','$produto','0','$qtd','$valunisai','$valtotsai',$porcao,$porcao_qtd
            )        
            ";
            if (!$query_saida_produto = mysql_query($sql_saida_produto)) die("Erro de SQL68: " . mysql_error());

            //Atualiza o item relacionado caso o sistema ignore lotes.
            //echo "<br><br>".
            $sql44="SELECT max(saipro_codigo) as item_maior FROM saidas_produtos WHERE saipro_saida=$saida";
            if (!$query44 = mysql_query($sql44)) die("Erro de SQL44: " . mysql_error());
            $dados44=mysql_fetch_assoc($query44);
            $item_cadastrado=$dados44["item_maior"];
            if ($conta==1) $item_principal=$item_cadastrado;
            //echo "<br>".
            $sql45 = "
                UPDATE saidas_produtos   
                SET saipro_itemconjunto='$item_principal'
                WHERE saipro_codigo='$item_cadastrado'
                AND saipro_saida=$saida
            ";
            if (!$query45 = mysql_query($sql45)) die("Erro SQL: 45" . mysql_error());

        }
    }
}


//Inserir saida principal com o status incompleto. Esse processo � feito uma unica vez, antes de come�ar 
//a inserção dos produtos dentro dessa saida
if (($saida == 0) && ($passo == 2)) {
    $dataatual = date("Y-m-d");
    $horaatual = date("H:i:s");
    $datahoracadastro=$dataatual." ".$horaatual;
    
    //Se for cliente novo, cadastrar ele antes de cadastrar a saida
    if ($cliente_nome!="") {
        //echo "Cadastrar nova pessoa <b>$cliente_nome</b><br>";
        
        $sql3="SELECT max(pes_id) as ultimo_id FROM pessoas";
        $query3 = mysql_query($sql3); if (!$query3) die("Erro de SQL2: " . mysql_error());
        $dados3=  mysql_fetch_assoc($query3);
        $id_ultimo=$dados3["ultimo_id"];
        $id=$id_ultimo+1;
        $consumidor_cpf2 =  str_replace(".", "", $consumidor_cpf);
        $consumidor_cpf2 =  str_replace("-", "", $consumidor_cpf2);
        $consumidor_cpf2 =  str_replace("_", "", $consumidor_cpf2);
        $consumidor_cnpj2 =  str_replace(".", "", $consumidor_cnpj);
        $consumidor_cnpj2 =  str_replace("/", "", $consumidor_cnpj2);
        $consumidor_cnpj2 =  str_replace("-", "", $consumidor_cnpj2);
        $consumidor_cnpj2 =  str_replace("_", "", $consumidor_cnpj2);
        
        //echo "<br><br>cadastrou pessoa<br><br>";
        if ($fazentregas==1) $consumidor_fone=$entrega_fone1;
        $sql1 = "
            INSERT INTO
                pessoas (pes_id,pes_nome,pes_cnpj,pes_cpf,pes_tipopessoa,pes_datacadastro,pes_horacadastro,pes_cooperativa,pes_possuiacesso,pes_usuarioquecadastrou,pes_quiosquequecadastrou,pes_fone1,pes_fone2,pes_endereco,pes_numero,pes_bairro, pes_cidade)
            VALUES
                ($id,'$cliente_nome','$consumidor_cnpj2','$consumidor_cpf2',$tipopessoa,'$dataatual','$horaatual',$usuario_cooperativa,0,$usuario_codigo,$usuario_quiosque, '$consumidor_fone','$entrega_fone2','$entrega_endereco','$entrega_endereco_numero','$entrega_bairro', $entrega_cidade)        
        ";
        $query1 = mysql_query($sql1); if (!$query1) die("Erro de SQL108: " . mysql_error());
        $consumidor = mysql_insert_id();
        $sql2="INSERT INTO mestre_pessoas_tipo (mespestip_pessoa,mespestip_tipo) VALUES ($consumidor,6)";
        $query2 = mysql_query($sql2); if (!$query2) die("Erro de SQL2: " . mysql_error());

    }
    
    if ($id=="") $id=0;

    if (($ignorar_vendas_incompletas!=1)&&($ignorar_vendas_areceber!=1)) { //Só insere se o consumidor não possuir restrições cadastrais

        $sql_saida = "
        INSERT INTO
            saidas (sai_quiosque, sai_caixaoperacaonumero, sai_consumidor, sai_tipo, sai_saidajustificada,sai_descricao, sai_datacadastro, sai_horacadastro,sai_status,sai_datahoracadastro,sai_usuarioquecadastrou, sai_id,sai_obs, sai_entrega, sai_dataentrega,sai_entrega_fone1, sai_entrega_fone2, sai_entrega_endereco, sai_entrega_endereco_numero, sai_entrega_bairro, sai_entrega_cidade, sai_entrega_concluida )
        VALUES
            ('$usuario_quiosque','$usuario_caixa_operacao','$consumidor','$tiposaida','$motivo','$descricao','$dataatual','$horaatual',2,'$datahoracadastro',$usuario_codigo, $id, '$obs','$entrega','$entrega_dataentrega','$entrega_fone1','$entrega_fone2','$entrega_endereco','$entrega_endereco_numero','$entrega_bairro',$entrega_cidade, 0 )        
        ";
        $query_saida = mysql_query($sql_saida);
        if (!$query_saida)
            die("Erro de SQL107: " . mysql_error());
        $saida = mysql_insert_id();
    } else {
        $saida=$_GET["codigo"];
    }
    
    $operacao=1;
    
}

//Enviar ocultamento o numero da saida
$tpl1->IGNORARLOTES = "$ignorarlotes";
$tpl1->IDENTIFICACAOCONSUMIDORVENDA = "$identificacaoconsumidorvenda";
$tpl1->USACODIGOBARRASINTERNO = "$usacodigobarrasinterno";
$tpl1->USAEAN = "$usaean";
$tpl1->IGNORARLOTES = "$ignorarlotes";
$tpl1->CAMPOOCULTO_NOME = "permitevendasareceber";
$tpl1->CAMPOOCULTO_VALOR = $permitevendasareceber;
$tpl1->block("BLOCK_CAMPOSOCULTOS");
$tpl1->CAMPOOCULTO_NOME = "saida";
$tpl1->CAMPOOCULTO_VALOR = $saida;
$tpl1->block("BLOCK_CAMPOSOCULTOS");
$tpl1->CAMPOOCULTO_NOME = "passo";
$tpl1->CAMPOOCULTO_VALOR = $passo2;
$tpl1->block("BLOCK_CAMPOSOCULTOS");


if ($tiposaida == 1) {
    
    //ID, Comanda, Ficha
    $tpl1->TR_ID="linha_comanda";
    $tpl1->CAMPO_QTD_CARACTERES = "8";
    $tpl1->TITULO = "Comanda / Ident.";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->CAMPO_DICA="";
    $tpl1->CAMPO_TIPO = "number";
    $tpl1->CAMPO_NOME = "id";
    $tpl1->CAMPO_TAMANHO = "8";
    $tpl1->CAMPO_ESTILO = "width:80px;";
    $tpl1->CAMPO_FOCO = "  ";
    if ($saida!="") {
        $sql22="SELECT sai_id FROM saidas WHERE sai_codigo=$saida";
        if (!$query22=mysql_query($sql22)) die("Erro22: " . mysql_error());
        $dados22=  mysql_fetch_array($query22);
        $id=$dados22[0];
    }
    $tpl1->CAMPO_VALOR = "$id";
    $tpl1->CAMPO_OBRIGATORIO = "  ";
    if ($passo!=1) {
        $tpl1->CAMPO_DESABILITADO = " disabled";
    } else {
        $tpl1->CAMPO_DESABILITADO = " ";
    }
    $tpl1->CAMPO_ONKEYUP = "verifica_comanda_duplicada(this.value)";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_CONTEUDO");
    
    $tpl1->ICONE_DESTINO="#";;
    $tpl1->ICONE_ARQUIVO="$icones"."confirmar2.png";;
    $tpl1->ICONE_DICA="Validador de comanda duplicada";
    $tpl1->ICONE_ALTERNATIVO="X";
    $tpl1->ICONE_ID="validador_comanda_duplicada";
    $tpl1->ICONE_AOCLICAR="";
    $tpl1->block("BLOCK_ICONE_TAMANHOPADRAO");
    $tpl1->block("BLOCK_ICONE");
    $tpl1->block("BLOCK_CONTEUDO");

    $tpl1->TEXTO_NOME="texto_comanda_duplicada";
    $tpl1->TEXTO_CLASSE="";
    $tpl1->TEXTO_VALOR="";
    $tpl1->block("BLOCK_TEXTO");
    $tpl1->block("BLOCK_CONTEUDO");

    $tpl1->block("BLOCK_ITEM");
    
    
    //Consumidor Cliente
    $tpl1->TR_ID="linha_consumidor";
    $tpl1->CAMPO_QTD_CARACTERES = "";
    $tpl1->TITULO = "Consumidor";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    
    //Tipo Pessoa
    $tpl1->SELECT_CLASSE = " width:80px; ";
    $tpl1->SELECT_NOME = "tipopessoa";
    $tpl1->SELECT_DESABILITADO = "";
    $tpl1->SELECT_OBRIGATORIO = " required ";
    if ($passo==2) {
        $tpl1->SELECT_DESABILITADO = " disabled ";
    }
    $tpl1->SELECT_AOTROCAR = " seleciona_tipo_pessoa(this.value)";
    $tpl1->OPTION_VALOR = "1";
    $tpl1->OPTION_NOME = "Física";
    if ($tipopessoa==1)
        $tpl1->OPTION_SELECIONADO = " selected ";
    else 
        $tpl1->OPTION_SELECIONADO = "  ";
    $tpl1->block("BLOCK_SELECT_OPTION");
    $tpl1->OPTION_VALOR = "2";
    $tpl1->OPTION_NOME = "Juridica";
    if ($tipopessoa==2)
        $tpl1->OPTION_SELECIONADO = " selected ";
    else 
        $tpl1->OPTION_SELECIONADO = "  ";
    $tpl1->block("BLOCK_SELECT_OPTION");
    $tpl1->block("BLOCK_SELECT");
    
    //Documento CPF CNPJ
    //CPF
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_ESTILO = "width:120px;";
    $tpl1->CAMPO_NOME = "cpf";
    $tpl1->CAMPO_TAMANHO = "14";
    $tpl1->CAMPO_QTD_CARACTERES = "14";
    $tpl1->CAMPO_FOCO = " ";
    $tpl1->CAMPO_VALOR = "$consumidor_cpf";
    if ($passo==2) {
        $tpl1->CAMPO_DESABILITADO = " disabled ";
    }
    $tpl1->CAMPO_OBRIGATORIO = "  ";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_DICA = "CPF";
    $tpl1->CAMPO_ONKEYUP = "verifica_cpf(this.value)";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONBLUR = ""; 
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_CAMPO");
    //CNPJ
    $tpl1->CAMPO_ESTILO = "width:155px;";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "cnpj";
    $tpl1->CAMPO_DICA = "CNPJ";
    $tpl1->CAMPO_TAMANHO = "70";
    $tpl1->CAMPO_FOCO = " ";
    $tpl1->CAMPO_VALOR = "$consumidor_cnpj";
    if ($passo==2) {
        $tpl1->CAMPO_DESABILITADO = " disabled ";
    } 
    $tpl1->CAMPO_OBRIGATORIO = "  ";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_ONKEYUP = "verifica_cnpj(this.value)";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONBLUR = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_CAMPO");


    //Telefone para cadastro de novos clientes
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_ESTILO = "width:120px;";
    $tpl1->CAMPO_NOME = "fone";
    $tpl1->CAMPO_TAMANHO = "20";
    $tpl1->CAMPO_QTD_CARACTERES = "14";
    $tpl1->CAMPO_FOCO = " ";
    $tpl1->CAMPO_VALOR = "$consumidor_fone";
    if ($passo==2) {
        $tpl1->CAMPO_DESABILITADO = " disabled ";
    }
    $tpl1->CAMPO_OBRIGATORIO = "  ";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_DICA = "Telefone";
    $tpl1->CAMPO_ONKEYUP = "verifica_fone_botao_incluir(this.value);";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONBLUR = "verifica_fone(this.value);"; 
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_CAMPO");

    
    //Nome do cliente para cadastro
    $tpl1->CAMPO_DICA = "Nome ";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "cliente_nome";
    $tpl1->CAMPO_TAMANHO = "";
    $tpl1->CAMPO_ESTILO = "width:180px;";
    $tpl1->CAMPO_FOCO = " ";
    $tpl1->CAMPO_QTD_CARACTERES = "70";
    $tpl1->CAMPO_VALOR = "$cliente_nome";
    $tpl1->CAMPO_DESABILITADO = " disabled ";
    $tpl1->CAMPO_OBRIGATORIO = " required ";
    $tpl1->CAMPO_ONBLUR = ""; 
    $tpl1->CAMPO_ONKEYUP = "";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_CAMPO");
    
    //Selecionar Cliente
    $tpl1->SELECT2_NOME = "consumidor";
    $tpl1->SELECT2_DESABILITADO = "";
    $tpl1->SELECT2_OBRIGATORIO = " required ";
    $tpl1->SELECT2_AOTROCAR = "";
    $tpl1->SELECT2_FOCO = "";
    if ($passo != 1) {
        $tpl1->SELECT2_DESABILITADO = " disabled ";
    } else {
        $tpl1->SELECT2_DESABILITADO = " ";
    }
    
    //Pesquisa tipo pessoa do novo consumidor escolhido
    if ($consumidor=="") $tipopessoa=1; //Se for cliente geral entao é pessoa fisica
    else {
        $sql2="SELECT pes_tipopessoa FROM pessoas WHERE pes_codigo=$consumidor";
        if (!$query2 = mysql_query($sql2)) die("Erro 7: " . mysql_error());
        $dados2=mysql_fetch_array($query2);
        $tipopessoa=$dados2[0];
    }
    if ($consumidor!=0) {
        $sql_filtro.="and pes_tipopessoa=$tipopessoa";
    }
    $sql = "
        SELECT
            *
        FROM
            pessoas
            join mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)
            join pessoas_tipo on (mespestip_tipo=pestip_codigo)
        WHERE
            mespestip_tipo=6 and
            pes_cooperativa=$usuario_cooperativa
            $sql_filtro
        ORDER BY
            pes_nome
    ";
    if (!$query = mysql_query($sql)) die("Erro 8: " . mysql_error());
    $tpl1->OPTION2_VALOR = "0";
    $tpl1->OPTION2_NOME = "Clientes Geral";
    $tpl1->OPTION2_SELECIONADO = " selected ";
    $tpl1->block("BLOCK_SELECT2_OPTION");
    while ($dados = mysql_fetch_array($query)) {
        $tpl1->OPTION2_VALOR = $dados["pes_codigo"];
        $tpl1->OPTION2_NOME = $dados["pes_nome"];
        if ($consumidor == $dados["pes_codigo"]) {
            $tpl1->OPTION2_SELECIONADO = " selected ";
        } else {
            $tpl1->OPTION2_SELECIONADO = " ";
        }
        $tpl1->block("BLOCK_SELECT2_OPTION");
    }
    $tpl1->SELECT2_AOTROCAR = "consumidor_selecionado(this.value);";
    $tpl1->block("BLOCK_SELECT2");
    $tpl1->block("BLOCK_CONTEUDO");
    
    //Icone para CADASTRAR CONSUMIDOR em NOVA JANELA
    //Atualizar
    $tpl1->ICONE_DESTINO="#";
    $tpl1->ICONE_ALVO="";
    $tpl1->ICONE_ARQUIVO="../imagens/icones/geral/atualizar.png";
    $tpl1->ICONE_DICA="Atualizar cadastro de consumidores";
    $tpl1->ICONE_ALTERNATIVO="ATU";
    $tpl1->ICONE_ID="atu_consumidor";
    $tpl1->ICONE_AOCLICAR="atualiza_consumidor()";
    $tpl1->block("BLOCK_ICONE_TAMANHOPADRAO");
    $tpl1->block("BLOCK_ICONE");
    $tpl1->block("BLOCK_CONTEUDO");
    //Cadastrar
    $tpl1->ICONE_DESTINO="pessoas_cadastrar.php?modal=1&operacao=cadastrar";
    $tpl1->ICONE_ALVO="_blank";
    $tpl1->ICONE_ARQUIVO="../imagens/icones/geral/add.png";
    $tpl1->ICONE_DICA="Cadastrar novo consumidor ";
    $tpl1->ICONE_ALTERNATIVO="CAD";
    $tpl1->ICONE_ID="cad_consumidor";
    $tpl1->ICONE_AOCLICAR="";
    $tpl1->block("BLOCK_ICONE_TAMANHOPADRAO");
    $tpl1->block("BLOCK_ICONE");
    $tpl1->block("BLOCK_CONTEUDO");

    $tpl1->block("BLOCK_ITEM");



    //Entragar no Cliente
    if ($fazentregas==1) {


        $tpl1->TR_ID="linha_entrega";
        $tpl1->CAMPO_QTD_CARACTERES = "";
        $tpl1->TITULO = "Entregar no cliente";
        $tpl1->ASTERISCO = "";
        $tpl1->block("BLOCK_TITULO");

        $tpl1->SELECT2_NOME = "entrega";
        $tpl1->SELECT2_DESABILITADO = "";
        $tpl1->SELECT2_OBRIGATORIO = " required ";
        $tpl1->SELECT2_FOCO = "";
        if ($passo==2) $tpl1->SELECT2_DESABILITADO = " disabled ";
        else $tpl1->SELECT2_DESABILITADO = "  ";
        $tpl1->SELECT2_AOTROCAR = "verifica_entrega(this.value)";
        $tpl1->OPTION2_VALOR = "";
        $tpl1->OPTION2_NOME = "Selecione";
        if ($entrega=="") $tpl1->OPTION2_SELECIONADO = " selected "; else  $tpl1->OPTION2_SELECIONADO = "  ";
        $tpl1->block("BLOCK_SELECT2_OPTION");
        $tpl1->OPTION2_VALOR = "0";
        $tpl1->OPTION2_NOME = "Não";
        if ($entrega==0) $tpl1->OPTION2_SELECIONADO = " selected "; else  $tpl1->OPTION2_SELECIONADO = "  ";
        $tpl1->block("BLOCK_SELECT2_OPTION");
        $tpl1->OPTION2_VALOR = "1";
        $tpl1->OPTION2_NOME = "Sim";
        if ($entrega==1) $tpl1->OPTION2_SELECIONADO = " selected "; else  $tpl1->OPTION2_SELECIONADO = "  ";
        $tpl1->block("BLOCK_SELECT2_OPTION");
        $tpl1->block("BLOCK_SELECT2");
        $tpl1->block("BLOCK_CONTEUDO");

        $tpl1->block("BLOCK_ITEM");

        //Data da entrega
        $tpl1->TR_ID="linha_dataentrega";
        $tpl1->TITULO = "Data da entrega";
        $tpl1->ASTERISCO = "";
        $tpl1->block("BLOCK_TITULO");
        $tpl1->CAMPO_OBRIGATORIO = " required ";
        $tpl1->CAMPO_TIPO = "date";
        $tpl1->CAMPO_ESTILO = "width:140px;";
        $tpl1->CAMPO_NOME = "dataentrega";
        $tpl1->CAMPO_TAMANHO = "";
        $tpl1->CAMPO_QTD_CARACTERES = "";
        $tpl1->CAMPO_FOCO = " ";
        $tpl1->CAMPO_VALOR = "$entrega_dataentrega";
        if ($passo==2) {
            $tpl1->CAMPO_DESABILITADO = " disabled ";
        } else {
            $tpl1->CAMPO_DESABILITADO = "  ";
        }
        $tpl1->CAMPO_ONKEYPRESS = "";
        $tpl1->CAMPO_DICA = "";
        $tpl1->CAMPO_ONKEYUP = "";
        $tpl1->CAMPO_ONKEYDOWN = "";
        $tpl1->CAMPO_ONBLUR = ""; 
        $tpl1->CAMPO_ONFOCUS = "";
        $tpl1->block("BLOCK_CAMPO");
        $tpl1->block("BLOCK_CONTEUDO");
        $tpl1->block("BLOCK_ITEM");      

       
        //Endereço
        $tpl1->TR_ID="linha_endereco";
        $tpl1->TITULO = "Endereço";
        $tpl1->ASTERISCO = "";
        $tpl1->block("BLOCK_TITULO");
        //endereco
        $tpl1->CAMPO_OBRIGATORIO = "  ";
        $tpl1->CAMPO_TIPO = "text";
        $tpl1->CAMPO_ESTILO = "width:320px;";
        $tpl1->CAMPO_NOME = "endereco";
        $tpl1->CAMPO_TAMANHO = "";
        $tpl1->CAMPO_QTD_CARACTERES = "";
        $tpl1->CAMPO_FOCO = " ";
        $tpl1->CAMPO_VALOR = "$entrega_endereco";
        if ($passo==2) {
            $tpl1->CAMPO_DESABILITADO = " disabled ";
        } else {
            $tpl1->CAMPO_DESABILITADO = "  ";
        }
        $tpl1->CAMPO_ONKEYPRESS = "";
        $tpl1->CAMPO_DICA = "";
        $tpl1->CAMPO_ONKEYUP = "";
        $tpl1->CAMPO_ONKEYDOWN = "";
        $tpl1->CAMPO_ONBLUR = ""; 
        $tpl1->CAMPO_ONFOCUS = "";
        $tpl1->block("BLOCK_CAMPO");
        //endereco numero
        $tpl1->CAMPO_OBRIGATORIO = "  ";
        $tpl1->CAMPO_TIPO = "text";
        $tpl1->CAMPO_ESTILO = "width:70px;";
        $tpl1->CAMPO_NOME = "endereco_numero";
        $tpl1->CAMPO_TAMANHO = "";
        $tpl1->CAMPO_QTD_CARACTERES = "";
        $tpl1->CAMPO_FOCO = " ";
        $tpl1->CAMPO_VALOR = "$entrega_endereco_numero";
        if ($passo==2) {
            $tpl1->CAMPO_DESABILITADO = " disabled ";
        } else {
            $tpl1->CAMPO_DESABILITADO = "  ";
        }
        $tpl1->CAMPO_ONKEYPRESS = "";
        $tpl1->CAMPO_DICA = "nº";
        $tpl1->CAMPO_ONKEYUP = "";
        $tpl1->CAMPO_ONKEYDOWN = "";
        $tpl1->CAMPO_ONBLUR = ""; 
        $tpl1->CAMPO_ONFOCUS = "";
        $tpl1->block("BLOCK_CAMPO");
        $tpl1->block("BLOCK_CONTEUDO");
        $tpl1->block("BLOCK_CONTEUDO");
        $tpl1->block("BLOCK_ITEM");

        //Bairro
        $tpl1->TR_ID="linha_bairro";
        $tpl1->TITULO = "Bairro";
        $tpl1->ASTERISCO = "";
        $tpl1->block("BLOCK_TITULO");
        $tpl1->CAMPO_OBRIGATORIO = "  ";
        $tpl1->CAMPO_TIPO = "text";
        $tpl1->CAMPO_ESTILO = "width:220px;";
        $tpl1->CAMPO_NOME = "bairro";
        $tpl1->CAMPO_TAMANHO = "";
        $tpl1->CAMPO_QTD_CARACTERES = "";
        $tpl1->CAMPO_FOCO = " ";
        $tpl1->CAMPO_VALOR = "$entrega_bairro";
        if ($passo==2) {
            $tpl1->CAMPO_DESABILITADO = " disabled ";
        } else {
            $tpl1->CAMPO_DESABILITADO = "  ";
        }
        $tpl1->CAMPO_ONKEYPRESS = "";
        $tpl1->CAMPO_DICA = "";
        $tpl1->CAMPO_ONKEYUP = "";
        $tpl1->CAMPO_ONKEYDOWN = "";
        $tpl1->CAMPO_ONBLUR = ""; 
        $tpl1->CAMPO_ONFOCUS = "";
        $tpl1->block("BLOCK_CAMPO");
        $tpl1->block("BLOCK_CONTEUDO");
        $tpl1->block("BLOCK_ITEM");


        //Cidade
        //pais
        $tpl1->TR_ID="linha_cidade";
        $tpl1->TITULO = "Cidade";
        $tpl1->ASTERISCO = "";
        $tpl1->block("BLOCK_TITULO");
        $tpl1->SELECT2_NOME = "pais";
        $tpl1->SELECT2_DESABILITADO = "";
        $tpl1->SELECT2_OBRIGATORIO = " required ";
        $tpl1->SELECT2_FOCO = "";
        if ($passo != 1) {
            $tpl1->SELECT2_DESABILITADO = " disabled ";
        } else {
            $tpl1->SELECT2_DESABILITADO = " ";
        }
        $sql = "SELECT * from paises";
        $pais=1; //Brasil
        if (!$query = mysql_query($sql)) die("Erro PAIS: " . mysql_error());
        while ($dados = mysql_fetch_array($query)) {
            $tpl1->OPTION2_VALOR = $dados["pai_codigo"];
            $tpl1->OPTION2_NOME = $dados["pai_nome"];
            if ($operacao==2) { //Se for edição pega do banco o pais
                if ($pais == $dados["pai_codigo"]) $tpl1->OPTION2_SELECIONADO = " selected ";
                else $tpl1->OPTION2_SELECIONADO = " ";
            } else { //Se for um consumidor novo durante uma nova venda
                if ($dados["pai_codigo"]==1) $tpl1->OPTION2_SELECIONADO = " selected ";
                else $tpl1->OPTION2_SELECIONADO = "  ";

            }
            $tpl1->block("BLOCK_SELECT2_OPTION");
        }

        $tpl1->SELECT2_AOTROCAR = "pupula_estados()";
        $tpl1->block("BLOCK_SELECT2");
        $tpl1->block("BLOCK_CONTEUDO");
        //estado
        $tpl1->SELECT2_NOME = "estado";
        $tpl1->SELECT2_DESABILITADO = "";
        $tpl1->SELECT2_OBRIGATORIO = " required ";
        $tpl1->SELECT2_FOCO = "";
        if ($passo != 1) {
            $tpl1->SELECT2_DESABILITADO = " disabled ";
        } else {
            $tpl1->SELECT2_DESABILITADO = " ";
        }
        if ($entrega==1) {
            $sql = "SELECT * from estados WHERE est_pais=$entrega_pais";
        } else {
            $sql = "SELECT * from estados WHERE est_pais=$usuario_quiosque_pais";
        }
        if (!$query = mysql_query($sql)) die("Erro ESTADO: " . mysql_error());
        while ($dados = mysql_fetch_array($query)) {
            $tpl1->OPTION2_VALOR = $dados["est_codigo"];
            $tpl1->OPTION2_NOME = $dados["est_sigla"];
            if ($operacao==2) { //Se for edição pega do banco o estado
                if ($entrega_estado == $dados["est_codigo"]) $tpl1->OPTION2_SELECIONADO = " selected ";
                else $tpl1->OPTION2_SELECIONADO = " ";
            } else { //Se for um consumidor novo durante uma nova venda
                if ($dados["est_codigo"]==$usuario_quiosque_estado) $tpl1->OPTION2_SELECIONADO = " selected ";
                else $tpl1->OPTION2_SELECIONADO = "  ";
                $estado=$usuario_quiosque_estado; 
            }            
            $tpl1->block("BLOCK_SELECT2_OPTION");
        }
        $tpl1->SELECT2_AOTROCAR = "popula_cidades(this.value)";
        $tpl1->block("BLOCK_SELECT2");
        $tpl1->block("BLOCK_CONTEUDO");
        //cidade
        $tpl1->SELECT2_NOME = "cidade";
        $tpl1->SELECT2_DESABILITADO = "";
        $tpl1->SELECT2_OBRIGATORIO = " required ";
        $tpl1->SELECT2_FOCO = "";
        if ($passo != 1) {
            $tpl1->SELECT2_DESABILITADO = " disabled ";
        } else {
            $tpl1->SELECT2_DESABILITADO = " ";
        }
        if ($entrega==1) {
            $sql = "SELECT * from cidades WHERE cid_estado=$entrega_estado";
            
        } else {
            $sql = "SELECT * from cidades WHERE cid_estado=$usuario_quiosque_estado";
        }
        if (!$query = mysql_query($sql)) die("Erro CIDADE: " . mysql_error());
        while ($dados = mysql_fetch_array($query)) {
            $tpl1->OPTION2_VALOR = $dados["cid_codigo"];
            $tpl1->OPTION2_NOME = $dados["cid_nome"];
            if ($operacao==2) { //Se for edição pega do banco o estado
                if ($entrega_cidade == $dados["cid_codigo"]) $tpl1->OPTION2_SELECIONADO = " selected ";
                else $tpl1->OPTION2_SELECIONADO = " ";
            } else { //Se for um consumidor novo durante uma nova venda
                if ($dados["cid_codigo"]==$usuario_quiosque_cidade) $tpl1->OPTION2_SELECIONADO = " selected ";
                else $tpl1->OPTION2_SELECIONADO = "  ";
                $cidade=$usuario_quiosque_cidade;
            }            
            $tpl1->block("BLOCK_SELECT2_OPTION");
        }
         $tpl1->SELECT2_AOTROCAR = "";
        $tpl1->block("BLOCK_SELECT2");
        $tpl1->block("BLOCK_CONTEUDO");
        $tpl1->block("BLOCK_ITEM");


        //Telefone 1
        $tpl1->TR_ID="linha_fone1";
        $tpl1->TITULO = "Telefone 1";
        $tpl1->ASTERISCO = "";
        $tpl1->block("BLOCK_TITULO");
        $tpl1->CAMPO_OBRIGATORIO = "  ";
        $tpl1->CAMPO_TIPO = "text";
        $tpl1->CAMPO_ESTILO = "width:120px;";
        $tpl1->CAMPO_NOME = "fone1";
        $tpl1->CAMPO_TAMANHO = "";
        $tpl1->CAMPO_QTD_CARACTERES = "";
        $tpl1->CAMPO_FOCO = " ";
        $tpl1->CAMPO_VALOR = "$entrega_fone1";
        if ($passo==2) {
            $tpl1->CAMPO_DESABILITADO = " disabled ";
        } else {
            $tpl1->CAMPO_DESABILITADO = "  ";
        }
        $tpl1->CAMPO_ONKEYPRESS = "";
        $tpl1->CAMPO_DICA = "";
        $tpl1->CAMPO_ONKEYUP = "mascara_telefone1(this.value)";
        $tpl1->CAMPO_ONKEYDOWN = "";
        $tpl1->CAMPO_ONBLUR = "verifica_telefone1(this.value)"; 
        $tpl1->CAMPO_ONFOCUS = "";
        $tpl1->block("BLOCK_CAMPO");
        $tpl1->block("BLOCK_CONTEUDO");
        $tpl1->block("BLOCK_ITEM");  

        //Telefone 2
        $tpl1->TR_ID="linha_fone2";
        $tpl1->TITULO = "Telefone 2";
        $tpl1->ASTERISCO = "";
        $tpl1->block("BLOCK_TITULO");
        $tpl1->CAMPO_OBRIGATORIO = "  ";
        $tpl1->CAMPO_TIPO = "text";
        $tpl1->CAMPO_ESTILO = "width:120px;";
        $tpl1->CAMPO_NOME = "fone2";
        $tpl1->CAMPO_TAMANHO = "";
        $tpl1->CAMPO_QTD_CARACTERES = "";
        $tpl1->CAMPO_FOCO = " ";
        $tpl1->CAMPO_VALOR = "$entrega_fone2";
        if ($passo==2) {
            $tpl1->CAMPO_DESABILITADO = " disabled ";
        } else {
            $tpl1->CAMPO_DESABILITADO = "  ";
        }
        $tpl1->CAMPO_ONKEYPRESS = "";
        $tpl1->CAMPO_DICA = "";
        $tpl1->CAMPO_ONKEYUP = "mascara_telefone2(this.value)";
        $tpl1->CAMPO_ONKEYDOWN = "";
        $tpl1->CAMPO_ONBLUR = "verifica_telefone2(this.value)"; 
        $tpl1->CAMPO_ONFOCUS = "";
        $tpl1->block("BLOCK_CAMPO");
        $tpl1->block("BLOCK_CONTEUDO");
        $tpl1->block("BLOCK_ITEM");        

    }

    //OBS
    if ($obsnavenda==1) {
        $tpl1->TR_ID="linha_obs";
        $tpl1->TITULO = "Observação";
        $tpl1->ASTERISCO = "";
        $tpl1->block("BLOCK_TITULO");
        $tpl1->CAMPO_TIPO = "text";
        $tpl1->CAMPO_ESTILO = "width:520px;";
        $tpl1->CAMPO_NOME = "obs";
        $tpl1->CAMPO_TAMANHO = "";
        $tpl1->CAMPO_QTD_CARACTERES = "";
        $tpl1->CAMPO_FOCO = " ";
        $tpl1->CAMPO_VALOR = "$obs";
        if ($passo==2) {
            $tpl1->CAMPO_DESABILITADO = " disabled ";
        } else {
            $tpl1->CAMPO_DESABILITADO = "  ";
        }
        $tpl1->CAMPO_OBRIGATORIO = "  ";
        $tpl1->CAMPO_ONKEYPRESS = "";
        $tpl1->CAMPO_DICA = "";
        $tpl1->CAMPO_ONKEYUP = "";
        $tpl1->CAMPO_ONKEYDOWN = "";
        $tpl1->CAMPO_ONBLUR = ""; 
        $tpl1->CAMPO_ONFOCUS = "";
        $tpl1->block("BLOCK_CAMPO");
    }
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");


}


//Se o tipo de saida for Devolução
if ($tiposaida == 3) {

    //Motivo
    $tpl1->TR_ID="linha_motivo";
    $tpl1->TITULO = "Motivo";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->SELECT_NOME = "motivo";
    $tpl1->SELECT_OBRIGATORIO = " required ";
    $tpl1->SELECT_FOCO = "  ";
    if ($passo == 2) {
        $tpl1->SELECT_DESABILITADO = " disabled ";
    } else {
        $tpl1->SELECT_DESABILITADO = " ";
    }
    $sql = "SELECT saimot_codigo,saimot_nome FROM saidas_motivo ORDER BY saimot_codigo ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro m: " . mysql_error());
    $tpl1->OPTION_VALOR = "";
    $tpl1->OPTION_NOME = "Selecione";
    $tpl1->block("BLOCK_SELECT_OPTION");
    while ($dados = mysql_fetch_array($query)) {
        $tpl1->OPTION_VALOR = $dados["saimot_codigo"];
        $tpl1->OPTION_NOME = $dados["saimot_nome"];
        if ($motivo == $dados["saimot_codigo"]) {
            $tpl1->OPTION_SELECIONADO = " selected ";
        } else {
            $tpl1->OPTION_SELECIONADO = " ";
        }
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");

    //Descri��o
    $tpl1->TR_ID="linha_descricao";
    $tpl1->TITULO = "Descrição";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->TEXTAREA_NOME = "descricao";
    $tpl1->TEXTAREA_TAMANHO = "55";
    $tpl1->TEXTAREA_TEXTO = $descricao;
    if ($passo == 2) {
        $tpl1->TEXTAREA_DESABILITADO = " disabled ";
    } else {
        $tpl1->TEXTAREA_DESABILITADO = " ";
    }
    $tpl1->block("BLOCK_TEXTAREA");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");

    //Alguns campos est�o desabilitados, portando deve-se enviar atraves de campos ocultos
    $tpl1->CAMPOOCULTO_NOME = "consumidor";
    $tpl1->CAMPOOCULTO_VALOR = "$consumidor";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "tiposaida";
    $tpl1->CAMPOOCULTO_VALOR = "$tiposaida";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "tiposaida";
    $tpl1->CAMPOOCULTO_VALOR = "$tiposaida";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "id2";
    $tpl1->CAMPOOCULTO_VALOR = "$id";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
}

if ($passo == 2) {
    
    $tpl1->TR_ID="";
   
    //Verifica se o consumidor possui vendas incompleta
    if (($identificacaoconsumidorvenda==1)||($identificacaoconsumidorvenda==2)) {


        if (($passo==2)&&($ignorar_vendas_incompletas!=1)&&($tiposaida!=3)&&($produto=="")) { // Se for uma devolução, então não realizar essa verificação
            $sql4="SELECT * from saidas  WHERE sai_status=2 and sai_consumidor= $consumidor and sai_codigo not in ($saida)";
            if (!$query4 = mysql_query($sql4)) die("Erro 4:" . mysql_error());
            $linhas4 = mysql_num_rows($query4);

            //print_r($_REQUEST);

            if (($linhas4>0)&&($operacao==1)&&($consumidor<>0)) { 
                $tpl = new Template("templates/notificacao.html");
                $tpl->ICONES = $icones;
                //$tpl->MOTIVO_COMPLEMENTO = "";
                $tpl->block("BLOCK_ATENCAO");
                $tpl->LINK = "saidas_cadastrar.php?codigo=$saida&operacao=$operacao&tiposaida=1&id=$id&consumidor=$consumidor&passo=2&usacomanda=$usacomanda&ignorar_vendas_incompletas=1&fone=$consumidor_fone&ignorar_vendas_areceber=$ignorar_vendas_areceber&entrega=$entrega&saida=$saida";
                $vendas_incompletas="<br> <i>";
                while ($dados4=  mysql_fetch_assoc($query4)) {
                    $vendainc_codigo=$dados4["sai_codigo"];
                    $vendainc_data=  converte_data($dados4["sai_datacadastro"]);
                    $vendainc_hora=  converte_hora($dados4["sai_horacadastro"]);
                    $vendas_incompletas=$vendas_incompletas."Nº $vendainc_codigo, Data: $vendainc_data $vendainc_hora<br>";
                }
                $vendas_incompletas=$vendas_incompletas."</i><br>";
                $tpl->MOTIVO = "
                    Este consumidor possui vendas incompletas!<br>
                    $vendas_incompletas
                ";
                $tpl->block("BLOCK_MOTIVO");
                $tpl->PERGUNTA = "Deseja continuar assim mesmo?";
                $tpl->block("BLOCK_PERGUNTA");
                $tpl->NAO_LINK = "saidas.php";
                $tpl->block("BLOCK_BOTAO_NAO_LINK");
                $tpl->block("BLOCK_BOTAO_SIMNAO");
                $tpl->show();
                exit;
            }
        }

        //Verifica se o consumidor possui vendas a receber
        if (($passo==2)&&($ignorar_vendas_areceber!=1)&&($tiposaida!=3)&&($produto=="")) { // Se for uma devolução, então não realizar essa verificação
            $sql4="SELECT * from saidas  WHERE sai_consumidor=$consumidor and sai_areceber=1 and sai_areceberquitado=0";
            if (!$query4 = mysql_query($sql4)) die("Erro 4:" . mysql_error());
            $linhas4 = mysql_num_rows($query4);

            //print_r($_REQUEST);

            if (($linhas4>0)&&($operacao==1)&&($consumidor<>0)) { 
                $tpl = new Template("templates/notificacao.html");
                $tpl->ICONES = $icones;
                //$tpl->MOTIVO_COMPLEMENTO = "";
                $tpl->block("BLOCK_ATENCAO");
                $tpl->LINK = "saidas_cadastrar.php?codigo=$saida&operacao=$operacao&tiposaida=1&id=$id&consumidor=$consumidor&passo=2&usacomanda=$usacomanda&ignorar_vendas_incompletas=1&fone=$consumidor_fone&ignorar_vendas_areceber=1&entrega=$entrega&saida=$saida";
                $listinha="<br> <i>";
                while ($dados4=  mysql_fetch_assoc($query4)) {
                    $listinha_codigo=$dados4["sai_codigo"];
                    $listinha_data=  converte_data($dados4["sai_datacadastro"]);
                    $listinha_hora=  converte_hora($dados4["sai_horacadastro"]);
                    $listinha=$listinha."Nº $listinha_codigo, Data: $listinha_data $listinha_hora<br>";
                }
                $listinha=$listinha."</i><br>";
                $tpl->MOTIVO = "
                    Este consumidor possui vendas a serem acertadas!<br>
                    $listinha
                ";
                $tpl->block("BLOCK_MOTIVO");
                $tpl->PERGUNTA = "Deseja continuar assim mesmo?";
                $tpl->block("BLOCK_PERGUNTA");
                $tpl->NAO_LINK = "saidas_deletar.php?codigo=$saida&tiposaida=1";
                $tpl->block("BLOCK_BOTAO_NAO_LINK");
                $tpl->block("BLOCK_BOTAO_SIMNAO");
                $tpl->show();
                exit;
            }
        }
    }


    //Etiqueta
    $tpl1->TR_ID="linha_codigobarrasinterno";
    $tpl1->CAMPO_DICA = "";
    $tpl1->CAMPO_QTD_CARACTERES = "14";
    $tpl1->TITULO = "Código de Barras Interno";
    $tpl1->ASTERISCO = "";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "etiqueta";
    $tpl1->CAMPO_TAMANHO = "15";
    $tpl1->CAMPO_FOCO = " ";
    $tpl1->CAMPO_VALOR = "";
    $tpl1->CAMPO_DESABILITADO = "";
    $tpl1->CAMPO_OBRIGATORIO = " ";
    $tpl1->CAMPO_ONKEYUP = "valida_etiqueta(this)";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");

    //Etiqueta EAN
    $tpl1->TR_ID="linha_ean";
    $tpl1->CAMPO_QTD_CARACTERES = "13";
    $tpl1->TITULO = "Código de Barras EAN";
    $tpl1->ASTERISCO = "";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "etiqueta2";
    $tpl1->CAMPO_TAMANHO = "15";
    $tpl1->CAMPO_FOCO = "  ";
    $tpl1->CAMPO_VALOR = "";
    $tpl1->CAMPO_DESABILITADO = "";
    $tpl1->CAMPO_OBRIGATORIO = " ";
    $tpl1->CAMPO_ONKEYUP = "valida_etiqueta2(this)";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->CAMPO_ONBLUR = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");


    

    //Produto
    $tpl1->TR_ID="linha_produto";
    $tpl1->TITULO = "Produto";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->ASTERISCO = "";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "produto_referencia";
    $tpl1->CAMPO_TAMANHO = "15";
    $tpl1->CAMPO_VALOR = "";
    $tpl1->CAMPO_FOCO = "";
    $tpl1->CAMPO_DESABILITADO = "";
    $tpl1->CAMPO_QTD_CARACTERES = "30";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_ONKEYUP = "";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONBLUR = "foco_produto_referencia()";
    $tpl1->CAMPO_ESTILO = " ";
    $tpl1->CAMPO_OBRIGATORIO = " ";
    $tpl1->block("BLOCK_CAMPO");    
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->SELECT_NOME = "produto";    
    $tpl1->SELECT_AOTROCAR = "selecionar_produto(this.value) ";
    $tpl1->SELECT_OBRIGATORIO = " required ";
    $tpl1->SELECT_FOCO = "  ";
    $tpl1->SELECT_DESABILITADO = "  ";
    $tpl1->SELECT_CLASSE = " width:300px; ";
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_CONTEUDO");
    if ($permiteedicaoreferencianavenda==1) {
        $tpl1->ICONE_AOCLICAR = "atualizar_referencia();";
        $tpl1->ICONE_DESTINO = "#";
        $tpl1->ICONE_ID = "atualizareferencia";
        $tpl1->ICONE_ALTERNATIVO = "atualizareferencia";
        $tpl1->ICONE_DICA = "Atualizar referencia do produto";
        $tpl1->ICONE_ARQUIVO = "$icones"."editar.png";
        $tpl1->ICONE_TAMANHO = "12px";
        //$tpl1->block("BLOCK_ICONE_TAMANHOPADRAO");
        $tpl1->block("BLOCK_ICONE");
        $tpl1->block("BLOCK_CONTEUDO");
    }
    //Campo oculto que informa se o produto selecionado é estoque infinito
    $tpl1->block("BLOCK_ITEM");
    $tpl1->CAMPO_TIPO = "hidden";
    $tpl1->CAMPO_NOME = "produto_controlar_estoque";
    $tpl1->CAMPO_VALOR = "";
    $tpl1->block("BLOCK_CAMPO");    
    $tpl1->block("BLOCK_CONTEUDO");    



    //Fornecedor
    $tpl1->TR_ID="linha_fornecedor";
    $tpl1->TITULO = "Fornecedor";
    $tpl1->SELECT_CLASSE = " width:210px; ";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->SELECT_NOME = "fornecedor";
    $tpl1->SELECT_AOTROCAR = "selecionar_fornecedor(this.value) ";
    $tpl1->SELECT_OBRIGATORIO = "  ";
    $tpl1->SELECT_FOCO = "  ";
    $tpl1->SELECT_DESABILITADO = "  ";
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");

    //Lote
    $tpl1->TR_ID="linha_lote";
    $tpl1->TITULO = "Lote";
    $tpl1->SELECT_CLASSE = " width:100px; ";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->SELECT_NOME = "lote";
    $tpl1->SELECT_OBRIGATORIO = " required ";
    $tpl1->SELECT_FOCO = "  ";
    $tpl1->SELECT_DESABILITADO = "  ";
    $tpl1->SELECT_AOTROCAR = "popula_lote_oculto(this.value);  selecionar_lote(this.value)";
    $tpl1->SPAN2_NOME = "prateleira";
    $tpl1->SPAN2_VALOR = "";
    $tpl1->block("BLOCK_SPAN2");
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");



    //Porção
    $tpl1->TR_ID="linha_porcoes";
    $tpl1->TITULO = "Porção";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->SELECT_CLASSE = " width:180px; ";
    $tpl1->SELECT_NOME = "porcao";
    $tpl1->SELECT_OBRIGATORIO = "  ";
    $tpl1->SELECT_FOCO = "  ";
    $tpl1->SELECT_DESABILITADO = "  ";
    $tpl1->SELECT_AOTROCAR = "selecionar_porcoes(this.value);";
    $tpl1->SPAN2_NOME = "porcao_qtd_label";
    $tpl1->SPAN2_VALOR = "";
    $tpl1->block("BLOCK_SPAN2");
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");
    //oculto
    $tpl1->CAMPOOCULTO_NOME = "porcao_oculto";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "porcao_oculto_custo";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");

    //Porção Quantidade
    $tpl1->TR_ID="linha_porcoes_qtd";
    $tpl1->CAMPO_QTD_CARACTERES = "9";
    $tpl1->TITULO = "Porção Quantidade";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->ASTERISCO = "";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "porcao_qtd";
    $tpl1->CAMPO_TAMANHO = "9";
    $tpl1->CAMPO_VALOR = "";
    $tpl1->CAMPO_FOCO = " ";
    $tpl1->CAMPO_DESABILITADO = "";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_ONKEYUP = "porcoesqtd();";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->CAMPO_OBRIGATORIO = " ";
    $tpl1->CAMPO_ONBLUR = "";
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");

    //Quantidade
    $tpl1->TR_ID="";
    $tpl1->CAMPO_QTD_CARACTERES = "9";
    $tpl1->TITULO = "Quantidade";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->ASTERISCO = "";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "qtd";
    $tpl1->CAMPO_TAMANHO = "9";
    $tpl1->CAMPO_VALOR = "";
    $tpl1->CAMPO_FOCO = "";
    $tpl1->CAMPO_DESABILITADO = "";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_ONKEYUP = "saidas_qtd()";
    $tpl1->CAMPO_ONKEYDOWN = "pesoqtd()";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->CAMPO_OBRIGATORIO = "required ";
    //Tipo Contagem
    $tpl1->SPAN_NOME = "tipocontagem";
    $tpl1->SPAN_VALOR = "";
    $tpl1->SPAN_CLASS = " negrito ";
    $tpl1->block("BLOCK_SPAN");
    //Quantidade atual no estoque
    $tpl1->SPAN_NOME = "qtdnoestoque";
    $tpl1->SPAN_VALOR = "$qtdnoestoque";
    $tpl1->SPAN_CLASS = "  ";
    $tpl1->block("BLOCK_SPAN");
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");
    $tpl1->CAMPOOCULTO_NOME = "qtd2";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");

    //Valor Unitário
    $tpl1->CAMPO_QTD_CARACTERES = "";
    $tpl1->TITULO = "Valor Unitário";
    $tpl1->ASTERISCO = "";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "valuni";
    $tpl1->CAMPO_TAMANHO = "28";
    $tpl1->CAMPO_FOCO = "";
    $tpl1->CAMPO_VALOR = "";
    $tpl1->CAMPO_DESABILITADO = " disabled ";
    $tpl1->CAMPO_OBRIGATORIO = "";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_ONKEYUP = "";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");
    //usado para receber o valor unitário padrão do produto
    $tpl1->CAMPOOCULTO_NOME = "valuni2";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    //Usado para receber o valor unitário considerando a escolha da porção
    $tpl1->CAMPOOCULTO_NOME = "valuni3";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");


    //Valor Total
    $tpl1->CAMPO_QTD_CARACTERES = "";
    $tpl1->TITULO = "Valor Total";
    $tpl1->ASTERISCO = "";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "valtot";
    $tpl1->CAMPO_TAMANHO = "28";
    $tpl1->CAMPO_FOCO = "";
    $tpl1->CAMPO_VALOR = "";
    $tpl1->CAMPO_DESABILITADO = " disabled ";
    $tpl1->CAMPO_OBRIGATORIO = "";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_ONKEYUP = "";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");

    //Como o campo está desabilitado é necessário criar um campo oculto. Este é populado via javascript
    $tpl1->CAMPOOCULTO_NOME = "valtot";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");

    $tpl1->CAMPOOCULTO_NOME = "qtd_custo";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "qtdnoestoque";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "consumidor";
    $tpl1->CAMPOOCULTO_VALOR = "$consumidor";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "tiposaida";
    $tpl1->CAMPOOCULTO_VALOR = "$tiposaida";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "produto2";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "lote2";
    $tpl1->CAMPOOCULTO_VALOR = "";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "motivo";
    $tpl1->CAMPOOCULTO_VALOR = "$motivo";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
    $tpl1->CAMPOOCULTO_NOME = "descricao";
    $tpl1->CAMPOOCULTO_VALOR = "$descricao";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");



    //LISTAGEM DO CARRINHO
    $tpl1->LISTA_GET_CONSUMIDOR = $consumidor;
    $tpl1->LISTA_GET_TIPOSAIDA = $tiposaida;
    $tpl1->LISTA_GET_SAIDA = $saida;
    $tpl1->LISTA_GET_PASSO = $passo;
    $sql_lista = "
    SELECT 
        pro_nome, pes_nome, saipro_lote, saipro_quantidade, saipro_valorunitario, saipro_valortotal,saipro_codigo,pro_codigo,saipro_codigo,saipro_porcao,saipro_porcao_quantidade,propor_nome,protip_sigla,pro_tipocontagem,pro_referencia, pro_tamanho,pro_cor,pro_descricao,saipro_produto,saipro_porcao_quantidade,saipro_loteconjunto,saipro_itemconjunto,pro_controlarestoque
    FROM 
        saidas_produtos
        JOIN produtos ON (saipro_produto=pro_codigo)    
        JOIN produtos_tipo ON (pro_tipocontagem=protip_codigo)
        LEFT JOIN produtos_porcoes ON (saipro_porcao=propor_codigo)
        left JOIN entradas ON (saipro_lote=ent_codigo)
        left JOIN pessoas ON (ent_fornecedor=pes_codigo)
    WHERE
        saipro_saida=$saida
    ORDER BY
        saipro_codigo DESC
    ";
    $query_lista = mysql_query($sql_lista);
    if (!$query_lista)
        die("Erro n: " . mysql_error());
    $linhas_lista = mysql_num_rows($query_lista);
    
    //CABECALHO
    if ($usavendaporcoes==1) $tpl1->block("BLOCK_LISTA_PORCAO_CABECALHO");
    if ($usaestoque==1) $tpl1->block("BLOCK_LISTA_CABECALHO_LOTE");


    if ($linhas_lista == 0) {
        $tpl1->block("BLOCK_LISTA_NADA");
        $tpl1->SALVAR_DESABILIDADO = " disabled ";
        $tpl1->FORM_LINK = "";

    } else {
        $num = 0;
        $total_geral = 0;
        $temdevolucoes=0;
        while ($dados_lista = mysql_fetch_array($query_lista)) {
            $num++;
            $tpl1->LISTA_GET_SAIPRO = $dados_lista["saipro_codigo"];
            $tpl1->LISTA_NUM = $dados_lista["saipro_codigo"];
            $itemvenda=$dados_lista["saipro_codigo"];
            $itemconjunto=$dados_lista["saipro_itemconjunto"];
            $prod_nome=$dados_lista["pro_nome"];
            $prod_referencia=$dados_lista["pro_referencia"];
            $prod_tamanho=$dados_lista["pro_tamanho"];
            $prod_cor=$dados_lista["pro_cor"];
            $prod_descricao=$dados_lista["pro_descricao"];
            $lote=$dados_lista["saipro_lote"];
            $produto_codigo=$dados_lista["saipro_produto"];
            $loteconjunto=$dados_lista["saipro_loteconjunto"];
            $este_produto_controlar_estoque=$dados_lista["pro_controlarestoque"];
            $nome2="$prod_nome $prod_tamanho $prod_cor $prod_descricao";
            $numeroreferencia=$produto_codigo;
            if ($prod_referencia!="") $numeroreferencia.=" ($prod_referencia)";
            $tpl1->LISTA_PRODUTO_REFERENCIA = $numeroreferencia;
            $tpl1->LISTA_PRODUTO = $nome2;
            $tpl1->LISTA_PRODUTO_COD = $dados_lista["pro_codigo"];
            //$tpl1->LISTA_FORNECEDOR = $dados_lista["pes_nome"];
            if ($usaestoque==1) {
                if ($dados_lista["saipro_lote"]==0) $tpl1->LISTA_LOTE = "---";
                else $tpl1->LISTA_LOTE = $dados_lista["saipro_lote"];
                $tpl1->block("BLOCK_LISTA_LOTE");
            }
            $tipocontagem=$dados_lista["pro_tipocontagem"];
            if (($tipocontagem==2)||($tipocontagem==3)) {
                $tpl1->LISTA_QTD = number_format($dados_lista["saipro_quantidade"], 3, ',', '.');
            } else {
                $tpl1->LISTA_QTD = number_format($dados_lista["saipro_quantidade"], 0, '', '.');
            }
            $tpl1->LISTA_TIPOCONTAGEM = $dados_lista["protip_sigla"];
            $qtdporcao=$dados_lista["saipro_porcao_quantidade"];
            $porcaonome=$dados_lista["propor_nome"];
            if ($qtdporcao==0) $qtdporcao="---"; 
            if ($qtdporcao==0) $porcaonome="---";
            if ($usavendaporcoes==1) {
                $tpl1->IGNORARLOTE_ROWSPAN = "";
                $tpl1->LISTA_PORCAO_NOME = "$porcaonome";
                if (($qtdporcao-round($qtdporcao))==0) { //não é valor quebrado
                    $tpl1->LISTA_PORCAO_QTD = "$qtdporcao";
                } else {
                    $tpl1->LISTA_PORCAO_QTD = number_format($qtdporcao, 2, ',', '.');
                }
                $tpl1->block("BLOCK_LISTA_PORCAO_LINHA");
            } else {
                $tpl1->LISTA_PORCAO_QTD=" ";
            }
            $tpl1->LISTA_VALUNI = "R$ " . number_format($dados_lista["saipro_valorunitario"], 2, ',', '.');
            $tpl1->LISTA_VALTOT = "R$ " . number_format($dados_lista["saipro_valortotal"], 2, ',', '.');
            $tpl1->LISTA_TIPOPESSOA = $tipopessoa;

            $total = $dados_lista["saipro_valortotal"];
            $total_geral = $total_geral + $total;

            //Não é possíve excluir itens da venda caso tenha devoluções ou nota fiscal gerada.
            //Verifica se há produtos devolvidos deste produto e lote
            $sql18="SELECT * FROM saidas_devolucoes_produtos WHERE saidevpro_saida=$saida AND saidevpro_itemsaida=$itemvenda";
            if (!$query18 = mysql_query($sql18)) die("Erro CONSULTA DEVOLUCOES:" . mysql_error()."");
            $linhas18=mysql_num_rows($query18);

            if ($linhas18>=1) { $temdevolucao=1; $temdevolucoes=1;} else {$temdevolucao=0; }
            if ($temdevolucao==1) {
                $tpl1->EXCLUIR_MOTIVO="Este item possui devoluções vinculados!";
                $tpl1->block("BLOCK_LISTA_EXCLUIR_DESABILITADO");
            } else if (($itemvenda!=$itemconjunto)&&($este_produto_controlar_estoque==1)) {
                $tpl1->EXCLUIR_MOTIVO="Este item não pode ser excluido porque este é um lançamento automático gerado a partir da inclusão de um item anteior que tem uma porcão que precisou tirar um pouco de cada lote para completar a quantidade da porção!";
                $tpl1->block("BLOCK_LISTA_EXCLUIR_DESABILITADO");
            } else {
                $tpl1->block("BLOCK_LISTA_EXCLUIR");
            }

            $tpl1->block("BLOCK_LISTA");
        }
    }
    $tpl1->TOTAL_GERAL = "R$ " . number_format($total_geral, 2, ',', '.');
    if ($usavendaporcoes == 1) $tpl1->block("BLOCK_RODAPE_PORCOES");
    if ($usaestoque == 1) $tpl1->block("BLOCK_RODAPE_LOTE");
    $tpl1->block("BLOCK_LISTAGEM");



    
    //BOTÕES
    //Botão Finalizar/Avançar/Salvar
    if ($tiposaida == 1) {
        $tpl1->FORM_LINK = "saidas_cadastrar2.php?tiposai=$tiposaida";
        $tpl1->block("BLOCK_SALVAR_VENDA");
    } else if ($tiposaida == 3) {
        $tpl1->FORM_LINK = "saidas_cadastrar3.php?tiposai=3";
        $tpl1->block("BLOCK_SALVAR_DEVOLUCAO");
    }
    if ($temdevolucoes==1) {
        $tpl1->SALVAR_DESABILIDADO=" disabled ";
        $tpl1->FORM_LINK = "";
        $tpl1->TITULO="Não é possível editar a venda quando se tem devoluções!";
    }
    $tpl1->block("BLOCK_BOTOES_RODAPE_SALVAR");
    
    
    //Botão Cancelar
    if ($tiposaida == 1) {
        $tpl1->LINK_CANCELAR = "saidas.php";
    } else {
        $tpl1->LINK_CANCELAR = "saidas_devolucao.php";
    }
    $tpl1->block("BLOCK_BOTOES_RODAPE_CANCELAR");
    
    
    //Botão Eliminar Venda
    //Verificar se foi emitido nota e se possui devolucoes,  se sim então não permitir a eliminação da venda
    if ($usamodulofiscal==1) {
        $sql="SELECT * FROM nfe_vendas WHERE nfe_numero=$saida";
        if (!$query = mysql_query($sql)) die("Erro BOTÃO ELIMINAR VENDA 1: (((" . mysql_error().")))");
        $linhas = mysql_num_rows($query);
        if ($linhas==0) $temnota=0; else $temnota=1;
    }  else $temnota=0;
    //Verifica se há devoluções
    if ($usadevolucoes==1) {
        $sql="SELECT * FROM saidas_devolucoes WHERE saidev_saida=$saida";
        if (!$query = mysql_query($sql)) die("Erro BOTÃO ELIMINAR VENDA 2: (((" . mysql_error().")))");
        $linhas = mysql_num_rows($query);
        if ($linhas>0) $temdevolucao=1; else $temdevolucao=0;
    } else $temdevolucao=0;
    
    if ((($temdevolucao==0))&&($temnota==0)) {
        $tpl1->block("BLOCK_BOTOES_RODAPE_ELIMINAR");
        $tpl1->LINK_ELIMINAR = "saidas_deletar.php?codigo=$saida&tiposaida=$tiposaida";
    
    }
    
    //Verifica qual é o status atual da venda (venda completa ou incompleta)
    $sql = "SELECT sai_status FROM saidas WHERE sai_codigo=$saida";
    if (!$query=mysql_query($sql)) die("Erro de SQL:" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $status_venda = $dados["sai_status"];
    //echo "($status_venda)";
    
    
    //Botão Devoluções
    /*
    if (($usadevolucoes==1)&&($status_venda==1)) {
        $tpl1->LINK_DEVOLUCOES = "saidas_devolucoes.php?codigo=$saida";
        $sql12="SELECT count(saidev_numero) as qtd_devolucoes FROM saidas_devolucoes WHERE saidev_saida=$saida";
        if (!$query12=mysql_query($sql12)) die("Erro de SQL12:" . mysql_error());
        $dados12=mysql_fetch_assoc($query12);
        $qtd_devolucoes=$dados12["qtd_devolucoes"];
        if ($qtd_devolucoes>0) $tpl1->QTD_DEVOLUCOES=" ($qtd_devolucoes)"; else $tpl1->QTD_DEVOLUCOES="";
        $tpl1->block("BLOCK_BOTOES_RODAPE_DEVOLUCOES");  
    }
    */


    //Botão Pagamentos
    /*
    if (($areceber==1)&&($status_venda==1)&&($usapagamentosparciais==1)) {
        $tpl1->LINK_PAGAMENTOS = "saidas_pagamentos.php?saida=$saida";
        $sql12="SELECT count(saipag_codigo) as qtd_pagamentos FROM saidas_pagamentos WHERE saipag_saida=$saida";
        if (!$query12=mysql_query($sql12)) die("Erro de SQL13:" . mysql_error());
        $dados12=mysql_fetch_assoc($query12);
        $qtd_pagamentos=$dados12["qtd_pagamentos"];
        if ($qtd_pagamentos>0) $tpl1->QTD_PAGAMENTOS=" ($qtd_pagamentos)"; else $tpl1->QTD_PAGAMENTOS="";
        $tpl1->block("BLOCK_BOTOES_RODAPE_PAGAMENTOS");  
    }
    */
    
    //Botão Cancelar Nota
    /*
    //Se foi emitido nota fiscal e o usuário usa módulo fisca então pode cancelar a nota
    if (($temnota==1)&&($usamodulofiscal==1)) {
        $tpl1->LINK_CANCELARNOTA = "saidas_cancelarnota.php?codigo=$saida";
        $tpl1->block("BLOCK_BOTOES_RODAPE_CANCELARNOTA");  
    }
    */
    
    
    $tpl1->block("BLOCK_BOTOES_RODAPE");
}

//Botão Continuar
$tpl1->TR_ID="";
$tpl1->BOTAO_TIPO = "submit";
if ($passo == 2) {
    $tpl1->BOTAO_DESABILITADO = " disabled ";
    $tpl1->BOTAO_VALOR = "INCLUIR";
} else {
    //$tpl1->block("BLOCK_FOCO");
    $tpl1->BOTAO_VALOR = "CONTINUAR";
    $passo=2;
}
$tpl1->BOTAO_NOME = "botao_incluir";
$tpl1->BOTAO_FOCO = " ";
$tpl1->block("BLOCK_BOTAO1");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

if ($tiposaida == 3) {
    $passo = 2;
}
$valor2 = "R$ " . number_format($total_geral, 2, ',', '.');
$tpl1->VALBRU2 = $valor2;


//Passo
$tpl1->CAMPOOCULTO_NOME = "passo";
$tpl1->CAMPOOCULTO_VALOR = $passo;
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//Usa Comanda / Indentificador
$tpl1->CAMPOOCULTO_NOME = "usacomanda";
$tpl1->CAMPOOCULTO_VALOR = $usacomanda;
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//Usa Porcoes
$tpl1->CAMPOOCULTO_NOME = "usavendaporcoes";
$tpl1->CAMPOOCULTO_VALOR = $usavendaporcoes;
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->show();
?>
