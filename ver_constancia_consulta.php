<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$filename='consulta1.php';
	$links=permission_usr($id_user,$filename);

	$id_constancia = $_REQUEST["id_constancia"];
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Constancia</h4>
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
	var id_constancia=<?php echo $id_constancia; ?>;
	$.ajax({
		type: "POST",
		data: "process=add_med&id_constancia="+id_constancia,
		url: "ver_constancia_consulta.php",
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
	$id_constancia = $_POST ['id_constancia'];
	$sql_constancia = "SELECT * FROM constancia WHERE id_constancia = '$id_constancia'";
    $query_constancia = _query($sql_constancia);
    $row_constancia = _fetch_array($query_constancia);

    $fecha = $row_constancia['fecha'];
    $padecimiento = $row_constancia['padecimiento'];
    $reposo = $row_constancia['reposo'];
    $id_doctor = $row_constancia['id_doctor'];

    $sql_doctor = "SELECT * FROM doctor WHERE id_doctor = '$id_doctor'";
    $query_doctor = _query($sql_doctor);
    $row_doctor = _fetch_array($query_doctor);
    $nombres_doctor = $row_doctor['nombres'];
    $apellidos_doctor = $row_doctor['apellidos'];
    $nombre_doctor  = "Dr. ".$nombres_doctor." ".$apellidos_doctor;
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
			<td><b>Fecha:</b></td><td>".$fecha."</td>
		</tr>

		<tr>
            <td><b>Doctor:</b></td><td>".$nombre_doctor."</td>
		</tr>
		<tr>
            <td><b>Padecimiento:</b></td><td>".$padecimiento."</td>
		</tr>

		<tr>
			<td style='color:blue;'><b>Reposo:</b></td><td colspan='3'>".$reposo." Dias</td>
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
