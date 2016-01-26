<?

function fbarcode($valor) {

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


//Desenho da barra
//Guarda inicial
    ?>
    <table border="0">
        <tr><td>
                <img src="../imagens/icones/codigo_barras/p.gif" width=<?= $fino ?> height=<?= $altura ?> border=0><img
                    src="../imagens/icones/codigo_barras/b.gif" width=<?= $fino ?> height=<?= $altura ?> border=0><img
                    src="../imagens/icones/codigo_barras/p.gif" width=<?= $fino ?> height=<?= $altura ?> border=0><img
                    src="../imagens/icones/codigo_barras/b.gif" width=<?= $fino ?> height=<?= $altura ?> border=0><img
                    <?
                    $texto = $valor;
                    if ((strlen($texto) % 2) <> 0) {
                        $texto = "0" . $texto;
                    }

// Draw dos dados
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
                            ?>
                            src="../imagens/icones/codigo_barras/p.gif" width=<?= $f1 ?> height=<?= $altura ?> border=0><img
                            <?
                            if (substr($f, $i, 1) == "0") {
                                $f2 = $fino;
                            } else {
                                $f2 = $largo;
                            }
                            ?>
                            src="../imagens/icones/codigo_barras/b.gif" width=<?= $f2 ?> height=<?= $altura ?> border=0><img
                            <?
                        }
                    }

// Draw guarda final
                    ?>
                    src="../imagens/icones/codigo_barras/p.gif" width=<?= $largo ?> height=<?= $altura ?> border=0><img
                    src="../imagens/icones/codigo_barras/b.gif" width=<?= $fino ?> height=<?= $altura ?> border=0><img
                    src="../imagens/icones/codigo_barras/p.gif" width=<?= 1 ?> height=<?= $altura ?> border=0>
            </td></tr>
        <tr><td align="center"> <? echo "$valor"; ?></td></tr> </table><?
}

//Fim da função

function esquerda($entra, $comp) {
    return substr($entra, 0, $comp);
}

function direita($entra, $comp) {
    return substr($entra, strlen($entra) - $comp, $comp);
}
                ?>
