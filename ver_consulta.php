<?php
include_once "_core.php";
function initial(){
    // Page setup
    $title = "Ver Consulta";
    $_PAGE = array();
    $_PAGE['title'] = $title;
    $_PAGE['links'] = null;
    $_PAGE['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
    $_PAGE ['links'] .= '<link href="css/plugins/blueimp/css/blueimp-gallery.css" rel="stylesheet" type="text/css"/>';
    $_PAGE ['links'] .= '<link href="css/plugins/blueimp/css/blueimp-gallery-indicator.css" rel="stylesheet" type="text/css"/>';
    $_PAGE ['links'] .= '<link href="css/plugins/blueimp/css/blueimp-gallery-video.css" rel="stylesheet" type="text/css"/>';
    $_PAGE['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/style.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link rel="stylesheet" type="text/css" href="css/odontograma.css">';
    $_PAGE ['links'].=  '<link href="lightbox/css/lightbox.min.css", rel="stylesheet">';

    include_once "header.php";
    include_once "main_menu.php";
     //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

    $id = $_REQUEST["id_cita"];
    $query = _query("SELECT r.*,d.id_doctor, CONCAT(d.nombres,' ',d.apellidos) as doctor FROM reserva_cita as r, doctor as d WHERE d.id_doctor=r.id_doctor AND r.id ='$id'");
    //$sql_paiente="SELECT *"

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

    $fc=$datos['fc'];
    $dx=$datos['dx'];
    $plan=$datos['plan'];
    $altura=$datos['altura'];

    $id_doctor_receta = $datos['id_doctor'];

    $spo2_ant = $datos["saturacion"];
    $hemoglucotest_ant = $datos["hemoglucotest"];
    $saturacion_ant = $datos["saturacion"];
    $hallazgo_fisico_ant = $datos['hallazgo_fisico'];
    $historia_clinica_ant = $datos['historia_clinica'];
    $antecedente_personal_ant = $datos['antecedente_personal'];
    $antecedente_familiar_ant = $datos['antecedente_familiar'];
    $ingreso_hospitalario_ant = $datos['ingreso_hospitalario'];
    $indicacion_medica_ant = $datos['indicacion_medica'];
    $otros_cobros_ant = $datos['otros_cobros'];

    $examenes_ultra=$datos['examenes_ultra'];
    $dx_ultra=$datos['dx_ultra'];



    $query_diagnostico = _query("SELECT d.descripcion, dp.id_diagnostico FROM diagnostico_paciente as dp, diagnostico as d WHERE d.id_diagnostico=dp.id_diagnostico AND dp.id_cita='$id' AND dp.id_paciente='$id_paciente'");
    $num_diagnostico = _num_rows($query_diagnostico);

    $query_examen = _query("SELECT e.descripcion, ep.id_examen_paciente as id_examen, e.url, e.ver, ep.fecha_asignacion, ep.fecha_lectura FROM examen_paciente as ep, examen as e WHERE e.id_examen=ep.id_examen AND ep.id_cita='$id' AND ep.id_paciente='$id_paciente'");
    $num_examen = _num_rows($query_examen);

    $query_receta = _query("SELECT m.* , r.id_medicamento,r.dosis FROM receta as r, ".EXTERNAL.".producto as m WHERE m.id_producto=r.id_medicamento AND r.id_cita='$id' AND r.id_paciente='$id_paciente'");
    $num_receta = _num_rows($query_receta);

    $query_img = _query("SELECT * FROM img_paciente WHERE id_cita='$id' AND id_paciente='$id_paciente' AND url LIKE '%.jpg'
        UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id' AND id_paciente='$id_paciente' AND url LIKE '%.png'
        UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id' AND id_paciente='$id_paciente' AND url LIKE '%.bmp'
        UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id' AND id_paciente='$id_paciente' AND url LIKE '%.gif'");
    $num_img = _num_rows($query_img);

    $query_img2 = _query("SELECT * FROM img_paciente WHERE id_cita='$id' AND id_paciente='$id_paciente' AND url LIKE '%.pdf'
        UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id' AND id_paciente='$id_paciente' AND url LIKE '%.doc'
        UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id' AND id_paciente='$id_paciente' AND url LIKE '%.docx'
        UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id' AND id_paciente='$id_paciente' AND url LIKE '%.odt'");
    $num_img2 = _num_rows($query_img2);

    $query_referencia = _query("SELECT * FROM referencia WHERE id_cita='$id' AND id_paciente='$id_paciente'");
    $num_referencia = _num_rows($query_referencia);

    $query_constancias_ant_a = _query("SELECT * FROM constancia WHERE id_cita = '$id'");
    $numero_constancias_ant_a = _num_rows($query_constancias_ant_a);

    $sql="SELECT p.*, d.nombre_departamento, m.nombre_municipio FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id_paciente'";
    $result = _query($sql);
    $numm = _num_rows($result);
    $nombre_paciente="";
    if($numm>0)
    {
        $row = _fetch_array($result);
        $nombre=$row['nombres'];
        $apellido = $row['apellidos'];
        $nombre_paciente=$nombre.' '.$apellido;
        $telefono1=$row["tel1"];
        $telefono2=$row["tel2"];
        if($telefono2 !="")
        {
            $telefono1 .= ", ".$row["tel2"];
        }
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
        $dato ='<table class="table  table-checkable datatable">';
        $dato.='
        <tr>
            <td style="width: 12%;">Nombres:</td>
            <td style="width: 37%;">'.$nombre.'</td>
            <td style="width: 12%;">Apellidos:</td>
            <td style="width: 37%;">'.$apellido.'</td>
        </tr>
        <tr>
            <td>Edad:</td>
            <td>'.$edad.'</td>
            <td>Género:</td>
            <td>'.$sexo.'</td>
        </tr>
        <tr>
            <td>Dirección:</td>
            <td>'.$direccion.'</td>
            <td>Télefono:</td>
            <td>'.$telefono1.'</td>
        </tr>
        <tr>
            <td>Padecimientos:</td>
            <td>'.$padecimientos.'</td>
            <td>Alergias:</td>
            <td>'.$alergias.'</td>
        </tr>
        <tr>
            <td colspan="2">Medicamentos Permánetes:</td>
            <td colspan="2">'.$medicamentos.'</td>
        </tr>
        ';
        $dato.= '</table>';
    }
    $sql2= _query("SELECT * FROM signos_vitales WHERE id_paciente ='$id_paciente' AND id_cita='$id' ORDER BY id_signo DESC LIMIT 1");
    $num2 = _num_rows($sql2);
    
    $datoa = "";
    if($num2>0)
    {
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
        $datoa = "";
        $datoa.= '<table class="table  table-checkable datatable" id="signo">
        <tr>
            <td>Estatura:</td>
            <td>'.$estatura.' mt</td>
            <td>Peso:</td>
            <td>'.$peso.' lb</td>
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
        </table>';
    }
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
            <?php
            //permiso del script
            if ($links!='NOT' || $admin=='1' ){
            ?>
            <div class="ibox-title">
                <h3 style="color:#194160;"> Dr. <?php echo $doctor; ?> <b class="pull-right"><?php echo "Consulta del ".$fecha_cita." a las ".$hora_cita; ?></b></h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Datos del Paciente</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content" id="dato">
                                    <?php echo $dato; ?>
                                </div>
                            </div>
                        </div>
                    </div>
              	</div>
                <?php if($motivo != ""){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Asunto/ Motivo/ Observaciones</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <?php echo $motivo; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } 
                if($historia_clinica_ant != ""){
                    ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Historia Clinica</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <?php echo $historia_clinica_ant; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                if($antecedente_personal_ant != ""){
                    ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Antecedentes Personales</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <?php echo $antecedente_personal_ant; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                if($antecedente_familiar_ant != ""){
                    ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Antecedentes Familiares</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <?php echo $antecedente_familiar_ant; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                if($t_o!="" || $peso!="" || $ta!="" || $p !="" || $fr !=""){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Evaluación Física</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><b>Ta: </b></td>
                                            <td><b>P: </b></td>
                                            <td><b>T°: </b></td>
                                            <td><b>Peso: </b></td>
                                            <td><b>FR: </b></td>
                                            <td><b>SpO2: </b></td>
                                            <td><b>Hemoglucotest: </b></td>
                                            <td><b>Saturacion: </b></td>
                                            <td><b>FC:</b></td>
                                        </tr>
                                        <tr>

                                            <td><?php echo $ta;?></td>
                                            <td><?php echo $p;?></td>
                                            <td><?php echo $t_o;?></td>
                                            <td><?php echo $peso;?></td>
                                            <td><?php echo $fr;?></td>
                                            <td><?php echo $spo2_ant;?></td>
                                            <td><?php echo $hemoglucotest_ant;?>
                                            </td>
                                            <td><?php echo $saturacion_ant;?></td>
                                            <td><b><?php echo $fc; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><b>DX</b></td>
                                            <td colspan="5"><b>PLAN</b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><?php echo $dx ?></td>
                                            <td colspan="5"><?php echo $plan ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } 
                if($hallazgo_fisico_ant != ""){
                    ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Hallazgos Fisicos</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <?php echo $hallazgo_fisico_ant; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                if($num_receta>0){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Receta</h4>
                            </div>
                             <div class="panel-body">
                                <div class="widget-content">
                                    <div class="col-lg-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 60%;">Descripción</th>
                                                <th style="width: 40%;">Dosis</th>
                                            </tr>
                                        </thead>
                                        <tbody id="receta">
                                            <?php
                                                while($row = _fetch_array($query_receta))
                                                {
                                                    echo "<tr id='$row[id_medicamento]'>
                                                            <td>$row[descripcion]</td>
                                                            <td>$row[dosis]</td>
                                                           </tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                     <a class="pull-right" style="margin-top: 10px;" href="receta_pdf.php?<?php echo "id_cita=".$id."&id_doctor=".$id_doctor_receta;?>" target="_blank"><i class="fa fa-print fa-2x"></i> Imprimir</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if($examenes_ultra!='') {?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class='text-success'>Ultrasonografia</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="widget-content">
                                        <a href="<?php echo $examenes_ultra; ?>" data-lightbox="image-1" data-title="Examen de Ultrasonografia de <?php echo $nombre_paciente; ?>" class="pop">
                                            <img id="view_ultra" src="<?php echo $examenes_ultra ?>" style='width: 200px; height: 200px;'>                          
                                         </a>
                                    </div>                          
                                </div>
                            </div>
                        </div>
                    </div> 
                <?php } if($dx_ultra!='') {?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class='text-success'>Diagnostico de Ultrasonografia</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="widget-content">
                                        <?php echo $dx_ultra; ?>
                                    </div>                          
                                </div>
                            </div>
                        </div>
                    </div> 
                <?php } if($medicamento !=""){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Otro Medicamento</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <?php echo $medicamento; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php  } if($datoa !=""){ ?>
              	<!--<div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Evaluación Preliminar</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content" id="signo">
                                    <?php //echo $datoa; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
                <?php } if($num_diagnostico>0){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Diagnóstico (Según estándar CIE-10-ES)</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <div class="col-lg-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Descripción</th>

                                            </tr>
                                        </thead>
                                        <tbody id="diagnos_tt">
                                            <?php
                                                while($row = _fetch_array($query_diagnostico))
                                                {
                                                    echo "<tr id='$row[id_diagnostico]'>
                                                            <td>".$row["descripcion"]."</td>
                                                          </tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if($diagnostico !=""){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Otro Diagnóstico</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                   <?php echo $diagnostico; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if($num_examen>0){ ?>
              	<div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Exámenes</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <div class="col-lg-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="col-lg-2">Descripción</th>
                                                <th class="col-lg-2">Fecha Asignación</th>

                                            </tr>
                                        </thead>
                                        <tbody id="exam_tt">
                                            <?php
                                                while($row = _fetch_array($query_examen))
                                                {
                                                    echo "<tr id='".$row["id_examen"]."'>
                                                            <td>".$row["descripcion"]."</td>
                                                            <td>".ED($row["fecha_asignacion"])."</td>
                                                            </tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                     <!--<a class="pull-right" style="margin-top: 10px;" href="ver_examen_pdf.php?<?php echo "id_cita=".$id."&id_paciente=".$id_paciente;?>" target="_blank"><i class="fa fa-print fa-2x"></i> Imprimir</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if($examen !=""){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Otros Exámenes</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <?php echo $examen; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }
                if($numero_constancias_ant_a>0){ ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class='text-success'>Constancias Generadas</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="widget-content">
                                        <div class="col-lg-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Padecimiento</th>
                                                        <th>Doctor</th>
                                                        <th>Reposo</th>
                                                        <th>Fecha</th>
                                                        <th>Accion</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="diagnos_tt">
                                                    <?php
                                    while($row = _fetch_array($query_constancias_ant_a))
                                    {
                                            $id_doctor_consulta = $row['id_doctor'];
                                            $sql_doctor_constancia = "SELECT * FROM doctor WHERE id_doctor = '$id_doctor_consulta'";
                                            $query_doctor_constancia = _query($sql_doctor_constancia);
                                            $row_doctor_constancia = _fetch_array($query_doctor_constancia);
                                            $nombre_doctor = $row_doctor_constancia['nombres']." ".$row_doctor_constancia['apellidos'];
                                        echo "<tr id='$row[id_constancia]'>
                                                <td>".$row['padecimiento']."</td>
                                                <td>".$nombre_doctor."</td>
                                                <td>".$row['reposo']."</td>
                                                <td>".ED($row['fecha'])."</td>
                                                <td><a href='ver_constancia1.php?id=".$row["id_constancia"]."&id_constancia=".$row["id_constancia"]."' target='_blank'><i class='fa fa-print'></i></a> </td>                                                                                                

                                              </tr>";
                                    }
                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php }
                if($num_img >0 || $num_img2>0){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Archivos del paciente</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                <div id='links' class="col-lg-12">

                                    <?php
                                        while($row = _fetch_array($query_img))
                                        {

                                                echo "
                                                  <div class='col-lg-3' style='text-align:center;'>
                                                    <a href='".$row["url"]."' title='".$row["descripcion"]."' data-gallery='#blueimp-gallery'>
                                                        <img src='".$row["url"]."' title='".$row["descripcion"]."' style='width:100%; height:180px;'>
                                                    </a>
                                                    <h4>".$row["descripcion"]."</h4><p>".nombre_dia($row["fecha"])."</p>
                                                   </div>
                                                  ";
                                        }
                                    ?>
                                     </div>
                                </div>
                                <?php if($num_img2>0){?>
                                <div class="col-lg-6"><br>
                                    <h4 class="text-success">Archivos</h4>
                                    <table class="table table-bordered">
                                        <tr class="bg-success">
                                            <th class="col-lg-1">N°</th>
                                            <th class="col-lg-5">Nombre</th>
                                            <th class="col-lg-6">Fecha</th>
                                        </tr>
                                    <?php
                                        $kj = 1;
                                        while($row = _fetch_array($query_img2))
                                        {

                                                echo "
                                                  <tr>
                                                    <td>".$kj."</td>
                                                    <td><a href='".$row["url"]."' title='".$row["descripcion"]."' target='_blank'>".$row["descripcion"]."</a></td>
                                                    <td>".nombre_dia($row["fecha"])."</td>
                                                   </tr>
                                                  ";
                                                  $kj++;
                                        }
                                    ?>
                                    </table>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if($num_referencia >0){ ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class='text-success'>Referencia</h4>
                            </div>
                            <div class="panel-body">
                                <div class="widget-content">
                                    <?php
                                        while($row = _fetch_array($query_referencia))
                                        {
                                            echo '<table class="table  table-checkable datatable">
                                            <tr>
                                                <td>Destino:</td>
                                                <td>'.$row["destino"].'</td>
                                                <td>Motivo:</td>
                                                <td>'.$row["motivo"].'</td>
                                            </tr>
                                            <tr>
                                                <td>Observaciones:</td>
                                                <td colspan="3">'.$row["observaciones"].'</td>
                                            </tr>
                                            </table>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }?>
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" id="id_cita" value="<?php echo $id; ?>">
                        <a class="btn btn-primary pull-right" href="reporte_expediente1.php?id_cita=<?php echo $id; ?>"><i class="fa fa-print"></i> Imprimir</a>
                    </div>
              	</div>
            </div>
            </div>
        </div>
    </div>
    <!-- Modal Agregar Cita-->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-md">

        </div>
    </div>
    </div>
    <!-- Fin Modal Agregar Cita-->
</div>
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls col-lg-12" data-start-slideshow="true">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_ver_consulta.js'></script>";
?>
<script type="text/javascript">
    $('#blueimp-gallery').data('gallery');
</script>

<?php
} //permiso del script
else
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
}
if(!isset($_POST['process']))
{
    initial();
}
else
{
    if(isset($_POST['process']))
    {
        switch ($_POST['process'])
        {
            case 'finalizar':
                finalizar();
                break;
        }
    }
}
?>
