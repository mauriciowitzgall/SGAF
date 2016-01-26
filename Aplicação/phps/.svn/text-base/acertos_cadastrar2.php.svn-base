<?php
$titulopagina="Acertos Cadastro/Edição";

//Verifica se o usuário tem permissão para acessar este conteúdo
require "login_verifica.php";
if ($permissao_acertos_cadastrar <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "acertos";
include "includes.php";

$operacao = $_POST["operacao"];
$fornecedor = $_POST["fornecedor"];
$supervisor = $usuario_codigo;
$data = desconverte_data($_POST["data"]);
$hora = $_POST["hora"];
$valorbruto = $_POST["total_bruto"];
$valortaxas = $_POST["valtaxas"];
$valorpendenteanterior = $_POST["valpen"];
$valortotal = number_format($_POST["valtot"],2,'.',''); //Não precisa usar replace nos , e . pois ele ja vem no formato de banco
$valorpago = $_POST["valpago"];
$valorpago = dinheiro_para_numero($valorpago);
$valorpendenteatual = $valortotal - $valorpago;



//echo "valorpendenteatual = $valortotal - $valorpago";
//$taxacoo =$_POST["taxacoo"];
//$taxaqui = $_POST["taxaqui"];

//echo "valortotal $valortotal valorbruto $valorbruto valortaxas $valortaxas valorpago $valorpago valorpendenteatual $valorpendenteatual taxacoo $taxacoo taxaqui $taxaqui ";

//Verifica se há produtos vendidos a serem acertados
//(Necessário caso o usuário apertou F5, se não tivesse iria duplicar o registro)
$sql = "
            SELECT pro_nome, round(sum(saipro_quantidade),2) as qtd, protip_sigla, avg(saipro_valorunitario) as valuni, round(sum(saipro_valortotal),2) as total
        FROM 
            saidas_produtos
            join produtos on (saipro_produto=pro_codigo)
            join produtos_tipo on (pro_tipocontagem=protip_codigo)
            join entradas on (saipro_lote=ent_codigo)
            join saidas on (saipro_saida=sai_codigo)
        WHERE
            saipro_acertado=0 and
            ent_fornecedor=$fornecedor and
            ent_quiosque=$usuario_quiosque and
            sai_tipo=1 and
            sai_status=1
        GROUP BY 
            saipro_produto
        ";
$query = mysql_query($sql);
if (!$query)
    die("Erro55" . mysql_error());
$linhas = mysql_num_rows($query);
if ($linhas == 0) {
    $tpl11 = new Template("templates/notificacao.html");
    $tpl11->ICONES = $icones;
    $tpl11->block("BLOCK_ATENCAO");
    $tpl11->MOTIVO = "Não há nenhum produto vendido deste fornecedor até o momento, portanto não é possível realizar o acerto!";
    $tpl11->block("BLOCK_MOTIVO");
    $tpl11->DESTINO="acertos.php";
    $tpl11->block("BLOCK_BOTAO");                
    $tpl11->show();
    exit;
}

//Inserir registro do acerto na tabela de acertos
$sql = "
INSERT INTO 
    acertos (
        ace_data,
        ace_hora,
        ace_supervisor,
        ace_fornecedor,
        ace_valorbruto,
        ace_valortaxas,
        ace_valorpendente,
        ace_valorpendenteanterior,
        ace_valortotal,
        ace_valorpago,        
        ace_quiosque
    )
VALUES (
    '$data',
    '$hora',
    '$supervisor',
    '$fornecedor',
    '$valorbruto',
    '$valortaxas',
    '$valorpendenteatual',
    '$valorpendenteanterior',
    '$valortotal',
    '$valorpago',    
    '$usuario_quiosque'        
    )
";
$query = mysql_query($sql);
if (!$query)
    die("Erro1:" . mysql_error());
$ultimo = mysql_insert_id();
echo "<br><br>";


//Inserir as taxas do acerto
//Verifica quais taxas que o quiosque tem para o acerto em questão
$sql = "
    SELECT * FROM quiosques_taxas join taxas on (tax_codigo=quitax_taxa)    
WHERE
    quitax_quiosque=$usuario_quiosque
";
$query = mysql_query($sql);
if (!$query)
    die("Erro43" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $taxa = $dados["quitax_taxa"];
    $taxaref = $dados["quitax_valor"];
    $taxavalor = $valorbruto * $taxaref / 100;
    $sql5 = "
    INSERT INTO
        acertos_taxas (
            acetax_acerto,
            acetax_taxa,
            acetax_referencia,
            acetax_valor
        )
    VALUES (
        '$ultimo',
        '$taxa',
        '$taxaref',
        '$taxavalor'
    )";
    $query5 = mysql_query($sql5);
    if (!$query5)
        die("Erro5:" . mysql_error());
}



//Atribuir como acertado os produtos vendidos
$sql8 = "
UPDATE
    saidas_produtos 
    join produtos on (saipro_produto=pro_codigo)
    join produtos_tipo on (pro_tipocontagem=protip_codigo) 
    join entradas on (saipro_lote=ent_codigo)
    join saidas on (sai_codigo=saipro_saida)
SET 
    saipro_acertado='$ultimo'
WHERE 
    saipro_acertado=0 and
    ent_fornecedor=$fornecedor and
    ent_quiosque=$usuario_quiosque and
    sai_tipo=1 and
    sai_status=1
";
$query8 = mysql_query($sql8);
if (!$query8)
    die("Erro8:" . mysql_error());
$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$tpl6->block("BLOCK_CONFIRMAR");
//$tpl6->block("BLOCK_CADASTRADO");    
$tpl6->MOTIVO = "O acerto foi registrado com sucesso!";

$tpl6->LINK = "acertos_cadastrar3.php?codigo=$ultimo&operacao=imprimir";
$tpl6->block("BLOCK_MOTIVO");
$tpl6->PERGUNTA = "Deseja imprimir o relatórios do acerto?";
$tpl6->block("BLOCK_PERGUNTA");
$tpl6->NAO_LINK = "acertos.php";
$tpl6->LINK_TARGET = "_blank";
$tpl6->block("BLOCK_BOTAO_NAO_LINK");
$tpl6->block("BLOCK_BOTAO_SIMNAO");
$tpl6->show();



include "rodape.php";
?>
