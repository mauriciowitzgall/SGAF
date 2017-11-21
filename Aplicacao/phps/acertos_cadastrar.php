<?php

$titulopagina = "Acertos Cadastro/Edição";

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
$operacao = $_GET["operacao"];
$passo = $_POST["passo"];
if ($permissao_acertos_cadastrar <> 1) {
    if ($usuario_grupo == 5) {
        if ($permissao_acertos_ver == 1) {
            if ($operacao == 'simular') {
                $fornecedor = $usuario_codigo;
                //Verifica qual é a data do ultimo acerto para setar como data inicial. Senão tiver então pega data da primeria entrada
                $sql5="SELECT MAX(ace_data) as acedatade FROM acertos WHERE ace_fornecedor=$usuario_codigo and ace_quiosque=$usuario_quiosque";
                if (!$query5=  mysql_query($sql5)) die ("Erro5: ".mysql_error);
                $dados5=  mysql_fetch_array($query5);
                $result=$dados5[0];
                if (($result!="")) { //Se houverem acertos pega a data do ultimo acerto do fornecedor
                    $datade=$result;
                } else { //Senão tiver então pega data da primeria entrada
                    $sql6="SELECT MIN(ent_datacadastro) as entdatade FROM entradas WHERE ent_fornecedor=$usuario_codigo and ent_quiosque=$usuario_quiosque";
                    if (!$query6=  mysql_query($sql6)) die ("Erro6: ".mysql_error);
                    $dados6=  mysql_fetch_array($query6);
                    $datade=$dados6[0];
                }
                //A data final da simulação é a data do dia
                $dataate = date("Y-m-d");
                $passo = 2;
            } else {
                header("Location: permissoes_semacesso.php");
            }
        } else {
            header("Location: permissoes_semacesso.php");
        }
    } else {
        header("Location: permissoes_semacesso.php");
    }
}

$passa = $_REQUEST["passa"];
$tipopagina = "acertos";
include "includes.php";

//print_r($_REQUEST);
//Variaveis
if ($passo == "")
    $passo = 1;

if ($passo == 1) {
    $codigo = $_GET["codigo"];
    $dataate = date("Y-m-d");
} else {
    if ($operacao <> 'simular') {
        $operacao = $_POST["operacao"];
        $codigo = $_POST["codigo"];
        $fornecedor = $_POST["fornecedor"];
        $tipopessoa = $_POST["tipopessoa"];
        $datade = $_POST["datade2"];
        $dataate = $_POST["dataate"];
    }
}

//--------------------TEMPLATE TÍTULO PRINCIPAL--------------------
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ACERTOS DE CONSIGNAÇÕES";
$tpl_titulo->SUBTITULO = "CADASTRO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "consignacao.png";
$tpl_titulo->show();

//Verifica se há produtos no estoque
$sql = "SELECT sai_codigo FROM saidas WHERE sai_quiosque=$usuario_quiosque";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas == 0) {
    echo "<br><br>";
    $tpl = new Template("templates/notificacao.html");
    $tpl->ICONES = $icones;
    $tpl->block("BLOCK_ATENCAO");
    if ($usuario_grupo==5) {
        $tpl->MOTIVO_COMPLEMENTO = "Não há o que simular, até o momento o quiosque não efetuou vendas suas de seus produtos!";
        $tpl->block("BLOCK_BOTAO_VOLTAR");       
    } else {
        $tpl->MOTIVO_COMPLEMENTO = "Para que você faça acertos com os fornecedores, é necessário que existam vendas. <b>Você não efetuou nenhuma venda até agora!</b> <br>Clique no botão abaixo para ir para a tela de cadastro de saídas, que é onde você realiza vendas!";
        if (($identificacaoconsumidorvenda==3)&&($usacomanda==0)) $tpl->DESTINO = "saidas_cadastrar.php?tiposaida=1&operacao=1&passo=2";
        else $tpl->DESTINO = "saidas_cadastrar.php?tiposaida=1&operacao=1&passo=1";        
        $tpl->block("BLOCK_BOTAO");
    }
    $tpl->show();
    exit;
} else if ($passa != 1) {
    //Verifica se há taxas vinculadas
    $sql = "SELECT * FROM quiosques_taxas WHERE quitax_quiosque=$usuario_quiosque";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas == 0) {
        echo "<br><br>";
        $tpl = new Template("templates/notificacao.html");
        $tpl->ICONES = $icones;
        //$tpl->MOTIVO_COMPLEMENTO = "";
        $tpl->block("BLOCK_ATENCAO");
        //$tpl->block("BLOCK_BOTAO");
        $tpl->LINK = "acertos_cadastrar.php?passa=1";
        $tpl->MOTIVO = "Seu quiosque não possui taxas vinculadas! Se continuar, este acerto não efetuará nenhum desconto sobre o valor de venda!";
        $tpl->block("BLOCK_MOTIVO");
        $tpl->PERGUNTA = "Escolha 'Sim' para continuar, ou 'Não' para vincular uma taxa ao quiosque!";
        $tpl->block("BLOCK_PERGUNTA");
        $tpl->NAO_LINK = "quiosque_taxas_cadastrar.php?quiosque=$usuario_quiosque&operacao=cadastrar";
        $tpl->block("BLOCK_BOTAO_NAO_LINK");
        $tpl->block("BLOCK_BOTAO_SIMNAO");
        $tpl->show();
        exit;
    }
}



//--------------------TEMPLATE FORNECEDOR E BOTÃO --------------------
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "acertos_cadastrar.php";

//Tipo Pessoa
$tpl1->TITULO = "Tipo Pessoa";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "tipopessoa";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "tipopessoa";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_ONCHANGE = "acerto_popula_fornecedores(this.value);";
$tpl1->block("BLOCK_SELECT_ONCHANGE");
//$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_NORMAL");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO2");
$sql2 = "SELECT pestippes_codigo,pestippes_nome FROM pessoas_tipopessoa";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro2:" . mysql_error());


while ($dados2 = mysql_fetch_assoc($query2)) {
    $tpl1->OPTION_VALOR = $dados2["pestippes_codigo"];
    $tpl1->OPTION_NOME = $dados2["pestippes_nome"];
    if ($tipopessoa == $dados2["pestippes_codigo"])
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");

    $tpl1->block("BLOCK_SELECT_OPTION");
}
if (($passo > 1) || ($operacao == 'ver'))
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");




//Fornecedor
$tpl1->TITULO = "Fornecedor";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "fornecedor";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "fornecedor";
$tpl1->SELECT_ONCHANGE = "popula_acertos_dataminmax(this.value,$usuario_quiosque)";
$tpl1->block("BLOCK_SELECT_ONCHANGE");
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
$sql2 = "
SELECT DISTINCT
    pes_codigo,pes_nome
FROM
    pessoas        
    join mestre_pessoas_tipo on (mespestip_pessoa=pes_codigo)    
    join entradas on (ent_fornecedor=pes_codigo)
    join saidas_produtos on (saipro_lote=ent_codigo)
WHERE
   mespestip_tipo=5 and 
   ent_quiosque=$usuario_quiosque  and 
   ent_tiponegociacao=1
ORDER BY pes_nome
";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro2:" . mysql_error());
while ($dados2 = mysql_fetch_assoc($query2)) {
    $tpl1->OPTION_VALOR = $dados2["pes_codigo"];
    $tpl1->OPTION_NOME = $dados2["pes_nome"];
    if ($fornecedor == $dados2["pes_codigo"])
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");

    $tpl1->block("BLOCK_SELECT_OPTION");
}
if (($passo > 1) || ($operacao == 'ver'))
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");


//Data De Até
$dataatual = date("Y-m-d");
$tpl1->TITULO = "Período";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "date";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "datade";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$datade";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->TEXTO_ID = "";
$tpl1->TEXTO = " até ";
$tpl1->block("BLOCK_TEXTO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->CAMPO_TIPO = "date";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "dataate";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
if ($passo == 2) {
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
}
$tpl1->CAMPO_TAMANHO = "";
$tpl1->CAMPO_VALOR = "$dataate";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
if ($passo == 1) {

    $tpl1->LINHA_CLASSE = "some";
    $tpl1->block("BLOCK_LINHA_CLASSE");
}
$tpl1->LINHA_ID = "periodo";
$tpl1->block("BLOCK_LINHA_ID");

$tpl1->block("BLOCK_ITEM");





//CAMPOS OCULTOS
$tpl1->CAMPOOCULTO_NOME = "passo";
$tpl1->CAMPOOCULTO_VALOR = "2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->CAMPOOCULTO_NOME = "passa";
$tpl1->CAMPOOCULTO_VALOR = "$passa";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->CAMPOOCULTO_NOME = "acerto";
$tpl1->CAMPOOCULTO_VALOR = "$codigo";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->CAMPOOCULTO_NOME = "operacao";
$tpl1->CAMPOOCULTO_VALOR = "$operacao";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

$tpl1->CAMPOOCULTO_NOME = "datade2";
$tpl1->CAMPOOCULTO_VALOR = "$datade";
$tpl1->block("BLOCK_CAMPOSOCULTOS");


//Botão Continuar
if ($passo == 1) {
    $tpl1->BOTAO_TIPO = "submit";
    $tpl1->BOTAO_VALOR = "CONTINUAR";
    $tpl1->BOTAO_NOME = "continuar";
    $tpl1->block("BLOCK_BOTAO1_SEMLINK");
    $tpl1->block("BLOCK_BOTAO1");
}
//echo "Operação: $operacao <br>Passo: $passo <br>Codigo: $codigo <br>Fornecedor=$fornecedor";
$tpl1->show();



if ($passo == 2) {


    //Verifica se há produtos vendidos a serem acertados
    $sql = "
            SELECT pro_nome, round(sum(saipro_quantidade),2) as qtd, protip_sigla, avg(saipro_valorunitario) as valuni, round(sum(saipro_valortotal),2) as total
        FROM 
            saidas_produtos
            join produtos on (saipro_produto=pro_codigo)
            join produtos_tipo on (pro_tipocontagem=protip_codigo)
            join entradas on (saipro_lote=ent_codigo)
            join saidas on (saipro_saida=sai_codigo)
        WHERE
            saipro_acertado=    0 and
            ent_fornecedor=$fornecedor and
            ent_quiosque=$usuario_quiosque and
            ent_tiponegociacao=1 and
            sai_tipo=1 and
            sai_datacadastro BETWEEN '$datade' AND '$dataate' and
            sai_status=1
        GROUP BY 
            saipro_produto
        ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro55" . mysql_error());
    $linhas = mysql_num_rows($query);

    if ($linhas == 0) {
        $tpl11 = new Template("templates/notificacao.html");
        $tpl11->ICONES = $icones;
        $tpl11->block("BLOCK_ATENCAO");
        $tpl11->MOTIVO = "Não há nenhum produto vendido deste fornecedor até o momento, portanto não é possível realizar o acerto!";
        $tpl11->block("BLOCK_MOTIVO");
        $tpl11->block("BLOCK_BOTAO_VOLTAR");
        $tpl11->show();
    } else {

        //--------------------------TOTAL VENDIDO-------------------------------------
        $tpl2 = new Template("templates/lista1.html");

        //Título
        $tpl2_tit = new Template("templates/tituloemlinha_1.html");
        $tpl2_tit->LISTA_TITULO = "VENDAS";
        $tpl2_tit->block("BLOCK_QUEBRA1");
        $tpl2_tit->block("BLOCK_TITULO");
        $tpl2_tit->show();

        //Cabecalho
        $tpl2->CABECALHO_COLUNA_TAMANHO = "200px";
        $tpl2->CABECALHO_COLUNA_COLSPAN = "";
        $tpl2->CABECALHO_COLUNA_NOME = "PRODUTO";
        $tpl2->block(BLOCK_LISTA_CABECALHO);
        $tpl2->CABECALHO_COLUNA_TAMANHO = "200px";
        $tpl2->CABECALHO_COLUNA_COLSPAN = "2";
        $tpl2->CABECALHO_COLUNA_NOME = "QUANTIDADE";
        $tpl2->block(BLOCK_LISTA_CABECALHO);
        $tpl2->CABECALHO_COLUNA_TAMANHO = "150px";
        $tpl2->CABECALHO_COLUNA_COLSPAN = "";
        $tpl2->CABECALHO_COLUNA_NOME = "VALOR UNIT. MÉDIO";
        $tpl2->block(BLOCK_LISTA_CABECALHO);
        $tpl2->CABECALHO_COLUNA_TAMANHO = "150px";
        $tpl2->CABECALHO_COLUNA_COLSPAN = "";
        $tpl2->CABECALHO_COLUNA_NOME = "VALOR BRUTO";
        $tpl2->block(BLOCK_LISTA_CABECALHO);

        //Mostra todos os produtos que foram vendidos mas ainda n�o foram acertados
        $sql = "
            SELECT pro_nome, round(sum(saipro_quantidade),2) as qtd, protip_sigla, avg(saipro_valorunitario) as valuni, round(sum(saipro_valortotal),2) as total,protip_codigo
        FROM 
            saidas_produtos
            join produtos on (saipro_produto=pro_codigo)
            join produtos_tipo on (pro_tipocontagem=protip_codigo)
            join entradas on (saipro_lote=ent_codigo)
            join saidas on (saipro_saida=sai_codigo)
        WHERE
            saipro_acertado=0 and
            ent_fornecedor=$fornecedor and
            ent_quiosque=$usuario_quiosque and
            sai_tipo=1 and
            ent_tiponegociacao=1 and
            sai_datacadastro BETWEEN '$datade' AND '$dataate' and
            sai_status=1
        GROUP BY 
            saipro_produto
        ";

        $query = mysql_query($sql);
        if (!$query)
            die("Erro43" . mysql_error());
        $total_bruto = 0;
        while ($dados = mysql_fetch_assoc($query)) {
            $tpl2->LISTA_CLASSE = "tab_linhas2";
            $tpl2->block("BLOCK_LISTA_CLASSE");

            $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tpl2->LISTA_COLUNA_VALOR = $dados["pro_nome"];

            $tpl2->block("BLOCK_LISTA_COLUNA");
            $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tipocontagem = $dados["protip_codigo"];
            if (($tipocontagem == 2)||($tipocontagem == 3))
                $tpl2->LISTA_COLUNA_VALOR = number_format($dados["qtd"], 3, ',', '.');
            else
                $tpl2->LISTA_COLUNA_VALOR = number_format($dados["qtd"], 0, '', '.');
            $tpl2->block("BLOCK_LISTA_COLUNA");
            $tpl2->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tpl2->LISTA_COLUNA_VALOR = $dados["protip_sigla"];

            $tpl2->block("BLOCK_LISTA_COLUNA");
            $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($dados["valuni"], 2, ',', '.');

            $tpl2->block("BLOCK_LISTA_COLUNA");
            $tpl2->LISTA_COLUNA_ALINHAMENTO = "right";
            $tpl2->LISTA_COLUNA_CLASSE = "";
            $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($dados["total"], 2, ',', '.');
            $tpl2->block("BLOCK_LISTA_COLUNA");

            $total_bruto = $total_bruto + $dados["total"];
            $tpl2->block("BLOCK_LISTA");
        }
        //Rodap� da lisagem
        $tpl2->LISTA_CLASSE = "tabelarodape1";
        $tpl2->block("BLOCK_LISTA_CLASSE");
        $tpl2->LISTA_COLUNA_VALOR = " ";
        $tpl2->block("BLOCK_LISTA_COLUNA");
        $tpl2->LISTA_COLUNA_VALOR = " ";
        $tpl2->block("BLOCK_LISTA_COLUNA");
        $tpl2->LISTA_COLUNA_VALOR = " ";
        $tpl2->block("BLOCK_LISTA_COLUNA");
        $tpl2->LISTA_COLUNA_VALOR = " ";
        $tpl2->block("BLOCK_LISTA_COLUNA");
        $tpl2->LISTA_COLUNA_VALOR = "R$ " . number_format($total_bruto, 2, ",", ".");
        $tpl2->block("BLOCK_LISTA_COLUNA");
        $tpl2->block("BLOCK_LISTA");

        $tpl2->block("BLOCK_LISTA1");
        $tpl2->show();



        //--------------------------TAXAS-------------------------------------
        $tpl5 = new Template("templates/lista1.html");

        //Título
        $tpl5_tit = new Template("templates/tituloemlinha_1.html");
        $tpl5_tit->LISTA_TITULO = "TAXAS";
        $tpl5_tit->block("BLOCK_QUEBRA1");
        $tpl5_tit->block("BLOCK_TITULO");
        $tpl5_tit->show();

        //Cabecalho

        $tpl5->CABECALHO_COLUNA_TAMANHO = "200px";
        $tpl5->CABECALHO_COLUNA_COLSPAN = "";
        $tpl5->CABECALHO_COLUNA_NOME = "TAXAS";
        $tpl5->block(BLOCK_LISTA_CABECALHO);
        $tpl5->CABECALHO_COLUNA_TAMANHO = "100px";
        $tpl5->CABECALHO_COLUNA_COLSPAN = "2";
        $tpl5->CABECALHO_COLUNA_NOME = "VAL. REF.";
        $tpl5->block(BLOCK_LISTA_CABECALHO);
        $tpl5->CABECALHO_COLUNA_TAMANHO = "150px";
        $tpl5->CABECALHO_COLUNA_COLSPAN = "";
        $tpl5->CABECALHO_COLUNA_NOME = "VALOR";
        $tpl5->block(BLOCK_LISTA_CABECALHO);

        //Mostra o valor das taxas dos produtos a serem acertados
        $sql = "
            SELECT * 
            FROM quiosques_taxas 
            join taxas on (tax_codigo=quitax_taxa)    
            WHERE quitax_quiosque=$usuario_quiosque
            and tax_tiponegociacao=1
        ";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro43" . mysql_error());
        $taxas = 0;
        while ($dados = mysql_fetch_assoc($query)) {
            $tpl5->LISTA_CLASSE = "tab_linhas2";
            $tpl5->block("BLOCK_LISTA_CLASSE");

            $tpl5->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl5->LISTA_COLUNA_CLASSE = "";
            $tpl5->LISTA_COLUNA_VALOR = $dados["tax_nome"];
            $tpl5->block("BLOCK_LISTA_COLUNA");

            $tpl5->LISTA_COLUNA_ALINHAMENTO = "right";
            $tpl5->LISTA_COLUNA_CLASSE = "";
            $taxas = $taxas + $dados["quitax_valor"];
            $tpl5->LISTA_COLUNA_VALOR = number_format($dados["quitax_valor"], 2, ',', '.');
            $tpl5->block("BLOCK_LISTA_COLUNA");

            $tpl5->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl5->LISTA_COLUNA_CLASSE = "";
            $tpl5->LISTA_COLUNA_VALOR = "%";
            $tpl5->block("BLOCK_LISTA_COLUNA");

            $tpl5->LISTA_COLUNA_ALINHAMENTO = "right";
            $tpl5->LISTA_COLUNA_CLASSE = "";
            $valtax = $total_bruto * $dados["quitax_valor"] / 100;
            $tpl5->LISTA_COLUNA_VALOR = "R$ " . number_format($valtax, 2, ',', '.');
            $tpl5->block("BLOCK_LISTA_COLUNA");

            $valtaxtot = $valtaxtot + $valtax;
            $tpl5->block("BLOCK_LISTA");
        }
        //Rodap� da listagem
        $tpl5->LISTA_CLASSE = "tabelarodape1";
        $tpl5->block("BLOCK_LISTA_CLASSE");
        $tpl5->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl5->LISTA_COLUNA_VALOR = "Fornecedor";
        $tpl5->block("BLOCK_LISTA_COLUNA");
        $taxa_fornecedor = 100 - $taxas;
        $tpl5->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl5->LISTA_COLUNA_VALOR = number_format($taxa_fornecedor, 2, ',', '.');
        $tpl5->block("BLOCK_LISTA_COLUNA");
        $tpl5->LISTA_COLUNA_ALINHAMENTO = "left";
        $tpl5->LISTA_COLUNA_VALOR = "%";
        $tpl5->block("BLOCK_LISTA_COLUNA");
        $tpl5->LISTA_COLUNA_ALINHAMENTO = "right";
        $valor_fornecedor = $total_bruto - $valtaxtot;
        $tpl5->LISTA_COLUNA_VALOR = "R$ " . number_format($valor_fornecedor, 2, ",", ".");
        $tpl5->block("BLOCK_LISTA_COLUNA");
        $tpl5->block("BLOCK_LISTA");

        $tpl5->block("BLOCK_LISTA1");
        $tpl5->show();



        //--------------------------DADOS FINANCEIROS DO ACERTO-----------------------------------
        //Título
        $tpl4_tit = new Template("templates/tituloemlinha_1.html");
        $tpl4_tit->LISTA_TITULO = "DADOS FINANCEIROS DO ACERTO";
        $tpl4_tit->block("BLOCK_QUEBRA1");
        $tpl4_tit->block("BLOCK_TITULO");
        $tpl4_tit->show();



        $tpl4 = new Template("templates/cadastro_edicao_detalhes_2.html");
        $tpl4->LINK_DESTINO = "acertos_cadastrar2.php";

        $data = date("d/m/Y");
        $hora = date("H:i:s");

        //Total Bruto
        $tpl4->TITULO = "Total Bruto";
        $tpl4->block("BLOCK_TITULO");
        $tpl4->CAMPO_QTD_CARACTERES = "";
        $tpl4->CAMPO_TIPO = "text";
        $tpl4->CAMPO_NOME = "bruto";
        $tpl4->CAMPO_DICA = "";
        $tpl4->CAMPO_ID = "";
        $tpl4->CAMPO_TAMANHO = "";
        $tpl4->CAMPO_VALOR = "R$ " . number_format($total_bruto, 2, ',', '.');
        $tpl4->CAMPO_QTD_CARACTERES = "";
        $tpl4->block("BLOCK_CAMPO_NORMAL");
        $tpl4->block("BLOCK_CAMPO_DESABILITADO");
        $tpl4->block("BLOCK_CAMPO");
        $tpl4->block("BLOCK_CONTEUDO");
        $tpl4->block("BLOCK_ITEM");

        //Taxa Total
        $tpl4->TITULO = "Total de Taxas";
        $tpl4->block("BLOCK_TITULO");
        $tpl4->CAMPO_QTD_CARACTERES = "";
        $tpl4->CAMPO_TIPO = "text";
        $tpl4->CAMPO_NOME = "valtaxtot";
        $tpl4->CAMPO_DICA = "";
        $tpl4->CAMPO_ID = "";
        $tpl4->CAMPO_TAMANHO = "";
        $tpl4->CAMPO_VALOR = "R$ " . number_format($valtaxtot, 2, ',', '.');
        $tpl4->CAMPO_QTD_CARACTERES = "";
        $tpl4->block("BLOCK_CAMPO_NORMAL");
        $tpl4->block("BLOCK_CAMPO_DESABILITADO");
        $tpl4->block("BLOCK_CAMPO");
        $tpl4->block("BLOCK_CONTEUDO");
        $tpl4->block("BLOCK_ITEM");


        //Valor Pendente
        $tpl4->TITULO = "Valor Pendente";
        $tpl4->block("BLOCK_TITULO");
        $tpl4->CAMPO_QTD_CARACTERES = "";
        $tpl4->CAMPO_TIPO = "text";
        $tpl4->CAMPO_NOME = "valpen";
        $tpl4->CAMPO_DICA = "";
        $tpl4->CAMPO_ID = "";
        $tpl4->CAMPO_TAMANHO = "";
        $sql = "
        SELECT ace_valorpendente
        FROM acertos
        WHERE ace_codigo = (
            SELECT max(ace_codigo) 
            FROM acertos 
            WHERE ace_fornecedor=$fornecedor 
            and ace_quiosque=$usuario_quiosque
        )";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro53" . mysql_error());
        $dados = mysql_fetch_assoc($query);
        $valpen = $dados["ace_valorpendente"];
        $tpl4->CAMPO_VALOR = "R$ " . number_format($valpen, 2, ",", ".");
        $tpl4->CAMPO_QTD_CARACTERES = "";
        $tpl4->block("BLOCK_CAMPO_NORMAL");
        $tpl4->block("BLOCK_CAMPO_DESABILITADO");
        $tpl4->block("BLOCK_CAMPO");
        $tpl4->block("BLOCK_CONTEUDO");
        $tpl4->block("BLOCK_ITEM");

        //Valor Total
        $tpl4->TITULO = "Valor Total";
        $tpl4->block("BLOCK_TITULO");
        $tpl4->CAMPO_QTD_CARACTERES = "";
        $tpl4->CAMPO_TIPO = "text";
        $tpl4->CAMPO_NOME = "valtot";
        $tpl4->CAMPO_DICA = "";
        $tpl4->CAMPO_ID = "";
        $tpl4->CAMPO_TAMANHO = "";
        $valtot = $total_bruto - $valtaxtot + $valpen;
        $tpl4->CAMPO_VALOR = "R$ " . number_format($valtot, 2, ",", ".");
        $tpl4->CAMPO_QTD_CARACTERES = "";
        $tpl4->block("BLOCK_CAMPO_NORMAL");
        $tpl4->block("BLOCK_CAMPO_DESABILITADO");
        $tpl4->block("BLOCK_CAMPO");
        $tpl4->block("BLOCK_CONTEUDO");
        $tpl4->block("BLOCK_ITEM");

        if ($operacao <> 'simular') {

            //Valor Pago
            $tpl4->TITULO = "Valor Pago";
            $tpl4->block("BLOCK_TITULO");
            $tpl4->CAMPO_QTD_CARACTERES = "";
            $tpl4->CAMPO_TIPO = "text";
            $tpl4->CAMPO_NOME = "valpago";
            $tpl4->CAMPO_DICA = "";
            $tpl4->CAMPO_ID = "valpago";
            $tpl4->CAMPO_ONKEYUP = "valorpago()";
            $tpl4->CAMPO_ONCLICK = "this.select()";
            $tpl4->CAMPO_TAMANHO = "";
            if ($valtot < 0)
                $valpagopadrao = 0;
            else
                $valpagopadrao = $valtot;
            $tpl4->CAMPO_VALOR = "R$ " . number_format($valpagopadrao, 2, ",", ".");
            $tpl4->CAMPO_QTD_CARACTERES = "";
            $tpl4->block("BLOCK_CAMPO_NORMAL");
            $tpl4->block("BLOCK_CAMPO_OBRIGATORIO");
            $tpl4->block("BLOCK_CAMPO");
            $tpl4->block("BLOCK_CONTEUDO");
            $tpl4->block("BLOCK_ITEM");
        }


        $tpl4->CAMPOOCULTO_NOME = "data";
        $tpl4->CAMPOOCULTO_VALOR = "$data";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "hora";
        $tpl4->CAMPOOCULTO_VALOR = "$hora";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "datade2";
        $tpl4->CAMPOOCULTO_VALOR = "$datade";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "dataate2";
        $tpl4->CAMPOOCULTO_VALOR = "$dataate";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "$supervisor";
        $tpl4->CAMPOOCULTO_VALOR = "supervisor";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "fornecedor";
        $tpl4->CAMPOOCULTO_VALOR = "$fornecedor";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "total_bruto";
        $tpl4->CAMPOOCULTO_VALOR = "$total_bruto";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "valtaxas";
        $tpl4->CAMPOOCULTO_VALOR = "$valtaxtot";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "valpen";
        $tpl4->CAMPOOCULTO_VALOR = "$valpen";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "valtot";
        $tpl4->CAMPOOCULTO_VALOR = "$valtot";
        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        $tpl4->CAMPOOCULTO_NOME = "operacao";
        $tpl4->CAMPOOCULTO_VALOR = "$operacao";

        $tpl4->block("BLOCK_CAMPOSOCULTOS");

        if ($usuario_grupo == 5) {
            //Bot�o Voltar
            $tpl4->BOTAO_LINK = "acertos.php";
            $tpl4->BOTAO_NOME = "VOLTAR";
            $tpl4->block("BLOCK_BOTAO_VARIADO");
        } else {
            //Bot�o Salvar
            $tpl4->BOTAO_NOME = "CONFIRMAR ACERTO";
            $tpl4->block("BLOCK_BOTAO_GERAL");

            //Bot�o Cancelar
            $tpl4->BOTAO_LINK = "acertos.php";
            $tpl4->block("BLOCK_BOTAO_CANCELAR");
        }
        $tpl4->block("BLOCK_BOTOES");
        $tpl4->show();
    }
}



include "rodape.php";
?>
