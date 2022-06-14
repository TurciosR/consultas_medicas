<?php

error_reporting(E_ERROR | E_PARSE);
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');

$id_sucursal=$_SESSION["id_sucursal"];
$id_cita=$_REQUEST["id_cita"];

$sqll = _query("SELECT * FROM empresa where id_empresa='$id_sucursal'");
$fila = _fetch_array($sqll);
$nombre = $fila["nombre"];
$direccion = $fila["direccion"];
$telefono1 = $fila["telefono1"];
$telefono2 = $fila["telefono2"];
$id_municipio = $fila['municipio'];
$id_departamento = $fila['departamento'];
$email = $fila["email"];
$logo = $fila['logo'];
$logo2 = $fila['logo'];

$sql_departamento = "SELECT * FROM departamento where id_departamento = '$id_departamento'";
$query_departamento = _query($sql_departamento);
$row_departamento = _fetch_array($query_departamento);
$nombre_departamento = $row_departamento['nombre_departamento'];

$sql_municipio = "SELECT * FROM municipio WHERE id_municipio = '$id_municipio'";
$query_municipio = _query($sql_municipio);
$row_municipio = _fetch_array($query_municipio);
$nombre_municipio = $row_municipio['nombre_municipio'];

$nombre_direccion = $nombre_municipio.", ".$nombre_departamento.".";

$sql_consulta = "SELECT * FROM reserva_cita WHERE id='$id_cita'";
$query_consulta = _query($sql_consulta);
$row_consulta = _fetch_array($query_consulta);
$fecha_cita = $row_consulta['fecha_cita'];
$hora_cita = $row_consulta['hora_cita'];
$id_paciente = $row_consulta['id_paciente']; //OCUPA SU PROPIA CONSULTA PARA TRAER TODOS LOS DATOS
$id_doctor = $row_consulta['id_doctor'];    //OCUPA SU PROPIA CONSULTA PARA TRAER TODOS LOS DATOS
$id_espacio = $row_consulta['id_espacio'];  //OCUPA SU PROPIA CONSULTA PARA TRAER TODOS LOS DATOS
$motivo_consulta = $row_consulta['motivo_consulta'];
$observaciones = $row_consulta['observaciones'];
$diagnostico = $row_consulta['diagnostico'];
$examen = $row_consulta['examen'];
$medicamento = $row_consulta['medicamento'];
$t_o = $row_consulta['t_o'];
$ta = $row_consulta['ta'];
$p = $row_consulta['p'];
$peso = $row_consulta['peso'];
$fr = $row_consulta['fr'];
$spo2 = $row_consulta['saturacion'];
$hemoglucotest = $row_consulta['hemoglucotest'];
$hallazgo_fisico = $row_consulta['hallazgo_fisico'];
$historia_clinica = $row_consulta['historia_clinica'];
$antecedente_familiar = $row_consulta['antecedente_familiar'];
$antecedente_personal = $row_consulta['antecedente_personal'];
$indicacion_medica = $row_consulta['indicacion_medica'];
$ingreso_hospitalario = $row_consulta['ingreso_hospitalario'];
$otros_cobros = $row_consulta['otros_cobros'];
$saturacion = $row_consulta['saturacion'];
$examenes_ultra=$row_consulta['examenes_ultra'];
$dx_ultra=$row_consulta['dx_ultra'];
$dx=$row_consulta['dx'];
$plan=$row_consulta['plan'];
$altura=$row_consulta['altura'];

$fc=$row_consulta['fc'];
$altura=$row_consulta['altura'];


$sql_doctor = "SELECT * FROM doctor WHERE id_doctor = '$id_doctor'";
$query_doctor = _query($sql_doctor);
$row_doctor = _fetch_array($query_doctor);
$nombres_doctor = $row_doctor['nombres'];
$apellidos_doctor = $row_doctor['apellidos'];
$jvpm = $row_doctor['jvpm'];
$nombre_doctor  = $nombres_doctor." ".$apellidos_doctor;


$sql_paciente = "SELECT * FROM paciente WHERE id_paciente = '$id_paciente'";
$query_paciente = _query($sql_paciente);
$row_paciente = _fetch_array($query_paciente);

$nombres_paciente = $row_paciente['nombres'];
$apellidos_paciente = $row_paciente['apellidos'];
$nombre_paciente = $nombres_paciente." ".$apellidos_paciente;
$sexo_paciente = $row_paciente['sexo'];
$edad = edad($row_paciente['fecha_nacimiento']);
$expediente = $row_paciente['expediente'];
$direccion_paciente = $row_paciente['direccion'];
$fecha_de_nacimiento = $row_paciente['fecha_nacimiento'];
$dui = $row_paciente['dui'];
$responsable = $row_paciente['responsable'];
if($responsable != ""){
    $parentezco_responsable = $row_paciente['parentezco_responsable'];
    $sql_parentezco = "SELECT * FROM parentezco WHERE id_parentezco = '$parentezco_responsable'";
    $query_parentezco = _query($sql_parentezco);
    $row_parentezco = _fetch_array($query_parentezco);
    $descripcion_parentezco = $row_parentezco['descripcion'];
}


/* ACA SE REALIZA LA CONSULTA DEL DIAGNOSTICO DEL PACIENTE */
$query_diagnostico_ant = _query("SELECT d.descripcion, dp.id_diagnostico FROM diagnostico_paciente as dp, diagnostico as d WHERE d.id_diagnostico=dp.id_diagnostico AND dp.id_cita='$id_cita' AND dp.id_paciente='$id_paciente'");
$num_diagnostico_ant = _num_rows($query_diagnostico_ant);
/* ACA SE REALIZA LA CONSULTA DEL DIAGNOSTICO DEL PACIENTE */

/* ACA SE REALIZA LA CONSULTA DE LOS EXAMENES QUE SE LE
DEJARON AL PACIENTE */
$query_examen_ant = _query("SELECT e.descripcion, ep.id_examen_paciente as id_examen, e.url, e.ver, ep.fecha_asignacion, ep.fecha_lectura FROM examen_paciente as ep, examen as e WHERE e.id_examen=ep.id_examen AND ep.id_cita='$id_cita' AND ep.id_paciente='$id_paciente'");
$num_examen_ant = _num_rows($query_examen_ant);
    
/* ACA SE REALIZA LA CONSULTA DE LOS EXAMENES QUE SE LE
DEJARON AL PACIENTE */

/* ACA SE REALIZA LA CONSULTA DE LOS MEDICAMENTOS QUE SE LE 
DEJARON AL PACIENTE */
$query_receta_ant = _query("SELECT m.* , r.id_medicamento,r.dosis FROM receta as r, medicamento as m WHERE m.id_medicamento=r.id_medicamento AND r.id_cita='$id_cita' AND r.id_paciente='$id_paciente'");
$num_receta_ant = _num_rows($query_receta_ant);
/* ACA SE REALIZA LA CONSULTA DE LOS MEDICAMENTOS QUE SE LE 
DEJARON AL PACIENTE */

/* ACA SE REALIZA LA CONSULTA DE LAS IMAGENES SUBIDAS EN
LA CONSULTA ANTERIOR */

$query_img_ant = _query("SELECT * FROM img_paciente WHERE id_cita='$id_cita' AND id_paciente='$id_paciente' AND url LIKE '%.jpg'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita' AND id_paciente='$id_paciente' AND url LIKE '%.png'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita' AND id_paciente='$id_paciente' AND url LIKE '%.bmp'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita' AND id_paciente='$id_paciente' AND url LIKE '%.gif'");
$num_img_ant = _num_rows($query_img_ant);
/* ACA SE REALIZA LA CONSULTA DE LAS IMAGENES SUBIDAS EN
LA CONSULTA ANTERIOR */
$id_cita_ant = $id_cita;
/*ACA SE REALIZA LA CONSULTA DE LAS REFERENCIAS DADAS
EN LA CONSULTA ANTERIOR */
$query_referencia_ant_a = _query("SELECT * FROM referencia WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente'");
$num_referencia_ant_a = _num_rows($query_referencia_ant_a);
/*ACA SE REALIZA LA CONSULTA DE LAS REFERENCIAS DADAS
EN LA CONSULTA ANTERIOR */


/* ACA SE REALIZA LA CONSULTA DE LAS REFERENCIAS DE LOS 
DOCUMENTOS SUBIDOS */
$query_img2_ant = _query("SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.pdf'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.doc'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.docx'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.odt'");
$num_img2_ant = _num_rows($query_img2_ant);
/* ACA SE REALIZA LA CONSULTA DE LAS REFERENCIAS DE LOS 
DOCUMENTOS SUBIDOS */


/* ACA SE REALIZA LA CONSULTA DE LOS SIGNOS VITALES
OBTENIDOS EN LA CONSULTA ANTERIOR */
$sql2_ant_a= _query("SELECT * FROM signos_vitales WHERE id_paciente ='$id_paciente' AND id_cita='$id_cita_ant' ORDER BY id_signo DESC LIMIT 1");
$num2_ant_a = _num_rows($sql2_ant_a);
/* ACA SE REALIZA LA CONSULTA DE LOS SIGNOS VITALES
OBTENIDOS EN LA CONSULTA ANTERIOR */

/* ACA SE REALIZA LA CONSULTA DE LAS CONSTANCIAS GENERADAS
EN LA CONSULTA ANTERIOR */
$query_constancias = _query("SELECT * FROM constancia WHERE id_cita = '$id_cita'");
$numero_constancias = _num_rows($query_constancias);
/* ACA SE REALIZA LA CONSULTA DE LAS CONSTANCIAS GENERADAS
EN LA CONSULTA ANTERIOR */

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
    'fecha_nacimiento' => $row_paciente['fecha_nacimiento'],
    'edad' => $edad,
    'expediente' => $expediente,
    'nombre_direccion' => $nombre_direccion,
    'jvpm' => $jvpm,
    'logo2' => $logo2,
    'fecha_cita' => $fecha_cita,
    'hora_cita' => $hora_cita,
    'id_cita' => $id_cita
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
        $b2="";
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
        $b2="";
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
        if($y + $he > 250){
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
          $this->Cell($data[$j]["size"][$i],7,$str,$abajo,$salto,$data[$j]["aling"][$i],1);
        }

        $this->setX(10);
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
    }
    public function Header()
    {
        //$this->Image("img/fondo_reporte.png",0,0,220,280);
        if ($this->PageNo() == 1){
            $set_x = $this->getX();
            $set_y = 0;
            $this->SetLineWidth(.5);
            $this->SetFillColor(255,255,255);
            $this->Image($this->infoext['logo2'],$set_x+130,$set_y,50,50);
            $this->SetDrawColor(0,0,0);
            $this->SetFont('Arial', 'B', 14);
            $this->SetTextColor(25, 65, 96);
            $set_y +=10;
            $this->setY($set_y+5);
            $this->Cell(160,7,utf8_decode("Cita #".$this->infoext['id_cita']." realizada el ".ED($this->infoext['fecha_cita'])." a las "._hora_media_decode($this->infoext['hora_cita'])),0,1,'L');
            $this->setY($set_y+12);
            $this->Cell(160,7,utf8_decode("Por el doctor: ".$this->infoext['nombre_doctor']),0,1,'L');
            $this->setY($set_y+19);
            $this->Cell(160,7,utf8_decode("Al paciente: ".$this->infoext['nombre_paciente']),0,1,'L');
            $this->setY($set_y+26);
            $this->Cell(160,7,utf8_decode("Nacido el : ".ED($this->infoext['fecha_nacimiento'])."  con ".$this->infoext['edad']." años de edad"),0,1,'L');
            $this->setY($set_y+33);
            $this->Cell(160,7,utf8_decode("Con numero de expediente: ".str_pad($this->infoext['expediente'], 6, '0', STR_PAD_LEFT)),0,1,'L');
            $this->SetLineWidth(.5);
            $this->SetDrawColor(25,65,96);
            $this->Line(13,$set_y+40,203,$set_y+40);
            $this->Line(23,$set_y+43,193,$set_y+43);
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


$pdf->setY(60);
$pdf->SetDrawColor(25,65,96);
cabecera_principal($pdf);
$array_data = array(
    array("RESULTADOS",190,"C"),
);
$pdf->setX(10);
$pdf->LineWriteB($array_data);

$array_data = array(
    array("DATOS GENERALES",190,"C"),
);
$pdf->setY($pdf->getY()+5);
$pdf->setX(10);
$pdf->LineWriteB($array_data);

cabecera_secundaria($pdf);
$pdf->SetFont('Arial', 'B', 12);
$array_data = array(
    array("Nombre Completo",85,"C"),
    array("Direccion",105,"C"),
);
$pdf->LineWriteB($array_data);
celda_comun($pdf);
$array_data = array(
    array(utf8_decode($nombre_paciente),85,"C"),
    array(utf8_decode($direccion_paciente.", ".$nombre_direccion),105,"C"),
);
$pdf->LineWriteB($array_data);
cabecera_secundaria($pdf);

if($dui != ""){
    $array_data = array(
        array("Telefono",38,"C"),
        array("Fecha de Nacimiento",47,"C"),
        array("Edad",29,"C"),
        array("Genero",38,"C"),
        array("Dui",38,"C"),
    );
    $pdf->LineWriteB($array_data);
    celda_comun($pdf);
    $array_data = array(
        array($telefono1,38,"C"),
        array($fecha_de_nacimiento,47,"C"),
        array(utf8_decode($edad." Años"),29,"C"),
        array($sexo_paciente,38,"C"),
        array($dui,38,"C"),
    );
    $pdf->LineWriteB($array_data);
}
else{
    $array_data = array(
        array("Telefono",37.5,"C"),
        array("Fecha de Nacimiento",47.5,"C"),
        array("Edad",47.5,"C"),
        array("Genero",57.5,"C"),
    );
    $pdf->LineWriteB($array_data);
    celda_comun($pdf);
    $array_data = array(
        array($telefono1,37.5,"C"),
        array($fecha_de_nacimiento,47.5,"C"),
        array(utf8_decode($edad." Años"),47.5,"C"),
        array($sexo_paciente,57.5,"C"),
    );
    $pdf->LineWriteB($array_data);
}
if($parentezco_responsable != ""){
    cabecera_secundaria($pdf);
    $array_data = array(
        array("Nombre del responsable",95,"C"),
        array("Parentezco del responsable",95,"C"),
    );
    $pdf->LineWriteB($array_data);
    celda_comun($pdf);
    $array_data = array(
        array(Mayu(utf8_decode($responsable)),95,"C"),
        array($descripcion_parentezco,95,"C"),
    );
    $pdf->LineWriteB($array_data);
}

if($motivo_consulta != "" || $historia_clinica !=""){
    espacios($pdf,5);
    cabecera_principal($pdf);
    $array_data = array(
        array("ASUNTO / HISTORIA CLINICA",190,"C"),
    );
    $pdf->LineWriteB($array_data);
    if($motivo_consulta != ""){
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Asunto / Motivo",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data = array(
            array(utf8_decode($motivo_consulta),190,"L"),
        );
        $pdf->LineWriteB($array_data);
    }
    if($historia_clinica != ""){
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Historia Clinica",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data = array(
            array(utf8_decode($historia_clinica),190,"L"),
        );
        $pdf->LineWriteB($array_data);
    }
}
if($antecedente_personal != "" || $antecedente_familiar != ""){
    espacios($pdf,5);
    cabecera_principal($pdf);
    $array_data = array(
        array("ANTECEDENTES",190,"C"),
    );
    $pdf->LineWriteB($array_data);
    if($antecedente_personal != ""){
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Antecedentes Personales",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data = array(
            array(utf8_decode($antecedente_personal),190,"L"),
        );
        $pdf->LineWriteB($array_data);
    }
    if($antecedente_familiar != ""){
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Antecedentes Familiares",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data = array(
            array(utf8_decode($antecedente_familiar),190,"L"),
        );
        $pdf->LineWriteB($array_data);
    }
}

    espacios($pdf,5);
    cabecera_principal($pdf);
    $array_data = array(
        array("HALLAZGOS FISICOS / SIGNOS VITALES",190,"C"),
    );
    $pdf->LineWriteB($array_data);
    cabecera_secundaria($pdf);
    $array_data = array(
        array("Signos Vitales / Evaluacion Fisica",190,"L"),
    );
    $pdf->LineWriteB($array_data);
    cabecera_tercearia($pdf);
    $array_data = array(
        array("TA",47.5,"C"),
        array("P",47.5,"C"),
        array(utf8_decode("T°"),47.5,"C"),
        array("Peso",47.5,"C"),
    );
    $pdf->LineWriteB($array_data);
    celda_comun($pdf);
    $array_data = array(
        array($ta,47.5,"C"),
        array($p,47.5,"C"),
        array($t_o,47.5,"C"),
        array($peso,47.5,"C"),
    );
    $pdf->LineWriteB($array_data);

    cabecera_tercearia($pdf);
    $array_data = array(
        array("FR",47.5,"C"),
        array("SpO2",47.5,"C"),
        array("Hemoglucotest",47.5,"C"),
        array("Saturacion",47.5,"C"),
    );
    $pdf->LineWriteB($array_data);
    celda_comun($pdf);
    $array_data = array(
        array($fr,47.5,"C"),
        array($spo2,47.5,"C"),
        array($hemoglucotest,47.5,"C"),
        array($saturacion,47.5,"C"),
    );
    $pdf->LineWriteB($array_data);

    if($fc!='' || $altura!=''){
        cabecera_tercearia($pdf);
        $array_data=array(
            array('FC', 95, "C"),
            array('ALTURA', 95, "C")
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data=array(
            array(utf8_decode($fc), 95, "C"),
            array(utf8_decode($altura), 95, "C")
        );
        $pdf->LineWriteB($array_data);   
    }

    if($plan!='' || $dx!=''){
        cabecera_tercearia($pdf);
        $array_data=array(
            array(utf8_decode('Dx'), 95, "C"),
            array(utf8_decode('PLAN'), 95, "C"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data=array(
            array(utf8_decode($dx), 95, 'L'),
            array(utf8_decode($plan), 95, "L"),
        );

        $pdf->LineWriteB($array_data);
    }


    if($hallazgo_fisico != ""){
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Hallazgos Fisico",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data = array(
            array(utf8_decode($hallazgo_fisico),190,"L"),
        );
        $pdf->LineWriteB($array_data);
    }

if($num_diagnostico_ant > 0 || $diagnostico !=""){
    //espacios($pdf,5);
    $pdf->AddPage();
    cabecera_principal($pdf);
    $array_data = array(
        array("DIAGNOSTICO",190,"C"),
    );
    $pdf->LineWriteB($array_data);
    cabecera_secundaria($pdf);
    $array_data = array(
        array(utf8_decode("Diagnóstico (Según estándar CIE-10-ES)"),190,"L"),
    );
    $pdf->LineWriteB($array_data);
    cabecera_tercearia($pdf);
    $array_data = array(
        array(utf8_decode("N°"),20,"C"),
        array("Diagnostico",170,"C"),
    );
    $pdf->LineWriteB($array_data);
    $numero_diagnostico = 1;
    celda_comun($pdf);
    while($row_diagnostico = _fetch_array($query_diagnostico_ant)){
        $array_data = array(
            array($numero_diagnostico,20,"C"),
            array(utf8_decode($row_diagnostico['descripcion']),170,"C"),
        );
        $pdf->LineWriteB($array_data);
        $numero_diagnostico++;
    }
    if($diagnostico != ""){
        cabecera_secundaria($pdf);

        $array_data = array(
            array("Otro Diagnostico",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data = array(
            array(utf8_decode($diagnostico),190,"L"),
        );
        $pdf->LineWriteB($array_data);
    }
}

if($num_examen_ant > 0 || $examen !=""){
    espacios($pdf,5);
    cabecera_principal($pdf);
    $array_data = array(
        array("EXAMENES DEJADOS",190,"C"),
    );
    $pdf->LineWriteB($array_data);
    cabecera_secundaria($pdf);
    $array_data = array(
        array(utf8_decode("Examen"),190,"L"),
    );
    $pdf->LineWriteB($array_data);
    cabecera_tercearia($pdf);
    $array_data = array(
        array(utf8_decode("N°"),20,"C"),
        array("Examenes",170,"C"),
    );
    $pdf->LineWriteB($array_data);
    $numero_diagnostico = 1;
    celda_comun($pdf);
    while($row_examenes = _fetch_array($query_examen_ant)){
        $array_data = array(
            array($numero_diagnostico,20,"C"),
            array(utf8_decode($row_examenes['descripcion']),170,"C"),
        );
        $pdf->LineWriteB($array_data);
        $numero_diagnostico++;
    }
    if($examen != ""){
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Otros Examenes",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data = array(
            array(utf8_decode($examen),190,"L"),
        );
        $pdf->LineWriteB($array_data);
    }
}

if($examenes_ultra!=''){
    $pdf->AddPage();
    //espacios($pdf, 20);
    cabecera_principal($pdf);
    $array_data = array(
        array(utf8_decode("Examenes de Ultrasonografia"),190,"C"),
    );
    $pdf->LineWriteB($array_data);
    celda_comun($pdf);
    $set_y=$pdf->GetY();
    $set_x=$pdf->GetX();
    $pdf->Image($examenes_ultra,$set_x, $set_y,195,200);

}
if($dx_ultra!=''){
    $pdf->AddPage();
    espacios($pdf, 5);
    cabecera_principal($pdf);
    $array_data=array(
        array(utf8_decode("Diagnostico de la Ultrasonografia") ,190, "C"),
    );
    $pdf->LineWriteB($array_data);
    celda_comun($pdf);
    $array_data=array(
        array(utf8_decode($dx_ultra), 190, "C"),
    );
    $pdf->LineWriteB($array_data);
}

if($num_receta_ant > 0 || $medicamento !=""){
    espacios($pdf,5);
    cabecera_principal($pdf);
    $array_data = array(
        array("RECETA",190,"C"),
    );
    $pdf->LineWriteB($array_data);
    cabecera_secundaria($pdf);
    $array_data = array(
        array(utf8_decode("Medicamentos (Vadecum)"),190,"L"),
    );
    $pdf->LineWriteB($array_data);
    cabecera_tercearia($pdf);
    
    $numero_diagnostico = 1;
    celda_comun($pdf);
    while($row_medicamento = _fetch_array($query_receta_ant)){
        $array_data = array(
            array($numero_diagnostico,20,"C"),
            array(utf8_decode($row_medicamento['descripcion']),90,"C"),
            array(utf8_decode($row_medicamento['dosis']),80,"C"),
        );
        $pdf->LineWriteB($array_data);
        $numero_diagnostico++;
    }
    if($medicamento != ""){
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Otros Medicamentos",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data = array(
            array(utf8_decode($medicamento),190,"L"),
        );
        $pdf->LineWriteB($array_data);
    }
}

if($ingreso_hospitalario!='' || $indicacion_medica!=''){
    espacios($pdf, 5);
    cabecera_principal($pdf);
    $array_data=array(
        array("INGRESO HOSPITALARIO", 190, "C"),
    );
    $pdf->LineWriteB($array_data);
    celda_comun($pdf);
    $array_data=array(
        array(utf8_decode($ingreso_hospitalario), 190, "L"),
    );
    $pdf->LineWriteB($array_data);
    cabecera_secundaria($pdf);
    $array_data=array(
        array(utf8_decode("INDICACIONES MEDICAS"), 190, "C"),
    );
    $pdf->LineWriteB($array_data);
    celda_comun($pdf);
    $array_data=array(
        array(utf8_decode($indicacion_medica), 190, "L")
    );

    $pdf->LineWriteB($array_data);

}

if($numero_constancias > 0){
    espacios($pdf,5);
    cabecera_principal($pdf);
    $array_data = array(
        array("CONSTANCIAS GENERADAS",190,"C"),
    );
    $pdf->LineWriteB($array_data);
    $numero_de_constancia = 1;
    while ($row_constancias = _fetch_array($query_constancias)) {
        espacios($pdf,5);
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Constancia #".$numero_de_constancia,190,"L"),
        );
        $pdf->LineWriteB($array_data);
        cabecera_tercearia($pdf);
        $array_data = array(
            array("Padecimiento",190,"C"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $array_data = array(
            array($row_constancias['padecimiento'],190,"C"),
        );
        $pdf->LineWriteB($array_data);
        cabecera_tercearia($pdf);
        $array_data = array(
            array("Dias de Reposo",50,"C"),
            array("Doctor",100,"C"),
            array("Fecha",40,"C"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $id_doctor_constancia = $row_constancias['id_doctor'];
        $sql_doctor_constancia = "SELECT * FROM doctor WHERE id_doctor = '$id_doctor_constancia'";
        $query_doctor_constancia = _query($sql_doctor_constancia);
        $row_doctor_constancia = _fetch_array($query_doctor_constancia);
        $nombre_doctor = $row_doctor_constancia['nombres']." ".$row_doctor_constancia['apellidos'];
        $array_data = array(
            array($row_constancias['reposo']." dias",50,"C"),
            array(utf8_decode($nombre_doctor),100,"C"),
            array(ED($row_constancias['fecha']),40,"C"),
        );
        $pdf->LineWriteB($array_data);
        $numero_de_constancia++;
    }
}   
$query_futuras_consultas = _query("SELECT * FROM reserva_cita INNER JOIN espacio on espacio.id_espacio = reserva_cita.id_espacio WHERE id_paciente = '$id_paciente' AND id != ".$id_cita." AND fecha_cita >= '".date("Y:m:d")."' ");
if(_num_rows($query_futuras_consultas) > 0){
    espacios($pdf,5);
    cabecera_principal($pdf);
    $array_data = array(
        array("FUTURAS CONSULTAS",190,"C"),
    );
    $pdf->LineWriteB($array_data);
    $numero_de_constancia = 1;
    while ($row_consultas = _fetch_array($query_futuras_consultas)) {
        espacios($pdf,5);
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Consulta #".$numero_de_constancia,190,"L"),
        );
        $pdf->LineWriteB($array_data);
        cabecera_tercearia($pdf);
        $array_data = array(
            array("Motivo",47.5,"C"),
            array("Doctor",65,"C"),
            array("Consultorio",30,"C"),
            array("Fecha",23.75,"C"),
            array("Hora",23.75,"C"),
        );
        $pdf->LineWriteB($array_data);
        celda_comun($pdf);
        $id_doctor_constancia = $row_consultas['id_doctor'];
        $motivo = $row_consultas['motivo_consulta'];
        $consultorio = $row_consultas['descripcion'];
        $fecha = ED($row_consultas['fecha_cita']);
        $hora = _hora_media_decode($row_consultas['hora_cita']);
        
        $sql_doctor_constancia = "SELECT * FROM doctor WHERE id_doctor = '$id_doctor_constancia'";
        $query_doctor_constancia = _query($sql_doctor_constancia);
        $row_doctor_constancia = _fetch_array($query_doctor_constancia);
        $nombre_doctor = $row_doctor_constancia['nombres']." ".$row_doctor_constancia['apellidos'];

        $array_data = array(
            array(utf8_decode($motivo),47.5,"C"),
            array(utf8_decode($nombre_doctor),65,"C"),
            array(utf8_decode($consultorio),30,"C"),
            array($fecha_cita,23.75,"C"),
            array($hora_cita,23.75,"C"),
        );
        $pdf->LineWriteB($array_data);
        $numero_de_constancia++;
    }
}
if($num_img_ant > 0 || $num_img2_ant > 0){
    $pdf->addPage();
    espacios($pdf,5);
    cabecera_principal($pdf);
    $array_data = array(
        array("ANEXOS",190,"C"),
    );
    $pdf->LineWriteB($array_data);
    if($num_img_ant > 0){
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Imagenes",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        cabecera_tercearia($pdf);
        celda_comun($pdf);
        $count_imagenes = 0;
        while ($row_imagenes = _fetch_array($query_img_ant)) {
            if($count_imagenes > 0){
                $pdf->addPage();
            }
            espacios($pdf,5);
            $descripcion_imagen = $row_imagenes['descripcion'];
            $url_imagen = $row_imagenes['url'];
            $pdf->Cell(0,10,utf8_decode($descripcion_imagen),0,0,'C');
            espacios($pdf,10);
            $pdf->Image($url_imagen,10,$pdf->getY(),190);
            $count_imagenes++;
        }
    }
    if($num_img2_ant > 0){
        if($num_img_ant > 0){
            $pdf->addPage();
        }
        cabecera_secundaria($pdf);
        $array_data = array(
            array("Documentos",190,"L"),
        );
        $pdf->LineWriteB($array_data);
        cabecera_tercearia($pdf);
        $count_imagenes = 0;
        while ($row_documentos = _fetch_array($query_img2_ant)) {
            espacios($pdf,5);
            $descripcion_imagen = $row_documentos['descripcion'];
            $url_imagen = $row_documentos['url'];
            //$pdf->Cell(0,10,utf8_decode($url_imagen),0,0,'C');

            $pdf->Cell(20,8 ,"Nombre de Documento: ".utf8_decode($descripcion_imagen),'','','',false, "./".$url_imagen); 

        }
    }
    
}



ob_clean();
$pdf->Output("receta_pdf.pdf","I");



function cabecera_principal($pdf){
    $pdf->setX(10);
    $pdf->SetFillColor(25,65,96);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Arial', 'B', 14);
}
function cabecera_secundaria($pdf){
    $pdf->setX(10);
    $pdf->SetFillColor(157,255,212);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial', 'B', 12);
}
function cabecera_tercearia($pdf){
    $pdf->setX(10);
    $pdf->SetFillColor(255,217,217);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial', 'B', 12);
}
function celda_comun($pdf){
    $pdf->setX(10);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial', '', 11);
}
function espacios($pdf,$cantidad){
    $pdf->setY($pdf->getY() + $cantidad);
}



?>
