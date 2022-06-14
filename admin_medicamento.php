<?php
	include ("_core.php");
	// Page setup
	$title='Administrar Medicamentos';
	$_PAGE = array ();
	$_PAGE ['title'] =$title;
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
	$_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
	
 	$sql="SELECT * FROM medicamento";
	$result=_query($sql);
	$count=_num_rows($result);
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	
	//mysql_query("SET NAMES 'utf8'");
?>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php  
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
				echo"<div class='ibox-title'>";
				$filename='agregar_medicamento.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
					echo "<a href='agregar_medicamento.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Medicamento</a>";
				
				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 style="color:#194160;"><i class="fa fa-medkit"></i> <b><?php echo $title;?></b></h3>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editablea">
							<thead>
								<tr>
									<th class="col-lg-1">Id</th>
									<th class="col-lg-3">Descripción</th>
									<th class="col-lg-3">Laboratorio</th>
									<th class="col-lg-2">Presentación</th>
									<th class="col-lg-1">Acción</th>
								</tr>
							</thead>
							<tbody> 
							</tbody>		
						</table>
						 <input type="hidden" name="autosave" id="autosave" value="false-0">	
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
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content '></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					<!-- MODAL PARA BORRAR-->

               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->  
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->			
</div>		
<?php    
include("footer.php");

echo" <script type='text/javascript' src='js/funciones/funciones_medicamento.js'></script>"; 
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	                         	     
?>
