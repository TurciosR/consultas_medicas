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
	<h4 class="modal-title">Lista de medicamentos</h4>
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
				<label>Medicamento</label>
				<input type="text" name="buscar_med" id="buscar_med" placeholder="Nombre del medicamento, principio activo, presentacion" class="form-control" autocomplete="off">
				<input type="hidden" name="id_med" id="id_med">
				<input type="hidden" name="descript" id="descript">
				<input type="hidden" name="cant_stock" id="cant_stock">
				<label id="medica"></label>
			</div>
			<div class="form-group" id="display_prod">

			</div>
			<div class="form-group" id="dosis_dis" hidden>
				<!--<div class="col-lg-12">
					<label>Cantidad</label>
					<input type="text" name="cantidad" id="cantidad" class="form-control numeric">
				</div>-->
				<div class="col-lg-12">
					<label>Dosis</label>
					<textarea name="dosis" id="dosis" class="form-control"></textarea>
				</div>
			</div>
			<div class="form-group" id="plaan" hidden>
				<div class="col-lg-12">
					<div class='checkbox i-checks'><br>
                        <label id='frentex'>
                            <input type='checkbox' id='plana' name='plana'> <strong> Plan de Vacunación</strong>
                        </label>
                    </div>
                    <input type='hidden' id='plan' name='plan' value="0">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btn_add_med">Agregar</button>
	<button type="button" class="btn btn-default" id="btn_cerrar_medicamento" data-dismiss="modal">Cerrar</button>
</div>
<script type="text/javascript">
	$('.i-checks').iCheck({
		checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
     });
	$(".numeric").numeric({negative:false});
	$("#buscar_med").typeahead(
	{
		//Definimos la ruta y los parametros de la busqueda para el autocomplete
	    source: function(query, process)
	    {
			$.ajax(
			{
	            url: 'autocomplete_medicamento.php',
	            type: 'GET',
	            data: 'query=' + query ,
	            dataType: 'JSON',
	            async: true,
	            //Una vez devueltos los resultados de la busqueda, se pasan los valores al campo del formulario
	            //para ser mostrados
	            success: function(data)
	            {
	              	process(data);
				}
	        });
	    },
	    //Se captura el evento del campo de busqueda y se llama a la funcion agregar_factura()
	    updater: function(selection)
	    {
	    	var data0=selection;
			var id = data0.split("|");
			var nombre = id[1];
			id = parseInt(id[0]);
			$("#id_med").val(id);
			$("#descript").val(nombre);
			$.ajax({
				type: "POST",
				data: "process=add_med&id_med="+id,
				url: "receta.php",
				dataType: "json",
				success: function(datax)
				{
					if(datax.typeinfo =="Success")
					{
						$("#display_prod").html(datax.table);
						$("#dosis_dis").show();
						if(datax.vacuna == '1')
						{
							$("#plaan").show();
							$("#plana").iCheck('uncheck');
							$("#plana").attr("checked",false);
						}
						else
						{
							$("#plaan").hide();
						}
					}
				},
			});
	    }
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
	$id_medicamento = $_POST ['id_med'];
	$query = _query("SELECT * FROM medicamento  WHERE id_medicamento ='$id_medicamento'");
	$datos = _fetch_array($query);

	$table = "<table class='table'>
		<tr>
			<td><b>Nombre:</b></td><td>".$datos["descripcion"]."</td>
		</tr>
		<tr>
		";

		//$table .="<td><b>Presentacion:</b></td><td>".$datos["presentacion"]."</td>";

		$table .="
		</tr>
		<tr>
			<td><b>Laboratorio:</b></td><td>".$datos["laboratorio"]."</td>
		</tr>
		<tr>
			<td><b>Principio:</b></td><td>".$datos["principio"]."</td>
		</tr>
	</table>
	";
	$xdatos["vacuna"] = 0;
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
