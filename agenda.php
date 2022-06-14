<?php
include_once "_core.php";
function initial() 
{
    $title1='Agenda';
  	$_PAGE = array ();
  	$_PAGE ['title'] = $title1;
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
    $_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">';
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

    $sql = _query("SELECT timer FROM empresa WHERE id_empresa=1");
    $row = _fetch_array($sql);
    $valor = $row["timer"];
    $id_doctor = $_SESSION['id_doctor'];
    $now = date("Y-m-d");
    $query=_query("SELECT id FROM reserva_cita WHERE fecha_cita<'$now' AND estado<6");
    $num = _num_rows($query);
    $i=0;
    _begin();
    if($num>0)
    {
        while($row = _fetch_array($query))
        {
            $form_data = array(
                'estado' => 6
            );
            $table = "reserva_cita";
            $where = "id='".$row["id"]."'";
            $inser = _update($table, $form_data,$where);
            if($inser)
            {
                $i++;
            }
        }
    }  
    if($num ==$i)
    {
        _commit();
    }
    else
    {
        _rollback();
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
                <h3 style="color:#194160;"><i class="fa fa-calendar"></i> <b><?php echo $title1;?></b></h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-6 text-center">
                        <div id="calendar" class="col-centered">
                        </div>
                        <input type="hidden" id="fechaoo" value="<?php echo date("Y-m-d"); ?>">
                    </div>
                    <div class="col-lg-6">
                        <!--Display in modal-->
                        <div class="form-group col-lg-4">
                        <a class="btn btn-info" id="add_fast"><i class="fa fa-plus"> Nueva</i></a>
                        </div>
                        <div class="form-group col-lg-4">
                        <a class="btn btn-info" href="admin_cita.php"><i class="fa fa-list"> Ver Todo</i></a>
                        </div>
                        <div class="form-group col-lg-4">
                        <a class="btn btn-info" id="reloadd"><i class="fa fa-refresh"> Recargar</i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8"><br>
                    <span style='color:#FF2222; '>&#9724; PENDIENTE</span>
                    <span style='color:#FF9900; margin-left: 45px;'>&#9724; EN ESPERA </span>
                    <span style='color:#009900; margin-left: 45px;'>&#9724; EN CONSULTA </span>
                    <span style='color:#3386FF; margin-left: 45px;'>&#9724; CONTROL </span>
                    </div>
                    <div class="col-lg-2">
                        <label>Tiempo de recargo (min)</label>
                    </div>
                    <div class="col-lg-2">
                        <select class="form-control" id="timer">
                            <option value="0" <?php if($valor=="0"){ echo " selected "; }?>>Manual</option>
                            <option value="5" <?php if($valor=="5"){ echo " selected "; }?>>5</option>
                            <option value="10" <?php if($valor=="10"){ echo " selected "; }?>>10</option>
                            <option value="15" <?php if($valor=="15"){ echo " selected "; }?>>15</option>
                            <option value="20" <?php if($valor=="20"){ echo " selected "; }?>>20</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Modal Agregar Cita-->
            <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content modal-md">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Agregar Cita</h4>
                    </div>
                    <div class="modal-body"> 
                        <form class="form-horizontal" id="add_cita" autocomplete="off">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label>Paciente <span style="color:red;">*</span></label>
                                    <input type="text" placeholder="Nombre del paciente" class="form-control" id="nombre" name="nombre">
                                    <label id="paciente"></label>
                                    <input type="hidden" name="id_paciente" id="id_paciente" value="">
                                    <input type="hidden" name="process" id="process" value="insert">
                                </div>
                                <div class="col-md-6">
                                    <label>Médico <span style="color:red;">*</span></label>
                                    <?php 
                                    if($id_doctor == 0){
                                        $sqlp = "SELECT * FROM doctor";
                                    }
                                    else{
                                        $sqlp = "SELECT * FROM doctor WHERE id_doctor='$id_doctor'";
                                    }
                                    $resultp=_query($sqlp);
                                    $num = _num_rows($resultp);
                                    echo "<input type='hidden' id='doctores' value='".$num."'>";
                                    if($num<2)
                                    {
                                        echo "<select class='form-control select' id='doctoraaa' name='doctoraaa' style='width:100%;'";
                                        echo " disabled ";
                                    }
                                    else
                                    {
                                        echo "<select class='form-control select' id='doctor' name='doctor' style='width:100%;'";
                                    }
                                    echo ">
                                    <option value=''>Seleccione</option>";
                                    $id_doctor_db = 0;
                                    while($pco = _fetch_array($resultp))
                                    {
                                        echo "<option value='".$pco["id_doctor"]."'";
                                         if($num<2)
                                        {
                                            echo " selected ";
                                            $id_doctor_db = $pco["id_doctor"];
                                        }
                                        echo ">".$pco["nombres"]." ".$pco["apellidos"]."</option>";
                                    }
                                    echo "</select>"; 
                                    if($num<2)
                                    {
                                        echo "<input type='hidden' name='doctor' value='".$id_doctor_db."'>";
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="form-group">            
                                <div class="col-md-6">
                                    <label>Fecha <span style="color:red;">*</span></label> 
                                    <input type="text" class="form-control datepicker" id="fecha" name="fecha">
                                </div>        
                                <div class="col-md-6">
                                    <label>Hora <span style="color:red;">*</span></label> 
                                    <input type="text" placeholder="00:00" class="form-control timepicker" id="hora" name="hora">
                                </div>        
                            </div>
                        </div>
                        <div class="row">      
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label>Consultorio <span style="color:red;">*</span></label>
                                    <?php 
                                    $sqlp = "SELECT * FROM espacio";
                                    $resultp=_query($sqlp);
                                    $num = _num_rows($resultp);
                                    echo "<input type='hidden' id='espacios' value='".$num."'>";
                                    if($num<2)
                                    {
                                        echo "<select class='form-control select' id='espacioaa' name='espacioaa' style='width:100%;'";
                                        echo " disabled ";
                                    }
                                    else
                                    {
                                        echo "<select class='form-control select' id='espacio' name='espacio' style='width:100%;'";
                                    }
                                    echo ">
                                    <option value=''>Seleccione</option>";
                                    $id_espacio_db = 0;
                                    while($pco = _fetch_array($resultp))
                                    {
                                        echo "<option value='".$pco["id_espacio"]."'";
                                         if($num<2)
                                        {
                                            echo " selected ";
                                            $id_espacio_db = $pco["id_espacio"];
                                        }
                                        echo ">".$pco["descripcion"]."</option>";
                                    }
                                    echo "</select>"; 
                                    if($num<2)
                                    {
                                        echo "<input type='hidden' name='espacio' value='".$id_espacio_db."'>";
                                    }
                                ?>
                                </div>    
                            </div>    
                        </div>
                        <div class="row">      
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Motivo de la consulta <span style="color:red;">*</span></label>
                                    <input type="text" name="motivo" id="motivo" class="form-control">
                                </div>    
                            </div>    
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal" id="btn_ca">Cerrar</a>
                        <a class="btn btn-primary" id="btn_add">Guardar</a>
                    </div>
                    </div>
                </div>
            </div>
            <!-- Fin Modal Agregar Cita-->

            <!-- Modal Editar Cita-->
            <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                <div class="modal-content modal-sm">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Editar Cita</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="edit_cita">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Paciente : </label>
                                    <label id="pacientee"></label>
                                    <input type="hidden" name="process" id="process" value="edit">
                                </div>
                            </div>
                        </div>
                        <div class="row">    
                            <div class="form-group">            
                                <div class="col-md-12">
                                    <label>Fecha: </label> 
                                    <input type="text" name="fechae" class="form-control datepicker" id="fechae">
                                </div>     
                            </div>
                            <div class="form-group">   
                                <div class="col-md-12">
                                    <label>Hora : </label> 
                                    <input type="text" name="horae" class="form-control timepicker" id="horae">
                                </div>        
                            </div>
                        </div>
                        <input type="hidden" name="id_cita" id="id_cita">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal" id="btn_ce">Cerrar</a>
                        <a class="btn btn-primary" id="btn_edit">Guardar</a>
                    </div>
                    </div>
                </div>
            </div>
            <!-- FIN Modal Editar Cita-->
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_agenda.js'></script>";
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	
}
function insertar()
{
    $id_paciente=$_POST["id_paciente"];
    $doctor=$_POST["doctor"];
    $now = date("Y-m-d");
    $fecha=MD($_POST["fecha"]);
    $hora=$_POST["hora"];
    $espacio=$_POST["espacio"];
    $estado=1;
    $motivo=trim($_POST["motivo"]);
    list($dato, $letra) = explode(" ", $hora);
    list($h, $m) = explode(":", $dato);
    if($letra=="PM")
    {
        if($h<12)
        {
            $h+=12;
        }
    }
    $hora = "$h:$m:00";
    $table = 'reserva_cita';
    $usuario = $_SESSION["id_usuario"];

    $form_data = array( 
    'fecha_cita' => $fecha,
    'hora_cita' => $hora,
    'id_paciente' => $id_paciente,
    'id_doctor' => $doctor,
    'id_espacio' => $espacio,
    'id_usuario' => $usuario,
    'motivo_consulta' => $motivo,
    'estado' => $estado,
    'observaciones' => '',
    'diagnostico' => '',
    'examen' => '',
    'medicamento' => '',
    't_o' => '',
    'ta' => '',
    'p' => '',
    'peso' => '',
    'fr'=> ''
    );      
    $sql_exis = _query("SELECT * FROM reserva_cita WHERE id_paciente='$id_paciente' AND id_doctor='$doctor' AND fecha_cita='$fecha' AND hora_cita='$hora'");
    $num_exis = _num_rows($sql_exis);
    if(compararFechas("-",ED($fecha),ED($now))>=0)
    {
        if($num_exis==0)
        {
            $insertar = _insert($table,$form_data );
            if($insertar)
            {
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Cita ingresada correctamente';
                $xdatos['process']='insert';
            }
            else
            {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Cita no pudo ser ingresada';
            }
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Este Cita ya fue ingresada';
        }    
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Esta fecha es anterior a la fecha actual';
    }
    echo json_encode($xdatos);
}
function editar()
{
    $id_cita=$_POST["id_cita"];
    $hora=$_POST["horae"];
    $fecha=MD($_POST["fechae"]);
    list($dato, $letra) = explode(" ", $hora);
    list($h, $m) = explode(":", $dato);
    if($letra=="PM")
    {
        if($h<12)
        {
            $h+=12;
        }
    }
    $hora = "$h:$m:00";
    $sql_exis = _query("SELECT fecha_cita, hora_cita, estado FROM reserva_cita WHERE id='$id_cita'");
    $datos_exis = _fetch_array($sql_exis);
    $fecha_db = $datos_exis["fecha_cita"];
    $hora_db = $datos_exis["hora_cita"];
    $estado_db = $datos_exis["estado"];
    if($fecha_db == $fecha && $hora_db == $hora)
    {
        $estado = $estado_db;
    }
    else
    {
        $estado = 1;
    }
    $table = 'reserva_cita';
    $now = date("Y-m-d");
    $form_data = array( 
    'fecha_cita' => $fecha,
    'hora_cita' => $hora,
    'estado' => $estado
    );   

    $where_clause = "id = '".$id_cita."'";   
    if(compararFechas("-",ED($fecha),ED($now))>=0)
    {
        $update = _update($table,$form_data,$where_clause);
        if($update)
        {
            $xdatos['typeinfo']='Success';
            $xdatos['msg']='Cita editada correctamente';
            $xdatos['process']='insert';
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Cita no pudo ser editada';
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Esta fecha es anterior a la fecha actual';
    }
    echo json_encode($xdatos);
}
function update_timer($valor)
{
   $table = 'empresa';
    
    $form_data = array( 
    'timer' => $valor
    );   

    $where_clause = "id_empresa = 1";   
    $update = _update($table,$form_data,$where_clause);
    if($update)
    {
        $xdatos['typeinfo']='Success';
        $xdatos['process']='insert';
    }
    else
    {
        $xdatos['typeinfo']='Error';
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
            case 'edit':
                editar();
                break;
            case 'timer':
                update_timer($_POST["value"]);
                break;
        } 
    }			
}
?>

