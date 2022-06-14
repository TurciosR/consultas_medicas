<?php

error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');

$id_sucursal=$_SESSION["id_sucursal"];

$sqll = _query("SELECT * FROM empresa where id_empresa='$id_sucursal'");
$fila = _fetch_array($sqll);
$nombre = $fila["nombre"];
$direccion = $fila["direccion"];
$telefono1 = $fila["telefono1"];
$telefono2 = $fila["telefono2"];
$id_departamento = $fila['departamento'];
$id_municipio = $fila['municipio'];
$email = $fila["email"];
$logo = "img/logo_reporte.png";

$sql_departamento = "SELECT * FROM departamento where id_departamento = '$id_departamento'";
$query_departamento = _query($sql_departamento);
$row_departamento = _fetch_array($query_departamento);
$nombre_departamento = $row_departamento['nombre_departamento'];

$sql_municipio = "SELECT * FROM municipio WHERE id_municipio = '$id_municipio'";
$query_municipio = _query($sql_municipio);
$row_municipio = _fetch_array($query_municipio);
$nombre_municipio = $row_municipio['nombre_municipio'];

$nombre_direccion = $nombre_municipio.", ".$nombre_departamento.".";

$infoext =  array(
    'nombre_empresa' => $nombre,
    'direccion' => $giro,
    'direccion' => $direccion,
    'telefono1' => $telefono1,
    'telefono2' => $telefono2,
    'email' => $email,
    'logo' => $logo,
);

class PDF extends FPDF{

  function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        if (strpos($corners, '2')===false)
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        if (strpos($corners, '3')===false)
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        if (strpos($corners, '4')===false)
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        if (strpos($corners, '1')===false)
        {
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
            $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

    function array_procesor($array)
    {
      $ygg=0;
      $maxlines=1;
      $array_a_retornar=array();
      foreach ($array as $key => $value) {
        /*Descripcion*/
        $nombr=$value[0];
        /*character*/
        $longitud=$value[1];
        /*fpdf width*/
        $size=$value[2];
        /*fpdf alignt*/
        $aling=$value[3];
        if(strlen($nombr) > $longitud)
        {
          $i=0;
          $nom = divtextlin($nombr, $longitud);
          foreach ($nom as $nnon)
          {
            $array_a_retornar[$ygg]["valor"][]=$nnon;
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $i++;
          }
          $ygg++;
          if ($i>$maxlines) {
            // code...
            $maxlines=$i;
          }
        }
        else {
          // code...
          $array_a_retornar[$ygg]['valor'][]=$nombr;
          $array_a_retornar[$ygg]['size'][]=$size;
          $array_a_retornar[$ygg]["aling"][]=$aling;
          $ygg++;

        }
      }

      $ygg=0;
      foreach($array_a_retornar as $keys)
      {
        for ($i=count($keys["valor"]); $i <$maxlines ; $i++) {
          // code...
          $array_a_retornar[$ygg]["valor"][]="";
          $array_a_retornar[$ygg]["size"][]=$array_a_retornar[$ygg]["size"][0];
          $array_a_retornar[$ygg]["aling"][]=$array_a_retornar[$ygg]["aling"][0];
        }
        $ygg++;
      }
      return $array_a_retornar;

    }
    function drawTextBox($strText, $w, $h, $align='C', $valign='T', $border=true, $primero)
    {
        $vl = $h/4;
        $xi=$this->GetX();
        $yi=$this->GetY();

        $hrow=$this->FontSize;
        $textrows=$this->drawRows($w,$hrow,$strText,0,$align,0,0,0);
        $maxrows=floor($h/$this->FontSize);
        $rows=min($textrows,$maxrows);

        $dy=0;
        if (strtoupper($valign)=='M')
            $dy=($h-$rows*$this->FontSize)/2;
        if (strtoupper($valign)=='B')
            $dy=$h-$rows*$this->FontSize;
            $va = $yi+$dy;
            $v = $xi;
            $calculo = "";
            $this->SetY($yi+$dy);
            $this->SetX($xi);

            $this->drawRows($w,$hrow,$strText,0,$align,false,$rows,1);

            $this->SetY($yi);
            $this->SetX($v+ $w);

        if ($border)
            $this->Rect($xi,$yi,$w,$h);
    }

    function drawRows($w, $h, $txt, $border=0, $align='C', $fill=false, $maxline=0, $prn=0)
    {
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-4*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $b=0;
        if($border)
        {
            if($border==1)
            {
                $border='LTRB';
                $b='LRT';
                $b2='LR';
            }
            else
            {
                $b2='';
                if(is_int(strpos($border,'L')))
                    $b2.='L';
                if(is_int(strpos($border,'R')))
                    $b2.='R';
                $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
            }
        }
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $ns=0;
        $nl=1;
        while($i<$nb)
        {
            //Get next character
            $c=$s[$i];
            if($c=="\n")
            {
                //Explicit line break
                if($this->ws>0)
                {
                    $this->ws=0;
                    if ($prn==1) $this->_out('0 Tw');
                }
                if ($prn==1) {
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,"C",$fill);
                }
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if ( $maxline && $nl > $maxline )
                    return substr($s,$i);
                continue;
            }
            if($c==' ')
            {
                $sep=$i;
                $ls=$l;
                $ns++;
            }
            $l+=$cw[$c];
            if($l>$wmax)
            {
                //Automatic line break
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                    if($this->ws>0)
                    {
                        $this->ws=0;
                        if ($prn==1) $this->_out('0 Tw');
                    }
                    if ($prn==1) {
                        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,"C",$fill);
                    }
                }
                else
                {
                    if($align=='J')
                    {
                        $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        if ($prn==1) $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                    }
                    if ($prn==1){
                        $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,"C",$fill);
                    }
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if ( $maxline && $nl > $maxline )
                    return substr($s,$i);
            }
            else
                $i++;
        }
        //Last chunk
        if($this->ws>0)
        {
            $this->ws=0;
            if ($prn==1) $this->_out('0 Tw');
        }
        if($border && is_int(strpos($border,'B')))
            $b.='B';
        if ($prn==1) {
            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,"C",$fill);
        }
        $this->x=$this->lMargin;
        return $nl;
    }

    public function LineWriteB($array)
    {
      $ygg=0;
      $maxlines=1;
      $array_a_retornar=array();
      $array_max= array();
      foreach ($array as $key => $value) {
        // /Descripcion/
        $nombr=$value[0];
        // /fpdf width/
        $size=$value[1];
        // /fpdf alignt/
        $aling=$value[2];
        $jk=0;
        $w = $size;
        $h  = 0;
        $txt=$nombr;
        $border=0;
        if(!isset($this->CurrentFont))
          $this->Error('No font has been set');
        $cw = &$this->CurrentFont['cw'];
        if($w==0)
          $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',$txt);
        $nb = strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
          $nb--;
        $b = 1;

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        while($i<$nb)
        {
          // Get next character
          $c = $s[$i];
          if($c=="\n")
          {
            $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $jk++;

            $i++;
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
            $nl++;
            if($border && $nl==2)
              $b = $b2;
            continue;
          }
          if($c==' ')
          {
            $sep = $i;
            $ls = $l;
            $ns++;
          }
          $l += $cw[$c];
          if($l>$wmax)
          {
            // Automatic line break
            if($sep==-1)
            {
              if($i==$j)
                $i++;
              $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
              $array_a_retornar[$ygg]["size"][]=$size;
              $array_a_retornar[$ygg]["aling"][]=$aling;
              $jk++;
            }
            else
            {
              $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$sep-$j);
              $array_a_retornar[$ygg]["size"][]=$size;
              $array_a_retornar[$ygg]["aling"][]=$aling;
              $jk++;

              $i = $sep+1;
            }
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
            $nl++;
            if($border && $nl==2)
              $b = $b2;
          }
          else
            $i++;
        }
        // Last chunk
        if($this->ws>0)
        {
          $this->ws = 0;
        }
        if($border && strpos($border,'B')!==false)
          $b .= 'B';
        $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
        $array_a_retornar[$ygg]["size"][]=$size;
        $array_a_retornar[$ygg]["aling"][]=$aling;
        $jk++;
        $ygg++;
        if ($jk>$maxlines) {
          // code...
          $maxlines=$jk;
        }
      }

      $ygg=0;
      foreach($array_a_retornar as $keys)
      {
        for ($i=count($keys["valor"]); $i <$maxlines ; $i++) {
          // code...
          $array_a_retornar[$ygg]["valor"][]="";
          $array_a_retornar[$ygg]["size"][]=$array_a_retornar[$ygg]["size"][0];
          $array_a_retornar[$ygg]["aling"][]=$array_a_retornar[$ygg]["aling"][0];
        }
        $ygg++;
      }
      $data=$array_a_retornar;
      $total_lineas=count($data[0]["valor"]);
      $total_columnas=count($data);


      $he = 4*$total_lineas;
      for ($i=0; $i < $total_lineas; $i++) {
        // code...
        $y = $this->GetY();
        if($y + $he > 274){
            $this-> AddPage();
        }
        for ($j=0; $j < $total_columnas; $j++) {
          // code...
          $salto=0;
          $abajo="LR";
          if ($i==0) {
            // code...
            $abajo="TLR";
          }
          if ($j==$total_columnas-1) {
            // code...
            $salto=1;
          }
          if ($i==$total_lineas-1) {
            // code...
            $abajo="BLR";
          }
          if ($i==$total_lineas-1&&$i==0) {
            // code...
            $abajo="1";
          }
          // if ($j==0) {
          //   // code...
          //   $abajo="0";
          // }
          $str = $data[$j]["valor"][$i];
          if ($str=="\b")
          {
            $abajo="0";
            $str="";
          }
          //$abajo="0";
          $this->Cell($data[$j]["size"][$i],5,$str,$abajo,$salto,$data[$j]["aling"][$i],0);
        }
      }
    }
    // Cabecera de página\
    var $infoext =   array();
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,'ESTO SALE EN LA Pagina '.$this->PageNo().'/{nb}',0,0,'C');

    }
    public function Header()
    {
        $set_x = $this->getX();
        $set_y = 2;
        $this->SetLineWidth(.5);
        $this->SetFillColor(255,255,255);

        $this->AddFont('latin','','latin.php');

        $this->SetFont('latin','',8);

        if($this->PageNo()==1)
        {

        $this->Cell(0,10,utf8_decode('Es la pagina '.$this->infoext['nombre_empresa'].$this->PageNo().'/{nb}'),0,1,'C');
        }

    }
    function __construct($orientation='P', $unit='', $size='A4',$value,$tel,$logo,$jdas,$pas, $infoext){
      parent::__construct($orientation, $unit, $size);
      $this->$infoext=$infoext;
      $this->$value=$value;
      $this->$tel=$tel;
      $this->$logo=$logo;
      $this->$jdas;
      $this->infoext=$infoext;

    }

    public function set($value,$tel,$logo,$jdas,$pas,$infoext)
    {
      $this->a=$value;
      $this->b=$tel;
      $this->c=$logo;
      $this->d=$jdas;
      $this->e=$pas;
      $this->infoext = $infoext;
    }

}

date_default_timezone_set("America/El_Salvador");
$pdf = new PDF('P','mm', 'Letter', $nombrelab,$telefono1,$logo,$jdas,1, $infoext);
$jdas="";
//$pdf->set($infoext);
$pdf->SetMargins(15,15);
$pdf->SetTopMargin(10);
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,15);
$pdf->AddFont('Georgia','','georgia.php');
$pdf->AddFont('Arial','','calibri.php');
$pdf->AddFont('Arial','B','calibrib.php');
$pdf->AddFont('latin','','latin.php');
$pdf->AddFont('GeorgiaI','','GeorgiaI.php');
$pdf->AddFont('GeorgiaBI','','GeorgiaBI.php');
$pdf->AddPage();


$array_data = array(
  array(utf8_decode("Calcula los años de tu vida. ¿Quieres calcular tu edad de la manera más exacta posible? Con la calculadora de edad actual que te hemos preparado no solo ..."),100,"C"),
  array("DOSIS",45,"C"),
  array("otra columna",45,"C")
);

$pdf->LineWriteB($array_data);

$array_data = array(
  array(utf8_decode("Calcula los años de tu vida. ¿Quieres calcular tu edad de la manera más exacta posible? Con la calculadora de edad actual que te hemos preparado no solo ..."),100,"C"),
  array("DOSIS",45,"C"),
  array("otra columna",45,"C"),
);
$pdf->LineWriteB($array_data);


$array_data=array(
  array(utf8_decode("Calcula los anios de vida"),45,'C'),
  array(utf8_decode("Hola este es otra matris"),45, 'C')
);
$pdf->LineWriteB($array_data);




$pdf->AddPage();
$pdf->AddPage();

/*
    $array_data = array(
        array("MEDICAMENTO",100,"C"),
        array("DOSIS",45,"C"),
    );
    $pdf->SetTextColor(0, 0, 0);

    $pdf->LineWriteB($array_data);
*/


ob_clean();
$pdf->Output("receta_pdf.pdf","I");

function setear_string($string){
    $nuevo_string = "";
    $activo = 0;
    for($a = 0; $a < strlen($string); $a++){
        if($activo >= 40){
            $nuevo_string.= " \n ";
            $activo = 0;
        }
        $nuevo_string.= $string[$a];
        $activo++;
    }
    return $nuevo_string;
}


function mesesdiff($inicio, $fin){
  $datetime1=new DateTime($inicio);
  $datetime2=new DateTime($fin);

  # obtenemos la diferencia entre las dos fechas
  $interval=$datetime2->diff($datetime1);

  # obtenemos la diferencia en meses
  $intervalMeses=$interval->format("%m");
  # obtenemos la diferencia en años y la multiplicamos por 12 para tener los meses
  $intervalAnos = $interval->format("%y")*12;

  return ($intervalMeses+$intervalAnos);
}
?>
