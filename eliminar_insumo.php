<?php
	include ("_core.php");
	// Page setup
	function initial(){
    $title='Eliminar insumo';
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
	$_PAGE ['links'] .= '<link href="css/plugins/tour/bootstrap-tour.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
	

	$id_insumo=$_GET['id_insumo'];
    $id_hospitalizacion=$_GET['id_hospitalizacion'];
    echo $id_hospitalizacion;

    $sql_insumo="SELECT  ie.id_recepcion, p.descripcion AS producto, sh.descripcion AS servicio, ie.cantidad FROM insumos_emergencia AS ie LEFT JOIN ".EXTERNAL.".producto AS
    p ON p.id_producto=ie.id_producto LEFT JOIN  ".EXTERNAL.".servicios_hospitalarios AS 
    sh on ie.id_servicio=sh.id_servicio WHERE ie.id_insumo=$id_insumo";
    $query_insumo=_query($sql_insumo);
    $row_insumo=_fetch_array($query_insumo);
    $producto=$row_insumo['producto'];
    $servicio=$row_insumo['servicio'];
    $id_recepcion=$row_insumo['id_recepcion'];
    $producto_servicio=$producto.''.$servicio;//si producto=="" no va a aparecer y lo mismo para servicio
	//echo $id_recepcion;
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

    
    //echo $id_recepcion;
	
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
				$filename='agregar_paciente.php';
				$link=permission_usr($id_user,$filename);
				
				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 style="color:#194160;"><i class="fa fa-user"></i> <b><?php echo $title;?></b></h3>
					</header>
					<section>
                    <div class="alert alert-danger" role="alert">
                        <center><h1>¿Esta seguro de eliminar este insumo? <br> <strong> <?php echo $producto_servicio ?><strong></h1></certer>
                        <div class="btn-group" role="group" aria-label="...">
                                <a href="eliminar_insumo.php?&process=eliminar&id_insumo=<?php echo $id_insumo?>&id_hospitalizacion=<?php echo $id_hospitalizacion ?>" class="btn btn-default">SI</a>
                                <a href="lista_insumos_hospitalizacion.php?&id_hospitalizacion=<?php echo $id_hospitalizacion ?>" class="btn btn-default">NO</a>
                        </div>
                    </div>
                        <php?

                        ?>
                            

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

echo" <script type='text/javascript' src='js/funciones/funciones_cuenta_insumos_hospitalizacion.js'></script>"; 
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	  

    }
function Eliminar(){
    $id_insumo=$_GET['id_insumo'];
    $id_hospitalizacion=$_GET['id_hospitalizacion'];
    $table='insumos_emergencia';
    $where_clause='id_insumo='.$id_insumo;
    $delete=_delete($table, $where_clause);
    if($delete){
        header('Location: '.'lista_insumos_hospitalizacion.php?id_hospitalizacion='.$id_hospitalizacion.'');
    }
}

    if(!isset($_REQUEST['process'])){
        initial();
    }else{
        echo $_REQUEST['process'];
        switch($_REQUEST['process']){
            case 'eliminar':
                Eliminar();
                break;
        }
    }
?>