<?php
include ("_core.php");
$id_hospitalizacion = $_REQUEST['id_hospitalizacion'];
$id_sucursal = $_SESSION['id_sucursal'];
$sql1="SELECT CONCAT(paciente.nombres, ' ', paciente.apellidos) as 'nombre_paciente', hospitalizacion.momento_entrada, hospitalizacion.total, hospitalizacion.momento_salida, recepcion.evento, estado_hospitalizacion.estado as 'estado_hospitalizacion', pisos.numero_piso, cuartos.id_cuarto, pisos.descripcion as 'descripcion_piso', cuartos.numero_cuarto, cuartos.descripcion as 'descripcion_cuarto', estado_cuarto.estado as 'estado_cuarto', tipo_cuarto.tipo, tipo_cuarto.descripcion as 'descripcion_tipo_cuarto', tipo_cuarto.cantidad, hospitalizacion.precio_habitacion, hospitalizacion.minuto FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN cuartos on cuartos.id_cuarto = hospitalizacion.id_cuarto_H INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion INNER JOIN estado_hospitalizacion on estado_hospitalizacion.id_estado_hospitalizacion = hospitalizacion.id_estado_hospitalizacion INNER JOIN estado_cuarto on estado_cuarto.id_estado_cuarto = cuartos.id_estado_cuarto_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto WHERE recepcion.id_sucursal_recepcion = '$id_sucursal' AND hospitalizacion.id_hospitalizacion = '$id_hospitalizacion'";
$consulta1 = _query($sql1);
$row1 = _fetch_array($consulta1);
$nombre_paciente = $row1['nombre_paciente'];
$momento_entrada = $row1['momento_entrada'];
$momento_salida = $row1['momento_salida'];
$evento = $row1['evento'];
$id_cuarto = $row1['id_cuarto'];
$estado_hospitalizacion = $row1['estado_hospitalizacion'];
$estado_hospitalizacion_F = $row1['estado_hospitalizacion'];
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
$tota_final = $row1['total'];

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

$fecha1 = new DateTime($momento_entrada);
$fecha2 =  new DateTime(date("Y-m-d H:i:s"));
$diff = $fecha1->diff($fecha2);

$Diferencia_de_tiempo = "";
$activo_con = 0;
if($diff->y > 0){
    if($diff->y == 1){
        $Diferencia_de_tiempo += "1 año";
    }
    else{
        $Diferencia_de_tiempo += "".strval($diff->y)." años";
    }
    $activo_con++;
}
if($diff->m > 0){
    if($activo_con == 0){
        if($diff->m == 1){
            $Diferencia_de_tiempo = "1 mes";
        }
        else{
            $Diferencia_de_tiempo = strval($diff->m)." meses";
        }
    }
    else{
        if($diff->m == 1){
            $Diferencia_de_tiempo += " con 1 mes";
        }
        else{
            $Diferencia_de_tiempo += " con ".strval($diff->m)." meses";
        }
        $activo_con++;
    }
}

if($diff->d > 0){
    if($activo_con == 0){
        if($diff->d == 1){
            $Diferencia_de_tiempo = "1 dia";
        }
        else{
            $Diferencia_de_tiempo = strval($diff->d)." dias";
        }
    }
    else{
        if($diff->d == 1){
            $Diferencia_de_tiempo .= " con 1 dia";
        }
        else{
            $Diferencia_de_tiempo .= " con ".strval($diff->d)." dias";
        }
    }
    $activo_con++;
}
if($diff->h > 0){
    if($activo_con == 0){
        if($diff->h == 1){
            $Diferencia_de_tiempo = "1 hora";
        }
        else{
            $Diferencia_de_tiempo = strval($diff->h)." horas";
        }
    }
    else{
        if($diff->h == 1){
            $Diferencia_de_tiempo .= " con 1 hora";
        }
        else{
            $Diferencia_de_tiempo .= " con ".strval($diff->h)." horas";
        }
    }
    $activo_con++;
}
if($diff->i > 0){
    if($activo_con == 0){
        if($diff->i == 1){
            $Diferencia_de_tiempo = "1 minuto";
        }
        else{
            $Diferencia_de_tiempo= strval($diff->i)." minuto";
        }
    }
    else{
        if($diff->i == 1){
            $Diferencia_de_tiempo .= " con 1 minuto";
        }
        else{
            $Diferencia_de_tiempo .= " con ".strval($diff->i)." minutos";
        }
    }
    $activo_con++;
}
$tablas="";
$tablas.="<table class='table table-bordered'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Id Hospitalizacion</th>";
$tablas.="<th scope='col'>Nombre Paciente</th>";
$tablas.="<th scope='col'>Numero Piso</th>";
$tablas.="<th scope='col'>Descripcion Piso</th>";
$tablas.="<th scope='col'>Numero Cuarto</th>";
$tablas.="<th scope='col'>Descripcion Cuarto</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<th scope='row'>".$id_hospitalizacion."</th>";
$tablas.="<th scope='row'>".$nombre_paciente."</th>";
$tablas.="<th scope='row'>".$numero_piso."</th>";
$tablas.="<th scope='row'>".$descripcion_piso."</th>";
$tablas.="<th scope='row'>".$numero_cuarto."</th>";
$tablas.="<th scope='row'>".$descripcion_cuarto."</th>";
$tablas.="</tr>";
$tablas.="</tbody>";
$tablas.="</table>";

if($cantidad == 1){
    $cantidad = "1 persona";
}
if($cantidad > 1){
    $cantidad = $cantidad." personas";
}
$precio_por_hora = "";
if($estado_hospitalizacion == 'PENDIENTE'){
    $estado_hospitalizacion = "<label class='badge' style='background:#11DCD9; color:#FFF; font-weight:bold;'>PENDIENTE</label>";
    $precio_por_hora= "<label class='badge' style='background:#11DCD9; color:#FFF; font-weight:bold;'>Pendiente de Ingresar</label>";
}
if($estado_hospitalizacion == 'EN HABITACION'){
   $estado_hospitalizacion= "<label class='badge' style='background:#39DC11; color:#FFF; font-weight:bold;'>EN HABITACION</label>";
   $total = calcular_precio_hospitalizacion($precio_habitacion, $momento_entrada, $minuto);
   $precio_por_hora = number_format($total, 5);
   $precio_por_hora= "<p style='color:#008704'>$".$precio_por_hora."</p>";
}
if($estado_hospitalizacion == 'FINALIZADA'){
    $estado_hospitalizacion="<label class='badge' style='background:#DC112D; color:#FFF; font-weight:bold;'>FINALIZADA</label>";
    $precio_por_hora= "<label class='badge' style='background:#E033FF; color:#FFF; font-weight:bold;'>$".number_format($tota_final, 5)."</label>";
}
if($estado_hospitalizacion == 'ANULADA'){
    $estado_hospitalizacion="<label class='badge' style='background:#DC112D; color:#FFF; font-weight:bold;'>ANULADA</label>";
    $precio_por_hora= "<label class='badge' style='background:#FCFF33; color:#FFF; font-weight:bold;'>Hospitalizacion anulada</label>";
}

if($estado_cuarto == 'DISPONIBLE'){
    $estado_cuarto ="<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>DISPONIBLE</label>";
}
if($estado_cuarto == 'OCUPADO'){
    $estado_cuarto ="<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>OCUPADO</label>";
}
if($estado_cuarto == 'MANTENIMIENTO'){
    $estado_cuarto = "<label class='badge' style='background:#A6B900; color:#FFF; font-weight:bold;'>MANTENIMIENTO</label>";
}
if($cantidad_hospitalizaciones == 0){
    $cantidad_hospitalizaciones = "Ninguna persona";
}
if($cantidad_hospitalizaciones == 1){
    $cantidad_hospitalizaciones = "1 persona";
}
if($cantidad_hospitalizaciones > 1){
    $cantidad_hospitalizaciones = $cantidad_hospitalizaciones." personas";
}
$tablas.="<table class='table table-bordered'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Tipo de cuarto</th>";
$tablas.="<th scope='col'>Descripcion tipo cuarto</th>";
$tablas.="<th scope='col'>Capacidad cuarto</th>";
$tablas.="<th scope='col'>Estado Cuarto</th>";
$tablas.="<th scope='col'>Estado Hospitalizacion</th>";
$tablas.="<th scope='col'>Pacientes actuales</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<th scope='row'>".$tipo."</th>";
$tablas.="<th scope='row'>".$descripcion_tipo_cuarto."</th>";
$tablas.="<th scope='row'>".$cantidad."</th>";
$tablas.="<th scope='row'>".$estado_cuarto."</th>";
$tablas.="<th scope='row'>".$estado_hospitalizacion."</th>";
$tablas.="<th scope='row'>".$cantidad_hospitalizaciones."</th>";
$tablas.="</tr>";
$tablas.="</tbody>";
$tablas.="</table>";



$tablas.="<table class='table table-bordered'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Fecha de entrada</th>";
$tablas.="<th scope='col'>Hora de entrada</th>";
$tablas.="<th scope='col'>Fecha de salida</th>";
$tablas.="<th scope='col'>Hora de salida</th>";
$tablas.="<th scope='col'>Tiempo hospitalizado</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<th scope='row'>".$fecha_entrada."</th>";
$tablas.="<th scope='row'>".$hora_entrada."</th>";
$tablas.="<th scope='row'>".$fecha_salida."</th>";
$tablas.="<th scope='row'>".$hora_salida."</th>";
$tablas.="<th scope='row'>".$Diferencia_de_tiempo."</th>";
$tablas.="</tr>";
$tablas.="</tbody>";
$tablas.="</table>";

$momento = "";
$momentos_pasados = "";
if($minuto == 0){
     $minuto ="<label class='badge' style='background:#FF4646; color:#FFF; font-weight:bold;'>A la hora</label>";
    if($estado_hospitalizacion_F == 'PENDIENTE'){
        $momentos_pasados = "<label class='badge' style='background:#11DCD9; color:#FFF; font-weight:bold;'>Pendiente de ingresar</label>";
    }
    if($estado_hospitalizacion_F == 'EN HABITACION'){
        $minuto ="<label class='badge' style='background:#FF4646; color:#FFF; font-weight:bold;'>A la hora</label>";
        $momentos_pasados =calcular_momentos(10, $momento_entrada, 0);
    }
    if($estado_hospitalizacion_F == 'FINALIZADA'){
        $momentos_pasados =calcular_momentos_salida(10, $momento_entrada, 0, $momento_salida);
    }
    if($estado_hospitalizacion_F == 'ANULADA'){
        $momentos_pasados="<label class='badge' style='background:#DC112D; color:#FFF; font-weight:bold;'>Hospitalizacion Anulada</label>";
    }
    $precio_habitacion = "$".number_format($precio_habitacion,5);
    $momento = "Horas pasadas";
    $minuto ="<label class='badge' style='background:#FF4646; color:#FFF; font-weight:bold;'>A la hora</label>";

}
if($minuto == 1){
    if($estado_hospitalizacion_F == 'PENDIENTE'){
        $momentos_pasados = "<label class='badge' style='background:#11DCD9; color:#FFF; font-weight:bold;'>Pendiente de ingresar</label>";
    }
    if($estado_hospitalizacion_F == 'EN HABITACION'){

        $momentos_pasados =calcular_momentos(10, $momento_entrada,1);
    }
    if($estado_hospitalizacion_F == 'FINALIZADA'){
        $momentos_pasados="<label class='badge' style='background:#DC112D; color:#FFF; font-weight:bold;'>Hospitalizacion finalizada</label>";
    }
    if($estado_hospitalizacion_F == 'ANULADA'){
        $momentos_pasados="<label class='badge' style='background:#DC112D; color:#FFF; font-weight:bold;'>Hospitalizacion Anulada</label>";
    }
    $total_x = $precio_habitacion/60;
        $precio_habitacion = "$".number_format($total_x, 5);
    $momento = "Minutos pasados";
    $minuto ="<label class='badge' style='background:#2EC824; color:#FFF; font-weight:bold;'>Al minuto</label>";
}


$tablas.="<table class='table table-bordered'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Tipo de Cobro</th>";
$tablas.="<th scope='col'>Precio por tipo de cobro</th>";
$tablas.="<th scope='col'>".$momento."</th>";
$tablas.="<th scope='col'>Total</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<th scope='row'>".$minuto."</th>";
$tablas.="<th scope='row'>".$precio_habitacion."</th>";
$tablas.="<th scope='row'>".$momentos_pasados."</th>";
$tablas.="<th scope='row'>".$precio_por_hora."</th>";
$tablas.="</tr>";
$tablas.="</tbody>";
$tablas.="</table>";




$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title text-navy">Datos de la hospitalizacion</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
            <?php	if ($links!='NOT' || $admin=='1' ){ ?>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-info text-center alert alert-info">
                          <label><?php echo "Informacion de la hospitalizacion a ".$nombre_paciente; ?></label>
                      </div>
                    </div>
                    <br>
                    <div class='col-md-12'>
                        <?php echo $tablas; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
<?php
    echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
    </div><!--/modal-footer -->";
}
else {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
}


function calcular_precio_hospitalizacion($preciox, $horax, $minutoXX){
    setlocale(LC_TIME, "es_SV.UTF-8");
    date_default_timezone_set("America/El_Salvador");
    $fecha1 =  new DateTime($horax);//fecha inicial
    $fecha2 =  new DateTime(date("Y-m-d H:i:s"));//fecha actual

    $diff = $fecha1->diff($fecha2);
    $mes_actual = date('m');
    $anio_actual = date('Y');
    $valor_anio = intval($anio_actual);
    $mes_valor = intval($mes_actual);
    $numero_dias_meses=0;
    $mes_alcance = intval( ($diff -> m));
    for($i =1; $i <= $mes_alcance; $i++){
        if($mes_actual -$i == 0){
            $mes_actual = 13;
            $anio_actual = $anio_actual-1;
        }
        $numero_dias_meses += cal_days_in_month(CAL_GREGORIAN, ($mes_actual-$i), $anio_actual);
    }

    $minutos_meses = (($numero_dias_meses*24)*60);
    $minutos_anios = (($diff->y *365)*60*24);
    $minutos_dias = ( ($diff->days * 24 ) * 60 );
    $minutos_horas = ( $diff->h * 60 );
    $minutos_normales = ($diff -> i);
    $precio_hora = $preciox;
    $minutos = ($minutos_anios+$minutos_meses+$minutos_dias+$minutos_horas+$minutos_normales);
    $horas = round(($minutos_anios+$minutos_meses+$minutos_dias+$minutos_horas+$minutos_normales)/60);
    $precio_minuto_x = $precio_hora/60;
    if($minutoXX == 0){
        $precio_total = $horas* $precio_hora;
        return $precio_total;
    }
    else{
        $precio_total = $minutos* $precio_minuto_x;
        return $precio_total;
    }
}

function calcular_momentos($preciox, $horax, $minutoXX){
    setlocale(LC_TIME, "es_SV.UTF-8");
    date_default_timezone_set("America/El_Salvador");
    $fecha1 =  new DateTime($horax);//fecha inicial
    $fecha2 =  new DateTime(date("Y-m-d H:i:s"));//fecha actual

    $diff = $fecha1->diff($fecha2);
    $mes_actual = date('m');
    $anio_actual = date('Y');
    $valor_anio = intval($anio_actual);
    $mes_valor = intval($mes_actual);
    $numero_dias_meses=0;
    $mes_alcance = intval( ($diff -> m));
    for($i =1; $i <= $mes_alcance; $i++){
        if($mes_actual -$i == 0){
            $mes_actual = 13;
            $anio_actual = $anio_actual-1;
        }
        $numero_dias_meses += cal_days_in_month(CAL_GREGORIAN, ($mes_actual-$i), $anio_actual);
    }

    $minutos_meses = (($numero_dias_meses*24)*60);
    $minutos_anios = (($diff->y *365)*60*24);
    $minutos_dias = ( ($diff->days * 24 ) * 60 );
    $minutos_horas = ( $diff->h * 60 );
    $minutos_normales = ($diff -> i);
    $precio_hora = $preciox;
    $minutos = ($minutos_anios+$minutos_meses+$minutos_dias+$minutos_horas+$minutos_normales);
    $horas = round(($minutos_anios+$minutos_meses+$minutos_dias+$minutos_horas+$minutos_normales)/60);
    $precio_minuto_x = $precio_hora/60;
    if($minutoXX == 0){
        return $horas;
    }
    else{
        return $minutos;
    }
}
function calcular_momentos_salida($preciox, $horax, $minutoXX, $hora_x2){
    setlocale(LC_TIME, "es_SV.UTF-8");
    date_default_timezone_set("America/El_Salvador");
    $fecha1 =  new DateTime($horax);//fecha inicial
    $fecha2 =  new DateTime($hora_x2);//fecha actual

    $diff = $fecha1->diff($fecha2);
    $mes_actual = date('m');
    $anio_actual = date('Y');
    $valor_anio = intval($anio_actual);
    $mes_valor = intval($mes_actual);
    $numero_dias_meses=0;
    $mes_alcance = intval( ($diff -> m));
    for($i =1; $i <= $mes_alcance; $i++){
        if($mes_actual -$i == 0){
            $mes_actual = 13;
            $anio_actual = $anio_actual-1;
        }
        $numero_dias_meses += cal_days_in_month(CAL_GREGORIAN, ($mes_actual-$i), $anio_actual);
    }

    $minutos_meses = (($numero_dias_meses*24)*60);
    $minutos_anios = (($diff->y *365)*60*24);
    $minutos_dias = ( ($diff->days * 24 ) * 60 );
    $minutos_horas = ( $diff->h * 60 );
    $minutos_normales = ($diff -> i);
    $precio_hora = $preciox;
    $minutos = ($minutos_anios+$minutos_meses+$minutos_dias+$minutos_horas+$minutos_normales);
    $horas = round(($minutos_anios+$minutos_meses+$minutos_dias+$minutos_horas+$minutos_normales)/60);
    $precio_minuto_x = $precio_hora/60;
    if($minutoXX == 0){
        return $horas;
    }
    else{
        return $minutos;
    }
}
?>
