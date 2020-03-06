<script language="JavaScript" src="produtos_subprodutos_cadastrar.js"></script>

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
$tpl_titulo->SUBTITULO = "CADASTRO DE SUB-PRODUTOS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "subproduto.png";
$tpl_titulo->show();


$produto = $_GET['produto'];
$subproduto = $_GET['subproduto'];
$numero = $_GET['codigo'];
$operacao = $_GET['operacao'];
$dataatual= date ("Y-m-d H:i:s");

//Pega nome do produto para popular campos de cabeçalho
$sql = "SELECT * FROM produtos JOIN produtos_tipo on (protip_codigo=pro_tipocontagem) WHERE pro_codigo=$produto";
if (!$query = mysql_query($sql))  die("Erro11: " . mysql_error());
$array = mysql_fetch_assoc($query); 
$produto_nome=$array["pro_nome"];
$tipocontagem=$array["pro_tipocontagem"];
$tipocontagem_sigla=$array["protip_sigla"];
$tipocontagem_nome=$array["protip_nome"];

//Pega dados EDIÇÃO
if (($operacao=="editar")||($operacao=="ver")) {
    $sql = "
         SELECT 
        (SELECT pro_nome from produtos WHERE pro_codigo=ps.prosub_subproduto) as subproduto_nome,
        (SELECT protip_codigo from produtos_tipo JOIN produtos on pro_tipocontagem=protip_codigo WHERE pro_codigo=ps.prosub_subproduto) as subproduto_tipocontagem,
        (SELECT protip_sigla from produtos_tipo JOIN produtos on pro_tipocontagem=protip_codigo WHERE pro_codigo=ps.prosub_subproduto) as subproduto_tipocontagem_sigla,
        ps.prosub_quantidade as qtd,
        ps.prosub_numero as numero,
        ps.prosub_subproduto
        FROM produtos_subproduto ps 
        WHERE ps.prosub_produto=$produto
        AND ps.prosub_subproduto=$subproduto
    ";
    if (!$query = mysql_query($sql))  die("Erro01: " . mysql_error());
    $array = mysql_fetch_assoc($query); 
    $subproduto_tipocontagem_sigla=$array["subproduto_tipocontagem_sigla"];
    $quantidade=$array["qtd"];
    $quantidade2=$array["qtd"];
    if (($subproduto_tipocontagem==2)||($subproduto_tipocontagem==3)) {
        $quantidade= number_format($quantidade,3,',','.');
    } else {
        $quantidade= number_format($quantidade,0,'','.');
    }
}


//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "produtos_subprodutos_cadastrar2.php";

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

//Tipo contagem
$tpl1->TITULO = "Tipo Contagem";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "tipocontagem";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "13";
$tpl1->CAMPO_VALOR = "$tipocontagem_nome";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Sub-Produto
$tpl1->TITULO="Sub-Produto";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME="subproduto";
$tpl1->SELECT_ID="";
$tpl1->SELECT_TAMANHO="";
$tpl1->SELECT_ONCHANGE="selecionar_subproduto(this.value)";
$tpl1->block("BLOCK_SELECT_ONCHANGE"); 
$tpl1->block("BLOCK_SELECT_NORMAL"); 
if ($operacao=="cadastrar") $tpl1->block("BLOCK_SELECT_OBRIGATORIO");
else $tpl1->block("BLOCK_SELECT_DESABILITADO");
$sql="SELECT * FROM produtos left join produtos_recipientes on pro_recipiente=prorec_codigo WHERE pro_podesersubproduto=1 and pro_cooperativa=$usuario_cooperativa ORDER BY pro_nome";
if (!$query=mysql_query($sql)) die("Erro SQL 3: " . mysql_error());
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO"); //Selecione
while ($dados = mysql_fetch_assoc($query)) {
    $subproduto_nome=$dados["pro_nome"];
    $subproduto_marca=$dados["pro_marca"];
    $subproduto_recipiente=$dados["prorec_nome"];
    $subproduto_volume=$dados["pro_volume"];
    $tpl1->OPTION_VALOR=$dados["pro_codigo"];
    if ($subproduto==$dados["pro_codigo"])  $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    $tpl1->OPTION_NOME="$subproduto_nome $subproduto_marca $subproduto_recipiente $subproduto_volume";
    $tpl1->block("BLOCK_SELECT_OPTION");
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");


//Quantidade
$tpl1->TITULO = "Quantidade";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "quantidade";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_VALOR = "$quantidade";
$tpl1->CAMPO_ESTILO="width:100px";
$tpl1->CAMPO_ONKEYUP="";
$tpl1->CAMPO_ONKEYDOWN="";
$tpl1->CAMPO_ONKEYPRESS="";
$tpl1->CAMPO_ONBLUR="";
$tpl1->block("BLOCK_CAMPO_ESTILO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao=="cadastrar")
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
else
    $tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
if ($operacao=="ver") $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->TEXTO_ID="subproduto_tipocontagem";
$tpl1->TEXTO="$subproduto_tipocontagem_sigla";
$tpl1->block("BLOCK_TEXTO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->TEXTO_ID="";
$tpl1->TEXTO="(para fazer 1 $tipocontagem_sigla)";
$tpl1->block("BLOCK_TEXTO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");


//OCULTO Quantidade
$tpl1->CAMPOOCULTO_VALOR=$quantidade2;
$tpl1->CAMPOOCULTO_NOME="quantidade2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");


//OCULTO Tipo de contagem
$tpl1->CAMPOOCULTO_VALOR=$tipocontagem;
$tpl1->CAMPOOCULTO_NOME="tipocontagem2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//OCULTO Produto
$tpl1->CAMPOOCULTO_VALOR=$produto;
$tpl1->CAMPOOCULTO_NOME="produto2";
$tpl1->block("BLOCK_CAMPOSOCULTOS");

//OCULTO Produto
$tpl1->CAMPOOCULTO_VALOR=$subproduto;
$tpl1->CAMPOOCULTO_NOME="subproduto2";
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
        $tpl1->BOTAO_LINK = "produtos_subprodutos.php?produto=$produto";
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
