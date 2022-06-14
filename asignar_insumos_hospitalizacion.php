<?php
    include("_core.php");
    include('num2letras.php');
?>
<?php
    function initial(){
        $title="Insumos Utilizados por Hospitalizacion";
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
        $id_hospitalizacion= $_REQUEST['id_hospitalizacion'];
        $id_sucursal = $_SESSION['id_sucursal'];
        $sql = "SELECT recepcion.id_recepcion, paciente.nombres, paciente.apellidos, recepcion.evento, doctor.nombres as 'nombres_doctor', doctor.apellidos as 'apellidos_doctor', cuartos.numero_cuarto, pisos.numero_piso FROM hospitalizacion LEFT JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion LEFT JOIN cuartos on cuartos.id_cuarto = hospitalizacion.id_cuarto_H LEFT JOIN pisos on pisos.id_piso = cuartos.id_cuarto LEFT JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion LEFT JOIN doctor on doctor.id_doctor = recepcion.id_doctor_recepcion WHERE hospitalizacion.deleted IS NULL AND recepcion.deleted is NULL AND hospitalizacion.id_estado_hospitalizacion = '2' AND recepcion.id_sucursal_recepcion = '$id_sucursal' AND hospitalizacion.id_hospitalizacion = '$id_hospitalizacion'";
        $consulta = _query($sql);
        $resultado = _fetch_array($consulta);
        $hora_hoy = _hora_media_decode(date("H:i:s"));
        $idRecepcion = $resultado['id_recepcion'];

        $sql_referencia="SELECT * FROM insumos_emergencia WHERE id_recepcion=$idRecepcion";
        $query_referencia=_query($sql_referencia);
        $no_referencia='';
        if(_num_rows($query_referencia)>0){
            $array_referencia=_fetch_array($query_referencia);
            $no_referencia=$array_referencia['no_referencia'];
        }
        

        
        ?>
<style type="text/css">
.datepicker table tr td,
.datepicker table tr th {
    border: none;
    background: white;
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
                            <div class="form-group col-md-6">
                                <label>Paciente:</label>
                                <input type="text" id="paciente" name="paciente"
                                    value='<?php echo $resultado['nombres']." ".$resultado['apellidos']; ?>'
                                    class="form-control usage" hidden readonly autocomplete="off">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Doctor:</label>
                                <input type="text" id="doctor" name="doctor" class="form-control usage"
                                    value='<?php echo $resultado['nombres_doctor']." ".$resultado['apellidos_doctor']; ?>' hidden
                                    readonly autocomplete="off">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Evento:</label>
                                <textarea class="form-control usage" hidden readonly autocomplete="off" name="evento"
                                    id="evento" cols="30" rows="2"><?php echo $resultado['evento']; ?></textarea>
                            </div>

                            <div class="form-group col-md-4">
                                <label id='buscar_habilitado'>Buscar Insumos (Descripci&oacute;n)</label>
                                <input type="text" id="producto_buscar" name="producto_buscar"
                                    class="form-control usage" placeholder="Ingrese Descripcion de producto"
                                    data-provide="typeahead" style="border-radius:0px">
                                <input type="text" id="servicio_buscar" name="servicio_buscar" class="form-control"
                                    placeholder="Ingrese  Descripcion de  servicio " data-provide="typeahead"
                                    style="border-radius:0px">
                                <input type="text" id="examen_buscar" name="examen_buscar" class="form-control"
                                    placeholder="Ingrese  Descripcion de examen" data-provide="typeahead"
                                    style="border-radius:0px">

                            </div>
                            <div class="form-group col-md-4">
                                <label>Fecha de aplicacion</label>
                                <input type="text" name="hasta" id="fechaEntrada" class="form-control datepicker"
                                    value="<?php echo $hoy?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label> Hora de inicio</label>
                                <input type="text" placeholder="HH:mm" class="form-control" id="hora_entrada"
                                    name="hora_entrada" autocomplete="off" value="<?php echo $hora_hoy; ?>">
                            </div>
                        </div>
                        <div class="row">

                        </div>
                        <div class="row focuss">
                            <div class="form-group col-md-6" style="margin-top:23px;text-align: center;">
                                <button type="button" id="btnBuscaProd" name="btnBuscaProd"
                                    class="btn btn-primary usage"><i class="fa fa-barcode"></i> Productos</button>
                                <button type="button" id="btnBuscaServ" name="btnBuscaServ"
                                    class="btn btn-primary usage"><i class="fa fa-eye"></i> Servicios</button>
                                <!--<button type="button" id="btnBuscarExam" name="btnBuscarExam"
                                    class="btn btn-primary usage"><i class="fa fa-clipboard"></i> Examenes</button>-->
                            </div>
                            <div class="title-action col-md-6" id='botones'
                                style="margin-top:-10px;text-align: center;">

                                <a class="btn btn-danger " style="margin-left:3%;" href="admin_emergencia.php"
                                    id='salir'><i class="fa fa-mail-reply"></i> F4 Salir</a>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#guardar_insumos" data-whatever="@mdo""><i
                                        class="fa fa-check"></i> F2 Guardar</button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <section>
                                    <input type='hidden' name='porc_iva' id='porc_iva' value='<?php echo $iva; ?>'>
                                    <input type='hidden' name='monto_retencion1' id='monto_retencion1'
                                        value='<?php echo $monto_retencion1 ?>'>
                                    <input type='hidden' name='monto_retencion10' id='monto_retencion10'
                                        value='<?php echo $monto_retencion10 ?>'>
                                    <input type='hidden' name='monto_percepcion' id='monto_percepcion' value='100'>
                                    <input type='hidden' name='porc_retencion1' id='porc_retencion1' value=0>
                                    <input type='hidden' name='porc_retencion10' id='porc_retencion10' value=0>
                                    <input type='hidden' name='porc_percepcion' id='porc_percepcion' value=0>
                                    <input type='hidden' name='porcentaje_descuento' id='porcentaje_descuento' value=0>
                                    <input type="hidden" name="no_referencia" id='no_referencia' value=<?php echo $no_referencia ?>>
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
                                                                        <th class='success  cell100 column30'>
                                                                            DESCRIPCI&Oacute;N</th>
                                                                        <th class='success  cell100 column10'>STOCK</th>
                                                                        <th class='success  cell100 column15'>
                                                                            PRESENTACI&Oacute;N</th>
                                                                        <th class='success  cell100 column10'>PRECIO
                                                                        </th>
                                                                        <th class='success  cell100 column5'>CANT</th>
                                                                        <th class='success  cell100 column10'>SUBTOT
                                                                        </th>
                                                                        <th class='success  cell100 column10'>
                                                                            ACCI&Oacute;N</th>
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
                                                                        <td
                                                                            class='cell100 column15 leftt  text-bluegrey '>
                                                                            &nbsp;</td>
                                                                        <td
                                                                            class='cell100 column15 leftt  text-bluegrey '>
                                                                            N° INSUMOS:</td>
                                                                        <td class='cell100 column10 text-right text-danger'
                                                                            id='totcant'>0.00</td>
                                                                        <td
                                                                            class='cell100 column15 leftt  text-bluegrey '>
                                                                            SUBTOTAL $:</td>
                                                                        <td class='cell100 column10 text-right text-danger'
                                                                            id='subtotal'>0.00</td>
                                                                        <td
                                                                            class="cell100 column15  leftt text-bluegrey ">
                                                                            TOTAL $:</td>
                                                                        <td class='cell100 column10 text-right text-green'
                                                                            id='total_gravado'>0.00</td>
                                                                        <td
                                                                            class='cell100 column10 leftt  text-bluegrey '>
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class='cell100 column50 text-bluegrey'
                                                                            id='totaltexto'>&nbsp;</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--valores ocultos para referencia -->
                                        <input type='hidden' name='id_empleado' id='id_empleado'>
                                        <input type='hidden' name='numero_doc' id='numero_doc'>
                                        <input type='hidden' name='id_factura' id='id_factura'>
                                        <input type='hidden' name='urlprocess' id='urlprocess'
                                            value="<?php echo $filename; ?>">
                                        <input type='hidden' name='totalfactura' id='totalfactura' value='0'>
                                        <input type="hidden" id="fecha" value="<?php echo $fecha_actual; ?>">
                                        <input type="hidden" id="id_microcirugia" value="-1">
                                        <input type="hidden" id="id_recepcion" value="<?php echo $idRecepcion;?>">
                                        <input type="hidden" id="id_paciente" value="-1">
                                        <input type="hidden" id='items' value="0">
                                        <input type='hidden' name='id_apertura' id='id_apertura'
                                            value='<?php echo $id_apertura; ?>'>
                                        <input type='hidden' name='turno' id='turno' value='<?php echo $turno; ?>'>
                                        <input type='hidden' name='tip_impre' id='tip_impre'
                                            value='<?php echo $tipo_fa; ?>'>
                                        <input type='hidden' name='caja' id='caja' value='<?php echo $caja; ?>'>
                                    </div>
                                    <!--div class="table-responsive m-t"-->
                                </section>
                            </div>
                        </div>
                        <!--div class='ibox-content'-->
                        <!-- Modal -->
                        <div class="modal-container">
                            <div class="modal fade" id="clienteModal" tabindex="-2" role="dialog"
                                aria-labelledby="myModalCliente" aria-hidden="true">
                                <div class="modal-dialog model-sm">
                                    <div class="modal-content"> </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-container">
                            <div class="modal fade" id="doctorModal" tabindex="-2" role="dialog"
                                aria-labelledby="myModalCliente" aria-hidden="true">
                                <div class="modal-dialog model-sm">
                                    <div class="modal-content"> </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-container">
                            <div class="modal fade" id="procedenciaModal" tabindex="-2" role="dialog"
                                aria-labelledby="myModalCliente" aria-hidden="true">
                                <div class="modal-dialog model-sm">
                                    <div class="modal-content"> </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-container">
                            <div class="modal fade" id="cliente1Modal" tabindex="-2" role="dialog"
                                aria-labelledby="myModalCliente" aria-hidden="true">
                                <div class="modal-dialog model-sm">
                                    <div class="modal-content"> </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="guardar_insumos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Se aplicaran los insumos al paciente</h4>
                            </div>
                                <div class="modal-body alert alert-danger">
                                    <p>¿Esta seguro de querer aplicar los insumos?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-primary" id="aplicar_insumos">Si Estoy seguro</button>
                                </div>
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

                  echo "<script src='js/funciones/funciones_insumos_hospitalizacion.js'></script>";
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
    /*function consultar_stock(){
        $id_producto = $_REQUEST['id_producto'];
        $id_presentacion="";
        if (isset($_REQUEST['id_presentacion'])){
            $id_presentacion=$_REQUEST['id_presentacion'];
        }
        $cortesia = "";
        $id_usuario=$_SESSION["id_usuario"];
        $iva=13/100;
        $precio=0;
        $sql1 = "SELECT p.id_producto, p.barcode, p.descripcion, p.id_estado_producto, 
        p.perecedero, p.exento, p.id_categoria, p.id_sucursal,s.id_stock,s.cantidad_stock,
         s.id_sucursal_stock, s.precio_unitario, s.costo_unitario
        FROM tblProductos AS p, tblStock AS s WHERE p.id_producto = s.id_producto_stock 
        AND p.id_producto ='$id_producto' ";
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
        /*inicio modificacion presentacion
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
    }*/

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

    /*function traer_insumos(){
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
    }*/

?>
            <?php
    function insert(){
        date_default_timezone_set('America/El_Salvador');
        $error = 0;
        $id_sucursal = $_SESSION['id_sucursal'];
        $id_paciente=$_POST['id_paciente'];
        $id_vendedor=$_SESSION['id_usuario'];
        $id_recepcion = $_POST["id_recepcion"];
        $total = $_POST["total"];
        $items = $_POST["items"];
        $cuantos = $_POST['cuantos'];
        $array_json=$_POST['json_arr'];
        $fecha=date("Y-m-d");
        $hora=date("H:i:s");
        $fecha_hora_aplicacion = $fecha." ".$hora;
        $id_empleado=$_SESSION["id_usuario"];
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

        $hora=date("H:i:s");
        $no_referencia=$_POST['no_referencia'];
        $ult="";
        $numero_doc ='';
        if($no_referencia=='')
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
          $sql_num=_fetch_array(_query("SELECT * FROM ".EXTERNAL.".factura where numero_ref=$no_referencia"));
          $numero_doc=$sql_num['numero_doc'];
          $ult=$sql_num['numero_ref'];
        }
        $abono=0;
        $saldo=0;
        $tipo_documento="TIK";
        $tipo_entrada_salida='NUM. REFERENCIA INTERNA';

        if ($no_referencia=="0") {
            # code...

            $table_fact= EXTERNAL.".factura";
            $form_data_fact = array(
              'id_server' => '0',
              'id_cliente' => '1',
              'fecha' => date("Y:m:d"),
              'numero_doc' => $numero_doc,
              'referencia' => $numero_doc,
              'numero_ref' => $ult,
              'subtotal' => '',
              'sumas'=>'',
              'suma_gravado'=>'',
              'iva' =>'',
              'retencion'=>'',
              'venta_exenta'=>'',
              'total_menos_retencion'=>'',
              'total' => '',
              'id_usuario'=>$id_empleado,
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

            );
            $insertar_fact = _insert($table_fact,$form_data_fact );
            $id_fact= _insert_id();

            if (!$insertar_fact) {
              # code...
              $b=0;
            }
          }


        /*{"id":"277",
            "id_presentacion":"349",
            "precio":"0.0000",
            "cantidad":"1",
            "subtotal":"0.00",
            "tipop":"P",
            "fecha":"00-00-0000 12:00 AM",
            "unidad":"1","id_insumo":""}*/
            $contar_insert=0;
        foreach($array as $producto_servicio){
            $id_producto=$producto_servicio['id'];
            $id_presentacion=$producto_servicio['id_presentacion'];
            $precio=$producto_servicio['precio'];
            $cantidad=$producto_servicio['cantidad'];
            $subtotal=$producto_servicio['subtotal'];
            $tipop=$producto_servicio['tipop'];
            $fecha=$producto_servicio['fecha'];
            $unidad=$producto_servicio['unidad'];

            $insert=-1;//inicializamos insert en -3
            
            if($tipop=="P"){
                $table='insumos_emergencia';
                $form_insumo=[
                    'id_recepcion'=>$id_recepcion,
                    'id_producto'=>$id_producto,
                    'id_presentacion'=>$id_presentacion,
                    'cantidad'=>$cantidad,
                    'total'=>$subtotal,
                    'hora_de_aplicacion'=>$fecha_hora_aplicacion,
                    'no_referencia'=>$ult
                ];
                $insert=_insert($table, $form_insumo);
                $contar_insert++;

            }else if($tipop=="S"){//si es tipo "S" entonces el id_producto es el de servicio
                $table='insumos_emergencia';
                $form_insumo=[
                    'id_recepcion'=>$id_recepcion,
                    'id_servicio'=>$id_producto,
                    'id_presentacion'=>$id_presentacion,
                    'cantidad'=>$cantidad,
                    'total'=>$subtotal,
                    'hora_de_aplicacion'=>$fecha_hora_aplicacion,
                    'no_referencia'=>$ult
                ];
                $insert=_insert($table, $form_insumo);
                $contar_insert++;
            }


        }
        //Recorre el arreglo con todos los productos y servicios agregados

        /*	
            0	Object { id: "464", id_presentacion: "617", precio: "5.7100", … }
            id	"464"
            id_presentacion	"617"
            precio	"5.7100"
            cantidad	"1"
            subtotal	"5.71"
            tipop	"P"
            fecha	"00-00-0000 12:00 AM"
            unidad	"20"
            id_insumo	

""
        */
        
        
        $xdatos=[];
        if($contar_insert==count($array)){
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Insumos ingresados con exito';
            $xdatos['process']='insert';
        }else{
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Los insumos no fuern agregado con exito, comprueve que se hayan agregado todos los insumos';
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
        $id_P = _fetch_array(_query("SELECT ".EXTERNAL.".producto.id_producto, ".EXTERNAL.".presentacion_producto.precio, 
        ".EXTERNAL.".presentacion_producto.unidad, ".EXTERNAL.".presentacion_producto.descripcion 
        FROM ".EXTERNAL.".producto
        INNER JOIN ".EXTERNAL.".presentacion_producto on 
        ".EXTERNAL.".presentacion_producto.id_producto = ".EXTERNAL.".producto.id_producto 
        WHERE ".EXTERNAL.".presentacion_producto.id_presentacion = $id_presentacion"));
        $id_producto = $id_P['id_producto'];
        $lquery="SELECT tblStock_Ubicacion.id_producto, SUM(tblStock_Ubicacion.cantidad) total FROM 
        ( SELECT ".EXTERNAL.".stock_ubicacion.id_producto, ".EXTERNAL.".stock_ubicacion.cantidad 
        FROM ".EXTERNAL.".stock_ubicacion WHERE ".EXTERNAL.".stock_ubicacion.id_producto = $id_producto 
        AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = 1 
        UNION ALL 
        SELECT cms.insumos_emergencia.id_producto, cms.insumos_emergencia.cantidad 
        FROM cms.insumos_emergencia 
        WHERE cms.insumos_emergencia.id_producto = $id_producto 
        AND cms.insumos_emergencia.id_recepcion =$id_recepcion) tblStock_Ubicacion 
        GROUP BY tblStock_Ubicacion.id_producto ";
        $sql=_fetch_array(_query($lquery));
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
        $sql="SELECT tblStock_Ubicacion.id_producto, SUM(tblStock_Ubicacion.cantidad)
        total FROM ( SELECT ".EXTERNAL.".stock_ubicacion.id_producto, ".EXTERNAL.".stock_ubicacion.cantidad 
        FROM ".EXTERNAL.".stock_ubicacion 
        WHERE ".EXTERNAL.".stock_ubicacion.id_producto = $id_producto  
        AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = 1 
        UNION ALL SELECT cms.insumos_emergencia.id_producto, cms.insumos_emergencia.cantidad 
        FROM cms.insumos_emergencia 
        WHERE cms.insumos_emergencia.id_producto = $id_producto 
        AND cms.insumos_emergencia.id_recepcion = $id_recepcion 
        AND cms.insumos_emergencia.deleted is NULL ) tblStock_Ubicacion 
        GROUP BY tblStock_Ubicacion.id_producto";
        $consulta = _query($sql);
        $row = _fetch_array($consulta);
        $xdatos['total'] = $row['total'];
        if($id_presentacion == ""){
            $sql2="SELECT ".EXTERNAL.".presentacion_producto.unidad FROM ".EXTERNAL.".presentacion_producto WHERE ".EXTERNAL.".presentacion_producto.id_producto = $id_producto";
        }
        else{
            $sql2="SELECT ".EXTERNAL.".presentacion_producto.unidad FROM ".EXTERNAL.".presentacion_producto WHERE ".EXTERNAL.".presentacion_producto.id_producto = $id_producto AND ".EXTERNAL.".presentacion_producto.id_presentacion = $id_presentacion";
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
        $sql="SELECT tblStock_Ubicacion.id_producto, SUM(tblStock_Ubicacion.cantidad) total 
              FROM ( SELECT ".EXTERNAL.".cmf.stock_ubicacion.id_producto, ".EXTERNAL.".cmf.stock_ubicacion.cantidad 
                FROM ".EXTERNAL.".cmf.stock_ubicacion WHERE ".EXTERNAL.".cmf.stock_ubicacion.id_producto = $id_producto 
                AND ".EXTERNAL.".cmf.stock_ubicacion.id_ubicacion = 1 
                UNION ALL 
                SELECT cms.insumos_emergencia.id_producto, cms.insumos_emergencia.cantidad 
                FROM cms.insumos_emergencia WHERE cms.insumos_emergencia.producto = $id_producto 
                AND cms.insumos_emergencia.id_recepcion = $id_recepcion
                AND cms.insumos_emergencia.deleted is NULL ) 
                tblStock_Ubicacion 
                GROUP BY tblStock_Ubicacion.id_producto";

        $consulta = _query($sql);
        $row = _fetch_array($consulta);
        $total = $row['total'];
        $numero_unidades =  $cantidad_especifica;
        $consulta_select="SELECT ".EXTERNAL.".presentacion.nombre, ".EXTERNAL.".presentacion_producto.descripcion,
        ".EXTERNAL.".presentacion_producto.id_presentacion, 
        ".EXTERNAL.".presentacion_producto.unidad,".EXTERNAL.".presentacion_producto.precio 
        FROM ".EXTERNAL.".presentacion_producto LEFT JOIN ".EXTERNAL.".presentacion 
        ON ".EXTERNAL.".presentacion.id_presentacion=".EXTERNAL.".presentacion_producto.id_presentacion 
        INNER JOIN ".EXTERNAL.".producto on ".EXTERNAL.".producto.id_producto = ".EXTERNAL.".presentacion_producto.id_producto 
        INNER JOIN ".EXTERNAL.".stock_ubicacion on ".EXTERNAL.".stock_ubicacion.id_producto = ".EXTERNAL.".producto.id_producto 
        WHERE ".EXTERNAL.".presentacion_producto.id_producto=$id_producto AND ".EXTERNAL.".presentacion_producto.activo =1 
        and ".EXTERNAL.".presentacion_producto.unidad <= $numero_unidades GROUP BY ".EXTERNAL.".presentacion_producto.id_presentacion ";
        

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