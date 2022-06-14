<?php
    //error_reporting(E_ERROR | E_PARSE);
    require("_core.php");
    require("num2letras.php");
    require('fpdf/fpdf.php');
    //Obteniendo valores por metodo GET
    $id_sucursal=$_SESSION["id_sucursal"];
    $id_cita=$_REQUEST["id_cita"];
    $id_doctor = $_REQUEST['id_doctor'];

    $sqll=_query("SELECT * FROM empresa where id_empresa='$id_sucursal'");
    $fila=_fetch_array($sqll);
    $direccion=$fila['direccion'];
    $telefono1=$fila["telefono1"];
    $id_departamento=$fila["departamento"];
    $id_departamento=$fila['municipio'];
    $email=$fila['municipio'];
    $logo="img/6100841a1c79a_zyro.jpeg";

    $sql_consulta="SELECT * FROM reserva_cita WHERE id= '$id_cita'";
    $query_consulta=_query($sql_consulta);
    if(_num_rows($query_consulta)>0){
        $row_consulta=_fetch_array($query_consulta);
        $fecha_cita=ED($row_consulta['fecha_cita']);
        $hora_cita=_hora_media_decode($row_consulta['hora_cita']);
        $id_paciente=$row_consulta['id_paciente'];

        $sql_paciente="SELECT * FROM paciente WHERE id_paciente = '$id_paciente'";
        $query_paciente=_query($sql_paciente);
        $row_paciente=_fetch_array($query_paciente);

        $nombres_paciente=$row_paciente['nombres'];
        $apellidos_paciente=$row_paciente['apellidos'];
        $nombre_paciente=$nombres_paciente." ".$apellidos_paciente;
        $sexo_paciente=$row_paciente['sexo'];
        $fecha_nacimiento=$row_paciente['fecha_nacimiento'];
        $edad_paciente=edad($row_paciente['fecha_nacimiento']);
        $expediente=$row_paciente['expediente'];

        $sql_doctor="SELECT * FROM doctor WHERE id_doctor = '$id_doctor'";
        $query_doctor=_query($sql_doctor);
        $row_doctor=_fetch_array($query_doctor);
        $nombres_doctor=$row_doctor['nombres'];
        $apellidos_doctor=$row_doctor['apellidos'];
        $jvpm=$row_doctor['jvpm'];
        $nombre_doctor=$nombres_doctor." ".$apellidos_doctor;
    }

    class Reporte extends FPDF{
        private $encabezado;
        private $barra_lateral;
        private $cuerpo;
        private $datos_adicionales;

        function setEncabezado($encabezado){
            $this->encabezado=$encabezado;
        }
        function setBarraLateral($barra_lateral){
            $this->barra_lateral=$barra_lateral;
        }
        function setCuerpo($cuerpo){
            $this->cuerpo=$cuerpo;
        }
        function setDatosAdicionales($datos_adicionales){
            $this->datos_adicionales=$datos_adicionales;
        }
        public function LineWriteB($array)
        {
          $resolver=$this->GetX();
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
              if($j==0){
                $this->SetX($resolver);
              }
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
              
              
              $this->Cell($data[$j]["size"][$i],0.5,$str,0,$salto,$data[$j]["aling"][$i],0);
            }
    
            $this->setX(5.5);
          }
        }

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

        function Header(){
                //$this->SetLineWidth(.5);
                //$this->SetFillColor(255,255,255);

    
                $this->SetTextColor(0, 0, 0);
                $set_y=4.70;
                $set_x=7.2;
                $this->setY($set_y);
                $this->setX($set_x);
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(13.5,1,Mayu(utf8_decode($this->encabezado['paciente'])),0,1,'L');

                $set_y+=1;
                $this->SetY($set_y);
                $this->SetX($set_x);
                $this->Cell(8,1,(utf8_decode(str_pad($this->encabezado['expediente'], 6, '0', STR_PAD_LEFT))),0,1,'L');
                
                $set_x+=7.5;
                $this->SetY($set_y);
                $this->SetX($set_x);
                $this->Cell(2.5,1,Mayu(utf8_decode($this->encabezado['edad']." AÑOS")),0,1,'L');
                
                $set_x+=3.5;
                $this->SetY($set_y);
                $this->SetX(18);
                $this->Cell(2.5,1,(utf8_decode(MD($this->encabezado['fecha_cita']))),0,1,'L');
                
               /* //esto iria al ultimo ya que es la fecha de la proxima cita
                $set_y+=16.80;
                $this->SetY($set_y);
                $this->SetX(8.5);
                $fecha_proxima_cita=MD($this->datos_adicionales['fecha_proxima_cita']);
                $this->Cell(6.5, 1, (utf8_decode("             ".$fecha_proxima_cita)), 0, 1, 'L');*/
 
                

        }
        function Footer(){

        }  
    }

    date_default_timezone_set("America/El_Salvador");
    //definiendo los datos del encabezado
    $encabezado=array(
        'doctor'=>$nombre_doctor,
        'jvp'=>$jvpm,
        'paciente'=>$nombre_paciente,
        'fecha_nacimiento'=>$fecha_nacimiento,
        'fecha_cita'=>$fecha_cita,
        'hora_cita'=>$hora_cita,
        'edad'=>$edad_paciente,
        'expediente'=>$expediente,
        'logo'=>$logo

    );

    $datos_adicionales=array(
        'fecha_proxima_cita'=>'25/05/2021',
        'direccion1'=>'SAN MIGUEL: Esquina Opuesto a metrocentro, contiguo a farmacia Sarai, san Miguel',
        'horario1'=>'HORARIO: Lunes Miercoles y Viernes 8:00 am - 12m / 2:00pm-5:30pm. Martes - Jueves, y Sabado 1:30pm - 5:30pm.',
        'direccion2'=>'INTIPUCA: Frente a Unidad de Salud, Intipucá.',
        'horario2'=>'HORARIO: Martes, Jueves y Sabados 7:00 am - 12:00m',
        'final'=>'EMERGENCIA LAS 24 HORAS EN EL HOSPITAL DE DE SU PREFERENCIA. ',
        
    );
    
    $especialidades=array(
        '* Neumonias',
        '* Sinusitis',
        '* Otitis',
        '* Cálculos Renales',
        '* Infección de',
        '  vías urinarias',
        '* Dolor Crónico',
        '  de hombros',
        '  y de rodillas',
        '* Parasitismo',
        '* Diarreas',
        '* Osteoporosis',
        '* Hernias',
        '* Hipertensión',
        '  Arterial',
        '* Colitis',
        '* Diabetes',
        '  Mellitus',
        '* Infecciones',
        '  de la piel',
        '* Obesidad',
        '* Alcoholismo'   
    );

    $examenes=array(
        '* Abdominal',
        '* Abdominal para',
        '  Traumas(FAST)',
        '* Renal.',
        '* Hepático y de',
        '  Vías billiares',
        '* Vesical',
        '* Pélvico Abdominal',
        '* Pélvico Transvaginal',
        '* Diagnóstico de',
        '  Embarazo.',
        '* Mamas',
        '* Tiroides.',
        '* Cuello',
        '* Dinámica de Vesícula.',
        '  bilial.(Disfunción',
        '  Vesicular)',
        '* Prostática',
        '  Transabdominal',
        '* Prostática Transrectal'
    );

    $ultrasonido=array(
        '* Mamas'
    );

    $barra_lateral=array(
        'especialidades'=>$especialidades,
        'examenes'=>$examenes,
        'ultrasonido'=>$ultrasonido
    );


    $pdf = new Reporte('P','cm', 'Letter');
    $pdf->setEncabezado($encabezado);
    $pdf->setBarraLateral($barra_lateral);
    $pdf->setDatosAdicionales($datos_adicionales);
    $jdas="";
    $pdf->SetMargins(1.5,1.5);
    $pdf->SetTopMargin(1);
    $pdf->SetLeftMargin(1);
    $pdf->AliasNbPages();
    //$pdf->SetAutoPageBreak(true,15);
    $pdf->AddFont('Georgia','','georgia.php');
    $pdf->AddFont('Arial','','calibri.php');
    $pdf->AddFont('Arial','B','calibrib.php');
    $pdf->AddFont('latin','','latin.php');
    $pdf->AddFont('GeorgiaI','','GeorgiaI.php');
    $pdf->AddFont('GeorgiaBI','','GeorgiaBI.php');
    $pdf->AddPage();

    //llenando receta

    $set_x=$pdf->getX();
    $set_y=$pdf->getY();
    $pdf->SetFont('Courier', 'B', 12);
    $count=70;
  
    
    
    //obteniendo los medicamentos de la consulta
    $query_receta=_query("SELECT m.* , r.id_medicamento,r.dosis FROM receta as r, ".EXTERNAL.".producto as m WHERE m.id_producto=r.id_medicamento AND r.id_cita='$id_cita' AND r.id_paciente='$id_paciente'");
    //echo "Holaaa";
    /*$query_receta=_query("
    SELECT m.* , r.id_medicamento,r.dosis FROM 
    receta as r, medicamento as m 
    WHERE m.id_medicamento=r.id_medicamento AND 
    r.id_cita='$id_cita' AND r.id_paciente='$id_paciente'
    ");*/
    $pdf->SetFont('Courier', 'B', 12);
    if(_num_rows($query_receta)>0){//Verifica que hayan medicamentos recetads
      $set_y+=1;
      $pdf->SetY($set_y);
      $pdf->setX(6);
      $pdf->SetDrawColor(25, 65, 96);
      $pdf->SetFillCOlor(255, 255, 255);
      //encabezado de la receta
      /*$array_datos=array(
          array("MEDICAMENTO", 10, "C"),
          array("DOSIS", 4.5, "C"),
      );
      $pdf->SetTextColor(0,0,0);
      $pdf->LineWriteB($array_datos);*/

      //definiendo nuevo tamanio de letra 
      $pdf->SetFont('Courier', 'B', 9);
      //obteniendo todos los medicamentos recesatos y escribiendolos
      $medic=0;//cuenta los medicamentos recetados
      while($row=_fetch_array($query_receta)){
          
          $pdf->setX(6);
          $pdf->SetFillColor(255, 255, 255);
          $pdf->SetTextColor(0,0,0);

          $array_data=array(
              array(Mayu($row['descripcion']), 10, 'L'),
              array(Mayu($row['dosis']), 4.5, 'L'),
          );
          $pdf->LineWriteB($array_data);

          $medic++;
          $salto=is_float($medic/23.0);//si salto es verdadero  entonces saltara de pagina
          if(!$salto){
              $pdf->AddPage();
          }

      }
  

      
      //$pdf->setY(7);
        //$pdf->SetDrawColor(25, 65, 96);
        //$pdf->SetFillCOlor(255, 255, 255);
        //encabezado de la receta


        //definiendo nuevo tamanio de letra 
       /* $pdf->SetFont('Arial', 'B', 9);
        //obteniendo todos los medicamentos recesatos y escribiendolos
        $medic=0;//cuenta los medicamentos recetados
   
        $set_y=$pdf->GetY()+1;
        $set_x+=5.5;//a x se le aumenta en 57
        while($row=_fetch_array($query_receta)){
            $pdf->SetY($set_y);
            $pdf->SetX($set_x);
        
            $pdf->MultiCell(13, 2, (Mayu($row['descripcion']).' '.Mayu($row['dosis'])),0, 'L',0);

            $set_y+=1;

            $medic++;
            $salto=is_float($medic/23.0);//si salto es verdadero  entonces saltara de pagina
            if(!$salto){
                $pdf->AddPage();
            }

        }
    }

    //escribiendo lo otros medicamentos
    $pdf->SetFont('Arial', 'B', 12);
    $query_aux=_query("SELECT * FROM reserva_cita WHERE id='$id_cita'");
    $aux=_fetch_array($query_aux);
    $otros=$aux["medicamento"];
    $otr=explode("|", $otros);
    //obteniendo el y actual 
    $set_y=$pdf->GetY();
    
    $set_x=$pdf->GetX();
   
    
    if(count($otr)>0 && $otros!=""){

        //colocando el titulo
        $set_y+=1;
        //echo $set_y;
        $pdf->SetY($set_y);
        $set_x+=5.5;
        $pdf->SetX($set_x);
        $pdf->Cell(13, 3.5, (utf8_decode(" Otros medicamentos.")), 0, 0, 'L');
        $pdf->Ln();

        $pdf->SetFont('Courier', 'B', 9);
        //dibujando los otros medicamentos 
        $set_y+=2;
        $pdf->SetY($set_y);
        
        for($i=0;$i<count($otr); $i++){
            $pdf->SetY($set_y);
            $pdf->SetX($set_x);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->MultiCell(9,1, $otr[$i], 0, 'L', 0 );
            $set_y++;
        }
        //echo $set_y;*/

    }

      //escribiendo lo otros medicamentos
      $pdf->SetFont('Courier', 'B', 12);
      $query_aux=_query("SELECT * FROM reserva_cita WHERE id='$id_cita'");
      $aux=_fetch_array($query_aux);
      $otros=$aux["medicamento"];
      $otr=explode("|", $otros);
     
      if(count($otr)>0 && $otros!=""){
  
          //colocando el titulo
          $pdf->setY($pdf->getY()+1);
          $pdf->setX(6);
          $pdf->Cell(13.5, 2, (utf8_decode(" Otros medicamentos.")), 0, 1, 'L');
          $pdf->setY($pdf->getY());
          $pdf->setX(6);
          /*$array_data=array(
              array("MEDICAMENTO", 14.5, "c"),
          );
          $pdf->LineWriteB($array_data);
          */
          $pdf->SetFont('Courier', 'B', 9);
          //dibujando los otros medicamentos 
          
          for($i=0;$i<count($otr); $i++){
              $pdf->setX(6);
              $pdf->SetFillColor(255, 255, 255);
              $pdf->SetTextColor(0, 0, 0);
  
              $array_data=array(
                  array(Mayu($otr[$i]), 14.5, "L"),
              );
              $pdf->LineWriteB($array_data);
          }
  
      }
    /*ob_clean();*/
    $pdf->Output("receta_pdf.pdf","I");

?>