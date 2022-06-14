<?php
include_once "_core.php";
function initial() 
{
    $title='Editar Consultorio';
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

    //Request Id
    $id_espacio=$_REQUEST["id_espacio"];
    
    //Get data from db
    $sql=_query("SELECT * FROM espacio WHERE id_espacio='$id_espacio'");
    $row = _fetch_array($sql);
    $descripcion = $row["descripcion"];//.", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
    $observaciones = $row["observaciones"];//.", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
            <?php 
            //permiso del script
            if ($links!='NOT' || $admin=='1' ){
            ?>
            <div class="ibox-title">
                <h3 style="color:#194160;"><i class="fa fa-hospital-o"></i> <b><?php echo $title;?></b></h3>
            </div>
            <div class="ibox-content">
                <form name="formulario_espacio" id="formulario_espacio" autocomplete='off'>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-info">
                                <label>Descripción <span style="color:red;">*</span></label> 
                                <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">
                            </div>        
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-info">
                                <label>Observaciones </label> 
                                <textarea class="form-control" id="observaciones" name="observaciones"><?php echo $observaciones; ?></textarea>
                            </div>        
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="edit">
                                <input type="hidden" name="id_espacio" id="id_espacio" value="<?php echo $id_espacio; ?>">
                                <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs pull-right"/>  
                            </div>
                        </div>
                    </div>      
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_espacio.js'></script>";
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}   
}

function editar()
{
    $id_espacio=$_POST["id_espacio"];
    $descripcion=$_POST["descripcion"];
    $observaciones=$_POST["observaciones"];

    $table = 'espacio';
    
    $form_data = array( 
    'descripcion' => $descripcion,
    'observaciones' => $observaciones
    );      
    $where_clause = "id_espacio = '".$id_espacio."'";
    $update = _update($table,$form_data, $where_clause);
    if($update)
    {
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Consultorio editado correctamente';
        $xdatos['process']='insert';
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Consultorio no pudo ser editado';
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
            case 'edit':
                editar();
                break;
        } 
    }           
}
?>



