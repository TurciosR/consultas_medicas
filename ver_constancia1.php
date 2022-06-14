<?php

error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
//require('fpdf/fpdf.php');
define('FPDF_FONTPATH','LibPDF/font/');
//require('LibPDF/fpdf.php');
require('LibPDF/jlpdf.php');
date_default_timezone_set('America/El_Salvador');


class PDF extends JLPDF{


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

/**
     * Write a line in a table
     *
     * handles the writing inside a cell controlling the avoiding of the text
     *
     * @author Ing. Raul Méndez
     * @var array $array
     */
    function LineWriteText(array $array, bool $fill = false, bool $is_border = true)
    {
        $max_line = 1;
        $data = array();

        foreach ($array as $key => $item) {

            // get the information to draw
            $text  = $item['name'];
            $aling = $item['align'];
            $width  = $item['width'];

            $jk = 0;
            $w = $width;
            $border = 0;

            if (!isset($this->CurrentFont))
                $this->Error('No font has been set');

            $caracter_width = &$this->CurrentFont['cw'];

            if ($w == 0)
                $w = $this->w - $this->rMargin - $this->x;

            $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
            $s = str_replace("\r", '', $text);
            $nb = strlen($s);

            if ($nb > 0 && $s[$nb - 1] == "\n")
                $nb--;

            $b = 1;

            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $ns = 0;
            $nl = 1;

            while ($i < $nb) {
                // Get next character
                $c = $s[$i];
                if ($c == "\n") {
                    $data[$key]["name"][] = substr($s, $j, $i - $j);
                    $data[$key]["width"][] = $width;
                    $data[$key]["aling"][] = $aling;
                    $jk++;

                    $i++;
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                    $nl++;
                }
                if ($c == ' ') {
                    $sep = $i;
                    $ns++;
                }
                $l += $caracter_width[$c];
                if ($l > $wmax) {
                    // Automatic line break
                    if ($sep == -1) {
                        if ($i == $j)
                            $i++;
                        $data[$key]["name"][] = substr($s, $j, $i - $j);
                        $data[$key]["width"][] = $width;
                        $data[$key]["aling"][] = $aling;
                        $jk++;
                    } else {
                        $data[$key]["name"][] = substr($s, $j, $sep - $j);
                        $data[$key]["width"][] = $width;
                        $data[$key]["aling"][] = $aling;
                        $jk++;

                        $i = $sep + 1;
                    }
                    $sep = -1;
                    $j = $i;
                    $l = 0;
                    $ns = 0;
                    $nl++;
                } else
                    $i++;
            }
            // Last chunk
            if ($this->ws > 0) {
                $this->ws = 0;
            }
            if ($border && strpos($border, 'B') !== false)
                $b .= 'B';
            $data[$key]["name"][] = substr($s, $j, $i - $j);
            $data[$key]["width"][] = $width;
            $data[$key]["aling"][] = $aling;
            $jk++;
            $key++;
            if ($jk > $max_line) {
                // code...
                $max_line = $jk;
            }
        }

        foreach ($data as $key => $item) {
            for ($i = count($item["name"]); $i < $max_line; $i++) {

                $data[$key]["name"][]  = "";
                $data[$key]["width"][] = $data[$key]["width"][0];
                $data[$key]["aling"][] = $data[$key]["aling"][0];
            }
        }

        $data = $data;
        $total_lines   = count($data[0]["name"]);
        $total_columns = count($data);

        for ($i = 0; $i < $total_lines; $i++) {
            for ($j = 0; $j < $total_columns; $j++) {
                $ln = 0;
                $border = 0;
                if ($is_border) {
                    
                    $border = "LR";
                    if ($i == 0) {
                        $border = "TLR";
                    }
                    if ($i == $total_lines - 1) {
                        $border = "BLR";
                    }
                    if ($i == $total_lines - 1 && $i == 0) {
                        $border = "1";
                    }
                }
                if ($j == $total_columns - 1) {
                    $ln = 1;
                }

                // A line break is created if the cell is cut to half a page
                if ($this->GetY() + 5 > (279.4 - 25)) {
                    $this->Line(
                        $this->GetX(),
                        $this->GetY(),
                        $this->GetX() + $this->total_width,
                        $this->GetY()
                    );
                }

                // draw the cell
                $this->Cell(
                    $data[$j]["width"][$i],
                    5,
                    utf8_decode($data[$j]["name"][$i]),
                    $border,
                    $ln,
                    $data[$j]["aling"][$i],
                    $fill
                );
            }
        }
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

    }
    var $adicional=array();
    public function setAdicional($adicional){
        $this->adicional=$adicional;
    }
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        //$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');

        $this->SetY(-40);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode("CLINICA MEDICA INTEGRAL DR TORRES"),0,0,'C');

        $this->SetY(-45);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode("Barrio el Centro, Lolotique San Miguel, Autorización 906 por CSSP"),0,0,'C');





        $this->SetY(-60);
        // Arial italic 8
        $this->SetFont('Times','B',14);
        // Número de página
        $this->Cell(0,10,utf8_decode("DUI:04026772-3",0,0,'C'));

        $this->SetY(-65);
        // Arial italic 8
        $this->SetFont('Times','B',14);
        // Número de página
        $this->Cell(0,10,utf8_decode("Dr. Jeremias A Torres Rodriguez"),0,0,'C');


        $this->Line(63,215,150,215);
    }

    public function obtenerFechaEnLetra($fecha){

        $dia= $this->conocerDiaSemanaFecha($fecha);

        $num = date("j", strtotime($fecha));

        $anno = date("Y", strtotime($fecha));

        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

        $mes = $mes[(date('m', strtotime($fecha))*1)-1];

        return $dia.', '.$num.' de '.$mes.' del '.$anno;

    }
    
    public function conocerDiaSemanaFecha($fecha) {

        $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');

        $dia = $dias[date('w', strtotime($fecha))];

        return $dia;

    }



    public function Header()
    {
        //$this->Image("img/fondo_reporte.png",0,0,220,280);
        if ($this->PageNo() == 1){
            $this->SetFont('Helvetica', 'B', 14);

            $set_x = $this->getX();
            $set_y = $this->getY();
            $negocio = "CLINICA MEDICA INTEGRAL DR TORRES";
            $direccion="Barrio el Centro, Lolotique San Miguel, Autorización 906 por CSSP";
            $fecha_constancia=$this->adicional['fecha_constancia'];
            $tipo_constancia=$this->adicional['tipo_constancia'];

            $set_y=$this->GetY();
            $set_x=$this->GetX();
            
            $set_x+=40;
            $this->SetY($set_y);
            $this->SetX($set_x);
            $this->Cell(100, 10, utf8_decode($negocio), 0,0,'C');
            $this->Ln();

            $this->SetFont('Helvetica', '', 14);
            $set_x-=50;
            $set_y+=8;
            $this->SetY($set_y);
            $this->SetX($set_x);
            $this->Cell(200, 10, utf8_decode($direccion), 0, 0, 'C');
            $this->Ln();
            
            $set_x+=125;
            $set_y+=25;
            $this->SetY($set_y);
            $this->SetX($set_x);
            $this->SetFont('Helvetica', '', 11);
            if($tipo_constancia!='carta'){
              $this->Cell(50, 10, "Fecha: ".$fecha_constancia, 0, 0, 'C');  
            }
            
            
            $set_x=40;
            $set_y+=10;
            $this->SetY($set_y);
            $this->SetX($set_x);
            $this->SetFont('Helvetica', 'B', 11);
            if($tipo_constancia=="medica"){
                $this->Cell(50, 10, utf8_decode("CONSTANCIA DE INCAPACIDAD  MÉDICA"), 0, 0, 'C'); 
            }else if($tipo_constancia=="muerte"){
                $this->Cell(50, 10, utf8_decode("CONSTANCIA DE DEFUNCION"), 0, 0, 'C');
            }else if($tipo_constancia=="examenes"){
                $this->Cell(50, 10, utf8_decode("CONSTANCIA  DE  SALUD  MÉDICA"), 0, 0, 'C');
            }
            
            $this->Ln();

               
            $set_y+=20;       
            $this->SetY($set_y);
            $this->SetFont('Helvetica', '', 12);
            if($tipo_constancia!="muerte"){
                $this->Cell(50, 10, utf8_decode("A quien corresponda"), 0, 0, 'L');
            }
            
        }
    }

}


function generar_constancia_medica($pdf, $tipo_constancia){
    $pdf->SetMargins(25,25);
    $pdf->SetTopMargin(10);
    $pdf->SetLeftMargin(25);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true,15);

    //obteniendo los datos 
    $fecha_medica=$_GET["fecha_medica"];
    $nombre_paciente_medica=$_GET["nombre_paciente_medica"];
    $direccion_paciente_medica=$_GET["direccion_paciente_medica"];
    $padecimiento_medica=$_GET["padecimiento_medica"];
    $reposo_medica=$_GET["reposo_medica"];
    $dui_medica=$_GET["dui_medica"];
    $expediente_medica=$_GET["expediente_medica"];
    $edad_medica=$_GET["edad_medica"];

    $adicional= array(
        'tipo_constancia'=>$tipo_constancia,
        'fecha_constancia'=>$fecha_medica,
    );
    $pdf->setAdicional($adicional);

    $pdf->AddPage();



    $constancia_medica='
    El infrascrito Medico inscrito en la Junta de Vigilancia de la Profesión Médica de El Salvador numero 19656  certifica, con motivo de hacer saber las causas por las cuales la paciente: [b]'.$nombre_paciente_medica.'[/b] de [b]'.$edad_medica.'[/b] años de edad, Residente de [b]'.$direccion_paciente_medica.'[/b]
    
    numero de DUI: [b]'.$dui_medica.'[/b] y número de expediente clínico [b]'.$expediente_medica.'[/b] en esta institución de salud, se presenta con sintomatología de: [b]'.$sintomas.'[/b] diagnosticando: [b]'.$diagnostico.'[/b] Hoy dicha sintomatología persiste con malestar general.
    
    Otorgo reposo e incapacidad laboral [/b]'.$reposo_medica.'[/b] días por  referido  cuadro  clínico-medico a partir de este día, del presente mes, mientras cumple analgesia o antibioticoterapia endovenosa.
    ';

    $set_x=$pdf->GetX();
    $set_y=$pdf->GetY();

    $set_y+=20;
    $pdf->SetY($set_x);
    $pdf->SetY($set_y);
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->JLCell(utf8_decode($constancia_medica), 160, 'j');

    //de lo contrario no hay registros que mostrar
    ob_clean();
    $pdf->Output("receta_pdf.pdf","I");

}

function generar_constancia_muerte($pdf, $tipo_constancia){
    $fecha=date('Y-m-d');
    $fecha_letras=fecha_a_letras($fecha);
    $pdf->SetMargins(25,25);
    $pdf->SetTopMargin(10);
    $pdf->SetLeftMargin(25);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true,15);

    
    
    $fecha_muerte=$_GET["fecha_muerte"];
    $nombre_muerto=$_GET["nombre_muerto"];
    $direccion_paciente_muerte=$_GET["direccion_paciente_muerte"];
    $edad_muerto=$_GET["edad_muerto"];
    $antecedentes_medicos=$_GET["antecedentes_medicos"];
    $hora_muerte=$_GET["hora_muerte"];
    $causa_muerte=$_GET["causa_muerte"];
    $dui_fallecido=$_GET["dui_fallecido"];

    $adicional=[
        'tipo_constancia'=>$tipo_constancia,
        'fecha_constancia'=>$fecha_muerte
    ];
    $pdf->setAdicional($adicional);

    $pdf->AddPage();

    $constancia_muerte='
    Se extiende el presente certificado de defunción a [b]'.$nombre_muerto.'[/b] de [b]'.$edad_muerto.'[/b] años de edad, según DUI: [b]'.$dui_fallecido.'[/b] Residente de: [b]'.$direccion_paciente_muerte.'[/b]. 

    Quien con antecedentes médicos de [b]'.$antecedentes_medicos.'[/b] [b]TRATADO CON MEDICAMENTOS VARIOS (ORIGEN NATURAL)[/b] entre otros, se evalúa en mal estado general. 

    [b]ESTE DIA FALLECE LAS '.$hora_muerte.' A CAUSA DE '.strtoupper($causa_muerte).'[/b] Y para los usos que se estime conveniente se expide el presente certificado en la ciudad de Lolotique, San Miguel, El Salvador a los '.$fecha_letras.'
    ';
    $set_x=$pdf->GetX();
    $set_y=$pdf->GetY();

    $set_y+=10;
    $pdf->SetY($set_x);
    $pdf->SetY($set_y);
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->JLCell(utf8_decode($constancia_muerte), 160, 'l');

    //de lo contrario no hay registros que mostrar
    ob_clean();
    $pdf->Output("receta_pdf.pdf","I");

}

function generar_constancia_de_examenes($pdf, $tipo_constancia){
    $pdf->SetMargins(10,10);
    $pdf->SetTopMargin(10);
    $pdf->SetLeftMargin(10);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true,15);

    $paciente_examenes=$_GET["paciente_examenes"];
    $fecha_examenes=$_GET["fecha_examenes"];
    $dui_examenes=$_GET["dui_examenes"];
    $direccion_paciente_examenes=$_GET["direccion_paciente_examenes"];
    $expediente_examenes=$_GET["expediente_examenes"];
    $examen_orina=$_GET["examen_orina"];
    $examen_heses=$_GET["examen_heses"];
    $examen_rayos_x=$_GET["examen_rayos_x"];
    $tipo_constancia=$_GET["examenes"];
    $edad_examenes=$_GET['edad_examenes'];
    $fecha_hoy=date('Y-m-d');
    $fecha_hoy_letra=fecha_a_letras($fecha_hoy);

    $adicional=[
        'tipo_constancia'=>$tipo_constancia,
        'fecha_constancia'=>$fecha_examenes
    ];
    $pdf->setAdicional($adicional);
    $pdf->AddPage();

    $constancia_examenes='
    El infrascrito Medico inscrito en la Junta de Vigilancia de la Profesión Médica de El Salvador 
    numero 19656 certifica, con motivo de hacer saber las causas por las cuales: 

    [b]'.$paciente_examenes.'[/b] de [b]'.$edad_examenes.'[/b] años  de edad, numero de DUI: [b]'.$dui_examenes.'[/b], residente de 
    [b]'.$direccion_paciente_examenes.'[/b] y número de  expediente clínico [b]'.$expediente_examenes.'[/b] en esta institución 
    de salud 
    Se presenta   para evaluación, aprobación de exámenes  y  análisis clínicos, el cual se analiza:

    [b]EXAMEN  GENERAL DE HECES: '.$examen_orina.'[/b]
    [b]EXAMEN GENERAL DE ORINA: '.$examen_orina.'[/b]

    [b]RAYOS X TORAX: '.$examen_rayos_x.'[/b]

    Motivo por el cual doy bajo mi supervisión, visto  bueno de exámenes clínicos y goza de  buena 
    salud física y orgánica. Y a solicitud del interesado estime conveniente, se extiende la presente 
    en  Clínica  Medica Dr. Torres, Lolotique San Miguel, a los '.$fecha_hoy_letra.' 
    ';
    $set_x=$pdf->GetX();
    $set_y=$pdf->GetY();

    $set_y+=10;
    $pdf->SetY($set_x);
    $pdf->SetY($set_y);
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->JLCell(utf8_decode($constancia_examenes), 200, 'l');

    //de lo contrario no hay registros que mostrar
    ob_clean();
    $pdf->Output("receta_pdf.pdf","I");
}


function generar_constancia_de_recomendacion($pdf, $tipo_constancia){
    $pdf->SetMargins(10,10);
    $pdf->SetTopMargin(10);
    $pdf->SetLeftMargin(10);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true,15);

    $fecha_hoy=date('Y-m-d');
    $fecha_hoy_letra=fecha_a_letras($fecha_hoy);
    $nombre_recomendado=$_GET["nombre_recomendado"];
    $edad_recomendado=$_GET["edad_recomendado"];
    $dui_recomendado=$_GET["dui_recomendado"];
    $direccion_recomendado=$_GET["direccion_recomendado"];


    $adicional=[
        'tipo_constancia'=>$tipo_constancia,
        'fecha_constancia'=>$fecha_examenes
    ];
    $pdf->setAdicional($adicional);
    $pdf->AddPage();


    $constancia_recomendacion='Yo, Jeremías  Anselmo  Torres  Rodríguez, mayor de edad, Doctor en Medicina en [b]CLINICA MEDICA 

INTEGRAL DR TORRES[/b], Municipio de Lolotique, Departamento de San Miguel a través de la presente 

manifiesto que conozco al/a  joven  [b]'.$nombre_recomendado.'[/b] de [b]'.$edad_recomendado.'[/b] años  de edad, numero de 
DUI: [b]'.$dui_recomendado.'[/b],

residente de:[b]'.$direccion_recomendado.'[/b] Y para los usos que el interesado estime conveniente, firmo la presente 

a los '.$fecha_hoy_letra.'.
    ';
    
    $set_x=$pdf->GetX();
    $set_y=$pdf->GetY();

    $set_y+=25;
    $pdf->SetY($set_x);
    $pdf->SetY($set_y);
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->JLCell(utf8_decode($constancia_recomendacion), 205, 'l');

    //de lo contrario no hay registros que mostrar
    ob_clean();
    $pdf->Output("receta_pdf.pdf","I");
}

date_default_timezone_set("America/El_Salvador");
$pdf = new PDF('P','mm', 'Letter');
 
if(isset($_GET['process'])){
    $tipo_constancia=$_GET['process'];
    switch ($tipo_constancia) {
        case 'medica':
            generar_constancia_medica($pdf, $tipo_constancia);
            break;
        case 'muerte':
            generar_constancia_muerte($pdf, $tipo_constancia);
            break;
        case 'examenes':
            generar_constancia_de_examenes($pdf, $tipo_constancia);
            break;
        case 'carta':
            generar_constancia_de_recomendacion($pdf, $tipo_constancia);
            break;
    }
}















function fecha_a_letras($fecha){
	$fecha = substr($fecha, 0, 10);
	$numeroDia = date('d', strtotime($fecha));
	$dia = date('l', strtotime($fecha));
	$mes = date('F', strtotime($fecha));
	$anio = date('Y', strtotime($fecha));
	$fecha_array=explode('-', $fecha);
	$num_dia=$fecha_array[2];
	$num_mes=$fecha_array[1];
	$num_anio=$fecha_array[0];
	//echo "".$mes;
	$dia_es="";
	$mes_es="";

	switch($dia){
		case "Monday":
			$dia_es="Lunes";
			break;
		case "Tuesday":
			$dia_es="Martes";
			break;
		case "Wednesday":
			$dia_es="Miércoles";
			break;
		case "Thursday":
			$dia_es="Jueves";
			break;
		case "Friday":
			$dia_es="Viernes";
			break;
		case "Saturday":
			$dia_es="Sábado";
			break;
		case "Sunday":
			$dia_es="Domingo";
			break;
	}

	switch($mes){
		case "January":
			$mes_es="Enero";
			break;
		case "February":
			$mes_es="Febrero";
			break;
		case "March":
			$mes_es="Marzo";
			break;
		case "April":
			$mes_es="Abril";
			break;
		case "May":
			$mes_es="Mayo";
			break;
		case "June":
			$mes_es="Junio";
			break;
		case "July":
			$mes_es="Julio";
			break;
		case "August":
			$mes_es="Agosto";
			break;
		case "September":
			$mes_es="Septiembre";
			break;
		case "October":
			$mes_es="Octubre";
			break;
		case "November":
			$mes_es="Noviembre";
			break;
		case "December":
			$mes_es="Diciembre";
			break;
	}

	$fecha_es=" ".$num_dia." dias del mes de ".$mes_es." del año ".$num_anio;

	return $fecha_es;
}

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
