<?php

print_r($_REQUEST);

//Verifica se o usuário pode acessar a tela
require "login_verifica.php";

$saida = $_GET["saida"];
$indicadorpresenca = $_POST["indicadorpresenca"];
$tipoimpressaodanfe = $_POST["tipoimpressaodanfe"];



$tipopagina = "saidas";
include "includes.php";




//Pega dados para montagem do ARRAY que emitirá a NFE

//Configurações do SISTEMA
$sql="SELECT * FROM configuracoes WHERE cnf_codigo=1";
if (!$query = mysql_query($sql)) die("<br>Erro SQL Configuracoes GLOBAL: ".mysql_error());
$dados=mysql_fetch_assoc($query);
$nfe_dataversaosistema= $dados["cnf_dataversao"];
$nfe_versaosistema= $dados["cnf_versao"];

//Quiosque Configurações e Emitente
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
$nfe_geral_versaonfe=$dados["quicnf_versaonfe"];
$nfe_geral_csc=$dados["quicnf_csctoken"];
$nfe_geral_csc = str_replace('-', '', $nfe_geral_csc);
$nfe_geral_cscid=$dados["quicnf_csctokenid"];
$nfe_geral_serie=$dados["quicnf_serienfe"];
$nfe_geral_crt=$dados["quicnf_crtnfe"];
$nfe_geral_numeroproximanota=$dados["quicnf_ultimanfe"]+1;
$nfe_geral_tipoimpressao=$tipoimpressaodanfe;
$nfe_geral_tipoemissao=1; //1=Emissão normal (não em contingência); 2=Contingência FS-IA, com impressão do DANFE em formulário de segurança; 3=Contingência SCAN (Sistema de Contingência do Ambiente Nacional); 4=Contingência DPEC (Declaração Prévia da Emissão em Contingência); 5=Contingência FS-DA, com impressão do DANFE em formulário de segurança; 6=Contingência SVC-AN (SEFAZ Virtual de Contingência do AN); 7=Contingência SVC-RS (SEFAZ Virtual de Contingência do RS);
$nfe_geral_entradaousaida=1; // 0:Entrada e 1:Saída
$nfe_operacaodestino=1;  //1:Operacao interna / 2: Operacao interestadual / 3: Operacao para exterior
date_default_timezone_set('America/Sao_Paulo');
$data=$date = date('Y-m-d');
$hora=$date = date('H:i:s');
if (date('I')==1) $gmt="-02:00"; //Se estiver no horário de verão
else $gmt="-03:00";
$nfe_geral_dataemissao=$data."T".$hora.$gmt;
$nfe_emitente_razaosocial=$dados["qui_razaosocial"];
$nfe_emitente_nomefantasia=$dados["qui_nome"];
$nfe_emitente_uf=$dados["est_codigo2"]; //IBGE
$nfe_emitente_cnpj=$dados["qui_cnpj"];
$nfe_emitente_ie=$dados["qui_ie"];
$nfe_emitente_im=$dados["qui_im"];
$nfe_emitente_estado=$dados["est_sigla"];
$nfe_emitente_cidade=$dados["cid_ibge"];
$nfe_emitente_cidade_nome=$dados["cid_nome"];
$nfe_emitente_endereco=$dados["qui_endereco"];
$nfe_emitente_endereco_numero=$dados["qui_numero"];
$nfe_emitente_endereco_complemento=$dados["qui_complemento"];
$nfe_emitente_bairro=$dados["qui_bairro"];
$nfe_emitente_cep=$dados["qui_cep"];
$nfe_emitente_cep=str_replace('-', '', $nfe_emitente_cep);
$nfe_emitente_pais=$dados["pai_ibge"];
$nfe_emitente_pais_nome=$dados["pai_nome"];
$nfe_emitente_telefone=$dados["qui_fone1"];
$nfe_geral_danfeoucupom=$dados["danfe_id"];

//Venda
$sql="
SELECT * 
FROM saidas
JOIN metodos_pagamento on (sai_metpag=metpag_codigo) 
WHERE sai_codigo= $saida ";
if (!$query = mysql_query($sql)) die("<br>Erro SQL SAIDA: ".mysql_error());
$dados=mysql_fetch_assoc($query);
$nfe_venda_numero= $saida;
$areceber=$dados["sai_areceber"];
$consumidor=$dados["sai_consumidor"];
$nfe_venda_valtot=$dados["sai_totalbruto"];
$desconto = $dados["sai_descontovalor"];
$troco_desconto = $dados["sai_descontoforcado"];
$troco_acrescimo = $dados["sai_acrescimoforcado"];
$nfe_venda_descontototal=$desconto + $troco_desconto - $troco_acrescimo;
$nfe_venda_totalliquido = $dados["sai_totalliquido"];
$nfe_venda_metodopagamento = $dados["metpag_nfecodigo"]; // // 01=Dinheiro 02=Cheque 03=Cartão de Crédito 04=Cartão de Débito 05=Crédito Loja 10=Vale Alimentação 11=Vale Refeição 12=Vale Presente 13=Vale Combustível 99=Outros
if ($consumidor==0) { //Se for sem identificação do cliente, ou seja, cliente geral
    $nfe_venda_indicadorcliente=1; // 0: Normal / 1: Consumidor final 
} else {
    $nfe_venda_indicadorcliente=0; // 0: Normal / 1: Consumidor final 
}
$nfe_venda_naturezaoperacao="VENDA";
if ($areceber==1) { // Indicador de pagamento 0: a vista / 1: a prazo / 2: outros
    $nfe_venda_indicadorpagamento=1;
} else {
    $nfe_venda_indicadorpagamento =0;
}
$nfe_venda_finalidade=1; // 1:NFe normal / 2: NFe complementar / 3: NFe de ajuste / 4: Devolução de mercadoria
$nfe_venda_frete=1; //0=Por conta do emitente; 1=Por conta do destinatário/remetente; 2=Por conta de terceiros; 9=Sem frete. (V2.0)
$nfe_venda_presencial=$indicadorpresenca;  // 0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);  1=Operação presencial;  2=Operação não presencial, pela Internet;  3=Operação não presencial, Teleatendimento;  4=NFC-e em operação com entrega a domicílio; 9=Operação não presencial, outros.
$nfe_venda_tipointegracaopagamento=2; // 1=Pagamento integrado com o sistema de automação da empresa (Ex.: equipamento TEF, Comércio Eletrônico); 2= Pagamento não integrado com o sistema de automação da empresa (Ex.: equipamento POS);

//tanto o config.json como o certificado.pfx podem estar
//armazenados em uma base de dados, então não é necessário
///trabalhar com arquivos, este script abaixo serve apenas como
//exemplo durante a fase de desenvolvimento e testes.




//EMITENTE
$arr = [
    "atualizacao" => "$nfe_dataversaosistema", //data da versão do SGAF 
    "tpAmb" => $nfe_geral_ambiente,
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

$dadosNfe = [
    "tpAmb" => "$nfe_geral_ambiente",
    "cDV" => "",
    "id" => "", // Se deixar nulo ele gerar automatico um numero
    "mod" => "$nfe_geral_danfeoucupom", // 55: NFe / 65: NFCe
    "cNF" => "$nfe_venda_numero", //Numero da venda 
    "cUF" => "$nfe_emitente_uf", //Código IBGE do estado
    "natOp" => "$nfe_venda_naturezaoperacao", //Venda ou Devolução ou Cancelamento
    "indPag" => "$nfe_venda_indicadorpagamento", //NÃO EXISTE MAIS NA VERSÃO 4.00 --- Versao 3.10= 0: a vista / 1: a prazo / 2: outros
    "serie" => "$nfe_geral_serie",
    "nNF" => "$nfe_geral_numeroproximanota", //ultima nota + 1
    "dhEmi" => "$nfe_geral_dataemissao",
    "dhSaiEnt" => null,
    "tpNF" => "$nfe_geral_entradaousaida", // 0:Entrada e 1:Saída
    "idDest" => "$nfe_operacaodestino", //1:Operacao interna / 2: Operacao interestadual / 3: Operacao para exterior
    "cMunFG" => "$nfe_emitente_cidade", //ibge
    "tpImp" => "$nfe_geral_tipoimpressao", //  1=DANFE normal, Retrato; 2=DANFE normal, Paisagem; 3=DANFE Simplificado; 4=DANFE NFC-e; 5=DANFE NFC-e em mensagem eletrônica
    "tpEmis" => "$nfe_geral_tipoemissao",
    "finNFe" => "$nfe_venda_finalidade", // 1:NFe normal / 2: NFe complementar / 3: NFe de ajuste / 4: Devolução de mercadoria
    "indFinal" => "$nfe_venda_indicadorcliente", // 0: Normal / 1: Consumidor final
    "indPres" => "$nfe_venda_presencial",
    "procEmi" => "0", //SEMPRE ZERO - Emissao deNFe com aplicativo do contribuinte
    "verProc" => "$nfe_versaosistema", //Versão do sistema SGAF
    "xNome" => "$nfe_emitente_razaosocial", //Razão social 
    "xFant" => "$nfe_emitente_nomefantasia",
    "IE" => "$nfe_emitente_ie", //Inscrição estadual
    "IEST" => null,
    "IM" => "$nfe_emitente_im", //Inscrição Municial (agricultor precisa)
    "CNAE" => null,
    "CRT" => $nfe_geral_crt,
    "xLgr" => "$nfe_emitente_endereco",
    "nro" => "$nfe_emitente_endereco_numero",
    "xCpl" => "$nfe_emitente_endereco_complemento",
    "xBairro" => "$nfe_emitente_bairro",
    "cMun" => "$nfe_emitente_cidade", //cidade ibge
    "xMun" => "nfe_emitente_cidade_nome",
    "UF" => "$nfe_emitente_estado",
    "CEP" => "$nfe_emitente_cep",
    "cPais" => "$nfe_emitente_pais", //ibge 1058=brasil
    "xPais" => "$nfe_emitente_pais_nome",
    "fone" => "$nfe_emitente_telefone", //só numeros, sem caracteres especiais
    "vBC" => null,
    "vICMS" => null,
    "vICMSDesonv" => null,
    "vBCST" => null,
    "vST" => null,
    "vProd" => $nfe_venda_valtot, //Valor total dos produtos da nota
    "vFrete" => null,
    "vSeg" => null,
    "vDesc" => $nfe_venda_descontototal, //Desconto total da venda
    "vII" => null,
    "vIPI" => null,
    "vPIS" => null,
    "vCOFINS" => null,
    "vOutro" => null,
    "vNF" => $nfe_venda_totalliquido, //total liquido
    "vTotTrib" => null,
    "vFCP" => null,
    "vFCPST" => null,
    "vFCPSTRet" => null,
    "vIPIDevol" => null,
    "modFrete" => $nfe_venda_frete, //0=Por conta do emitente; 1=Por conta do destinatário/remetente; 2=Por conta de terceiros; 9=Sem frete. (V2.0)
    "tPag" => "$nfe_venda_metodopagamento", // 01=Dinheiro 02=Cheque 03=Cartão de Crédito 04=Cartão de Débito 05=Crédito Loja 10=Vale Alimentação 11=Vale Refeição 12=Vale Presente 13=Vale Combustível 99=Outros
    "vPag" => $nfe_venda_totalliquido, ////Obs: deve ser o informado o valor total da Nota Fiscal (vPag = vNF), caso contrário a a SEFAZ irá retornar "Rejeição 767"
    "tpIntegra" => $nfe_venda_tipointegracaopagamento, // 1=Pagamento integrado com o sistema de automação da empresa (Ex.: equipamento TEF, Comércio Eletrônico); 2= Pagamento não integrado com o sistema de automação da empresa (Ex.: equipamento POS);
    "vTroco" => null,
    "doctoNfe" => [
      "doc" => "CNPJ", //Escrever se é CPF ou CNPJ conforme tipo de pessoa fisica ou juridica
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

echo $dadosNfeJson = json_encode($dadosNfe);

// AQUI VAMOS CHAMAR O METODO DE GERACAO DA NOTA DO SERVIDOR DO SPED NFE
// VAI RETORNAR UM OUTRO JSON CONTENDO MENSAGENS DE SUCESSO OU ERRO


//Atualiza a última NFE gerada




?>
