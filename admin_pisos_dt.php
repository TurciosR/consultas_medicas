<?php
include("_core.php");
$requestData= $_REQUEST;
require('ssp.customized.class.php' );
// DB table to use
$table = 'pisos';
$id_sucursal = $_SESSION["id_sucursal"];

// Table's primary key
$primaryKey = 'id_piso';
// MySQL server connection information  
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);
$joinQuery=" FROM pisos LEFT JOIN cuartos on pisos.id_piso = cuartos.id_piso_cuarto";
$extraWhere="  pisos.id_ubicacion_piso = '$id_sucursal' AND pisos.deleted is NULL";

//and p.id_sucursal='$id_sucursal'*/
$columns = array(
    array( 'db' => 'pisos.numero_piso', 'dt' => 0, 'field' => 'numero_piso'),
    array( 'db' => "pisos.descripcion",   'dt' => 1, 'field' => 'descripcion'),
    array( 'db' => 'pisos.id_piso',   'dt' => 2, 'formatter' => function($id_piso){
        $sql = "SELECT id_cuarto from cuartos where id_piso_cuarto = '$id_piso'";
        $consulta = _query($sql);
        $total = _num_rows($consulta);
        return $total;
    },'field' => 'id_piso'),
    array( 'db' => 'pisos.id_piso','dt' => 3, 'formatter' => function($id_piso){
        $sql = "SELECT id_cuarto from cuartos WHERE id_piso_cuarto = '$id_piso' AND id_estado_cuarto_cuarto = '1'";
        $consulta = _query($sql);
        $total = _num_rows($consulta);
        if($total == 0){
            return "<input type='hidden' id='estado1' value='".$total."'><label class='badge' style='background:#FF4646; color:#FFF; font-weight:bold;'>Sin habitaciones disponibles</label>";
        }
        else{
            return "<input type='hidden' id='estado1' value='".$total."'><label class='badge' style='background:#2EC824; color:#FFF; font-weight:bold;'>Con habitaciones disponibles</label>";
        }
    },'field' => 'id_piso'),
    array( 'db' => 'pisos.id_piso','dt' => 4, 'formatter' =>function($id_piso){
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"];
        $tabla ="<td><div class=\"btn-group\">
		<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
		<ul class=\"dropdown-menu dropdown-primary\">";
		/*echo "<li><a href=\"permiso_usuario.php?id_usuario=".$row['id_usuario']."\"><i class=\"fa fa-lock\"></i> Permisos</a></li>";*/
        $filename='admin_habitaciones.php';
        $link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
		    $tabla.= "<li><a  href='admin_habitaciones.php?id_piso=".$id_piso."' data-target='#editModal' data-refresh='true'><i class=\"fa fa-bed\"></i> Ver Habitaciones</a></li>";
        $filename='editar_piso.php';
		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
		    $tabla.= "<li><a  href='editar_piso.php?id_piso=".$id_piso."' data-target='#editModal' data-refresh='true'><i class=\"fa fa-pencil\"></i> Editar</a></li>";
        $filename='ver_piso.php';
        $link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
            $tabla.= "<li><a data-toggle='modal' href='ver_piso.php?id_piso=".$id_piso."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver</a></li>";
		$filename='borrar_piso.php';
		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
            $tabla.= "<li><a data-toggle='modal' href='borrar_piso.php?id_piso=".$id_piso."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
        $tabla.=  "	</ul>
		</div>
		</td>
		</tr>";
        return $tabla;
    },  'field' => 'id_piso'),
);
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, " id_piso" )
);
?>
