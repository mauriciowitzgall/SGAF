
<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_quiosque_definirvendedores <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "quiosques";
include "includes.php";

$quiosque = $_POST['quiosque'];
$vendedor = $_POST['vendedor'];
$operacao = $_POST['operacao'];
$datafuncao = desconverte_data($_POST['datafuncao']);


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "VENDEDORES";
$tpl_titulo->SUBTITULO = "CADASTRO DE VENDEDORES DO QUIOSQUE";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "../pessoas2/vendedor.png";
$tpl_titulo->show();

//Estrutura da notifica��o
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "vendedores.php?quiosque=$quiosque";


//Se a opera��o for cadastro ent�o
if ($operacao == 'cadastrar') {
    //Verifica se o vendedor j� est� na lista de vendedores do quiosque
    $sql = "SELECT * FROM quiosques_vendedores WHERE quiven_vendedor=$vendedor and quiven_quiosque=$quiosque";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro de SQL:" . mysql_error());
    //$dados=  mysql_fetch_assoc($query);
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "Este vendedor já está na lista!";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOEDITADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_FALTADADOS");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
        exit;
    } else {
        //Insere novo registro
        $sql = "
        INSERT INTO 
            quiosques_vendedores (
                quiven_quiosque,
                quiven_vendedor,
                quiven_datafuncao
            )
        VALUES (
            '$quiosque',
            '$vendedor',
            '$datafuncao'
        )";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro de SQL:" . mysql_error());
        //Verifica se esse vendedor já possui um usuário no sistema
        $sql2 = "SELECT pes_possuiacesso FROM pessoas WHERE pes_codigo=$vendedor";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro de SQL:" . mysql_error());
        $dados2 = mysql_fetch_array($query2);
        $possiacesso = $dados2[0];
        if ($possiacesso == 0) {
            echo "<br>";
            $tpl_notificacao->block("BLOCK_ATENCAO");
            $tpl_notificacao->LINK = "pessoas_cadastrar.php?codigo=$vendedor&operacao=editar";
            $tpl_notificacao->MOTIVO = "Este vendedor ainda não possui acesso ao sistema!";
            $tpl_notificacao->block("BLOCK_MOTIVO");
            $tpl_notificacao->PERGUNTA = "Deseja definir uma senha de acesso para ele agora mesmo?";
            $tpl_notificacao->block("BLOCK_PERGUNTA");
            $tpl_notificacao->NAO_LINK = "vendedores.php?quiosque=$quiosque";
            $tpl_notificacao->block("BLOCK_BOTAO_NAO_LINK");
            $tpl_notificacao->block("BLOCK_BOTAO_SIMNAO");
            $tpl_notificacao->show();
        } else {
            $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
            $tpl_notificacao->block("BLOCK_CONFIRMAR");
            $tpl_notificacao->block("BLOCK_CADASTRADO");
            $tpl_notificacao->block("BLOCK_BOTAO");
            $tpl_notificacao->show();
        }
    }
} else { //Se for uma edi��o
    $sql = "
    UPDATE
        quiosques_vendedores
    SET            
        quiven_datafuncao='$datafuncao'
    WHERE
        quiven_quiosque=$quiosque and
        quiven_vendedor=$vendedor
    ";
    if (!mysql_query($sql))
        die("Erro: " . mysql_error());
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}


include "rodape.php";
?>

