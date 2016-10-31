<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_entradas_etiquetas <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

$tipopagina = "entradas";
include "includes2.php";
$lote = $_GET["lote"];
$numero = $_GET["numero"];
$qtd_etiquetas = $_GET["qtd_etiquetas"];

$data = date("Y-m-d");
$hora = date("H:i:s");


//Pesquisa os demais valores que precisa no banco
$sql = "
    SELECT 
        pes_nome,pro_nome,entpro_quantidade,protip_sigla,protip_codigo,entpro_validade,entpro_valorunitario,entpro_local,pro_descricao,pro_codigo,pes_id
    FROM 
        entradas 
        join entradas_produtos on (ent_codigo=entpro_entrada) 
        join pessoas on (ent_fornecedor=pes_codigo)
        join produtos on (pro_codigo=entpro_produto)
        join produtos_tipo on (protip_codigo=pro_tipocontagem)
    WHERE 
        entpro_entrada=$lote and
        entpro_numero=$numero        
";
$query = mysql_query($sql);
if (!$query)
    die("Erro1:" . mysql_error());
while ($dados = mysql_fetch_assoc($query)) {
    $fornecedor_nome = $dados["pes_nome"];
    $fornecedor_id = $dados["pes_id"];
    $produto_nome = $dados["pro_nome"];
    $produto = $dados["pro_codigo"];
    $produto_descricao = $dados["pro_descricao"];
    $fornecedor = $dados["pes_codigo"];
    $qtd = $dados["entpro_quantidade"];
    $tipo_contagem = $dados["protip_codigo"];
    $sigla = $dados["protip_sigla"];
    $validade = $dados["entpro_validade"];
    $valuni = $dados["entpro_valorunitario"];
    $local = $dados["entpro_local"];        
}

//Fun�ões necess�rias para o C�digo de Barras
function esquerda($entra, $comp) {
    return substr($entra, 0, $comp);
}

function direita($entra, $comp) {
    return substr($entra, strlen($entra) - $comp, $comp);
}

//Cria o c�digo 
$produto_barra = str_pad($produto, 6, "0", STR_PAD_LEFT);
$lote_barra = str_pad($lote, 8, "0", STR_PAD_LEFT);
$etiqueta = $produto_barra . $lote_barra;
$valor = $etiqueta;




$tpl = new Template("entradas_etiquetao.html");
$cont=0;

for ($cont = 1; $cont <= $qtd_etiquetas; $cont++) {

    $tpl->PRODUTO = "$produto_nome";    

//C�digo de Barras
    $fino = 1;
    $largo = 3;
    $altura = 50;
    $barcodes[0] = "00110";
    $barcodes[1] = "10001";
    $barcodes[2] = "01001";
    $barcodes[3] = "11000";
    $barcodes[4] = "00101";
    $barcodes[5] = "10100";
    $barcodes[6] = "01100";
    $barcodes[7] = "00011";
    $barcodes[8] = "10010";
    $barcodes[9] = "01010";
    for ($f1 = 9; $f1 >= 0; $f1--) {
        for ($f2 = 9; $f2 >= 0; $f2--) {
            $f = ($f1 * 10) + $f2;
            $texto = "";
            for ($i = 1; $i < 6; $i++) {
                $texto .= substr($barcodes[$f1], ($i - 1), 1) . substr($barcodes[$f2], ($i - 1), 1);
            }
            $barcodes[$f] = $texto;
        }
    }
    $tpl->FINO = $fino;
    $tpl->ALTURA = $altura;
    $texto = $valor;
    if ((strlen($texto) % 2) <> 0) {
        $texto = "0" . $texto;
    }
    while (strlen($texto) > 0) {
        $i = round(esquerda($texto, 2));
        $texto = direita($texto, strlen($texto) - 2);
        $f = $barcodes[$i];
        for ($i = 1; $i < 11; $i+=2) {
            if (substr($f, ($i - 1), 1) == "0") {
                $f1 = $fino;
            } else {
                $f1 = $largo;
            }
            $tpl->F1 = $f1;
            $tpl->ALTURA = $altura;
            $tpl->block("BLOCK_1");
            $tpl->block("BLOCK_ITEM");
            if (substr($f, $i, 1) == "0") {
                $f2 = $fino;
            } else {
                $f2 = $largo;
            }
            $tpl->F2 = $f2;
            $tpl->ALTURA = $altura;
            $tpl->block("BLOCK_2");
            $tpl->block("BLOCK_ITEM");
        }
    }
    $tpl->LARGO = $largo;
    $tpl->FINO = $fino;

    $tpl->VALOR = $valor;
    $tpl->block("BLOCK_CODBARRAS");


    //Fornecedor e Descri��o do Produto
    
    
    
    if ($tipo_contagem==1) {
        $tpl->QUANTIDADE = "";
        $tpl->VALOR_UNITARIO = "";
        $valtot=$valuni;
        $tpl->SIGLA = "";
        $tpl->SIGLA2 = "";
    } else {
        $valtot = $valuni * $qtd;    
        $tpl->QUANTIDADE = number_format($qtd, 2, ',', '.');
        $tpl->VALOR_UNITARIO = " \ R$ " . number_format($valuni, 2, ',', '.');
        $tpl->SIGLA = "$sigla";
        $tpl->SIGLA2 = "por $sigla";
    }
    $tpl->VALOR_TOTAL = "R$ " . number_format($valtot, 2, ',', '.');   
    
    if ($validade=="0000-00-00") {
        $tpl->VALIDADE = "";        
    } else {
        $tpl->VALIDADE = "Válido até ".converte_data($validade);
    }

    $tpl->CAMPO_VALOR = "$fornecedor_nome";
    $tpl->FORNECEDOR_ID = "$fornecedor_id";
    $tpl->TEXTAREA_VALOR = "$produto_descricao";

//Quiosque e Cooperativa
    $sql = "
SELECT
    *
FROM
    quiosques
    JOIN cidades on (qui_cidade=cid_codigo)
    join estados on (est_codigo=cid_estado)
    JOIN cooperativas on (qui_cooperativa=coo_codigo)
WHERE
    qui_codigo=$usuario_quiosque
";
    $query = mysql_query($sql);
    if (!$query)
        die("Erro: " . mysql_error());
    $dados = mysql_fetch_assoc($query);
    $cidade_nome = $dados["cid_nome"];
    $endereco = $dados["qui_endereco"];
    $fone1 = $dados["qui_fone1"];
    $email = $dados["qui_email"];
    $cep = $dados["qui_cep"];
    $coo_sigla = $dados["coo_abreviacao"];
    $coo_nome = $dados["coo_nomecompleto"];
    $estado = $dados["est_sigla"];
    $obs = $dados["qui_obs"];

    $tpl->QUIOSQUE = $usuario_quiosquenome;
    $tpl->CIDADE = "$cidade_nome";
    $tpl->ESTADO = "$estado";

    if ($cep != "")
        $tpl->CEP = " - " . $cep;
    $tpl->ENDERECO = "$endereco";

    if ($fone1 != "")
        $tpl->TELEFONE = " - " . $fone1;
    if ($email != "")
        $tpl->EMAIL = "$email";
    $tpl->FRASE = "$obs";
    $tpl->COOP_SIGLA = "$coo_sigla";
    $tpl->COOP_NOME = "$coo_nome";

    
    $tpl->block("BLOCK_ETIQUETA");
}
$tpl->show();
?>
