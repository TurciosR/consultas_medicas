<?php
//Cambios
include_once "_core.php";
function initial(){
  // Page setup
  $title = "Facturacion";
  $_PAGE = array();
  $_PAGE['title'] = $title;
  $_PAGE['links'] = null;
  $_PAGE['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
  $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
  $_PAGE ['links'] .= '<link href="css/plugins/blueimp/css/blueimp-gallery.css" rel="stylesheet" type="text/css"/>';
  $_PAGE ['links'] .= '<link href="css/plugins/blueimp/css/blueimp-gallery-indicator.css" rel="stylesheet" type="text/css"/>';
  $_PAGE ['links'] .= '<link href="css/plugins/blueimp/css/blueimp-gallery-video.css" rel="stylesheet" type="text/css"/>';
  $_PAGE['links'] .= '<link href="css/animate.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/style.css" rel="stylesheet">';
  $_PAGE['links'] .= '<link href="css/typeahead.css" rel="stylesheet">';


  include_once "header.php";
  include_once "main_menu.php";
  //permiso del script
  $id_user=$_SESSION["id_usuario"];
  $admin=$_SESSION["admin"];
  $id = $_REQUEST['id'];

  $sql_factura="SELECT * FROM ".EXTERNAL.".factura WHERE id_recepcion=$id";
  $query_factura=_query($sql_factura);
  $id_factura="";
  if(_num_rows($query_factura)>0){
    $row_factura=_fetch_array($query_factura);
    $id_factura=$row_factura['id_factura'];
  }

  

  $uri = $_SERVER['SCRIPT_NAME'];
  $filename=get_name_script($uri);
  $links=permission_usr($id_user,$filename);

  $sql_iva="SELECT iva,monto_retencion1,monto_retencion10,monto_percepcion FROM ".EXTERNAL.".sucursal WHERE id_sucursal=1";
    $result_IVA=_query($sql_iva);
    $row_IVA=_fetch_array($result_IVA);
    $iva=$row_IVA['iva']/100;
    $monto_retencion1=$row_IVA['monto_retencion1'];
    $monto_retencion10=$row_IVA['monto_retencion10'];
    $monto_percepcion=$row_IVA['monto_percepcion'];
  ?>
<style media="screen">
tr .descp {
    display: none !important;
}
</style>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <?php
          //permiso del script
          if ($links!='NOT' || $admin=='1' ){
            ?>
                <div class="ibox-title">
                    <h3 style="color:#194160;"><i class="fa fa-stethoscope"></i> <b><?php echo $title;?></b></h3>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="form-group col-md-5">
                            <div id="a">
                                <label id='buscar_habilitado'>Buscar Producto (Descripci&oacute;n)</label>
                                <div id="scrollable-dropdown-menu">
                                    <input type="text" id="producto_buscar" name="producto_buscar"
                                        style="width:100% !important" class=" form-control usage typeahead"
                                        placeholder="Ingrese Descripcion de producto" data-provide="typeahead"
                                        style="border-radius:0px">
                                </div>
                            </div>

                            <div hidden id="b">
                                <label id='buscador_composicion'>Buscar Producto (Composición)</label>
                                <div id="scrollable-dropdown-menu">
                                    <input type="text" id="composicion" name="composicion" style="width:100% !important"
                                        class=" form-control usage typeahead"
                                        placeholder="Ingrese la Composicion del producto" data-provide="typeahead"
                                        style="border-radius:0px">
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-info col-md-4">
                            <label>Facturar en Sucursal</label>
                            <select id="id_sucursal" style="width:100%" class="" name="" disabled>
                                <?php
                    $sql_suc = _query("SELECT * FROM ".EXTERNAL.".sucursal ");
                    while ($rows = _fetch_array($sql_suc)){
                        if($rows['descripcion']=="BOTIQUIN SAN JOSE"){

                      ?>

                                <option value="<?=$rows['id_sucursal'] ?>" selected><?=$rows['descripcion']?></option>
                                <?php
                            }
                    }
                     ?>
                            </select>
                        </div>
                        <div hidden class="form-group has-info col-md-2">
                            <label>Tipo Impresi&oacuten</label>
                            <select name='tipo_impresion' id='tipo_impresion' class='select form-control usage'>
                                <option value='TIK' selected>COBRO</option>
                                <option value='TIK'>TICKET</option>
                                <option value='COF'>FACTURA</option>
                                <option value='CCF'>CREDITO FISCAL</option>
                            </select>
                        </div>
                        <div hidden class="col-md-2">
                            <div class="form-group has-info">
                                <label>Seleccione tipo de pago</label><br>
                                <select name='con_pago' id='con_pago' class='select form-control usage'>
                                    <option value='0' selected>Contado</option>
                                    <option value='1'>Credito</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group has-info col-md-3"><br>
                            <button type="button" id="preventax" style="margin-left:1%;" name="preventa"
                                class="btn btn-sm btn-success pull-right usage"><i class="fa fa-save"></i> F8
                                Guardar</button>

                            <a data-toggle="modal" href="agregar_servicio1.php" style="margin-right:1%;"
                                data-target="#viewModal" data-refresh="true"
                                class="btn btn-sm btn-warning pull-right"><i class="fa fa-medkit icon-large"></i>
                                Servicio</a>
                        </div>
                    </div>
                    <div class="row">
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
                        <input type="hidden" id='id_recepcion' name="id_recepcion" value="<?php echo $id; ?>">
                        <input type="hidden" name="id_factura" id="id_factura" value="<?php echo $id_factura ?>">
                        <div class="form-group has-info col-md-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th style='display:none'>ID</th>
                                    <th class="col-lg-3">Descripcion</th>
                                    <th class="col-lg-1">Stock</th>
                                    <th class="col-lg-1">Cantidad</th>
                                    <th class="col-lg-2">Presentacion</th>
                                    <th class="col-lg-1 descp">Presentacion</th>
                                    <th class="col-lg-1">Precio</th>
                                    <th class="col-lg-2">$</th>
                                    <th class="col-lg-2">Subtotal</th>
                                    <th class="col-lg-1">Accion</th>
                                </thead>
                                <tbody id="inventable">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <td class='cell100 column50 text-bluegrey' id='totaltexto'>&nbsp;</td>
                                        <td class='cell100 column15 leftt  text-bluegrey '>CANT. PROD:</td>
                                        <td class='cell100 column10 text-right text-danger' id='totcant'>0.00</td>
                                        <td class="cell100 column10  leftt text-bluegrey ">TOTALES $:</td>
                                        <td class='cell100 column15 text-right text-green' id='total_gravado'>0.00</td>

                                    </tr>
                                    <tr hidden>
                                        <td class="cell100 column15 leftt text-bluegrey ">SUMAS (SIN IVA) $:</td>
                                        <td class="cell100 column10 text-right text-green" id='total_gravado_sin_iva'>
                                            0.00</td>
                                        <td class="cell100 column15  leftt  text-bluegrey ">IVA $:</td>
                                        <td class="cell100 column10 text-right text-green " id='total_iva'>0.00</td>
                                        <td class="cell100 column15  leftt text-bluegrey ">SUBTOTAL $:</td>
                                        <td class="cell100 column10 text-right  text-green" id='total_gravado_iva'>0.00
                                        </td>
                                        <td class="cell100 column15 leftt  text-bluegrey ">VENTA EXENTA $:</td>
                                        <td class="cell100 column10  text-right text-green" id='total_exenta'>0.00</td>
                                    </tr>
                                    <tr hidden>
                                        <td class="cell100 column15 leftt text-bluegrey ">PERCEPCION $:</td>
                                        <td class="cell100 column10 text-right  text-green" id='total_percepcion'>0.00
                                        </td>
                                        <td class="cell100 column15  leftt  text-bluegrey ">RETENCION $:</td>
                                        <td class="cell100 column10 text-right text-green" id='total_retencion'>0.00
                                        </td>
                                        <td class="cell100 column15 leftt text-bluegrey ">DESCUENTO $:</td>
                                        <td class="cell100 column10  text-right text-green" id='total_final'>0.00</td>
                                        <td class="cell100 column15 leftt  text-bluegrey">A PAGAR $:</td>
                                        <td class="cell100 column10  text-right text-green" id='monto_pago'>0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'></div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
    ?>
<div class="footer">
    <strong>Copyright</strong> <a href="http://opensolutionsystems.com/" target="_blank">OpenSolutionSystems</a> &copy;
    <?php echo date("Y");?>
</div>
</div>
<script src="js/jquery-2.1.1.js"></script>
<script src="js/plugins/datapicker/moment.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="js/plugins/chosen/chosen.jquery.js"></script>
<script src="js/inspinia.js"></script>
<script src="js/plugins/pace/pace.min.js"></script>
<script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="js/plugins/validate/jquery.validate.min.js"></script>
<script src="js/plugins/iCheck/icheck.min.js"></script>
<script src="js/plugins/toastr/toastr.min.js"></script>
<script src="js/plugins/jqGrid/i18n/grid.locale-en.js"></script>
<script src="js/plugins/jqGrid/jquery.jqGrid.min.js"></script>
<script src="js/plugins/jeditable/jquery.jeditable.js"></script>
<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="js/plugins/dataTables/dataTables.responsive.js"></script>
<script src="js/plugins/dataTables/dataTables.tableTools.min.js"></script>
<script src="js/plugins/flot/jquery.flot.js"></script>
<script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="js/plugins/flot/jquery.flot.spline.js"></script>
<script src="js/plugins/flot/jquery.flot.resize.js"></script>
<script src="js/plugins/flot/jquery.flot.pie.js"></script>
<script src="js/plugins/flot/jquery.flot.symbol.js"></script>
<script src="js/plugins/flot/curvedLines.js"></script>
<script src="js/plugins/peity/jquery.peity.min.js"></script>
<script src="js/plugins/chartJs/Chart.js"></script>
<script src="js/plugins/iCheck/icheck.min.js"></script>
<script src="js/plugins/spinedit/spinedit.js"></script>
<script src='js/plugins/select2/select2.js'></script>
<script src='js/plugins/switchery/switchery.js'></script>
<script src='js/plugins/summernote/summernote.min.js'></script>
<script src='js/plugins/sortable/jquery-sortable.js'></script>
<script src='js/plugins/upload_file/fileinput.js'></script>
<script src='js/plugins/upload_file/fileinput_locale_es.js'></script>
<script src='js/plugins/numeric/jquery.numeric.js'></script>
<script src='js/plugins/datapicker/bootstrap-datepicker.js'></script>
<script src='js/plugins/jasny/jasny-bootstrap.min.js'></script>
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
<script src="js/plugins/fullcalendar/fullcalendar.js"></script>
<script src="js/plugins/fullcalendar/es.js"></script>
<script src="js/plugins/timepicker/jquery.timepicker.js"></script>
<script type="text/javascript" src="js/plugins/fileinput/fileinput.js"></script>
<script type="text/javascript" src="js/plugins/tour/bootstrap-tour.js"></script>
<script type="text/javascript" src="js/plugins/ckeditor/ckeditor.js"></script>
<script src="js/funciones/functions_messages_clean.js"></script>
<script src="js/funciones/funciones_asistente.js"></script>
<script src="js/html2canvas.js"></script>

<!--autocomplete bootstrap3 -->


<script src='js/plugins/typeahead11/bloodhound.min.js'></script>
<script src='js/plugins/typeahead11/typeahead.jquery.min.js'></script>



<script type="text/javascript">
window.setInterval(function() {
    var el = document.createElement('img');
    el.src = 'sessionRenew.php?rand=' + Math.random();
    el.style.opacity = .01;
    el.style.width = 1;
    el.style.height = 1;
    el.onload = function() {
        document.body.removeChild(el);
    }
    document.body.appendChild(el);
}, 60000);
</script>

</body>

</html>
<?php
    $a = rand(1,9999);
    echo "<script src='js/funciones/venta_eh.js?t$a=$a'></script>";
    ?>
<?php
    //echo "<script src='js/funciones/funciones_cita.js'></script>";
  } //permiso del script
}

function consultar_stock()
{
  $id_producto = $_REQUEST['id_producto'];
  $id_usuario=$_SESSION["id_usuario"];

  $precios= 7;
  $limit="";
  if ($precios==0) {
    $limit="LIMIT 1";
  }
  else{
    $limit="LIMIT 7";
  }
  $id_sucursal=$_REQUEST['id_sucursal'];
  $id_factura=$_REQUEST['id_factura'];
  $precio=0;
  $categoria="";

  $sql1 = "SELECT p.id_producto,p.id_categoria, p.barcode, p.descripcion, p.estado, p.perecedero, p.exento, p.id_categoria,
   p.id_sucursal,SUM(su.cantidad) as stock 
   FROM ".EXTERNAL.".producto AS p JOIN 
   ".EXTERNAL.".stock_ubicacion as su ON 
   su.id_producto=p.id_producto JOIN ".EXTERNAL.".ubicacion as u
    ON u.id_ubicacion=su.id_ubicacion  WHERE  
    p.id_producto ='$id_producto' AND 
    u.bodega=0 AND su.id_sucursal=$id_sucursal";
  $stock1=_query($sql1);
  $row1=_fetch_array($stock1);
  $nrow1=_num_rows($stock1);
  if ($nrow1>0)
  {
    $hoy=date("Y-m-d");
    $perecedero=$row1['perecedero'];
    $barcode = $row1["barcode"];
    $descripcion = $row1["descripcion"];
    $estado = $row1["estado"];
    $perecedero = $row1["perecedero"];
    $exento = $row1["exento"];
    $categoria=$row1['id_categoria'];
    $sql_res_pre=_fetch_array(_query("SELECT SUM(factura_detalle.cantidad) as reserva FROM ".EXTERNAL.".factura JOIN ".EXTERNAL.".factura_detalle ON factura_detalle.id_factura=factura.id_factura WHERE factura_detalle.id_prod_serv=$id_producto AND factura.id_sucursal=$id_sucursal AND factura.fecha = '$hoy' AND factura.finalizada=0 "));
    $reserva=$sql_res_pre['reserva'];

    $sql_res_esto=_fetch_array(_query("SELECT SUM(factura_detalle.cantidad) as reservado FROM ".EXTERNAL.".factura JOIN ".EXTERNAL.".factura_detalle ON factura_detalle.id_factura=factura.id_factura WHERE factura_detalle.id_prod_serv=$id_producto AND factura.id_factura=$id_factura"));
    $reservado=$sql_res_esto['reservado'];


    $stock= $row1["stock"]-$reserva+$reservado;
    if($stock<0)
    {
      $stock=0;
    }

    $i=0;
    $unidadp=0;
    $preciop=0;
    $descripcionp=0;
    $select_rank="<select class='sel_r form-control'>";
    $sql_p=_query("SELECT presentacion.nombre, presentacion_producto.descripcion,presentacion_producto.id_presentacion,presentacion_producto.unidad,presentacion_producto.precio FROM ".EXTERNAL.".presentacion_producto JOIN ".EXTERNAL.".presentacion ON presentacion.id_presentacion=presentacion_producto.presentacion WHERE presentacion_producto.id_producto='$id_producto' AND presentacion_producto.activo=1 AND presentacion_producto.id_sucursal=$id_sucursal ORDER BY presentacion_producto.unidad ASC");
    $select="<select class='sel form-control'>";
    while ($row=_fetch_array($sql_p))
    {
      if ($i==0)
      {
        $unidadp=$row['unidad'];
        $preciop=$row['precio'];
        $descripcionp=$row['descripcion'];

        $xc=0;

        $sql_rank=_query("SELECT presentacion_producto_precio.id_prepd,presentacion_producto_precio.desde,presentacion_producto_precio.hasta,presentacion_producto_precio.precio FROM ".EXTERNAL.".presentacion_producto_precio WHERE presentacion_producto_precio.id_presentacion=$row[id_presentacion] AND presentacion_producto_precio.id_sucursal=$id_sucursal AND presentacion_producto_precio.precio!=0 ORDER BY presentacion_producto_precio.precio DESC $limit
          ");

          if(_num_rows($sql_rank)==0)
          {
            $select_rank.="<option value='$preciop'>$preciop</option>";
          }

          while ($rowr=_fetch_array($sql_rank)) {
            # code...
            $select_rank.="<option value='$rowr[precio]'";
            if($xc==0)
            {
              $select_rank.="selected";
              $preciop=$rowr['precio'];
              $xc=1;
            }
            $select_rank.=">$rowr[precio]</option>";
          }
          $select_rank.="</select>";
        }
        $select.="<option value='$row[id_presentacion]'>$row[nombre]</option>";
        $i=$i+1;
      }


      $select.="</select>";
      $xdatos['perecedero']=$perecedero;
      $xdatos['descripcion']= $descripcion;
      $xdatos['select']= $select;
      $xdatos['select_rank']= $select_rank;
      $xdatos['stock']= $stock;
      $xdatos['preciop']= $preciop;

      $sql_e=_fetch_array(_query("SELECT exento FROM ".EXTERNAL.".producto WHERE id_producto=$id_producto"));
      $exento=$sql_e['exento'];
      if ($exento==1) {
        # code...
        $xdatos['preciop_s_iva']=$preciop;
      }
      else {
        # code...
        $sqkl=_fetch_array(_query("SELECT iva FROM ".EXTERNAL.".sucursal WHERE id_sucursal=$id_sucursal"));
        $iva=$sqkl['iva']/100;
        $iva=1+$iva;
        $xdatos['preciop_s_iva']= round(($preciop/$iva),8,PHP_ROUND_HALF_DOWN);
      }
      $xdatos['unidadp']= $unidadp;
      $xdatos['descripcionp']= $descripcionp;
      $xdatos['exento']=$exento;
      $xdatos['categoria']=$categoria;

      echo json_encode($xdatos); //Return the JSON Array
    }
  }
  function getpresentacion()
  {
    $id_sucursal= $_REQUEST['id_sucursal'];
    $id_presentacion =$_REQUEST['id_presentacion'];
    $cant =$_REQUEST['cant'];
    $sql=_fetch_array(_query("SELECT * FROM ".EXTERNAL.".presentacion_producto WHERE id_presentacion=$id_presentacion"));
    $precio=$sql['precio'];
    $unidad=$sql['unidad'];
    $descripcion=$sql['descripcion'];
    $id_producto=$sql['id_producto'];
    $sql_e=_fetch_array(_query("SELECT exento FROM ".EXTERNAL.".producto WHERE id_producto=$id_producto"));
    $exento=$sql_e['exento'];

    $select_rank="<select class='sel_r precio_r form-control'>";
    $xc=0;

    $id_usuario=$_SESSION["id_usuario"];
    $r_precios=_fetch_array(_query("SELECT precios FROM ".EXTERNAL.".usuario WHERE id_usuario=$id_usuario"));
    $precios=7;
    $limit="";
    if ($precios==0) {
      $limit="LIMIT 7";
    }
    else{
      $limit="LIMIT 7";
    }

    $sql_rank=_query("SELECT id_prepd,desde,hasta,precio
      FROM ".EXTERNAL.".presentacion_producto_precio
      WHERE id_presentacion=$id_presentacion
      AND id_sucursal=$id_sucursal
      AND precio>0
      ORDER BY precio DESC $limit");

      while ($rowr=_fetch_array($sql_rank))
      {
        # code...
        $select_rank.="<option value='$rowr[precio]'";
        if(!$xc)
        {
          $select_rank.=" selected ";
          $precio=$rowr['precio'];
          $xc=1;
        }
        $select_rank.=">$rowr[precio]</option>";
      }
      if (_num_rows($sql_rank)==0) {
        # code...
        $select_rank.="<option value='$precio'";
        $select_rank.="selected";
        $select_rank.=">$precio</option>";
      }
      $select_rank.="</select>";

      $des = "<input type='text' id='ss' class='txt_box form-control' value='".$descripcion."' readonly>";
      $xdatos['precio']=$precio;

      if ($exento==1) {
        # code...
        $xdatos['preciop_s_iva']=$precio;
      }
      else {
        # code...
        $sqkl=_fetch_array(_query("SELECT iva FROM ".EXTERNAL.".sucursal WHERE id_sucursal=$id_sucursal"));
        $iva=$sqkl['iva']/100;
        $iva=1+$iva;
        $xdatos['preciop_s_iva']= round(($precio/$iva),8,PHP_ROUND_HALF_DOWN);
      }
      $xdatos['unidad']=$unidad;
      $xdatos['descripcion']=$des;
      $xdatos['descripcion']=$des;
      $xdatos['select_rank']=$select_rank;
      echo json_encode($xdatos);
    }

if(!isset($_POST['process']))
{
  initial();
}
else
{
  if(isset($_POST['process']))
  {
    switch ($_POST['process'])
    {
      case 'getpresentacion':
      getpresentacion();
      break;
      case 'consultar_stock':
      consultar_stock();
      break;
      case 'insert_preventa':
        insertar_preventa();
        break;
      default:
      break;

    }
  }
}


function insertar_preventa()
        {
          //date_default_timezone_set('America/El_Salvador');

          $id_factura=$_POST['id_factura'];
          $fecha_movimiento= $_POST['fecha_movimiento'];
          $id_cliente=$_POST['id_cliente'];

          $id_vendedor=$_SESSION['id_usuario'];
          $cuantos = $_POST['cuantos'];
          $array_json=$_POST['json_arr'];
          //  IMPUESTOS
          $total_percepcion= $_POST['total_percepcion'];

          $subtotal=$_POST['subtotal'];
          $sumas=$_POST['sumas'];
          $suma_gravada=$_POST['suma_gravada'];
          $iva= $_POST['iva'];
          $retencion= $_POST['retencion'];
          $venta_exenta= $_POST['venta_exenta'];
          $total_menos_retencion=$_POST['total'];
          $total = $retencion+$_POST['total'];

          $id_empleado=$_SESSION["id_usuario"];
          $id_sucursal=$_POST["id_sucursal"];
          $fecha_actual = date('Y-m-d');
          $tipoprodserv = "PRODUCTO";
          $id_recepcion=$_POST['id_recepcion'];

          $insertar_fact=false;
          $insertar_fact_dett=true;
          $insertar_numdoc =false;

          $hora=date("H:i:s");
          $xdatos['typeinfo']='';
          $xdatos['msg']='';
          $xdatos['process']='';

          _begin();

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
          $tipo_documento=$_POST['tipo_impresion'];
          $tipo_entrada_salida='NUM. REFERENCIA INTERNA';
          if ($id_factura=="0") {
            # code...

            $table_fact= EXTERNAL.".factura";
            $form_data_fact = array(
              'id_server' => '0',
              'id_cliente' => '1',
              'fecha' => date("Y:m:d"),
              'numero_doc' => $numero_doc,
              'referencia' => $numero_doc,
              'numero_ref' => $ult,
              'subtotal' => $subtotal,
              'sumas'=>$sumas,
              'suma_gravado'=>$suma_gravada,
              'iva' =>$iva,
              'retencion'=>$retencion,
              'venta_exenta'=>$venta_exenta,
              'total_menos_retencion'=>$total_menos_retencion,
              'total' => $total,
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
              'id_recepcion'=>$id_recepcion

            );
            $insertar_fact = _insert($table_fact,$form_data_fact );
            $id_fact= _insert_id();

            if (!$insertar_fact) {
              # code...
              $b=0;
            }
          }
          else {

            //actualizando los campos nuevos de la factura
            $sql_factura="SELECT f.subtotal, f.sumas, f.suma_gravado, f.iva, f.venta_exenta, f.retencion, 
                          f.total_menos_retencion, f.saldo, f.abono, f.total 
                          FROM ".EXTERNAL.".factura AS f WHERE id_factura=$id_factura";
            
            $sub_total_q=0.0;
            $sumas_q=0.0;
            $suma_gravado_q=0.0;
            $iva_q=0.0;
            $venta_exenta_q=0.0;
            $total_menos_retencion_q=0.0;
            $retencion_q=0.0;
            $saldo_q=0.0;
            $abono_q=0.0;
            $total_q=0.0;

            $query_factura_q=_query($sql_factura);
            if(_num_rows($query_factura_q)>0){
              $row_factura_q=_fetch_array($query_factura_q);
              //actualizando los datos
              $sub_total_q=$row_factura_q['subtotal'] + $subtotal;
              $sumas_q=$row_factura_q['sumas'] + $sumas;
              $suma_gravado_q=$row_factura_q['suma_gravado'] + $suma_gravada;
              $iva_q=$row_factura_q['iva'] + $iva;
              $venta_exenta=$row_factura_q['venta_exenta'] + $venta_exenta;
              $retencion_q=$row_factura_q['retencion']+$retencion;
              $total_menos_retencion_q=$row_factura_q['total_menos_retencion'] + $total_menos_retencion;
              $saldo_q=$row_factura_q['saldo'] + $saldo;
              $abono_q=$row_factura_q['abono'] + $abono;
              $total_q=$row_factura_q['total'] + $total;
            }
            # code...
            $table_fact= EXTERNAL.".factura";
            $form_data_fact = array(
              'id_cliente' => $id_cliente,
              'fecha' => date("Y:m:d"),
              'numero_doc' => $numero_doc,
              'referencia' => $numero_doc,
              'numero_ref' => $ult,
              'subtotal' => $sub_total_q,
              'sumas'=>$sumas_q,
              'suma_gravado'=>$suma_gravado_q,
              'iva' =>$iva_q,
              'retencion'=>$retencion_q,
              'venta_exenta'=>$venta_exenta_q,
              'total_menos_retencion'=>$total_menos_retencion_q,
              'total' => $total_q,
              'id_usuario'=>$id_empleado,
              'id_empleado' => $id_vendedor,
              'id_sucursal' => $id_sucursal,
              'tipo' => $tipo_entrada_salida,
              'hora' => $hora,
              'finalizada' => '0',
              'abono'=>$abono_q,
              'saldo' => $saldo_q,
              'tipo_documento' => $tipo_documento,
              'id_recepcion'=>$id_recepcion
            );
            $whereclause="id_factura='".$id_factura."'";
            $insertar_fact = _update($table_fact,$form_data_fact,$whereclause );
            $id_fact= $id_factura;

            if (!$insertar_fact) {
              # code...
              $b=0;
            }
            /*$table="".EXTERNAL.".factura_detalle";
            $where_clause="id_factura='".$id_fact."'";
            $delete=_delete($table,$where_clause);
            if (!$delete) {
              # code...
              $b=0;
            }*/

          }

          if ($cuantos>0)
          {
            $array = json_decode($array_json, true);
            foreach ($array as $fila)
            {
              $id_producto=$fila['id'];
              $unidades=$fila['unidades'];
              $subtotal=$fila['subtotal'];
              $cantidad=$fila['cantidad'];
              $id_presentacion=$fila['id_presentacion'];
              $cantidad_real=$cantidad*$unidades;
              $exento=$fila['exento'];
              $precio_venta=$fila['precio'];
              $servicio = $fila['servicio'];

              $table_fact_det= "".EXTERNAL.".factura_detalle";
              $data_fact_det = array(
                'id_server' => '0',
                'id_factura' => $id_fact,
                'id_prod_serv' => $id_producto,
                'cantidad' => $cantidad_real,
                'precio_venta' => $precio_venta,
                'subtotal' => $subtotal,
                'tipo_prod_serv' => $tipoprodserv,
                'id_empleado' => $id_empleado,
                'id_sucursal' => $id_sucursal,
                'fecha' => date("Y:m:d"),
                'id_presentacion'=> $id_presentacion,
                'exento' => $exento,
                'descuento' => '0',
                'id_server_prod' => '0',
                'id_factura_dia' => '0',
                'impresa_lote' => '0',
                'hora' => date("H:i:s"),
                'id_server_presen' => '0',
                'servicio' => $servicio
              );
              $insertar_fact_det = _insert($table_fact_det,$data_fact_det );
              if (!$insertar_fact_det) {
                # code...
                $c=0;
              }

            } //foreach ($array as $fila){
              if ($a&&$b&&$c)
              {


                _commit(); // transaction is committed
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Referenca Numero: <strong>'.$numero_doc.'</strong>  Guardado con Exito !';
                $xdatos['referencia']=$ult;
                $xdatos['tot']=number_format($total,2);
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
?>
