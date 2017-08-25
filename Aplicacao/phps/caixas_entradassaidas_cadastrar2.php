<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_caixas_operacoes_ver <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "caixas";
include "includes.php";


$operacao = $_POST['operacao'];
$tipo = $_POST['tipo'];
if ($tipo==1) {
    $areceber = $_POST['areceber']; 
    $consumidor = $_POST['consumidor'];
    $datavenda = $_POST['datavenda'];
    $venda = $_POST['venda']; if ($venda=="") $venda='null';
} else if ($tipo==2) {
    $areceber = 'null';
    $consumidor = 'null';
    $datavenda = 'null';
    $venda = 'null';   
}
$valor = $_POST['valor'];
$valor_db= dinheiro_para_numero($valor);
$descricao = $_POST['descricao'];
$numero = $_POST['numero'];
if ($operacao=="editar") {
    $id = $_POST['id'];
} else {
    $id="";
}
$datahoraatual=date("Y-m-d H:i:s");

//print_r($_REQUEST);


//TÍTULO PRINCIPAL
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "CAIXAS";
$tpl_titulo->SUBTITULO = "ENTRADAS E SAÍDAS DE CAIXA";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "caixa_entradasaida.png";
$tpl_titulo->show();


$usacaixa=usamodulocaixa($usuario_quiosque);
if ($usacaixa!=1) {
    $tpl6 = new Template("templates/notificacao.html");
    $tpl6->block("BLOCK_ERRO");
    $tpl6->ICONES = $icones;
    //$tpl6->block("BLOCK_NAOAPAGADO");
    $tpl6->MOTIVO = "Você não tem permissão para acessar esta tela.<br>Se deseja realizar vendas solicite a um administrador para <br><b>HABILITAR MÓDULO CAIXA</b>";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO_VOLTAR");
    $tpl6->show();
    exit;
}


//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "caixas_entradassaidas.php?caixaoperacao=$numero";


//Se a operação for cadastro então
if ($operacao == 'cadastrar') {
    
    //Insere novo registro
    $sql2 = "
    INSERT INTO 
        caixas_entradassaidas (
            caientsai_tipo,
            caientsai_valor,
            caientsai_datacadastro,
            caientsai_descricao,
            caientsai_usuarioquecadastrou,
            caientsai_numerooperacao
        )
    VALUES (
        '$tipo',
        '$valor_db',
        '$datahoraatual',
        '$descricao',
        '$usuario_codigo',    
        '$numero'    
    )";
    
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro de SQL:" . mysql_error());
    $ultimo=  mysql_insert_id();

    $tpl_notificacao->block("BLOCK_CADASTRADO");
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}

//Se a operação for edição então
if ($operacao == 'editar') {

    $sql = "
    UPDATE
        caixas_entradassaidas
    SET            
        caientsai_tipo='$tipo',
        caientsai_valor='$valor_db',
        caientsai_descricao='$descricao'
    WHERE
        caientsai_id='$id'
    ";
    if (!mysql_query($sql))
        die("Erro: " . mysql_error());
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
}
?>

