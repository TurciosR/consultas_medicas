<?php
include("_core.php");

$requestData= $_REQUEST;
$fini= MD($_REQUEST["fini"]);
$fin= MD($_REQUEST["fin"]);

require('ssp.customized.class.php');
// DB table to use
$table = 'hospitalizacion';
// Table's primary key
$primaryKey = 'id_hospitalizacion';

// MySQL server connection information
$sql_details = array(
  'user' => $usuario,
  'pass' => $clave,
  'db'   => $dbname,
  'host' => $servidor
);
/*SELECT factura.fecha, CONCAT(cliente.nombre,' ',cliente.apellido) AS nombre, factura.num_fact_impresa,factura.total,factura.abono,factura.saldo FROM factura JOIN cliente ON cliente.id_cliente=factura.id_cliente WHERE factura.factura=1 */
//permiso del script
$id_sucursal=$_SESSION['id_sucursal'];
$joinQuery ="   FROM hospitalizacion INNER JOIN recepcion on hospitalizacion.id_recepcion = recepcion.id_recepcion INNER JOIN cuartos on cuartos.id_cuarto = hospitalizacion.id_cuarto_H INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN estado_hospitalizacion on estado_hospitalizacion.id_estado_hospitalizacion = hospitalizacion.id_estado_hospitalizacion INNER JOIN doctor ON hospitalizacion.id_doctor_at=doctor.id_doctor";
$extraWhere = " recepcion.id_sucursal_recepcion = '$id_sucursal'  AND hospitalizacion.deleted is NULL and hospitalizacion.momento_entrada BETWEEN '$fini 00:00:00' AND '$fin 23:59:59'";/*	AND factura.fecha BETWEEN '$fechai' AND '$fechaf' */

$columns = array(
    array( 'db' => 'hospitalizacion.id_hospitalizacion', 'dt' => 0, 'field' => 'id_hospitalizacion' ),
    array( 'db' => "CONCAT(paciente.nombres,' ', COALESCE(paciente.apellidos,'') )", 'dt' => 1, 'field' => "paciente", 'as'=>'paciente'),
    array('db'=>"CONCAT(doctor.nombres, ' ', doctor.apellidos)", 'dt'=>2, 'field'=>"nombre_doctor", 'as'=>'nombre_doctor'),
    array( 'db' => "hospitalizacion.momento_entrada", 'dt' =>3, 'formatter' => function($momento_entrada){
        $form = explode(" ", $momento_entrada);
        $hora = _hora_media_decode($form[1]);
        $fecha = ED($form[0]);
        return "El dia ".$fecha." a las ".$hora;
    }, 'field' => 'momento_entrada'),
    array( 'db' => "hospitalizacion.momento_salida", 'dt' =>4, 'formatter' => function($momento_salida){
        if ($momento_salida != "") {
            $form = explode(" ", $momento_salida);
            $hora = _hora_media_decode($form[1]);
            $fecha = ED($form[0]);
            return "El dia ".$fecha." a las ".$hora;
        } else {
            return "No tiene tiempo de salida";
        }
    }, 'field' => 'momento_salida'),
    array( 'db' => 'hospitalizacion.minuto', 'dt' =>5, 'formatter' => function($minuto){
        if($minuto == 0){
            return "<label class='badge' style='background:#FF4646; color:#FFF; font-weight:bold;'>A la hora</label>";
        }
        else{
            return "<label class='badge' style='background:#2EC824; color:#FFF; font-weight:bold;'>Al minuto</label>";
        }
    }, 'field' => 'minuto' ),
    array( 'db' => 'hospitalizacion.id_hospitalizacion', 'dt' =>6, 'formatter' => function($id_hospitalizacion){
        $id_sucursal = $_SESSION['id_sucursal'];
        $sql = "SELECT hospitalizacion.precio_habitacion, hospitalizacion.momento_entrada, hospitalizacion.total, hospitalizacion.minuto, hospitalizacion.id_estado_hospitalizacion FROM hospitalizacion INNER JOIN cuartos on cuartos.id_cuarto = hospitalizacion.id_cuarto_H INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND pisos.id_ubicacion_piso = '$id_sucursal'";
        $query = _query($sql);
        $numero = _num_rows($query);
        if($numero > 0 ){
            $row = _fetch_array($query);
            $estado_hos = $row['id_estado_hospitalizacion'];
            $precio = $row['precio_habitacion'];
            $momento_entrada = $row['momento_entrada'];
            $minuto = $row['minuto'];
            $total_X = $row['total'];
            if($estado_hos == 1){
                $precio_por_hora= "<label class='badge' style='background:#11DCD9; color:#FFF; font-weight:bold;'>Pendiente de Ingresar</label>";
                return $precio_por_hora;
            }
            if($estado_hos == 2){
                $total = calcular_precio_hospitalizacion($precio, $momento_entrada, $minuto);
                $precio_por_hora = number_format($total, 5);
                $precio_por_hora= "<p style='color:#008704'>$".$precio_por_hora."</p>";
                return $precio_por_hora;
            }
            if($estado_hos == 3){
                $precio_por_hora = number_format($total_X, 5);
                $precio_por_hora= "<p style='color:#008704'>$".$precio_por_hora."</p>";
                return $precio_por_hora;
            }
            if($estado_hos ==4){
                $precio_por_hora= "<label class='badge' style='background:#FCFF33; color:#FFF; font-weight:bold;'>Hospitalizacion anulada</label>";;
                return $precio_por_hora;
            }

        }
        else{
            return "x";
        }
    }, 'field' => 'id_hospitalizacion' ),
    array( 'db' => 'hospitalizacion.id_hospitalizacion', 'dt' =>7, 'formatter' => function($id_hospitalizacion){
        $id_sucursal = $_SESSION['id_sucursal'];
        $sql = "SELECT cuartos.numero_cuarto, cuartos.descripcion, hospitalizacion.minuto, pisos.numero_piso, hospitalizacion.precio_habitacion FROM cuartos INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN hospitalizacion on hospitalizacion.id_cuarto_H = cuartos.id_cuarto WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion' AND hospitalizacion.deleted is NULL AND pisos.id_ubicacion_piso = '$id_sucursal'";
        $query = _query($sql);
        $row = _fetch_array($query);
        $minuto = $row['minuto'];
        $resultado ="";
        $numero_cuarto = $row['numero_cuarto'];
        $descripcion = $row['descripcion'];
        $numero_piso = $row['numero_piso'];
        $precio_por_hora = $row['precio_habitacion'];
        $precio_por_minuto = number_format(($precio_por_hora/60), 5);
        $precio_por_hora = number_format($precio_por_hora, 5);
        $precio_por_hora= "<p style='color:#008704'>$".$precio_por_hora."</p>";
        $precio_por_minuto= "<p style='color:#008704'>$".$precio_por_minuto."</p>";
        if($minuto == 1){
            $resultado = "Cuarto #".$numero_cuarto.", en el piso #".$numero_piso.", ".$descripcion.', precio por minuto: '.$precio_por_minuto;
        }
        else{
            $resultado = "Cuarto #".$numero_cuarto.", en el piso #".$numero_piso.", ".$descripcion.', precio por hora: '.$precio_por_hora;
        }


        return $resultado;

    }, 'field' => 'id_hospitalizacion' ),
    array( 'db' => 'estado_hospitalizacion.estado', 'dt' =>8, 'formatter' => function($estado){
        $tablas1="";
        if($estado == 'PENDIENTE'){
            $tablas1.= "<label class='badge' style='background:#11DCD9; color:#FFF; font-weight:bold;'>PENDIENTE</label>";
        }
        if($estado == 'EN HABITACION'){
           $tablas1.= "<label class='badge' style='background:#39DC11; color:#FFF; font-weight:bold;'>EN HABITACION</label>";
        }
        if($estado == 'FINALIZADA'){
            $tablas1.="<label class='badge' style='background:#DC112D; color:#FFF; font-weight:bold;'>FINALIZADA</label>";
        }
        if($estado == 'ANULADA'){
            $tablas1.="<label class='badge' style='background:#FCFF33; color:#FFF; font-weight:bold;'>ANULADA</label>";
        }
        return $tablas1;
    }, 'field' => 'estado' ),
    array( 'db' => 'hospitalizacion.id_hospitalizacion', 'dt' =>9, 'formatter' => function($id_hospitalizacion){
        $id_sucursal = $_SESSION['id_sucursal'];
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"];
        $menudrop="<div class='btn-group'>
            <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
            <ul class='dropdown-menu dropdown-primary'>";
            $sql = "SELECT recepcion.recepcion_hospitalizacion, hospitalizacion.id_estado_hospitalizacion, recepcion.id_recepcion FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion'";
            $query = _query($sql);
            $row = _fetch_array($query);
            $idRecepcion = $row['id_recepcion'];
            $id_estado_hospitalizacion = $row['id_estado_hospitalizacion'];
            $recepcion_hospitalizacion = $row['recepcion_hospitalizacion'];

            $filename='ver_hospitalizacion.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' ){
                $menudrop .="<li><a data-toggle='modal' href='ver_hospitalizacion.php?id_hospitalizacion=".$id_hospitalizacion."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver Hospitalizacion</a></li>";
            }
            if($id_estado_hospitalizacion == 1 || $id_estado_hospitalizacion == 2){
                $filename='editar_hospitalizacion.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' ){
                    $menudrop .="<li><a  href='editar_hospitalizacion.php?id_hospitalizacion=".$id_hospitalizacion."' data-target='#viewModal' data-refresh='true' ><i class=\"fa fa-pencil\"></i> Editar</a></li>";
                }
            }
            if($id_estado_hospitalizacion == 4){
                $filename='borrar_hospitalizacion.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' ){
                    $menudrop .="<li><a data-toggle='modal' href='borrar_hospitalizacion.php?id_hospitalizacion=".$id_hospitalizacion."' data-target='#deleteModal' data-refresh='true' ><i class=\"fa fa-eraser\"></i> Borrar Hospitalizacion</a></li>";
                }
            }
            if($id_estado_hospitalizacion == 1){
                $filename='ingresar_paciente.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' ){
                    $menudrop .="<li><a data-toggle='modal' href='ingresar_paciente.php?id_hospitalizacion=".$id_hospitalizacion."' data-target='#viewModal' data-refresh='true' ><i class=\"fa fa-check\"></i> Ingresar Paciente</a></li>";
                }
            }
            if($id_estado_hospitalizacion == 2){
                $filename='salida_ingreso.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' ){
                    $menudrop .="<li><a data-toggle='modal' href='salida_paciente.php?id_hospitalizacion=".$id_hospitalizacion."' data-target='#viewModal' data-refresh='true' ><i class=\"fa fa-reply\"></i> Dar Salida</a></li>";
                }
            }
            /*if($id_estado_hospitalizacion == 3 || $id_estado_hospitalizacion == 2 || $id_estado_hospitalizacion == 1 ){
                $filename='anular_hospitalizacion.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' ){
                    $menudrop .="<li><a  data-toggle='modal' href='anular_hospitalizacion.php?id_hospitalizacion=".$id_hospitalizacion."' data-target='#viewModal' data-refresh='true' ><i class=\"fa fa-times\"></i> Anular Hospitalizacion</a></li>";
                }
            }*/
            if($id_estado_hospitalizacion == 2 ){
                $filename='asignar_insumos_hospitalizacion.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' ){
                    $sql = "SELECT recepcion.recepcion_hospitalizacion, hospitalizacion.id_estado_hospitalizacion, recepcion.id_recepcion FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion'";
                    $query = _query($sql);
                    $row = _fetch_array($query);
                    $idRecepcion = $row['id_recepcion'];
                    $menudrop .="<li><a  href='venta_h.php?id=".$idRecepcion."' ><i class=\"fa fa-plus\"></i> Asignar Insumos</a></li>";
                }
            }
                $filename='asignar_insumos_hospitalizacion.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' ){
                    $menudrop .="<li><a  href='lista_insumos_hospitalizacion.php?id_hospitalizacion=".$id_hospitalizacion."' ><i class=\"fa fa-medkit\"></i> Ver cuenta paciente</a></li>";
                }
            
            /*if($recepcion_hospitalizacion == "1"){
                $filename='transferir_recepcion.php';
                $menudrop.= "<li><a  data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."' data-target='#transferenciaModal' data-refresh='true'><i class='fa fa-upload'></i> Transferir </a></li>";
            }*/
            //$menudrop .="<li><a  data-toggle='modal' href='reporte_hospitalizacion.php?id_hospitalizacion=".$id_hospitalizacion."' ><i class=\"fa fa-money\"></i> Cuenta</a></li>";
        $menudrop .="</ul>
        </div>";
        return $menudrop;
    }, 'field' => 'id_hospitalizacion' ),
);
echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);



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