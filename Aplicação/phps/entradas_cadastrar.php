<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_entradas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

include "includes.php";

//Verifica se há fornecedores na cooperativa
$sql = "
    SELECT pes_nome,pes_codigo
FROM 
    pessoas
    join mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)
WHERE
    pes_cooperativa=$usuario_cooperativa and
    mespestip_tipo=5
";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL (Não Tem Fornecedor):" . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas == 0) {
    $tpl11 = new Template("templates/notificacao.html");
    $tpl11->ICONES = $icones;            
    $tpl11->block("BLOCK_ATENCAO");
    $tpl11->MOTIVO = "Não há fornecedores cadastrados! Portanto não é possível realizar entradas!";
    $tpl11->block("BLOCK_MOTIVO");
    $tpl11->block("BLOCK_BOTAO_VOLTAR");                
    $tpl11->show();
    exit;
}

$tpl = new Template("entradas_cadastrar.html");
$tpl->ICONES_CAMINHO = "$icones";
$operacao = $_GET["operacao"]; //Operação 1=Cadastras 2=Editar 3=Ver
//Cadastro de uma nova entrada
$passo = $_POST['passo'];
$entrada = $_POST['entrada'];
$fornecedor = $_POST['fornecedor'];
if ($fornecedor == "") { //caso o campo fornecedor fique desabilitado!
    $fornecedor = $_POST['fornecedor2'];
}
$produto = $_POST['produto'];
$item_numero = $_POST['item_numero'];


$qtd = $_POST['qtd'];
$qtd = str_replace('.', '', $qtd);
$qtd = str_replace(',', '.', $qtd);
$valuni = $_POST['valuni'];
$valuni2 = $_POST['valuni2'];

//Se o valor unitário estiver desabilitado então devemos pegar o valuni2 que veio por hidden e alimentado via javascript
if (($valuni2 != "") && ($valuni == "")) {
    $valuni = $valuni2;
} else {
    $valuni = explode(" ", $valuni);
    $valuni = $valuni[1];   
}
$valuni = str_replace('.', '', $valuni);
$valuni = str_replace(',', '.', $valuni);

$validade = $_POST['validade'];
$validade2 = $_POST['validade2'];
//echo "validade: $validade validade2: $validade2 --";

if (($validade2 != "") && ($validade == "")) {
    $validade = $validade2;
}
//echo "validade: $validade validade2: $validade2--";






$local = strtoupper($_POST['local']);


//Caso seja precionado o botão cancelar a entrada deve ser pego por GET
$cancelar = $_GET["cancelar"];
if ($cancelar == 1) {
    $entrada = $_GET['entrada'];
    $item_numero = $_GET['item_numero'];
    $produto = $_GET['produto'];
    $fornecedor = $_GET['fornecedor'];
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
}
$tpl->FORNECEDOR = $fornecedor;

//Caso seja uma operação de Editar então ir para o passo2
if ($operacao == 2) {
    $tpl->SUBTITULO = "EDITAR";
    $passo = 3;
} else {
    $tpl->SUBTITULO = "REGISTRAR ENTRADA";
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
//echo "produtomanter: $produtomanter";
//PASSO 01 - Selecionando o fornecedor
//Options do Select do Fornecedor
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
    //Caso a operação seja VER então desabilitar o select e trocar a classe
    if ($operacao == 3) {
        $tpl->SELECT_DESABILITADO = " disabled ";
    }
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
} else {
    echo mysql_error();
}
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
			(ent_quiosque,ent_fornecedor,ent_supervisor,ent_datacadastro,ent_horacadastro,ent_tipo,ent_status )
		VALUES
			('$usuario_quiosque','$fornecedor','$usuario_codigo','$data','$hora','$tipo',2);";
        if (mysql_query($sql)) {
            
        } else {
            echo mysql_error();
        }
        $entrada = mysql_insert_id();
        $tpl->ENTRADA = $entrada;
    }

    //Options do Select dos PRODUTOS
    $sql = "SELECT pro_codigo,pro_nome FROM produtos WHERE pro_cooperativa='$usuario_cooperativa' ORDER BY pro_nome";
    $query = mysql_query($sql);
    if ($query) {
        while ($dados = mysql_fetch_array($query)) {
            $tpl->SELECT2_OBRIGATORIO = " required ";
            $tpl->SELECT2_DESABILITADO = "";
            $tpl->OPTION2_VALOR = $dados[0];
            $tpl->OPTION2_TEXTO = $dados[1];
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


    //PASSO 3 - Mostra os produtos já inseridos na entrada e/ou faz a insersão!
    $sql5 = "
	SELECT
		pro_nome, protip_nome, entpro_quantidade,pro_codigo,entpro_valorunitario,entpro_validade,entpro_local,entpro_numero,protip_sigla
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

        //Verifica se será feita um exclusão da lista ou inclusão
        if ($cancelar == 1) {

            //Devolver para o estoque
            $sql2 = "SELECT entpro_quantidade FROM entradas_produtos WHERE entpro_entrada=$entrada and entpro_numero=$item_numero";
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
                //Como a quantidade do produto é zero então eliminar o produto do estoque
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
            //OBS: Quando é realizado alguma alteração é necessário que seja clicado no salvar para atualizar o estoque
            $sql_status = "UPDATE entradas SET ent_status=2 WHERE ent_codigo=$entrada";
            $query_status = mysql_query($sql_status);
            if (!$query_status)
                die("Erro de SQL 3: " . mysql_error());
        } else {

            if ($operacao != 2) {

                //Faz a inserção do produto na entrada (inserir item de entrada)
                $validade = desconverte_data($validade);
                $total = number_format($valuni * $qtd, 2);
                $sql = "
                INSERT INTO
                    entradas_produtos (
                        entpro_entrada,
                        entpro_produto,
                        entpro_quantidade,
                        entpro_valorunitario,
                        entpro_validade,
                        entpro_local,
                        entpro_valtot
                    )
                VALUES (
                    '$entrada',
                    '$produto',
                    '$qtd',
                    '$valuni',
                    '$validade',
                    '$local',
                    '$total'
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
    

    $tpl->ENTRADA = $entrada;
    $query5 = mysql_query($sql5);
    if ($query5) {
        $tot = mysql_num_rows($query5);
        if ($tot == "0") {
            $tpl->block("BLOCK_LISTA_NADA");
            $tpl->SALVAR_DESABILIDADO = " disabled ";
        } else {
            $tpl->OPER_COLSPAN=2;
            $tpl->block("BLOCK_CABECALHO_OPERACAO");
            while ($dados = mysql_fetch_array($query5)) {
                $tpl->ENTRADAS_NUMERO = $dados['entpro_numero'];
                $tpl->ENTRADAS_PRODUTO_NOME = $dados[0];
                $tpl->ENTRADAS_LOCAL = $dados[6];
                $tpl->SIGLA = $dados["protip_sigla"];
                if ($dados["protip_sigla"]=="kg.")
                    $tpl->ENTRADAS_QTD = number_format($dados[2], 3,',','.');
                else
                    $tpl->ENTRADAS_QTD = number_format($dados[2], 0,',','.');
                $tpl->ENTRADAS_VALORUNI = "R$ " . number_format($dados[4], 2, ',', '.');
                if ($dados['5'] != "0000-00-00")
                    $tpl->ENTRADAS_VALIDADE = converte_data($dados['5']);
                else
                    $tpl->ENTRADAS_VALIDADE = "";
                $tpl->ENTRADAS_VALOR_TOTAL = "R$ " . number_format($dados['2'] * $dados['4'], 2, ',', '.');
                $tpl->PRODUTO = $dados[3];
                $numero = $dados['entpro_numero'];
                $tpl->IMPRIMIR_LINK = "entradas_etiquetas.php?lote=$entrada&numero=$numero";
                $tpl->IMPRIMIR = $icones . "etiquetas.png";
                $tpl->block("BLOCK_LISTA_OPERACAO_EXCLUIR");
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
            $tpl->block("BLOCK_LISTA_NADA_OPERACAO");
            $tpl->block("BLOCK_LISTA_NADA_OPERACAO");
            $tpl->TOTAL_ENTRADA = "$tot8";
        }
        $tpl->block("BLOCK_PASSO2");
        $tpl->OPERACAO = $operacao;
        $tpl->INTERROMPER="CANCELAR";
        $tpl->ENTRADA=$entrada;
        $tpl->block("BLOCK_BOTOES");
        $tpl->block("BLOCK_PASSO3");
    } else {
        echo mysql_error;
    }
}

$tpl->show();
include "rodape.php";
?>
