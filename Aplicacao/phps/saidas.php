<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if (($permissao_saidas_ver <> 1)) {
    header("Location: permissoes_semacesso.php");
    exit;
}


$tipopagina = "saidas";
include "includes.php";

//Verifica se o usuário é um caixa e não tem caixa aberto, se sim não pode acessar as vendas
if (($usuario_caixa_operacao=="")&&($usuario_grupo==4)) {
    header("Location: permissoes_semacesso.php");
    exit;
}

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "VENDAS";
$tpl_titulo->SUBTITULO = "LISTAGEM DE VENDAS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "vendas.png";
$tpl_titulo->show();


if ($usavendas!=1) {
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



//Se não tiver nehuma saida registra e nenhum caixa criado então sugerir criar caixa e abri-lo
if ($usacaixa==1) {
    $sql = "SELECT * FROM saidas WHERE sai_quiosque=$usuario_quiosque";
    $query = mysql_query($sql);
    if (!$query)    die("Erro: " . mysql_error());
    $linhas_saidas = mysql_num_rows($query);
    $sql = "SELECT * FROM caixas WHERE cai_quiosque=$usuario_quiosque and cai_status=1";
    $query = mysql_query($sql);
    if (!$query)    die("Erro: " . mysql_error());
    $linhas_caixa = mysql_num_rows($query);
    $linhas=$linhas_caixa+$linhas_saidas;
    if ($linhas == 0) {
        echo "<br><br>";
        $tpl = new Template("templates/notificacao.html");
        $tpl->ICONES = $icones;
        $tpl->MOTIVO_COMPLEMENTO = "Para poder realizar vendas é necessário que você tenha um caixa aberto.</b>Clique abaixo para registrar um novo caixa e após abri-lo!";
        $tpl->block("BLOCK_ATENCAO");
        $tpl->DESTINO = "caixas_cadastrar.php?operacao=cadastrar";
        $tpl->block("BLOCK_BOTAO");
        $tpl->show();
        exit;
    }
}



$tpl = new Template("templates/listagem.html");


//Filtro Inicio
$filtro_numero = $_REQUEST["filtro_numero"];
$filtro_produto = $_REQUEST["filtro_produto"];
$filtro_consumidor = $_POST["filtro_consumidor"];
$filtro_fornecedor = $_POST["filtro_fornecedor"];
$filtro_tipo = $_POST["filtro_tipo"];
$filtro_lote = $_REQUEST["filtro_lote"];

$usacomanda=usacomanda($usuario_quiosque);


//Se o usuario tem uma caixa definido então mostrar apenas as saidas deste
$caixaoperacao = pessoa_caixaoperacao($usuario_codigo);

$filtro_caixaoperacao = $_REQUEST["filtro_caixaoperacao"];
$filtro_id = $_REQUEST["filtro_id"];
$filtro_status = $_REQUEST["filtro_status"];
$filtro_areceber = $_REQUEST["filtro_areceber"];
$filtro_devolucao = $_REQUEST["filtro_devolucao"];
$tpl->LINK_FILTRO = "saidas.php";
$tpl->FORM_ONLOAD = "valida_filtro_saidas_numero()";


//Filtro Numero da saida
$tpl->CAMPO_TITULO = "Nº Saída";
$tpl->CAMPO_TAMANHO = "15";
$tpl->CAMPO_NOME = "filtro_numero";
$tpl->CAMPO_VALOR = $filtro_numero;
$tpl->CAMPO_QTD_CARACTERES = "";
$tpl->CAMPO_ONKEYUP = "valida_filtro_saidas_numero()";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_ESPACO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Filtro Consumidor
$tpl->SELECT_TITULO = "Consumidor";
$tpl->SELECT_NOME = "filtro_consumidor";
$tpl->SELECT_OBRIGATORIO = "";
$sql = "
    SELECT DISTINCT pes_codigo, pes_nome 
    FROM mestre_pessoas_tipo 
    join pessoas on (mespestip_pessoa=pes_codigo) 
    JOIN saidas on (sai_consumidor=pes_codigo) 
    WHERE mespestip_tipo=6 
    and pes_cooperativa=$usuario_cooperativa 
    and sai_tipo=1
    ORDER BY pes_nome
";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL1" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl->SELECT_OPTION_CODIGO = $dados["pes_codigo"];
    $tpl->SELECT_OPTION_NOME = $dados["pes_nome"];
    if ($filtro_consumidor == $dados["pes_codigo"])
        $tpl->SELECT_OPTION_SELECIONADO = " selected ";
    else
        $tpl->SELECT_OPTION_SELECIONADO = " ";
    $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
    
}
$tpl->block("BLOCK_FILTRO_SELECT_OPTIONPADRAO");
$tpl->block("BLOCK_FILTRO_SELECT");
$tpl->block("BLOCK_FILTRO_ESPACO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Filtro Produto
$tpl->CAMPO_TITULO = "Produto";
$tpl->CAMPO_TAMANHO = "20";
$tpl->CAMPO_NOME = "filtro_produto";
$tpl->CAMPO_VALOR = $filtro_produto;
$tpl->CAMPO_QTD_CARACTERES = "";
$tpl->CAMPO_ONKEYUP = "";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_ESPACO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Fornecedor
$tpl->SELECT_TITULO = "Fornecedor";
$tpl->SELECT_NOME = "filtro_fornecedor";
$tpl->SELECT_OBRIGATORIO = "";
$sql = "
SELECT DISTINCT pes_codigo, pes_nome 
FROM saidas_produtos
JOIN saidas on (saipro_saida=sai_codigo)
JOIN entradas on (saipro_lote=ent_codigo)
JOIN pessoas on (ent_fornecedor=pes_codigo)
WHERE pes_cooperativa=$usuario_cooperativa 
and sai_tipo=1
ORDER BY pes_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL2" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl->SELECT_OPTION_CODIGO = $dados["pes_codigo"];
    $tpl->SELECT_OPTION_NOME = $dados["pes_nome"];
    if ($filtro_fornecedor == $dados["pes_codigo"])
        $tpl->SELECT_OPTION_SELECIONADO = " selected ";
    else
        $tpl->SELECT_OPTION_SELECIONADO = " ";
    $tpl->block("BLOCK_FILTRO_SELECT_OPTION");
}
$tpl->block("BLOCK_FILTRO_SELECT_OPTIONPADRAO");
$tpl->block("BLOCK_FILTRO_SELECT");
$tpl->block("BLOCK_FILTRO_ESPACO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Nº Lote
$tpl->CAMPO_TITULO = "Nº Lote";
$tpl->CAMPO_TAMANHO = "10";
$tpl->CAMPO_NOME = "filtro_lote";
$tpl->CAMPO_VALOR = $filtro_lote;
$tpl->CAMPO_QTD_CARACTERES = "";
$tpl->CAMPO_ONKEYUP = "";
$tpl->block("BLOCK_FILTRO_CAMPO");
$tpl->block("BLOCK_FILTRO_ESPACO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Nº Operação Caixa
if (($caixaoperacao=="")&&($usacaixa==1)) {
    $tpl->CAMPO_TITULO = "Nº Caixa Oper.";
    $tpl->CAMPO_TAMANHO = "10";
    $tpl->CAMPO_NOME = "filtro_caixaoperacao";
    $tpl->CAMPO_VALOR = $filtro_caixaoperacao;
    $tpl->CAMPO_QTD_CARACTERES = "";
    $tpl->CAMPO_ONKEYUP = "";
    $tpl->block("BLOCK_FILTRO_CAMPO");
    $tpl->block("BLOCK_FILTRO_ESPACO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
}

//Filtro fim
$tpl->block("BLOCK_FILTRO");

//Comanda
if ($usacomanda==1) {
    $tpl->CAMPO_TITULO = "ID (Comanda)";
    $tpl->CAMPO_TAMANHO = "10";
    $tpl->CAMPO_NOME = "filtro_id";
    $tpl->CAMPO_VALOR = $filtro_id;
    $tpl->CAMPO_QTD_CARACTERES = "";
    $tpl->CAMPO_ONKEYUP = "";
    $tpl->block("BLOCK_FILTRO_CAMPO");
    $tpl->block("BLOCK_FILTRO_ESPACO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
}

//Filtro Status
$tpl->SELECT_TITULO = "Status";
$tpl->SELECT_NOME = "filtro_status";
$tpl->SELECT_OBRIGATORIO = "";
$tpl->block("BLOCK_FILTRO_SELECT_OPTIONPADRAO");
if ($filtro_status=="") $tpl->SELECT_OPTION_SELECIONADO = " selected ";
else $tpl->SELECT_OPTION_SELECIONADO = "  ";

$tpl->SELECT_OPTION_CODIGO = "1";
$tpl->SELECT_OPTION_NOME = "Completas";
if ($filtro_status == 1) $tpl->SELECT_OPTION_SELECIONADO = " selected ";
else $tpl->SELECT_OPTION_SELECIONADO = "  ";
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->SELECT_OPTION_CODIGO = "2";
$tpl->SELECT_OPTION_NOME = "Icompletas";
if ($filtro_status == 2) $tpl->SELECT_OPTION_SELECIONADO = " selected ";
else $tpl->SELECT_OPTION_SELECIONADO = "  ";
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->block("BLOCK_FILTRO_SELECT");
$tpl->block("BLOCK_FILTRO_ESPACO");
$tpl->block("BLOCK_FILTRO_COLUNA");


//Filtro A receber Caderninho
$tpl->SELECT_TITULO = "À receber";
$tpl->SELECT_NOME = "filtro_areceber";
$tpl->SELECT_OBRIGATORIO = "";
if ($filtro_areceber=="") {
    $tpl->SELECT_OPTION_SELECIONADO = " selected ";
} else {
    $tpl->SELECT_OPTION_SELECIONADO = "";
}
$tpl->block("BLOCK_FILTRO_SELECT_OPTIONPADRAO");
$tpl->SELECT_OPTION_CODIGO = "1";
$tpl->SELECT_OPTION_NOME = "Sim";
if ($filtro_areceber == 1) {
    $tpl->SELECT_OPTION_SELECIONADO = " selected ";
} else { 
    $tpl->SELECT_OPTION_SELECIONADO = "";
}
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->SELECT_OPTION_CODIGO = "0";
$tpl->SELECT_OPTION_NOME = "Não";
if ($filtro_areceber == '0') {
    $tpl->SELECT_OPTION_SELECIONADO = " selected ";
} else { 
    $tpl->SELECT_OPTION_SELECIONADO = "";
}
$tpl->block("BLOCK_FILTRO_SELECT_OPTION");
$tpl->block("BLOCK_FILTRO_SELECT");
$tpl->block("BLOCK_FILTRO_ESPACO");
$tpl->block("BLOCK_FILTRO_COLUNA");

//Filtro Nº Devolução
$usadevolucoessobrevendas=usadevolucoessobrevendas($usuario_quiosque);
if ($usadevolucoessobrevendas==1) {
    $tpl->CAMPO_TITULO = "Nº Devolução";
    $tpl->CAMPO_TAMANHO = "10";
    $tpl->CAMPO_NOME = "filtro_devolucao";
    $tpl->CAMPO_VALOR = $filtro_devolucao;
    $tpl->CAMPO_QTD_CARACTERES = "";
    $tpl->CAMPO_ONKEYUP = "";
    $tpl->block("BLOCK_FILTRO_CAMPO");
    $tpl->block("BLOCK_FILTRO_ESPACO");
    $tpl->block("BLOCK_FILTRO_COLUNA");
}


$tpl->block("BLOCK_FILTRO");

//Inicio da tabela de listagem

//Cabeçalho da lista

//Nº
$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "Nº";
$tpl->block("BLOCK_LISTA_CABECALHO");


//Comanda ID
if ($usacomanda==1) {
    $tpl->CABECALHO_COLUNA_TAMANHO = "";
    $tpl->CABECALHO_COLUNA_COLSPAN = "";
    $tpl->CABECALHO_COLUNA_NOME = "ID";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}


$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "2";
$tpl->CABECALHO_COLUNA_NOME = "DATA";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "CONSUMIDOR";
$tpl->block("BLOCK_LISTA_CABECALHO");

/*
$tpl->CABECALHO_COLUNA_TAMANHO = "50px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "QTD. PROD.";
$tpl->block("BLOCK_LISTA_CABECALHO");
*/

$tpl->CABECALHO_COLUNA_TAMANHO = "50px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "ITENS";
$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "70px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "TOTAL";
$tpl->block("BLOCK_LISTA_CABECALHO");

//$tpl->CABECALHO_COLUNA_TAMANHO = "";
//$tpl->CABECALHO_COLUNA_COLSPAN = "";
//$tpl->CABECALHO_COLUNA_NOME = "TOTAL BRUTO";
//$tpl->block("BLOCK_LISTA_CABECALHO");

$tpl->CABECALHO_COLUNA_TAMANHO = "70px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "DESC.";
$tpl->block("BLOCK_LISTA_CABECALHO");

if ($usacaixa==1) {
    $tpl->CABECALHO_COLUNA_TAMANHO = "";
    $tpl->CABECALHO_COLUNA_COLSPAN = "3";
    $tpl->CABECALHO_COLUNA_NOME = "CAIXA";
    $tpl->block("BLOCK_LISTA_CABECALHO");
}

$tpl->CABECALHO_COLUNA_TAMANHO = "40 px";
$tpl->CABECALHO_COLUNA_COLSPAN = "";
$tpl->CABECALHO_COLUNA_NOME = "PAG.";
$tpl->block("BLOCK_LISTA_CABECALHO");


$oper = 0;
$oper_tamanho = 0;
if ($permissao_saidas_ver == 1) {
    $oper = $oper + 1;
    $oper_tamanho = $oper_tamanho + 50;
}
if ($permissao_saidas_editar == 1) {
    $oper++;
    $oper_tamanho = $oper_tamanho + 50;
}


$tpl->CABECALHO_COLUNA_TAMANHO = "$oper_tamanho";
$tpl->CABECALHO_COLUNA_COLSPAN = "$oper";
$tpl->CABECALHO_COLUNA_NOME = "OPERAÇÕES";
$tpl->block("BLOCK_LISTA_CABECALHO");

//Linhas
//Verifica quais filtros devem ser considerados no sql principal
if ($filtro_numero <> "")
    $sql_filtro_numero = " and sai_codigo = $filtro_numero ";
if ($filtro_produto <> "")
    $sql_filtro_produto = " and ((pro_nome like '%$filtro_produto%')or(pro_referencia like '%$filtro_produto%')or(pro_tamanho like '%$filtro_produto%')or(pro_cor like '%$filtro_produto%')or(pro_descricao like '%$filtro_produto%')or (pro_codigo like '%$filtro_produto%'))";
if ($filtro_lote <> "")
    $sql_filtro_lote = " and saipro_lote = $filtro_lote ";
if ($filtro_consumidor <> "")
    $sql_filtro_consumidor = " and sai_consumidor = $filtro_consumidor ";
if ($filtro_caixa <> "")
    $sql_filtro_caixa = " and sai_consumidor = $filtro_caixa ";
if ($filtro_fornecedor <> "")
    $sql_filtro_fornecedor = " and ent_fornecedor = $filtro_fornecedor ";
if ($filtro_tipo <> "")
    $sql_filtro_tipo = " and sai_tipo = $filtro_tipo ";
if ($filtro_caixaoperacao <> "")
    $sql_filtro_caixaoperacao = " and sai_caixaoperacaonumero = $filtro_caixaoperacao ";
if ($filtro_id <> "")
    $sql_filtro_id = " and sai_id = $filtro_id ";
if ($filtro_status <> "")
    $sql_filtro_status = " and sai_status = $filtro_status ";
if ($filtro_devolucao <> "")
    $filtro_devolucao = " and saidev_numero = $filtro_devolucao ";
if ($filtro_areceber <> "")
    $sql_filtro_areceber = " and sai_areceber = $filtro_areceber ";
//Se o usuário logado possui um caixa definido para vendas, então mostrar apenas as vendas realizadas nesta operação de caixa
//Se o usuario filtrar por A RECEBER então deve ignorar a validação anterior para mostar todas vendas a receber de outros caixas e operações.
if ($filtro_areceber!=1) {
    if ($caixaoperacao > 0) {
        $sql_filtro_caixaoperacao= " AND sai_caixaoperacaonumero=$caixaoperacao";
    }
}



//Se  o for filtrado para mostrar apenas as vendas a receber, o resultado deve mostrar vendas de outros dias e outros caixas.
if ($filtro_areceber==1) $sql_filtro_caixaoperacao="";
$sql_filtro = $sql_filtro_numero . " " . $sql_filtro_consumidor . " " . $sql_filtro_caixa . " " . $sql_filtro_tipo . " " . $sql_filtro_produto . " " . $sql_filtro_lote . " " . $sql_filtro_fornecedor . " " . $sql_filtro_caixaoperacao." ".$sql_filtro_id." ".$sql_filtro_status. " ".$sql_filtro_areceber." ".$filtro_devolucao;


//SQL Principal das linhas
$sql = "
SELECT DISTINCT sai_codigo,sai_datacadastro,sai_horacadastro,sai_consumidor,sai_tipo,sai_totalliquido,sai_totalbruto,sai_status,sai_metpag,sai_areceber,sai_caixaoperacaonumero,pes_nome,cai_nome,pes_codigo,sai_usuarioquecadastrou,caiopo_operador, (SELECT pes_nome FROM pessoas p WHERE p.pes_codigo=sai_usuarioquecadastrou) as usuarioquecadastrou_nome,sai_id,sai_descontoforcado,sai_acrescimoforcado,sai_descontovalor
FROM saidas 
JOIN saidas_tipo on (sai_tipo=saitip_codigo) 
left join saidas_produtos on (saipro_saida=sai_codigo)
LEFT JOIN produtos on (saipro_produto=pro_codigo)
left join entradas on (saipro_lote=ent_codigo)
left join caixas_operacoes on (sai_caixaoperacaonumero=caiopo_numero)
left join caixas on (caiopo_caixa=cai_codigo)
left join pessoas on (pes_codigo=caiopo_operador)
LEFT JOIN saidas_devolucoes on (sai_codigo=saidev_saida)
WHERE sai_quiosque=$usuario_quiosque and
sai_tipo=1 $sql_filtro 
ORDER BY sai_codigo DESC
";
//Paginação
$query = mysql_query($sql);
if (!$query)
    die("Erro SQL Principal Paginação:" . mysql_error());
$linhas = mysql_num_rows($query);
$por_pagina = $usuario_paginacao;
$paginaatual = $_POST["paginaatual"];
$paginas = ceil($linhas / $por_pagina);
//Se � a primeira vez que acessa a pagina ent�o come�ar na pagina 1
if (($paginaatual == "") || ($paginas < $paginaatual) || ($paginaatual <= 0)) {
    $paginaatual = 1;
}
$comeco = ($paginaatual - 1) * $por_pagina;
$tpl->PAGINAS = "$paginas";
$tpl->PAGINAATUAL = "$paginaatual";
$tpl->PASTA_ICONES = "$icones";
$tpl->block("BLOCK_PAGINACAO");
$sql = $sql . " LIMIT $comeco,$por_pagina ";





$query = mysql_query($sql);
if (!$query)
    die("Erro 55: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas == 0) {
    $listanada = 9;
    $tpl->LISTANADA = 30;
    $tpl->block("BLOCK_LISTA_NADA");
} else {
    while ($dados = mysql_fetch_array($query)) {

        $numero = $dados["sai_codigo"];
        $saida=$numero;
        $data = $dados["sai_datacadastro"];
        $hora = $dados["sai_horacadastro"];
        $consumidor = $dados["sai_consumidor"];
        $tipo = $dados["sai_tipo"];
        $valorliquido = $dados["sai_totalliquido"];
        $valorbruto = $dados["sai_totalbruto"];
        $status = $dados["sai_status"];
        $areceber = $dados["sai_areceber"];
        $metodopag = $dados["sai_metpag"];
        $areceber = $dados["sai_areceber"];
        $caixa = $dados["cai_codigo"];
        $caixanome = $dados["cai_nome"];
        $caixaoperador = $dados["pes_codigo"];
        $usuarioquecadastrou=$dados["sai_usuarioquecadastrou"];
        $usuarioquecadastrou_nome=$dados["usuarioquecadastrou_nome"];
        $caixaoperadornome = $dados["pes_nome"];
        $caixaoperadorresponsavel = $dados["caiopo_operador"];
        $desconto = $dados["sai_descontovalor"];
        $troco_desconto = $dados["sai_descontoforcado"];
        $troco_acrescimo = $dados["sai_acrescimoforcado"];
        $id = $dados["sai_id"];

        //Cor de fundo da linha
        if ($status == 2) {
            if ($usuario_codigo == $operadorcaixa) {
                $tpl->LISTA_LINHA_CLASSE = "tabelalinhafundovermelho negrito";
            } else {
                $dataatual = date("Y-m-d");
                $horaatual = date("H:i:s");
                $tempo1 = $data . "_" . $hora;
                $tempo2 = $dataatual . "_" . $horaatual;
                $total_segundos = diferenca_entre_datahora($tempo1, $tempo2);
                if ($total_segundos > 5400) {
                    $tpl->LISTA_LINHA_CLASSE = "tabelalinhafundovermelho negrito";
                } else {
                    $tpl->LISTA_LINHA_CLASSE = "tabelalinhafundoamarelo negrito";
                }
            }
        } else {
            $tpl->LISTA_LINHA_CLASSE = "";
        }

        //Coluna Numero
        $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl->LISTA_COLUNA_CLASSE = "";
        $tpl->LISTA_COLUNA_VALOR = $numero;
        $tpl->block("BLOCK_LISTA_COLUNA");

        //Coluna ID
        $usacomanda=usacomanda($usuario_quiosque);
        if ($usacomanda==1) {
            $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
            $tpl->LISTA_COLUNA_CLASSE = "";
            $tpl->LISTA_COLUNA_VALOR = $id;
            $tpl->block("BLOCK_LISTA_COLUNA");
        }
        
        //Coluna Data    
        $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl->LISTA_COLUNA_VALOR = converte_data($data);
        $tpl->LISTA_COLUNA_CLASSE = "";
        $tpl->block("BLOCK_LISTA_COLUNA");

        //Coluna Hora
        $tpl->LISTA_COLUNA_ALINHAMENTO = "";
        $tpl->LISTA_COLUNA_CLASSE = "";
        $tpl->LISTA_COLUNA_VALOR = converte_hora($hora);
        $tpl->block("BLOCK_LISTA_COLUNA");

        //Coluna Consumidor
        $tpl->LISTA_COLUNA_ALINHAMENTO = "";
        $tpl->LISTA_COLUNA_CLASSE = "";

        if ($consumidor == 0) {
            $tpl->LISTA_COLUNA_VALOR = "Cliente Geral";
        } else {
            $sql2 = "SELECT pes_nome FROM pessoas WHERE pes_codigo=$consumidor";
            $query2 = mysql_query($sql2);
            if (!$query2)
                die("Erro 56: " . mysql_error());
            while ($dados2 = mysql_fetch_assoc($query2)) {
                $tpl->LISTA_COLUNA_VALOR = $dados2["pes_nome"];
            }
        }
        $tpl->block("BLOCK_LISTA_COLUNA");

        /*
        //Coluna Quantidade Produtos
        $tpl->LISTA_COLUNA_ALINHAMENTO = "center";
        $tpl->LISTA_COLUNA_CLASSE = "";
        $sql3 = "SELECT DISTINCT saipro_produto as qtd FROM saidas_produtos WHERE saipro_saida=$numero";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro: " . mysql_error());
        $tpl->LISTA_COLUNA_VALOR = "(" . mysql_num_rows($query3) . ")";
        $tpl->block("BLOCK_LISTA_COLUNA");
        */

        //Coluna Quantidade Itens
        $tpl->LISTA_COLUNA_ALINHAMENTO = "center";
        $tpl->LISTA_COLUNA_CLASSE = "";
        $sql3 = "SELECT DISTINCT saipro_codigo FROM saidas_produtos WHERE saipro_saida=$numero";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro: " . mysql_error());
        $tpl->LISTA_COLUNA_VALOR = "(" . mysql_num_rows($query3) . ")";
        $tpl->block("BLOCK_LISTA_COLUNA");


        //Total
        $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
        $tpl->LISTA_COLUNA_CLASSE = "";
        $tpl->LISTA_COLUNA_VALOR = "R$ " . number_format($valorbruto,2, ',', '.');
        $tpl->block("BLOCK_LISTA_COLUNA");


        //Desconto
        $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
        $desconto_mostra = number_format($desconto + $troco_desconto - $troco_acrescimo, 2,",",".");
        if ($desconto == 0)
            $tpl->LISTA_COLUNA_CLASSE = "";
        else if ($desconto_mostra > 0)
            $tpl->LISTA_COLUNA_CLASSE = "tabelalinhavermelha";
        else
            $tpl->LISTA_COLUNA_CLASSE = "tabelalinhaazul";
        $tpl->LISTA_COLUNA_VALOR = "$desconto_mostra";
        $tpl->block("BLOCK_LISTA_COLUNA");

        
        //Coluna Caixa
        if ($usacaixa==1) {
            $tpl->LISTA_COLUNA_ALINHAMENTO = "right";
            $tpl->LISTA_COLUNA_CLASSE = "";
            $tpl->LISTA_COLUNA_VALOR = $caixanome;
            $tpl->block("BLOCK_LISTA_COLUNA");
            $tpl->LISTA_COLUNA_ALINHAMENTO = "left";
            $tpl->LISTA_COLUNA_CLASSE = "";
            $tpl->LISTA_COLUNA_VALOR = $caixaoperadornome;
            $tpl->block("BLOCK_LISTA_COLUNA");
            if ($caixaoperadorresponsavel==$usuarioquecadastrou) {
                $tpl->ICONE2_TAMANHO = "10px";
                $tpl->ICONE2_ARQUIVO = $icones2 . "supervisor2.png";
                $tpl->OPERACAO2_NOME = "";
                $tpl->block("BLOCK_LISTA_COLUNA_ICONE2");            
            } else {
                $tpl->ICONE2_TAMANHO = "10px";
                $tpl->ICONE2_ARQUIVO = $icones2 . "supervisor.png";
                $tpl->OPERACAO2_NOME = "Venda efetuada por: $usuarioquecadastrou_nome";
                $tpl->block("BLOCK_LISTA_COLUNA_ICONE2");
            }
        }
        
        
        //Metodo de pagamento
        if ($metodopag == 1) {
            $tpl->ICONE_ARQUIVO = $icones . "dinheiro2.png";
            $tpl->OPERACAO_NOME = "Dinheiro";
            $tpl->block("BLOCK_LISTA_COLUNA_ICONE");
        } else if ($metodopag == 2) {
            $tpl->ICONE_ARQUIVO = $icones . "credit_card2.png";
            $tpl->OPERACAO_NOME = "Cartão Crédito";
            $tpl->block("BLOCK_LISTA_COLUNA_ICONE");
        } else if ($metodopag == 3) {
            $tpl->OPERACAO_NOME = "Cartão Débito";
            $tpl->ICONE_ARQUIVO = $icones . "credit_card.png";
            $tpl->block("BLOCK_LISTA_COLUNA_ICONE");
        } else if ($areceber == 1) {
            $tpl->OPERACAO_NOME = "Caderninho (A Receber)";
            $tpl->ICONE_ARQUIVO = $icones . "pendente.png";
            $tpl->block("BLOCK_LISTA_COLUNA_ICONE");
        } else if ($metodopag==4){
            $tpl->OPERACAO_NOME = "Cheque";
            $tpl->ICONE_ARQUIVO = $icones . "cheque1.png";
            $tpl->block("BLOCK_LISTA_COLUNA_ICONE");
        } else if ($metodopag==6){
            $tpl->OPERACAO_NOME = "Dinheiro + Cartão Débito";
            $tpl->ICONE_ARQUIVO = $icones . "dinheiro_cartao1.png";
            $tpl->block("BLOCK_LISTA_COLUNA_ICONE");
        } else if ($metodopag==7){
            $tpl->OPERACAO_NOME = "Dinheiro + Cartão Crédito";
            $tpl->ICONE_ARQUIVO = $icones . "dinheiro_cartao2.png";
            $tpl->block("BLOCK_LISTA_COLUNA_ICONE");
        } else {
            $tpl->OPERACAO_NOME = "Desconhecido";
            $tpl->ICONE_ARQUIVO = $icones . "nada.png";
            $tpl->block("BLOCK_LISTA_COLUNA_ICONE");
        }
        ;

        //Coluna Operações    
        $tpl->CODIGO = $numero;

        if ($permissao_saidas_ver == 1) {

            //detalhes
            $tpl->LINK = "saidas_ver.php";
            $tpl->LINK_COMPLEMENTO = "ope=3&tiposaida=1&passo=1";
            $tpl->ICONE_ARQUIVO = $icones . "detalhes.png";
            $tpl->OPERACAO_NOME = "Detalhes";
            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");
            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_TODAS");
        }

        //Verifica se algum produto desta saida foi acertado
        $sql22 = "SELECT saipro_acertado FROM `saidas_produtos` WHERE saipro_saida=$numero and saipro_acertado !=0";
        $query22 = mysql_query($sql22);
        if (!$query22)
            die("Erro de SQL (22):" . mysql_error());
        $linhas22 = mysql_num_rows($query22);

        //Verifica se algum produto desta saida foi fechado
        $sql23 = "SELECT saipro_fechado FROM `saidas_produtos` WHERE saipro_saida=$numero and saipro_fechado !=0";
        $query23 = mysql_query($sql23);
        if (!$query23)
            die("Erro de SQL (23):" . mysql_error());
        $linhas23 = mysql_num_rows($query23);

        $temdevolucoes=0;
        //Verifica se há produtos devolvidos nesta venda
        $sql18="SELECT * FROM saidas_devolucoes_produtos WHERE saidevpro_saida=$saida";
        if (!$query18 = mysql_query($sql18)) die("Erro CONSULTA DEVOLUCOES:" . mysql_error()."");
        $linhas18=mysql_num_rows($query18);
        if ($linhas18>0) $temdevolucoes=1;

        //Verifica se tem pagamentos a receber efetuados
        $tempagamentos=0;
        $sql17="SELECT * FROM saidas_pagamentos WHERE saipag_saida=$saida";
        if (!$query17 = mysql_query($sql17)) die("Erro CONSULTA PAGAMENTOS:" . mysql_error()."");
        $linhas17=mysql_num_rows($query17);
        if ($linhas17>0) $tempagamentos=1;


        //Verificar se foi emitido nota nesta venda
        $sql9="SELECT * FROM nfe_vendas WHERE nfe_numero=$saida";
        if (!$query9 = mysql_query($sql9)) die("Erro Tem Nota: (((" . mysql_error().")))");
        $linhas9 = mysql_num_rows($query9);    
        if ($linhas9>0) $temnota=1; else $temnota=0;    


        //editar 
        $permiteedicaoclientenavenda=permiteedicaoclientenavenda($usuario_quiosque);
        if ($permiteedicaoclientenavenda==1) $passo=1; else $passo=2;

        if ($permissao_saidas_editar == 1) {
            //Se algum produto ja foi acertado não pode editar
            if ($linhas22 > 0) {
                $tpl->OPERACAO_NOME = "Você não pode editar esta Saída porque algum produto desta venda já foi acertado com o fornecedor!";
                $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";
                $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");
            } else if ($linhas23 > 0) {
                $tpl->OPERACAO_NOME = "Você não pode editar esta saída porque algum produto desta venda já foi fechado/acertado!";
                $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";
                $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");
            } else {
                 // Se tiver devoluções não pode editar.
                if ($temdevolucoes==1) {
                    $tpl->OPERACAO_NOME = "Você não pode editar vendas que possuem DEVOLUÇÕES!";
                    $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";
                    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");
                } else {
                    
                    //Se for venda a receber e ter pelo menos um pagamento não pode mais editar.
                    if ($tempagamentos==1) {
                        $tpl->OPERACAO_NOME = "Você não pode editar vendas que possuem PAGAMENTOS efetuados!";
                        $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";
                        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");
                    } else {





                        //Se foi gerado nota fiscal não pode editar.
                        if ($temnota==1) {
                            $tpl->OPERACAO_NOME = "Você não pode editar vendas que possuem NOTA FISCAL GERADA";
                            $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";
                            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");
                        } else {
                            //Se for um operador de caixa deve permitir a edição de apenas a ultima venda realizada por ele sob algumas condições
                            if ($usuario_grupo == 4) {
                                //Verifica qual foi a ultima venda realizada por este caixa
                                $sql_ven = "SELECT max(sai_codigo) FROM saidas JOIN caixas_operacoes on sai_caixaoperacaonumero=caiopo_numero WHERE caiopo_operador=$usuario_codigo";
                                $query_ven = mysql_query($sql_ven);
                                if (!$query_ven)
                                    die("Erro de SQL Caixa Ultima Venda:" . mysql_error());
                                $dados_ven = mysql_fetch_array($query_ven);
                                $ultimo = $dados_ven[0];

                                
                                //Se esta Sa�da for a ultima Saída que o caixa efetuou                   
                                if (($numero == $ultimo) || ($status == 2)) {
                                    if ($status == 1) { //Se a venda ja foi concluída o caixa tem um limite de tempo para pode editá-la
                                        $dataatual = date("Y-m-d");
                                        $horaatual = date("H:i:s");
                                        $tempo1 = $data . "_" . $hora;
                                        $tempo2 = $dataatual . "_" . $horaatual;
                                        $total_segundos = diferenca_entre_datahora($tempo1, $tempo2);
                                        if ($total_segundos < 900) { //O caixa tem 15 minutos ap�s o inicio para editar esta venda j� concluida 
                                            $tpl->OPERACAO_NOME = "Editar";
                                            $tpl->LINK = "saidas_cadastrar.php";

                                            $tpl->LINK_COMPLEMENTO = "operacao=2&tiposaida=1&passo=$passo";
                                            $tpl->ICONE_ARQUIVO = $icones . "editar.png";
                                            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");
                                        } else {
                                            $tpl->OPERACAO_NOME = "Você não pode editar sua última venda porque já passou 15 minutos após a finalização da venda!";
                                            $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";
                                            $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");
                                        }
                                    } else { //Se for incompleta permitir que o caixa possa continuar a venda
                                        $tpl->OPERACAO_NOME = "Editar";
                                        $tpl->LINK = "saidas_cadastrar.php";
                                        $tpl->LINK_COMPLEMENTO = "operacao=2&tiposaida=1&passo=$passo";
                                        $tpl->ICONE_ARQUIVO = $icones . "editar.png";
                                        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");
                                    }
                                } else {

                                    $tpl->OPERACAO_NOME = "Você não pode editar vendas antigas! Se precisa alterar ou remover alguma venda, contate um supervisor!";
                                    $tpl->ICONE_ARQUIVO = $icones . "editar_desabilitado.png";
                                    $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_DESABILITADO");
                                }
                            } else {
                                $tpl->OPERACAO_NOME = "Editar";
                                $tpl->LINK = "saidas_cadastrar.php";
                                $tpl->LINK_COMPLEMENTO = "operacao=2&tiposaida=1&passo=$passo";
                                $tpl->ICONE_ARQUIVO = $icones . "editar.png";
                                $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO");
                            }
                        }
                    }
                }
            }
        }
        $tpl->block("BLOCK_LISTA_COLUNA_OPERACAO_TODAS");


        $tpl->block("BLOCK_LISTA");
    }
}

if ($tipopagina == "saidas") {
    //Vendas
    if (($permissao_saidas_cadastrar == 1) && ($usuario_quiosque != 0)) {
        if ($usuario_grupo == 4) {
            //Verifica se há vendas incompletas, se sim então impedir de fazer novas vendas. só pode ter no máximo 1 venda incompleta
            $sql8 = "
                SELECT sai_codigo 
                FROM saidas
                JOIN caixas_operacoes on sai_caixaoperacaonumero=caiopo_numero
                WHERE sai_tipo=1 
                and caiopo_operador=$usuario_codigo
                and sai_status=2
            ";
            $query8 = mysql_query($sql8);
            if (!$query8)
                die("Erro SQL Caixa Incompletas Botão cadastrar" . mysql_error());
            $linhas8 = mysql_num_rows($query8);
            if ($linhas8 > 1) {
                $tpl->CADASTRAR_NOME = "REALIZAR VENDA";
                $dica="Você precisa finalizar as vendas incompletas primeiro para pode retornar a realizar novas vendas! Os caixas só podem ter no máximo 2 vendas incompletas!";
                $tpl->TITULO="$dica";
                $tpl->block("BLOCK_RODAPE_BOTOES_DESABILITADOS");
                $tpl->DICA_NOME = "REALIZAR VENDA";
                /*$tpl->DICA = "$dica";
                $tpl->DICA_ARQUIVO = $icones . "atencao.png";
                $tpl->block("BLOCK_RODAPE_BOTOES_DICA");*/
            } else {
                $tpl->CADASTRAR_NOME = "REALIZAR VENDA";
                $tpl->LINK_CADASTRO = "saidas_cadastrar.php?tiposaida=1&operacao=1&passo=1";
                $tpl->block("BLOCK_RODAPE_BOTOES");
            }
        } else if (($usacaixa==1)&&($usuario_caixa=="")) {
            $tpl->CADASTRAR_NOME = "REALIZAR VENDA";
            $dica="Para realizar vendas é necessário definir um caixa aberto padrão!";
            $tpl->TITULO="$dica";
            $tpl->block("BLOCK_RODAPE_BOTOES_DESABILITADOS");
            $tpl->DICA_NOME = "REALIZAR VENDA";
            /*$tpl->DICA = "$dica";
            $tpl->DICA_ARQUIVO = $icones . "atencao.png";
            $tpl->block("BLOCK_RODAPE_BOTOES_DICA");*/
        } else {
            $tpl->CADASTRAR_NOME = "REALIZAR VENDA";
            $tpl->LINK_CADASTRO = "saidas_cadastrar.php?tiposaida=1&operacao=1&passo=1";
            $tpl->block("BLOCK_RODAPE_BOTOES");
        }
    }
}



$tpl->show();
include "rodape.php";
?>
