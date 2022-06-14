<?php
include_once "_core.php";
function initial()
{
    $title = 'Editar Piso';
    $_PAGE = array ();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
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

    $id_habitacion = $_REQUEST['id_habitacion'];
    $sql = "SELECT *, estado_cuarto.estado as 'est', tipo_cuarto.tipo as 'tipo_c', pisos.numero_piso as 'numero_pi' FROM cuartos INNER JOIN estado_cuarto on estado_cuarto.id_estado_cuarto = cuartos.id_estado_cuarto_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto  INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto where id_cuarto = '$id_habitacion'";
    $consulta = _query($sql);
    $rowx = _fetch_array($consulta);
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
                                            <label>Numero de piso  <span style="color:red;">*</span></label>
                                            <input type="text" placeholder="Estado de habitacion" class="form-control" id="piso" name="piso" value="<?php echo $rowx['numero_pi']; ?>" readonly>
                                            <input type="hidden" name="numero_piso" id="numero_piso" value="<?php echo $rowx['id_piso_cuarto']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group has-info single-line">
                                            <label>Numero de habitacion  <span style="color:red;">*</span></label>
                                            <input type="text" placeholder="Numero de habitacion" class="form-control" id="numero_habitacion" name="numero_habitacion" value="<?php echo $rowx['numero_cuarto']; ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group has-info single-line">
                                            <label>Tipo de habitacion  <span style="color:red;">*</span></label>
                                            <input type="text" placeholder="Tipo de habitacion" class="form-control" id="tipo" name="tipo" value="<?php echo $rowx['tipo_c']; ?>" readonly> 
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group has-info single-line">
                                            <label>Descripcion de la habitacion<span style="color:red;">*</span></label>
                                            <input type="text" placeholder="Descripcion del piso" class="form-control" id="descripcion" name="descripcion" value="<?php echo $rowx['descripcion']; ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group has-info single-line">
                                            <label>Estado de habitacion  <span style="color:red;">*</span></label>
                                            <input type="text" placeholder="Estado de habitacion" class="form-control" id="estado" name="estado" value="<?php echo $rowx['est']; ?>" readonly>                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group has-info single-line">
                                            <label>Precio por hora<span style="color:red;">*</span></label>
                                            <input type="text" placeholder="Precio por hora" class="form-control decimal" id="precio_por_hora" name="precio_por_hora" value="<?php echo $rowx['precio_por_hora']; ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <div class='col-lg-6'>
                                    
                                    </div>
                                    <br>
                                    <div class='col-lg-6 d-flex justify-content-between align-items-center'>
                                    <br>
                                        <input type="submit" id="agregar_habitacion" style="float: right;" name="agregar_habitacion" value="Guardar" class="btn btn-primary m-t-n-xs" />
                                    </div>
                                </div>                               
                                
                                <input type="hidden" name="process" id="process" value="edited"><br>
                                <input type="hidden" name="id_habitacion" id='id_habitacion' value="<?php echo $id_habitacion; ?>">
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

function editar()
{
    $id_habitacion = $_POST['id_habitacion'];
    $numero_piso = $_POST['numero_piso'];
    $numero_habitacion = $_POST['numero_habitacion'];
    $descripcion =  $_POST['descripcion'];
    $precio_por_hora =  $_POST['precio_por_hora'];
    $id_sucursal = $_SESSION["id_sucursal"];
    $sql = "SELECT id_cuarto FROM cuartos where cuartos.id_cuarto != '$id_habitacion' AND cuartos.numero_cuarto = '$numero_habitacion' AND cuartos.id_piso_cuarto = '$numero_piso' ";
    $query = _query($sql);
    $numero = _num_rows($query);
    if($numero > 0){
        $xdatos['typeinfo'] ="Error";
        $xdatos['msg'] = 'Ya se encuentra un registro con esos datos, no se puede modificar!';
    }
    else{
        $tabla = 'cuartos';
        $form_data = array(
            'numero_cuarto' => $numero_habitacion,
            'descripcion' => $descripcion,
            'precio_por_hora' => $precio_por_hora
        );
        $where = " id_cuarto = '$id_habitacion'";
        $update = _update($tabla,$form_data, $where);
        if($update) {
            $xdatos['typeinfo'] = 'Success';
            $xdatos['msg'] = 'Registro actualizado con exito!';
        }
        else{
            $xdatos['typeinfo'] = 'Error';
            $xdatos['msg'] ='Registro no se pudo actualizar!';
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
            case 'edited':
                editar();
            break;
        }
    }
}
?>
