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
    "cDV" => "",
    "id" => "NFe35150271780456000160550010000000021800700082",
    "mod" => "55",
    "cNF" => "12346789",
    "cUF" => "43",
    "natOp" => "VENDA",
    "indPag" => "0",
    "serie" => "1",
    "nNF" => "123",
    "dhEmi" => "2015-02-19T13:48:00-02:00",
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
    "IE" => "",
    "IEST" => null,
    "IM" => "",
    "CNAE" => "",
    "CRT" => "",
    "xLgr" => "",
    "nro" => "",
    "xCpl" => "",
    "xBairro" => "",
    "cMun" => "",
    "xMun" => "",
    "UF" => "",
    "CEP" => "",
    "cPais" => "",
    "xPais" => "",
    "fone" => "",
    "vBC" => "",
    "vICMS" => "",
    "vICMSDesonv" => "",
    "vBCST" => "",
    "vST" => "",
    "vProd" => "",
    "vFrete" => "",
    "vSeg" => "",
    "vDesc" => "",
    "vII" => "",
    "vIPI" => "",
    "vPIS" => "",
    "vCOFINS" => "",
    "vOutro" => "",
    "vNF" => "",
    "vTotTrib" => "",
    "vFCP" => "",
    "vFCPST" => "",
    "vFCPSTRet" => "",
    "vIPIDevol" => "",
    "modFrete" => 1,
    "tPag" => "01",
    "vPag" => 200.00,
    "tpIntegra" => 2,
    "vTroco" => "",
    "" => "",
    "" => "",
    "" => "",
    "" => "",
    "" => "",
    "" => "",
    "" => "",
    "doctoNfe" => [
      "doc" => "CNPJ",
      "CNPJ" => "21996226000164",
      "CPF" => null,
    ],
    "usaNfeCartaoCredito" => [
      "Cartao" => "false",
      "CNPJ" => "",
      "tBand" => "",
      "cAut" => "",
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
      "nro" => "585",
      "xCpl" => null,
      "xBairro" => "Três Vendas",
      "cMun" => "4754",
      "xMun" => "Erechim",
      "UF" => "RS",
      "CEP" => "99713-220",
      "cPais" => "1058",
      "xPais" => "BRASIL",
      "fone" => "(54) 9 9622 7898",
      "doctoNfe" => [
        "doc" => "CPF",
        "CNPJ" => "",
        "CPF" => "01852675055",
      ],
      "endEntrega" => [
        "xLgr" => "",
        "nro" => "",
        "xCpl" => "",
        "xBairro" => "",
        "cMun" => "",
        "xMun" => "",
        "UF" => "",
        "doctoNfe" => [
          "doc" => "CPF",
          "CNPJ" => "",
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
      "contingencia" => "false",
      "dhCont" => "2015-02-19T13:48:00-02:00",
      "xJust" => "Justificativa da contingencuia",
    ],
    "usaNfReferenciada" => [
      "referenciada" => "false",
      "refNFe" => "35150271780456000160550010000253101000253101",
    ],
    "usaNfISSQN" => [
      "ISSQN" => "false",
    ],
    "usaNfRetTributos" => [
      "RetTributos" => "false",
    ],
    "usaNfTransportadora" => [
      "Transportadora" => "false",
      "xNome" => "",
      "IE" => "",
      "xEnder" => "",
      "xMun" => "",
      "UF" => "",
      "CNPJ" => "",
      "CPF" => "",
      "usaNfVolumeTransportadora" => [
        "Volume" => "false",
        1 => [
          "item" => "1",
          "qVol" => "2",
          "esp" => "caixa",
          "marca" => "",
          "nVol" => "",
          "pesoL" => "",
          "pesoB" => "",
        ],
      ],
    ],
    "usaNfDetalheVeicTransportadora" => [
      "Veiculo" => "false",
    ],
    "usaNfReboqueVeicTransportadora" => [
      "Reboque" => "false",
    ],
    "usaNfDadosFatura" => [
      "Fatura" => "false",
      "nFat" => "",
      "vOrig" => "",
      "vDesc" => "",
      "vLiq" => "",
    ],
    "usaNfDadosDuplicata" => [
      "Duplicata" => "false",
      "nDup" => "",
      "dVenc" => "",
      "vDup" => "",
    ],
    "usaNfExportacao" => [
      "Exportacao" => "false",
    ],
    "infAdFisco" => "",
    "infCpl" => "",
    "qtdItens" => "1",
];

$dadosNfeItens = [
  1 => [
    "item" => "1",
    "cProd" => "",
    "cEAN" => "",
    "xProd" => "",
    "NCM" => "",
    "EXTIPI" => "",
    "CFOP" => "",
    "uCom" => "",
    "qCom" => "",
    "vUnCom" => "",
    "vProd" => "",
    "cEANTrib" => "",
    "uTrib" => "",
    "qTrib" => "",
    "vUnTrib" => "",
    "vFrete" => "",
    "vSeg" => "",
    "vDesc" => "",
    "vOutro" => "",
    "indTot" => "1",
    "xPed" => "",
    "nItemPed" => "",
    "nFCI" => "",
    "infAdProd" => "Informações adicionais do produto.",
    "CEST" => null,
    "indEscala" => null,
    "CNPJFab" => null,
    "nLote" => "",
    "qLote" => "",
    "dFab" => "",
    "dVal" => "",
    "cAgreg" => "",
    "vTotTrib" => 0.00,
    "orig" => "",
    "CST" => "",
    "modBC" => "",
    "vBC" => "",
    "pICMS" => "",
    "vICMS" => "",
    "pFCP" => "",
    "vFCP" => "",
    "vBCFCP" => "",
    "modBCST" => "",
    "pMVAST" => "",
    "pRedBCST" => "",
    "vBCST" => "",
    "pICMSST" => "",
    "vICMSST" => "",
    "vBCFCPST" => "",
    "pFCPST" => "",
    "vFCPST" => "",
    "vICMSDeson" => "",
    "motDesICMS" => "",
    "pRedBC" => "",
    "vICMSOp" => "",
    "pDif" => "",
    "vICMSDif" => "",
    "vBCSTRet" => "",
    "pST" => "",
    "vICMSSTRet" => "",
    "vBCFCPSTRet" => "",
    "pFCPSTRet" => "",
    "vFCPSTRet" => "",
    "pBCOp" => "",
    "UFST" => "",
    "vBCSTDest" => "",
    "vICMSSTDest" => "",
    "CSOSN" => "",
    "pCredSN" => "",
    "vCredICMSSN" => "",
    "vBCUFDest" => "",
    "vBCFCPUFDest" => "",
    "pFCPUFDest" => "",
    "pICMSUFDest" => "",
    "pICMSInter" => "",
    "pICMSInterPart" => "",
    "vFCPUFDest" => "",
    "vICMSUFDest" => "",
    "vICMSUFRemet" => "",
    "pPIS" => "",
    "vPIS" => "",
    "qBCProd" => "",
    "vAliqProd" => "",
    "pCOFINS" => "",
    "vCOFINS" => "",
    "qBCProd" => "",
    "vAliqProd" => "",
    "pDevol" => "",
    "vIPIDevol" => "",
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
      "TributaSN" => false,
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
      "nLote" => "",
      "qLote" => "",
      "dFab" => "",
      "dVal" => "",
      "vPMC" => "",
      "cProdANVISA" => "",
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

$content = file_get_contents('1000364088.pfx');
$tools = new Tools($configJson, Certificate::readPfx($content, 'nanda1706'));
$tools->model('55');
$nfeLayout = $arr["versao"];

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
if ($dadosNfe["doctoNfe"]["doc"] == "CNPJ") {
    $std->CNPJ = $dadosNfe["doctoNfe"]["CNPJ"]; // Indicar apenas um CNPJ ou CPF
}
else {
    $std->CPF = $dadosNfe["doctoNfe"]["CPF"];
}

$elem = $nfe->tagemit($std);


//enrtrada dos pais
//padrinhos
//minha
//livia e tomas
//nanda
//saida
// Texto antes de cada musica para Lucas


// MAKE 5 - pagina 191 do manual NFe
$std = new stdClass();
$std->xLgr; // Logradouro
$std->nro; // Numero
$std->xCpl; // Complemento
$std->xBairro; // Bairro
$std->cMun; // Cod_IBGE do municipio emitente
$std->xMun; // Nome do municipio
$std->UF;  // Sigla do estado
$std->CEP;
$std->cPais; // Codigo do pais 1058 para Brasil
$std->xPais; // Nome do pais Brasil ou BRASIL
$std->fone; // DDD + numero... para venda exterior adicionar codigo do pais

$elem = $nfe->tagenderEmit($std);






// MAKE 6 - pagina 192 do manual NFe
$std = new stdClass();
$std->xNome;
/*
 * indIEDest
 *    1=Contribuinte ICMS (informar a IE do destinatário);
 *    2=Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS
 *    9=Não Contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes do ICMS.
 *    ***Nota 1: No caso de NFC-e informar indIEDest=9 e não informar a tag IE do destinatário;
 *    Nota 2: No caso de operação com o Exterior informar indIEDest=9 e não informar a tag IE do destinatário;
 *    Nota 3: No caso de Contribuinte Isento de Inscrição (indIEDest=2), não informar a tag IE do destinatário.
 */
$std->indIEDest = 9;
$std->IE = null; // Opcional = IE do destinatario nao informar em NFCe
$std->ISUF = null; // Só é obrigatorio para incentivados pelo SUFRAMA (MANAUS)
$std->IM = null; // opcional
$std->email;
$std->CNPJ; //indicar apenas um CNPJ ou CPF ou idEstrangeiro
$std->CPF;
$std->idEstrangeiro; // numero do passaporte ou documento legal -- pode ser null

$elem = $nfe->tagdest($std);










// MAKE 7 - pagina 193 do manual NFe -- obrigatorio para Mod55 mas nao para Mod65
$std = new stdClass();
$std->xLgr;
$std->nro;
$std->xCpl;
$std->xBairro;
$std->cMun; // Cod_IBGE do municipio do comprador
$std->xMun; // Nome do municipio
$std->UF; // Sigla
$std->CEP;
$std->cPais;  // Codigo do pais 1058 para Brasil
$std->xPais; // Nome pais Brasil ou BRASIL
$std->fone;

$elem = $nfe->tagenderDest($std);








// MAKE 8 - pagina 194 do manual NFe -- Obrigatorio qdo a entrega do produto for diferente doendereco do emitente (por exemplo, venda a pronta entrega - a venda esta sendo feita em passo fundo mas a empresa do vendedor esta em erehim)
$std = new stdClass();
$std->xLgr;
$std->nro;
$std->xCpl;
$std->xBairro;
$std->cMun;
$std->xMun;
$std->UF;
$std->CNPJ; //indicar apenas um CNPJ ou CPF
$std->CPF = null;

$elem = $nfe->tagretirada($std);





// MAKE 9 - pagina 194 do manual NFe (exemplo, compra da TV com o Teti.. o cadastro dele tem endereco em Santa Maria mas mandou entregar em Erechim)
$std = new stdClass();
$std->xLgr;
$std->nro;
$std->xCpl;
$std->xBairro;
$std->cMun;
$std->xMun;
$std->UF;
$std->CNPJ; //indicar um CNPJ ou CPF
$std->CPF = null;

$elem = $nfe->tagentrega($std);





// MAKE 10 - pagina 195 do manual NFe
$std = new stdClass();
$std->CNPJ = '12345678901234'; //indicar um CNPJ ou CPF
$std->CPF = null;
$elem = $nfe->tagautXML($std);






// MAKE 11 - pagina 196 do manual NFe
$std = new stdClass();
$std->item = 1; //item da NFe (Sequencial 1-n)
$std->cProd; // Código do produto no sistema (SGAF ou eVET)
$std->cEAN; // Código de Barras
$std->xProd; // Descricao do produto
$std->NCM; // Nomenclaru comum do mercosul

if ($nfeLayout == "4.00") {
    $std->cBenf; //***incluido no layout 4.00
}

$std->EXTIPI; // Contador diz o uso
$std->CFOP;
$std->uCom; // Unidade
$std->qCom; // Quantidade
$std->vUnCom; // Valor unitario
$std->vProd; // Valor total bruto do produto
$std->cEANTrib; // Codigo barras da unidade tributavel
$std->uTrib; // Unidade
$std->qTrib; // Quantidade
$std->vUnTrib; // Valor tributada
$std->vFrete; // Valor frete
$std->vSeg; // Valor do seguro
$std->vDesc; // Valor desconto
$std->vOutro; // Outras despesas
$std->indTot = 1; // Indica se o valor total compoe ou nao na NFe // 0: Valor do Item não compoe o valor total da NFe / 1: Valor do item compoes o valor total da NFe
$std->xPed; // opcional numero do pedido
$std->nItemPed; // opcional item do pedido
$std->nFCI; // Para importacao

$elem = $nfe->tagprod($std);




// MAKE 12 - pagina 196 do manual NFe
$std = new stdClass();
$std->item = 1; //item da NFe

$std->infAdProd = 'Informacao adicional do item';

$elem = $nfe->taginfAdProd($std);






// LAYOUT 4.00
if ($nfeLayout == "4.00") {
    $std = new stdClass();
    $std->item = 1; //item da NFe
    $std->CEST;
    $std->indEscala; //incluido no layout 4.00
    $std->CNPJFab; //incluido no layout 4.00

    $elem = $nfe->tagCEST($std);
}



if ($usaNfRecopi == true) {
  // TEMOS OPCAO PARA GERAR TAG PARA RECOPI
}

if ($usaNfDI == true) {
  // TEMOS OPCAO PARA GERAR TAG DE Declaracao de Importacao (tagDI)
}

if ($usaNfExportacao == true) {
  // TEMOS OPCAO PARA GERAR TAG DE EXPORTACAO
}







// MAKE 13 --> Ver no layout 4
if ($nfeLayout == "4.00") {
    $std = new stdClass();
    $std->item = 1; //item da NFe
    $std->nLote;
    $std->qLote;
    $std->dFab;
    $std->dVal;
    $std->cAgreg;

    $elem = $nfe->tagRastro($std);
}



if ($usaNfVeiculo == true) {
  // TEMOS OPCAO PARA TAGS DE INDUSTRIALIZACAO DE VEICULOS
}





// VER VERSAO 4
if (($nfeLayout == "3.10") && ($usaNfMedicamentos == true)) {
    $std = new stdClass();
    $std->item = 1; //item da NFe

    $std->nLote; //removido no layout 4.00
    $std->qLote; //removido no layout 4.00
    $std->dFab; //removido no layout 4.00
    $std->dVal; //removido no layout 4.00

    $std->vPMC;

    $std->cProdANVISA; //incluido no layout 4.00

    $elem = $nfe->tagmed($std);
}



if ($usaNfArmamento == true) {
  // TEMOS OPCAO PARA TAGS DE COMERCIALIZACAO DE ARMAMENTO
}

if ($usaNfCombustivel == true) {
  // TEMOS OPCAO PARA TAGS DE COMERCIALIZACAO DE COMBUSTIVEL
}






// MAKE 14 --> Pagina 203
$std = new stdClass();
$std->item = 1; //item da NFe
$std->vTotTrib = 0.00; // Por Item -- Valor total de tributos

$elem = $nfe->tagimposto($std);








// MAKE 15 --> Pagina 203
$std = new stdClass();
$std->item = 1; //item da NFe

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
$std->orig; // Origem da mercadoria
$std->CST; //CST 00 / 10 / 20 / 30 / 40 / 41 / 50 / 51 / 60 / 70 / 90
/*
 * modBC
 *    0=Margem Valor Agregado (%);
 *    1=Pauta (Valor);
 *    2=Preço Tabelado Máx. (valor);
 *    3=Valor da operação.
 */
$std->modBC; // Modalidade da base de calculo
$std->vBC; // Valor da base de calculo do ICMS
$std->pICMS; // Aliquota do ICMS
$std->vICMS; // Valor do ICMS
$std->pFCP; // ??????
$std->vFCP;  // ?????
$std->vBCFCP; // ?????
/*
 * modBCST - Modalidade de determinacao da BC do ICMS ST
 *    0=Preço tabelado ou máximo sugerido;
 *    1=Lista Negativa (valor);
 *    2=Lista Positiva (valor);
 *    3=Lista Neutra (valor);
 *    4=Margem Valor Agregado (%);
 *    5=Pauta (valor);
 */
$std->modBCST;
$std->pMVAST; // Percentual da margem do Valor Adicionar do ICMS ST
$std->pRedBCST; // Percentual de redução da BC do ICMS ST
$std->vBCST; // Valor da BC do ICMS ST
$std->pICMSST; // Aliquota do importo do ICMS ST
$std->vICMSST; // Valor do ICMS ST
$std->vBCFCPST; // ???????
$std->pFCPST; // ?????
$std->vFCPST; // ???????
$std->vICMSDeson = null; // Informar nos motivos de desoneracao informadas no proximo campo
/*
 * motDesICMS - Motivo de desoneração do ICMS
 *    Campo será preenchido quando o campo anterior estiver preenchido.
 *    Informar o motivo da desoneração:
 *      3=Uso na agropecuária;
 *      9=Outros;
 *      12=Órgão de fomento e desenvolvimento agropecuário.
 */
$std->motDesICMS;
$std->pRedBC; // Percentual da redução da BC
$std->vICMSOp; // Valor como se nao tivesse o diferimento
$std->pDif; // Percentual do diferimento - se houver diferimento total infornar 100
$std->vICMSDif; // Valor do ICMS diferido
$std->vBCSTRet; // Valor da BC do ICMS ST retido
$std->pST;  // ????????
$std->vICMSSTRet; // Valor do ICMS ST retido
$std->vBCFCPSTRet; //?????????
$std->pFCPSTRet; //?????????
$std->vFCPSTRet; //?????????

$elem = $nfe->tagICMS($std);







// MAKE 16 --> Pagina 214
$std = new stdClass();
$std->item = 1; //item da NFe
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
$std->orig = 0;
/*
 * CST
 *   10=Tributada e com cobrança do ICMS por substituição tributária;
 *   90=Outros.
 */
$std->CST = '90';
/*
 * modBC
 *    0=Margem Valor Agregado (%);
 *    1=Pauta (Valor);
 *    2=Preço Tabelado Máx. (valor);
 *    3=Valor da operação.
 */
$std->modBC = 0;
$std->vBC = 1000.00;
$std->pRedBC = null; // Percentual de redução da BC
$std->pICMS = 18.00; // Aliquota ICMS
$std->vICMS = 180.00; // Valor ICMS
/*
 * modBCST - Modalidade de determinacao da BC do ICMS ST
 *    0=Preço tabelado ou máximo sugerido;
 *    1=Lista Negativa (valor);
 *    2=Lista Positiva (valor);
 *    3=Lista Neutra (valor);
 *    4=Margem Valor Agregado (%);
 *    5=Pauta (valor);
 */
$std->modBCST = 1000.00;
$std->pMVAST = 40.00; // Percentual de margem de valor adicional do ICMS ST
$std->pRedBCST = null; // Percentual de redução da BC do ICMS ST
$std->vBCST = 1400.00; // Valor da BC do ICMS ST
$std->pICMSST = 10.00; // Aliquota do importo do ICMS ST
$std->vICMSST = 140.00; // Valor do ICMS ST
$std->pBCOp = 10.00;  // Percentual da BC operacao propria
$std->UFST = 'RJ'; // UF para qual é devido o ICMS ST

$elem = $nfe->tagICMSPart($std);






// MAKE 17 --> Pagina 216
$std = new stdClass();
$std->item = 1; //item da NFe
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
$std->orig = 0;
$std->CST = '41'; // CST 41 apenas - Nao tributado
$std->vBCSTRet = 1000.00; // valor BC do ICMS ST retido na UF remetente
$std->vICMSSTRet = 190.00; // valor do ICMS ST retido na UF do rementete
$std->vBCSTDest = 1000.00; // Valor da BC do ICMS ST UF destino
$std->vICMSSTDest = 1.00; // Valor do ICMS ST da UF destino

$elem = $nfe->tagICMSST($std);








// MAKE 18 --> Pagina 216
$std = new stdClass();
$std->item = 1; //item da NFe
$std->orig = 0;
$std->CSOSN = '101'; //  102 103 300 400
$std->pCredSN = 2.00; // ALiquota aplicavel de calculo de credito -- contador
$std->vCredICMSSN = 20.00; // Valor calculo de credito que pode ser aproveitado -- contador
/*
 * modBCST - Modalidade de determinacao da BC do ICMS ST
 *    0=Preço tabelado ou máximo sugerido;
 *    1=Lista Negativa (valor);
 *    2=Lista Positiva (valor);
 *    3=Lista Neutra (valor);
 *    4=Margem Valor Agregado (%);
 *    5=Pauta (valor);
 */
$std->modBCST = null;
$std->pMVAST = null; // Percentual de margem de valor adicional do ICMS ST
$std->pRedBCST = null; // Percentual de redução da BC do ICMS ST
$std->vBCST = null; // Valor BC do ICMS ST
$std->pICMSST = null; // Aliquota do imposto do ICMS ST
$std->vICMSST = null; // Valor do ICMS ST
if ($nfeLayout == "4.00") {
    $std->vBCFCPST = null; //incluso no layout 4.00 ???
    $std->pFCPST = null; //incluso no layout 4.00 ???
    $std->vFCPST = null; //incluso no layout 4.00 ???
}
$std->pCredSN = null; // Aliquota aplicavel de calculo de credito
$std->vCredICMSSN = null;
$std->pCredSN = null;
$std->vCredICMSSN = null;
$std->vBCSTRet = null;
$std->pST = null;
$std->vICMSSTRet = null;
if ($nfeLayout == "4.00") {
    $std->vBCFCPSTRet = null; //incluso no layout 4.00
    $std->pFCPSTRet = null; //incluso no layout 4.00
    $std->vFCPSTRet = null; //incluso no layout 4.00
}
$std->modBC = null;
$std->vBC = null;
$std->pRedBC = null;
$std->pICMS = null;
$std->vICMS = null;

$elem = $nfe->tagICMSSN($std); // ACHO Q N VAMOS USAR ESSA TAG









// MAKE 19 --> Pagina 6 - NT 2015 03
$std = new stdClass();
$std->item = 1; //item da NFe
$std->vBCUFDest = 100.00; // Valor da BC do ICMS na UF destino
$std->vBCFCPUFDest = 100.00; //
$std->pFCPUFDest = 1.00; // Percentual o ICMS relativo ao fundo de combate a pobreza
$std->pICMSUFDest = 18.00; // Aliquota do ICMS  do UF destino
/*
 * pICMSInter
 *    Alíquota interestadual das UF envolvidas:
 *      - 4% alíquota interestadual para produtos importados;
 *      - 7% para os Estados de origem do Sul e Sudeste (exceto ES), destinado para os Estados do Norte, Nordeste, Centro-Oeste e Espírito Santo;
 *      - 12% para os demais casos.
 */
$std->pICMSInter = 12.00; // Aliquota interestadual das UFs envolvidas
/*
 * pIMCSInterPart
 *    Percentual de ICMS Interestadual para a UF de destino:
 *      - 40% em 2016;
 *      - 60% em 2017;
 *      - 80% em 2018;
 *      - 100% a partir de 2019.
 */
$std->pICMSInterPart = 80.00; // Percentual provisório de partilhsa do ICMS interestadual
$std->vFCPUFDest = 1.00; // Valor do ICMS relativo ao fundo de combate a pobreza
$std->vICMSUFDest = 14.44; // Valor do ICMS interestadual para a UF destino
$std->vICMSUFRemet = 3.56; // Valor do ICMS interestadual para a UF destino

$elem = $nfe->tagICMSUFDest($std);



if ($usaNfIPI == true) {
  // TEMOS OPCAO PARA TAGS DE IPI
}

if ($usaNfII == true) {
  // TEMOS OPCAO PARA TAGS DE II (Imposto sobre Importação)
}




$std = new stdClass();
$std->item = 1; // item da NFe
/*
 * CST --- PISNT (Nao tributado)
 *    04=Operação Tributável (tributação monofásica (alíquota zero));
 *    05=Operação Tributável (Substituição Tributária);
 *    06=Operação Tributável (alíquota zero);
 *    07=Operação Isenta da Contribuição;
 *    08=Operação Sem Incidência da Contribuição;
 *    09=Operação com Suspensão da Contribuição;
 */
$std->CST = '07'; // 07 PADRAO
if ($usaNfTributacaoPIS == true) {
    $std->vBC = null;
    $std->pPIS = null; // Percentual de PIS
    $std->vPIS = null; // Valor de PIS
    $std->qBCProd = null;
    $std->vAliqProd = null;
}
$elem = $nfe->tagPIS($std);


if ($usaNfPISST == true) {
  // TEMOS OPCAO PARA TAGS PISST
}




$std = new stdClass();
$std->item = 1; //item da NFe
/*
 * CST --- PISNT (Nao tributado)
 *    04=Operação Tributável (tributação monofásica (alíquota zero));
 *    05=Operação Tributável (Substituição Tributária);
 *    06=Operação Tributável (alíquota zero);
 *    07=Operação Isenta da Contribuição;
 *    08=Operação Sem Incidência da Contribuição;
 *    09=Operação com Suspensão da Contribuição;
 */
$std->CST = '07';
if ($usaNfTributacaoCOFINS == true) {
    $std->vBC = null;
    $std->pCOFINS = null;
    $std->vCOFINS = null;
    $std->qBCProd = null;
    $std->vAliqProd = null;
}
$elem = $nfe->tagCOFINS($std);


if ($usaNfCOFINSST == true) {
  // TEMOS OPCAO PARA TAGS COFINSST
}

if ($usaNfISSQN == true) {
  // TEMOS OPCAO PARA TAGS ISSQN
}

if ($usaNfDevolucao == true) {
  // TEMOS OPCAO PARA Imposto Devolvido
    $std = new stdClass();
    $std->item = 1; //item da NFe
    $std->pDevol = 2.00;
    $std->vIPIDevol = 123.36;

    $elem = $nfe->tagimpostoDevol($std);
}



$std = new stdClass();
$std->vBC = 1000.00; // Valor da base de caclulo do ICMS
$std->vICMS = 1000.00; // Valor total do ICMS
$std->vICMSDesonv = 1000.00; // Valor total do ICMS desonerado
if ($nfeLayout == "4.00") {
    $std->vFCP = 1000.00; //incluso no layout 4.00
    $std->vFCPST = 1000.00; //incluso no layout 4.00
    $std->vFCPSTRet = 1000.00; //incluso no layout 4.00
    $std->vIPIDevol = 1000.00; //incluso no layout 4.00
}
$std->vBCST = 1000.00; // BC do ICMS ST
$std->vST = 1000.00; // Valor do ICMS ST
$std->vProd = 1000.00; // Valor total de produitos
$std->vFrete = 1000.00; // Valor total de frete
$std->vSeg = 1000.00; // Valor total de seguro
$std->vDesc = 1000.00; // Total de desconto
$std->vII = 1000.00; // Imposto sob importacao
$std->vIPI = 1000.00; // IPI total
$std->vPIS = 1000.00; // Total PIS
$std->vCOFINS = 1000.00; // Total cofins
$std->vOutro = 1000.00; // Total outros
$std->vNF = 1000.00; // Valor total da NF
$std->vTotTrib = 1000.00; // Valor total tributos

$elem = $nfe->tagICMSTot($std);



if ($usaNfISSQN == true) {
  // TEMOS OPCAO PARA TAGS TOTAL DO ISSQN
}


if ($usaNfRetTributos == true) {
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
$std->modFrete = 1;

$elem = $nfe->tagtransp($std);



if ($usaNfTransportadora == true) {
    $std = new stdClass(); // DADOS DA TRANSPORTADORA
    $std->xNome = 'Rodo Fulano';
    $std->IE = '12345678901';
    $std->xEnder = 'Rua Um, sem numero';
    $std->xMun = 'Cotia';
    $std->UF = 'SP';
    $std->CNPJ = '12345678901234';//só pode haver um ou CNPJ ou CPF, se um deles é especificado o outro deverá ser null
    $std->CPF = null;

    $elem = $nfe->tagtransporta($std);
}


if ($usaNfDetalheVeicTransportadora == true) {
  // TEMOS OPCAO PARA TAGS DE DESCRICAO DO VEICULO DE TRANSPORTE
}

if ($usaNfReboqueVeicTransportadora == true) {
  // TEMOS OPCAO PARA TAGS DE DESCRICAO DO REBOQUE DO VEICULO DE TRANSPORTE
}

if ($usaNfVolumeTransportadora == true) {
    $std = new stdClass();
    $std->item = 1; //indicativo do numero do volume
    $std->qVol = 2;
    $std->esp = 'caixa';
    $std->marca = 'OLX';
    $std->nVol = '11111';
    $std->pesoL = 10.50;
    $std->pesoB = 11.00;

    $elem = $nfe->tagvol($std);
}

if ($usaNfDadosFatura == true) {
    $std = new stdClass();
    $std->nFat = '1233';
    $std->vOrig = 1254.22;
    $std->vDesc = null;
    $std->vLiq = 1254.22;

    $elem = $nfe->tagfat($std);
}


if ($usaNfDadosDuplicata == true) {
    $std = new stdClass();
    $std->nDup = '1233-1';
    $std->dVenc = '2017-08-22';
    $std->vDup = 1254.22;

    $elem = $nfe->tagdup($std);
}

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
$std->tPag = '03';
$std->vPag = 200.00; //Obs: deve ser o informado o valor total da Nota Fiscal (vPag = vNF), caso contrário a a SEFAZ irá retornar "Rejeição 767"
$std->CNPJ = '12345678901234'; // Informar CNPJ da credenciadora do cartao de credito se tiver
/*
 * tBand -- Bandeira da operadora de cartao de credito ou débito
 *    01-Visa
 *    02=Mastercard
 *    03=American Express
 *    04=Sorocred
 *    99=Outros
 */
$std->tBand = '01';
$std->cAut = '3333333'; // Identifiva o numero da autorizacao da transacao da operacao com cartao

/*
 * tpIntegra -- Tipo de integracao para pagamento
 *      Tipo de Integração do processo de pagamento com o sistema de automação da empresa:
 *         1=Pagamento integrado com o sistema de automação da empresa (Ex.: equipamento TEF, Comércio Eletrônico);
 *         2= Pagamento não integrado com o sistema de automação da empresa (Ex.: equipamento POS);
 */
$std->tpIntegra = 2; // Incluso na NT 2015/002

if ($nfeLayout == "4.00") {
    $std->vTroco = null; //incluso no layout 4.00
}

$elem = $nfe->tagpag($std);





if ($nfeLayout == "4.00") {
    $std = new stdClass();
    $std->tPag = '03';
    $std->vPag = 200.00; //Obs: deve ser informado o valor pago pelo cliente
    $std->CNPJ = '12345678901234';
    $std->tBand = '01';
    $std->cAut = '3333333';

    $std->tpIntegra = 1; //incluso na NT 2015/002

    $elem = $nfe->tagdetPag($std);
}


$std = new stdClass();
$std->infAdFisco = 'informacoes para o fisco';
$std->infCpl = 'informacoes complementares';

$elem = $nfe->taginfAdic($std);


if ($usaNfExportacao == true) {
    $std = new stdClass();
    $std->UFSaidaPais = 'PR';
    $std->xLocExporta = 'Paranagua';
    $std->xLocDespacho = 'Informação do Recinto Alfandegado';

    $elem = $nfe->tagexporta($std);
}


if ($usaNfAquisicaoCana == true) {
  // TEMOS OPCAO PARA TAGS DE INFORMACOES PARA AQUISICAO DE CANA
}

$result = $nfe->montaNFe();
$xml = $nfe->getXML();
$chave = $nfe->geChave();
