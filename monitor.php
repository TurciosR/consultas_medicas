<?php
include_once "_core.php";
function initial() 
{
    $title1='Monitor';
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
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

    include_once "header.php";
    //include_once "main_menu.php";	

  	//permiso del script
  	//$id_user=$_SESSION["id_usuario"];
  	//$admin=$_SESSION["admin"];
  	//$uri = $_SERVER['SCRIPT_NAME'];
  	//$filename=get_name_script($uri);
  	//$links=permission_usr($id_user,$filename);
    $id_doctor = $_SESSION['id_doctor'];

?>
<div id="container">

<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
            <?php 
    		//permiso del script
            //if ($links!='NOT' || $admin=='1' ){
            if (true){
            ?>
            <div class="ibox-content">
                <div class="row">
                <div class="col-lg-12">    
                     <h1 style="font-weight: bold;">Siguiente</h1>
                     <div  id="sig">
                     </div>
                </div>        
                </div>
                <div class="row">
                <div class="col-lg-12">    
                    <h1 style="font-weight: bold;">En espera</h1>  
                    <table class="table table-borderd table-condensed">
                      <thead class="thead-inverse">  
                        <tr style='font-size:26px; font-weight:bold;' class="bg-info">
                            <th>Paciente</th>
                            <th>Consultorio</th>
                            <th>Médico</th>
                            <th>Turno</th>
                            <!--<th>Estado</th>-->
                        </tr>
                      </thead>
                      <tbody id="fill">
                        
                      </tbody>  
                    </table>
                </div>    
                <input type="hidden" name="id_doctor" id="id_doctor" value="<?php echo $id_doctor; ?>">
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="js/jquery-2.1.1.js"></script>
<?php
//include_once ("footer.php");
echo "<script src='js/funciones/funciones_monitor.js'></script>";
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	
}
function sig()
{
    $now = date("Y-m-d");
    $id_doctor = $_POST['id'];
    if($id_doctor == 0){
        $sqln = _query("SELECT cd.prioridad as turno, concat(p.nombres,' ',p.apellidos) as paciente, concat(d.nombres,' ',d.apellidos) as doctor, e.descripcion as espacio, es.descripcion as estado, es.color  FROM cola_dia as cd, paciente as p, doctor as d, espacio as e, reserva_cita as r, estado_cita as es WHERE cd.fecha='$now' AND r.id=cd.id_cita AND p.id_paciente=r.id_paciente AND d.id_doctor=r.id_doctor  AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.estado<4 AND cd.prioridad>0 ORDER BY cd.prioridad ASC");
        $n = _num_rows($sqln);
        $n-=1;
        $sql = _query("SELECT cd.prioridad as turno, concat(p.nombres,' ',p.apellidos) as paciente, concat(d.nombres,' ',d.apellidos) as doctor, e.descripcion as espacio, es.descripcion as estado, es.color  FROM cola_dia as cd, paciente as p, doctor as d, espacio as e, reserva_cita as r, estado_cita as es WHERE cd.fecha='$now' AND r.id=cd.id_cita AND p.id_paciente=r.id_paciente AND d.id_doctor=r.id_doctor  AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.estado<4 AND cd.prioridad>0 ORDER BY cd.prioridad ASC LIMIT 0,1");
        $table = "<div class='alert alert-info' style='font-size:26px; font-weight:bold;'>";
        while($row = _fetch_array($sql))
        { 
            $table.=$row["paciente"]." - ".$row["espacio"]." - ".$row["doctor"]."";
        }
        $table .= "</div>";
        $xdata["sig"] = $table;
        $xdata["id"] = $id_doctor;
        echo json_encode($xdata);
    }
    else{
        $sqld=_query("SELECT min(id_doctor) as id_doctor FROM cola_dia WHERE fecha='$now' AND id_doctor = $id_doctor");
        $id_doctor = _fetch_array($sqld)["id_doctor"];
        if($id_doctor<1)
        {
            $sqld=_query("SELECT min(id_doctor) as id_doctor FROM cola_dia WHERE fecha='$now' AND id_doctor>0");
            $id_doctor = _fetch_array($sqld)["id_doctor"];
        }
        $sqln = _query("SELECT cd.prioridad as turno, concat(p.nombres,' ',p.apellidos) as paciente, concat(d.nombres,' ',d.apellidos) as doctor, e.descripcion as espacio, es.descripcion as estado, es.color  FROM cola_dia as cd, paciente as p, doctor as d, espacio as e, reserva_cita as r, estado_cita as es WHERE cd.fecha='$now' AND r.id=cd.id_cita AND p.id_paciente=r.id_paciente AND d.id_doctor=r.id_doctor AND cd.id_doctor='$id_doctor' AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.estado<4 AND cd.prioridad>0 ORDER BY cd.prioridad ASC");
        $n = _num_rows($sqln);
        $n-=1;
        $sql = _query("SELECT cd.prioridad as turno, concat(p.nombres,' ',p.apellidos) as paciente, concat(d.nombres,' ',d.apellidos) as doctor, e.descripcion as espacio, es.descripcion as estado, es.color  FROM cola_dia as cd, paciente as p, doctor as d, espacio as e, reserva_cita as r, estado_cita as es WHERE cd.fecha='$now' AND r.id=cd.id_cita AND p.id_paciente=r.id_paciente AND d.id_doctor=r.id_doctor AND cd.id_doctor='$id_doctor' AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.estado<4 AND cd.prioridad>0 ORDER BY cd.prioridad ASC LIMIT 0,1");
        $table = "<div class='alert alert-info' style='font-size:26px; font-weight:bold;'>";
        while($row = _fetch_array($sql))
        { 
            $table.=$row["paciente"]." - ".$row["espacio"]." - ".$row["doctor"]."";
        }
        $table .= "</div>";
        $xdata["sig"] = $table;
        if($id_doctor < 1)
        {
            $id_doctor = 0;
        }
        $xdata["id"] = $id_doctor;
        echo json_encode($xdata);
    }
    
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
            case 'siguiente':
                sig();
                break;
        } 
    }     
}
?>

