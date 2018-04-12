<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once 'bootstrap.php';

use NFePHP\NFe\Tools;
use NFePHP\NFe\Make;
use NFePHP\Common\Certificate;
use NFePHP\Common\Standardize;
use NFePHP\Common\Soap\SoapCurl;

use NFePHP\DA\NFe\Danfe;
use NFePHP\DA\NFe\Danfce;
use NFePHP\DA\Legacy\FilesFolders;


class EmissorNFe {
//tanto o config.json como o certificado.pfx podem estar
//armazenados em uma base de dados, então não é necessário
///trabalhar com arquivos, este script abaixo serve apenas como
//exemplo durante a fase de desenvolvimento e testes.
  protected $arr;
  protected $dadosNfe;
  protected $dadosNfeItens;
$arr = [
    "atualizacao" => "2017-11-27 21:25:00",
    "tpAmb" => 2,
    "razaosocial" => "FERNANDA WITZGALL ME",
    "cnpj" => "21996226000164",
    "siglaUF" => "RS",
    "schemes" => "PL008i2",
    "versao" => '3.10',
    "tokenIBPT" => "",
    "CSC" => "F8D212EF010646CA9B7048BA1707D335",
    "CSCid" => "000001",
    "proxyConf" => [
        "proxyIp" => "",
        "proxyConf" => "",
        "proxyUser" => "",
        "proxyPass" => ""
    ]
];

$dadosNfe = [
    "tpAmb" => "2",
    "cDV" => null,
    "id" => "NFe35150271780456000160550010000000021800700082",
    "mod" => "55",
    "cNF" => "12346789",
    "cUF" => "43",
    "natOp" => "VENDA",
    "indPag" => "0",
    "serie" => "1",
    "nNF" => "1",
    "dhEmi" => "2017-12-11T09:54:18-02:00",
    "dhSaiEnt" => null,
    "tpNF" => "1",
    "idDest" => "1",
    "cMunFG" => "4307005",
    "tpImp" => "1",
    "tpEmis" => "1",
    "finNFe" => "1",
    "indFinal" => "0",
    "indPres" => "9",
    "procEmi" => "0",
    "verProc" => "1.0.0",
    "xNome" => "FERNANDA WITZGALL ME",
    "xFant" => "Clínica Veterinária Fernanda Witzgall",
    "IE" => "0390174319",
    "IEST" => null,
    "IM" => null,
    "CNAE" => null,
    "CRT" => 1,
    "xLgr" => "Rua Torres Gonçalves",
    "nro" => "156",
    "xCpl" => null,
    "xBairro" => "Centro",
    "cMun" => "4307005",
    "xMun" => "Erechim",
    "UF" => "RS",
    "CEP" => "99700422",
    "cPais" => "1058",
    "xPais" => "Brasil",
    "fone" => "5499683888",
    "vBC" => null,
    "vICMS" => null,
    "vICMSDesonv" => null,
    "vBCST" => null,
    "vST" => null,
    "vProd" => 100.00,
    "vFrete" => null,
    "vSeg" => null,
    "vDesc" => null,
    "vII" => null,
    "vIPI" => null,
    "vPIS" => null,
    "vCOFINS" => null,
    "vOutro" => null,
    "vNF" => 100.00,
    "vTotTrib" => null,
    "vFCP" => null,
    "vFCPST" => null,
    "vFCPSTRet" => null,
    "vIPIDevol" => null,
    "modFrete" => 1,
    "tPag" => "01",
    "vPag" => 100.00,
    "tpIntegra" => 2,
    "vTroco" => null,
    "doctoNfe" => [
      "doc" => "CNPJ",
      "CNPJ" => "21996226000164",
      "CPF" => null,
    ],
    "usaNfeCartaoCredito" => [
      "Cartao" => false,
      "CNPJ" => null,
      "tBand" => "02",
      "cAut" => null,
    ],
    "destinatario" => [
      "xNome" => "Guilherme Afonso Madalozzo",
      "indIEDest" => "9",
      "IE" => null,
      "ISUF" => null,
      "IM" => null,
      "email" => "guimadalozzo@gmail.com",
      "idEstrangeiro" => null,
      "xLgr" => "Rua João Paulo I",
      "nro" => 585,
      "xCpl" => null,
      "xBairro" => "Três Vendas",
      "cMun" => "4307005",
      "xMun" => "Erechim",
      "UF" => "RS",
      "CEP" => "99713220",
      "cPais" => "1058",
      "xPais" => "BRASIL",
      "fone" => "54996227898",
      "doctoNfe" => [
        "doc" => "CPF",
        "CNPJ" => null,
        "CPF" => "01852675055",
      ],
      "endEntrega" => [
        "xLgr" => "Rua João Paulo I",
        "nro" => 585,
        "xCpl" => null,
        "xBairro" => "Três Vendas",
        "cMun" => "4307005",
        "xMun" => "Erechim",
        "UF" => "RS",
        "doctoNfe" => [
          "doc" => "CPF",
          "CNPJ" => null,
          "CPF" => "01852675055",
        ],
      ],
      "endRetirada" => [
        "retirada" => false,
        "xLgr" => null,
        "nro" => null,
        "xCpl" => null,
        "xBairro" => null,
        "cMun" => null,
        "xMun" => null,
        "UF" => null,
        "doctoNfe" => [
          "doc" => null,
          "CNPJ" => null,
          "CPF" => null,
        ],
      ],
    ],
    "usaNfContingencia" => [
      "contingencia" => false,
      "dhCont" => "2015-02-19T13:48:00-02:00",
      "xJust" => "Justificativa da contingencuia",
    ],
    "usaNfReferenciada" => [
      "referenciada" => false,
      "refNFe" => "35150271780456000160550010000253101000253101",
    ],
    "usaNfISSQN" => [
      "ISSQN" => false,
    ],
    "usaNfRetTributos" => [
      "RetTributos" => false,
    ],
    "usaNfTransportadora" => [
      "Transportadora" => false,
      "xNome" => null,
      "IE" => null,
      "xEnder" => null,
      "xMun" => null,
      "UF" => null,
      "CNPJ" => null,
      "CPF" => null,
      "usaNfVolumeTransportadora" => [
        "Volume" => false,
        1 => [
          "item" => "1",
          "qVol" => "2",
          "esp" => "caixa",
          "marca" => null,
          "nVol" => null,
          "pesoL" => null,
          "pesoB" => null,
        ],
      ],
    ],
    "usaNfDetalheVeicTransportadora" => [
      "Veiculo" => false,
    ],
    "usaNfReboqueVeicTransportadora" => [
      "Reboque" => false,
    ],
    "usaNfDadosFatura" => [
      "Fatura" => false,
      "nFat" => null,
      "vOrig" => null,
      "vDesc" => null,
      "vLiq" => null,
    ],
    "usaNfDadosDuplicata" => [
      "Duplicata" => false,
      "nDup" => null,
      "dVenc" => null,
      "vDup" => null,
    ],
    "usaNfExportacao" => [
      "Exportacao" => false,
    ],
    "infAdFisco" => null,
    "infCpl" => null,
    "qtdItens" => "1",
];

$dadosNfeItens = [
  1 => [
    "item" => 1,
    "cProd" => 1,
    "cEAN" => null,
    "xProd" => "Vanguard Plus 1DS",
    "NCM" => "30023090",
    "EXTIPI" => null,
    "CFOP" => "5102",
    "uCom" => "UN",
    "qCom" => 1,
    "vUnCom" => 55.00,
    "vProd" => 55.00,
    "cEANTrib" => null,
    "uTrib" => "UN",
    "qTrib" => 1,
    "vUnTrib" => 55.00,
    "vFrete" => null,
    "vSeg" => null,
    "vDesc" => null,
    "vOutro" => null,
    "indTot" => 1,
    "xPed" => null,
    "nItemPed" => null,
    "nFCI" => null,
    "infAdProd" => "Informações adicionais do produto.",
    "CEST" => null,
    "indEscala" => null,
    "CNPJFab" => null,
    "nLote" => null,
    "qLote" => null,
    "dFab" => null,
    "dVal" => null,
    "cAgreg" => null,
    "vTotTrib" => 0.00,
    "orig" => "0",
    "CST" => "40",
    "modBC" => null,
    "vBC" => null,
    "pICMS" => null,
    "vICMS" => null,
    "pFCP" => null,
    "vFCP" => null,
    "vBCFCP" => null,
    "modBCST" => null,
    "pMVAST" => null,
    "pRedBCST" => null,
    "vBCST" => null,
    "pICMSST" => null,
    "vICMSST" => null,
    "vBCFCPST" => null,
    "pFCPST" => null,
    "vFCPST" => null,
    "vICMSDeson" => null,
    "motDesICMS" => null,
    "pRedBC" => null,
    "vICMSOp" => null,
    "pDif" => null,
    "vICMSDif" => null,
    "vBCSTRet" => null,
    "pST" => null,
    "vICMSSTRet" => null,
    "vBCFCPSTRet" => null,
    "pFCPSTRet" => null,
    "vFCPSTRet" => null,
    "pBCOp" => null,
    "UFST" => null,
    "vBCSTDest" => null,
    "vICMSSTDest" => null,
    "CSOSN" => "400",
    "pCredSN" => null,
    "vCredICMSSN" => null,
    "vBCUFDest" => null,
    "vBCFCPUFDest" => null,
    "pFCPUFDest" => null,
    "pICMSUFDest" => null,
    "pICMSInter" => null,
    "pICMSInterPart" => null,
    "vFCPUFDest" => 0.00,
    "vICMSUFDest" => null,
    "vICMSUFRemet" => null,
    "pPIS" => null,
    "vPIS" => null,
    "qBCProd" => null,
    "vAliqProd" => null,
    "pCOFINS" => null,
    "vCOFINS" => null,
    "qBCProd" => null,
    "vAliqProd" => null,
    "pDevol" => null,
    "vIPIDevol" => null,
    "usaNfDevolucao" => [
      "Devolucao" => false,
    ],
    "usaNfISSQN" => [
      "ISSQN" => false,
    ],
    "usaNfCOFINSST" => [
      "cofinsST" => false,
    ],
    "usaNfTributacaoCOFINS" => [
      "cofins" => false,
    ],
    "usaNfPISST" => [
      "pisST" => false,
    ],
    "usaNfPIS" => [
      "PIS" => false,
    ],
    "usaNfIPI" => [
      "IPI" => false,
    ],
    "usaNfII" => [
      "ImpostoImportacao" => false,
    ],
    "usaNfTributacaoSN" => [
      "TributaSN" => true,
    ],
    "usaNfICMSInterestadual" => [
      "ICMSInter" => false,
    ],
    "usaNfICMSRetido" => [
      "RetemICMS" => false,
    ],
    "usaNfPartilhaUF" => [
      "PartilhaUF" => false,
    ],
    "usaNfRecopi" => [
      "Recopi" => false,
    ],
    "usaNfDI" => [
      "DI" => false,
    ],
    "usaNfExportacao" => [
      "Exportacao" => false,
    ],
    "usaNfVeiculo" => [
      "Veiculo" => false,
    ],
    "usaNfMedicamentos" => [
      "Medicamento" => false,
      "nLote" => null,
      "qLote" => null,
      "dFab" => null,
      "dVal" => null,
      "vPMC" => null,
      "cProdANVISA" => null,
    ],
    "usaNfArmamento" => [
      "Armamento" => false,
    ],
    "usaNfCombustivel" => [
      "Combustivel" => false,
    ],
  ],
];

//monta o config.json
$configJson = json_encode($arr);
$nfeJson = json_encode($dadosNfe);
$nfeItensJson = json_encode($dadosNfeItens);

$content = file_get_contents('includes/1000364088.pfx');
$tools = new Tools($configJson, Certificate::readPfx($content, 'nanda1706'));
$tools->model($dadosNfe['mod']);
$nfeLayout = "3.10";//$arr["versao"];

$nfe = new Make();

$std = new stdClass();
$std->versao = $nfeLayout; //versão do layout
$std->Id = $dadosNfe["id"]; //se o Id de 44 digitos não for passado será gerado automaticamente
$std->pk_nItem = null; //deixe essa variavel sempre como NULL
$elem = $nfe->taginfNFe($std);

$std = new stdClass();
$std->cUF = $dadosNfe["cUF"]; // código IBGE do estado de quem está emitindo
$std->cNF = $dadosNfe["cNF"]; // CODIGO DO SISTEMA EMISSOR (SGAF ou eVET)
/*
 * natOp
 *   Informar a natureza da operação de que decorrer a saída ou aentrada, tais como:
 *   venda, compra, transferência, devolução, importação, consignação,
 *   remessa (para fins de demonstração, de industrialização ou outra),
 *   conforme previsto na alínea 'i', inciso I, art. 19 do CONVÊNIO S/Nº, de 15 de dezembro de 1970.
 */
$std->natOp = $dadosNfe["natOp"];
if ($nfeLayout != "4.00") {
    $std->indPag = $dadosNfe["indPag"]; //NÃO EXISTE MAIS NA VERSÃO 4.00 --- Versao 3.10= 0: a vista / 1: a prazo / 2: outros
}
$std->mod = $dadosNfe["mod"]; // Exemplos na pasta - 55: NFe / 65: NFCe
$std->serie = $dadosNfe["serie"]; // Contador informa
$std->nNF = $dadosNfe["nNF"]; // CODIGO SEQUENCIAL DO SEFAZ
$std->dhEmi = $dadosNfe["dhEmi"];
$std->dhSaiEnt = $dadosNfe["dhSaiEnt"]; // Deixar null para a NFCe (Mod65)
$std->tpNF = $dadosNfe["tpNF"]; // 0:Entrada e 1:Saída
$std->idDest = $dadosNfe["idDest"]; //1:Operacao interna / 2: Operacao interestadual / 3: Operacao para exterior
$std->cMunFG = $dadosNfe["cMunFG"]; // Cod_IBGE do municipio que ocorreu a operação de venda (vendedor a pronta entrega, por exemplo cuja NF foi emitida em outra cidade)
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
$std->tpImp = $dadosNfe["tpImp"];
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
$std->tpEmis = $dadosNfe["tpEmis"];
$std->cDV = $dadosNfe["mod"]; ////// --> DESCOBRIR
$std->tpAmb = $dadosNfe["tpAmb"]; // 1: producao / 2: Homologação
$std->finNFe = $dadosNfe["finNFe"]; // 1:NFe normal / 2: NFe complementar / 3: NFe de ajsute / 4: Devolução de mercadoria
$std->indFinal = $dadosNfe["indFinal"]; // 0: Normal / 1: Consumidor final
/*
 * indPres
 *   0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
 *   1=Operação presencial;
 *   2=Operação não presencial, pela Internet;
 *   3=Operação não presencial, Teleatendimento;
 *   4=NFC-e em operação com entrega a domicílio;
 *   9=Operação não presencial, outros.
 */
$std->indPres = $dadosNfe["indPres"];
$std->procEmi = $dadosNfe["procEmi"]; // SEMPRE ZERO - Emissao deNFe com aplicativo do contribuinte
$std->verProc = $dadosNfe["verProc"]; // Versão do sistema (SGAF ou eVET)

if($dadosNfe["usaNfContingencia"]["contingencia"] == true) {
    $std->dhCont = $dadosNfe["usaNfContingencia"]["dhCont"]; // Data e hora em contingência -- só usar isso em contingência
    $std->xJust = $dadosNfe["usaNfContingencia"]["xJust"]; // Justificar contingência - Tamanho de 15 a 256 caracteres
}
$elem = $nfe->tagide($std);

// SE PRECISAR REFERENCIAR UMA NFe ou NFCe UTILIZAR ESTA TAG
// Em casos de devolução ou cancelamento ou complementacao
//
// Temos a opção de refenciar uma Nota do modelo 1 ou 2 (bloco de notas impressas em graficas)
//
// Temos opção para se caso algum produtor rural não ter ainda a NFe
//
// Temos opção de referenciar CTe (Conhecimento de Transporte Eletrônico)
//
// Temos opção de referenciar ECF (Escrituração Contábil Fiscal)
if($dadosNfe["usaNfReferenciada"]["referenciada"] == true) {
    $std = new stdClass();
    $std->refNFe = $dadosNfe["usaNfReferenciada"]["refNFe"]; // Chave de acesso da NF referenciada

    $elem = $nfe->tagrefNFe($std);
}

$std = new stdClass();
$std->xNome = $dadosNfe["xNome"]; // Razao social ou nome do emitente
$std->xFant = $dadosNfe["xFant"]; // Nome fantasia
$std->IE = $dadosNfe["IE"]; // Inscricao estadual do emitente
$std->IEST = $dadosNfe["IEST"];// IE do substituto tributário ---- Ver se o emitente tem com o contator
$std->IM = $dadosNfe["IM"]; // Inscricao municipal --- informar apenas com itens e servicos misturados na NFe (A PRINCIPIO NAO VAMOS USAR_
$std->CNAE = $dadosNfe["CNAE"]; // Campo opcional -- informar qdo a IM for informada
$std->CRT = $dadosNfe["CRT"]; // Codigo de Regime Tributario // 1: Simples Nacional / 2: Simples Nacional, excesso sublimite de receita bruta / 3: Regime Normal
if ($dadosNfe["doctoNfe"]["doc"] == "CNPJ") {  // Indicar apenas um CNPJ ou CPF
    $std->CNPJ = $dadosNfe["doctoNfe"]["CNPJ"];
}
else {
    $std->CPF = $dadosNfe["doctoNfe"]["CPF"];
}
$elem = $nfe->tagemit($std);

$std = new stdClass();
$std->xLgr = $dadosNfe["xLgr"]; // Logradouro
$std->nro = $dadosNfe["nro"]; // Numero
$std->xCpl = $dadosNfe["xCpl"]; // Complemento
$std->xBairro = $dadosNfe["xBairro"]; // Bairro
$std->cMun = $dadosNfe["cMun"]; // Cod_IBGE do municipio emitente
$std->xMun = $dadosNfe["xMun"]; // Nome do municipio
$std->UF = $dadosNfe["UF"];  // Sigla do estado
$std->CEP = $dadosNfe["CEP"];
$std->cPais = $dadosNfe["cPais"]; // Codigo do pais 1058 para Brasil
$std->xPais = $dadosNfe["xPais"]; // Nome do pais Brasil ou BRASIL
$std->fone = $dadosNfe["fone"]; // DDD + numero... para venda exterior adicionar codigo do pais

$elem = $nfe->tagenderEmit($std);


if ($dadosNfe["destinatario"]["xNome"] != null) {
  $std = new stdClass();
  $std->xNome = $dadosNfe["destinatario"]["xNome"];
  /*
   * indIEDest
   *    1=Contribuinte ICMS (informar a IE do destinatário);
   *    2=Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS
   *    9=Não Contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes do ICMS.
   *    ***Nota 1: No caso de NFC-e informar indIEDest=9 e não informar a tag IE do destinatário;
   *    Nota 2: No caso de operação com o Exterior informar indIEDest=9 e não informar a tag IE do destinatário;
   *    Nota 3: No caso de Contribuinte Isento de Inscrição (indIEDest=2), não informar a tag IE do destinatário.
   */
  $std->indIEDest = $dadosNfe["destinatario"]["indIEDest"];
  $std->IE = $dadosNfe["destinatario"]["IE"]; // Opcional = IE do destinatario nao informar em NFCe
  $std->ISUF = $dadosNfe["destinatario"]["ISUF"]; // Só é obrigatorio para incentivados pelo SUFRAMA (MANAUS)
  $std->IM = $dadosNfe["destinatario"]["IM"]; // opcional
  $std->email = $dadosNfe["destinatario"]["email"];
  if ($dadosNfe["destinatario"]["doctoNfe"]["doc"] == "CNPJ") {
      $std->CNPJ = $dadosNfe["destinatario"]["doctoNfe"]["CNPJ"]; //indicar apenas um CNPJ ou CPF ou idEstrangeiro
  }
  else {
      $std->CPF = $dadosNfe["destinatario"]["doctoNfe"]["CPF"];
  }
  $std->idEstrangeiro = $dadosNfe["destinatario"]["idEstrangeiro"]; // numero do passaporte ou documento legal -- pode ser null
  //$elem = $nfe->tagdest($std);

  // $std = new stdClass();
  // $std->xLgr    = $dadosNfe["destinatario"]["xLgr"];
  // $std->nro     = $dadosNfe["destinatario"]["nro"];
  // $std->xCpl    = $dadosNfe["destinatario"]["xCpl"];
  // $std->xBairro = $dadosNfe["destinatario"]["xBairro"];
  // $std->cMun    = $dadosNfe["destinatario"]["cMun"]; // Cod_IBGE do municipio do comprador
  // $std->xMun    = $dadosNfe["destinatario"]["xMun"]; // Nome do municipio
  // $std->UF      = $dadosNfe["destinatario"]["UF"]; // Sigla
  // $std->CEP     = $dadosNfe["destinatario"]["CEP"];
  // $std->cPais   = $dadosNfe["destinatario"]["cPais"];  // Codigo do pais 1058 para Brasil
  // $std->xPais   = $dadosNfe["destinatario"]["xPais"]; // Nome pais Brasil ou BRASIL
  // $std->fone    = $dadosNfe["destinatario"]["fone"];
  //$elem = $nfe->tagenderDest($std);

  // Obrigatorio qdo a entrega do produto for diferente do endereco do emitente (por exemplo, venda a pronta entrega - a venda esta sendo feita em passo fundo mas a empresa do vendedor esta em erehim)
  if ($dadosNfe["destinatario"]["endRetirada"]["retirada"] == true) {
      $std = new stdClass();
      $std->xLgr = $dadosNfe["destinatario"]["endEntrega"]["xLgr"];
      $std->nro = $dadosNfe["destinatario"]["endEntrega"]["nro"];
      $std->xCpl = $dadosNfe["destinatario"]["endEntrega"]["xCpl"];
      $std->xBairro = $dadosNfe["destinatario"]["endEntrega"]["xBairro"];
      $std->cMun = $dadosNfe["destinatario"]["endEntrega"]["cMun"];
      $std->xMun = $dadosNfe["destinatario"]["endEntrega"]["xMun"];
      $std->UF = $dadosNfe["destinatario"]["endEntrega"]["UF"];
      if ($dadosNfe["destinatario"]["endEntrega"]["doctoNfe"]["doc"] == "CNPJ") {
          $std->CNPJ = $dadosNfe["destinatario"]["endEntrega"]["doctoNfe"]["CNPJ"]; //indicar um CNPJ ou CPF
      }
      else {
          $std->CPF = $dadosNfe["destinatario"]["endEntrega"]["doctoNfe"]["CPF"];
      }
      $elem = $nfe->tagretirada($std);
  }

  // exemplo, compra da TV com o Teti.. o cadastro dele tem endereco em Santa Maria mas mandou entregar em Erechim)
  $std = new stdClass();
  $std->xLgr = $dadosNfe["destinatario"]["endEntrega"]["xLgr"];
  $std->nro = $dadosNfe["destinatario"]["endEntrega"]["nro"];
  $std->xCpl = $dadosNfe["destinatario"]["endEntrega"]["xCpl"];
  $std->xBairro = $dadosNfe["destinatario"]["endEntrega"]["xBairro"];
  $std->cMun = $dadosNfe["destinatario"]["endEntrega"]["cMun"];
  $std->xMun = $dadosNfe["destinatario"]["endEntrega"]["xMun"];
  $std->UF = $dadosNfe["destinatario"]["endEntrega"]["UF"];
  if ($dadosNfe["destinatario"]["endEntrega"]["doctoNfe"]["doc"] == "CNPJ") {
      $std->CNPJ = $dadosNfe["destinatario"]["endEntrega"]["doctoNfe"]["CNPJ"]; //indicar um CNPJ ou CPF
  }
  else {
      $std->CPF = $dadosNfe["destinatario"]["endEntrega"]["doctoNfe"]["CPF"];
  }
  $elem = $nfe->tagentrega($std);
}

$std = new stdClass();
$std->CNPJ = $dadosNfe["doctoNfe"]["CNPJ"]; //indicar um CNPJ ou CPF
$std->CPF  = $dadosNfe["doctoNfe"]["CPF"];
$elem = $nfe->tagautXML($std);


foreach ($dadosNfeItens as $itemNfe) {
    $std = new stdClass();
    $std->item = $itemNfe["item"]; //item da NFe (Sequencial 1-n)
    $std->cProd = $itemNfe["cProd"]; // Código do produto no sistema (SGAF ou eVET)
    $std->cEAN = $itemNfe["cEAN"]; // Código de Barras
    $std->xProd = $itemNfe["xProd"]; // Descricao do produto
    $std->NCM = $itemNfe["NCM"]; // Nomenclaru comum do mercosul
    if ($nfeLayout == "4.00") {
        $std->cBenf = $itemNfe["cBenf"]; //***incluido no layout 4.00
    }
    $std->EXTIPI = $itemNfe["EXTIPI"]; // Contador diz o uso
    $std->CFOP = $itemNfe["CFOP"];
    $std->uCom = $itemNfe["uCom"]; // Unidade
    $std->qCom = $itemNfe["qCom"]; // Quantidade
    $std->vUnCom = $itemNfe["vUnCom"]; // Valor unitario
    $std->vProd = $itemNfe["vProd"]; // Valor total bruto do produto
    $std->cEANTrib = $itemNfe["cEANTrib"]; // Codigo barras da unidade tributavel
    $std->uTrib = $itemNfe["uTrib"]; // Unidade
    $std->qTrib = $itemNfe["qTrib"]; // Quantidade
    $std->vUnTrib = $itemNfe["vUnTrib"]; // Valor tributada
    $std->vFrete = $itemNfe["vFrete"]; // Valor frete
    $std->vSeg = $itemNfe["vSeg"]; // Valor do seguro
    $std->vDesc = $itemNfe["vDesc"]; // Valor desconto
    $std->vOutro = $itemNfe["vOutro"]; // Outras despesas
    $std->indTot = $itemNfe["indTot"]; // Indica se o valor total compoe ou nao na NFe // 0: Valor do Item não compoe o valor total da NFe / 1: Valor do item compoes o valor total da NFe
    $std->xPed = $itemNfe["xPed"]; // opcional numero do pedido
    $std->nItemPed = $itemNfe["nItemPed"]; // opcional item do pedido
    $std->nFCI = $itemNfe["nFCI"]; // Para importacao
    $elem = $nfe->tagprod($std);

    $std = new stdClass();
    $std->item = $itemNfe["item"]; //item da NFe
    if ($itemNfe["infAdProd"] != null) {
      $std->infAdProd = $itemNfe["infAdProd"];
      $elem = $nfe->taginfAdProd($std);
    }

    // LAYOUT 4.00
    if ($nfeLayout == "4.00") {
        $std = new stdClass();
        $std->item = $itemNfe["item"]; //item da NFe
        $std->CEST = $itemNfe["CEST"];
        $std->indEscala = $itemNfe["indEscala"]; //incluido no layout 4.00
        $std->CNPJFab = $itemNfe["CNPJFab"]; //incluido no layout 4.00
        $elem = $nfe->tagCEST($std);
    }

    if ($itemNfe["usaNfRecopi"]["Recopi"] == true) {
      // TEMOS OPCAO PARA GERAR TAG PARA RECOPI
    }

    if ($itemNfe["usaNfDI"]["DI"] == true) {
      // TEMOS OPCAO PARA GERAR TAG DE Declaracao de Importacao (tagDI)
    }

    if ($itemNfe["usaNfExportacao"]["Exportacao"] == true) {
      // TEMOS OPCAO PARA GERAR TAG DE EXPORTACAO
    }

    if ($nfeLayout == "4.00") {
        $std = new stdClass();
        $std->item = $itemNfe["item"]; //item da NFe
        $std->nLote = $itemNfe["nLote"];
        $std->qLote = $itemNfe["qLote"];
        $std->dFab = $itemNfe["dFab"];
        $std->dVal = $itemNfe["dVal"];
        $std->cAgreg = $itemNfe["cAgreg"];
        $elem = $nfe->tagRastro($std);
    }

    if ($itemNfe["usaNfVeiculo"]["Veiculo"] == true) {
      // TEMOS OPCAO PARA TAGS DE INDUSTRIALIZACAO DE VEICULOS
    }

    // VER VERSAO 4
    if (($nfeLayout == "3.10") && ($itemNfe["usaNfMedicamentos"]["Medicamento"] == true)) {
        $std = new stdClass();
        $std->item = $itemNfe["item"]; //item da NFe
        $std->nLote = $itemNfe["nLote"]; //removido no layout 4.00
        $std->qLote = $itemNfe["qLote"]; //removido no layout 4.00
        $std->dFab = $itemNfe["dFab"]; //removido no layout 4.00
        $std->dVal = $itemNfe["dVal"]; //removido no layout 4.00
        $std->vPMC = $itemNfe["vPMC"];
        $std->cProdANVISA = $itemNfe["cProdANVISA"]; //incluido no layout 4.00
        $elem = $nfe->tagmed($std);
    }

    if ($itemNfe["usaNfArmamento"]["Armamento"] == true) {
      // TEMOS OPCAO PARA TAGS DE COMERCIALIZACAO DE ARMAMENTO
    }

    if ($itemNfe["usaNfCombustivel"]["Combustivel"] == true) {
      // TEMOS OPCAO PARA TAGS DE COMERCIALIZACAO DE COMBUSTIVEL
    }

    $std = new stdClass();
    $std->item = $itemNfe["item"]; //item da NFe
    $std->vTotTrib = $itemNfe["vTotTrib"]; // Por Item -- Valor total de tributos
    $elem = $nfe->tagimposto($std);

    if ($itemNfe["usaNfTributacaoSN"]["TributaSN"] == false) {
      $std = new stdClass();
      $std->item = $itemNfe["item"]; //item da NFe

       /*
       * orig
       *    0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8;
       *    1 - Estrangeira - Importação direta, exceto a indicada no código 6;
       *    2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7;
       *    3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%;
       *    4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes;
       *    5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%;
       *    6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX e gás natural;
       *    7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e gás natural.
       *    8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%;
       */
      $std->orig = $itemNfe["orig"]; // Origem da mercadoria
      $std->CST = $itemNfe["CST"]; //CST 00 / 10 / 20 / 30 / 40 / 41 / 50 / 51 / 60 / 70 / 90
      /*
       * modBC
       *    0=Margem Valor Agregado (%);
       *    1=Pauta (Valor);
       *    2=Preço Tabelado Máx. (valor);
       *    3=Valor da operação.
       */
      $std->modBC = $itemNfe["modBC"]; // Modalidade da base de calculo
      $std->vBC = $itemNfe["vBC"]; // Valor da base de calculo do ICMS
      $std->pICMS = $itemNfe["pICMS"]; // Aliquota do ICMS
      $std->vICMS = $itemNfe["vICMS"]; // Valor do ICMS
      $std->pFCP = $itemNfe["pFCP"]; // ??????
      $std->vFCP = $itemNfe["vFCP"];  // ?????
      $std->vBCFCP = $itemNfe["vBCFCP"]; // ?????
      /*
       * modBCST - Modalidade de determinacao da BC do ICMS ST
       *    0=Preço tabelado ou máximo sugerido;
       *    1=Lista Negativa (valor);
       *    2=Lista Positiva (valor);
       *    3=Lista Neutra (valor);
       *    4=Margem Valor Agregado (%);
       *    5=Pauta (valor);
       */
      $std->modBCST = $itemNfe["modBCST"];
      $std->pMVAST = $itemNfe["pMVAST"]; // Percentual da margem do Valor Adicionar do ICMS ST
      $std->pRedBCST = $itemNfe["pRedBCST"]; // Percentual de redução da BC do ICMS ST
      $std->vBCST = $itemNfe["vBCST"]; // Valor da BC do ICMS ST
      $std->pICMSST = $itemNfe["pICMSST"]; // Aliquota do importo do ICMS ST
      $std->vICMSST = $itemNfe["vICMSST"]; // Valor do ICMS ST
      $std->vBCFCPST = $itemNfe["vBCFCPST"]; // ???????
      $std->pFCPST = $itemNfe["pFCPST"]; // ?????
      $std->vFCPST = $itemNfe["vFCPST"]; // ???????
      $std->vICMSDeson = $itemNfe["vICMSDeson"]; // Informar nos motivos de desoneracao informadas no proximo campo
      /*
       * motDesICMS - Motivo de desoneração do ICMS
       *    Campo será preenchido quando o campo anterior estiver preenchido.
       *    Informar o motivo da desoneração:
       *      3=Uso na agropecuária;
       *      9=Outros;
       *      12=Órgão de fomento e desenvolvimento agropecuário.
       */
      $std->motDesICMS = $itemNfe["motDesICMS"];
      $std->pRedBC = $itemNfe["pRedBC"]; // Percentual da redução da BC
      $std->vICMSOp = $itemNfe["vICMSOp"]; // Valor como se nao tivesse o diferimento
      $std->pDif = $itemNfe["pDif"]; // Percentual do diferimento - se houver diferimento total infornar 100
      $std->vICMSDif = $itemNfe["vICMSDif"]; // Valor do ICMS diferido
      $std->vBCSTRet = $itemNfe["vBCSTRet"]; // Valor da BC do ICMS ST retido
      $std->pST = $itemNfe["pST"];  // ????????
      $std->vICMSSTRet = $itemNfe["vICMSSTRet"]; // Valor do ICMS ST retido
      $std->vBCFCPSTRet = $itemNfe["vBCFCPSTRet"]; //?????????
      $std->pFCPSTRet = $itemNfe["pFCPSTRet"]; //?????????
      $std->vFCPSTRet = $itemNfe["vFCPSTRet"]; //?????????

      $elem = $nfe->tagICMS($std);
    }

    if ($itemNfe["usaNfPartilhaUF"]["PartilhaUF"] == true) {
      $std = new stdClass();
      $std->item = $itemNfe["item"]; //item da NFe
      /*
       * orig
       *   0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8;
       *   1 - Estrangeira - Importação direta, exceto a indicada no código 6;
       *   2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7;
       *   3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%;
       *   4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes;
       *   5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%;
       *   6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX e gás natural;
       *   7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e gás natural. 8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%;
       */
      $std->orig = $itemNfe["orig"];
      /*
       * CST
       *   10=Tributada e com cobrança do ICMS por substituição tributária;
       *   90=Outros.
       */
      $std->CST = $itemNfe["CST"];
      /*
       * modBC
       *    0=Margem Valor Agregado (%);
       *    1=Pauta (Valor);
       *    2=Preço Tabelado Máx. (valor);
       *    3=Valor da operação.
       */
      $std->modBC = $itemNfe["modBC"];
      $std->vBC = $itemNfe["vBC"];
      $std->pRedBC = $itemNfe["pRedBC"]; // Percentual de redução da BC
      $std->pICMS = $itemNfe["pICMS"]; // Aliquota ICMS
      $std->vICMS = $itemNfe["vICMS"]; // Valor ICMS
      /*
       * modBCST - Modalidade de determinacao da BC do ICMS ST
       *    0=Preço tabelado ou máximo sugerido;
       *    1=Lista Negativa (valor);
       *    2=Lista Positiva (valor);
       *    3=Lista Neutra (valor);
       *    4=Margem Valor Agregado (%);
       *    5=Pauta (valor);
       */
      $std->modBCST = $itemNfe["modBCST"];
      $std->pMVAST = $itemNfe["pMVAST"]; // Percentual de margem de valor adicional do ICMS ST
      $std->pRedBCST = $itemNfe["pRedBCST"]; // Percentual de redução da BC do ICMS ST
      $std->vBCST = $itemNfe["pRedBCST"]; // Valor da BC do ICMS ST
      $std->pICMSST = $itemNfe["pICMSST"]; // Aliquota do importo do ICMS ST
      $std->vICMSST = $itemNfe["vICMSST"]; // Valor do ICMS ST
      $std->pBCOp = $itemNfe["pBCOp"];  // Percentual da BC operacao propria
      $std->UFST = $itemNfe["UFST"]; // UF para qual é devido o ICMS ST

      $elem = $nfe->tagICMSPart($std);
    }

    if ($itemNfe["usaNfICMSRetido"]["RetemICMS"] == true) {
      $std = new stdClass();
      $std->item = $itemNfe["item"]; //item da NFe
      /*
       * orig
       *   0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8;
       *   1 - Estrangeira - Importação direta, exceto a indicada no código 6;
       *   2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7;
       *   3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%;
       *   4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes;
       *   5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%;
       *   6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX e gás natural;
       *   7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e gás natural. 8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%;
       */
      $std->orig = $itemNfe["orig"];
      $std->CST = $itemNfe["CST"];
      $std->vBCSTRet = $itemNfe["vBCSTRet"]; // valor BC do ICMS ST retido na UF remetente
      $std->vICMSSTRet = $itemNfe["vICMSSTRet"]; // valor do ICMS ST retido na UF do rementete
      $std->vBCSTDest = $itemNfe["vBCSTDest"]; // Valor da BC do ICMS ST UF destino
      $std->vICMSSTDest = $itemNfe["vICMSSTDest"]; // Valor do ICMS ST da UF destino

      $elem = $nfe->tagICMSST($std);
    }

    if ($itemNfe["usaNfTributacaoSN"]["TributaSN"] == true) {
      $std = new stdClass();
      $std->item = $itemNfe["item"]; //item da NFe
      $std->orig = $itemNfe["orig"];
      $std->CSOSN = $itemNfe["CSOSN"]; //  102 103 300 400
      $std->pCredSN = $itemNfe["pCredSN"]; // ALiquota aplicavel de calculo de credito -- contador
      $std->vCredICMSSN = $itemNfe["vCredICMSSN"]; // Valor calculo de credito que pode ser aproveitado -- contador
      /*
       * modBCST - Modalidade de determinacao da BC do ICMS ST
       *    0=Preço tabelado ou máximo sugerido;
       *    1=Lista Negativa (valor);
       *    2=Lista Positiva (valor);
       *    3=Lista Neutra (valor);
       *    4=Margem Valor Agregado (%);
       *    5=Pauta (valor);
       */
      $std->modBCST = $itemNfe["modBCST"];
      $std->pMVAST = $itemNfe["pMVAST"]; // Percentual de margem de valor adicional do ICMS ST
      $std->pRedBCST = $itemNfe["pRedBCST"]; // Percentual de redução da BC do ICMS ST
      $std->vBCST = $itemNfe["vBCST"]; // Valor BC do ICMS ST
      $std->pICMSST = $itemNfe["pICMSST"]; // Aliquota do imposto do ICMS ST
      $std->vBCSTRet = $itemNfe["vBCSTRet"];
      $std->pST = $itemNfe["pST"];
      $std->vICMSSTRet = $itemNfe["vICMSSTRet"];
      $std->modBC = $itemNfe["modBC"];
      $std->vBC = $itemNfe["vBC"];
      $std->pRedBC = $itemNfe["pRedBC"];
      $std->pICMS = $itemNfe["pICMS"];
      $std->vICMS = $itemNfe["vICMS"];
      if ($nfeLayout == "4.00") {
          $std->vBCFCPSTRet = $itemNfe["vBCFCPSTRet"]; //incluso no layout 4.00
          $std->pFCPSTRet = $itemNfe["pFCPSTRet"]; //incluso no layout 4.00
          $std->vFCPSTRet = $itemNfe["vFCPSTRet"]; //incluso no layout 4.00
          $std->vBCFCPST = $itemNfe["vBCFCPST"]; //incluso no layout 4.00 ???
          $std->pFCPST = $itemNfe["pFCPST"]; //incluso no layout 4.00 ???
          $std->vFCPST = $itemNfe["vFCPST"]; //incluso no layout 4.00 ???
      }

      $elem = $nfe->tagICMSSN($std); // ACHO Q N VAMOS USAR ESSA TAG.. POIS BEM, ACHO Q SIM
    }

    if ($itemNfe["usaNfICMSInterestadual"]["ICMSInter"] == true) {
      $std = new stdClass();
      $std->item = $itemNfe["item"]; //item da NFe
      $std->vBCUFDest = $itemNfe["vBCUFDest"]; // Valor da BC do ICMS na UF destino
      $std->vBCFCPUFDest = $itemNfe["vBCFCPUFDest"]; //
      $std->pFCPUFDest = $itemNfe["pFCPUFDest"]; // Percentual o ICMS relativo ao fundo de combate a pobreza
      $std->pICMSUFDest = $itemNfe["pICMSUFDest"]; // Aliquota do ICMS  do UF destino
      /*
       * pICMSInter
       *    Alíquota interestadual das UF envolvidas:
       *      - 4% alíquota interestadual para produtos importados;
       *      - 7% para os Estados de origem do Sul e Sudeste (exceto ES), destinado para os Estados do Norte, Nordeste, Centro-Oeste e Espírito Santo;
       *      - 12% para os demais casos.
       */
      $std->pICMSInter = $itemNfe["pICMSInter"]; // Aliquota interestadual das UFs envolvidas
      /*
       * pIMCSInterPart
       *    Percentual de ICMS Interestadual para a UF de destino:
       *      - 40% em 2016;
       *      - 60% em 2017;
       *      - 80% em 2018;
       *      - 100% a partir de 2019.
       */
      $std->pICMSInterPart = $itemNfe["pICMSInterPart"]; // Percentual provisório de partilhsa do ICMS interestadual
      $std->vFCPUFDest = $itemNfe["vFCPUFDest"]; // Valor do ICMS relativo ao fundo de combate a pobreza
      $std->vICMSUFDest = $itemNfe["vICMSUFDest"]; // Valor do ICMS interestadual para a UF destino
      $std->vICMSUFRemet = $itemNfe["vICMSUFRemet"]; // Valor do ICMS interestadual para a UF destino

      $elem = $nfe->tagICMSUFDest($std);
    }

    if ($itemNfe["usaNfIPI"]["IPI"] == true) {
      // TEMOS OPCAO PARA TAGS DE IPI
    }

    if ($itemNfe["usaNfII"]["ImpostoImportacao"] == true) {
      // TEMOS OPCAO PARA TAGS DE II (Imposto sobre Importação)
    }

    if ($itemNfe["usaNfPIS"]["PIS"] == true) {
      $std = new stdClass();
      $std->item = $itemNfe["item"]; // item da NFe
      /*
       * CST --- PISNT (Nao tributado)
       *    04=Operação Tributável (tributação monofásica (alíquota zero));
       *    05=Operação Tributável (Substituição Tributária);
       *    06=Operação Tributável (alíquota zero);
       *    07=Operação Isenta da Contribuição;
       *    08=Operação Sem Incidência da Contribuição;
       *    09=Operação com Suspensão da Contribuição;
       */
      $std->CST = $itemNfe["CST"]; // 07 PADRAO
      $std->vBC = $itemNfe["vBC"];
      $std->pPIS = $itemNfe["pPIS"]; // Percentual de PIS
      $std->vPIS = $itemNfe["vPIS"]; // Valor de PIS
      $std->qBCProd = $itemNfe["qBCProd"];
      $std->vAliqProd = $itemNfe["vAliqProd"];
      $elem = $nfe->tagPIS($std);
    }

    if ($itemNfe["usaNfPISST"]["pisST"] == true) {
      // TEMOS OPCAO PARA TAGS PISST
    }

    if ($itemNfe["usaNfTributacaoCOFINS"]["cofins"] == true) {
      $std = new stdClass();
      $std->item = $itemNfe["item"]; //item da NFe
      /*
       * CST --- PISNT (Nao tributado)
       *    04=Operação Tributável (tributação monofásica (alíquota zero));
       *    05=Operação Tributável (Substituição Tributária);
       *    06=Operação Tributável (alíquota zero);
       *    07=Operação Isenta da Contribuição;
       *    08=Operação Sem Incidência da Contribuição;
       *    09=Operação com Suspensão da Contribuição;
       */
      $std->CST = $itemNfe["CST"];
      $std->vBC = $itemNfe["vBC"];
      $std->pCOFINS = $itemNfe["pCOFINS"];
      $std->vCOFINS = $itemNfe["vCOFINS"];
      $std->qBCProd = $itemNfe["qBCProd"];
      $std->vAliqProd = $itemNfe["vAliqProd"];
      $elem = $nfe->tagCOFINS($std);
    }

    if ($itemNfe["usaNfCOFINSST"]["cofinsST"] == true) {
      // TEMOS OPCAO PARA TAGS COFINSST
    }

    if ($itemNfe["usaNfISSQN"]["ISSQN"] == true) {
      // TEMOS OPCAO PARA TAGS ISSQN
    }

    if ($itemNfe["usaNfDevolucao"]["Devolucao"] == true) {
      // TEMOS OPCAO PARA Imposto Devolvido
        $std = new stdClass();
        $std->item = $itemNfe["item"]; //item da NFe
        $std->pDevol = $itemNfe["pDevol"];
        $std->vIPIDevol = $itemNfe["vIPIDevol"];

        $elem = $nfe->tagimpostoDevol($std);
    }
}

$std = new stdClass();
$std->vBC = $dadosNfe["vBC"]; // Valor da base de caclulo do ICMS
$std->vICMS = $dadosNfe["vICMS"]; // Valor total do ICMS
$std->vICMSDesonv = $dadosNfe["vICMSDesonv"]; // Valor total do ICMS desonerado
$std->vBCST = $dadosNfe["vBCST"]; // BC do ICMS ST
$std->vST = $dadosNfe["vST"]; // Valor do ICMS ST
$std->vProd = $dadosNfe["vProd"]; // Valor total de produitos
$std->vFrete = $dadosNfe["vFrete"]; // Valor total de frete
$std->vSeg = $dadosNfe["vSeg"]; // Valor total de seguro
$std->vDesc = $dadosNfe["vDesc"]; // Total de desconto
$std->vII = $dadosNfe["vII"]; // Imposto sob importacao
$std->vIPI = $dadosNfe["vIPI"]; // IPI total
$std->vPIS = $dadosNfe["vPIS"]; // Total PIS
$std->vCOFINS = $dadosNfe["vCOFINS"]; // Total cofins
$std->vOutro = $dadosNfe["vOutro"]; // Total outros
$std->vNF = $dadosNfe["vNF"]; // Valor total da NF
$std->vTotTrib = $dadosNfe["vTotTrib"]; // Valor total tributos
if ($nfeLayout == "4.00") {
    $std->vFCP = $dadosNfe["vFCP"]; //incluso no layout 4.00
    $std->vFCPST = $dadosNfe["vFCPST"]; //incluso no layout 4.00
    $std->vFCPSTRet = $dadosNfe["vFCPSTRet"]; //incluso no layout 4.00
    $std->vIPIDevol = $dadosNfe["vIPIDevol"]; //incluso no layout 4.00
}

$elem = $nfe->tagICMSTot($std);

if ($dadosNfe["usaNfISSQN"]["ISSQN"] == true) {
  // TEMOS OPCAO PARA TAGS TOTAL DO ISSQN
}

if ($dadosNfe["usaNfRetTributos"]["RetTributos"] == true) {
  // TEMOS OPCAO PARA TAGS DE RETENCAO DE TRIBUTOS
}

$std = new stdClass();
/*
 * modFrete
 *   0=Por conta do emitente;
 *   1=Por conta do destinatário/remetente;
 *   2=Por conta de terceiros;
 *   9=Sem frete. (V2.0)
 */
$std->modFrete = $dadosNfe["modFrete"];
$elem = $nfe->tagtransp($std);

if ($dadosNfe["usaNfTransportadora"]["Transportadora"] == true) {
    $std = new stdClass(); // DADOS DA TRANSPORTADORA
    $std->xNome = $dadosNfe["usaNfTransportadora"]["xNome"];
    $std->IE = $dadosNfe["usaNfTransportadora"]["IE"];
    $std->xEnder = $dadosNfe["usaNfTransportadora"]["xEnder"];
    $std->xMun = $dadosNfe["usaNfTransportadora"]["xMun"];
    $std->UF = $dadosNfe["usaNfTransportadora"]["UF"];
    $std->CNPJ = $dadosNfe["usaNfTransportadora"]["CNPJ"];//só pode haver um ou CNPJ ou CPF, se um deles é especificado o outro deverá ser null
    $std->CPF = $dadosNfe["usaNfTransportadora"]["CPF"];

    $elem = $nfe->tagtransporta($std);
}

if ($dadosNfe["usaNfDetalheVeicTransportadora"]["Veiculo"] == true) {
  // TEMOS OPCAO PARA TAGS DE DESCRICAO DO VEICULO DE TRANSPORTE
}

if ($dadosNfe["usaNfReboqueVeicTransportadora"]["Reboque"] == true) {
  // TEMOS OPCAO PARA TAGS DE DESCRICAO DO REBOQUE DO VEICULO DE TRANSPORTE
}

if ($dadosNfe["usaNfTransportadora"]["usaNfVolumeTransportadora"]["Volume"] == true) {
    // $std = new stdClass();
    // $std->item = 1; //indicativo do numero do volume
    // $std->qVol = 2;
    // $std->esp = 'caixa';
    // $std->marca = 'OLX';
    // $std->nVol = '11111';
    // $std->pesoL = 10.50;
    // $std->pesoB = 11.00;
    //
    // $elem = $nfe->tagvol($std);
}

if ($dadosNfe["usaNfDadosFatura"]["Fatura"] == true) {
    $std = new stdClass();
    $std->nFat = $dadosNfe["usaNfDadosFatura"]["nFat"];
    $std->vOrig = $dadosNfe["usaNfDadosFatura"]["vOrig"];
    $std->vDesc = $dadosNfe["usaNfDadosFatura"]["vDesc"];
    $std->vLiq = $dadosNfe["usaNfDadosFatura"]["vLiq"];

    $elem = $nfe->tagfat($std);
}

if ($dadosNfe["usaNfDadosDuplicata"]["Duplicata"] == true) {
    $std = new stdClass();
    $std->nDup = $dadosNfe["usaNfDadosDuplicata"]["nDup"];
    $std->dVenc = $dadosNfe["usaNfDadosDuplicata"]["dVenc"];
    $std->vDup = $dadosNfe["usaNfDadosDuplicata"]["vDup"];

    $elem = $nfe->tagdup($std);
}

if ($dadosNfe["mod"] == 55) {
  $std = new stdClass();
  /*
   * tPag -- Forma de Pagamento
   *   01=Dinheiro
   *   02=Cheque
   *   03=Cartão de Crédito
   *   04=Cartão de Débito
   *   05=Crédito Loja
   *   10=Vale Alimentação
   *   11=Vale Refeição
   *   12=Vale Presente
   *   13=Vale Combustível
   *   99=Outros
   */
  $std->tPag = $dadosNfe["tPag"];
  $std->vPag = $dadosNfe["vPag"]; //Obs: deve ser o informado o valor total da Nota Fiscal (vPag = vNF), caso contrário a a SEFAZ irá retornar "Rejeição 767"
  if ($dadosNfe["usaNfeCartaoCredito"]["Cartao"] == true) {
    $std->CNPJ = $dadosNfe["usaNfeCartaoCredito"]["CNPJ"]; // Informar CNPJ da credenciadora do cartao de credito se tiver
    /*
     * tBand -- Bandeira da operadora de cartao de credito ou débito
     *    01-Visa
     *    02=Mastercard
     *    03=American Express
     *    04=Sorocred
     *    99=Outros
     */
    $std->tBand = $dadosNfe["usaNfeCartaoCredito"]["tBand"];
    $std->cAut = $dadosNfe["usaNfeCartaoCredito"]["cAut"]; // Identifiva o numero da autorizacao da transacao da operacao com cartao
  }
  /*
   * tpIntegra -- Tipo de integracao para pagamento
   *      Tipo de Integração do processo de pagamento com o sistema de automação da empresa:
   *         1=Pagamento integrado com o sistema de automação da empresa (Ex.: equipamento TEF, Comércio Eletrônico);
   *         2= Pagamento não integrado com o sistema de automação da empresa (Ex.: equipamento POS);
   */
  $std->tpIntegra = $dadosNfe["tpIntegra"]; // Incluso na NT 2015/002

  if ($nfeLayout == "4.00") {
      $std->vTroco = $dadosNfe["vTroco"]; //incluso no layout 4.00
  }

  $elem = $nfe->tagpag($std);
}

if ($nfeLayout == "4.00") {
    $std = new stdClass();
    /*
     * tPag -- Forma de Pagamento
     *   01=Dinheiro
     *   02=Cheque
     *   03=Cartão de Crédito
     *   04=Cartão de Débito
     *   05=Crédito Loja
     *   10=Vale Alimentação
     *   11=Vale Refeição
     *   12=Vale Presente
     *   13=Vale Combustível
     *   99=Outros
     */
    $std->tPag = $dadosNfe["tPag"];
    $std->vPag = $dadosNfe["vPag"]; //Obs: deve ser informado o valor pago pelo cliente

    if ($dadosNfe["usaNfeCartaoCredito"]["Cartao"] == true) {
      $std->CNPJ = $dadosNfe["usaNfeCartaoCredito"]["CNPJ"];
      $std->tBand = $dadosNfe["usaNfeCartaoCredito"]["tBand"];
      $std->cAut = $dadosNfe["usaNfeCartaoCredito"]["cAut"];
    }

    $std->tpIntegra = $dadosNfe["tpIntegra"]; //incluso na NT 2015/002

    $elem = $nfe->tagdetPag($std);
}


$std = new stdClass();
$std->infAdFisco = $dadosNfe["infAdFisco"];
$std->infCpl = $dadosNfe["infCpl"];

$elem = $nfe->taginfAdic($std);

if ($dadosNfe["usaNfExportacao"]["Exportacao"] == true) {
    // $std = new stdClass();
    // $std->UFSaidaPais = 'PR';
    // $std->xLocExporta = 'Paranagua';
    // $std->xLocDespacho = 'Informação do Recinto Alfandegado';
    //
    // $elem = $nfe->tagexporta($std);
}

$result = $nfe->montaNFe();
$xml = $nfe->getXML();
$chave = $nfe->getChave();
$modelo = $nfe->getModelo();

try {
    $response = $tools->signNFe($xml);

    header('Content-type: text/xml');
    if ($dadosNfe["mod"] == 65)
      $arq = 'NFCe'.$dadosNfe["nNF"];
    else
      $arq = 'NFe'.$dadosNfe["nNF"];

      $dom = new DomDocument('1.0', 'UTF-8');
      $dom->loadXML($xml);
      $dom->saveXML();
      $dom->save('xmls/'.$arq.'.xml');
} catch (\Exception $e) {
    echo $e->getMessage();
}

// $enviaEmail = true;
// if ($enviaEmail) {
//
// }

// if ($dadosNfe["mod"] == 65) { // DANFE
//   $docxml = FilesFolders::readFile('/xmls/'.$arq.'.xml');
//   try {
//      $danfe = new Danfe($docxml, 'P', 'A4', 'images/logo.jpg', 'S', '');
//      $id = $danfe->montaDANFE();
//      $pdf = $danfe->render();
//      header('Content-Disposition: attachment; filename="'.$arq.'.pdf"');
//      echo $response;
//      header('Content-type: application/pdf');
//      echo $pdf;
//   } catch (InvalidArgumentException $e) {
//      echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
//   }
// }
// else { //DANFCE
//   try {
//     $docxml = file_get_contents(realpath(__DIR__."/xmls/".$arq.".xml"));
//     $pathLogo = realpath(__DIR__.'/../../include/logo.png');//use somente imagens JPEG
//     $danfce = new Danfce($docxml, $pathLogo, 0);
//     $id = $danfce->monta();
//     $pdf = $danfce->render();
//     header('Content-Type: application/pdf');
//     echo $pdf;
//   } catch (InvalidArgumentException $e) {
//      echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
//   }
// }
}