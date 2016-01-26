<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";

$codigo = $_GET["codigo"];
$operacao = $_GET["operacao"];

//Se o usuario estiver alterando seu próprio cadastro passa
if ($usuario_codigo != $codigo) {
    if ($operacao == "cadastrar") {
        if ($permissao_pessoas_cadastrar <> 1) {
            header("Location: permissoes_semacesso.php");
            exit;
        }
    }
    if ($operacao == "ver") {
        $sql = "SELECT mespestip_tipo FROM mestre_pessoas_tipo WHERE mespestip_pessoa=$codigo";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro: 15" . mysql_error());
        while ($dados = mysql_fetch_assoc($query)) {
            $tipo = $dados["mespestip_tipo"];
            if (($tipo == 1) && ($permissao_pessoas_ver_administradores == 0)) {
                header("Location: permissoes_semacesso.php");
                exit;
            }
            if (($tipo == 2) && ($permissao_pessoas_ver_presidentes == 0)) {
                header("Location: permissoes_semacesso.php");
                exit;
            }
            if (($tipo == 3) && ($permissao_pessoas_ver_supervisores == 0)) {
                header("Location: permissoes_semacesso.php");
                exit;
            }
            if (($tipo == 4) && ($permissao_pessoas_ver_vendedores == 0)) {
                header("Location: permissoes_semacesso.php");
                exit;
            }
            if (($tipo == 5) && ($permissao_pessoas_ver_fornecedores == 0)) {
                header("Location: permissoes_semacesso.php");
                exit;
            }
            if (($tipo == 6) && ($permissao_pessoas_ver_consumidores == 0)) {
                header("Location: permissoes_semacesso.php");
                exit;
            }
        }
    }
}


$tipopagina = "pessoas";
include "includes.php";

//Verifica se alguma cooperativa cadastrada, se não tiver então o root deve cadastrar uma antes de cadastrar um administrador
$sql = "SELECT coo_codigo FROM cooperativas";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas == 0) {
    $tpl = new Template("templates/notificacao.html");
    $tpl->ICONES = $icones;
    $tpl->MOTIVO_COMPLEMENTO = "Você deve cadastrar uma cooperativas antes de cadastrar um administrador!";
    $tpl->block("BLOCK_ATENCAO");
    $tpl->DESTINO = "cooperativas_cadastrar.php?operacao=cadastrar";
    $tpl->block("BLOCK_BOTAO");
    $tpl->show();
    exit;
}


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PESSSOAS";
if ($operacao == 'ver')
    $tpl_titulo->SUBTITULO = "DETALHES";
if ($operacao == 'editar')
    $tpl_titulo->SUBTITULO = "EDIÇÃO";
if ($operacao == 'cadastrar')
    $tpl_titulo->SUBTITULO = "CADASTRO";

$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "pessoas.png";
$tpl_titulo->show();

//Pega todos os dados da tabela (Necessário caso seja uma edição ou visulização de detalhes)
if (($operacao == "editar") || ($operacao == 'ver')) {
    $sql = "SELECT * FROM pessoas WHERE pes_codigo='$codigo'";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: 4" . mysql_error());
    while ($array = mysql_fetch_array($query)) {
        $id = $array['pes_id'];
        $nome = $array['pes_nome'];
        $cidade = $array['pes_cidade'];
        $cep = $array['pes_cep'];
        $bairro = $array['pes_bairro'];
        $vila = $array['pes_vila'];
        $endereco = $array['pes_endereco'];
        $complemento = $array['pes_complemento'];
        $referencia = $array['pes_referencia'];
        $numero = $array['pes_numero'];
        $fone1 = $array['pes_fone1'];
        $fone2 = $array['pes_fone2'];
        $email = $array['pes_email'];
        $obs = $array['pes_obs'];
        $chat = $array['pes_chat'];
        $cooperativa = $array['pes_cooperativa'];
        $possiacesso = $array['pes_possuiacesso'];
        $senha = $array['pes_senha'];
        $grupopermissoes = $array['pes_grupopermissoes'];
        $quiosqueusuario = $array['pes_quiosqueusuario'];

        $sql = "SELECT * FROM cidades join estados on (cid_estado=est_codigo) WHERE cid_codigo='$cidade'";
        $query = mysql_query($sql);

        if (!$query)
            die("Erro: " . mysql_error());
        while ($dados = mysql_fetch_array($query)) {
            $estado = $dados["cid_estado"];
            $pais = $dados["est_pais"];
        }
    }
}


//Estrutura dos campos de cadastro
$tpl1 = new Template("templates/cadastro_edicao_detalhes_2.html");
$tpl1->LINK_DESTINO = "pessoas_cadastrar2.php";

//Chama o arquivo javascript
$tpl1->JS_CAMINHO = "pessoas_cadastrar.js";
$tpl1->block("BLOCK_JS");


//ID
$tpl1->TITULO = "ID";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "number";
$tpl1->CAMPO_NOME = "id";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "id";
$tpl1->CAMPO_TAMANHO = "10";
if ($id == "") {
    $sql2 = "SELECT MAX(pes_id) as id FROM pessoas";
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro1:" . mysql_error());
    $dados2 = mysql_fetch_assoc($query2);
    $id = $dados2['id'] + 1;
}
$tpl1->CAMPO_VALOR = $id;
$tpl1->CAMPO_QTD_CARACTERES = 9;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
$tpl1->block("BLOCK_CAMPO_FOCO");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
IF ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Nome 
$tpl1->TITULO = "Nome";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_NOME = "nome";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "capitalizar";
$tpl1->CAMPO_ONKEYPRESS = "capitalize()";
$tpl1->CAMPO_TAMANHO = "35";
$tpl1->CAMPO_VALOR = $nome;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
//$tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO_FOCO");
$tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
IF ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
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
if ($operacao == 'ver')
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
$tpl1->block("BLOCK_SELECT");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Estado
$tpl1->TITULO = "Estado";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "estado";
$tpl1->SELECT_ID = "estado";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");
$tpl1->block("BLOCK_SELECT_OPTION_PADRAO");
//Se a operação for editar então mostrar os options, e o option em questão selecionado
if (($operacao == "editar") || ($operacao == "ver")) {
    $sql = "SELECT * FROM estados WHERE est_pais=$pais ORDER BY est_nome ";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: 6" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $tpl1->OPTION_VALOR = $dados["est_codigo"];
        $tpl1->OPTION_NOME = $dados["est_nome"];
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
//Se a operação for editar então mostrar os options, e o option em questão selecionado
if (($operacao == "editar") || ($operacao == "ver")) {
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
IF ($operacao == 'ver')
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
IF ($operacao == 'ver')
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
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "numero";
$tpl1->CAMPO_TIPO = "number";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "5";
$tpl1->CAMPO_VALOR = $numero;
$tpl1->CAMPO_DICA = "Nº";
$tpl1->CAMPO_QTD_CARACTERES = 11;
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
IF ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Referência do Endereço
$tpl1->TITULO = "Referência";
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
IF ($operacao == 'ver')
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
$tpl1->block("BLOCK_CAMPO_NORMAL");
IF ($operacao == 'ver')
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
$tpl1->block("BLOCK_CAMPO_NORMAL");
IF ($operacao == 'ver')
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
$tpl1->block("BLOCK_CAMPO_NORMAL");
IF ($operacao == 'ver')
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
$tpl1->block("BLOCK_CAMPO_NORMAL");
IF ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Chat
$tpl1->TITULO = "Chat";
$tpl1->block("BLOCK_TITULO");
$tpl1->CAMPO_TIPO = "text";
$tpl1->CAMPO_QTD_CARACTERES = "";
$tpl1->CAMPO_NOME = "chat";
$tpl1->CAMPO_DICA = "";
$tpl1->CAMPO_ID = "";
$tpl1->CAMPO_TAMANHO = "40";
$tpl1->CAMPO_VALOR = $chat;
$tpl1->CAMPO_QTD_CARACTERES = 70;
$tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
$tpl1->block("BLOCK_CAMPO_NORMAL");
if ($operacao == 'ver')
    $tpl1->block("BLOCK_CAMPO_DESABILITADO");
$tpl1->block("BLOCK_CAMPO");
$tpl1->block("BLOCK_CONTEUDO");
$tpl1->block("BLOCK_ITEM");

//Tipo
$tpl1->TITULO = "Tipo";
$tipo_administrador = 0;
$tipo_presidente = 0;
$tipo_supervisor = 0;
$tipo_vendedor = 0;
$tipo_fornecedor = 0;
$tipo_consumidor = 0;
if (($operacao == "editar") || ($operacao == "ver")) {
    $sql = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_pessoa=$codigo";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: 8" . mysql_error());
    while ($dados = mysql_fetch_assoc($query)) {
        $tipo = $dados["mespestip_tipo"];
        if ($tipo == 1)
            $tipo_administrador = 1;
        if ($tipo == 2)
            $tipo_presidente = 1;
        if ($tipo == 3)
            $tipo_supervisor = 1;
        if ($tipo == 4)
            $tipo_vendedor = 1;
        if ($tipo == 5)
            $tipo_fornecedor = 1;
        if ($tipo == 6)
            $tipo_consumidor = 1;
    }
} else {
    $tipo_consumidor = 1;
}
//Se o usuário está editando seu próprio cadastro então ele não pode escolher o Tipo
if ($usuario_codigo != $codigo) {

    //Tipo Administrador
    if (($permissao_pessoas_cadastrar_administradores == 1) || (($permissao_pessoas_ver_administradores == 1) && ($operacao = 'ver'))) {
        $tpl1->CHECKBOX_NOME = "box[0]";
        $tpl1->CHECKBOX_VALOR = "1";
        $tpl1->LABEL_NOME = "Administrador";
        if ($tipo_administrador == 1)
            $tpl1->block("BLOCK_CHECKBOX_SELECIONADO");
        if ($operacao == 'ver')
            $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
        $tpl1->block("BLOCK_CHECKBOX");
    }

    //Tipo Presidente
    if (($permissao_pessoas_cadastrar_presidentes == 1) || (($permissao_pessoas_ver_presidentes == 1) && ($operacao = 'ver'))) {
        $tpl1->CHECKBOX_NOME = "box[1]";
        $tpl1->CHECKBOX_VALOR = "2";
        $tpl1->LABEL_NOME = "Presidente";
        if ($tipo_presidente == 1) {
            $tpl1->block("BLOCK_CHECKBOX_SELECIONADO");
        }
        //Se for edição de pessoa
        if ($operacao == "editar") {
            //Verifica se a pessoa em questão é presidente de alguma cooperativa, se sim então desabilitar esse check
            $sql2 = "SELECT * FROM cooperativas WHERE coo_presidente=$codigo";
            $query2 = mysql_query($sql2);
            if (!$query2)
                die("Erro: 1" . mysql_error());
            $total2 = mysql_num_rows($query2);
            if ($total2 > 0) {
                $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
                $tpl1->CHECKBOX_ICONE_ARQUIVO = "../imagens/icones/geral/help.png";
                $tpl1->CHECKBOX_ICONE_MENSAGEM = "Você não pode desmarcar esta opção porque esta pessoa atualmente é presidente de alguma cooperativa. Contate os administradores para saber mais!";
                $tpl1->block("BLOCK_CHECKBOX_ICONE");
                //Chama o campo oculto caso o checkbox fique desabilitado
                $tpl1->CAMPOOCULTO_NOME = "box[1]";
                $tpl1->CAMPOOCULTO_VALOR = "2";
                $tpl1->block("BLOCK_CAMPOSOCULTOS");
            }
        }
        if ($operacao == 'ver')
            $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
        $tpl1->block("BLOCK_CHECKBOX");
    }

    //Tipo Supervisor
    if (($permissao_pessoas_cadastrar_supervisores == 1) || (($permissao_pessoas_ver_supervisores == 1) && ($operacao = 'ver'))) {
        $tpl1->CHECKBOX_NOME = "box[2]";
        $tpl1->CHECKBOX_VALOR = "3";
        $tpl1->LABEL_NOME = "Supervisor";
        if ($tipo_supervisor == 1)
            $tpl1->block("BLOCK_CHECKBOX_SELECIONADO");
        //Se for edição de pessoa
        if ($operacao == "editar") {
            //Verifica se a pessoa em questão é supervisor de algum quiosque, se sim então desabilitar esse check
            $sql2 = "SELECT * FROM quiosques_supervisores WHERE quisup_supervisor=$codigo";
            $query2 = mysql_query($sql2);
            if (!$query2)
                die("Erro: 1" . mysql_error());
            $total2 = mysql_num_rows($query2);
            if ($total2 > 0) {
                $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
                $tpl1->CHECKBOX_ICONE_ARQUIVO = "../imagens/icones/geral/help.png";
                $tpl1->CHECKBOX_ICONE_MENSAGEM = "Você não pode desmarcar esta opção porque esta pessoa atualmente é supervisora de algum quiosque. Contate os administradores para saber mais!";
                $tpl1->block("BLOCK_CHECKBOX_ICONE");
                //Chama o campo oculto caso o checkbox fique desabilitado
                $tpl1->CAMPOOCULTO_NOME = "box[2]";
                $tpl1->CAMPOOCULTO_VALOR = "3";
                $tpl1->block("BLOCK_CAMPOSOCULTOS");
            }
        }
        if ($operacao == 'ver')
            $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
        $tpl1->block("BLOCK_CHECKBOX");
    }

    //Tipo Vendedor
    if (($permissao_pessoas_cadastrar_vendedores == 1) || (($permissao_pessoas_ver_vendedores == 1) && ($operacao = 'ver'))) {
        $tpl1->CHECKBOX_NOME = "box[3]";
        $tpl1->CHECKBOX_VALOR = "4";
        $tpl1->LABEL_NOME = "Vendedor";
        if ($tipo_vendedor == 1)
            $tpl1->block("BLOCK_CHECKBOX_SELECIONADO");
        //Se for edição de pessoa
        if ($operacao == "editar") {
            //Verifica se a pessoa em questão é supervisor de algum quiosque, se sim então desabilitar esse check
            $sql2 = "SELECT * FROM quiosques_vendedores WHERE quiven_vendedor=$codigo";
            $query2 = mysql_query($sql2);
            if (!$query2)
                die("Erro: 1" . mysql_error());
            $total2 = mysql_num_rows($query2);
            if ($total2 > 0) {
                $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
                $tpl1->CHECKBOX_ICONE_ARQUIVO = "../imagens/icones/geral/help.png";
                $tpl1->CHECKBOX_ICONE_MENSAGEM = "Você não pode desmarcar esta opção porque esta pessoa atualmente é vendedora de algum quiosque. Contate os administradores para saber mais!";
                $tpl1->block("BLOCK_CHECKBOX_ICONE");
                //Chama o campo oculto caso o checkbox fique desabilitado
                $tpl1->CAMPOOCULTO_NOME = "box[3]";
                $tpl1->CAMPOOCULTO_VALOR = "4";
                $tpl1->block("BLOCK_CAMPOSOCULTOS");
            }
        }
        if ($operacao == 'ver')
            $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
        $tpl1->block("BLOCK_CHECKBOX");
    }

    //Tipo Fornecedor

    if (($permissao_pessoas_cadastrar_fornecedores == 1) || (($permissao_pessoas_ver_fornecedores == 1) && ($operacao = 'ver'))) {
        $tpl1->CHECKBOX_NOME = "box[4]";
        $tpl1->CHECKBOX_VALOR = "5";
        $tpl1->LABEL_NOME = "Fornecedor";
        if ($tipo_fornecedor == 1)
            $tpl1->block("BLOCK_CHECKBOX_SELECIONADO");
        if ($operacao == 'ver')
            $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
        $tpl1->block("BLOCK_CHECKBOX");
    }

    //Tipo Consumidor
    if (($permissao_pessoas_cadastrar_consumidores == 1) || (($permissao_pessoas_ver_consumidores == 1) && ($operacao = 'ver'))) {
        $tpl1->CHECKBOX_NOME = "box[5]";
        $tpl1->CHECKBOX_VALOR = "6";
        $tpl1->LABEL_NOME = "Consumidor";
        if ($tipo_consumidor == 1)
            $tpl1->block("BLOCK_CHECKBOX_SELECIONADO");
        if ($operacao == 'ver')
            $tpl1->block("BLOCK_CHECKBOX_DESABILITADO");
        $tpl1->block("BLOCK_CHECKBOX");
    }

    $tpl1->block("BLOCK_TITULO");
    $tpl1->block("BLOCK_CONTEUDO");
    $tpl1->block("BLOCK_ITEM");
}

//Cooperativa
$tpl1->TITULO = "Cooperativa";
$tpl1->block("BLOCK_TITULO");
$tpl1->SELECT_NOME = "cooperativa";
$tpl1->SELECT_ID = "cooperativa";
$tpl1->SELECT_TAMANHO = "";
$tpl1->block("BLOCK_SELECT_OBRIGATORIO");

if ($codigo != "") {
    //Verifica se a pessoa é administrador ou root
    $sql8 = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_tipo=1 and mespestip_pessoa=$codigo";
    $query8 = mysql_query($sql8);
    if (!$query8)
        die("Erro 40:" . mysql_error());
    $linhas8 = mysql_num_rows($query8);
}

if (($linhas8 > 0)||($usuario_grupo==7))
    $sql = "SELECT * FROM cooperativas ORDER BY coo_abreviacao";
else
    $sql = "SELECT * FROM cooperativas WHERE coo_codigo=$usuario_cooperativa";
$query = mysql_query($sql);
if (!$query)
    die("Erro: 0" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $tpl1->OPTION_VALOR = $dados["coo_codigo"];
    $tpl1->OPTION_NOME = $dados["coo_abreviacao"];
    $coo = $dados["coo_codigo"];
    if ($coo == $usuario_cooperativa)
        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
    $tpl1->block("BLOCK_SELECT_OPTION");
}
if ($operacao == 'ver')
    $tpl1->block("BLOCK_SELECT_DESABILITADO");
$tpl1->block("BLOCK_SELECT");
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

//Verifica se o usuário tem permissão para cadastrar editar ou ver dados de usuário
if ($operacao == "editar") {
    if (($permissao_pessoas_criarusuarios == 1) || ($codigo == $usuario_codigo)) {

        //Verifica se o esta pessoa é administradora
        $sql = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_pessoa=$codigo and mespestip_tipo=1";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro: 0" . mysql_error());
        $linhas = mysql_num_rows($query);


        //Verifica se o esta pessoa é vendedor de algum quiosque
        $sql2 = "SELECT qui_nome FROM quiosques JOIN quiosques_vendedores on (quiven_quiosque=qui_codigo)  WHERE quiven_vendedor=$codigo";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro: 1" . mysql_error());
        $linhas2 = mysql_num_rows($query2);

        //Verifica se o esta pessoa é supervisor de algum quiosque
        $sql3 = "SELECT DISTINCT qui_nome FROM quiosques join quiosques_supervisores on (qui_codigo=quisup_quiosque) WHERE quisup_supervisor=$codigo";
        $query3 = mysql_query($sql3);
        if (!$query3)
            die("Erro: 2" . mysql_error());
        $linhas3 = mysql_num_rows($query3);

        //Verifica se o esta pessoa é fornecedor de algum quiosque
        $sql4 = "SELECT DISTINCT qui_nome FROM quiosques join entradas on (ent_quiosque=qui_codigo) WHERE ent_fornecedor=$codigo";
        $query4 = mysql_query($sql4);
        if (!$query4)
            die("Erro: 3" . mysql_error());
        $linhas4 = mysql_num_rows($query4);

        //Verifica se o esta pessoa é administradora
        $sql6 = "SELECT * FROM cooperativas WHERE coo_presidente=$codigo";
        $query6 = mysql_query($sql6);
        if (!$query6)
            die("Erro: 0" . mysql_error());
        $linhas6 = mysql_num_rows($query6);

        $linhas5 = $linhas + $linhas2 + $linhas3 + $linhas4 + $linhas6;


        //Possui Acesso?
        $tpl1->TITULO = "Possui Acesso?";
        $sql = "SELECT pes_possuiacesso FROM pessoas WHERE pes_codigo=$codigo";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro: 3" . mysql_error());
        $dados = mysql_fetch_assoc($query);
        $acesso = $dados["pes_possuiacesso"];
        $tpl1->block("BLOCK_TITULO");
        $tpl1->SELECT_NOME = "possuiacesso";
        $tpl1->SELECT_ID = "possuiacesso";
        $tpl1->SELECT_TAMANHO = "";
        $tpl1->OPTION_VALOR = 0;
        $tpl1->OPTION_NOME = "Não";
        if ($linhas5 == 0) {
            $tpl1->block("BLOCK_SELECT_DESABILITADO");
            $tpl1->COMPLEMENTO_ICONE_ARQUIVO = "../imagens/icones/geral/help.png";
            $tpl1->COMPLEMENTO_ICONE_MENSAGEM = "Esta pessoa não pode ter acesso ao sistema porque ela não é presidente, supervisora, vendedora ou fornecedora de nenhum quiosque. Contate os administradores para saber mais!";
            $tpl1->block("BLOCK_COMPLEMENTO_ICONE");
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        }
        if ($acesso == 0) {
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        }
        $tpl1->block("BLOCK_SELECT_OBRIGATORIO");
        $tpl1->block("BLOCK_SELECT_OPTION");
        $tpl1->OPTION_VALOR = 1;
        $tpl1->OPTION_NOME = "Sim";
        if ($acesso == 1) {
            $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
        }
        $tpl1->block("BLOCK_SELECT_OPTION");
        //Verifica se o usuário está editando seu próprio cadastro
        if ($usuario_codigo == $codigo) {

            $tpl1->block("BLOCK_SELECT_DESABILITADO");
            //Caso o campo fique desabilitado deve-se enviar o valor ocultamente
            $tpl1->CAMPOOCULTO_NOME = "possuiacesso";
            $tpl1->CAMPOOCULTO_VALOR = "$acesso";
            $tpl1->block("BLOCK_CAMPOSOCULTOS");
        }
        $tpl1->block("BLOCK_SELECT");
        $tpl1->block("BLOCK_CONTEUDO");
        //Verifica se o usuário está editando seu próprio cadastro, se sim mostrar icone informativo ao lado
        if ($usuario_codigo == $codigo) {
            $tpl1->COMPLEMENTO_ICONE_ARQUIVO = "../imagens/icones/geral/help.png";
            $tpl1->COMPLEMENTO_ICONE_MENSAGEM = "Você não tem permissão para desativar seu próprio usuário! Contate um adminsitrador se deseja fazer isto!";
            $tpl1->block("BLOCK_COMPLEMENTO_ICONE");
        }
        $tpl1->block("BLOCK_CONTEUDO");
        $tpl1->block("BLOCK_ITEM");


        //Se a pessoa estiver setada como vendedor, supervisor ou fornecedor de algum quiosque então liberar acesso ao sistema
        if ($linhas5 > 0) {

            //Senha Nova
            $tpl1->TITULO = "Nova Senha";
            $tpl1->TITULO_ID = "span_password";
            $tpl1->block("BLOCK_TITULO_ID");
            $tpl1->block("BLOCK_TITULO");
            $tpl1->CAMPO_TIPO = "password";
            $tpl1->CAMPO_QTD_CARACTERES = "";
            $tpl1->CAMPO_NOME = "senha";
            $tpl1->CAMPO_ID = "password";
            $tpl1->CAMPO_TAMANHO = "20";
            $tpl1->CAMPO_VALOR = "";
            $tpl1->CAMPO_QTD_CARACTERES = 30;
            $tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
            if ($senha == "") {
                $tpl1->block("BLOCK_CAMPO_PASSWORD_OBRIGATORIO");
            } else {
                $tpl1->block("BLOCK_CAMPO_PASSWORD");
            }
            $tpl1->block("BLOCK_CAMPO");
            $tpl1->block("BLOCK_COMPLEMENTO");
            $tpl1->block("BLOCK_CONTEUDO");
            $tpl1->block("BLOCK_ITEM");

            //Regidigite a Nova Senha
            $tpl1->TITULO = "Redigite a Nova Senha";
            $tpl1->TITULO_ID = "span_senha2";
            $tpl1->block("BLOCK_TITULO_ID");
            $tpl1->block("BLOCK_TITULO");
            $tpl1->CAMPO_TIPO = "password";
            $tpl1->CAMPO_QTD_CARACTERES = "";
            $tpl1->CAMPO_NOME = "senha2";
            $tpl1->CAMPO_ID = "senha2";
            $tpl1->CAMPO_TAMANHO = "20";
            $tpl1->CAMPO_VALOR = "";
            $tpl1->CAMPO_QTD_CARACTERES = 30;
            $tpl1->block("BLOCK_CAMPO_AUTOSELECIONAR");
            if ($senha == "") {
                $tpl1->block("BLOCK_CAMPO_OBRIGATORIO");
            } else {
                $tpl1->block("BLOCK_CAMPO_NORMAL");
            }
            IF ($operacao == 'ver')
                $tpl1->block("BLOCK_CAMPO_DESABILITADO");
            $tpl1->block("BLOCK_CAMPO");
            $tpl1->block("BLOCK_CONTEUDO");
            $tpl1->block("BLOCK_ITEM");



            //Grupo de Permissões
            $tpl1->TITULO = "Grupo de Permissões";
            $tpl1->TITULO_ID = "span_grupopermissoes";
            $tpl1->block("BLOCK_TITULO_ID");
            $tpl1->block("BLOCK_TITULO");
            $tpl1->SELECT_NOME = "grupopermissoes";
            $tpl1->SELECT_ID = "grupopermissoes";
            $tpl1->SELECT_TAMANHO = "";
            $tpl1->block("BLOCK_SELECT_OBRIGATORIO");
            $tpl1->block("BLOCK_SELECT_OPTION_PADRAO");

            //Verifica se o esta pessoa é presidente
            $sql6 = "SELECT * FROM cooperativas WHERE coo_presidente=$codigo";
            $query6 = mysql_query($sql6);
            if (!$query6)
                die("Erro: 0" . mysql_error());
            $linhas6 = mysql_num_rows($query6);
            if ($linhas6 > 0) {
                $tpl1->OPTION_VALOR = "2";
                $tpl1->OPTION_NOME = "Presidente";
                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
                $tpl1->block("BLOCK_SELECT_OPTION");
            } else {


                $sql = "SELECT * FROM grupo_permissoes ORDER BY gruper_codigo";
                $query = mysql_query($sql);
                if (!$query)
                    die("Erro: 2" . mysql_error());
                while ($dados = mysql_fetch_assoc($query)) {
                    $tpl1->OPTION_VALOR = $dados["gruper_codigo"];
                    $tpl1->OPTION_NOME = $dados["gruper_nome"];
                    $grupo_codigo = $dados["gruper_codigo"];

                    //Verifica se o esta pessoa é administrador
                    if ($grupo_codigo == 1) {
                        $sql9 = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_pessoa=$codigo and mespestip_tipo=1";
                        $query9 = mysql_query($sql9);
                        if (!$query9)
                            die("Erro: 0" . mysql_error());
                        $linhas9 = mysql_num_rows($query9);
                        if ($linhas9 > 0) {
                            if ($grupopermissoes == $grupo_codigo)
                                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
                            $tpl1->block("BLOCK_SELECT_OPTION");
                        }
                    }

                    //Verifica se o esta pessoa é presidente
                    if ($grupo_codigo == 2) {
                        $sql6 = "SELECT * FROM cooperativas WHERE coo_presidente=$codigo";
                        $query6 = mysql_query($sql6);
                        if (!$query6)
                            die("Erro: 0" . mysql_error());
                        $linhas6 = mysql_num_rows($query6);
                        if ($linhas6 > 0) {
                            if ($grupopermissoes == $grupo_codigo)
                                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
                            $tpl1->block("BLOCK_SELECT_OPTION");
                        }
                    }

                    //Verifica se o esta pessoa é supervisor de algum quiosque
                    if ($grupo_codigo == 3) {
                        $sql3 = "SELECT DISTINCT qui_nome FROM quiosques join quiosques_supervisores on (qui_codigo=quisup_quiosque) WHERE quisup_supervisor=$codigo";
                        $query3 = mysql_query($sql3);
                        if (!$query3)
                            die("Erro: 2" . mysql_error());
                        $linhas3 = mysql_num_rows($query3);
                        if ($linhas3 > 0) {
                            if ($grupopermissoes == $grupo_codigo)
                                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
                            $tpl1->block("BLOCK_SELECT_OPTION");
                        }
                    }

                    //Verifica se o esta pessoa é vendedor de algum quiosque
                    if ($grupo_codigo == 4) {
                        $sql2 = "SELECT qui_nome FROM quiosques JOIN quiosques_vendedores on (quiven_quiosque=qui_codigo)  WHERE quiven_vendedor=$codigo";
                        $query2 = mysql_query($sql2);
                        if (!$query2)
                            die("Erro: 1" . mysql_error());
                        $linhas2 = mysql_num_rows($query2);
                        if ($linhas2 > 0) {
                            if ($grupopermissoes == $grupo_codigo)
                                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
                            $tpl1->block("BLOCK_SELECT_OPTION");
                        }
                    }

                    //Verifica se o esta pessoa é fornecedor de algum quiosque
                    if ($grupo_codigo == 5) {
                        $sql4 = "SELECT DISTINCT qui_nome FROM quiosques join entradas on (ent_quiosque=qui_codigo) WHERE ent_fornecedor=$codigo";
                        $query4 = mysql_query($sql4);
                        if (!$query4)
                            die("Erro: 3" . mysql_error());
                        $linhas4 = mysql_num_rows($query4);
                        if ($linhas4 > 0) {
                            if ($grupopermissoes == $grupo_codigo)
                                $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
                            $tpl1->block("BLOCK_SELECT_OPTION");
                        }
                    }
                }
            }
            $tpl1->block("BLOCK_SELECT");
            $tpl1->block("BLOCK_CONTEUDO");
            $tpl1->block("BLOCK_ITEM");

            //Quiosque do Usuário
            $tpl1->TITULO = "Quiosque do Usuário";
            $tpl1->TITULO_ID = "span_quiosqueusuario";
            $tpl1->block("BLOCK_TITULO_ID");
            $tpl1->block("BLOCK_TITULO");
            $tpl1->SELECT_NOME = "quiosqueusuario";
            $tpl1->SELECT_ID = "quiosqueusuario";
            $tpl1->SELECT_TAMANHO = "";
            $tpl1->block("BLOCK_SELECT_NORMAL");

            //Verifica se a pessoa é administrador
            $sql8 = "SELECT * FROM mestre_pessoas_tipo WHERE mespestip_tipo=1 and mespestip_pessoa=$codigo";
            $query8 = mysql_query($sql8);
            if (!$query8)
                die("Erro 40:" . mysql_error());
            $linhas8 = mysql_num_rows($query8);

            //Verifica se o usuário é presidente de alguma cooperativa
            $sql5 = "SELECT coo_codigo FROM cooperativas WHERE coo_presidente=$codigo";
            $query5 = mysql_query($sql5);
            if (!$query5)
                die("Erro 40:" . mysql_error());
            $linhas5 = mysql_num_rows($query5);

            //Se o usuário logado for presidente ou administrador mostrar apenas a opção 'todas' para quiosques
            if ($linhas5 > 0) {
                $tpl1->OPTION_VALOR = "";
                $tpl1->OPTION_NOME = "Todos";
                $tpl1->block("BLOCK_SELECT_OPTION");
            } else {
                if ($linhas8 > 0) {
                    $tpl1->OPTION_VALOR = "";
                    $tpl1->OPTION_NOME = "Todos";
                    $tpl1->block("BLOCK_SELECT_OPTION");
                }
                //Se a pessoa é administrador ou presidente ou o usuário logado é root mostrar todos quiosques da cooperativa
                if (($linhas8 > 0) || ($usuario_grupo == 7) || ($linhas5 > 0)) {
                    $sql = "SELECT * FROM quiosques WHERE qui_cooperativa=$cooperativa";
                } else { //Mostrar apenas os quiosque que ele é supervisor ou vendedor ou fornecedor                
                    $sql = "
                SELECT DISTINCT
                    qui_codigo,qui_nome
                FROM
                    quiosques
                    left join quiosques_vendedores on (quiven_quiosque=qui_codigo)
                    left join quiosques_supervisores on (quisup_quiosque=qui_codigo)
                    left join entradas on (ent_quiosque=qui_codigo)
                WHERE
                    quiven_vendedor= $codigo or
                    quisup_supervisor = $codigo or 
                    ent_fornecedor = $codigo
                ";
                }


                $query = mysql_query($sql);
                if (!$query)
                    die("Erro: 8" . mysql_error());

                while ($dados = mysql_fetch_assoc($query)) {
                    if ($quiosqueusuario == $dados['qui_codigo'])
                        $tpl1->block("BLOCK_SELECT_OPTION_SELECIONADO");
                    $tpl1->OPTION_VALOR = $dados["qui_codigo"];
                    $tpl1->OPTION_NOME = $dados["qui_nome"];
                    $tpl1->block("BLOCK_SELECT_OPTION");
                }
            }

            $tpl1->block("BLOCK_SELECT");
            $tpl1->block("BLOCK_CONTEUDO");
            $tpl1->block("BLOCK_ITEM");
        }
    }
}

//Campos ocultos do formulario caso seja uma edição
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
//Operação
$tpl1->CAMPOOCULTO_NOME = "operacao";
$tpl1->CAMPOOCULTO_VALOR = "$operacao";
$tpl1->block("BLOCK_CAMPOSOCULTOS");



//BOTOES
if (($operacao == "editar") || ($operacao == "cadastrar")) {
    //Botão Salvar
    $tpl1->block("BLOCK_BOTAO_SALVAR");

    //Botão Cancelar
    if ($codigo != $usuario_codigo) {
        $tpl1->BOTAO_LINK = "pessoas.php";
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
