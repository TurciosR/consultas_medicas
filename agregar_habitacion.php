<?php
include_once "_core.php";
function initial()
{
    $title = 'Agregar Habitacion';
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
                                    <label>Numero de piso <span style="color:red;">*</span></label>
                                    <br>
                                    <select class="select col-lg-6" name="numero_piso" id="numero_piso"
                                        style="width:100%;">
                                        <option value="">Seleccione</option>
                                        <?php
												$sql = _query("SELECT * FROM pisos WHERE deleted is NULL ORDER BY numero_piso ASC");
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
                            <div class="col-lg-6">
                                <div class="form-group has-info single-line">
                                    <label>Numero de habitacion <span style="color:red;">*</span></label>
                                    <input type="text" placeholder="Numero de habitacion" class="form-control"
                                        id="numero_habitacion" name="numero_habitacion">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group has-info single-line">
                                    <label>Tipo de habitacion <span style="color:red;">*</span></label>
                                    <br>
                                    <select class="select col-lg-6" name="tipo_habitacion" id="tipo_habitacion"
                                        style="width:100%;">
                                        <option value="">Seleccione</option>
                                        <?php
												$sql = _query("SELECT * FROM tipo_cuarto WHERE deleted is NULL ORDER BY tipo ASC");
                                                while ($row = _fetch_array($sql))
                                                {
                                                    $id_tipo_cuarto = $row["id_tipo_cuarto"];
                                                    $tipo = $row["tipo"];
                                                    $cantidad = $row['cantidad'];
                                                    $resultado = $tipo.", para ".$cantidad." personas";
                                                    echo "<option value='".$id_tipo_cuarto."'>".$resultado."</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group has-info single-line">
                                    <label>Descripcion de la habitacion<span style="color:red;">*</span></label>
                                    <input type="text" placeholder="Descripcion del piso" class="form-control"
                                        id="descripcion" name="descripcion">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group has-info single-line">
                                    <label>Estado de habitacion <span style="color:red;">*</span></label>
                                    <br>
                                    <select class="select col-lg-6" name="estado_habitacion" id="estado_habitacion"
                                        style="width:100%;">
                                        <option value="">Seleccione</option>
                                        <?php
												$sql = _query("SELECT * FROM estado_cuarto WHERE deleted is NULL ORDER BY estado ASC");
                                                while ($row = _fetch_array($sql))
                                                {
                                                    $id_estado_cuarto = $row["id_estado_cuarto"];
                                                    $estado = $row["estado"];
                                                    echo "<option value='".$id_estado_cuarto."'>".$estado."</option>";
                                                }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group has-info single-line">
                                    <label>Precio por hora<span style="color:red;">*</span></label>
                                    <input type="text" placeholder="Precio por hora" class="form-control decimal"
                                        id="precio_por_hora" name="precio_por_hora">
                                </div>
                            </div>
                            <br>
                            <div class='col-lg-6'>

                            </div>
                            <br>
                            <div class='col-lg-6 d-flex justify-content-between align-items-center'>
                                <br>
                                <input type="submit" id="agregar_habitacion" style="float: right;"
                                    name="agregar_habitacion" value="Guardar" class="btn btn-primary m-t-n-xs" />
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
        echo "<script src='js/funciones/funciones_habitacion.js'></script>";
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
    $numero_piso = $_POST['numero_piso'];
    $numero_habitacion = $_POST['numero_habitacion'];
    $tipo_habitacion =  $_POST['tipo_habitacion'];
    $descripcion =  $_POST['descripcion'];
    $estado_habitacion =  $_POST['estado_habitacion'];
    $precio_por_hora =  $_POST['precio_por_hora'];
    $id_sucursal=$_SESSION["id_sucursal"];
    $sql = "SELECT cuartos.id_cuarto FROM cuartos WHERE cuartos.numero_cuarto = '$numero_habitacion' AND cuartos.id_piso_cuarto = '$numero_piso' AND cuartos.id_tipo_cuarto_cuarto = '$tipo_habitacion' AND cuartos.deleted is NULL";
    $sql_exis = _query($sql);
    $num_exis = _num_rows($sql_exis);
    if($num_exis > 0)
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Ya existe ese numero y tipo de habitacion registrado en ese piso!';
    }
    else
    {
        $table = 'cuartos';
        $form_data = array(
            'numero_cuarto' => $numero_habitacion,
            'descripcion' => $descripcion,
            'precio_por_hora' => $precio_por_hora,
            'id_piso_cuarto' => $numero_piso,
            'id_tipo_cuarto_cuarto' => $tipo_habitacion,
            'id_estado_cuarto_cuarto' =>  $estado_habitacion
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