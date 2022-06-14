<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_examen.php";
	$links=permission_usr($id_user,$filename);

	$id_examen = $_REQUEST['id_examen'];

	$sql="SELECT * FROM examen WHERE id_examen='$id_examen'";
	$result = _query($sql);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Examen</h4>
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
						$id_examen=$row["id_examen"];
						$descripcion = $row["descripcion"];
						$observaciones = $row["observaciones"];
						//$fecha_reg = ED($row["fecha_registro"]);
						echo"<tr><td>Id examen</td><td>".$id_examen."</td></tr>";
						echo"<tr><td>Descripcion</td><td>".$descripcion."</td></tr>";											
						echo"<tr><td>Observaciones</td><td>".$observaciones."</td></tr>";											
					?>
				</table>
			</div>
		</div>
		<?php 
		echo "<input type='hidden' name='id_examen' id='id_examen' value='$id_examen'>";
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
	$id_examen = $_POST ['id_examen'];
	$table = 'examen';
	$where_clause = "id_examen='" . $id_examen . "'";
	$delete = _delete ( $table, $where_clause );
	if ($delete)
	{
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Examen eliminado correctamente';
	}
	else 
	{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Examen no pudo ser eliminado';
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
