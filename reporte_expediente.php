<style type='text/css'>
table.page_header {
    width: 100%;
    margin-left: 53px;
    margin-top: 25px;
    margin-bottom: 25px;
    border: none;
    background-color: #FFFFFF;
    font-family: times, serif;
    font-weight: bold;
    font-size: 14px;
}

table.page_footer {
    width: 100%;
    border: none;
    background-color: #FFF;
    padding: 2mm;
    color: #FFFFFF;
    font-family: times, serif;
    font-weight: bold;
}

div.note {
    border: solid 1mm #DDDDDD;
    background-color: #EEEEEE;
    padding: 2mm;
    border-radius: 2mm;
    width: 100%;
}

ul.main {
    width: 95%;
    list-style-type: square;
}

ul.main li {
    padding-bottom: 2mm;
}

h1 {
    text-align: center;
    font-size: 20mm
}

h3 {
    text-align: right;
    font-size: 14px;
    color: #000080
}

table {
    vertical-align: middle;
}

tr {
    vertical-align: middle;
}

p {
    margin: 0px 5px 0px 5px;
}

span {
    margin: 5px;
}

img {
    border: 1px #000000;
}
</style>
<?php
include_once("_core.php");
$sql_empresa = "SELECT e.*, d.nombre_departamento, m.nombre_municipio FROM empresa as e, departamento as d, municipio as m WHERE d.id_departamento=e.departamento AND m.id_municipio=e.municipio AND e.id_empresa='1'";

$resultado_emp=_query($sql_empresa);
$num_rows = _num_rows($resultado_emp);
$row_emp=_fetch_array($resultado_emp);

$empresa=utf8_decode($row_emp['nombre']);
$direccion_emp = Mayu(utf8_decode($row_emp["direccion"].", ".$row_emp["nombre_municipio"].", ".$row_emp["nombre_departamento"]));
$telefonos=$row_emp['telefono1'].'   '.$row_emp['telefono2'];
$logo=$row_emp['logo'];
$id_cita = $_REQUEST["id_cita"];

$query = _query("SELECT r.*, CONCAT(d.nombres,' ',d.apellidos) as doctor FROM reserva_cita as r, doctor as d WHERE d.id_doctor=r.id_doctor AND r.id ='$id_cita'");
$datos = _fetch_array($query);
$id_paciente = $datos["id_paciente"];
$doctor = $datos["doctor"];
$motivo = $datos["motivo_consulta"];
$diagnostico = $datos["diagnostico"];
$examen = $datos["examen"];
$fecha_cita = nombre_dia($datos["fecha_cita"]);
$hora_cita = hora($datos["hora_cita"]);
$medicamento = $datos["medicamento"];
$t_o = $datos["t_o"];
$ta = $datos["ta"];
$p = $datos["p"];
$peso = $datos["peso"];
$fr = $datos["fr"];

$query_diagnostico = _query("SELECT d.descripcion, dp.id_diagnostico FROM diagnostico_paciente as dp, diagnostico as d WHERE d.id_diagnostico=dp.id_diagnostico AND dp.id_cita='$id_cita' AND dp.id_paciente='$id_paciente'");
$num_diagnostico = _num_rows($query_diagnostico);

$query_examen = _query("SELECT e.descripcion, ep.id_examen_paciente, ep.id_examen, ep.fecha_lectura FROM examen_paciente as ep, examen as e WHERE e.id_examen=ep.id_examen AND ep.id_cita='$id_cita' AND ep.id_paciente='$id_paciente'");
$num_examen = _num_rows($query_examen);

$query_receta = _query("SELECT m.* , r.id_medicamento,r.dosis FROM receta as r, medicamento as m WHERE m.id_medicamento=r.id_medicamento AND r.id_cita='$id_cita' AND r.id_paciente='$id_paciente'");
$num_receta = _num_rows($query_receta);

$query_referencia = _query("SELECT * FROM referencia WHERE id_cita='$id_cita' AND id_paciente='$id_paciente'");
$num_referencia = _num_rows($query_referencia);

$sql="SELECT p.*, d.nombre_departamento, m.nombre_municipio FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id_paciente'";
$result = _query($sql);
$numm = _num_rows($result);
$row = _fetch_array($result);
$nombre=$row['nombres'];
$apellido = $row['apellidos'];
$telefono1=$row["tel1"];
$telefono2=$row["tel2"];
if($telefono2 !="")
{
  $telefono1 .= ", ".$row["tel2"];
}
$expediente = $row["expediente"];
$len = strlen((string)$expediente);
$fill = 7 - $len;
if($fill <0)
$fill = 0;
$n_exp = zfill($expediente, $fill);
$email=$row["email"];
$sexo = $row["sexo"];
$fecha = ED($row["fecha_nacimiento"]);
$datos_fecha = explode("-", $fecha);
$anio_nac  = $datos_fecha[2];
$edad = date("Y") - $anio_nac;
$direccion = $row["direccion"].", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
$padecimientos = $row["padecimientos"];
$medicamentos = $row["medicamento_permanente"];
$alergias = $row["alergias"];

?>
<page backtop="40mm" backbottom="20mm" backleft="15mm" backright="15mm" style="font-size: 10pt" backimgx="center"
    backimgy="bottom" backimgw="100%">
    <page_header>
        <table class="page_header">
            <tr rowspan="3">
                <td style="width: 12%; color: #444444;">
                    <?php
          //  echo "<img style='width: 12%;' src='./".$logo."'>";
          ?>
                </td>
            </tr>
            <tr>
                <td align=center style='width:90%;'> <?php echo  strtoupper(utf8_encode($empresa)); ?></td>
            </tr>
            <tr>
                <td align=center style='width:90%;'> <?php echo strtoupper(utf8_encode($direccion_emp)); ?></td>
            </tr>
            <tr>
                <td align=center style='width:90%;'> <?php echo "TEL.  ".$telefonos; ?></td>
            </tr>
            <tr>
                <td align=center style='width:90%;'><?php echo 'Dr. '.$doctor; ?></td>
            </tr>
            <tr>
                <td align=center style='width:90%;'><?php echo 'Consulta del '.$fecha_cita.' a las '.$hora_cita; ?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 80%; text-align: left; color:#000;">
                    Impreso: <?php echo date('d-m-Y')." ".hora(date("H:i:s")) ;?>
                </td>
                <!--<td style="width: 35%; text-align: left; color:#000;">
        <?php echo "Expediente ".$n_exp; ?>
      </td>-->
                <td style="width: 20%; text-align: right; color:#000;">
                    <?php echo "Expediente ".$n_exp; ?>
                </td>
            </tr>
        </table>
    </page_footer>
    <?php
echo "<table align='center' cellspacing='0' style='width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;'>
<tr>
<td style='background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;'>DATOS GENERALES</td>
</tr>
</table>
<table align='center' cellspacing='12' style='width: 98%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;'>
<tr>
<td style='width: 12%;'>Nombres:</td>
<td style='width: 38%;'>".$nombre."</td>
<td style='width: 12%;'>Apellidos:</td>
<td style='width: 38%;'>".$apellido."</td>
</tr>
<tr>
<td>Edad:</td>
<td>".$edad."</td>
<td>Género:</td>
<td>".$sexo."</td>
</tr>
<tr>
<td>Dirección:</td>
<td>".$direccion."</td>
<td>Télefono:</td>
<td>".$telefono1."</td>
</tr>
<tr>
<td>Padecimientos:</td>
<td>".$padecimientos."</td>
<td>Alergias:</td>
<td>".$alergias."</td>
</tr>
<tr>
<td colspan='2'>Medicamentos Permánetes:</td>
<td colspan='2'>".$medicamentos."</td>
</tr>
</table>";
if($motivo != "")
{
  echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">ASUNTO/MOTIVO/OBSERVACIONES</td>
  </tr>
  </table>
  <table align="center" cellspacing="0" style="width: 98%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="width:100%;">'.$motivo.'</td>
  </tr>
  </table>';
}
echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
<tr>
<td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">EVALUACION FISICA</td>
</tr>
</table>
<table border="0.3px" align="center" cellspacing="0"  style="width: 98%; border:none; text-align: center; font-size: 9pt; color:#000; font-family:times,serif;">
<tr>
<th style="width:20%;">Ta</th>
<th style="width:20%;">P</th>
<th style="width:20%;">T°</th>
<th style="width:20%;">Peso</th>
<th style="width:20%;">FR</th>
</tr>
<tr>
<td>'.$ta.'</td>
<td>'.$p.'</td>
<td>'.$t_o.'</td>
<td>'.$peso.'</td>
<td>'.$fr.'</td>
</tr>
</table>';
if($num_receta>0)
{
  echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">RECETA</td>
  </tr>
  </table>
  <table border="0.3px" align="center" cellspacing="0"  style="width: 98%; border:none; text-align: center; font-size: 9pt; color:#000; font-family:times,serif;">
  <tr>
  <th style="width:5%;">N°</th>
  <th style="width:65%;">Descripción</th>
  <th style="width:30%;">Dosis</th>
  </tr>';
  /*

  <th style="width:25%;">Presentación</th>
  <th style="width:10%;">Cantidad</th>
  */
  $i = 1;
  while ($row = _fetch_array($query_receta))
  {
    echo "<tr>
    <td style='width:5%;'>".$i."</td>
    <td style='width:65%;'>".$row["descripcion"]."</td>
    <td style='width:30%;'>".$row["dosis"]."</td>
    </tr>";

    /*<td style='width:25%;'>".$row["presentacion"]."</td>
    <td style='width:10%;'>".$row["cantidad"]."</td>*/
    $i++;
  }
  echo'</table>';
}
if($medicamento !="")
{
  echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">OTRO MEDICAMENTO</td>
  </tr>
  </table>
  <table align="center" cellspacing="0" style="width: 98%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="width:100%;">'.$medicamento.'</td>
  </tr>
  </table>';
}
if($num_diagnostico>0)
{
  echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">DIAGNÓSTICO (Según estándar CIE-10-ES)</td>
  </tr>
  </table>
  <table border="0.3px" align="center" cellspacing="0"  style="width: 98%; border:none; text-align: center; font-size: 9pt; color:#000; font-family:times,serif;">
  <tr>
  <th style="width:5%;">N°</th>
  <th style="width:95%;">Descripción</th>
  </tr>';
  $i = 1;
  while ($row = _fetch_array($query_diagnostico))
  {
    echo "<tr>
    <td style='width:5%;'>".$i."</td>
    <td style='width:95%;'>".$row["descripcion"]."</td>
    </tr>";
    $i++;
  }
  echo'</table>';
}
if($diagnostico !="")
{
  echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">OTRO DIAGNÓSTICO</td>
  </tr>
  </table>
  <table align="center" cellspacing="0" style="width: 98%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="width:100%;">'.$diagnostico.'</td>
  </tr>
  </table>';
}
$sql2= _query("SELECT * FROM signos_vitales WHERE id_paciente ='$id_paciente' AND id_cita='$id_cita' ORDER BY id_signo DESC LIMIT 1");
$num2 = _num_rows($sql2);
$datos =_fetch_array($sql2);
$estatura = $datos["estatura"];
$peso = $datos["peso"];
$temperatura = $datos["temperatura"];
$presion = $datos["presion"];
$frecuencia_c = $datos["frecuencia_cardiaca"];
$frecuencia_r = $datos["frecuencia_respiratoria"];
$observaciones = $datos['observaciones'];
$fecha_ev = ED($datos['fecha']);
$hora_ev = hora($datos['hora']);
if($num2>0)
{
  /* echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">EVALUACION PRELIMINAR</td>
  </tr>
  </table>
  <table align="center" cellspacing="12" style="width: 98%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="width:25%;">Estatura:</td>
  <td style="width:25%;">'.$estatura.' mt</td>
  <td style="width:25%;">Peso:</td>
  <td style="width:25%;">'.$peso.' lb</td>
  </tr>
  <tr>
  <td>Temperatura:</td>
  <td>'.$temperatura.' °C</td>
  <td>Presión:</td>
  <td>'.$presion.'</td>
  </tr>

  <tr>
  <td>Fecha:</td>
  <td>'.$fecha_ev.'</td>
  <td>Hora:</td>
  <td>'.$hora_ev.'</td>
  </tr>
  <tr>
  <td colspan="2">Observaciones:</td>
  <td colspan="2">'.$observaciones.'</td>
  </tr>
  </table>';*/
}
if($num_examen>0)
{
  echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
  <tr>
  <td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">EXÁMENES</td>
  </tr>
  </table>';
  /*echo '<table border="0.3px" align="center" cellspacing="0"  style="width: 98%; border:none; text-align: center; font-size: 9pt; color:#000; font-family:times,serif;">
  <tr>
  <th style="width:5%;">N°</th>
  <th style="width:15%;">Descripción</th>
  <th style="width:81%;">Resultados</th>
  </tr>';*/
  $i = 1;
  while ($row = _fetch_array($query_examen))
  {
      //echo'<table border="0.3px" align="center" cellspacing="0"  style="width: 110%; border:none; text-align: center; font-size: 9pt; color:#000; font-family:times,serif;">';
      $id_examen=$row["id_examen"];
      $id_examen_paciente=$row["id_examen_paciente"];
    ?>
    <table style="width:80%;">
        <?php
        switch ($id_examen)
        {
          case '1':
          $query1 = _query("SELECT e_h.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_heces AS e_h, examen_paciente AS e_p WHERE e_h.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");

          $datos1 = _fetch_array($query1);
          $color = $datos1["color"];
          $consistencia = $datos1["consistencia"];
          $mucus = $datos1["mucus"];
          $restos_alimenticios = $datos1["restos_alimenticios"];
          $leucocitos = $datos1["leucocitos"];
          $hematies = $datos1["hematies"];
          $protozoarios = $datos1["protozoarios"];
          $metazoarios = $datos1["metazoarios"];
          $flora = $datos1["flora"];
          $otros = $datos1["otros"];
          $fecha_lectura = ED($datos1["fecha_lectura"]);
          ?>
        <tr>
            <th colspan="2"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th style="width:40%;">
                <label>Datos</label>
            </th>
            <th style="width:60%;">
                Resultado
            </th>
        </tr>
        <tr>
            <td>
                <label>Color</label>
            </td>
            <td>
                <?php echo $color; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Consistencia</label>
            </td>
            <td><?php echo $consistencia; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Mucus</label>
            </td>
            <td><?php echo $mucus ;?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Restos alimeticos</label>
            </td>
            <td><?php echo $restos_alimenticios; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Leucocitos</label>
            </td>
            <td><?php echo $leucocitos; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Hematies</label>
            </td>
            <td><?php echo $hematies; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Protozoarios quistes</label>
            </td>
            <td><?php echo $protozoarios; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Metazoarios huevos</label>
            </td>
            <td><?php echo $metazoarios; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Flora Bacteriana</label>
            </td>
            <td><?php echo $flora; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Otros Hallazgos</label>
            </td>
            <td><?php echo $otros; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php

        break;

        case '2':
        $query2 = _query("SELECT e_o.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_orina AS e_o, examen_paciente AS e_p WHERE e_o.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
        $datos2 = _fetch_array($query2);
        $color = $datos2["color"];
        $aspecto = $datos2["aspecto"];
        $densidad = $datos2["densidad"];
        $ph = $datos2["ph"];
        $proteinas = $datos2["proteinas"];
        $glucosa = $datos2["glucosa"];
        $sangre_oculta = $datos2["sangre_oculta"];
        $cuerpos_cetonicos = $datos2["cuerpos_cetonicos"];
        $urobilinogeno = $datos2["urobilinogeno"];
        $bilirrubina = $datos2["bilirrubina"];
        $nitritos = $datos2["nitritos"];
        $hemoglobina = $datos2["hemoglobina"];
        $e_leucocitaria = $datos2["e_leucocitaria"];
        $celulas_epiteliales = $datos2["celulas_epiteliales"];
        $leucocitos = $datos2["leucocitos"];
        $hematies = $datos2["hematies"];
        $urato = $datos2["urato"];
        $cilindro_grueso = $datos2["cilindro_grueso"];
        $cilindro_leucocitario = $datos2["cilindro_leucocitario"];
        $cilindro_hematico = $datos2["cilindro_hematico"];
        $cilindro_hialino = $datos2["cilindro_hialino"];
        $parasitologico = $datos2["parasitologico"];
        $bacterias = $datos2["bacterias"];
        $filamento_mucoide = $datos2["filamento_mucoide"];
        $otros = $datos2["otros"];
        $observacion = $datos2["observacion"];
        $reporta = $datos2["reporta"];
        //$id_examen = $datos2["id_examen_orina"];
        $fecha_lectura = ED($datos2["fecha_lectura"]);
        ?>
        <tr>
            <th colspan="4"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th style="width:30%;">
                <label>Datos</label>
            </th>
            <th style="width:30%;">
                Resultado
            </th>
            <th style="width:30%;">
                <label>Datos</label>
            </th>
            <th style="width:30%;">
                Resultado
            </th>
        </tr>
        <tr>
            <td>
                <label>Color</label>
            </td>
            <td>
                <?php echo $color; ?>
            </td>
            <td>
                <label>Aspecto</label>
            </td>
            <td>
                <?php echo $aspecto; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Densidad</label>
            </td>
            <td>
                <?php echo $densidad ;?>
            </td>
            <td>
                <label>Ph</label>
            </td>
            <td>
                <?php echo $ph; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Proteinas</label>
            </td>
            <td>
                <?php echo $proteinas; ?>
            </td>
            <td>
                <label>Glucosa</label>
            </td>
            <td>
                <?php echo $glucosa; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Sangre oculta</label>
            </td>
            <td>
                <?php echo $sangre_oculta; ?>
            </td>
            <td>
                <label>Cuerpos cetonicos</label>
            </td>
            <td>
                <?php echo $cuerpos_cetonicos; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Urobilinogeno</label>
            </td>
            <td>
                <?php echo $urobilinogeno; ?>
            </td>
            <td>
                <label>Bilirrubina</label>
            </td>
            <td>
                <?php echo $bilirrubina; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Nitritos</label>
            </td>
            <td>
                <?php echo $nitritos; ?>
            </td>
            <td>
                <label>Hemoglobina</label>
            </td>
            <td>
                <?php echo $hemoglobina; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Esterasa leucocitoria</label>
            </td>
            <td>
                <?php echo $e_leucocitaria; ?>
            </td>
            <td>
                <label>Celulas epiteliales</label>
            </td>
            <td>
                <?php echo $celulas_epiteliales; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Leucocitos</label>
            </td>
            <td>
                <?php echo $leucocitos; ?>
            </td>
            <td>
                <label>Hematies</label>
            </td>
            <td>
                <?php echo $hematies; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Cristales urato amorfo</label>
            </td>
            <td>
                <?php echo $urato; ?>
            </td>
            <td>
                <label>Cilindros granuloso grueso</label>
            </td>
            <td>
                <?php echo $cilindro_grueso; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Cilindro leucocitario</label>
            </td>
            <td>
                <?php echo $cilindro_leucocitario; ?>
            </td>
            <td>
                <label>Cilindro hematico</label>
            </td>
            <td>
                <?php echo $cilindro_hematico; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Cilindro hialino</label>
            </td>
            <td>
                <?php echo $cilindro_hialino; ?>
            </td>
            <td>
                <label>Parasitologico</label>
            </td>
            <td>
                <?php echo $parasitologico; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Bacterias</label>
            </td>
            <td>
                <?php echo $bacterias; ?>
            </td>
            <td>
                <label>Filamento mucoide</label>
            </td>
            <td>
                <?php echo $filamento_mucoide; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Otros</label>
            </td>
            <td>
                <?php echo $otros; ?>
            </td>
            <td>
                <label>Reportados por</label>
            </td>
            <td>
                <?php echo $reporta; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Observaciones</label>
            </td>
            <td>
                <?php echo $observacion; ?>
            </td>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td>
                <?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '3':
    $query3 = _query("SELECT e_b.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_bacteriologia AS e_b, examen_paciente AS e_p WHERE e_b.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
          $datos3 = _fetch_array($query3);
          $muestra = $datos3["muestra"];
          $area_corporal = $datos3["area_corporal"];
          $microorganismo_aislado = $datos3["microorganismo_aislado"];
          $conteo_colonia = $datos3["conteo_colonia"];
          $sensible = explode("|",$datos3["sensible"]);
          $intermedio = explode("|",$datos3["intermedio"]);
          $resistente = explode("|",$datos3["resistente"]);
          //$id_examen = $datos3["id_bacteriologia"];
          $fecha_lectura = ED($datos3["fecha_lectura"]);
          ?>
        <tr>
            <th colspan="2"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th style="width:40%;">
                <label>Datos</label>
            </th>
            <th colspan="2" style="width:60%;">
                Resultado
            </th>
        </tr>
        <tr>
            <td>
                <label>Muestra</label>
            </td>
            <td colspan="2"><?php echo $muestra; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Area corporal</label>
            </td>
            <td colspan="2"><?php echo $area_corporal; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Microorganismo aislado</label>
            </td>
            <td colspan="2"><?php echo $microorganismo_aislado ;?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Conteo de colonia</label>
            </td>
            <td colspan="2"><?php echo $conteo_colonia; ?>
            </td>
        </tr>
        <tr>
            <td class="col-lg-4"><label>Sensible</label></td>
            <td class="col-lg-4"><label>Intermedio</label></td>
            <td class="col-lg-4"><label>Resistente</label></td>
        </tr>
        <?php
            for($i=0; $i<count($sensible); $i++)
            {
              echo "<tr>
                  <td>".$sensible[$i]."</td>
                  <td>".$intermedio[$i]."</td>
                  <td>".$resistente[$i]."</td>
                  </tr>";
            }
          ?>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td colspan="2"><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '4':
        $query4 = _query("SELECT e_c.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_creatinina AS e_c, examen_paciente AS e_p  WHERE e_c.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
        $datos4 = _fetch_array($query4);
        $volumen_orina = $datos4["volumen_orina"];
        $creatinina_orina = $datos4["creatinina_orina"];
        $creatinina_sangre = $datos4["creatinina_sangre"];
        $depuracion_creatinina = $datos4["depuracion_creatinina"];
        $proteinas_orina = $datos4["proteinas_orina"];
        //$id_examen = $datos4["id_examen_creatinina"];
        $fecha_lectura = ED($datos4["fecha_lectura"]);
        ?>
        <tr>
            <th colspan="3"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th style="width:50%">
                <label>Datos</label>
            </th>
            <th style="width:25%">
                Resultado
            </th>
            <th style="width:25%">
                Valor de Ref.
            </th>
        </tr>
        <tr>
            <td>
                <label>Volumen de orina</label>
            </td>
            <td><?php echo $volumen_orina; ?></td>
            <td>ml/24 horas
            </td>
        </tr>
        <tr>
            <td>
                <label>Creatinina en orina</label>
            </td>
            <td><?php echo $creatinina_orina; ?></td>
            <td>Mg/24 horas
            </td>
        </tr>
        <tr>
            <td>
                <label>Creatinina en sangre</label>
            </td>
            <td><?php echo $creatinina_sangre ;?></td>
            <td>0.4 - 1.4 mg/dl
            </td>
        </tr>
        <tr>
            <td>
                <label>Depuración de creatinina en orina</label>
            </td>
            <td><?php echo $depuracion_creatinina; ?></td>
            <td>50 - 157 ml/mto
            </td>
        </tr>
        <tr>
            <td>
                <label>Proteinas en orina</label>
            </td>
            <td><?php echo $proteinas_orina; ?></td>
            <td>10 - 150 mgrs/24 horas
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td colspan="2"><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '5':
    $query5 = _query("SELECT e_po.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_prostatico AS e_po, examen_paciente AS e_p WHERE e_po.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");

        $datos5 = _fetch_array($query5);
        $resultado = $datos5["resultado"];
        $fecha_lectura = ED($datos5["fecha_lectura"]);
        ?>
        <tr>
            <th colspan="3"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th style="width:50%;"><label>Datos</label></th>
            <th style="width:25%;">Resultado</th>
            <th style="width:25%;">Valor de Ref.</th>
        </tr>
        <tr>
            <td>
                <label></label>
            </td>
            <td><?php echo $resultado; ?></td>
            <td>Hasta 4.0 ng/ml
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td colspan="2"><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '6':
    $query6 = _query("SELECT e_f.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_febriles AS e_f, examen_paciente AS e_p WHERE e_f.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
        $datos6 = _fetch_array($query6);
        $tifico_h = $datos6["tifico_h"];
        $tifico_o = $datos6["tifico_o"];
        $paratifico_a = $datos6["paratifico_a"];
        $paratifico_b = $datos6["paratifico_b"];
        $proteus = $datos6["proteus"];
        $brocela_abortus = $datos6["brocela_abortus"];
        //$id_examen = $datos6["id_febriles"];
        $fecha_lectura = ED($datos6["fecha_lectura"]);
        ?>
        <tr>
            <th colspan="3"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th style="width:40%;"><label>Datos</label></th>
            <th style="width:60%;">Resultado</th>
        </tr>
        <tr>
            <td>
                <label>Tifico H</label>
            </td>
            <td><?php echo $tifico_h; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Tifico O</label>
            </td>
            <td><?php echo $tifico_o; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Paratifico A</label>
            </td>
            <td><?php echo $paratifico_a ;?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Paratifico B</label>
            </td>
            <td><?php echo $paratifico_b ;?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Proteus OX19</label>
            </td>
            <td><?php echo $proteus; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Brocela abortus</label>
            </td>
            <td><?php echo $brocela_abortus; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '7':
    $query7 = _query("SELECT e_h.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_hematologia AS e_h, examen_paciente AS e_p WHERE e_h.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
        $datos7 = _fetch_array($query7);
        $globulos_rojos = $datos7["globulos_rojos"];
        $hemoglobina = $datos7["hemoglobina"];
        $hematocrito = $datos7["hematocrito"];
        $vcm = $datos7["vcm"];
        $hcm = $datos7["hcm"];
        $chcm = $datos7["chcm"];
        $globulos_blancos = $datos7["globulos_blancos"];
        $n_segmentados = $datos7["n_segmentados"];
        $n_banda = $datos7["n_banda"];
        $linfocitos = $datos7["linfocitos"];
        $monocitos = $datos7["monocitos"];
        $eosinofilos = $datos7["eosinofilos"];
        $basofilos = $datos7["basofilos"];
        $plaquetas = $datos7["plaquetas"];
        $tiempo_protobina = $datos7["tiempo_protobina"];
        $inr = $datos7["inr"];
        $isi = $datos7["isi"];
        $tiempo_tromboplastima = $datos7["tiempo_tromboplastima"];
        $eritrosedimentacion = $datos7["eritrosedimentacion"];
        $observacion = $datos7["observacion"];
        $reporta = $datos7["reporta"];
        //$id_examen = $datos7["id_hematologia"];
        $fecha_lectura = ED($datos7["fecha_lectura"]);
        ?>
        <tr>
            <th colspan="3"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th style="width:40%"><label>Datos</label></th>
            <th style="width:20%">Resultado</th>
            <th style="width:40%">Valor de Ref.</th>
        </tr>
        <tr>
            <td>
                <label>Globulos rojos</label>
            </td>
            <td><?php echo $globulos_rojos; ?></td>
            <td>4,000.000 - 5,000.000 XMM³
            </td>
        </tr>
        <tr>
            <td>
                <label>Hemoglobina</label>
            </td>
            <td><?php echo $hemoglobina; ?></td>
            <td>Hombre 14-17, Mujer 12.5-15, Niños 11-13 GR/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Hematocrito</label>
            </td>
            <td><?php echo $hematocrito; ?></td>
            <td>Hombre 42-52, Mujer 38-42, Niños 33-38%
            </td>
        </tr>
        <tr>
            <td>
                <label>VCM</label>
            </td>
            <td><?php echo $vcm; ?></td>
            <td>80-100 Micras cúbicas
            </td>
        </tr>
        <tr>
            <td>
                <label>HCM</label>
            </td>
            <td><?php echo $hcm; ?></td>
            <td>27-34 Micro microgramos
            </td>
        </tr>
        <tr>
            <td>
                <label>CHCM</label>
            </td>
            <td><?php echo $chcm; ?></td>
            <td>30-34%
            </td>
        </tr>
        <tr>
            <td>
                <label>Globulos blancos</label>
            </td>
            <td><?php echo $globulos_blancos ;?></td>
            <td>Adultos 5,000-10,000, Niños 5,000-12,000 XMM³
            </td>
        </tr>
        <tr>
            <td>
                <label>Neutrófilos segmentados</label>
            </td>
            <td><?php echo $n_segmentados; ?></td>
            <td>Adultos 60-70, Niños 20-45%
            </td>
        </tr>
        <tr>
            <td>
                <label>Neutrófilos en banda</label>
            </td>
            <td><?php echo $n_banda; ?></td>
            <td>Adultos 2-5, Niños 20-45%
            </td>
        </tr>
        <tr>
            <td>
                <label>Linfocitos</label>
            </td>
            <td><?php echo $linfocitos; ?></td>
            <td>Adultos 15-40, Niños 40-60%
            </td>
        </tr>
        <tr>
            <td>
                <label>Monocitos</label>
            </td>
            <td><?php echo $monocitos; ?></td>
            <td>Adultos y niños 2-8%
            </td>
        </tr>
        <tr>
            <td>
                <label>Eosinófilos</label>
            </td>
            <td><?php echo $eosinofilos; ?></td>
            <td>Adultos 1-4, Niños 1-5%
            </td>
        </tr>
        <tr>
            <td>
                <label>Basófilos</label>
            </td>
            <td><?php echo $basofilos; ?></td>
            <td>Adultos y niños 0-1%
            </td>
        </tr>
        <tr>
            <td>
                <label>Plaquetas</label>
            </td>
            <td><?php echo $plaquetas; ?></td>
            <td>150,000 - 450,000 XMM³
            </td>
        </tr>
        <tr>
            <td>
                <label>Tiempo de protobina</label>
            </td>
            <td><?php echo $tiempo_protobina; ?></td>
            <td>8-14 Segundos
            </td>
        </tr>
        <tr>
            <td>
                <label>I.N.R</label>
            </td>
            <td><?php echo $inr; ?></td>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                <label>ISI</label>
            </td>
            <td><?php echo $isi; ?></td>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                <label>Tiempo de tromboplastina</label>
            </td>
            <td><?php echo $tiempo_tromboplastima; ?></td>
            <td>25-45 segundos, Hombres 0-7 MM/Hora
            </td>
        </tr>
        <tr>
            <td>
                <label>Eritrosedimentacion</label>
            </td>
            <td><?php echo $eritrosedimentacion; ?></td>
            <td>Mujeres 0-15 MM/Hora, Niños 0-20 MM/Hora
            </td>
        </tr>
        <tr>
            <td>
                <label>Reportados por</label>
            </td>
            <td colspan="2"><?php echo $reporta; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Observaciones</label>
            </td>
            <td colspan="2"><?php echo $observacion; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td colspan="2"><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '8':
    $query8 = _query("SELECT * FROM examen_quimica_sanguinea WHERE id_examen_paciente ='$id_examen_paciente'");
        $datos8 = _fetch_array($query8);
        $glucosa_azar = $datos8["glucosa_azar"];
        $glucosa_prandial = $datos8["glucosa_prandial"];
        $colesterol_total = $datos8["colesterol_total"];
        $colesterol_hdl = $datos8["colesterol_hdl"];
        $colesterol_ldl = $datos8["colesterol_ldl"];
        $trigliceridos = $datos8["trigliceridos"];
        $lipidos_totales = $datos8["lipidos_totales"];
        $creatinina = $datos8["creatinina"];
        $acido_urico = $datos8["acido_urico"];
        $urea = $datos8["urea"];
        $nitrogeno_ureico = $datos8["nitrogeno_ureico"];
        $sodio = $datos8["sodio"];
        $potasio = $datos8["potasio"];
        $cloro = $datos8["cloro"];
        $proteinas_totales = $datos8["proteinas_totales"];
        $albumina = $datos8["albumina"];
        $globulina = $datos8["globulina"];
        $relacion_ag = $datos8["relacion_ag"];
        $amilasa = $datos8["amilasa"];
        $bilirrubina_total = $datos8["bilirrubina_total"];
        $bilirrubina_directa = $datos8["bilirrubina_directa"];
        $bilirrubina_indirecta = $datos8["bilirrubina_indirecta"];
        $calcio = $datos8["calcio"];
        $fosforo = $datos8["fosforo"];
        $proteina_reactiva = $datos8["proteina_reactiva"];
        $tsh = $datos8["tsh"];
        $t3_libre = $datos8["t3_libre"];
        $t4_libre = $datos8["t4_libre"];
        $ldh = $datos8["ldh"];
        $hda1 = $datos8["hda1"];
        $fraccion = $datos8["fraccion"];
        $transaminasa_go = $datos8["transaminasa_go"];
        $transaminasa_gp = $datos8["transaminasa_gp"];
        $observacion = $datos8["observacion"];
        $reporta = $datos8["reporta"];
        //$id_examen = $datos8["id_sanguinea"];
        $fecha_lectura = ED($datos8["fecha"]);
        ?>
        <tr>
            <th colspan="3"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th style="width:50%;"><label>Datos</label></th>
            <th style="width:25%;">Resultado</th>
            <th style="width:25%;">Valor de Ref.</th>
        </tr>
        <tr>
            <td>
                <label>Glucosa al azar</label>
            </td>
            <td><?php echo $glucosa_azar; ?></td>
            <td>60-110 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Glucosa post-prandial </label>
            </td>
            <td><?php echo $glucosa_prandial; ?></td>
            <td>70-140 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Colesterol total</label>
            </td>
            <td><?php echo $colesterol_total; ?></td>
            <td>Hasta 200 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Colesterol HDL</label>
            </td>
            <td><?php echo $colesterol_hdl; ?></td>
            <td>45-60 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Colesterol LDL</label>
            </td>
            <td><?php echo $colesterol_ldl; ?></td>
            <td>Hasta 130 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Triglicéridos</label>
            </td>
            <td><?php echo $trigliceridos; ?></td>
            <td>Hasta 150 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Lípidos totales</label>
            </td>
            <td><?php echo $lipidos_totales ;?></td>
            <td>400-800 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Creatinina</label>
            </td>
            <td><?php echo $creatinina; ?></td>
            <td>Hombres 0.7-1.4 MG/DL, Mujeres 0.6-1.1 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Ácido úrico</label>
            </td>
            <td><?php echo $acido_urico; ?></td>
            <td>Hombres 3.6-7.7 MG/DL, Mujeres 2.5-6.8 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Urea</label>
            </td>
            <td><?php echo $urea; ?></td>
            <td>15-45 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Nitrógeno ureico</label>
            </td>
            <td><?php echo $nitrogeno_ureico; ?></td>
            <td>4.5-22.7 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Sodio</label>
            </td>
            <td><?php echo $sodio; ?></td>
            <td>135-148 MEQ/L
            </td>
        </tr>
        <tr>
            <td>
                <label>Potasio</label>
            </td>
            <td><?php echo $potasio; ?></td>
            <td>3.5-5.3 MEQ/L
            </td>
        </tr>
        <tr>
            <td>
                <label>Cloro</label>
            </td>
            <td><?php echo $cloro; ?></td>
            <td>98-107 MEQ/L
            </td>
        </tr>
        <tr>
            <td>
                <label>Proteínas totales</label>
            </td>
            <td><?php echo $proteinas_totales; ?></td>
            <td>6.6-8.3 G/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Albúmina</label>
            </td>
            <td><?php echo $albumina; ?></td>
            <td>3.8-5.1 G/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Globulina</label>
            </td>
            <td><?php echo $globulina; ?></td>
            <td>1.5-3 G/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Relación A/G</label>
            </td>
            <td><?php echo $relacion_ag; ?></td>
            <td>1.1-2.2
            </td>
        </tr>
        <tr>
            <td>
                <label>Amilasa</label>
            </td>
            <td><?php echo $amilasa; ?></td>
            <td>1-90 U/L
            </td>
        </tr>
        <tr>
            <td>
                <label>Bilirrubina total</label>
            </td>
            <td><?php echo $bilirrubina_total; ?></td>
            <td>0-1.1 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Bilirrubina directa</label>
            </td>
            <td><?php echo $bilirrubina_directa; ?></td>
            <td>0-0.25 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Bilirrubina indirecta</label>
            </td>
            <td><?php echo $bilirrubina_indirecta; ?></td>
            <td>0-0.50 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Calcio</label>
            </td>
            <td><?php echo $calcio; ?></td>
            <td>8.5-10.5 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Fósforo</label>
            </td>
            <td><?php echo $fosforo; ?></td>
            <td>2.5-5.0 MG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Proteina C reactiva</label>
            </td>
            <td><?php echo $proteina_reactiva; ?></td>
            <td>Hasta 12 MG/L
            </td>
        </tr>
        <tr>
            <td>
                <label>TSH</label>
            </td>
            <td><?php echo $tsh; ?></td>
            <td>0.38-4.31 Uiu/ML
            </td>
        </tr>
        <tr>
            <td>
                <label>T3 libre</label>
            </td>
            <td><?php echo $t3_libre; ?></td>
            <td>2.1-3.8 PG/ML
            </td>
        </tr>
        <tr>
            <td>
                <label>T4 libre</label>
            </td>
            <td><?php echo $t4_libre; ?></td>
            <td>0.82-1.63 NG/DL
            </td>
        </tr>
        <tr>
            <td>
                <label>Deshidrogenasa láctica (LDH)</label>
            </td>
            <td><?php echo $ldh; ?></td>
            <td>230-460 U/L
            </td>
        </tr>
        <tr>
            <td>
                <label>Hemoglobina glicosilada HDA1</label>
            </td>
            <td><?php echo $hda1; ?></td>
            <td>5-8%
            </td>
        </tr>
        <tr>
            <td>
                <label>Fracción HDA,C</label>
            </td>
            <td><?php echo $fraccion; ?></td>
            <td>4.2-6.2%
            </td>
        </tr>
        <tr>
            <td>
                <label>Transaminasa G.O</label>
            </td>
            <td><?php echo $transaminasa_go; ?></td>
            <td>Mujer 3.1 U/L, Hombre
            </td>
        </tr>
        <tr>
            <td>
                <label>Transaminasa G.P</label>
            </td>
            <td><?php echo $transaminasa_gp; ?></td>
            <td>Mujer 3.2 U/L, Hombre
            </td>
        </tr>
        <tr>
            <td>
                <label>Reportados por</label>
            </td>
            <td colspan="2"><?php echo $reporta; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Observaciones</label>
            </td>
            <td colspan="2"><?php echo $observacion; ?>
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td colspan="2"><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '9':
    $query9 = _query("SELECT e_v.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_varios AS e_v, examen_paciente AS e_p WHERE e_v.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");

        $datos9 = _fetch_array($query9);
        $muestra = $datos9["muestra"];
        $examen = $datos9["examen"];
        $resultado = $datos9["resultado"];
        //$id_examen = $datos9["id_examen_vario"];
        $fecha_lectura = ED($datos9["fecha_lectura"]);
        ?>
        <tr>
            <th colspan="2"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th style="width:40%;"><label>Examen Realizado</label></th>
            <th style="width:60%;"><?php echo $examen; ?></th>
        </tr>
        <tr>
            <td><label>Muestra</label></td>
            <td><?php echo $muestra; ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center"><label>Resultado</label></td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: justify;"><?php echo $resultado; ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '10':
    $query10 = _query("SELECT e_r.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_radiografia AS e_r, examen_paciente AS e_p WHERE e_r.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
        $datos10 = _fetch_array($query10);
        $resultado = $datos10["resultado"];
        //$id_examen = $datos10["id_radiografia"];
        $fecha_lectura = ED($datos10["fecha_lectura"]);
        ?>
        <tr>
            <th colspan="2"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th colspan="2" class="text-center"><label>Resultado</label></th>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: justify;"><?php echo $resultado; ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '11':
    $query11 = _query("SELECT e_r.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_ultrasonografia AS e_r, examen_paciente AS e_p WHERE e_r.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
        $datos11 = _fetch_array($query11);
        $resultado = $datos11["resultado"];
      //  $id_examen = $datos11["id_ultrasonografia"];
        $fecha_lectura = ED($datos11["fecha_lectura"]);
        ?>
        <tr>
            <th colspan="2"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th colspan="2" class="text-center"><label>Resultado</label></th>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: justify;"><?php echo $resultado; ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php
    break;
    case '12':
    $query12 = _query("SELECT e_r.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_tac AS e_r, examen_paciente AS e_p WHERE e_r.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
        $datos12 = _fetch_array($query12);
        $resultado = $datos12["resultado"];
      //  $id_examen = $datos12["id_tac"];
        $fecha_lectura = ED($datos12["fecha_lectura"]);
        ?>
        <tr>
            <th colspan="2"><?=$row["descripcion"];?>
            </th>
        </tr>
        <tr>
            <th colspan="2" class="text-center"><label>Resultado</label></th>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: justify;"><?php echo $resultado; ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <label>Fecha de lectura</label>
            </td>
            <td><?php echo $fecha_lectura; ?>
            </td>
        </tr>
        <?php

    break;
  }
  ?>
    </table>
    <br>
    <?php
      $i++;
    }
  }
  if($examen !="")
  {
    echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
    <tr>
    <td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">OTRO EXAMEN</td>
    </tr>
    </table>
    <table align="center" cellspacing="0" style="width: 98%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
    <tr>
    <td style="width:100%;">'.$examen.'</td>
    </tr>
    </table>';
  }
  if($num_referencia >0)
  {
    echo '<br><br><table align="center" cellspacing="0" style="width: 100%;  padding:3px; text-align: left; font-size:9pt; color:#000; font-family:times,serif;">
    <tr>
    <td style="background: #222; text-align: center; font-size:9pt; color:#FFFFFF; font-family:times,serif; width:100%;">REFERENCIAS</td>
    </tr>
    </table>
    <table align="center" border="0.3px" cellspacing="0" style="width: 98%;  text-align: left; font-size:9pt; color:#000; font-family:times,serif;">';
    $row = _fetch_array($query_referencia);
    echo "<tr>
    <td style='width:25%;'><b>Destino</b></td>
    <td style='width:75%;'>".$row["destino"]."</td>
    </tr>
    <tr>
    <td><b>Motivo</b></td>
    <td>".$row["motivo"]."</td>
    </tr>
    <tr>
    <td><b>Observaciones</b></td><td>".$row["observaciones"]."</td>
    </tr>";
    echo'</table>';
  }


  ?>
</page>