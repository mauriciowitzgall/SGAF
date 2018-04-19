<?php

require "login_verifica.php";
if ($permissao_saidas_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "saidas";
include "includes.php";

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


//print_r($_REQUEST);

//Verifica se o usuário é um caixa e não tem caixa aberto, se sim não pode acessar as vendas
if (($usuario_caixa_operacao=="")&&($usuario_grupo==4)) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tiposai = $_REQUEST["tiposai"];
$passo = $_REQUEST["passo"];
$saida = $_REQUEST["saida"];

//Verifica tipo da entrada, se é devolução ou venda
$sql_f = "SELECT sai_tipo FROM saidas WHERE sai_codigo=$saida";
if (!$query_f = mysql_query($sql_f))
    die("Erro F: " . mysql_error());
$dados_f = mysql_fetch_array($query_f);
$tipo = $dados_f["sai_tipo"];

$valbru=$_REQUEST["valbru2"];
$descper = $_REQUEST["descper2"];
$descval = $_REQUEST["descval2"];
$total = $_REQUEST["total2"];
$metodopag = $_REQUEST["metodopag2"];
$recebidodinheiro = $_REQUEST["recebidodinheiro"];
$recebidocartao = $_REQUEST["recebidocartao"];
$cartaobandeira = $_REQUEST["cartaobandeira"];


$areceber = $_REQUEST["areceber2"];
$dinheiro = $_REQUEST["dinheiro2"];
if ($areceber == 1)
    $troco = 0;
else 
    $troco = $_REQUEST["troco2"];
$troco_devolvido = number_format(dinheiro_para_numero($_REQUEST["troco_devolvido"]), 2, '.', '');

//rint_r($_REQUEST);
//Calcula o valor do desconto ou acr�scimo for�ado
if ($troco_devolvido == "")
    $troco_devolvido = 0;
if ($troco == "")
    $troco = 0;
if ($total == "")
    $total = 0;

$forcado = number_format($troco - $troco_devolvido, 2, '.', '');
if ($forcado > 0) {
    $forcadodesc = 0;
    $forcadoacre = $forcado;
} else {
    $forcadodesc = $forcado * -1;
    $forcadoacre = 0;
}
//Calcula o valor liquido total da Sa�da
$totalliq = $total + $forcado;

/*
  echo "<br>valbru=$valbru<br>";
  echo "descper=$descper<br>";
  echo "descval=$descval<br>";
  echo "total=$total<br>";
  echo "dinheiro=$dinheiro<br>";
  echo "troco=$troco<br>";

  echo "trocodevolvido=$troco_devolvido<br>";
  echo "forcado=$forcado<br>";
  echo "totalliq=$totalliq<br>";
 */


//Se for saida do tipo Venda (não devolução)
if ($tipo == 1) {
    $sql_filtro = " 
       sai_descontopercentual='$descper',
       sai_descontovalor='$descval',
       sai_valorecebido='$dinheiro',
       sai_troco='$troco',
       sai_trocodevolvido='$troco_devolvido',
       sai_descontoforcado='$forcadodesc',
       sai_acrescimoforcado='$forcadoacre',
       sai_areceber=$areceber, 
       sai_metpag='$metodopag',
       sai_recebidodinheiro='$recebidodinheiro',
       sai_recebidocartao='$recebidocartao',
       sai_cartaobandeira='$cartaobandeira',
   ";
}
$sql = "
UPDATE
    saidas
SET
    sai_totalbruto='$valbru',    
    sai_totalcomdesconto='$total',  
    sai_totalliquido='$totalliq',
    $sql_filtro
    sai_status=1
WHERE
    sai_codigo=$saida
";
if (!mysql_query($sql))
    die("Erro: " . mysql_error());


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
if ($tiposai == 1) {
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


//Verifica se é uma conta areceber, se for avaliar se o cliente foi identificado! Caso não tenha sido sugerir edição.
$sql = "SELECT * FROM saidas WHERE sai_codigo=$saida";
if (!$query=mysql_query($sql)) die("Erro SQL 8:" . mysql_error());
$dados=mysql_fetch_assoc($query);
$consumidor=$dados["sai_consumidor"];
$areceber=$dados["sai_areceber"];
if (($consumidor==0)&&($areceber==1)) {

  $tpl6 = new Template("templates/notificacao.html");
  $tpl6->ICONES = $icones;

  //$tpl6->block("BLOCK_CONFIRMAR");
  $tpl6->block("BLOCK_ATENCAO");
  //$tpl6->block("BLOCK_CADASTRADO");    
  $tpl6->MOTIVO = "Não é permitido realizar vendas A RECEBER sem identificação do consumidor!<br><br>Você deve Editar esta venda e <b>IDENTIFICAR o CONSUMIDOR! </b><br>";
  $tpl6->block("BLOCK_MOTIVO");
  if (($identificacaoconsumidorvenda==3)&&($usacomanda==0)) $tpl6->DESTINO = "saidas_cadastrar.php?codigo=$saida&operacao=2&tiposaida=1&passo=2&editarconsumidor=1";
  else $tpl6->DESTINO = "saidas_cadastrar.php?codigo=$saida&operacao=2&tiposaida=1&passo=1&editarconsumidor=1";
  $tpl6->PERGUNTA = "";
  $tpl6->block("BLOCK_BOTAO");
  
  //$tpl6->NAO_LINK = "saidas.php";
  $tpl6->LINK_TARGET = "";
  //$tpl6->block("BLOCK_BOTAO_NAO_LINK");
  //$tpl6->block("BLOCK_BOTAO_SIMNAO");
  $tpl6->show();
  exit;
}

$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;

//Botões para vendas normais
$sql = "SELECT * FROM quiosques_configuracoes WHERE quicnf_quiosque=$usuario_quiosque";
if (!$query=mysql_query($sql)) die("Erro SQL quiosque configuracoes:" . mysql_error());
$dados = mysql_fetch_assoc($query);
$usamodulofiscal=$dados["quicnf_usamodulofiscal"];

if ($usamodulofiscal==1) {
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_CADASTRADO");
    //Botão gerar nota fiscal AGORA
    $tpl_notificacao->BOTAOGERAL_DESTINO="saidas_cadastrar_nfe.php?saida=$saida&ope=1";
    //$tpl->block("BLOCK_BOTAOGERAL_NOVAJANELA");
    $tpl_notificacao->BOTAOGERAL_TIPO="button";
    $tpl_notificacao->BOTAOGERAL_NOME="GERAR NOTA";
    //$tpl_notificacao->block("BLOCK_BOTAOGERAL_AUTOFOCO");
    $tpl_notificacao->block("BLOCK_BOTAOGERAL");    
    
    //Botão gerar nota fiscal DEPOIS
    $tpl_notificacao->BOTAOGERAL_DESTINO="saidas_ver.php?codigo=$saida&tiposaida=$tiposai&ope=4";
    $tpl_notificacao->block("BLOCK_BOTAOGERAL_NOVAJANELA");
    $tpl_notificacao->BOTAOGERAL_TIPO="button";
    $tpl_notificacao->BOTAOGERAL_NOME="IMPRIMIR";
    //$tpl_notificacao->block("BLOCK_BOTAOGERAL_AUTOFOCO");
    $tpl_notificacao->block("BLOCK_BOTAOGERAL");   


    //Botão Imprimir
    if ($tiposai == "3") $tpl_notificacao->BOTAOGERAL_DESTINO="saidas_devolucao.php";
    else $tpl_notificacao->BOTAOGERAL_DESTINO="saidas.php";
    //$tpl->block("BLOCK_BOTAOGERAL_NOVAJANELA");
    $tpl_notificacao->BOTAOGERAL_TIPO="button";
    $tpl_notificacao->BOTAOGERAL_NOME="CONTINUAR";
    $tpl_notificacao->block("BLOCK_BOTAOGERAL_AUTOFOCO");
    $tpl_notificacao->block("BLOCK_BOTAOGERAL"); 
    
} else { //Venda padrão ou devolução sem usar módulo fiscal
    
    //Botão continuar
    if ($tiposai == "3") $tpl_notificacao->DESTINO = "saidas_devolucao.php";
    else $tpl_notificacao->DESTINO = "saidas.php";
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_CADASTRADO");
    $tpl_notificacao->block("BLOCK_BOTAO");


    //Botão Imprimir
    $tpl_notificacao->BOTAOGERAL_DESTINO="saidas_ver.php?codigo=$saida&tiposaida=$tiposai&ope=4";
    $tpl_notificacao->block("BLOCK_BOTAOGERAL_NOVAJANELA");
    $tpl_notificacao->BOTAOGERAL_TIPO="button";
    $tpl_notificacao->BOTAOGERAL_NOME="IMPRIMIR";
    //$tpl_notificacao->block("BLOCK_BOTAOGERAL_AUTOFOCO");
    $tpl_notificacao->block("BLOCK_BOTAOGERAL");



    //Botão cadastrar mais
    if (($identificacaoconsumidorvenda==3)&&($usacomanda==0)) $tpl_notificacao->BOTAOGERAL_DESTINO="saidas_cadastrar.php?tiposaida=$tiposai&operacao=1&passo=2";
    else $tpl_notificacao->BOTAOGERAL_DESTINO="saidas_cadastrar.php?tiposaida=$tiposai&operacao=1&passo=1";
    //$tpl->block("BLOCK_BOTAOGERAL_NOVAJANELA");
    $tpl_notificacao->BOTAOGERAL_TIPO="button";
    $tpl_notificacao->BOTAOGERAL_NOME="REALIZAR NOVA SAIDA";
    $tpl_notificacao->block("BLOCK_BOTAOGERAL_AUTOFOCO");
    $tpl_notificacao->block("BLOCK_BOTAOGERAL");

}

$tpl_notificacao->show();

?>
