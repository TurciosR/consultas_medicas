<?php
include_once "_core.php";
function initial() 
{
    $title='Editar Medicamento';
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

    //Request Id
    $id_medicamento=$_REQUEST["id_medicamento"];
    
    //Get data from db
    $sql=_query("SELECT * FROM medicamento WHERE id_medicamento='$id_medicamento'");
    $row = _fetch_array($sql);
    $descripcion=$row['descripcion'];
    $principio=$row['principio'];
    $precio=$row["precio"];
    $presentacion=$row["presentacion"];
    $laboratorio = $row["laboratorio"];
    $forma = $row["forma"];//.", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
    $vacuna = $row["vacuna"];
    $img = "img/medicamentos/img.png";
    if($row["img"] !="")
    {
        $img = $row["img"];
    }
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
                            <label>Nombre <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">
                        </div>
                        <div class="form-group has-info col-md-6">
                              <label>Principio Activo <span style="color:red;">*</span></label>
                              <input type="text" class="form-control" id="principio" name="principio" value="<?php echo $principio; ?>">
                        </div>
                    </div>
                    <div class="row">       
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Presentación <span style="color:red;">*</span></label> 
                                <input type="text" class="form-control" id="presentacion" name="presentacion" value="<?php echo $presentacion; ?>">
                            </div>        
                        </div>          
                        <div class="col-md-6">                                
                            <div class="form-group has-info">
                                <label>Laboratorio <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="laboratorio" name="laboratorio" value="<?php echo $laboratorio; ?>">
                            </div>       
                        </div>
                    </div>  
                    <div class="row">       
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Precio </label> 
                                <input type="text" class="form-control" id="precio" name="precio" value="<?php echo $precio; ?>">
                            </div>        
                        </div>          
                        <div class="col-md-6">                                
                            <div class="form-group has-info">
                                <label>Forma Farmacéutica </label>
                                <input type="text" class="form-control" id="forma" name="forma" value="<?php echo $forma; ?>">
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
                                    <input type='checkbox' id='vacuna' name='vacuna' <?php if($vacuna) echo " checked "; ?> > <strong> Inyectable</strong>
                                </label>
                            </div>
                            <input type='hidden' id='vacun' name='vacun' value="<?php echo $vacuna;?>">
                        </div>
                    </div>
                    <div class="row">     
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <img src='<?php echo $img; ?>' style="width: 260px; height: 160px;">
                            </div>
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="edit">
                                <input type="hidden" name="id_medicamento" id="id_medicamento" value="<?php echo $id_medicamento; ?>">
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
function editar()
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
                $id_medicamento=$_POST["id_medicamento"];
                $query = _query("SELECT img FROM medicamento WHERE id_medicamento='$id_medicamento'");
                $result = _fetch_array($query);
                $urlb=$result["img"];
                if($urlb!="")
                {
                    unlink($urlb);
                } 
                $descripcion=$_POST["descripcion"];
                $principio=$_POST["principio"];
                $precio=$_POST["precio"];
                $presentacion=$_POST["presentacion"];
                $laboratorio=$_POST["laboratorio"];
                $vacuna=$_POST["vacun"];
                $forma=$_POST["forma"];
                $cuerpo=quitar_tildes($foo->file_src_name_body);
                $cuerpo=trim($cuerpo);
                $img = 'img/medicamentos/'.$pref.$cuerpo.".".$foo->file_src_name_ext;

                $table = 'medicamento';
                
                $form_data = array( 
                'descripcion' => $descripcion,
                'laboratorio' => $laboratorio,
                'precio' => $precio,
                'principio' => $principio,
                'forma' => $forma,
                'presentacion' => $presentacion,
                'img' => $img,
                'vacuna' => $vacuna
                );      
                $where_clause = "id_medicamento = '".$id_medicamento."'";
                $update = _update($table,$form_data, $where_clause);
                if($update)
                {
                    $xdatos['typeinfo']='Success';
                    $xdatos['msg']='Medicamento editado correctamente';
                    $xdatos['process']='insert';
                }
                else
                {
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='Medicamento no pudo ser editado';
                }
            }
            else
            {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Medicamento no pudo ser editado'.$foo->error;
            }    
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Medicamento no pudo ser editado'.$foo->error;   
        }
    }    
    else
    {
        $id_medicamento=$_POST["id_medicamento"];
        $descripcion=$_POST["descripcion"];
        $principio=$_POST["principio"];
        $precio=$_POST["precio"];
        $presentacion=$_POST["presentacion"];
        $laboratorio=$_POST["laboratorio"];
        $forma=$_POST["forma"];
        $vacuna = $_POST["vacun"];

        $table = 'medicamento';
        
        $form_data = array( 
        'descripcion' => $descripcion,
        'laboratorio' => $laboratorio,
        'precio' => $precio,
        'principio' => $principio,
        'forma' => $forma,
        'presentacion' => $presentacion,
        'vacuna' => $vacuna
        );      
        $where_clause = "id_medicamento = '".$id_medicamento."'";
        $update = _update($table,$form_data, $where_clause);
        if($update)
        {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Medicamento editado correctamente';
            $xdatos['process']='insert';
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Medicamento no pudo ser editado';
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
            case 'edit':
                editar();
                break;
        } 
    }           
}
?>