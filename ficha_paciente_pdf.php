<?php
error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');



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
        if($y + $he > 256){
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
        $this->SetX(10);
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
$pdf->SetLeftMargin(10);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true,1);
$pdf->AddFont("Helvetica","","helvetica.php");


$id_sucursal = $_SESSION["id_sucursal"];
$id_paciente = $_REQUEST["id_paciente"];


$sql_empresa = "SELECT * FROM empresa WHERE id_empresa='$id_sucursal'";
$resultado_emp=_query($sql_empresa);
$row_emp=_fetch_array($resultado_emp);
$nombre_lab = utf8_decode(Mayu(utf8_decode(trim($row_emp["nombre"]))));
$direccionXX = utf8_decode(Mayu(utf8_decode(trim($row_emp["direccion"]))));
$telefono1 = $row_emp["telefono1"];
$telefono2 = $row_emp["telefono2"];
$nrc = $row_emp['nrc'];
$muni = $row_emp["id_municipio_EMP"];
$nit = $row_emp['nit'];
$logo = $row_emp["logo"];
$whatsapp=$row_emp["whatsapp"];
$email=$row_emp["email"];
$sql3 = _query("SELECT mun.* FROM municipio as mun WHERE mun.id_municipio='$muni'");
$row3 = _fetch_array($sql3);
$municipio = $row3["municipio"];
$sql2 = _query("SELECT departamento.nombre_departamento FROM departamento INNER JOIN  on departamento.id_departamento= municipio.id_departamento_MUN WHERE municipio.id_municipio = '$muni'");
$row2 = _fetch_array($sql2);
$departamento = quitar_tildes($row2["nombre_departamento"]);
$precio_total_final = 0;
    

    $sql_info = "SELECT paciente.nombres, paciente.apellidos, sexo.sexo, paciente.fecha_de_nacimiento, recepcion.evento, cuartos.numero_cuarto, pisos.numero_piso, hospitalizacion.momento_entrada, hospitalizacion.precio_habitacion, hospitalizacion.id_estado_hospitalizacion, hospitalizacion.momento_salida, hospitalizacion.minuto, recepcion.evento, recepcion.fecha_de_entrada FROM recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion INNER JOIN tblHospitalizacion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN cuartos on cuartos.id_cuarto =hospitalizacion.id_cuarto_H INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN sexo on sexo.id_sexo = paciente.id_sexo WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND hospitalizacion.deleted is NULL";
    $query_info = _query($sql_info);
    $info = _fetch_array($query_info);
    $nombres_paciente = $info['nombres'];
    $apellidos_paciente = $info['apellidos'];
    $sexo = $info['sexo'];
    $fecha_de_nacimiento = ED($info['fecha_de_nacimiento']);
    $evento = $info['evento'];
    $numero_cuarto = $info['numero_cuarto'];
    $numero_piso = $info['numero_piso'];
    $momento_de_entrada = $info['momento_entrada'];
    $momento_de_entrada = explode(" ", $momento_de_entrada);
    $hora_de_entrada = _hora_media_decode($momento_de_entrada[1]);
    $fecha_de_entrada = ED($momento_de_entrada[0]);
    $momento_de_salida = $info['momento_de_salida'];
    $momento_de_salida = explode(" ", $momento_de_salida);
    $hora_de_salida = _hora_media_decode($momento_de_salida[1]);
    $fecha_de_salida = ED($momento_de_salida[0]);
    $precio_habitacion = number_format($info['precio_habitacion'],2);
    $id_estado_hospitalizacion = $info['id_estado_hospitalizacion'];
    $evento_recepcion = $info['evento'];
    $fecha_de_entrada_recepcion = $info['fecha_entrada'];
    $momento_recepcion = explode(" ", $fecha_de_entrada_recepcion);
    $hora_recepcion = _hora_media_decode($momento_recepcion[1]);
    $fecha_recepcion = ED($momento_recepcion[0]);
    $impress = "REPORTE DE HOSPITALIZACION DE ".$nombres_paciente." ".$apellidos_paciente;

    
    $existenas = "";
    if($min>0)
    {
        $existenas = "CANTIDAD: $min";
    }

    $pdf->AddPage();
    $pdf->SetFont('Arial','',10);
    $pdf->Image($logo,25,2,38,28);
    $set_x = 5;
    $set_y = 6;

    $sql0="SELECT paciente.nombres, paciente.expediente, paciente.apellidos, paciente.fecha_de_nacimiento, sexo.sexo, paciente.dui, estado_Civil.estado, paciente.religion, paciente.escolaridad, paciente.tel1, paciente.tel2, grupo_sanguineo.grupo, paciente.nombre_conyugue, departamento.nombre_departamento, municipio.municipio, paciente.direccion,paciente.email, paciente.responsable, parentezcos.parentezco, paciente.alergias, paciente.padecimientos, paciente.medicamento_permanente FROM tblPaciente INNER JOIN tblSexo on sexo.id_sexo = paciente.id_sexo LEFT JOIN estado_Civil on estado_Civil.id_estado_civil = paciente.id_estado_civil_PAC LEFT JOIN municipio on municipio.id_municipio = paciente.id_municipio_PAC LEFT JOIN departamento on departamento.id_departamento = municipio.id_departamento_MUN LEFT JOIN parentezcos on parentezcos.id_parentezco = paciente.id_parentezco_responsable_PAC LEFT JOIN grupo_sanguineo on grupo_sanguineo.id_grupo = paciente.id_grupo_sanguineo_PAC WHERE paciente.id_paciente = '$id_paciente' AND paciente.deleted is NULL";
    $result0=_query($sql0);
    $num_rows0=_num_rows($result0);
    $nombre_pte="";
    $dir_pte="";
    if($num_rows0>0){
    $row0=_fetch_array($result0);
        $nombres = $row0['nombres'];
        $apellidos = $row0['apellidos'];
        $fecha_de_nacimiento = $row0['fecha_de_nacimiento'];
        $sexo = $row0['sexo'];
        $dui = $row0['dui'];
        $estado = $row0['estado'];
        $religion = $row0['religion'];
        $escolaridad = $row0['escolaridad'];
        $tel1 = $row0['tel1'];
        $tel2 = $row0['tel2'];
        $grupo = $row0['grupo'];
        $nombre_conyugue = $row0['nombre_conyugue'];
        $nombre_departamento = $row0['nombre_departamento'];
        $municipio = $row0['municipio'];
        $direccion = $row0['direccion'];
        $email = $row0['email'];
        $responsable = $row0['responsable'];
        $parentezco = $row0['parentezco'];
        $alergias = $row0['alergias'];
        $padecimientos = $row0['padecimientos'];
        $medicamento_permanente = $row0['medicamento_permanente'];
        $expediente = $row0['expediente'];
    }
    //Encabezado General

    $pdf->SetFont('Arial','',16);
    $pdf->SetXY($set_x, $set_y);
    $pdf->Cell(220,5,utf8_decode($nombre_lab),0,1,'C');
    $pdf->SetXY($set_x, $set_y+11);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(220,5,utf8_decode(ucwords(("Depto. ".utf8_decode($departamento)))),0,1,'C');
    $pdf->SetXY($set_x+67.5, $set_y+5);
    $pdf->MultiCell(85,3,str_replace(" Y ", " y ",ucwords(utf8_decode(($direccionXX)))).", La Union",0,'C',0);
    $pdf->SetXY($set_x, $set_y+14);
    //$pdf->Cell(280,5,Mayu("PBX: ".$telefono1." / ".$telefono2),0,1,'C');
    $plus = 0;
    $pdf->SetXY($set_x, $set_y+18-$plus);
    //$pdf->Cell(280,5,utf8_decode(ucwords("WhatsApp: ").$whatsapp),0,1,'C');
    $pdf->SetXY($set_x, $set_y+21-$plus);
    //$pdf->Cell(280,5,utf8_decode("E-mail: ".$email),0,1,'C');
    $pdf->SetXY($set_x+5, $set_y+25);
    $pdf->Cell(280,5,utf8_decode($titulo),0,1,'L');
    $y = $pdf->getY();
    $pdf->SetDrawColor(0,0,0);
    $pdf->Line(20,45,190,45);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetXY(25, 40);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(30,5,ED(date("Y:m:d"))." "._hora_media_decode(date("H:i:s")),0,1,'C');
    $pdf->SetTextColor(243,42,22);
    $pdf->SetXY(165, 40);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(30,5,$expediente,0,1,'C');
    $pdf->SetTextColor(0,0,0);
    $pdf->SetXY(135, 40);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(30,5,"No de expediente:",0,1,'C');
    $pdf->SetDrawColor(0,0,0);
    $pdf->Line(20,45,20,120);
    $pdf->Line(190,45,190,120);
    $pdf->Line(20,120,190,120);

    $pdf->SetXY(30, 50);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Apellidos: ",0,1,'L');
    $pdf->SetXY(70, 50);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,5,$apellidos,0,1,'L');


    $pdf->SetXY(125, 50);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Sexo: ",0,1,'L');
    $pdf->SetXY(155, 50);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,5,$sexo,0,1,'L');


    $pdf->SetXY(125, 60);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Departamento: ",0,1,'L');
    $pdf->SetXY(155, 60);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,5,utf8_decode($nombre_departamento),0,1,'L');

    $pdf->SetXY(125, 70);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Municipio: ",0,1,'L');
    $pdf->SetXY(155, 70);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,5,utf8_decode($municipio),0,1,'L');


    $pdf->SetXY(125, 80);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Telefono 1: ",0,1,'L');
    $pdf->SetXY(155, 80);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,5,utf8_decode($tel1),0,1,'L');

    $pdf->SetXY(125, 90);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Telefono 2: ",0,1,'L');
    $pdf->SetXY(155, 90);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,5,utf8_decode($tel2),0,1,'L');


    $pdf->SetXY(125, 100);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Parentezco: ",0,1,'L');
    $pdf->SetXY(155, 100);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,5,utf8_decode($parentezco),0,1,'L');


    $pdf->SetXY(30, 60);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,5,"Nombres: ",0,1,'L');
    $pdf->SetXY(70, 60);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,5,$nombres,0,1,'L');
    $pdf->SetXY(30, 70);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(28,5,"Fecha de Nacimiento: ",0,1,'L');
    $pdf->SetXY(70, 70);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(40,5,ED($fecha_de_nacimiento),0,1,'L');

    $pdf->SetXY(30, 80);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(3,5,"Edad: ",0,1,'L');
    $pdf->SetXY(70, 80);
    $pdf->SetFont('Arial','',10);
    if(calcular_edad($fecha_de_nacimiento) == "0"){
      $fecha=date("Y-m-d H:i:s");
      $edad = mesesdiff($fecha_de_nacimiento."00:00:00", $fecha);
      $edad = $edad." meses";
      $pdf->Cell(25,5,$edad,0,1,'L');
    }
    else{
        $pdf->Cell(25,5,calcular_edad($fecha_de_nacimiento),0,1,'L');
    }
    


    $pdf->SetXY(30, 90);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(3,5,"DUI: ",0,1,'L');
    $pdf->SetXY(70, 90);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,5,$dui,0,1,'L');
    
    $pdf->SetXY(30, 100);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(3,5,"Responsable: ",0,1,'L');
    $pdf->SetXY(70, 100);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(25,5,utf8_decode($responsable),0,1,'L');

    $pdf->SetXY(30, 110);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(3,5,"Direccion: ",0,1,'L');
    $pdf->SetXY(70, 110);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(150,5,utf8_decode($direccion),0,1,'L');


    $pdf->SetTextColor(243,42,22);
    $pdf->SetXY(55, 122);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(100,5,"HISTORIA CLINICA",0,1,'C');
    $pdf->SetDrawColor(0,0,0);

    $pdf->Line(20,130,190,130);
    $pdf->Line(20,130,20,265);
    $pdf->Line(190,130,190,265);
    $pdf->Line(20,265,190,265);
    
    ///////////////////////////////////////////////////////////////////////

ob_clean();
$pdf->Output("reporte_hospitalizacion.pdf","I");


function get_format($df) {

  $str = '';
  $str .= ($df->invert == 1) ? ' - ' : '';
  if ($df->y > 0) {
      // years
      $str .= ($df->y > 1) ? $df->y . ' Años' : $df->y . ' Año ';
  } if ($df->m > 0) {
      // month
      $str .= ($df->m > 1) ? $df->m . ' Meses ' : $df->m . ' Mes ';
  } if ($df->d > 0) {
      // days
      $str .= ($df->d > 1) ? $df->d . ' Dias ' : $df->d . ' Dia ';
  } if ($df->h > 0) {
      // hours
      $str .= ($df->h > 1) ? $df->h . ' Horas ' : $df->h . ' Hora ';
  } if ($df->i > 0) {
      // minutes
      $str .= ($df->i > 1) ? $df->i . ' Minutos ' : $df->i . ' Minuto ';
  } if ($df->s > 0) {
      // seconds
      $str .= ($df->s > 1) ? $df->s . ' Segundos ' : $df->s . ' Segundo ';
  }

  echo $str;
}

function calcular_edad($fecha){
    list($A,$m,$d)=explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$A-1 : date("Y")-$A);
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