<?php
include ("_core.php");
$requestData= $_REQUEST;
require('ssp.customized.class.php' );
// DB table to use
$table = 'paciente';

$id_sucursal = 1;//$_SESSION["id_sucursal"];
// Table's primary key
$primaryKey = 'id_paciente';
// MySQL server connection information
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);
$joinQuery=" FROM paciente AS p, departamento AS d, municipio AS m ";
$extraWhere="  p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento";
//and p.id_sucursal='$id_sucursal'*/
$columns = array(
    array( 'db' => '`p`.`id_paciente`', 'dt' => 0, 'field' => 'id_paciente'),
    array( 'db' => "CONCAT(p.nombres,' ', COALESCE(p.apellidos,'') )", 'dt' => 1, 'field' => "paciente", 'as'=>'paciente'),
    array( 'db' => "CONCAT(p.direccion, ' ',m.nombre_municipio)",   'dt' => 2, 'field' => 'direccion', 'as' => 'direccion'),
    array( 'db' => '`p`.`fecha_nacimiento`',   'dt' => 3, 'field' => 'fecha_nacimiento'),
    array( 'db' => '`p`.`fecha_nacimiento`',   'dt' => 4, 'formatter' => function($fecha_nacimiento)
    {
        $edad=calcular_edad($fecha_nacimiento);
        return $edad;
    }, 'field' => 'fecha_nacimiento'),
    array( 'db' => '`p`.`expediente`',   'dt' => 5, 'formatter' => function($expediente)
    {
        $len = strlen((string)$expediente);
        $fill = 7 - $len;
        if($fill <0)
            $fill = 0;
        $n_exp = zfill($expediente, $fill);
        return $n_exp;
    }, 'field' => 'expediente'),
    array( 'db' => 'id_paciente','dt' => 6,'formatter' => function( $id_paciente){
        return  dropdown($id_paciente);
    }, 'field' => 'id_paciente')
);
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
function calcular_edad($fecha){
    list($A,$m,$d)=explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$A-1 : date("Y")-$A);
}
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