<?php
include ("_core.php");
$requestData= $_REQUEST;
require('ssp.customized.class.php' );
// DB table to use
$table = 'espacio';

$id_sucursal = 1;//$_SESSION["id_sucursal"];
// Table's primary key
$primaryKey = 'id_espacio';
// MySQL server connection information
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);
$joinQuery=" FROM espacio";
$extraWhere="";
//and p.id_sucursal='$id_sucursal'*/
$columns = array(
    array( 'db' => '`espacio`.`id_espacio`', 'dt' => 0, 'field' => 'id_espacio'),
    array( 'db' => '`espacio`.`descripcion`', 'dt' => 1, 'field' => 'descripcion'),
    array( 'db' => '`espacio`.`observaciones`', 'dt' => 2, 'field' => 'observaciones'),
    array( 'db' => 'espacio.id_espacio','dt' => 3,'formatter' => function( $id_espacio){
        return  dropdown($id_espacio);
    }, 'field' => 'id_espacio')
);
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
function calcular_edad($fecha){
    list($A,$m,$d)=explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$A-1 : date("Y")-$A);
}
function dropdown($id_espacio){
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $menudrop="<div class=\"btn-group\">";
    $menudrop.="<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>";
    $menudrop.="<ul class=\"dropdown-menu dropdown-primary\">";
    $filename='editar_espacio.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
        $menudrop.="<li><a href=\"editar_espacio.php?id_espacio=".$id_espacio."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
    $filename='borrar_espacio.php';
    $link=permission_usr($id_user,$filename);
    if ($link!='NOT' || $admin=='1' )
        $menudrop.="<li><a data-toggle='modal' href='borrar_espacio.php?id_espacio=".$id_espacio."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
    $menudrop.="</ul>";
    $menudrop.=" </div>";
    return $menudrop;
}
?>