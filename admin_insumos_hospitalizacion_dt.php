<?php
include ("_core.php");
error_reporting(E_ALL ^ E_NOTICE);
$requestData= $_REQUEST;
require('ssp.customized.class.php' );
// DB table to use
$table = 'insumos_emergencia';

$id_sucursal = 1;//$_SESSION["id_sucursal"];
// Table's primary key
$primaryKey = 'id_insumo';
// MySQL server connection information
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);

//$id_recepcion=$_GET['id_recepcion'];

//jalando los insumos
/*sql=SELECT ie.id_insumo, p.descripcion AS producto, sh.descripcion AS servicio, 
ie.cantidad, ie.total FROM insumos_emergencia AS ie LEFT JOIN cmf.producto AS
 p ON p.id_producto=ie.id_producto LEFT JOIN  cmf.servicios_hospitalarios AS 
 sh on ie.id_servicio=sh.id_servicio WHERE ie.id_recepcion=13*/

$joinQuery=" FROM insumos_emergencia AS ie LEFT JOIN ".EXTERNAL.".producto AS
             p ON p.id_producto=ie.id_producto LEFT JOIN  ".EXTERNAL.".servicios_hospitalarios AS 
             sh on ie.id_servicio=sh.id_servicio";

$extraWhere=" ie.id_recepcion=13";
//and p.id_sucursal='$id_sucursal'*/
$columns = array(
    array( 'db' => 'ie.id_insumo', 'dt' => 0, 'field' => 'id_insumo'),
    array( 'db' => "p.descripcion", 'dt' => 1, 'field' => "descripcion", 'as'=>'producto'),
    array( 'db' => "sh.descripcion",   'dt' => 2, 'field' => 'servicio', 'as' => 'servicio'),
    array( 'db' => 'ie.cantidad',   'dt' => 3, 'field' => 'cantidad'),
    array( 'db' => 'ie.total',   'dt' => 3, 'total' => 'fecha_nacimiento'),
    array( 'db' => 'ie.id_insumo','dt' => 4,'formatter' => function( $id_insumo){
        return  dropdown($id_insumo);
    }, 'field' => 'id_insumo')
);
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);

function dropdown($id_paciente){
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $menudrop="<div class=\"btn-group\">";
    $menudrop.="<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>";
    $menudrop.="<ul class=\"dropdown-menu dropdown-primary\">";
    $filename='editar_paciente.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
        $menudrop.="<li><a href=\"editar_paciente.php?id_paciente=".$id_paciente."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
    $filename='borrar_paciente.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
        $menudrop.= "<li><a data-toggle='modal' href='borrar_paciente.php?id_paciente=".$id_paciente."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
    $filename='ver_paciente.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
        $menudrop.="<li><a data-toggle='modal' href='ver_paciente.php?id_paciente=".$id_paciente."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-search\"></i> Ver Detalle</a></li>";
    $filename='expediente.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
        $menudrop.="<li><a href='expediente.php?id_paciente=".$id_paciente."'><i class=\"fa fa-eye\"></i> Ver Expediente</a></li>";
    $menudrop.="</ul>";
    $menudrop.=" </div>";
    return $menudrop;
}
?>