<?php
	include ("_core.php");
	// Page setup
	$title='Emergencia';
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
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
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
					<!--load datables estructure html-->
					<header>
						<h3 style="color:#194160;"><i class="fa fa-user-md"></i> <b><?php echo $title;?></b></h3>
					</header>
					<div class="row">
						<div class="col-lg-5">
							<div class="form-group has-info">
								<label>DESDE</label>
								<input type="text" name="desde" id="desdeRecepcion" class="form-control datepicker" value="<?php echo $hoy;?>">
							</div>
						</div>
						<div class="col-lg-5">
							<div class="form-group has-info">
								<label>HASTA</label>
								<input type="text" name="hasta" id="hastaRecepcion" class="form-control datepicker" value="<?php echo $hoy
								;?>">
							</div>
						</div>
						<div class="col-lg-2">
							<div class="form-group has-info"><br>
								<button class="btn btn-primary pull-right" id='buscarRecepcion'><i class="fa fa-search"></i> Mostrar</button>
							</div>
						</div>
					</div><br><br>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editableRecepcion">
							<thead>
								<tr>
									<th>Id Recep</th>
									<th>Paciente</th>
									<th>Doctor Ref</th>
									<th>Fecha/Hora</th>
									<th>Evento</th>
									<th>Estado</th>
									<th>Acci&oacute;n</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>

					</section>

					<!-- MODAL PARA DETALLE-->
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<!-- MODAL PARA DETALLE-->

					<!-- MODAL PARA BORRAR-->
					<div class='modal fade' id='deleteModal'  role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content '></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<!-- MODAL PARA BORRAR-->
					<div class='modal fade bd-example-modal-lg' id='realizarModal1'  role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog modal-lg'>
							<div class='modal-content'>
										
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div>
					<div class="modal fade" id='hospitalizacionModal'  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class='modal-dialog modal-md'>
							<div class='modal-content modal-md'>
										
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div>
					<div class="modal fade" id='transferenciaModal'  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class='modal-dialog modal-md'>
							<div class='modal-content modal-md'>
										
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div>
               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
</div>
<?php
	include("footer.php");
	echo" <script type='text/javascript' src='js/funciones/admin_emergencia.js'></script>";
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
?>
