<?php
include_once "_core.php";
function initial() {
	$title='Constancias';
	$_PAGE = array ();
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";

	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
?>
<div class="row wrapper border-bottom white-bg page-heading"></div>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <?php
               	 		if ($links!='NOT' || $admin=='1' ){
               		?>
                <div class="ibox-title">
                    <h3 style="color:#194160;"><i class="fa fa-file-pdf-o"></i> <b><?php echo $title;?></b></h3>
                </div>
                <div class="ibox-content">
                    <div class="col-lg-12" id="forma_pago">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class='text-success'>Tipo de Constancia</h3>
                            </div>
                            <div class="panel-body">
							<div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label>Tipo</label>
                                        <select class="form-control select" name="forma" id="forma" disabled>
                                            <option value="">Seleccione</option>
                                            <option value="constancia" Selected>Constancia Médica</option>
                                            <option value="defuncion">Constancia de Defunción</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label>Fecha de expedición:</label>
                                            <input type='text' class='datepicker form-control' id='fecha' name='fecha'
                                                value='<?php echo date('d-m-Y');?>'>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="div_pa">
                                    <div class="col-md-4">
                                        <div class="form-group has-info">
                                            <label>Buscar Paciente</label>
                                            <input type="text" id="buscar_paciente" name="buscar_paciente"
                                                class="form-control"
                                                placeholder="Ingrese nombre(s) o apellido(s) para buscar">
                                            <input type="hidden" name="id_paciente" id="id_paciente">
                                            <label id="paciente"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2"><br>
                                        <a class="btn btn-primary" data-target="#viewModal" data-toggle="modal"
                                            data-refresh="true" href="agregar_paciente1.php"><i class="fa fa-user"></i>
                                            Nuevo</a>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>Doctor</label>
                                        <br>
                                        <select name="id_doctor" id="id_doctor" style="width:100%" class="select">
                                            <option value="" selected>Seleccione</option>
                                            <?php
													$sql_doctor = "SELECT * FROM doctor";
													$query_doctor = _query($sql_doctor);
													
													while($row_doctor = _fetch_array($query_doctor)){
														$nombre_doctor = $row_doctor['nombres']." ".$row_doctor['apellidos'];
														echo "<option value='".$row_doctor['id_doctor']."'>";
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
                                        <input type="text" name="padecimiento" id="padecimiento" class="form-control">
                                    </div>
                                    <!--<div class="col-lg-6">
												<label>Tratamiento</label>
												<input type="text" name="tratamiento" id="tratamiento" class="form-control">
											</div>-->
                                    <div class="col-lg-6">
                                        <label>Reposo (Días)</label>
                                        <input type="text" name="reposo" id="reposo" class="form-control numeric">
                                    </div>
                                </div>
                                <div class="row defu" hidden>
                                    <div class="col-lg-6">
                                        <label>Fecha de Defunción</label>
                                        <input type="text" name="fecha_d" id="fecha_d" class="form-control datepicker"
                                            value="<?php echo date("d-m-Y"); ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Hora de Defunción</label>
                                        <input type="text" name="hora" id="hora" class="form-control timepicker">
                                    </div>
                                </div>
                                <div class="row defu" hidden><br>
                                    <div class="col-lg-6 form-group">
                                        <label>Lugar de Defunción</label>
                                        <input type="text" name="lugar" id="lugar" class="form-control">
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>Causa de la Defunción</label>
                                        <input type="text" name="causa" id="causa" class="form-control">
                                    </div>
                                </div>
                                <div class="row otr" hidden><br>
                                    <div class="col-lg-12 form-group">
                                        <label class="control-label">Texto de la constancia</label>
                                        <p class="alert alert-info">Utilice el comodin <b>{paciente}</b> para insertar
                                            el nombre del paciente y su edad. Ej: Juan Perez con 22 años de edad</p>
                                        <textarea name="texto" id="texto" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="process" id="process" value="insert">
                    <div class="title-action" id='botones'>
                        <a id="btn_fin" name="btn_fin" class="btn btn-primary"><i class="fa fa-check"></i> Guardar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
            aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- MODAL PARA DETALLE-->
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_constancia.js'></script>";

} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function insert()
{

	$tipo= $_POST['tipo'];
	$fecha= MD($_POST['fecha']);
	$id_paciente= $_POST['id_paciente'];
	$padecimiento= $_POST['padecimiento'];
	$tratamiento= $_POST['tratamiento'];
	$reposo= $_POST['reposo'];
	$fecha_d= MD($_POST['fecha_d']);
	$hora= $_POST['hora'];
	$lugar = $_POST['lugar'];
	$texto = $_POST['texto'];
	$causa = $_POST['causa'];
	$id_doctor = $_POST['id_doctor'];
	$hora_d = "";
	if($hora != "")
	{
		list($dato, $letra) = explode(" ", $hora);
	    list($h, $m) = explode(":", $dato);
	    if($letra=="PM")
	    {
	        if($h<12)
	        {
	            $h+=12;
	        }
	    }
		$hora_d = "$h:$m:00";
	}
	$table = 'constancia';
	if($tipo == "constancia")
	{
		$form_data = array(
			'id_paciente' => $id_paciente,
			'fecha' => $fecha,
			'padecimiento' => $padecimiento,
			'tratamiento' => $tratamiento,
			'reposo' => $reposo,
			'tipo' => $tipo,
			'fecha_d' => date("Y:m:d"),
			'hora_d' => date("H:i:s"),
			'lugar' => '',
			'id_doctor' => $id_doctor
		);
	}
	else if($tipo == "defuncion")
	{
		$form_data = array(
			'id_paciente' => $id_paciente,
			'fecha' => $fecha,
			'padecimiento' => $causa,
			'fecha_d' => $fecha_d,
			'hora_d' => $hora_d,
			'lugar' => $lugar,
			'tipo' => $tipo,
			'tratamiento' => '',
			'reposo' => 0,
			'id_doctor' => $id_doctor

		);
	}
	else
	{
		$form_data = array(
			'id_paciente' => $id_paciente,
			'fecha' => $fecha,
			'padecimiento' => $texto,
			'tipo' => $tipo,
			'tratamiento' => '',
			'reposo' => 0,
			'fecha_d' => date("Y:m:d"),
			'hora_d' => date("H:i:s"),
			'lugar' => '',
			'id_doctor' => $id_doctor
		);
	}
	$insert = _insert($table, $form_data);
	if($insert)
	{
		$id_constancia = _insert_id();
	   	$xdatos['typeinfo']='Success';
       	$xdatos['msg']='Constancia generada con exito!';
       	$xdatos['id_constancia'] = $id_constancia;
	}
	else
	{
		$xdatos['typeinfo']='Error';
       	$xdatos['msg']='Constancia no pudo ser generada !';
	}
	echo json_encode($xdatos);
}
if(!isset($_POST['process'])){
	initial();
}
else
{
if(isset($_POST['process']))
{
switch ($_POST['process']) {
	case 'insert':
		insert();
		break;
	}
}
}
?>