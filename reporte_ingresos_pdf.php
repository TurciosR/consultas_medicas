<?php
include('_core.php');
require('html_table.php');
require('num2letras.php');
//Obtener valores por $_GET

$fecha_inicio=$_GET['fecha_inicio'];
$fecha_fin= $_GET['fecha_fin'];
$fi=MD($fecha_inicio);
$ff=MD($fecha_fin);
class PDF extends PDF_HTML_Table
{
     //ancho por columna, 10 Columnas
  public $weights=array(10,40,70,40,25);

public function Header()
{
  $fecha_inicio=$_GET['fecha_inicio'];
  $fecha_fin= $_GET['fecha_fin'];
  // Consultar la base de datos configuracion
   $sql_empresa = "SELECT e.*, d.nombre_departamento, m.nombre_municipio FROM empresa as e, departamento as d, municipio as m WHERE d.id_departamento=e.departamento AND m.id_municipio=e.municipio AND e.id_empresa='1'";

  $resultado_emp=_query($sql_empresa);
  $num_rows = _num_rows($resultado_emp);
  $row_emp=_fetch_array($resultado_emp);
	$empresa=utf8_decode($row_emp['nombre']);
  $direct = utf8_decode(Mayu($row_emp["direccion"]));
  $municipio = utf8_decode(Mayu($row_emp["nombre_municipio"]));
  $departamento = utf8_decode(Mayu($row_emp["nombre_departamento"]));
  $direccion = $direct.", ".$municipio.", ".$departamento;$telefonos=$row_emp['telefono1'].'   '.$row_emp['telefono2'];
  $logo=$row_emp['logo'];

	//Title
  $fechaprint=date('d-m-Y');
  $title0="REPORTE DE INGRESOS";
  $title1="DESDE ".$fecha_inicio. " HASTA ".$fecha_fin;
  $title2="FECHA IMPRESION : ".$fechaprint;
  $this->SetLeftMargin(16); 
	$this->SetFont('Arial','B',11);
	$this->Cell(0,6,$empresa,0,1,'C');
  $this->SetFont('Arial','B',10);
  $this->Cell(0,6,$direccion,0,1,'C');
  $this->Cell(0,6,$title0,0,1,'C');
  $this->SetFont('Arial','B',9);
  $this->Cell(0,6,$title1,0,1,'C');
  $this->SetFont('Arial','B',8);
  $this->Cell(0,6,$title2,0,1,'C');

  //$this->Image($logo,10,10,30,20);
	$this->Ln(6);

$tableData=array("No.","FECHA","CLIENTE","FORMA PAGO", "TOTAL");

 $this->SetFillColor(192,192,192);
 $this->SetTextColor(0);
 $this->SetFont('Arial','B',8);

   $x=$this->GetX();
   $y=$this->GetY();
   $he=5; //altura
   $nb=1; //lineas
   $w=$this->weights;
   for ($i=0;$i<5;$i++) {
       $x=$this->GetX();
       $y=$this->GetY();
       $this->Rect($x, $y, $w[$i], $he*$nb);
      $datoss=$tableData[$i].' ';
      $this->MultiCell($w[$i], $he, $tableData[$i].' ', 0,'C',1);
   //Put the position to the right of the cell
   $this->SetXY($x+$w[$i], $y);
   }
   $this->Ln($he*$nb);
     $this->SetFont('Arial','',8);

  //Ensure table header is output
	parent::Header();
}

}
//sql stock producto
$htmlTable="<TABLE>";
//sql stock antiguo
$sql="SELECT  * FROM factura WHERE  fecha BETWEEN '$fi' AND '$ff' ORDER BY fecha ASC";
//GROUP BY  producto.id_producto,movimiento_producto.fecha_movimiento,movimiento_producto.numero_doc
$result=_query($sql);

  $sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
  $datos_moneda = _fetch_array($sql0);
  $simbolo = $datos_moneda["simbolo"];  
  $moneda = $datos_moneda["moneda"];

//Create Table
$i = 1;
$totalg= 0;
while($row = _fetch_array($result))
{
  $fecha=ED($row['fecha']);
  $fechaprint=$fecha;
  $paciente = $row["cliente"];
  if($row["id_paciente"]>0)
  {
    $paciente = buscar($row["id_paciente"]);
  }
  $tipo_pago=$row['tipo_pago'];
  $total=$row['total'];
  
  $htmlTable.=	'<TR><TD>'.$i.'</TD>';
  $htmlTable.=	'<TD>'.$fechaprint.'</TD>';
  $htmlTable.=	'<TD>'.utf8_decode(Mayu($paciente)).'</TD>';
  $htmlTable.=	'<TD>'.utf8_decode(Mayu($tipo_pago)).'</TD>';
  $htmlTable.=	'<TD>'.$simbolo.''.number_format($total,2,".",",").'</TD>';
  $htmlTable.=	'</TR>';
  $i++;
  $totalg +=$total;
}

$htmlTable.="<TR><TD></TD><TD></TD><TD></TD><TD>TOTAL</TD><TD>".$simbolo."".number_format($totalg,2,".",",")."</TD></TR>";
$htmlTable.='</TABLE>';

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P','Letter');
$pdf->SetFont('Arial','',8);
$pdf->WriteHTML("$htmlTable");
$pdf->Output("reporte_ingresos.pdf","I");
?>
