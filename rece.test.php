<?php
    require('_core.php');

    require('fpdf/fpdf.php');

    $sql_dates = 'SELECT nombres, apellidos, sexo, fecha_nacimiento, direccion, tel1 FROM paciente';

    $sql_info_empresa='SELECT id_empresa, nombre, propietario, departamento, municipio, direccion FROM empresa WHERE id_empresa=1';
    $query_dates=_query($sql_dates);

    $query_date=_query($sql_info_empresa);
    $array_empresa=_fetch_array($query_date);

    class PDF extends FPDF{
        function __construct($orientation='P', $unit='', $size='A4', $static_date){
            parent::__construct($orientation, $unit, $size);
            $this->static_date=$static_date;
            
        }
        function Header(){
            $this->Cell(50,10, utf8_decode('Nombre de la empresa'));
            $this->Ln();
            $this->Cell(50,10, utf8_decode($this->static_date['nombre']));
            $this->Ln();
            $this->Cell(50, 10, utf8_decode("Propietario de la empresa"));
            $this->Ln();
            $this->Cell(50, 10, utf8_decode($this->static_date['propietario']));

        }

        function Footer(){
            $this->SetY(-45);
            $this->Cell(10,50, utf8_decode("Ese es el pie de pagina"));
            $this->Ln();
            $this->Cell(25, 126,(''.$this->page));
        }
    }

    $pdf=new PDF('P', 'mm', 'A4', $array_empresa);
    //$pdf->SetMargins(15,15);
    //$pdf->SetTopMargin(10);
    //$pdf->SetLeftMargin(10);
    //$pdf->AliasNBPages();
    $pdf->SetFont('helvetica','', 10);
    $pdf->addPage();
    while($_row_data=_fetch_array($query_dates)){
        $pdf->Cell(40,50, utf8_decode($_row_data['nombres']));
        $pdf->Ln(5);
        $pdf->Cell(40, 50, utf8_decode($_row_data['apellidos']));
        $pdf->Ln(5);
        $pdf->Cell(40, 50, utf8_decode($_row_data['sexo']));
        //$pdf->Ln(5);
        $pdf->Cell(40,50, utf8_decode($_row_data['fecha_nacimiento']));
    }


    $pdf->Output();

?>