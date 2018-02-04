<?php

//print_r($_REQUEST);

//Verifica se o usuário pode acessar a tela
require "login_verifica.php";

//NFE
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once 'spednferest/bootstrap.php';
require_once 'spednferest/EmissorNFe.php';


$saida = $_GET["saida"];
$indicadorpresenca = $_POST["indicadorpresenca"];
$tipoimpressaodanfe = $_POST["tipoimpressaodanfe"];
$ope=""; //1=normal 2=cancelamento 3=devolucao


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
if ($dados["qui_tipopessoa"] == 2)
  $nfe_emitente_tipopessoa="CNPJ";
else
  $nfe_emitente_tipopessoa="CPF";
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
left join pessoas on (sai_consumidor=pes_codigo)
left join cidades on (pes_cidade=cid_codigo)
left join estados on (cid_estado=est_codigo)
left join paises on (est_pais=pai_codigo)
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
$nfe_venda_consumidor_nome = $dados["pes_nome"];
if ($nfe_venda_consumidor_nome=="Cliente Geral");
$nfe_venda_consumidor_ie = $dados["pes_ie"];
$nfe_venda_consumidor_im = $dados["pes_im"];
$nfe_venda_consumidor_endereco = $dados["pes_endereco"];
$nfe_venda_consumidor_endereco_numero = $dados["pes_endereco"];
$nfe_venda_consumidor_endereco_complemento = $dados["pes_numero"];
$nfe_venda_consumidor_bairro = $dados["pes_bairro"];
$nfe_venda_consumidor_cidade = $dados["cid_ibge"];
$nfe_venda_consumidor_cidade_nome = $dados["cid_nome"];
$nfe_venda_consumidor_estado = $dados["est_sigla"];
$nfe_venda_consumidor_pais = $dados["pai_ibge"];
$nfe_venda_consumidor_pais_nome = $dados["pai_nome"];
$nfe_venda_consumidor_telefone = $dados["pes_fone1"];
$nfe_venda_consumidor_cep = $dados["pes_cep"];
$nfe_venda_consumidor_cep=str_replace('-', '', $nfe_venda_consumidor_cep);
$nfe_venda_consumidor_email = $dados["pes_email"];
$nfe_venda_troco = null; //Eu não sei se é troco a dever ou troco devolvido?!
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
$tipopessoa=$dados["pes_tipopessoa"];
if ($tipopessoa==1) {
  $nfe_venda_consumidor_tipopessoa="CPF";
  $nfe_venda_consumidor_cpf=$dados["pes_cpf"];
  $nfe_venda_consumidor_cnpj=null;
} else {
  $nfe_venda_consumidor_tipopessoa="CNPJ";
  $nfe_venda_consumidor_cnpj=$dados["pes_cnpj"];
  $nfe_venda_consumidor_cpf=null;
}
if (($dados["metpag_nfecodigo"]==2)||($dados["metpag_nfecodigo"]==3)||($dados["metpag_nfecodigo"]==6)||($dados["metpag_nfecodigo"]==7)) {
  $nfe_venda_usacartao = true;
  $nfe_venda_usacartao_bandeira=$dados["sai_cartaobandeira"]; //1:VISA 2:Mastercard 3:American Express 4:Sorocred 99:Outros
  if ($nfe_venda_usacartao_bandeira==1) $nfe_venda_usacartao_cnpj="31551765000143";
  if ($nfe_venda_usacartao_bandeira==2) $nfe_venda_usacartao_cnpj="05577343000137";
  if ($nfe_venda_usacartao_bandeira==3) $nfe_venda_usacartao_cnpj="59438325000101";
  if ($nfe_venda_usacartao_bandeira==4) $nfe_venda_usacartao_cnpj="04814563000174";
  if ($nfe_venda_usacartao_bandeira==9) $nfe_venda_usacartao_cnpj=null;
  $nfe_venda_usacartao_caut="";
} else {
  $nfe_venda_usacartao = false;
}
if (($nfe_geral_danfeoucupom==65)||($nfe_operacaodestino==3)) { //Cupom fiscal
  $nfe_venda_indiedest="9";
  $nfe_venda_indiedest_ie=null;
} else {
  $nfe_venda_indiedest=$dados["pes_contribuinte_icms"];
  if ($nfe_venda_indiedest==2) $nfe_venda_indiedest_ie=null;
  else $nfe_venda_indiedest_ie=$nfe_venda_consumidor_ie;
}



//Verifica se não for cupom fiscal é necessário que o endereço do cliente esteja preenchido
if (($tipoimpressaodanfe!=4)&&($tipoimpressaodanfe!=5)) {
  // echo "$nfe_consumidor_endereco / $nfe_consumidor_endereco_numero / $nfe_consumidor_bairro";
  if ($nfe_consumidor_endereco=="") {
    $msg=$msg . " Preencher <b>endereço</b> do consumidor. <br>";
    $semendereco="1";
  }
  if ($nfe_consumidor_endereco_numero=="") {
    $msg=$msg . " Preencher <b>número do endereço</b> do consumidor. <Br>";
    $semendereco="1";
  }
  if ($nfe_consumidor_bairro=="") {
    $msg=$msg . " Preencher <b>bairro</b> do consumidor. <br>";
    $semendereco=1;
  }
  if ($semendereco==1) {
      $tpl6 = new Template("templates/notificacao.html");
      $tpl6->block("BLOCK_ATENCAO");
      $tpl6->ICONES = $icones;
      $tpl6->MOTIVO = "<Br>Consumidor sem a informação: <b>ENDEREÇO</b>. <br><br> $msg <br><br>";
      $tpl6->block("BLOCK_MOTIVO");
      $tpl6->block("BLOCK_BOTAO_VOLTAR");
      $tpl6->show();
      exit;
  }
}




//tanto o config.json como o certificado.pfx podem estar
//armazenados em uma base de dados, então não é necessário
///trabalhar com arquivos, este script abaixo serve apenas como
//exemplo durante a fase de desenvolvimento e testes.

//EMITENTE
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

$dadosNfe = [
    "tpAmb" => (int)$nfe_geral_ambiente,
    "cDV" => "",
    "id" => null, // Se deixar nulo ele gerar automatico um numero
    "mod" => "$nfe_geral_danfeoucupom", // 55: NFe / 65: NFCe
    "cNF" => "$nfe_venda_numero", // Numero da venda 
    "cUF" => "$nfe_emitente_uf", // Código IBGE do estado
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
    "vICMS" => "",
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
    "vTroco" => $nfe_venda_troco,
    "doctoNfe" => [
      "doc" => "$nfe_emitente_tipopessoa", //Escrever se é CPF ou CNPJ conforme tipo de pessoa fisica ou juridica
      "CNPJ" => "$nfe_emitente_cnpj",
      "CPF" => "$nfe_emitente_cpf",
    ],
    "usaNfeCartaoCredito" => [ 
      "Cartao" => $nfe_venda_usacartao, //true ou false
      "CNPJ" => "$nfe_venda_usacartao_cnpj",
      "tBand" => "$nfe_venda_usacartao_bandeira",
      "cAut" => "$nfe_venda_usacartao_caut",
    ],
    "destinatario" => [
      "xNome" => "$nfe_venda_consumidor_nome",
      "indIEDest" => "$nfe_venda_indiedest",
      "IE" => "$nfe_venda_indiedest_ie",
      "ISUF" => null,
      "IM" => "$nfe_venda_consumidor_im",
      "email" => "$nfe_venda_consumidor_email",
      "idEstrangeiro" => null,
      "xLgr" => "$nfe_venda_consumidor_endereco",
      "nro" => $nfe_venda_consumidor_endereco_numero,
      "xCpl" => "$nfe_venda_consumidor_endereco_complemento",
      "xBairro" => "$nfe_venda_consumidor_bairro",
      "cMun" => "$nfe_venda_consumidor_cidade", //ibge
      "xMun" => "$nfe_venda_consumidor_cidade_nome",
      "UF" => "$nfe_venda_consumidor_estado",
      "CEP" => "$nfe_venda_consumidor_cep",
      "cPais" => "$nfe_venda_consumidor_pais",
      "xPais" => "$nfe_venda_consumidor_pais_nome",
      "fone" => "$nfe_venda_consumidor_telefone",
      "doctoNfe" => [
        "doc" => "$nfe_venda_consumidor_tipopessoa",
        "CNPJ" => "$nfe_venda_consumidor_cnpj",
        "CPF" => "$nfe_venda_consumidor_cpf",
      ],
      "endEntrega" => [
        "xLgr" => "$nfe_venda_consumidor_endereco",
        "nro" => $nfe_venda_consumidor_endereco_numero,
        "xCpl" => "$nfe_venda_consumidor_endereco_complemento",
        "xBairro" => "$nfe_venda_consumidor_bairro",
        "cMun" => "$nfe_venda_consumidor_cidade",
        "xMun" => "$nfe_venda_consumidor_cidade_nome",
        "UF" => "$nfe_venda_consumidor_estado",
        "doctoNfe" => [
          "doc" => "nfe_venda_consumidor_tipopessoa",
          "CNPJ" => "$nfe_venda_consumidor_cnpj",
          "CPF" => "$nfe_venda_consumidor_cpf",
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
    "usaNfContingencia" => [ //Avaliar se tem relação com devoluÇào e cancelamento 
      "contingencia" => false,
      "dhCont" => null, //2015-02-19T13:48:00-02:00
      "xJust" => null, //Justificativa
    ],
    "usaNfReferenciada" => [ //Quando for devolução ou cancelamento
      "referenciada" => false,
      "refNFe" => null, //35150271780456000160550010000253101000253101
    ],
    "usaNfISSQN" => [ //Imposto sobre Serviços de Qualquer Natureza
      "ISSQN" => false,
    ],
    "usaNfRetTributos" => [ //Retenção de tributos
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
    "qtdItens" => "$nfe_venda_qtditens",
];


//Itens da Venda
$sql="
SELECT * 
FROM saidas_produtos 
join produtos on (saipro_produto=pro_codigo)
left join nfe_cfop on (pro_cfop=cfop_codigo)
left join nfe_ncm on (pro_ncm=ncm_codigo)
WHERE saipro_saida=$saida
";
if (!$query = mysql_query($sql)) die("<br>Erro SQL SAIDA PRODUTOS: ".mysql_error());
$nfe_venda_qtditens=mysql_num_rows($query);
$dadosNfeItens = array();
while ($dados=mysql_fetch_assoc($query)) {
  $numero=$dados["saipro_codigo"];
  $nfe_venda_item_numero=$numero;
  $nfe_venda_item_produto_codigo=$dados["saipro_produto"]; //Código do produto no sistema SGAF
  $nfe_venda_item_produto_ean=$dados["pro_codigounico"];
  $nfe_venda_item_produto_nome=$dados["pro_nome"];
  $nfe_venda_item_ncm=$dados["ncm_id"];
  $nfe_venda_item_cfop=str_replace(".","",$dados["cfop_id"]);
  $tipocontagem=$dados["pro_tipocontagem"];
  if ($tipocontagem==1) $nfe_venda_item_tipocontagem="UN"; //UN KG LT
  if ($tipocontagem==2) $nfe_venda_item_tipocontagem="KG"; //UN KG LT
  if ($tipocontagem==3) $nfe_venda_item_tipocontagem="LT"; //UN KG LT
  $nfe_venda_item_quantidade=$dados["saipro_quantidade"];
  $nfe_venda_item_valuni=$dados["saipro_valorunitario"];
  $nfe_venda_item_valtot=$dados["saipro_valortotal"]; //bruto
  $nfe_venda_item_totalcompoenfe=1; //Indica se o valor total compoe ou nao na NFe // 0: Valor do Item não compoe o valor total da NFe / 1: Valor do item compoes o valor total da NFe
  $nfe_venda_item_origem=$dados["pro_origem"]; // 0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8;  1 - Estrangeira - Importação direta, exceto a indicada no código 6;  2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7; 3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%; 4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes; 5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%;  6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX e gás natural; 7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e gás natural. 8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%;
  $nfe_venda_item_cst=40; 
  //Tabela A: 0 - Nacional, 1 - Importação Direta, 2 - Estrangeira Adquirida no Mercado Interno 
  //Tabela B: 00 Tributada integralmente, 10 Tributada e com cobrança do ICMS por substituição tributária, 20 Com redução de base de cálculo, 30 Isenta ou não tributada e com cobrança do ICMS por substituição tributária, 40 Isenta, 41 Não tributada, 50 Suspensão, 51 Diferimento, 60 ICMS cobrado anteriormente por substituição tributária, 70 Com redução de base de cálculo e cobrança do ICMS por substituição tributária, 90 Outras
  $nfe_venda_item_csosn=400;
  //101 – Tributada pelo Simples Nacional com permissão de crédito – Classificam-se neste código as operações que permitem a indicação da alíquota do ICMS devido no Simples Nacional e o valor do crédito correspondente.
  //102 – Tributada pelo Simples Nacional sem permissão de crédito – Classificam-se neste código as operações que não permitem a indicação da alíquota do ICMS devido pelo Simples Nacional e do valor do crédito, e não estejam abrangidas nas hipóteses dos códigos 103, 203, 300, 400, 500 e 900.
  //103 – Isenção do ICMS no Simples Nacional para faixa de receita bruta – Classificam-se neste código as operações praticadas por optantes pelo Simples Nacional contemplados com isenção concedida para faixa de receita bruta nos termos da Lei Complementar nº 123, de 2006.
  //201 – Tributada pelo Simples Nacional com permissão de crédito e com cobrança do ICMS por substituição tributária – Classificam-se neste código as operações que permitem a indicação da alíquota do ICMS devido pelo Simples Nacional e do valor do crédito, e com cobrança do ICMS por substituição tributária. 
  //202 – Tributada pelo Simples Nacional sem permissão de crédito e com cobrança do ICMS por substituição tributária – Classificam-se neste código as operações que não permitem a indicação da alíquota do ICMS devido pelo Simples Nacional e do valor do crédito, e não estejam abrangidas nas hipóteses dos códigos 103, 203, 300, 400, 500 e 900, e com cobrança do ICMS por substituição tributária. 
  //203 – Isenção do ICMS no Simples Nacional para faixa de receita bruta e com cobrança do ICMS por substituição tributária – Classificam-se neste código as operações praticadas por optantes pelo Simples Nacional contemplados com isenção para faixa de receita bruta nos termos da Lei Complementar nº 123, de 2006, e com cobrança do ICMS por substituição tributária. 
  //300 – Imune – Classificam-se neste código as operações praticadas por optantes pelo Simples Nacional contempladas com imunidade do ICMS. 
  //400 – Não tributada pelo Simples Nacional – Classificam-se neste código as operações praticadas por optantes pelo Simples Nacional não sujeitas à tributação pelo ICMS dentro do Simples Nacional. 
  //500 – ICMS cobrado anteriormente por substituição tributária (substituído) ou por antecipação – Classificam-se neste código as operações sujeitas exclusivamente ao regime de substituição tributária na condição de substituído tributário ou no caso de antecipações.
  //900 – Outros – Classificam-se neste código as demais operações que não se enquadrem nos códigos 101, 102, 103, 201, 202, 203, 300, 400 e 500.
    
  $prod[$numero] = array(
    "item" => $nfe_venda_item_numero,
    "cProd" => $nfe_venda_item_produto_codigo, //Código do produto no sistema SGAF
    "cEAN" => $nfe_venda_item_produto_ean,
    "xProd" => "$nfe_venda_item_produto_nome",
    "NCM" => "$nfe_venda_item_ncm",
    "EXTIPI" => null,
    "CFOP" => "$nfe_venda_item_cfop",
    "uCom" => "$nfe_venda_item_tipocontagem", //UN KG LT
    "qCom" => $nfe_venda_item_quantidade,
    "vUnCom" => $nfe_venda_item_valuni,
    "vProd" => $nfe_venda_item_valtot, //bruto
    "cEANTrib" => null,
    "uTrib" => "$nfe_venda_item_tipocontagem",
    "qTrib" => $nfe_venda_item_quantidade,
    "vUnTrib" => $nfe_venda_item_valuni,
    "vFrete" => null,
    "vSeg" => null,
    "vDesc" => null,
    "vOutro" => null,
    "indTot" => $nfe_venda_item_totalcompoenfe, //Indica se o valor total compoe ou nao na NFe // 0: Valor do Item não compoe o valor total da NFe / 1: Valor do item compoes o valor total da NFe
    "xPed" => null,
    "nItemPed" => null,
    "nFCI" => null,
    "infAdProd" => null,
    "CEST" => null,
    "indEscala" => null,
    "CNPJFab" => null,
    "nLote" => null,
    "qLote" => null,
    "dFab" => null,
    "dVal" => null,
    "cAgreg" => null,
    "vTotTrib" => 0.00, //avaliar
    "orig" => "$nfe_venda_item_origem", // 0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8;  1 - Estrangeira - Importação direta, exceto a indicada no código 6;  2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7; 3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%; 4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes; 5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%;  6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX e gás natural; 7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e gás natural. 8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%;
    "CST" => "$nfe_venda_item_cst", //40
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
    "CSOSN" => "$nfe_venda_item_csosn", //400
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
  );

  array_push($dadosNfeItens, $prod[$numero]);

}

echo json_encode($dadosNfe)."<br><br>";
echo json_encode($arr)."<br><br>";
echo json_encode($dadosNfeItens)."<br><br>";

// AQUI VAMOS CHAMAR O METODO DE GERACAO DA NOTA DO SERVIDOR DO SPED NFE
// VAI RETORNAR UM OUTRO JSON CONTENDO MENSAGENS DE SUCESSO OU ERRO
$configJson = json_encode($arr);
$dadosNfeJson = json_encode($dadosNfe);
$dadosNfeItensJson = json_encode($dadosNfeItens);
$emissor = new EmissorNFe($configJson, $dadosNfeJson, $dadosNfeItensJson);
$emissor->emiteNfe();


//Atualiza a última NFE gerada


//Quando se trata de um cancelamento, pergunta ao cliente se ele deseja gerar uma nova venda com os mesmo produto (duplicar a venda). Isto facilita para o usuário quando trata-se de uma venda com muitos itens, ele não precisa fazer uma nova venda tudo do zero caso só deseje editar um ou outro item


?>
