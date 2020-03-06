<?php

$titulopagina = "SGAF";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($quiosque_revenda <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
$tipopagina = "negociacoes";
include "includes.php";

$datacadastro = date("Y-m-d");
$horacadastro = date("H:i:s");
$dataini = $_POST["dataini"];
$datafim = $_POST["datafim"];
$horaini = $_POST["horaini"];
$horafim = $_POST["horafim"];
$datafim_datetime=$datafim." ".$horafim;
$dataini_datetime=$dataini." ".$horaini;

$supervisor = $usuario_codigo;
$totalvenda = $_POST["totalvenda"];
$totalcusto = $_POST["totalcusto"];
$totallucro = $_POST["totallucro"];
$totaltaxas = $_POST["totaltaxas"];
$totaltaxaquiosque = $_POST["totaltaxaquiosque"];
$qtdvendas = $_POST["qtdvendas"];
$qtdprodutos = $_POST["qtdprodutos"];
$qtdfornecedores = $_POST["qtdfornecedores"];
$qtdlotes = $_POST["qtdlotes"];

include "controller/classes.php";


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "FECHAMENTO DE REVENDAS";
$tpl_titulo->SUBTITULO = "CADASTRO DE FECHAMENTOS DE REVENDAS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "fechamentos.png";
$tpl_titulo->show();


//Verifica se tem produtos a serem acertados
$sql="
SELECT saipro_produto
FROM saidas_produtos
JOIN entradas on (saipro_lote = ent_codigo)
JOIN saidas on (sai_codigo = saipro_saida)
WHERE saipro_fechado = 0
AND ent_tiponegociacao = 2
AND ent_quiosque = $usuario_quiosque
AND sai_tipo = 1
AND sai_status = 1
";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas==0) {
    $tpl11 = new Template("templates/notificacao.html");
    $tpl11->ICONES = $icones;
    $tpl11->block("BLOCK_ATENCAO");
    $tpl11->MOTIVO = "Não há nenhum produto de revenda vendido que não tenha sido acertado. Portanto não é possível realizar fechamento!";
    $tpl11->block("BLOCK_MOTIVO");
    $tpl11->DESTINO = "acertos.php";
    $tpl11->block("BLOCK_BOTAO");
    $tpl11->show();
    exit;    
}

//Inserir registro do acerto na tabela de acertos
$sql = "
INSERT INTO 
    fechamentos (
        fch_datacadastro,
        fch_horacadastro,
        fch_dataini,
        fch_datafim,
        fch_supervisor,
        fch_totalvenda,
        fch_totalcusto,
        fch_totallucro,        
        fch_totaltaxas,        
        fch_totaltaxaquiosque,        
        fch_qtdvendas,        
        fch_qtdprodutos,        
        fch_qtdfornecedores,        
        fch_qtdlotes,
        fch_quiosque
    )
VALUES (
    '$datacadastro',
    '$horacadastro',
    '$dataini_datetime',
    '$datafim_datetime',
    '$supervisor',
    '$totalvenda',
    '$totalcusto',
    '$totallucro',
    '$totaltaxas',
    '$totaltaxaquiosque',
    '$qtdvendas',
    '$qtdprodutos',
    '$qtdfornecedores',
    '$qtdlotes',
    '$usuario_quiosque'
    )
";
$query = mysql_query($sql);
if (!$query)
    die("Erro Principal: " . mysql_error());
$ultimo = mysql_insert_id();

echo "<br><br>";


//Inserir as taxas do acerto
//Verifica quais taxas que o quiosque tem para o acerto em questào
$sql2 = "
SELECT quitax_taxa, quitax_valor 
FROM quiosques_taxas 
JOIN taxas on (quitax_taxa=tax_codigo) 
WHERE quitax_quiosque in ($usuario_quiosque) 
and tax_tiponegociacao = 2
";
$query2 = mysql_query($sql2);
if (!$query2)
    die("Erro: " . mysql_error());
while ($dados2 = mysql_fetch_assoc($query2)) {
    
    $taxa = $dados2["quitax_taxa"];
    $taxaref = $dados2["quitax_valor"];
    $taxavalor = $totallucro * $taxaref / 100;    
    $sql5 = "
    INSERT INTO fechamentos_taxas (
        fchtax_fechamento,
        fchtax_taxa,
        fchtax_referencia,
        fchtax_valor
    ) VALUES (
        '$ultimo',
        '$taxa',
        '$taxaref',
        '$taxavalor'
    )";
    $query5 = mysql_query($sql5);
    if (!$query5)
        die("Erro taxas: " . mysql_error());
}



//Atribuir como fechado os produtos vendidos
$sql8="
    UPDATE saidas_produtos
    JOIN entradas on (saipro_lote=ent_codigo)
    JOIN saidas on (sai_codigo=saipro_saida)
    SET saipro_fechado='$ultimo'
    WHERE saipro_fechado=0
    AND ent_tiponegociacao=2     
    AND ent_quiosque=$usuario_quiosque
    AND sai_tipo=1
    AND sai_datahoracadastro BETWEEN '$dataini_datetime' AND '$datafim_datetime:59'
    AND sai_status=1      
";
$query8 = mysql_query($sql8);
if (!$query8)
    die("Erro: " . mysql_error());

$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$tpl6->block("BLOCK_CONFIRMAR");
//$tpl6->block("BLOCK_CADASTRADO");    
$tpl6->MOTIVO = "O fechamento foi registrado com sucesso!";

$tpl6->LINK = "acertos_revenda_cadastrar3.php?codigo=$ultimo&ope=5";
$tpl6->block("BLOCK_MOTIVO");
$tpl6->PERGUNTA = "Deseja imprimir o relatórios do fechamento?";
$tpl6->block("BLOCK_PERGUNTA");
$tpl6->NAO_LINK = "acertos_revenda.php";
$tpl6->LINK_TARGET = "_blank";
$tpl6->block("BLOCK_BOTAO_NAO_LINK");
$tpl6->block("BLOCK_BOTAO_SIMNAO");
$tpl6->show();

include "rodape.php";
?>
