
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
$tpl_titulo->SUBTITULO = "CADASTRO DE SUB-PRODUTOS";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "subproduto.png";
$tpl_titulo->show();

//print_r($_REQUEST);
//exit;


$operacao = $_POST['operacao'];
$produto = $_POST['produto2'];
if ($operacao=="cadastrar") {
    $subproduto = $_POST['subproduto'];
} else {
    $subproduto = $_POST['subproduto2'];
}

//echo "($produto/$subproduto)";
$quantidade = $_POST['quantidade'];
$quantidade = str_replace('.', '', $quantidade);
$quantidade = str_replace(',', '.', $quantidade);
$datahoraatual=date("Y-m-d H:i:s");


//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "produtos_subprodutos.php?produto=$produto";


//Verifica se o subproduto já está incluso
if ($operacao == "cadastrar") {
    $sql = "SELECT * FROM produtos_subproduto WHERE prosub_produto=$produto and prosub_subproduto=$subproduto";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro de SQL 55:" . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas >= 1) {
        //Ele já está na lista!
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->DESTINO = "#";
        $tpl_notificacao->block("BLOCK_ERRO");
        //$tpl_notificacao->block("BLOCK_NAOCADASTRADO");
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "O sub-produto escolhido já está na lista de sub-produtos! <br>Você só pode incluí-lo uma unica vez!";
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
        exit;
    }
}




//Se a operação for cadastro então
if ($operacao == 'cadastrar') {
        
    //Verifica qual é o ultimo numero e define o próximo
    $sql7="SELECT max(prosub_numero) FROM produtos_subproduto WHERE prosub_produto=$produto";
    $query7 = mysql_query($sql7);
    if (!$query7)  die("Erro de SQL:" . mysql_error());
    $dados7=  mysql_fetch_array($query7);
    $ultimo_num=$dados7[0]+1; 
            
    
    
    
    //Insere novo registro
    $sql2 = "
    INSERT INTO 
        produtos_subproduto (
            prosub_produto,
            prosub_subproduto,
            prosub_quantidade,
            prosub_usuarioquecadastrou,
            prosub_datacadastro,
            prosub_numero
        )
    VALUES (
        '$produto',
        '$subproduto',
        '$quantidade',
        '$usuario_codigo',
        '$datahoraatual',
        '$ultimo_num'
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
        UPDATE produtos_subproduto
        SET prosub_quantidade='$quantidade'
        WHERE prosub_produto='$produto'
        AND prosub_subproduto=$subproduto
    ";
    if (!mysql_query($sql))   die("Erro: " . mysql_error());
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}
?>

