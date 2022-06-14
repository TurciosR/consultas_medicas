<?php
	include ("_core.php");
	// Page setup
	$title='Estado de cuenta del paciente';
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
	$_PAGE['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
	

	$id_hospitalizacion=$_GET['id_hospitalizacion'];
 	$sql_hospitalizacion="SELECT h.id_recepcion, 
	 CONCAT(p.nombres, ' ', p.apellidos) AS nombre_paciente 
	 FROM hospitalizacion AS h 
	 LEFT JOIN recepcion AS r on h.id_recepcion=r.id_recepcion
	  LEFT JOIN paciente AS p on r.id_paciente_recepcion=p.id_paciente  
	  WHERE h.id_hospitalizacion=$id_hospitalizacion";
	$query_hospitalizacion=_query($sql_hospitalizacion);
	$row_hospitalizacion=_fetch_array($query_hospitalizacion);
	$id_recepcion=$row_hospitalizacion['id_recepcion'];
	$nombre_paciente=$row_hospitalizacion['nombre_paciente'];

	//obteniendo el id factura y la referencia de la factura para listar los productos que se le han aplicado
	$sql_factura="SELECT id_factura, numero_ref  FROM ".EXTERNAL.".factura AS f WHERE f.id_recepcion=$id_recepcion  AND f.finalizada != 1";
	$query_factura=_query($sql_factura);
	
	$no_referencia="";
	$id_factura="";
	if(_num_rows($query_factura)>0){
		$row_factura=_fetch_array($query_factura);
		$id_factura=$row_factura['id_factura'];
		$no_referencia=$row_factura['numero_ref'];


	}
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
					<div class="alert alert-success" role="alert">
						<div class="row">
							<div class="col-md-6">
								<h4 class="alert-heading">PACIENTE: <?php echo $nombre_paciente ?></h4>
							</div>
							<div class="col-md-6">
								<h4 class="alert-heading">REFERENCIA N#: <?php echo $no_referencia ?></h4>
							</div>
						</div>
					</div>
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="editable2">
								<thead>
									<tr>
										<th class="col-lg-1">Id</th>
										<th class="col-lg-3">producto</th>
										<th class="col-lg-1">Precio de Venta</th>
										<th class="col-lg-1">cantidad</th>										
										<th class="col-lg-1">total</th>
									</tr>
								</thead>
								<tbody> 
									<?php
										if($id_factura!=''){
										//obteniendo el listado de detalles de facturas de el cliente hospitalizado
										$sql_detalle_factura="SELECT fd.id_factura_detalle, p.descripcion AS producto,
										fd.precio_venta, fd.cantidad, fd.subtotal FROM ".EXTERNAL.".factura_detalle AS fd LEFT JOIN 
										".EXTERNAL.".producto AS p on fd.id_prod_serv=p.id_producto LEFT JOIN ".EXTERNAL.".factura 
										AS f ON fd.id_factura = f.id_factura WHERE fd.servicio=0 AND 
										fd.id_factura=$id_factura 
										UNION ALL 
										SELECT fd.id_factura_detalle, s.servicio AS producto,  fd.cantidad, 
										fd.precio_venta, fd.subtotal FROM ".EXTERNAL.".factura_detalle AS fd 
										LEFT JOIN ".EXTERNAL.".servicios AS s ON fd.id_prod_serv=s.id_servicio 
										LEFT JOIN ".EXTERNAL.".factura AS f ON fd.id_factura = f.id_factura 
										WHERE fd.servicio =1 AND fd.id_factura=$id_factura";

										$query_detalle_factura=_query($sql_detalle_factura);
										while($row_detalle_factura=_fetch_array($query_detalle_factura)){
											$id=$row_detalle_factura['id_factura_detalle'];
											$producto=$row_detalle_factura['producto'];
											$precio_venta=$row_detalle_factura['precio_venta'];
											$cantidad=$row_detalle_factura['cantidad'];
											$total=$row_detalle_factura['subtotal'];
		


											$body="<tr>
												<td>$id</td>
												<td>$producto</td>
												<td>$ $precio_venta</td>
												<td> $cantidad</td>
												<td>$ $total</td>
												</tr>";

											echo $body;
										}

										$sql_total="SELECT ROUND(SUM(prod.subtotal), 2) AS total FROM 
										(SELECT fd.id_factura_detalle, p.descripcion AS producto, 
										 precio_venta, fd.cantidad, fd.subtotal 
										FROM ".EXTERNAL.".factura_detalle AS fd LEFT JOIN
										".EXTERNAL.".producto AS p on fd.id_prod_serv=p.id_producto 
										 LEFT JOIN ".EXTERNAL.".factura AS f ON fd.id_factura = f.id_factura 
										 WHERE fd.servicio=0 AND fd.id_factura=$id_factura 
										 UNION ALL 
										 SELECT fd.id_factura_detalle, 
										 s.servicio AS producto, fd.cantidad, 
										 fd.precio_venta, fd.subtotal 
										 FROM ".EXTERNAL.".factura_detalle AS fd 
										 LEFT JOIN ".EXTERNAL.".servicios AS s 
										 ON fd.id_prod_serv=s.id_servicio 
										 LEFT JOIN ".EXTERNAL.".factura AS f ON fd.id_factura = f.id_factura 
										 WHERE fd.servicio =1 AND fd.id_factura=$id_factura) AS prod";
										$query_total=_query($sql_total);
										$row_total=_fetch_array($query_total);
										$suma_total=$row_total['total'];
										echo "<tr>
												<td colspan=\"2\">Total<td>
												<td>$ $suma_total</td>
											</tr>";
										}else{
											echo '<div class="alert alert-success" role="alert">
											<div class="row">
													<h4 class="alert-heading">No se le ha aplicado ningun producto a este paciente</h4>
											</div>
										</div>';
										}

									?>
									
								</tbody>		
							</table>
						</div>
						<input type="hidden" name="referencia" id="referencia" value="<?php echo $no_referencia ?>">
						 <input type="hidden" name="id_factura" id="id_factura" value="<?php echo $id_factura ?>">
						 <input type="hidden" name="nombre_paciente" id="nombre_paciente" value="<?php echo $nombre_paciente ?>">
						 <input type="hidden" name="autosave" id="autosave" value="false-0">	
					</section>   
					<div class="row">

						<div class="col-lg-6">
							<p>
								<a href="admin_hospitalizaciones.php" class="btn btn-primary" role="button">Regresar</a> 
							</p>
						</div>
						<div class="col-lg-6">
							<a href="" id="imprimir_cuenta" class="btn btn-primary" role="button">
								imprimir cuenta
							</a>
						</div>
					</div>


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
?>
