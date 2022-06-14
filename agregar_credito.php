<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='Agregar Crédito';
    $_PAGE = array ();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

    include_once "header.php";
    include_once "main_menu.php";

    $sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
    $datos_moneda = _fetch_array($sql0);
    $simbolo = $datos_moneda["simbolo"];  
    $moneda = $datos_moneda["moneda"]; 

    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);
?>
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <?php
                        if ($links!='NOT' || $admin=='1' ){
                    ?>
                    <div class="ibox-title">
                         <h3 style="color:#194160;"><i class="fa fa-money"></i> <b><?php echo $title;?></b></h3>
                    </div>
                    <div class="ibox-content">
                        <div class="col-lg-12" id="cliente_detalle">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3 class='text-success'>Datos del Cliente</h3></div>
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Nombre</label>
                                                <input type="text" name="nombre" id="nombre" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-info">
                                                    <label>DUI</label>
                                                    <input type="text" id="dui" name="dui" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Tipo de crédito</label>
                                                <select class="form-control select" name="tipo_c" id="tipo_c">
                                                    <option value="PERSONAL">Personal</option>
                                                    <option value="INSTITUCIONAL">Institucional</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Fecha</label>
                                                <input type='text' class='form-control' id='fecha' name='fecha' value='<?php echo date('d-m-Y');?>' readonly>
                                            </div>
                                        </div>
                                        <div class="row" id='div_inst' hidden>
                                            <div class="col-md-12">
                                                <div class="form-group has-info">
                                                    <label>Institución</label>
                                                    <input type="text" id="institucion" name="institucion" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-info">
                                                    <label>Tipo de Documento</label>
                                                    <input type="text" id="tipo_d" name="tipo_d" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-info">
                                                    <label>Número</label>
                                                    <input type="text" id="numero_d" name="numero_d" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" id="detalle_servicio">
                            <div class="panel panel-default"><!-- panel RECIBO -->
                                <div class="panel-heading"><h3 class='text-success'>Detalle de Servicios</h3></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group has-info">
                                                    <label>Buscar Servicio</label>
                                                    <input type="text" id="buscar_servicio" name="buscar_servicio" class="form-control" placeholder="Ingrese descripcion del producto o servicio">
                                                    <input type="hidden" name="id_servicio" id="id_servicio"/>
                                                    <!--div id='contrib_selected'></div-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table class="table table-condensed table-striped">
                                                    <thead class="thead-inverse">
                                                        <tr class="bg-success">
                                                            <th class="col-lg-1">Id</th>
                                                            <th class="col-lg-7">Descripción</th>
                                                            <th class="col-lg-1">Precio</th>
                                                            <th class="col-lg-1">Cantidad</th>
                                                            <th class="col-lg-1">Subtotal</th>
                                                            <th class="col-lg-1">&nbsp;&nbsp;Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id='table_credito'>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line text-center" id='text_dinero'></td>
                                                        <td class="thick-line" id='total_dinero'></td>
                                                        <td class="thick-line" id='total_fiestas'></td>
                                                        <td class="thick-line"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <table class="table invoice-total">
                                                    <tbody>
                                                    <tr>
                                                        <td><strong>TOTAL <?php echo $simbolo; ?>:</strong></td>
                                                        <td id='total_final' style="text-align: left;"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <div class="well m-t"  id='totaltexto'><strong></strong> </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" id="cliente_detalle">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3 class='text-success'>Detalle de Pagos</h3></div>
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group has-info">
                                                    <label>Número cuotas</label>
                                                    <input type="text" id="numero_c" name="numero_c" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-info">
                                                    <label>Cuota <?php echo '('.$simbolo.')'; ?></label>
                                                    <input type="text" id="cuota" name="cuota" class="form-control numeric" readonly value="0.00">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-info">
                                                    <label>Fecha Inicio</label>
                                                    <input type="text" id="inicio" name="inicio" class="form-control datepicker" value="<?php echo date("d-m-Y");?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group has-info">
                                                    <label>Frecuencia (días)</label>
                                                    <input type="text" id="frecuencia" name="frecuencia" class="form-control numeric" value="30">
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="process" id="process" value="insert">
                        <input type="hidden" name="simbolo" id="simbolo" value="<?php echo $simbolo; ?>">
                        <div class="title-action" id='botones'>
                            <a id="btn_fin" name="btn_fin" class="btn btn-primary"><i class="fa fa-check"></i> Guardar</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_credito.js'></script>";

} //permiso del script
else {
        echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
    }
}
function insert()
{

    $fecha= MD($_POST['fecha']);
    $total_final= $_POST['total'];
    $dui= $_POST['dui'];
    $nombre= $_POST['nombre'];
    $tipo_c= $_POST['tipo_c'];
    $institucion= $_POST['institucion'];
    $tipo_d= $_POST['tipo_d'];
    $numero_d= $_POST['numero_d'];
    $numero_c= $_POST['numero_c'];
    $frecuencia= $_POST['frecuencia'];
    $cuota= $_POST['cuota'];
    $inicio= $_POST['inicio'];
    $stringdatos = $_POST['datos'];
    $ffin = $frecuencia * ($numero_c-1);
    if(_num_rows(_query("SELECT * FROM credito WHERE cliente='$nombre' AND dui='$dui' AND tipo='$tipo_c' AND monto='$total_final' AND fecha='$fecha'"))>0)
    {
       $xdatos['typeinfo']='Error';
       $xdatos['msg']='Este Credito ya fue registrado!';
    }
    else
    {
        _begin();
        $listadatos=explode('|',$stringdatos);
        $update = false;
        $cuantos = $_POST["cuantos"];
        $validos = 0;
        $k = 0;
        $table = 'credito';
        if($cuantos>0)
        {
            $form_data = array(
                'cliente'=>$nombre,
                'dui'=>$dui,
                'tipo'=>$tipo_c,
                'institucion'=>$institucion,
                'documento'=>$tipo_d,
                'numero_doc'=>$numero_d,
                'monto'=>$total_final,
                'fecha'=>$fecha,
                'fecha_inicio'=>MD($inicio),
                'fecha_fin'=>sumar_dias(MD($inicio), $ffin),
                'estado'=>'PENDIENTE',
                'frecuencia'=>$frecuencia,
                'cuota' => $cuota
            );
            $update = _insert($table,$form_data);
            $id_credito= _insert_id();

            for ($i=0;$i<$cuantos ;$i++)
            {
                list($id_servicio,$cantidad,$precio)=explode(',',$listadatos[$i]);
                $subtotal = $cantidad * $precio;

                $table2 = 'detalle_credito';

                $form_data2 = array(
                    'id_credito'=>$id_credito,
                    'id_servicio'=>$id_servicio,
                    'cantidad'=>$cantidad,
                    'precio'=>$precio,
                    'subtotal'=>$subtotal,
                );

                $update2 = _insert($table2,$form_data2 );
                if($update2)
                {
                    $validos ++;
                }
            }
            for($j=0; $j<$numero_c; $j++)
            {
                $table3 = "abono_credito";
                $form_data3=array(
                    'id_credito'=>$id_credito,
                    'fecha'=>MD($inicio),
                    'monto'=>$cuota,
                    'pagado'=>0
                    );  
                $inicio = ED(sumar_dias($inicio, $frecuencia));     
                $insert = _insert($table3, $form_data3);
                if($insert)
                {
                    $k++;
                }
            }
        }
        if($update && $cuantos==$validos && $k==$numero_c)
        {
            _commit();
           $xdatos['typeinfo']='Success';
           $xdatos['msg']='Credito ingresado con exito!';
           $xdatos['process']='insert';
        }
        else
        {
            _rollback(); 
           $xdatos['typeinfo']='Error';
           $xdatos['msg']='Credito no pudo ser ingresado !';
        }
    }
    echo json_encode($xdatos);
}

function consultar_servicio()
{
    $id_servicio=$_POST["id_servicio"];
    $sql0="SELECT * FROM servicio WHERE id_servicio='$id_servicio'";
    $sql=_query($sql0);
    while($row=_fetch_array($sql))
    {
        $descripcion=$row["descripcion"];
        $precio=$row["precio"];
    }
    $xdatos['descripcion']=$descripcion;
    $xdatos['precio']=$precio;
    echo json_encode($xdatos); //Return the JSON Array
}

function total_texto()
{
    $sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
    $datos_moneda = _fetch_array($sql0);
    $simbolo = $datos_moneda["simbolo"];  
    $moneda = $datos_moneda["moneda"]; 

    $total=number_format($_POST['total'],2,".","");
    $lista = explode('.',$total);
    $entero = $lista[0];
    $decimal = $lista[1];
    
    $enteros_txt=num2letras($entero);

    if($entero>1)
        $dolar=$moneda;
    else
        $dolar=$moneda;
    $cadena_salida= "SON: <strong>".strtoupper($enteros_txt." ".$dolar)." CON ".$decimal."/100.</strong>";
    //echo $cadena_salida;
    $xdatos['totaltexto']=$cadena_salida;
    echo json_encode($xdatos); //Return the JSON Array
}

if(!isset($_POST['process'])){
    initial();
}
else
{
if(isset($_POST['process']))
{
switch ($_POST['process']) {
    case 'insert':
        insert();
        break;
    case 'consultar_servicio':
        consultar_servicio();
        break;
    case 'total_texto':
        total_texto();
        break;
    }
}
}
?>
