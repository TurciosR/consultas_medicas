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
	<h4 class="modal-title">Servicio Profesional</h4>
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
		data: "process=add_med&id_servicio_profesional="+id+"&idc="+idc,
		url: "ver_servicio_profesional.php",
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
	$id_servicio_profesional = $_POST ['id_servicio_profesional'];
	$idc = $_POST ['idc'];
	$query = _query("SELECT * FROM servicios_profesionales WHERE id_servicio_profesional = '$id_servicio_profesional'");
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
			<td><b>Descripcion:</b></td><td>".$datos["descripcion"]."</td>
		</tr>

		<tr>
			<td><b>Precio:</b></td><td> $".number_format($datos["precio"],2)."</td>
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
