<?php
include_once "_core.php";
function initial() 
{
    $title='Agregar Medicamento';
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
    $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
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
                <h3 style="color:#194160;"><i class="fa fa-medkit"></i> <b><?php echo $title;?></b></h3> (Los campos marcados con <span style="color:red;">*</span> son requeridos)
            </div>
            <div class="ibox-content">
                <form name="formulario_medicamento" id="formulario_medicamento" autocomplete='off'>
                    <div class="row">
                        <div class="form-group has-info col-md-6">
                            <label>Descripción <span style="color:red;">*</span></label>
                            <input type="text" placeholder="Descripción" class="form-control" id="descripcion" name="descripcion">
                        </div>
                        <div class="form-group has-info col-md-6">
                              <label>Principio Activo <span style="color:red;">*</span></label>
                              <input type="text" placeholder="Principio Activo" class="form-control" id="principio" name="principio">
                        </div>
                    </div>
                    <div class="row">                
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Presentación <span style="color:red;">*</span></label> 
                                <input type="text" placeholder="Presentacion" class="form-control" id="presentacion" name="presentacion">
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Laboratorio <span style="color:red;">*</span></label>
                                <input type="text"  placeholder="Laboratorio" class="form-control" id="laboratorio" name="laboratorio">
                            </div>
                        </div>          
                    </div>
                    <div class="row">                
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Precio $</label> 
                                <input type="text" placeholder="00.00" class="form-control numeric" id="precio" name="precio">
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Forma Farmacéutica </label>
                                <input type="text"  placeholder="Forma" class="form-control" id="forma" name="forma">
                            </div>
                        </div>          
                    </div>
                    <div class="row">     
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Imagen </label>
                                <input type="file" class="file" data-preview-file-type="image" id="imagen" name="imagen">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class='checkbox i-checks'><br>
                                <label id='frentex'>
                                    <input type='checkbox' id='vacuna' name='vacuna'> <strong> Inyectable</strong>
                                </label>
                            </div>
                            <input type='hidden' id='vacun' name='vacun' value="0">
                        </div>
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
echo "<script src='js/funciones/funciones_medicamento.js'></script>";
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	
}
function insertar()
{
    require_once 'class.upload.php';
    if ($_FILES["imagen"]["name"]!="")
    { 
        $foo = new \Verot\Upload\Upload($_FILES['imagen'],'es_ES');
        if ($foo->uploaded) 
        {
            $pref = uniqid()."_";
            $foo->file_force_extension = false;
            $foo->no_script = false;
            $foo->file_name_body_pre = $pref;
           // save uploaded image with no changes
           $foo->Process('img/medicamentos/');
           if ($foo->processed) 
           {
                $descripcion=$_POST["descripcion"];
                $principio=$_POST["principio"];
                $precio=$_POST["precio"];
                $forma=$_POST["forma"];
                $presentacion=$_POST["presentacion"];
                $laboratorio=$_POST["laboratorio"];
                $vacuna=$_POST["vacun"];
                $cuerpo=quitar_tildes($foo->file_src_name_body);
                $cuerpo=trim($cuerpo);
                $img = 'img/medicamentos/'.$pref.$cuerpo.".".$foo->file_src_name_ext;
               
                $table = 'medicamento';
                
                $form_data = array( 
                'descripcion' => $descripcion,
                'laboratorio' => $laboratorio,
                'precio' => $precio,
                'forma' => $forma,
                'principio' => $principio,
                'presentacion' => $presentacion,
                'vacuna' => $vacuna,
                'img' => $img
                );      

                $sql_exis = _query("SELECT * FROM medicamento WHERE descripcion='$descripcion' AND presentacion='$presentacion' AND laboratorio='$laboratorio'");
                $num_exis = _num_rows($sql_exis);
                if($num_exis==0)
                {
                    $insertar = _insert($table,$form_data );
                    if($insertar)
                    {
                        $xdatos['typeinfo']='Success';
                        $xdatos['msg']='Medicamento ingresado correctamente';
                        $xdatos['process']='insert';
                    }
                    else
                    {
                        $xdatos['typeinfo']='Error';
                        $xdatos['msg']='Medicamento no pudo ser ingresado';
                    }
                }
                else
                {
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='Este Medicamento ya fue ingresado';
                }    
            }
            else
            {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Medicamento no pudo ser ingresado';   
            }
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Medicamento no pudo ser ingresado';
        }
    }
    else
    {
        $descripcion=$_POST["descripcion"];
        $principio=$_POST["principio"];
        $precio=$_POST["precio"];
        $forma=$_POST["forma"];
        $presentacion=$_POST["presentacion"];
        $laboratorio=$_POST["laboratorio"];
        $vacuna=$_POST["vacun"];

        $table = 'medicamento';
        
        $form_data = array( 
        'descripcion' => $descripcion,
        'laboratorio' => $laboratorio,
        'precio' => $precio,
        'forma' => $forma,
        'principio' => $principio,
        'presentacion' => $presentacion,
        'vacuna' => $vacuna
        );      

        $sql_exis = _query("SELECT * FROM medicamento WHERE descripcion='$descripcion' AND presentacion='$presentacion' AND laboratorio='$laboratorio'");
        $num_exis = _num_rows($sql_exis);
        if($num_exis==0)
        {
            $insertar = _insert($table,$form_data );
            if($insertar)
            {
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Medicamento ingresado correctamente';
                $xdatos['process']='insert';
            }
            else
            {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Medicamento no pudo ser ingresado';
                
            }
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Este Medicamento ya fue ingresado';
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



