<?php
//Inclusion de funciones de conexion y session y definicion de variables de conexion
include_once "_core.php";
/*
 * Clase de personalizacion de Data Table, para uso como Server Side
 * Nelson Borland J. Vides
 */
 
// Tabla DB
$table = 'diagnostico';
 
// Llave de la Tabla
$primaryKey = 'id_diagnostico';
 
// Array con las columnas de la tabla
//Las llaves definidas por db corresponden a los nombres de las columnas de la tabla
//Las llaves definidas con dt corresponden a las columnas de la tabla como indices numericos
//Formatter, personalizacion del campo de la tabla, permite dar estilo a los datos mostrados en esa columna
$columns = array(
    array( 'db' => 'id_diagnostico', 'dt' => 0 ),
    array( 'db' => 'descripcion',  'dt' => 1 ),
    array( 'db' => 'id_diagnostico',  'dt' => 2, 'formatter' => function($id_diagnostico, $row){
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"]; 
        $boton = "<div class=\"btn-group\">
                    <a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
                    <ul class=\"dropdown-menu dropdown-primary\">";
        $filename='editar_diagnostico.php';
        $link=permission_usr($id_user,$filename);
        if ($link!='NOT' || $admin=='1' )
            $boton .= "<li><a href=\"editar_diagnostico.php?id_diagnostico=".$id_diagnostico."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
        $filename='borrar_diagnostico.php';
        $link=permission_usr($id_user,$filename);
        if ($link!='NOT' || $admin=='1' )
            $boton .= "<li><a data-toggle='modal' href='borrar_diagnostico.php?id_diagnostico=".$id_diagnostico."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
        $boton .= "</ul>
            </div>";
        return $boton;}, 'field' => 'id_diagnostico'),
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