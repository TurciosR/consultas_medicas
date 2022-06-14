<?php
include_once "_core.php";
function initial() {
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
    $id_sucursal=$_SESSION["id_sucursal"];

    $id_hospitalizacion = $_REQUEST['id_hospitalizacion'];
    $id_sucursal = $_SESSION['id_sucursal'];
    $sql1="SELECT CONCAT(paciente.nombres, ' ', paciente.apellidos) as 'nombre_paciente', hospitalizacion.momento_entrada, hospitalizacion.momento_salida, recepcion.evento, estado_hospitalizacion.estado as 'estado_hospitalizacion', pisos.numero_piso, cuartos.id_cuarto, pisos.descripcion as 'descripcion_piso', cuartos.numero_cuarto, cuartos.descripcion as 'descripcion_cuarto', estado_cuarto.estado as 'estado_cuarto', tipo_cuarto.tipo, tipo_cuarto.descripcion as 'descripcion_tipo_cuarto', tipo_cuarto.cantidad, hospitalizacion.precio_habitacion, hospitalizacion.minuto, recepcion.fecha_de_entrada FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN cuartos on cuartos.id_cuarto = hospitalizacion.id_cuarto_H INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion INNER JOIN estado_hospitalizacion on estado_hospitalizacion.id_estado_hospitalizacion = hospitalizacion.id_estado_hospitalizacion INNER JOIN estado_cuarto on estado_cuarto.id_estado_cuarto = cuartos.id_estado_cuarto_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto WHERE recepcion.id_sucursal_recepcion = '$id_sucursal' AND hospitalizacion.id_hospitalizacion = '$id_hospitalizacion'";
    $consulta1 = _query($sql1);
    $row1 = _fetch_array($consulta1);
    $nombre_paciente = $row1['nombre_paciente'];
    $momento_entrada = $row1['momento_entrada'];
    $momento_salida = $row1['momento_salida'];
    $evento = $row1['evento'];
    $id_cuarto = $row1['id_cuarto'];
    $estado_hospitalizacion = $row1['estado_hospitalizacion'];
    $numero_piso = $row1['numero_piso'];
    $descripcion_piso = $row1['descripcion_piso'];
    $numero_cuarto = $row1['numero_cuarto'];
    $descripcion_cuarto = $row1['descripcion_cuarto'];
    $estado_cuarto = $row1['estado_cuarto'];
    $tipo = $row1['tipo'];
    $minuto = $row1['minuto'];
    $descripcion_tipo_cuarto = $row1['descripcion_tipo_cuarto'];
    $cantidad = $row1['cantidad'];
    $precio_habitacion = $row1['precio_habitacion'];
    $fecha_entrada_recepcion = $row1['momento_entrada'];
    $fecha_entrada_recepcion = explode(" ", $fecha_entrada_recepcion);
    $fecha_recepcion = ED($fecha_entrada_recepcion[0]);
    $hora_recepcion = _hora_media_decode($fecha_entrada_recepcion[1]);
    $sql_cantidad = "SELECT COUNT(*) as 'cantidad_hospitalizaciones' FROM hospitalizacion INNER JOIN cuartos on cuartos.id_cuarto = hospitalizacion.id_cuarto_H WHERE (hospitalizacion.id_estado_hospitalizacion = '1' OR hospitalizacion.id_estado_hospitalizacion = '2') AND cuartos.id_cuarto = '$id_cuarto'";
    $query_cant = _query($sql_cantidad);
    $row_c = _fetch_array($query_cant);
    $cantidad_hospitalizaciones = $row_c['cantidad_hospitalizaciones'];

    $momentos_entrada_ex = explode(" ",$momento_entrada);
    $fecha_entrada= ED($momentos_entrada_ex[0]);
    $hora_entrada = _hora_media_decode($momentos_entrada_ex[1]);
    $fecha_salida = "No posee fecha de salida";
    $hora_salida = "No posee hora de salida";

    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$(\s{1})(([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)/",$momento_salida)) {
        $form = explode(" ", $momento_salida);
        $hora_salida = _hora_media_decode($form[1]);
        $fecha_salida = ED($form[0]);
    }


	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	?>
    <link href="css/plugins/timepicki/timepicki.css" rel="stylesheet">
    <style  type="text/css">
                .datepicker table tr td, .datepicker table tr th{
                    border:none;
                    background:white;
                }
    </style>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 class="modal-title text-navy">Ingresar Hospitalizacion de <?php  echo $nombre_paciente; ?></h3>
	</div>
	<div class="modal-body">
		<?php if($links != 'NOT' || $admin == '1'){ ?>
		<div class="row" id="row1">
			<div class="col-lg-12">
				<form name="formulario" id="formulario" autocomplete="off">
                <div class="row">
                        <div class='col-md-12'>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-info single-line">
                                        <label>Fecha de entrada <span style="color:red;">*</span></label>
                                        <input type="text" name="fecha_de_entrada_x" id="fecha_de_entrada_x" class="form-control" value="<?php echo $fecha_recepcion;?>" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info single-line x_f">
                                        <label>Hora de entrada <span style="color:red;">*</span></label>
                                        <input type="text" placeholder="HH:mm" class="form-control" id="hora_entradax" name="hora_entradax" autocomplete="off" value="<?php  echo $hora_recepcion ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_hospitalizacion_ingreso" id="id_hospitalizacion_ingreso" value="<?php echo $id_hospitalizacion; ?>">
					<div>
						<input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs" />
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
	$(document).ready(function(){

        $.validator.addMethod('regexp', function(value, element, param)
        {
            return this.optional(element) || value.match(param);
        },'Mensaje a mostrar si se incumple la condición');

		$('#formulario').validate({
			rules: {
				fecha_de_entrada_x: {
					required: true,
				},
                hora_entradax: {
					required: true,
                    regexp:/^(0?[1-9]|1[012])(:[0-5]\d) [APap][mM]$/,
				},
			},
			messages: {
                fecha_de_entrada_x: {
                    required: "Por favor ingrese la fecha de entrada",
                    regexp:'La fecha de entrada no es valida' ,
                },
                hora_entradax: {
                    required: "Por favor ingrese la hora de entrada",
                    regexp:'La hora de entrada no es valida' ,
                },
			},
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			success: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			},
			submitHandler: function (form) {
				ingresar_hospitalizacion();
			}
		});


	});
    function ingresar_hospitalizacion(){
        var fecha_de_entrada_x = $("#fecha_de_entrada_x").val();
        var hora_entradax = $("#hora_entradax").val();
        var id_hospitalizacion_ingreso = $("#id_hospitalizacion_ingreso").val();
        swal({
            title: "Esta a punto de ingresar la hospitalizacion!",
            text: "Una vez introducida la fecha y hora de entrada no se podra cambiar. Esta seguro de hacerlo?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, ingresar",
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                var dataString="process=insert&id_hospitalizacion="+id_hospitalizacion_ingreso+"&hora_entradax="+hora_entradax+"&fecha_de_entrada_x="+fecha_de_entrada_x;
                $.ajax({
                    type : "POST",
                    url : "ingresar_paciente.php",
                    data : dataString,
                    async: false,
                    dataType: 'json',
                    success : function(datax) {
                        display_notify(datax.typeinfo,datax.msg);
                        if(datax.typeinfo == "Success")
                        {
                            setInterval("reload1();", 1500);
                        }
                    }
                });
            } else {
                swal("Cancelado", "Operación cancelada", "error");
                correcto++;
            }
        });


    }
    function reload1()
        {
        location.href = 'admin_hospitalizaciones.php';
        }

	</script>
<?php
	}
	else
	{
		//$mensaje = mensaje_permiso();
		echo "<br><br>No tiene permiso para este modulo</div></div></div></div>";;
	}
}


function insert(){
    $fecha_de_entrada_x = $_POST['fecha_de_entrada_x'];
    $hora_entradax = $_POST['hora_entradax'];
    $id_hospitalizacion_ingreso = $_POST['id_hospitalizacion'];
    $momento_entrada = MD($fecha_de_entrada_x)." "._hora_media_encode($hora_entradax);
    $tabla = "hospitalizacion";
    $form_data = array(
        'momento_entrada' => $momento_entrada,
        'id_estado_hospitalizacion'=>2
    );
    $where = " id_hospitalizacion = '$id_hospitalizacion_ingreso'";
    $update = _update($tabla, $form_data, $where);
    if($update){
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Paciente ingresado con exito!';
    }
    else{
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Hospitalizacion no pudo ser ingreada!'._error();
    }
    echo json_encode($xdatos);
}
if(!isset($_REQUEST['process'])){
	initial();
}
else
{
	if(isset($_REQUEST['process']))
	{
		switch ($_REQUEST['process'])
		{
			case 'insert':
				insert();
				break;
			case 'formEdit' :
				initial();
				break;
		}
	}
}
?>
