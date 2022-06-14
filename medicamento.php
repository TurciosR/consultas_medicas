<?php
//Inclusion de funciones de conexion y session y definicion de variables de conexion
include_once "_core.php";
/*
 * Clase de personalizacion de Data Table, para uso como Server Side
 * Nelson Borland J. Vides
 */
 
// Tabla DB
$table = 'medicamento';
 
// Llave de la Tabla
$primaryKey = 'id_medicamento';
 
// Array con las columnas de la tabla
//Las llaves definidas por db corresponden a los nombres de las columnas de la tabla
//Las llaves definidas con dt corresponden a las columnas de la tabla como indices numericos
//Formatter, personalizacion del campo de la tabla, permite dar estilo a los datos mostrados en esa columna
$columns = array(
    array( 'db' => 'id_medicamento', 'dt' => 0 ),
    array( 'db' => 'descripcion',  'dt' => 1 ),
    array( 'db' => 'laboratorio',  'dt' => 2 ),
    array( 'db' => 'presentacion',  'dt' => 3 ),
    array( 'db' => 'id_medicamento',  'dt' => 4, 'formatter' => function($id_medicamento, $row){
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"]; 
        $boton = "<div class=\"btn-group\">
        <a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
        <ul class=\"dropdown-menu dropdown-primary\">";
            $filename='ver_medicamento.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' )
                $boton.="<li><a data-toggle='modal' href=\"ver_medicamento.php?id_medicamento=".$row['id_medicamento']."\" data-target='#viewModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver Detalle</a></li>";
            $filename='editar_medicamento.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' )
                $boton.="<li><a href=\"editar_medicamento.php?id_medicamento=".$row['id_medicamento']."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
            $filename='borrar_medicamento.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' )
                $boton.= "<li><a data-toggle='modal' href='borrar_medicamento.php?id_medicamento=".$row ['id_medicamento']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";                                   
            $boton.= "</ul>
                </div>";
        return $boton;}, 'field' => 'id_medicamento'),
);
 
// Conexion a la Base de datos, variables traidas de _conexion.php incluidas en _core.php
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);
 
 
//Clase que ejecuta el parseo de datos de php hacia le data table
require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);