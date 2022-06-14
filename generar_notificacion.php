<?php
	include ("_core.php");
	// Page setup
function initial()
{	
	$title='Generar Notificaciones';
	$_PAGE = array ();
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
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
	$now = date("d-m-Y");
	$ini = ED(sumar_dias($now,1));
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
			<?php  
			//permiso del script
			if ($links!='NOT' || $admin=='1' )
			{

			?>
			<div class='ibox-title'>
				<h3 style="color:#194160;"><i class="fa fa-send"></i> <b><?php echo $title;?></b></h3>
			</div>
				<div class="ibox-content">
                    <div class="row">   
                        <div class="form-group col-lg-6">
                            <label>Fecha</label>
							<input type="text" name="fecha" id="fecha" class="form-control datepicker" value="<?php echo $ini;?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-actions col-lg-6">
                            <input type="hidden" name="process" id="process" value="insert">
                            <a class="btn btn-primary pull-right" id="gener">Generar</a>
                        </div>
                    </div>
            	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->  
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->			
<?php    
	include("footer.php");
	echo" <script type='text/javascript' src='js/funciones/funciones_notificacion.js'></script>"; 
	} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	                         	   
}
function generar()
{
	$fecha = MD($_POST["fecha"]);
	$sql  = _query("SELECT r.id, p.id_paciente, p.notificaciones FROM reserva_cita as r, paciente as p WHERE r.fecha_cita='$fecha' AND  p.id_paciente=r.id_paciente");
	$n = _num_rows($sql);
	$i=0;
	_begin();
	while($row = _fetch_array($sql))
	{
		$id_cita = $row["id"];
		$id_paciente = $row["id_paciente"];
		$notificaciones = $row["notificaciones"];
		$table = "notificacion";
		$form_data = array(
			'fecha' => $fecha,
			'generado' => 1,
			'id_cita' => $id_cita,
			'id_paciente' => $id_paciente,
			'tipo' => $notificaciones,
			'enviado' => '1',
			'proceso' => ''
		);
		$sql_val = _query("SELECT * FROM notificacion WHERE fecha='$fecha' AND id_cita='$id_cita' AND id_paciente='$id_paciente' AND tipo='$notificaciones'");
		if(_num_rows($sql_val)>0)
		{
			$i++;
		}
		else
		{
			$insert = _insert($table, $form_data);
			if($insert)
			{
				$i++;
			}
		}
	}
	if($n == $i)
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
