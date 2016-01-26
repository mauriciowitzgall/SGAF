<?php
function Cabecalho()
{
        global $pdf;
        $pdf->AddPage(); 
        $pdf->SetY(10);
        $pdf->Image('imagem/logo_ibe.jpg', 10, 5); // importa uma imagem
        $pdf->SetXY(10, 45);
        $pdf->SetAutoPageBreak(auto , 20);
        $pdf->y0=$pdf->GetY();
}

function Rodape()
{
        global $pdf;
    $pdf->SetY(-18);
//    $pdf->SetY(265);
    $pdf->SetFont('Arial','I',8);
        $pdf->Cell(0, 5, "endereço completo do meu cliente: Rua XV de Novembro, 111 - Cidade - Estado - CEP: 000-0000", 0, 0);
        $pdf->ln();
        $pdf->Cell(170, 5, "Telefone: (xx) XXX-XXXX Fax: (XX) XXX-XXXX E-mail: contrato@dominio_da_empresa.com.br", 0, 0);
    //Imprime o número da página centralizado
        $pdf->Cell(10, 5,'Página ' . $pdf->PageNo().' de {nb}',0,'R');
//      $pdf->Cell(0,5,$_SERVER['SCRIPT_NAME'],0,0,'R');
}