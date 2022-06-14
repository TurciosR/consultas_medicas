<?php
include ("_conexion.php");
include ("_session.php");
include ("num2letras.php");
function inicio() 
{
	$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
	$datos_moneda = _fetch_array($sql0);
	$simbolo = $datos_moneda["simbolo"];  
	$moneda = $datos_moneda["moneda"];

	$id_credito = $_REQUEST['id_credito'];
	$sql="SELECT * FROM detalle_credito WHERE id_credito='$id_credito' ";
	$sql1="SELECT * FROM credito WHERE id_credito='$id_credito'";
	$result = _query( $sql);
	$result1 = _query( $sql1);
	$row1=_fetch_array($result1);
	$count = _num_rows( $result );

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Detalle de Crédito</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
					<div class="widget-content">
						<div class="row">
							<div class="form-group col-md-6">
								<label class="control-label">Cliente: </label>
									<label ><?php echo $row1["cliente"]; ?></label>
							</div>
							<div class="form-group col-md-6">	
								<label class="control-label">Fecha: </label>
									<label ><?php echo ED($row1["fecha"]); ?></label>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label class="control-label">Tipo de crédito: </label>
									<label ><?php echo $row1["tipo"]; ?></label>
							</div>
						</div>
						<?php if($row1["tipo"]!="PERSONAL"){?>
						<div class="row">
							<div class="form-group col-md-4">
								<label class="control-label">Institución: </label><br>
									<label ><?php echo $row1["institucion"]; ?></label>
							</div>
							<div class="form-group col-md-4">
								<label class="control-label">Tipo documento: </label><br>
									<label ><?php echo $row1["documento"]; ?></label>
							</div>
							<div class="form-group col-md-4">
								<label class="control-label">Número: </label><br>
									<label ><?php echo $row1["numero_doc"]; ?></label>
							</div>
						</div>
						<?php }?>
					</div>
					<div class="widget-content">
						<table class="table table-bordered table-checkable" border>
							<tr class="bg-success">
								<th class="col-md-1">Id</th>
								<th class="col-md-5">Descripci&oacute;n</th>
								<th class="col-md-2">Precio</th>
								<th class="col-md-2">Cantidad</th>
								<th class="col-md-2">Subtotal</th>
							</tr>
							<?php 
								$cantidad = 0;
								$precio = 0.0;
								$i=1;
								while($row = _fetch_array($result))
								{
									$producto_all = _query("SELECT * FROM servicio WHERE id_servicio='$row[id_servicio]' ");
									$producto_detail = _fetch_array($producto_all);
									echo "<tr>
											<td>".$row["id_servicio"]."</td>
											<td>".$producto_detail["descripcion"]."</td>
											<td>".$simbolo."".number_format($row["precio"],2,".",",")."</td>
											<td>".$row["cantidad"]."</td><td>".$simbolo."".number_format($row["subtotal"],2,".",",")."</td>
										  </tr>";
									$cantidad = $cantidad + $row["cantidad"];
									$precio = $precio + $row["subtotal"];
									$i++;
								}
							?>
							<tr>
								<td colspan="4" style="text-align: center;">Total</td>
								<td><?php echo $simbolo."".number_format($precio,2,".",","); ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<div class="modal-footer">
	

<?php

	echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
	</div><!--/modal-footer -->";	
	/*} //permiso del script
	else {
		$mensaje="No tiene permiso para este modulo.";
		echo "<div id='content'><div class='container'><div class='row'>
			<div class='alert alert-warning'><h5 class='text-success'>$mensaje</h5></div>
			</div></div></div>";
	}//permiso del script	
*/}
inicio();
?>
