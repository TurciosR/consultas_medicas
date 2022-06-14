<?php
include_once "_core.php";
function initial() 
{
    $title='Agregar Examen de Ultrasonografia';
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
    $_PAGE ['links'] .= '<link href="css/plugins/tour/bootstrap-tour.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
    $_PAGE ['links'].=  '<link href="css/estilos.css", rel="stylesheet">';
   $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
    include_once "header.php";
    include_once "main_menu.php";	

  	//permiso del script
  	$id_user=$_SESSION["id_usuario"];
  	$admin=$_SESSION["admin"];
  	$uri = $_SERVER['SCRIPT_NAME'];
  	$filename=get_name_script($uri);
  	$links=permission_usr($id_user,$filename);

  	$id_cita=$_GET['id_cita'];
    $lugar=$_GET['lugar'];

  	$sql_recerva_cita="SELECT rc.examenes_ultra, rc.dx_ultra FROM reserva_cita AS rc WHERE rc.id=$id_cita";
  	$query_recerva_cita=_query($sql_recerva_cita);
  	$url_examen='';
    $dx_ultra='';
  	if(_num_rows($query_recerva_cita)>0){
  		$row_examen=_fetch_array($query_recerva_cita);
  		$url_examen=$row_examen['examenes_ultra'];
        $dx_ultra=$row_examen['dx_ultra'];
  	}
?>
<div class="wrapper wrapper-content  animated fadeInRight" >
    <div class="row">
        <div class="col-lg-12" >
            <div class="ibox">
            <?php 
    		//permiso del script
            if ($links!='NOT' || $admin=='1' ){
            ?>
            <div class="ibox-title">
                <h3 style="color:#194160;"><i class="fa fa-user"></i> <b><?php echo $title;?></b></h3> (Los campos marcados con <span style="color:red;">*</span> son requeridos)
            </div>
            <div class="ibox-content">
                <form name="form_ultra" id="form_ultra" autocomplete='off' enctype="multipart/form-data">
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Imagen de la Ultra</label>
                                <input type="file" name="ultra" id="ultra" class="file" data-preview-file-type="image">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <img id="view_ultra" src="<?php echo $url_examen ?>" style='width: 100px; height: 100px;'>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-info">
                                <label>Dx</label>
                                <textarea class="form-control" cols="5" rows="2" id="dx_ultra" name="dx_ultra">
                                    <?= $dx_ultra ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                            	<input type="hidden" name="process" id="process" value="guardar_ultra">
                            	<input type="hidden" name="id_cita" id="id_cita" value="<?php echo $id_cita ?>">
                                <input type="submit" id="guardar_cita" name="guardar_cita" value="Guardar" class="btn btn-primary m-t-n-xs pull-right" />  
                                <input type="hidden" name="lugar" id="lugar" value="<?php echo $lugar ?>">
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
echo "<script src='js/funciones/funciones_agregar_ultra.js'></script>";
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	
}

function guardar_ultra()
{	require_once "class.upload.php";
	$xdatos=[];
	$id_cita=$_POST['id_cita'];
    $dx_ultra=$_POST['dx_ultra'];


	//echo $_FILES['ultra']['name'];
	if($_FILES['ultra']['name']!=''){
        
		$subir=new \Verot\Upload\Upload($_FILES['ultra'],'es_ES');
		$pref=uniqid().'_';
		$subir->file_force_extension=false;
		$subir->no_script=false;
		$subir->file_name_body_pre=$pref;
		$subir->Process('img/ultra/');
        //echo $subir->log; 
		if($subir->processed){

            
			$cuerpo=quitar_tildes($subir->file_src_name_body);
			//echo $cuerpo;
			$cuerpo=trim($cuerpo);
			$url_ultra='img/ultra/'.$pref.$cuerpo.".".$subir->file_src_name_ext;
			$tabla='reserva_cita';
			$form_data=[
				'examenes_ultra'=>$url_ultra,
                'dx_ultra'=>$dx_ultra
			];

			$where_clause="id=".$id_cita;

			$sql_examen_ultra_anterior="SELECT rc.examenes_ultra FROM reserva_cita AS rc  WHERE id=$id_cita";
			$query_examen_ultra_anterior=_query($sql_examen_ultra_anterior);
			$row_examen_ultra_anterior=_fetch_array($query_examen_ultra_anterior);
			$url_examen_utra_anterior=$row_examen_ultra_anterior['examenes_ultra'];
			if($url_examen_utra_anterior!=''){
				unlink($url_examen_utra_anterior);//elimina la imagen anterior antes de registrar el dato nuevo
			}

			$update=_update($tabla, $form_data, $where_clause);
            
            //echo $update;
			if($update){
				$xdatos['typeinfo']='Success';
				$xdatos['msg']='ultrasonografia subida';
				$xdatos['process']='edit';
			}else{
				$xdatos['typeinfo']="Error";
				$xdatos['msg']='Error al guardar la imagen';
			}
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
            case 'guardar_ultra':
            	guardar_ultra();
                break;	
        } 
    }			
}
?>
