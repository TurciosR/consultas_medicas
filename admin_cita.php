<?php
	include ("_core.php");
	// Page setup
	$title='Administrar Citas';
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
	$id_doctor = $_SESSION['id_doctor'];

	$mes = date("m");
	$anio = date("Y");
	$ini =	"01-".$mes."-".$anio;
	$ult = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
	$fin = $ult."-".$mes."-".$anio;

	$now = date("Y-m-d");
    $query=_query("SELECT id FROM reserva_cita WHERE fecha_cita<'$now' AND estado<6");
    $num = _num_rows($query);
    $i=0;
    _begin();
    if($num>0)
    {
        while($row = _fetch_array($query))
        {
            $form_data = array(
                'estado' => 6
            );
            $table = "reserva_cita";
            $where = "id='".$row["id"]."'";
            $inser = _update($table, $form_data,$where);
            if($inser)
            {
                $i++;
            }
        }
    }  
    if($num ==$i)
    {
        _commit();
    }
    else
    {
        _rollback();
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
				$filename='agregar_cita1.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
					echo "<a href='agregar_cita1.php' class='btn btn-primary' role='button' id='btn_citass'><i class='fa fa-plus icon-large'></i> Agregar Cita</a>";
				
				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 style="color:#194160;"><i class="fa fa-stethoscope"></i> <b><?php echo $title;?></b></h3>
					</header>
					<div class="row">
						<div class="col-lg-5">
							<div class="form-group has-info">
								<label>DESDE</label>
								<input type="text" name="ini" id="ini" class="form-control datepicker" value="<?php echo $ini;?>">
							</div>
						</div>
						<div class="col-lg-5">
							<div class="form-group has-info">
								<label>HASTA</label>
								<input type="text" name="fin" id="fin" class="form-control datepicker" value="<?php echo $fin;?>">
							</div>
						</div>
						<div class="col-lg-2">
							<div class="form-group has-info"><br>
								<button class="btn btn-primary pull-right" id='buscar'><i class="fa fa-search"></i> Mostrar</button>
							</div>
						</div>
					</div><br><br>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable2">
							<thead>
								<tr>
									<th>Id</th>
									<th>Fecha</th>
									<th>Hora</th>
									<th>Paciente</th>
									<th>Médico</th>
									<th>Consultorio</th>
									<th>Estado</th>
									<th>Acción</th>
								</tr>
							</thead>
							<tbody>
							</tbody>		
						</table>
						<input type="hidden" name="id_doctor" id="id_doctor" value="<?php echo $id_doctor; ?>">
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

echo" <script type='text/javascript' src='js/funciones/funciones_cita.js'></script>"; 
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	                         	     
?>
