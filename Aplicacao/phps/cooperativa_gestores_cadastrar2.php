<?php
//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_cooperativa_gestores_gerir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "cooperativa";
include "includes.php";


$gestor = $_REQUEST['gestor'];
$operacao = $_POST['operacao'];
$cooperativa=$usuario_cooperativa;

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "GESTORES";
$tpl_titulo->SUBTITULO = "LISTA DE GESTORES DA COOPERATIVA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "cooperativa_gestores.png";
$tpl_titulo->show();

//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "cooperativa_gestores.php";


//Se a operação for cadastro então
if ($operacao=='cadastrar') {
    //Verifica se o gestor já está na lista de gestores da cooperativa
    $sql = "SELECT * FROM cooperativa_gestores WHERE cooges_gestor=$gestor and cooges_cooperativa=$cooperativa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro de SQL 1:" . mysql_error());
    //$dados=  mysql_fetch_assoc($query);
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "Este gestor já está na lista!";
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
            cooperativa_gestores (
                cooges_cooperativa,
                cooges_gestor
            )
        VALUES (
            '$cooperativa',
            '$gestor'
        )";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro de SQL:" . mysql_error());
        //Verifica se esse Gestor já possui um usuário no sistema
        $sql2 = "SELECT pes_possuiacesso FROM pessoas WHERE pes_codigo=$gestor";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro de SQL:" . mysql_error());
        $dados2 = mysql_fetch_array($query2);
        $possiacesso = $dados2[0];
        if ($possiacesso == 0) {
            echo "<br>";
            $tpl_notificacao->block("BLOCK_ATENCAO");
            $tpl_notificacao->LINK = "pessoas_cadastrar.php?codigo=$gestor&operacao=editar";
            $tpl_notificacao->MOTIVO = "Este gestor ainda não possui acesso ao sistema!";
            $tpl_notificacao->block("BLOCK_MOTIVO");
            $tpl_notificacao->PERGUNTA = "Deseja definir uma senha de acesso para ele agora mesmo?";
            $tpl_notificacao->block("BLOCK_PERGUNTA");
            $tpl_notificacao->NAO_LINK = "cooperativa_gestores.php";
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
} else { //Se for uma edição
    echo "Não há edição";
}

include "rodape.php";
?>

