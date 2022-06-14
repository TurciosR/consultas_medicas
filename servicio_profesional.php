<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Agregar Servicio Profesional</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
			<div class="form-group">
				<label>Descripcion</label>
				<input type="text" name="descripcion_sp" id="descripcion_sp" placeholder="Descripcion del servicio profesional" class="form-control" autocomplete="off">
			</div>
			<div class="form-group">
				<label>Precio</label>
				<input type="text" name="precio_sp" id="precio_sp" placeholder="Precio del servicio profesional" class="form-control" autocomplete="off">
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btn_add_sp">Agregar</button>
	<button type="button" class="btn btn-default" id="btn_cerrar_sp" data-dismiss="modal">Cerrar</button>
</div>
<script type="text/javascript">
	
</script>
<!--/modal-footer -->
<?php
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function add_servicio_profesional()
{
	$descripcion = $_POST ['descripcion'];
    $precio = $_POST ['precio'];
    $id_cita = $_POST ['id_cita'];

    $sql = "SELECT * FROM reserva_cita WHERE id = '$id_cita'";
    $query = _query($sql);
    $row = _fetch_array($query);

    $id_paciente = $row['id_paciente'];

    $table = 'servicios_profesionales';
    $form_data = array(
        'descripcion' => $descripcion,
        'precio' => $precio,
        'id_cita' => $id_cita,
        'id_paciente' => $id_paciente
    );
    $insert = _insert($table, $form_data);
    if($insert){    
        $id_servicio_profesional = _insert_id();
        $xdatos["id_servicio_profesional"] = $id_servicio_profesional;
        $xdatos['precio'] = "$ ".number_format($precio,2);
        $xdatos["typeinfo"] = "Success";
    }
    else{
        $xdatos["typeinfo"] = "Error";
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
			case 'add_servicio_profesional' :
				add_servicio_profesional();
				break;
		}
	}
}

?>
