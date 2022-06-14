<?php
//Cambios
include_once "_core.php";
function initial(){
    // Page setup
    $title = "Consulta";
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
    $_PAGE['links'] .= '<link href="css/plugins/timepicki/timepicki.css" rel="stylesheet">';
    $_PAGE ['links'].=  '<link href="css/estilos.css", rel="stylesheet">';
    $_PAGE ['links'].=  '<link href="lightbox/css/lightbox.min.css", rel="stylesheet">';


    include_once "header.php";
    include_once "main_menu.php";
     //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

    $id = $_REQUEST["id"];
	$acc = $_REQUEST["acc"];
    $query = _query("SELECT fecha_cita, hora_cita, id_paciente, motivo_consulta, diagnostico, 
    examen, medicamento, t_o, ta, p, peso, fr, spo2, hemoglucotest, antecedente_personal,
     antecedente_familiar, ingreso_hospitalario, indicacion_medica, otros_cobros, hallazgo_fisico, 
     historia_clinica, saturacion, fc, dx, plan, hx, altura, examenes_ultra, dx_ultra FROM reserva_cita WHERE id ='$id'");
    $datos = _fetch_array($query);
    $fecha_cita = nombre_dia($datos['fecha_cita']);
    $hora_cita = hora($datos['hora_cita']);
    $id_paciente = $datos["id_paciente"];
    $motivo = $datos["motivo_consulta"];
    $diagnostico = $datos["diagnostico"];
    $examen = $datos["examen"];
    $medicamento = $datos["medicamento"];
    $ta = $datos["ta"];
    $p = $datos["p"];
    $peso = $datos["peso"];
    $t_o = $datos["t_o"];
    $fr = $datos["fr"];
    $spo2 = $datos["spo2"];
    $hemoglucotest = $datos["hemoglucotest"];
    $saturacion = $datos["saturacion"];
    $hallazgo_fisico = $datos['hallazgo_fisico'];
    $historia_clinica = $datos['historia_clinica'];
    $antecedente_personal = $datos['antecedente_personal'];
    $antecedente_familiar = $datos['antecedente_familiar'];
    $ingreso_hospitalario = $datos['ingreso_hospitalario'];
    $indicacion_medica = $datos['indicacion_medica'];
    $otros_cobros = $datos['otros_cobros'];
    $id_paciente_info = $datos['id_paciente'];
    $examen_ultra=$datos['examenes_ultra'];
    $dx_ultra=$datos['dx_ultra'];
    $fc=$datos['fc'];
    $dx=$datos['dx'];
    $plan=$datos['plan'];
    $hx1=$datos['hx'];

    $altura=$datos['altura'];

    $query_constancias = _query("SELECT * FROM constancia WHERE id_cita = '$id'");
    $numero_constancias = _num_rows($query_constancias);
    //echo "SELECT id, id_doctor FROM reserva_cita WHERE id_paciente='$id_paciente' AND id<'$id' AND estado='7' ORDER BY id DESC LIMIT 1";
    $query_exis = _query("SELECT id, id_doctor FROM reserva_cita WHERE id_paciente='$id_paciente' AND id<'$id' AND estado>=7 ORDER BY id DESC LIMIT 1");

    $n_exis_a = _num_rows($query_exis);
    if($n_exis_a > 0)
    {
        $id_cita_ant = _fetch_array($query_exis)["id"];
        $query_ant = _query("SELECT r.*,  CONCAT(d.nombres,' ',d.apellidos) as doctor 
                            FROM reserva_cita as r, doctor as d 
                            WHERE d.id_doctor=r.id_doctor AND r.id ='$id_cita_ant'");
        $datos_ant = _fetch_array($query_ant);
        $motivo_ant = $datos_ant["motivo_consulta"];
        $diagnostico_ant = $datos["diagnostico"];
        $examen_ant = $datos_ant["examen"];
        $fecha_cita_ant = nombre_dia($datos_ant["fecha_cita"]);
        $hora_cita_ant = hora($datos_ant["hora_cita"]);
        $medicamento_ant = $datos_ant["medicamento"];
        $t_o_ant = $datos_ant["t_o"];
        $ta_ant = $datos_ant["ta"];
        $p_ant = $datos_ant["p"];
        $peso_ant = $datos_ant["peso"];
        $fr_ant = $datos_ant["fr"];
        $fc_ant=$datos_ant["fc"];
        $dx_ant=$datos_ant["dx"];
        $plan_ant=$datos_ant["plan"];
        $hx_ant=$datos_ant["hx"];
        $altura_ant=$datos['altura'];

        $spo2_ant = $datos_ant["spo2"];
        $hemoglucotest_ant = $datos_ant["hemoglucotest"];
        $saturacion_ant = $datos_ant["saturacion"];
        $hallazgo_fisico_ant = $datos_ant['hallazgo_fisico'];
        $historia_clinica_ant = $datos_ant['historia_clinica'];
        $antecedente_personal_ant = $datos_ant['antecedente_personal'];
        $antecedente_familiar_ant = $datos_ant['antecedente_familiar'];
        $ingreso_hospitalario_ant = $datos_ant['ingreso_hospitalario'];
        $indicacion_medica_ant = $datos_ant['indicacion_medica'];
        $otros_cobros_ant = $datos_ant['otros_cobros'];
        $examenes_ultra_ant=$datos_ant['examenes_ultra'];
        $dx_ultra_ant=$datos_ant['dx_ultra'];


        $query_diagnostico_ant = _query("SELECT d.descripcion, dp.id_diagnostico FROM diagnostico_paciente as dp, diagnostico as d WHERE d.id_diagnostico=dp.id_diagnostico AND dp.id_cita='$id_cita_ant' AND dp.id_paciente='$id_paciente'");
        $num_diagnostico_ant = _num_rows($query_diagnostico_ant);

        $query_examen_ant = _query("SELECT e.descripcion, ep.id_examen_paciente as id_examen, e.url, e.ver, ep.fecha_asignacion, ep.fecha_lectura FROM examen_paciente as ep, examen as e WHERE e.id_examen=ep.id_examen AND ep.id_cita='$id_cita_ant' AND ep.id_paciente='$id_paciente'");
        $num_examen_ant = _num_rows($query_examen_ant);

        $query_receta_ant = _query("SELECT m.* , r.id_medicamento,r.dosis FROM receta as r, ".EXTERNAL.".producto as m WHERE m.id_producto=r.id_medicamento AND r.id_cita='$id_cita_ant' AND r.id_paciente='$id_paciente'");
        $num_receta_ant = _num_rows($query_receta_ant);

        $query_img_ant = _query("SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.jpg'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.png'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.bmp'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.gif'");
        $num_img_ant = _num_rows($query_img_ant);

        $query_img2_ant = _query("SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.pdf'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.doc'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.docx'
            UNION ALL SELECT * FROM img_paciente WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente' AND url LIKE '%.odt'");
        $num_img2_ant = _num_rows($query_img2_ant);

        $query_referencia_ant_a = _query("SELECT * FROM referencia WHERE id_cita='$id_cita_ant' AND id_paciente='$id_paciente'");
        $num_referencia_ant_a = _num_rows($query_referencia_ant_a);


        $sql2_ant_a= _query("SELECT * FROM signos_vitales WHERE id_paciente ='$id_paciente' AND id_cita='$id_cita_ant' ORDER BY id_signo DESC LIMIT 1");
        $num2_ant_a = _num_rows($sql2_ant_a);
        $query_constancias_ant_a = _query("SELECT * FROM constancia WHERE id_cita = '$id_cita_ant'");
        $numero_constancias_ant_a = _num_rows($query_constancias_ant_a);


        $datoa_ant_a = "";
        if($num2_ant_a>0)
        {
            $datos_ant_a =_fetch_array($sql2_ant_a);
            $estatura_ant_a = $datos_ant_a["estatura"];
            $peso_ant_a = $datos_ant_a["peso"];
            $temperatura_ant_a = $datos_ant_a["temperatura"];
            $presion_ant_a = $datos_ant_a["presion"];
            $frecuencia_c_ant_a = $datos_ant_a["frecuencia_cardiaca"];
            $frecuencia_r_ant_a = $datos_ant_a["frecuencia_respiratoria"];
            $observaciones_ant_a = $datos_ant_a['observaciones'];
            $fecha_ev_ant_a = ED($datos_ant_a['fecha']);
            $hora_ev_ant_a = hora($datos_ant_a['hora']);
            $datoa_ant_a = "";
            $datoa_ant_a.= '<table class="table  table-checkable datatable" id="signo">
            <tr>
                <td>Estatura:</td>
                <td>'.$estatura_ant_a.' mt</td>
                <td>Peso:</td>
                <td>'.$peso_ant_a.' lb</td>
            </tr>
            <tr>
                <td>Temperatura:</td>
                <td>'.$temperatura_ant_a.' °C</td>
                <td>Presión:</td>
                <td>'.$presion_ant_a.'</td>
            </tr>
            <tr>
                <td>Fecha:</td>
                <td>'.$fecha_ev_ant_a.'</td>
                <td>Hora:</td>
                <td>'.$hora_ev_ant_a.'</td>
            </tr>
            <tr>
                <td colspan="2">Observaciones:</td>
                <td colspan="2">'.$observaciones_ant_a.'</td>
            </tr>
            </table>';
        }
    }
    $query_diagnostico = _query("SELECT d.descripcion, dp.id_diagnostico FROM diagnostico_paciente as dp, diagnostico as d WHERE d.id_diagnostico=dp.id_diagnostico AND dp.id_cita='$id' AND dp.id_paciente='$id_paciente'");

    $query_examen = _query("SELECT e.descripcion, ep.id_examen FROM examen_paciente as ep, examen as e WHERE e.id_examen=ep.id_examen AND ep.id_cita='$id' AND ep.id_paciente='$id_paciente'");

    $query_receta = _query("SELECT m.* , r.id_medicamento FROM receta as r, ".EXTERNAL.".producto as m WHERE m.id_producto=r.id_medicamento AND r.id_cita='$id' AND r.id_paciente='$id_paciente'");

    $query_servicios_profesionales = _query("SELECT * FROM servicios_profesionales WHERE id_cita = '$id' AND id_paciente = '$id_paciente'");

    $query_futuras_consultas = _query("SELECT * FROM reserva_cita INNER JOIN espacio on espacio.id_espacio = reserva_cita.id_espacio WHERE id_paciente = '$id_paciente' AND id != ".$id." AND fecha_cita >= '".date("Y:m:d")."' ")
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <?php
            //permiso del script
            if ($links!='NOT' || $admin=='1' ){
                $sql_nombre_paciente = "SELECT * FROM paciente WHERE id_paciente = '$id_paciente_info'";
                $query_nombre_paciente = _query($sql_nombre_paciente);
                $row_paciente = _fetch_array($query_nombre_paciente);
                $nombre_paciente = $row_paciente['nombres'];
                $apellido_paciente = $row_paciente['apellidos'];

                $descripcion = $nombre_paciente." ".$apellido_paciente.", ".$fecha_cita.", ".$hora_cita;
            ?>
                <div class="ibox-title">
                    <h3 style="color:#194160;"><i class="fa fa-check"></i>
                        <b><?php echo $title." de ".$descripcion;?></b>
                    </h3>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-3">
                            <a data-toggle="modal" data-target="#photoModal" data-refresh="true"
                                href="foto_paciente.php?id_paciente=<?php echo $id_paciente."&id_cita=".$id; ?>"
                                style="margin-left: 15px;"><i title="Fotos de paciente"
                                    class="fa fa-camera fa-3x"></i></a>
                            <a data-toggle="modal" data-target="#viewModal" data-refresh="true"
                                href="referencia.php?id_paciente=<?php echo $id_paciente."&id_cita=".$id; ?>"
                            style="margin-left: 15px;"><i title="Referencia" class="fa fa-hospital-o fa-3x"></i></a>

                            <ul class="nav flex-column" id="pills-tab" role="tablist">

                                <li class="nav-item active">
                                    <a class="nav-link active" id="pills-consulta-tab" data-toggle="pill"
                                        href="#pills-consulta" role="tab" aria-controls="pills-consulta"
                                        aria-selected="true">Ultima Consulta</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="pills-datos-tab" data-toggle="pill" href="#pills-datos"
                                        role="tab" aria-controls="pills-datos" aria-selected="false">Datos del
                                        Paciente</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-historia-tab" data-toggle="pill"
                                        href="#pills-historia" role="tab" aria-controls="pills-historia"
                                        aria-selected="false">Asunto /
                                        Historia</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-antecedentes-tab" data-toggle="pill"
                                        href="#pills-antecedentes" role="tab" aria-controls="pills-antecedentes"
                                        aria-selected="false">Antecedentes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-asunto-tab" data-toggle="pill" href="#pills-asunto"
                                        role="tab" aria-controls="pills-asunto" aria-selected="false">Datos Fisicos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-diagnosticos-tab" data-toggle="pill"
                                        href="#pills-diagnosticos" role="tab" aria-controls="pills-diagnosticos"
                                        aria-selected="false">Diagnostico</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-receta-tab" data-toggle="pill" href="#pills-receta"
                                        role="tab" aria-controls="pills-receta" aria-selected="false">Receta</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-examen-tab" data-toggle="pill" href="#pills-examen"
                                        role="tab" aria-controls="pills-examen" aria-selected="false">Examenes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-constacia-tab" data-toggle="pill"
                                        href="#pills-constancia" role="tab" aria-controls="pills-constacia"
                                        aria-selected="false">Constancias</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-ultra-tab" data-toggle="pill"
                                        href="#pills-ultra" role="tab" aria-controls="pills-ultra"
                                        aria-selected="false">Examen de Ultrasonografia</a>
                                </li>
                                <!--<li class="nav-item">
                                    <a class="nav-link" id="pills-cobros-tab" data-toggle="pill" href="#pills-cobros"
                                        role="tab" aria-controls="pills-cobros" aria-selected="false">Cobros</a>
                                </li>-->
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-ingresos-tab" data-toggle="pill"
                                        href="#pills-ingresos" role="tab" aria-controls="pills-ingresos"
                                        aria-selected="false">Ingresos / Indicaciones</a>
                                </li>
                                <!--<li class="nav-item">
                                    <a class="nav-link" id="pills-consultas-tab" data-toggle="pill"
                                        href="#pills-consultas" role="tab" aria-controls="pills-consultas"
                                        aria-selected="false">Futuras Consultas</a>
                                </li>-->
                            </ul>
                        </div>
                        <div class="col-md-9" style="border:2px solid #194160; padding:10px">
                            <div class="tab-content" id="pills-tabContent">
                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DE LA ULTIMA
                                CONSULTA QUE RECIBIO EL PACIENTE EN EL LOCAL -->
                                <div class="tab-pane fade active in" id="pills-consulta" role="tabpanel"
                                    aria-labelledby="pills-consulta-tab">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class='text-success'><a data-toggle="collapse" href="#collapse00"
                                                    class="change" act="down">Última Consulta
                                                    <?php
                                                    if($n_exis_a > 0){
                                                        echo "($fecha_cita_ant a las $hora_cita_ant)";
                                                    }
                                                    else{
                                                        echo "No hay cita anterior";
                                                    }
                                                    ?><i
                                                        class="fa fa-angle-double-down pull-right"></i></a></h4>
                                        </div>
                                        <div id="collapse00" class="collapse panel-collapse in">
                                            <div class="panel-body">
                                                <div class="widget-content">
                                                    <?php
                                                    if($n_exis_a > 0){
                                                    ?>
                                                    <?php if($motivo_ant != ""){ ?>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class='text-success'>Motivo de consulta</h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="widget-content">
                                                                        <?php echo $motivo_ant; ?>
                                                                    </div>
                                                                    <div class="widget-content">
                                                                        <label>Hx</label>
                                                                        <?php echo $hx_ant; ?>
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
                                                    if($t_o_ant!="" || $peso_ant!="" || $ta_ant!="" || $p_ant !="" || $fr_ant !="" || $fc_ant!="" || $dx_ant!="" || $plan_ant !=""){ ?>
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
                                                                                <td class="ocultar"><b>SpO2: </b></td>
                                                                                <td><b>Hemoglucotest: </b></td>
                                                                                <td><b>Altura</b></td>
                                                                                <td><b>Saturacion: </b></td>
                                                                                <td><b>FC:</b></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><?php echo $t_o_ant;?></td>
                                                                                <td><?php echo $peso_ant;?></td>
                                                                                <td><?php echo $ta_ant;?></td>
                                                                                <td><?php echo $p_ant;?></td>
                                                                                <td><?php echo $fr_ant;?></td>
                                                                                
                                                                                <td class="ocultar"><?php echo $hemoglucotest_ant;?></td>
                                                                                <td><?php echo $altura_ant; ?></td>
                                                                                <td><?php echo $saturacion_ant;?></td>
                                                                                <td><?php echo $fc_ant; ?></td>

                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="5"><b>Dx:</b></td>
                                                                                <td colspan="4"><b>Plan:</b></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="5"><?php echo $dx_ant; ?></td>
                                                                                <td colspan="4"><?php echo $plan_ant; ?></td>
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
                                    if($num_receta_ant>0){ ?>
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
                                                                                        <th style="width: 60%;">
                                                                                            Descripción</th>
                                                                                        <th style="width: 40%;">Dosis
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="receta_table">
                                                                                    <?php
                                                                    while($row = _fetch_array($query_receta_ant))
                                                                    {
                                                                        echo "<tr id='$row[id_medicamento]'>
                                                                                <td>$row[descripcion]</td>
                                                                                <td>$row[dosis]</td>
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
                                                <?php }if($examenes_ultra_ant!=""){ ?>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class='text-success'>Ultrasonografia</h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="widget-content">
                                                                        <a href="<?php echo $examenes_ultra_ant ?>" data-lightbox="image-1" data-title="Examen de Ultrasonografia de <?php echo $nombre_paciente.' '.$apellido_paciente ?>" class="pop">
                                                                            <img id="view_ultra" src="<?php echo $examenes_ultra_ant ?>" style='width: 200px; height: 200px;'>                          
                                                                        </a>
                                                                    </div>
                          
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <?php} if($dx_ultra_ant!=''){  ?>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class='text-success'>Diagnostico de Ultrasonografia</h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="widget-content">
                                                                        <?php echo $dx_ultra_ant; ?>

                                                                    </div>
                          
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 

                                                    <?php } if($medicamento_ant !=""){ ?>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class='text-success'>Otro Medicamento</h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="widget-content">
                                                                        <?php echo $medicamento_ant; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php  } if($datoa_ant_a !=""){ ?>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class='text-success'>Evaluación Preliminar</h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="widget-content" id="signo">
                                                                        <?php echo $datoa_ant_a; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } if($num_diagnostico_ant>0){ ?>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class='text-success'>Diagnóstico (Según estándar
                                                                        CIE-10-ES)</h4>
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
                                                                    while($row = _fetch_array($query_diagnostico_ant))
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
                                                    <?php } if($diagnostico_ant !=""){ ?>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class='text-success'>Otro Diagnóstico</h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="widget-content">
                                                                        <?php echo $diagnostico_ant; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } if($num_examen_ant>0){ ?>
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
                                                                                        <th class="col-lg-2">Descripción
                                                                                        </th>
                                                                                        <th class="col-lg-2">Fecha
                                                                                            Asignación
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                    while($row = _fetch_array($query_examen_ant))
                                                                    {
                                                                        echo "<tr id='".$row["id_examen"]."'>
                                                                                <td>".$row["descripcion"]."</td>
                                                                                <td>".ED($row["fecha_asignacion"])."</td>
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
                                                    <?php } if($examen_ant !=""){ ?>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class='text-success'>Otros Exámenes</h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="widget-content">
                                                                        <?php echo $examen_ant; ?>
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
                                                                            echo "<tr id='$row[padecimiento]'>
                                                                                    <td>".$nombre_doctor."</td>
                                                                                    <td>".$row['reposo']."</td>
                                                                                    <td>".ED($row['fecha'])."</td>
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
                                                        <?php } if($num_img_ant >0 || $num_img2_ant>0){ ?>
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
                                                            while($row = _fetch_array($query_img_ant))
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
                                                                    <?php } if($num_img2_ant>0){?>
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
                                                            while($row = _fetch_array($query_img2_ant))
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
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php} if($num_referencia_ant_a >0){ ?>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading">
                                                                    <h4 class='text-success'>Referencia</h4>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="widget-content">
                                                                        <?php
                                                            while($row = _fetch_array($query_referencia_ant_a))
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
                                                    <?php
                                                    }
                                                    else{
                                                        echo "Es primera consulta";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <!--ACA TERMINA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DE LA ULTIMA
                                CONSULTA QUE RECIBIO EL PACIENTE EN EL LOCAL -->
                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER LOS DATOS
                                MAS RELEVANTES DEL PACIENTE -->
                                <div class="tab-pane fade" id="pills-datos" role="tabpanel"
                                    aria-labelledby="pills-datos-tab">
                                    <!--EMPIEZAN LOS DATOS GENERALES DEL PACIENTE -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class='text-success'><a data-toggle="collapse" href="#collapse1"
                                                    class="change" act="down">Datos Generales<i
                                                        class="fa fa-angle-double-down pull-right"></i></a></h4>
                                        </div>
                                        <div id="collapse1" class="collapse panel-collapse active in">
                                            <div class="panel-body">
                                                <div class="widget-content" id="dato">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--TERMINA LOS DATOS GENERALES DEL PACIENTE -->
                                </div>
                                <!--ACA TERMINA EL ESPACIO QUE SERVIRA PARA PODER VER LOS DATOS
                                MAS RELEVANTES DEL PACIENTE -->
                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA VER LOS DATOS FISICOS
                                MAS RELEVANTES DEL PACIENTE -->
                                <div class="tab-pane fade" id="pills-asunto" role="tabpanel"
                                    aria-labelledby="pills-asunto-tab">
                                    <!--ACA EMPIEZA EL MENU DE LAS OPCIONES DE FISICO -->
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="evaluacion-fisica-tab" data-toggle="tab"
                                                href="#evaluacion-fisica" role="tab" aria-controls="evaluacion-fisica"
                                                aria-selected="true">Evaluacion Fisica</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="hallazgos-fisicos-tab" data-toggle="tab"
                                                href="#hallazgos-fisicos" role="tab" aria-controls="hallazgos-fisicos"
                                                aria-selected="false">Hallazgos Fisicos</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade active in" id="evaluacion-fisica" role="tabpanel"
                                            aria-labelledby="evaluacion-fisica-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse22" class="change" act="down">Evaluación
                                                            Física<i class="fa fa-angle-double-down pull-right"></i></a>
                                                    </h4>
                                                </div>
                                                <div id="collapse22" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <div class=" form-group col-lg-6">
                                                                <label>TA</label>
                                                                <input type="text" name="ta" id="ta"
                                                                    class="form-control" value="<?php echo $ta;?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>P</label>
                                                                <input type="text" name="p" id="p" class="form-control"
                                                                    value="<?php echo $p;?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>T°</label>
                                                                <input type="text" name="to" id="to"
                                                                    class="form-control" value="<?php echo $t_o;?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>FC</label>
                                                                <input type="text" name="fc" id="fc"
                                                                    class="form-control" value="<?php echo $fc; ?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>Peso</label>
                                                                <input type="text" name="peso" id="peso"
                                                                    class="form-control" value="<?php echo $peso;?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>Altura</label>
                                                                <input type="text" name="altura" id="altura"
                                                                    class="form-control" value="<?php echo $altura;?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>FR</label>
                                                                <input type="text" name="fr" id="fr"
                                                                    class="form-control" value="<?php echo $fr;?>">
                                                            </div>
                                                            <div class="form-group col-lg-6 ocultar">
                                                                <label>SpO2</label>
                                                                <input type="text" name="spo2" id="spo2"
                                                                    class="form-control" value="<?php echo $spo2;?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>Hemoglucotest</label>
                                                                <input type="text" name="hemoglucotest"
                                                                    id="hemoglucotest" class="form-control"
                                                                    value="<?php echo $hemoglucotest;?>">
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label>Saturacion</label>
                                                                <input type="text" name="saturacion" id="saturacion"
                                                                    class="form-control"
                                                                    value="<?php echo $saturacion; ?>">
                                                            </div>
                                                            <div class="form-group col-lg-12">
                                                                <label>Dx</label>
                                                                <textarea rows="4" class="from-control col-lg-12"
                                                                    name="dx"
                                                                    id="dx"><?php echo $dx; ?></textarea>
                                                            </div>
                                                            <div class="form-group col-lg-12">
                                                            <label>Plan</label>
                                                                <textarea rows="4" class="from-control col-lg-12"
                                                                    name="plan"
                                                                    id="plan"><?php echo $plan ?></textarea> 
                                                            </div>
                                                            <div class="col-lg-6"></div>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 26px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="hallazgos-fisicos" role="tabpanel"
                                            aria-labelledby="hallazgos-fisicos-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse11" class="change" act="down">Hallazgos
                                                            Fisicos<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse11" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="hallazgo_fisico"
                                                                id="hallazgo_fisico"><?php echo $hallazgo_fisico; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!--ACA FINALIZA EL MENU DE LAS OPCIONES DE FISICO -->
                                </div>
                                <!--ACA FINALIZA EL ESPACIO QUE SERVIRA PARA VER LOS DATOS FISICOS
                                MAS RELEVANTES DEL PACIENTE -->




                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->
                                <div class="tab-pane fade" id="pills-historia" role="tabpanel"
                                    aria-labelledby="pills-historia-tab">
                                    <!---inicio-->
                                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="asunto-motivo-tab" data-toggle="tab"
                                                href="#asunto-motivo" role="tab" aria-controls="asunto-motivo"
                                                aria-selected="true">Asunto / Motivo</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="asunto-historial-tab" data-toggle="tab"
                                                href="#asunto-historial" role="tab" aria-controls="asunto-historial"
                                                aria-selected="false">Historia Clinica</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent2">
                                        <div class="tab-pane fade active in" id="asunto-motivo" role="tabpanel"
                                            aria-labelledby="asunto-motivo-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse" href="#collapse3"
                                                            class="change" act="down">Asunto/ Motivo/ Observaciones/Hx<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse3" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label>Motivo</label>
                                                                    <input type="text" name="otr_motivo" id="otr_motivo" class="form-control col-lg-12" value="<?php echo $motivo ?>">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label for="">Hx.</label>
                                                                    <textarea rows="4" class="from-control col-lg-12"
                                                                    name="hx"
                                                                    id="hx1"><?php echo $hx1; ?></textarea>
                                                                </div>
                                                            </div>

                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="asunto-historial" role="tabpanel"
                                            aria-labelledby="asunto-historial-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse12" class="change" act="down">Historia
                                                            Clinica<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse12" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="historia_clinica"
                                                                id="historia_clinica"><?php echo $historia_clinica; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!--final-->
                                </div>
                                <!--ACA FINALIZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->

                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS
                                DE LOS ANTECEDENTES DEL PACIENTE-->
                                <div class="tab-pane fade" id="pills-antecedentes" role="tabpanel"
                                    aria-labelledby="pills-antecedentes-tab">
                                    <ul class="nav nav-tabs" id="myTab3" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="antecedentes-personales-tab"
                                                data-toggle="tab" href="#antecedentes-personales" role="tab"
                                                aria-controls="antecedentes-personales"
                                                aria-selected="true">Antecedentes Personales</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="antecedentes-familiares-tab" data-toggle="tab"
                                                href="#antecedentes-familiares" role="tab"
                                                aria-controls="antecedentes-familiares"
                                                aria-selected="false">Antecedentes Familiares</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent3">
                                        <div class="tab-pane fade active in" id="antecedentes-personales"
                                            role="tabpanel" aria-labelledby="antecedentes-personales-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse13" class="change" act="down">Antecedentes
                                                            Personales<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse13" class="collapse panel-collapse in ">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="antecedente_personal"
                                                                id="antecedente_personal"><?php echo $antecedente_personal; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="antecedentes-familiares" role="tabpanel"
                                            aria-labelledby="antecedentes-familiares-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse14" class="change" act="down">Antecedentes
                                                            Familiares<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse14" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="antecedente_familiar"
                                                                id="antecedente_familiar"><?php echo $antecedente_familiar; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--ACA FINALIZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS
                                DE LOS ANTECEDENTES DEL PACIENTE-->





                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL
                                DIAGNOSTIVO DEL PACIENTE-->
                                <div class="tab-pane fade" id="pills-diagnosticos" role="tabpanel"
                                    aria-labelledby="pills-antecedentes-tab">
                                    <ul class="nav nav-tabs" id="myTab4" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="diagnostico-estandar-tab" data-toggle="tab"
                                                href="#diagnostico-estandar" role="tab"
                                                aria-controls="diagnostico-estandar" aria-selected="true">Diagnóstico
                                                (Según estándar CIE-10-ES)</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="otro-diagnostico-tab" data-toggle="tab"
                                                href="#otro-diagnostico" role="tab" aria-controls="otro-diagnostico"
                                                aria-selected="false">Otro Diagnostico</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent4">
                                        <div class="tab-pane fade active in" id="diagnostico-estandar" role="tabpanel"
                                            aria-labelledby="diagnostico-estandar-tab">

                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse" href="#collapse4"
                                                            class="change" act="down">Diagnóstico (Según estándar
                                                            CIE-10-ES)<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse4" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <div class="col-lg-12">
                                                                <label>Buscar</label>
                                                                <input type="text" name="diagno" id="diagno"
                                                                    class="form-control autocomplete">
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 90%;">Descripción</th>
                                                                            <th style="width: 10%;">Acción</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="diagnos_tt">
                                                                        <?php
                                                while($row = _fetch_array($query_diagnostico))
                                                {
                                                    echo "<tr id='$row[id_diagnostico]'>
                                                            <td>".$row["descripcion"]."</td>
                                                            <td><a class='btn elim' id='$row[id_diagnostico]'><i class='fa fa-trash'></i></a></td>
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
                                        <div class="tab-pane fade" id="otro-diagnostico" role="tabpanel"
                                            aria-labelledby="otro-diagnostico-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a class="change" data-toggle="collapse"
                                                            href="#collapse5" act="down">Otro Diagnóstico<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse5" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="otr_diagnostico"
                                                                id="otr_diagnostico"><?php echo $diagnostico; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--ACA FINALIZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL
                                DIAGNOSTIVO DEL PACIENTE-->





                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->
                                <div class="tab-pane fade" id="pills-receta" role="tabpanel"
                                    aria-labelledby="pills-receta-tab">
                                    <ul class="nav nav-tabs" id="myTab5" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="medicamento-recetado-tab" data-toggle="tab"
                                                href="#medicamento-recetado" role="tab"
                                                aria-controls="medicamento-recetado"
                                                aria-selected="true">Medicamento</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="otro-medicamento-tab" data-toggle="tab"
                                                href="#otro-medicamento" role="tab" aria-controls="otro-medicamento"
                                                aria-selected="false">Otro medicamento</a>
                                        </li>
                                    </ul>
                                    <!-- ACA EMPIEZA EL SELECT DE LOS DOCTORES QUE IMPRIMIRAN LA RECETA -->
                                    <label for="">Doctor receta</label>
                                    <select name="doctor_receta" id="doctor_receta" class="select" style="width:100%">


                                        <?php
                                        $sql_d = "SELECT id_doctor FROM reserva_cita WHERE id = '$id'";
                                        $query_d = _query($sql_d);
                                        $row_d = _fetch_array($query_d);
                                        $id_doctor_receta = $row_d['id_doctor'];
                                        $sql_doctor_receta = "SELECT * FROM doctor";
                                        $query_doctor_receta = _query($sql_doctor_receta);

                                        while($row_doctor_receta = _fetch_array($query_doctor_receta)){

                                            echo "<option value='".$row_doctor_receta['id_doctor']."' ";
                                            if($row_doctor_receta['id_doctor'] == $id_doctor_receta){
                                                echo " Selected ";
                                            }
                                            echo ">".$row_doctor_receta['nombres']." ".$row_doctor_receta['apellidos'];
                                            echo "</option>";
                                        }

                                    ?>

                                    </select>
                                    <input type="hidden" name="id_doctor_receta" id="id_doctor_receta"
                                        value="<?php echo $id_doctor_receta; ?>">
                                    <input type="hidden" name="id_paciente_consulta" id="id_paciente_consulta"
                                        value="<?php echo $id_paciente_info; ?>">
                                    <input type="hidden" name="id_cita_consulta" id="id_cita_consulta"
                                        value="<?php echo $id; ?>">

                                    <hr>
                                    <!-- ACA FINALIZA EL SELECT DE LOS DOCTORES QUE IMPRIMIRAN LA RECETA -->
                                    <div class="tab-content" id="myTabContent5">
                                        <div class="tab-pane fade active in" id="medicamento-recetado" role="tabpanel"
                                            aria-labelledby="medicamento-recetado-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse" href="#collapse6"
                                                            class="change" act="down">Receta<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse6" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <div class="col-lg-12 form-group">
                                                                <a style="margin-top: 10px;" data-toggle="modal"
                                                                    data-target="#viewModal" data-refresh="true"
                                                                    href="receta.php?id=<?php echo $id_paciente."&id_cita=".$id; ?>"><i
                                                                        class="fa fa-plus-circle fa-2x"></i> Agregar</a>
                                                            </div>
                                                            <div class="col-lg-12 form-group">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 90%;">Descripción</th>
                                                                            <th style="width: 10%;" colspan="2">Acción
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="receta">
                                                                        <?php
                                                                            while($row = _fetch_array($query_receta))
                                                                            {

                                                                                echo "<tr id='$row[id_medicamento]'>
                                                                                        <td> $row[descripcion]</td>
                                                                                        <td>
                                                                                            <a class='btn elimin' id='$row[id_medicamento]'><i class='fa fa-trash'></i></a>
                                                                                        </td>
                                                                                        <td>
                                                                                        <a href='ver_receta.php?id=".$row["id_medicamento"]."&idc=".$id."' class='btn' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye'></i></a>
                                                                                        </td>
                                                                                    </tr>";
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                                <a id="imprimir_receta_a" class="pull-right print_rect"
                                                                    style="margin-top: 10px;"
                                                                    href="receta_pdf.php?<?php echo "id_cita=".$id."&id_paciente=".$id_paciente."&id_doctor=".$id_doctor_receta;?>"
                                                                    target="_blank"><i class="fa fa-print fa-2x"></i>
                                                                    Imprimir</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="otro-medicamento" role="tabpanel"
                                            aria-labelledby="otro-medicamento-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse" href="#collapse7"
                                                            class="change" act="down">Otro Medicamento<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse7" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="otr_medicamento"
                                                                id="otr_medicamento"><?php echo $medicamento; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!--ACA FINALIZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->
                                <div class="tab-pane fade" id="pills-examen" role="tabpanel"
                                    aria-labelledby="pills-examen-tab">
                                    <ul class="nav nav-tabs" id="myTab6" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="examen-add-tab" data-toggle="tab"
                                                href="#examen-add" role="tab" aria-controls="examen-add"
                                                aria-selected="true">Examen</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="otro-examen-tab" data-toggle="tab"
                                                href="#otro-examen" role="tab" aria-controls="otro-examen-familiares"
                                                aria-selected="false">Otros Examenes</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent6">
                                        <div class="tab-pane fade active in" id="examen-add" role="tabpanel"
                                            aria-labelledby="examen-add-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse" href="#collapse8"
                                                            class="change" act="down">Examen<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse8" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <div class="col-lg-12">
                                                                <label>Buscar</label>
                                                                <input type="text" name="exam" id="exam"
                                                                    class="form-control autocomplete">
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 90%;">Descripción</th>
                                                                            <th style="width: 10%;">Acción</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="exam_tt">
                                                                        <?php
                                                while($row = _fetch_array($query_examen))
                                                {
                                                    echo "<tr id='$row[id_examen]'>
                                                            <td>$row[descripcion]</td>
                                                            <td><a class='btn elimi' id='$row[id_examen]'><i class='fa fa-trash'></i></a></td>
                                                           </tr>";
                                                }
                                            ?>
                                                                    </tbody>
                                                                </table>
                                                                <!--<a class="pull-right" style="margin-top: 10px;"
                                                                    href="ver_examen_pdf.php?<?php echo "id_cita=".$id."&id_paciente=".$id_paciente;?>"
                                                                    target="_blank"><i class="fa fa-print fa-2x"></i>
                                                                    Imprimir</a>-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="otro-examen" role="tabpanel"
                                            aria-labelledby="otro-examen-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse" href="#collapse9"
                                                            class="change" act="down">Otro Examen<i
                                                                class="fa fa-angle-double-down pull-right"></i></a>
                                                    </h4>
                                                </div>
                                                <div id="collapse9" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="otr_examen"
                                                                id="otr_examen"><?php echo $examen; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->


                                <!--ACA FINALIZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->


                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->
                                <div class="tab-pane fade" id="pills-constancia" role="tabpanel"
                                    aria-labelledby="pills-constancia-tab">
                                    <ul class="nav nav-tabs" id="myTab6" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="constancia-add-tab" data-toggle="tab"
                                                href="#constancia-add" role="tab" aria-controls="constancia-add"
                                                aria-selected="true">Agregar Constancia</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="ver-constancias-tab" data-toggle="tab"
                                                href="#ver-constancias" role="tab" aria-controls="ver-constancias"
                                                aria-selected="false">Ver / Imprimir Constancias</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent6">
                                        <div class="tab-pane fade" id="ver-constancias" role="tabpanel"
                                            aria-labelledby="ver-constancias-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse" href="#collapse8"
                                                            class="change" act="down">Constancias<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse8" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <div class="col-lg-12">
                                                                <label>Buscar</label>
                                                                <input type="text" name="exam" id="exam"
                                                                    class="form-control autocomplete">
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 80%;">Descripción</th>
                                                                            <th style="width: 20%;">Acción</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="constancias_agregadas">
                                                                        <?php
                                                                            if($numero_constancias > 0){
                                                                                while($row = _fetch_array($query_constancias))
                                                                                {
                                                                                    echo "<tr id='$row[id_constancia]'>
                                                                                            <td>$row[padecimiento]</td>
                                                                                            <td>
                                                                                                <a class='btn elimin_constancia' id='$row[id_constancia]'><i class='fa fa-trash'></i></a>
                                                                                                <a href='ver_constancia_consulta.php?id_constancia=".$row["id_constancia"]."' class='btn' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye'></i></a>
                                                                                                <a href='ver_constancia1.php?id=".$row["id_constancia"]."&id_constancia=".$row["id_constancia"]."' target='_blank'><i class='fa fa-print'></i></a>
                                                                                            </td>
                                                                                        </tr>";
                                                                                }
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
                                        <div class="tab-pane fade active in" id="constancia-add" role="tabpanel"
                                            aria-labelledby="constancia-add-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse" href="#collapse9"
                                                            class="change" act="down">Agregar Constancia<i
                                                                class="fa fa-angle-double-down pull-right"></i></a>
                                                    </h4>
                                                </div>
                                                <div id="collapse9" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <div class="row">
                                                                <div class="col-lg-6 form-group">
                                                                    <label>Tipo</label>
                                                                    <br>
                                                                    <select class="form-control select" name="forma"
                                                                        id="forma" disabled style="width:100%">
                                                                        <option value="">Seleccione</option>
                                                                        <option value="constancia" Selected>Constancia
                                                                            Médica</option>
                                                                        <option value="defuncion">Constancia de
                                                                            Defunción</option>
                                                                        <option value="otro">Otro</option>
                                                                    </select>
                                                                </div>
                                                                <div class='col-md-6'>
                                                                    <div class="form-group">
                                                                        <label>Fecha de expedición:</label>
                                                                        <input type='text'
                                                                            class='datepicker form-control'
                                                                            id='fecha_expedicion'
                                                                            name='fecha_expedicion'
                                                                            value='<?php echo date('d-m-Y');?>'>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" id="div_pa">

                                                                <div class="col-lg-12 form-group">
                                                                    <label>Doctor</label>
                                                                    <br>
                                                                    <select name="id_doctor_constancia"
                                                                        id="id_doctor_constancia" style="width:100%"
                                                                        class="select">
                                                                        <option value="" selected>Seleccione</option>
                                                                        <?php
                                                                            $sql_doctor = "SELECT * FROM doctor";
                                                                            $query_doctor = _query($sql_doctor);

                                                                            while($row_doctor = _fetch_array($query_doctor)){
                                                                                $nombre_doctor = $row_doctor['nombres']." ".$row_doctor['apellidos'];
                                                                                echo "<option value='".$row_doctor['id_doctor']."'";
                                                                                if($id_doctor_receta == $row_doctor['id_doctor']){
                                                                                    echo " selected ";
                                                                                }
                                                                                echo ">";
                                                                                echo $nombre_doctor;
                                                                                echo "</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row cons">
                                                                <div class="col-lg-6 form-group">
                                                                    <label>Padecimiento</label>
                                                                    <input type="text" name="padecimiento"
                                                                        id="padecimiento" class="form-control">
                                                                </div>
                                                                <!--<div class="col-lg-6">
                                                                    <label>Tratamiento</label>
                                                                    <input type="text" name="tratamiento" id="tratamiento" class="form-control">
                                                                </div>-->
                                                                <div class="col-lg-6">
                                                                    <label>Reposo (Días)</label>
                                                                    <input type="text" name="reposo" id="reposo"
                                                                        class="form-control numeric">
                                                                </div>
                                                            </div>
                                                            <a class="btn btn-primary pull-right guardar_constancia"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--ACA FINALIZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->


                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->
                                <div class="tab-pane fade" id="pills-cobros" role="tabpanel"
                                    aria-labelledby="pills-cobros-tab">
                                    <ul class="nav nav-tabs" id="myTab7" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="cobros-servicios-tab" data-toggle="tab"
                                                href="#cobros-servicios" role="tab" aria-controls="cobros-servicios"
                                                aria-selected="true">Servicios Profesionales</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="otro-cobros-tab" data-toggle="tab"
                                                href="#otro-cobros" role="tab" aria-controls="otro-cobros-familiares"
                                                aria-selected="false">Otros Cobros</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent7">
                                        <div class="tab-pane fade active in" id="cobros-servicios" role="tabpanel"
                                            aria-labelledby="cobros-servicios-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse10" class="change" act="down">Servicios
                                                            Profesionales (Cobros)<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse10" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <div class="col-lg-12 form-group">
                                                                <a style="margin-top: 10px;" data-toggle="modal"
                                                                    data-target="#viewModal" data-refresh="true"
                                                                    href="servicio_profesional.php?id=<?php echo $id_paciente."&id_cita=".$id; ?>"><i
                                                                        class="fa fa-plus-circle fa-2x"></i> Agregar</a>
                                                            </div>
                                                            <div class="col-lg-12 form-group">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 90%;">Descripción</th>
                                                                            <th style="width: 10%;" colspan="2">Acción
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="servicios_profesionales">
                                                                        <?php
                                                while($row = _fetch_array($query_servicios_profesionales))
                                                {
                                                    echo "<tr id='$row[id_servicio_profesional]'>";
                                                            echo "<td> ".$row['descripcion'].", $".number_format($row['precio'],2)."</td>";
                                                            echo "<td>
                                                                <a class='btn elimin_sp' id='$row[id_servicio_profesional]'><i class='fa fa-trash'></i></a>
                                                            </td>
                                                            <td>
                                                                <a href='ver_servicio_profesional.php?id=".$row["id_servicio_profesional"]."&idc=".$id."' class='btn' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye'></i></a>
                                                            </td>
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
                                        <div class="tab-pane fade" id="otro-cobros" role="tabpanel"
                                            aria-labelledby="otro-cobros-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse17" class="change" act="down">Otros Cobros<i
                                                                class="fa fa-angle-double-down pull-right"></i></a>
                                                    </h4>
                                                </div>
                                                <div id="collapse17" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="otros_cobros"
                                                                id="otros_cobros"><?php echo $otros_cobros; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--ACA FINALIZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->



                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->
                                <div class="tab-pane fade" id="pills-ingresos" role="tabpanel"
                                    aria-labelledby="pills-ingresos-tab">
                                    <ul class="nav nav-tabs" id="myTab8" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="ingreso-paciente-tab" data-toggle="tab"
                                                href="#ingreso-paciente" role="tab" aria-controls="ingreso-paciente"
                                                aria-selected="true">Ingreso Hospitalario</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="indicaciones-paciente-tab" data-toggle="tab"
                                                href="#indicaciones-paciente" role="tab"
                                                aria-controls="indicaciones-paciente" aria-selected="false">Indicaciones
                                                Medicas</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent8">
                                        <div class="tab-pane fade active in" id="ingreso-paciente" role="tabpanel"
                                            aria-labelledby="ingreso-paciente-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse15" class="change" act="down">Ingreso
                                                            Hospitalario<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse15" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="ingreso_hospitalario"
                                                                id="ingreso_hospitalario"><?php echo $ingreso_hospitalario; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="indicaciones-paciente" role="tabpanel"
                                            aria-labelledby="indicaciones-paciente-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse16" class="change" act="down">Indicaciones
                                                            Medicas<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse16" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <textarea rows="4" class="from-control col-lg-12"
                                                                name="indicacion_medica"
                                                                id="indicacion_medica"><?php echo $indicacion_medica; ?></textarea>
                                                            <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--ACA FINALIZA EL ESPACIO QUE SERVIRA PARA PODER VER DATOS DEL ASUNTO
                                Y DE LA HISTORIA CLINICA DEL PACIENTE-->

                                <!--Inicia el espacio donde mostrara el examen de la ultra-->
                                <div class="tab-pane fade" id="pills-ultra" role="tabpanel"
                                    aria-labelledby="pills-ultra-tab">
                                    <ul class="nav nav-tabs" id="myTab8" role="tablist">
                                        <li class="nav-item active">
                                            <a class="nav-link active" id="ingreso-paciente-tab" data-toggle="tab"
                                                href="#ingreso-paciente" role="tab" aria-controls="ingreso-paciente"
                                                aria-selected="true">Examen de ultrasonografia</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent8">
                                        <div class="tab-pane fade active in" id="ingreso-paciente" role="tabpanel"
                                            aria-labelledby="ingreso-paciente-tab">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class='text-success'><a data-toggle="collapse"
                                                            href="#collapse15" class="change" act="down">Examen de Ultrasonografia<i
                                                                class="fa fa-angle-double-down pull-right"></i></a></h4>
                                                </div>
                                                <div id="collapse15" class="collapse panel-collapse in">
                                                    <div class="panel-body">
                                                        <div class="widget-content">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <a href="<?php echo $examen_ultra ?>" data-lightbox="image-1" data-title="Examen de Ultrasonografia de <?php echo $nombre_paciente.' '.$apellido_paciente ?>" class="pop">
                                                                        <?php if($examen_ultra!=''){ ?>
                                                                            <img id="view_ultra" src="<?php echo $examen_ultra ?>" style='width: 200px; height: 200px;'>
                                                                        <?php }else{ ?>
                                                                           <img id="view_ultra" src="img/sinimagen.png" style='width: 200px; height: 200px;'>                                                                    
                                                                        <?php } ?>                          
                                                                
                                                                    </a>
                                                                </div>
                                                                <div class="col-lg-12 form-group">
                                                                    <label>Diagnostico de examen:</label>
                                                                    <textarea class="form-control" name="dx_ultra" id="dx_ultra" cols="10" rows="3">
                                                                        <?php
                                                                            echo $dx_ultra
                                                                        ?>
                                                                    </textarea>
                                                                    <a class="btn btn-primary pull-right otr_guardar"
                                                                style="margin-top: 10px;">Guardar</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!--finaliza el espacio donde mostrara el examen de la ultra-->

                                <!--ACA EMPIEZA EL ESPACIO QUE SERVIRA PARA PODER VER LOS DATOS
                                MAS RELEVANTES DEL PACIENTE -->
                                <div class="tab-pane fade" id="pills-consultas" role="tabpanel"
                                    aria-labelledby="pills-consultas-tab">
                                    <!--EMPIEZAN LOS DATOS GENERALES DEL PACIENTE -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class='text-success'>
                                                <a data-toggle="collapse" href="#collapse18"
                                                    class="change" act="down">Futuras Consultas<i
                                                        class="fa fa-angle-double-down pull-right"></i></a></h4>
                                        </div>
                                        <div id="collapse18" class="collapse panel-collapse in">
                                            <div class="panel-body">
                                                <div class="widget-content">
                                                    <div class="col-lg-12 form-group">
                                                        <a style="margin-top: 10px;" data-toggle="modal"
                                                            data-target="#viewModal" data-refresh="true"
                                                            href="futura_consulta.php?id=<?php echo $id_paciente."&id_cita=".$id; ?>"><i
                                                                class="fa fa-plus-circle fa-2x"></i> Agregar</a>
                                                    </div>
                                                    <div class="col-lg-12 form-group">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 40%;">Motivo</th>
                                                                    <th style="width: 25%;">Fecha</th>
                                                                    <th style="width: 25%;">Consultorio</th>
                                                                    <th style="width: 10%;" colspan="2">Acción</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="futuras_consultas">
                                                                <?php
                                                    while($row = _fetch_array($query_futuras_consultas))
                                                    {
                                                        echo "<tr id='".$row['id']."'>";
                                                        echo "<td style='width: 40%;'> ".$row['motivo_consulta']."</td>";
                                                        echo "<td style='width: 25%;'> ".ED($row['fecha_cita']).", "._hora_media_decode($row['hora_cita'])."</td>";
                                                        echo "<td style='width: 25%;'> ".$row['descripcion']."</td>";
                                                        echo "<td style='width: 5%;'>";
                                                        echo "<a class='btn eliminar_consulta' id='".$row['id']."'><i class='fa fa-trash'></i></a>";
                                                        echo "</td>";
                                                        echo "<td style='width: 5%;'>";
                                                        echo "<a href='ver_futura_consulta.php?id=".$row["id"]."&idc=".$id."' class='btn' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye'></i></a>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--TERMINA LOS DATOS GENERALES DEL PACIENTE -->
                                </div>
                                <!--ACA TERMINA EL ESPACIO QUE SERVIRA PARA PODER VER LOS DATOS
                                MAS RELEVANTES DEL PACIENTE -->




                            </div>


                        </div>
                    </div>

                </div>
            </div>
                                                
        </div>


        <div class="row">
            <div class="col-lg-12">
                <button class="btn btn-primary pull-right" id="finiquit">Finalizar Consulta</button>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_cita" id="id_cita" value="<?php echo $id; ?>">
    <input type="hidden" name="acc" id="acc" value="<?php echo $acc; ?>">
    <!-- Modal Agregar Cita-->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-md">

            </div>
        </div>
    </div>
    <!-- Fin Modal Agregar Cita-->
    <!-- Modal Agregar Cita-->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-md">

            </div>
        </div>
    </div>
    <!-- Fin Modal Agregar Cita-->
    <!-- Modal Agregar Cita-->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
echo "<script src='js/funciones/funciones_consulta1.js'></script>";
} //permiso del script
else
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
}
function finalizar()
{
    $diagnostico = trim($_POST["diagnostico"]);
    $examen = trim($_POST["examen"]);
    $medicamento = trim($_POST["medicamento"]);
    $motivo = trim($_POST["motivo"]);
    $ta = $_POST["ta"];
    $to = $_POST["t_o"];
    $p = $_POST["p"];
    $peso = $_POST["peso"];
    $fr =$_POST["fr"];
    $id = $_POST["id"];
    $now = date("Y-m-d");
    $query = _query("SELECT id_cola FROM cola_dia WHERE id_cita='$id' AND fecha='$now'");
    if(_num_rows($query) == 0){
        $sql_doctor = "SELECT * FROM reserva_cita WHERE id= '$id'";
        $query_doctor = _query($sql_doctor);
        $row_doctor = _fetch_array($query_doctor);
        $id_doctor = $row_doctor['id_doctor'];
        $form_data = array(
            'id_cita' => $id,
            'id_doctor' => $id_doctor,
            'fecha' => $now,
            'prioridad' => 0,
        );
        $table = "cola_dia";
        $inser = _insert($table, $form_data);
        $id_cola = _insert_id();
    }
    else{
        $datos = _fetch_array($query);
        $id_cola = $datos["id_cola"];
    }
    $table = "reserva_cita";
    $table2 = 'cola_dia';
    $form_data = array(
        'motivo_consulta' => $motivo,
        'diagnostico' => $diagnostico,
        'examen' => $examen,
        'medicamento' => $medicamento,
        'ta' => $ta,
        't_o' => $to,
        'p' => $p,
        'peso' => $peso,
        'fr' => $fr,
        'estado' =>8,//se actualiza el estado a pendiente de pago
    );
    $form_data2 =  array(
        'prioridad'=> -1
    );
    $where_clause = "id = '".$id."'";
    $where_clause2 = "id_cola = '".$id_cola."'";
    $update = _update($table,$form_data,$where_clause);
    $update2 = _update($table2,$form_data2,$where_clause2);
    if($update && $update2)
    {
        //ahora que se ha actualizado cola y reserva_cita se tienq ue actualizar recepcion
        //y pasarlo a factura pendiente
        $sql_recepcion_cita="SELECT rc.id_recepcion FROM 
                            recepcion_cita AS rc WHERE rc.id_reserva_cita=$id";//se busca la recepcion de 
                            //esta cita
        //obteniendo la recepcion de cita
            //echo _num_rows(_query($sql_recepcion_cita));
        if(_num_rows(_query($sql_recepcion_cita))>0){//si la cita existe se busca dentro de la tabla 
            //recepcion_cita para actualizar el estado de el estado de recepcion..
            ///echo "Entro aqui....";
            $row_recepcion=_fetch_array(_query($sql_recepcion_cita));
            //obteniendo el id
            $id_recepcion=$row_recepcion['id_recepcion'];
            
            $table_recepcion="recepcion";
            $form_data3=[
                'id_estado_recepcion'=>7,
            ];
            $extra_where='id_recepcion='.$id_recepcion;
            
            $update_rece=_update($table_recepcion, $form_data3, $extra_where);
            if($update_rece){
                $xdata['msgrec']="estado recepcion actualizada correctamente..";
            }
        }else if(_num_rows(_query($sql_recepcion_cita))==0){
            //si no existe la recepcion tendra que crearla ya que el paciente viene por cita
            $sql_cita='SELECT * FROM reserva_cita WHERE id='.$id;
            $query_cita=_query($sql_cita);
            $row_cita=_fetch_array($query_cita);
            $id_paciente_recepcion=$row_cita['id_paciente'];
            $id_doctor=$row_cita['id_doctor'];
            $id_usuario=$_SESSION['id_usuario'];

            $table='recepcion';
            $fecha_hoy=date('Y-m-d');
            $form_data3=[
                "evento"=>'consulta',
                'nombre_pariente'=>'',
                'fecha_de_entrada'=>$fecha_hoy,
                'telefono_contacto'=>'',
                'id_paciente_recepcion'=>$id_paciente_recepcion,
                'id_doctor_recepcion'=>$id_doctor,
                'id_usuario_recepcion'=>$id_usuario,
                'id_estado_recepcion'=>'7',
                'id_sucursal_recepcion'=>'1',
                'id_tipo_recepcion'=>'14',
                'recepcion_consulta'=>'1',
                'doctor_refiere'=>$id_doctor

            ];

            $insert_re=_insert($table, $form_data3);
            if($insert_re){
                $id_recep=_insert_id();
                $table='recepcion_cita';
                $form_data4=[
                    'id_recepcion'=>$id_recep,
                    'id_reserva_cita'=>$id
                ];
                $insert_re_ci=_insert($table, $form_data4);
            }
            

        }

        $estado_pago=8;
        
        //actualizando el estado de la consulta 

        $xdata["typeinfo"]="Success";
        $xdata["msg"]="Consulta Finalizada correctamente";
    }
    else
    {
        $xdata["typeinfo"]="Error";
        $xdata["msg"]="Ocurrio un error inesperado, intentelo de nuevo";
    }
    echo json_encode($xdata);
}
function otr()
{
    $xdata=array();

    $diagnostico = trim($_POST["diagnostico"]);
    $examen = trim($_POST["examen"]);
    $medicamento = trim($_POST["medicamento"]);
    $motivo = trim($_POST["motivo"]);
    $ta = $_POST["ta"];
    $to = $_POST["t_o"];
    $p = $_POST["p"];
    $peso = $_POST["peso"];
    $altura=$_POST["altura"];
    $fr =$_POST["fr"];
    $id = $_POST["id"];
    $spo2 = $_POST['spo2'];
    $hemoglucotest = $_POST['hemoglucotest'];
    $hallazgo_fisico = $_POST['hallazgo_fisico'];
    $historia_clinica = $_POST['historia_clinica'];
    $antecedente_familiar = $_POST['antecedente_familiar'];
    $antecedente_personal = $_POST['antecedente_personal'];
    $ingreso_hospitalario = $_POST['ingreso_hospitalario'];
    $indicacion_medica = $_POST['indicacion_medica'];
    $otros_cobros = $_POST['otros_cobros'];
    $saturacion = $_POST['saturacion'];
    $frecuencia_cardiaca=$_POST['fc'];
    $dx=$_POST['dx'];
    $plan=$_POST['plan'];
    $hx1=$_POST['hx1'];
    $dx_ultra=$_POST['dx_ultra'];

    $table = "reserva_cita";
    $form_data = array(
        'motivo_consulta' => $motivo,
        'diagnostico' => $diagnostico,
        'examen' => $examen,
        'medicamento' => $medicamento,
        'ta' => $ta,
        't_o' => $to,
        'p' => $p,
        'peso' => $peso,
        'altura'=>$altura,
        'fr' => $fr,
        'spo2' => $spo2,
        'hemoglucotest' => $hemoglucotest,
        'hallazgo_fisico' => $hallazgo_fisico,
        'historia_clinica' => $historia_clinica,
        'antecedente_familiar'=> $antecedente_familiar,
        'antecedente_personal' => $antecedente_personal,
        'ingreso_hospitalario' => $ingreso_hospitalario,
        'indicacion_medica' => $indicacion_medica,
        'otros_cobros' => $otros_cobros,
        'saturacion' => $saturacion,
        'fc'=>$frecuencia_cardiaca,
        'dx'=>$dx,
        'plan'=>$plan,
        'hx'=>$hx1,
        'dx_ultra'=>$dx_ultra
    );
    $where_clause = "id = '".$id."'";
    $update = _update($table,$form_data,$where_clause);
    if($update)
    {
        $xdata["typeinfo"]="Success";
        $xdata["msg"]="Informacion guardada correctamente";
        //$xdatos['datos']=$update;
    }
    else
    {
        $xdata["typeinfo"]="Error";
        $xdata["msg"]="Informacion no pudo ser guardada";
    }
    echo json_encode($xdata);
}
function buscar_dat($idc)
{

    $aux = _query("SELECT * FROM reserva_cita WHERE id='$idc'");
    $id = _fetch_array($aux)["id_paciente"];

    $sql="SELECT p.*, d.nombre_departamento, m.nombre_municipio FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id'";
    $result = _query($sql);
    $numm = _num_rows($result);
    if($numm>0)
    {
        $row = _fetch_array($result);
        $nombre=$row['nombres'];
        $apellido = $row['apellidos'];
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
        $dui=$row['dui'];

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
            <td>Teléfono:</td>
            <td>'.$telefono1.'</td>
        </tr>
        <tr>
            <td style="width: 37%;">DUI:</td>
            <td style="width: 12%;">'.$dui.'</td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td colspan="2"><a  class="pull-right" data-toggle="modal" data-target="#editModal" data-refresh="true" href="editar_paciente1.php?id='.$id.'&id_cita='.$idc.'"><i title="Editar Datos" class="fa fa-pencil-square fa-2x"></i> Editar</a>
            </td>
        </tr>';
        $dato.= '</table>';
        $sql2= _query("SELECT * FROM signos_vitales WHERE id_paciente ='$id' AND id_cita='$idc' ORDER BY id_signo DESC LIMIT 1");
        $num2 = _num_rows($sql2);
        $datoa= '<table class="table  table-checkable datatable" id="signo">';
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

            $datoa.='
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
            <tr>
                <td colspan="4"><a class="btn btn-primary pull-right" data-toggle="modal" data-target="#editModal" data-refresh="true" href="signos.php?id='.$idc.'" id="eval">Repetir Evaluación</a></td>
            </tr>';
        }
        else
        {
           $datoa.='<tr>
                <td colspan="4"><a class="btn btn-primary pull-right" data-toggle="modal" data-target="#editModal" data-refresh="true" href="signos.php?id='.$idc.'" id="eval">Agregar Evaluación</a></td>
            </tr>';
        }
        $datoa.='</table>';
    }
    else
    {

    }
    $xdatt["table"] = $dato;
    $xdatt["signo"] = $datoa;
    $xdatt['typeinfo']="Success";
    echo json_encode($xdatt);
}
function diagnostico()
{
    $id = $_POST["id"];
    $id_cita = $_POST["id_cita"];
    $query = _query("SELECT id_paciente FROM reserva_cita WHERE id ='$id_cita'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $table = "diagnostico_paciente";
    $form_data  = array(
        'id_diagnostico' => $id,
        'id_paciente' => $id_paciente,
        'id_cita' => $id_cita
        );
    $insert = _insert($table, $form_data);
    if($insert)
    {
        $xdata["typeinfo"]="Success";
    }
    else
    {
        $xdata["typeinfo"] = "Error";
    }
    echo json_encode($xdata);
}
function rm_diagnostico()
{
    $id = $_POST["id"];
    $id_cita = $_POST["id_cita"];
    $query = _query("SELECT id_paciente FROM reserva_cita WHERE id ='$id_cita'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $table = "diagnostico_paciente";
    $where_clause = "id_diagnostico='".$id."' AND id_paciente='".$id_paciente."' AND id_cita='".$id_cita."'";
    $delete = _delete($table, $where_clause);
    if($delete)
    {
        $xdata["typeinfo"]="Success";
    }
    else
    {
        $xdata["typeinfo"] = "Error";
    }
    echo json_encode($xdata);
}
function examen()
{
    $id = $_POST["id"];
    $id_cita = $_POST["id_cita"];
    $query = _query("SELECT id_paciente FROM reserva_cita WHERE id ='$id_cita'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $fecha = date("Y-m-d");

    $table = "examen_paciente";
    $form_data  = array(
        'id_examen' => $id,
        'id_paciente' => $id_paciente,
        'id_cita' => $id_cita,
        'fecha_asignacion' => $fecha,
        'realizado' => 0,
        'fecha_realizacion' => date("Y:m:d"),
        'resultado' => '',
        'fecha_lectura' => date("Y:m:d"),
        'observaciones' => ''
    );
    $insert = _insert($table, $form_data);
    if($insert)
    {
        $xdata["typeinfo"]="Success";
    }
    else
    {
        $xdata["typeinfo"] = "Error";
    }
    echo json_encode($xdata);
}
function rm_examen()
{
    $id = $_POST["id"];
    $id_cita = $_POST["id_cita"];
    $query = _query("SELECT id_paciente FROM reserva_cita WHERE id ='$id_cita'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $table = "examen_paciente";
    $where_clause = "id_examen='".$id."' AND id_paciente='".$id_paciente."' AND id_cita='".$id_cita."'";
    $delete = _delete($table, $where_clause);
    if($delete)
    {
        $xdata["typeinfo"]="Success";
    }
    else
    {
        $xdata["typeinfo"] = "Error";
    }
    echo json_encode($xdata);
}
function receta()
{
    $id = $_POST["id"];
    $id_cita = $_POST["id_cita"];
    $cantidad = $_POST["cantidad"];
    $dosis = $_POST["dosis"];
    $plan = $_POST["plan"];
    $sql_exis = _query("SELECT vacuna FROM medicamento WHERE id_medicamento='$id'");
    $vacuna = "";
    if(_num_rows($sql_exis) > 0){
        $dato_exis = _fetch_array($sql_exis);
        $vacuna = $dato_exis["vacuna"];
    }
    $query = _query("SELECT id_paciente FROM reserva_cita WHERE id ='$id_cita'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $table = "receta";
    $form_data  = array(
        'id_medicamento' => $id,
        'id_paciente' => $id_paciente,
        'id_cita' => $id_cita,
        'cantidad' => $cantidad,
        'dosis' => $dosis
        );
    $insert = _insert($table, $form_data);
    if($insert)
    {
        if($vacuna)
        {
            if($plan)
            {
                $table2 = "plan_vacuna";
            }
            else
            {
                $table2 = "vacuna_dia";
            }
            if($id_cita == '-999' && $table2=="plan_vacuna")
            {
                 $form_data2  = array(
                    'id_plan' => -999,
                    'id_paciente' => $id_paciente,
                    'id_cita' => $id_cita,
                    'id_medicamento' => $id,
                    'cantidad' => $cantidad,
                    'dosis' => $dosis,
                    'fecha' => date("Y-m-d"),
                    'finalizado' => '0'
                );

            }
            else
            {
                 $form_data2  = array(
                    'id_paciente' => $id_paciente,
                    'id_cita' => $id_cita,
                    'id_medicamento' => $id,
                    'cantidad' => $cantidad,
                    'dosis' => $dosis,
                    'fecha' => date("Y-m-d"),
                    'finalizado' => '0'
                );

            }
            $insert2 = _insert($table2, $form_data2);
            if($insert2)
            {
                $xdata["typeinfo"]="Success";
            }
            else
            {
                $xdata["typeinfo"] = "Error :"._error();
            }
        }
        else
        {
            $xdata["typeinfo"]="Success";
        }
    }
    else
    {
        $xdata["typeinfo"] = "Error :"._error();
    }
    echo json_encode($xdata);
}
function rm_receta()
{
    $id = $_POST["id"];
    $id_cita = $_POST["id_cita"];
    $query = _query("SELECT id_paciente FROM reserva_cita WHERE id ='$id_cita'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $table = "receta";
    $where_clause = "id_medicamento='".$id."' AND id_paciente='".$id_paciente."' AND id_cita='".$id_cita."'";
    $delete = _delete($table, $where_clause);

    $table2 = "vacuna_dia";
    $where_clause2 = "id_medicamento='".$id."' AND id_paciente='".$id_paciente."' AND id_cita='".$id_cita."'";
    $delete2 = _delete($table2, $where_clause2);

    $table3 = "plan_vacuna";
    $where_clause3 = "id_medicamento='".$id."' AND id_paciente='".$id_paciente."' AND id_cita='".$id_cita."'";
    $delete3 = _delete($table3, $where_clause3);

    if($delete && $delete2)
    {
        $xdata["typeinfo"]="Success";
    }
    else
    {
        $xdata["typeinfo"] = "Error";
    }
    echo json_encode($xdata);
}
/* ESTA FUNCION SIRVE PARA ELIMINAR LOS SERVICIOS PROFESIONALES SOBRE LOS CUALES SE
DE CLICK SOBRE EL BOTON ELIMINAR */
function rm_servicio_profesional()
{
    $id = $_POST["id"];
    $id_cita = $_POST["id_cita"];
    $table = 'servicios_profesionales';
    $where = " id_servicio_profesional = '$id'";
    $delete = _delete($table, $where);
    if($delete)
    {
        $xdata["typeinfo"]="Success";
    }
    else
    {
        $xdata["typeinfo"] = "Error";
    }
    echo json_encode($xdata);
}
/* ACA FINALIZA LA FUNCION QUE ELIMINA LOS SERVICIOS PROFESIONALES DE LA CONSULTA */


/* ESTA FUNCION SIRVE PARA ELIMINAR FUTURAS CONSULTAS CUANDO SE DE CLIC SOBRE EL
BOTON ELIMINAR DEL REGISTRO DE LA TABLA DE FUTURAS CONSULTAS */

function rm_futura_consulta()
{
    $id = $_POST["id"];
    $id_cita = $_POST["id_cita"];
    $table = 'reserva_cita';
    $where = " id = '$id'";
    $delete = _delete($table, $where);
    if($delete)
    {
        $xdata["typeinfo"]="Success";
    }
    else
    {
        $xdata["typeinfo"] = "Error";
    }
    echo json_encode($xdata);
}
/* ACA FINALIZA LA FUNCION QUE ELIMINA LAS FUTURAS CONSULTAS DE LA CONSULTA */

/* ACA EMPIEZA LA FUNCION QUE SIRVE PARA AGREGAR UNA CONSTANCIA A
LA CITA */

function agregar_constancia(){
    $id_sucursal = $_SESSION['id_sucursal'];
    $id_paciente = $_POST['id_paciente'];
    $id_doctor = $_POST['id_doctor'];
    $fecha_expedicion =MD( $_POST['fecha_expedicion']);
    $padecimiento = $_POST['padecimiento'];
    $reposo = $_POST['reposo'];
    $id_cita = $_POST['id_cita'];
    $tabla = 'constancia';
    $form_data = array(
        'id_paciente' => $id_paciente,
        'fecha' => $fecha_expedicion,
        'padecimiento' => $padecimiento,
        'tratamiento' => '',
        'reposo' => $reposo,
        'tipo' => 'constancia',
        'fecha_d' => date("Y:m:d"),
        'hora_d' => date("H:i:s"),
        'lugar' => '',
        'id_doctor' => $id_doctor,
        'id_cita' => $id_cita
    );
    $insert = _insert($tabla,$form_data);
    if($insert){
        $id_constancia = _insert_id();
        $xdatos['typeinfo'] = "Success";
        $xdatos['msg'] = "Constancia agregada con exito!";
        $xdatos['id_constancia'] = $id_constancia;
    }
    else{
        $xdatos['typeinfo'] = "Error";
        $xdatos['msg'] = "No se pudo agregar la constancia";
    }
    echo json_encode($xdatos);
}

/* ACA FINALIZA LA FUNCION QUE SIRVE PARA AGREGAR UNA CONSTANCIA A
LA CITA */

/* ESTA FUCNION SIRVE PARA PODER ELIMINAR UNA CONSTANCIA
AGREGADA EN UNA CITA */

function rm_constancia(){
    $id_constancia = $_POST['id_constancia'];
    $table = 'constancia';
    $where = " WHERE id_constancia = '$id_constancia'";
    $delete = _delete($table,$where);
    if($delete){
        $xdatos['typeinfo'] = "Success";
    }
    else{
        $xdatos['typeinfo'] = "Error";
    }
    echo json_encode($xdatos);
}
/* ESTA FUCNION SIRVE PARA PODER ELIMINAR UNA CONSTANCIA
AGREGADA EN UNA CITA */

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
            case 'otr':
                otr();
                break;
            case 'buscar':
                buscar_dat($_POST["id"]);
                break;
            case 'diagnostico':
                diagnostico();
                break;
            case 'rm_diagnostico':
                rm_diagnostico();
                break;
            case 'examen':
                examen();
                break;
            case 'rm_examen':
                rm_examen();
                break;
            case 'receta':
                receta();
                break;
            case 'rm_receta':
                rm_receta();
                break;
            case 'rm_servicio_profesional':
                rm_servicio_profesional();
                break;
            case 'rm_futura_consulta':
                rm_futura_consulta();
                break;
            case 'agregar_constancia':
                agregar_constancia();
                break;
            case 'rm_constancia':
                rm_constancia();
                break;
        }
    }
}
?>
