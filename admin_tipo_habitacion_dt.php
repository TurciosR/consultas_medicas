<?php
include("_core.php");
$requestData= $_REQUEST;
require('ssp.customized.class.php' );
// DB table to use
$table = 'tipo_cuarto';
$id_sucursal = $_SESSION["id_sucursal"];
// Table's primary key
$primaryKey = 'id_tipo_cuarto';
// MySQL server connection information
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);
$joinQuery=" FROM tipo_cuarto";
$extraWhere= ' deleted is NULL';

//and p.id_sucursal='$id_sucursal'*/
$columns = array(
    array( 'db' => 'tipo_cuarto.id_tipo_cuarto', 'dt' => 0, 'field' => 'id_tipo_cuarto'),
    array( 'db' => "tipo_cuarto.tipo",   'dt' => 1, 'field' => 'tipo'),
    array( 'db' => 'tipo_cuarto.descripcion', 'dt' => 2, 'field' => 'descripcion'),
    array( 'db' => "tipo_cuarto.cantidad",   'dt' => 3, 'formatter' => function($cantidad){
        if($cantidad == 1){
            return $cantidad." persona.";
        }
        else{
            return $cantidad." personas.";
        }
    }, 'field' => 'cantidad'),
    array( 'db' => 'tipo_cuarto.estado','dt' => 4, 'formatter' => function($estado){
        if($estado == 1){
            return "<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>Activo</label>";
        }
        if($estado == 0){
            return "<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>Inactivo</label>";
        }
    },'field' => 'estado'),
    array( 'db' => 'tipo_cuarto.id_tipo_cuarto','dt' => 5, 'formatter' =>function($id_tipo_cuarto){
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"];
        $sql = "SELECT estado from tipo_cuarto where id_tipo_cuarto = '$id_tipo_cuarto'";
        $consulta = _query($sql);
        $row = _fetch_array($consulta);
        $estado = $row['estado'];
        $text="";
        $text1 = "";
        $fa = "";
        if($estado == 1)
        {
            $text = "Activo";
            $text1 = "Desactivar";
            $fa = "fa fa-eye-slash";
        }
        else
        {
            $text = "Inactivo";
            $text1 = "Activar";
            $fa = "fa fa-eye";
        }

        $tabla ="<td><div class=\"btn-group\">
		<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
		<ul class=\"dropdown-menu dropdown-primary\">";
		/*echo "<li><a href=\"permiso_usuario.php?id_usuario=".$row['id_usuario']."\"><i class=\"fa fa-lock\"></i> Permisos</a></li>";*/
        $filename='editar_tipo_habitacion.php';
		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
		    $tabla.= "<li><a  href='editar_tipo_habitacion.php?id_tipo_habitacion=".$id_tipo_cuarto."'><i class=\"fa fa-pencil\"></i> Editar</a></li>";
        $filename='ver_tipo_habitacion.php';
        $link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
            $tabla.= "<li><a data-toggle='modal' href='ver_tipo_habitacion.php?id_tipo_habitacion=".$id_tipo_cuarto."'  data-target='#viewModal' data-refresh='true' ><i class=\"fa fa-eye\"></i> Ver</a></li>";
        $filename='estado_tipo_habitacion.php';
        $link=permission_usr($id_user,$filename);
        if ($link!='NOT' || $admin=='1' )
        //echo "<li><a data-toggle='modal' href='borrar_cliente.php?id_cliente=".$row['id_cliente']."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Borrar</a></li>";
            $tabla .= "<li><a data-toggle='modal' href='estado_tipo_habitacion.php?id_tipo_habitacion=".$id_tipo_cuarto."&estado=".$estado."' data-target='#estadoModal' data-refresh='true' ><i class='".$fa."'></i> ".$text1."</a></li>";
        $filename='borrar_tipo_habitacion.php';
		$link=permission_usr($id_user,$filename);
		if ($link!='NOT' || $admin=='1' )
            $tabla.= "<li><a data-toggle='modal' href='borrar_tipo_habitacion.php?id_tipo_habitacion=".$id_tipo_cuarto."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
        $tabla.=  "	</ul></div></td></tr>";
        return $tabla;
    },  'field' => 'id_tipo_cuarto'),
);
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
?>
