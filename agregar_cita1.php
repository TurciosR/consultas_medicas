<?php
include_once "_core.php";
function initial() 
{
    $title='Agregar Cita';
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
    $_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';
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
    $id_doctor = $_SESSION['id_doctor'];
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
                <h3 style="color:#194160;"><i class="fa fa-stethoscope"></i> <b><?php echo $title;?></b></h3> (Los campos marcados con <span style="color:red;">*</span> son requeridos)
            </div>
            <div class="ibox-content">
                <form name="formulario_cita" id="formulario_cita" autocomplete='off'>
                    <div class="row">
                        <div class="form-group has-info col-md-6">
                            <label>Paciente <span style="color:red;">*</span></label>
                            <input type="text" placeholder="Nombre del paciente" class="form-control" id="nombre" name="nombre">
                            <label id="paciente"></label>
                            <input type="hidden" id="id_paciente" value="">
                        </div>
                        <div class="form-group has-info col-md-6">
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
                                    echo "<select class='form-control select' id='doctor' name='doctor'";
                                    if($num<2)
                                    {
                                        echo " disabled ";
                                    }
                                    echo ">
                                    <option value=''>Seleccione</option>";
                                    while($pco = _fetch_array($resultp))
                                    {
                                        echo "<option value='".$pco["id_doctor"]."'";
                                         if($num<2)
                                        {
                                            echo " selected ";
                                        }
                                        echo ">".$pco["nombres"]." ".$pco["apellidos"]."</option>";
                                    }
                                    echo "</select>"; 
                                ?>
                        </div>
                    </div>
                    <div class="row">                
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Fecha <span style="color:red;">*</span></label> 
                                <input type="text" class="form-control datepicker" id="fecha" name="fecha" value="<?php echo date("d-m-Y"); ?>">
                            </div>        
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Hora <span style="color:red;">*</span></label> 
                                <input type="text" placeholder="HH:mm" class="form-control timepicker" id="hora" name="hora">
                            </div>        
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Consultorio <span style="color:red;">*</span></label>
                                <?php 
                                    $sqlp = "SELECT * FROM espacio";
                                    $resultp=_query($sqlp);
                                    $num = _num_rows($resultp);
                                    echo "<select class='form-control select' id='espacio' name='espacio'";
                                    if($num<2)
                                    {
                                        echo " disabled ";
                                    }
                                    echo ">
                                    <option value=''>Seleccione</option>";
                                    while($pco = _fetch_array($resultp))
                                    {
                                        echo "<option value='".$pco["id_espacio"]."'";
                                         if($num<2)
                                        {
                                            echo " selected ";
                                        }
                                        echo ">".$pco["descripcion"]."</option>";
                                    }
                                    echo "</select>"; 
                                ?>
                            </div>    
                        </div>  
                    </div> 
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group has-info">
                                <label>Motivo de la cita <span style="color:red;">*</span></label>
                                <textarea class="form-control" name="motivo" id="motivo"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group has-info">
                                <label>Observaciones</label>
                                <textarea class="form-control" name="obsevaciones" id="observaciones"></textarea>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-actions has-info">
                                <input type="hidden" id="process" value="insert">
                                <input type="hidden" id="estado" value="1">
                                <a id="submit1" class="btn btn-primary m-t-n-xs pull-right">Guardar</a>  
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
echo "<script src='js/funciones/funciones_cita.js'></script>";
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
    $fecha=MD($_POST["fecha"]);
    $hora=$_POST["hora"];
    $espacio=$_POST["espacio"];
    $estado=$_POST["estado"];
    $observaciones=$_POST["observaciones"];
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
    $now = date("Y-m -d");
    $form_data = array(	
        'fecha_cita' => $fecha,
        'hora_cita' => $hora,
        'id_paciente' => $id_paciente,
        'id_doctor' => $doctor,
        'id_espacio' => $espacio,
        'id_usuario' => $usuario,
        'motivo_consulta' => $motivo,
        'observaciones' => $observaciones,
        'estado' => $estado,
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
    if(compararFechas("-", ED($fecha), ED($now))>=0)
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
        $xdatos['msg']='La fecha es menor que la fecha actual';
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