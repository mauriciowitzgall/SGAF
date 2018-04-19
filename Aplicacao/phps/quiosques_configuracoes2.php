<?php

//Verifica se o usuário tem permissão para acessar este conteúdo
$tipopagina="quiosque_configuracao";
require "login_verifica.php";
include "includes.php";

//print_r($_REQUEST);

$quiosque = $_POST['quiosque'];
$usamodulofiscal = $_POST['usamodulofiscal'];
$crtnfe = $_POST['crtnfe'];
$cstnfe = $_POST['cstnfe'];
$csosnnfe = $_POST['csosnnfe'];
$serienfe = $_POST['serienfe'];
$csctoken = $_POST['csctoken'];
$csctokenid = $_POST['csctokenid'];
$tipoimpressaodanfe = $_POST['tipoimpressaodanfe'];
$ambientenfe = $_POST['ambientenfe'];
$versaonfe = $_POST['versaonfe'];
$razaosocial = $_POST['razaosocial'];
$tipopessoanfe = $_POST['tipopessoanfe'];
$usacomanda = $_POST['usacomanda'];
$identificacaoconsumidorvenda = $_POST['identificacaoconsumidorvenda'];
$comandaduplicada = $_POST['comandaduplicada'];
$controlavalidade = $_POST['controlavalidade'];
$valorvendazero = $_POST['valorvendazero'];
$obsnavenda = $_POST['obsnavenda'];
$obsnaentrada = $_POST['obsnaentrada'];
$fazentregas = $_POST['fazentregas'];
$usaprateleira = $_POST['usaprateleira'];
$gerirestoqueideal = $_POST['gerirestoqueideal'];
$geririmobilizado = $_POST['geririmobilizado'];
$vendasareceber = $_POST['vendasareceber'];
$usacaixa = $_POST['usacaixa'];
$usaean = $_POST['usaean'];
$usacodigobarrasinterno = $_POST['usacodigobarrasinterno'];
$usavendas = $_POST['usavendas'];
$usaproducao = $_POST['usaproducao'];
$ignorarlotes = $_POST['ignorarlotes'];
$usaestoque = $_POST['usaestoque'];
$multicaixas = $_POST['multicaixas'];
$fazfechamentos = $_POST['fazfechamentos'];
$fazfrete = $_POST['fazfrete'];
$fazacertos = $_POST['fazacertos'];
$usavendaporcoes = $_POST['usavendaporcoes'];
$classificacaopadraoestoque = $_POST['classificacaopadraoestoque'];
$devolucoessobrevendas = $_POST['devolucoessobrevendas'];
$pagamentosparciais = $_POST['pagamentosparciais'];
$permiteedicaoreferencianavenda = $_POST['permiteedicaoreferencianavenda'];

    
$cpf = $_POST['cpf'];
$cpf = str_replace(".","", $cpf);
$cpf = str_replace("-","", $cpf);
$cnpj = $_POST['cnpj'];
$cnpj = str_replace(".","", $cnpj);
$cnpj = str_replace("/","", $cnpj);
$cnpj = str_replace("-","", $cnpj);
$ie = $_POST['ie'];
$ie = str_replace(".","", $ie);
$ie = str_replace("/","", $ie);
$ie = str_replace("-","", $ie);
$im = $_POST['im'];
$im = str_replace(".","", $im);
$im = str_replace("/","", $im);
$im = str_replace("-","", $im);

$endereco = $_POST['endereco'];
$endereco_numero = $_POST['endereco_numero'];
$bairro = $_POST['bairro'];
$cep = $_POST['cep'];


if ($usuario_grupo==1) {
    $ultimanfe = $_POST['ultimanfe'];
    $complemento.=" , quicnf_ultimanfe='$ultimanfe'"; 
}



//Template de Título e Sub-título
$tpl_titulo = new Template("templates/titulos.html");
$tpl_titulo->TITULO = "QUIOSQUE";
$tpl_titulo->SUBTITULO = "CONFIGURACOES";
$tpl_titulo->ICONES_CAMINHO = "$icones";
$tpl_titulo->NOME_ARQUIVO_ICONE = "quiosques_configuracoes.png";
$tpl_titulo->show();


//OPERAÇÕES
//Estrutura da notificação
$tpl_notificacao = new Template("templates/notificacao.html");
$tpl_notificacao->ICONES = $icones;
$tpl_notificacao->DESTINO = "quiosques_configuracoes.php";

 $sql = "
UPDATE
    quiosques_configuracoes
SET            
    quicnf_usamodulofiscal='$usamodulofiscal',
    quicnf_crtnfe='$crtnfe',
    quicnf_cstnfe='$cstnfe',
    quicnf_csosnnfe='$csosnnfe',
    quicnf_serienfe='$serienfe',
    quicnf_csctoken='$csctoken',
    quicnf_csctokenid='$csctokenid',
    quicnf_tipoimpressaodanfe='$tipoimpressaodanfe',
    quicnf_ambientenfe='$ambientenfe',
    quicnf_usacomanda='$usacomanda',
    quicnf_identificacaoconsumidorvenda='$identificacaoconsumidorvenda',
    quicnf_comandaduplicada='$comandaduplicada',
    quicnf_usaprateleira='$usaprateleira',
    quicnf_controlavalidade='$controlavalidade',
    quicnf_valorvendazero='$valorvendazero',
    quicnf_obsnavenda='$obsnavenda',
    quicnf_obsnaentrada='$obsnaentrada',
    quicnf_fazentregas='$fazentregas',
    quicnf_gerirestoqueideal='$gerirestoqueideal',
    quicnf_geririmobilizado='$geririmobilizado',
    quicnf_vendasareceber='$vendasareceber',
    quicnf_usaean='$usaean',
    quicnf_usacodigobarrasinterno='$usacodigobarrasinterno',
    quicnf_ignorarlotes='$ignorarlotes',
    quicnf_classificacaopadraoestoque='$classificacaopadraoestoque',
    quicnf_devolucoessobrevendas='$devolucoessobrevendas',
    quicnf_pagamentosparciais='$pagamentosparciais',
    quicnf_permiteedicaoreferencianavenda=$permiteedicaoreferencianavenda,
    quicnf_versaonfe='$versaonfe',
    quicnf_usamodulocaixa='$usacaixa',
    quicnf_usamodulovendas='$usavendas',
    quicnf_usamoduloproducao='$usaproducao',
    quicnf_usamoduloestoque='$usaestoque',
    quicnf_multicaixas='$multicaixas',
    quicnf_fazfechamentos='$fazfechamentos',
    quicnf_fazfrete='$fazfrete',
    quicnf_fazacertos='$fazacertos',
    quicnf_usavendaporcoes='$usavendaporcoes'
    $complemento    
WHERE
    quicnf_quiosque=$quiosque
";
if (!mysql_query($sql)) die("Erro SQL quiosques_configuracoes: " . mysql_error());

$sql2 = "
UPDATE
    quiosques
SET            
    qui_razaosocial='$razaosocial',
    qui_cnpj='$cnpj',
    qui_ie='$ie',
    qui_cpf='$cpf',
    qui_tipopessoa='$tipopessoanfe',
    qui_endereco='$endereco',
    qui_numero='$endereco_numero',
    qui_bairro='$bairro',
    qui_cep='$cep',
    qui_im='$im'
WHERE
    qui_codigo=$quiosque
";
if (!mysql_query($sql2)) die("Erro SQL quiosques: " . mysql_error());


//Upload do certificado digital
if ($usamodulofiscal==1) {
    //echo json_encode($_FILES['certificadodigital']);
    if(isset($_FILES['certificadodigital'])) {
        date_default_timezone_set("Brazil/East"); //Definindo timezone padrão
        $ext = strtolower(substr($_FILES['certificadodigital']['name'],-4)); //Pegando extensão do arquivo
        $new_name = date("Y.m.d-H.i.s") . $ext; //Definindo um novo nome para o arquivo
        
        //Upload para uma pasta
        $dir = 'uploads/'; 
        move_uploaded_file($_FILES['certificadodigital']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
    
        $arquivo = $_FILES['certificadodigital']['tmp_name']; 
        $tamanho = $_FILES['certificadodigital']['size'];
        $tipo = $_FILES['certificadodigital']['type'];
        $nome = $ext;
        
        //Upload para o banco de dados
        if ( $arquivo != "none" ) {
            $fp = fopen($arquivo, "rb");
            $conteudo = fread($fp, $tamanho);
            $conteudo = addslashes($conteudo);
            fclose($fp); 

            echo "( $conteudo )";

            $sql = "UPDATE quiosques_configuracoes SET quicnf_pfx='$conteudo', quicnf_pfx_tipo='$tipo' WHERE quicnf_quiosque=$usuario_quiosque";
            if (!$query=mysql_query($sql)) die("Erro SQL Upload Certificado: " . mysql_error());


        } else {
            print "Não foi possível carregar o arquivo para o servidor.";
        
        }
    }
}


$tpl_notificacao->block("BLOCK_CONFIRMAR");
$tpl_notificacao->block("BLOCK_EDITADO");
$tpl_notificacao->block("BLOCK_BOTAO");
$tpl_notificacao->show();




?>
