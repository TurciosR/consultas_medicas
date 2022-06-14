<?php
    require('_core.php');
    //require 'fpdf/fpdf.php';
    require('fpdf/fpdf.php');
    $sql_pacientes='SELECT nombres, apellidos, sexo, fecha_nacimiento, direccion FROM paciente';
    $query_paciente = _query($sql_pacientes);
    //$array_paciente=_fetch_array($query_paciente);
    //$row_paciente=_fetch_array($query_paciente);

    class PDF extends FPDF{

        
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
        
        public function __construct($orientation='P', $unit='', $size='A4'){
            parent::__construct($orientation, $unit, $size);


        }

       public function Header(){
         //$this->SetY(-15);
         if($this->page==1){
          $this->Cell(50,10,utf8_decode("Este es el titulo"),0,0);
          $this->Ln(14);
         }



        }
        public function Fotter(){
            
        }
    }

    date_default_timezone_set("America/El_Salvador");
    $pdf = new PDF('P','mm', 'Letter');
    $jdas="";
    //$pdf->set($infoext);
    $pdf->SetMargins(15,15);
    $pdf->SetTopMargin(10);
    $pdf->SetLeftMargin(10);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true,15);
    
    $pdf->SetFont('helvetica','',10);
    $pdf->AddFont('Georgia','','georgia.php');
    $pdf->AddFont('Arial','','calibri.php');
    $pdf->AddFont('Arial','B','calibrib.php');
    $pdf->AddFont('latin','','latin.php');
    $pdf->AddFont('GeorgiaI','','GeorgiaI.php');
    $pdf->AddFont('GeorgiaBI','','GeorgiaBI.php');
    $pdf->AddPage();
    while($_fila_row=_fetch_array($query_paciente)){
        $array_date=array(
            array($_fila_row['nombres'], 30, 'C'),
            array($_fila_row['apellidos'], 30, 'C'),
            array($_fila_row['sexo'], 20, 'C'),
            array($_fila_row['fecha_nacimiento'],30 , 'C'),
            array($_fila_row['direccion'], 85, 'C')
        );
        $pdf->LineWriteB($array_date);
    }
    $pdf->Output();


?>