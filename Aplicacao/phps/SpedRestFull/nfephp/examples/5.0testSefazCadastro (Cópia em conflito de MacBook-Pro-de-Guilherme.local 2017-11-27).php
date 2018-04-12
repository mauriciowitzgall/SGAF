<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\NFe\Tools;
use NFePHP\NFe\Make;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;

//tanto o config.json como o certificado.pfx podem estar
//armazenados em uma base de dados, então não é necessário
///trabalhar com arquivos, este script abaixo serve apenas como
//exemplo durante a fase de desenvolvimento e testes.
$arr = [
    "atualizacao" => "2016-11-03 18:01:21",
    "tpAmb" => 2,
    "razaosocial" => "FERNANDA WITZGALL ME",
    "cnpj" => "21996226000164",
    "siglaUF" => "RS",
    "schemes" => "PL008i2",
    "versao" => '4.00',
    "tokenIBPT" => "",
    "CSC" => "F8D212EF010646CA9B7048BA1707D335",
    "CSCid" => "000001",
    "proxyConf" => [
        "proxyIp" => "",
        "proxyPort" => "",
        "proxyUser" => "",
        "proxyPass" => ""
    ]
];
//monta o config.json
$configJson = json_encode($arr);

//carrega o conteudo do certificado.
$content = file_get_contents('1000364088.pfx');

$tools = new Tools($configJson, Certificate::readPfx($content, 'nanda1706'));

//Somente para modelo 55, o modelo 65 evidentemente não possue
//esse tipo de serviço
$tools->model('55');

//coloque a UF e escolha entre
//CNPJ
//IE
//CPF
//pelo menos um dos três deverá ser indicado
//essa busca não funciona se não houver a disponibilidade do serviço na SEFAZ
$uf = 'RS';
$cnpj = '21996226000164';
$iest = '';
$cpf = '';
$response = $tools->sefazCadastro($uf, $cnpj, $iest, $cpf);

header('Content-type: text/xml; charset=UTF-8');
echo $response;









// MAKE 1
$nfe = new Make();
$std = new stdClass();
$std->versao = '4.00'; //versão do layout
$std->Id = 'NFe35150271780456000160550010000000021800700082';//se o Id de 44 digitos não for passado será gerado automaticamente
$std->pk_nItem = null; //deixe essa variavel sempre como NULL

$elem = $nfe->taginfNFe($std);




// MAKE 2

$std = new stdClass();
$std->cUF = 43; // código IBGE do estado de quem está emitindo
$std->cNF = '80070008'; // CODIGO DO SISTEMA EMISSOR (SGAF ou eVET)
/*
 * natOp
 *   Informar a natureza da operação de que decorrer a saída ou aentrada, tais como: 
 *   venda, compra, transferência, devolução, importação, consignação, 
 *   remessa (para fins de demonstração, de industrialização ou outra), 
 *   conforme previsto na alínea 'i', inciso I, art. 19 do CONVÊNIO S/Nº, de 15 de dezembro de 1970.
 */
$std->natOp = 'VENDA';

$std->indPag = 0; //NÃO EXISTE MAIS NA VERSÃO 4.00 --- Versao 3.10= 0: a vista / 1: a prazo / 2: outros

$std->mod = 55; // Exemplos na pasta - 55: NFe / 65: NFCe
$std->serie = 1; // Contador informa
$std->nNF = 2; // CODIGO SEQUENCIAL DO SEFAZ
$std->dhEmi = '2015-02-19T13:48:00-02:00';  
$std->dhSaiEnt = null; // Deixar null para a NFCe (Mod65)
$std->tpNF = 1; // 0:Entrada e 1:Saída
$std->idDest = 1; //1:Operacao interna / 2: Operacao interestadual / 3: Operacao para exterior
$std->cMunFG = 3518800; // Cod_IBGE do municipio que vendeu
/* 
 * tpImp
 *   1=DANFE normal, Retrato; 
 *   2=DANFE normal, Paisagem; 
 *   3=DANFE Simplificado; 
 *   4=DANFE NFC-e; 
 *   5=DANFE NFC-e em mensagem eletrônica 
 *      (o envio de mensagem eletrônica pode ser feita de forma 
 *       simultânea com a impressão do DANFE; 
 *       usar o tpImp=5 quando esta for a única forma de disponibilização do DANFE).
 */
$std->tpImp = 1;
/* 
 * tpEmis
 *    1=Emissão normal (não em contingência); 
 *    2=Contingência FS-IA, com impressão do DANFE em formulário de segurança; 
 *    3=Contingência SCAN (Sistema de Contingência do Ambiente Nacional); 
 *    4=Contingência DPEC (Declaração Prévia da Emissão em Contingência); 
 *    5=Contingência FS-DA, com impressão do DANFE em formulário de segurança; 
 *    6=Contingência SVC-AN (SEFAZ Virtual de Contingência do AN); 
 *    7=Contingência SVC-RS (SEFAZ Virtual de Contingência do RS);
 */
$std->tpEmis = 1;
$std->cDV = 2; ////// --> DESCOBRIR
$std->tpAmb = 2; // 1: producao / 2: Homologação
$std->finNFe = 1; // 1:NFe normal / 2: NFe complementar / 3: NFe de ajsute / 4: Devolução de mercadoria
$std->indFinal = 0; // 0: Normal / 1: Consumidor final
/*
 * indPres
 *   0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste); 
 *   1=Operação presencial; 
 *   2=Operação não presencial, pela Internet; 
 *   3=Operação não presencial, Teleatendimento; 
 *   4=NFC-e em operação com entrega a domicílio; 
 *   9=Operação não presencial, outros.
 */
$std->indPres = 0;
$std->procEmi = '3.10.31';
$std->verProc = null;
$std->dhCont = null;
$std->xJust = null;

$elem = $nfe->tagide($std);

