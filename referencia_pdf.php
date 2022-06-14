<?php

error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');

$id_sucursal=$_SESSION["id_sucursal"];
$id_cita=$_REQUEST["id_cita"];
if (!function_exists('set_magic_quotes_runtime')) {
    function set_magic_quotes_runtime($new_setting) {
        return true;
    }
}   

$sqll = _query("SELECT * FROM empresa where id_empresa='$id_sucursal'");
$fila = _fetch_array($sqll);
$nombre = $fila["nombre"];
$direccion = $fila["direccion"];
$telefono1 = $fila["telefono1"];
$telefono2 = $fila["telefono2"];
$id_municipio = $fila['municipio'];
$id_departamento = $fila['departamento'];
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

/* INFORMACION SOBRE LA REFERENCIA */
$sql_referencia = "SELECT * FROM referencia WHERE id_cita = '$id_cita'";
$query_referencia = _query($sql_referencia);
$row_referencia = _fetch_array($query_referencia);
$destino = $row_referencia['destino'];
$motivo = $row_referencia['motivo'];
$observaciones  = $row_referencia['observaciones'];
$id_doctor = $row_referencia['id_doctor'];


$sql_consulta = "SELECT * FROM reserva_cita WHERE id= '$id_cita'";
$query_consulta = _query($sql_consulta);


if(_num_rows($query_consulta) > 0){
    $row_consulta = _fetch_array($query_consulta);
    $id_paciente = $row_consulta['id_paciente'];
    $fecha_constancia = $row_consulta['fecha_cita'];
    $sql_paciente = "SELECT * FROM paciente WHERE id_paciente = '$id_paciente'";
    $query_paciente = _query($sql_paciente);
    $row_paciente = _fetch_array($query_paciente);

    $nombres_paciente = $row_paciente['nombres'];
    $apellidos_paciente = $row_paciente['apellidos'];
    $nombre_paciente = $nombres_paciente." ".$apellidos_paciente;
    $sexo_paciente = $row_paciente['sexo'];
    $edad = edad($row_paciente['fecha_nacimiento']);
    $expediente = $row_paciente['expediente'];

    $sql_doctor = "SELECT * FROM doctor WHERE id_doctor = '$id_doctor'";
    $query_doctor = _query($sql_doctor);
    $row_doctor = _fetch_array($query_doctor);
    $nombres_doctor = $row_doctor['nombres'];
    $apellidos_doctor = $row_doctor['apellidos'];
    $jvpm = $row_doctor['jvpm'];
    $nombre_doctor  = $nombres_doctor." ".$apellidos_doctor;


}

$infoext =  array(
    'nombre_empresa' => $nombre,
    'direccion' => $direccion,
    'telefono1' => $telefono1,
    'telefono2' => $telefono2,
    'email' => $email,
    'logo' => $logo,
    'nombre_paciente' => $nombre_paciente,
    'nombre_doctor' => $nombre_doctor,
    'sexo_paciente' => $sexo_paciente,
    'edad' => $edad,
    'expediente' => $expediente,
    'fecha_constancia' => $fecha_constancia,
    'padecimiento' => $padecimiento,
    'nombre_direccion' => $nombre_direccion,
    'reposo' => $reposo,
    'jvpm' => $jvpm,
    'destino' => $destino,
    'motivo' => $motivo,
    'observaciones' => $observaciones
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
          $this->Cell($data[$j]["size"][$i],4,$str,$abajo,$salto,$data[$j]["aling"][$i],1);
        }

        $this->setX(55);
      }
      /*
      $arreglo_valores = array();
      $hei = 4 * $total_lineas;
        for($i = 0; $i < $total_columnas ; $i++){
            $valor_p="";
            $size_p = 0;
            for($j = 0; $j < $total_lineas; $j++){
                $valor_p.=" ".$data[$i]["valor"][$j];
                $size_p=$data[$i]["size"][$j];
            }
            $arreglo_valores[] = array(
                'valor' => $valor_p,
                'size' => $size_p
            );
        }
        $count = 0;
        $y = $this->GetY();
        if($y + $hei > 274){
            $this-> AddPage();
        }
        foreach ($arreglo_valores as $key => $value) {
            if($count == 0){
                $this->drawTextBox($value['valor'], $value['size'], $hei, "C", 'M',1,1);
            }
            else{

                $this->drawTextBox($value['valor'], $value['size'], $hei, "C", 'M',1,0);
            }
            $count++;
        }

        $this->Ln($hei);
        */
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
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');

        $this->SetY(-20);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode($this->infoext['nombre_direccion']),0,0,'C');

        $this->SetY(-25);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode($this->infoext['direccion']),0,0,'C');





        $this->SetY(-60);
        // Arial italic 8
        $this->SetFont('Times','B',14);
        // Número de página
        $this->Cell(0,10,utf8_decode("JVPM: #".$this->infoext['jvpm']),0,0,'C');

        $this->SetY(-65);
        // Arial italic 8
        $this->SetFont('Times','B',14);
        // Número de página
        $this->Cell(0,10,utf8_decode("Dr. ".$this->infoext['nombre_doctor']),0,0,'C');

        
        $this->Line(63,215,150,215);
    }
    public function Header()
    {
        //$this->Image("img/fondo_reporte.png",0,0,220,280);
        if ($this->PageNo() == 1){
            $set_x = $this->getX();
            $set_y = 2;
            $this->SetLineWidth(.5);
            $this->SetFillColor(255,255,255);

            $this->Image($this->infoext['logo'],$set_x,$set_y,190,45);
            $this->SetDrawColor(0,0,0);
            $this->SetFont('Courier', 'B', 19);
            $this->SetTextColor(25, 65, 96);
            $set_y +=10;
            $this->setY($set_y+5);
            //$this->Cell(160,7,utf8_decode($this->infoext['nombre_empresa']),0,1,'L');
            $set_y +=5;
            $this->setY($set_y+15);
            $this->SetFont('Courier', 'B', 16);
            $this->Cell(180,7,utf8_decode("Dr. ".$this->infoext['nombre_doctor']),0,1,'R');
            $this->setY($set_y+23);
            $this->SetFont('Courier', 'B', 18);
            $this->Cell(140,7,"REFERENCIA",0,1,'R');   
            $texto_poner = "El infrascrito  médico, hace constar que ".Mayu($this->infoext['nombre_paciente']);
            $texto_poner.= " de ".$this->infoext['edad']." años de edad  consulto este día esta clínica ";
            $texto_poner.= " y fue referido a ".Mayu(utf8_decode($this->infoext['destino']))."; por el motivo de ".Mayu(utf8_decode($this->infoext['motivo']))."";
            $texto_poner .= " con el siguiente plan ".$this->infoext['observaciones']." ";
            $texto_poner = utf8_decode($texto_poner); 
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Times', '', 12);
            $this->SetXY(30,65);
            $this->MultiCell(150,5,utf8_decode($this->infoext['nombre_direccion']),0,"J",0);
            $this->SetXY(30,72);
            $this->MultiCell(150,5,utf8_decode(nombre_dia(ED($this->infoext['fecha_constancia']))),0,"J",0);
            $this->SetXY(30,89.5);
            $this->MultiCell(150,5,"A Quien Interese",0,"J",0);
            $this->SetXY(30,96.5);
            $this->MultiCell(150,5,"Presente",0,"J",0);
            $this->SetXY(30,114);
            $this->MultiCell(150,7,$texto_poner,0,"J",0);
            
            $this->SetXY(30,145.5);
            $this->MultiCell(150,7,utf8_decode("Extendiendo la presente referencia médica para los usos que se estimen convenientes."),0,"J",0);

            

        }
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
$pdf = new PDF('P','mm', 'Letter');
$jdas="";
$pdf->set($nombrelab,$telefono1,$logo,$jdas,1,$infoext);
$pdf->SetMargins(15,15);
$pdf->SetTopMargin(10);
$pdf->SetLeftMargin(13);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,15);
$pdf->AddPage();

//QUERY PARA DATOS DEL PACIENTE
//DATOS DEL PACIENTE


$set_x = $pdf->getX();
$set_y = $pdf->getY();
$pdf->SetFont('Courier', 'B', 12);
$count = 70;
$pdf->setY($pdf->getY()-115);










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