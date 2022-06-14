<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri=$_SERVER['REQUEST_URI'];
	$filename = "borrar_doctor.php";
	$links=permission_usr($id_user,$filename);

	$id_doctor = $_REQUEST['id_doctor'];

	$sql="SELECT d.*, e.descripcion FROM doctor AS d, especialidad as e WHERE d.id_especialidad = e.id_especialidad AND d.id_doctor='$id_doctor'";
	$result = _query($sql);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Médico</h4>
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
						$id_doctor=$row["id_doctor"];
						$contacto=$row['nombres']."  ".$row['apellidos'];
						$sexo = $row["sexo"];
						$fecha = ED($row["fecha_nac"]); 
				        $datos_fecha = explode("-", $fecha);
				        $anio_nac  = $datos_fecha[2];
				        $edad = date("Y") - $anio_nac;             
						$jvpm = $row["jvpm"];
						$especialidad = $row["descripcion"];
						$estado = "Activo";
						if(!$row["activo"])
						{
							$estado = "Inactivo";
						}
						//$fecha_reg = ED($row["fecha_registro"]);
						echo"<tr><td>Id Doctor</td><td>".$id_doctor."</td></tr>";
						echo"<tr><td>Nombre</td><td>".$contacto."</td></tr>";
						echo"<tr><td>Género</td><td>".$sexo."</td></tr>";
						echo"<tr><td>Edad</td><td>".$edad."</td></tr>";
						echo"<tr><td>JVPM</td><td>".$jvpm."</td></tr>";						
						echo"<tr><td>Especialidad</td><td>".$especialidad."</td></tr>";	
						echo"<tr><td>Estado</td><td>".$estado."</td></tr>";											
					?>
				</table>
			</div>
		</div>
		<?php 
		echo "<input type='hidden' name='id_doctor' id='id_doctor' value='$id_doctor'>";
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
	$id_doctor = $_POST ['id_doctor'];
	$table = 'doctor';
	$where_clause = "id_doctor='" . $id_doctor . "'";
	$table2 = 'usuario';

	$delete = _delete ( $table, $where_clause );
	
	$delete2 = _delete ( $table2, $where_clause );

	if ($delete && $delete2)
	{
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Médico eliminado correctamente';
	}
	else 
	{
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Médico no pudo ser eliminado';
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
