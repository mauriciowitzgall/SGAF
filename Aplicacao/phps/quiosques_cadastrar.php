<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
$codigo = $_GET["codigo"];
$operacao = $_GET["operacao"];
if ($operacao == 'ver') {
    if ($permissao_quiosque_ver <> 1) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
} else if ($operacao == 'cadastrar') {
    if ($permissao_quiosque_cadastrar <> 1) {
        header("Location: permissoes_semacesso.php");
        exit;
    }
} else {
    if ((($permissao_quiosque_editar == 1) && ($codigo == $usuario_quiosque)) || ($usuario_grupo == 1)) {
        //Pode
    } else {
        header("Location: permissoes_semacesso.php");
        exit;
    }
}


include "includes.php";
$tipopagina = "cooperativa";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "QUIOSQUES";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques.png";
$tpl_titulo->show();

//Pega todos os dados da tabela (Necessário caso seja uma edi��o ou visuliza��o de detalhes)
$sql = "SELECT * FROM quiosques WHERE qui_codigo='$codigo'";
$query = mysql_query($sql);
if (!$query)
    die("Erro: 4" . mysql_error());
while ($array = mysql_fetch_array($query)) {
    $nome = $array['qui_nome'];
    $cidade = $array['qui_cidade'];
    $cep = $array['qui_cep'];
    $bairro = $array['qui_bairro'];
    $vila = $array['qui_vila'];
    $endereco = $array['qui_endereco'];
    $numero = $array['qui_numero'];
    $complemento = $array['qui_complemento'];
    $referencia = $array['qui_referencia'];
    $fone1 = $array['qui_fone1'];
    $fone2 = $array['qui_fone2'];
    $obs = $array['qui_obs'];
    $email = $array['qui_email'];
    $cooperativa = $array['qui_cooperativa'];

    //Pega todos os dados da tabela (Necessário caso seja uma edi��o)
    $sql = "SELECT * FROM cidades join estados on (cid_estado=est_codigo) WHERE cid_codigo='$cidade'";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    while ($dados = mysql_fetch_array($query)) {
        $estado = $dados["cid_estado"];
        $pais = $dados["est_pais"];
    }
}

//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "quiosques_cadastrar2.php";

//Chama o arquivo javascript
$tpl1->JS_CAMINHO = "quiosques_cadastrar.js";
$tpl1->block("BLOCK_JS");

//Nome 
$tpl1->TITULO = "Nome";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "nome";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "35";
$tpl1->CAMPO_VALOR = $nome;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_HISTORICO_DESATIVADO");
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO_FOCO");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Pais
$tpl1->TITULO = "Pais";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "pais";
$tpl1->CAMPO_DICA = "";
$tpl1->SELECT_ID = "pais";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_ONCHANGE = "popula_estados();";
$tpl1->block("BLOCK_SELECT_ONCHANGE");

if ($operacao == 'ver')
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
$sql = "
SELECT DISTINCT
    pai_codigo,pai_nome
FROM
    paises
    join estados on (est_pais=pai_codigo)
    join cidades on (cid_estado=est_codigo)
ORDER BY
    pai_nome";
$query = mysql_query($sql);
if (!$query)
    die("Erro: 5" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["pai_codigo"];
    $tpl1->OPTION_NOME = $dados["pai_nome"];
    if ($pais == $dados["pai_codigo"]) {
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    }
    $tpl1->block("BLOCK_SELECT_OPTION");
}
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Estado
$tpl1->TITULO = "Estado";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "estado";
$tpl1->SELECT_ID = "estado";
$tpl1->SELECT_TAMANHO = "";
$tpl1->SELECT_ONCHANGE = "popula_cidades();";
$tpl1->block("BLOCK_SELECT_ONCHANGE");
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO2");
//Se a opera��o for editar ent�o mostrar os options, e o option em quest�o selecionado
if ($codigo != "") {
    $sql = "SELECT * FROM estados WHERE est_pais=$pais ORDER BY est_nome";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: 6" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $tpl1->OPTION_VALOR = $dados["est_codigo"];
        $tpl1->OPTION_NOME = $dados["est_sigla"];
        if ($estado == $dados["est_codigo"]) {
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        }
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
}
if ($operacao == 'ver')
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Cidade
$tpl1->TITULO = "Cidade";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "cidade";
$tpl1->SELECT_ID = "cidade";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
//Se a opera��o for editar ent�o mostrar os options, e o option em quest�o selecionado
if ($codigo != "") {
    $sql = "SELECT * FROM cidades WHERE cid_estado=$estado ORDER BY cid_nome ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: 7" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $tpl1->OPTION_VALOR = $dados["cid_codigo"];
        $tpl1->OPTION_NOME = $dados["cid_nome"];
        if ($cidade == $dados["cid_codigo"]) {
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        }
        $tpl1->block("BLOCK_SELECT_OPTION");
    }
}
if ($operacao == 'ver')
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Vila
$tpl1->TITULO = "Vila";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "vila";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "30";
$tpl1->CAMPO_VALOR = $vila;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Bairro
$tpl1->TITULO = "Bairro";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "bairro";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "35";
$tpl1->CAMPO_VALOR = $bairro;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Endereço
$tpl1->TITULO = "Endereço";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "endereco";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "45";
$tpl1->CAMPO_VALOR = $endereco;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "numero";
$tpl1->CAMPO_TIPO = "number";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "5";
$tpl1->CAMPO_VALOR = $numero;
$tpl1->CAMPO_DICA = "Nº";
$tpl1->CAMPO_QTD_CARACTERES = 11;
$tpl1->block("BLOCK_CAMPO_HISTORICO_DESATIVADO");
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Complemento do Endereço
$tpl1->TITULO = "Complemento";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "complemento";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "30";
$tpl1->CAMPO_VALOR = $complemento;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Referência do Endereço
$tpl1->TITULO = "Ponto de Referência";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "referencia";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "50";
$tpl1->CAMPO_VALOR = $referencia;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//CEP
$tpl1->TITULO = "CEP";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "cep";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "cep";
$tpl1->CAMPO_TAMANHO = "9";
$tpl1->CAMPO_VALOR = $cep;
$tpl1->CAMPO_QTD_CARACTERES = 9;
$tpl1->block("BLOCK_CAMPO_HISTORICO_DESATIVADO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Telefone 01
$tpl1->TITULO = "Telefone 01";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_NOME = "fone1";
$tpl1->CAMPO_ID = "telefone1";
$tpl1->CAMPO_TAMANHO = "15";
$tpl1->CAMPO_VALOR = $fone1;
$tpl1->CAMPO_QTD_CARACTERES = 15;
$tpl1->block("BLOCK_CAMPO_HISTORICO_DESATIVADO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Telefone 02
$tpl1->TITULO = "Telefone 02";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_NOME = "fone2";
$tpl1->CAMPO_ID = "telefone2";
$tpl1->CAMPO_TAMANHO = "15";
$tpl1->CAMPO_VALOR = $fone2;
$tpl1->CAMPO_QTD_CARACTERES = 15;
$tpl1->block("BLOCK_CAMPO_HISTORICO_DESATIVADO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//E-mail
$tpl1->TITULO = "E-mail";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "email";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "email";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "40";
$tpl1->CAMPO_VALOR = $email;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_HISTORICO_DESATIVADO");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Observação
$tpl1->TITULO = "Observação";
$tpl1->block("BLOCK_TITULO");
$tpl1->TEXTAREA_TAMANHO = "65";
$tpl1->TEXTAREA_NOME = "obs";
//$tpl1->block("BLOCK_TEXTAREA_DESABILITADO");
$tpl1->TEXTAREA_TEXTO = $obs;
if ($operacao == 'ver')
    $tpl1->block("BLOCK_TEXTAREA_DESABILITADO");
$tpl1->block("BLOCK_TEXTAREA");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Cooperativa
$tpl1->TITULO = "Cooperativa";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "cooperativa";
$tpl1->SELECT_ID = "cooperativa";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
if ($usuario_grupo == 7)
    $sql = "SELECT * FROM cooperativas ORDER BY coo_abreviacao";
else
    $sql = "SELECT * FROM cooperativas WHERE coo_codigo=$usuario_cooperativa";
$query = mysql_query($sql);
if (!$query)
    die("Erro: 7" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["coo_codigo"];
    $tpl1->OPTION_NOME = $dados["coo_abreviacao"];
    //Se a opera��o for editar ent�o mostrar os options, e o option em quest�o selecionado
    if (isset($codigo)) {
        if ($cooperativa == $dados["coo_codigo"]) {
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        }
    }
    $tpl1->block("BLOCK_SELECT_OPTION");
}
if ($operacao == 'ver')
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Tipo de negociação
$tpl1->TITULO = "Tipo de negociação";
if (($operacao == "editar") || ($operacao == "ver")) {
    $sql = "SELECT * FROM quiosques_tiponegociacao WHERE quitipneg_quiosque=$codigo";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: 8" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $tipo = $dados["quitipneg_tipo"];
        if ($tipo == 1)
            $tipo_consignacao = 1;
        if ($tipo == 2)
            $tipo_revenda = 1;
    }
}
//Tipo Consignação
$tpl1->CHECKBOX_NOME = "box[1]";
$tpl1->CHECKBOX_VALOR = "1";
$tpl1->LABEL_NOME = "Consignação";
if ($tipo_consignacao == 1) {
    $tpl1->block("BLOCK_CHECKBOX_SELECIONADO");
}
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
$tpl1->CHECKBOX_SPAN_ID = "";
$tpl1->block("BLOCK_CHECKBOX");

//Tipo Revenda
$tpl1->CHECKBOX_NOME = "box[2]";
$tpl1->CHECKBOX_VALOR = "2";
$tpl1->LABEL_NOME = "Revenda";
if ($tipo_revenda == 1) {
    $tpl1->block("BLOCK_CHECKBOX_SELECIONADO");
}
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
$tpl1->CHECKBOX_SPAN_ID = "";
$tpl1->block("BLOCK_CHECKBOX");
$tpl1->block("BLOCK_TITULO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");




//BOTOES
if (($operacao == "editar") || ($operacao == "cadastrar")) {
    //Bot�o Salvar
    $tpl1->block("BLOCK_BOTAO_SALVAR");

    //Bot�o Cancelar   
    $tpl1->BOTAO_LINK = "quiosques.php";
    $tpl1->block("BLOCK_BOTAO_CANCELAR");
} else {
    //Bot�o Voltar
    $tpl1->block("BLOCK_BOTAO_VOLTAR");
}
$tpl1->block("BLOCK_BOTOES");


//Campos ocultos do formulario caso seja uma edi��o
if ($operacao == "editar") {
    //Codigo
    $tpl1->CAMPOOCULTO_NOME = "codigo";
    $tpl1->CAMPOOCULTO_VALOR = "$codigo";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");

    //Nome
    $tpl1->CAMPOOCULTO_NOME = "nomenobanco";
    $tpl1->CAMPOOCULTO_VALOR = "$nome";
    $tpl1->block("BLOCK_CAMPOSOCULTOS");
}
//Opera��o
$tpl1->CAMPOOCULTO_NOME = "operacao";
$tpl1->CAMPOOCULTO_VALOR = "$operacao";
$tpl1->block("BLOCK_CAMPOSOCULTOS");


$tpl1->show();


include "rodape.php";
?>
