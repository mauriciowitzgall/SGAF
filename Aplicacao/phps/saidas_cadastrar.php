<?php
//Verifica se o usuário pode acessar a tela
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

//Verifica se o usuário é um caixa e não tem caixa aberto, se sim não pode acessar as vendas
if (($usuario_caixa_operacao=="")&&($usuario_grupo==4)) {
    header("Location: permissoes_semacesso.php");
    exit;
}

//CONTROLE DA OPERAÇÃO
$dataatual = date("Y/m/d");
$horaatual = date("H:i:s");
$operacao = $_GET["operacao"]; //Operação 1=Cadastrar 2=Editar 3=Ver

$retirar_produto = $_GET["retirar_produto"];
//Se for eliminação de um produto ja da lista então pegar por get
if ($retirar_produto == '1') {
    $consumidor = $_GET["consumidor"];
    $id = $_GET["id"];
    $tiposaida = $_GET["tiposaida"];
    $saida = $_GET["saida"];
    $saipro = $_GET["saipro"];
    $passo = $_GET["passo"];
    $lote = $_GET["lote"];
    $qtd = $_GET["qtd"];
    $produto = $_GET["produto"];
    $tipopessoa = $_GET["tipopessoa"];
} else { 
    if ($operacao == 2) { // Se for edição pega os dados principais da venda para popular campos
        $saida = $_GET["codigo"];
        $passo = "2";
        $sql = "
            SELECT * 
            FROM saidas 
            left join pessoas on (sai_consumidor=pes_codigo)
            WHERE sai_codigo=$saida
        ";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro de SQL11:" . mysql_error());
        while ($dados = mysql_fetch_assoc($query)) {
            $consumidor = $dados["sai_consumidor"];
            $consumidor_cpf = $dados["pes_cpf"];
            $consumidor_cnpj = $dados["pes_cnpj"];
            $tipopessoa = $dados["pes_tipopessoa"];
            
            $id = $dados["sai_id"];
            $tiposaida = $dados["sai_tipo"];
            $motivo = $dados["sai_saidajustificada"];
            $descricao = $dados["sai_descricao"];
        }
    } else { //Caso seja uma venda nova, cadastro
        $operacao=1;
        $saida = $_POST["saida"];
        $passo = $_POST["passo"];
        $consumidor = $_POST["consumidor"];
        $cliente_nome = $_POST["cliente_nome"];
        $tipopessoa = $_POST["tipopessoa"];
        $consumidor_cpf=$_POST["cpf"];
        $consumidor_cnpj=$_POST["cnpj"];

        if (($consumidor!="")&&($consumidor!=0)) { //foi selecionado uma pessoa
            $sql0="SELECT pes_cpf, pes_cnpj,pes_tipopessoa FROM pessoas WHERE pes_codigo=$consumidor";
            if (!$query0 = mysql_query($sql0)) die("Erro 0: " . mysql_error());
            $dados0=  mysql_fetch_assoc($query0);
            $consumidor_cpf=$dados0["pes_cpf"];
            $consumidor_cnpj=$dados0["pes_cnpj"];
            $tipopessoa = $dados0["pes_tipopessoa"];
        } 
        if ($tipopessoa=="") { //Pro padrão a pessoa é fisica, cpf
            $tipopessoa=1;
        }
        $id = $_POST["id"];
        $tiposaida = $_GET["tiposaida"];
        $motivo = $_POST["motivo"];
        $descricao = $_POST["descricao"];
        $saipro = "";
        $porcao = $_POST["porcao"];
        if ($porcao=="") $porcao=0;
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
}

//echo "valunietq:$valunietq valunisai:$valunisai valtotsai:$valtotsai";

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


//Inicio do formulário de saidas
$tpl1 = new Template("saidas_cadastrar.html");
$tpl1->LINK_DESTINO = "saidas_cadastrar.php?tiposaida=$tiposaida";
$tpl1->LINK_ATUAL = "saidas_cadastrar.php?tiposaida=$tiposaida";
$tpl1->ICONES_CAMINHO = $icones;

$tpl1->JS_CAMINHO = "saidas_cadastrar.js";
$tpl1->block("BLOCK_JS");

$tpl1->TR_ID="";


//Se for para deletar uma produto da lista
if ($retirar_produto == '1') { //Se o usuário clicou no excluir produto da lista
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
        } else { //O produto não existe mais no estoque, vamos inserir
            //Pegar os demais dados necessários para inserir no estoque
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


        //Elimina o protudo da Saída
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
    //Independente se for cadastrou ou edição, só inserir produto na saida se vier os dados do produto e lote etc. dos campos
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
            INSERT INTO saidas_produtos (
                saipro_saida, saipro_produto, saipro_lote, saipro_quantidade, saipro_valorunitario,saipro_valortotal,saipro_porcao,saipro_porcao_quantidade
            )
            VALUES (
                '$saida','$produto','$lote','$qtd','$valunisai',$valtotsai,$porcao,$porcao_qtd
            )        
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
        
        $sql1 = "
            INSERT INTO
                pessoas (pes_id,pes_nome,pes_cnpj,pes_cpf,pes_tipopessoa,pes_cidade,pes_datacadastro,pes_horacadastro,pes_cooperativa,pes_possuiacesso,pes_categoria,pes_usuarioquecadastrou,pes_quiosquequecadastrou)
            VALUES
                ($id,'$cliente_nome','$consumidor_cnpj2','$consumidor_cpf2',$tipopessoa,0,'$dataatual','$horaatual',$usuario_cooperativa,0,0,$usuario_codigo,$usuario_quiosque)        
        ";
        $query1 = mysql_query($sql1); if (!$query1) die("Erro de SQL1: " . mysql_error());
        $consumidor = mysql_insert_id();
        $sql2="INSERT INTO mestre_pessoas_tipo (mespestip_pessoa,mespestip_tipo) VALUES ($consumidor,6)";
        $query2 = mysql_query($sql2); if (!$query2) die("Erro de SQL2: " . mysql_error());

    }
    
    if ($id=="") $id=0;
    
    $sql_saida = "
    INSERT INTO
        saidas (sai_quiosque, sai_caixaoperacaonumero, sai_consumidor, sai_tipo, sai_saidajustificada,sai_descricao, sai_datacadastro, sai_horacadastro,sai_status,sai_datahoracadastro,sai_usuarioquecadastrou, sai_id)
    VALUES
        ('$usuario_quiosque','$usuario_caixa_operacao','$consumidor','$tiposaida','$motivo','$descricao','$dataatual','$horaatual',2,'$datahoracadastro',$usuario_codigo, $id)        
    ";
    $query_saida = mysql_query($sql_saida);
    if (!$query_saida)
        die("Erro de SQL10: " . mysql_error());
    $saida = mysql_insert_id();
    
    $operacao=1;
    
}

//Enviar ocultamento o numero da saida
$tpl1->CAMPOOCULTO_NOME = "saida";
$tpl1->CAMPOOCULTO_VALOR = $saida;
$tpl1->block("BLOCK_CAMPOSOCULTOS");
$tpl1->CAMPOOCULTO_NOME = "passo";
$tpl1->CAMPOOCULTO_VALOR = $passo2;
$tpl1->block("BLOCK_CAMPOSOCULTOS");


if ($tiposaida == 1) {

    //ID, Comanda, Ficha
    $tpl1->CAMPO_QTD_CARACTERES = "8";
    $tpl1->TITULO = "ID";
    $tpl1->ASTERISCO = "";
    $tpl1->block("BLOCK_TITULO");
    $tpl1->CAMPO_TIPO = "number";
    $tpl1->CAMPO_NOME = "id";
    $tpl1->CAMPO_TAMANHO = "8";
    $tpl1->CAMPO_ESTILO = "width:80px;";
    $tpl1->CAMPO_FOCO = "  ";
    $tpl1->CAMPO_VALOR = "$id";
    $tpl1->CAMPO_DESABILITADO = "";
    $tpl1->CAMPO_OBRIGATORIO = " required ";
    $tpl1->CAMPO_ONKEYUP = "";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_CAMPO");
    $tpl1->block("BLOCK_ITEM");

    //Consumidor Cliente
    $tpl1->CAMPO_QTD_CARACTERES = "14";
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
    $tpl1->CAMPO_QTD_CARACTERES = "70";
    $tpl1->CAMPO_FOCO = " ";
    $tpl1->CAMPO_VALOR = "$consumidor_cpf";
    if ($passo==2) {
        $tpl1->CAMPO_DESABILITADO = " disabled ";
    }
    $tpl1->CAMPO_OBRIGATORIO = "  ";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_ONKEYUP = "verifica_cpf(this.value)";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONBLUR = ""; 
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_CAMPO");
    //CNPJ
    $tpl1->CAMPO_ESTILO = "width:155px;";
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "cnpj";
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
    
    //Nome do cliente para cadastro
    $tpl1->CAMPO_TIPO = "text";
    $tpl1->CAMPO_NOME = "cliente_nome";
    $tpl1->CAMPO_TAMANHO = "";
    $tpl1->CAMPO_ESTILO = "width:180px;";
    $tpl1->CAMPO_FOCO = " ";
    $tpl1->CAMPO_VALOR = "$cliente_nome";
    $tpl1->CAMPO_DESABILITADO = " disabled ";
    $tpl1->CAMPO_OBRIGATORIO = " required ";
    $tpl1->CAMPO_ONKEYUP = "";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->block("BLOCK_CAMPO");
    
    //Selecionar Cliente
    $tpl1->SELECT2_NOME = "consumidor";
    $tpl1->SELECT2_DESABILITADO = "";
    $tpl1->SELECT2_OBRIGATORIO = " required ";
    $tpl1->SELECT2_FOCO = "";
    if ($passo != 1) {
        $tpl1->SELECT2_DESABILITADO = " disabled ";
    } else {
        $tpl1->SELECT2_DESABILITADO = " ";
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
    $query = mysql_query($sql);
    if (!$query)
        die("Erro 8: " . mysql_error());
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
    $tpl1->block("BLOCK_SELECT2");
    
    
    
    $tpl1->block("BLOCK_ITEM");
}


//Se o tipo de saida for Devolução
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
    
   
    //Verifica se o consumidor possui vendas incompleta
    if ($consumidor!="") { // Se for uma devolução, então não realizar essa verificação
        
        $sql4="SELECT * from saidas  WHERE sai_status=2 and sai_consumidor= $consumidor and sai_codigo not in ($saida)";
        if (!$query4 = mysql_query($sql4)) die("Erro 4:" . mysql_error());
        $linhas4 = mysql_num_rows($query4);

        //print_r($_REQUEST);

        if (($linhas4>0)&&($operacao==1)&&($consumidor<>0)) { 
            $tpl = new Template("templates/notificacao.html");
            $tpl->ICONES = $icones;
            //$tpl->MOTIVO_COMPLEMENTO = "";
            $tpl->block("BLOCK_ATENCAO");
            $tpl->LINK = "saidas_cadastrar.php?codigo=$saida&operacao=2&tiposaida=1";
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
    $tpl1->SELECT_AOTROCAR = " ";
    $tpl1->SELECT_OBRIGATORIO = " required ";
    $tpl1->SELECT_FOCO = "  ";
    $tpl1->SELECT_DESABILITADO = "  ";
    $tpl1->SELECT_CLASSE = " width:200px; ";
    $tpl1->block("BLOCK_SELECT");
    $tpl1->block("BLOCK_ITEM");

    //Fornecedor
    $tpl1->TITULO = "Fornecedor";
    $tpl1->SELECT_CLASSE = " width:210px; ";
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
    $tpl1->SELECT_CLASSE = " width:100px; ";
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
    $tpl1->SELECT_AOTROCAR = "";
    $tpl1->SPAN2_NOME = "porcao_qtd_label";
    $tpl1->SPAN2_VALOR = "";
    $tpl1->block("BLOCK_SPAN2");
    $tpl1->block("BLOCK_SELECT");
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
    $tpl1->CAMPO_FOCO = "";
    $tpl1->CAMPO_DESABILITADO = "";
    $tpl1->CAMPO_ONKEYPRESS = "";
    $tpl1->CAMPO_ONKEYUP = "porcoesqtd(); saidas_qtd();";
    $tpl1->CAMPO_ONKEYDOWN = "";
    $tpl1->CAMPO_ONFOCUS = "";
    $tpl1->CAMPO_OBRIGATORIO = " ";
    $tpl1->block("BLOCK_CAMPO");
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
        pro_nome, pes_nome, saipro_lote, saipro_quantidade, saipro_valorunitario, saipro_valortotal,saipro_codigo,pro_codigo,saipro_codigo,saipro_porcao,saipro_porcao_quantidade,propor_nome,protip_sigla,pro_tipocontagem
    FROM 
        saidas_produtos
        JOIN produtos ON (saipro_produto=pro_codigo)    
        JOIN entradas ON (saipro_lote=ent_codigo)
        JOIN pessoas ON (ent_fornecedor=pes_codigo)
        JOIN produtos_tipo ON (pro_tipocontagem=protip_codigo)
        LEFT JOIN produtos_porcoes ON (saipro_porcao=propor_codigo)
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
        $tpl1->block("BLOCK_LISTA_PORCAO_CABECALHO");
        $tpl1->block("BLOCK_LISTA_NADA");
        $tpl1->SALVAR_DESABILIDADO = " disabled ";
    } else {
        $num = 0;
        $total_geral = 0;
        $tpl1->block("BLOCK_LISTA_PORCAO_CABECALHO");
        while ($dados_lista = mysql_fetch_array($query_lista)) {
            $num++;
            $tpl1->LISTA_GET_SAIPRO = $dados_lista["saipro_codigo"];
            $tpl1->LISTA_NUM = $dados_lista["saipro_codigo"];
            $tpl1->LISTA_PRODUTO = $dados_lista["pro_nome"];
            $tpl1->LISTA_PRODUTO_COD = $dados_lista["pro_codigo"];
            $tpl1->LISTA_FORNECEDOR = $dados_lista["pes_nome"];
            $tpl1->LISTA_LOTE = $dados_lista["saipro_lote"];
            $tipocontagem=$dados_lista["pro_tipocontagem"];
            if (($tipocontagem==2)||($tipocontagem==3)) {
                $tpl1->LISTA_QTD = number_format($dados_lista["saipro_quantidade"], 3, ',', '.');
            } else {
                $tpl1->LISTA_QTD = number_format($dados_lista["saipro_quantidade"], 0, '', '.');
            }
            $tpl1->LISTA_TIPOCONTAGEM = $dados_lista["protip_sigla"];
            $tpl1->LISTA_PORCAO_NOME = $dados_lista["propor_nome"];
            $qtdporcao=$dados_lista["saipro_porcao_quantidade"];
            if ($qtdporcao==0) $qtdporcao="";
            $tpl1->LISTA_PORCAO_QTD = $qtdporcao;
            $tpl1->block("BLOCK_LISTA_PORCAO_LINHA");
            $tpl1->LISTA_VALUNI = "R$ " . number_format($dados_lista["saipro_valorunitario"], 2, ',', '.');
            $tpl1->LISTA_VALTOT = "R$ " . number_format($dados_lista["saipro_valortotal"], 2, ',', '.');
            $tpl1->LISTA_TIPOPESSOA = $tipopessoa;

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
    //$tpl1->block("BLOCK_FOCO");
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