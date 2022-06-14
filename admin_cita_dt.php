<?php
include ("_core.php");
$requestData= $_REQUEST;
$ini= MD($_REQUEST['ini']);
$fin= MD($_REQUEST['fin']);
$id_doctor = $_REQUEST['id_doctor'];
require('ssp.customized.class.php' );
// DB table to use
$table = 'reserva_cita';
$id_sucursal = 1;//$_SESSION["id_sucursal"];
// Table's primary key
$primaryKey = 'id';
// MySQL server connection information
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);
$joinQuery=" FROM reserva_cita as r, doctor as d, espacio as e, estado_cita as es, paciente as p";
$extraWhere="  d.id_doctor=r.id_doctor AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.fecha_cita BETWEEN '$ini' AND '$fin' AND p.id_paciente = r.id_paciente";
if($id_doctor != 0){
	$extraWhere.= " AND d.id_doctor = '$id_doctor'";
}
//and p.id_sucursal='$id_sucursal'*/
$columns = array(
    array( 'db' => 'r.id', 'dt' => 0, 'field' => 'id'),
    array( 'db' => "r.fecha_cita", 'dt' => 1, 'field' => "fecha_cita"),
	array( 'db' => "r.hora_cita", 'dt' => 2, 'field' => "hora_cita"),
    array( 'db' => "CONCAT(p.nombres,' ',p.apellidos)",   'dt' => 3, 'field' => 'paciente', 'as' => 'paciente'),
    array( 'db' => "CONCAT(d.nombres,' ',d.apellidos)",   'dt' => 4, 'field' => 'medico', 'as' => 'medico'),
	array( 'db' => "e.descripcion", 'dt' => 5, 'field' => "descripcion"),
	array( 'db' => "r.id", 'dt' => 6, 'formatter' => function($id){
		$sql = "SELECT * FROM reserva_cita as r, estado_cita as es WHERE es.id_estado = r.estado AND r.id= '$id'";
		$query = _query($sql);
		$row = _fetch_array($query);
		$var = "<label class='badge' style='background:".$row['color']."; color:#FFF; font-weight:bold;'>".$row['descripcion']."</label>";
		return $var;
	}, 'field' => "id"),
    array( 'db' => 'r.id','dt' => 7,'formatter' => function($id){
        return  dropdown($id);
    }, 'field' => 'id')
);
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
function calcular_edad($fecha){
    list($A,$m,$d)=explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$A-1 : date("Y")-$A);
}
function dropdown($id){
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
	$sql = "SELECT * FROM reserva_cita WHERE id = '$id'";
	$query = _query($sql);
	$row = _fetch_array($query);
    $menudrop="<div class=\"btn-group\">";
    $menudrop.="<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>";
    $menudrop.="<ul class=\"dropdown-menu dropdown-primary\">";
	$filename='ver_cita.php';
	$link=permission_usr($id_user,$filename);
	if ($link!='NOT' || $admin=='1' )
		$menudrop.= "<li><a data-toggle='modal' href='ver_cita.php?id_cita=".$row['id']."&process="."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver Detalle</a></li>";
	if($row["estado"]<6)
	{
	$filename='editar_cita1.php';
	$link=permission_usr($id_user,$filename);	
	if ($link!='NOT' || $admin=='1' )
		$menudrop.= "<li><a href=\"editar_cita1.php?id=".$row['id']."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";

	$filename='borrar_cita.php';
	$link=permission_usr($id_user,$filename);
	if ($link!='NOT' || $admin=='1' )
		$menudrop.= "<li><a data-toggle='modal' href='borrar_cita.php?id_cita=".$row['id']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";


	$filename='agregar_ultra.php';
	$link=permission_usr($id_user,$filename);
	if ($link!='NOT' || $admin=='1' )
		$url=$filename.'?&id_cita='.$row['id'].'&lugar=citas';
		$menudrop.= "<li><a  href=".$url." data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Agregar Ultra</a></li>";
	}
    $menudrop.="</ul>";
    $menudrop.=" </div>";
    return $menudrop;
}
?>