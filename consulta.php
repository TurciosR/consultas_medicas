<?php
//Cambios
include_once "_core.php";
function initial(){
    // Page setup
    $title = "Consulta";
    $_PAGE = array();
    $_PAGE['title'] = $title;
    $_PAGE['links'] = null;
    $_PAGE['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE['links'] .= '<link href="css/style.css" rel="stylesheet">';    
    $_PAGE['links'] .= '<link href="css/plugins/timepicki/timepicki.css" rel="stylesheet">';    

    include_once "header.php";
    include_once "main_menu.php";
     //permiso del script
    $id_user=$_SESSION["id_usuario"];
	$id_doctor=$_SESSION["id_doctor"];
	$admin=$_SESSION["admin"];
	
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row" id="div_consulta">
        <div class="col-lg-12">
            <div class="ibox">
                <?php 
            //permiso del script
            if ($links!='NOT' || $admin=='1' ){
            ?>
                
                <div class="ibox-title">
                    <h3 style="color:#194160;"><i class="fa fa-check"></i> <b><?php echo $title;?></b></h3>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-8" id="paciente_consulta">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class='text-success'>Paciente en consulta <a class="pull-right" id="reloadd"><i
                                                class="fa fa-refresh"> Actualizar</i></a></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="widget-content pre-scrollable">
                                        <table class="table  table-checkable datatable" id="table">
                                            <td style="text-align: center;" colspan="4"><b>SIN PACIENTES EN CONSULTA</b>
                                            </td>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4" id="paciente_espera">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class='text-success'>Pacientes en espera <label class="badge badge-info"
                                            style="margin-left: 15px; background: red;" id="count1"></label><a
                                            class="pull-right" id="reloadda"><i class="fa fa-refresh">
                                                Actualizar</i></a></h4>
                                </div>
                                <div class="panel-body">
                                    <div class="widget-content">
                                        <div class="row pre-scrollable widget-content">
                                            <ul class="list-group">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_doctor" value="<?php echo $id_doctor; ?>" id="id_doctor">
                    <!-- Modal Agregar Cita-->
                    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                            </div>
                        </div>
                    </div>
                    <a data-toggle="modal" data-target="#viewModal" data-refresh="true" id="display"></a>
                    <!-- Fin Modal Agregar Cita-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_consulta.js'></script>";
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}   
}
function buscar_dat($id_c)
{
    $admin = $_SESSION["admin"];
    $id_doctor = $_POST["id_d"];
    $now = date("Y-m-d");
    $script_esp = get_script_name($id_doctor);
    if($id_doctor>0)
    {
        $aux = _query("SELECT r.id, r.id_paciente FROM reserva_cita as r, cola_dia as c WHERE r.id_doctor='$id_doctor' AND r.estado = 4 AND r.fecha_cita='$now' AND r.id=c.id_cita AND c.prioridad>0 AND c.fecha ='$now'");
    }
    else if($admin)
    {
        $aux = _query("SELECT r.id, r.id_paciente FROM reserva_cita as r, cola_dia as c WHERE r.estado = 4 AND r.fecha_cita='$now' AND r.id=c.id_cita AND c.prioridad>0 AND c.fecha ='$now'");   
    }
    $dats_aux = _fetch_array($aux);
    $id = $dats_aux["id_paciente"];
    $id_cita = $dats_aux["id"];
    $sql="SELECT p.*, d.nombre_departamento, m.nombre_municipio FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id'";
    $result = _query($sql);
    $numm = _num_rows($result);
    $dato='';
    $xdatt=array();
    if($numm>0)
    {
        $row = _fetch_array($result);
        $nombre=$row['nombres'];
        $apellido = $row['apellidos'];
        $telefono1=$row["tel1"];
        $telefono2=$row["tel2"];
        if($telefono2 !="")
        {
            $telefono1 .= ", WS: ".$row["tel2"];
        }
        $email=$row["email"];
        $sexo = $row["sexo"];
        $fecha = ED($row["fecha_nacimiento"]); 
        $datos_fecha = explode("-", $fecha);
        $anio_nac  = $datos_fecha[2];
        $edad = date("Y") - $anio_nac;             
        $direccion = $row["direccion"].", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
        $padecimientos = $row["padecimientos"];
        $medicamentos = $row["medicamento_permanente"];
        $alergias = $row["alergias"];

        $dato='<tr>
            <td style="text-align: center;" colspan="4"><b>DATOS GENERALES</b></td>
        </tr>
        <tr>
            <td style="width: 12%;">Nombres:</td>
            <td style="width: 37%;">'.$nombre.'</td>
            <td style="width: 12%;">Apellidos:</td>
            <td style="width: 37%;">'.$apellido.'</td>
        </tr>
        <tr>
            <td>Edad:</td>
            <td>'.$edad.'</td>
            <td>Género:</td>
            <td>'.$sexo.'</td>
        </tr>
        <tr>
            <td>Dirección:</td>
            <td>'.$direccion.'</td>
            <td>Télefono:</td>
            <td>'.$telefono1.'</td>
        </tr>
        <tr>
            <td>Padecimientos:</td>
            <td>'.$padecimientos.'</td>
            <td>Alergias:</td>
            <td>'.$alergias.'</td>
        </tr>
        <tr>
            <td colspan="2">Medicamentos Permánetes:</td>
            <td colspan="2">'.$medicamentos.'</td>
        </tr>
        <tr>
            <td colspan="4"><a class="btn btn-primary pull-right realizar_evaluacion_btn" href="'.$script_esp.'?id='.$id_cita.'&acc=new">Realizar Consulta</a></td>
        </tr>';
        $admin=$_SESSION["admin"];
        $filename = "signos.php";
        $id_user=$_SESSION["id_usuario"];
        $links=permission_usr($id_user,$filename);
        if($links != 'NOT' || $admin == '1')
        {
            $sql2= _query("SELECT * FROM signos_vitales WHERE id_paciente ='$id' AND id_cita='$id_cita'");
            $num2 = _num_rows($sql2);
            if($num2 > 0){
                $datos =_fetch_array($sql2);
            $estatura = $datos["estatura"];
            $peso = $datos["peso"];
            $temperatura = $datos["temperatura"];
            $presion = $datos["presion"];
            $frecuencia_c = $datos["frecuencia_cardiaca"];
            $frecuencia_r = $datos["frecuencia_respiratoria"];
            $observaciones = $datos['observaciones'];
            $fecha_ev = ED($datos['fecha']);
            $hora_ev = hora($datos['hora']);
            $dato.='<tr>
                    <td style="text-align: center;" colspan="4"><b>EVALUACION PRELIMINAR</b></td>
                </tr>';
            if($num2>0)
            {
                $dato.='
                <tr>
                    <td>Estatura:</td>
                    <td>'.$estatura.' mt</td>
                    <td>Peso:</td>
                    <td>'.$peso.' lb</td>
                </tr>
                <tr>
                    <td>Temperatura:</td>
                    <td>'.$temperatura.' °C</td>
                    <td>Presión:</td>
                    <td>'.$presion.'</td>
                </tr>
                
                <tr>
                    <td>Fecha:</td>
                    <td>'.$fecha_ev.'</td>
                    <td>Hora:</td>
                    <td>'.$hora_ev.'</td>
                </tr>
                <tr>
                <td colspan="2">Observaciones:</td>
                <td colspan="2">'.$observaciones.'</td>
            </tr>
            <tr>
                <td colspan="4"><a class="btn btn-primary pull-right" data-toggle="modal" data-target="#viewModal" data-refresh="true" href="signos.php?id='.$id_cita.'">Repetir Evaluación</a></td>
            </tr>';  
            }
            else
            {
                $dato.='<tr>
                    <td colspan="4"><a class="btn btn-primary pull-right agregar_paciente_btn" data-toggle="modal" data-target="#viewModal" data-refresh="true" href="signos.php?id='.$id_cita.'">Agregar Evaluación</a></td>
                </tr>';
            }
            }
            
        }
    }    
    else
    {
        $dato='<tr>
            <td style="text-align: center;" colspan="4"><b>SIN PACIENTES EN CONSULTA</b></td>
        </tr>';   
    }
    $xdatt["table"] = $dato;
    $xdatt['typeinfo']="Success";
    echo json_encode($xdatt);
}
function lista()
{
    $admin = $_SESSION["admin"];
    $id_doctor = $_POST["id_d"];
    $now=date("Y-m-d");
    if($id_doctor>0)
    {
        $sql1="SELECT cd.id_cola, c.hora_cita, c.estado, c.id as id_cita, CONCAT(p.nombres,' ',p.apellidos) as paciente, es.descripcion as consultorio FROM reserva_cita as c, paciente as p, espacio as es, cola_dia as cd WHERE c.id_doctor='$id_doctor' AND c.id=cd.id_cita AND p.id_paciente=c.id_paciente AND es.id_espacio=c.id_espacio AND c.estado<6 AND cd.prioridad>0 AND cd.fecha='$now' AND c.fecha_cita='$now' ORDER BY cd.prioridad ASC";
    }
    else if($admin)
    {
        $sql1="SELECT cd.id_cola, c.hora_cita, c.estado, c.id as id_cita, CONCAT(p.nombres,' ',p.apellidos) as paciente, es.descripcion as consultorio FROM reserva_cita as c, paciente as p, espacio as es, cola_dia as cd WHERE c.id=cd.id_cita AND p.id_paciente=c.id_paciente AND es.id_espacio=c.id_espacio AND c.estado<6 AND cd.prioridad>0 AND cd.fecha='$now' AND c.fecha_cita='$now' ORDER BY cd.prioridad ASC";   
    }
    $xdato['list'] = '';
    $query1 = _query($sql1);
    $num1 = _num_rows($query1);   
    while($row = _fetch_array($query1))
    {
        if($row["estado"]==4)
        {
            $xdato["list"].="<li class='list-group-item bg-green' id='$row[id_cita]'>".hora($row["hora_cita"])." - ".$row["paciente"]." (En consulta)</li>";
        }
        else
        {
            $xdato["list"].="<li class='list-group-item' id='$row[id_cita]'>".hora($row["hora_cita"])." - ".$row["paciente"]."</li>";
        }
    } 
    $xdato["num"] = $num1;
    $xdato["typeinfo"] = "Success";
    echo json_encode($xdato);
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
            case 'buscar':
                buscar_dat($_POST["id"]);
                break;
            case 'lista':
                lista();
                break;
        } 
    }           
}
?>