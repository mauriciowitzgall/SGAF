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

$tpl = new Template("templates/cadastro_edicao_detalhes_2.html");

$tpl->LINK_DESTINO="caixas_entradassaidas_cadastrar2.php";
$tpl->LINK_TARGET="";


//Tipo
$tpl->TITULO="Tipo";
$tpl->block("BLOCK_TITULO");
$tpl->SELECT_NOME="tipo";
$tpl->SELECT_ID="";
$tpl->SELECT_TAMANHO="";
$tpl->SELECT_ONCHANGE="tipo_entradasaida_caixa()";
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

//A receber
$tpl->LINHA_ID="tr_areceber";
$tpl->block("BLOCK_LINHA_ID");
$tpl->TITULO="À Receber";
$tpl->block("BLOCK_TITULO");
$tpl->SELECT_NOME="areceber";
$tpl->SELECT_ID="";
$tpl->SELECT_TAMANHO="";
$tpl->SELECT_ONCHANGE="areceber_entradasaida_caixa()";
$tpl->block("BLOCK_SELECT_ONCHANGE"); //Classe campopadrao
$tpl->block("BLOCK_SELECT_NORMAL"); //Classe campopadrao
$tpl->block("BLOCK_SELECT_OBRIGATORIO");
//$tpl->block("BLOCK_SELECT_OPTION_PADRAO"); //Selecione
//Não
$tpl->OPTION_VALOR="0";
$tpl->OPTION_NOME="Não";
if (($areceber==0)&&($operacao=="editar")) $tpl->block("BLOCK_SELECT_OPTION_SELECIONADO");
$tpl->block("BLOCK_SELECT_OPTION");
//Sim
$tpl->OPTION_VALOR="1";
$tpl->OPTION_NOME="Sim";
if (($areceber==1)&&($operacao=="editar")) $tpl->block("BLOCK_SELECT_OPTION_SELECIONADO");
$tpl->block("BLOCK_SELECT_OPTION");
$tpl->block("BLOCK_SELECT");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Consumidor
$tpl->LINHA_ID="tr_consumidor";
$tpl->block("BLOCK_LINHA_ID");
$tpl->TITULO="Consumidor";
$tpl->block("BLOCK_TITULO");
$tpl->SELECT_NOME="consumidor";
$tpl->SELECT_ID="";
$tpl->SELECT_TAMANHO="";
$tpl->SELECT_ONCHANGE="consumidor_entradasaida_caixa()";
$tpl->block("BLOCK_SELECT_ONCHANGE"); //Classe campopadrao
$tpl->block("BLOCK_SELECT_NORMAL"); //Classe campopadrao
//$tpl->block("BLOCK_SELECT_OBRIGATORIO");
if (($operacao=="editar")&&($consumidor!="")) {
    $sql="SELECT DISTINCT pes_codigo,pes_nome FROM pessoas JOIN saidas on (sai_consumidor=pes_codigo) WHERE sai_areceber=1 and sai_quiosque=$usuario_quiosque";
    if (!$query=mysql_query($sql)) die("Erro SQL 89: " . mysql_error());
    //$tpl->block("BLOCK_SELECT_OPTION_PADRAO"); //Selecione
    while ($dados = mysql_fetch_assoc($query)) {
        $consumidor_codigo=$dados["pes_codigo"];
        if ($consumidor_codigo==0) {
            $tpl->OPTION_VALOR="";
            $tpl->OPTION_NOME="Cliente Geral";
        } else {
            $tpl->OPTION_VALOR=$dados["pes_codigo"];
            $tpl->OPTION_NOME=$dados["pes_nome"];
        }
        if ($consumidor == $dados["pes_codigo"]) {
            $tpl->block("BLOCK_SELECT_OPTION_SELECIONADO");
        }
        $tpl->block("BLOCK_SELECT_OPTION");
    }
}
$tpl->block("BLOCK_SELECT");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Data da Venda
$tpl->LINHA_ID="tr_datavenda";
$tpl->block("BLOCK_LINHA_ID");
$tpl->TITULO="Data da Venda";
$tpl->block("BLOCK_TITULO");
$tpl->SELECT_NOME="datavenda";
$tpl->SELECT_ID="";
$tpl->SELECT_TAMANHO="";
$tpl->SELECT_ONCHANGE="datavenda_entradasaida_caixa()";
$tpl->block("BLOCK_SELECT_ONCHANGE"); //Classe campopadrao
$tpl->block("BLOCK_SELECT_NORMAL"); //Classe campopadrao
//$tpl->block("BLOCK_SELECT_OBRIGATORIO");
if (($operacao=="editar")&&($consumidor!="")) {
    $sql="SELECT DISTINCT sai_datacadastro FROM saidas LEFT JOIN pessoas on pes_codigo=sai_consumidor WHERE sai_areceber=1 and sai_consumidor=$consumidor and sai_quiosque=$usuario_quiosque";
    if (!$query=mysql_query($sql)) die("Erro SQL 76: " . mysql_error());
    //$tpl->block("BLOCK_SELECT_OPTION_PADRAO"); //Selecione
    while ($dados = mysql_fetch_assoc($query)) {
        $datacad=$dados["sai_datacadastro"];
        $datacad2=converte_data($datacad);
        $tpl->OPTION_VALOR=$datacad;
        if ($datavenda==$datacad)  $tpl->block("BLOCK_SELECT_OPTION_SELECIONADO");
        $tpl->OPTION_NOME="$datacad2";
        $tpl->block("BLOCK_SELECT_OPTION");
    }
}
$tpl->block("BLOCK_SELECT");
$tpl->block("BLOCK_CONTEUDO");
$tpl->block("BLOCK_ITEM");

//Venda
$tpl->LINHA_ID="tr_venda";
$tpl->block("BLOCK_LINHA_ID");
$tpl->TITULO="Venda";
$tpl->block("BLOCK_TITULO");
$tpl->SELECT_NOME="venda";
$tpl->SELECT_ID="";
$tpl->SELECT_TAMANHO="";
$tpl->SELECT_ONCHANGE="venda_entradasaida_caixa()";
$tpl->block("BLOCK_SELECT_ONCHANGE"); 
$tpl->block("BLOCK_SELECT_NORMAL"); //Classe campopadrao
//$tpl->block("BLOCK_SELECT_OBRIGATORIO");
if (($operacao=="editar")&&($consumidor!="")) {
    $sql="SELECT DISTINCT sai_codigo FROM saidas JOIN pessoas on pes_codigo=sai_consumidor WHERE sai_areceber=1 and sai_consumidor=$consumidor and sai_datacadastro='$datavenda' and sai_quiosque=$usuario_quiosque";
    if (!$query=mysql_query($sql)) die("Erro SQL 78: " . mysql_error());
    //$tpl->block("BLOCK_SELECT_OPTION_PADRAO"); //Selecione
    while ($dados = mysql_fetch_assoc($query)) {
        $saida=$dados["sai_codigo"];
        $tpl->OPTION_VALOR="$venda";
        if ($saida==$venda)  {
            $tpl->block("BLOCK_SELECT_OPTION_SELECIONADO");
        }
        $tpl->OPTION_NOME="$saida";
        $tpl->block("BLOCK_SELECT_OPTION");
    }
}
$tpl->block("BLOCK_SELECT");
$tpl->block("BLOCK_CONTEUDO");
$tpl->COMPLEMENTO_ICONE_ARQUIVO="$icones"."detalhes.png";
$tpl->COMPLEMENTO_ICONE_MENSAGEM="Pesquisar Venda";
$tpl->block("BLOCK_COMPLEMENTO_ICONE");
$tpl->LINK="saidas_ver.php?codigo=$venda&tiposaida=1&ope=4&botaofechar=1";
$tpl->LINK_TARGET="_blank";
$tpl->LINK_NOME="link_vervenda";
$tpl->LINK_ID="link_vervenda";
$tpl->block("BLOCK_LINK");
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
