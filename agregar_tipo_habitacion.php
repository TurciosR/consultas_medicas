<?php
include_once "_core.php";
function initial()
{
    $title = 'Agregar Tipo de Habitacion';
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
    $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

    include_once "header.php";
    include_once "main_menu.php";
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);

    ?>
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
                    <h5><?php echo $title; ?></h5>
                </div>
                <div class="ibox-content">
                    <form name="formulario_habitacion" id="formulario_habitacion">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group has-info single-line">
                                    <label>Tipo de habitacion<span style="color:red;">*</span></label>
                                    <input type="text" placeholder="Tipo de habitacion" class="form-control"
                                        id="tipo_habitacion" name="tipo_habitacion">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group has-info single-line">
                                    <label>Capacidad de pacientes<span style="color:red;">*</span></label>
                                    <input type="text" placeholder="Capacidad de pacientes" class="form-control"
                                        id="capacidad" name="capacidad">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group has-info single-line">
                                    <label>Descripcion de habitacion<span style="color:red;">*</span></label>
                                    <input type="text" placeholder="Descripcion de la habitacion" class="form-control"
                                        id="descripcion" name="descripcion">
                                </div>
                            </div>
                            <br>
                            <div class='col-lg-6'>

                            </div>
                            <div class='col-lg-6 d-flex justify-content-between align-items-center'>
                                <br>
                                <input type="submit" id="agregar_tipo_habitacion" style="float: right;"
                                    name="agregar_tipo_habitacion" value="Guardar" class="btn btn-primary m-t-n-xs" />
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
        echo "<script src='js/funciones/funciones_tipo_habitacion.js'></script>";
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
    $capacidad = $_POST['capacidad'];
    $tipo_habitacion =  $_POST['tipo_habitacion'];
    $descripcion =  $_POST['descripcion'];
    $id_sucursal=$_SESSION["id_sucursal"];
    $sql = "SELECT tipo_cuarto.id_tipo_cuarto FROM tipo_cuarto WHERE tipo_cuarto.tipo = '$tipo_habitacion' AND tipo_cuarto.id_sucursal = '$id_sucursal' or tipo_cuarto.descripcion = '$descripcion' AND tipo_cuarto.id_sucursal = '$id_sucursal'";
    $sql_exis = _query($sql);
    $num_exis = _num_rows($sql_exis);
    if($num_exis > 0)
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Ya existe ese tipo de habitacion registrado!';
    }
    else
    {
        $table = 'tipo_cuarto';
        $form_data = array(
            'tipo' => $tipo_habitacion,
            'descripcion' => $descripcion,
            'cantidad' => $capacidad,
            'estado' => 1,
            'id_sucursal' => $id_sucursal
        );
        $insertar = _insert($table,$form_data );
        if($insertar)
        {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Registro ingreado con exito!';
            $xdatos['process']='insert';
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Registro no pudo ser ingreado!'._error();
        }
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
        }
    }
}
?>