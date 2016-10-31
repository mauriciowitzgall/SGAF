
<?php

include "controle/conexao.php";
include "controle/conexao_tipo.php";
//require("templates/Template.class.php");

$tpl8 = new Template("cabecalho.html");

//Se o usuário for caixa verifica qual caixa ele está trabalhando
$sql="
    SELECT cai_codigo,cai_nome,caiopo_numero
    FROM pessoas 
    JOIN caixas_operacoes on (caiopo_numero=pes_caixaoperacaonumero) 
    JOIN caixas on (caiopo_caixa=cai_codigo)
    WHERE pes_codigo=$usuario_codigo
";
$query = mysql_query($sql);
if (!$query) die("Erro de SQL Cabeçalho:" . mysql_error());
$dados=mysql_fetch_array($query);
$usuario_caixa=$dados[0];
$usuario_caixa_nome=$dados[1];
$usuario_caixa_operacao=$dados[2];

//echo "($usuario_caixa)($usuario_caixa_nome)($usuario_caixa_operacao)";


if ($usuario_grupo == 7) {
    $tpl8->COOPERATIVA = "O USUÁRIO ROOT NÃO PERTENCE A NENHUMA COOPERATIVA";
    $tpl8->QUIOSQUE = "USUÁRIO ROOT";
    $tpl8->USUARIO_NOME = "";
    $tpl8->CODIGO_USUARIO = "";
} else {
    $tpl8->COOPERATIVA = "$usuario_cooperativanomecompleto";
    $tpl8->QUIOSQUE = $usuario_quiosquenome;
    $tpl8->USUARIO_NOME = $usuario_nome;
    $tpl8->CODIGO_USUARIO = $usuario_codigo;
}

if ($usuario_caixa<>0) {
    if (($usuario_grupo==1)||($usuario_grupo==3)) {
        //$tpl8->block("BLOCK_DESASSOCIAR_CAIXA");
    }
    $tpl8->CAIXAUSUARIO="$usuario_caixa_nome";
    $tpl8->block("BLOCK_NOME_CAIXA");
}
$tpl8->block("BLOCK_NOME_QUIOSQUE_COOPERATIVA");


//Configurações
if (($usuario_grupo==1)||($usuario_grupo==7)) {
    $tpl8->block("BLOCK_CONFIGURACOES");
}


//Grupo de permissão
if ($usuario_grupo == 0) {
    //$tpl8->USUARIO_TIPO_ARQUIVO = "../geral/info.png";
    $tpl8->USUARIO_GRUPO = "Desconhecido";
} else {
    $tpl8->USUARIO_GRUPO = $permissao_nome;
}


//Caixa Abrir Encerrar e Vendas
if ($usuario_grupo==4) {
    if ($usuario_caixa_operacao=="") {
        $tpl8->block("BLOCK_CAIXA_ABRIR");
    }
    else {
        $tpl8->ENCERRARCAIXA="$usuario_caixa_operacao";
        $tpl8->block("BLOCK_CAIXA_ENCERRAR");
        $tpl8->block("BLOCK_VENDAS");
        $tpl8->NUMEROOPERACAO="$usuario_caixa_operacao";
        $tpl8->block("BLOCK_CAIXA_FLUXO");
    }
    
}






//Troca Unidade
//Pega todos os quisoque que o usuario é supervisor ou caixa
$sql="select distinct qui_codigo
from quiosques 
join quiosques_supervisores on quisup_quiosque=qui_codigo
where quisup_supervisor=$usuario_codigo
UNION
select distinct cai_quiosque
from caixas_operadores
join caixas on cai_codigo=caiope_caixa
where caiope_operador=$usuario_codigo";
$query = mysql_query($sql);
if (!$query)
    die("Erro: " . mysql_error());
$qtdquiosques= mysql_num_rows($query);

if (($usuario_grupo == 1)
||($usuario_grupo == 2)
||(($usuario_grupo == 3)&&($qtdquiosques>1))
||(($usuario_grupo == 4)&&($qtdquiosques>1))
) {
    $tpl8->QUIOSQUE_COD="";
    $tpl8->COOPERATIVA_COD="";
    $tpl8->block("BLOCK_TROCA_QUIOSQUE");
}

//Contato
$tpl8->block("BLOCK_SUPORTE");


$tpl8->show();

?>
