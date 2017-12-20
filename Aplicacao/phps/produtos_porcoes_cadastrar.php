<script language="JavaScript" src="produtos_porcoes_cadastrar.js"></script>

<?php
$tipopagina = "produtos";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";

$produto=$_GET["produto"];
if ($produto=="") { echo "Não foi recebido parametro do produto"; exit;  }

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PRODUTOS";
$tpl_titulo->SUBTITULO = "LISTA DE PORÇÕES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos_porcoes.png";
$tpl_titulo->show();

$produto = $_GET['produto'];
$numero = $_GET['codigo'];
$operacao = $_GET['operacao'];
$dataatual= date ("Y-m-d H:i:s");

//Pega nome do produto para popular campos de cabeçalho
$sql = "SELECT * FROM produtos JOIN produtos_tipo on (protip_codigo=pro_tipocontagem) WHERE pro_codigo=$produto";
if (!$query = mysql_query($sql))  die("Erro1: " . mysql_error());
$array = mysql_fetch_assoc($query); 
$produto_nome=$array["pro_nome"];
$tipocontagem=$array["pro_tipocontagem"];
$tipocontagem_sigla=$array["protip_sigla"];

//Pega todos os dados da tabela (Necessário caso seja uma edição)
if (($operacao=="editar")||($operacao=="ver")) {
    $sql = "SELECT * FROM produtos_porcoes JOIN produtos on (pro_codigo=propor_produto) WHERE propor_codigo=$numero";
    if (!$query = mysql_query($sql))  die("Erro1: " . mysql_error());
    $array = mysql_fetch_assoc($query); 
    $porcao_nome=$array["propor_nome"];
    $porcao_valuniref=$array["propor_valuniref"];
    $porcao_valuniref= number_format($porcao_valuniref,2,'.',',');
    $porcao_quantidade=$array["propor_quantidade"];
    if (($tipocontagem==2)||($tipocontagem==3)) {
        $porcao_quantidade= number_format($porcao_quantidade,3,'.','');
    } else {
        $porcao_quantidade= number_format($porcao_quantidade,0,'','.');
    }
}


//echo "($tipocontagem-$porcao_quantidade)";
//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "produtos_porcoes_cadastrar2.php";

//Produto
$tpl1->TITULO = "Produto";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "produto";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "35";
$tpl1->CAMPO_VALOR = "$produto_nome";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");


//Porção Nome
$tpl1->TITULO = "Nome da porção";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "porcao_nome";
$tpl1->CAMPO_ONCLICK="";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_VALOR = "$porcao_nome";
$tpl1->CAMPO_ESTILO="width:200px";
$tpl1->block("BLOCK_CAMPO_ESTILO");
if ($operacao=="ver") $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Porção Quantidade
$tpl1->TITULO = "Quantidade Ref. Estoque";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "porcao_quantidade";
if ($tipocontagem==1) $qtdrefestoque_mascara="0";
if ($tipocontagem==2) $qtdrefestoque_mascara="0,000";
if ($tipocontagem==3) $qtdrefestoque_mascara="0,000";
$tpl1->CAMPO_DICA = "$qtdrefestoque_mascara";
$tpl1->CAMPO_ID = "";
//if ($porcao_quantidade=="") $porcao_quantidade=$qtdrefestoque_mascara;
$tpl1->CAMPO_VALOR = "$porcao_quantidade";
$tpl1->CAMPO_ESTILO="width:100px";
$tpl1->CAMPO_ONKEYUP="";
$tpl1->CAMPO_ONKEYDOWN="";
$tpl1->CAMPO_ONKEYPRESS="";
$tpl1->CAMPO_ONBLUR="";
$tpl1->CAMPO_ONCLICK="this.select();";
$tpl1->block("BLOCK_CAMPO_ESTILO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
if ($operacao=="ver") $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->TEXTO_ID="tipocontagem";
$tpl1->TEXTO="$tipocontagem_sigla";
$tpl1->block("BLOCK_TEXTO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Porção Valor Unitário Referencial
$tpl1->TITULO = "Valor Unitário Referencial";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "porcao_valuniref";
$tpl1->CAMPO_DICA = "R$ 0,00";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_VALOR = "$porcao_valuniref";
$tpl1->CAMPO_ESTILO="width:100px";
$tpl1->block("BLOCK_CAMPO_ESTILO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
if ($operacao=="ver") $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//OCULTO Quantidade
$tpl1->CAMPOOCULTO_VALOR=$porcao_quantidade;
$tpl1->CAMPOOCULTO_NOME="porcao_quantidade2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//OCULTO Valor Unitário Referencial
$tpl1->CAMPOOCULTO_VALOR=$porcao_valuniref;
$tpl1->CAMPOOCULTO_NOME="porcao_valuniref2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//OCULTO Tipo de contagem
$tpl1->CAMPOOCULTO_VALOR=$tipocontagem;
$tpl1->CAMPOOCULTO_NOME="tipocontagem2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//OCULTO Produto
$tpl1->CAMPOOCULTO_VALOR=$produto;
$tpl1->CAMPOOCULTO_NOME="produto2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//OCULTO Numero
$tpl1->CAMPOOCULTO_VALOR=$numero;
$tpl1->CAMPOOCULTO_NOME="numero2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//OCULTO Operação
$tpl1->CAMPOOCULTO_VALOR=$operacao;
$tpl1->CAMPOOCULTO_NOME="operacao";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//BOTOES
if (($operacao == "editar") || ($operacao == "cadastrar")) {
    //Botão Salvar
    $tpl1->block("BLOCK_BOTAO_SALVAR");

    //Botão Cancelar
    if ($codigo != $usuario_codigo) {
        $tpl1->BOTAO_LINK = "produtos_porcoes.php?produto=$produto";
        $tpl1->block("BLOCK_BOTAO_CANCELAR");
    }
    
} else {
    //Botão Voltar
    $tpl1->block("BLOCK_BOTAO_VOLTAR");
}
$tpl1->block("BLOCK_BOTOES");

$tpl1->show();

include "rodape.php";
?>
