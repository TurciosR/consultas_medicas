<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_espacio.php";
	$links=permission_usr($id_user,$filename);

	$id_espacio = $_REQUEST['id_espacio'];

	$sql="SELECT * FROM espacio WHERE id_espacio='$id_espacio'";
	$result = _query($sql);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Consultorio </h4>
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
						$id_espacio=$row["id_espacio"];
						$descripcion = $row["descripcion"];
						$observaciones = $row["observaciones"];
						//$fecha_reg = ED($row["fecha_registro"]);
						echo"<tr><td>Id</td><td>".$id_espacio."</td></tr>";
						echo"<tr><td>Descripcion</td><td>".$descripcion."</td></tr>";											
						echo"<tr><td>observaciones</td><td>".$observaciones."</td></tr>";											
					?>
				</table>
			</div>
		</div>
		<?php 
		echo "<input type='hidden' name='id_espacio' id='id_espacio' value='$id_espacio'>";
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
	$id_espacio = $_POST ['id_espacio'];
	$table = 'espacio';
	$where_clause = "id_espacio='" . $id_espacio . "'";
	$delete = _delete ( $table, $where_clause );
	if ($delete)
	{
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Consultorio eliminado correctamente';
	}
	else 
	{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Consultorio no pudo ser eliminado';
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
