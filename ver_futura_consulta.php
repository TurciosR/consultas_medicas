<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$filename='ver_receta.php';
	$links=permission_usr($id_user,$filename);

	$id = $_REQUEST["id"];
	$idc = $_REQUEST["idc"];
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Futura Consulta</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
			<div class="form-group" id="display_prod">

			</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
<script type="text/javascript">
	var id=<?php echo $id; ?>;
	var idc=<?php echo $idc; ?>;
	$.ajax({
		type: "POST",
		data: "process=add_med&id_reserva="+id+"&idc="+idc,
		url: "ver_futura_consulta.php",
		dataType: "json",
		success: function(datax)
		{
			if(datax.typeinfo =="Success")
			{
				$("#display_prod").html(datax.table);
				//$("#dosisss").text("Dosis: "+datax.dosis);
				//$("#canttt").text("Cantidad: "+datax.cantidad);
			}
		},
	});
</script>
<!--/modal-footer -->
<?php
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function add_med()
{
	$id_reserva = $_POST ['id_reserva'];
	$idc = $_POST ['idc'];
    $sql = "SELECT reserva_cita.fecha_cita, reserva_cita.hora_cita, reserva_cita.motivo_consulta, doctor.nombres as 'n_doc', doctor.apellidos as 'a_doc', espacio.descripcion, paciente.nombres, paciente.apellidos FROM reserva_cita INNER JOIN doctor on doctor.id_doctor = reserva_cita.id_doctor INNER JOIN paciente on paciente.id_paciente = reserva_cita.id_paciente INNER JOIN espacio on espacio.id_espacio = reserva_cita.id_espacio WHERE reserva_cita.id = '$id_reserva'";
    $query = _query($sql);
    $datos = _fetch_array($query);
	/*
	<tr>
		<td><b>Presentacion:</b></td><td>".$datos["presentacion"]."</td>
	</tr>
	<tr>
		<td style='color:blue;'><b>Cantidad:</b></td><td colspan='3'>".$datos2["cantidad"]."</td>
	</tr>
	*/
	$table = "<table class='table'>
		<tr>
			<td><b>Paciente:</b></td><td>".$datos["nombres"]." ".$datos['apellidos']."</td>
		</tr>
        <tr>
			<td><b>Doctor:</b></td><td>".$datos["n_doc"]." ".$datos['a_doc']."</td>
		</tr>
        <tr>
			<td><b>Fecha:</b></td><td>".ED($datos['fecha_cita']).","._hora_media_decode($datos['hora_cita'])."</td>
		</tr>
        <tr>
			<td><b>Consultorio:</b></td><td>".$datos["descripcion"]."</td>
		</tr>
        <tr>
			<td><b>Motivo:</b></td><td>".$datos["motivo_consulta"]."</td>
		</tr>

		
	</table>
	";
	$xdatos["table"] = $table;
	$xdatos["typeinfo"] = "Success";
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
			case 'add_med' :
				add_med();
				break;
		}
	}
}

?>
