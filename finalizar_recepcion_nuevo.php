<?php
include ("_core.php");
function initial(){
	$idRecepcion = $_REQUEST['idRecepcion'];
	$id_sucursal=$_SESSION['id_sucursal'];
	$sql="SELECT recepcion.evento, recepcion.nombre_pariente, recepcion.fecha_de_entrada, recepcion.telefono_contacto, parentezco.descripcion, recepcion.otro, paciente.nombres as 'nombrePa', paciente.Apellidos as 'apellidoPa', doctor.nombres, doctor.apellidos, usuario.nombre, estado_recepcion.estado, empresa.nombre as 'sucursal', tipo_recepcion.tipo FROM recepcion LEFT JOIN parentezco on parentezco.id_parentezco = recepcion.id_pariente_contacto INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion LEFT JOIN doctor on doctor.id_doctor = recepcion.id_doctor_recepcion LEFT JOIN usuario on usuario.id_usuario = recepcion.id_usuario_recepcion INNER JOIN estado_recepcion on estado_recepcion.id_estado_recepcion = recepcion.id_estado_recepcion INNER JOIN empresa on empresa.id_empresa = recepcion.id_sucursal_recepcion INNER JOIN tipo_recepcion on tipo_recepcion.id_tipo_recepcion = recepcion.id_tipo_recepcion  WHERE recepcion.id_recepcion = $idRecepcion AND recepcion.id_sucursal_recepcion = $id_sucursal AND recepcion.deleted is NULL";
	$result = _query( $sql );
	$count = _num_rows( $result );
	$row = _fetch_array ($result);
	$numero_areas = 0;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Finalizar Recepcion</h4>
</div>

<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<div class="modal-body">
    <!--div class="wrapper wrapper-content  animated fadeInRight"-->
    <div class="row" id="row1">
        <div class="col-lg-12">
            <table class="table table-bordered table-striped" id="tableview">
                <thead>
                    <tr>
                        <th>Nombres paciente</th>
                        <th>Apellidos paciente</th>
                        <th>Evento</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <td><?php echo $row['nombrePa']; ?></td>
                    <td><?php echo $row['apellidoPa']; ?></td>
                    <td><?php echo $row['evento']; ?></td>
                    <td><?php echo $row['estado']; ?></td>
                </tbody>
            </table>
        </div>
        <div class="col-lg-12">
            <table class="table table-bordered table-striped" id="tableview">
                <thead>
                    <tr>
                        <th>Nombres doctor a cargo</th>
                        <th>Apellidos doctor a cargo</th>
                        <th>Fecha de entrada</th>
                        <th>Tipo recepcion</th>
                    </tr>
                </thead>
                <tbody>
                    <td><?php echo $row['nombres']; ?></td>
                    <td><?php echo $row['apellidos']; ?></td>
                    <td><?php echo $row['fecha_de_entrada']; ?></td>
                    <td><?php echo $row['tipo']; ?></td>
                </tbody>
            </table>
        </div>
        <div class="col-lg-12">
            <table class="table table-bordered table-striped" id="tableview">
                <thead>
                    <tr>
                        <th>Nombres pariente</th>
                        <th>Parentezco</th>
                        <th>Contacto pariente</th>
                        <th>Otro parentezco</th>
                    </tr>
                </thead>
                <tbody>
                    <td><?php echo $row['nombre_pariente']; ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><?php echo $row['telefono_contacto']; ?></td>
                    <td><?php echo $row['otro']; ?></td>
                </tbody>
            </table>
        </div>
        <div class="col-lg-12">
            <table class="table table-bordered table-striped" id="tableview">
                <thead>
                    <tr>
                        <th>Sucursal</th>
                    </tr>
                </thead>
                <tbody>
                    <td><?php echo $row['sucursal']; ?></td>
                </tbody>
            </table>
        </div>
    </div>
    <?php
        echo "<input type='hidden' nombre='idRecepcion' id='idRecepcion' value='$idRecepcion'>";
    	?>
    <script>
    $(function() {
        //binding event click for button in modal form
        // Clean the modal form
        $(document).on('hidden.bs.modal', function(e) {
            var target = $(e.target);
            target.removeData('bs.modal').find(".modal-content").html('');
        });
    });

    function finalizar() {
        ingresar();
    }

    function ingresar() {
        var idRecepcion = $('#idRecepcion').val();
        var dataString = 'process=ingregar_cobros' + '&id_recepcion=' + idRecepcion;
        $.ajax({
            type: "POST",
            url: "finalizar_recepcion_nuevo.php",
            data: dataString,
            dataType: 'json',
            success: function(datax) {
                swal({
                    html: true,
                    title: "<b>Referencia <i># " + datax.referencia + "</i><br>$ " + datax.tot +
                        "</b>",
                    text: "<b>Presione OK para continuar</b>",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: '',
                    confirmButtonText: 'OK',
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        setInterval("location.reload();", 500);
                        $('#deleteModal').hide();
                    } else {}

                });
            }
        });
    }
    </script>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" onclick="finalizar();">Finalizar</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
<!--/modal-footer -->]
<?php
}

?>

<?php

function comprobar_recepciones(){
    $id_recepcion = $_POST['id_recepcion'];
    $sql = "SELECT * FROM recepcion WHERE id_recepcion = '$id_recepcion'";
    $query = _query($sql);
    $row = _fetch_array($query);

    $r1 = $row['recepcion_emergencia'];
    $r2 = $row['recepcion_pediatria'];
    $r3 = $row['recepcion_estacion_enfermeria_a'];
    $r4 = $row['recepcion_estacion_enfermeria_b'];
    $r5 = $row['recepcion_enfermeria_sala_estar'];
    $r6 = $row['recepcion_nefrologia'];
    $r7 = $row['recepcion_rayosx'];
    $r8 = $row['recepcion_uci'];
    $r9 = $row['recepcion_terapia_respiratoria'];
    $r10 = $row['recepcion_laboratorio'];
    $r11 = $row['recepcion_sala_operaciones'];
    $r12 = $row['recepcion_microcirugia'];
    $r13 = $row['recepcion_hospitalizacion'];

    if($r1 == "1" || $r2 == "1" || $r3 == "1" || $r4 == "1" || $r5 == "1" || $r6 == "1" || $r7 == "1" || $r8 == "1" || $r9 == "1" || $r10 == "1" || $r11 == "1" || $r12 == "1" || $r13 == "1" ){
        echo "1";
    }
    else{
        echo "2";
    }

}

function ingresar_cobros(){
	$id_sucursal = $_SESSION['id_sucursal'];
    $id_recepcion = $_POST['id_recepcion'];
	$sql_paciente = "SELECT id_paciente_recepcion FROM recepcion WHERE id_recepcion = '$id_recepcion'";
	$query_paciente = _query($sql_paciente);
	$row_paciente = _fetch_array($query_paciente);
	$id_paciente = $row_paciente['id_paciente_recepcion'];
	$array_detalle_cobro = array();

    /* ESTA ES UNA LISTA DE VARIABLES LAS CUALES ME SERVIRAN PARA PODER RECOLECTAR LA INFORMACION
    A CERCA DE PRODUCTOS, SERVICIOS, EXAMENES Y TIEMPO DE ENCAMADO QUE LA RECEPCION HA REGISTRADO
    EN LAS DISTINTAS AREAS */
    $contador_hospitalizacion = 0;
    $contador_emergencia = 0;
    $contador_sala_operaciones = 0;
    $contador_rayos_x = 0;
    $contador_pediatria = 0;
    $contador_nefrologia = 0;
    $contador_examenes = 0;
    $productos_hospitalizacion = 0;
    $servicios_hospitalizacion = 0;
    $examenes_hospitalizacion = 0;
    $productos_emergencia = 0;
    $servicios_emergencia= 0;
    $examenes_emergencia = 0;
    $productos_sala_operaciones = 0;
    $servicios_sala_operaciones = 0;
    $examenes_sala_operaciones = 0;
    $productos_rayos_x = 0;
    $servicios_rayos_x = 0;
    $examenes_rayos_x = 0;
    $productos_pediatria = 0;
    $servicios_pediatria = 0;
    $examenes_pediatria = 0;
    $productos_nefrologia = 0;
    $servicios_nefrologia = 0;
    $examenes_nefrologia = 0;
    $tiempo_encamado = 0;
	$subtotal_cobro = 0;
	$pequenia_cirugia_activa = 0;
	$uso_de_consultorio_emergencia = 0;
    $uso_de_sala_operaciones = 0;
    /* LISTA DE TOTALES */
    $total_examenes = 0;
    $total_hospitalizacion = 0;
    $total_emergencia = 0;
    $total_rayos_x = 0;
    $total_pediatria = 0;
    $total_nefrologia = 0;
    $total_sala_operaciones = 0;
    $total_tiempo_encamado = 0;
    $numero_areas = 0;
    /*  MAXIMO DE COLUMNAS QUE SE VAN A MOSTRAR EN EL DETALLE DE LA ".EXTERNAL.".factura */
    $maximo_columnas = 5;


	_begin();
	/* EN ESTA PARTE DE ACA SE EMPEZARA A BUSCAR LOS INSUMOS UTILIZADOS POR LAS
    DISTINTAS RECEPCIONES, COMO POR EJEMPLO INSUMOS DE HOSPITALIZACION, INSUMOS DE EMERGENCIAS,
    INSUMOS DE SALA DE OPERACIONES, INSUMOS DE RAYOS X, INSUMOS DE PEDIATRIA, ETC. */

    /*PRIMER TIPO DE COBRO 'COBROS DE HOSPITALIZACION HOSPITALIZACION'*/
    /*  ACA EMPIEZA EL PRIMER TIPO DE COBRO */

    /* SE VERIFICA QUE EXISTA ESE TIPO DE COBRO PRIMERAMENTE A TRAVES DE LA SIGUIENTE CONSULTA */

    $sql_hospitalizacion = "SELECT * FROM hospitalizacion WHERE id_recepcion = '$id_recepcion' AND deleted is NULL";
    $query_hospitalizacion = _query($sql_hospitalizacion);
    /* SI EXISTE MAS DE 0 FILAS COMO RESPUESTA A LA CONSULTA SIGNIFICA QUE HAY POR LO MENOS
    UNA HOSPITALIZACION REGISTRADA CON LA MISMA RECEPCION */
    if(_num_rows($query_hospitalizacion) > 0){
        $row_hospizalizacion_id = _fetch_array($query_hospitalizacion);
        $id_hospitalizacion = $row_hospizalizacion_id['id_hospitalizacion'];
        $numero_areas++;
        /*  QUERY PARA CALCULAR EL TIEMPO DE LA HOSPITALIZACION */
        $sql_tiempo = "SELECT hospitalizacion.id_estado_hospitalizacion, paciente.expediente,hospitalizacion.id_hospitalizacion, hospitalizacion.momento_entrada, hospitalizacion.momento_salida, estado_hospitalizacion.estado, hospitalizacion.precio_habitacion, hospitalizacion.minuto, hospitalizacion.total FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion INNER JOIN estado_hospitalizacion on estado_hospitalizacion.id_estado_hospitalizacion = hospitalizacion.id_estado_hospitalizacion WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND hospitalizacion.deleted is NULL";
        $query_tiempo = _query($sql_tiempo);
        $row_query_tiempo = _fetch_array($query_tiempo);
        $momento_de_entrada = $row_query_tiempo['momento_entrada'];
        $momento_de_salida = $row_query_tiempo['momento_salida'];
        $minuto = $row_query_tiempo['minuto'];
        $total = $row_query_tiempo['total'];
        $id_estado_hospitalizacion = $row_query_tiempo['id_estado_hospitalizacion'];
        $precio_habitacion = $row_query_tiempo['precio_habitacion'];
        $fecha1 = new DateTime($momento_de_entrada);
        if($id_estado_hospitalizacion == "3"){
            $fecha2 = new DateTime($momento_de_salida);
            $momento_salida = explode(" ",$momento_de_salida);
        }
        if($id_estado_hospitalizacion == "2"){
            $fecha2 = new DateTime(date("Y-m-d H:i:s"));
        }
        $estado = $row_query_tiempo['estado'];
        $diff = $fecha1->diff($fecha2);
        $activo_con = 0;
        $precio_total_final = 0;
        /* SI EL TOTAL ES NULL SIGNIFICA QUE TODAVIA NO SE HA DADO DE ALTA AL PACIENTE, ENTONCES
        SE PROCEDERA A HACER EL CALCULO DEL TOTAL DE ENCAMADO DESDE EL TIEMPO EN QUE ENTRO HASTA
        EL MOMENTO ACTUAL (EN EL QUE SE ESTA FINALIZANDO LA RECEPCION) PARA HACER EL COBRO */
        if(!is_numeric($total)){
            if($diff->y > 0){
                if($diff->y == 1){
                    $precio_total_final += $precio_habitacion * 8760;
                }
                else{
                    $precio_total_final += $precio_habitacion * 8760 * ($diff->y);
                }
                $activo_con++;
            }
            if($diff->m > 0){
                if($activo_con == 0){
                    if($diff->m == 1){
                        $precio_total_final += $precio_habitacion * 730;
                    }
                    else{
                        $precio_total_final += $precio_habitacion * 730 * ($diff->m);
                    }
                }
                else{
                    if($diff->m == 1){
                        $precio_total_final += $precio_habitacion * 730;
                    }
                    else{
                        $precio_total_final += $precio_habitacion * 730 * ($diff->m);
                    }
                    $activo_con++;
                }
            }

            if($diff->d > 0){
                if($activo_con == 0){
                    if($diff->d == 1){
                        $precio_total_final += $precio_habitacion * 24;
                    }
                    else{
                        $precio_total_final += $precio_habitacion * 24 * ($diff->d);
                    }
                }
                else{
                    if($diff->d == 1){
                        $precio_total_final += $precio_habitacion * 24;
                    }
                    else{
                        $precio_total_final += $precio_habitacion * 24 * ($diff->d);
                    }
                }
                $activo_con++;
            }
            if($diff->h > 0){
                if($activo_con == 0){
                    if($diff->h == 1){
                        $precio_total_final += $precio_habitacion;
                    }
                    else{
                        $precio_total_final += $precio_habitacion * ($diff->h);
                    }
                }
                else{
                    if($diff->h == 1){
                        $precio_total_final += $precio_habitacion;
                    }
                    else{
                        $precio_total_final += $precio_habitacion * ($diff->h);
                    }
                }
                $activo_con++;
            }
            $total_tiempo_encamado = $precio_total_final;
        }
        /* SI EL TOTAL NO ES NO SIGNIFICA QUE EL PACIENTE YA SE DIO DE ALTA Y POR CONSECUENTE
        YA SE PUEDE TRAER EL TOTAL DEL TIEMPO ENCAMADO DEL PACIENTE EN LA HOSPITALIZACION */
        else{
            $total_tiempo_encamado = $row_query_tiempo['total'];
        }

        /*  UNA VEZ TENIENDO EL TIEMPO TOTAL DE ENCAMADO DEL PACIENTE EN LA HOSPITALIZACION
        PROCEDEREMOS A TRAER LOS PRODUCTOS QUE EL PACIENTE HA HECHO USO EN LA HOSPITALIZACION */
        $sql_productos_hospitalizacion = "SELECT ".EXTERNAL.".producto.descripcion, insumos_hospitalizacion.id_insumo, insumos_hospitalizacion.cantidad, insumos_hospitalizacion.total, insumos_hospitalizacion.created_at, ".EXTERNAL.".presentacion_producto.precio,".EXTERNAL.".presentacion_producto.unidad FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN insumos_hospitalizacion on insumos_hospitalizacion.id_recepcion = recepcion.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion = insumos_hospitalizacion.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND insumos_hospitalizacion.deleted is NULL";
        $query_productos_hospitalizacion = _query($sql_productos_hospitalizacion);
        /* SE VERIFICA QUE HAYAN PRODUCTOS AGREGADOS DESDE HOSPITALIZACION */
        if(_num_rows($query_productos_hospitalizacion) > 0){
            while($row_productos = _fetch_array($query_productos_hospitalizacion)){
                $precio = $row_productos['precio'];
                $cantidad = $row_productos['cantidad'];
                $unidad = $row_productos['unidad'];
                $cantidad = $cantidad / $unidad;
                $productos_hospitalizacion+=($precio * $cantidad);
                $contador_hospitalizacion++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS SERVICIOS QUE EL PACIENTE HA HECHO USO EN LA
        HOSPITALIZACION */
        $sql_servicios_hospitalizacion = "SELECT insumos_hospitalizacion.id_insumo, ".EXTERNAL.".servicios_hospitalarios.descripcion,insumos_hospitalizacion.created_at, insumos_hospitalizacion.cantidad, insumos_hospitalizacion.total, insumos_hospitalizacion.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN insumos_hospitalizacion on insumos_hospitalizacion.id_recepcion = recepcion.id_recepcion INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = insumos_hospitalizacion.id_servicio WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND insumos_hospitalizacion.deleted is NULL";
        $query_servicios_hospitalizacion = _query($sql_servicios_hospitalizacion);
        /* SE VERIFICA QUE HAYAN SERVICIOS AGREGADOS DESDE HOSPITALIZACION */
        if(_num_rows($query_servicios_hospitalizacion) > 0){
            while($row_servicios = _fetch_array($query_servicios_hospitalizacion)){
                $precio = $row_servicios['precio'];
                $cantidad = $row_servicios['cantidad'];
                $servicios_hospitalizacion+=($precio * $cantidad);
                $contador_hospitalizacion++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS EXAMENES QUE EL PACIENTE HA HECHO USO EN LA
        HOSPITALIZACION */
        /*
        $sql_examenes_hospitalizacion = "SELECT insumos_hospitalizacion.id_insumo, insumos_hospitalizacion.id_examen,insumos_hospitalizacion.cantidad, insumos_hospitalizacion.created_at, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM insumos_hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = insumos_hospitalizacion.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = insumos_hospitalizacion.id_examen INNER JOIN hospitalizacion on hospitalizacion.id_recepcion = recepcion.id_recepcion WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND insumos_hospitalizacion.deleted is NULL ";
        $query_examenes_hospitalizacion = _query($sql_examenes_hospitalizacion);
        if(_num_rows($query_examenes_hospitalizacion) > 0){
            while($row_examenes = _fetch_array($query_examenes_hospitalizacion)){
                $precio = $row_examenes['precio_examen'];
                $cantidad = 1;
                $examenes_hospitalizacion+=($precio * $cantidad);
                $contador_hospitalizacion++;
            }
        }*/
        /* EL TOTAL DE INSUMOS DE LA HOSPITALIZACION SE CALCULA A TRAVES DE LA SUMA DE LOS PRODUCTOS
        Y LOS SERVICIOS QUE SE APLICARON EN HOSPITALIZACION MAS EL TOTAL DEL TIEMPO ENCAMADO QUE EL
        PACIENTE ESTUVO EN EL HOSPITAL, Y CON RESPECTO A LOS EXAMENES, ESTOS LLEVAN SU DETALLE APARTE
        YA QUE SE COBRARAN EN UN CONCEPTO DISTINTO*/
        $total_hospitalizacion = $productos_hospitalizacion + $servicios_hospitalizacion + $total_tiempo_encamado;
        $array_detalle_cobro[] = array(
			'detalle' => 'COSTO DE ENCAMADO E INSUMOS HOSPITALARIOS.',
			'precio' => ($total_hospitalizacion)
		);
    }
    /* SI NO EXISTE SE PROCEDE A IGNORAR ESTE TIPO DE COBRO YA QUE AL NO EXISTIR UNA
    HOSPITALIZACION TAMPOCO EXISTIRAN INSUMOS QUE COBRAR DE ESTA */
    else{
        $contador_hospitalizacion = 0;
    }
    /* ACA TERMINA EL PRIMER TIPO DE COBRO */
	/* -----------******************---------- */
    /*SEGUNDO TIPO DE COBRO 'COBROS DE EMERGENCIA'*/
    /*  ACA EMPIEZA EL SEGUNDO TIPO DE COBRO */

    /* SE VERIFICA QUE EXISTA ESE TIPO DE COBRO PRIMERAMENTE A TRAVES DE LA SIGUIENTE CONSULTA */
    
    $sql_emergencia = "SELECT * FROM insumos_emergencia WHERE id_recepcion = '$id_recepcion' AND deleted is NULL AND cobrado_actual is NULL ";
    $query_emergencia = _query($sql_emergencia);
    /* SI EXISTE MAS DE 0 FILAS COMO RESPUESTA A LA CONSULTA SIGNIFICA QUE HAY POR LO MENOS
    UN INSUMO REGISTRADO EN EMERGENCIA A TRAVES DE ESTA RECEPCION */
    
    if(_num_rows($query_emergencia) > 0){
        $numero_areas++;
        /* PRIMERO SE PROCEDERA A VERIFICAR QUE EXISTAN PRODUCTOS AGREGADOS EN LOS
        INSUMOS DE EMERGENCIA A TRAVES DE LA SIGUIENTE CONSULTA */
        $sql_productos_emergencia = "SELECT insumos_emergencia.id_insumo, ".EXTERNAL.".producto.descripcion, insumos_emergencia.cantidad, insumos_emergencia.total, insumos_emergencia.created_at, ".EXTERNAL.".presentacion_producto.precio, ".EXTERNAL.".presentacion_producto.unidad FROM insumos_emergencia INNER JOIN recepcion on recepcion.id_recepcion = insumos_emergencia.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion = insumos_emergencia.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE recepcion.id_recepcion = '$id_recepcion' AND insumos_emergencia.deleted is NULL AND insumos_emergencia.cobrado_actual is NULL";
        $query_productos_emergencia = _query($sql_productos_emergencia);
        /* SE VERIFICA QUE HAYAN PRODUCTOS AGREGADOS DESDE EMERGENCIA */
        if(_num_rows($query_productos_emergencia) > 0){
            while($row_productos = _fetch_array($query_productos_emergencia)){
                $precio = $row_productos['precio'];
                $unidad = $row_productos['unidad'];
                $cantidad = $row_productos['cantidad'];
                $cantidad = $cantidad/$unidad;
                $productos_emergencia+=($precio * $cantidad);
                $contador_emergencia++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS SERVICIOS QUE EL PACIENTE HA HECHO USO EN EMERGENCIA */
        $sql_servicios_emergencia = "SELECT insumos_emergencia.id_insumo, ".EXTERNAL.".servicios_hospitalarios.descripcion, ".EXTERNAL.".servicios_hospitalarios.id_servicio, insumos_emergencia.created_at, insumos_emergencia.cantidad, insumos_emergencia.total, insumos_emergencia.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM insumos_emergencia INNER JOIN recepcion on recepcion.id_recepcion = insumos_emergencia.id_recepcion  INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = insumos_emergencia.id_servicio WHERE recepcion.id_recepcion = '$id_recepcion' AND insumos_emergencia.deleted is NULL AND insumos_emergencia.cobrado_actual is NULL";
        $query_servicios_emergencia = _query($sql_servicios_emergencia);
        /* SE VERIFICA QUE HAYAN SERVICIOS AGREGADOS DESDE HOSPITALIZACION */
        if(_num_rows($query_servicios_emergencia) > 0){
            while($row_servicios = _fetch_array($query_servicios_emergencia)){
                $id_servicio = $row_servicios['id_servicio'];
                if($id_servicio == 438 || $id_servicio == 451){
                    $pequenia_cirugia_activa = 1;
                }
                if($id_servicio == 343){
                    $uso_de_consultorio_emergencia = 1;
                }
                $precio = $row_servicios['precio'];
                $cantidad = $row_servicios['cantidad'];
                $servicios_emergencia+=($precio * $cantidad);
                $contador_emergencia++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS EXAMENES QUE EL PACIENTE HA HECHO USO EN EMERGENCIA */
        /*$sql_examenes_emergencia = "SELECT insumos_emergencia.id_insumo, insumos_emergencia.id_examen, insumos_emergencia.created_at,insumos_emergencia.cantidad, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM insumos_emergencia INNER JOIN recepcion on recepcion.id_recepcion = insumos_emergencia.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = insumos_emergencia.id_examen  WHERE recepcion.id_recepcion = '$id_recepcion' AND insumos_emergencia.deleted is NULL AND insumos_emergencia.cobrado_actual is NULL";
        $query_examenes_emergencia = _query($sql_examenes_emergencia);
        if(_num_rows($query_examenes_emergencia) > 0){
            while($row_examenes = _fetch_array($query_examenes_emergencia)){
                $precio = $row_examenes['precio_examen'];
                $cantidad = 1;
                $examenes_emergencia+=($precio * $cantidad);
                $contador_emergencia++;
            }
        }
        /* EL TOTAL DE INSUMOS DE EMERGENCIA SERA LA SUMA DE LOS PRODUCTOS QUE SE HAYAN
        UTILIZADO EN EMERGENCIA MAS LOS SERVICIOS QUE IGUAL SE HAN UTILIZADO EN ESTA AREA
        A EXCEPCION DE LOS EXAMENES QUE LLEVAN SU DETALLE APARTE */
        $total_emergencia = $productos_emergencia + $servicios_emergencia;
        $array_detalle_cobro[] = array(
			'detalle' => 'COSTO DE SERVICIOS DE EMERGENCIA.',
			'precio' => ($total_emergencia)
		);
    }
    /* SI NO EXISTE SE PROCEDE A IGNORAR ESTE TIPO DE COBRO YA QUE AL NO EXISTIR NINGUN
    INSUMO EN EMERGENCIA NO SE AGREGARA NADA AL COBRO */
    /*else{
        $contador_emergencia=0;
    }
    /* ACA TERMINA EL SEGUNDO TIPO DE COBRO */
	/* -----------******************---------- */


    /*TERCER TIPO DE COBRO 'COBROS DE RAYOS X'*/
    /*  ACA EMPIEZA EL TERCER TIPO DE COBRO */

    /* SE VERIFICA QUE EXISTA ESE TIPO DE COBRO PRIMERAMENTE A TRAVES DE LA SIGUIENTE CONSULTA */

    /*$sql_rayos_x = "SELECT * FROM tblInsumos_RayosX WHERE id_recepcion = '$id_recepcion' AND deleted is NULL AND cobrado_actual is NULL ";
    $query_rayos_x = _query($sql_rayos_x);
    /* SI EXISTE MAS DE 0 FILAS COMO RESPUESTA A LA CONSULTA SIGNIFICA QUE HAY POR LO MENOS
    UN INSUMO REGISTRADO EN RAYOS X A TRAVES DE ESTA RECEPCION */
    /*if(_num_rows($query_rayos_x) > 0){
        $numero_areas++;
        /* PRIMERO SE PROCEDERA A VERIFICAR QUE EXISTAN PRODUCTOS AGREGADOS EN LOS
        INSUMOS DE RAYOS X A TRAVES DE LA SIGUIENTE CONSULTA */
       /* $sql_productos_rayos_x = "SELECT tblInsumos_RayosX.id_insumo, ".EXTERNAL.".producto.descripcion, tblInsumos_RayosX.cantidad, tblInsumos_RayosX.total, tblInsumos_RayosX.created_at, ".EXTERNAL.".presentacion_producto.precio FROM tblInsumos_RayosX INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_RayosX.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion_producto = tblInsumos_RayosX.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_RayosX.deleted is NULL AND tblInsumos_RayosX.cobrado_actual is NULL";
        $query_productos_rayos_x = _query($sql_productos_rayos_x);
        /* SE VERIFICA QUE HAYAN PRODUCTOS AGREGADOS DESDE RAYOS X */
        /*if(_num_rows($query_productos_rayos_x) > 0){
            while($row_productos = _fetch_array($query_productos_rayos_x)){
                $precio = $row_productos['precio'];
                $cantidad = $row_productos['cantidad'];
                $productos_rayos_x+=($precio * $cantidad);
                $contador_rayos_x++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS SERVICIOS QUE EL PACIENTE HA HECHO USO EN RAYOS X */
        /*$sql_servicios_rayos_x = "SELECT tblInsumos_RayosX.id_insumo, tblInsumos_RayosX.created_at, ".EXTERNAL.".servicios_hospitalarios.descripcion, tblInsumos_RayosX.cantidad, tblInsumos_RayosX.total, tblInsumos_RayosX.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM tblInsumos_RayosX INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_RayosX.id_recepcion  INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = tblInsumos_RayosX.id_servicio WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_RayosX.deleted is NULL AND tblInsumos_RayosX.cobrado_actual is NULL";
        $query_servicios_rayos_x = _query($sql_servicios_rayos_x);
        /* SE VERIFICA QUE HAYAN SERVICIOS AGREGADOS DESDE RAYOS X */
        /*if(_num_rows($query_servicios_rayos_x) > 0){
            while($row_servicios = _fetch_array($query_servicios_rayos_x)){
                $precio = $row_servicios['precio'];
                $cantidad = $row_servicios['cantidad'];
                $servicios_rayos_x+=($precio * $cantidad);
                $contador_rayos_x++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS EXAMENES QUE EL PACIENTE HA HECHO USO EN RAYOS X */
        /*$sql_examenes_rayos_x = "SELECT tblInsumos_RayosX.id_insumo, tblInsumos_RayosX.id_examen, tblInsumos_RayosX.created_at,tblInsumos_RayosX.cantidad, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM tblInsumos_RayosX INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_RayosX.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = tblInsumos_RayosX.id_examen  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_RayosX.deleted is NULL AND tblInsumos_RayosX.cobrado_actual is NULL";
        $query_examenes_rayos_x = _query($sql_examenes_rayos_x);
        if(_num_rows($query_examenes_rayos_x) > 0){
            while($row_examenes = _fetch_array($query_examenes_rayos_x)){
                $precio = $row_examenes['precio_examen'];
                $cantidad = 1;
                $examenes_rayos_x+=($precio * $cantidad);
                $contador_rayos_x++;
            }
        }
        /* EL TOTAL DE INSUMOS DE EMERGENCIA SERA LA SUMA DE LOS PRODUCTOS QUE SE HAYAN
        UTILIZADO EN RAYOS X MAS LOS SERVICIOS QUE IGUAL SE HAN UTILIZADO EN ESTA AREA
        A EXCEPCION DE LOS EXAMENES QUE LLEVAN SU DETALLE APARTE */
        /*$total_rayos_x = $productos_rayos_x + $servicios_rayos_x;
        $array_detalle_cobro[] = array(
			'detalle' => 'COSTO DE SERVICIOS DE RAYOS X.',
			'precio' => ($total_rayos_x)
		);
    }
    /* SI NO EXISTE SE PROCEDE A IGNORAR ESTE TIPO DE COBRO YA QUE AL NO EXISTIR NINGUN
    INSUMO EN RAYOS X NO SE AGREGARA NADA AL COBRO */
    /*else{
        $contador_rayos_x = 0;
    }
    /* ACA TERMINA EL TERCER TIPO DE COBRO */
	/* -----------******************---------- */


     /*CUARTO TIPO DE COBRO 'COBROS DE SALA DE OPERACIONES'*/
    /*  ACA EMPIEZA EL CUARTO TIPO DE COBRO */

    /* SE VERIFICA QUE EXISTA ESE TIPO DE COBRO PRIMERAMENTE A TRAVES DE LA SIGUIENTE CONSULTA */

   /* $sql_sala_operaciones = "SELECT * FROM tblInsumos_Sala_Operaciones WHERE id_recepcion = '$id_recepcion' AND deleted is NULL AND cobrado_actual is NULL ";
    $query_sala_operaciones = _query($sql_sala_operaciones);
    /* SI EXISTE MAS DE 0 FILAS COMO RESPUESTA A LA CONSULTA SIGNIFICA QUE HAY POR LO MENOS
    UN INSUMO REGISTRADO EN PEDIATRIA A TRAVES DE ESTA RECEPCION */
   /* if(_num_rows($query_sala_operaciones) > 0){
        $numero_areas++;

        /* PRIMERO SE PROCEDERA A VERIFICAR QUE EXISTAN PRODUCTOS AGREGADOS EN LOS
        INSUMOS DE SALA DE OPERACIONES A TRAVES DE LA SIGUIENTE CONSULTA */
       /* $sql_productos_sala_operaciones = "SELECT tblInsumos_Sala_Operaciones.id_insumo, ".EXTERNAL.".producto.descripcion, tblInsumos_Sala_Operaciones.cantidad, tblInsumos_Sala_Operaciones.total, tblInsumos_Sala_Operaciones.created_at, ".EXTERNAL.".presentacion_producto.precio FROM tblInsumos_Sala_Operaciones INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Sala_Operaciones.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion_producto = tblInsumos_Sala_Operaciones.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Sala_Operaciones.deleted is NULL AND tblInsumos_Sala_Operaciones.cobrado_actual is NULL";
        $query_productos_sala_operaciones = _query($sql_productos_sala_operaciones);
        /* SE VERIFICA QUE HAYAN PRODUCTOS AGREGADOS DESDE SALA DE OPERACIONES */
        /*if(_num_rows($query_productos_sala_operaciones) > 0){
            while($row_productos = _fetch_array($query_productos_sala_operaciones)){
                $uso_de_sala_operaciones=1;
                $precio = $row_productos['precio'];
                $cantidad = $row_productos['cantidad'];
                $productos_sala_operaciones+=($precio * $cantidad);
                $contador_sala_operaciones++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS SERVICIOS QUE EL PACIENTE HA HECHO USO EN
        SALA DE OPERACIONES */
        /*$sql_servicios_sala_operaciones = "SELECT tblInsumos_Sala_Operaciones.id_insumo, ".EXTERNAL.".servicios_hospitalarios.descripcion, tblInsumos_Sala_Operaciones.created_at, tblInsumos_Sala_Operaciones.cantidad, tblInsumos_Sala_Operaciones.total, tblInsumos_Sala_Operaciones.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM tblInsumos_Sala_Operaciones INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Sala_Operaciones.id_recepcion  INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = tblInsumos_Sala_Operaciones.id_servicio WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Sala_Operaciones.deleted is NULL AND tblInsumos_Sala_Operaciones.cobrado_actual is NULL ";
        $query_servicios_sala_operaciones = _query($sql_servicios_sala_operaciones);
        /* SE VERIFICA QUE HAYAN SERVICIOS AGREGADOS DESDE SALA DE OPERACIONES */
        /*if(_num_rows($query_servicios_sala_operaciones) > 0){
            while($row_servicios = _fetch_array($query_servicios_sala_operaciones)){
                $uso_de_sala_operaciones=1;
                $precio = $row_servicios['precio'];
                $cantidad = $row_servicios['cantidad'];
                $servicios_sala_operaciones+=($precio * $cantidad);
                $contador_sala_operaciones++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS EXAMENES QUE EL PACIENTE HA HECHO USO
        SALA DE OPERACIONES */
        /*$sql_examenes_sala_operaciones = "SELECT tblInsumos_Sala_Operaciones.id_insumo, tblInsumos_Sala_Operaciones.id_examen,  tblInsumos_Sala_Operaciones.cantidad, tblInsumos_Sala_Operaciones.created_at, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM tblInsumos_Sala_Operaciones INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Sala_Operaciones.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = tblInsumos_Sala_Operaciones.id_examen  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Sala_Operaciones.deleted is NULL AND tblInsumos_Sala_Operaciones.cobrado_actual is NULL";
        $query_productos_sala_operaciones = _query($sql_examenes_sala_operaciones);
        if(_num_rows($query_productos_sala_operaciones) > 0){
            while($row_examenes = _fetch_array($query_productos_sala_operaciones)){
                $uso_de_sala_operaciones=1;
                $precio = $row_examenes['precio_examen'];
                $cantidad = 1;
                $examenes_sala_operaciones+=($precio * $cantidad);
                $contador_sala_operaciones++;
            }
        }
        /* EL TOTAL DE INSUMOS DE SALA DE OPERACIONES SERA LA SUMA DE LOS PRODUCTOS QUE SE HAYAN
        UTILIZADO EN SALA DE OPERACIONES MAS LOS SERVICIOS QUE IGUAL SE HAN UTILIZADO EN ESTA AREA
        A EXCEPCION DE LOS EXAMENES QUE LLEVAN SU DETALLE APARTE */
       /* $total_sala_operaciones = $productos_sala_operaciones + $servicios_sala_operaciones;
        $array_detalle_cobro[] = array(
			'detalle' => 'COSTO DE SERVICIOS DE SALA DE OPERACIONES.',
			'precio' => ($total_sala_operaciones)
		);
    }
    /* SI NO EXISTE SE PROCEDE A IGNORAR ESTE TIPO DE COBRO YA QUE AL NO EXISTIR NINGUN
    INSUMO EN SALA DE OPERACIONES NO SE AGREGARA NADA AL COBRO */
    /*else{
        $contador_sala_operaciones = 0;
    }
    /* ACA TERMINA EL CUARTO TIPO DE COBRO */
	/* -----------******************---------- */


    /*QUINTO TIPO DE COBRO 'COBROS DE SALA DE OPERACIONES'*/
    /*  ACA EMPIEZA EL QUINTO TIPO DE COBRO */

    /* SE VERIFICA QUE EXISTA ESE TIPO DE COBRO PRIMERAMENTE A TRAVES DE LA SIGUIENTE CONSULTA */

    /*$sql_pediatria = "SELECT * FROM tblInsumos_Pediatria WHERE id_recepcion = '$id_recepcion' AND deleted is NULL AND cobrado_actual is NULL";
    $query_pediatria = _query($sql_pediatria);
    /* SI EXISTE MAS DE 0 FILAS COMO RESPUESTA A LA CONSULTA SIGNIFICA QUE HAY POR LO MENOS
    UN INSUMO REGISTRADO EN PEDIATRIA A TRAVES DE ESTA RECEPCION */
    /*if(_num_rows($query_pediatria) > 0){
        $numero_areas++;
        /* PRIMERO SE PROCEDERA A VERIFICAR QUE EXISTAN PRODUCTOS AGREGADOS EN LOS
        INSUMOS DE PEDIATRIA A TRAVES DE LA SIGUIENTE CONSULTA */
        /*$sql_productos_pediatria = "SELECT tblInsumos_Pediatria.id_insumo, ".EXTERNAL.".producto.descripcion, tblInsumos_Pediatria.cantidad, tblInsumos_Pediatria.total, tblInsumos_Pediatria.created_at, ".EXTERNAL.".presentacion_producto.precio FROM tblInsumos_Pediatria INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Pediatria.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion_producto = tblInsumos_Pediatria.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Pediatria.deleted is NULL AND tblInsumos_Pediatria.cobrado_actual is NULL";
        $query_productos_pediatria = _query($sql_productos_pediatria);
        /* SE VERIFICA QUE HAYAN PRODUCTOS AGREGADOS DESDE PEDIATRIA */
        /*if(_num_rows($query_productos_pediatria) > 0){
            while($row_productos = _fetch_array($query_productos_pediatria)){
                $precio = $row_productos['precio'];
                $cantidad = $row_productos['cantidad'];
                $productos_pediatria+=($precio * $cantidad);
                $contador_pediatria++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS SERVICIOS QUE EL PACIENTE HA HECHO USO EN
        PEDIATRIA */
        /*$sql_servicios_pediatria = "SELECT tblInsumos_Pediatria.id_insumo, ".EXTERNAL.".servicios_hospitalarios.descripcion, tblInsumos_Pediatria.created_at, tblInsumos_Pediatria.cantidad, tblInsumos_Pediatria.total, tblInsumos_Pediatria.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM tblInsumos_Pediatria INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Pediatria.id_recepcion  INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = tblInsumos_Pediatria.id_servicio WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Pediatria.deleted is NULL AND tblInsumos_Pediatria.cobrado_actual is NULL";
        $query_servicios_pediatria = _query($sql_servicios_pediatria);
        /* SE VERIFICA QUE HAYAN SERVICIOS AGREGADOS DESDE PEDIATRIA */
        /*if(_num_rows($query_servicios_pediatria) > 0){
            while($row_servicios = _fetch_array($query_servicios_pediatria)){
                $precio = $row_servicios['precio'];
                $cantidad = $row_servicios['cantidad'];
                $servicios_pediatria+=($precio * $cantidad);
                $contador_pediatria++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS EXAMENES QUE EL PACIENTE HA HECHO USO
        PEDIATRIA */
        /*$sql_examenes_pediatria = "SELECT tblInsumos_Pediatria.id_insumo, tblInsumos_Pediatria.id_examen, tblInsumos_Pediatria.created_at,tblInsumos_Pediatria.cantidad, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM tblInsumos_Pediatria INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Pediatria.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = tblInsumos_Pediatria.id_examen  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Pediatria.deleted is NULL AND tblInsumos_Pediatria.cobrado_actual is NULL";
        $query_productos_pediatria = _query($sql_examenes_pediatria);
        if(_num_rows($query_productos_pediatria) > 0){
            while($row_examenes = _fetch_array($query_productos_pediatria)){
                $precio = $row_examenes['precio_examen'];
                $cantidad = 1;
                $examenes_pediatria+=($precio * $cantidad);
                $contador_pediatria++;
            }
        }
        /* EL TOTAL DE INSUMOS DE PEDIATRIA SERA LA SUMA DE LOS PRODUCTOS QUE SE HAYAN
        UTILIZADO EN PEDIATRIA MAS LOS SERVICIOS QUE IGUAL SE HAN UTILIZADO EN ESTA AREA
        A EXCEPCION DE LOS EXAMENES QUE LLEVAN SU DETALLE APARTE */
        /*$total_pediatria = $productos_pediatria + $servicios_pediatria;
        $array_detalle_cobro[] = array(
			'detalle' => 'COSTO DE SERVICIOS DE PEDIATRIA.',
			'precio' => ($total_pediatria)
		);
    }
    /* SI NO EXISTE SE PROCEDE A IGNORAR ESTE TIPO DE COBRO YA QUE AL NO EXISTIR NINGUN
    INSUMO EN PEDIATRIA NO SE AGREGARA NADA AL COBRO */
    /*else{
        $contador_pediatria = 0;
    }
    /* ACA TERMINA EL QUINTO TIPO DE COBRO */
	/* -----------******************---------- */

    /*SEXTO TIPO DE COBRO 'COBROS DE NEFROLOGIA'*/
    /*  ACA EMPIEZA EL SEXTO TIPO DE COBRO */

    /* SE VERIFICA QUE EXISTA ESE TIPO DE COBRO PRIMERAMENTE A TRAVES DE LA SIGUIENTE CONSULTA */

   /* $sql_nefrologia = "SELECT * FROM tblInsumos_Nefrologia WHERE id_recepcion = '$id_recepcion' AND deleted is NULL AND cobrado_actual is NULL ";
    $query_nefrologia = _query($sql_nefrologia);
    /* SI EXISTE MAS DE 0 FILAS COMO RESPUESTA A LA CONSULTA SIGNIFICA QUE HAY POR LO MENOS
    UN INSUMO REGISTRADO EN NEFROLOGIA A TRAVES DE ESTA RECEPCION */
    /*if(_num_rows($query_nefrologia) > 0){
        $numero_areas++;
        /* PRIMERO SE PROCEDERA A VERIFICAR QUE EXISTAN PRODUCTOS AGREGADOS EN LOS
        INSUMOS DE NEFROLOGIA A TRAVES DE LA SIGUIENTE CONSULTA */
        /*$sql_productos_nefrologia = "SELECT tblInsumos_Nefrologia.id_insumo, ".EXTERNAL.".producto.descripcion, tblInsumos_Nefrologia.cantidad, tblInsumos_Nefrologia.total, tblInsumos_Nefrologia.created_at, ".EXTERNAL.".presentacion_producto.precio FROM tblInsumos_Nefrologia INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Nefrologia.id_recepcion INNER JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion_producto = tblInsumos_Nefrologia.id_presentacion INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Nefrologia.deleted is NULL AND tblInsumos_Nefrologia.cobrado_actual is NULL";
        $query_productos_nefrologia = _query($sql_productos_nefrologia);
        /* SE VERIFICA QUE HAYAN PRODUCTOS AGREGADOS DESDE NEFROLOGIA */
        /*if(_num_rows($query_productos_nefrologia) > 0){
            while($row_productos = _fetch_array($query_productos_nefrologia)){
                $precio = $row_productos['precio'];
                $cantidad = $row_productos['cantidad'];
                $productos_nefrologia+=($precio * $cantidad);
                $contador_rayos_x++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS SERVICIOS QUE EL PACIENTE HA HECHO USO EN NEFROLOGIA */
        /*$sql_servicios_nefrologia = "SELECT tblInsumos_Nefrologia.id_insumo, tblInsumos_Nefrologia.created_at, ".EXTERNAL.".servicios_hospitalarios.descripcion, tblInsumos_Nefrologia.cantidad, tblInsumos_Nefrologia.total, tblInsumos_Nefrologia.hora_de_aplicacion, ".EXTERNAL.".servicios_hospitalarios.precio FROM tblInsumos_Nefrologia INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Nefrologia.id_recepcion  INNER JOIN ".EXTERNAL.".servicios_hospitalarios on ".EXTERNAL.".servicios_hospitalarios.id_servicio = tblInsumos_Nefrologia.id_servicio WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Nefrologia.deleted is NULL AND tblInsumos_Nefrologia.cobrado_actual is NULL";
        $query_servicios_nefrologia = _query($sql_servicios_nefrologia);
        /* SE VERIFICA QUE HAYAN SERVICIOS AGREGADOS DESDE NEFROLOGIA */
        /*if(_num_rows($query_servicios_nefrologia) > 0){
            while($row_servicios = _fetch_array($query_servicios_nefrologia)){
                $precio = $row_servicios['precio'];
                $cantidad = $row_servicios['cantidad'];
                $servicios_nefrologia+=($precio * $cantidad);
                $contador_rayos_x++;
            }
        }
        /* AHORA SE PROCEDERA A TRAER LOS EXAMENES QUE EL PACIENTE HA HECHO USO EN NEFROLOGIA */
        /*$sql_examenes_nefrologia = "SELECT tblInsumos_Nefrologia.id_insumo, tblInsumos_Nefrologia.id_examen, tblInsumos_Nefrologia.created_at,tblInsumos_Nefrologia.cantidad, labangel.examen.nombre_examen, labangel.examen.precio_examen FROM tblInsumos_Nefrologia INNER JOIN recepcion on recepcion.id_recepcion = tblInsumos_Nefrologia.id_recepcion INNER JOIN labangel.examen on labangel.examen.id_examen = tblInsumos_Nefrologia.id_examen  WHERE recepcion.id_recepcion = '$id_recepcion' AND tblInsumos_Nefrologia.deleted is NULL AND tblInsumos_Nefrologia.cobrado_actual is NULL";
        $query_examenes_nefrologia = _query($sql_examenes_nefrologia);
        if(_num_rows($query_examenes_nefrologia) > 0){
            while($row_examenes = _fetch_array($query_examenes_nefrologia)){
                $precio = $row_examenes['precio_examen'];
                $cantidad = 1;
                $examenes_nefrologia+=($precio * $cantidad);
                $contador_rayos_x++;
            }
        }
        /* EL TOTAL DE INSUMOS DE EMERGENCIA SERA LA SUMA DE LOS PRODUCTOS QUE SE HAYAN
        UTILIZADO EN RAYOS X MAS LOS SERVICIOS QUE IGUAL SE HAN UTILIZADO EN ESTA AREA
        A EXCEPCION DE LOS EXAMENES QUE LLEVAN SU DETALLE APARTE */
        /*$total_nefrologia = $productos_nefrologia + $servicios_nefrologia;
        $array_detalle_cobro[] = array(
			'detalle' => 'COSTO DE SERVICIOS DE NEFROLOGIA.',
			'precio' => ($total_nefrologia)
		);
    }
    /* SI NO EXISTE SE PROCEDE A IGNORAR ESTE TIPO DE COBRO YA QUE AL NO EXISTIR NINGUN
    INSUMO EN RAYOS X NO SE AGREGARA NADA AL COBRO */
    /*else{
        $contador_nefrologia = 0;
    }
    /* ACA TERMINA EL TERCER TIPO DE COBRO */
	/* -----------******************---------- */


    /* EN LA VARIABLE $total_examenes SE ALMACENARA EL TOTAL EN CONCEPTO DE EFECTIVO
    DE LAS AREAS DE HOSPITALIZACION, EMERGENCIA, RAYOS X, SALA DE OPERACIONES Y PEDIATRIA */
    /*$total_examenes = $examenes_hospitalizacion + $examenes_emergencia + $examenes_rayos_x+ $examenes_sala_operaciones + $examenes_pediatria + $examenes_nefrologia;
    /* SI EL TOTAL EXAMENES ES MAYOR A CERO SIGNIFICA QUE HAY POR LO MENOS UN EXAMEN COBRADO
    ENTONCES SE CREA UN NUEVO DETALLE COBRO CON EL CONCEPTO 'COSTO DE SERVICIOS DE
    LABORATORIO' */
    /*if($total_examenes > 0){
        $numero_areas++;
        $array_detalle_cobro[] = array(
			'detalle' => 'COSTO DE SERVICIOS DE LABORATORIO.',
			'precio' => ($total_examenes)
		);
    }
    /* SUBTOTAL COBRO ES EL TOTAL QUE DA DE LA SUMATORIA DE LOS PRODUCTOS Y SERVICIOS APLICADOS
    EN LAS AREAS DE HOSPITALIZACION, EMERGENCIA, RAYOS X, SALA DE OPERACIONES  Y PEDIATRIA */
    $subtotal_cobro = $total_hospitalizacion + $total_emergencia + $total_rayos_x + $total_sala_operaciones + $total_pediatria + $total_nefrologia;
    /* MAS LOS EXAMENES CLARAMENTE */
    /*$subtotal_cobro+= $total_examenes;
    /* SI HAY UNA HOSPITALIZACION O UNA PEQUENIA CIRUGIA ENTONCES SE PROCEDERA A CREAR
    2 CAMPOS MAS, LO CUALES SERAN EL 30 Y 10 POR CIENTO RESPECTIVAMENTE DE CUIDADOS HOSPITALARIOS */
    if($contador_hospitalizacion > 0 || $pequenia_cirugia_activa == 1 || $uso_de_sala_operaciones==1){
        $numero_areas+=2;
        $total_honorarios_hospital = 0;
		foreach ($array_detalle_cobro as $key => $value) {
			$total_honorarios_hospital += $value['precio'];
		}
        $servicios_administrativos = $total_honorarios_hospital * 0.30;
		$servicios_generales = $total_honorarios_hospital * 0.10;
        $array_detalle_cobro[] = array(
			'detalle' => 'SERVICIOS ADMINISTRATIVOS Y ENFERMERIA.',
			'precio' => ($servicios_administrativos)
		);
        $array_detalle_cobro[] = array(
			'detalle' => 'SERVICIOS GENERALES HOSPITALARIOS.',
			'precio' => ($servicios_generales)
		);
    }
    if($numero_areas > $maximo_columnas){
        $total_honorarios_hospital = 0;
		foreach ($array_detalle_cobro as $key => $value) {
            //echo "<br><br>DESC: ".$value['detalle']." - - - PRECIO: ".$value['precio']."<br><br>";
			$total_honorarios_hospital += $value['precio'];
		}
		$array_final_cobro = array();
		$array_final_cobro[] = array(
			'detalle' => 'COSTOS POR SERVICIOS HOSPITALARIOS.',
			'precio' => $total_honorarios_hospital
		);
        $array_detalle_cobro = $array_final_cobro;
    }
    $iva = 0;
    if($contador_hospitalizacion > 0 || $contador_emergencia > 1 || $uso_de_sala_operaciones==1){
        $total_honorarios_hospital = 0;
        $array_final_cobro = array();
		foreach ($array_detalle_cobro as $key => $value) {
			$array_final_cobro[] = array(
                'detalle' => $value['detalle'],
                'precio' => ($value['precio']*1.13)
            );
            $iva += $value['precio']*1.13;
		}
        $array_detalle_cobro = $array_final_cobro;
    }
    /* ESTO SE HARA PARA CALCULAR EL TOTAL FINAL QUE SALDRA EN LA ".EXTERNAL.".factura */
    $total_honorarios_hospital = 0;
    foreach ($array_detalle_cobro as $key => $value) {
        $total_honorarios_hospital += $value['precio'];
    }
    $hora = date("H:i:s");


    //date_default_timezone_set('America/El_Salvador');
    $id_factura=0;
    $fecha_movimiento= date("Y:m:d");
    $id_cliente=1;
    $id_vendedor=$_SESSION['id_usuario'];
    //  IMPUESTOS
    $fecha_actual = date('Y-m-d');

    $insertar_fact=false;
    $insertar_fact_dett=true;
    $insertar_numdoc =false;

    $hora=date("H:i:s");
    $xdatos['typeinfo']='';
    $xdatos['msg']='';
    $xdatos['process']='';

   

    $a=1;
    $b=1;
    $c=1;

    if($id_factura==0)
    {
      $hoy = date("Y-m-d");
      $sql="SELECT MAX(numero_ref) as ref FROM ".EXTERNAL.".factura WHERE id_sucursal='$id_sucursal' AND fecha='$hoy'";
      $result= _query($sql);
      $rows=_fetch_array($result);
      $ult=$rows['ref']+1;
      $numero_doc = str_pad($ult,7,"0",STR_PAD_LEFT)."_REF";
    }
    else
    {
      $sql_num=_fetch_array(_query("SELECT * FROM ".EXTERNAL.".factura where id_factura=$id_factura"));
      $numero_doc=$sql_num['numero_doc'];
      $ult=$sql_num['numero_ref'];
    }

    $abono=0;
    $saldo=0;
    $tipo_documento="";
    $tipo_entrada_salida='NUM. REFERENCIA INTERNA';
    $id_empleado = $_SESSION['id_usuario'];

    # code...
    $table_fact= "".EXTERNAL.".factura";
    $form_data_fact = array(
      'id_server' => '0',
      'id_cliente' => $id_cliente,
      'fecha' => $fecha_movimiento,
      'numero_doc' => $numero_doc,
      'referencia' => $numero_doc,
      'numero_ref' => $ult,
      'subtotal' => $total_honorarios_hospital,
      'sumas'=>$total_honorarios_hospital,
      'suma_gravado'=>$total_honorarios_hospital,
      'iva' =>$iva,
      'retencion'=>0,
      'venta_exenta'=>0,
      'total_menos_retencion'=>$total_honorarios_hospital,
      'total' => $total_honorarios_hospital,
      'id_usuario'=>$id_vendedor,
      'id_empleado' => $id_vendedor,
      'id_sucursal' => $id_sucursal,
      'tipo' => $tipo_entrada_salida,
      'hora' => $hora,
      'finalizada' => '0',
      'abono'=>$abono,
      'saldo' => $saldo,
      'tipo_documento' => $tipo_documento,
      'descuento' => '0',
      'porcentaje' => '0',
      'impresa' => '0',
      'serie' => '0',
      'num_fact_impresa' => '0',
      'turno' => '0',
      'id_apertura' => '0',
      'id_apertura_pagada' => '0',
      'credito' => '0',
      'afecta' => '0',
      'caja' => '0',
      'nombre' => '',
      'direccion' => '',
        'id_recepcion' => $id_recepcion
    );
    $insertar_fact = _insert($table_fact,$form_data_fact );
    $id_fact= _insert_id();

    if (!$insertar_fact) {
      # code...
      $b=0;
    }

    if ($subtotal_cobro>0)
    {
      foreach ($array_detalle_cobro as $key => $value)
      {
        $table_fact_det= "".EXTERNAL.".factura_detalle";
        $data_fact_det = array(
          'id_server' => '0',
          'id_factura' => $id_fact,
          'id_prod_serv' => 0,
          'cantidad' => 1,
          'precio_venta' => 0,
          'subtotal' => $value['precio'],
          'tipo_prod_serv' => "INSUMO",
          'id_empleado' => $id_empleado,
          'id_sucursal' => $id_sucursal,
          'fecha' => $fecha_movimiento,
          'id_presentacion'=> 0,
          'exento' => 0,
          'descuento' => '0',
          'id_server_prod' => '0',
          'id_factura_dia' => '0',
          'impresa_lote' => '0',
          'hora' => date("H:i:s"),
          'id_server_presen' => '0',
          'servicio' => 0,
          'id_recepcion' => $id_recepcion,
          'descripcion_hospitaliazion' => $value['detalle']
        );
        $insertar_fact_det = _insert($table_fact_det,$data_fact_det );
        if (!$insertar_fact_det) {
          # code...
          $c=0;
        }
      } //foreach ($array as $fila){
        if ($a&&$b&&$c)
        {
            $tabla_recepcion = 'recepcion';
            $form_data_recepcion = array(
                'id_estado_recepcion' => '3'
            );
            $where_recepcion = " id_recepcion = '$id_recepcion'";
            $insert3 = _update($tabla_recepcion, $form_data_recepcion, $where_recepcion);
            if($insert3){
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Recepcion ingresada correctamente!';
                _commit();
            }
            else{
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='La Recepcion no se pudo ingresar!';
                _rollback();
            }
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Referenca Numero: <strong>'.$numero_doc.'</strong>  Guardado con Exito !';
            $xdatos['referencia']=$ult;
            $xdatos['tot']=number_format($total_honorarios_hospital,2);
        }
        else
        {
          _rollback(); // transaction rolls back
          $xdatos['typeinfo']='Error';
          $xdatos['msg']='Registro no pudo ser ingresado!'.$a."-".$b."-".$c;
        }
      }
      echo json_encode($xdatos);
}

if (! isset ($_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
			case 'anular' :
				initial();
				break;
			case 'anular_datos' :
				anular_datos();
                break;
            case 'recuperar':
                recuperar();
            break;
            case 'recuperar_bd':
                recuperarBD();
            break;
            case 'comprobar_recepciones':
                comprobar_recepciones();
                break;
            case 'ingregar_cobros':
                ingresar_cobros();
                break;
		}
	}
}
?>