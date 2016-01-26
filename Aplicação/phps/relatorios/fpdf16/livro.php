<?php






// cria matriz com os títulos e larguras das colunas
$titulos = array('Posição', 'País', 'Nome', 'Pontuação','Pontuação');
$larguras= array( 50, 160, 200, 70,60 );

// cria matriz com os dados da tabela
$dados   = array();
$dados[] = array( 1, 'Brazil',         'Ayrton Senna', 'deded',     90);
$dados[] = array( 2, 'France',         'Alain Prost', 'deded',       87);
$dados[] = array( 3, 'Austria',        'Gerhard Berger', 'deded',    41);
$dados[] = array( 4, 'Belgium',        'Thierry Boutsen','deded',    27);
$dados[] = array( 5, 'Italy',          'Michele Alboreto','deded',   24);
$dados[] = array( 6, 'Brazil',         'Nelson Piquet',  'deded',    22);
$dados[] = array( 7, 'Italy',          'Ivan Capelli',   'deded',    17);
$dados[] = array( 8, 'United Kingdom', 'Derek Warwick',   'deded',   17);
$dados[] = array( 9, 'United Kingdom', 'Nigel Mansell',  'deded',    12);
$dados[] = array(10, 'Italy',          'Alessandro Nannini','deded', 12);
$dados[] = array(11, 'Italy',          'Riccardo Patrese','deded',    8);
$dados[] = array(12, 'United States',  'Eddie Cheever',    'deded',   6);
$dados[] = array(13, 'Brazil',         'Maurício Gugelmin', 'deded',  5);
$dados[] = array(14, 'United Kingdom', 'Jonathan Palmer',  'deded',  5);
$dados[] = array(15, 'Italy',          'Andrea de Cesaris', 'deded', 3);
$dados[] = array(16, 'Japan',          'Satoru Nakajima',   'deded', 1);
$dados[] = array(17, 'Italy',          'Pierluigi Martini','deded',  1);

// inclui a classe FPDF
require('fpdf.php');

// instancia a classe FPDF
$pdf = new FPDF('P', 'pt', 'A4');

// adiciona uma página
$pdf->AddPage();

// define a fonte
$pdf->SetFont('Arial','B',12);

// define cor de preenchimento,
// cor de texto e espessura da linha
$pdf->SetFillColor(130,80,70);
$pdf->SetTextColor(255);
$pdf->SetLineWidth(1);

// cria o cabeçalho da tabela
$i = 0;
foreach ($titulos as $titulo)
{
    $pdf->Cell($larguras[$i], 20, $titulo, 1, 0, 'C', true);
    $i++;
}
// quebra de linha
$pdf->Ln();

// define cor de fundo, do
// texto e fonte dos dados
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 12);

// adiciona linhas com os dados
$colore = FALSE;
$total = 0;
foreach($dados as $linha)
{
    $col = 0;
    foreach ($linha as $coluna)
    {
        // se inteiro, alinha à direita
        if (is_int($coluna))
        {
            $pdf->Cell($larguras[$col], 14, number_format($coluna),'LR',0,'R',$colore);
        }
        // caso contrário alinha à direita
        else
        {
            $pdf->Cell($larguras[$col], 14, $coluna,'LR',0,'L',$colore);
        }
        $col ++;
    }
    // acumula total de valor
    $total += $linha[3];
    // quebra de linha
    $pdf->Ln();
    $colore=!$colore; // inverte cor de fundo
}

// define a fonte dos totais
$pdf->SetFont('Arial','B',12);

// calcula larguras das células
$largura1 = array_sum($larguras)-$larguras[count($larguras)-1];
$largura2 = array_sum($larguras)-$largura1;

// exibe a linha de total
$pdf->Cell($largura1, 20, 'Total', 1, 0, 'L', true);
$pdf->Cell($largura2, 20, $total,  1, 0, 'R', true);


// exibe o resultado no navegador
$pdf->Output();
?>