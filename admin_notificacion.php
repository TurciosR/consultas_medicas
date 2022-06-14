<?php
	include ("_core.php");
	// Page setup
function initial()
{	
	$title='Notificaciones por Enviar';
	//$title='Administrar Notificaciones';
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
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
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
				$now = date("Y-m-d");	
				echo"<div class='ibox-title'>";
				$filename = "generar_notificacion.php";
				$links=permission_usr($id_user, $filename);
				if($links != 'NOT' || $admin = '1')
					echo "<a class='btn btn-primary' role='button' href='generar_notificacion.php'><i class='fa fa-plus icon-large'></i> Generar Notificaciones</a>";
				echo "</div>";
				?>
				<div class="ibox-content">
					
					<header>
						<h3 style="color:#194160;"><i class="fa fa-send"></i> <b><?php echo $title;?></b></h3>
					</header>
					<div class="row">
						<div class="col-lg-4">
							<div class="form-group has-info">
								<label>FECHA</label>
								<input type="text" name="desde" id="desde" class="form-control datepicker" value="<?php echo $ini;?>">
							</div>
						</div>
						<div class="col-lg-8">
							<div class="form-group has-info"><br>
								<button class="btn btn-primary" id='buscar'><i class="fa fa-search"></i> Mostrar</button>
							</div>
						</div>
					</div>

					<section>
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>Id</th>
									<th>Tipo de Notificación</th>	
									<th>Cantidad</th>									
									<th>Disponible</th>									
									<th>Acci&oacute;n</th>
								</tr>
							</thead>
							<tbody id='refill'> 
							</tbody>		
						</table>
						 <input type="hidden" name="autosave" id="autosave" value="false-0">	
					</section>   
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-sm'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->  
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->			
<?php    
	include("footer.php");
		echo" <script type='text/javascript' src='js/funciones/funciones_notificacion.js'></script>"; 
	} //permiso del script
	else 
	{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	
}
initial();                         	   
?>
