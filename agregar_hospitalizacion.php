<?php
include_once "_core.php";
function initial()
{
    $title = 'Agregar Hospitalizacion';
    $_PAGE = array ();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
    $_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/timepicki/timepicki.css" rel="stylesheet">';

    include_once "header.php";
    include_once "main_menu.php";
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);
    $hoy = date("d-m-Y");
    $id_sucursal = $_SESSION['id_sucursal'];
    ?>
            <style  type="text/css">
                .datepicker table tr td, .datepicker table tr th{
                    border:none;
                    background:white;
                }
            </style>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-2">
            </div>
        </div>
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                    <?php
                    //permiso del script
                    if ($links!='NOT' || $admin=='1' ){
                    ?>
                        <div class="ibox-title">
                        <h3 class="text-navy"><b><i class="fa fa-money fa-1x"></i> <?php echo $title;?></b></h3>
                        </div>
                        <div class="ibox-content">
                            <form name="formulario_hospitalizacion" id="formulario_hospitalizacion">
                                <div class="row">
                                    <div class = "col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group has-info single-line">
                                                    <label>Paciente <span style="color:red;">*</span></label>
                                                    <input type="text" id="paciente" name="paciente"  class="form-control usage sel" placeholder="Ingrese Paciente" data-provide="typeahead" autocomplete="off">
                                                    <input type="text" id="paciente_replace" name="paciente_replace"  class="form-control usage" hidden readonly autocomplete="off">
                                                    <input type="hidden" name="pacientee" id="pacientee" value=''>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group has-info single-line">
                                                    <label>Descripcion de la recepcion<span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" id="descripcion_recepcion" name="descripcion_recepcion" value='Primero ingrese el paciente que se recepciono.' readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group has-info single-line">
                                                    <label>Doctor que lo atendera <span style="color:red;">*</span></label>
                                                    <select class="select" id="id_doctor_at" name="id_doctor_at">
                                                        <?php
                                                            $sql_doctor="SELECT d.id_doctor, CONCAT(d.nombres, '', d.apellidos) AS nombre_doctor FROM doctor AS d";
                                                            $query_doctor=_query($sql_doctor);
                                                            if(_num_rows($query_doctor)>0){
                                                                while($row_doctor=_fetch_array($query_doctor)){
                                                        ?>
                                                            <option value="<?=  $row_doctor['id_doctor']; ?>"><?= $row_doctor['nombre_doctor']; ?></option>
                                                        <?php
                                                                }
                                                            }
                                                        ?>                                                        
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group has-info single-line">
                                                    <label>Numero de piso  <span style="color:red;">*</span></label>
                                                    <br>
                                                    <select class="select col-lg-6" name="numero_piso" id="numero_piso" style="width:100%;">
                                                    <option value="">Seleccione</option>
                                                    <?php
                                                        $sql = _query("SELECT * FROM pisos WHERE deleted is NULL AND id_ubicacion_piso = '$id_sucursal'  ORDER BY numero_piso ASC");
                                                        while ($row = _fetch_array($sql))
                                                        {
                                                            $id_piso = $row["id_piso"];
                                                            $numero = $row["numero_piso"];
                                                            $descripcion = $row['descripcion'];
                                                            $resultado = "Piso #".$numero."- ".$descripcion;
                                                            echo "<option value='".$id_piso."'>".$resultado."</option>";
                                                        }
                                                    ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group has-info single-line">
                                                    <label>Numero de habitacion <span style="color:red;">*</span></label>
                                                    <select class="col-md-12 select" id="n_habitacion" name="n_habitacion">
                                                        <option value="">Primero seleccione un numero de piso</option>
                                                    </select>
                                                    <input type="hidden" name="estado_habitacion" id = "estado_habitacion" value ="0">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 ">
                                                <div id='informacion_estado'>
                                                    <div class="form-group has-info text-center alert alert-warning">
                                                        <label><?php echo "Primero seleccione una habitacion"; ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 ">
                                                <div class="form-group has-info single-line">
                                                    <label>Precio por hora de la habitacion<span style="color:green;"> $ </span><span style="color:red;"> * </span></label>
                                                    <input type="text" class="form-control decimal" id="precio_habitacion" name="precio_habitacion" value='Primero seleccione una habitacion.' readonly>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 ">
                                                <div class="form-group has-info single-line">
                                                    <label>Tipo de cobro <span style="color:red;">*</span></label>
                                                    <br>
                                                    <input class="form-control-input" type="radio" name="radio_cobro" id="radio_cobro1" value="1" checked>
                                                    <label class="form-control-label" for="radio_cobro1">
                                                        Por hora
                                                    </label>
                                                    <input class="form-control-input" type="radio" name="radio_cobro" id="radio_cobro2" value="2" >
                                                    <label class="form-control-label" for="radio_cobro1">
                                                        Por minuto
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group has-info single-line">
                                                    <label>Fecha de entrada <span style="color:red;">*</span></label>
                                                    <input type="text" name="fecha_de_entrada" id="fecha_de_entrada" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group has-info single-line">
                                                    <label>Hora de entrada <span style="color:red;">*</span></label>
                                                    <input type="text" placeholder="HH:mm" class="form-control" id="hora_entrada" name="hora_entrada" autocomplete="off" readonly >
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group has-info single-line">
                                                    <label>Fecha de salida </label>
                                                    <input type="text" name="fecha_de_salida" id="fecha_de_salida" class="form-control datepicker" value="<?php echo $hoy;?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group has-info single-line">
                                                    <label>Hora de salida </label>
                                                    <input type="text" placeholder="HH:mm" class="form-control" id="hora_salida" name="hora_salida" autocomplete="off">
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                    <br>
                                    <div class='col-lg-6'>

                                    </div>
                                    <br>
                                    <div class='col-lg-6 d-flex justify-content-between align-items-center'>
                                    <br>
                                        <input type="submit" id="agregar_hospitalizacion" style="float: right;" name="agregar_hospitalizacion" value="Guardar" class="btn btn-primary m-t-n-xs" />
                                    </div>
                                </div>

                                <input type="hidden" name="process" id="process" value="insert"><br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include_once ("footer.php");
        echo "<script src='js/funciones/funciones_hospitalizacion.js'></script>";
    } //permiso del script
    else
    {
        $mensaje = "No tiene permiso para acceder a este modulo";
        echo "<br><br>$mensaje<div><div></div></div</div></div>";
        include "footer.php";
    }
}

function insertar()
{
    $id_sucursal = $_SESSION['id_sucursal'];
    $id_recepcion = $_POST['id_recepcion'];
    $id_cuarto = $_POST['id_cuarto'];
    $precio_por_hora = $_POST['precio_por_hora'];
    $tipo_pago = $_POST['tipo_pago'];
    $fecha_de_entrada = $_POST['fecha_de_entrada'];
    $fecha_de_salida = $_POST['fecha_de_salida'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];
    $id_doctor_at=$_POST['id_doctor_at'];
    if($hora_salida != "00:00:00"){
        $hora_salida = _hora_media_encode($hora_salida);
    }
    $momento_entrada = MD($fecha_de_entrada)." "._hora_media_encode($hora_entrada);
    $momento_salida = MD($fecha_de_salida)." ".$hora_salida;
    $insert_table = 'hospitalizacion';
    $form_data = array(
        'id_recepcion' => $id_recepcion,
        'id_cuarto_H' => $id_cuarto,
        'momento_entrada' => $momento_entrada,
        'momento_salida' => $momento_salida,
        'precio_habitacion' => $precio_por_hora,
        'id_estado_hospitalizacion' => 1,
        'minuto' => $tipo_pago,
        'id_doctor_at'=>$id_doctor_at
    );
    $insert = _insert($insert_table, $form_data);
    if($insert){
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Registro ingreado con exito!';
        $xdatos['process']='insert';
    }
    else{
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Registro no pudo ser ingreado!'._error();
    }

    echo json_encode($xdatos);
}
function habitacion($id_piso)
{
    $option = "";
    $id_sucursal = $_SESSION['id_sucursal'];
    $sql_mun = _query("SELECT cuartos.id_cuarto, cuartos.numero_cuarto, cuartos.descripcion, tipo_cuarto.tipo, tipo_cuarto.cantidad FROM cuartos INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto WHERE pisos.id_piso = '$id_piso' AND pisos.id_ubicacion_piso = '$id_sucursal'");
    $option .= "<option value=''>Seleccione</option>";
    while($mun_dt=_fetch_array($sql_mun))
    {
        $resultado = "Cuarto #".$mun_dt['numero_cuarto'].", ".$mun_dt['descripcion'].", cuarto de tipo ".$mun_dt['tipo']." con capacidad para ".$mun_dt['cantidad']." personas";
        $option .= "<option value='".$mun_dt["id_cuarto"]."'>".$resultado."</option>";
    }
    echo $option;
}
function estado_habitacion(){
    $id_habitacion = $_POST['id_habitacion'];
    $id_sucursal = $_SESSION['id_sucursal'];

    $sql = "SELECT estado_cuarto.estado, cuartos.precio_por_hora FROM estado_cuarto INNER JOIN cuartos on estado_cuarto.id_estado_cuarto = cuartos.id_estado_cuarto_cuarto INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto WHERE cuartos.id_cuarto = '$id_habitacion' AND pisos.id_ubicacion_piso = '$id_sucursal'";
    $query = _query($sql);
    $row = _fetch_array($query);
    $estado = $row['estado'];
    $resultado = "";
    if($estado == "DISPONIBLE"){
        $resultado .= "<div class='form-group has-info text-center alert alert-success'>";
        $resultado .="<label id='texto_estado' value='DISPONIBLE'>El cuarto se encuentra disponible</label>";
        $resultado .="</div>";
    }
    if($estado == "OCUPADO"){
        $resultado .= "<div class='form-group has-info text-center alert alert-warning'>";
        $resultado .="<label id='texto_estado' value='OCUPADO'>El cuarto se encuentra ocupado</label>";
        $resultado .="</div>";
    }
    if($estado == "MANTENIMIENTO"){
        $resultado .= "<div class='form-group has-info text-center alert alert-info'>";
        $resultado .="<label id='texto_estado' value='MANTENIMIENTO'>El cuarto se encuentra en mantenimiento</label>";
        $resultado .="</div>";
    }
    if($estado == "FUERA DE SERVICIO"){
        $resultado .= "<div class='form-group has-info text-center alert alert-danger'>";
        $resultado .="<label id='texto_estado' value='FUERA_DE_SERVICIO'>El cuarto se encuentra fuera de servicio</label>";
        $resultado .="</div>";
    }
    $xdatos['resultado']=$resultado;
    $precio_por_hora = number_format($row['precio_por_hora'], 2);
        $precio_por_hora= $precio_por_hora;
    $xdatos['precio']=$precio_por_hora;
    $xdatos['estado'] = $estado;
    echo json_encode($xdatos);
}
function verificar_paciente($id_paciente){
    $id_sucursal = $_SESSION['id_sucursal'];
    $sql = "SELECT paciente.id_paciente, recepcion.id_recepcion FROM paciente INNER JOIN recepcion on recepcion.id_paciente_recepcion = paciente.id_paciente INNER JOIN estado_recepcion on estado_recepcion.id_estado_recepcion = recepcion.id_estado_recepcion WHERE paciente.id_paciente = '$id_paciente' AND recepcion.id_sucursal_recepcion = '$id_sucursal' AND estado_recepcion.id_estado_recepcion != 4 AND estado_recepcion.id_estado_recepcion != 5 AND estado_recepcion.id_estado_recepcion != 3";
    $consulta = _query($sql);
    if(_num_rows($consulta) > 0){
        $row = _fetch_array($consulta);
        $xdatos['resultado'] = "1";
        $xdatos['id_recepcion_x'] = $row['id_recepcion'];
    }
    else{
        $xdatos['resultado'] = "0";
    }
    echo json_encode($xdatos);
}
function verificar_hospitalizacion(){
    $id_sucursal = $_SESSION['id_sucursal'];
    $id_paciente = $_POST['id_paciente'];
    $sql = "SELECT hospitalizacion.id_hospitalizacion FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion WHERE (hospitalizacion.id_estado_hospitalizacion = '1' OR hospitalizacion.id_hospitalizacion = '2') AND paciente.id_paciente = '$id_paciente'";

    $query = _query($sql);
    $numero = _num_rows($query);
    if($numero == 0){
        $xdatos['respuesta']= "1";
    }
    else{
        $xdatos['respuesta']= "0";
    }
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
            case 'insert':
                insertar();
            break;
            case 'habitacion':
                habitacion($_POST['id_piso']);
            break;
            case 'estado_habitacion':
                estado_habitacion();
            break;
            case 'verificar_paciente':
                verificar_paciente($_POST['id_paciente']);
                break;
            case 'verificar_hospitalizacion':
                verificar_hospitalizacion();
                break;
        }
    }
}
?>
