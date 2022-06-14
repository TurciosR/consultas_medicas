<?php
include_once "_core.php";
function initial() {
	$title='Constancias';
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
    $_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
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
<div class="row wrapper border-bottom white-bg page-heading"></div>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <?php
               	 		if ($links!='NOT' || $admin=='1' ){
               		?>
                <div class="ibox-title">
                    <h3 style="color:#194160;"><i class="fa fa-file-pdf-o"></i> <b><?php echo $title;?></b></h3>
                </div>
                <div class="ibox-content">
                    <div class="col-lg-12" id="forma_pago">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class='text-success'>Tipo de Constancia</h3>
                            </div>
                            <div class="panel-body">
								<div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label>Tipo</label>
                                        <select class="form-control select" name="forma" id="forma">
                                            <option value="">Seleccione</option>
                                            <option value="medica">Constancia Médica</option>
                                            <option value="muerte">Constancia de Defunción</option>
                                            <option value="examenes">Constancia de examenes medicos</option>
                                            <option value="carta">Carta de recomendacion</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="div_constancia_medica">
                                	<!--constancia medica-->
                                    <form action="" method="GET" id="form_medica">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Fecha de expedición:</label>
                                                        <input type='text' class='datepicker form-control' id='fecha_medica' name='fecha_medica'
                                                            value='<?php echo date('d-m-Y');?>'>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Nombre del paciente</label>
                                                        <input type="text" id="nombre_paciente_medica" name="nombre_paciente_medica"
                                                            class="form-control"
                                                            placeholder="Ingrese los nombres del paciente">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>EDAD</label>
                                                        <input type="text" name="edad_medica" class="form-control" id="edad_medica">
                                                    </div>  
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Direccion Paciente</label>
                                                        <textarea name="direccion_paciente_medica" id="direccion_paciente_medica" cols="30" rows="1" class="form-control">
                                                        
                                                        </textarea>
                                                    </div>  
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Padecimiento</label>
                                                        <input type="text" name="padecimiento_medica" id="padecimiento_medica" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Reposo (Días)</label>
                                                        <input type="text" name="reposo_medica" id="reposo_medica" class="form-control numeric">
                                                    </div>                                              
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                        <label>DUI:</label>
                                                        <input type="text" placeholder="00000000-0" class="form-control" name="dui_medica" id="dui_medica">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                        <label>Numero de expediente</label>
                                                        <input type="text" class="form-control" name="expediente_medica" id="expediente_medica">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group has-info pull-right">
                                                        <button id="btn_constancia_medica" type="submit" class="btn btn-primary">Generar Constancia</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--Constancia de difucion-->
                                <div class="row" id="div_constancia_muerte">
                                    <form action="" method="GET" id="form_muerte">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Fecha de fallecimiento</label>
                                                        <input type='text' class='datepicker form-control' id='fecha_muerte' name='fecha_muerte' value='<?php echo date('d-m-Y');?>'>   
                                                    </div>                          
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Nombre del fallecido</label>
                                                        <input type="text" name="nombre_muerto" id="nombre_muerto" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>DUI:</label>
                                                        <input type="text" placeholder="00000000-0" class="form-control" id="dui_fallecido" name="dui_fallecido">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Edad</label>
                                                        <input type="text" class="form-control" id="edad_muerto" name="edad_muerto">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Antecedentes medicos</label>
                                                        <input type="text" class="form-control" name="antecedentes_medicos" id="antecedentes_medicos">                                                  
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Direccion Paciente</label>
                                                        <textarea name="direccion_paciente_muerte" id="direccion_paciente_muerte" cols="30" rows="1" class="form-control">
                                                        
                                                        </textarea>
                                                    </div>  
                                                </div>          
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Hora de fallecimiento</label>
                                                        <input type="text" class="form-control" name="hora_muerte" id="hora_muerte">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Causa de Fallecimiento</label>
                                                    <input type="text" class="form-control" name="causa_muerte" id="causa_muerte">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group has-info pull-right">
                                                        <button id="btn_constancia_muerte" type="submit" class="btn btn-primary">Generar Carta de Difucion</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--Constancia de examenes-->
                                <div class="row" id="div_examenes">
                                    <form action="" id="form_examenes">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Nombre paciente</label>
                                                        <input type="text" class="form-control" name="paciente_examenes" id="paciente_examenes">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Edad</label>
                                                        <input type="text" class="form-control" id="edad_examenes" name="edad_examenes">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group has-info">
                                                        <label>Fecha de examenes</label>
                                                        <input type='text' class='datepicker form-control' id='fecha_examenes' name='fecha_examenes' value='<?php echo date('d-m-Y');?>'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                        <label>Dui</label>
                                                        <input type="text" class="form-control" id='dui_examenes' placeholder="00000000-0" name="dui_examenes">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                        <label>Direccion Paciente</label>
                                                        <textarea name="direccion_paciente_examenes" id="direccion_paciente_examenes" cols="30" rows="2" class="form-control">
                                                        
                                                        </textarea>
                                                    </div>  
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                        <label>Expediente clinico</label>
                                                        <input type="text" class="form-control" id="expediente_examenes" name="expediente_examenes">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                        <label>EXAMEN  GENERAL DE HECES</label>
                                                        <textarea name="examen_orina" id="examen_orina" cols="30" rows="1" class="form-control">
                                                            
                                                        </textarea>                                                 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                        <label>EXAMEN GENERAL DE ORINA</label>
                                                        <textarea name="examen_heses" id="examen_heses" cols="30" rows="1" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>RAYOS X TORAX:</label>
                                                    <textarea class="form-control" name="examen_rayos_x" id="examen_rayos_x" cols="30" rows="1"></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group has-info pull-right">
                                                        <button id="btn_constancia_examen" type="submit" class="btn btn-primary">Generar constancia de examenes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </form>

                                </div>
                                <!--Constancia de recomendacion-->
                                <div class="row" id="div_carta_recomendacion">
                                    <form action="" id="form_carta" method="GET">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                        <label>Nombre de recomendado</label>
                                                        <input type="text" class="form-control" id="nombre_recomendado" name="nombre_recomendado">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                       <label>Edad:</label>
                                                       <input type="text" class="form-control" name="edad_recomendado" id="edad_recomendado">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                       <label>Dui</label>  
                                                       <input type="text" placeholder="00000000-0" class="form-control" name="dui_recomendado" id="dui_recomendado">                                            
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group has-info">
                                                        <label>Direcion</label>
                                                        <textarea name="direccion_recomendado" id="direccion_recomendado" cols="30" rows="1" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group has-info pull-right">
                                                        <button id="btn_carta_recomendacion" type="submit" class="btn btn-primary">Generar Carta de recomendacion</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <input type="hidden" name="process" id="process" value="insert">
                    <div class="title-action" id='botones'>
                        <a id="btn_fin" name="btn_fin" class="btn btn-primary"><i class="fa fa-check"></i> Regresar</a>
                    </div>
       		</div>
       	
        </div>
        <div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
            aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- MODAL PARA DETALLE-->
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_crear_constancia.js'></script>";

} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
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
	}
}
}
?>