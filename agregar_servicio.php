<?php
include_once "_core.php";
function initial() 
{
    $title='Agregar Servicio';
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
            <div class="ibox">
            <?php 
    		//permiso del script
            if ($links!='NOT' || $admin=='1' ){
            ?>
            <div class="ibox-title">
                <h3 style="color:#194160;"><i class="fa fa-money"></i> <b><?php echo $title;?></b></h3>
            </div>
            <div class="ibox-content">
                <form name="formulario_servicio" id="formulario_servicio" autocomplete='off'>
                    <div class="row"> 
                        <div class="col-md-12">
                            <div class="form-group has-info">
                                <label>Nombre del servicio <span style="color:red;">*</span></label> 
                                <input type="text" placeholder="Descripción" class="form-control" id="descripcion" name="descripcion">
                            </div>        
                        </div>
                        <!---<div class="col-md-12">
                            <div class="form-group has-info">
                                <label>Precio <?php echo "(".$moneda." <label class='badge badge-default'>".$simbolo."</label>)"; ?><span style="color:red;">*</span></label> 
                                <input type="text" placeholder="00.00" class="form-control numeric" id="precio" name="precio">
                            </div>        
                        </div>-->
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="insert">
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
echo "<script src='js/funciones/funciones_servicio.js'></script>";
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	
}

function insertar()
{

    $servicio=$_POST["descripcion"];
    //$precio=$_POST["precio"];

    $table = EXTERNAL.'.servicios';
    
    $form_data = array(	
    'servicio' => $servicio,
    //'precio' => $precio
    );   	
    $sql_exis = _query("SELECT * FROM ".EXTERNAL.".servicios WHERE servicio='$servicio'");
    $num_exis = _num_rows($sql_exis);
    if($num_exis==0)
    {
        $insertar = _insert($table,$form_data );
        if($insertar)
        {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Servicio ingresado correctamente';
            $xdatos['process']='insert';
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Servicio no pudo ser ingresado';
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Esta servicio ya fue ingresado';
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



