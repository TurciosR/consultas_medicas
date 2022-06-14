<?php
include("_core.php");
$requestData= $_REQUEST;
require('ssp.customized.class.php' );
// DB table to use
$table = 'cuartos';
$id_sucursal = $_SESSION["id_sucursal"];
$id_piso= $_REQUEST['id_piso'];
// Table's primary key
$primaryKey = 'id_cuarto';
// MySQL server connection information
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);
$where = "";
if($id_piso != 0){
    $where.=" cuartos.id_piso_cuarto = '$id_piso' AND cuartos.deleted is NULL";
}
else{
    $where.=" cuartos.deleted is NULL";
}
$joinQuery=" FROM cuartos INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto INNER JOIN estado_cuarto on estado_cuarto.id_estado_cuarto = cuartos.id_estado_cuarto_cuarto INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto ";
$extraWhere= $where;

//and p.id_sucursal='$id_sucursal'*/
$columns = array(
    array( 'db' => 'cuartos.numero_cuarto', 'dt' => 0, 'formatter' => function($numero_cuarto){
        return "# ".$numero_cuarto;
    }, 'field' => 'numero_cuarto'),
    array( 'db' => "pisos.numero_piso",   'dt' => 1, 'formatter' => function($numero_piso){
        return "# ".$numero_piso;
    },'field' => 'numero_piso'),
    array( 'db' => 'cuartos.descripcion', 'dt' => 2, 'field' => 'descripcion'),
    array( 'db' => "cuartos.precio_por_hora",   'dt' => 3, 'formatter' => function($precio_por_hora){
        $precio_por_hora = number_format($precio_por_hora, 2);
        return "<p style='color:#008704'>$".$precio_por_hora."</p>";
    }, 'field' => 'precio_por_hora'),
    array( 'db' => 'tipo_cuarto.tipo',   'dt' => 4, 'field' => 'tipo'),
    array( 'db' => 'estado_cuarto.estado','dt' => 5, 'formatter' => function($estado){
        if($estado == 'DISPONIBLE'){
            return "<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>DISPONIBLE</label>";
        }
        if($estado == 'OCUPADO'){
            return "<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>OCUPADO</label>";
        }
        if($estado == 'MANTENIMIENTO'){
            return "<label class='badge' style='background:#A6B900; color:#FFF; font-weight:bold;'>MANTENIMIENTO</label>";
        }
    },'field' => 'estado'),
    array( 'db' => 'cuartos.id_cuarto','dt' => 6, 'formatter' =>function($id_cuarto){
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"];
        $tabla ="<td><div class=\"btn-group\">
		<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
		<ul class=\"dropdown-menu dropdown-primary\">";
		/*echo "<li><a href=\"permiso_usuario.php?id_usuario=".$row['id_usuario']."\"><i class=\"fa fa-lock\"></i> Permisos</a></li>";*/
        /*$filename='estado_habitacion.php';
		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
		    $tabla.= "<li><a  href='estado_habitacion.php?id_habitacion=".$id_cuarto."' ><i class=\"fa fa-binoculars\"></i> Estado</a></li>";
        */
        $filename='editar_habitacion.php';
		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
		    $tabla.= "<li><a  href='editar_habitacion.php?id_habitacion=".$id_cuarto."'><i class=\"fa fa-pencil\"></i> Editar</a></li>";
        $filename='ver_habitacion.php';
        $link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
            $tabla.= "<li><a  href='ver_habitacion.php?id_habitacion=".$id_cuarto."' ><i class=\"fa fa-eye\"></i> Ver</a></li>";
		$filename='borrar_habitacion.php';
		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
            $tabla.= "<li><a data-toggle='modal' href='borrar_habitacion.php?id_habitacion=".$id_cuarto."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
        $tabla.=  "	</ul>
		</div>
		</td>
		</tr>";
        return $tabla;
    },  'field' => 'id_cuarto'),
);
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>
