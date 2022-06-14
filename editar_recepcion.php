<?php
	include ("_core.php");
	// Page setup
	$title='Editar recepcion';
	$_PAGE = array ();
	$_PAGE ['title'] =$title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/timepicki/timepicki.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
    $hoy=date('d-m-Y');
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
    $idRecepcion = $_REQUEST['idRecepcion'];
    $_SESSION['idRecepcion'] = $idRecepcion;
    $consultadatos="SELECT recepcion.id_doctor_recepcion, recepcion.doctor_refiere, recepcion.evento, recepcion.recuperado_base, recepcion.fecha_de_entrada, recepcion.nombre_pariente, recepcion.telefono_contacto, recepcion.id_tipo_recepcion, recepcion.otro, recepcion.id_pariente_contacto, paciente.id_paciente, paciente.dui, paciente.direccion, paciente.nombres, paciente.apellidos, paciente.fecha_nacimiento, paciente.sexo FROM recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion WHERE recepcion.id_recepcion = '$idRecepcion'";
    $resultado = _query($consultadatos);
    $row=_fetch_array($resultado);
    $doctor_refiere = $row['doctor_refiere'];
    $idDoctorRecepcion = $row['id_doctor_recepcion'];
    $nombrePariente = $row['nombre_pariente'];
    $telefonoContacto = $row['telefono_contacto'];
    $fechaDeEntrada = $row['fecha_de_entrada'];
    $idRecepcionTipoRecepcion = $row['id_tipo_recepcion'];
    $otro = $row['otro'];
    $idParienteContacto = $row['id_pariente_contacto'];
    $nombres = $row['nombres'];
    $apellidos = $row['apellidos'];
    $evento = $row['evento'];
    $recuperado = $row['recuperado_base'];
    $idPaciente = $row['id_paciente'];
    $sexo = $row['sexo'];
    $fecha_nacimiento= $row['fecha_nacimiento'];
    $dui = $row['dui'];
    $direccion = $row['direccion'];
    $checado='';
    $checkEmergencia="";
    $checkDoctor="";
    $fechaCambiar = $fechaDeEntrada;
    $fechaMOD = explode(" ",$fechaCambiar);
    $FECHA = ED($fechaMOD[0]);
    $HORA = _hora_media_decode($fechaMOD[1]);
    if($recuperado == 1){
        $checado = "checked";
    }
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row" id="row1">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
				echo"<div class='ibox-title'>";
				?>
                <div class="ibox-content">
                    <form name="formulario_crear_recepcion" id="formulario_crear_recepcion" autocomplete='off'>
                        <!--load datables estructure html-->
                        <header>
                            <h3 style="color:#194160;"><i class="fa fa-user-md"></i> <b><?php echo $title;?></b></h3>
                        </header>

                        <div class="row focuss">
                            <hr>
                            <br>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group has-info single-line">
                                        <label> Doctor que atiende:</label>
                                        <select class="col-md-12 select usage sel1" id="doctor" name="doctor"
                                            style="width:100%; ">
                                            <option value="">Seleccione</option>
                                            <?php
                                            $sqld = "SELECT doctor.id_doctor, concat(doctor.nombres,' ',doctor.apellidos) as 'nombre_d' FROM doctor ";
                                            $resul=_query($sqld);
                                            while($doctr = _fetch_array($resul))
                                            {
                                                echo "<option value=".$doctr["id_doctor"];
                                                if($idDoctorRecepcion == $doctr['id_doctor']){
                                                    echo " selected ";
                                                }
                                                echo">".$doctr["nombre_d"]."</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group has-info single-line">
                                        <label> Doctor que refiere:</label>
                                        <select class="col-md-12 select usage sel1" id="doctor_refiere"
                                            name="doctor_refiere" style="width:100%; ">
                                            <option value="">Seleccione</option>
                                            <?php
                                            $sqld = "SELECT doctor.id_doctor, concat(doctor.nombres,' ',doctor.apellidos) as 'nombre_d' FROM doctor ";
                                            $resul=_query($sqld);
                                            while($doctr = _fetch_array($resul))
                                            {
                                                echo "<option value=".$doctr["id_doctor"];
                                                if($doctor_refiere == $doctr['id_doctor']){
                                                    echo " selected ";
                                                }
                                                echo">".$doctr["nombre_d"]."</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group has-info single-line">
                                        <label>Fecha de entrada: </label>
                                        <input type="text" name="hasta" id="fechaEntrada" class="form-control"
                                            value="<?php echo $FECHA?>">
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="form-group has-info single-line">
                                        <label>Paciente: </label>
                                        <input type="text" id="paciente" name="paciente" class="form-control usage sel"
                                            value="<?php echo Mayu($nombres." ".$apellidos); ?>"; readonly
                                            style="border-radius:0px">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group has-info single-line">
                                        <label> Tipo de recepcion: </label>
                                        <select class="form-control select" name="tipo_recepcion" id="tipo_recepcion" disabled>
                                            <?php
                                            $sql_tipo = "SELECT * FROM tipo_recepcion where activo = '1'";
                                            $query_tipo = _query($sql_tipo);
                                            while($row_tipo = _fetch_array($query_tipo)){
                                                echo "<option value='".$row_tipo["id_tipo_recepcion"]."'>".$row_tipo["descripcion"]."</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group has-info single-line">
                                        <label> Hora de entrada: </label>
                                        <input type="text" placeholder="HH:mm" class="form-control" id="hora_entrada"
                                            name="hora_entrada" autocomplete="off" value="<?php echo $HORA?>">
                                        <input type="text" placeholder="HH:mm" class="form-control"
                                            id="hora_entrada_replace" name="hora_entrada_replace" hidden readonly
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-3">
                                    <div class="form-group has-info single-line">
                                        <label>Fecha Nacimiento</label>
                                        <input type="text" class="form-control" id="fecha_nacimiento"
                                            value = "<?php echo $fecha_nacimiento; ?>" name="fecha_nacimiento" readonly disable>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group has-info single-line">
                                        <label>Sexo</label>
                                        <input type="text" class="form-control" id="sexo" name="sexo" value = "<?php echo $sexo; ?>" readonly disable>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group has-info single-line">
                                        <label>DUI</label>
                                        <input type="text" class="form-control" id="dui" name="dui" value = "<?php echo $dui; ?>" readonly disable>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group has-info single-line">
                                        <label>Direccion</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" value = "<?php echo $direccion; ?>" readonly
                                            disable>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group has-info single-line">
                                        <label>Descripcion del evento:</label>
                                        <br>
                                        <textarea name="descripcionEvento"
                                            placeholder="Ejemplo: presenta una fractura en el femur derecho."
                                            id="descripcionEvento" cols="119" rows="1"
                                            style="margin-top:6px;"><?php echo $evento; ?></textarea>
                                    </div>
                                </div>

                            </div>

                            <input type="hidden" name="" id='OriginalEstado'
                                value='<?php echo $idRecepcionTipoRecepcion; ?>'>
                            <input type="hidden" name="id_pestania_recepcion" id="id_pestania_recepcion" value="edited">
                            <div class="col-md-12">
                                <div class="form-actions"><br>
                                    <input type="hidden" name="id_recepcion_editar" id="id_recepcion_editar"
                                        value="<?php echo $idRecepcion; ?>">
                                    <input type="hidden" name="process" id="process" value="edit">
                                    <input type="submit" id="submit1" name="submit1" value="Guardar"
                                        class="btn btn-primary m-t-n-xs pull-right" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--div class='ibox-content'-->
            </div>
            <!--<div class='ibox float-e-margins' -->
        </div>
        <!--div class='col-lg-12'-->
    </div>
    <!--div class='row'-->
</div>
<!--div class='wrapper wrapper-content  animated fadeInRight'-->
</div>
<?php
    include("footer.php");
    echo" <script type='text/javascript' src='js/funciones/funciones_crear_recepcion.js'></script>";
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
function convertirFecha($fechaYHora){
    $fechaNueva = explode(" ",$fechaYHora);
    $fecha = $fechaNueva[0];
    $hora = $fechaNueva[1];

    $fechaMod = explode("-",$fecha);
    $fecha = $fechaMod[2]."-". $fechaMod[1]."-". $fechaMod[0];
    $horaMod = explode(":",$hora);
    $horaFinal="";
    if($horaMod[0] > 12){
        $horaMod[0] = $horaMod[0] - 13;
        $horaFinal = $horaMod[0].":".$horaMod[1]." PM";
    }
    if($horaMod[0] == 12){
        $horaFinal = $horaMod[0].":".$horaMod[1]." PM";
    }
    else{
        $horaFinal = $horaMod[0].":".$horaMod[1]." AM";
    }
    return $fecha." ".$horaFinal;
}

?>