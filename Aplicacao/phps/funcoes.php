<?php

//Converte data do formato Y-m-d para d/m/Y
function converte_data($data) {
    if ($data != "") {
        $texto = explode("-", $data);
        return $texto[2] . "/" . $texto[1] . "/" . $texto[0];
    }
    return $data;
}

//Converte data do formato Y-m-d para d/m/Y
function converte_dinheirotela_para_dinheirodb($data) {
    if ($data != "") {
        $texto = explode(" ", $data);
        $texto = $texto[2];
        return $texto[2] . "/" . $texto[1] . "/" . $texto[0];
    }
    return $data;
}

//Converte data do formato d/m/Y para Y-m-d
function desconverte_data($data) {
    if ($data != "") {
        $texto = explode("/", $data);
        return $texto[2] . "-" . $texto[1] . "-" . $texto[0];
    }
    return $data;
}

//Converte datahora do formato Y-m-d H:m:s para d/m/Y H:m:s
function converte_datahora($data) {
    if ($data != "") {
        $texto = explode(" ", $data);
        $data1=$texto[0];
        $hora1=$texto[1];
        $textodata = explode ("-",$data1);
        return $textodata[2] . "/" . $textodata[1] . "/" . $textodata[0] . " " . "$hora1";
    }
    return $data;
}

function converte_hora($hora) {
    if ($hora != "") {
        $texto = explode(":", $hora);
        return $texto[0] . ":" . $texto[1];
    }
    return $hora;
}

function dinheiro_para_numero($dinheiro) {
    $dinheiro2 = explode(" ", $dinheiro);
    if ($dinheiro2[1] != "") {
        $dinheiro = $dinheiro2[1];
        $dinheiro = str_replace('.', '', $dinheiro);
        $dinheiro = str_replace(',', '.', $dinheiro);
    }
    return $dinheiro;
}

function dinheirosemcrifrao_para_numero($dinheiro) {
    $dinheiro = str_replace('.', '', $dinheiro);
    $dinheiro = str_replace(',', '.', $dinheiro);
    return $dinheiro;
}

//Calcula a diferen�a entre duas datas
//A data deve estar no formato Y-m-d
function diferenca_data($d1, $d2, $type = '', $sep = '-') {
    $d1 = explode($sep, $d1);
    $d2 = explode($sep, $d2);
    switch ($type) {
        case 'A':
            $X = 31536000;
            break;
        case 'M':
            $X = 2592000;
            break;
        case 'D':
            $X = 86400;
            break;
        case 'H':
            $X = 3600;
            break;
        case 'MI':
            $X = 60;
            break;
        default:
            $X = 1;
    }
    return floor(( ( mktime(0, 0, 0, $d2[1], $d2[2], $d2[0]) - mktime(0, 0, 0, $d1[1], $d1[2], $d1[0]) ) / $X));
}

//Formado da data deve ser ..
function diferenca_entre_datahora($tempo1, $tempo2) {

    $t1 = explode("_", $tempo1);
    $t1_data = $t1[0];
    $t1_data = explode("-", $t1_data);
    $t1_ano = $t1_data[0];
    $t1_mes = $t1_data[1];
    $t1_dia = $t1_data[2];
    $t1_horas = $t1[1];
    $t1_horas = explode(":", $t1_horas);
    $t1_hora = $t1_horas[0];
    $t1_minuto = $t1_horas[1];
    $t1_segundo = $t1_horas[2];

    $t2 = explode("_", $tempo2);
    $t2_data = $t2[0];
    $t2_data = explode("-", $t2_data);
    $t2_ano = $t2_data[0];
    $t2_mes = $t2_data[1];
    $t2_dia = $t2_data[2];
    $t2_horas = $t2[1];
    $t2_horas = explode(":", $t2_horas);
    $t2_hora = $t2_horas[0];
    $t2_minuto = $t2_horas[1];
    $t2_segundo = $t2_horas[2];

    $data_inicial = mktime($t1_hora, $t1_minuto, $t1_segundo, $t1_mes, $t1_dia, $t1_ano);
    //echo "($t1_hora, $t1_minuto, $t1_segundo, $t1_mes, $t1_dia, $t1_ano)<br>";
    $data_final = mktime($t2_hora, $t2_minuto, $t2_segundo, $t2_mes, $t2_dia, $t2_ano);
    //echo "($t2_hora, $t2_minuto, $t2_segundo, $t2_mes, $t2_dia, $t2_ano)";
    $total_segundos = $data_final - $data_inicial;

    //Em segundos
    return $total_segundos;
    //$tempo=gmdate("H:i",$total_segundos);
}

function limpa_cpf($valor) {
    $valor = str_replace('.', '', $valor);
    $valor = str_replace('-', '', $valor);
    return $valor;
}

function limpa_cnpj($valor) {
    $valor = str_replace('.', '', $valor);
    $valor = str_replace('-', '', $valor);
    $valor = str_replace('/', '', $valor);
    return $valor;
}



function usamodulofiscal_tipopessoa($quiosque){
    $sql="SELECT qui_tipopessoa FROM quiosques WHERE qui_codigo=$quiosque";
    if (!$query= mysql_query($sql)) die("Erro SQL Função: usamodulofiscal_tipopessoa: " . mysql_error());
    while ($dados=  mysql_fetch_assoc($query)) {    
        $tipopessoa=$dados["qui_tipopessoa"];
    }
    return $tipopessoa;
}

function usacomanda($quiosque) {
    $sql="SELECT quicnf_usacomanda FROM quiosques_configuracoes WHERE quicnf_quiosque=$quiosque";
    if (!$query= mysql_query($sql)) die("Erro SQL Função: usacomanda: " . mysql_error());
    while ($dados=  mysql_fetch_assoc($query)) {    
        $usa=$dados["quicnf_usacomanda"];
    }
    return $usa;
}

function usapagamentosparciais($quiosque) {
    $sql="SELECT quicnf_pagamentosparciais FROM quiosques_configuracoes WHERE quicnf_quiosque=$quiosque";
    if (!$query= mysql_query($sql)) die("Erro SQL Função: usacomanda: " . mysql_error());
    while ($dados=  mysql_fetch_assoc($query)) {    
        $usa=$dados["quicnf_pagamentosparciais"];
    }
    return $usa;
}


function usamodulovendas($quiosque) {
    $sql="SELECT quicnf_usamodulovendas FROM quiosques_configuracoes WHERE quicnf_quiosque=$quiosque";
    if (!$query= mysql_query($sql)) die("Erro SQL Função: usamodulovendas: " . mysql_error());
    while ($dados=  mysql_fetch_assoc($query)) {    
        $usa=$dados["quicnf_usamodulovendas"];
    }
    return $usa;
}

function usamoduloproducao($quiosque) {
    $sql="SELECT quicnf_usamoduloproducao FROM quiosques_configuracoes WHERE quicnf_quiosque=$quiosque";
    if (!$query= mysql_query($sql)) die("Erro SQL Função: usamoduloproducao:" . mysql_error());
    while ($dados=  mysql_fetch_assoc($query)) {    
        $usa=$dados["quicnf_usamoduloproducao"];
    }
    return $usa;
}

function usamoduloestoque($quiosque) {
    $sql="SELECT quicnf_usamoduloestoque FROM quiosques_configuracoes WHERE quicnf_quiosque=$quiosque";
    if (!$query= mysql_query($sql)) die("Erro SQL Função: usamoduloestoque:" . mysql_error());
    while ($dados=  mysql_fetch_assoc($query)) {    
        $usa=$dados["quicnf_usamoduloestoque"];
    }
    return $usa;
}

function usavendaporcoes($quiosque) {
    $sql="SELECT quicnf_usavendaporcoes FROM quiosques_configuracoes WHERE quicnf_quiosque=$quiosque";
    if (!$query= mysql_query($sql)) die("Erro SQL Função: usavendaporcoes:" . mysql_error());
    while ($dados=  mysql_fetch_assoc($query)) {    
        $usa=$dados["quicnf_usavendaporcoes"];
    }
    return $usa;
}

function pessoa_caixaoperacao($pessoa) {
    $sql="SELECT pes_caixaoperacaonumero FROM pessoas WHERE pes_codigo=$pessoa";
    if (!$query= mysql_query($sql)) die("Erro SQL Função: usamodulovendas: " . mysql_error());
    while ($dados=  mysql_fetch_assoc($query)) {    
        $caixaoperacao=$dados["pes_caixaoperacaonumero"];
    }
    return $caixaoperacao;
}




function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for($i = 0; $i<=strlen($mask)-1; $i++)
    {
    if($mask[$i] == '#')
    {
    if(isset($val[$k]))
    $maskared .= $val[$k++];
    }
    else
    {
    if(isset($mask[$i]))
    $maskared .= $mask[$i];
    }
    }
    return $maskared;

    //Exemplos de aplicação

    //$cnpj = "11222333000199";
    //$cpf = "00100200300";
    //$cep = "08665110";
    //$data = "10102010";
    //echo mask($cnpj,'##.###.###/####-##');
    //echo mask($cpf,'###.###.###-##');
    //echo mask($cep,'#####-###');
    //echo mask($data,'##/##/####

}


?>
