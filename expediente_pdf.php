<?php 
require("_core.php");
require("num2letras.php");
require('fpdf/fpdf.php');

class PDF extends FPDF
{
//Cabecera de página
function Header()
{
    $sql_empresa = "SELECT e.*, d.nombre_departamento, m.nombre_municipio FROM empresa as e, departamento as d, municipio as m WHERE d.id_departamento=e.departamento AND m.id_municipio=e.municipio AND e.id_empresa='1'";
    $resultado_emp=_query($sql_empresa);
    $num_rows = _num_rows($resultado_emp);
    $row_emp=_fetch_array($resultado_emp);
    $empresa=$row_emp['nombre'];
    $direccion_emp = $row_emp["direccion"].", ".$row_emp["nombre_municipio"].", ".$row_emp["nombre_departamento"];
    $telefonos="TEL. ".$row_emp['telefono1'].' - '.$row_emp['telefono2'];

    $set_x = 15;
    $set_y = 26;

    //Header Title
    $this->SetFont('Times','B',10);
    $this->SetXY($set_x-10,$set_y-7);    
    $this->Cell(206,-10,utf8_decode($empresa),0,1,'C');
    $this->SetXY($set_x-10,$set_y-1);    
    $this->Cell(206,-10,utf8_decode($direccion_emp),0,1,'C');
    $this->SetXY($set_x-10,$set_y+5);    
    $this->Cell(206,-10,utf8_decode($telefonos),0,1,'C');
    $this->SetXY($set_x-10,$set_y+11);   
}

//Pie de página
function Footer()
{
    $set_x=0;
    $set_y=265;   
    $this->SetXY($set_x+10, $set_y);
    $this->SetFont('Times','B',10); 
    $this->Cell(0, 0.4, 'Impreso: '.date('d-m-Y')." ".hora(date("H:i:s")), 0, 0, 'L');
    $this->Cell(0, 0.4, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'R');
}
}
function hay_salto($y, $pdf, $xy)
{
    //echo $pdf->GetY().", ".$y.", ".$xy." ->";
    $set_y = $xy;
    if($y >= 235)
    {
        $set_y = 28;
        $pdf->AddPage("P","Letter");
        $pdf->SetY(0);
    }
    return $set_y;
        
}
//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage("P","Letter");

$id_paciente_rq = $_REQUEST["id_paciente"];
$ini = '1823-01-01';
$fin = '2017-12-01';
$sql_ini = _query("SELECT id FROM reserva_cita WHERE id_paciente = '$id_paciente_rq' AND estado=7 AND fecha_cita BETWEEN '$ini' AND '$fin' ORDER BY fecha_cita ASC");
$primera = 1;
while($rowq = _fetch_array($sql_ini))
{
    $id_cita = $rowq["id"];

    $query = _query("SELECT r.id_paciente, r.motivo_consulta, r.diagnostico, r.examen, r.medicamento, r.fecha_cita, r.hora_cita, CONCAT(d.nombres,' ',d.apellidos) as doctor FROM reserva_cita as r, doctor as d WHERE d.id_doctor=r.id_doctor AND r.id ='$id_cita'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $doctor = utf8_decode($datos["doctor"]);
    $motivo = utf8_decode($datos["motivo_consulta"]); 
    $diagnostico = utf8_decode($datos["diagnostico"]); 
    $examen = utf8_decode($datos["examen"]); 
    $fecha_cita = nombre_dia($datos["fecha_cita"]); 
    $hora_cita = hora($datos["hora_cita"]); 
    $medicamento = utf8_decode($datos["medicamento"]); 

    $sql2= _query("SELECT * FROM signos_vitales WHERE id_paciente ='$id_paciente' AND id_cita='$id_cita' ORDER BY id_signo DESC LIMIT 1");
    $num2 = _num_rows($sql2);
    $datos =_fetch_array($sql2);
    $estatura = utf8_decode($datos["estatura"]);
    $peso = utf8_decode($datos["peso"]);
    $temperatura = utf8_decode($datos["temperatura"]);
    $presion = utf8_decode($datos["presion"]);
    $observaciones = utf8_decode($datos['observaciones']);
    $fecha_ev = ED($datos['fecha']);
    $hora_ev = hora($datos['hora']);
        
    $query_diagnostico = _query("SELECT d.descripcion, dp.id_diagnostico FROM diagnostico_paciente as dp, diagnostico as d WHERE d.id_diagnostico=dp.id_diagnostico AND dp.id_cita='$id_cita' AND dp.id_paciente='$id_paciente'");
    $num_diagnostico = _num_rows($query_diagnostico);

    $query_examen = _query("SELECT e.descripcion, ep.id_examen FROM examen_paciente as ep, examen as e WHERE e.id_examen=ep.id_examen AND ep.id_cita='$id_cita' AND ep.id_paciente='$id_paciente'");
    $num_examen = _num_rows($query_examen);

    $query_receta = _query("SELECT m.descripcion, m.presentacion, r.id_medicamento, r.cantidad, r.dosis FROM receta as r, medicamento as m WHERE m.id_medicamento=r.id_medicamento AND r.id_cita='$id_cita' AND r.id_paciente='$id_paciente'");
    $num_receta = _num_rows($query_receta);

    $query_img = _query("SELECT * FROM img_paciente WHERE id_cita='$id_cita' AND id_paciente='$id_paciente'");
    $num_img = _num_rows($query_img);

    $query_referencia = _query("SELECT * FROM referencia WHERE id_cita='$id_cita' AND id_paciente='$id_paciente'");
    $num_referencia = _num_rows($query_referencia);

    $sql="SELECT p.*, d.nombre_departamento, m.nombre_municipio FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id_paciente'";
    $result = _query($sql);
    $numm = _num_rows($result);
    $row = _fetch_array($result);
    $nombre=utf8_decode($row['nombres']);
    $apellido = utf8_decode($row['apellidos']);
    $telefono1=$row["tel1"];
    $telefono2=$row["tel2"];
    if($telefono2 !="")
    {
        $telefono1 .= ", WS: ".$row["tel2"];
    }
    $expediente = $row["expediente"];
    $len = strlen((string)$expediente);
    $fill = 7 - $len;
    if($fill <0)
        $fill = 0;
    $n_exp = zfill($expediente, $fill);
    $email=utf8_decode($row["email"]);
    $sexo = utf8_decode($row["sexo"]);
    $fecha = ED($row["fecha_nacimiento"]); 
    $datos_fecha = explode("-", $fecha);
    $anio_nac  = $datos_fecha[2];
    $edad = date("Y") - $anio_nac;             
    $direccion = utf8_decode($row["direccion"].", ".$row["nombre_municipio"].", ".$row["nombre_departamento"]);
    $padecimientos = utf8_decode($row["padecimientos"]);
    $medicamentos = utf8_decode($row["medicamento_permanente"]);
    $alergias = utf8_decode($row["alergias"]);

    $set_x = 15;
    $set_y = 26;
    /*
    //Header Title
    $pdf->SetXY($set_x-10,$set_y-7);    
    $pdf->Cell(206,-10,utf8_decode($empresa),0,1,'C');
    $pdf->SetXY($set_x-10,$set_y-1);    
    $pdf->Cell(206,-10,utf8_decode($direccion_emp),0,1,'C');
    $pdf->SetXY($set_x-10,$set_y+5);    
    $pdf->Cell(206,-10,utf8_decode($telefonos),0,1,'C');
    $pdf->SetXY($set_x-10,$set_y+11);*/
    if(!$primera)
    {
        $pdf->AddPage("P","Letter");
        $pdf->SetFont('Times','B',10); 

        $pdf->Cell(206,-10,utf8_decode('Dr. '.$doctor),0,1,'C');
        $pdf->SetXY($set_x-10,$set_y+17);   
        $pdf->Cell(206,-10,utf8_decode('Consulta del '.$fecha_cita.' a las '.$hora_cita),0,1,'C');
        $pdf->SetFont('Times','',9);
        $set_y = 32;
    } 
    else
    {
        $pdf->SetFont('Times','B',10); 
        $pdf->Cell(206,-10,utf8_decode('Dr. '.$doctor),0,1,'C');
        $pdf->SetXY($set_x-10,$set_y+17);   
        $pdf->Cell(206,-10,utf8_decode('Consulta del '.$fecha_cita.' a las '.$hora_cita),0,1,'C');
        $pdf->SetFont('Times','',9);
       
    }
    if($primera)
    {
        //Header Datos
        $set_y = 48;    
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,"DATOS GENERALES",1,0,'C',1);
        $pdf->SetTextColor(0, 0, 0);

        $set_y = 52; 
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,"Nombre: ",0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(65,6,$nombre,0,0,'');
        $pdf->SetXY($set_x+95, $set_y);
        $pdf->Cell(25,6,"Apellido: ",0,0,'');
        $pdf->SetXY($set_x+120, $set_y);
        $pdf->Cell(65,6,$apellido,0,0,'');

        $set_y = 58;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,"Edad: ",0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(65,6,$edad,0,0,'');
        $pdf->SetXY($set_x+95, $set_y);
        $pdf->Cell(25,6,utf8_decode("Género: "),0,0,'');
        $pdf->SetXY($set_x+120, $set_y);
        $pdf->Cell(65,6,$sexo,0,0,'');

        $set_y = 64;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,utf8_decode("Dirección: "),0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(65,6,$direccion,0,0,'');
        $pdf->SetXY($set_x+95, $set_y);
        $pdf->Cell(25,6,utf8_decode("Teléfono: "),0,0,'');
        $pdf->SetXY($set_x+120, $set_y);
        $pdf->Cell(65,6,$telefono1,0,0,'');

        $set_y = 70;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,"Padecimientos: ",0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(65,6,$padecimientos,0,0,'');
        $pdf->SetXY($set_x+95, $set_y);
        $pdf->Cell(25,6,"Alergias: ",0,0,'');
        $pdf->SetXY($set_x+120, $set_y);
        $pdf->Cell(65,6,$alergias,0,0,'');

        $set_y = 76;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(45,6,"Medicamentos permanentes: ",0,0,'');
        $pdf->SetXY($set_x+50, $set_y);
        $pdf->Cell(135,6,$medicamentos,0,0,'');
        $primera = 0;
    }
    if($num2 >0)
    {
        //Header Datos
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y); 
        $set_y += 14;
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,utf8_decode("EVALUACIÓN PRELIMINAR"),1,0,'C',1);
        $pdf->SetTextColor(0, 0, 0);

        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);  
        $set_y += 6;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,"Estatura: ",0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(65,6,$estatura." mt",0,0,'');
        $pdf->SetXY($set_x+95, $set_y);
        $pdf->Cell(25,6,"Peso: ",0,0,'');
        $pdf->SetXY($set_x+120, $set_y);
        $pdf->Cell(65,6,$peso." lb",0,0,'');

        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 6;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,"Temperatura: ",0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(65,6,$temperatura.utf8_decode(" °C"),0,0,'');
        $pdf->SetXY($set_x+95, $set_y);
        $pdf->Cell(25,6,utf8_decode("Presión: "),0,0,'');
        $pdf->SetXY($set_x+120, $set_y);
        $pdf->Cell(65,6,$presion,0,0,'');

        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 6;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,"Fecha: ",0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(65,6,$fecha_ev,0,0,'');
        $pdf->SetXY($set_x+95, $set_y);
        $pdf->Cell(25,6,"Hora: ",0,0,'');
        $pdf->SetXY($set_x+120, $set_y);
        $pdf->Cell(65,6,$hora_ev,0,0,'');

        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 6;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,"Observaciones: ",0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(155,6,$observaciones,0,0,'');
    }
    if($motivo !="")
    {
        //Header Datos
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 14;   
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,"ASUNTO/MOTIVO/OBSERVACIONES",1,0,'C',1);
        $pdf->SetTextColor(0, 0, 0);

        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 6;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(180,6,$motivo,0,0,'');    
    }
    if($num_diagnostico>0)
    {
        //Header Datos
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 14;   
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,utf8_decode("DIAGNÓSTICO (Según Estándar CIE-10-ES)"),1,0,'C',1);
        $pdf->SetTextColor(0, 0, 0);


        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 6;    
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->SetFont('Times','B',8);
        $pdf->Cell(15,6,utf8_decode("N°"),1,0,'C');
        $pdf->SetXY($set_x+20, $set_y);
        $pdf->Cell(165,6,utf8_decode("Descripción"),1,0,'C');
        $pdf->SetFont('Times','',9);
        $i = 1;
        while($row = _fetch_array($query_diagnostico))
        {
            $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
            $set_y += 6;    
            $pdf->SetXY($set_x+5, $set_y);
            $pdf->Cell(15,6,$i,1,0,'C');
            $pdf->SetXY($set_x+20, $set_y);
            $pdf->Cell(165,6,utf8_decode($row["descripcion"]),1,0,'');
            $i++;
        }
    }
    if($diagnostico !="")
    {
        //Header Datos
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y); 
        $set_y += 14;
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,utf8_decode("OTRO DIAGNÓSTICO"),1,0,'C',1);
        $pdf->SetTextColor(0, 0, 0);

        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);  
        $set_y += 6;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(180,6,$diagnostico,0,0,'');  
    }
    if($num_receta>0)
    {
        //Header Datos
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 14;   
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,utf8_decode("RECETA"),1,0,'C',1);
        $pdf->SetTextColor(0, 0, 0);


        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y); 
        $set_y += 6;   
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->SetFont('Times','B',8);
        $pdf->Cell(5,6,utf8_decode("N°"),1,0,'C');
        $pdf->SetXY($set_x+10, $set_y);
        $pdf->Cell(90,6,utf8_decode("Descripción"),1,0,'C');
        $pdf->SetXY($set_x+100, $set_y);
        $pdf->Cell(20,6,utf8_decode("Cantidad"),1,0,'C');
        $pdf->SetXY($set_x+120, $set_y);
        $pdf->Cell(65,6,utf8_decode("Dosis"),1,0,'C');
        $pdf->SetFont('Times','',9);
        $i = 1;
        while($row = _fetch_array($query_receta))
        {
            $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
            $set_y += 6;  
            $pdf->SetXY($set_x+5, $set_y);
            $pdf->Cell(5,6,$i,1,0,'C');
            $pdf->SetXY($set_x+10, $set_y);
            $pdf->Cell(90,6,utf8_decode($row["descripcion"]),1,0,'C');
            $pdf->SetXY($set_x+100, $set_y);
            $pdf->Cell(20,6,utf8_decode($row["cantidad"]),1,0,'C');
            $pdf->SetXY($set_x+120, $set_y);
            $pdf->Cell(65,6,utf8_decode($row["dosis"]),1,0,'C');
            $i++;
        }
    }
    if($medicamento !="")
    {
        //Header Datos
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 14;   
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,utf8_decode("OTRO MEDICAMENTO"),1,0,'C',1);
        $pdf->SetTextColor(0, 0, 0);

        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 6;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(180,6,$medicamento,0,0,'');  
    }
    if($num_examen>0)
    {
        //Header Datos
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 14; 
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,utf8_decode("EXÁMENES"),1,0,'C',1);
        $pdf->SetTextColor(0, 0, 0);

        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y); 
        $set_y += 6;   
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->SetFont('Times','B',8);
        $pdf->Cell(15,6,utf8_decode("N°"),1,0,'C');
        $pdf->SetXY($set_x+20, $set_y);
        $pdf->Cell(165,6,utf8_decode("Descripción"),1,0,'C');
        $pdf->SetFont('Times','',9);
        $i = 1;
        while($row = _fetch_array($query_examen))
        {
            $set_y = hay_salto($pdf->GetY(), $pdf, $set_y); 
            $set_y += 6;   
            $pdf->SetXY($set_x+5, $set_y);
            $pdf->Cell(15,6,$i,1,0,'C');
            $pdf->SetXY($set_x+20, $set_y);
            $pdf->Cell(165,6,utf8_decode($row["descripcion"]),1,0,'C');
            $i++;
        }
    }
    if($examen !="")
    {
        //Header Datos
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 14;   
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,utf8_decode("OTROS EXÁMENES"),1,1,'C',1);
        $pdf->SetTextColor(0, 0, 0);

        $set_y += 6;
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(180,6,$examen,0,0,'');  
    }
    if($num_referencia >0)
    {
        //Header Datos
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 14;   
        $pdf->SetXY($set_x, $set_y);
        $pdf->SetFillColor(34, 34, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(190,4,utf8_decode("REFERENCIAS"),1,0,'C',1);
        $pdf->SetTextColor(0, 0, 0);

        $row = _fetch_array($query_referencia);
        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 6;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,"Destino: ",0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(65,6,$row["destino"],0,0,'');
        $pdf->SetXY($set_x+95, $set_y);
        $pdf->Cell(25,6,"Motivo: ",0,0,'');
        $pdf->SetXY($set_x+120, $set_y);
        $pdf->Cell(65,6,$row["motivo"],0,0,'');

        $set_y = hay_salto($pdf->GetY(), $pdf, $set_y);
        $set_y += 6;
        $pdf->SetXY($set_x+5, $set_y);
        $pdf->Cell(25,6,"Observaciones: ",0,0,'');
        $pdf->SetXY($set_x+30, $set_y);
        $pdf->Cell(155,6,$row["observaciones"],0,0,''); 
    }
}
$pdf->Output("Expediente_".$n_exp.".pdf","I");
?>