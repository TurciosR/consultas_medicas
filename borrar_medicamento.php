<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_medicamento.php";
	$links=permission_usr($id_user,$filename);

	$id_medicamento = $_REQUEST['id_medicamento'];

	$sql="SELECT * FROM medicamento WHERE id_medicamento='$id_medicamento'";
	$result = _query($sql);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Medicamento</h4>
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
						<th class="col-lg-3">Campo</th>
						<th class="col-lg-9">Descripción</th>
					</tr>
					<?php
						$row = _fetch_array ($result);
						$id_medicamento=$row["id_medicamento"];
						$descripcion=$row['descripcion'];
						$laboratorio = $row["laboratorio"];
						$principio = $row["principio"];
						$presentacion = $row["presentacion"];
						
						//$fecha_reg = ED($row["fecha_registro"]);
						echo"<tr><td>Id</td><td>".$id_medicamento."</td></tr>";
						echo"<tr><td>Descripción</td><td>".$descripcion."</td></tr>";
						echo"<tr><td>Laboratorio</td><td>".$laboratorio."</td></tr>";
						echo"<tr><td>Principio Activo</td><td>".$principio."</td></tr>";						
						echo"<tr><td>Presentación</td><td>".$presentacion."</td></tr>";									
					?>
				</table>
			</div>
		</div>
		<?php 
		echo "<input type='hidden' name='id_medicamento' id='id_medicamento' value='$id_medicamento'>";
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
	$id_medicamento = $_POST ['id_medicamento'];
	$table = 'medicamento';
	$where_clause = "id_medicamento='" . $id_medicamento . "'";
	$delete = _delete ( $table, $where_clause );
	if ($delete)
	{
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Medicamento eliminado correctamente';
	}
	else 
	{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Medicamento no pudo ser eliminado';
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
