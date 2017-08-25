<script src="caixas_entradassaidas_cadastrar.js" type="text/javascript"></script>
<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operacoes_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

$operacao=$_GET["operacao"];
$id=$_GET["codigo"];
$numero=$_GET["caixaoperacao"];

//Se for edição pega os valores
if ($operacao=="editar") {
    $sql="SELECT * FROM caixas_entradassaidas LEFT JOIN saidas on (sai_codigo=caientsai_venda) WHERE caientsai_id=$id";
    if (!$query=mysql_query($sql)) die("Erro SQL 4: " . mysql_error());
    $dados=  mysql_fetch_assoc($query);
    $tipo=$dados["caientsai_tipo"];
    $valor= $dados["caientsai_valor"];
    $valortela=  "R$ ".number_format($valor,2,',','.');
    $descricao= $dados["caientsai_descricao"];
    $areceber= $dados["caientsai_areceber"];
    $venda= $dados["caientsai_venda"];
    $consumidor= $dados["sai_consumidor"];
    $datavenda= $dados["sai_datacadastro"];
} else {
    $tipo=$_REQUEST["tipooperacao"];
}



//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "ENTRADAS E SAÍDAS DE CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixa_entradasaida.png";
$tpl_titulo->show();


$usacaixa=usamodulocaixa($usuario_quiosque);
if ($usacaixa!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}

$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

$tpl->LINK_DESTINO="caixas_entradassaidas_cadastrar2.php";
$tpl->LINK_TARGET="";


//Tipo
$tpl->TITULO="Tipo";
$tpl->block("BLOCK_TITULO");
$tpl->SELECT_NOME="tipo";
$tpl->SELECT_ID="";
$tpl->SELECT_TAMANHO="";
$tpl->SELECT_ONCHANGE="";
$tpl->block("BLOCK_SELECT_ONCHANGE"); 
$tpl->block("BLOCK_SELECT_NORMAL"); 
$tpl->block("BLOCK_SELECT_OBRIGATORIO");
$sql="SELECT * FROM caixas_tipo ORDER BY caitip_codigo DESC";
if (!$query=mysql_query($sql)) die("Erro SQL 3: " . mysql_error());
//$tpl->block("BLOCK_SELECT_OPTION_PADRAO"); //Selecione
while ($dados = mysql_fetch_assoc($query)) {
    $tpl->OPTION_VALOR=$dados["caitip_codigo"];
    if ($tipo==$dados["caitip_codigo"])  $tpl->block("BLOCK_SELECT_OPTION_SELECIONADO");
    $tpl->OPTION_NOME=$dados["caitip_nome"];
    $tpl->block("BLOCK_SELECT_OPTION");
}
$tpl->block("BLOCK_SELECT");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");


//Valor
$tpl->TITULO="Valor";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="valor";
$tpl->CAMPO_VALOR="$valortela";
$tpl->CAMPO_TAMANHO="12";
$tpl->CAMPO_QTD_CARACTERES="";
$tpl->block("BLOCK_CAMPO_FOCO");
$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Descrição
$tpl->TITULO="Descrição";
$tpl->block("BLOCK_TITULO");
$tpl->CAMPO_TIPO="text";
$tpl->CAMPO_NOME="descricao";
$tpl->CAMPO_VALOR="$descricao";
$tpl->CAMPO_TAMANHO="40";
$tpl->CAMPO_QTD_CARACTERES="";
//$tpl->block("BLOCK_CAMPO_FOCO");
//$tpl->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl->block("BLOCK_CAMPO_NORMAL"); //classe padrao
$tpl->block("BLOCK_CAMPO");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

$tpl->LINK_TARGET="";

// PARA EDIÇAO
//Operação
$tpl->CAMPOOCULTO_VALOR="$operacao";
$tpl->CAMPOOCULTO_NOME="operacao";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//Codigo
$tpl->CAMPOOCULTO_VALOR="$codigo";
$tpl->CAMPOOCULTO_NOME="codigo";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//ID
$tpl->CAMPOOCULTO_VALOR="$id";
$tpl->CAMPOOCULTO_NOME="id";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//numero
$tpl->CAMPOOCULTO_VALOR="$numero";
$tpl->CAMPOOCULTO_NOME="numero";
$tpl->block("BLOCK_CAMPOSOCULTOS");

//numero
$tpl->CAMPOOCULTO_VALOR="$valortela";
$tpl->CAMPOOCULTO_NOME="valortela";
$tpl->block("BLOCK_CAMPOSOCULTOS");


//Botão Salvar
$tpl->block("BLOCK_BOTAO_SALVAR");

//Botão Cancelar
$tpl->BOTAO_LINK="caixas_entradassaidas.php?caixaoperacao=$numero";
$tpl->block("BLOCK_BOTAO_CANCELAR");

$tpl->block("BLOCK_BOTOES");

$tpl->show();
include "rodape.php";
?>
