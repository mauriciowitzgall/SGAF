<?php

//Verifica se o usu�rio tem permiss�o para acessar este conte�do
require "login_verifica.php";
if ($permissao_entradas_etiquetas <> 1) {
    header("Location: permissoes_semacesso.php");
    exit;
}

//Funções necessárias para o Código de Barras
function esquerda($entra, $comp) {
    return substr($entra, 0, $comp);
}
function direita($entra, $comp) {
    return substr($entra, strlen($entra) - $comp, $comp);
}


$tipopagina = "entradas";
include "includes_etiquetadora.php";

$massa=$_GET["massa"];
$lote = $_GET["lote"];

if ($massa==1) {

    //Verifica quantos itens tem na entrada
    $sql = "SELECT count(entpro_numero) FROM entradas_produtos WHERE entpro_entrada=$lote";
    $query = mysql_query($sql);
    if (!$query) die("Erro CONTAGEM DE ITENS DA ENTRADA:" . mysql_error());
    $dados = mysql_fetch_array($query);
    $qtd_itens=$dados[0];
   
    
    //Soma o total de etiquetas que deverão ser impressas conforme quantidade desejada
    $total_etiquetas = array_sum($_POST["qtddesejada"]);
    

    $sql2 = "SELECT DISTINCT entpro_numero FROM entradas_produtos WHERE entpro_entrada=$lote";
    $query2 = mysql_query($sql2);
    if (!$query2) die("Erro 2:" . mysql_error());
    $cont=1;
    $pag=1;
    
    $tpl = new Template("entradas_etiqueta_compacta.html");

    while ($dados2 = mysql_fetch_array($query2)) {
        
        
        $numero=$dados2[0];
        $qtddesejada=$_POST["qtddesejada"];
        $qtdd=$qtddesejada[$numero];
        //echo "<br>$num $qtddesejada[$num] <br>";
        
        for ($j=1;$j<=$qtdd;$j++) {
            //echo "<br>$num $j <br>";
            
            //Gerar código de barras

            //Pesquisa os demais valores que precisa no banco
            $sql = "
                SELECT 
                    *
                FROM 
                    entradas 
                    join entradas_produtos on (ent_codigo=entpro_entrada) 
                    join pessoas on (ent_fornecedor=pes_codigo)
                    join produtos on (pro_codigo=entpro_produto)
                    join produtos_tipo on (protip_codigo=pro_tipocontagem)
                WHERE 
                    entpro_entrada=$lote and
                    entpro_numero=$numero
                ORDER BY entpro_numero
            ";
            $query = mysql_query($sql);
            if (!$query)
                die("Erro1:" . mysql_error());
            while ($dados = mysql_fetch_assoc($query)) {
                $fornecedor_nome = $dados["pes_nome"];
                $produto_nome = $dados["pro_nome"];
                $produto_referencia = $dados["pro_referencia"];
                $produto_tamanho = $dados["pro_tamanho"];
                $produto_cor = $dados["pro_cor"];
                $produto_descricao = $dados["pro_descricao"];
                $produto_nome2= "$produto_nome $produto_referencia $produto_tamanho $produto_cor $produto_descricao";
                $produto = $dados["pro_codigo"];
                $produto_descricao = $dados["pro_descricao"];
                $fornecedor = $dados["pes_codigo"];
                $fornecedor_id = $dados["pes_id"];
                $qtd = $dados["entpro_quantidade"];
                $tipo_contagem = $dados["protip_codigo"];
                $sigla = $dados["protip_sigla"];
                $validade = $dados["entpro_validade"];
                $valuni = $dados["entpro_valorunitario"];
                $local = $dados["entpro_local"];
            }



            //Cria o código 
            $produto_barra = str_pad($produto, 6, "0", STR_PAD_LEFT);
            $lote_barra = str_pad($lote, 8, "0", STR_PAD_LEFT);
            $etiqueta = $produto_barra . $lote_barra;
            $valor = $etiqueta;
            
            $produto_nome2=substr($produto_nome2,0,40);
            $tpl->PRODUTO = "$produto_nome2";
            
            //Código de Barras
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


            
            $pula = $cont / 1;
            $tpl->COLUNA_ALTURA="96.16px";
            
            if (is_int($pula) == true) {
                //echo "($valor)<br>";
                $tpl->block("BLOCK_ETIQUETA_COLUNA");
                $tpl->block("BLOCK_ETIQUETA_CONTEUDO");
                $tpl->block("BLOCK_ETIQUETA_LINHA");
            } else {
                $tpl->block("BLOCK_ETIQUETA_COLUNA");
                $tpl->COLUNA_ESPACO_TAMANHO="5px";
                $tpl->block("BLOCK_ETIQUETA_COLUNA_ESPACO");
                $tpl->block("BLOCK_ETIQUETA_CONTEUDO");
            }
            
            $cont++; 
            
        }
    }
    $tpl->block("BLOCK_ETIQUETA_LINHA");
    $tpl->block("BLOCK_ETIQUETA_TABELA");
    $tpl->show();           
   //------------------------------------------------------------------------------------------
} else {
echo "ERRO - Não é possível gerar este estilo de etiqueta individualmente, somente em massa! Contato o suporte para mais informações!";
}

?>
