<?php
error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
//require('fpdf/fpdf.php');
class PDF extends FPDF{
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
        if($y + $he > 215){
            $this-> AddPage();
            $this-> SetY(20);
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
        $this->SetX(5);
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
    public function headexa($altura,$nn)
    {
      $this->m=$altura;
      $this->v=$catt;

      $this->SetFont('latin','',11);
      $this->SetXY($set_x, $this->m-5);
      $this->SetFillColor(178, 207, 255);
      $this->Cell(215,5,utf8_decode($this->v),0,1,'C',1);
      $this->SetFillColor(255,255,255);
      $this->Line(10,$this->m,205, $this->m);
      $this->SetFont('latin','',10);

      $this->SetXY(70, $this->m);
      $this->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
      $this->SetXY(110, $this->m);
      if($this->e==0)
      {
        $this->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
        $this->SetXY(170, $this->m);
      }
      $this->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
    }
    public function headexaC($altura,$catt)
    {
        $this->m=$altura+175;
        $this->v=$catt+175;

        $this->SetFont('latin','',11);
        $this->SetXY($set_x, $this->m-5);
        $this->SetFillColor(178, 207, 255);
        $this->Cell(215,5,utf8_decode($this->d),0,1,'C',1);
        $this->SetFillColor(255,255,255);
        $this->Line(10,$this->m,205, $this->m);
        $this->SetFont('latin','',10);
        //$this->SetXY(10, $this->m);
        //$this->Cell(50,5,utf8_decode("PARAMETRO"),0,1,'L');
        $this->SetXY(70, $this->m);
        $this->Cell(50,5,utf8_decode("RESULTADO"),0,1,'L');
        $this->SetXY(110, $this->m);
        if($this->e==0)
        {
          $this->Cell(205,5,utf8_decode("VALORES DE REFERENCIA"),0,1,'L');
          $this->SetXY(170, $this->m);
        }
        $this->Cell(50,5,utf8_decode("UNIDADES"),0,1,'L');
    }
}



$pdf = new PDF('P','mm', 'Letter');
$pdf->SetMargins(10,5);
$pdf->SetTopMargin(2);
$pdf->SetLeftMargin(5);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,1);
$pdf->AddFont("Helvetica","","helvetica.php");
$pdf->AddPage();
$pdf->SetFont('Arial','',10);


$id_cita = $_REQUEST["id_cita"];
$id_paciente = $_REQUEST["id_paciente"];

$query = _query("SELECT r.id_paciente, r.motivo_consulta, r.diagnostico, r.examen, r.medicamento, r.fecha_cita, r.hora_cita, r.peso, CONCAT(d.nombres,' ',d.apellidos) as doctor FROM reserva_cita as r, doctor as d WHERE d.id_doctor=r.id_doctor AND r.id ='$id_cita'");
$datos = _fetch_array($query);
$id_paciente = $datos["id_paciente"];
$doctor = $datos["doctor"];
$fecha_cita = nombre_dia($datos["fecha_cita"]);
$fecha = $datos["fecha_cita"];
$peso = $datos["peso"];
$hora_cita = hora($datos["hora_cita"]);
$sql_ed = _query("SELECT fecha_nacimiento FROM paciente WHERE id_paciente='$id_paciente'");
$datos_ed = _fetch_array($sql_ed);
$edad = edad($datos_ed["fecha_nacimiento"]);
$query_receta = _query("SELECT m.* , r.id_medicamento,r.dosis FROM receta as r, ".EXTERNAL.".producto as m WHERE m.id_producto=r.id_medicamento AND r.id_cita='$id_cita' AND r.id_paciente='$id_paciente'");
$query_aux = _query("SELECT * FROM reserva_cita WHERE id='$id_cita'");

$pdf->setXY(70,53.5);
$pdf->Cell(50,5,utf8_decode(buscar($id_paciente)),0,1,'L');
$pdf->setXY(145,63.5);
$pdf->Cell(50,5,$edad,0,1,'L');
$pdf->setXY(180,63.5);
$pdf->Cell(50,5,date("d:m:Y"),0,1,'L');
$pdf->setXY(145,78.5);
$pdf->Cell(50,5,$peso,0,1,'L');

$query_receta = _query("SELECT m.* , r.id_medicamento,r.dosis FROM receta as r, medicamento as m WHERE m.id_medicamento=r.id_medicamento AND r.id_cita='$id_cita'");
while ($row = _fetch_array($query_receta))
{
    $pdf-> setX(70);
    $count = $pdf->getY();
    $pdf->setXY(70,($count+5));
    $pdf->Cell(50,5,"**-".$row["descripcion"],0,1,'L');
    $count = $pdf->getY();
    $pdf->setXY(70,($count+5));
    $pdf->Cell(50,5,$row["dosis"],0,1,'L');
    //echo "_________________________________________<br>";
}


$query_aux = _query("SELECT * FROM reserva_cita WHERE id='$id_cita'");
$aux = _fetch_array($query_aux);
$otros = $aux["medicamento"];
$otr = explode("|", $otros);

for ($i=0; $i<count($otr); $i++)
{
    $count = $pdf->getY();
    $pdf->setXY(145,($count+5));
    $pdf->Cell(50,5,$otr[$i],0,1,'L');
}


    
ob_clean();
$pdf->Output("receta.pdf","I");
