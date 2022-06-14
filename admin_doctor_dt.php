<?php
include ("_core.php");
$requestData= $_REQUEST;
require('ssp.customized.class.php' );
// DB table to use
$table = 'doctor';

$id_sucursal = 1;//$_SESSION["id_sucursal"];
// Table's primary key
$primaryKey = 'id_doctor';
// MySQL server connection information
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);
$joinQuery=" FROM doctor AS d, especialidad as e  ";
$extraWhere="  d.id_especialidad = e.id_especialidad";
//and p.id_sucursal='$id_sucursal'*/
$columns = array(
    array( 'db' => 'd.id_doctor', 'dt' => 0, 'field' => 'id_doctor'),
    array( 'db' => "CONCAT(d.nombres,' ', COALESCE(d.apellidos,'') )", 'dt' => 1, 'field' => "doctor", 'as'=>'doctor'),
    array( 'db' => "d.telefono",   'dt' => 2, 'field' => 'telefono'),
    array( 'db' => "d.direccion",   'dt' => 3, 'field' => 'direccion'),
    array( 'db' => '`d`.`fecha_nac`',   'dt' => 4, 'formatter' => function($fecha_nacimiento)
    {
        $edad=calcular_edad($fecha_nacimiento);
        return $edad;
    }, 'field' => 'fecha_nac'),
    array( 'db' => '`e`.`descripcion`',   'dt' => 5, 'field' => 'descripcion'),
    array( 'db' => 'id_doctor','dt' => 6,'formatter' => function( $id_doctor){
        return  dropdown($id_doctor);
    }, 'field' => 'id_doctor')
);
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
function calcular_edad($fecha){
    list($A,$m,$d)=explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$A-1 : date("Y")-$A);
}
function dropdown($id_doctor){
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $menudrop="<div class=\"btn-group\">";
    $menudrop.="<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>";
    $menudrop.="<ul class=\"dropdown-menu dropdown-primary\">";
    $filename='editar_doctor.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
        $menudrop.="<li><a href=\"editar_doctor.php?id_doctor=".$id_doctor."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
    $filename='borrar_doctor.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
        $menudrop.= "<li><a data-toggle='modal' href='borrar_doctor.php?id_doctor=".$id_doctor."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
    $filename='ver_doctor.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
        $menudrop.= "<li><a data-toggle='modal' href='ver_doctor.php?id_doctor=".$id_doctor."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-search\"></i> Ver Detalle</a></li>";									
    $menudrop.="</ul>";
    $menudrop.=" </div>";
    return $menudrop;
}
?>