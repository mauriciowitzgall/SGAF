<?php
require('fpdf16/fpdf.php');

class PDF extends FPDF {

//Page header
    function Header() {
        $fonte = "Arial";
        $data = date("d/M/Y");
        $hora = date("H:i:s");
        $rel_numero = $_POST["codigo"];
        $rel_nome = "asdfasdf";

        $this->SetFont("$fonte", 'B', 15);
        $this->Cell(0, 0, utf8_decode('RELATÃ“RIO'), 0, 0);

        $this->SetFont("$fonte", 'I', 8);
        $this->Cell(0, 0, "$data - $hora", 0, 0);

        $this->Ln(10);
        $this->SetFont("$fonte", 'B', 10);
        $this->Cell(0, 0, "Num.:", 0, 0, 'L');
        $this->Ln(10);
        $this->Cell(0, 0, "$rel_numero", 1, 0);
        $this->SetX(100);
        $this->Cell(0, 0, "Cooperativa: $usuario_cooperativaabreviacao", 0, 0, 'L');
        $this->Ln(5);
        $this->Cell(0, 0, "Nome: $rel_nome", 0, 0, 'L');
        $this->SetX(100);
        $this->Cell(0, 0, "Quiosque: $usuario_quiosquenome", 0, 0, 'L');

        $this->Image('../../imagens/logos/sgaf1.png', 180, 13, 17);
        $this->Ln(5);
        
        //Linha de baixo do cabeçalho
        $this->Cell(190, 0, "", 'B', 1);
    }

//Page footer
    function Footer() {
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

//Instanciation of inherited class

include "../conexao.php";
$rel_numero = 1;
$sql = "
            SELECT rel_nome,rel_descricao
            FROM relatorios
            WHERE rel_codigo=$rel_numero
        ";
$query = mysql_query($sql);
if (!$query)
    die("Erro1: " . mysql_error());
$dados = mysql_fetch_assoc($query);
$rel_nome2 = $dados["rel_nome"];


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
for ($i = 1; $i <= 40; $i++)
    $pdf->Cell(0, 10, "Printing line number $rel_nome2" . $i, 0, 1);

//Define o nome do arquivo pdf
$data2 = date("Y-M-d");
$hora2 = date("H-i-s");
$nome_pdf=$rel_numero."_".$data2."_".$hora2;     
$pdf->Output("$nome_pdf",'I');
?>