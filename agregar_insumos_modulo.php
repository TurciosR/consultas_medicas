<?php
    include("_core.php");
    include('num2letras.php');
?>
<?php
    function consultar_stock(){
        $id_usb = $_REQUEST['id_usb'];
        $id_producto = $_REQUEST['id_producto'];
        $id_presentacion="";
        if (isset($_REQUEST['id_presentacion'])){
            $id_presentacion=$_REQUEST['id_presentacion'];
        }
        $cortesia = "";
        $id_usuario=$_SESSION["id_usuario"];
        $iva=13/100;
        $precio=0;
        $sql1 = "SELECT p.id_producto, p.barcode, p.descripcion, p.perecedero, p.exento, p.id_categoria, p.id_sucursal,s.id_stock,s.stock, s.id_sucursal, s.precio_unitario, s.costo_unitario
        FROM ".EXTERNAL.".producto AS p, ".EXTERNAL.".stock AS s WHERE p.id_producto = s.id_producto AND p.id_producto ='$id_producto' ";
        $stock1=_query($sql1);
        $row1=_fetch_array($stock1);
        $nrow1=_num_rows($stock1);
        if ($nrow1>0) {
            $perecedero=$row1['perecedero'];
            $barcode = $row1["barcode"];
            $descripcion = $row1["descripcion"];
            $perecedero = $row1["perecedero"];
            $exento = $row1["exento"];
            $id_stock = $row1["id_stock"];
            $stock = $row1["stock"];
            $precio_unitario = $row1["precio_unitario"];
            $costo_unitario = $row1["costo_unitario"];
            //precio de venta
            $fecha_hoy=date("Y-m-d");
            $fecha_hoy2=date("d-m-Y");
            //consultar si es perecedero
            $sql_existencia = "SELECT su.id_ubicacion, su.id_producto, su.cantidad, su.id_ubicacion, u.id_sucursal, u.bodega
            FROM ".EXTERNAL.".stock_ubicacion as su, ".EXTERNAL.".ubicacion as u
            WHERE su.id_producto = '$id_producto' AND su.id_ubicacion = u.id_ubicacion AND u.id_ubicacion = '$id_usb' ORDER BY su.id_ubicacion ASC";
            $resul_existencia = _query($sql_existencia);
            $cuenta_existencia = _num_rows($resul_existencia);
            $existencia_real = 0;
            if ($cuenta_existencia > 0) {
              while ($row_ex = _fetch_array($resul_existencia)) {
                $cantidad_ex = $row_ex["cantidad"];
                $existencia_real += $cantidad_ex;
              }
            }
            $fecha_caducidad="0000-00-00";
            $stock_fecha=0;
        }
        //si no hay stock devuelve cero a todos los valores !!!
        if ($nrow1==0) {
            $existencias=0;
            $precio_venta=0;
            $costos_pu=array(0,0,0,0);
            $precios_vta=array(0,0,0,0);
            $cp=0;
            $iva=0;
            $unidades=" ";
            $imagen='';
            $combo=0;
            $fecha_caducidad='0000-00-00';
            $stock_fecha=0;
            $oferta=0;
            $total = 0;
        }
        /*inicio modificacion presentacion*/
        $i=0;
        $unidadp=1;
        $preciop=0;
        $descripcionp=0;
        if($existencia_real == 0){
            $sqlCE = "SELECT ".EXTERNAL.".presentacion.nombre, ".EXTERNAL.".presentacion_producto.descripcion,".EXTERNAL.".presentacion_producto.id_presentacion,
            ".EXTERNAL.".presentacion_producto.unidad,".EXTERNAL.".presentacion_producto.precio FROM ".EXTERNAL.".presentacion_producto JOIN ".EXTERNAL.".presentacion ON ".EXTERNAL.".presentacion.id_presentacion=".EXTERNAL.".presentacion_producto.presentacion
            LEFT JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto LEFT  JOIN ".EXTERNAL.".stock_ubicacion on ".EXTERNAL.".stock_ubicacion.id_producto = ".EXTERNAL.".producto.id_producto
            WHERE ".EXTERNAL.".presentacion_producto.id_producto=$id_producto AND ".EXTERNAL.".presentacion_producto.activo =1  GROUP BY ".EXTERNAL.".presentacion_producto.id_presentacion";
        }
        else{
            $sqlCE = "SELECT ".EXTERNAL.".presentacion.nombre, ".EXTERNAL.".presentacion_producto.descripcion,".EXTERNAL.".presentacion_producto.id_presentacion,
            ".EXTERNAL.".presentacion_producto.unidad,".EXTERNAL.".presentacion_producto.precio FROM ".EXTERNAL.".presentacion_producto JOIN ".EXTERNAL.".presentacion ON ".EXTERNAL.".presentacion.id_presentacion=".EXTERNAL.".presentacion_producto.presentacion
            LEFT JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto LEFT  JOIN ".EXTERNAL.".stock_ubicacion on ".EXTERNAL.".stock_ubicacion.id_producto = ".EXTERNAL.".producto.id_producto
            WHERE ".EXTERNAL.".presentacion_producto.id_producto=$id_producto AND ".EXTERNAL.".presentacion_producto.activo =1  and ".EXTERNAL.".presentacion_producto.unidad <= $existencia_real GROUP BY ".EXTERNAL.".presentacion_producto.id_presentacion";
        }
        $sql_p=_query($sqlCE);
        $select="<select class='sel id_pres form-control' id='id_presentacion'>";
        while ($row=_fetch_array($sql_p)) {
            if ($i==0) {
                $unidadp=$row['unidad'];
                $preciop=$row['precio'];
                $descripcionp=$row['descripcion'];
            }
            if ($row['id_presentacion'] == $id_presentacion){
                $unidadp=$row['unidad'];
                $preciop=$row['precio'];
                $descripcionp=$row['descripcion'];
                $select.="<option value=".$row['id_presentacion']." selected>".$row['nombre']."</option>";
            }
            else{
                $select.="<option value=".$row['id_presentacion'].">".$row['nombre']."</option>";
            }
            $i=$i+1;
        }
        $select.="</select>";
        $total=$existencia_real / $unidadp;
        $total=round($total, 0, PHP_ROUND_HALF_DOWN);
        $xdatos['existencias']=$total;
        $xdatos['fecha_caducidad']=$fecha_caducidad;
        $xdatos['stock_fecha']=$stock_fecha;
        $xdatos['perecedero']=$perecedero;
        $xdatos['fecha_hoy']=$fecha_hoy;
        $xdatos['descripcion']=$descripcion;
        $xdatos['preciop']=$preciop;
        $xdatos['unidadp']=$unidadp;
        $xdatos['descripcionp']=$descripcionp;
        $xdatos['select']=$select;
        echo json_encode($xdatos); //Return the JSON Array
    }

?>
<?php
    function traer_insumos(){
        $id_recepcion=$_POST['idRecepcion'];
        $id_usb = $_POST['id_usb'];
        $tabla_buscar = $_POST['tabla_buscar'];
        $sql_ins="SELECT  ".EXTERNAL.".producto.id_producto, ".EXTERNAL.".producto.descripcion, ".EXTERNAL.".".$tabla_buscar.".id_insumo ,".EXTERNAL.".".$tabla_buscar.".cantidad, ".EXTERNAL.".presentacion_producto.precio, ".EXTERNAL.".presentacion_producto.id_presentacion, ".EXTERNAL.".presentacion_producto.unidad FROM ".EXTERNAL.".producto LEFT JOIN ".EXTERNAL.".".$tabla_buscar." on ".EXTERNAL.".".$tabla_buscar.".id_producto = ".EXTERNAL.".producto.id_producto LEFT JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto WHERE ".EXTERNAL.".".$tabla_buscar.".deleted is NULL and ".EXTERNAL.".".$tabla_buscar.".id_recepcion = $id_recepcion  AND ".EXTERNAL.".".$tabla_buscar.".producto = 1 AND ".EXTERNAL.".presentacion_producto.id_presentacion = ".EXTERNAL.".".$tabla_buscar.".id_presentacion";
        //echo $sql_ins;
        $res_ins=_query($sql_ins);
        $n=_num_rows($res_ins);
        $array_prod = array();
        for($i=0;$i<$n;$i++){
            $row=_fetch_array($res_ins);
            $array_prod[] = array(
                'id_producto' => $row['id_producto'],
                'tipo' => "P",
                'desc' =>   $row['descripcion'],
                'cantidad' =>  $row['cantidad'],
                'precio' => $row['precio'],
                'hora' => $row['id_producto'],
                'id_presentacion' => $row['id_presentacion'],
                'unidad' => $row['unidad'],
                'id_insumo' => $row['id_insumo'],
            );
        }
        $sql_serv="SELECT ".EXTERNAL.".servicios_hospitalarios.id_servicio, ".EXTERNAL.".servicios_hospitalarios.descripcion, ".EXTERNAL.".".$tabla_buscar.".id_insumo , ".EXTERNAL.".".$tabla_buscar.".hora_de_aplicacion, ".EXTERNAL.".".$tabla_buscar.".cantidad, ".EXTERNAL.".servicios_hospitalarios.precio FROM ".EXTERNAL.".servicios_hospitalarios LEFT JOIN ".EXTERNAL.".".$tabla_buscar." on ".EXTERNAL.".servicios_hospitalarios.id_servicio = ".EXTERNAL.".".$tabla_buscar.".id_servicio WHERE ".EXTERNAL.".".$tabla_buscar.".id_recepcion =$id_recepcion AND ".EXTERNAL.".".$tabla_buscar.".servicio = 1 AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL";
        $res_serv=_query($sql_serv);
        $nr=_num_rows($res_serv);
        for($j=0;$j<$nr;$j++){
            $row1=_fetch_array($res_serv);
            $array_prod[] = array(
                'id_producto' => $row1['id_servicio'],
                'tipo' => "S",
                'desc' =>   $row1['descripcion'],
                'cantidad' =>  $row1['cantidad'],
                'precio' => $row1['precio'],
                'hora' => $row1['hora_de_aplicacion'],
                'id_presentacion' => "1",
                'unidad' => "1",
                'id_insumo' => $row1['id_insumo'],
            );
        }

        $sql_examenes="SELECT labangel.examen.id_examen, labangel.examen.nombre_examen, ".EXTERNAL.".".$tabla_buscar.".id_insumo , ".EXTERNAL.".".$tabla_buscar.".hora_de_aplicacion, ".EXTERNAL.".".$tabla_buscar.".cantidad, labangel.examen.precio_examen FROM labangel.examen LEFT JOIN ".EXTERNAL.".".$tabla_buscar." on labangel.examen.id_examen = ".EXTERNAL.".".$tabla_buscar.".id_examen WHERE ".EXTERNAL.".".$tabla_buscar.".id_recepcion =$id_recepcion AND ".EXTERNAL.".".$tabla_buscar.".examen = 1 AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL";
        $res_serv=_query($sql_examenes);
        $nr=_num_rows($res_serv);
        for($j=0;$j<$nr;$j++){
            $row1=_fetch_array($res_serv);
            $array_prod[] = array(
                'id_producto' => $row1['id_examen'],
                'tipo' => "E",
                'desc' =>   $row1['nombre_examen'],
                'cantidad' =>  $row1['cantidad'],
                'precio' => $row1['precio_examen'],
                'hora' => $row1['hora_de_aplicacion'],
                'id_presentacion' => "1",
                'unidad' => "1",
                'id_insumo' => $row1['id_insumo'],
            );
        }
        echo json_encode($array_prod);
    }

?>
<?php
    /*function insert(){
        //id del stock ubicacion a buscar
        $id_usb = $_POST['id_usb'];
        //id de la tabla de insumos a buscar
        $tabla_buscar = $_POST['tabla_buscar'];
        date_default_timezone_set('America/El_Salvador');
        $error = 0;
        $id_sucursal = $_SESSION['id_sucursal'];
        $id_recepcion = $_POST["id_recepcion"];
        $sql_paciente = "SELECT * FROM recepcion where id_recepcion = '$id_recepcion'";
        $query_recepcion = _query($sql_paciente);
        $row_paciente = _fetch_array($query_recepcion);
        $id_paciente = $row_paciente['id_paciente_recepcion'];
        $total = $_POST["total"];
        $items = $_POST["items"];
        $cuantos = $_POST['cuantos'];
        $array_json=$_POST['json_arr'];
        $fecha=date("Y-m-d");
        $hora=date("H:i:s");
        $fecha_hora_ingresar = $fecha." ".$hora;
        $id_empleado=$_SESSION["id_usuario"];
        $fecha_actual = date('Y-m-d');
        $array = json_decode($array_json, true);
        $array_cargas = array();
        $array_descargas = array();
        $precio_cargas = 0;
        $precio_descargas = 0;
        $descarga_de_inventario=0;
        $id_descarga_movimiento;
        $array_tabla = array();
        $array_examen = array();
        //Recorre el arreglo con todos los productos y servicios_hospitalarios agregados

        foreach ($array as $key => $fila){
            $id_producto = $fila['id'];
            $id_presentacion = 0;
            if(isset($fila['id_presentacion'])){
                $id_presentacion = $fila['id_presentacion'];
            }
            $precio = $fila['precio'];
            $cantidad = $fila['cantidad'];
            $subtotal = $fila['subtotal'];
            $tipop = $fila['tipop'];
            $fecha = $fila['fecha'];
            if($fecha == ""){
                $fecha_ingresar = date("Y:m:d");
                $hora_ingresar = date("H:i:s");
            }
            else{
                $fecha_hora = explode(" ",$fecha);
                $fecha_ingresar = $fecha_hora[0];
                $hora_ingresar = $fecha_hora[1];
                if($fecha_ingresar == "00-00-0000"){
                    $fecha_ingresar = date("Y:m:d");
                    $hora_ingresar = date("H:i:s");
                }
            }
            $unidad = $fila['unidad'];
            $id_insumo = $fila['id_insumo'];
            $cantidad_total = $cantidad * $unidad;
            $precio_venta = $cantidad_total * $precio;
            $seguir = 1;
            _begin();
            //Iniciar la comprobacion de los tipos de insumos
            if($tipop == "P"){
                //VERIFICAR QUE EXISTA UN REGISTRO IDENTICO
                $sql_repetido_identico = "SELECT ".$tabla_buscar.".id_insumo FROM
                 ".$tabla_buscar." 
                 WHERE ".$tabla_buscar.".id_insumo = '$id_insumo' 
                 AND ".$tabla_buscar.".id_recepcion = '$id_recepcion' 
                 AND ".$tabla_buscar.".producto = '1' 
                 ".$tabla_buscar.".id_producto = '$id_producto' 
                 AND ".$tabla_buscar.".cantidad = '$cantidad_total' 
                 AND ".$tabla_buscar.".total = '$precio_venta' 
                 AND ".$tabla_buscar.".deleted is NULL 
                 AND ".$tabla_buscar.".id_presentacion = '$id_presentacion'";
                $query_repetido_identico = _query($sql_repetido_identico);
                if(_num_rows($query_repetido_identico) > 0){
                    $array_tabla[]= array(
                        'id_insumo' => $id_insumo,
                        'id_producto' => $id_producto,
                        'cantidad' => $cantidad_total,
                        'id_presentacion' => $id_presentacion,
                        'tipo' =>"P",
                    );
                }
                else{
                    //VERIFICAR QUE EXISTA UN REGISTRO REPETIDO PERO QUE NO COINCIDA CON LAS CANTIDADES NI PRESENTACIONES
                    $sql_repetido_parcial = "SELECT ".$tabla_buscar.".id_insumo FROM 
                    ".$tabla_buscar." WHERE 
                    ".$tabla_buscar.".id_insumo = '$id_insumo' AND 
                    ".$tabla_buscar.".id_recepcion = '$id_recepcion' AND 
                    ".$tabla_buscar.".producto = '1' AND 
                    ".$tabla_buscar.".id_producto = '$id_producto' AND 
                    ".$tabla_buscar.".deleted is NULL";
                    $query_repetido_parcial = _query($sql_repetido_parcial);
                    if(_num_rows($query_repetido_parcial) > 0){
                        $verificacion_cantidad_actual = "SELECT ".EXTERNAL.".".$tabla_buscar.".id_insumo, 
                        ".EXTERNAL.".".$tabla_buscar.".id_producto, 
                        ".EXTERNAL.".".$tabla_buscar.".id_presentacion, 
                        ".EXTERNAL.".".$tabla_buscar.".cantidad FROM 
                        ".EXTERNAL.".".$tabla_buscar." WHERE 
                        ".EXTERNAL.".".$tabla_buscar.".id_insumo = 
                        '$id_insumo' AND ".EXTERNAL.".".$tabla_buscar.".id_recepcion = '$id_recepcion' AND 
                        ".EXTERNAL.".".$tabla_buscar.".deleted is NULL";
                        $query_verificacion_cantidad_actual = _query($verificacion_cantidad_actual);
                        if(_num_rows($query_verificacion_cantidad_actual) > 0){
                            $row_verificacion_cantidad_actual = _fetch_array($query_verificacion_cantidad_actual);
                            $id_insumo_actual = $row_verificacion_cantidad_actual['id_insumo'];
                            $id_producto_actual = $row_verificacion_cantidad_actual['id_producto'];
                            $id_presentacion_actual = $row_verificacion_cantidad_actual['id_presentacion'];
                            $cantidad_actual = $row_verificacion_cantidad_actual['cantidad'];
                            $consulta_stock_general = "SELECT ".EXTERNAL.".stock.stock, ".EXTERNAL.".stock.id_stock FROM ".EXTERNAL.".stock WHERE ".EXTERNAL.".stock.id_producto = '$id_producto' AND ".EXTERNAL.".stock.id_sucursal = '$id_sucursal'";
                            $query_consulta_stock_general = _query($consulta_stock_general);
                            if(_num_rows($query_consulta_stock_general) > 0){
                                $row_consulta_stock_general = _fetch_array($query_consulta_stock_general);
                                $cantidad_actual_stock = $row_consulta_stock_general['stock'];
                                $id_stock_general = $row_consulta_stock_general['id_stock'];
                                $cantidad_nueva_stock_general = 0;
                                if($cantidad_total > $cantidad_actual){
                                    $cantidad_nueva_stock_general = $cantidad_actual_stock - ($cantidad_total - $cantidad_actual);
                                }
                                if($cantidad_actual > $cantidad_total){
                                    $cantidad_nueva_stock_general = $cantidad_actual_stock + ($cantidad_actual - $cantidad_total);
                                }
                                if($cantidad_actual == $cantidad_total){
                                    $cantidad_nueva_stock_general = $cantidad_actual_stock;
                                }
                                $tabla_actualizar_stock_general = "".EXTERNAL.".stock";
                                $form_data_stock_general= array(
                                    'stock' => $cantidad_nueva_stock_general
                                );
                                $where_stock_general = " WHERE id_stock = '$id_stock_general'";
                                $update_stock_general = _update($tabla_actualizar_stock_general,$form_data_stock_general,$where_stock_general);
                                if($update_stock_general){
                                    $consulta_stock_ubicacion = "SELECT * FROM ".EXTERNAL.".stock_ubicacion WHERE id_producto='$id_producto' AND ".EXTERNAL.".stock_ubicacion.id_ubicacion='$id_usb' AND ".EXTERNAL.".stock_ubicacion.id_sucursal = '$id_sucursal' ORDER BY id_ubicacion DESC LIMIT 1";
                                    $query_consulta_stock_ubicacion = _query($consulta_stock_ubicacion);
                                    if(_num_rows($query_consulta_stock_ubicacion) > 0){
                                        $consulta_presentacion_producto = "SELECT ".EXTERNAL.".presentacion_producto.precio, ".EXTERNAL.".presentacion_producto.costo FROM ".EXTERNAL.".presentacion_producto WHERE ".EXTERNAL.".presentacion_producto.id_presentacion = '$id_presentacion'";
                                        $query_presentacion_producto = _query($consulta_presentacion_producto);
                                        $precio_producto = 0;
                                        $costo_producto = 0;
                                        if(_num_rows($query_presentacion_producto) > 0){
                                            $row_presentacion_productos = _fetch_array($query_presentacion_producto);
                                            $precio_producto = $row_presentacion_productos['precio'];
                                            $costo_producto = $row_presentacion_productos['costo'];
                                        }
                                        $row_consulta_stock_ubicacion = _fetch_array($query_consulta_stock_ubicacion);
                                        $id_ubicacion = $row_consulta_stock_ubicacion['id_su'];
                                        $cantidad_stock_ubicacion_actual = $row_consulta_stock_ubicacion['cantidad'];
                                        $cantidad_nueva_stock_ubicacion = 0;
                                        if($cantidad_total > $cantidad_actual){
                                            $cantidad_nueva_stock_ubicacion = $cantidad_stock_ubicacion_actual - ($cantidad_total - $cantidad_actual);
                                            $precio_cargo = $precio_producto * ($cantidad_total - $cantidad_actual);
                                            $costo_cargo  = $costo_producto * ($cantidad_total - $cantidad_actual);
                                            $precio_cargas+=$precio_cargo;
                                            $array_cargas[] = array(
                                                'id_producto' => $id_producto,
                                                'id_presentacion' => $id_presentacion,
                                                'cantidad' => ($cantidad_total - $cantidad_actual),
                                                'costo' =>  $costo_cargo,
                                                'precio' => $precio_cargo,
                                                'stock_anterior' => $cantidad_stock_ubicacion_actual,
                                                'stock_actual' => $cantidad_nueva_stock_ubicacion
                                            );
                                        }
                                        if($cantidad_actual > $cantidad_total){
                                            $cantidad_nueva_stock_ubicacion = $cantidad_stock_ubicacion_actual + ($cantidad_actual - $cantidad_total);
                                            $precio_cargo = $precio_producto * ($cantidad_total - $cantidad_actual);
                                            $costo_cargo  = $costo_producto * ($cantidad_total - $cantidad_actual);
                                            $precio_descargas+=$precio_cargo;
                                            $array_descargas [] = array(
                                                'id_producto' => $id_producto,
                                                'id_presentacion' => $id_presentacion,
                                                'cantidad' => ($cantidad_total - $cantidad_actual),
                                                'costo' =>  $costo_cargo,
                                                'precio' => $precio_cargo,
                                                'stock_anterior' => $cantidad_stock_ubicacion_actual,
                                                'stock_actual' => $cantidad_nueva_stock_ubicacion
                                            );
                                        }
                                        if($cantidad_actual == $cantidad_total){
                                            $cantidad_nueva_stock_ubicacion = $cantidad_stock_ubicacion_actual;
                                        }
                                        $tabla_actualizar_stock_ubicacion = "".EXTERNAL.".stock_ubicacion";
                                        $form_data_stock_ubicacion= array(
                                            'cantidad' => $cantidad_nueva_stock_ubicacion
                                        );
                                        $where_stock_ubicacion = " WHERE id_su = '$id_ubicacion'";
                                        $update_stock_ubicacion = _update($tabla_actualizar_stock_ubicacion,$form_data_stock_ubicacion,$where_stock_ubicacion);
                                        if($update_stock_ubicacion){
                                            $tabla_actualizar_insumos = $tabla_buscar;
                                            $form_data_tabla_insumos = array(
                                                'cantidad' => $cantidad_total,
                                                'id_presentacion' => $id_presentacion,
                                                'total' => $precio_venta
                                            );
                                            $where_tabla_insumos = " id_insumo = '$id_insumo'";
                                            $update_tabla_insumos = _update($tabla_actualizar_insumos,$form_data_tabla_insumos,$where_tabla_insumos);
                                            if($update_tabla_insumos){
                                                $array_tabla[]= array(
                                                    'id_insumo' => $id_insumo,
                                                    'id_producto' => $id_producto,
                                                    'cantidad' => $cantidad_total,
                                                    'id_presentacion' => $id_presentacion,
                                                    'tipo' =>"P",
                                                );
                                            }
                                            else{
                                                $error++;
                                            }
                                        }
                                        else{
                                            $error++;
                                        }
                                    }
                                }
                                else{
                                    $error++;
                                }
                            }
                        }
                    }
                    //NO EXISTE PARA NADA EL PRODUCTO, HAY QUE INGRESARLO
                    else{
                        $tabla_insertar_insumos = $tabla_buscar;
                        $form_data_insertar_insumos = array(
                            'id_recepcion' => $id_recepcion,
                            'producto' => '1',
                            'id_producto' => $id_producto,
                            'id_presentacion' => $id_presentacion,
                            'cantidad' => $cantidad_total,
                            'total' => $precio_venta,
                            'hora_de_aplicacion' => date("Y:m:d H:i:s")
                        );
                        $insertar_insumo = _insert($tabla_insertar_insumos, $form_data_insertar_insumos);
                        $id_insumo_insertado = _insert_id();
                        if($insertar_insumo){
                            $array_tabla[]= array(
                                'id_insumo' => $id_insumo_insertado,
                                'id_producto' => $id_producto,
                                'cantidad' => $cantidad_total,
                                'id_presentacion' => $id_presentacion,
                                'tipo' =>"P",
                            );
                        }
                        else{
                            $error++;
                        }
                    }
                }
            }
            if($tipop == "S"){
                $verificacion_repetido = "SELECT * FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_insumo = '$id_insumo' AND deleted is NULL AND ".EXTERNAL.".".$tabla_buscar.".servicio = '1' AND ".EXTERNAL.".".$tabla_buscar.".id_servicio = '$id_producto' AND ".EXTERNAL.".".$tabla_buscar.".cantidad = '$cantidad_total'";
                $query_verificacion_repetido = _query($verificacion_repetido);
                if(_num_rows($query_verificacion_repetido) > 0){
                    $array_tabla[]= array(
                        'id_insumo' => $id_insumo,
                        'id_producto' => $id_producto,
                        'cantidad' => $cantidad_total,
                        'id_presentacion' => '1',
                        'tipo' =>"S",
                    );
                }
                else{
                    //verificar que existe pero con otro valor
                    $verificacion_existe = "SELECT * FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_insumo = '$id_insumo' AND deleted is NULL AND ".EXTERNAL.".".$tabla_buscar.".servicio = '1' AND ".EXTERNAL.".".$tabla_buscar.".id_servicio = '$id_producto'";
                    $query_verificacion_existe = _query($verificacion_existe);
                    //SI EXISTE
                    if(_num_rows($query_verificacion_existe) > 0){
                        $tabla_actualizar_insumos = $tabla_buscar;
                        $form_data_tabla_insumos = array(
                            'cantidad' => $cantidad_total,
                            'total' => $precio_venta
                        );
                        $where_tabla_insumos = " id_insumo = '$id_insumo'";
                        $update_tabla_insumos = _update($tabla_actualizar_insumos,$form_data_tabla_insumos,$where_tabla_insumos);
                        if($update_tabla_insumos){
                            $array_tabla[]= array(
                                'id_insumo' => $id_insumo,
                                'id_producto' => $id_producto,
                                'cantidad' => $cantidad_total,
                                'id_presentacion' => "1",
                                'tipo' =>"S",
                            );
                        }
                        else{
                            $error++;
                        }
                    }
                    //NO EXISTE
                    else{
                        $tabla_insertar_insumos = $tabla_buscar;
                        $form_data_insertar_insumos = array(
                            'id_recepcion' => $id_recepcion,
                            'servicio' => '1',
                            'id_servicio' => $id_producto,
                            'cantidad' => $cantidad_total,
                            'total' => $precio_venta,
                            'hora_de_aplicacion' => date("Y:m:d H:i:s")
                        );
                        $insertar_insumo = _insert($tabla_insertar_insumos, $form_data_insertar_insumos);
                        $id_insumo_insertado = _insert_id();
                        if($insertar_insumo){
                            $array_tabla[]= array(
                                'id_insumo' => $id_insumo_insertado,
                                'id_producto' => $id_producto,
                                'cantidad' => $cantidad_total,
                                'id_presentacion' => "1",
                                'tipo' =>"S",
                            );
                        }
                        else{
                            $error++;
                        }
                    }
                }
            }
            if($tipop == "EXAMEN_AGREGADO" || $tipop == "E"){
                //VERIFICAR SI EXISTE UN EXAMEN REPETIDO
                $consulta_verificar_repetido = "SELECT ".EXTERNAL.".".$tabla_buscar.".id_insumo FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_insumo = '$id_insumo' AND ".EXTERNAL.".".$tabla_buscar.".examen = '1' AND ".EXTERNAL.".".$tabla_buscar.".id_examen = '$id_producto' AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL AND ".EXTERNAL.".".$tabla_buscar.".cantidad = '$cantidad_total'";
                $query_verificar_repetido = _query($consulta_verificar_repetido);
                //SI ESTA REPETIDO EXACTAMENTE
                if(_num_rows($query_verificar_repetido) > 0){
                    $array_tabla[]= array(
                        'id_insumo' => $id_insumo,
                        'id_producto' => $id_producto,
                        'cantidad' => $cantidad_total,
                        'id_presentacion' => '1',
                        'tipo' =>"E",
                    );
                }
                //NO ESTA REPETIDO EXACTAMENTE
                else{
                    //VERIFICAR QUE EXISTA POR LO MENOS PERO CON OTROS DATOS
                    $sql_verificar_existe = "SELECT ".EXTERNAL.".".$tabla_buscar.".id_insumo FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_insumo = '$id_insumo' AND ".EXTERNAL.".".$tabla_buscar.".examen = '1' AND ".EXTERNAL.".".$tabla_buscar.".id_examen = '$id_producto' AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL";
                    $query_verificar_existe = _query($sql_verificar_existe);
                    //POR LO MENOS EXISTE
                    if(_num_rows($query_verificacion_existe) > 0){
                        $tabla_actualizar_insumos = $tabla_buscar;
                        $form_data_tabla_insumos = array(
                            'cantidad' => $cantidad_total,
                            'total' => $precio_venta
                        );
                        $where_tabla_insumos = " id_insumo = '$id_insumo'";
                        $update_tabla_insumos = _update($tabla_actualizar_insumos,$form_data_tabla_insumos,$where_tabla_insumos);
                        if($update_tabla_insumos){
                            $array_tabla[]= array(
                                'id_insumo' => $id_insumo,
                                'id_producto' => $id_producto,
                                'cantidad' => $cantidad_total,
                                'id_presentacion' => "1",
                                'tipo' =>"E",
                            );
                        }
                        else{
                            $error++;
                        }
                    }
                    //NO EXISTE PARA NADA
                    else{
                        $tabla_insertar_insumos = $tabla_buscar;
                        $form_data_insertar_insumos = array(
                            'id_recepcion' => $id_recepcion,
                            'examen' => '1',
                            'id_examen' => $id_producto,
                            'cantidad' => $cantidad_total,
                            'total' => $precio_venta,
                            'hora_de_aplicacion' => date("Y:m:d H:i:s")
                        );
                        $insertar_insumo = _insert($tabla_insertar_insumos, $form_data_insertar_insumos);
                        $id_insumo_insertado = _insert_id();
                        $array_examen[] = array(
                            'id_examen' => $fila['id'],
                            'id_insumo' => $id_insumo_insertado
                        );
                        if($insertar_insumo){
                            $array_tabla[]= array(
                                'id_insumo' => $id_insumo_insertado,
                                'id_producto' => $id_producto,
                                'cantidad' => $cantidad_total,
                                'id_presentacion' => "1",
                                'tipo' =>"E",
                            );
                        }
                        else{
                            $error++;
                        }
                    }
                }
            }
        }
        if($error == 0){
            $error2 = 0;
            $hora1=date("H:i:s");
            $dia1 =date('Y-m-d');
            if(!empty($array_cargas)){
                $sql_num = _query("SELECT di FROM ".EXTERNAL.".correlativo WHERE id_sucursal='$id_sucursal'");
                $datos_num = _fetch_array($sql_num);
                $ult = $datos_num["di"]+1;
                $numero_doc=$ult.'_DI';
                $tipo_entrada_salida='Asignacion de productos a la recepcion a la repcion con el id:'.$id_recepcion;
                /*actualizar los correlativos de AI
                $corr=1;
                $up=1;
                $table="".EXTERNAL.".correlativo";
                $form_data = array(
                    'di' =>$ult
                );
                $where_clause_c="id_sucursal='".$id_sucursal."'";
                $up_corr=_update($table,$form_data,$where_clause_c);
                if($up_corr){
                    $table="".EXTERNAL.".movimiento_producto";
                    $form_data = array(
                        'id_sucursal' => $id_sucursal,
                        'correlativo' => $numero_doc,
                        'concepto' => "ASIGNACION DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                        'total' => $precio_cargas,
                        'tipo' => 'SALIDA',
                        'proceso' => 'di',
                        'referencia' => $numero_doc,
                        'id_empleado' => $id_empleado,
                        'fecha' => $dia1,
                        'hora' => $hora1,
                        'id_suc_origen' => $id_sucursal,
                        'id_suc_destino' => $id_sucursal,
                        'id_proveedor' => 0,
                        'id_server' => '0',
                        'id_compra' => '0',
                        'id_traslado' => '0',
                        'id_factura' => '0',
                        'numero' => '0',
                        'conteo' => '0',
                        'sistema' => '0',
                    );
                    $insert_mov =_insert($table,$form_data);
                    $id_movimiento=_insert_id();
                    if($insert_mov){
                        foreach ($array_cargas as $array_cargas1) {
                            $table1= "".EXTERNAL.".movimiento_producto_detalle";
                            $form_data1 = array(
                                'id_movimiento'=>$id_movimiento,
                                'id_producto' => $array_cargas1['id_producto'],
                                'cantidad' => $array_cargas1['cantidad'],
                                'costo' => $array_cargas1['costo'],
                                'precio' => $array_cargas1['precio'],
                                'stock_anterior'=>$array_cargas1['stock_anterior'],
                                'stock_actual'=>$array_cargas1['stock_actual'],
                                'id_presentacion' => $array_cargas1['id_presentacion'],
                                'id_server' => '0',
                                'id_sucursal' => $id_sucursal,
                                'id_server_prod' => '0',
                                'id_server_presen' => '0',
                                'proceso' => '',
                                'referencia' => '0',
                                'lote' => '0',
                                'fecha' => date("Y:m:d"),
                                'hora' => date("H:i:s"),
                            );
                            $insert_mov_det = _insert($table1,$form_data1);
                            if(!$insert_mov_det){
                                $error2++;
                            }
                        }
                    }
                    else{
                      $error2++;
                    }
                }
                else{
                  $error2++;
                }
            }
            if(!empty($array_descargas)){
                $descarga_de_inventario = 1;
                $sql_num = _query("SELECT ti FROM ".EXTERNAL.".correlativo WHERE id_sucursal='$id_sucursal'");
                $datos_num = _fetch_array($sql_num);
                $ult = $datos_num["ti"]+1;
                $numero_doc=$ult.'_TI';
                $tipo_entrada_salida='Descarga de productos de la repcion con el id:'.$id_recepcion;
                /*actualizar los correlativos de AI
                $corr=1;
                $up=1;
                $table="".EXTERNAL.".correlativo";
                $form_data = array(
                  'ti' =>$ult
                );
                $where_clause_c="id_sucursal='".$id_sucursal."'";
                $up_corr=_update($table,$form_data,$where_clause_c);
                if($up_corr){
                    $table="".EXTERNAL.".movimiento_producto";
                    $form_data = array(
                      'id_sucursal' => $id_sucursal,
                      'correlativo' => $numero_doc,
                      'concepto' => "DESCARGA DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                      'total' => $precio_descargas,
                      'tipo' => 'ENTRADA',
                      'proceso' => 'ti',
                      'referencia' => $numero_doc,
                      'id_empleado' => $id_empleado,
                      'fecha' => $dia1,
                      'hora' => $hora1,
                      'id_suc_origen' => $id_sucursal,
                      'id_suc_destino' => $id_sucursal,
                      'id_proveedor' => 0,
                      'id_server' => '0',
                      'id_compra' => '0',
                      'id_traslado' => '0',
                      'id_factura' => '0',
                      'numero' => '0',
                      'conteo' => '0',
                      'sistema' => '0',
                    );
                    $insert_mov =_insert($table,$form_data);
                    $id_movimiento=_insert_id();
                    if($insert_mov){
                        $id_descarga_movimiento = $id_movimiento;
                        foreach ($array_descargas as $array_cargas1) {
                            $table1= "".EXTERNAL.".movimiento_producto_detalle";
                            $form_data1 = array(
                                'id_movimiento'=>$id_movimiento,
                                'id_producto' => $array_cargas1['id_producto'],
                                'cantidad' => $array_cargas1['cantidad'],
                                'costo' => $array_cargas1['costo'],
                                'precio' => $array_cargas1['precio'],
                                'stock_anterior'=>$array_cargas1['stock_anterior'],
                                'stock_actual'=>$array_cargas1['stock_actual'],
                                'id_presentacion' => $array_cargas1['id_presentacion'],
                                'id_server' => '0',
                                'id_sucursal' => $id_sucursal,
                                'id_server_prod' => '0',
                                'id_server_presen' => '0',
                                'proceso' => '',
                                'referencia' => '0',
                                'lote' => '0',
                                'fecha' => date("Y:m:d"),
                                'hora' => date("H:i:s"),
                            );
                            $insert_mov_det = _insert($table1,$form_data1);
                            if(!$insert_mov_det){
                                $error2++;
                            }
                        }
                    }
                    else{
                        $error2++;
                    }
                }
                else{
                    $error2++;
                }
            }
            if($error2 == 0){
                $error3=0;
                $algun_producto = 0;
                $sqlx = "SELECT ".EXTERNAL.".".$tabla_buscar.".id_insumo, ".EXTERNAL.".".$tabla_buscar.".examen,".EXTERNAL.".".$tabla_buscar.".id_examen, ".EXTERNAL.".".$tabla_buscar.".total, ".EXTERNAL.".".$tabla_buscar.".id_producto, ".EXTERNAL.".".$tabla_buscar.".id_servicio, ".EXTERNAL.".".$tabla_buscar.".producto, ".EXTERNAL.".".$tabla_buscar.".servicio, ".EXTERNAL.".".$tabla_buscar.".id_presentacion, ".EXTERNAL.".presentacion_producto.costo FROM ".EXTERNAL.".".$tabla_buscar." LEFT JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".".$tabla_buscar.".id_producto LEFT JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion = ".EXTERNAL.".".$tabla_buscar.".id_presentacion WHERE ".EXTERNAL.".".$tabla_buscar.".id_recepcion = $id_recepcion AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL AND ".EXTERNAL.".".$tabla_buscar.".created_at != '$fecha_hora_ingresar'";
                //echo $sqlx;
                $consultax = _query($sqlx);
                while($rowx = _fetch_array($consultax)){
                    $tipop_X = "";
                    $producto = $rowx['producto'];
                    $servicio = $rowx['servicio'];
                    $examen = $rowx['examen'];
                    $id_producto_X=0;
                    if($producto == 1){
                      $tipop_X = "P";
                      $id_producto_X = $rowx['id_producto'];
                    }
                    if($servicio == 1){
                      $tipop_X = "S";
                      $id_producto_X = $rowx['id_servicio'];
                    }
                    if($examen == 1){
                        $tipop_X = "E";
                        $id_producto_X = $rowx['id_examen'];
                    }
                    $id_insumo_X = $rowx['id_insumo'];
                    $array_base[] = array(
                        'id_insumo' =>$id_insumo_X,
                        'id_producto' => $id_producto_X,
                        'tipo' => $tipop_X,
                        'precio' => $rowx['total'],
                        'id_presentacion' => $rowx['id_presentacion'],
                        'costo' => $rowx['costo']
                    );
                }
                $count_arreglo0=0;
                $existe=0;
                if(!empty($array_base)){
                    foreach ($array_base as $key => $value) {
                        $id_insumoX = $value['id_insumo'];
                        $id_productoX = $value['id_producto'];
                        $tipoX = $value['tipo'];
                        $coun_arreglo = 0;
                        foreach ($array_tabla as $key1 => $value1) {
                            $id_insumoTB = $value1['id_insumo'];
                            $id_productoTB = $value1['id_producto'];
                            $tipoTB = $value1['tipo'];
                            if($id_insumoX == $id_insumoTB){
                                unset($array_base[$count_arreglo0]);
                            }
                            $coun_arreglo++;
                        }
                        $count_arreglo0++;
                    }
                    foreach ($array_base as $key => $value) {
                        $tipoX = $value['tipo'];
                        if($tipoX == "P"){
                            $algun_producto++;
                        }
                    }
                }

                if(!empty($array_base)){
                    if($algun_producto == 0){
                        foreach ($array_base as $key1 => $value1){
                            $tabla_deleted="".EXTERNAL.".".$tabla_buscar."";
                            $where = " WHERE id_insumo = ".$value1['id_insumo']." AND examen = 0";
                            $eliminar_insumo = _soft_delete($tabla_deleted,$where);
                            if($eliminar_insumo){
                                $xdatos['typeinfo']='Success';
                                $xdatos['msg']='Operacion realizada con Exito !';
                            }
                            else{
                              $error3++;
                            }
                        }
                    }
                    else{
                        $precio_total_eliminado = 0;
                        foreach ($array_base as $key => $value1){
                            if($value1['tipo'] == "P"){
                                $precio_total_eliminado+= $value1['precio'];
                            }
                        }
                        $sql_num = _query("SELECT ti FROM ".EXTERNAL.".correlativo WHERE id_sucursal='$id_sucursal'");
                        $datos_num = _fetch_array($sql_num);
                        $ult = $datos_num["ti"]+1;
                        $numero_doc=$ult.'_TI';
                        /*actualizar los correlativos de AI
                        $tableC="".EXTERNAL.".correlativo";
                        $form_dataC = array(
                            'ti' =>$ult
                        );
                        $where_clause_cC="id_sucursal='".$id_sucursal."'";
                        $up_corrC=_update($tableC,$form_dataC,$where_clause_cC);
                        if($up_corrC){
                            $table="".EXTERNAL.".movimiento_producto";
                            $form_data = array(
                                'id_sucursal' => $id_sucursal,
                                'correlativo' => $numero_doc,
                                'concepto' => "ELIMINACION DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                                'total' => $precio_total_eliminado,
                                'tipo' => 'ENTRADA',
                                'proceso' => 'ti',
                                'referencia' => $numero_doc,
                                'id_empleado' => $id_empleado,
                                'fecha' => $dia1,
                                'hora' => $hora1,
                                'id_suc_origen' => $id_sucursal,
                                'id_suc_destino' => $id_sucursal,
                                'id_proveedor' => 0,
                                'id_server' => '0',
                                'id_compra' => '0',
                                'id_traslado' => '0',
                                'id_factura' => '0',
                                'numero' => '0',
                                'conteo' => '0',
                                'sistema' => '0',
                            );
                            $insert_mov =_insert($table,$form_data);
                            $id_movimientox=_insert_id();
                            if($insert_mov){
                                foreach ($array_base as $key => $value) {
                                    $hora1=date("H:i:s");
                                    $dia1 =date('Y-m-d');
                                    if($value['tipo'] == "P"){
                                        $id_producto_eliminado = $value['id_producto'];
                                        $sqlD = "SELECT ".EXTERNAL.".".$tabla_buscar.".cantidad FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_insumo =".$value['id_insumo'];
                                        $consultaD = _query($sqlD);
                                        $registroD = _fetch_array($consultaD);
                                        $cantidad = $registroD['cantidad'];
                                        $sql_consulta_sg = "SELECT ".EXTERNAL.".stock.id_stock, ".EXTERNAL.".stock.stock FROM ".EXTERNAL.".stock WHERE ".EXTERNAL.".stock.id_producto = $id_producto_eliminado AND ".EXTERNAL.".stock.id_sucursal = 1";
                                        $consulta_sql_sg = _query($sql_consulta_sg);
                                        $registros_sql_sql = _fetch_array($consulta_sql_sg);
                                        $id_stock_original = $registros_sql_sql['id_stock'];
                                        $cantidad_stock_original = $registros_sql_sql['stock'];
                                        $sql_consulta_su = "SELECT ".EXTERNAL.".stock_ubicacion.id_su, ".EXTERNAL.".stock_ubicacion.cantidad FROM ".EXTERNAL.".stock_ubicacion WHERE ".EXTERNAL.".stock_ubicacion.id_producto = $id_producto_eliminado AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = '$id_usb' AND ".EXTERNAL.".stock_ubicacion.id_sucursal = 1";
                                        $consulta_sql_su = _query($sql_consulta_su);
                                        $registros_sql_su = _fetch_array($consulta_sql_su);
                                        $id_ubicacion = $registros_sql_su['id_su'];
                                        $cantidad_stock_ubicacion = $registros_sql_su['cantidad'];
                                        $cantidad_nueva_so = $cantidad_stock_original+$cantidad;
                                        $cantidad_nueva_su = $cantidad_stock_ubicacion+$cantidad;
                                        $tabla3x = "".EXTERNAL.".stock";
                                        $fd3x= array(
                                            'stock' => $cantidad_nueva_so
                                        );
                                        $where3x = " WHERE id_stock = '$id_stock_original'";
                                        $ins3x = _update($tabla3x,$fd3x,$where3x);
                                        if($ins3x){
                                            $tabla4x = "".EXTERNAL.".stock_ubicacion";
                                            $fd4x= array(
                                                'cantidad' => $cantidad_nueva_su
                                            );
                                            $where4x = " WHERE id_su = '$id_ubicacion'";
                                            $ins4x = _update($tabla4x,$fd4x,$where4x);
                                            if($ins4x){
                                                $table1x= "".EXTERNAL.".movimiento_producto_detalle";
                                                $form_data1x = array(
                                                    'id_movimiento'=>$id_movimientox,
                                                    'id_producto' => $value['id_producto'],
                                                    'cantidad' => $cantidad,
                                                    'costo' => $value['costo'],
                                                    'precio' => $value['precio'],
                                                    'stock_anterior'=>$cantidad_stock_original,
                                                    'stock_actual'=>$cantidad_nueva_so,
                                                    'id_presentacion' => $value['id_presentacion'],
                                                    'id_server' => '0',
                                                    'id_sucursal' => $id_sucursal,
                                                    'id_server_prod' => '0',
                                                    'id_server_presen' => '0',
                                                    'proceso' => '',
                                                    'referencia' => '0',
                                                    'lote' => '0',
                                                    'fecha' => date("Y:m:d"),
                                                    'hora' => date("H:i:s"),
                                                );
                                                $insert_mov_detx = _insert($table1x,$form_data1x);
                                                if($insert_mov_detx){
                                                    $tabla_deleted="".EXTERNAL.".".$tabla_buscar."";
                                                    $eliminar_insumo = _soft_delete($tabla_deleted," WHERE id_insumo = ".$value['id_insumo']." AND examen = 0");
                                                    if(!$eliminar_insumo){
                                                        $error3++;
                                                    }
                                                }
                                                else{
                                                    $error3++;
                                                }
                                            }
                                            else{
                                                $error3++;
                                            }
                                        }
                                        else{
                                            $error3++;
                                        }
                                    }
                                    if($value['tipo'] == "S"){
                                        $tabla_deleted="".EXTERNAL.".".$tabla_buscar."";
                                        $eliminar_insumo = _soft_delete($tabla_deleted," WHERE id_insumo = ".$value['id_insumo']." AND examen = 0");
                                        if(!$eliminar_insumo){
                                            $error3++;
                                        }
                                    }
                                    if($value['tipo'] == "E"){
                                        $tabla_deleted="".EXTERNAL.".".$tabla_buscar."";
                                        $tabla_delete_insumo = "labangel.examen_paciente_interno";
                                        $sql_verificacion_examen = "SELECT * FROM ".$tabla_delete_insumo." WHERE id_insumo = '".$value['id_insumo']."'";
                                        $query_verificacion_examen = _query($sql_verificacion_examen);
                                        $row_verificacion_examen = _fetch_array($query_verificacion_examen);
                                        $resultados = $row_verificacion_examen['resultados'];
                                        if($resultados == "" || is_null($resultados)){
                                            $xdatos['typeinfo']='Error';
                                            $xdatos['msg']='El examen a eliminar tiene datos agregados!';
                                            _rollback();
                                            $error3++;
                                        }
                                        else{
                                            $eliminar_insumo = _soft_delete($tabla_deleted," WHERE id_insumo = ".$value['id_insumo']."");
                                            if($eliminar_insumo){
                                                $where_delete_insumo = " WHERE id_insumo = '".$value['id_insumo']."' ";
                                                $eliminar_segundo_insumo = _soft_delete($tabla_delete_insumo, $where_delete_insumo);
                                                if(!$eliminar_segundo_insumo){
                                                    $error3++;
                                                }
                                            }
                                            else{
                                                $error3++;
                                            }
                                        }

                                    }
                                }
                            }
                            else{
                                $error3++;
                            }
                        }
                        else{
                            $error3++;
                        }
                    }
                }
                if($error3 == 0){
                    if(!empty($array_examen)){
                        $table1="labangel.examen_paciente_interno";
                        foreach ($array_examen as $key => $value1) {
                            $form_data111=array(
                                'id_examen'=>$value1['id_examen'],
                                'id_doctor'=>"0",
                                'id_paciente'=>$id_paciente,
                                'fecha_cobro'=>date("Y:m:d"),
                                'hora_cobro'=>date("H:i:s"),
                                'fecha_examen'=>date("Y:m:d"),
                                'hora_examen'=>date("H:i:s"),
                                'estado_realizado'=>"Pendiente",
                                'id_sucursal'=>$id_sucursal,
                                'id_recepcion'=>$id_recepcion,
                                'correlativo_m'=>"",
                                'procedencia'=>$id_usb,
                                'correlativo_a'=>"",
                                'id_empleado' =>$id_empleado,
                                'examen_paciente_nulo' => 0,
                                'id_insumo' =>  $value1['id_insumo']
                            );
                            $insertt1=_insert($table1, $form_data111);
                            if($insertt1){
                                $xdatos['typeinfo']='Success';
                                $xdatos['msg']='Operacion realizada con Exito !';
                                _commit();
                            }
                            else{
                                $xdatos['typeinfo']='Error';
                                $xdatos['msg']='No se pudo realizar la operacion!';
                                _rollback();
                            }
                        }


                    }
                    else{
                        $xdatos['typeinfo']='Success';
                        $xdatos['msg']='Operacion realizada con Exito !';
                        _commit();
                    }
                }
                else{
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='No se pudo realizar la operacion!';
                    _rollback();
                }
            }
            else{
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='No se pudo realizar la operacion!';
                _rollback();
            }
        }
        else{
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='No se pudo realizar la operacion!';
            _rollback();
        }
        echo json_encode($xdatos);
    }*/

    function insert(){
        date_default_timezone_set('America/El_Salvador');
        $error = 0;
        $id_sucursal = $_SESSION['id_sucursal'];
        $id_paciente=$_POST['id_paciente'];
        $id_recepcion = $_POST["id_recepcion"];
        $total = $_POST["total"];
        $items = $_POST["items"];
        $cuantos = $_POST['cuantos'];
        $array_json=$_POST['json_arr'];
        $fecha=date("Y-m-d");
        $hora=date("H:i:s");
        $fecha_hora_ingresar = $fecha." ".$hora;
        $id_empleado=$_SESSION["id_usuario"];
        $fecha_actual = date('Y-m-d');
        $tipoprodserv = "Agregar_Productos";
        $array = json_decode($array_json, true);
        $array_cargas = array();
        $array_descargas = array();
        $precio_cargas = 0;
        $precio_descargas = 0;
        $descarga_de_inventario=0;
        $id_descarga_movimiento;
        $array_tabla = array();
        //Recorre el arreglo con todos los productos y servicios agregados

        foreach ($array as $key => $fila) {
            $id_producto=$fila['id'];
            $id_presentacion =0;
            if(is_numeric($fila['id_presentacion'])){
                $id_presentacion = $fila['id_presentacion'];
            }
            $subtotal=$fila['subtotal'];
            $cantidad=$fila['cantidad'];
            $precio_venta=$fila['precio'];
            $unidad = $fila['unidad'];
            $precio_venta = $precio_venta * $cantidad;
            $cantidad = $cantidad * $unidad;
            $tipop=$fila['tipop'];
            $hora=$fila['fecha'];
            $id_insumo =$fila['id_insumo'];
            if(!is_numeric($id_insumo)){
                $id_insumo = 0;
            }
            $id_insumo_S = $id_insumo;
            $seguir = 1;
            _begin();
            if($tipop=="P"){
                $sql_pres="SELECT ".EXTERNAL.".presentacion_producto.unidad 
                FROM ".EXTERNAL.".presentacion_producto 
                WHERE ".EXTERNAL.".presentacion_producto.id_presentacion=$id_presentacion 
                AND ".EXTERNAL.".presentacion_producto.id_producto=$id_producto";
                $unidadx = 1;
                $res_pres=_query($sql_pres);
                if(_num_rows($res_pres) > 0){
                    $row = _fetch_array($res_pres);
                    $unidadx=$row['unidad'];
                }
                $cantidad_real=$cantidad*$unidadx;
                $repetido="SELECT insumos_emergencia.id_insumo FROM insumos_emergencia 
                WHERE insumos_emergencia.id_recepcion = $id_recepcion
                AND insumos_emergencia.id_insumo = $id_insumo
                AND insumos_emergencia.producto = '1'
                AND insumos_emergencia.id_producto = $id_producto 
                AND insumos_emergencia.id_presentacion =$id_presentacion
                AND insumos_emergencia.cantidad = $cantidad 
                AND insumos_emergencia.total = $precio_venta 
                AND insumos_emergencia.deleted is NULL";

                $repetidoQuery=_query($repetido);
                $repe = _num_rows($repetidoQuery);
                if($repe == 0){
                    $existente="SELECT insumos_emergencia.id_insumo, insumos_emergencia.cantidad 
                    FROM insumos_emergencia WHERE insumos_emergencia.id_recepcion = $id_recepcion 
                    AND insumos_emergencia.producto = '1' 
                    AND insumos_emergencia.id_producto = '$id_producto' 
                    AND insumos_emergencia.id_insumo = $id_insumo";

                    $existenteQuery=_query($existente);
                    $exist = _num_rows($existenteQuery);
                    $cantidad_anterior = 0;
                    if($exist > 0){
                        $row = _fetch_array($existenteQuery);
                        $id_insumox = $row['id_insumo'];
                        $cantidad_anterior = $row['cantidad'];
                        $tabla = "insumos_emergencia";
                        $fd2= array(
                            'id_recepcion' => $id_recepcion,
                            'id_producto' => $id_producto,
                            'producto' => 1,
                            'id_presentacion'=>$id_presentacion,
                            'cantidad' => $cantidad,
                            'total' => $precio_venta,
                            'created_at' => $fecha_hora_ingresar
                        );
                        $where = " WHERE id_insumo = '$id_insumox'";
                        $ins2 = _update($tabla,$fd2,$where);
                    }
                    else{
                        $tabla = "insumos_emergencia";
                        $fd2= array(
                            'id_recepcion' => $id_recepcion,
                            'id_producto' => $id_producto,
                            'producto' => 1,
                            'id_presentacion'=>$id_presentacion,
                            'cantidad' => $cantidad,
                            'total' => $precio_venta,
                            'created_at' => $fecha_hora_ingresar
                        );
                        $ins2 = _insert($tabla,$fd2);
                    }
                    if($ins2){
                        $cant_st_su="SELECT ".EXTERNAL.".stock_ubicacion.id_su AS id_stock_ubicacion, 
                        ".EXTERNAL.".stock_ubicacion.cantidad, ".EXTERNAL.".presentacion_producto.costo 
                        from ".EXTERNAL.".stock_ubicacion INNER JOIN ".EXTERNAL.".producto 
                        on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".stock_ubicacion.id_producto 
                        INNER JOIN ".EXTERNAL.".presentacion_producto 
                        on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto 
                        WHERE ".EXTERNAL.".stock_ubicacion.id_producto = $id_producto 
                        AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = 1 
                        AND ".EXTERNAL.".presentacion_producto.id_presentacion =$id_presentacion";

                        $cant_stQuery_su=_query($cant_st_su);
                        $row_cant_su = _fetch_array($cant_stQuery_su);
                        $id_su = $row_cant_su['id_stock_ubicacion'];
                        $cant_stock_su = $row_cant_su['cantidad'];
                        $costo = $row_cant_su['costo'];
                        $cant_stock_original="SELECT ".EXTERNAL.".stock.id_stock, ".EXTERNAL.".stock.stock as 
                        cantidad_stock FROM ".EXTERNAL.".stock
                         WHERE ".EXTERNAL.".stock.id_producto = $id_producto AND ".EXTERNAL.".stock.id_sucursal=$id_sucursal";

                        $cant_stock_originalQuery=_query($cant_stock_original);
                        $row_cant_stock_original = _fetch_array($cant_stock_originalQuery);
                        $id_stock_ori = $row_cant_stock_original['id_stock'];
                        $cant_stock_ori = $row_cant_stock_original['cantidad_stock'];
                        $cambio_stock=0;
                        $cantidadTotal=$cantidad-$cantidad_anterior;
                        if($cantidadTotal > 0){
                            $stock_original_nuevo = $cant_stock_ori - $cantidadTotal;
                            $stock_ubicacion_nuevo = $cant_stock_su - $cantidadTotal;
                            $cambio_stock+=1;
                            $array_cargas[] = array(
                                'id_producto' => $id_producto,
                                'id_presentacion' => $id_presentacion,
                                'cantidad' =>   $cantidad,
                                'costo' =>  $costo,
                                'precio' => $precio_venta,
                                'stock_anterior' => $cant_stock_ori,
                                'stock_actual' => $stock_original_nuevo
                            );
                            $precio_cargas+= $precio_venta;
                            if(is_numeric($id_insumo)){
                                $array_tabla[] = array(
                                    'id_insumo' => $id_insumo,
                                    'id_producto' => $fila['id'],
                                    'tipo' => $fila['tipop'],
                                    'cantidad' => $cantidad
                                );
                            }
                        }
                        if($cantidadTotal < 0){
                            $cantidadTotal = $cantidadTotal*(-1);
                            $stock_original_nuevo = $cant_stock_ori + $cantidadTotal;
                            $stock_ubicacion_nuevo = $cant_stock_su + $cantidadTotal;
                            $cambio_stock+=1;
                            $array_descargas[] = array(
                              'id_producto' => $id_producto,
                              'id_presentacion' => $id_presentacion,
                              'cantidad' =>   $cantidad,
                              'costo' =>  $costo,
                              'precio' => $precio_venta,
                              'stock_anterior' => $cant_stock_ori,
                              'stock_actual' => $stock_original_nuevo
                            );
                            $precio_descargas+= $precio_venta;
                            if(is_numeric($id_insumo)){
                                $array_tabla[] = array(
                                    'id_insumo' => $id_insumo,
                                    'id_producto' => $fila['id'],
                                    'tipo' => $fila['tipop'],
                                    'cantidad' => $cantidad
                                );
                            }
                        }
                        if($cambio_stock > 0){
                            $tabla3 = "".EXTERNAL.".stock";
                            $fd3= array(
                                'stock' => $stock_original_nuevo
                            );
                            $where3 = " WHERE id_stock = '$id_stock_ori'";
                            $ins3 = _update($tabla3,$fd3,$where3);
                            if($ins3){
                                $tabla4 = "".EXTERNAL.".stock_ubicacion";
                                $fd4= array(
                                    'cantidad' => $stock_ubicacion_nuevo
                                );
                                $where4 = " WHERE id_su= '$id_su'";
                                $ins4 = _update($tabla4,$fd4,$where4);
                                if($ins4){

                                }
                                else{
                                    $error++;
                                }
                            }
                            else{
                                $error++;
                            }


                        }
                        if($cambio_stock == 0){

                        }
                    }
                }
                else{
                    if(is_numeric($id_insumo)){
                        $array_tabla[] = array(
                            'id_insumo' => $id_insumo,
                            'id_producto' => $fila['id'],
                            'tipo' => $fila['tipop'],
                            'cantidad' => $cantidad
                        );
                    }
                }
            }
            else{
                $f_h = explode(" ",$hora);
                $fecha_hora = unirFecha($f_h[0],$f_h[1], $f_h[2]);
                $unidad=1;
                $cantidad_real=$cantidad*$unidad;
                $existente="SELECT insumos_emergencia.id_insumo FROM insumos_emergencia 
                WHERE insumos_emergencia.id_recepcion = $id_recepcion 
                and insumos_emergencia.servicio = 1 
                AND insumos_emergencia.id_servicio = $id_producto 
                AND insumos_emergencia.total = $precio_venta 
                AND insumos_emergencia.hora_de_aplicacion = '$fecha_hora'  
                AND insumos_emergencia.deleted is NULL AND insumos_emergencia.id_insumo = $id_insumo_S";

                $servicioExistente=_query($existente);
                $repe = _num_rows($servicioExistente);
                if($repe == 0){
                    $tabla = "insumos_emergencia";
                    $fd2= array(
                        'id_recepcion' => $id_recepcion,
                        'id_servicio' => $id_producto,
                        'servicio' => 1,
                        'cantidad' => 1,
                        'total' => $precio_venta,
                        'hora_de_aplicacion' => $fecha_hora,
                        'created_at' => $fecha_hora_ingresar
                    );
                    $ins2 = _insert($tabla,$fd2);
                    if($ins2){
                        $xdatos['typeinfo']='Success';
                        $xdatos['msg']='Registro Guardado con Exito !';
                        if(is_numeric($id_insumo)){
                            $array_tabla[] = array(
                                'id_insumo' => $id_insumo,
                                'id_producto' => $fila['id'],
                                'tipo' => $fila['tipop'],
                                'cantidad' => $cantidad
                            );
                        }
                    }
                    else{
                        $xdatos['typeinfo']='Error';
                        $xdatos['msg']='No se pudo guardar el registro !';
                        $error++;
                    }
                }
                else{
                    if(is_numeric($id_insumo)){
                        $array_tabla[] = array(
                            'id_insumo' => $id_insumo,
                            'id_producto' => $fila['id'],
                            'tipo' => $fila['tipop'],
                            'cantidad' => $cantidad
                        );
                    }
                }
            }
        }
        $error2 =0;
        if($error == 0){
            $hora1=date("H:i:s");
            $dia1 =date('Y-m-d');
            if(!empty($array_cargas)){
                $sql_num=_query("SELECT di FROM ".EXTERNAL.".correlativo WHERE id_sucursal='$id_sucursal'");
                $datos_num = _fetch_array($sql_num);
                $ult = $datos_num["di"]+1;
                $numero_doc=$ult.'_DI';
                $tipo_entrada_salida='Asignacion de productos a la recepcion a la repcion con el id:'.$id_recepcion;
                /*actualizar los correlativos de AI*/
                $corr=1;
                $up=1;
                $table="".EXTERNAL.".correlativo";
                $form_data = array(
                    'di' =>$ult
                );
                $where_clause_c="id_sucursal='".$id_sucursal."'";
                $up_corr=_update($table,$form_data,$where_clause_c);
                if($up_corr){
                    $table="".EXTERNAL.'.movimiento_producto';
                    $form_data = array(
                      'id_sucursal' => $id_sucursal,
                      'correlativo' => $numero_doc,
                      'concepto' => "ASIGNACION DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                      'total' => $precio_cargas,
                      'tipo' => 'SALIDA',
                      'proceso' => 'di',
                      'referencia' => $numero_doc,
                      'id_empleado' => $id_empleado,
                      'fecha' => $dia1,
                      'hora' => $hora1,
                      'id_suc_origen' => $id_sucursal,
                      'id_suc_destino' => $id_sucursal,
                      'id_proveedor' => 0,
                    );
                    $insert_mov =_insert($table,$form_data);
                    $id_movimiento=_insert_id();
                    if($insert_mov){
                        foreach ($array_cargas as $array_cargas1) {
                            $table1= "".EXTERNAL.'.movimiento_producto_detalle';
                            $form_data1 = array(
                                'id_movimiento'=>$id_movimiento,
                                'id_producto' => $array_cargas1['id_producto'],
                                'cantidad' => $array_cargas1['cantidad'],
                                'costo' => $array_cargas1['costo'],
                                'precio' => $array_cargas1['precio'],
                                'stock_anterior'=>$array_cargas1['stock_anterior'],
                                'stock_actual'=>$array_cargas1['stock_actual'],
                                'id_presentacion' => $array_cargas1['id_presentacion']
                            );
                            $insert_mov_det = _insert($table1,$form_data1);
                            if($insert_mov_det){

                            }
                            else{
                                $error2++;
                            }
                        }
                    }
                    else{
                      $error2++;
                    }
                }
                else{
                  $error2++;
                }
            }
            if(!empty($array_descargas)){
                $descarga_de_inventario = 1;
                $sql_num = _query("SELECT ti FROM ".EXTERNAL."correlativo WHERE id_sucursal='$id_sucursal'");
                $datos_num = _fetch_array($sql_num);
                $ult = $datos_num["ti"]+1;
                $numero_doc=$ult.'_TI';
                $tipo_entrada_salida='Descarga de productos de la repcion con el id:'.$id_recepcion;
                /*actualizar los correlativos de AI*/
                $corr=1;
                $up=1;
                $table="".EXTERNAL.".correlativo";
                $form_data = array(
                  'ti' =>$ult
                );
                $where_clause_c="id_sucursal='".$id_sucursal."'";
                $up_corr=_update($table,$form_data,$where_clause_c);
                if($up_corr){
                    $table="".EXTERNAL.'.movimiento_producto';
                    $form_data = array(
                      'id_sucursal' => $id_sucursal,
                      'correlativo' => $numero_doc,
                      'concepto' => "DESCARGA DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                      'total' => $precio_descargas,
                      'tipo' => 'ENTRADA',
                      'proceso' => 'ti',
                      'referencia' => $numero_doc,
                      'id_empleado' => $id_empleado,
                      'fecha' => $dia1,
                      'hora' => $hora1,
                      'id_suc_origen' => $id_sucursal,
                      'id_suc_destino' => $id_sucursal,
                      'id_proveedor' => 0,
                    );
                    $insert_mov =_insert($table,$form_data);
                    $id_movimiento=_insert_id();
                    if($insert_mov){
                        $id_descarga_movimiento = $id_movimiento;
                        foreach ($array_descargas as $array_cargas1) {
                            $table1= ''.EXTERNAL.'.movimiento_producto_detalle';
                            $form_data1 = array(
                                'id_movimiento'=>$id_movimiento,
                                'id_producto' => $array_cargas1['id_producto'],
                                'cantidad' => $array_cargas1['cantidad'],
                                'costo' => $array_cargas1['costo'],
                                'precio' => $array_cargas1['precio'],
                                'stock_anterior'=>$array_cargas1['stock_anterior'],
                                'stock_actual'=>$array_cargas1['stock_actual'],
                                'id_presentacion' => $array_cargas1['id_presentacion']
                            );
                            $insert_mov_det = _insert($table1,$form_data1);
                            if($insert_mov_det){

                            }
                            else{
                                $error2++;
                            }
                        }
                    }
                    else{
                        $error2++;
                    }
                }
                else{
                    $error2++;
                }
            }
        }
        else{
            $error2++;
        }
        if($error2 == 0){
            $algun_producto=0;
            $error3=0;
            $array_base = array();
            $sqlx="SELECT insumos_emergencia.id_insumo, insumos_emergencia.total, 
            insumos_emergencia.id_producto, insumos_emergencia.id_servicio, 
            insumos_emergencia.producto, insumos_emergencia.servicio, 
            insumos_emergencia.id_presentacion, ".EXTERNAL.".presentacion_producto.costo 
            FROM insumos_emergencia LEFT JOIN 
            ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = insumos_emergencia.id_producto 
            LEFT JOIN ".EXTERNAL.".presentacion_producto 
            on presentacion_producto.id_presentacion = insumos_emergencia.id_presentacion 
            WHERE insumos_emergencia.id_recepcion = $id_recepcion AND insumos_emergencia.deleted is NULL 
            AND insumos_emergencia.created_at != '$fecha_hora_ingresar' ";

            $consultax = _query($sqlx);
            while($rowx = _fetch_array($consultax)){
                $tipop = "";
                $producto = $rowx['producto'];
                $servicio = $rowx['servicio'];
                $id_producto=0;
                if($producto == 1){
                  $tipop = "P";
                  $id_producto = $rowx['id_producto'];
                }
                if($servicio == 1){
                  $tipop = "S";
                  $id_producto = $rowx['id_servicio'];
                }
                $id_insumo = $rowx['id_insumo'];
                $array_base[] = array(
                    'id_insumo' =>$id_insumo,
                    'id_producto' => $id_producto,
                    'tipo' => $tipop,
                    'precio' => $rowx['total'],
                    'id_presentacion' => $rowx['id_presentacion'],
                    'costo' => $rowx['costo']
                );
            }
            $count_arreglo0=0;
            $existe=0;


            foreach ($array_base as $key => $value) {
                $id_insumoX = $value['id_insumo'];
                $id_productoX = $value['id_producto'];
                $tipoX = $value['tipo'];
                if(!is_numeric($id_insumo)){
                    unset($array_base[$count_arreglo0]);
                }
                else{
                    $coun_arreglo = 0;
                    foreach ($array_tabla as $key1 => $value1) {
                        $id_insumoTB = $value1['id_insumo'];
                        $id_productoTB = $value1['id_producto'];
                        $tipoTB = $value1['tipo'];
                        if($id_insumoX == $id_insumoTB){
                            unset($array_base[$count_arreglo0]);
                        }
                        $coun_arreglo++;
                    }
                }
                $count_arreglo0++;
            }

            foreach ($array_base as $key => $value) {
                $tipoX = $value['tipo'];
                if($tipoTB == "P"){
                    $algun_producto++;
                }
            }
            if(!empty($array_base)){
                if($algun_producto == 0){
                    foreach ($array_base as $key1 => $value1){
                        $tabla_deleted="insumos_emergencia";
                        $where = " WHERE id_insumo = ".$value1['id_insumo'];
                        $eliminar_insumo = _delete($tabla_deleted,$where);
                        if($eliminar_insumo){
                          $xdatos['typeinfo']='Success';
                          $xdatos['msg']='Operacion realizada con Exito !';
                        }
                        else{
                          $error3++;
                        }
                    }
                }
                else{
                    $precio_total_eliminado = 0;
                    foreach ($array_base as $key => $value1){
                        if($value1['tipo'] == "P"){
                          $precio_total_eliminado+= $value1['precio'];
                        }
                    }
                    $sql_num = _query("SELECT ti FROM ".EXTERNAL.".correlativo WHERE id_sucursal='$id_sucursal'");
                    $datos_num = _fetch_array($sql_num);
                    $ult = $datos_num["ti"]+1;
                    $numero_doc=$ult.'_TI';
                    /*actualizar los correlativos de AI*/
                    $tableC="".EXTERNAL.".correlativo";
                    $form_dataC = array(
                        'ti' =>$ult
                    );
                    $where_clause_cC="id_sucursal='".$id_sucursal."'";
                    $up_corrC=_update($tableC,$form_dataC,$where_clause_cC);
                    if($up_corrC){
                        $table=''.EXTERNAL.'.movimiento_producto';
                        $form_data = array(
                            'id_sucursal' => $id_sucursal,
                            'correlativo' => $numero_doc,
                            'concepto' => "ELIMINACION DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                            'total' => $precio_total_eliminado,
                            'tipo' => 'ENTRADA',
                            'proceso' => 'ti',
                            'referencia' => $numero_doc,
                                'id_empleado' => $id_empleado,
                                'fecha' => $dia1,
                                'hora' => $hora1,
                                'id_suc_origen' => $id_sucursal,
                                'id_suc_destino' => $id_sucursal,
                                'id_proveedor' => 0,
                        );
                        $insert_mov =_insert($table,$form_data);
                        $id_movimientox=_insert_id();
                        if($insert_mov){
                          foreach ($array_base as $key => $value) {
                              $hora1=date("H:i:s");
                              $dia1 =date('Y-m-d');
                              if($value['tipo'] == "P"){
                                  $id_producto_eliminado = $value['id_producto'];
                                  $sqlD = "SELECT insumos_emergencia.cantidad FROM insumos_emergencia WHERE insumos_emergencia.id_insumo =".$value['id_insumo'];
                                  $consultaD = _query($sqlD);
                                  $registroD = _fetch_array($consultaD);
                                  $cantidad = $registroD['cantidad'];
                                  $sql_consulta_sg="SELECT ".EXTERNAL.".stock.id_stock, ".EXTERNAL.".stock.stock AS cantidad_stock FROM ".EXTERNAL.".stock WHERE ".EXTERNAL.".stock.id_producto= $id_producto_eliminado AND ".EXTERNAL.".stock.id_sucursal = 1 ";
                                  $consulta_sql_sg = _query($sql_consulta_sg);
                                  $registros_sql_sql = _fetch_array($consulta_sql_sg);
                                  $id_stock_original = $registros_sql_sql['id_stock'];
                                  $cantidad_stock_original = $registros_sql_sql['cantidad_stock'];
                                  $sql_consulta_su="SELECT ".EXTERNAL.".stock_ubicacion.id_su AS id_stock_ubicacion, 
                                  ".EXTERNAL.".stock_ubicacion.cantidad FROM ".EXTERNAL.".stock_ubicacion WHERE ".EXTERNAL.".stock_ubicacion.id_producto = $id_producto_eliminado 
                                  AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = 1 AND ".EXTERNAL.".stock_ubicacion.id_sucursal = 1 ";
                                  $consulta_sql_su = _query($sql_consulta_su);
                                  $registros_sql_su = _fetch_array($consulta_sql_su);
                                  $id_stock_ubicacion = $registros_sql_su['id_stock_ubicacion'];
                                  $cantidad_stock_ubicacion = $registros_sql_su['cantidad'];
                                  $cantidad_nueva_so = $cantidad_stock_original+$cantidad;
                                  $cantidad_nueva_su = $cantidad_stock_ubicacion+$cantidad;
                                  $tabla3x = "".EXTERNAL.".stock";
                                  $fd3x= array(
                                      'stock' => $cantidad_nueva_so
                                  );
                                  $where3x = " WHERE id_stock = '$id_stock_original'";
                                  $ins3x = _update($tabla3x,$fd3x,$where3x);
                                  if($ins3x){
                                      $tabla4x = "".EXTERNAL.".stock_ubicacion";
                                      $fd4x= array(
                                          'cantidad' => $cantidad_nueva_su
                                      );
                                      $where4x = " WHERE id_su = '$id_stock_ubicacion'";
                                      $ins4x = _update($tabla4x,$fd4x,$where4x);
                                      if($ins4x){
                                          $table1x= "".EXTERNAL.".movimiento_producto_detalle";
                                          $form_data1x = array(
                                              'id_movimiento'=>$id_movimientox,
                                              'id_producto' => $value['id_producto'],
                                              'cantidad' => $cantidad,
                                              'costo' => $value['costo'],
                                              'precio' => $value['precio'],
                                              'stock_anterior'=>$cantidad_stock_original,
                                              'stock_actual'=>$cantidad_nueva_so,
                                              'id_presentacion' => $value['id_presentacion']
                                          );
                                          $insert_mov_detx = _insert($table1x,$form_data1x);
                                          if($insert_mov_detx){
                                              $tabla_deleted="insumos_emergencia";
                                              $eliminar_insumo = _delete($tabla_deleted," WHERE id_insumo = ".$value['id_insumo']);
                                              if($eliminar_insumo){

                                              }
                                              else{
                                                $error3++;
                                              }
                                          }
                                          else{
                                              $error3++;
                                          }
                                      }
                                      else{
                                          $error3++;
                                      }
                                  }
                                  else{
                                      $error3++;
                                  }
                              }
                              if($value['tipo'] == "S"){
                                    $tabla_deleted="insumos_emergencia";
                                    $eliminar_insumo = _delete($tabla_deleted," WHERE id_insumo = ".$value['id_insumo']);
                                    if($eliminar_insumo){

                                    }
                                    else{
                                        $error3++;
                                    }
                                }
                            }
                        }
                        else{
                            $error3++;
                        }
                    }
                    else{
                        $error3++;
                    }
                }
            }
            if($error3 == 0){
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Operacion realizada con Exito !';
                _commit();
            }
            else{
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='No se pudo realizar la operacion!';
                _rollback();
            }
        }
        else{
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='No se pudo realizar la operacion!';
            _rollback();
        }
        echo json_encode($xdatos);
    }



    function unirFecha($fecha, $hora, $horario){
      $fechaN = explode("-",$fecha);
      $fecha = $fechaN[2]."-".$fechaN[1]."-".$fechaN[0];
      $horaNU = explode(":", $hora);
      if($horario == "PM"){
          if($horaNU[0] != 12){
              $horaNU[0] += 12;
          }
      }
      if($horario == "AM"){
        if($horaNU[0] == 12){
           $horaNU[0] = "00";
        }
      }
      $horaDevolver = $fecha." ".$horaNU[0].":".$horaNU[1].":00";
      return $horaDevolver;

  }

?>

<?php
    function get_presentacion(){
        $id_usb = $_REQUEST['id_usb'];
        $tabla_buscar = $_REQUEST['tabla_buscar'];
        $id_presentacion =$_REQUEST['id_presentacion'];
        $id_recepcion =$_REQUEST['id_recepcion'];
        $id_P = _fetch_array(_query("SELECT ".EXTERNAL.".producto.id_producto, ".EXTERNAL.".presentacion_producto.precio, ".EXTERNAL.".presentacion_producto.unidad, ".EXTERNAL.".presentacion_producto.descripcion FROM ".EXTERNAL.".producto LEFT JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_producto = ".EXTERNAL.".producto.id_producto WHERE ".EXTERNAL.".presentacion_producto.id_presentacion = $id_presentacion"));
        $id_producto = $id_P['id_producto'];
        $sql=_fetch_array(_query("SELECT ".EXTERNAL.".stock_ubicacion.id_producto, SUM(".EXTERNAL.".stock_ubicacion.cantidad) total FROM ( SELECT ".EXTERNAL.".stock_ubicacion.id_producto, ".EXTERNAL.".stock_ubicacion.cantidad FROM ".EXTERNAL.".stock_ubicacion WHERE ".EXTERNAL.".stock_ubicacion.id_producto = $id_producto AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = '$id_usb' UNION ALL SELECT ".EXTERNAL.".".$tabla_buscar.".id_producto, ".EXTERNAL.".".$tabla_buscar.".cantidad FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_producto = $id_producto AND ".EXTERNAL.".".$tabla_buscar.".id_recepcion = $id_recepcion) stock_ubicacion GROUP BY ".EXTERNAL.".stock_ubicacion.id_producto "));
        $precio=$id_P['precio'];
        $unidad=$id_P['unidad'];
        $descripcion=$id_P['descripcion'];
        $cantidad=$sql['total'];
        $total = $cantidad / $unidad;
        $total = round($total, 0, PHP_ROUND_HALF_DOWN);
        $des = "<input type='text' id='dsd2' class='form-control' value='".$descripcion."' readonly>";
        $xdatos['precio']=$precio;
        $xdatos['unidad']=$unidad;
        $xdatos['descripcion']=$descripcion;
        $xdatos['total']=$total;
        echo json_encode($xdatos);
    }
?>
<?php

    function consultar_existencias(){
        $id_usb = $_POST['id_usb'];
        $tabla_buscar = $_POST['tabla_buscar'];
        $id_recepcion=$_POST['idRecepcion'];
        $id_producto = $_POST['id_producto'];
        $id_presentacion="";
        if (isset($_REQUEST['id_presentacion'])){
            $id_presentacion=$_REQUEST['id_presentacion'];
        }

        //select arreglado
        /*$sql3='
        SELECT cmf.stock_ubicacion.id_producto, cmf.stock_ubicacion.cantidad FROM cmf.stock_ubicacion
        SELECT cmf.stock_ubicacion.id_producto, cmf.stock_ubicacion.cantidad FROM cmf.stock_ubicacion WHERE cmf.stock_ubicacion.id_ubicacion=1 
        SELECT insumos_hospitalizacion.id_producto, insumos_hospitalizacion.cantidad FROM insumos_hospitalizacion WHERE insumos_hospitalizacion.id_producto=439 AND insumos_hospitalizacion.id_recepcion=4 AND insumos_hospitalizacion.deleted IS NULL
        
                SELECT sub_stock.id_producto, SUM(sub_stock.cantidad) FROM 
        (SELECT stock.id_producto, stock.cantidad FROM '.EXTERNAL.'.stock_ubicacion AS stock WHERE stock.id_ubicacion=1 AND stock.id_producto='.$id_producto.'
         UNION ALL 
         SELECT ins.id_producto, ins.cantidad FROM insumos_hospitalizacion as ins WHERE ins.id_producto='.$id_producto.' AND ins.id_recepcion='.$id_recepcion.' AND ins.deleted IS NULL) AS sub_stock
         ORDER BY sub_stock.id_producto
        ';*/
        $sql3='
        SELECT sub_stock.id_producto, SUM(sub_stock.cantidad) FROM 
        (SELECT stock.id_producto, stock.cantidad FROM '.EXTERNAL.'.stock_ubicacion AS stock WHERE stock.id_ubicacion=1 AND stock.id_producto='.$id_producto.'
         UNION ALL 
         SELECT ins.id_producto, ins.cantidad FROM cms.insumos_hospitalizacion as ins WHERE ins.id_producto='.$id_producto.' AND ins.id_recepcion='.$id_recepcion.' AND ins.deleted IS NULL) AS sub_stock
         ORDER BY sub_stock.id_producto
        ';


        //echo $sql;
        $consulta = _query($sql3);
        $row = _fetch_array($consulta);
        $xdatos['total'] = $row['total'];
        if($id_presentacion == ""){
            $sql2 ="SELECT ".EXTERNAL.".presentacion_producto.unidad FROM ".EXTERNAL.".presentacion_producto WHERE ".EXTERNAL.".presentacion_producto.id_producto = $id_producto";
        }
        else{
            $sql2 ="SELECT ".EXTERNAL.".presentacion_producto.unidad FROM ".EXTERNAL.".presentacion_producto WHERE ".EXTERNAL.".presentacion_producto.id_producto = $id_producto AND ".EXTERNAL.".presentacion_producto.id_presentacion = $id_presentacion";
        }
        $consulta2 = _query($sql2);
        $i = 0;
        while ($row1=_fetch_array($consulta2)) {
            if ($i==0) {
                $unidadp=$row1['unidad'];
            }
            $i=$i+1;
        }
        $xdatos['unidad'] = $unidadp;
        echo json_encode($xdatos);
    }
?>


<?php
    function consultar_selects(){
        $id_recepcion=$_POST['idRecepcion'];
        $id_producto = $_POST['id_producto'];
        $cantidad_general = $_POST['cantidad_general'];
        $cantidad_especifica = $_POST['cantidad_especifica'];
        $id_presentacion = $_POST['id_presentacion'];
        $id_usb = $_POST['id_usb'];
        $tabla_buscar = $_POST['tabla_buscar'];
        $sql = "SELECT ".EXTERNAL.".stock_ubicacion.id_producto, SUM(".EXTERNAL.".stock_ubicacion.cantidad) total FROM ( SELECT ".EXTERNAL.".stock_ubicacion.id_producto, ".EXTERNAL.".stock_ubicacion.cantidad FROM ".EXTERNAL.".stock_ubicacion WHERE ".EXTERNAL.".stock_ubicacion.id_producto = $id_producto AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = '$id_usb' UNION ALL SELECT ".EXTERNAL.".".$tabla_buscar.".id_producto, ".EXTERNAL.".".$tabla_buscar.".cantidad FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".producto = $id_producto AND ".EXTERNAL.".".$tabla_buscar.".id_recepcion = $id_recepcion AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL ) stock_ubicacion GROUP BY ".EXTERNAL.".stock_ubicacion.id_producto ";
        $consulta = _query($sql);
        $row = _fetch_array($consulta);
        $total = $row['total'];
        $numero_unidades =  $cantidad_especifica;
        $consulta_select="SELECT ".EXTERNAL.".presentacion.nombre, ".EXTERNAL.".presentacion_producto.descripcion,".EXTERNAL.".presentacion_producto.id_presentacion,
        ".EXTERNAL.".presentacion_producto.unidad,".EXTERNAL.".presentacion_producto.precio FROM ".EXTERNAL.".presentacion_producto JOIN ".EXTERNAL.".presentacion ON ".EXTERNAL.".presentacion.id_presentacion=".EXTERNAL.".presentacion_producto.presentacion
        LEFT JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto LEFT  JOIN ".EXTERNAL.".stock_ubicacion on ".EXTERNAL.".stock_ubicacion.id_producto = ".EXTERNAL.".producto.id_producto
        WHERE ".EXTERNAL.".presentacion_producto.id_producto=$id_producto AND ".EXTERNAL.".presentacion_producto.activo =1 and ".EXTERNAL.".presentacion_producto.unidad <= $numero_unidades GROUP BY ".EXTERNAL.".presentacion_producto.id_presentacion";
        $sql_p = _query($consulta_select);
        $select="<select class='sel id_pres form-control' id='id_presentacion'>";
        while ($row=_fetch_array($sql_p)) {
            if($row['id_presentacion'] == $id_presentacion){
                $select.="<option value=".$row['id_presentacion']." selected>".$row['nombre']."</option>";
            }
            else{
              $select.="<option value=".$row['id_presentacion'].">".$row['nombre']."</option>";
            }
        }
        $select.="</select>";
        $xdatos['select']=$select;
        echo json_encode($xdatos);

    }
?>


<?php
    function total_texto()
    {
      $total=$_REQUEST['total'];
      list($entero, $decimal)=explode('.', $total);
      $enteros_txt=num2letras($entero);
      $decimales_txt=num2letras($decimal);

      if ($entero>1) {
        $dolar=" dolares";
      } else {
        $dolar=" dolar";
      }
      $cadena_salida= "Son: <strong>".$enteros_txt.$dolar." con ".$decimal."/100 ctvs.</strong>";
      echo $cadena_salida;
    }
?>


<?php
    function finalizar(){
        $id_usb = $_POST['id_usb'];
        $tabla_buscar = $_POST['tabla_buscar'];
        date_default_timezone_set('America/El_Salvador');
        $error = 0;
        $id_sucursal = $_SESSION['id_sucursal'];
        $id_recepcion = $_POST["id_recepcion"];
        $sql_paciente = "SELECT * FROM recepcion where id_recepcion = '$id_recepcion'";
        $query_recepcion = _query($sql_paciente);
        $row_paciente = _fetch_array($query_recepcion);
        $id_paciente = $row_paciente['id_paciente_recepcion'];
        $total = $_POST["total"];
        $items = $_POST["items"];
        $cuantos = $_POST['cuantos'];
        $array_json=$_POST['json_arr'];
        $fecha=date("Y-m-d");
        $hora=date("H:i:s");
        $fecha_hora_ingresar = $fecha." ".$hora;
        $id_empleado=$_SESSION["id_usuario"];
        $fecha_actual = date('Y-m-d');
        $tipoprodserv = "Agregar_Productos";
        $array = json_decode($array_json, true);
        $array_cargas = array();
        $array_descargas = array();
        $precio_cargas = 0;
        $precio_descargas = 0;
        $descarga_de_inventario=0;
        $id_descarga_movimiento;
        $array_tabla = array();
        $array_examen = array();
        //Recorre el arreglo con todos los productos y servicios_hospitalarios agregados
        foreach ($array as $key => $fila) {
            $id_producto=$fila['id'];
            $id_presentacion =0;
            if(isset($fila['id_presentacion'])){
                if(is_numeric($fila['id_presentacion'])){
                    $id_presentacion = $fila['id_presentacion'];
                }
            }
            else{
                $id_presentacion = 0;
            }
            $subtotal=$fila['subtotal'];
            $cantidad=$fila['cantidad'];
            $cantidad_servicio = $cantidad;
            $precio_venta=$fila['precio'];
            $unidad = $fila['unidad'];
            $precio_venta = $precio_venta * $cantidad;
            $cantidad = $cantidad * $unidad;
            $tipop=$fila['tipop'];
            $hora=$fila['fecha'];
            if(isset($fila['id_insumo'])){
                $id_insumo =$fila['id_insumo'];
            }
            else{
                $id_insumo = 0;
            }

            if(!is_numeric($id_insumo)){
                $id_insumo = 0;
            }
            $id_insumo_S = $id_insumo;
            $seguir = 1;
            _begin();
            if($tipop=="P"){
                $sql_pres="SELECT ".EXTERNAL.".presentacion_producto.unidad FROM ".EXTERNAL.".presentacion_producto WHERE ".EXTERNAL.".presentacion_producto.id_presentacion='$id_presentacion' AND ".EXTERNAL.".presentacion_producto.id_producto='$id_producto' ";
                $unidadx = 1;
                $res_pres=_query($sql_pres);
                if(_num_rows($res_pres) > 0){
                    $row = _fetch_array($res_pres);
                    $unidadx=$row['unidad'];
                }
                $cantidad_real=$cantidad*$unidadx;
                $repetido = "SELECT ".EXTERNAL.".".$tabla_buscar.".id_insumo FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_recepcion = $id_recepcion AND ".EXTERNAL.".".$tabla_buscar.".id_insumo = $id_insumo AND ".EXTERNAL.".".$tabla_buscar.".producto = '1' AND ".EXTERNAL.".".$tabla_buscar.".id_producto = '$id_producto' AND ".EXTERNAL.".".$tabla_buscar.".id_presentacion = $id_presentacion AND ".EXTERNAL.".".$tabla_buscar.".cantidad = $cantidad AND ".EXTERNAL.".".$tabla_buscar.".total = $precio_venta AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL";
                $repetidoQuery=_query($repetido);
                $repe = _num_rows($repetidoQuery);
                if($repe == 0){
                    $existente = "SELECT ".EXTERNAL.".".$tabla_buscar.".id_insumo, ".EXTERNAL.".".$tabla_buscar.".cantidad FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_recepcion = '$id_recepcion' AND ".EXTERNAL.".".$tabla_buscar.".producto = '1' AND ".EXTERNAL.".".$tabla_buscar.".id_producto = '$id_producto' AND ".EXTERNAL.".".$tabla_buscar.".id_insumo = $id_insumo";
                    $existenteQuery=_query($existente);
                    $exist = _num_rows($existenteQuery);
                    $cantidad_anterior = 0;
                    if($exist > 0){
                        $row = _fetch_array($existenteQuery);
                        $id_insumox = $row['id_insumo'];
                        $cantidad_anterior = $row['cantidad'];
                        $tabla = "".EXTERNAL.".".$tabla_buscar."";
                        $fd2= array(
                            'id_recepcion' => $id_recepcion,
                            'id_producto' => $id_producto,
                            'producto' => 1,
                            'id_presentacion'=>$id_presentacion,
                            'cantidad' => $cantidad,
                            'total' => $precio_venta,
                            'created_at' => $fecha_hora_ingresar
                        );
                        $where = " WHERE id_insumo = '$id_insumox'";
                        $ins2 = _update($tabla,$fd2,$where);
                    }
                    else{
                        $tabla = "".EXTERNAL.".".$tabla_buscar."";
                        $fd2= array(
                            'id_recepcion' => $id_recepcion,
                            'id_producto' => $id_producto,
                            'producto' => 1,
                            'id_presentacion'=>$id_presentacion,
                            'cantidad' => $cantidad,
                            'total' => $precio_venta,
                            'created_at' => $fecha_hora_ingresar
                        );
                        $ins2 = _insert($tabla,$fd2);
                    }
                    if($ins2){
                        $cant_st_su = "SELECT ".EXTERNAL.".stock_ubicacion.id_su, ".EXTERNAL.".stock_ubicacion.cantidad, ".EXTERNAL.".presentacion_producto.costo from ".EXTERNAL.".stock_ubicacion LEFT JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".stock_ubicacion.id_producto LEFT JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto WHERE ".EXTERNAL.".stock_ubicacion.id_producto = $id_producto AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = '$id_usb' AND ".EXTERNAL.".presentacion_producto.id_presentacion = $id_presentacion ";
                        $cant_stQuery_su=_query($cant_st_su);
                        $row_cant_su = _fetch_array($cant_stQuery_su);
                        $id_su = $row_cant_su['id_su'];
                        $cant_stock_su = $row_cant_su['cantidad'];
                        $costo = $row_cant_su['costo'];
                        $cant_stock_original = "SELECT ".EXTERNAL.".stock.id_stock, ".EXTERNAL.".stock.stock FROM ".EXTERNAL.".stock WHERE ".EXTERNAL.".stock.id_producto = $id_producto AND ".EXTERNAL.".stock.id_sucursal = $id_sucursal";
                        $cant_stock_originalQuery=_query($cant_stock_original);
                        $row_cant_stock_original = _fetch_array($cant_stock_originalQuery);
                        $id_stock_ori = $row_cant_stock_original['id_stock'];
                        $cant_stock_ori = $row_cant_stock_original['stock'];
                        $cambio_stock=0;
                        $cantidadTotal=$cantidad-$cantidad_anterior;
                        if($cantidadTotal > 0){
                            $stock_original_nuevo = $cant_stock_ori - $cantidadTotal;
                            $stock_ubicacion_nuevo = $cant_stock_su - $cantidadTotal;
                            $cambio_stock+=1;
                            $array_cargas[] = array(
                                'id_producto' => $id_producto,
                                'id_presentacion' => $id_presentacion,
                                'cantidad' =>   $cantidad,
                                'costo' =>  $costo,
                                'precio' => $precio_venta,
                                'stock_anterior' => $cant_stock_ori,
                                'stock_actual' => $stock_original_nuevo
                            );
                            $precio_cargas+= $precio_venta;
                            if(is_numeric($id_insumo)){
                                $array_tabla[] = array(
                                    'id_insumo' => $id_insumo,
                                    'id_producto' => $fila['id'],
                                    'tipo' => $fila['tipop'],
                                    'cantidad' => $cantidad
                                );
                            }
                        }
                        if($cantidadTotal < 0){
                            $cantidadTotal = $cantidadTotal*(-1);
                            $stock_original_nuevo = $cant_stock_ori + $cantidadTotal;
                            $stock_ubicacion_nuevo = $cant_stock_su + $cantidadTotal;
                            $cambio_stock+=1;
                            $array_descargas[] = array(
                              'id_producto' => $id_producto,
                              'id_presentacion' => $id_presentacion,
                              'cantidad' =>   $cantidad,
                              'costo' =>  $costo,
                              'precio' => $precio_venta,
                              'stock_anterior' => $cant_stock_ori,
                              'stock_actual' => $stock_original_nuevo
                            );
                            $precio_descargas+= $precio_venta;
                            if(is_numeric($id_insumo)){
                                $array_tabla[] = array(
                                    'id_insumo' => $id_insumo,
                                    'id_producto' => $fila['id'],
                                    'tipo' => $fila['tipop'],
                                    'cantidad' => $cantidad
                                );
                            }
                        }
                        if($cambio_stock > 0){
                            $tabla3 = "".EXTERNAL.".stock";
                            $fd3= array(
                                'stock' => $stock_original_nuevo
                            );
                            $where3 = " WHERE id_stock = '$id_stock_ori'";
                            $ins3 = _update($tabla3,$fd3,$where3);
                            if($ins3){
                                $tabla4 = "".EXTERNAL.".stock_ubicacion";
                                $fd4= array(
                                    'cantidad' => $stock_ubicacion_nuevo
                                );
                                $where4 = " WHERE id_su = '$id_su'";
                                $ins4 = _update($tabla4,$fd4,$where4);
                                if($ins4){

                                }
                                else{
                                    $error++;
                                }
                            }
                            else{
                                $error++;
                            }


                        }
                        if($cambio_stock == 0){

                        }
                    }
                }
                else{
                    if(is_numeric($id_insumo)){
                        $array_tabla[] = array(
                            'id_insumo' => $id_insumo,
                            'id_producto' => $fila['id'],
                            'tipo' => $fila['tipop'],
                            'cantidad' => $cantidad
                        );
                    }
                }
            }
            if($tipop == "EXAMEN_AGREGADO"){
                $select = "SELECT * FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_insumo = '$id_insumo_S' AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL";
                $query_select = _query($select);
                if(_num_rows($query_select) > 0){
                    if(is_numeric($id_insumo)){
                        $array_tabla[] = array(
                            'id_insumo' => $id_insumo,
                            'id_producto' => $fila['id'],
                            'tipo' => "EXAMEN_AGREGADO",
                            'cantidad' => '1'
                        );
                    }
                }
                else{
                    $tabla = "".EXTERNAL.".".$tabla_buscar."";
                    $fd2= array(
                        'id_recepcion' => $id_recepcion,
                        'id_examen' => $id_producto,
                        'examen' => 1,
                        'cantidad' => 1,
                        'total' => $precio_venta,
                        'hora_de_aplicacion' => date("Y:m:d")." ".date("h:i:s"),
                        'created_at' => $fecha_hora_ingresar
                    );
                    $ins2 = _insert($tabla,$fd2);
                    if($ins2){
                        $xdatos['typeinfo']='Success';
                        $xdatos['msg']='Registro Guardado con Exito !';
                        $array_examen[] = array(
                            'id_examen' => $fila['id'],
                        );
                        if(is_numeric($id_insumo)){
                            $array_tabla[] = array(
                                'id_insumo' => $id_insumo,
                                'id_producto' => $fila['id'],
                                'tipo' => $fila['tipop'],
                                'cantidad' => '1'
                            );
                        }
                    }
                    else{
                        $xdatos['typeinfo']='Error';
                        $xdatos['msg']='No se pudo guardar el registro !';
                        $error++;
                    }
                }

            }
            if($tipop == "S"){
                $f_h = explode(" ",$hora);
                $fecha_hora = unirFecha($f_h[0],$f_h[1], $f_h[2]);
                $unidad=1;
                $cantidad_real=$cantidad*$unidad;
                $existente = "SELECT ".EXTERNAL.".".$tabla_buscar.".id_insumo FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_recepcion = $id_recepcion and ".EXTERNAL.".".$tabla_buscar.".servicio = 1 AND ".EXTERNAL.".".$tabla_buscar.".id_servicio = $id_producto AND ".EXTERNAL.".".$tabla_buscar.".total = $precio_venta AND ".EXTERNAL.".".$tabla_buscar.".hora_de_aplicacion = '$fecha_hora'  AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL AND ".EXTERNAL.".".$tabla_buscar.".id_insumo = $id_insumo_S";
                $servicioExistente=_query($existente);
                $repe = _num_rows($servicioExistente);
                if($repe == 0){
                    $tabla = "".EXTERNAL.".".$tabla_buscar."";
                    $fd2= array(
                        'id_recepcion' => $id_recepcion,
                        'id_servicio' => $id_producto,
                        'servicio' => 1,
                        'cantidad' => $cantidad_servicio,
                        'total' => $precio_venta,
                        'hora_de_aplicacion' => $fecha_hora,
                        'created_at' => $fecha_hora_ingresar
                    );
                    $ins2 = _insert($tabla,$fd2);
                    if($ins2){
                        $xdatos['typeinfo']='Success';
                        $xdatos['msg']='Registro Guardado con Exito !';
                        if(is_numeric($id_insumo)){
                            $array_tabla[] = array(
                                'id_insumo' => $id_insumo,
                                'id_producto' => $fila['id'],
                                'tipo' => $fila['tipop'],
                                'cantidad' => $cantidad_servicio
                            );
                        }
                    }
                    else{
                        $xdatos['typeinfo']='Error';
                        $xdatos['msg']='No se pudo guardar el registro !';
                        $error++;
                    }
                }
                else{
                    if(is_numeric($id_insumo)){
                        $array_tabla[] = array(
                            'id_insumo' => $id_insumo,
                            'id_producto' => $fila['id'],
                            'tipo' => $fila['tipop'],
                            'cantidad' => $cantidad_servicio
                        );
                    }
                }
            }
        }
        $error2 =0;
        if($error == 0){
            $hora1=date("H:i:s");
            $dia1 =date('Y-m-d');
            if(!empty($array_cargas)){
                $sql_num = _query("SELECT di FROM ".EXTERNAL.".correlativo WHERE id_sucursal='$id_sucursal'");
                $datos_num = _fetch_array($sql_num);
                $ult = $datos_num["di"]+1;
                $numero_doc=$ult.'_DI';
                $tipo_entrada_salida='Asignacion de productos a la recepcion a la repcion con el id:'.$id_recepcion;
                /*actualizar los correlativos de AI*/
                $corr=1;
                $up=1;
                $table="".EXTERNAL.".correlativo";
                $form_data = array(
                    'di' =>$ult
                );
                $where_clause_c="id_sucursal='".$id_sucursal."'";
                $up_corr=_update($table,$form_data,$where_clause_c);
                if($up_corr){
                    $table="".EXTERNAL.".movimiento_producto";
                    $form_data = array(
                      'id_sucursal' => $id_sucursal,
                      'correlativo' => $numero_doc,
                      'concepto' => "ASIGNACION DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                      'total' => $precio_cargas,
                      'tipo' => 'SALIDA',
                      'proceso' => 'di',
                      'referencia' => $numero_doc,
                      'id_empleado' => $id_empleado,
                      'fecha' => $dia1,
                      'hora' => $hora1,
                      'id_suc_origen' => $id_sucursal,
                      'id_suc_destino' => $id_sucursal,
                      'id_proveedor' => 0,
                      'id_server' => '0',
                      'id_compra' => '0',
                      'id_traslado' => '0',
                      'id_factura' => '0',
                      'numero' => '0',
                      'conteo' => '0',
                      'sistema' => '0',
                    );
                    $insert_mov =_insert($table,$form_data);
                    $id_movimiento=_insert_id();
                    if($insert_mov){
                        foreach ($array_cargas as $array_cargas1) {
                            $table1= "".EXTERNAL.".movimiento_producto_detalle";
                            $form_data1 = array(
                                'id_movimiento'=>$id_movimiento,
                                'id_producto' => $array_cargas1['id_producto'],
                                'cantidad' => $array_cargas1['cantidad'],
                                'costo' => $array_cargas1['costo'],
                                'precio' => $array_cargas1['precio'],
                                'stock_anterior'=>$array_cargas1['stock_anterior'],
                                'stock_actual'=>$array_cargas1['stock_actual'],
                                'id_presentacion' => $array_cargas1['id_presentacion'],
                                'id_server' => '0',
                                'id_sucursal' => $id_sucursal,
                                'id_server_prod' => '0',
                                'id_server_presen' => '0',
                                'proceso' => '',
                                'referencia' => '0',
                                'lote' => '0',
                                'fecha' => date("Y:m:d"),
                                'hora' => date("H:i:s"),
                            );
                            $insert_mov_det = _insert($table1,$form_data1);
                            if($insert_mov_det){

                            }
                            else{
                                $error2++;
                            }
                        }
                    }
                    else{
                      $error2++;
                    }
                }
                else{
                  $error2++;
                }
            }
            if(!empty($array_descargas)){
                $descarga_de_inventario = 1;
                $sql_num = _query("SELECT ti FROM ".EXTERNAL.".correlativo WHERE id_sucursal='$id_sucursal'");
                $datos_num = _fetch_array($sql_num);
                $ult = $datos_num["ti"]+1;
                $numero_doc=$ult.'_TI';
                $tipo_entrada_salida='Descarga de productos de la repcion con el id:'.$id_recepcion;
                /*actualizar los correlativos de AI*/
                $corr=1;
                $up=1;
                $table="".EXTERNAL.".correlativo";
                $form_data = array(
                  'ti' =>$ult
                );
                $where_clause_c="id_sucursal='".$id_sucursal."'";
                $up_corr=_update($table,$form_data,$where_clause_c);
                if($up_corr){
                    $table="".EXTERNAL.".movimiento_producto";
                    $form_data = array(
                      'id_sucursal' => $id_sucursal,
                      'correlativo' => $numero_doc,
                      'concepto' => "DESCARGA DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                      'total' => $precio_descargas,
                      'tipo' => 'ENTRADA',
                      'proceso' => 'ti',
                      'referencia' => $numero_doc,
                      'id_empleado' => $id_empleado,
                      'fecha' => $dia1,
                      'hora' => $hora1,
                      'id_suc_origen' => $id_sucursal,
                      'id_suc_destino' => $id_sucursal,
                      'id_proveedor' => 0,
                      'id_server' => '0',
                      'id_compra' => '0',
                      'id_traslado' => '0',
                      'id_factura' => '0',
                      'numero' => '0',
                      'conteo' => '0',
                      'sistema' => '0',
                    );
                    $insert_mov =_insert($table,$form_data);
                    $id_movimiento=_insert_id();
                    if($insert_mov){
                        $id_descarga_movimiento = $id_movimiento;
                        foreach ($array_descargas as $array_cargas1) {
                            $table1= "".EXTERNAL.".movimiento_producto_detalle";
                            $form_data1 = array(
                                'id_movimiento'=>$id_movimiento,
                                'id_producto' => $array_cargas1['id_producto'],
                                'cantidad' => $array_cargas1['cantidad'],
                                'costo' => $array_cargas1['costo'],
                                'precio' => $array_cargas1['precio'],
                                'stock_anterior'=>$array_cargas1['stock_anterior'],
                                'stock_actual'=>$array_cargas1['stock_actual'],
                                'id_presentacion' => $array_cargas1['id_presentacion'],
                                'id_server' => '0',
                                'id_sucursal' => $id_sucursal,
                                'id_server_prod' => '0',
                                'id_server_presen' => '0',
                                'proceso' => '',
                                'referencia' => '0',
                                'lote' => '0',
                                'fecha' => date("Y:m:d"),
                                'hora' => date("H:i:s"),
                            );
                            $insert_mov_det = _insert($table1,$form_data1);
                            if($insert_mov_det){

                            }
                            else{
                                $error2++;
                            }
                        }
                    }
                    else{
                        $error2++;
                    }
                }
                else{
                    $error2++;
                }
            }
        }
        else{
            $error2++;
        }
        if($error2 == 0){
            $algun_producto=0;
            $error3=0;
            $array_base = array();
            $sqlx = "SELECT ".EXTERNAL.".".$tabla_buscar.".id_insumo, ".EXTERNAL.".".$tabla_buscar.".examen,".EXTERNAL.".".$tabla_buscar.".id_examen, ".EXTERNAL.".".$tabla_buscar.".total, ".EXTERNAL.".".$tabla_buscar.".id_producto, ".EXTERNAL.".".$tabla_buscar.".id_servicio, ".EXTERNAL.".".$tabla_buscar.".producto, ".EXTERNAL.".".$tabla_buscar.".servicio, ".EXTERNAL.".".$tabla_buscar.".id_presentacion, ".EXTERNAL.".presentacion_producto.costo FROM ".EXTERNAL.".".$tabla_buscar." LEFT JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".".$tabla_buscar.".id_producto LEFT JOIN ".EXTERNAL.".presentacion_producto on ".EXTERNAL.".presentacion_producto.id_presentacion = ".EXTERNAL.".".$tabla_buscar.".id_presentacion WHERE ".EXTERNAL.".".$tabla_buscar.".id_recepcion = $id_recepcion AND ".EXTERNAL.".".$tabla_buscar.".deleted is NULL AND ".EXTERNAL.".".$tabla_buscar.".created_at != '$fecha_hora_ingresar'";
            $consultax = _query($sqlx);
            while($rowx = _fetch_array($consultax)){
                $tipop = "";
                $producto = $rowx['producto'];
                $servicio = $rowx['servicio'];
                $examen = $rowx['examen'];
                $id_producto=0;
                if($producto == 1){
                  $tipop = "P";
                  $id_producto = $rowx['id_producto'];
                }
                if($servicio == 1){
                  $tipop = "S";
                  $id_producto = $rowx['id_servicio'];
                }
                if($examen == 1){
                    $tipop = "EXAMEN_AGREGADO";
                    $id_producto = $rowx['id_examen'];
                }
                $id_insumo = $rowx['id_insumo'];
                $array_base[] = array(
                    'id_insumo' =>$id_insumo,
                    'id_producto' => $id_producto,
                    'tipo' => $tipop,
                    'precio' => $rowx['total'],
                    'id_presentacion' => $rowx['id_presentacion'],
                    'costo' => $rowx['costo']
                );
            }
            $count_arreglo0=0;
            $existe=0;
            foreach ($array_base as $key => $value) {
                $id_insumoX = $value['id_insumo'];
                $id_productoX = $value['id_producto'];
                $tipoX = $value['tipo'];
                if(!is_numeric($id_insumo)){
                    unset($array_base[$count_arreglo0]);
                }
                else{
                    $coun_arreglo = 0;
                    foreach ($array_tabla as $key1 => $value1) {
                        $id_insumoTB = $value1['id_insumo'];
                        $id_productoTB = $value1['id_producto'];
                        $tipoTB = $value1['tipo'];
                        if($id_insumoX == $id_insumoTB){
                            unset($array_base[$count_arreglo0]);
                        }
                        $coun_arreglo++;
                    }
                }
                $count_arreglo0++;
            }

            foreach ($array_base as $key => $value) {
                $tipoX = $value['tipo'];
                if($tipoX == "P"){
                    $algun_producto++;
                }
            }
            if(!empty($array_base)){
                if($algun_producto == 0){
                    foreach ($array_base as $key1 => $value1){
                        $tabla_deleted="".EXTERNAL.".".$tabla_buscar."";
                        $where = " WHERE id_insumo = ".$value1['id_insumo']." AND examen = 0";
                        $eliminar_insumo = _soft_delete($tabla_deleted,$where);
                        if($eliminar_insumo){
                          $xdatos['typeinfo']='Success';
                          $xdatos['msg']='Operacion realizada con Exito !';
                        }
                        else{
                          $error3++;
                        }
                    }
                }
                else{
                    $precio_total_eliminado = 0;
                    foreach ($array_base as $key => $value1){
                        if($value1['tipo'] == "P"){
                          $precio_total_eliminado+= $value1['precio'];
                        }
                    }
                    $sql_num = _query("SELECT ti FROM ".EXTERNAL.".correlativo WHERE id_sucursal='$id_sucursal'");
                    $datos_num = _fetch_array($sql_num);
                    $ult = $datos_num["ti"]+1;
                    $numero_doc=$ult.'_TI';
                    /*actualizar los correlativos de AI*/
                    $tableC="".EXTERNAL.".correlativo";
                    $form_dataC = array(
                        'ti' =>$ult
                    );
                    $where_clause_cC="id_sucursal='".$id_sucursal."'";
                    $up_corrC=_update($tableC,$form_dataC,$where_clause_cC);
                    if($up_corrC){
                        $table="".EXTERNAL.".movimiento_producto";
                        $form_data = array(
                            'id_sucursal' => $id_sucursal,
                            'correlativo' => $numero_doc,
                            'concepto' => "ELIMINACION DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                            'total' => $precio_total_eliminado,
                            'tipo' => 'ENTRADA',
                            'proceso' => 'ti',
                            'referencia' => $numero_doc,
                                'id_empleado' => $id_empleado,
                                'fecha' => $dia1,
                                'hora' => $hora1,
                                'id_suc_origen' => $id_sucursal,
                                'id_suc_destino' => $id_sucursal,
                                'id_proveedor' => 0,
                                'id_server'=> '0',
                                'id_compra' => '0',
                                'id_traslado' => '0',
                                'id_factura' => '0',
                                'numero' => '0',
                                'conteo' => '0',
                                'sistema' => '0',
                        );
                        $insert_mov =_insert($table,$form_data);
                        $id_movimientox=_insert_id();
                        if($insert_mov){
                          foreach ($array_base as $key => $value) {
                              $hora1=date("H:i:s");
                              $dia1 =date('Y-m-d');
                              if($value['tipo'] == "P"){
                                  $id_producto_eliminado = $value['id_producto'];
                                  $sqlD = "SELECT ".EXTERNAL.".".$tabla_buscar.".cantidad FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_insumo =".$value['id_insumo'];
                                  $consultaD = _query($sqlD);
                                  $registroD = _fetch_array($consultaD);
                                  $cantidad = $registroD['cantidad'];
                                  $sql_consulta_sg = "SELECT ".EXTERNAL.".stock.id_stock, ".EXTERNAL.".stock.stock FROM ".EXTERNAL.".stock WHERE ".EXTERNAL.".stock.id_producto = $id_producto_eliminado AND ".EXTERNAL.".stock.id_sucursal = 1";
                                  $consulta_sql_sg = _query($sql_consulta_sg);
                                  $registros_sql_sql = _fetch_array($consulta_sql_sg);
                                  $id_stock_original = $registros_sql_sql['id_stock'];
                                  $cantidad_stock_original = $registros_sql_sql['stock'];
                                  $sql_consulta_su = "SELECT ".EXTERNAL.".stock_ubicacion.id_su, ".EXTERNAL.".stock_ubicacion.cantidad FROM ".EXTERNAL.".stock_ubicacion WHERE ".EXTERNAL.".stock_ubicacion.id_producto = $id_producto_eliminado AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = '$id_usb' AND ".EXTERNAL.".stock_ubicacion.id_sucursal = 1";
                                  $consulta_sql_su = _query($sql_consulta_su);
                                  $registros_sql_su = _fetch_array($consulta_sql_su);
                                  $id_ubicacion = $registros_sql_su['id_su'];
                                  $cantidad_stock_ubicacion = $registros_sql_su['cantidad'];
                                  $cantidad_nueva_so = $cantidad_stock_original+$cantidad;
                                  $cantidad_nueva_su = $cantidad_stock_ubicacion+$cantidad;
                                  $tabla3x = "".EXTERNAL.".stock";
                                  $fd3x= array(
                                      'stock' => $cantidad_nueva_so
                                  );
                                  $where3x = " WHERE id_stock = '$id_stock_original'";
                                  $ins3x = _update($tabla3x,$fd3x,$where3x);
                                  if($ins3x){
                                      $tabla4x = "".EXTERNAL.".stock_ubicacion";
                                      $fd4x= array(
                                          'cantidad' => $cantidad_nueva_su
                                      );
                                      $where4x = " WHERE id_su = '$id_ubicacion'";
                                      $ins4x = _update($tabla4x,$fd4x,$where4x);
                                      if($ins4x){
                                          $table1x= "".EXTERNAL.".movimiento_producto_detalle";
                                          $form_data1x = array(
                                              'id_movimiento'=>$id_movimientox,
                                              'id_producto' => $value['id_producto'],
                                              'cantidad' => $cantidad,
                                              'costo' => $value['costo'],
                                              'precio' => $value['precio'],
                                              'stock_anterior'=>$cantidad_stock_original,
                                              'stock_actual'=>$cantidad_nueva_so,
                                              'id_presentacion' => $value['id_presentacion'],
                                              'id_server' => '0',
                                              'id_sucursal' => $id_sucursal,
                                              'id_server_prod' => '0',
                                              'id_server_presen' => '0',
                                              'proceso' => '',
                                              'referencia' => '0',
                                              'lote' => '0',
                                              'fecha' => date("Y:m:d"),
                                              'hora' => date("H:i:s"),
                                          );
                                          $insert_mov_detx = _insert($table1x,$form_data1x);
                                          if($insert_mov_detx){
                                              $tabla_deleted="".EXTERNAL.".".$tabla_buscar."";
                                              $eliminar_insumo = _soft_delete($tabla_deleted," WHERE id_insumo = ".$value['id_insumo']." AND examen = 0");
                                              if($eliminar_insumo){

                                              }
                                              else{
                                                $error3++;
                                              }
                                          }
                                          else{
                                              $error3++;
                                          }
                                      }
                                      else{
                                          $error3++;
                                      }
                                  }
                                  else{
                                      $error3++;
                                  }
                              }
                              if($value['tipo'] == "S"){
                                    $tabla_deleted="".EXTERNAL.".".$tabla_buscar."";
                                    $eliminar_insumo = _soft_delete($tabla_deleted," WHERE id_insumo = ".$value['id_insumo']." AND examen = 0");
                                    if($eliminar_insumo){

                                    }
                                    else{
                                        $error3++;
                                    }
                                }
                            }
                        }
                        else{
                            $error3++;
                        }
                    }
                    else{
                        $error3++;
                    }
                }
            }
            if($error3 == 0){
                $sql_change = "SELECT recepcion_hospitalizacion, recepcion_emergencia, recepcion_pediatria, recepcion_estacion_enfermeria_a, recepcion_estacion_enfermeria_b, recepcion_enfermeria_sala_estar, recepcion_nefrologia, recepcion_rayosx, recepcion_uci, recepcion_terapia_respiratoria, recepcion_laboratorio, recepcion_sala_operaciones, recepcion_microcirugia FROM recepcion WHERE id_recepcion = '$id_recepcion'";
                $query_change = _query($sql_change);
                $row_change = _fetch_array($query_change);
                $tabla_insert = 'recepcion';
                if($row_change['recepcion_emergencia'] == '1'){
                    $form_data = array(
                        'recepcion_emergencia' => 2,
                        'observaciones_emergencia' => $observaciones
                    );
                }
                if($row_change['recepcion_pediatria'] == '1'){
                    $form_data = array(
                        'recepcion_pediatria' => 2,
                        'observaciones_pediatria' => $observaciones
                    );
                }
                if($row_change['recepcion_estacion_enfermeria_a'] == '1'){
                    $form_data = array(
                        'recepcion_estacion_enfermeria_a' => 2,
                        'observaciones_estacion_enfermeria_a' => $observaciones
                    );
                }
                if($row_change['recepcion_estacion_enfermeria_b'] == '1'){
                    $form_data = array(
                        'recepcion_estacion_enfermeria_b' => 2,
                        'observaciones_estacion_enfermeria_b' => $observaciones
                    );
                }
                if($row_change['recepcion_enfermeria_sala_estar'] == '1'){
                    $form_data = array(
                        'recepcion_enfermeria_sala_estar' => 2,
                        'observaciones_enfermeria_sala_estar' => $observaciones
                    );
                }
                if($row_change['recepcion_nefrologia'] == '1'){
                    $form_data = array(
                        'recepcion_nefrologia' => 2,
                        'observaciones_nefrologia' => $observaciones
                    );
                }
                if($row_change['recepcion_rayosx'] == '1'){
                    $form_data = array(
                        'recepcion_rayosx' => 2,
                        'observaciones_rayosx' => $observaciones
                    );
                }
                if($row_change['recepcion_uci'] == '1'){
                    $form_data = array(
                        'recepcion_uci' => 2,
                        'observaciones_uci' => $observaciones
                    );
                }
                if($row_change['recepcion_terapia_respiratoria'] == '1'){
                    $form_data = array(
                        'recepcion_terapia_respiratoria' => 2,
                        'observaciones_terapia_respiratoria' => $observaciones
                    );
                }
                if($row_change['recepcion_laboratorio'] == '1'){
                    $form_data = array(
                        'recepcion_laboratorio' => 2,
                        'observaciones_laboratorio' => $observaciones
                    );
                }
                if($row_change['recepcion_sala_operaciones'] == '1'){
                    $form_data = array(
                        'recepcion_sala_operaciones' => 2,
                        'observaciones_sala_operaciones' => $observaciones
                    );
                }
                if($row_change['recepcion_microcirugia'] == '1'){
                    $form_data = array(
                        'recepcion_microcirugia' => 2,
                        'observaciones_microcirugia' => $observaciones
                    );
                }
                if($row_change['recepcion_hospitalizacion'] == '1'){
                    $form_data = array(
                        'recepcion_hospitalizacion' => 2,
                        'observaciones_hospitalizacion' => $observaciones
                    );
                }
                $where_insert = " id_recepcion = '$id_recepcion'";
                $update_insert = _update($tabla_insert, $form_data, $where_insert);
                if($update_insert){
                    if(!empty($array_examen)){
                        $table1="labangel.examen_paciente_interno";
                        foreach ($array_examen as $key => $value1) {
                            $form_data11=array(
                                'id_examen' => $value1['id_examen'],
                                'id_doctor'=>"0",
                                'id_paciente'=>$id_paciente,
                                'fecha_cobro'=>date("Y:m:d"),
                                'hora_cobro'=>date("h:i:s"),
                                'fecha_examen'=>date("Y:m:d"),
                                'hora_examen'=>date("h:i:s"),
                                'estado_realizado'=>"Pendiente",
                                'id_sucursal'=>$id_sucursal,
                                'id_recepcion'=>$id_recepcion   ,
                                'correlativo_m'=>"",
                                'procedencia'=>$id_usb,
                                'correlativo_a'=>"",
                                'id_empleado' =>$id_empleado,
                                'examen_paciente_nulo' => 0
                            );
                            $insertt1=_insert($table1, $form_data11);
                            if($insertt1){
                                $xdatos['typeinfo']='Success';
                                $xdatos['msg']='Operacion realizada con Exito !';
                                _commit();
                            }
                            else{
                                $xdatos['typeinfo']='Error';
                                $xdatos['msg']='No se pudo realizar la operacion!';
                                _rollback();
                            }
                        }


                    }
                    else{
                        $xdatos['typeinfo']='Success';
                        $xdatos['msg']='Operacion realizada con Exito !';
                        _commit();
                    }
                    $xdatos['typeinfo']='Success';
                    $xdatos['msg']='Operacion realizada con Exito !';
                    _commit();
                }
                else{
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='No se pudo realizar la operacion!';
                    _rollback();
                }

            }
            else{
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='No se pudo realizar la operacion!';
                _rollback();
            }
        }
        else{
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='No se pudo realizar la operacion!';
            _rollback();
        }
        echo json_encode($xdatos);
    }


?>

<?php
    function anular_datos() {
        $id_usb = $_POST['id_usb'];
        $tabla_buscar = $_POST['tabla_buscar'];
        $error_final = 0;
        $id_recepcion = $_POST ['idRecepcion'];
        $id_sucursal = $_SESSION['id_sucursal'];
        $id_empleado = $_SESSION['id_empleado'];
        $total_salido = 0;
        //anulara en 	$table1 = 'microcirugia_paciente';
        $array_salida = array();
        $bueno = 1;
        $sql_productos = "SELECT * FROM ".EXTERNAL.".".$tabla_buscar." WHERE ".EXTERNAL.".".$tabla_buscar.".id_recepcion = '$id_recepcion'";
        $query_productos = _query($sql_productos);
        while($row_productos = _fetch_array($query_productos)){
            $es_producto = $row_productos['producto'];
            $es_servicio = $row_productos['servicio'];
            if($es_producto == "1"){
                $id_producto = $row_productos['id_producto'];
                $id_presentacion = $row_productos['id_presentacion'];
                $cantidad = $row_productos['cantidad'];

                $sql_pcp = "SELECT ".EXTERNAL.".presentacion_producto.precio, ".EXTERNAL.".presentacion_producto.costo, ".EXTERNAL.".presentacion_producto.unidad FROM ".EXTERNAL.".presentacion_producto WHERE ".EXTERNAL.".presentacion_producto.id_presentacion = '$id_presentacion'";
                $query_pcp = _query($sql_pcp);
                $row_pcp = _fetch_array($query_pcp);
                $precio = $row_pcp['precio'];
                $costo = $row_pcp['costo'];
                $unidad = $row_pcp['unidad'];
                $sql_stock = "SELECT ".EXTERNAL.".stock.stock FROM ".EXTERNAL.".stock WHERE ".EXTERNAL.".stock.id_producto = '$id_producto' AND ".EXTERNAL.".stock.id_sucursal = '$id_sucursal'";
                $query_stock = _query($sql_stock);
                $row_stock = _fetch_array($query_stock);
                $stock = $row_stock['stock'];
                $sql_stock_ubicacion = "SELECT ".EXTERNAL.".stock_ubicacion.cantidad FROM ".EXTERNAL.".stock_ubicacion WHERE ".EXTERNAL.".stock_ubicacion.id_producto = '$id_producto' AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = '$id_usb' AND ".EXTERNAL.".stock_ubicacion.id_sucursal = '$id_sucursal'";
                $query_stock_ubicacion = _query($sql_stock_ubicacion);
                $row_stock_ubicacion = _fetch_array($query_stock_ubicacion);
                $cantidad_stock_ubicacion = $row_stock_ubicacion['cantidad'];
                $cantidad_nueva_stock = $stock + ($cantidad * $unidad);
                $cantidad_nueva_stock_ubicacion = $cantidad_stock_ubicacion + ($cantidad * $unidad);
                $form_data_stock = array(
                    'stock' => $cantidad_nueva_stock
                );
                $form_data_stock_ubicacion = array(
                    'cantidad' => $cantidad_nueva_stock_ubicacion
                );
                $actualizar_stock = _update('".EXTERNAL.".stock', $form_data_stock, " WHERE ".EXTERNAL.".stock.id_producto = '$id_producto' AND ".EXTERNAL.".stock.id_sucursal = '$id_sucursal'");
                $actualizar_stock_ubicacion = _update('".EXTERNAL.".stock_ubicacion', $form_data_stock_ubicacion, " WHERE ".EXTERNAL.".stock_ubicacion.id_producto = '$id_producto' AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = '$id_usb' AND ".EXTERNAL.".stock_ubicacion.id_sucursal = '$id_sucursal'");
                if(!$actualizar_stock || !$actualizar_stock_ubicacion){
                    $bueno = 0;
                }
                $array_salida[] = array(
                    'id_producto' => $id_producto,
                    'id_presentacion' => $id_presentacion,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'costo' => $costo,
                    'stock_anterior' => $stock,
                    'stock_actual' => $cantidad_nueva_stock
                );
                $total_salido += $row_productos['total'];
            }
            if($es_servicio == "1"){
                $eliminar = _delete_total('".EXTERNAL.".".$tabla_buscar."', 'WHERE id_insumo ='.$row_productos['id_insumo']);
                if(!$eliminar){
                    $bueno = 0;
                }
            }
        }
        if($bueno == 1){
            $sql_num = _query("SELECT ti FROM ".EXTERNAL.".stock_ubicacion WHERE id_sucursal='$id_sucursal'");
            $datos_num = _fetch_array($sql_num);
            $ult = $datos_num["ti"]+1;
            $numero_doc=$ult.'_TI';
            $corr=1;
            $up=1;
            $table="".EXTERNAL.".stock_ubicacion";
            $form_data = array(
            'ti' =>$ult
            );
            $where_clause_c="id_sucursal='".$id_sucursal."'";
            $up_corr=_update($table,$form_data,$where_clause_c);
            if($up_corr){
                $hora1=date("H:i:s");
                $dia1 =date('Y-m-d');
                $table="".EXTERNAL.".movimiento_producto";
                $form_dataxxxxx = array(
                    'id_sucursal' => $id_sucursal,
                    'correlativo' => $numero_doc,
                    'concepto' => "DESCARGA DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion ",
                    'total' => $total_salido,
                    'tipo' => 'ENTRADA',
                    'proceso' => 'ti',
                    'referencia' => $numero_doc,
                    'id_empleado' => $id_empleado,
                    'fecha' => $dia1,
                    'hora' => $hora1,
                    'id_suc_origen' => $id_sucursal,
                    'id_suc_destino' => $id_sucursal,
                    'id_proveedor' => 0,
                    'id_server' => '0',
                    'id_compra' => '0',
                    'id_traslado' => '0',
                    'id_factura' => '0',
                    'numero' => '0',
                    'conteo' => '0',
                    'sistema' => '0',
                );
                $insert_mov =_insert($table,$form_dataxxxxx);
                $id_movimiento=_insert_id();
                if($insert_mov){
                    foreach ($array_salida as $key => $value) {
                        $id_producto = $value['id_producto'];
                        $id_presentacion = $value['id_presentacion'];
                        $cantidad = $value['cantidad'];
                        $precio = $value['precio'];
                        $costo = $value['costo'];
                        $stock_anterior = $value['stock_anterior'];
                        $stock_actual = $value['stock_actual'];
                        $table1= "".EXTERNAL.".movimiento_producto_detalle";
                        $form_data1 = array(
                            'id_movimiento'=>$id_movimiento,
                            'id_producto' => $id_producto,
                            'cantidad' => $cantidad,
                            'costo' => $costo,
                            'precio' => $precio,
                            'stock_anterior'=>$stock_anterior,
                            'stock_actual'=>$stock_actual,
                            'id_presentacion' => $id_presentacion,
                            'id_server' => '0',
                            'id_sucursal' => $id_sucursal,
                            'id_server_prod' => '0',
                            'id_server_presen' => '0',
                            'proceso' => '',
                            'referencia' => '0',
                            'lote' => '0',
                            'fecha' => date("Y:m:d"),
                            'hora' => date("H:i:s"),
                        );
                        $insert_mov_det = _insert($table1,$form_data1);
                        if(!$insert_mov_det){
                            $error_final++;
                        }
                    }
                }
                else{
                    $error_final++;
                }
            }
            else{
                $error_final++;
            }
        }
        else{
            $error_final++;
        }
        if($error_final == 0){
            $table_d2 = "".EXTERNAL.".".$tabla_buscar."";
            $delete2 = _delete_total($table_d2, "where id_recepcion = '$id_recepcion'");
            if($delete2){
                $tabla1 = 'recepcion';
                $wc1 = "id_recepcion = $id_recepcion";
                $d1 = array(
                    'id_estado_recepcion' => 5
                );
                $delete = _update($tabla1,$d1,$wc1);
                if($delete){
                    $tbl2 = 'recepcion';
                    $xdatos ['typeinfo'] = 'Success';
                    $xdatos ['msg'] = 'Operacion Realizada con Exito!';

                    _commit();
                }
                else{
                    $xdatos ['typeinfo'] = 'Error';
                    $xdatos ['msg'] = 'No se pudo realizar la operacion! ';
                }
            }
            else{
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='No se pudo realizar la operacion!';
                _rollback();
            }
        }
        else{
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='No se pudo realizar la operacion!';
            _rollback();
        }
        echo json_encode ( $xdatos );
    }


?>
<?php
if (!isset($_REQUEST['process']))
{
  initial();
}
if (isset($_REQUEST['process']))
{
  switch ($_REQUEST['process'])
  {
    case 'agregar_insumos':
      agregar_insumos();
      break;
    case 'consultar_stock':
      consultar_stock();
    break;
    case 'traer_insumos':
      traer_insumos();
    break;
    case 'insert':
      insert();
    break;
    case 'total_texto':
        total_texto();
    break;
    case 'get_presentancion':
      get_presentacion();
    break;
    case 'consultar_existencias':
      consultar_existencias();
    break;
    case 'consultar_selects':
      consultar_selects();
      break;
    case 'finalizar':
        finalizar();
    break;
    case 'anular_datos':
        anular_datos();
        break;
  }
}
?>
