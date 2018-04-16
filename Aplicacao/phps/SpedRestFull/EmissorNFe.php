<?php
namespace SpedRestFull;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

use NFePHP\NFe\Tools;
use NFePHP\NFe\Make;
use NFePHP\NFe\Complements;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Common\Standardize;
use NFePHP\Common\Soap\SoapCurl;

use NFePHP\DA\NFe\Danfe;
use NFePHP\DA\NFe\Danfce;
use NFePHP\DA\Legacy\FilesFolders;

use Storage;

class EmissorNFe {
  protected $arr;
  protected $dadosNfe;
  protected $dadosNfeItens;
  public $nfGerada;
  public $tools;

  public function __construct($arr, $dadosNfe, $dadosNfeItens) {
    $this->arr = json_decode($arr);
    $this->dadosNfe = json_decode($dadosNfe);
    $this->dadosNfeItens = json_decode($dadosNfeItens);

    $configJson = json_encode($this->arr);

    //$url = Storage::disk('public')->url('1000364088.pfx');]
    //$content = \File::get(storage_path('app/1000364088.pfx'));
    //$url = Storage::url('1000364088.pfx');

    // echo($url); die();
    $content = file_get_contents("SpedRestFull/includes/1000543641.pfx");
    $this->tools = new Tools($configJson, Certificate::readPfx($content, 'Valmor9'));
  }

  public function emiteNFe() {
    //monta o config.json
    $dadosNfe = $this->dadosNfe;
    $dadosNfeItens = $this->dadosNfeItens;
    
    $this->tools->model($dadosNfe->mod);
    $nfeLayout = "3.10";//$arr->versao;

    $nfe = new Make();

    $std = new \stdClass();
    $std->versao = $nfeLayout; //versão do layout
    $std->Id = $dadosNfe->id; //se o Id de 44 digitos não for passado será gerado automaticamente
    $std->pk_nItem = null; //deixe essa variavel sempre como NULL
    $elem = $nfe->taginfNFe($std);

    $std = new \stdClass();
    $std->cUF = $dadosNfe->cUF; // código IBGE do estado de quem está emitindo
    $std->cNF = $dadosNfe->cNF; // CODIGO DO SISTEMA EMISSOR (SGAF ou eVET)
    /*
     * natOp
     *   Informar a natureza da operação de que decorrer a saída ou aentrada, tais como:
     *   venda, compra, transferência, devolução, importação, consignação,
     *   remessa (para fins de demonstração, de industrialização ou outra),
     *   conforme previsto na alínea 'i', inciso I, art. 19 do CONVÊNIO S/Nº, de 15 de dezembro de 1970.
     */
    $std->natOp = $dadosNfe->natOp;
    if ($nfeLayout != "4.00") {
        $std->indPag = $dadosNfe->indPag; //NÃO EXISTE MAIS NA VERSÃO 4.00 --- Versao 3.10= 0: a vista / 1: a prazo / 2: outros
    }
    $std->mod = $dadosNfe->mod; // Exemplos na pasta - 55: NFe / 65: NFCe
    $std->serie = $dadosNfe->serie; // Contador informa
    $std->nNF = $dadosNfe->nNF; // CODIGO SEQUENCIAL DO SEFAZ
    $std->dhEmi = $dadosNfe->dhEmi;
    $std->dhSaiEnt = $dadosNfe->dhSaiEnt; // Deixar null para a NFCe (Mod65)
    $std->tpNF = $dadosNfe->tpNF; // 0:Entrada e 1:Saída
    $std->idDest = $dadosNfe->idDest; //1:Operacao interna / 2: Operacao interestadual / 3: Operacao para exterior
    $std->cMunFG = $dadosNfe->cMunFG; // Cod_IBGE do municipio que ocorreu a operação de venda (vendedor a pronta entrega, por exemplo cuja NF foi emitida em outra cidade)
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
    $std->tpImp = $dadosNfe->tpImp;
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
    $std->tpEmis = $dadosNfe->tpEmis;
    $std->cDV = $dadosNfe->mod; ////// --> DESCOBRIR
    $std->tpAmb = $dadosNfe->tpAmb; // 1: producao / 2: Homologação
    $std->finNFe = $dadosNfe->finNFe; // 1:NFe normal / 2: NFe complementar / 3: NFe de ajsute / 4: Devolução de mercadoria
    $std->indFinal = $dadosNfe->indFinal; // 0: Normal / 1: Consumidor final
    /*
     * indPres
     *   0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
     *   1=Operação presencial;
     *   2=Operação não presencial, pela Internet;
     *   3=Operação não presencial, Teleatendimento;
     *   4=NFC-e em operação com entrega a domicílio;
     *   9=Operação não presencial, outros.
     */
    $std->indPres = $dadosNfe->indPres;
    $std->procEmi = $dadosNfe->procEmi; // SEMPRE ZERO - Emissao deNFe com aplicativo do contribuinte
    $std->verProc = $dadosNfe->verProc; // Versão do sistema (SGAF ou eVET)

    if($dadosNfe->usaNfContingencia->contingencia == true) {
        $std->dhCont = $dadosNfe->usaNfContingencia->dhCont; // Data e hora em contingência -- só usar isso em contingência
        $std->xJust = $dadosNfe->usaNfContingencia->xJust; // Justificar contingência - Tamanho de 15 a 256 caracteres
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
    if($dadosNfe->usaNfReferenciada->referenciada == true) {
        $std = new \stdClass();
        $std->refNFe = $dadosNfe->usaNfReferenciada->refNFe; // Chave de acesso da NF referenciada

        $elem = $nfe->tagrefNFe($std);
    }

    $std = new \stdClass();
    $std->xNome = $dadosNfe->xNome; // Razao social ou nome do emitente
    $std->xFant = $dadosNfe->xFant; // Nome fantasia
    $std->IE = $dadosNfe->IE; // Inscricao estadual do emitente
    $std->IEST = $dadosNfe->IEST;// IE do substituto tributário ---- Ver se o emitente tem com o contator
    $std->IM = $dadosNfe->IM; // Inscricao municipal --- informar apenas com itens e servicos misturados na NFe (A PRINCIPIO NAO VAMOS USAR_
    $std->CNAE = $dadosNfe->CNAE; // Campo opcional -- informar qdo a IM for informada
    $std->CRT = $dadosNfe->CRT; // Codigo de Regime Tributario // 1: Simples Nacional / 2: Simples Nacional, excesso sublimite de receita bruta / 3: Regime Normal
    if ($dadosNfe->doctoNfe->doc == "CNPJ") {  // Indicar apenas um CNPJ ou CPF
        $std->CNPJ = $dadosNfe->doctoNfe->CNPJ;
    }
    else {
        $std->CPF = $dadosNfe->doctoNfe->CPF;
    }
    $elem = $nfe->tagemit($std);

    $std = new \stdClass();
    $std->xLgr = $dadosNfe->xLgr; // Logradouro
    $std->nro = $dadosNfe->nro; // Numero
    $std->xCpl = $dadosNfe->xCpl; // Complemento
    $std->xBairro = $dadosNfe->xBairro; // Bairro
    $std->cMun = $dadosNfe->cMun; // Cod_IBGE do municipio emitente
    $std->xMun = $dadosNfe->xMun; // Nome do municipio
    $std->UF = $dadosNfe->UF;  // Sigla do estado
    $std->CEP = $dadosNfe->CEP;
    $std->cPais = $dadosNfe->cPais; // Codigo do pais 1058 para Brasil
    $std->xPais = $dadosNfe->xPais; // Nome do pais Brasil ou BRASIL
    $std->fone = $dadosNfe->fone; // DDD + numero... para venda exterior adicionar codigo do pais

    $elem = $nfe->tagenderEmit($std);


    if ($dadosNfe->destinatario->xNome != null) {
      $std = new \stdClass();
      $std->xNome = $dadosNfe->destinatario->xNome;
      /*
       * indIEDest
       *    1=Contribuinte ICMS (informar a IE do destinatário);
       *    2=Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS
       *    9=Não Contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes do ICMS.
       *    ***Nota 1: No caso de NFC-e informar indIEDest=9 e não informar a tag IE do destinatário;
       *    Nota 2: No caso de operação com o Exterior informar indIEDest=9 e não informar a tag IE do destinatário;
       *    Nota 3: No caso de Contribuinte Isento de Inscrição (indIEDest=2), não informar a tag IE do destinatário.
       */
      $std->indIEDest = $dadosNfe->destinatario->indIEDest;
      $std->IE = $dadosNfe->destinatario->IE; // Opcional = IE do destinatario nao informar em NFCe
      $std->ISUF = $dadosNfe->destinatario->ISUF; // Só é obrigatorio para incentivados pelo SUFRAMA (MANAUS)
      $std->IM = $dadosNfe->destinatario->IM; // opcional
      $std->email = $dadosNfe->destinatario->email;
      if ($dadosNfe->destinatario->doctoNfe->doc == "CNPJ") {
          $std->CNPJ = $dadosNfe->destinatario->doctoNfe->CNPJ; //indicar apenas um CNPJ ou CPF ou idEstrangeiro
      }
      else {
          $std->CPF = $dadosNfe->destinatario->doctoNfe->CPF;
      }
      $std->idEstrangeiro = $dadosNfe->destinatario->idEstrangeiro; // numero do passaporte ou documento legal -- pode ser null
      $elem = $nfe->tagdest($std);

      // $std = new stdClass();
      // $std->xLgr    = $dadosNfe->destinatario->xLgr;
      // $std->nro     = $dadosNfe->destinatario->nro;
      // $std->xCpl    = $dadosNfe->destinatario->xCpl;
      // $std->xBairro = $dadosNfe->destinatario->xBairro;
      // $std->cMun    = $dadosNfe->destinatario->cMun; // Cod_IBGE do municipio do comprador
      // $std->xMun    = $dadosNfe->destinatario->xMun; // Nome do municipio
      // $std->UF      = $dadosNfe->destinatario->UF; // Sigla
      // $std->CEP     = $dadosNfe->destinatario->CEP;
      // $std->cPais   = $dadosNfe->destinatario->cPais;  // Codigo do pais 1058 para Brasil
      // $std->xPais   = $dadosNfe->destinatario->xPais; // Nome pais Brasil ou BRASIL
      // $std->fone    = $dadosNfe->destinatario->fone;
      //$elem = $nfe->tagenderDest($std);

      // Obrigatorio qdo a entrega do produto for diferente do endereco do emitente (por exemplo, venda a pronta entrega - a venda esta sendo feita em passo fundo mas a empresa do vendedor esta em erehim)
      if ($dadosNfe->destinatario->endRetirada->retirada == true) {
          $std = new \stdClass();
          $std->xLgr = $dadosNfe->destinatario->endEntrega->xLgr;
          $std->nro = $dadosNfe->destinatario->endEntrega->nro;
          $std->xCpl = $dadosNfe->destinatario->endEntrega->xCpl;
          $std->xBairro = $dadosNfe->destinatario->endEntrega->xBairro;
          $std->cMun = $dadosNfe->destinatario->endEntrega->cMun;
          $std->xMun = $dadosNfe->destinatario->endEntrega->xMun;
          $std->UF = $dadosNfe->destinatario->endEntrega->UF;
          if ($dadosNfe->destinatario->endEntrega->doctoNfe->doc == "CNPJ") {
              $std->CNPJ = $dadosNfe->destinatario->endEntrega->doctoNfe->CNPJ; //indicar um CNPJ ou CPF
          }
          else {
              $std->CPF = $dadosNfe->destinatario->endEntrega->doctoNfe->CPF;
          }
          $elem = $nfe->tagretirada($std);
      }

      // exemplo, compra da TV com o Teti.. o cadastro dele tem endereco em Santa Maria mas mandou entregar em Erechim)
      $std = new \stdClass();
      $std->xLgr = $dadosNfe->destinatario->endEntrega->xLgr;
      $std->nro = $dadosNfe->destinatario->endEntrega->nro;
      $std->xCpl = $dadosNfe->destinatario->endEntrega->xCpl;
      $std->xBairro = $dadosNfe->destinatario->endEntrega->xBairro;
      $std->cMun = $dadosNfe->destinatario->endEntrega->cMun;
      $std->xMun = $dadosNfe->destinatario->endEntrega->xMun;
      $std->UF = $dadosNfe->destinatario->endEntrega->UF;
      if ($dadosNfe->destinatario->endEntrega->doctoNfe->doc == "CNPJ") {
          $std->CNPJ = $dadosNfe->destinatario->endEntrega->doctoNfe->CNPJ; //indicar um CNPJ ou CPF
      }
      else {
          $std->CPF = $dadosNfe->destinatario->endEntrega->doctoNfe->CPF;
      }
      $elem = $nfe->tagentrega($std);
    }

    $std = new \stdClass();
    $std->CNPJ = $dadosNfe->doctoNfe->CNPJ; //indicar um CNPJ ou CPF
    $std->CPF  = $dadosNfe->doctoNfe->CPF;
    $elem = $nfe->tagautXML($std);


    foreach ($dadosNfeItens as $itemNfe) {
        $std = new \stdClass();
        $std->item = $itemNfe->item; //item da NFe (Sequencial 1-n)
        $std->cProd = $itemNfe->cProd; // Código do produto no sistema (SGAF ou eVET)
        $std->cEAN = $itemNfe->cEAN; // Código de Barras
        $std->xProd = $itemNfe->xProd; // Descricao do produto
        $std->NCM = $itemNfe->NCM; // Nomenclaru comum do mercosul
        if ($nfeLayout == "4.00") {
            $std->cBenf = $itemNfe->cBenf; //***incluido no layout 4.00
        }
        $std->EXTIPI = $itemNfe->EXTIPI; // Contador diz o uso
        $std->CFOP = $itemNfe->CFOP;
        $std->uCom = $itemNfe->uCom; // Unidade
        $std->qCom = $itemNfe->qCom; // Quantidade
        $std->vUnCom = $itemNfe->vUnCom; // Valor unitario
        $std->vProd = $itemNfe->vProd; // Valor total bruto do produto
        $std->cEANTrib = $itemNfe->cEANTrib; // Codigo barras da unidade tributavel
        $std->uTrib = $itemNfe->uTrib; // Unidade
        $std->qTrib = $itemNfe->qTrib; // Quantidade
        $std->vUnTrib = $itemNfe->vUnTrib; // Valor tributada
        $std->vFrete = $itemNfe->vFrete; // Valor frete
        $std->vSeg = $itemNfe->vSeg; // Valor do seguro
        $std->vDesc = $itemNfe->vDesc; // Valor desconto
        $std->vOutro = $itemNfe->vOutro; // Outras despesas
        $std->indTot = $itemNfe->indTot; // Indica se o valor total compoe ou nao na NFe // 0: Valor do Item não compoe o valor total da NFe / 1: Valor do item compoes o valor total da NFe
        $std->xPed = $itemNfe->xPed; // opcional numero do pedido
        $std->nItemPed = $itemNfe->nItemPed; // opcional item do pedido
        $std->nFCI = $itemNfe->nFCI; // Para importacao
        $elem = $nfe->tagprod($std);

        $std = new \stdClass();
        $std->item = $itemNfe->item; //item da NFe
        if ($itemNfe->infAdProd != null) {
          $std->infAdProd = $itemNfe->infAdProd;
          $elem = $nfe->taginfAdProd($std);
        }

        // LAYOUT 4.00
        if ($nfeLayout == "4.00") {
            $std = new \stdClass();
            $std->item = $itemNfe->item; //item da NFe
            $std->CEST = $itemNfe->CEST;
            $std->indEscala = $itemNfe->indEscala; //incluido no layout 4.00
            $std->CNPJFab = $itemNfe->CNPJFab; //incluido no layout 4.00
            $elem = $nfe->tagCEST($std);
        }

        if ($itemNfe->usaNfRecopi->Recopi == true) {
          // TEMOS OPCAO PARA GERAR TAG PARA RECOPI
        }

        if ($itemNfe->usaNfDI->DI == true) {
          // TEMOS OPCAO PARA GERAR TAG DE Declaracao de Importacao (tagDI)
        }

        if ($itemNfe->usaNfExportacao->Exportacao == true) {
          // TEMOS OPCAO PARA GERAR TAG DE EXPORTACAO
        }

        if ($nfeLayout == "4.00") {
            $std = new \stdClass();
            $std->item = $itemNfe->item; //item da NFe
            $std->nLote = $itemNfe->nLote;
            $std->qLote = $itemNfe->qLote;
            $std->dFab = $itemNfe->dFab;
            $std->dVal = $itemNfe->dVal;
            $std->cAgreg = $itemNfe->cAgreg;
            $elem = $nfe->tagRastro($std);
        }

        if ($itemNfe->usaNfVeiculo->Veiculo == true) {
          // TEMOS OPCAO PARA TAGS DE INDUSTRIALIZACAO DE VEICULOS
        }

        // VER VERSAO 4
        if (($nfeLayout == "3.10") && ($itemNfe->usaNfMedicamentos->Medicamento == true)) {
            $std = new \stdClass();
            $std->item = $itemNfe->item; //item da NFe
            $std->nLote = $itemNfe->nLote; //removido no layout 4.00
            $std->qLote = $itemNfe->qLote; //removido no layout 4.00
            $std->dFab = $itemNfe->dFab; //removido no layout 4.00
            $std->dVal = $itemNfe->dVal; //removido no layout 4.00
            $std->vPMC = $itemNfe->vPMC;
            $std->cProdANVISA = $itemNfe->cProdANVISA; //incluido no layout 4.00
            $elem = $nfe->tagmed($std);
        }

        if ($itemNfe->usaNfArmamento->Armamento == true) {
          // TEMOS OPCAO PARA TAGS DE COMERCIALIZACAO DE ARMAMENTO
        }

        if ($itemNfe->usaNfCombustivel->Combustivel == true) {
          // TEMOS OPCAO PARA TAGS DE COMERCIALIZACAO DE COMBUSTIVEL
        }

        $std = new \stdClass();
        $std->item = $itemNfe->item; //item da NFe
        $std->vTotTrib = $itemNfe->vTotTrib; // Por Item -- Valor total de tributos
        $elem = $nfe->tagimposto($std);

        if ($itemNfe->usaNfTributacaoSN->TributaSN == false) {
          $std = new \stdClass();
          $std->item = $itemNfe->item; //item da NFe

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
          $std->orig = $itemNfe->orig; // Origem da mercadoria
          $std->CST = $itemNfe->CST; //CST 00 / 10 / 20 / 30 / 40 / 41 / 50 / 51 / 60 / 70 / 90
          /*
           * modBC
           *    0=Margem Valor Agregado (%);
           *    1=Pauta (Valor);
           *    2=Preço Tabelado Máx. (valor);
           *    3=Valor da operação.
           */
          $std->modBC = $itemNfe->modBC; // Modalidade da base de calculo
          $std->vBC = $itemNfe->vBC; // Valor da base de calculo do ICMS
          $std->pICMS = $itemNfe->pICMS; // Aliquota do ICMS
          $std->vICMS = $itemNfe->vICMS; // Valor do ICMS
          $std->pFCP = $itemNfe->pFCP; // ??????
          $std->vFCP = $itemNfe->vFCP;  // ?????
          $std->vBCFCP = $itemNfe->vBCFCP; // ?????
          /*
           * modBCST - Modalidade de determinacao da BC do ICMS ST
           *    0=Preço tabelado ou máximo sugerido;
           *    1=Lista Negativa (valor);
           *    2=Lista Positiva (valor);
           *    3=Lista Neutra (valor);
           *    4=Margem Valor Agregado (%);
           *    5=Pauta (valor);
           */
          $std->modBCST = $itemNfe->modBCST;
          $std->pMVAST = $itemNfe->pMVAST; // Percentual da margem do Valor Adicionar do ICMS ST
          $std->pRedBCST = $itemNfe->pRedBCST; // Percentual de redução da BC do ICMS ST
          $std->vBCST = $itemNfe->vBCST; // Valor da BC do ICMS ST
          $std->pICMSST = $itemNfe->pICMSST; // Aliquota do importo do ICMS ST
          $std->vICMSST = $itemNfe->vICMSST; // Valor do ICMS ST
          $std->vBCFCPST = $itemNfe->vBCFCPST; // ???????
          $std->pFCPST = $itemNfe->pFCPST; // ?????
          $std->vFCPST = $itemNfe->vFCPST; // ???????
          $std->vICMSDeson = $itemNfe->vICMSDeson; // Informar nos motivos de desoneracao informadas no proximo campo
          /*
           * motDesICMS - Motivo de desoneração do ICMS
           *    Campo será preenchido quando o campo anterior estiver preenchido.
           *    Informar o motivo da desoneração:
           *      3=Uso na agropecuária;
           *      9=Outros;
           *      12=Órgão de fomento e desenvolvimento agropecuário.
           */
          $std->motDesICMS = $itemNfe->motDesICMS;
          $std->pRedBC = $itemNfe->pRedBC; // Percentual da redução da BC
          $std->vICMSOp = $itemNfe->vICMSOp; // Valor como se nao tivesse o diferimento
          $std->pDif = $itemNfe->pDif; // Percentual do diferimento - se houver diferimento total infornar 100
          $std->vICMSDif = $itemNfe->vICMSDif; // Valor do ICMS diferido
          $std->vBCSTRet = $itemNfe->vBCSTRet; // Valor da BC do ICMS ST retido
          $std->pST = $itemNfe->pST;  // ????????
          $std->vICMSSTRet = $itemNfe->vICMSSTRet; // Valor do ICMS ST retido
          $std->vBCFCPSTRet = $itemNfe->vBCFCPSTRet; //?????????
          $std->pFCPSTRet = $itemNfe->pFCPSTRet; //?????????
          $std->vFCPSTRet = $itemNfe->vFCPSTRet; //?????????

          $elem = $nfe->tagICMS($std);
        }

        if ($itemNfe->usaNfPartilhaUF->PartilhaUF == true) {
          $std = new \stdClass();
          $std->item = $itemNfe->item; //item da NFe
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
          $std->orig = $itemNfe->orig;
          /*
           * CST
           *   10=Tributada e com cobrança do ICMS por substituição tributária;
           *   90=Outros.
           */
          $std->CST = $itemNfe->CST;
          /*
           * modBC
           *    0=Margem Valor Agregado (%);
           *    1=Pauta (Valor);
           *    2=Preço Tabelado Máx. (valor);
           *    3=Valor da operação.
           */
          $std->modBC = $itemNfe->modBC;
          $std->vBC = $itemNfe->vBC;
          $std->pRedBC = $itemNfe->pRedBC; // Percentual de redução da BC
          $std->pICMS = $itemNfe->pICMS; // Aliquota ICMS
          $std->vICMS = $itemNfe->vICMS; // Valor ICMS
          /*
           * modBCST - Modalidade de determinacao da BC do ICMS ST
           *    0=Preço tabelado ou máximo sugerido;
           *    1=Lista Negativa (valor);
           *    2=Lista Positiva (valor);
           *    3=Lista Neutra (valor);
           *    4=Margem Valor Agregado (%);
           *    5=Pauta (valor);
           */
          $std->modBCST = $itemNfe->modBCST;
          $std->pMVAST = $itemNfe->pMVAST; // Percentual de margem de valor adicional do ICMS ST
          $std->pRedBCST = $itemNfe->pRedBCST; // Percentual de redução da BC do ICMS ST
          $std->vBCST = $itemNfe->pRedBCST; // Valor da BC do ICMS ST
          $std->pICMSST = $itemNfe->pICMSST; // Aliquota do importo do ICMS ST
          $std->vICMSST = $itemNfe->vICMSST; // Valor do ICMS ST
          $std->pBCOp = $itemNfe->pBCOp;  // Percentual da BC operacao propria
          $std->UFST = $itemNfe->UFST; // UF para qual é devido o ICMS ST

          $elem = $nfe->tagICMSPart($std);
        }

        if ($itemNfe->usaNfICMSRetido->RetemICMS == true) {
          $std = new \stdClass();
          $std->item = $itemNfe->item; //item da NFe
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
          $std->orig = $itemNfe->orig;
          $std->CST = $itemNfe->CST;
          $std->vBCSTRet = $itemNfe->vBCSTRet; // valor BC do ICMS ST retido na UF remetente
          $std->vICMSSTRet = $itemNfe->vICMSSTRet; // valor do ICMS ST retido na UF do rementete
          $std->vBCSTDest = $itemNfe->vBCSTDest; // Valor da BC do ICMS ST UF destino
          $std->vICMSSTDest = $itemNfe->vICMSSTDest; // Valor do ICMS ST da UF destino

          $elem = $nfe->tagICMSST($std);
        }

        if ($itemNfe->usaNfTributacaoSN->TributaSN == true) {
          $std = new \stdClass();
          $std->item = $itemNfe->item; //item da NFe
          $std->orig = $itemNfe->orig;
          $std->CSOSN = $itemNfe->CSOSN; //  102 103 300 400
          $std->pCredSN = $itemNfe->pCredSN; // ALiquota aplicavel de calculo de credito -- contador
          $std->vCredICMSSN = $itemNfe->vCredICMSSN; // Valor calculo de credito que pode ser aproveitado -- contador
          /*
           * modBCST - Modalidade de determinacao da BC do ICMS ST
           *    0=Preço tabelado ou máximo sugerido;
           *    1=Lista Negativa (valor);
           *    2=Lista Positiva (valor);
           *    3=Lista Neutra (valor);
           *    4=Margem Valor Agregado (%);
           *    5=Pauta (valor);
           */
          $std->modBCST = $itemNfe->modBCST;
          $std->pMVAST = $itemNfe->pMVAST; // Percentual de margem de valor adicional do ICMS ST
          $std->pRedBCST = $itemNfe->pRedBCST; // Percentual de redução da BC do ICMS ST
          $std->vBCST = $itemNfe->vBCST; // Valor BC do ICMS ST
          $std->pICMSST = $itemNfe->pICMSST; // Aliquota do imposto do ICMS ST
          $std->vBCSTRet = $itemNfe->vBCSTRet;
          $std->pST = $itemNfe->pST;
          $std->vICMSSTRet = $itemNfe->vICMSSTRet;
          $std->modBC = $itemNfe->modBC;
          $std->vBC = $itemNfe->vBC;
          $std->pRedBC = $itemNfe->pRedBC;
          $std->pICMS = $itemNfe->pICMS;
          $std->vICMS = $itemNfe->vICMS;
          if ($nfeLayout == "4.00") {
              $std->vBCFCPSTRet = $itemNfe->vBCFCPSTRet; //incluso no layout 4.00
              $std->pFCPSTRet = $itemNfe->pFCPSTRet; //incluso no layout 4.00
              $std->vFCPSTRet = $itemNfe->vFCPSTRet; //incluso no layout 4.00
              $std->vBCFCPST = $itemNfe->vBCFCPST; //incluso no layout 4.00 ???
              $std->pFCPST = $itemNfe->pFCPST; //incluso no layout 4.00 ???
              $std->vFCPST = $itemNfe->vFCPST; //incluso no layout 4.00 ???
          }

          $elem = $nfe->tagICMSSN($std); // ACHO Q N VAMOS USAR ESSA TAG.. POIS BEM, ACHO Q SIM
        }

        if ($itemNfe->usaNfICMSInterestadual->ICMSInter == true) {
          $std = new \stdClass();
          $std->item = $itemNfe->item; //item da NFe
          $std->vBCUFDest = $itemNfe->vBCUFDest; // Valor da BC do ICMS na UF destino
          $std->vBCFCPUFDest = $itemNfe->vBCFCPUFDest; //
          $std->pFCPUFDest = $itemNfe->pFCPUFDest; // Percentual o ICMS relativo ao fundo de combate a pobreza
          $std->pICMSUFDest = $itemNfe->pICMSUFDest; // Aliquota do ICMS  do UF destino
          /*
           * pICMSInter
           *    Alíquota interestadual das UF envolvidas:
           *      - 4% alíquota interestadual para produtos importados;
           *      - 7% para os Estados de origem do Sul e Sudeste (exceto ES), destinado para os Estados do Norte, Nordeste, Centro-Oeste e Espírito Santo;
           *      - 12% para os demais casos.
           */
          $std->pICMSInter = $itemNfe->pICMSInter; // Aliquota interestadual das UFs envolvidas
          /*
           * pIMCSInterPart
           *    Percentual de ICMS Interestadual para a UF de destino:
           *      - 40% em 2016;
           *      - 60% em 2017;
           *      - 80% em 2018;
           *      - 100% a partir de 2019.
           */
          $std->pICMSInterPart = $itemNfe->pICMSInterPart; // Percentual provisório de partilhsa do ICMS interestadual
          $std->vFCPUFDest = $itemNfe->vFCPUFDest; // Valor do ICMS relativo ao fundo de combate a pobreza
          $std->vICMSUFDest = $itemNfe->vICMSUFDest; // Valor do ICMS interestadual para a UF destino
          $std->vICMSUFRemet = $itemNfe->vICMSUFRemet; // Valor do ICMS interestadual para a UF destino

          $elem = $nfe->tagICMSUFDest($std);
        }

        if ($itemNfe->usaNfIPI->IPI == true) {
          // TEMOS OPCAO PARA TAGS DE IPI
        }

        if ($itemNfe->usaNfII->ImpostoImportacao == true) {
          // TEMOS OPCAO PARA TAGS DE II (Imposto sobre Importação)
        }

        if ($itemNfe->usaNfPIS->PIS == true) {
          $std = new \stdClass();
          $std->item = $itemNfe->item; // item da NFe
          /*
           * CST --- PISNT (Nao tributado)
           *    04=Operação Tributável (tributação monofásica (alíquota zero));
           *    05=Operação Tributável (Substituição Tributária);
           *    06=Operação Tributável (alíquota zero);
           *    07=Operação Isenta da Contribuição;
           *    08=Operação Sem Incidência da Contribuição;
           *    09=Operação com Suspensão da Contribuição;
           */
          $std->CST = $itemNfe->CST; // 07 PADRAO
          $std->vBC = $itemNfe->vBC;
          $std->pPIS = $itemNfe->pPIS; // Percentual de PIS
          $std->vPIS = $itemNfe->vPIS; // Valor de PIS
          $std->qBCProd = $itemNfe->qBCProd;
          $std->vAliqProd = $itemNfe->vAliqProd;
          $elem = $nfe->tagPIS($std);
        }

        if ($itemNfe->usaNfPISST->pisST == true) {
          // TEMOS OPCAO PARA TAGS PISST
        }

        if ($itemNfe->usaNfTributacaoCOFINS->cofins == true) {
          $std = new \stdClass();
          $std->item = $itemNfe->item; //item da NFe
          /*
           * CST --- PISNT (Nao tributado)
           *    04=Operação Tributável (tributação monofásica (alíquota zero));
           *    05=Operação Tributável (Substituição Tributária);
           *    06=Operação Tributável (alíquota zero);
           *    07=Operação Isenta da Contribuição;
           *    08=Operação Sem Incidência da Contribuição;
           *    09=Operação com Suspensão da Contribuição;
           */
          $std->CST = $itemNfe->CST;
          $std->vBC = $itemNfe->vBC;
          $std->pCOFINS = $itemNfe->pCOFINS;
          $std->vCOFINS = $itemNfe->vCOFINS;
          $std->qBCProd = $itemNfe->qBCProd;
          $std->vAliqProd = $itemNfe->vAliqProd;
          $elem = $nfe->tagCOFINS($std);
        }

        if ($itemNfe->usaNfCOFINSST->cofinsST == true) {
          // TEMOS OPCAO PARA TAGS COFINSST
        }

        if ($itemNfe->usaNfISSQN->ISSQN == true) {
          // TEMOS OPCAO PARA TAGS ISSQN
        }

        if ($itemNfe->usaNfDevolucao->Devolucao == true) {
          // TEMOS OPCAO PARA Imposto Devolvido
            $std = new \stdClass();
            $std->item = $itemNfe->item; //item da NFe
            $std->pDevol = $itemNfe->pDevol;
            $std->vIPIDevol = $itemNfe->vIPIDevol;

            $elem = $nfe->tagimpostoDevol($std);
        }
    }

    $std = new \stdClass();
    $std->vBC = $dadosNfe->vBC; // Valor da base de caclulo do ICMS
    $std->vICMS = $dadosNfe->vICMS; // Valor total do ICMS
    $std->vICMSDesonv = $dadosNfe->vICMSDesonv; // Valor total do ICMS desonerado
    $std->vBCST = $dadosNfe->vBCST; // BC do ICMS ST
    $std->vST = $dadosNfe->vST; // Valor do ICMS ST
    $std->vProd = $dadosNfe->vProd; // Valor total de produitos
    $std->vFrete = $dadosNfe->vFrete; // Valor total de frete
    $std->vSeg = $dadosNfe->vSeg; // Valor total de seguro
    $std->vDesc = $dadosNfe->vDesc; // Total de desconto
    $std->vII = $dadosNfe->vII; // Imposto sob importacao
    $std->vIPI = $dadosNfe->vIPI; // IPI total
    $std->vPIS = $dadosNfe->vPIS; // Total PIS
    $std->vCOFINS = $dadosNfe->vCOFINS; // Total cofins
    $std->vOutro = $dadosNfe->vOutro; // Total outros
    $std->vNF = $dadosNfe->vNF; // Valor total da NF
    $std->vTotTrib = $dadosNfe->vTotTrib; // Valor total tributos
    if ($nfeLayout == "4.00") {
      $std->vFCP = $dadosNfe->vFCP; //incluso no layout 4.00
      $std->vFCPST = $dadosNfe->vFCPST; //incluso no layout 4.00
      $std->vFCPSTRet = $dadosNfe->vFCPSTRet; //incluso no layout 4.00
      $std->vIPIDevol = $dadosNfe->vIPIDevol; //incluso no layout 4.00
    }

    $elem = $nfe->tagICMSTot($std);

    if ($dadosNfe->usaNfISSQN->ISSQN == true) {
      // TEMOS OPCAO PARA TAGS TOTAL DO ISSQN
    }

    if ($dadosNfe->usaNfRetTributos->RetTributos == true) {
      // TEMOS OPCAO PARA TAGS DE RETENCAO DE TRIBUTOS
    }

    $std = new \stdClass();
    /*
     * modFrete
     *   0=Por conta do emitente;
     *   1=Por conta do destinatário/remetente;
     *   2=Por conta de terceiros;
     *   9=Sem frete. (V2.0)
     */
    $std->modFrete = $dadosNfe->modFrete;
    $elem = $nfe->tagtransp($std);

    if ($dadosNfe->usaNfTransportadora->Transportadora == true) {
        $std = new \stdClass(); // DADOS DA TRANSPORTADORA
        $std->xNome = $dadosNfe->usaNfTransportadora->xNome;
        $std->IE = $dadosNfe->usaNfTransportadora->IE;
        $std->xEnder = $dadosNfe->usaNfTransportadora->xEnder;
        $std->xMun = $dadosNfe->usaNfTransportadora->xMun;
        $std->UF = $dadosNfe->usaNfTransportadora->UF;
        $std->CNPJ = $dadosNfe->usaNfTransportadora->CNPJ;//só pode haver um ou CNPJ ou CPF, se um deles é especificado o outro deverá ser null
        $std->CPF = $dadosNfe->usaNfTransportadora->CPF;

        $elem = $nfe->tagtransporta($std);
    }

    if ($dadosNfe->usaNfDetalheVeicTransportadora->Veiculo == true) {
      // TEMOS OPCAO PARA TAGS DE DESCRICAO DO VEICULO DE TRANSPORTE
    }

    if ($dadosNfe->usaNfReboqueVeicTransportadora->Reboque == true) {
      // TEMOS OPCAO PARA TAGS DE DESCRICAO DO REBOQUE DO VEICULO DE TRANSPORTE
    }

    if ($dadosNfe->usaNfTransportadora->usaNfVolumeTransportadora->Volume == true) {
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

    if ($dadosNfe->usaNfDadosFatura->Fatura == true) {
        $std = new \stdClass();
        $std->nFat = $dadosNfe->usaNfDadosFatura->nFat;
        $std->vOrig = $dadosNfe->usaNfDadosFatura->vOrig;
        $std->vDesc = $dadosNfe->usaNfDadosFatura->vDesc;
        $std->vLiq = $dadosNfe->usaNfDadosFatura->vLiq;

        $elem = $nfe->tagfat($std);
    }

    if ($dadosNfe->usaNfDadosDuplicata->Duplicata == true) {
        $std = new \stdClass();
        $std->nDup = $dadosNfe->usaNfDadosDuplicata->nDup;
        $std->dVenc = $dadosNfe->usaNfDadosDuplicata->dVenc;
        $std->vDup = $dadosNfe->usaNfDadosDuplicata->vDup;

        $elem = $nfe->tagdup($std);
    }

    $std = new \stdClass();
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
    $std->tPag = $dadosNfe->tPag;
    $std->vPag = $dadosNfe->vPag; //Obs: deve ser o informado o valor total da Nota Fiscal (vPag = vNF), caso contrário a a SEFAZ irá retornar "Rejeição 767"
    // if ($dadosNfe->usaNfeCartaoCredito->Cartao == true) {
      $std->CNPJ = $dadosNfe->usaNfeCartaoCredito->CNPJ; // Informar CNPJ da credenciadora do cartao de credito se tiver
      /*
       * tBand -- Bandeira da operadora de cartao de credito ou débito
       *    01-Visa
       *    02=Mastercard
       *    03=American Express
       *    04=Sorocred
       *    99=Outros
       */
      $std->tBand = $dadosNfe->usaNfeCartaoCredito->tBand;
      $std->cAut = $dadosNfe->usaNfeCartaoCredito->cAut; // Identifiva o numero da autorizacao da transacao da operacao com cartao
    // }
    /*
     * tpIntegra -- Tipo de integracao para pagamento
     *      Tipo de Integração do processo de pagamento com o sistema de automação da empresa:
     *         1=Pagamento integrado com o sistema de automação da empresa (Ex.: equipamento TEF, Comércio Eletrônico);
     *         2= Pagamento não integrado com o sistema de automação da empresa (Ex.: equipamento POS);
     */
    $std->tpIntegra = $dadosNfe->tpIntegra; // Incluso na NT 2015/002

    if ($nfeLayout == "4.00") {
        $std->vTroco = $dadosNfe->vTroco; //incluso no layout 4.00
    }

    $elem = $nfe->tagpag($std);

    if ($nfeLayout == "4.00") {
        $std = new \stdClass();
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
        $std->tPag = $dadosNfe->tPag;
        $std->vPag = $dadosNfe->vPag; //Obs: deve ser informado o valor pago pelo cliente

        if ($dadosNfe->usaNfeCartaoCredito->Cartao == true) {
          $std->CNPJ = $dadosNfe->usaNfeCartaoCredito->CNPJ;
          $std->tBand = $dadosNfe->usaNfeCartaoCredito->tBand;
          $std->cAut = $dadosNfe->usaNfeCartaoCredito->cAut;
        }

        $std->tpIntegra = $dadosNfe->tpIntegra; //incluso na NT 2015/002

        $elem = $nfe->tagdetPag($std);
    }


    $std = new \stdClass();
    $std->infAdFisco = $dadosNfe->infAdFisco;
    $std->infCpl = $dadosNfe->infCpl;

    $elem = $nfe->taginfAdic($std);

    if ($dadosNfe->usaNfExportacao->Exportacao == true) {
        // $std = new stdClass();
        // $std->UFSaidaPais = 'PR';
        // $std->xLocExporta = 'Paranagua';
        // $std->xLocDespacho = 'Informação do Recinto Alfandegado';
        //
        // $elem = $nfe->tagexporta($std);
    }

    $result = $nfe->montaNFe();
    $this->nfGerada = $nfe;
  }
  
  public function geraXML(){
    $this->tools->model('65');
    $nfe = $this->nfGerada;
    $xml = $nfe->getXML();
    $chave = $nfe->getChave();
    $modelo = $nfe->getModelo();
  
    try {
        $xml = $this->tools->signNFe($xml);
    
        $idLote = str_pad($this->dadosNfe->nNF, 15, '0', STR_PAD_LEFT);
        //envia o xml para pedir autorização ao SEFAZ
        $resp = $this->tools->sefazEnviaLote([$xml], $idLote);
        //transforma o xml de retorno em um stdClass
        $st = new Standardize();
        $std = $st->toStd($resp);
        if ($std->cStat != 103) {
            //erro registrar e voltar
            return "[$std->cStat] $std->xMotivo";
        }
        $recibo = $std->infRec->nRec;

        $resp = $this->tools->sefazConsultaRecibo($recibo, 2); // segundo parametro é 1 (producao) ou 2 (homologacao)

        $protocoledXML = Complements::toAuthorize($xml, $resp);

        if ($this->dadosNfe->mod == 65)
          $arq = 'NFCe'.$this->dadosNfe->nNF;
        else
          $arq = 'NFe'.$this->dadosNfe->nNF;

          $dom = new \DomDocument('1.0', 'UTF-8');
          $dom->loadXML($protocoledXML);
          $dom->saveXML();

          $dom->save($arq.'.xml');

          $retorno = array('chave'=>$chave, 'xml'=>$protocoledXML);
          return $retorno;

    } catch (\Exception $e) {
            $errors = array('error' => $e->getMessage());
            return $errors;
    }
  }

  public function enviaEmail() {

  }

  public function consultaNFe($chave) {
    $this->tools->model('65');

    //$chave = '43180121996226000164650000000002541000002541';

    $response = $this->tools->sefazConsultaChave($chave);
    echo "Resultado da consulta: <br>"; 
    //você pode padronizar os dados de retorno atraves da classe abaixo
    //de forma a facilitar a extração dos dados do XML
    //NOTA: mas lembre-se que esse XML muitas vezes será necessário, 
    //      quando houver a necessidade de protocolos
    $stdCl = new Standardize($response);
    //nesse caso $std irá conter uma representação em stdClass do XML
    $std = $stdCl->toStd();
    //nesse caso o $arr irá conter uma representação em array do XML
    $arr = $stdCl->toArray();
    //nesse caso o $json irá conter uma representação em JSON do XML
    $json = $stdCl->toJson();

    echo $arr["xMotivo"]; die();
    //echo $json; die();

  }

  public function inutiliza($serie, $inicio, $fim, $justificativa, $tpAmb) {
    $this->tools->model('65');
    $response = $this->tools->sefazInutiliza(
                    $serie,
                    $inicio,
                    $fim,
                    $justificativa,
                    $tpAmb
                  );
            $stdCl = new Standardize($response);
            $arr = $stdCl->toArray();
            return $arr;
  }

  public function geraPDF($NumNFe) {
    $this->tools->model('65');

    // if (6 == 55) { // DANFE
    //         $arq = 'NFe'.$NumNFe;
    //   $docxml = FilesFolders::readFile($arq.'.xml');
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
    // else { // DANFCE
      try {
        $arq = 'NFCe'.$NumNFe;
        $docxml = FilesFolders::readFile($arq.'.xml');
        //$pathLogo = realpath(__DIR__.'/include/nanda.jpeg');//use somente imagens JPEG
        $pathLogo = null;
        $danfce = new Danfce($docxml, $pathLogo, 0);
        $id = $danfce->monta();
        $pdf = $danfce->render();
        header('Content-Type: application/pdf');
        echo $pdf;
      } catch (InvalidArgumentException $e) {
         echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
      }
    //}
  }
}



/**
 * Mecanismo de Template para PHP5
 * 
 * Mecanismos de Template permitem manter o código HTML em arquivos externos
 * que ficam completamente livres de código PHP. Dessa forma, consegue-se manter 
 * a lógica de programação (PHP) separada da estrutura visual (HTML ou XML, CSS, etc).
 *
 * Se você já é familiar ao uso de mecanismos de template PHP, esta classe inclui 
 * algumas melhorias: suporte à objetos, automaticamente detecta blocos, mantém uma 
 * lista interna das variáveis que existem, limpa automaticamente blocos "filhos", 
 * avisando quando tentamos chamar blocos ou variáveis que são existem, avisando quando 
 * criamos blocos "mal formados", e outras pequenas ajudas.
 * 
 * @author Rael G.C. (rael.gc@gmail.com)
 * @version 1.9
 */
class Template {

  /**
   * A list of existent document variables.
   *
   * @var       array
   */
  private $vars = array();
  
  /**
   * A hash with vars and values setted by the user.
   *
   * @var       array
   */
  private $values = array();
  
  /**
   * A hash of existent object properties variables in the document.
   *
   * @var       array
   */
  private $properties = array();
  
  /**
   * A hash of the object instances setted by the user.
   *
   * @var       array
   */
  private $instances = array();
  
  /**
   * A list of all automatic recognized blocks.
   *
   * @var       array
   */
  private $blocks = array();
  
  /**
   * A list of all blocks that contains at least a "child" block.
   *
   * @var       array
   */
  private $parents = array();
  
  /**
   * Describes the replace method for blocks. See the Template::setFile() 
   * method for more details.
   *
   * @var       boolean
   */
  private $accurate;
  
  /**
   * Regular expression to find var and block names. 
   * Only alfa-numeric chars and the underscore char are allowed.
   *
   * @var   string
   */
  private static $REG_NAME = "([[:alnum:]]|_)+";
  
  /**
   * Cria um novo template, usando $filename como arquivo principal
   * 
   * Quando o parâmetro $accurate é true, a substituição dos blocos no arquivo   
   * final será perfeitamente fiel ao arquivo original, isto é, todas as tabulações 
   * serão removidas. Isso vai ter um pequeno prejuízo na performance, que pode variar 
   * de acordo com a versão do PHP em uso. Mas é útil quando estamos usando tags HTML 
   * como &lt;pre&gt; ou &lt;code&gt;. Em outros casos, ou melhor, quase sempre, 
   * nunca se mexe no valor de $accurate.
   *
   * @param     string $filename    caminho do arquivo que será lido
   * @param     booelan $accurate   true para fazer substituição fiel das tabulações
   */
  public function __construct($filename, $accurate = false){
    $this->accurate = $accurate;
    $this->loadfile(".", $filename);
  }
  
  /**
   * Adiciona o conteúdo do arquivo identificado por $filename na variável de template 
   * identificada por $varname
   *
   * @param     string $varname   uma variável de template existente
   * @param     string $filename    arquivo a ser carregado
   */
  public function addFile($varname, $filename){
    if(!$this->exists($varname)) throw new InvalidArgumentException("addFile: var $varname não existe");
    $this->loadfile($varname, $filename);
  }
  
  /**
   * Não use este método, ele serve apenas para podemos acessar as variáveis 
   * de template diretamente.
   *
   * @param string  $varname  template var name
   * @param mixed $value    template var value
   */
  public function __set($varname, $value){
    if(!$this->exists($varname)) throw new RuntimeException("var $varname não existe");
    $stringValue = $value;
    if(is_object($value)){
      $this->instances[$varname] = $value;
      if(!array_key_exists($varname, $this->properties)) $this->properties[$varname] = array();
      if(method_exists($value, "__toString")) $stringValue = $value->__toString();
      else $stringValue = "Object";
    } 
    $this->setValue($varname, $stringValue);
    return $value;
  }
  
  /**
   * Não use este método, ele serve apenas para podemos acessar as variáveis 
   * de template diretamente.
   *
   * @param string  $varname  template var name
   */
  public function __get($varname){
    if (isset($this->values["{".$varname."}"])) return $this->values["{".$varname."}"];
    throw new RuntimeException("var $varname não existe");
  }

  /**
   * Verifica se uma variável de template existe.
   *
   * Retorna true se a variável existe. Caso contrário, retorna false.
   *
   * @param string  $varname  template var name
   */
  public function exists($varname){
    return in_array($varname, $this->vars);
  }

  /**
   * Loads a file identified by $filename.
   * 
   * The file will be loaded and the file's contents will be assigned as the 
   * variable's value.
   * Additionally, this method call Template::recognize() that identifies 
   * all blocks and variables automatically.
   *
   * @param     string $varname   contains the name of a variable to load
   * @param     string $filename    file name to be loaded
   * 
   * @return    void
   */
  private function loadfile($varname, $filename) {
    if (!file_exists($filename)) throw new InvalidArgumentException("arquivo $filename não existe");
    // Reading file and hiding comments
    $str = preg_replace("/<!---.*?--->/smi", "", file_get_contents($filename));
    $blocks = $this->recognize($str, $varname);
    if (empty($str)) throw new InvalidArgumentException("arquivo $filename está vazio");
    $this->setValue($varname, $str);
    $this->createBlocks($blocks);
  }
  
  /**
   * Identify all blocks and variables automatically and return them.
   * 
   * All variables and blocks are already identified at the moment when 
   * user calls Template::setFile(). This method calls Template::identifyVars() 
   * and Template::identifyBlocks() methods to do the job.
   *
   * @param     string  $content    file content
   * @param     string  $varname    contains the variable name of the file
   * 
   * @return    array   an array where the key is the block name and the value is an 
   *            array with the children block names.
   */
  private function recognize(&$content, $varname){
    $blocks = array();
    $queued_blocks = array();
    foreach (explode("\n", $content) as $line) {
      if (strpos($line, "{")!==false) $this->identifyVars($line);
      if (strpos($line, "<!--")!==false) $this->identifyBlocks($line, $varname, $queued_blocks, $blocks);
    }
    return $blocks;
  }

  /**
   * Identify all user defined blocks automatically.
   *
   * @param     string $line        contains one line of the content file
   * @param     string $varname     contains the filename variable identifier
   * @param     string $queued_blocks   contains a list of the current queued blocks
   * @param     string $blocks      contains a list of all identified blocks in the current file
   * 
   * @return    void
   */
  private function identifyBlocks(&$line, $varname, &$queued_blocks, &$blocks){
    $reg = "/<!--\s*BEGIN\s+(".self::$REG_NAME.")\s*-->/sm";
    preg_match($reg, $line, $m);
    if (1==preg_match($reg, $line, $m)){
      if (0==sizeof($queued_blocks)) $parent = $varname;
      else $parent = end($queued_blocks);
      if (!isset($blocks[$parent])){
        $blocks[$parent] = array();
      }
      $blocks[$parent][] = $m[1];
      $queued_blocks[] = $m[1];
    }
    $reg = "/<!--\s*END\s+(".self::$REG_NAME.")\s*-->/sm";
    if (1==preg_match($reg, $line)) array_pop($queued_blocks);
  }
  
  /**
   * Identifies all variables defined in the document.
   *
   * @param     string $line        contains one line of the content file
   */
  private function identifyVars(&$line){
    $r = preg_match_all("/{(".self::$REG_NAME.")((\-\>(".self::$REG_NAME."))*)?}/", $line, $m);
    if ($r){
      for($i=0; $i<$r; $i++){
        // Object var detected
        if($m[3][$i] && (!array_key_exists($m[1][$i], $this->properties) || !in_array($m[3][$i], $this->properties[$m[1][$i]]))){
          $this->properties[$m[1][$i]][] = $m[3][$i];
        }
        if(!in_array($m[1][$i], $this->vars)) $this->vars[] = $m[1][$i];
      }
    }
  }
  
  /**
   * Create all identified blocks given by Template::identifyBlocks().
   *
   * @param     array $blocks   contains all identified block names
   * @return    void
   */
  private function createBlocks(&$blocks) {
    $this->parents = array_merge($this->parents, $blocks);
    foreach($blocks as $parent => $block){
      foreach($block as $chield){
        if(in_array($chield, $this->blocks)) throw new UnexpectedValueException("bloco duplicado: $chield");
        $this->blocks[] = $chield;
        $this->setBlock($parent, $chield);
      }
    }
  }
  
  /**
   * A variable $parent may contain a variable block defined by:
   * &lt;!-- BEGIN $varname --&gt; content &lt;!-- END $varname --&gt;. 
   * 
   * This method removes that block from $parent and replaces it with a variable 
   * reference named $block. The block is inserted into the varKeys and varValues 
   * hashes. 
   * Blocks may be nested.
   *
   * @param     string $parent  contains the name of the parent variable
   * @param     string $block   contains the name of the block to be replaced
   * @return    void
   */
  private function setBlock($parent, $block) {
    $name = "B_".$block;
    $str = $this->getVar($parent);
    if($this->accurate){
      $str = str_replace("\r\n", "\n", $str);
      $reg = "/\t*<!--\s*BEGIN\s+$block\s+-->\n*(\s*.*?\n?)\t*<!--\s+END\s+$block\s*-->\n?/sm";
    } 
    else $reg = "/<!--\s*BEGIN\s+$block\s+-->\s*(\s*.*?\s*)<!--\s+END\s+$block\s*-->\s*/sm";
    if(1!==preg_match($reg, $str, $m)) throw new UnexpectedValueException("bloco $block está mal formado");
    $this->setValue($name, '');
    $this->setValue($block, $m[1]);
    $this->setValue($parent, preg_replace($reg, "{".$name."}", $str));
  }

  /**
   * Internal setValue() method.
   *
   * The main difference between this and Template::__set() method is this 
   * method cannot be called by the user, and can be called using variables or 
   * blocks as parameters.
   *
   * @param     string $varname   constains a varname
   * @param     string $value        constains the new value for the variable
   * @return    void
   */
  private function setValue($varname, $value) {
    $this->values["{".$varname."}"] = $value;
  }
  
  /**
   * Returns the value of the variable identified by $varname.
   *
   * @param     string  $varname  the name of the variable to get the value of
   * @return    string  the value of the variable passed as argument
   */
  private function getVar($varname) {
    return $this->values['{'.$varname.'}'];
  }
  
  /**
   * Limpa o valor de uma variável
   * 
   * O mesmo que $this->setValue($varname, "");
   *
   * @param     string $varname nome da variável
   */
  public function clear($varname) {
    $this->setValue($varname, "");
  }
  
  /**
   * Fill in all the variables contained within the variable named
   * $varname. The resulting value is returned as the function result and the
   * original value of the variable varname is not changed. The resulting string
   * is not "finished", that is, the unresolved variable name policy has not been
   * applied yet.
   *
   * @param     string  $varname      the name of the variable within which variables are to be substituted
   * @return    string  the value of the variable $varname with all variables substituted.
   */
  private function subst($varname) {
    $s = $this->getVar($varname);
    // Common variables replacement
    $s = str_replace(array_keys($this->values), $this->values, $s);
    // Object variables replacement
    foreach($this->instances as $var => $instance){
      foreach($this->properties[$var] as $properties){
        if(false!==strpos($s, "{".$var.$properties."}")){
          $pointer = $instance;
          $property = explode("->", $properties);
          for($i = 1; $i < sizeof($property); $i++){
            $obj = str_replace('_', '', $property[$i]);
            // Non boolean accessor
            if(method_exists($pointer, "get$obj")){
              $pointer = $pointer->{"get$obj"}();
            }
            // Boolean accessor
            elseif(method_exists($pointer, "is$obj")){
              $pointer = $pointer->{"is$obj"}();
            }
            // Magic __get accessor
            elseif(method_exists($pointer, "__get")){
              $pointer = $pointer->__get($property[$i]);
            }
            // Accessor dot not exists: throw Exception
            else {
              $className = $property[$i-1] ? $property[$i-1] : get_class($instance);
              $class = is_null($pointer) ? "NULL" : get_class($pointer);
              throw new BadMethodCallException("não existe método na classe ".$class." para acessar ".$className."->".$property[$i]);
            }
          }
          // Checking if final value is an object
          if(is_object($pointer)){
            if(method_exists($pointer, "__toString")){
              $pointer = $pointer->__toString();
            } else {
              $pointer = "Object";
            }
          }
          // Replace
          $s = str_replace("{".$var.$properties."}", $pointer, $s);
        }
      }
    }
    return $s;
  }
  
  /**
   * Clear all child blocks of a given block.
   *
   * @param     string $block a block with chield blocks.
   */
  private function clearBlocks($block) {
    if (isset($this->parents[$block])){
      $chields = $this->parents[$block];
      foreach($chields as $chield){
        $this->clear("B_".$chield);
      }
    }
  }
  
  /**
   * Mostra um bloco.
   * 
   * Esse método deve ser chamado quando um bloco deve ser mostrado.
   * Sem isso, o bloco não irá aparecer no conteúdo final.
   *
   * Se o parâmetro $append for true, o conteúdo do bloco será 
   * adicionado ao conteúdo que já existia antes. Ou seja, use true 
   * quando quiser que o bloco seja duplicado.
   *
   * @param     string $block   nome do bloco que deve ser mostrado
   * @param     boolean $append   true se o conteúdo anterior deve ser mantido (ou seja, para duplicar o bloco)
   */
  public function block($block, $append = true) {
    if(!in_array($block, $this->blocks)) throw new InvalidArgumentException("bloco $block não existe");
    if ($append) $this->setValue("B_".$block, $this->getVar("B_".$block) . $this->subst($block));
    else $this->setValue("B_".$block, $this->subst($block));
    $this->clearBlocks($block);
  }
  
  /**
  * Retorna o conteúdo final, sem mostrá-lo na tela. 
  * Se você quer mostrá-lo na tela, use o método Template::show().
  * 
  * @return    string 
  */
  public function parse() {
    // After subst, remove empty vars
    return preg_replace("/{(".self::$REG_NAME.")((\-\>(".self::$REG_NAME."))*)?}/", "", $this->subst("."));
  }

  /**
   * Mostra na tela o conteúdo final.
   */
  public function show() {
    echo $this->parse();
  }
    
}
?>

