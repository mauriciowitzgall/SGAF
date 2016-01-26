<?php

//Converte data do formato Y-m-d para d/m/Y
function converte_data($data) {
    if ($data != "") {
        $texto = explode("-", $data);
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

?>
