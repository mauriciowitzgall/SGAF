<?php
$sql="
    SELECT * 
    FROM quiosques_configuracoes 
    JOIN quiosques on (quicnf_quiosque=qui_codigo)
    JOIN cidades on (qui_cidade=cid_codigo)
    JOIN estados on (cid_estado=est_codigo) 
    WHERE quicnf_quiosque=$usuario_quiosque
";
if (!$query= mysql_query($sql)) die("Erro SQL INCLUDES: " . mysql_error());
while ($dados=  mysql_fetch_assoc($query)) {    
    $usuario_quiosque_nome=$dados["qui_nome"];
    $usuario_quiosque_pais=$dados["est_pais"];
    $usuario_quiosque_estado=$dados["cid_estado"];
    $usuario_quiosque_cidade=$dados["qui_cidade"];
    $usuario_quiosque_cidade_nome=$dados["cid_nome"];
    $usuario_quiosque_endereco=$dados["qui_endereco"];
    $usuario_quiosque_endereco_numero=$dados["qui_numero"];
    $usuario_quiosque_razaosocial=$dados["qui_razaosocial"];
    $usuario_quiosque_fone1=$dados["qui_fone1"];
    $usuario_quiosque_fone2=$dados["qui_fone2"];
    $usaestoque=$dados["quicnf_usamoduloestoque"];
    $usamodulofiscal=$dados["quicnf_usamodulofiscal"];
    $usaproducao=$dados["quicnf_usamoduloproducao"];
    $usavendas=$dados["quicnf_usamodulovendas"];
    $usacaixa=$dados["quicnf_usamodulocaixa"];
    $usadevolucoes=$dados["quicnf_devolucoessobrevendas"];
    $usavendaporcoes=$dados["quicnf_usavendaporcoes"];
    $usacomanda=$dados["quicnf_usacomanda"];
    $usapagamentosparciais=$dados["quicnf_pagamentosparciais"];
    $usamulticaixas=$dados["quicnf_multicaixas"];
    $permiteedicaoreferencianavenda=$dados["quicnf_permiteedicaoreferencianavenda"];
    $fazacertos=$dados["quicnf_fazacertos"];
    $fazfrete=$dados["quicnf_fazfrete"];
    $fazfechamentos=$dados["quicnf_fazfechamentos"];
    $permitevendasareceber=$dados["quicnf_vendasareceber"];
    $usacodigobarrasinterno=$dados["quicnf_usacodigobarrasinterno"];
    $usaean=$dados["quicnf_usaean"];
    $gerirestoqueideal=$dados["quicnf_gerirestoqueideal"];
    $ignorarlotes=$dados["quicnf_ignorarlotes"];
    $geririmobilizado=$dados["quicnf_geririmobilizado"];
    $controlavalidade=$dados["quicnf_controlavalidade"];
    $valorvendazero=$dados["quicnf_valorvendazero"];
    $obsnavenda=$dados["quicnf_obsnavenda"];
    $obsnaentrada=$dados["quicnf_obsnaentrada"];
    $fazentregas=$dados["quicnf_fazentregas"];
    $usaprateleira=$dados["quicnf_usaprateleira"];
    $identificacaoconsumidorvenda=$dados["quicnf_identificacaoconsumidorvenda"];
    $filtro_saida_ultimosdias=$dados["quicnf_filtrosaidaultimosdias"];
    $usagrupoconsumidores=1;
}


if (($filtro_saida_ultimosdias==0)||($filtro_saida_ultimosdias=="")) $filtro_saida_ultimosdias=7; //em dias


$sql="SELECT * FROM quiosques_tiponegociacao WHERE quitipneg_quiosque=$usuario_quiosque";
if (!$query= mysql_query($sql)) die("Erro SQL INCLUDES: " . mysql_error());
$quiosque_consignacao=0;
$quiosque_revenda=0;
$tipnegqtd=0;
while ($dados=  mysql_fetch_assoc($query)) {  
    if ($dados["quitipneg_tipo"]==1) { $quiosque_consignacao=1; $tipnegqt+=1; }
    if ($dados["quitipneg_tipo"]==2) { $quiosque_revenda=1; $tipnegqtd+=1; }
}


?>