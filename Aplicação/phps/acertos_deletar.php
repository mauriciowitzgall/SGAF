<?php
$titulopagina="Acertos Exclusão";

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_acertos_excluir <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "acertos";
include "includes.php";

//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "ACERTOS  ";
$tpl_titulo->SUBTITULO = "EXCLUIR";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "acertos2.jpg";
$tpl_titulo->show();

//RESUMO
//Na exclus�o de acertos devemos alterar todos aqueles produtos que foram vendidos e acertados. Feitos isso
//podemos ent�o excluir o acerto, mas devemos lembrar de excluir primeiro as taxas do acerto para depois excluir
//o acerto em quest�o, dessa forma n�o deixamos lixo no banco.
//Ao excluir devemos considerar algumas regras
//S� podemos excluir um acerto caso ele seja o ultimo acerto do fornecedor, se fosse possível excluir acertos antigos 
//poderia dar problemas quanto ao dado 'valor pendente' pois um acerto depende do outro para ser calculado o 'valor total'.

$tpl6 = new Template("templates/notificacao.html");
$tpl6->ICONES = $icones;

$acerto = $_GET["codigo"];
$fornecedor = $_GET["fornecedor"];

//Verifica se esta � o ultimo acerto do fornecedor do acerto em quest�o
$sql="SELECT max(ace_codigo) FROM acertos WHERE ace_fornecedor=$fornecedor";
$query = mysql_query($sql);
if (!$query)
    die("Erro 1:" . mysql_error());
$dados= mysql_fetch_array($query);

//Se este for o ultimo acerto do fornecedor em quest�o ent�o pode excluir
if ($dados[0]==$acerto) {
    
    //Altera todos os produtos vendidos que foram acertados para 'n�o acertados'
    $sql2 = "
    UPDATE
    saidas_produtos   
    SET 
    saipro_acertado='0'
    WHERE 
    saipro_acertado=$acerto
    ";    
    $query2 = mysql_query($sql2);
    if (!$query2)
        die("Erro2:" . mysql_error());
    
    //Deleta todos as taxas do acerto para depois excluir o acerto
    $sql3 = "DELETE FROM acertos_taxas WHERE acetax_acerto=$acerto";       
    $query3 = mysql_query($sql3);
    if (!$query3)
        die("Erro3:" . mysql_error());
    
    //Deleta o acerto
    $sql4 = "DELETE FROM acertos WHERE ace_codigo=$acerto";       
    $query4 = mysql_query($sql4);
    if (!$query4)
        die("Erro4:" . mysql_error()); 
    $tpl6->block("BLOCK_CONFIRMAR");
    $tpl6->block("BLOCK_APAGADO");    
    $tpl6->DESTINO = "acertos.php";
    $tpl6->block("BLOCK_BOTAO"); 
    
} else {
    //N�o � possivel excluir este acerto
    $tpl6->block("BLOCK_ATENCAO");
    $tpl6->block("BLOCK_NAOAPAGADO"); 
    $tpl6->DESTINO = "acertos.php";
    $tpl6->MOTIVO = "Você não pode excluir este acerto enquanto houverem acertos posteriores a este! Apenas é permitido excluir o ultimo acerto gerado. O motivo disso se dá por vários motivos, entre eles o fato de que o 'Valor Pendente' de um acerto influencía no cálculo do outro.";
    $tpl6->block("BLOCK_MOTIVO");
    $tpl6->block("BLOCK_BOTAO");
       
}
$tpl6->show(); 
include "rodape.php";
?>
