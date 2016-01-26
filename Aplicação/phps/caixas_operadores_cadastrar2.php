
<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operadores_gerir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";

$caixa = $_POST['caixa2'];
$operador = $_POST['operador'];
$operacao = $_POST['operacao'];
$datafuncao = $_POST['datafuncao'];

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "OPERADORES DE CAIXA";
$tpl_titulo->SUBTITULO = "CADASTRO OPERADORES DE CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "../pessoas2/caixa.png";
$tpl_titulo->show();

//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "caixas_operadores.php?caixa=$caixa";


//Se a operação for cadastro então
if ($operacao=='cadastrar') {
    //Verifica se o supervisor já está na lista de supervisores do quiosque
    $sql = "SELECT * FROM caixas_operadores WHERE caiope_operador=$operador and caiope_caixa=$caixa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro de SQL:" . mysql_error());
    //$dados=  mysql_fetch_assoc($query);
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "Este operador já está na lista!";
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
            caixas_operadores (
                caiope_caixa,
                caiope_operador,
                caiope_datafuncao
            )
        VALUES (
            '$caixa',
            '$operador',
            '$datafuncao'
        )";
        $query = mysql_query($sql);
        if (!$query)
            die("Erro de SQL:" . mysql_error());
        
        //Verifica se esse supervisor já possui um usuário no sistema
        $sql2 = "SELECT pes_possuiacesso FROM pessoas WHERE pes_codigo=$operador";
        $query2 = mysql_query($sql2);
        if (!$query2)
            die("Erro de SQL:" . mysql_error());
        $dados2 = mysql_fetch_array($query2);
        $possiacesso = $dados2[0];
        if ($possiacesso == 0) {
            echo "<br>";
            $tpl_notificacao->block("BLOCK_ATENCAO");
            $tpl_notificacao->LINK = "pessoas_cadastrar.php?codigo=$operador&operacao=editar";
            $tpl_notificacao->MOTIVO = "Este operador ainda não possui acesso ao sistema!";
            $tpl_notificacao->block("BLOCK_MOTIVO");
            $tpl_notificacao->PERGUNTA = "Deseja definir uma senha de acesso para ele agora mesmo?";
            $tpl_notificacao->block("BLOCK_PERGUNTA");
            $tpl_notificacao->NAO_LINK = "caixas_operadores.php?caixa=$caixa";
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
    $sql = "
    UPDATE
        caixas_operadores
    SET            
        caiope_datafuncao='$datafuncao'
    WHERE
        caiope_caixa=$caixa and
        caiope_operador=$operador
    ";
    if (!mysql_query($sql))
        die("Erro 33: " . mysql_error());
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();    
}


include "rodape.php";
?>

