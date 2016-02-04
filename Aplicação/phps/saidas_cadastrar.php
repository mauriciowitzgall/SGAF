<?php

require "login_verifica.php";
$saida = $_GET["codigo"];
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

if ($saida != "") {
//Verifica se algum produto desta Sa�da j� foi acertado
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

//Verifica se o usuário é um caixa e não tem caixa aberto, se sim não pode acessar as vendas
if (($usuario_caixa_operacao=="")&&($usuario_grupo==4)) {
    header("Location: permissoes_semacesso.php");
    exit;
}

//CONTROLE DA OPERAÇÃO
$dataatual = date("Y/m/d");
$horaatual = date("H:i:s");
$operacao = $_GET["operacao"]; //Opera��o 1=Cadastras 2=Editar 3=Ver

$retirar_produto = $_GET["retirar_produto"];
//Se for elimina��o de um produto ja da lista ent�o pegar por get
if ($retirar_produto == '1') {
    $consumidor = $_GET["consumidor"];
    $tiposaida = $_GET["tiposaida"];
    $saida = $_GET["saida"];
    $saipro = $_GET["saipro"];
    $passo = $_GET["passo"];
    $lote = $_GET["lote"];
    $qtd = $_GET["qtd"];
    $produto = $_GET["produto"];
} else { //Se n�o pegar por post
    if ($operacao == 2) {
        $saida = $_GET["codigo"];
        $passo = "2";
        $sql = "SELECT * FROM saidas WHERE sai_codigo=$saida";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro de SQL11:" . mysql_error());
        while ($dados = mysql_fetch_assoc($query)) {
            $consumidor = $dados["sai_consumidor"];
            $tiposaida = $dados["sai_tipo"];
            $motivo = $dados["sai_saidajustificada"];
            $descricao = $dados["sai_descricao"];
        }
    } else {
        $saida = $_POST["saida"];
        $passo = $_POST["passo"];
        $consumidor = $_POST["consumidor"];
        $tiposaida = $_GET["tiposaida"];
        $motivo = $_POST["motivo"];
        $descricao = $_POST["descricao"];
        $saipro = "";
        $lote = $_POST["lote"];
        $lote2 = $_POST["lote2"];
        $produto = $_POST["produto"];
        $produto2 = $_POST["produto2"];
        if ($produto2 != "") {
            $produto = $produto2;
        }
        if ($lote2 != "") {
            $lote = $lote2;
        }
        $valuni = $_POST["valuni"];
        $valuni = explode(" ", $valuni);
        $valuni = $valuni[1];
        $valuni = str_replace(',', '.', $valuni);
        $valtot = $_POST["valtot"];
        $valtot = explode(" ", $valtot);
        $valtot = $valtot[1];
        $valtot = str_replace('.', '', $valtot);
        $valtot = str_replace(',', '.', $valtot);
        $qtd = $_POST["qtd"];
        $qtd = str_replace('.', '', $qtd);
        $qtd = str_replace(',', '.', $qtd);
        $qtdnoestoque = $_POST["qtdnoestoque"];
    }
}


//Verifica se a saida existe
//(� necess�rio por que se o usu�rio abrir uma nova janela na tela saidas.php o sistema exclui
//as saidas incompletas e vazias do usu�rio logado e portanto pode excluir a saida que est� 
//em andamento e ainda n�o incluiu nenhum produto)
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


//echo "retirar: $retirar_produto - consumidor: $consumidor - tiposaida: $tiposaida - saida: $saida - saipro: $saipro - passo:$passo<br>";
//echo "<br> <br>lote e produto: ($lote - $produto) <br>lote2 e produto2: ($lote2 - $produto2)<br> valuni:$valuni - qtd:$qtd - valtot:$valtot";
//CONTROLE DO PASSO
if ($passo == "") {
    $passo = 1;
} else if ($passo == 1) {
    if ($tiposaida == 3) {
        $passo = 1;
    } else {
        $passo = 2;
    }
}

if ($tiposaida == "") {
    $tiposaida = 1;
}

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "SAÍDAS";
if ($tiposaida == 1) {
    $tpl_titulo->SUBTITULO = "VENDA";
} else {
    $tpl_titulo->SUBTITULO = "DEVOLUÇÃO";
}
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "saidas.png";
$tpl_titulo->show();

//Verifica se há produtos no estoque
$sql = "SELECT etq_lote FROM estoque JOIN entradas on (etq_lote=ent_codigo) WHERE ent_quiosque=$usuario_quiosque";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas == 0) {
    echo "<br><br>";
    $tpl = new Template("templates/notificacao.html");
    $tpl->ICONES = $icones;
    $tpl->MOTIVO_COMPLEMENTO = "Para gerar uma venda ou devolução <b>é necessário que se tenha produtos em seu estoque</b>. <br>Clique no botão abaixo para ir para a tela de cadastro de entradas, que é onde você insere produtos em seu estoque!";
    $tpl->block("BLOCK_ATENCAO");
    $tpl->DESTINO = "entradas_cadastrar.php?operacao=cadastrar";
    $tpl->block("BLOCK_BOTAO");
    $tpl->show();
    exit;
}


//Inicio do formul�rio de saidas
$tpl1 = new Template("saidas_cadastrar.html");
$tpl1->LINK_DESTINO = "saidas_cadastrar.php?tiposaida=$tiposaida";
$tpl1->LINK_ATUAL = "saidas_cadastrar.php?tiposaida=$tiposaida";
$tpl1->ICONES_CAMINHO = $icones;

$tpl1->JS_CAMINHO = "saidas_cadastrar.js";
$tpl1->block("BLOCK_JS");


//Se for para deletar uma produto da lista
if ($retirar_produto == '1') { //Se o usu�rio clicou no excluir produto da lista
    //Antes de atualizar o estoque e remover item das saidas verificar se o item da saida que est� querendo deletar j� n�o foi deletado
    //(isso acontece quando o usu�rio pressiona F5 depois de clicar no bot�o remover item)        
    $sql_f5 = "
        SELECT * 
        FROM saidas_produtos
        WHERE saipro_saida=$saida
        AND saipro_codigo=$saipro
    ";
    $query_f5 = mysql_query($sql_f5);
    if (!$query_f5) {
        die("Erro de SQL F5 Remover item:" . mysql_error());
    }
    $linhas_f5 = mysql_num_rows($query_f5);
    if ($linhas_f5 > 0) {

        //Devolver para o estoque, e excluir o produto da saida
        //Verifica se o produto existe no estoque ou se foi eliminado por ter valor 0
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
            $qtd = str_replace('.', '', $qtd);
            $qtd = str_replace(',', '.', $qtd);
            $sql_repor = "
            UPDATE
                estoque 
            SET 
                etq_quantidade=(etq_quantidade+$qtd)
            WHERE
                etq_quiosque=$usuario_quiosque and
                etq_produto=$produto and
                etq_lote=$lote 
            ";
            $query_repor = mysql_query($sql_repor);
            if (!$query_repor) {
                die("Erro de SQL2:" . mysql_error());
            }
        } else { //O produto n�o existe mais no estoque, vamos inserir
            //Pegar os demais dados necess�rios para inserir no estoque
            $sql = "SELECT * FROM `entradas_produtos` join entradas on (entpro_entrada=ent_codigo) WHERE entpro_entrada=$lote";
            $query = mysql_query($sql);
            if (!$query) {
                die("Erro de SQL3:" . mysql_error());
            }
            while ($dados = mysql_fetch_assoc($query)) {
                $validade = $dados["entpro_validade"];
                $valuni = $dados["entpro_valorunitario"];
                $fornecedor = $dados["ent_fornecedor"];
            }
            //Interir o produto no estoque
            $sql16 = "INSERT INTO estoque (etq_quiosque,etq_produto,etq_fornecedor,etq_lote,etq_quantidade,etq_valorunitario,etq_validade)
			VALUES ('$usuario_quiosque','$produto','$fornecedor','$lote','$qtd','$valuni','$validade')";
            $query16 = mysql_query($sql16);
            if (!$query16) {
                die("Erro de SQL4 (inserir no estoque): " . mysql_error());
            }
        }


        //Elimina o protudo da Sa�da
        $sql_del = "DELETE FROM saidas_produtos WHERE saipro_saida=$saida and saipro_codigo=$saipro";
        $query_del = mysql_query($sql_del);
        if (!$query_del) {
            die("Erro de SQL5:" . mysql_error());
        }

        //Atualiza o status para incompleto
        $sql_status = "UPDATE saidas SET sai_status=2 WHERE sai_codigo=$saida";
        $query_status = mysql_query($sql_status);
        if (!$query_status)
            die("Erro de SQL Status: " . mysql_error());
    }
} else {
    //Independente se for cadastrou ou edi��o, s� inserir produto na saida se vier os dados do produto e lote etc. dos campos
    if (($saida != "") && ($produto != "") && ($lote != "")) {

        //Verifica a quantida atual do estoque
        $sql = "SELECT etq_quantidade FROM estoque WHERE etq_quiosque=$usuario_quiosque and etq_produto=$produto and etq_lote=$lote";
        $query = mysql_query($sql);
        if (!$query) {
            die("Erro de SQL7:" . mysql_error());
        }
        while ($dados = mysql_fetch_assoc($query)) {
            $qtdatual = $dados["etq_quantidade"];
        }

        //Calculando a quantidade final
        $qtdfinal = $qtdatual - $qtd;
        //echo "qtdfinal = $qtdatual - $qtd;";

        //Se a quantidade final do estoque ficar negativa ent�o n�o permitir seja inserido a saida deste produto e nem atualizado o estoque        
        //(Isso acontece quando o usu�rio inclui um produto na lista e pressiona F5)
        if ($qtdfinal >= 0) {
            //Inserindo os produtos na Sa�da
            $sql_saida_produto = "
            INSERT INTO
                saidas_produtos (saipro_saida, saipro_produto, saipro_lote, saipro_quantidade, saipro_valorunitario,saipro_valortotal)
            VALUES
                ('$saida','$produto','$lote','$qtd','$valuni',$valuni*$qtd)        
            ";
            $query_saida_produto = mysql_query($sql_saida_produto);
            if (!$query_saida_produto) {
                die("Erro de SQL6: " . mysql_error());
            }

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
    }
}


//Inserir saida principal com o status incompleto. Esse processo � feito uma unica vez, antes de come�ar 
//a inserção dos produtos dentro dessa saida
if (($saida == 0) && ($passo == 2)) {
    $datahoracadastro=$dataatual." ".$horaatual;
    
    $sql_saida = "
    INSERT INTO
        saidas (sai_quiosque, sai_caixaoperacaonumero, sai_consumidor, sai_tipo, sai_saidajustificada,sai_descricao, sai_datacadastro, sai_horacadastro,sai_status,sai_datahoracadastro,sai_usuarioquecadastrou)
    VALUES
        ('$usuario_quiosque','$usuario_caixa_operacao','$consumidor','$tiposaida','$motivo','$descricao','$dataatual','$horaatual',2,'$datahoracadastro',$usuario_codigo)        
    ";
    $query_saida = mysql_query($sql_saida);
    if (!$query_saida)
        die("Erro de SQL10: " . mysql_error());
    $saida = mysql_insert_id();
}

//Enviar ocultamento o numero da saida
$tpl1->CAMPOOCULTO_NOME = "saida";
$tpl1->CAMPOOCULTO_VALOR = $saida;
$tpl1->block("BLOCK_CAMPOSOCULTOS");


if ($tiposaida == 1) {

//Consumidor
    $tpl1->TITULO = "Consumidor";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->SELECT_NOME = "consumidor";
    $tpl1->SELECT_DESABILITADO = "";
    $tpl1->SELECT_OBRIGATORIO = " required ";
    $tpl1->SELECT_FOCO = "";
    if ($passo != 1) {
        $tpl1->SELECT_DESABILITADO = " disabled ";
    } else {
        $tpl1->SELECT_DESABILITADO = " ";
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
ORDER BY
    pes_nome
";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $tpl1->OPTION_VALOR = "0";
    $tpl1->OPTION_NOME = "Clientes Geral";
    $tpl1->OPTION_SELECIONADO = " selected ";
    $tpl1->block("BLOCK_SELECT_OPTION");
    while ($dados = mysql_fetch_array($query)) {
        $tpl1->OPTION_VALOR = $dados["pes_codigo"];
        $tpl1->OPTION_NOME = $dados["pes_nome"];
        if ($consumidor == $dados["pes_codigo"]) {
            $tpl1->OPTION_SELECIONADO = " selected ";
        } else {
            $tpl1->OPTION_SELECIONADO = " ";
        }
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_ITEM");
}

//Se o tipo de saida for Devolu��o
if ($tiposaida == 3) {

//Motivo
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
        die("Erro: " . mysql_error());
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
    $tpl1->block("BLOCK_ITEM");

    //Descri��o
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
}


if ($passo == 2) {

    //Etiqueta
    $tpl1->CAMPO_QTD_CARACTERES = "14";
    $tpl1->TITULO = "Etiqueta";
    $tpl1->ASTERISCO = "";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "etiqueta";
    $tpl1->CAMPO_TAMANHO = "15";
    $tpl1->CAMPO_FOCO = " autofocus ";
    $tpl1->CAMPO_VALOR = "";
    $tpl1->CAMPO_DESABILITADO = "";
    $tpl1->CAMPO_OBRIGATORIO = " ";
    $tpl1->CAMPO_ONKEYUP = "valida_etiqueta(this)";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_ITEM");

    //Etiqueta Produto Industrializado
    $tpl1->CAMPO_QTD_CARACTERES = "13";
    $tpl1->TITULO = "Etiqueta Código Único";
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
    $tpl1->block("BLOCK_TITULO");
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_ITEM");


    //Produto
    $tpl1->TITULO = "Produto";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->SELECT_NOME = "produto";
    $tpl1->SELECT_OBRIGATORIO = " required ";
    $tpl1->SELECT_FOCO = "  ";
    $tpl1->SELECT_DESABILITADO = "  ";
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_ITEM");

    //Fornecedor
    $tpl1->TITULO = "Fornecedor";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->SELECT_NOME = "fornecedor";
    $tpl1->SELECT_OBRIGATORIO = "  ";
    $tpl1->SELECT_FOCO = "  ";
    $tpl1->SELECT_DESABILITADO = "  ";
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_ITEM");

    //Lote
    $tpl1->TITULO = "Lote";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->SELECT_NOME = "lote";
    $tpl1->SELECT_OBRIGATORIO = " required ";
    $tpl1->SELECT_FOCO = "  ";
    $tpl1->SELECT_DESABILITADO = "  ";
    $tpl1->SELECT_AOTROCAR = "popula_lote_oculto(this.value);";
    $tpl1->SPAN2_NOME = "prateleira";
    $tpl1->SPAN2_VALOR = "";
    $tpl1->block("BLOCK_SPAN2");
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_ITEM");

    //Quantidade
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
    $tpl1->block("BLOCK_ITEM");

    //Valor Unit�rio
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
    $tpl1->block("BLOCK_ITEM");
    //Como o campo est� desabilitado � necess�rio criar um campo oculto. Este � populado via javascript
    $tpl1->CAMPOOCULTO_NOME = "valuni";
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
    $tpl1->block("BLOCK_ITEM");

    //Como o campo est� desabilitado � necess�rio criar um campo oculto. Este � populado via javascript
    $tpl1->CAMPOOCULTO_NOME = "valtot";
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
        pro_nome, pes_nome, saipro_lote, saipro_quantidade, saipro_valorunitario, saipro_valortotal,saipro_codigo,pro_codigo,saipro_codigo
    FROM 
        saidas_produtos
        JOIN produtos ON (saipro_produto=pro_codigo)    
        JOIN entradas ON (saipro_lote=ent_codigo)
        JOIN pessoas ON (ent_fornecedor=pes_codigo)
    WHERE
        saipro_saida=$saida
    ORDER BY
        saipro_codigo DESC
    ";
    $query_lista = mysql_query($sql_lista);
    if (!$query_lista)
        die("Erro: " . mysql_error());
    $linhas_lista = mysql_num_rows($query_lista);
    if ($linhas_lista == 0) {
        $tpl1->block("BLOCK_LISTA_NADA");
        $tpl1->SALVAR_DESABILIDADO = " disabled ";
    } else {
        $num = 0;
        $total_geral = 0;
        while ($dados_lista = mysql_fetch_array($query_lista)) {
            $num++;
            $tpl1->LISTA_GET_SAIPRO = $dados_lista["saipro_codigo"];
            $tpl1->LISTA_NUM = $dados_lista["saipro_codigo"];
            $tpl1->LISTA_PRODUTO = $dados_lista["pro_nome"];
            $tpl1->LISTA_PRODUTO_COD = $dados_lista["pro_codigo"];
            $tpl1->LISTA_FORNECEDOR = $dados_lista["pes_nome"];
            $tpl1->LISTA_LOTE = $dados_lista["saipro_lote"];
            $tpl1->LISTA_QTD = number_format($dados_lista["saipro_quantidade"], 3, ',', '.');
            $tpl1->LISTA_VALUNI = "R$ " . number_format($dados_lista["saipro_valorunitario"], 2, ',', '.');
            $tpl1->LISTA_VALTOT = "R$ " . number_format($dados_lista["saipro_valortotal"], 2, ',', '.');

            $total = $dados_lista["saipro_valortotal"];
            $total_geral = $total_geral + $total;
            $tpl1->block("BLOCK_LISTA_EXCLUIR");
            $tpl1->block("BLOCK_LISTA");
        }
    }
    $tpl1->TOTAL_GERAL = "R$ " . number_format($total_geral, 2, ',', '.');
    $tpl1->block("BLOCK_LISTAGEM");
    if ($tiposaida == 1) {
        $tpl1->FORM_LINK = "saidas_cadastrar2.php?tiposai=$tiposaida";
        $tpl1->block("BLOCK_SALVAR_VENDA");
    } else if ($tiposaida == 3) {
        $tpl1->FORM_LINK = "saidas_cadastrar3.php?tiposai=3";
        $tpl1->block("BLOCK_SALVAR_DEVOLUCAO");
    }
    $tpl1->block("BLOCK_BOTOES_RODAPE_SALVAR");
    $tpl1->LINK_CANCELAR = "saidas_deletar.php?codigo=$saida&tiposaida=$tiposaida";
    $tpl1->block("BLOCK_BOTOES_RODAPE_ELIMINAR");
    if ($tiposaida == 1) {
        $tpl1->LINK_CANCELAR = "saidas.php";
    } else {
        $tpl1->LINK_CANCELAR = "saidas_devolucao.php";
    }
    $tpl1->block("BLOCK_BOTOES_RODAPE_CANCELAR");
    $tpl1->block("BLOCK_BOTOES_RODAPE");
}

//Bot�o Continuar
$tpl1->BOTAO_TIPO = "submit";
if ($passo == 2) {
    $tpl1->BOTAO_DESABILITADO = " disabled ";
    $tpl1->BOTAO_VALOR = "INCLUIR";
} else {
    $tpl1->block("BLOCK_FOCO");
    $tpl1->BOTAO_VALOR = "CONTINUAR";
}
$tpl1->BOTAO_NOME = "botao_incluir";
$tpl1->BOTAO_FOCO = " ";
$tpl1->block("BLOCK_BOTAO1");
$tpl1->block("BLOCK_ITEM");

if ($tiposaida == 3) {
    $passo = 2;
}
$valor2 = "R$ " . number_format($total_geral, 2, ',', '.');
$tpl1->VALBRU2 = $valor2;
$tpl1->CAMPOOCULTO_NOME = "passo";
$tpl1->CAMPOOCULTO_VALOR = $passo;
$tpl1->block("BLOCK_CAMPOSOCULTOS");
$tpl1->show();
?>
