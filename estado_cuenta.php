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
    $id_recepcion = $_REQUEST["id_recepcion"];

    /* ACA SE OBTIENEN DATOS DE LA EMPRESA QUE SE MOSTRARAN EN EL REPORTE */
    $sql_empresa = "SELECT * FROM empresa WHERE id_empresa='$id_sucursal'";
    $resultado_emp=_query($sql_empresa);
    $row_emp=_fetch_array($resultado_emp);
    $nombre_lab = (((trim($row_emp["nombre"]))));
    $direccion = utf8_decode(Mayu(utf8_decode(trim($row_emp["direccion"]))));
    $telefono1 = $row_emp["telefono1"];
    $telefono2 = $row_emp["telefono2"];
    $nrc = $row_emp['nrc'];
    $muni = $row_emp["municipio"];
    $departamento = $row_emp['departamento'];
    $nit = $row_emp['nit'];
    $logo = "img/appmedic_logo.png";
    $whatsapp=$row_emp["whatsapp"];
    $email=$row_emp["email"];
    $sql3 = _query("SELECT mun.* FROM municipio as mun WHERE mun.id_municipio='$muni'");
    $row3 = _fetch_array($sql3);
    $municipio = $row3["municipio"];
    $sql2 = _query("SELECT departamento.nombre_departamento FROM departamento WHERE id_departamento = '$departamento'");
    $row2 = _fetch_array($sql2);
    $departamento = ($row2["nombre_departamento"]);
    

    /* ESTA ES UNA LISTA DE VARIABLES LAS CUALES ME SERVIRAN PARA PODER RECOLECTAR LA INFORMACION
    A CERCA DE PRODUCTOS, SERVICIOS, EXAMENES Y TIEMPO DE ENCAMADO QUE LA RECEPCION HA REGISTRADO
    EN LAS DISTINTAS AREAS */
    $contador_hospitalizacion = 0;
    $contador_emergencia = 0;
    $contador_sala_operaciones = 0;
    $contador_rayos_x = 0;
    $contador_pediatria = 0;
    $contador_examenes = 0;
    $productos_hospitalizacion = 0;
    $servicios_hospitalizacion = 0;
    $examenes_hospitalizacion = 0;
    $productos_emergencia = 0;
    $servicios_emergencia= 0;
    $examenes_emergencia = 0;
    $productos_sala_operaciones = 0;
    $servicios_sala_operaciones = 0;
    $examenes_sala_operaciones = 0;
    $productos_rayos_x = 0;
    $servicios_rayos_x = 0;
    $examenes_rayos_x = 0;
    $productos_pediatria = 0;
    $servicios_pediatria = 0;
    $examenes_pediatria = 0;
    $productos_nefrologia = 0;
    $servicios_nefrologia = 0;
    $examenes_nefrologia = 0;

    $tiempo_encamado = 0;
	$subtotal_cobro = 0;
	$pequenia_cirugia_activa = 0;
	$uso_de_consultorio_emergencia = 0;
    /* LISTA DE TOTALES */
    $total_examenes = 0;
    $total_hospitalizacion = 0;
    $total_emergencia = 0;
    $total_rayos_x = 0;
    $total_pediatria = 0;
    $total_sala_operaciones = 0;
    $total_tiempo_encamado = 0;
    $total_nefrologia = 0;
    $numero_areas = 0;

    

    /* ESTA SIGUIENTE PORCION DE CODIGO SIRVE PARA TRAER LOS DATOS CORRESPONDIENTES
    AL PACIENTE DEL CUAL SE DESEA CONOCER EL ESTADO DE CUENTA */
    $sql_info = "SELECT paciente.nombres, paciente.expediente, paciente.apellidos, paciente.sexo, paciente.fecha_nacimiento, recepcion.evento, recepcion.abono, recepcion.evento, recepcion.fecha_de_entrada FROM recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion WHERE recepcion.id_recepcion = '$id_recepcion' AND recepcion.deleted is NULL";
    $query_info = _query($sql_info);
    $info = _fetch_array($query_info);
    $nombres_paciente = $info['nombres'];
    $abono = $info['abono'];
    $apellidos_paciente = $info['apellidos'];
    $sexo = $info['sexo'];
    $fecha_nacimiento = ED($info['fecha_nacimiento']);
    $evento = $info['evento'];
    $evento_recepcion = $info['evento'];
    $fecha_de_entrada_recepcion = $info['fecha_de_entrada'];
    $momento_recepcion = explode(" ", $fecha_de_entrada_recepcion);
    $hora_recepcion = _hora_media_decode($momento_recepcion[1]);
    $fecha_recepcion = ED($momento_recepcion[0]);
    $expediente = $info['expediente'];
    
    

    $pdf->AddPage();
    $pdf->SetFont('Arial','',10);
    $pdf->Image($logo,5,2,60,35);
    $set_x = 5;
    $set_y = 6;
    $pdf->SetFont('Arial','',10);
    $pdf->Image($logo,150,2,60,35);
    $set_x = 5;
    $set_y = 6;

    //Encabezado General

    $pdf->SetFont('Helvetica','',14);
    $pdf->SetXY(0, $set_y);
    $pdf->Cell(215,5,utf8_decode($nombre_lab),0,1,'C');
    $pdf->SetXY($set_x+90, $set_y+11);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(215,5,(ucwords(("Depto. ".utf8_decode($departamento)))),0,1,'L');
    $pdf->SetXY($set_x+60, $set_y+5.5);
    $pdf->MultiCell(85,3,str_replace(" Y ", " y ",ucwords(utf8_decode(($direccion)))),0,'C',0);
    $pdf->SetXY($set_x, $set_y+14);
    //$pdf->Cell(280,5,Mayu("PBX: ".$telefono1." / ".$telefono2),0,1,'C');
    $plus = 0;
    $pdf->SetXY($set_x, $set_y+18-$plus);
    //$pdf->Cell(280,5,utf8_decode(ucwords("WhatsApp: ").$whatsapp),0,1,'C');
    $pdf->SetXY($set_x, $set_y+21-$plus);
    //$pdf->Cell(280,5,utf8_decode("E-mail: ".$email),0,1,'C');
    $pdf->SetXY($set_x+5, $set_y+25);
    $pdf->SetDrawColor(0,0,0);
    $pdf->Line(63,23,150,23);
    $pdf->Line(73,26,140,26);
    $pdf->Line(83,29,130,29);
    $pdf->Line(93,32,120,32);
    $pdf->Line(1,35,215,35);
    ///////////////////////////////////////////////////////////////////////
    $pdf->SetXY(0, $set_y+33);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(215,5,utf8_decode(ucwords(("ESTADO DE CUENTA DEL PACIENTE: ".utf8_decode($nombres_paciente." ".$apellidos_paciente)))),0,1,'C');
    $pdf->SetXY(18, $set_y+38);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(60,5,utf8_decode(ucwords(("FECHA DE IMPRESION: ".utf8_decode(date("d:m:Y")." "._hora_media_decode(date("H:i:s")))))),0,1,'C');

    $pdf->SetXY(78, $set_y+38);
    $pdf->SetTextColor(243,42,22);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(60,5,utf8_decode(ucwords(("Nº EXPEDIENTE: ".utf8_decode($expediente)))),0,1,'C');

    $pdf->SetXY(138, $set_y+38);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(60,5,utf8_decode("ENTRADA RECEPCION: ".$fecha_recepcion." ".$hora_recepcion),0,1,'C');
    $pdf->Line(1,$set_y+45,215,$set_y+45);

    $pdf->Line(1,35,1,$set_y+45);
    $pdf->Line(215,35,215,$set_y+45);
    $y = $pdf->getY();
    $pdf->SetTextColor(101,222,0);
    $pdf->SetXY(20, $y+5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(10,5,utf8_decode("Detalle..."),0,1,'C');
    $pdf->SetTextColor(0,0,0);

    $y = $pdf->getY();
    $pdf-> setXY(10,$y+5);
    $pdf->SetFillColor(255, 255, 255);
    /*PRIMER TIPO DE COBRO 'COBROS DE HOSPITALIZACION HOSPITALIZACION'*/
    /*  ACA EMPIEZA EL PRIMER TIPO DE COBRO */

    /* SE VERIFICA QUE EXISTA ESE TIPO DE COBRO PRIMERAMENTE A TRAVES DE LA SIGUIENTE CONSULTA */
    $sql_comprobar_hospitalizacion = "SELECT * FROM hospitalizacion WHERE hospitalizacion.id_recepcion = '$id_recepcion' AND hospitalizacion.deleted is null";
    $query_comprobar_hospitalizacion = _query($sql_comprobar_hospitalizacion);
    /* SI EXISTE MAS DE 0 FILAS COMO RESPUESTA A LA CONSULTA SIGNIFICA QUE HAY POR LO MENOS
    UNA HOSPITALIZACION REGISTRADA CON LA MISMA RECEPCION */
    if(_num_rows($query_comprobar_hospitalizacion) > 0){
        $contador_hospitalizacion = 1;
        $sql_info = "SELECT hospitalizacion.id_hospitalizacion, paciente.nombres, paciente.apellidos, paciente.sexo, paciente.fecha_nacimiento, recepcion.evento, cuartos.numero_cuarto, pisos.numero_piso, hospitalizacion.momento_entrada, hospitalizacion.precio_habitacion, hospitalizacion.id_estado_hospitalizacion, hospitalizacion.momento_salida, hospitalizacion.minuto, recepcion.evento, recepcion.fecha_de_entrada FROM recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion INNER JOIN hospitalizacion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN cuartos on cuartos.id_cuarto = hospitalizacion.id_cuarto_H INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto WHERE recepcion.id_recepcion = '$id_recepcion' AND hospitalizacion.deleted is NULL";
        $query_info = _query($sql_info);
        $info = _fetch_array($query_info);
        $nombres_paciente = $info['nombres'];
        $apellidos_paciente = $info['apellidos'];
        $sexo = $info['sexo'];
        $fecha_nacimiento = ED($info['fecha_nacimiento']);
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
        $fecha_de_entrada_recepcion = $info['fecha_de_entrada'];
        $id_hospitalizacion = $info['id_hospitalizacion'];
        $momento_recepcion = explode(" ", $fecha_de_entrada_recepcion);
        $hora_recepcion = _hora_media_decode($momento_recepcion[1]);
        $fecha_recepcion = ED($momento_recepcion[0]);

        /* EN ESTA PARTE EMPIEZA A ESCRIBIR EN EL PDF */
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(101,222,0);
        $pdf->Cell(45,5,utf8_decode("DETALLE DE HOSPITALIZACION..."),0,1,'C');
        $pdf->SetTextColor(0,0,0);
        $y = $pdf->getY();
        $pdf->setXY(10,$y+5);
        $pdf->SetFillColor(255, 255, 255);
        $array_data = array(
            array("NOMBRES DEL PACIENTE",47.5,"C"),
            array("APELLIDOS DEL PACIENTE",47.5,"C"),
            array("FECHA INGRESO",26.75,"C"),
            array("HORA INGRESO",26.75,"C"),
            array("No PISO",20.75,"C"),
            array("No CUARTO",20.75,"C"),
        );
        $pdf->SetFont('Arial','B',8);
        $pdf->LineWriteB($array_data);
        $array_data = array(
            array($nombres_paciente,47.5,"C"),
            array($apellidos_paciente,47.5,"C"),
            array($fecha_de_entrada,26.75,"C"),
            array($hora_de_entrada,26.75,"C"),
            array("#".$numero_piso,20.75,"C"),
            array("#".$numero_cuarto,20.75,"C"),
        );
        $pdf->SetFont('Arial','',8);
        $pdf->setX(10);
        $pdf->LineWriteB($array_data);
    
        $array_data = array(
          array("No EXPEDIENTE",25,"C"),
          array("TIEMPO HOSPITALIZADO",70,"C"),
          array("FECHA DE ALTA",26.75,"C"),
          array("HORA DE ALTA",26.75,"C"),
          array("ESTADO",41.50,"C"),
        );
        $pdf->SetFont('Arial','B',8);
        $pdf->LineWriteB($array_data);
    
        $segunda_query = "SELECT paciente.expediente, hospitalizacion.momento_entrada, hospitalizacion.momento_salida, estado_hospitalizacion.estado, hospitalizacion.precio_habitacion, hospitalizacion.minuto, hospitalizacion.total FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion INNER JOIN estado_hospitalizacion on estado_hospitalizacion.id_estado_hospitalizacion = hospitalizacion.id_estado_hospitalizacion WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND hospitalizacion.deleted is NULL";
        $query_segunda = _query($segunda_query);
        $row_segundo = _fetch_array($query_segunda);
    
        $expediente = $row_segundo['expediente'];
        $momento_de_entrada = $row_segundo['momento_entrada'];
        $momento_de_salida = $row_segundo['momento_salida'];
        $minuto = $row_segundo['minuto'];
        $total = $row_segundo['total'];
        $precio_habitacion = $row_segundo['precio_habitacion'];
        $fecha1 = new DateTime($momento_de_entrada);
        if($id_estado_hospitalizacion == "3"){
            $fecha2 = new DateTime($momento_de_salida);
            $momento_salida = explode(" ",$momento_de_salida);
            $fecha_alta = $momento_salida[0];
            $hora_alta = $momento_salida[1];
        }
        if($id_estado_hospitalizacion == "2"){
            $fecha2 = new DateTime(date("Y-m-d H:i:s"));
            $fecha_alta = "--------";
            $hora_alta = "--------";
        }
        $estado = $row_segundo['estado'];
        $diff = $fecha1->diff($fecha2);
        $Diferencia_de_tiempo = "";
        $activo_con = 0;
        if($diff->y > 0){
            if($diff->y == 1){
                $Diferencia_de_tiempo += "1 año";
                $precio_total_final += $precio_habitacion * 8760;
            }
            else{
                $Diferencia_de_tiempo += "".strval($diff->y)." años";
                $precio_total_final += $precio_habitacion * 8760 * ($diff->y);
            }
            $activo_con++;
        }
        if($diff->m > 0){
            if($activo_con == 0){
                if($diff->m == 1){
                    $Diferencia_de_tiempo = "1 mes";
                    $precio_total_final += $precio_habitacion * 730;
                }
                else{
                    $Diferencia_de_tiempo = strval($diff->m)." meses";
                    $precio_total_final += $precio_habitacion * 730 * ($diff->m);
                }
            }
            else{
                if($diff->m == 1){
                    $Diferencia_de_tiempo += " con 1 mes";
                    $precio_total_final += $precio_habitacion * 730;
                }
                else{
                    $Diferencia_de_tiempo += " con ".strval($diff->m)." meses";
                    $precio_total_final += $precio_habitacion * 730 * ($diff->m);
                }
                $activo_con++;
            }
        }
    
        if($diff->d > 0){
            if($activo_con == 0){
                if($diff->d == 1){
                    $Diferencia_de_tiempo = "1 dia";
                    $precio_total_final += $precio_habitacion * 24;
                }
                else{
                    $Diferencia_de_tiempo = strval($diff->d)." dias";
                    $precio_total_final += $precio_habitacion * 24 * ($diff->d);
                }
            }
            else{
                if($diff->d == 1){
                    $Diferencia_de_tiempo .= " con 1 dia";
                    $precio_total_final += $precio_habitacion * 24;
                }
                else{
                    $Diferencia_de_tiempo .= " con ".strval($diff->d)." dias";
                    $precio_total_final += $precio_habitacion * 24 * ($diff->d);
                }
            }
            $activo_con++;
        }
        if($diff->h > 0){
            if($activo_con == 0){
                if($diff->h == 1){
                    $Diferencia_de_tiempo = "1 hora";
                    $precio_total_final += $precio_habitacion;
                }
                else{
                    $Diferencia_de_tiempo = strval($diff->h)." horas";
                    $precio_total_final += $precio_habitacion * ($diff->h);
                }
            }
            else{
                if($diff->h == 1){
                    $Diferencia_de_tiempo .= " con 1 hora";
                    $precio_total_final += $precio_habitacion;
                }
                else{
                    $Diferencia_de_tiempo .= " con ".strval($diff->h)." horas";
                    $precio_total_final += $precio_habitacion * ($diff->h);
                }
            }
            $activo_con++;
        }
        if($diff->i > 0){
            if($activo_con == 0){
                if($diff->i == 1){
                    $Diferencia_de_tiempo = "1 minuto";
                }
                else{
                    $Diferencia_de_tiempo= strval($diff->i)." minuto";
                }
            }
            else{
                if($diff->i == 1){
                    $Diferencia_de_tiempo .= " con 1 minuto";
                }
                else{
                    $Diferencia_de_tiempo .= " con ".strval($diff->i)." minutos";
                }
            }
            $activo_con++;
        }
        /* SI EL TOTAL ES NULL SIGNIFICA QUE TODAVIA NO SE HA DADO DE ALTA AL PACIENTE, ENTONCES
        SE PROCEDERA A HACER EL CALCULO DEL TOTAL DE ENCAMADO DESDE EL TIEMPO EN QUE ENTRO HASTA 
        EL MOMENTO ACTUAL (EN EL QUE SE ESTA FINALIZANDO LA RECEPCION) PARA HACER EL COBRO */
        if(is_null($total)){
            $total_tiempo_encamado = $precio_total_final;
        }
        else{
            $total_tiempo_encamado = $total;
        }
        
        
        
        $array_data = array(
            array($expediente,25,"C"),
            array($Diferencia_de_tiempo,70,"C"),
            array($fecha_alta,26.75,"C"),
            array($hora_alta,26.75,"C"),
            array($estado,41.50,"C"),
        );
        $pdf->SetFont('Arial','',8);
        $pdf->setX(10);
        $pdf->LineWriteB($array_data);
    
        $precio_servicios = 0;
        $precio_productos = 0;
        $precio_examenes = 0;
        /* IREMOS A TRAER LOS PRODUCTOS QUE FUERON UTILIZADOS EN LA HOSPITALIZACION
        QUE REFERENCIA A ESTA RECEPCION */
        $sql_productos = "SELECT ".EXTERNAL.".producto.descripcion, insumos_hospitalizacion.id_insumo, insumos_hospitalizacion.cantidad, insumos_hospitalizacion.total, insumos_hospitalizacion.created_at, ".EXTERNAL.".presentacion_producto.precio, ".EXTERNAL.".presentacion_producto.unidad FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN insumos_hospitalizacion on insumos_hospitalizacion.id_recepcion = recepcion.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion = insumos_hospitalizacion.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND insumos_hospitalizacion.deleted is NULL";
       //echo $sql_productos;
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $y = $pdf->getY();
            $pdf->setXY(10,$y+5);
            $array_data = array(
                array("PRODUCTOS Y MEDICAMENTOS UTILIZADOS EN LA HOSPITALIZACION",190,"L"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $unidad = $row_productos['unidad'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $cantidad = $cantidad/$unidad;
                $productos_hospitalizacion+=($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
                $contador++;
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($productos_hospitalizacion,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }
        $pdf->SetFont('Arial','B',8);
        
        /* ACA IREMOS A TRAER LOS SERVICIOS UTILIZADOS EN LA HOSPITALIZACION QUE HACE
        REFERENCIA A LA RECEPCION */
        $sql_productos = "SELECT insumos_hospitalizacion.id_insumo, ".EXTERNAL.".servicios_hospitalarios.descripcion,insumos_hospitalizacion.created_at, insumos_hospitalizacion.cantidad, insumos_hospitalizacion.total, insumos_hospitalizacion.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN insumos_hospitalizacion on insumos_hospitalizacion.id_recepcion = recepcion.id_recepcion INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = insumos_hospitalizacion.id_servicio WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND insumos_hospitalizacion.deleted is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("SERVICIOS APLICADOS AL PACIENTE EN LA HOSPITALIZACION",190,"L"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $servicios_hospitalizacion+=($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array((utf8_decode($descripcion)),65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
                $contador++;
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($servicios_hospitalizacion,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }
       
        /*
        $sql_productos = "SELECT insumos_hospitalizacion.id_insumo, insumos_hospitalizacion.id_examen, insumos_hospitalizacion.created_at, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM insumos_hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = insumos_hospitalizacion.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = insumos_hospitalizacion.id_examen INNER JOIN hospitalizacion on hospitalizacion.id_recepcion = recepcion.id_recepcion WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND insumos_hospitalizacion.deleted is NULL ";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("EXAMENES REALIZADOS AL PACIENTE EN LA HOSPITALIZACION",190,"L"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['nombre_examen'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = 1;
                $examenes_hospitalizacion+=($row_productos['precio_examen'] * $cantidad);
                $precio = number_format($row_productos['precio_examen'],2);
                $monto = number_format(($row_productos['precio_examen'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($examenes_hospitalizacion,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }*/
        $array_data = array(
            array("COBRO DE ENCAMADO DE LA HOSPITALIZACION",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        $pdf->setX(10);
        $array_data = array(
            array("No Cuarto",25,"C"),
            array("Tipo de cobro",40,"C"),
            array("Precio por hora",35,"C"),
            array("Tiempo Hospitalizado",65,"C"),
            array("Monto",25,"C"),
        );
        $pdf->LineWriteB($array_data);
        $pdf->setX(10);
        $array_data = array(
            array("#".$numero_cuarto,25,"C"),
            array("A LA HORA",40,"C"),
            array("$ ".number_format($precio_habitacion, 2),35,"C"),
            array($Diferencia_de_tiempo,65,"C"),
            array("$ ".number_format($total_tiempo_encamado, 2),25,"C"),
        );
        $pdf->SetFont('Arial','',8);
        $pdf->LineWriteB($array_data);
        $pdf->SetFont('Arial','B',8);
        $pdf->setX(10);
        $array_data = array(
            array("",25,"C"),
            array("",40,"C"),
            array("",35,"C"),
            array("SUB TOTAL POR CLASE...",65,"C"),
            array("$ ".number_format($total_tiempo_encamado,2),25,"C"),
        );
        $encamado_hospitalizacion = $total_tiempo_encamado;
        $pdf->LineWriteB($array_data);
        $total_hospitalizacion = $productos_hospitalizacion + $servicios_hospitalizacion + $examenes_hospitalizacion + $encamado_hospitalizacion;
        $pdf->setX(10);
        $array_data = array(
            array("TOTAL GENERAL...",165,"C"),
            array("$ ".number_format($total_hospitalizacion,2),25,"C"),
        );
        $pdf->LineWriteB($array_data);  
    }
    else{
        $contador_hospitalizacion = 0;
    }
    /* ACA TERMINA EL PRIMER TIPO DE COBRO */
	/* -----------******************---------- */






     /*SEGUNDO TIPO DE COBRO 'COBROS DE EMERGENCIA'*/
    /*  ACA EMPIEZA EL SEGUNDO TIPO DE COBRO */

    /* SE VERIFICA QUE EXISTA ESE TIPO DE COBRO PRIMERAMENTE A TRAVES DE LA SIGUIENTE CONSULTA */
    $sql_comprobar_emergencia = "SELECT * FROM insumos_emergencia WHERE id_recepcion = '$id_recepcion' AND deleted is NULL AND cobrado_actual is NULL ";
    $query_comprobar_emergencia = _query($sql_comprobar_emergencia);
    /* SI EXISTE MAS DE 0 FILAS COMO RESPUESTA A LA CONSULTA SIGNIFICA QUE HAY POR LO MENOS
    UN INSUMO REGISTRADO EN EMERGENCIA A TRAVES DE ESTA RECEPCION */
    if(_num_rows($query_comprobar_emergencia) > 0){
        $y = $pdf->getY();
        $pdf-> setXY(10,$y+5);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(101,222,0);
        $pdf->Cell(45,5,utf8_decode("DETALLE DE EMERGENCIA..."),0,1,'C');
        $pdf->SetTextColor(0,0,0);
        $emergencia_activa = 1;
        $precio_servicios = 0;
        $precio_productos = 0;
        $precio_examenes = 0;
        
        $sql_productos = "SELECT insumos_emergencia.id_insumo, ".EXTERNAL.".producto.descripcion, insumos_emergencia.cantidad, insumos_emergencia.total, insumos_emergencia.created_at, ".EXTERNAL.".presentacion_producto.precio, ".EXTERNAL.".presentacion_producto.unidad FROM insumos_emergencia INNER JOIN recepcion on recepcion.id_recepcion = insumos_emergencia.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion = insumos_emergencia.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE recepcion.id_recepcion = '$id_recepcion' AND insumos_emergencia.deleted is NULL AND insumos_emergencia.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $y = $pdf->getY();
            $pdf->setXY(10,$y+5);
            $array_data = array(
                array("PRODUCTOS Y MEDICAMENTOS UTILIZADOS EN EMERGENCIA",190,"L"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $contador_emergencia++;
                $unidad = $row_productos['unidad'];
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $cantidad = $cantidad / $unidad;
                $productos_emergencia += ($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
                $contador++;
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($productos_emergencia,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }

        $pdf->SetFont('Arial','B',8);
        
        $sql_productos = "SELECT insumos_emergencia.id_insumo, ".EXTERNAL.".servicios_hospitalarios.descripcion, ".EXTERNAL.".servicios_hospitalarios.id_servicio, insumos_emergencia.created_at, insumos_emergencia.cantidad, insumos_emergencia.total, insumos_emergencia.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM insumos_emergencia INNER JOIN recepcion on recepcion.id_recepcion = insumos_emergencia.id_recepcion  INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = insumos_emergencia.id_servicio WHERE recepcion.id_recepcion = '$id_recepcion' AND insumos_emergencia.deleted is NULL AND insumos_emergencia.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("SERVICIOS APLICADOS AL PACIENTE EN EMERGENCIA",190,"L"),
            );
            
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_servicio = $row_productos['id_servicio'];
                $contador_emergencia++;
                if($id_servicio == 438 || $id_servicio == 451){
                    $pequenia_cirugia_activa = 1;
                }
                if($servicio == 343){
                    $uso_de_consultorio_emergencia = 1;
                }
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $servicios_emergencia+=($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array((utf8_decode($descripcion)),65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
                $contador++;
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($servicios_emergencia,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }
        /*
        $sql_productos = "SELECT insumos_emergencia.id_insumo, insumos_emergencia.id_examen, insumos_emergencia.created_at, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM insumos_emergencia INNER JOIN recepcion on recepcion.id_recepcion = insumos_emergencia.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = insumos_emergencia.id_examen  WHERE recepcion.id_recepcion = '$id_recepcion' AND insumos_emergencia.deleted is NULL AND insumos_emergencia.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("EXAMENES REALIZADOS AL PACIENTE EN EMERGENCIA",190,"L"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $contador_emergencia++;
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['nombre_examen'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = 1;
                $examenes_emergencia+=($row_productos['precio_examen'] * $cantidad);
                $precio = number_format($row_productos['precio_examen'],2);
                $monto = number_format(($row_productos['precio_examen'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($examenes_emergencia,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
              
        }
        */
        $total_emergencia = $productos_emergencia + $servicios_emergencia ;
        $pdf->setX(10);
        $array_data = array(
            array("TOTAL GENERAL...",165,"C"),                
            array("$ ".number_format($total_emergencia,2),25,"C"),
        );
        $pdf->LineWriteB($array_data);

    }
    //FINAL DE TRAER COBROS DE EMERGENCIAS
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////


    //TRAER COBROS DE RAYOSX


    $sql_comprobar_emergencia = "SELECT * FROM tblInsumos_RayosX WHERE id_recepcion = '$id_recepcion' AND deleted is NULL AND cobrado_actual is NULL ";
    $query_comprobar_emergencia = _query($sql_comprobar_emergencia);
    if(_num_rows($query_comprobar_emergencia) > 0){
        $y = $pdf->getY();
        $pdf-> setXY(10,$y+5);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(101,222,0);
        $pdf->Cell(45,5,utf8_decode("Detalle de RayosX..."),0,1,'C');
        $pdf->SetTextColor(0,0,0);

        $precio_servicios = 0;
        $precio_productos = 0;
        $precio_examenes = 0;
        
        $sql_productos = "SELECT tblInsumos_RayosX.id_insumo, ".EXTERNAL.".producto.descripcion, tblInsumos_RayosX.cantidad, tblInsumos_RayosX.total, tblInsumos_RayosX.created_at, ".EXTERNAL.".presentacion_producto.precio FROM tblInsumos_RayosX INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_RayosX.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion = tblInsumos_RayosX.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_RayosX.deleted is NULL AND tblInsumos_RayosX.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $y = $pdf->getY();
            $pdf->setXY(10,$y+5);
            $array_data = array(
                array("PRODUCTOS Y MEDICAMENTOS UTILIZADOS EN RAYOSX",190,"L"),
            );
            
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $productos_rayos_x+=($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($productos_rayos_x,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }
        

        $pdf->SetFont('Arial','B',8);
        
        $sql_productos = "SELECT tblInsumos_RayosX.id_insumo, tblInsumos_RayosX.created_at, ".EXTERNAL.".servicios_hospitalarios.descripcion, tblInsumos_RayosX.cantidad, tblInsumos_RayosX.total, tblInsumos_RayosX.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM tblInsumos_RayosX INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_RayosX.id_recepcion  INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = tblInsumos_RayosX.id_servicio WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_RayosX.deleted is NULL AND tblInsumos_RayosX.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("EXAMENES APLICADOS AL PACIENTE EN RAYOSX",190,"L"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $servicios_rayos_x+=($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array((utf8_decode($descripcion)),65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($servicios_rayos_x,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }
       
        $sql_productos = "SELECT tblInsumos_RayosX.id_insumo, tblInsumos_RayosX.id_examen, tblInsumos_RayosX.created_at, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM tblInsumos_RayosX INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_RayosX.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = tblInsumos_RayosX.id_examen  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_RayosX.deleted is NULL AND tblInsumos_RayosX.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("LABORATORIOS REALIZADOS AL PACIENTE EN RAYOSX",190,"L"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['nombre_examen'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = 1;
                $examenes_rayos_x+=($row_productos['precio_examen'] * $cantidad);
                $precio = number_format($row_productos['precio_examen'],2);
                $monto = number_format(($row_productos['precio_examen'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($examenes_rayos_x,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }

        
        $total_rayos_x = $productos_rayos_x + $servicios_rayos_x + $examenes_rayos_x;
        $pdf->setX(10);
        $array_data = array(
            array("TOTAL GENERAL...",165,"C"),                
            array("$ ".number_format($total_rayos_x,2),25,"C"),
        );
        $pdf->LineWriteB($array_data);  
    }
    //FINAL DE TRAER COBROS DE RAYOS X
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////



    //TRAER COBROS DE SALA DE OPERACIONES


    $sql_comprobar_emergencia = "SELECT * FROM tblInsumos_Sala_Operaciones WHERE id_recepcion = '$id_recepcion' AND deleted is NULL AND cobrado_actual is NULL ";
    $query_comprobar_emergencia = _query($sql_comprobar_emergencia);
    if(_num_rows($query_comprobar_emergencia) > 0){
        $y = $pdf->getY();
        $pdf-> setXY(10,$y+5);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(101,222,0);
        $pdf->Cell(45,5,utf8_decode("Detalle de Sala de Operaciones..."),0,1,'C');
        $pdf->SetTextColor(0,0,0);

        $precio_servicios = 0;
        $precio_productos = 0;
        $precio_examenes = 0;
        
        $sql_productos = "SELECT tblInsumos_Sala_Operaciones.id_insumo, ".EXTERNAL.".producto.descripcion, tblInsumos_Sala_Operaciones.cantidad, tblInsumos_Sala_Operaciones.total, tblInsumos_Sala_Operaciones.created_at, ".EXTERNAL.".presentacion_producto.precio FROM tblInsumos_Sala_Operaciones INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Sala_Operaciones.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion = tblInsumos_Sala_Operaciones.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Sala_Operaciones.deleted is NULL AND tblInsumos_Sala_Operaciones.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $y = $pdf->getY();
            $pdf->setXY(10,$y+5);
            $array_data = array(
                array("PRODUCTOS Y MEDICAMENTOS UTILIZADOS EN SALA DE OPERACIONES",190,"L"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $contador_sala_operaciones++;
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $productos_sala_operaciones += ($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
                $contador++;
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($productos_sala_operaciones,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }
        

        $pdf->SetFont('Arial','B',8);
        
        $sql_productos = "SELECT tblInsumos_Sala_Operaciones.id_insumo, ".EXTERNAL.".servicios_hospitalarios.descripcion, tblInsumos_Sala_Operaciones.created_at, tblInsumos_Sala_Operaciones.cantidad, tblInsumos_Sala_Operaciones.total, tblInsumos_Sala_Operaciones.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM tblInsumos_Sala_Operaciones INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Sala_Operaciones.id_recepcion  INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = tblInsumos_Sala_Operaciones.id_servicio WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Sala_Operaciones.deleted is NULL AND tblInsumos_Sala_Operaciones.cobrado_actual is NULL ";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("SERVICIOS APLICADOS AL PACIENTE EN SALA DE OPERACIONES",190,"L"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $contador_sala_operaciones++;
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $servicios_sala_operaciones += ($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array((utf8_decode($descripcion)),65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
                $contador++;
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($servicios_sala_operaciones,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }
       
        $sql_productos = "SELECT tblInsumos_Sala_Operaciones.id_insumo, tblInsumos_Sala_Operaciones.id_examen, tblInsumos_Sala_Operaciones.created_at, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM tblInsumos_Sala_Operaciones INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Sala_Operaciones.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = tblInsumos_Sala_Operaciones.id_examen  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Sala_Operaciones.deleted is NULL AND tblInsumos_Sala_Operaciones.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("EXAMENES REALIZADOS AL PACIENTE EN SALA DE OPERACIONES",190,"L"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $contador_sala_operaciones++;
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['nombre_examen'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = 1;
                $examenes_sala_operaciones += ($row_productos['precio_examen'] * $cantidad);
                $precio = number_format($row_productos['precio_examen'],2);
                $monto = number_format(($row_productos['precio_examen'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($examenes_sala_operaciones,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);

             
        }
        $total_sala_operaciones = $productos_sala_operaciones + $servicios_sala_operaciones + $examenes_sala_operaciones;
        $pdf->setX(10);
        $array_data = array(
            array("TOTAL GENERAL...",165,"C"),                
            array("$ ".number_format($total_sala_operaciones,2),25,"C"),
        );
        $pdf->LineWriteB($array_data); 

    }
    //FINAL DE TRAER COBROS DE SALA DE OPERACIONES
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////


    //FINAL DE TRAER COBROS DE NEFROLOGIA
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////

    $sql_comprobar_nefrologia = "SELECT * FROM tblInsumos_Nefrologia WHERE id_recepcion = '$id_recepcion' AND deleted is NULL AND cobrado_actual is NULL ";
    $query_comprobar_nefrologia = _query($sql_comprobar_nefrologia);
    if(_num_rows($query_comprobar_nefrologia) > 0){
        $y = $pdf->getY();
        $pdf-> setXY(10,$y+5);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetTextColor(101,222,0);
        $pdf->Cell(45,5,utf8_decode("Detalle de Nefrologia..."),0,1,'C');
        $pdf->SetTextColor(0,0,0);

        $precio_servicios = 0;
        $precio_productos = 0;
        $precio_examenes = 0;
        
        $sql_productos = "SELECT tblInsumos_Nefrologia.id_insumo, ".EXTERNAL.".producto.descripcion, tblInsumos_Nefrologia.cantidad, tblInsumos_Nefrologia.total, tblInsumos_Nefrologia.created_at, ".EXTERNAL.".presentacion_producto.precio FROM tblInsumos_Nefrologia INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Nefrologia.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion = tblInsumos_Nefrologia.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Nefrologia.deleted is NULL AND tblInsumos_Nefrologia.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $y = $pdf->getY();
            $pdf->setXY(10,$y+5);
            $array_data = array(
                array("PRODUCTOS Y MEDICAMENTOS UTILIZADOS EN NEFROLOGIA",190,"L"),
            );
            
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $productos_nefrologia+=($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($productos_nefrologia,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }
        

        $pdf->SetFont('Arial','B',8);
        
        $sql_productos = "SELECT tblInsumos_Nefrologia.id_insumo, tblInsumos_Nefrologia.created_at, ".EXTERNAL.".servicios_hospitalarios.descripcion, tblInsumos_Nefrologia.cantidad, tblInsumos_Nefrologia.total, tblInsumos_Nefrologia.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM tblInsumos_Nefrologia INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Nefrologia.id_recepcion  INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = tblInsumos_Nefrologia.id_servicio WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Nefrologia.deleted is NULL AND tblInsumos_Nefrologia.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("EXAMENES APLICADOS AL PACIENTE EN NEFROLOGIA",190,"L"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->SetFont('Arial','B',8);
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['descripcion'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = $row_productos['cantidad'];
                $servicios_nefrologia+=($row_productos['precio'] * $cantidad);
                $precio = number_format($row_productos['precio'],2);
                $monto = number_format(($row_productos['precio'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array((utf8_decode($descripcion)),65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($servicios_nefrologia,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }
       
        $sql_productos = "SELECT tblInsumos_Nefrologia.id_insumo, tblInsumos_Nefrologia.id_examen, tblInsumos_Nefrologia.created_at, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM tblInsumos_Nefrologia INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Nefrologia.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = tblInsumos_Nefrologia.id_examen  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Nefrologia.deleted is NULL AND tblInsumos_Nefrologia.cobrado_actual is NULL";
        $query_productos = _query($sql_productos);
        if(_num_rows($query_productos) > 0){
            $array_data = array(
                array("LABORATORIOS REALIZADOS AL PACIENTE EN NEFROLOGIA",190,"L"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->setX(10);
            $array_data = array(
                array("No. Solicitud",25,"C"),
                array("Fecha del cargo",30,"C"),
                array("Descripcion",65,"C"),
                array("Cantidad",20,"C"),
                array("Precio Unitario",25,"C"),
                array("Monto",25,"C"),
            );
            $pdf->LineWriteB($array_data);
            $pdf->SetFont('Arial','',8);
            while($row_productos = _fetch_array($query_productos)){
                $id_producto = $row_productos['id_insumo'];
                $descripcion = $row_productos['nombre_examen'];
                $fecha_del_cargo = $row_productos['created_at'];
                $fecha_del_cargo = explode(" ",$fecha_del_cargo);
                $fecha_cargo = ED($fecha_del_cargo[0]);
                $hora_cargo = _hora_media_decode($fecha_del_cargo[1]);
                $cantidad = 1;
                $examenes_nefrologia+=($row_productos['precio_examen'] * $cantidad);
                $precio = number_format($row_productos['precio_examen'],2);
                $monto = number_format(($row_productos['precio_examen'] * $cantidad),2);
                $pdf->setX(10);
                $array_data = array(
                    array($id_producto,25,"C"),
                    array($fecha_cargo." ".$hora_cargo,30,"C"),
                    array($descripcion,65,"C"),
                    array($cantidad,20,"C"),
                    array("$ ".$precio,25,"C"),
                    array("$ ".$monto,25,"C"),
                );
                $pdf->LineWriteB($array_data);
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->setX(10);
            $array_data = array(
                array("",25,"C"),
                array("",30,"C"),
                array("SUB TOTAL POR CLASE...",65,"C"),
                array("",20,"C"),
                array("",25,"C"),
                array("$ ".number_format($examenes_nefrologia,2),25,"C"),
            );
            $pdf->LineWriteB($array_data);
        }

        
        $total_nefrologia = $productos_nefrologia + $servicios_nefrologia + $examenes_nefrologia;
        $pdf->setX(10);
        $array_data = array(
            array("TOTAL GENERAL...",165,"C"),                
            array("$ ".number_format($total_nefrologia,2),25,"C"),
        );
        $pdf->LineWriteB($array_data);  
    }

    //FINAL DE TRAER COBROS DE NEFROLOGIA
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////






    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////
    //TRAER TODOS LOS COBROS FINALES
    $y = $pdf->getY();
    $pdf-> setXY(10,$y+5);
    $array_data = array(
        array("TOTALES POR CLASES",190,"L"),
    );
    $pdf->LineWriteB($array_data);
    $pdf->setX(10);
    $array_data = array(
        array("No",25,"C"),
        array("NOMBRE DE CLASE",140,"C"),
        array("TOTAL",25,"C"),
    );
    $pdf->LineWriteB($array_data);
    $contador = 1;
    $pdf->SetFont('Arial','',8);
    $TOTAL_HOSPITALIZACION = $total_hospitalizacion;
    if($TOTAL_HOSPITALIZACION > 0){
        $pdf->setX(10);
        $array_data = array(
            array($contador,25,"C"),
            array("HOSPITALIZACION",140,"C"),
            array("$ ".number_format($TOTAL_HOSPITALIZACION,4),25,"C"),
        );
        $pdf->LineWriteB($array_data);
        $contador++;
    }
    $TOTAL_EMERGENCIA = $total_emergencia;

    if($TOTAL_EMERGENCIA > 0){
        $pdf->setX(10);
        $array_data = array(
            array($contador,25,"C"),
            array("EMERGENCIA",140,"C"),
            array("$ ".number_format($TOTAL_EMERGENCIA,4),25,"C"),
        );
        $pdf->LineWriteB($array_data);
        $contador++;
    }

    $TOTAL_RAYOSX = $total_rayos_x;
    if($TOTAL_RAYOSX > 0){
        $pdf->setX(10);
        $array_data = array(            
            array($contador,25,"C"),
            array("RAYOS X",140,"C"),
            array("$ ".number_format($TOTAL_RAYOSX,4),25,"C"),
        );
        $pdf->LineWriteB($array_data);
        $contador++;
    }

    $TOTAL_SALA_OPERACIONES = $total_sala_operaciones;
    if($TOTAL_SALA_OPERACIONES > 0){
        $pdf->setX(10);
        $array_data = array(            
            array($contador,25,"C"),
            array("SALA DE OPERACIONES",140,"C"),
            array("$ ".number_format($TOTAL_SALA_OPERACIONES,4),25,"C"),
        );
        $pdf->LineWriteB($array_data);
        $contador++;
    }


    $TOTAL_NEFROLOGIA = $total_nefrologia;
    if($TOTAL_NEFROLOGIA > 0){
        $pdf->setX(10);
        $array_data = array(            
            array($contador,25,"C"),
            array("NEFROLOGIA",140,"C"),
            array("$ ".number_format($TOTAL_NEFROLOGIA,4),25,"C"),
        );
        $pdf->LineWriteB($array_data);
        $contador++;
    }
    //FINAL DE TRAER TODOS LOS COBROS FINALES
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////
    /////////////////////////

    if($abono == "" || $abono == NULL){
        $abono = 0;
    }
    //TOTAL FINAL DE TODOS LOS COBROS FINALES
    $total_final_todo = $TOTAL_HOSPITALIZACION + $TOTAL_EMERGENCIA + $TOTAL_RAYOSX + $TOTAL_SALA_OPERACIONES + $TOTAL_NEFROLOGIA;
    $pdf->SetFont('Arial','B',8);
    $pdf->setX(10);
    $array_data = array(         
        array("TOTAL",165,"C"),
        array("$ ".number_format($total_final_todo,2),25,"C"),
    );
    $pdf->LineWriteB($array_data);
    //FINAL TOTAL DE TODOS LOS COBROS FINALES


    if($contador_hospitalizacion > 0 || $pequenia_cirugia_activa == 1 || $contador_sala_operaciones > 0){
        $y = $pdf->getY();
        $pdf->setXY(10,$y+5);
        $array_data = array(
            array("SERVICIOS ADMINISTRATIVOS",190,"L"),
        );    
        $pdf->LineWriteB($array_data);
        $pdf->setX(10);
        $array_data = array(
            array("Descripcion",165,"C"),
            array("Monto",25,"C"),
        );
        $pdf->LineWriteB($array_data);
        $pdf->SetFont('Arial','',8);
        $pdf->setX(10);
        $servicios_administrativos_enfermeria = $total_final_todo * 0.30;
        $array_data = array(
            array("SERVICIOS ADMINISTRATIVOS Y ENFERMERIA",165,"C"),
            array("$ ".number_format($servicios_administrativos_enfermeria,2),25,"C"),
        );
        $pdf->LineWriteB($array_data);
        $pdf->setX(10);
        $servicios_generales_hospitalarios = $total_final_todo * 0.10;
        $total_final_todo += $servicios_generales_hospitalarios + $servicios_administrativos_enfermeria;
        $array_data = array(
            array("SERVICIOS GENERALES HOSPITALARIOS",165,"C"),
            array("$ ".number_format($servicios_generales_hospitalarios,2),25,"C"),
        );
        $pdf->LineWriteB($array_data);
    }
    if($contador_hospitalizacion > 0 || $contador_emergencia > 1 || $contador_sala_operaciones > 0){
        $pdf->SetFont('Arial','B',10);
        $total_iva = $total_final_todo*0.13;
        $pdf->SetDrawColor(0,0,0);
        $pdf->Line(140,218,200,218);
        $pdf->setXY(120,220);
        $pdf->Cell(100,5,"Total Sin IVA                     $ ".number_format($total_final_todo,2),0,1,'C');
        $pdf->setXY(120,225);
        $pdf->Cell(100,5,"Total IVA                         $ ".number_format($total_iva,2),0,1,'C');
        $pdf->setXY(120,230);
        $pdf->Cell(100,5,"Abono                             $ ".number_format($abono,2),0,1,'C');
        $pdf->setXY(120,235);
        $pdf->Cell(100,5,"Total                             $ ".number_format((($total_iva + $total_final_todo) - $abono),2),0,1,'C');
    }
    else{
        $pdf->setXY(120,220);
        $pdf->Cell(100,5,"Total                            $ ".number_format(($total_final_todo),2),0,1,'C');
    }
    





ob_clean();
$pdf->Output("reporte_estado_cuenta.pdf","I");

