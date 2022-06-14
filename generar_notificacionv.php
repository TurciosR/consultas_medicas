<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
	$title='Generar Notificaciones Varias';
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
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";

	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename="admin_notificacionv.php";
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
                         <h3 style="color:#194160;"><i class="fa fa-send"></i> <b><?php echo $title;?></b></h3>
                    </div>
                    <div class="ibox-content">
                        <div class="col-lg-12" id="forma_pago">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class='text-success'>Tipo de Notificación</h3></div>
									<div class="panel-body">	
										<div class="row">
											<div class="col-lg-6 form-group">
												<label>Tipo de Notificación</label>
												<select class="form-control select" name="tipo_m" id="tipo_m">
													<option value="">Seleccione</option>
													<option value="Teléfono">Teléfono</option>
													<option value="Correo">Correo Electrónico</option>
												</select>
											</div>
											<div class="col-lg-6 form-group">
												<label>Fecha</label>
												<input type="text" name="fecha" id="fecha" class="form-control datepicker" value="<?php echo date("d-m-Y"); ?>">
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 form-group">
												<label>Texto del Mensaje</label>
												<textarea name="mensaje" id="mensaje" class="form-control" cols='4' placeholder="Utilice {paciente} para incluir el nombre del paciente en el texto del mensaje. Ej: Hola {paciente}, buenos dias"></textarea>
											</div>
										</div>
									</div>
	                        </div>
						</div>
	             	    <div class="col-lg-12">
							<div class="panel panel-default"><!-- panel RECIBO -->
								<div class="panel-heading"><h3 class='text-success'>Lista de Destinantarios</h3></div>
									<div class="panel-body">
										<div class="row">
										  	<div class="col-lg-12">
												<table class="table table-condensed table-striped">
										        	<thead class="thead-inverse">
														<tr class="bg-success">
											        		<th>N°</th>
											        		<th>Paciente</th>
											        		<th>Contacto</th>
											        		<th class='text-center'><p><div class='checkbox i-checks' id="all"><label><input id='chk' name='chk' type='checkbox'></label></div></p></th>
											        	</tr>
										        	</thead>
										        	<tbody id="datos">
										        	</tbody>
			       								</table>
											</div>
										</div>
	                        	</div>
							</div>
	                	</div>
						<input type="hidden" name="process" id="process" value="insert">
						<input type="hidden" name="sms" id="sms">
						<input type="hidden" id="tipo" value="correo">
		                <div class="title-action" id='botones'>
							<a id="btn_fin" name="btn_fin" class="btn btn-primary"> Guardar</a>
						</div>
            	</div>
            </div>
            <div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
			<div class='modal-dialog modal-sm'>
				<div class='modal-content'></div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->	
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_notificacionv.js'></script>";

} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function generar()
{
	$fecha = MD($_POST["fecha"]);
	$mensaje = $_POST["mensaje"];
	$tipo = $_POST["tipo"];
	$datos = $_POST["datos"];
	$cuantos = $_POST["cuantos"];
		
	$n=0;
	$lista = explode("|", $datos);
	_begin();
	for($i=0; $i<$cuantos; $i++)
	{
		$data = explode(",", $lista[$i]);
		$id_paciente = $data[0];
		$contacto = $data[1];
		$table = "notificaciones_varias";
		$form_data = array(
			'id_paciente' => $id_paciente,
			'contacto' => $contacto,
			'fecha' => $fecha,
			'tipo' => $tipo,
			'mensaje' => $mensaje,
			'enviado' => 0
			);
		$sql_val = _query("SELECT * FROM notificaciones_varias WHERE fecha='$fecha' AND id_paciente='$id_paciente' AND tipo='$tipo' AND mensaje='$mensaje' AND contacto='$contacto'");
		if(_num_rows($sql_val)>0)
		{
			$n++;
		}
		else
		{
			$insert = _insert($table, $form_data);
			if($insert)
			{
				$n++;
			}
		}
	}
	if($cuantos == $n)
	{
		_commit();
		$xdata["typeinfo"]="Success";
		$xdata["msg"]="Notificaciones generadas con exito!!!";

	}
	else
	{
		_rollback();
		$xdata["typeinfo"]="Error";
		$xdata["msg"]="Notificaciones no pudieron ser generadas!!!";

	}
	echo json_encode($xdata);

}
if(!isset($_POST['process'])){
	initial();
}
else
{
if(isset($_POST['process']))
{
switch ($_POST['process']) {
	case 'generar':
		generar();
		break;
	}
}
}

?>
