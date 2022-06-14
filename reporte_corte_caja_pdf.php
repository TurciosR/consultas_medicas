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
  public $weights=array(10,20,15,54,26,20,20,22,26,42);

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
  $title0="REPORTE DE CORTE DE CAJA";
  $title1="DESDE ".$fecha_inicio. " HASTA ".$fecha_fin;
  $title2="FECHA IMPRESION : ".$fechaprint;
  $this->SetLeftMargin(16); 
	$this->SetFont('Arial','B',11);
  $this->Cell(0,6,$empresa,0,1,'C');
	$this->SetFont('Arial','B',10);
  $this->Cell(0,6,Mayu($direccion),0,1,'C');
  $this->Cell(0,6,$title0,0,1,'C');
  $this->SetFont('Arial','B',9);
  $this->Cell(0,6,$title1,0,1,'C');
  $this->SetFont('Arial','B',8);
  $this->Cell(0,6,$title2,0,1,'C');

  //$this->Image($logo,10,10,30,20);
	$this->Ln(6);

$tableData=array("No.","FECHA","HORA","USUARIO","TOTAL SISTEMA", "EFECTIVO", "CHEQUE", "TARJETA", "TOTAL CORTE", "OBSERVACIONES");

 $this->SetFillColor(192,192,192);
 $this->SetTextColor(0);
 $this->SetFont('Arial','B',8);

   $x=$this->GetX();
   $y=$this->GetY();
   $he=5; //altura
   $nb=1; //lineas
   $w=$this->weights;
   for ($i=0;$i<10;$i++) {
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
$sql="SELECT  cc.*, usuario.nombre FROM corte_caja as cc, usuario WHERE  usuario.id_usuario=cc.id_usuario AND cc.fecha BETWEEN '$fi' AND '$ff'";
//GROUP BY  producto.id_producto,movimiento_producto.fecha_movimiento,movimiento_producto.numero_doc
$result=_query($sql);
$num = _num_rows($result);

  $sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
  $datos_moneda = _fetch_array($sql0);
  $simbolo = $datos_moneda["simbolo"];  
  $moneda = $datos_moneda["moneda"];

//Create Table
if($num>0)
{
  $i = 1;
  $totalg= 0;
  while($row = _fetch_array($result))
  {
    $fecha=ED($row['fecha']);
    $hora=hora($row['hora']);
    $fechaprint=$fecha;
    $usuario = $row["nombre"];  
    $total_sistema=$row['total_sistema'];
    $total_corte=$row['total_corte'];
    $efectivo=$row['efectivo'];
    $cheque=$row['cheque'];
    $tarjeta=$row['tarjeta'];
    $observaciones=$row['observaciones'];
    
    $htmlTable.=	'<TR><TD>'.$i.'</TD>';
    $htmlTable.=  '<TD>'.$fechaprint.'</TD>';
    $htmlTable.=	'<TD>'.$hora.'</TD>';
    $htmlTable.=	'<TD>'.utf8_decode(Mayu($usuario)).'</TD>';
    $htmlTable.=  '<TD>'.$simbolo.''.number_format($total_sistema,2,".",",").'</TD>';
    $htmlTable.=  '<TD>'.$simbolo.''.number_format($efectivo,2,".",",").'</TD>';
    $htmlTable.=  '<TD>'.$simbolo.''.number_format($cheque,2,".",",").'</TD>';
    $htmlTable.=  '<TD>'.$simbolo.''.number_format($tarjeta,2,".",",").'</TD>';
    $htmlTable.=  '<TD>'.$simbolo.''.number_format($total_corte,2,".",",").'</TD>';
    $htmlTable.=	'<TD>'.$observaciones.'</TD>';
    $htmlTable.=	'</TR>';
    $i++;
  }
}
else
{
  $htmlTable.="<TR>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
                <TD></TD>
              </TR>";
}
$htmlTable.='</TABLE>';

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L','Letter');
$pdf->SetFont('Arial','',8);
$pdf->WriteHTML("$htmlTable");
$pdf->Output("corte_caja.pdf","I");
?>
