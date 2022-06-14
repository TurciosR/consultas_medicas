<?php
    include("_core.php");
    include('num2letras.php');
?>
<?php
    function initial(){
        $title="Insumos Utilizados por Emergencia";
        $_PAGE = array();
        $_PAGE ['title'] = $title;
        $_PAGE ['links'] = null;
        $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/bootstrap-checkbox/bootstrap-checkbox.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';
        $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/plugins/perfect-scrollbar/perfect-scrollbar.css">';
        $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/util.css">';
        $_PAGE ['links'] .= '<link rel="stylesheet" type="text/css" href="css/main.css">';
        $_PAGE ['links'] .= '<link href="css/plugins/timepicki/timepicki.css" rel="stylesheet">';

        include_once "header.php";
        include_once "main_menu.php";
        $hoy=date('d-m-Y');

        //permiso del script
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"];
        $uri = $_SERVER['SCRIPT_NAME'];
        $filename=get_name_script($uri);
        $links=permission_usr($id_user,$filename);
        $idRecepcion= $_REQUEST['idRecepcion'];
        $sql = "SELECT paciente.id_paciente,paciente.nombres as 'nombre_paciente', paciente.apellidos as 'apellido_paciente',  CONCAT(doctor.nombres,' ',doctor.apellidos) as 'nombreDoctor',  recepcion.evento FROM paciente INNER JOIN recepcion on paciente.id_paciente = recepcion.id_paciente_recepcion LEFT JOIN doctor on doctor.id_doctor = recepcion.id_doctor_recepcion  WHERE recepcion.deleted is NULL AND recepcion.id_recepcion = $idRecepcion";
        $consulta = _query($sql);
        $resultado = _fetch_array($consulta);
        $hora_hoy = _hora_media_decode(date("H:i:s"));
        ?>
         <style  type="text/css">
                .datepicker table tr td, .datepicker table tr th{
                    border:none;
                    background:white;
                }
            </style>
            <div class="gray-bg">
                <div class="wrapper wrapper-content  animated fadeInRight">
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="ibox ">
                              <?php
                              //permiso del script
                                  if ($links!='NOT' || $admin=='1') {
                              ?>
                              <div class="ibox-content">
                                  <div class="row focuss"><br>
                                      <div  class="form-group col-md-6">
                                          <label>Paciente:</label>
                                          <input type="text" id="paciente" name="paciente" value='<?php echo $resultado['nombre_paciente']." ".$resultado['apellido_paciente']; ?>'  class="form-control usage" hidden readonly autocomplete="off">
                                      </div>
                                      <div  class="form-group col-md-6">
                                          <label>Doctor:</label>
                                          <input type="text" id="doctor" name="doctor"  class="form-control usage" value='<?php echo $resultado['nombreDoctor']; ?>' hidden readonly autocomplete="off">
                                      </div>
                                      <div  class="form-group col-md-12">
                                          <label>Evento:</label>
                                          <textarea  class="form-control usage" hidden readonly autocomplete="off" name="evento" id="evento" cols="30" rows="2"><?php echo $resultado['evento']; ?></textarea>
                                      </div>

                                      <div class="form-group col-md-4">
                                          <label id='buscar_habilitado'>Buscar Insumos (Descripci&oacute;n)</label>
                                          <input type="text" id="producto_buscar" name="producto_buscar"  class="form-control usage" placeholder="Ingrese Descripcion de producto" data-provide="typeahead" style="border-radius:0px">
                                          <input type="text" id="servicio_buscar" name="servicio_buscar" class="form-control" placeholder="Ingrese  Descripcion de  servicio " data-provide="typeahead" style="border-radius:0px">
                                          <input type="text" id="examen_buscar" name="examen_buscar" class="form-control" placeholder="Ingrese  Descripcion de examen" data-provide="typeahead" style="border-radius:0px">

                                      </div>
                                      <div  class="form-group col-md-4">
                                          <label>Fecha de aplicacion</label>
                                          <input type="text" name="hasta" id="fechaEntrada" class="form-control datepicker" value="<?php echo $hoy?>">
                                      </div>
                                      <div  class="form-group col-md-4">
                                          <label> Hora de inicio</label>
                                          <input type="text" placeholder="HH:mm" class="form-control" id="hora_entrada" name="hora_entrada" autocomplete="off" value="<?php echo $hora_hoy; ?>">
                                      </div>
                                  </div>
                                  <div class="row">

                                  </div>
                                    <div class="row focuss">
                                        <div class="form-group col-md-6" style="margin-top:23px;text-align: center;">
                                            <button type="button" id="btnBuscaProd" name="btnBuscaProd" class="btn btn-primary usage"><i class="fa fa-barcode"></i> Productos</button>
                                            <button type="button" id="btnBuscaServ" name="btnBuscaServ" class="btn btn-primary usage"><i class="fa fa-eye"></i> Servicios</button>
                                            <button type="button" id="btnBuscarExam" name="btnBuscarExam" class="btn btn-primary usage"><i class="fa fa-clipboard"></i> Examenes</button>
                                        </div>
                                        <div class="title-action col-md-6" id='botones' style="margin-top:-10px;text-align: center;">

                                          <a class="btn btn-danger " style="margin-left:3%;" href="admin_emergencia.php" id='salir'><i class="fa fa-mail-reply"></i> F4 Salir</a>
                                          <button type="button" id="submit1" name="submit1" class="btn btn-primary usage"><i class="fa fa-check"></i> F2 Guardar</button>
                                        </div>
                                  </div>
                                  <br>
                      <div class="row">
                        <div class="col-md-12">
                          <section>
                            <input type='hidden' name='porc_iva' id='porc_iva' value='<?php echo $iva; ?>'>
                            <input type='hidden' name='monto_retencion1' id='monto_retencion1' value='<?php echo $monto_retencion1 ?>'>
                            <input type='hidden' name='monto_retencion10' id='monto_retencion10' value='<?php echo $monto_retencion10 ?>'>
                            <input type='hidden' name='monto_percepcion' id='monto_percepcion' value='100'>
                            <input type='hidden' name='porc_retencion1' id='porc_retencion1' value=0>
                            <input type='hidden' name='porc_retencion10' id='porc_retencion10' value=0>
                            <input type='hidden' name='porc_percepcion' id='porc_percepcion' value=0>
                            <input type='hidden' name='porcentaje_descuento' id='porcentaje_descuento' value=0>
                            <div class="">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="wrap-table1001">
                                    <div class="table100 ver1 m-b-10">
                                      <div class="table100-head">
                                        <table id="inventable1">
                                          <thead>
                                            <tr class="row100 head">
                                              <th class="success cell100 column10">ID</th>
                                              <th class='success  cell100 column30'>DESCRIPCI&Oacute;N</th>
                                              <th class='success  cell100 column10'>STOCK</th>
                                              <th class='success  cell100 column15'>PRESENTACI&Oacute;N</th>
                                              <th class='success  cell100 column10'>PRECIO</th>
                                              <th class='success  cell100 column5'>CANT</th>
                                              <th class='success  cell100 column10'>SUBTOT</th>
                                              <th class='success  cell100 column10'>ACCI&Oacute;N</th>
                                            </tr>
                                          </thead>
                                        </table>
                                      </div>
                                      <div class="table100-body js-pscroll">
                                        <table>
                                          <tbody id="inventable"></tbody>
                                        </table>
                                      </div>
                                      <div class="table101-body">
                                        <table>
                                          <tbody>
                                            <tr>
                                              <td class='cell100 column15 leftt  text-bluegrey ' >&nbsp;</td>
                                              <td class='cell100 column15 leftt  text-bluegrey ' >N° INSUMOS:</td>
                                              <td class='cell100 column10 text-right text-danger' id='totcant'>0.00</td>
                                              <td class='cell100 column15 leftt  text-bluegrey ' >SUBTOTAL $:</td>
                                              <td class='cell100 column10 text-right text-danger' id='subtotal'>0.00</td>
                                              <td class="cell100 column15  leftt text-bluegrey ">TOTAL $:</td>
                                              <td class='cell100 column10 text-right text-green' id='total_gravado'>0.00</td>
                                              <td class='cell100 column10 leftt  text-bluegrey ' >&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td class='cell100 column50 text-bluegrey'  id='totaltexto'>&nbsp;</td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!--valores ocultos para referencia -->
                              <input type='hidden' name='id_empleado' id='id_empleado' >
                              <input type='hidden' name='numero_doc' id='numero_doc' >
                              <input type='hidden' name='id_factura' id='id_factura' >
                              <input type='hidden' name='urlprocess' id='urlprocess' value="<?php echo $filename; ?>">
                              <input type='hidden' name='totalfactura' id='totalfactura' value='0'>
                              <input type="hidden" id="fecha" value="<?php echo $fecha_actual; ?>">
                              <input type="hidden" id="id_microcirugia" value="-1">
                              <input type="hidden" id="id_recepcion"  value="<?php echo $idRecepcion;?>">
                              <input type="hidden" id="id_paciente" value="-1">
                              <input type="hidden" id='items' value="0">
                              <input type='hidden' name='id_apertura' id='id_apertura' value='<?php echo $id_apertura; ?>'>
                              <input type='hidden' name='turno' id='turno' value='<?php echo $turno; ?>'>
                              <input type='hidden' name='tip_impre' id='tip_impre' value='<?php echo $tipo_fa; ?>'>
                              <input type='hidden' name='caja' id='caja' value='<?php echo $caja; ?>'>
                            </div>
                            <!--div class="table-responsive m-t"-->
                          </section>
                          </div>
                        </div>
                        <!--div class='ibox-content'-->
                        <!-- Modal -->
                        <div class="modal-container">
                          <div class="modal fade" id="clienteModal" tabindex="-2" role="dialog" aria-labelledby="myModalCliente" aria-hidden="true">
                            <div class="modal-dialog model-sm">
                              <div class="modal-content"> </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-container">
                          <div class="modal fade" id="doctorModal" tabindex="-2" role="dialog" aria-labelledby="myModalCliente" aria-hidden="true">
                            <div class="modal-dialog model-sm">
                              <div class="modal-content"> </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-container">
                          <div class="modal fade" id="procedenciaModal" tabindex="-2" role="dialog" aria-labelledby="myModalCliente" aria-hidden="true">
                            <div class="modal-dialog model-sm">
                              <div class="modal-content"> </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-container">
                          <div class="modal fade" id="cliente1Modal" tabindex="-2" role="dialog" aria-labelledby="myModalCliente" aria-hidden="true">
                            <div class="modal-dialog model-sm">
                              <div class="modal-content"> </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!--<div class='ibox float-e-margins' -->
                    </div>
                      </div>
                    <!--div class='col-lg-12'-->
                    <!--div class='row'-->
                    <!--div class='wrapper wrapper-content  animated fadeInRight'-->

                    <?php

                  include_once("footer.php");

                  echo "<script src='js/funciones/admin_emergencia.js'></script>";
                  echo "<script src='js/plugins/arrowtable/arrow-table.js'></script>";
                  echo "<script src='js/plugins/bootstrap-checkbox/bootstrap-checkbox.js'></script>";
                  echo "<script src='js/plugins/datetime/bootstrap-datetimepicker.js'></script>";
                  echo '<script src="js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
                  <script src="js/funciones/main.js"></script>';
                  echo "<script src='js/funciones/util.js'></script>";
                } //permiso del script
                else {
                  echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
                  include_once("footer.php");
                }
      ?>
        <?php
    }
?>

<?php
    function consultar_stock(){
        $id_producto = $_REQUEST['id_producto'];
        $id_presentacion="";
        if (isset($_REQUEST['id_presentacion'])){
            $id_presentacion=$_REQUEST['id_presentacion'];
        }
        $cortesia = "";
        $id_usuario=$_SESSION["id_usuario"];
        $iva=13/100;
        $precio=0;
        $sql1 = "SELECT p.id_producto, p.barcode, p.descripcion, p.id_estado_producto, p.perecedero, p.exento, p.id_categoria, p.id_sucursal,s.id_stock,s.cantidad_stock, s.id_sucursal_stock, s.precio_unitario, s.costo_unitario
        FROM tblProductos AS p, tblStock AS s WHERE p.id_producto = s.id_producto_stock AND p.id_producto ='$id_producto' ";
        $stock1=_query($sql1);
        $row1=_fetch_array($stock1);
        $nrow1=_num_rows($stock1);
        if ($nrow1>0) {
            $perecedero=$row1['perecedero'];
            $barcode = $row1["barcode"];
            $descripcion = $row1["descripcion"];
            $estado = $row1["id_estado_producto"];
            $perecedero = $row1["perecedero"];
            $exento = $row1["exento"];
            $id_stock = $row1["id_stock"];
            $stock = $row1["cantidad_stock"];
            $precio_unitario = $row1["precio_unitario"];
            $costo_unitario = $row1["costo_unitario"];
            //precio de venta
            $fecha_hoy=date("Y-m-d");
            $fecha_hoy2=date("d-m-Y");
            //consultar si es perecedero
            $sql_existencia = "SELECT su.id_stock_ubicacion, su.id_producto, su.cantidad, su.id_ubicacion, u.id_sucursal_ubicacion, u.bodega
            FROM tblStock_Ubicacion as su, tblUbicacion_Stock as u
            WHERE su.id_producto = '$id_producto' AND su.id_ubicacion = u.id_ubicacion AND u.id_ubicacion = 2 ORDER BY su.id_stock_ubicacion ASC";
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
            $sqlCE = "SELECT tblPresentacion.nombre, tblPresentacion_Productos.descripcion,tblPresentacion_Productos.id_presentacion_producto,
            tblPresentacion_Productos.unidad,tblPresentacion_Productos.precio FROM tblPresentacion_Productos JOIN tblPresentacion ON tblPresentacion.id_presentacion=tblPresentacion_Productos.id_presentacion_PP
            INNER JOIN tblProductos on tblProductos.id_producto = tblPresentacion_Productos.id_producto_PP INNER  JOIN tblStock_Ubicacion on tblStock_Ubicacion.id_producto = tblProductos.id_producto
            WHERE tblPresentacion_Productos.id_producto_PP=$id_producto AND tblPresentacion_Productos.activo =1 AND tblProductos.deleted is NULL GROUP BY tblPresentacion_Productos.id_presentacion_producto";
        }
        else{
            $sqlCE = "SELECT tblPresentacion.nombre, tblPresentacion_Productos.descripcion,tblPresentacion_Productos.id_presentacion_producto,
            tblPresentacion_Productos.unidad,tblPresentacion_Productos.precio FROM tblPresentacion_Productos JOIN tblPresentacion ON tblPresentacion.id_presentacion=tblPresentacion_Productos.id_presentacion_PP
            INNER JOIN tblProductos on tblProductos.id_producto = tblPresentacion_Productos.id_producto_PP INNER  JOIN tblStock_Ubicacion on tblStock_Ubicacion.id_producto = tblProductos.id_producto
            WHERE tblPresentacion_Productos.id_producto_PP=$id_producto AND tblPresentacion_Productos.activo =1 AND tblProductos.deleted is NULL and tblPresentacion_Productos.unidad <= $existencia_real GROUP BY tblPresentacion_Productos.id_presentacion_producto";
        }
        $sql_p=_query($sqlCE);
        $select="<select class='sel id_pres form-control' id='id_presentacion'>";
        while ($row=_fetch_array($sql_p)) {
            if ($i==0) {
                $unidadp=$row['unidad'];
                $preciop=$row['precio'];
                $descripcionp=$row['descripcion'];
            }
            if ($row['id_presentacion_producto'] == $id_presentacion){
                $unidadp=$row['unidad'];
                $preciop=$row['precio'];
                $descripcionp=$row['descripcion'];
                $select.="<option value=".$row['id_presentacion_producto']." selected>".$row['nombre']."</option>";
            }
            else{
                $select.="<option value=".$row['id_presentacion_producto'].">".$row['nombre']."</option>";
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
        $sql_ins="SELECT tblTipo_Producto.tipo_producto, tblProductos.id_producto, tblProductos.descripcion,tblInsumos_Emergencia.id_insumo ,tblInsumos_Emergencia.cantidad, tblPresentacion_Productos.precio, tblPresentacion_Productos.id_presentacion_producto, tblPresentacion_Productos.unidad FROM tblProductos INNER JOIN tblTipo_Producto on tblTipo_Producto.id_tipo_producto = tblProductos.id_tipo_producto INNER JOIN tblInsumos_Emergencia on tblInsumos_Emergencia.id_producto = tblProductos.id_producto INNER JOIN tblPresentacion_Productos on tblProductos.id_producto = tblPresentacion_Productos.id_producto_PP WHERE tblInsumos_Emergencia.deleted is NULL and tblInsumos_Emergencia.id_recepcion = $id_recepcion AND tblTipo_Producto.tipo_producto = 'P' AND tblInsumos_Emergencia.producto = 1 AND tblProductos.id_sucursal = 1 AND tblPresentacion_Productos.id_presentacion_producto = tblInsumos_Emergencia.id_presentacion";
        $res_ins=_query($sql_ins);
        $n=_num_rows($res_ins);
        $array_prod = array();
        for($i=0;$i<$n;$i++){
            $row=_fetch_array($res_ins);
            $array_prod[] = array(
                'id_producto' => $row['id_producto'],
                'tipo' => $row['tipo_producto'],
                'desc' =>   $row['descripcion'],
                'cantidad' =>  $row['cantidad'],
                'precio' => $row['precio'],
                'hora' => $row['id_producto'],
                'id_presentacion' => $row['id_presentacion_producto'],
                'unidad' => $row['unidad'],
                'id_insumo' => $row['id_insumo'],
            );
        }
        $sql_serv="SELECT tblServicios.id_servicio, tblServicios.descripcion, tblInsumos_Emergencia.id_insumo , tblInsumos_Emergencia.hora_de_aplicacion, tblInsumos_Emergencia.cantidad, tblServicios.precio FROM tblServicios INNER JOIN tblInsumos_Emergencia on tblServicios.id_servicio = tblInsumos_Emergencia.id_servicio WHERE tblInsumos_Emergencia.id_recepcion =$id_recepcion AND tblInsumos_Emergencia.servicio = 1 AND tblInsumos_Emergencia.deleted is NULL";
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
        echo json_encode($array_prod);
    }

?>
<?php
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
                $sql_pres="SELECT tblPresentacion_Productos.unidad FROM tblPresentacion_Productos WHERE tblPresentacion_Productos.id_presentacion_producto='$id_presentacion' AND tblPresentacion_Productos.id_producto_PP='$id_producto' ";
                $unidadx = 1;
                $res_pres=_query($sql_pres);
                if(_num_rows($res_pres) > 0){
                    $row = _fetch_array($res_pres);
                    $unidadx=$row['unidad'];
                }
                $cantidad_real=$cantidad*$unidadx;
                $repetido = "SELECT tblInsumos_Emergencia.id_insumo FROM tblInsumos_Emergencia WHERE tblInsumos_Emergencia.id_recepcion = $id_recepcion AND tblInsumos_Emergencia.id_insumo = $id_insumo AND tblInsumos_Emergencia.producto = '1' AND tblInsumos_Emergencia.id_producto = '$id_producto' AND tblInsumos_Emergencia.id_presentacion = $id_presentacion AND tblInsumos_Emergencia.cantidad = $cantidad AND tblInsumos_Emergencia.total = $precio_venta AND tblInsumos_Emergencia.deleted is NULL";
                $repetidoQuery=_query($repetido);
                $repe = _num_rows($repetidoQuery);
                if($repe == 0){
                    $existente = "SELECT tblInsumos_Emergencia.id_insumo, tblInsumos_Emergencia.cantidad FROM tblInsumos_Emergencia WHERE tblInsumos_Emergencia.id_recepcion = '$id_recepcion' AND tblInsumos_Emergencia.producto = '1' AND tblInsumos_Emergencia.id_producto = '$id_producto' AND tblInsumos_Emergencia.id_insumo = $id_insumo";
                    $existenteQuery=_query($existente);
                    $exist = _num_rows($existenteQuery);
                    $cantidad_anterior = 0;
                    if($exist > 0){
                        $row = _fetch_array($existenteQuery);
                        $id_insumox = $row['id_insumo'];
                        $cantidad_anterior = $row['cantidad'];
                        $tabla = "tblInsumos_Emergencia";
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
                        $tabla = "tblInsumos_Emergencia";
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
                        $cant_st_su = "SELECT tblStock_Ubicacion.id_stock_ubicacion, tblStock_Ubicacion.cantidad, tblPresentacion_Productos.costo from tblStock_Ubicacion INNER JOIN tblProductos on tblProductos.id_producto = tblStock_Ubicacion.id_producto INNER JOIN tblPresentacion_Productos on tblProductos.id_producto = tblPresentacion_Productos.id_producto_PP WHERE tblStock_Ubicacion.id_producto = $id_producto AND tblStock_Ubicacion.id_ubicacion = 2 AND tblPresentacion_Productos.id_presentacion_producto = $id_presentacion ";
                        $cant_stQuery_su=_query($cant_st_su);
                        $row_cant_su = _fetch_array($cant_stQuery_su);
                        $id_su = $row_cant_su['id_stock_ubicacion'];
                        $cant_stock_su = $row_cant_su['cantidad'];
                        $costo = $row_cant_su['costo'];
                        $cant_stock_original = "SELECT tblStock.id_stock, tblStock.cantidad_stock FROM tblStock WHERE tblStock.id_producto_stock = $id_producto AND tblStock.id_sucursal_stock = $id_sucursal";
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
                            $tabla3 = "tblStock";
                            $fd3= array(
                                'cantidad_stock' => $stock_original_nuevo
                            );
                            $where3 = " WHERE id_stock = '$id_stock_ori'";
                            $ins3 = _update($tabla3,$fd3,$where3);
                            if($ins3){
                                $tabla4 = "tblStock_Ubicacion";
                                $fd4= array(
                                    'cantidad' => $stock_ubicacion_nuevo
                                );
                                $where4 = " WHERE id_stock_ubicacion = '$id_su'";
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
                $existente = "SELECT tblInsumos_Emergencia.id_insumo FROM tblInsumos_Emergencia WHERE tblInsumos_Emergencia.id_recepcion = $id_recepcion and tblInsumos_Emergencia.servicio = 1 AND tblInsumos_Emergencia.id_servicio = $id_producto AND tblInsumos_Emergencia.total = $precio_venta AND tblInsumos_Emergencia.hora_de_aplicacion = '$fecha_hora'  AND tblInsumos_Emergencia.deleted is NULL AND tblInsumos_Emergencia.id_insumo = $id_insumo_S";
                $servicioExistente=_query($existente);
                $repe = _num_rows($servicioExistente);
                if($repe == 0){
                    $tabla = "tblInsumos_Emergencia";
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
                $sql_num = _query("SELECT di FROM tblCorrelativo WHERE id_sucursal='$id_sucursal'");
                $datos_num = _fetch_array($sql_num);
                $ult = $datos_num["di"]+1;
                $numero_doc=$ult.'_DI';
                $tipo_entrada_salida='Asignacion de productos a la recepcion a la repcion con el id:'.$id_recepcion;
                /*actualizar los correlativos de AI*/
                $corr=1;
                $up=1;
                $table="tblCorrelativo";
                $form_data = array(
                    'di' =>$ult
                );
                $where_clause_c="id_sucursal='".$id_sucursal."'";
                $up_corr=_update($table,$form_data,$where_clause_c);
                if($up_corr){
                    $table='tblMovimiento_Producto';
                    $form_data = array(
                      'id_sucursal_MP' => $id_sucursal,
                      'correlativo' => $numero_doc,
                      'concepto' => "ASIGNACION DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                      'total' => $precio_cargas,
                      'tipo' => 'SALIDA',
                      'proceso' => 'di',
                      'referencia' => $numero_doc,
                      'id_empleado_MP' => $id_empleado,
                      'fecha' => $dia1,
                      'hora' => $hora1,
                      'id_suc_origen' => $id_sucursal,
                      'id_suc_destino' => $id_sucursal,
                      'id_proveedor_MP' => 0,
                    );
                    $insert_mov =_insert($table,$form_data);
                    $id_movimiento=_insert_id();
                    if($insert_mov){
                        foreach ($array_cargas as $array_cargas1) {
                            $table1= 'tblMovimiento_Producto_Detalle';
                            $form_data1 = array(
                                'id_movimiento_MP'=>$id_movimiento,
                                'id_producto_MP' => $array_cargas1['id_producto'],
                                'cantidad' => $array_cargas1['cantidad'],
                                'costo' => $array_cargas1['costo'],
                                'precio' => $array_cargas1['precio'],
                                'stock_anterior'=>$array_cargas1['stock_anterior'],
                                'stock_actual'=>$array_cargas1['stock_actual'],
                                'id_presentacion_MP' => $array_cargas1['id_presentacion']
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
                $sql_num = _query("SELECT ti FROM tblCorrelativo WHERE id_sucursal='$id_sucursal'");
                $datos_num = _fetch_array($sql_num);
                $ult = $datos_num["ti"]+1;
                $numero_doc=$ult.'_TI';
                $tipo_entrada_salida='Descarga de productos de la repcion con el id:'.$id_recepcion;
                /*actualizar los correlativos de AI*/
                $corr=1;
                $up=1;
                $table="tblCorrelativo";
                $form_data = array(
                  'ti' =>$ult
                );
                $where_clause_c="id_sucursal='".$id_sucursal."'";
                $up_corr=_update($table,$form_data,$where_clause_c);
                if($up_corr){
                    $table='tblMovimiento_Producto';
                    $form_data = array(
                      'id_sucursal_MP' => $id_sucursal,
                      'correlativo' => $numero_doc,
                      'concepto' => "DESCARGA DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                      'total' => $precio_descargas,
                      'tipo' => 'ENTRADA',
                      'proceso' => 'ti',
                      'referencia' => $numero_doc,
                      'id_empleado_MP' => $id_empleado,
                      'fecha' => $dia1,
                      'hora' => $hora1,
                      'id_suc_origen' => $id_sucursal,
                      'id_suc_destino' => $id_sucursal,
                      'id_proveedor_MP' => 0,
                    );
                    $insert_mov =_insert($table,$form_data);
                    $id_movimiento=_insert_id();
                    if($insert_mov){
                        $id_descarga_movimiento = $id_movimiento;
                        foreach ($array_descargas as $array_cargas1) {
                            $table1= 'tblMovimiento_Producto_Detalle';
                            $form_data1 = array(
                                'id_movimiento_MP'=>$id_movimiento,
                                'id_producto_MP' => $array_cargas1['id_producto'],
                                'cantidad' => $array_cargas1['cantidad'],
                                'costo' => $array_cargas1['costo'],
                                'precio' => $array_cargas1['precio'],
                                'stock_anterior'=>$array_cargas1['stock_anterior'],
                                'stock_actual'=>$array_cargas1['stock_actual'],
                                'id_presentacion_MP' => $array_cargas1['id_presentacion']
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
            $sqlx = "SELECT tblInsumos_Emergencia.id_insumo, tblInsumos_Emergencia.total, tblInsumos_Emergencia.id_producto, tblInsumos_Emergencia.id_servicio, tblInsumos_Emergencia.producto, tblInsumos_Emergencia.servicio, tblInsumos_Emergencia.id_presentacion, tblPresentacion_Productos.costo FROM tblInsumos_Emergencia LEFT JOIN tblProductos on tblProductos.id_producto = tblInsumos_Emergencia.id_producto LEFT JOIN tblPresentacion_Productos on tblPresentacion_Productos.id_presentacion_producto = tblInsumos_Emergencia.id_presentacion WHERE tblInsumos_Emergencia.id_recepcion = $id_recepcion AND tblInsumos_Emergencia.deleted is NULL AND tblInsumos_Emergencia.created_at != '$fecha_hora_ingresar'";
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
                        $tabla_deleted="tblInsumos_Emergencia";
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
                    $sql_num = _query("SELECT ti FROM tblCorrelativo WHERE id_sucursal='$id_sucursal'");
                    $datos_num = _fetch_array($sql_num);
                    $ult = $datos_num["ti"]+1;
                    $numero_doc=$ult.'_TI';
                    /*actualizar los correlativos de AI*/
                    $tableC="tblCorrelativo";
                    $form_dataC = array(
                        'ti' =>$ult
                    );
                    $where_clause_cC="id_sucursal='".$id_sucursal."'";
                    $up_corrC=_update($tableC,$form_dataC,$where_clause_cC);
                    if($up_corrC){
                        $table='tblMovimiento_Producto';
                        $form_data = array(
                            'id_sucursal_MP' => $id_sucursal,
                            'correlativo' => $numero_doc,
                            'concepto' => "ELIMINACION DE PRODUCTOS A LA RECEPCION CON EL ID $id_recepcion",
                            'total' => $precio_total_eliminado,
                            'tipo' => 'ENTRADA',
                            'proceso' => 'ti',
                            'referencia' => $numero_doc,
                                'id_empleado_MP' => $id_empleado,
                                'fecha' => $dia1,
                                'hora' => $hora1,
                                'id_suc_origen' => $id_sucursal,
                                'id_suc_destino' => $id_sucursal,
                                'id_proveedor_MP' => 0,
                        );
                        $insert_mov =_insert($table,$form_data);
                        $id_movimientox=_insert_id();
                        if($insert_mov){
                          foreach ($array_base as $key => $value) {
                              $hora1=date("H:i:s");
                              $dia1 =date('Y-m-d');
                              if($value['tipo'] == "P"){
                                  $id_producto_eliminado = $value['id_producto'];
                                  $sqlD = "SELECT tblInsumos_Emergencia.cantidad FROM tblInsumos_Emergencia WHERE tblInsumos_Emergencia.id_insumo =".$value['id_insumo'];
                                  $consultaD = _query($sqlD);
                                  $registroD = _fetch_array($consultaD);
                                  $cantidad = $registroD['cantidad'];
                                  $sql_consulta_sg = "SELECT tblStock.id_stock, tblStock.cantidad_stock FROM tblStock WHERE tblStock.id_producto_stock = $id_producto_eliminado AND tblStock.id_sucursal_stock = 1";
                                  $consulta_sql_sg = _query($sql_consulta_sg);
                                  $registros_sql_sql = _fetch_array($consulta_sql_sg);
                                  $id_stock_original = $registros_sql_sql['id_stock'];
                                  $cantidad_stock_original = $registros_sql_sql['cantidad_stock'];
                                  $sql_consulta_su = "SELECT tblStock_Ubicacion.id_stock_ubicacion, tblStock_Ubicacion.cantidad FROM tblStock_Ubicacion WHERE tblStock_Ubicacion.id_producto = $id_producto_eliminado AND tblStock_Ubicacion.id_ubicacion = 2 AND tblStock_Ubicacion.id_sucursal = 1";
                                  $consulta_sql_su = _query($sql_consulta_su);
                                  $registros_sql_su = _fetch_array($consulta_sql_su);
                                  $id_stock_ubicacion = $registros_sql_su['id_stock_ubicacion'];
                                  $cantidad_stock_ubicacion = $registros_sql_su['cantidad'];
                                  $cantidad_nueva_so = $cantidad_stock_original+$cantidad;
                                  $cantidad_nueva_su = $cantidad_stock_ubicacion+$cantidad;
                                  $tabla3x = "tblStock";
                                  $fd3x= array(
                                      'cantidad_stock' => $cantidad_nueva_so
                                  );
                                  $where3x = " WHERE id_stock = '$id_stock_original'";
                                  $ins3x = _update($tabla3x,$fd3x,$where3x);
                                  if($ins3x){
                                      $tabla4x = "tblStock_Ubicacion";
                                      $fd4x= array(
                                          'cantidad' => $cantidad_nueva_su
                                      );
                                      $where4x = " WHERE id_stock_ubicacion = '$id_stock_ubicacion'";
                                      $ins4x = _update($tabla4x,$fd4x,$where4x);
                                      if($ins4x){
                                          $table1x= 'tblMovimiento_Producto_Detalle';
                                          $form_data1x = array(
                                              'id_movimiento_MP'=>$id_movimientox,
                                              'id_producto_MP' => $value['id_producto'],
                                              'cantidad' => $cantidad,
                                              'costo' => $value['costo'],
                                              'precio' => $value['precio'],
                                              'stock_anterior'=>$cantidad_stock_original,
                                              'stock_actual'=>$cantidad_nueva_so,
                                              'id_presentacion_MP' => $value['id_presentacion']
                                          );
                                          $insert_mov_detx = _insert($table1x,$form_data1x);
                                          if($insert_mov_detx){
                                              $tabla_deleted="tblInsumos_Emergencia";
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
                                    $tabla_deleted="tblInsumos_Emergencia";
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
        $id_presentacion =$_REQUEST['id_presentacion'];
        $id_recepcion =$_REQUEST['id_recepcion'];
        $id_P = _fetch_array(_query("SELECT tblProductos.id_producto, tblPresentacion_Productos.precio, tblPresentacion_Productos.unidad, tblPresentacion_Productos.descripcion FROM tblProductos INNER JOIN tblPresentacion_Productos on tblPresentacion_Productos.id_producto_PP = tblProductos.id_producto WHERE tblPresentacion_Productos.id_presentacion_producto = $id_presentacion"));
        $id_producto = $id_P['id_producto'];
        $sql=_fetch_array(_query("SELECT tblStock_Ubicacion.id_producto, SUM(tblStock_Ubicacion.cantidad) total FROM ( SELECT tblStock_Ubicacion.id_producto, tblStock_Ubicacion.cantidad FROM tblStock_Ubicacion WHERE tblStock_Ubicacion.id_producto = $id_producto AND tblStock_Ubicacion.id_ubicacion = 2 UNION ALL SELECT tblInsumos_Emergencia.id_producto, tblInsumos_Emergencia.cantidad FROM tblInsumos_Emergencia WHERE tblInsumos_Emergencia.id_producto = $id_producto AND tblInsumos_Emergencia.id_recepcion = $id_recepcion) tblStock_Ubicacion GROUP BY tblStock_Ubicacion.id_producto "));
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
        $id_recepcion=$_POST['idRecepcion'];
        $id_producto = $_POST['id_producto'];
        $id_presentacion="";
        if (isset($_REQUEST['id_presentacion'])){
            $id_presentacion=$_REQUEST['id_presentacion'];
        }
        $sql = "SELECT tblStock_Ubicacion.id_producto, SUM(tblStock_Ubicacion.cantidad) total FROM ( SELECT tblStock_Ubicacion.id_producto, tblStock_Ubicacion.cantidad FROM tblStock_Ubicacion WHERE tblStock_Ubicacion.id_producto = $id_producto AND tblStock_Ubicacion.id_ubicacion = 2 UNION ALL SELECT tblInsumos_Emergencia.id_producto, tblInsumos_Emergencia.cantidad FROM tblInsumos_Emergencia WHERE tblInsumos_Emergencia.id_producto = $id_producto AND tblInsumos_Emergencia.id_recepcion = $id_recepcion AND tblInsumos_Emergencia.deleted is NULL ) tblStock_Ubicacion GROUP BY tblStock_Ubicacion.id_producto ";
        $consulta = _query($sql);
        $row = _fetch_array($consulta);
        $xdatos['total'] = $row['total'];
        if($id_presentacion == ""){
            $sql2 ="SELECT tblPresentacion_Productos.unidad FROM tblPresentacion_Productos WHERE tblPresentacion_Productos.id_producto_PP = $id_producto";
        }
        else{
            $sql2 ="SELECT tblPresentacion_Productos.unidad FROM tblPresentacion_Productos WHERE tblPresentacion_Productos.id_producto_PP = $id_producto AND tblPresentacion_Productos.id_presentacion_producto = $id_presentacion";
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
        $sql = "SELECT tblStock_Ubicacion.id_producto, SUM(tblStock_Ubicacion.cantidad) total FROM ( SELECT tblStock_Ubicacion.id_producto, tblStock_Ubicacion.cantidad FROM tblStock_Ubicacion WHERE tblStock_Ubicacion.id_producto = $id_producto AND tblStock_Ubicacion.id_ubicacion = 2 UNION ALL SELECT tblInsumos_Emergencia.id_producto, tblInsumos_Emergencia.cantidad FROM tblInsumos_Emergencia WHERE tblInsumos_Emergencia.producto = $id_producto AND tblInsumos_Emergencia.id_recepcion = $id_recepcion AND tblInsumos_Emergencia.deleted is NULL ) tblStock_Ubicacion GROUP BY tblStock_Ubicacion.id_producto ";
        $consulta = _query($sql);
        $row = _fetch_array($consulta);
        $total = $row['total'];
        $numero_unidades =  $cantidad_especifica;
        $consulta_select="SELECT tblPresentacion.nombre, tblPresentacion_Productos.descripcion,tblPresentacion_Productos.id_presentacion_producto,
        tblPresentacion_Productos.unidad,tblPresentacion_Productos.precio FROM tblPresentacion_Productos JOIN tblPresentacion ON tblPresentacion.id_presentacion=tblPresentacion_Productos.id_presentacion_PP
        INNER JOIN tblProductos on tblProductos.id_producto = tblPresentacion_Productos.id_producto_PP INNER  JOIN tblStock_Ubicacion on tblStock_Ubicacion.id_producto = tblProductos.id_producto
        WHERE tblPresentacion_Productos.id_producto_PP=$id_producto AND tblPresentacion_Productos.activo =1 AND tblProductos.deleted is NULL and tblPresentacion_Productos.unidad <= $numero_unidades GROUP BY tblPresentacion_Productos.id_presentacion_producto";
        $sql_p = _query($consulta_select);
        $select="<select class='sel id_pres form-control' id='id_presentacion'>";
        while ($row=_fetch_array($sql_p)) {
            if($row['id_presentacion_producto'] == $id_presentacion){
                $select.="<option value=".$row['id_presentacion_producto']." selected>".$row['nombre']."</option>";
            }
            else{
              $select.="<option value=".$row['id_presentacion_producto'].">".$row['nombre']."</option>";
            }
        }
        $select.="</select>";
        $xdatos['select']=$select;
        echo json_encode($xdatos);

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
    case 'get_presentancion':
      get_presentacion();
    break;
    case 'consultar_existencias':
      consultar_existencias();
    break;
    case 'consultar_selects':
      consultar_selects();
      break;
  }
}
?>
