<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_entradas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

include "includes.php";


$tpl = new Template("entradas_cadastrar.html");
$tpl->ICONES_CAMINHO = "$icones";
$operacao = $_GET["operacao"]; //Opera��o 1=Cadastras 2=Editar 3=Ver
//Cadastro de uma nova entrada
$passo = $_POST['passo'];
$entrada = $_POST['entrada'];
$tiponegociacao = $_POST['tiponegociacao'];
if ($tiponegociacao == "") { //caso o campo fornecedor fique desabilitado!
    $tiponegociacao = $_POST['tiponegociacao2'];
}
$fornecedor = $_POST['fornecedor'];
if ($fornecedor == "") { //caso o campo fornecedor fique desabilitado!
    $fornecedor = $_POST['fornecedor2'];
}
$tipopessoa = $_POST['tipopessoa'];
if ($tipopessoa == "") { //caso o campo fornecedor fique desabilitado!
    $tipopessoa = $_POST['tipopessoa2'];
}
$produto = $_POST['produto'];
$marca = $_POST['marca'];
$item_numero = $_POST['item_numero'];


$qtd = $_POST['qtd'];
$qtd = str_replace('.', '', $qtd);
$qtd = str_replace(',', '.', $qtd);
$valuni = $_POST['valuni'];
$valuni2 = $_POST['valuni2'];
$valunicusto = $_POST['valunicusto'];
$valunicusto2 = $_POST['valunicusto2'];
//Se o valor unit�rio estiver desabilitado ent�o devemos pegar o valuni2 que veio por hidden e alimentado via javascript
if (($valuni2 != "") && ($valuni == "")) {
    $valuni = $valuni2;
} else {
    $valuni = explode(" ", $valuni);
    $valuni = $valuni[1];
}
$valuni = str_replace('.', '', $valuni);
$valuni = str_replace(',', '.', $valuni);

if (($valunicusto2 != "") && ($valunicusto == "")) {
    $valunicusto = $valunicusto2;
} else {
    $valunicusto = explode(" ", $valunicusto);
    $valunicusto = $valunicusto[1];
}
$valunicusto = str_replace('.', '', $valunicusto);
$valunicusto = str_replace(',', '.', $valunicusto);

$validade = $_POST['validade'];
$validade2 = $_POST['validade2'];
//echo "validade: $validade validade2: $validade2 --";

if (($validade2 != "") && ($validade == "")) {
    $validade = $validade2;
}
//echo "validade: $validade validade2: $valid
//ade2--";






$local = strtoupper($_POST['local']);



//Caso seja precionado o botão cancelar a entrada deve ser pego por GET
$cancelar = $_GET["cancelar"];
if ($cancelar == 1) {
    $entrada = $_GET['entrada'];
    $item_numero = $_GET['item_numero'];
    $produto = $_GET['produto'];
    $fornecedor = $_GET['fornecedor'];
    $tiponegociacao = $_GET['tiponegociacao'];
    $tipopessoa = $_GET['tipopessoa'];
    $passo = $_GET["passo"];
}

//Caso seja uma operação seja ver ou editar
if (($operacao == 3) || ($operacao == 2)) {
    $entrada = $_GET['codigo'];
    $sql = "SELECT * FROM entradas join pessoas on (ent_fornecedor=pes_codigo)WHERE ent_codigo=$entrada";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro SQL" . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $fornecedor = $dados["ent_fornecedor"];
    $tipopessoa = $dados["pes_tipopessoa"];
    $tiponegociacao = $dados["ent_tiponegociacao"];
}
$tpl->FORNECEDOR = $fornecedor;
$tpl->TIPOPESSOA = $tipopessoa;
$tpl->TIPONEGOCIACAO = $tiponegociacao;
IF ($tiponegociacao == 2)
    $tpl->block(BLOCK_PERCENT);

//Caso seja uma operação de Editar ent�o ir para o passo2
if ($operacao == 2) {
    $tpl->SUBTITULO = "EDITAR";
    $passo = 3;
} else {
    $tpl->SUBTITULO = "REGISTRAR ENTRADA";
}

//Verifica se há fornecedores na cooperativa
$sql = "SELECT mespestip_pessoa FROM mestre_pessoas_tipo join pessoas on (mespestip_pessoa=pes_codigo) WHERE mespestip_tipo=5 and mespestip_pessoa not in ($usuario_codigo) and pes_cooperativa=$usuario_cooperativa";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas == 0) {
    echo "<br><br>";
    $tpl = new Template("templates/notificacao.html");
    $tpl->ICONES = $icones;
    $tpl->MOTIVO_COMPLEMENTO = "Além de você mesmo, não há nenhuma pessoa do tipo 'Fornecedor' cadastrada. A entrada é sempre atribuida à um fornecedor, seja uma pessoa física ou jurídica. <br>Por favor, clique no botão abaixo para ir para a tela de cadastro de pessoa, e <b>certifique-se de cadastrar um fornecedor</b>!";
    $tpl->block("BLOCK_ATENCAO");
    $tpl->DESTINO = "pessoas_cadastrar.php?operacao=cadastrar";
    $tpl->block("BLOCK_BOTAO");
    $tpl->show();
    exit;
} else {
    //Verifica se há produtos cadastrados
    $sql = "SELECT pro_codigo FROM produtos  WHERE pro_cooperativa=$usuario_cooperativa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas == 0) {
        echo "<br><br>";
        $tpl = new Template("templates/notificacao.html");
        $tpl->ICONES = $icones;
        $tpl->MOTIVO_COMPLEMENTO = "Para gerar uma entrada é necessário que se tenha produtos cadastrados. Seu ponto de venda ainda <b>não possui produtos cadastrados</b>.<br>Por favor, clique no botão abaixo para ir para a tela de cadastro de produtos!";
        $tpl->block("BLOCK_ATENCAO");
        $tpl->DESTINO = "";
        $tpl->BOTAOGERAL_DESTINO = "produtos_cadastrar.php?operacao=cadastrar";
        $tpl->BOTAOGERAL_TIPO="button";
        $tpl->BOTAOGERAL_NOME="CADASTRAR PRODUTO";
        $tpl->block("BLOCK_BOTAOGERAL_AUTOFOCO");
        $tpl->block("BLOCK_BOTAOGERAL");
        $tpl->show();
        exit;
    }
}


$data = date("Y/m/d");
$hora = date("H:i:s");
$tipo = 1;

$produtomanter = $_POST['produtomanter'];
if ($produtomanter == 'on') {
    $tpl->PRODUTOMANTER_HABILITADO = " checked ";
} else {
    $tpl->PRODUTOMANTER_HABILITADO = " ";
}

//PASSO 1
//Tipo de negociação
$sql = "
    SELECT tipneg_codigo,tipneg_nome
    FROM tipo_negociacao
    JOIN quiosques_tiponegociacao ON (tipneg_codigo=quitipneg_tipo)
    WHERE quitipneg_quiosque=$usuario_quiosque
";
$query = mysql_query($sql);
if ($query) {
    $tpl->SELECT_OBRIGATORIO = " required ";
    if ($passo == "") {
        $tpl->SELECT_DESABILITADO = "";
    } else {
        $tpl->SELECT_DESABILITADO = " disabled ";
    }
    //Caso a operação seja VER então desabilitar o select e trocar a classe
    if ($operacao == 3) {
        $tpl->SELECT_DESABILITADO = " disabled ";
    }
    $linhas=  mysql_num_rows($query);
    while ($dados = mysql_fetch_array($query)) {
        $tpl->OPTION_VALOR = $dados[0];
        $tpl->OPTION_TEXTO = "$dados[1]";
        //Quando for novo cadastro de entrada, se o quiosque tiver apenas 1 tipo de negociação então já deixa selecionado
        if ($operacao=="1") {
            if ($linhas==1)  $tpl->OPTION_SELECIONADO = " SELECTED ";
            else $tpl->OPTION_SELECIONADO = "  ";
        } else {
            //se for edição seleciona o que está no banco
            if ($dados[0] == $tiponegociacao) {
                $tpl->OPTION_SELECIONADO = " SELECTED ";
            } else {
                $tpl->OPTION_SELECIONADO = "";
            }
        }
        $tpl->block("BLOCK_OPTIONS_TIPONEGOCIACAO");
    }
} else {
    echo mysql_error();
}
$tpl->block("BLOCK_SELECT_TIPONEGOCIACAO");


//Tipo de pessoa
$sql = "SELECT pestippes_codigo,pestippes_nome FROM pessoas_tipopessoa";
$query = mysql_query($sql);
if ($query) {
    if ($passo == "") {
        $tpl->SELECT_TIPOPESSOA_DESABILITADO = "";
    } else {
        $tpl->SELECT_TIPOPESSOA_DESABILITADO = " disabled ";
    }
    //Caso a operação seja VER então desabilitar o select e trocar a classe
    if ($operacao == 3) {
        $tpl->SELECT_TIPOPESSOA_DESABILITADO = " disabled ";
    }
    if ($operacao != 1) {

        while ($dados = mysql_fetch_array($query)) {
            $tpl->OPTION_TIPOPESSOA_VALOR = $dados[0];
            $tpl->OPTION_TIPOPESSOA_TEXTO = "$dados[1]";
            if ($dados[0] == $tipopessoa) {
                $tpl->OPTION_TIPOPESSOA_SELECIONADO = " SELECTED ";
            } else {
                $tpl->OPTION_TIPOPESSOA_SELECIONADO = "";
            }
            $tpl->block("BLOCK_OPTIONS_TIPOPESSOA");
        }
        $tpl->block("BLOCK_OPTIONPADRAO");
    } else {
        $tpl->block("BLOCK_OPTIONPADRAO2");
    }
} else {
    echo mysql_error();
}
$tpl->block("BLOCK_SELECT_TIPOPESSOA");



//Fornecedor
$sql = "
SELECT 
    pes_codigo,pes_nome
FROM 
    pessoas 
    inner join mestre_pessoas_tipo on (pes_codigo=mespestip_pessoa)
WHERE 
    mespestip_tipo=5 and 
    pes_cooperativa=$usuario_cooperativa 
ORDER BY 
    pes_nome
";
$query = mysql_query($sql);
if ($query) {
    $tpl->SELECT_OBRIGATORIO = " required ";
    if ($passo == "") {
        $tpl->SELECT_DESABILITADO = "";
    } else {
        $tpl->SELECT_DESABILITADO = " disabled ";
    }
    //Caso a operação seja VER ent�o desabilitar o select e trocar a classe
    if ($operacao == 3) {
        $tpl->SELECT_DESABILITADO = " disabled ";
    }
    if ($operacao != 1) {

        while ($dados = mysql_fetch_array($query)) {
            $tpl->OPTION_VALOR = $dados[0];
            $tpl->OPTION_TEXTO = "$dados[1]";
            if ($dados[0] == $fornecedor) {
                $tpl->OPTION_SELECIONADO = " SELECTED ";
            } else {
                $tpl->OPTION_SELECIONADO = "";
            }
            $tpl->block("BLOCK_OPTIONS_FORNECEDOR");
        }
    }
} else {
    echo mysql_error();
}
$tpl->block("BLOCK_SELECT_FORNECEDOR");


if ($passo == "") {
    $tpl->block("BLOCK_BOTAO_PASSO1");
}



//PASSO 02 - Gravando entrada no Banco
if ($passo != "") {
    if ($passo == 2) {
        $tpl->SALVAR_DESABILIDADO = " disabled ";
    } else {
        $tpl->SALVAR_DESABILIDADO = " ";
    }

    //Grava no Banco a Entrada com Status "Incompleto"
    if ($entrada == "") {
        $sql = "
		INSERT INTO entradas 
			(ent_quiosque,ent_fornecedor,ent_supervisor,ent_datacadastro,ent_horacadastro,ent_tipo,ent_status,ent_tiponegociacao )
		VALUES
			('$usuario_quiosque','$fornecedor','$usuario_codigo','$data','$hora','$tipo',2,'$tiponegociacao');";
        if (mysql_query($sql)) {
            
        } else {
            echo mysql_error();
        }
        $entrada = mysql_insert_id();
        $tpl->ENTRADA = $entrada;
    }

    //Marca
    $sql = "
        SELECT DISTINCT TRIM(pro_marca)
        FROM produtos 
        JOIN mestre_produtos_tipo ON (mesprotip_produto=pro_codigo)
        WHERE pro_cooperativa='$usuario_cooperativa' 
        AND mesprotip_tipo=$tiponegociacao
        ORDER BY TRIM(pro_marca)
    ";
    $query = mysql_query($sql);
    if ($query) {
        while ($dados = mysql_fetch_array($query)) {
            $tpl->SELECT_MARCA_OBRIGATORIO = " required ";
            $tpl->SELECT_MARCA_DESABILITADO = "";
            $marca_banco = $dados[0];
            $marca_banco_valor = $dados[0];
            $marca_banco = trim($marca_banco);
            if ($marca_banco == "") {                
                $marca_banco_valor = "";
                $marca_banco = "Sem marca";
            }
            $tpl->OPTION_MARCA_VALOR = $marca_banco_valor;
            $tpl->OPTION_MARCA_TEXTO = $marca_banco;
            if (($marca == $marca_banco) && ($produtomanter == 'on'))
                $tpl->OPTION_MARCA_SELECIONADO = " selected ";
            else
                $tpl->OPTION_MARCA_SELECIONADO = "";
            $tpl->block("BLOCK_OPTIONS_MARCA");
        }
    } else {
        echo mysql_error();
    }



    //PRODUTOS
    $sql = "
        SELECT pro_codigo,pro_nome,prorec_nome,pro_volume,pro_marca,protip_sigla
        FROM produtos 
        JOIN mestre_produtos_tipo ON (mesprotip_produto=pro_codigo)
        join produtos_tipo on pro_tipocontagem=protip_codigo
        left JOIN produtos_recipientes on (prorec_codigo=pro_recipiente)
        WHERE pro_cooperativa='$usuario_cooperativa' 
        AND mesprotip_tipo=$tiponegociacao
        ORDER BY pro_nome
    ";
    $query = mysql_query($sql);
    if ($query) {
        while ($dados = mysql_fetch_array($query)) {
            $tpl->SELECT2_OBRIGATORIO = " required ";
            $tpl->SELECT2_DESABILITADO = "";
            $tpl->OPTION2_VALOR = "$dados[0]";
            $tpl->OPTION2_TEXTO = "$dados[1] $dados[4] $dados[2] $dados[3] ($dados[5])";
            if (($produto == $dados[0]) && ($produtomanter == 'on'))
                $tpl->OPTION2_SELECIONADO = " selected ";
            else
                $tpl->OPTION2_SELECIONADO = "";
            $tpl->block("BLOCK_OPTIONS_PRODUTO");
        }
    } else {
        echo mysql_error();
    }




    $tpl->block("BLOCK_BOTAO_PASSO2");
    if ($tiponegociacao == 2)
        $tpl->block("BLOCK_CAMPO_VALCUSTO");


    //PASSO 3 - Mostra os produtos já inseridos na entrada e/ou faz a insersão!
    $sql5 = "
	SELECT
		pro_nome, 
                protip_nome, 
                entpro_quantidade,
                pro_codigo,
                entpro_valorunitario,
                entpro_validade,
                entpro_local,
                entpro_numero,
                protip_sigla,
                entpro_valunicusto,
                entpro_valtotcusto,
                entpro_temsubprodutos,
                entpro_retiradodoestoquesubprodutos
	FROM
		entradas_produtos
		join entradas on (ent_codigo=entpro_entrada) 
		join produtos on (entpro_produto=pro_codigo) 
		join produtos_tipo on (protip_codigo=pro_tipocontagem)
                
	WHERE
		ent_codigo=$entrada
        ORDER BY 
                entpro_numero DESC
	";
    if ($passo == "3") {

        //Verifica se será feita um exclusão da lista ou inclusào
        if ($cancelar == 1) {

            //Retirar produto do estoque caso ele ja tenha sido incluso no estoque
            $sql1="SELECT * FROM entradas_produtos WHERE entpro_entrada=$entrada and entpro_numero=$item_numero and entpro_status=1";
            $query1 = mysql_query($sql1); if (!$query1)  die("Erro de SQL 12:" . mysql_error());
            $dados1= mysql_fetch_assoc($query1);
            $status1=$dados1["entpro_status"];
            if ($status1==1) { //Somente se o item já foi incluso no estoque que se pode retirar do estoque
                $sql2 = "
                    SELECT entpro_quantidade 
                    FROM entradas_produtos 
                    WHERE entpro_entrada=$entrada 
                    and entpro_numero=$item_numero
                ";
                $query2 = mysql_query($sql2);
                if (!$query2)
                    die("Erro de SQL 12:" . mysql_error());
                $dados2 = mysql_fetch_array($query2);
                $qtd2 = $dados2[0];
                $sql_retirar = "
                UPDATE
                    estoque 
                SET 
                    etq_quantidade=(etq_quantidade-'$qtd2')
                WHERE
                    etq_quiosque=$usuario_quiosque and
                    etq_produto=$produto and
                    etq_lote=$entrada
                ";
                $query_retirar = mysql_query($sql_retirar);
                if (!$query_retirar)
                    die("Erro de SQL 11:" . mysql_error());
                
                //echo "Atualizado estoque, decrementado $entrada $produto , quantidade $qtd2";
            }

            //Devolver ao estoque os subprodutos caso seja um produto composto
            $sql6="
                SELECT * 
                FROM entradas_subprodutos 
                JOIN entradas_produtos on (entsub_item=entpro_numero and entsub_entrada=entpro_entrada) 
                WHERE entsub_entrada=$entrada and entsub_item=$item_numero and entpro_retiradodoestoquesubprodutos=1
            ";
            $query6 = mysql_query($sql6);
            if (!$query6)
                die("Erro de SQL 6:" . mysql_error());
            while ($dados6=  mysql_fetch_assoc($query6)) {
                $produto3=$dados6["entsub_produto"];
                $subproduto=$dados6["entsub_subproduto"];
                $lote=$dados6["entsub_lote"];
                $qtd3=$dados6["entsub_quantidade"];
                $valuni3=$dados6["entpro_valunicusto"];
                $validade3=$dados6["entpro_validade"];
                
                //Verifica se o lote a ser inserido no estoque existe. Se o lote não existe mais no estoque deve-se criar o registro
                $sql8="
                    SELECT * FROM estoque
                    WHERE etq_quiosque=$usuario_quiosque 
                    and etq_produto=$subproduto
                    and etq_lote=$lote
                ";
                $query8 = mysql_query($sql8); if (!$query8) die("Erro de SQL 8:" . mysql_error());
                $linhas8=  mysql_num_rows($query8);
                if ($linhas8==0) { //inserir novamente no estoque o lote do subproduto
                    $sql9="
                        INSERT INTO 
                        estoque (
                            etq_quiosque,
                            etq_produto,
                            etq_fornecedor,
                            etq_lote,
                            etq_quantidade,
                            etq_valorunitario,
                            etq_validade
                        )
                        VALUES (
                            '$usuario_quiosque',
                            '$subproduto',
                            '$fornecedor',
                            '$lote',
                            '$qtd3',
                            '$valuni3',
                            '$validade3'
                        )
                    ";
                    $query9 = mysql_query($sql9); if (!$query9) die("Erro de SQL 9:" . mysql_error());
                    //echo "Inserido novo registro no estoque $produto3 $subproduto $lote <br>";

                    
                } else { //atualizar lote do estoque incrementando a quantidade do subproduto
                    
                    
                    $sql7="
                        UPDATE estoque 
                        SET etq_quantidade=(etq_quantidade+'$qtd3')
                        WHERE etq_quiosque=$usuario_quiosque 
                        and etq_produto=$subproduto
                        and etq_lote=$lote
                    ";
                    $query7 = mysql_query($sql7); if (!$query7) die("Erro de SQL 7:" . mysql_error());
                    //echo "Atualizado estoque $produto3 $subproduto $lote <br>";
                }
                
            }
            
            //Excluir da tabela entradas_subprodutos o item removido
            $sql10="DELETE FROM entradas_subprodutos WHERE entsub_entrada=$entrada AND entsub_item=$item_numero";
            $query10 = mysql_query($sql10); if (!$query10) die("Erro de SQL 10:" . mysql_error());

            
            
            //Verifica se a quantidade do produto no estoque como o descremento ficou zero
            $sql3 = "
            SELECT 
                etq_quantidade
            FROM 
                estoque
            WHERE 
                etq_quiosque=$usuario_quiosque and 
                etq_produto=$produto and 
                etq_lote=$entrada
            ";
            $query3 = mysql_query($sql3);
            if (!$query3)
                die("Erro de SQL 12:" . mysql_error());
            $dados3 = mysql_fetch_array($query3);
            $qtd_noestoque = $dados3[0];
            if ($qtd_noestoque == 0) {
                //Como a quantidade do produto � zero ent�o eliminar o produto do estoque
                $sql4 = "
                DELETE FROM 
                    estoque
                WHERE 
                    etq_quiosque=$usuario_quiosque and 
                    etq_produto=$produto and 
                    etq_lote=$entrada                    
                ";
                $query4 = mysql_query($sql4);
                if (!$query4)
                    die("Erro de SQL 8:" . mysql_error());
            }


            //Deleta item da entrada
            $sqldel = "
            DELETE FROM 
                entradas_produtos 
            WHERE 
                entpro_entrada=$entrada and
                entpro_numero = $item_numero
            ";
            $querydel = mysql_query($sqldel);
            if (!$querydel)
                die("Erro de SQL 8:" . mysql_error());

            //Troca o status da entrada para Incompleto.
            //OBS: Quando � realizado alguma altera��o � necess�rio que seja clicado no salvar para atualizar o estoque
            $sql_status = "UPDATE entradas SET ent_status=2 WHERE ent_codigo=$entrada";
            $query_status = mysql_query($sql_status);
            if (!$query_status)
                die("Erro de SQL 3: " . mysql_error());
        } else {

            if ($operacao != 2) {

                //Verifica se o produto possui subprodutos
                $sql11="SELECT * FROM produtos_subproduto WHERE prosub_produto=$produto";
                if (!$query11 = mysql_query($sql11)) die("Erro de SQL 11: " . mysql_error());
                $linhas11 =  mysql_num_rows($query11);
                if ($linhas11>0) $temsubprodutos=1;
                else $temsubprodutos=0;
                
                //Faz a inserção do produto na entrada (inserir item de entrada)
                //$validade = desconverte_data($validade);
                $total = number_format($valuni * $qtd, 2, '.', '');
                $totalcusto = number_format($valunicusto * $qtd, 2, '.', '');
                $sql = "
                INSERT INTO
                    entradas_produtos (
                        entpro_entrada,
                        entpro_produto,
                        entpro_quantidade,
                        entpro_valorunitario,
                        entpro_validade,
                        entpro_local,
                        entpro_valtot,
                        entpro_valunicusto,
                        entpro_valtotcusto,
                        entpro_temsubprodutos
                    )
                VALUES (
                    '$entrada',
                    '$produto',
                    '$qtd',
                    '$valuni',
                    '$validade',
                    '$local',
                    '$total',
                    '$valunicusto',
                    '$totalcusto',
                    '$temsubprodutos'
                )";
                $query = mysql_query($sql);
                if (!$query)
                    die("Erro de SQL 3: " . mysql_error());

                //Troca o status da entrada para Incompleto.
                //OBS: Quando é realizado alguma alteração é necessário que seja clicado no salvar para atualizar o estoque
                $sql_status = "UPDATE entradas SET ent_status=2 WHERE ent_codigo=$entrada";
                $query_status = mysql_query($sql_status);
                if (!$query_status)
                    die("Erro de SQL 3: " . mysql_error());
            }
        }
    }
    $tpl->block("BLOCK_ENTER");
    $tpl->block("BLOCK_HR");


    //Lista de produtos
    $tpl->ENTRADA = $entrada;
    IF ($tiponegociacao == 2) {
        $tpl->block("BLOCK_CUSTO_CABECALHO");
        //$tpl->block("BLOCK_LUCRO_CABECALHO");
    }
    
    $tpl->block("BLOCK_VENDA_VALUNI_CABECALHO");
    $tpl->block("BLOCK_VENDA_CABECALHO");

    $query5 = mysql_query($sql5);
    if ($query5) {
        $tot = mysql_num_rows($query5);
        if ($tot == "0") {
            $tpl->block("BLOCK_LISTA_NADA");
            $tpl->SALVAR_DESABILIDADO = " disabled ";
        } else {
            $tpl->OPER_COLSPAN = 2;
            $tpl->block("BLOCK_CABECALHO_OPERACAO");
            while ($dados = mysql_fetch_array($query5)) {
                $tpl->ENTRADAS_NUMERO = $dados['entpro_numero'];
                $tpl->ENTRADAS_PRODUTO = $dados[3];
                $tpl->ENTRADAS_PRODUTO_NOME = $dados[0];
                //$tpl->ENTRADAS_LOCAL = $dados[6];
                $tpl->SIGLA = $dados["protip_sigla"];
                if ($dados["protip_sigla"] == "kg.")
                    $tpl->ENTRADAS_QTD = number_format($dados[2], 3, ',', '.');
                else
                    $tpl->ENTRADAS_QTD = number_format($dados[2], 0, ',', '.');
                $tpl->ENTRADAS_VALORUNI = "R$ " . number_format($dados[4], 2, ',', '.');
                $tpl->block("BLOCK_VENDA_VALUNI");
                $tpl->ENTRADAS_VENDA_TOTAL = "R$ " . number_format(($dados[2] * $dados[4]), 2, ',', '.');
                if ($tiponegociacao == 2) {
                    $tpl->ENTRADAS_VALORUNI_CUSTO = "R$ " . number_format($dados[9], 2, ',', '.');
                    $tpl->ENTRADAS_VALOR_TOTAL_CUSTO = "R$ " . number_format($dados[2] * $dados[9], 2, ',', '.');
                    $lucro = ($dados[2] * $dados[4]) - ($dados[2] * $dados[9]);
                    $tpl->ENTRADAS_VALOR_LUCRO = "R$ " . number_format($lucro, 2, ',', '.');
                    $tpl->block("BLOCK_CUSTO");
                    //$tpl->block("BLOCK_LUCRO");
                }
                $tpl->block("BLOCK_VENDA");
                $tpl->PRODUTO = $dados[3];
                $numero = $dados['entpro_numero'];
                $tpl->IMPRIMIR_LINK = "entradas_etiquetas.php?lote=$entrada&numero=$numero";
                $tpl->IMPRIMIR = $icones . "etiquetas.png";
                $tpl->ENTRADAS_VALIDADE= converte_data($dados[5]);
                
                //Subprodutos
                $subprodutos_retirado_do_estoque=$dados["entpro_retiradodoestoquesubprodutos"];
                $temsubprodutos2=$dados["entpro_temsubprodutos"];
                
                
                
                if ($temsubprodutos2==1) { //mostra icone
                    $tpl->NOMEARQUIVO="subproduto.png";
                    $tpl->TITULO="Este é um produto composto (possui sub-produtos)";
                    
                    if ($subprodutos_retirado_do_estoque==1) {
                        $tpl->SUBPRODUTOS_NOMEICONEARQUIVO="saidas.png";
                        $tpl->SUBPRODUTOS_TITULO="Os subprodutos foram retirados do estoque";
                        $tpl->SUBPRODUTOS_ALINHAMENTO="right";
                        $tpl->SUBPRODUTOS_NOMEICONEARQUIVO_VER="procurar.png";
                        $tpl->block("BLOCK_SUBPRODUTOS");
                        $tpl->block("BLOCK_SUBPRODUTOS_VER");
                        $tpl->SUBPRODUTOS_COLSPAN="";
                    }
                    else if ($subprodutos_retirado_do_estoque==2) {
                        $tpl->SUBPRODUTOS_NOMEICONEARQUIVO="saidas2.png";
                        $tpl->SUBPRODUTOS_NOMEICONEARQUIVO_VER="procurar_desabilitado.png";
                        $tpl->SUBPRODUTOS_TITULO="Optou-se por não realizar a retirada dos sub-produtos do estoque";
                        $tpl->SUBPRODUTOS_COLSPAN="";
                        $tpl->SUBPRODUTOS_ALINHAMENTO="right";
                        $tpl->block("BLOCK_SUBPRODUTOS");
                        $tpl->block("BLOCK_SUBPRODUTOS_VER");
                    } else { //não foi deicido ainda o que ferá se vai tirar do estoque ou não
                        $tpl->SUBPRODUTOS_COLSPAN="2";
                        $tpl->SUBPRODUTOS_ALINHAMENTO="center";
                        $tpl->SUBPRODUTOS_NOMEICONEARQUIVO="atencao.png";
                        $tpl->SUBPRODUTOS_TITULO="Ainda não decidiu-se se será realizado a retirada dos sub-produtos do estoque";
                        $tpl->block("BLOCK_SUBPRODUTOS");

                        
                    }
                } else { //não mostra icone
                    $tpl->NOMEARQUIVO="subproduto2.png";
                    $tpl->TITULO="Este é não é um produto composto.";
                    $tpl->SUBPRODUTOS_COLSPAN="2";

                }
                $tpl->block("BLOCK_SUBPRODUTOS_TEM");
                
                
                //Verifica se ja foi efetuado Saídas quaisquer para o lote/entrada em questão
                $prod=$dados["pro_codigo"];
                $sql3 = "SELECT * FROM saidas_produtos WHERE saipro_lote=$entrada and saipro_produto=$prod ";
                $query3 = mysql_query($sql3);
                if (!$query3) {
                    die("Erro SQL: " . mysql_error());
                }
                $linhas3 = mysql_num_rows($query3);
                if ($linhas3 > 0) { 
                    $nao_pode_excluir_item=1;
                    $nao_pode_excluir_entrada=1;
                } else {                    
                    $nao_pode_excluir_item=0;                   
                }
                //Se já houve Saídas referentes a esta entrada então não pode-se excluir
                if ($nao_pode_excluir_item == 1) {                    
                    $tpl->ICONES_TITULO="Não pode excluir este item porque já existem vendas de pelo menos uma unidade ou quilo deste produto nesta entrada";
                    $tpl->ICONES_ARQUIVO="remover_desabilitado.png";
                    $tpl->block("BLOCK_LISTA_OPERACAO_EXCLUIR");                    
                } else {                    
                    $tpl->ICONES_TITULO="Remover";
                    $tpl->ICONES_ARQUIVO="remover.png";
                    $tpl->block("BLOCK_LISTA_OPERACAO_EXCLUIR_LINK");
                    $tpl->block("BLOCK_LISTA_OPERACAO_EXCLUIR");
                }                
                
                
                $tpl->block("BLOCK_LISTA_OPERACAO_ETIQUETAS");
                $tpl->block("BLOCK_LISTA_OPERACAO");

                $tpl->block("BLOCK_LISTA");
            }

            //Calcula o valor total geral da entrada
            $sql8 = "SELECT round(sum(entpro_valorunitario*entpro_quantidade),2) FROM `entradas_produtos` WHERE entpro_entrada=$entrada";
            $query8 = mysql_query($sql8);
            while ($dados8 = mysql_fetch_array($query8)) {
                $tot8 = "R$ " . number_format($dados8[0], 2, ',', '.');
            }
            //Calcula o valor total de custo geral da entrada
            $sql9 = "SELECT round(sum(entpro_valunicusto*entpro_quantidade),2) FROM entradas_produtos WHERE entpro_entrada=$entrada";
            $query9 = mysql_query($sql9);
            while ($dados9 = mysql_fetch_array($query9)) {
                $tot9 = "R$ " . number_format($dados9[0], 2, ',', '.');
            }
            //Calcula o valor total de lucro da entrada
            $sql10 = "SELECT round(sum((entpro_valorunitario*entpro_quantidade)-(entpro_valunicusto*entpro_quantidade)),2) FROM entradas_produtos WHERE entpro_entrada=$entrada";
            $query10 = mysql_query($sql10);
            while ($dados10 = mysql_fetch_array($query10)) {
                $tot10 = "R$ " . number_format($dados10[0], 2, ',', '.');
            }
            $tpl->block("BLOCK_LISTA_NADA_OPERACAO");
            $tpl->block("BLOCK_LISTA_NADA_OPERACAO");
            $tpl->TOTAL_CUSTO = "$tot9";
            //$tpl->TOTAL_ENTRADA = "$tot8";
            $tpl->TOTAL_LUCRO = "$tot10";
        }
        if ($tiponegociacao == 2) {
            $tpl->block("BLOCK_CUSTO_RODAPE");
            //$tpl->block("BLOCK_LUCRO_RODAPE");
        }
        $tpl->block("BLOCK_VENDA_RODAPE");
        $tpl->block("BLOCK_PASSO2");
        $tpl->VALIDADE_MIN = date("Y-m-d");        
        $tpl->OPERACAO = $operacao;
        $tpl->INTERROMPER = "CANCELAR";
        $tpl->ENTRADA = $entrada;
        
        if ($nao_pode_excluir_entrada==1) {
            $tpl->EXCLUIR_CLASSE=" ";
            $tpl->EXCLUIR_TITULO=" Você não pode excluir esta entrada porque existem produtos dentro desta entrada que já foram vendido!";
            $tpl->ELIMINAR_ENTRADA_DESABILITADO=" disabled";
        } else {
            $tpl->EXCLUIR_TITULO=" Excluir entrada ";
            $tpl->EXCLUIR_CLASSE=" botaovermelho ";
            $tpl->ELIMINAR_ENTRADA_DESABILITADO="";            
            $tpl->block("BLOCK_BOTOES_EXCLUIR_LINK");
        }
        $tpl->block("BLOCK_BOTOES");
        $tpl->block("BLOCK_PASSO3");
    } else {
        echo mysql_error;
    }
}

$tpl->show();
include "rodape.php";
?>
