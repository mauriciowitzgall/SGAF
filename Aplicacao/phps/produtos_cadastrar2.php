<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_produtos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

include "includes.php";

$codigo = $_GET['codigo'];
$nome = ucwords(strtolower($_POST['nome']));
$nome2 = ucwords(strtolower($_POST['nome2']));
$modal=$_GET['modal'];

//print_r($_REQUEST);

$tipo = $_POST['tipo'];
$marca = $_POST['marca'];
$recipiente = $_POST['recipiente'];
$volume = $_POST['volume'];
$composicao = $_POST['composicao'];
$industrializado = $_POST['industrializado'];
$subproduto = $_POST['subproduto'];
$tamanho = $_POST['tamanho'];
$cor = $_POST['cor'];
$referencia = $_POST['referencia'];
$categoria = $_POST['categoria'];
$descricao = $_POST['descricao'];
$tiponegociacao = $_POST['box'];
$codigounico = $_POST['codigounico'];
$dadosfiscais = $_POST['dadosfiscais'];
$incluirnanfe = $_POST['incluirnanfe'];
$controlarestoque = $_POST['controlarestoque'];
if ($usaestoque==0) $controlarestoque=0;
$evendido = $_POST['evendido'];
if ($evendido=="") $evendido=0;
$valunicusto=$_POST['valunicusto'];
$valunicusto = str_replace("R$ ", "",$valunicusto);
$valunicusto = str_replace(".", "",$valunicusto);
$valunicusto = str_replace( ",", ".",$valunicusto);
$valunivenda = $_POST['valunivenda'];
$valunivenda = str_replace( "R$ ", "",$valunivenda);
$valunivenda = str_replace( ".", "",$valunivenda);
$valunivenda = str_replace( ",", ".",$valunivenda);

if ($dadosfiscais==1) {
    $ncm = $_POST['nfencm_codigo'];
    $cfop = $_POST['nfecfop_codigo'];
    $ipi = str_replace(",", ".",$_POST['nfeipi']);
    $pis = str_replace(",", ".",$_POST['nfepis']);
    $cofins = str_replace(",", ".",$_POST['nfecofins']);
    $origem = $_POST['nfeorigem'];
} else {
    $ncm = 'null';
    $cfop = 'null';
    $ipi = 'null';
    $pis = 'null';
    $cofins = 'null';
    $origem = 'null'; 
}
$data = date("Y/m/d");
$hora = date("H:i:s");

if ($cofins=="") $cofins=0;
if ($pis=="") $pis=0;
if ($ipi=="") $ipi=0;




//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "PRODUTOS";
$tpl_titulo->SUBTITULO = "CADASTRO/EDIÇÃO";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "produtos.png";
$tpl_titulo->show();

       

//Verifica se foi selecionado pelo menos um tipo de negociacao

if ($evendido==1) {
    if (empty($tiponegociacao)) {
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "É necessário selecionar pelo menos um tipo de negociação!";
        //$tpl_notificacao->DESTINO = "produtos.php";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOEDITADO");
        //$tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
        exit;
    }
}


if ($codigo == "") { //caso seja um cadastro novo fazer isso
    
    /*
    //Verifica se tem produtos com o mesmo nome
    $sql = "SELECT * FROM produtos WHERE pro_nome='$nome' and pro_cooperativa=$usuario_cooperativa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro1: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($linhas > 0) {
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome";
        $tpl_notificacao->DESTINO = "produtos.php";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOEDITADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
        exit;
    } 
    */
    
    $idunico=  uniqid();
    if ($controlarestoque==0) {
        $filtro_controlarestoque_campos=", pro_controlarestoque, pro_valunicusto, pro_valunivenda ";
        $filtro_controlarestoque_valor=", 0, '$valunicusto' , '$valunivenda' ";
    } else {
        $filtro_controlarestoque_campos=", pro_controlarestoque ";
        $filtro_controlarestoque_valor=", $controlarestoque ";
    }
    $sql = "INSERT INTO produtos (
        pro_nome,
        pro_tipocontagem,
        pro_categoria,
        pro_descricao,
        pro_datacriacao,
        pro_horacriacao,
        pro_cooperativa,
        pro_volume,
        pro_marca,
        pro_recipiente,
        pro_composicao,
        pro_codigounico,
        pro_idunico,
        pro_industrializado,
        pro_usuarioquecadastrou,
        pro_quiosquequecadastrou,
        pro_tamanho,
        pro_cor,
        pro_referencia,
        pro_podesersubproduto,
        pro_dadosfiscais,
        pro_ncm,
        pro_cfop,
        pro_ipi,
        pro_pis,
        pro_cofins,
        pro_origem,
        pro_incluirnanfe,
        pro_evendido
        $filtro_controlarestoque_campos
    ) VALUES (
        '$nome',
        '$tipo',
        '$categoria',
        '$descricao',
        '$data',
        '$hora',
        $usuario_cooperativa,
        '$volume',
        '$marca',
        '$recipiente',
        '$composicao',
        '$codigounico',
        '$idunico',
        '$industrializado',
        '$usuario_codigo',
        '$usuario_quiosque',
        '$tamanho',
        '$cor',
        '$referencia',
        '$subproduto',
        '$dadosfiscais',
        $ncm,
        $cfop,
        $ipi,
        $pis,
        $cofins,
        $origem,
        $incluirnanfe,
        $evendido
        $filtro_controlarestoque_valor
    );";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro22: " . mysql_error());
    $ultimo = mysql_insert_id();
    $produto = $ultimo;
    foreach ($tiponegociacao as $tiponegociacao) {
        $sql2 = "
            INSERT INTO 
                mestre_produtos_tipo (
                    mesprotip_produto,
                    mesprotip_tipo                       
                ) 
            VALUES (
                '$produto',
                '$tiponegociacao'
            )";
        if (!mysql_query($sql2))
            die("Erro7: " . mysql_error());
    }
    $tpl_notificacao = new Template("templates/notificacao.html");

    if ($modal==1) $tpl_notificacao->DESTINO = "javascript:window.close(0);";
    else $tpl_notificacao->DESTINO = "produtos.php"; 

    if ($modal!=1) {
        $tpl_notificacao->BOTAOGERAL_DESTINO="produtos_cadastrar.php?operacao=1";
        //$tpl_notificacao->block("BLOCK_BOTAOGERAL_NOVAJANELA");
        $tpl_notificacao->BOTAOGERAL_TIPO="button";
        $tpl_notificacao->BOTAOGERAL_NOME="CADASTRAR MAIS";
        $tpl_notificacao->block("BLOCK_BOTAOGERAL_AUTOFOCO");
        $tpl_notificacao->block("BLOCK_BOTAOGERAL");
    }

    $tpl_notificacao->ICONES = $icones;
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_CADASTRADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    $tpl_notificacao->show();
    
} else { //Caso seja uma alteração de um registro fazer isso
    //Verifica se j� existe registros com o mesmo nome    
    /*
    $sql = "SELECT * FROM produtos WHERE pro_nome='$nome' and pro_cooperativa=$usuario_cooperativa";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro3: " . mysql_error());
    $linhas = mysql_num_rows($query);
    if ($nome == $nome2)
        $linhatot = 1;
    else
        $linhatot = 0;
    if ($linhas > $linhatot) {
        $tpl_notificacao = new Template("templates/notificacao.html");
        $tpl_notificacao->ICONES = $icones;
        $tpl_notificacao->MOTIVO_COMPLEMENTO = "nome";
        $tpl_notificacao->DESTINO = "produto.php";
        $tpl_notificacao->block("BLOCK_ERRO");
        $tpl_notificacao->block("BLOCK_NAOEDITADO");
        $tpl_notificacao->block("BLOCK_MOTIVO_JAEXISTE");
        $tpl_notificacao->block("BLOCK_BOTAO_VOLTAR");
        $tpl_notificacao->show();
    } else { 
    
    */
    if ($controlarestoque==0) {
        $filtro_controlarestoque_update=", pro_controlarestoque=$controlarestoque , pro_valunicusto='$valunicusto' , pro_valunivenda='$valunivenda'";
    } else {
        $filtro_controlarestoque_update=", pro_controlarestoque=$controlarestoque ";
    }
    $sql = "UPDATE produtos SET 
    pro_nome='$nome',
    pro_tipocontagem='$tipo',
    pro_categoria='$categoria',
    pro_descricao='$descricao',
    pro_dataedicao='$data',
    pro_horaedicao='$hora',
    pro_cooperativa='$usuario_cooperativa',
    pro_volume='$volume',
    pro_marca='$marca',
    pro_recipiente='$recipiente',
    pro_composicao='$composicao',
    pro_codigounico='$codigounico',
    pro_tamanho='$tamanho',
    pro_cor='$cor',
    pro_referencia='$referencia',
    pro_podesersubproduto='$subproduto',
    pro_dadosfiscais=$dadosfiscais,
    pro_industrializado=$industrializado,
    pro_ncm=$ncm,
    pro_cfop=$cfop,
    pro_ipi=$ipi,
    pro_pis=$pis,
    pro_cofins=$cofins,
    pro_origem=$origem,
    pro_incluirnanfe=$incluirnanfe,
    pro_evendido=$evendido
    $filtro_controlarestoque_update
    WHERE pro_codigo = '$codigo'
    ";
    if (!mysql_query($sql))
        die("Erro: " . mysql_error());
    //Deleta os tipos de negociação para depois incluir de novo no novo formato
    $sqldel = " DELETE FROM mestre_produtos_tipo WHERE mesprotip_produto='$codigo'";
    if (!mysql_query($sqldel))
        die("Erro9: " . mysql_error());
    foreach ($tiponegociacao as $tiponegociacao) {
        $sql2 = "
        INSERT INTO mestre_produtos_tipo (
            mesprotip_produto,
            mesprotip_tipo
        ) VALUES (
            '$codigo',
            '$tiponegociacao'
        )";
        if (!mysql_query($sql2))
            die("Erro78: " . mysql_error());
    }
    

    $tpl_notificacao = new Template("templates/notificacao.html");
    $tpl_notificacao->ICONES = $icones;
    $tpl_notificacao->MOTIVO_COMPLEMENTO = "";
    $tpl_notificacao->DESTINO = "produtos.php";
    $tpl_notificacao->block("BLOCK_CONFIRMAR");
    $tpl_notificacao->block("BLOCK_EDITADO");
    $tpl_notificacao->block("BLOCK_BOTAO");
    
 
    
    $tpl_notificacao->show();
    
}
$paginadestino = "produtos.php";


include "rodape.php";
?>

