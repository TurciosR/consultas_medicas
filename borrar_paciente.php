<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_paciente.php";
	$links=permission_usr($id_user,$filename);

	$id_paciente = $_REQUEST ['id_paciente'];
	$sql="SELECT p.*, d.nombre_departamento, m.nombre_municipio FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id_paciente'";
	$result = _query( $sql );
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Paciente</h4>
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
						$id_paciente = $row ['id_paciente'];
						$contacto=$row['nombres']."  ".$row['apellidos'];
						$sexo = $row["sexo"];
						$fecha = ED($row["fecha_nacimiento"]); 
				        $datos_fecha = explode("-", $fecha);
				        $anio_nac  = $datos_fecha[2];
				        $edad = date("Y") - $anio_nac;             
						$direccion = $row["direccion"].", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
								
						echo "<tr><td>Id paciente</th><td>$id_paciente</td></tr>";
						echo"<tr><td>Nombre</td><td>".$contacto."</td></tr>";
						echo"<tr><td>Género</td><td>".$sexo."</td></tr>";
						echo"<tr><td>Edad</td><td>".$edad."</td></tr>";
						echo"<tr><td>Dirección</td><td>".$direccion."</td></tr>";
						echo "</tr>";
					?>
				</table>
			</div>
		</div>
		<?php 
		echo "<input type='hidden' name='id_paciente' id='id_paciente' value='$id_paciente'>";
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
	$id_paciente = $_POST ['id_paciente'];
	$table = 'paciente';
	$where_clause = "id_paciente='" . $id_paciente . "'";
	$delete = _delete ( $table, $where_clause );
	if ($delete) {
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Paciente eliminado correctamente';
	} else {
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Paciente no pudo ser eliminado';
	}
	echo json_encode ( $xdatos );
}
if (! isset ( $_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
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
