<?php
/**
 * This file is part of the cmfpacientes.
 * 
 * (c) Open Solution Systems <operaciones@tumundolaboral.com.sv>
 * 
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */
//cambios
include_once "_core.php";
function initial() 
{
    $title1='Control de Citas';
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
    $id_doctor = $_SESSION['id_doctor'];

?>
<style type="text/css">
.dual-list .list-group {
    margin-top: 8px;
}

.list-left li,
.list-right li {
    cursor: pointer;
}

.list-arrows {
    padding-top: 100px;
}

.list-arrows button {
    margin-bottom: 5px;
    margin-top: -115px;
}
</style>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <?php 
            //permiso del script
            if ($links!='NOT' || $admin=='1' ){
            ?>
                <div class="ibox-title">
                    <h3 style="color:#194160;"><i class="fa fa-check-square-o"></i> <b><?php echo $title1;?></b></h3>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-6">
                            <!--Display in modal-->
                            <div class="form-group col-lg-3">
                                <button class="text-success" id="agregar_ultra" style="font-size: 15px;" disabled><i class="fa fa-plus">
                                        Agregar examen ultra</i></button>
                            </div>
                            <div class="form-group col-lg-3">
                                <button class="text-success" id="add_datos_fisicos" style="font-size: 15px;" disabled><i class="fa fa-plus">
                                        Agregar datos fisicos</i></button>
                                        <input type="hidden" name="id_cola" id="id_cola" value="">
                            </div>
                            <div class="form-group col-lg-2">
                                <a class="text-success" id="add_fast" style="font-size: 15px;"><i class="fa fa-plus">
                                        Nueva</i></a>
                            </div>
                            <div class="form-group col-lg-1">
                                <a class="text-success" href="admin_cita.php" style="font-size: 15px;"><i
                                        class="fa fa-list"> Ver Citas</i></a>
                            </div>
                            <div class="form-group col-lg-3">
                                <a class="text-success" id="reloadd" style="font-size: 15px;"><i class="fa fa-refresh">
                                        Recargar</i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row"><br>
                        <div class="col-lg-8">
                            <div class="dual-list list-left col-md-5">
                                <div class="well">
                                    <div class="row" id="paciente_cola">
                                        <div class="col-md-12"><label>Pacientes Citados </label><label
                                                class="badge badge-danger" style="margin-left: 15px;"
                                                id="count1"></label></div>
                                        <div class="col-md-1">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="input-group col-md-12">
                                                <input type="text" name="SearchDualList" class="form-control"
                                                    placeholder="Nombre del paciente" />
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                        </div>
                                    </div><br>
                                    <div class="row pre-scrollable">
                                        <ul class="list-group" id="citados">
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="list-arrows col-md-2 text-center">
                                <button class="btn btn-default btn-sm move-left">
                                    <span class="fa fa-chevron-left"></span>
                                </button>

                                <button class="btn btn-default btn-sm move-right">
                                    <span class="fa fa-chevron-right"></span>
                                </button>
                            </div>

                            <div class="dual-list list-right col-md-5">
                                <div class="well">
                                    <div class="row" id="paciente_citado">
                                        <div class="col-md-12"><label>Pacientes en espera </label><label
                                                class="badge badge-danger" style="margin-left: 15px;"
                                                id="count2"></label></div>
                                        <div class="col-md-1">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="input-group col-md-12">
                                                <input type="text" name="SearchDualList" class="form-control"
                                                    placeholder="Nombre del paciente" />
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                        </div>
                                    </div><br>
                                    <div class="row pre-scrollable">
                                        <ul class="list-group" id="espera">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-center">
                            <div id="calendar" class="col-centered">
                            </div>
                            <input type="hidden" id="fechaoo" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                    </div>
                </div>
                <input type="hidden" id="now" value="<?php echo date("d-m-Y"); ?>">
                <!-- Modal Agregar Cita-->
                <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content modal-md">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Agregar Cita</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" id="add_cita" autocomplete="off">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label>Paciente <span style="color:red;">*</span></label>
                                                <input type="text" placeholder="Nombre del paciente"
                                                    class="form-control" id="nombre" name="nombre">
                                                <label id="paciente"></label>
                                                <input type="hidden" name="id_paciente" id="id_paciente" value="">
                                                <input type="hidden" name="process" id="process" value="insert">
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
                                                <input type="text" class="form-control datepicker" id="fecha"
                                                    name="fecha" value="<?php echo date("d-m-Y");?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Hora <span style="color:red;">*</span></label>
                                                <input type="text" placeholder="00:00" class="form-control timepicker"
                                                    id="hora" name="hora">
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
                <!-- Modal Editar Cita-->
                <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content modal-sm">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Editar Turno</h4>
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
                                    <div class="row" hidden>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label>Fecha <span style="color:red;">*</span></label>
                                                <input type="text" class="form-control datepicker" id="fechae"
                                                    name="fechae">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Hora <span style="color:red;">*</span></label>
                                                <input type="text" placeholder="00:00" class="form-control timepicker"
                                                    id="horae" name="horae">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>Estado <span style="color:red;">*</span></label>
                                                <select name="estado2" class="form-control" id="estado2">
                                                    <option value="">Seleccione</option>
                                                    <?php 
                                            $sqlp = "SELECT * FROM estado_cita WHERE id_estado >3";
                                            $resultp=_query($sqlp);
                                            while($pco = _fetch_array($resultp))
                                            {
                                                echo "<option style='color:".$pco["color"].";' value='".$pco["id_estado"]."'>&#9724; ".ucfirst(strtolower($pco["descripcion"]))."</option>";
                                            }
                                        ?>
                                                </select>
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

                <!-- Modal Editar Cita-->
                <div class="modal fade" id="ModalEdit1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content modal-sm">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Editar Cita</h4>
                            </div>
                            <div class="modal-body">
                                <form class="form-horizontal" id="edit_cita1">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>Paciente : </label>
                                                <label id="pacientee1"></label>
                                                <input type="hidden" name="process" id="process" value="edit1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>Fecha: </label>
                                                <input type="text" name="fechae1" class="form-control datepicker"
                                                    id="fechae1">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>Hora : </label>
                                                <input type="text" name="horae1" class="form-control timepicker"
                                                    id="horae1">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_cita1" id="id_cita1">
                                    <input type="hidden" name="id_doctor" id="id_doctor" value="<?php echo $id_doctor; ?>">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <a class="btn btn-default" data-dismiss="modal" id="btn_ce1">Cerrar</a>
                                <a class="btn btn-primary" id="btn_edit1">Guardar</a>
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
echo "<script src='js/funciones/funciones_dashs.js'></script>";
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
    $now = date("Y-m-d");
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
        'fr' => ''
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
    $id_cola=$_POST["id_cita"];
    $sql_cita = _query("SELECT id_cita FROM cola_dia WHERE id_cola='$id_cola'");
    $datos_cita = _fetch_array($sql_cita);
    $id_cita = $datos_cita["id_cita"];
    $estado=$_POST["estado2"];
    /*$hora=$_POST["horae"];
    $fechae=MD($_POST["fechae"]);
    list($dato, $letra) = explode(" ", $hora);
    list($h, $m) = explode(":", $dato);
    if($letra=="PM")
    {
        if($h<12)
        {
            $h+=12;
        }
    }
    $hora = "$h:$m:00";*/
    $now = date("Y-m-d");
    $aux = _query("SELECT * FROM reserva_cita WHERE id = '$id_cita'");
    $dats_aux =_fetch_array($aux);
    $fecha = $dats_aux["fecha_cita"];
    $hora_db = $dats_aux["hora_cita"];
    $doctor = $dats_aux["id_doctor"];
    $esta_ex = $dats_aux["estado"];
    $pendiente = true;
    $xdatos=[];
    if($estado == 7 && $esta_ex<4)
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='No puede finalizar esta cita, por que aun no se ha realizado';
    }
    else
    {
        if($estado==4)
        {
            $sql_val = _query("SELECT * FROM reserva_cita WHERE id_doctor='$doctor' AND fecha_cita='$fecha' AND estado = '4'");
            if($esta_ex < 3)
            {
                $pendiente  = false;
            }  
            $actualiza = true;   
        }
        else
        {
            $sql_val = _query("SELECT * FROM reserva_cita WHERE id_doctor='$doctor' AND fecha_cita='$fecha' AND estado = '20'");    
            $datos_edit  = array(
                'prioridad' => -1
                );
            $actualiza = _update("cola_dia",$datos_edit, "id_cola='".$id_cola."'");
        }
        $num_val = _num_rows($sql_val);
        
        if(compararFechas("-",ED($fecha),ED($now))>=0)
        {
            if($num_val==0)
            {
                if($pendiente)
                {
                    $table = 'reserva_cita';    
                    
                    /*$form_data = array( 
                    'hora_cita' => $hora,
                    'fecha_cita' => $fechae,
                    'estado' => $estado
                    );*/   
                    $form_data = array( 
                    'estado' => $estado
                    );   

                    $where_clause = "id = '".$id_cita."'";   
                    $update = _update($table,$form_data,$where_clause);
                    if($update && $actualiza)
                    {
                        $xdatos['typeinfo']='Success';
                        $xdatos['msg']='Estado editado correctamente';
                        //Aqui es donde debo de actualizar el estado de la 
                        //recepcion ya que una vez que se finalize la consulta tiene que pasar a recepcion a cancelar
                        //actualizando el estado de la recepcion...
                        if($estado==7){//7 es el id del estado finalizado
                            $sql_recepcion_cita="SELECT id_recepcion FROM `recepcion_cita` WHERE id_reserva_cita=$id_cita";
                            $query_recepcion_cita=_query($sql_recepcion_cita);
                            $row_recepcion_cita=_fetch_array($query_recepcion_cita);
                            $id_recepcion=$row_recepcion_cita['id_recepcion'];
                            //una vez obtenida la recepcion se tiene que actualizar el estado de la recepcion
                            $table="recepcion";
                            $form_data_re=[
                                'id_estado_recepcion'=>7
                            ];
                            $extra_where='id_recepcion='.$id_recepcion;
                            $update_recepcion=_update($table, $form_data_re, $extra_where);
                            if($update_recepcion){
                                $xdatos['msgre']='Estado de recepcion actualizada correctamente.';
                            }
                        }


                        if($estado == 4)
                        {
                            $xdatos['id']= $id_cola;
                        }
                        else
                        {
                            $xdatos['id']=0;
                        }
                        $xdatos['process']='insert';
                    }
                    else
                    {
                        $xdatos['typeinfo']='Error';
                        $xdatos['msg']='Estado no pudo ser editado';
                    }
                }
                else
                {
                    $xdatos['typeinfo']='Error';
                    $xdatos['msg']='El paciente aun no se encuentra en espera';   
                }
            } 
            else
            {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Ya existe un paciente en consulta, espere a que esta finalice';   
            }
        }
        else
        {
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Esta fecha es anterior a la fecha actual';    
        }
    }
    echo json_encode($xdatos);
}
function editar1()
{
    $id_cita=$_POST["id_cita1"];
    $hora=$_POST["horae1"];
    $fecha=MD($_POST["fechae1"]);
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
function update()
{
    $accion = $_POST["acc"];
    $id_cola = $_POST["id"];
    if($id_cola !="undefined")
    {
        $sql_cita = _query("SELECT id_cita, id_doctor FROM cola_dia WHERE id_cola='$id_cola'");
        $datos_cita = _fetch_array($sql_cita);
        $id_cita = $datos_cita["id_cita"];
        $id_doctor = $datos_cita["id_doctor"];

        $table = 'cola_dia';
        $table2 = 'reserva_cita';

        $now = date("Y-m-d");
        if($accion == "insert")
        {
            $sql_exis = _query("SELECT max(prioridad) as prioridad FROM cola_dia WHERE fecha='$now' AND id_doctor='$id_doctor'");
            $result_exis = _fetch_array($sql_exis);
            $prioridad = $result_exis["prioridad"] + 1;
            $estado = 3;
        }
        else
        {
            $prioridad = 0;
            $estado = 1;
        }
        $form_data = array( 
        'prioridad' => $prioridad
        );   
        $form_data2 = array( 
        'estado' => $estado
        );   

        $where_clause = "id_cola = '".$id_cola."'";   
        $where_clause2 = "id = '".$id_cita."'";   
        $update = _update($table,$form_data,$where_clause);
        $update2 = _update($table2,$form_data2,$where_clause2);
        if($update && $update2)
        {
            $xdatos['typeinfo']='Success';
            $xdatos['process']='insert';
        }
        else
        {
            $xdatos['typeinfo']='Error';
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
    }       
    echo json_encode($xdatos);
}
function lista($tipo = "")
{
    $now = date("Y-m-d");
    $id_doctor = $_POST['id_doctor'];
    if($id_doctor == 0){
        $query=_query("SELECT c.id, c.id_doctor FROM reserva_cita as c, estado_cita as e WHERE e.id_estado = c.estado AND c.estado<6 AND c.fecha_cita='$now' ORDER BY hora_cita ASC");
        $num = _num_rows($query);
    }
    else{
        $query=_query("SELECT c.id, c.id_doctor FROM reserva_cita as c, estado_cita as e WHERE e.id_estado = c.estado AND c.estado<6 AND c.fecha_cita='$now' AND c.id_doctor = '$id_doctor' ORDER BY hora_cita ASC");
        $num = _num_rows($query);
    }
    
    $i=0;
    _begin();
    if($num>=0)
    {
        while($row = _fetch_array($query))
        {
            $query_b = _query("SELECT * FROM cola_dia WHERE fecha ='$now' AND id_cita='$row[id]' AND id_doctor='$row[id_doctor]'");
            $num_b = _num_rows($query_b);
            if($num_b==0)
            {
                $form_data = array(
                    'id_cita' => $row["id"],
                    'id_doctor' => $row["id_doctor"],
                    'fecha' => $now,
                    'prioridad' => 0,
                );
                $table = "cola_dia";
                $inser = _insert($table, $form_data);
                if($inser)
                {
                    $i++;
                }
            }
            else
            {
                $i++;
            }
        }
    }  
    if($num == $i)
    {
        _commit();
    }
    else
    {
        _rollback();
    }
    if($id_doctor == 0){
        $sql1="SELECT cd.id_cola, c.hora_cita, c.estado, 
        CONCAT(p.nombres,' ',p.apellidos) as paciente, 
        CONCAT(d.nombres,' ',d.apellidos) as doctor, 
        es.descripcion as consultorio FROM doctor as d, 
        reserva_cita as c, paciente as p, espacio as es,
         cola_dia as cd WHERE c.id=cd.id_cita AND 
         p.id_paciente=c.id_paciente AND 
         d.id_doctor=c.id_doctor AND 
         es.id_espacio=c.id_espacio AND 
         cd.prioridad=0 AND cd.fecha='$now' AND 
         c.fecha_cita='$now' ORDER BY c.hora_cita ASC";

        $query1 = _query($sql1);
        $num1 = _num_rows($query1);
    
        $sql2="SELECT cd.id_cola, c.hora_cita, c.estado, 
        CONCAT(p.nombres,' ',p.apellidos) as paciente,
         CONCAT(d.nombres,' ',d.apellidos) as doctor, 
         es.descripcion as consultorio 
         FROM doctor as d, reserva_cita as c, paciente as p, 
         espacio as es, cola_dia as cd WHERE c.id=cd.id_cita AND 
         p.id_paciente=c.id_paciente AND d.id_doctor=c.id_doctor AND
         es.id_espacio=c.id_espacio AND cd.prioridad>0 AND cd.fecha='$now' AND 
         c.fecha_cita='$now' ORDER BY cd.prioridad ASC";

        $query2 = _query($sql2);
        $num2 = _num_rows($query2);
    }
    else{
        $sql1="SELECT cd.id_cola, c.hora_cita, c.estado, 
        CONCAT(p.nombres,' ',p.apellidos) as paciente, 
        CONCAT(d.nombres,' ',d.apellidos) as doctor, 
        es.descripcion as consultorio FROM 
        doctor as d, reserva_cita as c, paciente as p, espacio as es, cola_dia as cd
        WHERE c.id=cd.id_cita AND 
        p.id_paciente=c.id_paciente AND 
        d.id_doctor=c.id_doctor AND 
        es.id_espacio=c.id_espacio AND 
        cd.prioridad=0 AND cd.fecha='$now' AND 
        c.fecha_cita='$now' AND c.id_doctor='$id_doctor' ORDER BY c.hora_cita ASC";
        $query1 = _query($sql1);
        $num1 = _num_rows($query1);
    
        $sql2="SELECT cd.id_cola, c.hora_cita, c.estado, 
        CONCAT(p.nombres,' ',p.apellidos) as paciente, 
        CONCAT(d.nombres,' ',d.apellidos) as doctor, 
        es.descripcion as consultorio FROM doctor as d, 
        reserva_cita as c, paciente as p, espacio as es, cola_dia as cd 
        WHERE c.id=cd.id_cita AND p.id_paciente=c.id_paciente AND 
        d.id_doctor=c.id_doctor AND es.id_espacio=c.id_espacio AND 
        cd.prioridad>0 AND cd.fecha='$now' AND c.fecha_cita='$now' AND 
        c.id_doctor='$id_doctor' ORDER BY cd.prioridad ASC";
        $query2 = _query($sql2);
        $num2 = _num_rows($query2);
    }
    
    $citados = "";
    $espera = "";
    while($row = _fetch_array($query1))
    {
        if($tipo !="")
        {
           $citados.= "<li class='list-group-item' id='$row[id_cola]' estado='$row[estado]'>".hora($row["hora_cita"])." - ".$row["paciente"]." - ".$row["doctor"]." </li>"; 
        }
        else
        {
            $citados.= "<li class='list-group-item bg-info' id='$row[id_cola]' estado='$row[estado]'>".hora($row["hora_cita"])." - ".$row["paciente"]." - ".$row["doctor"]." </li>";
        }
        
    } 
    while($row = _fetch_array($query2))
    {
        if($row["estado"] == 4)
        {
            $espera.= "<li class='list-group-item bg-green' id='$row[id_cola]' estado='$row[estado]'>".hora($row["hora_cita"])." - ".$row["paciente"]." - ".$row["doctor"]." (En consulta)  </li>";
        }
        else
        {
            $espera.= "<li class='list-group-item bg-naranja' id='$row[id_cola]' estado='$row[estado]'>".hora($row["hora_cita"])." - ".$row["paciente"]." - ".$row["doctor"]." </li>";
        }
    } 
    $xdatos["espera"]=$espera;
    $xdatos["citados"]=$citados;
    $xdatos["num1"]=$num1;
    $xdatos["num2"]=$num2;
    echo json_encode($xdatos);
}

function obtener_idcita_para_ingresar_datos_previos(){
    $id_cola=$_POST['id_cola'];
    $sql_cola="SELECT id_cita FROM cola_dia WHERE id_cola=$id_cola";
    $query_cola=_query($sql_cola);

    $xdatos=array();
    $id_cita='';
    if(_num_rows($query_cola)>0){
        while($row_cola=_fetch_array($query_cola)){
            $id_cita=$row_cola['id_cita'];
        }
        $xdatos['id_cita']=$id_cita;
    }
    echo json_encode($xdatos);
}

if(!isset($_POST['tipo']))
{
    $tipo = ""; 
}
else
{
    $tipo = $_POST['tipo'];
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
            case 'edit1':
                editar1();
                break;
            case 'update':
                update();
                break;
            case 'list':
                lista($tipo);
                break;
            case 'datos_previos':
                obtener_idcita_para_ingresar_datos_previos();
                break;
                
        } 
    }           
}
?>