<?php

require('fpdf16/fpdf.php');
require('../login_verifica.php');


class PDF extends FPDF {

    //Page header


    //Page footer
    function Footer() {
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        $this->SetFont("$fonte", 'I', 8);
        //Page number
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

//Instanciation of inherited class
$pdf=new PDF();

$fornecedor = $_POST["fornecedor"];
$datade = $_POST["datade"];
$dataate = $_POST["dataate"];

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont("$fonte", '', 12);
for ($i = 1; $i <= 40; $i++)
    $pdf->Cell(0, 10, 'Printing line number há CAMINHÃO' . $i, 0, 1);

$pdf->Output();
?>