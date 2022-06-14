<?php
	include ("_core.php");
	// Page setup
	$title='Expediente';
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
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
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
	//mysql_query("SET NAMES 'utf8'");

	$id = $_REQUEST["id_paciente"];

	$fin =	date("Y-m-d");
	$ini = restar_meses($fin,11);
	$quer = _query("SELECT expediente FROM paciente WHERE id_paciente='$id'");
	$dato = _fetch_array($quer);
	$expediente = $dato["expediente"];
	$len = strlen((string)$expediente);
	$ini = ED($ini);
	$fin = ED($fin);
	$fill = 7 - $len;
	if($fill <0)
		$fill = 0;
	$expe = zfill($expediente, $fill);
	
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
						<h3 style="color:#194160;"><b>Exp. <?php echo $expe; ?></b><b class="pull-right"><?php echo buscar($id); ?></b></h3>
					</header>
					<div class="row"><br>
						<div class="col-lg-5">
							<div class="form-group has-info">
								<label>DESDE</label>
								<input type="text" name="desde" id="desde" class="form-control datepicker" value="<?php echo $ini;?>">
							</div>
						</div>
						<div class="col-lg-5">
							<div class="form-group has-info">
								<label>HASTA</label>
								<input type="text" name="hasta" id="hasta" class="form-control datepicker" value="<?php echo $fin;?>">
							</div>
						</div>
						<div class="col-lg-2">
							<div class="form-group has-info"><br>
								<a class="btn btn-primary" id='buscar'><i class="fa fa-search"></i> Mostrar</a>
							</div>
						</div>
						<!--<div class="col-lg-2">
							<div class="form-group has-info"><br>
								<a class="btn btn-primary" href="expediente_pdf.php?id_paciente=<?php echo $id;?>" target="_blank"><i class="fa fa-print"></i> Imprimir</a>
							</div>
						</div>-->
					</div><br><br>
					<section id="refill">
						
					</section>   
					<input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $id; ?>">	
					
               	</div><!--div class='ibox-content'-->
               	<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content modal-sm'></div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->	
				<!-- MODAL PARA BORRAR-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->  
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->			
</div>		
<?php    
include("footer.php");

echo" <script type='text/javascript' src='js/funciones/funciones_expediente_dt.js'></script>"; 
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	                         	     
?>
