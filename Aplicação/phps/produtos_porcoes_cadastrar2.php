
<?php
$tipopagina = "produtos";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_produtos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}
include "includes.php";


//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PRODUTOS";
$tpl_titulo->SUBTITULO = "CADASTRO DE PORÇÕES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos_porcoes.png";
$tpl_titulo->show();

//print_r($_REQUEST);

$operacao = $_POST['operacao'];
$numero = $_POST['numero2'];
$nome = $_POST['porcao_nome'];
$tipocontagem = $_POST['tipocontagem2'];
$produto = $_POST['produto2'];
$quantidade = $_POST['porcao_quantidade'];
$quantidade = str_replace('.', '', $quantidade);
$quantidade = str_replace(',', '.', $quantidade);
$datahoraatual=date("Y-m-d H:i:s");



//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "produtos_porcoes.php?produto=$produto";


//Se a operação for cadastro então
if ($operacao == 'cadastrar') {
    
        //Insere novo registro
        $sql2 = "
        INSERT INTO 
            produtos_porcoes (
                propor_produto,
                propor_nome,
                propor_quantidade,
                propor_usuarioquecadastrou,
                propor_quiosquequecadastrou,
                propor_datacadastro
            )
        VALUES (
            '$produto',
            '$nome',
            '$quantidade',
            '$usuario_codigo',
            '$usuario_quiosque',
            '$datahoraatual'    
        )";
        $query2 = mysql_query($sql2);
        if (!$query2)  die("Erro de SQL:" . mysql_error());
        $ultimo=  mysql_insert_id();
        

        $tpl_notificacao->block("BLOCK_CONFIRMAR");
        $tpl_notificacao->block("BLOCK_EDITADO");
        $tpl_notificacao->block("BLOCK_BOTAO");
        $tpl_notificacao->show();
    }


//Se a operação for edição então
if ($operacao == 'editar') {

    $sql = "
    UPDATE
        produtos_porcoes
    SET            
        propor_nome='$nome',
        propor_quantidade='$quantidade'
    WHERE
        propor_codigo='$numero'
    ";
    if (!mysql_query($sql))   die("Erro: " . mysql_error());
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}
?>

