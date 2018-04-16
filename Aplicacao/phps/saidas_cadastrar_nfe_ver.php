<?php
namespace SpedRestFull;

$nfe_numero=$_GET["nfe_numero"];

require "login_verifica.php";
require_once 'SpedRestFull/bootstrap.php';
require_once 'SpedRestFull/EmissorNFe.php';


//Globais
$sql="SELECT * FROM configuracoes WHERE cnf_codigo=1";
if (!$query = mysql_query($sql)) die("<br>Erro SQL Configuracoes GLOBAL: ".mysql_error());
$dados=mysql_fetch_assoc($query);
$nfe_dataversaosistema= $dados["cnf_dataversao"];

//Configuracoes
$sql="
    SELECT * FROM quiosques 
    JOIN quiosques_configuracoes on (quicnf_quiosque=qui_codigo) 
    JOIN cidades on (qui_cidade=cid_codigo)
    JOIN estados on (cid_estado=est_codigo)
    JOIN nfe_danfeimpressao on (quicnf_tipoimpressaodanfe=danfe_codigo)
    JOIN paises on (est_pais=pai_codigo)
    WHERE qui_codigo=$usuario_quiosque
";
if (!$query = mysql_query($sql)) die("<br>Erro SQL Quiosque: ".mysql_error());
$dados=mysql_fetch_assoc($query);
$nfe_geral_ambiente=$dados["quicnf_ambientenfe"];
$nfe_emitente_razaosocial=$dados["qui_razaosocial"];
$nfe_emitente_cnpj=$dados["qui_cnpj"];
$nfe_emitente_estado=$dados["est_sigla"];
$nfe_geral_versaonfe=$dados["quicnf_versaonfe"];
$nfe_geral_csc=$dados["quicnf_csctoken"];
$nfe_geral_csc =  $nfe_geral_csc;   
$nfe_geral_cscid=$dados["quicnf_csctokenid"];
$nfe_geral_serie=intval($dados["quicnf_serienfe"]);
$nfe_geral_crt=$dados["quicnf_crtnfe"];

$arr = [
    "atualizacao" => "$nfe_dataversaosistema", //data da versão do SGAF 
    "tpAmb" => (int)$nfe_geral_ambiente,
    "razaosocial" => "$nfe_emitente_razaosocial",
    "cnpj" => "$nfe_emitente_cnpj",
    "siglaUF" => "$nfe_emitente_estado",
    "schemes" => "PL008i2", 
    "versao" => "$nfe_geral_versaonfe",
    "tokenIBPT" => "",
    "CSC" => "$nfe_geral_csc",
    "CSCid" => "$nfe_geral_cscid",
    "proxyConf" => [
        "proxyIp" => "",
        "proxyConf" => "",
        "proxyUser" => "",
        "proxyPass" => ""
    ]
];
echo $configJson = json_encode($arr);

$emissor = new EmissorNFe($configJson, null, null);
$emissor->geraPDF($nfe_numero);

?>