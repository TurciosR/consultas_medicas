<?php
	include ("_core.php");
    // Page setup
    $id_habitacion = $_REQUEST['id_habitacion'];
$sql1="SELECT cuartos.id_cuarto, cuartos.numero_cuarto, cuartos.descripcion as 'descripcion_cuarto', cuartos.precio_por_hora, pisos.numero_piso, pisos.descripcion as 'descripcion_piso', tipo_cuarto.tipo, tipo_cuarto.descripcion as 'descripcion_tipo_cuarto', tipo_cuarto.cantidad, estado_cuarto.estado, estado_cuarto.descripcion as 'descripcion_estado_cuarto' FROM cuartos INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto INNER JOIN estado_cuarto on estado_cuarto.id_estado_cuarto = cuartos.id_estado_cuarto_cuarto WHERE cuartos.id_cuarto = '$id_habitacion'";
$consulta1 = _query($sql1);
$row1 = _fetch_array($consulta1);
$id_cuarto = $row1['id_cuarto'];
$numero_cuarto = $row1['numero_cuarto'];
$descripcion_cuarto = $row1['descripcion_cuarto'];
$precio_por_hora = $row1['precio_por_hora'];
$numero_piso = $row1['numero_piso'];
$descripcion_piso = $row1['descripcion_piso'];
$tipo_cuarto = $row1['tipo'];
$descripcion_tipo_cuarto = $row1['descripcion_tipo_cuarto'];
$cantidad_cuarto = $row1['cantidad'];
$estado_cuarto = $row1['estado'];
$descripcion_estado_cuarto = $row1['descripcion_estado_cuarto'];
$precio_por_hora = number_format($precio_por_hora, 2);
$precio_por_hora= "<p style='color:#008704'>$".$precio_por_hora."</p>";
if($estado_cuarto == 'DISPONIBLE'){
    $estado_cuarto = "<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>DISPONIBLE</label>";
}
if($estado_cuarto == 'OCUPADO'){
    $estado_cuarto = "<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>OCUPADO</label>";
}
if($estado_cuarto == 'MANTENIMIENTO'){
    $estado_cuarto = "<label class='badge' style='background:#A6B900; color:#FFF; font-weight:bold;'>MANTENIMIENTO</label>";
}


$sql_hospitalizaciones = "SELECT tblHospitalizacion.id_hospitalizacion, tblHospitalizacion.momento_entrada, tblHospitalizacion.momento_salida, tblEstado_Hospitalizacion.estado, CONCAT(tblPaciente.nombres,' ',tblPaciente.apellidos) as 'nombre_paciente' FROM tblHospitalizacion INNER JOIN tblEstado_Hospitalizacion on tblEstado_Hospitalizacion.id_estado_hospitalizacion = tblHospitalizacion.id_estado_hospitalizacion INNER JOIN tblRecepcion on tblRecepcion.id_recepcion = tblHospitalizacion.id_recepcion INNER JOIN tblPaciente on tblPaciente.id_paciente = tblRecepcion.id_paciente_recepcion WHERE tblHospitalizacion.id_cuarto_H ='$id_cuarto' ";
$consulta_hospitalizaciones = _query($sql_hospitalizaciones);
$numero = _num_rows($consulta_hospitalizaciones);

$tablas="";
$tablas.="<table class='table table-bordered'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Id Cuarto</th>";
$tablas.="<th scope='col'>Numero Cuarto</th>";
$tablas.="<th scope='col'>Descripcion Cuarto</th>";
$tablas.="<th scope='col'>Precio por hora</th>";
$tablas.="<th scope='col'>Numero Piso</th>";
$tablas.="<th scope='col'>Descripcion Piso</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<th scope='row'>".$id_cuarto."</th>";
$tablas.="<th scope='row'>".$numero_cuarto."</th>";
$tablas.="<th scope='row'>".$descripcion_cuarto."</th>";
$tablas.="<th scope='row'>".$precio_por_hora."</th>";
$tablas.="<th scope='row'>".$numero_piso."</th>";
$tablas.="<th scope='row'>".$descripcion_piso."</th>";
$tablas.="</tr>";
$tablas.="</tbody>";
$tablas.="</table>";
$tablas.="</br>";
$tablas.="<table class='table table-bordered'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Tipo de cuarto</th>";
$tablas.="<th scope='col'>Descripcion tipo cuarto</th>";
$tablas.="<th scope='col'>Capacidad</th>";
$tablas.="<th scope='col'>Estado Cuarto</th>";
$tablas.="<th scope='col'>Descripcion estado cuarto</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<th scope='row'>".$tipo_cuarto."</th>";
$tablas.="<th scope='row'>".$descripcion_tipo_cuarto."</th>";
$tablas.="<th scope='row'>".$cantidad_cuarto."</th>";
$tablas.="<th scope='row'>".$estado_cuarto."</th>";
$tablas.="<th scope='row'>".$descripcion_estado_cuarto."</th>";
$tablas.="</tr>";
$tablas.="</tbody>";
$tablas.="</table>";
if($numero > 0){
    $tablas.="<table class='table table-bordered'>";
    $tablas.="<thead class='thead-dark'>";
    $tablas.="<tr>";
    $tablas.="<th scope='col'>Id Hospitalizacion</th>";
    $tablas.="<th scope='col'>Momento de entrada</th>";
    $tablas.="<th scope='col'>Momento de salida</th>";
    $tablas.="<th scope='col'>Estado</th>";
    $tablas.="<th scope='col'>Nombre de paciente</th>";
    $tablas.="</tr>";
    $tablas.="</thead>";
    $tablas.="<tbody>";
    while ($row2 = _fetch_array($consulta_hospitalizaciones)) {
        $tablas.="<tr>";
        $tablas.="<th scope='row'>".$row2['id_hospitalizacion']."</th>";
        $tablas.="<td>".$row2['momento_entrada']."</td>";
        $tablas.="<td>".$row2['momento_salida']."</td>";
        $tablas.="<td>";
        if($row2['estado'] == 'EN HABITACION'){
          $tablas.= "<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>EN HABITACION</label>";
        }
        if($row2['estado'] == 'FINALIZADA'){
          $tablas.= "<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>FINALIZADA</label>";
        }
        if($row2['estado'] == 'PENDIENTE'){
          $tablas.="<label class='badge' style='background:#A6B900; color:#FFF; font-weight:bold;'>PENDIENTE</label>";
        }
        $tablas.="</td>";
        $tablas.="<td>".$row2['nombre_paciente']."</td>";
        $tablas.="</tr>";
    }
    $tablas.="</tbody>";
    $tablas.="</table>";
}
	$title='DATOS DE LA HABITACION #'.$numero_cuarto." EN EL PISO #".$numero_piso;
	$_PAGE = array ();
	$_PAGE ['title'] =$title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	include_once "header.php";
    include_once "main_menu.php";
    $id_piso=0;
    if(isset($_REQUEST["id_piso"])){
        $id_piso = $_REQUEST["id_piso"];
    }
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

    if ($links!='NOT' || $admin=='1' ){
	//mysql_query("SET NAMES 'utf8'");
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row" id="row1">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <!--load datables estructure html-->
                    <header>
                        <h3 style="color:#194160;"><i class="fa fa-user-md"></i> <b><?php echo $title;?></b></h3>
                    </header>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-info text-center alert alert-info">
                                <label><?php echo "Informacion de la habitacion #".$numero_cuarto." en el piso #".$numero_piso."."; ?></label>
                                <label><?php echo $descripcion_cuarto; ?></label>
                            </div>
                            <div class='col-md-12'>
                                <?php echo $tablas; ?>
                                <br>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btnVolver">Volver</button>
                        </div>
                    </div>
                    <!--div class='ibox-content'-->
                </div>
                <!--<div class='ibox float-e-margins' -->
            </div>
            <!--div class='col-lg-12'-->
        </div>
        <!--div class='row'-->
    </div>
    <!--div class='wrapper wrapper-content  animated fadeInRight'-->
</div>
<?php
    include("footer.php");
    echo" <script type='text/javascript' src='js/funciones/funciones_habitacion.js'></script>";
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
?>