<?php
//Inclusion de funciones de conexion y session y definicion de variables de conexion
include_once "_core.php";
/*
 * Clase de personalizacion de Data Table, para uso como Server Side
 * Nelson Borland J. Vides
 */
 
// Tabla DB
$table = 'paciente';
 
// Llave de la Tabla
$primaryKey = 'id_paciente';
 
// Array con las columnas de la tabla
//Las llaves definidas por db corresponden a los nombres de las columnas de la tabla
//Las llaves definidas con dt corresponden a las columnas de la tabla como indices numericos
//Formatter, personalizacion del campo de la tabla, permite dar estilo a los datos mostrados en esa columna
$columns = array(
    array( 'db' => 'nombres',  'dt' => 0),
    array( 'db' => 'apellidos',  'dt' => 1),
    array( 'db' => 'tel1', 'dt' => 2),
    array( 'db' => 'direccion', 'dt' => 3 ),
    array( 'db' => 'fecha_nacimiento', 'dt' => 4, 'formatter' => function($fecha_nacimiento, $row){
            $fecha = ED($fecha_nacimiento); 
            $datos_fecha = explode("-", $fecha);
            $anio_nac  = $datos_fecha[2];
            $edad = date("Y") - $anio_nac;   
            return $edad;}, 'field', 'fecha_nacimiento'),
    array( 'db' => 'expediente', 'dt' => 5, 'formatter' => function($expediente, $row){
            $len = strlen((string)$expediente);
            $fill = 7 - $len;
            if($fill <0)
                $fill = 0;
            $n_exp = zfill($expediente, $fill);
            return $n_exp;}, 'field', 'expediente'),
    array( 'db' => 'id_paciente',  'dt' => 6, 'formatter' => function($id_paciente, $row){
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"]; 
        $boton = "<div class=\"btn-group\">
            <a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
            <ul class=\"dropdown-menu dropdown-primary\">";
                $filename='editar_paciente.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' )
                    $boton.="<li><a href=\"editar_paciente.php?id_paciente=".$row['id_paciente']."\"><i class=\"fa fa-pencil\"></i> Editar</a></li>";
                $filename='borrar_paciente.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' )
                    $boton.= "<li><a data-toggle='modal' href='borrar_paciente.php?id_paciente=".$row ['id_paciente']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
                $filename='ver_paciente.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' )
                    $boton.= "<li><a data-toggle='modal' href='ver_paciente.php?id_paciente=".$row['id_paciente']."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-search\"></i> Ver Detalle</a></li>"; 
                $filename='expediente.php';
                $link=permission_usr($id_user,$filename);
                if ($link!='NOT' || $admin=='1' )
                    $boton.= "<li><a href='expediente.php?id_paciente=".$row['id_paciente']."'><i class=\"fa fa-eye\"></i> Ver Expediente</a></li>";                                    
            $boton.= "</ul>
                    </div>";
        return $boton;}, 'field' => 'id_paciente'),
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