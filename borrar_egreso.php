<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_egreso.php";
	$links=permission_usr($id_user,$filename);

	$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
	$datos_moneda = _fetch_array($sql0);
	$simbolo = $datos_moneda["simbolo"];  
	$moneda = $datos_moneda["moneda"];

	$id_egreso = $_REQUEST['id_egreso'];

	$sql="SELECT * FROM egreso WHERE id_egreso='$id_egreso'";
	$result = _query($sql);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Egreso</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
			<?php 
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
				<table class="table table-bordered" style="width: 100%;">
					<tr class="bg-success">
						<th>Campo</th>
						<th>Descripción</th>
					</tr>
					<?php
						$row = _fetch_array ($result);
						$id_egreso=$row["id_egreso"];
						$descripcion = $row["responsable"];
						$precio = $row["total"];
						//$fecha_reg = ED($row["fecha_registro"]);
						echo"<tr><td>Id egreso</td><td>".$id_egreso."</td></tr>";
						echo"<tr><td>Responsable</td><td>".$descripcion."</td></tr>";											
						echo"<tr><td>Total</td><td>".$simbolo."".number_format($precio,2,".",",")."</td></tr>";											
					?>
				</table>
			</div>
		</div>
		<?php 
		echo "<input type='hidden' name='id_egreso' id='id_egreso' value='$id_egreso'>";
		?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnDelete">Borrar</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
<!--/modal-footer -->
<?php
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	
}
function deleted() {
	$id_egreso = $_POST ['id_egreso'];
	$table = 'egreso';
	_begin();
	$where_clause = "id_egreso='" . $id_egreso . "'";
	$delete = _delete ( $table, $where_clause );
	if ($delete)
	{
		_commit();
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Egreso eliminado correctamente';
	}
	else 
	{
		_rollback();
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Egreso no pudo ser eliminado';
	}
	echo json_encode ($xdatos);
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else {
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formDelete' :
				initial();
				break;
			case 'deleted' :
				deleted();
				break;
		}
	}
}

?>
