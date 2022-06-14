<?php
/**
 * This file is part of the OpenPyme1.
 * 
 * (c) Open Solution Systems <operaciones@tumundolaboral.com.sv>
 * 
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

/**
 * Sistema basado en Consultorio de Doctor Torres - Lolotique
 */
    $dbname   =  "consultas_medicas";   // "cmdt2";
    $servidor = "localhost";            // "localhost";
    $usuario  = "root";                 // "torres";
    $clave    = "admin$";               // "torres@2021";
    DEFINE ("EXTERNAL","consultas_medicas_caja");


    $conexion = mysqli_connect("$servidor","$usuario","$clave","$dbname");
    if (mysqli_connect_errno()){
        echo "Error en conexión MySQL: " . mysqli_connect_error();
    }

setlocale(LC_TIME, "es_SV.UTF-8");

date_default_timezone_set("America/El_Salvador");
function _query($sql_string){
    global $conexion;
    //echo $sql_string;
    if ($sql_string!=""){
        $query=mysqli_query($conexion,$sql_string);
        
        echo _error();
        return $query;
    }
    else{
        echo "Error en la consulta...!";
    }

}
// Begin functions queries

function _fetch_array($sql_string){
    global $conexion;
    $fetched = mysqli_fetch_array($sql_string,MYSQLI_ASSOC);
    echo _error();
    return $fetched;
}

function _fetch_row($sql_string){
    global $conexion;
    $fetched = mysqli_fetch_row($sql_string);
    return $fetched;
}
function _fetch_assoc($sql_string){
    global $conexion;
    $fetched = mysqli_fetch_assoc($sql_string);
    return $fetched;
}

function _num_rows($sql_string){
    global $conexion;
    $rows = mysqli_num_rows($sql_string);
    return $rows;
}
function _insert_id(){
    global $conexion;
    $value = mysqli_insert_id($conexion);
    return $value;
}
// End functions queries
//funcion real escape string
function _real_escape($sql_string){
    global $conexion;
    $query=mysqli_real_escape_string($conexion,$sql_string);
    return $query;
}
function _error(){
    global $conexion;
        return mysqli_error($conexion);
}

// funciones insertar
function _insert($table_name, $form_data){
    // retrieve the keys of the array (column titles)
    $form_data2=array();
    $variable='';
    $sql_pk = _query("DESCRIBE $table_name");
    while($row = _fetch_array($sql_pk))
    {
        if($row["Field"] =="unique_id")
        {
            $form_data['unique_id']=uniqid("S",true);
        }
    }
    // retrieve the keys of the array (column titles)
    $fields = array_keys ( $form_data );
    // join as string fields and variables to insert
    $fieldss = implode ( ',', $fields );
    //$variables = implode ( "','", $form_data ); U+0027
    foreach($form_data as $variable){
        $var1=preg_match('/\x{27}/u', $variable);
        $var2=preg_match('/\x{22}/u', $variable);
        if($var1==true || $var2==true){
         $variable = addslashes($variable);
        }
        array_push($form_data2,$variable);
    }
    $variables = implode ( "','",$form_data2 );

    // build the query
    $sql = "INSERT INTO " . $table_name . "(" . $fieldss . ")";
    $sql .= "VALUES('" . $variables . "')";
    // run and return the query result resource
    return _query($sql);
}
function db_close(){
    global $conexion;
    mysqli_close($conexion);
}
// the where clause is left optional incase the user wants to delete every row!
function _delete($table_name, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add keyword
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // build the query
    $sql = "DELETE FROM ".$table_name.$whereSQL;
    return _query($sql);
}



/* SOFT DELETE, SIRVE PARA ELIMINAR REGISTROS ACTIVANDO CON LA
FECHA EL CAMPO DE DELETE DEL REGISTRO */

function _soft_delete($table_name, $where_clause)
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add keyword
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // build the query
    $hora = date('Y/m/d H:i');
    $sql = "UPDATE $table_name SET deleted = '$hora' ".$whereSQL;

    return _query($sql);
}
/* SOFT DELETE, SIRVE PARA ELIMINAR REGISTROS ACTIVANDO CON LA
FECHA EL CAMPO DE DELETE DEL REGISTRO */








// again where clause is left optional
function _update($table_name, $form_data, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    $form_data2=array();
    $variable='';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // start the actual SQL statement
    $sql = "UPDATE ".$table_name." SET ";

    // loop and build the column /
    $sets = array();
    //begin modified
    foreach($form_data as $index=>$variable){
        $var1=preg_match('/\x{27}/u', $variable);
        $var2=preg_match('/\x{22}/u', $variable);
        if($var1==true || $var2==true){
         $variable = addslashes($variable);
        }
        $form_data2[$index] = $variable;
    }
    foreach ( $form_data2 as $column => $value ) {
        $sets [] = $column . " = '" . $value . "'";
    }
    $sql .= implode(', ', $sets);

    // append the where statement
    $sql .= $whereSQL;
    // run and return the query result
    return _query($sql);
}

function max_id($field,$table)
{
    $max_id=_query("SELECT MAX($field) FROM $table");
    $row = _fetch_array($max_id);
    $max_record = $row[0];

    return $max_record;
}

function ED($fecha){
    $dia = substr($fecha,8,2);
    $mes = substr($fecha,5,2);
    $a = substr($fecha,0,4);
    $fecha = "$dia-$mes-$a";
    return $fecha;
}
function MD($fecha){
    $dia = substr($fecha,0,2);
    $mes = substr($fecha,3,2);
    $a = substr($fecha,6,4);
    $fecha = "$a-$mes-$dia";
    return $fecha;
}
function get_name_script($url){
//metodo para obtener el nombre del file:
$nombre_archivo = $url;
//verificamos si en la ruta nos han indicado el directorio en el que se encuentra
if ( strpos($url, '/') !== FALSE )
    //de ser asi, lo eliminamos, y solamente nos quedamos con el nombre y su extension
    $nombre_archivo_tmp = explode('/', $url);
    $nombre_archivo= array_pop($nombre_archivo_tmp );
    return  $nombre_archivo;
}
function permission_usr($id_user,$filename){
    $sql1="SELECT menu.id_menu, menu.nombre as nombremenu, menu.prioridad,
            modulo.id_modulo,  modulo.nombre as nombremodulo, modulo.descripcion, modulo.filename,
            usuario_modulo.id_usuario,usuario.tipo_usuario as admin
            FROM menu, modulo, usuario_modulo, usuario
            WHERE usuario.id_usuario='$id_user'
            AND menu.id_menu=modulo.id_menu
            AND usuario.id_usuario=usuario_modulo.id_usuario
            AND usuario_modulo.id_modulo=modulo.id_modulo
            AND modulo.filename='$filename'
            ";
    $result1=_query($sql1);
    $count1=_num_rows($result1);
    if($count1 >0){
        $row1=_fetch_array($result1);
        $admin=$row1['admin'];
        $nombremodulo=$row1['nombremodulo'];
        $filename=$row1['filename'];
        $name_link=$filename;
    }
    else $name_link='NOT';
        return $name_link;

}
//functions for transactions
function _begin(){

    global $conexion;
    mysqli_query($conexion, "START TRANSACTION");

    /* disable autocommit, with command from mysqli */
    /* mysqli_autocommit($link, FALSE); */
}

function _commit(){
    global $conexion;
    mysqli_query($conexion,"COMMIT");
    /* commit insert , with command from mysqli */
    /* mysqli_commit($link); */
}

function _rollback(){
    global $conexion;
    mysqli_query($conexion,"ROLLBACK");
    /* Rollback */
    /* mysqli_rollback($link); */

}
//comparar 2 fechas
function compararFechas($separador,$primera, $segunda){
  $valoresPrimera = explode ($separador, $primera);
  $valoresSegunda = explode ($separador, $segunda);
  $diaPrimera    = $valoresPrimera[0];
  $mesPrimera  = $valoresPrimera[1];
  $anyoPrimera   = $valoresPrimera[2];
  $diaSegunda   = $valoresSegunda[0];
  $mesSegunda = $valoresSegunda[1];
  $anyoSegunda  = $valoresSegunda[2];

  $diasPrimeraJuliano = gregoriantojd((int)$mesPrimera, (int)$diaPrimera, (int)$anyoPrimera);
  $diasSegundaJuliano = gregoriantojd((int)$mesSegunda, (int)$diaSegunda, (int)$anyoSegunda);

  if(!checkdate((int)$mesPrimera, (int)$diaPrimera, (int)$anyoPrimera)){
    // "La fecha ".$primera." no es valida";
    return 0;
  }elseif(!checkdate((int)$mesSegunda, (int)$diaSegunda, (int)$anyoSegunda)){
    // "La fecha ".$segunda." no es valida";
    return 0;
  }else{
    return  $diasPrimeraJuliano - $diasSegundaJuliano;
  }

}
//sumar dias a una fecha dada
function sumar_dias($fecha,$dias){
    //formato date('Y-m-j');
    $nuevafecha = strtotime ('+'.$dias.' day' , strtotime ( $fecha ) ) ;
    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
    return 	$nuevafecha;
}

//restar dias a una fecha dada
function restar_dias($fecha,$dias){
    //formato date('Y-m-j');
    $nuevafecha = strtotime ('-'.$dias.' day' , strtotime ( $fecha ) ) ;
    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
    return 	$nuevafecha;
}
function zfill($string, $n)
{
    return str_pad($string,$n,"0",STR_PAD_LEFT);
}
function buscar($id)
{
    $sql = "SELECT CONCAT(nombres,' ',apellidos) as nombre FROM paciente WHERE id_paciente='$id'";
    $query = _query($sql);
    $result = _fetch_array($query);
    $nombre = $result["nombre"];
    return $nombre;
}
function buscar_user($id)
{
    $sql = "SELECT nombre FROM usuario WHERE id_usuario='$id'";
    $query = _query($sql);
    $result = _fetch_array($query);
    $nombre = $result["nombre"];
    return $nombre;
}
function hora($hora)
{
    $hora_pre = date_create($hora);
    $hora_pos = date_format($hora_pre, 'g:i A');
    return $hora_pos;
}

function nombre_dia($fecha){
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $fecha_array=explode('-', $fecha);
    $num_dia=$fecha_array[2];
    $num_mes=$fecha_array[1];
    $num_anio=$fecha_array[0];
    //echo "".$mes;
    $dia_es="";
    $mes_es="";

    switch($dia){
        case "Monday":
            $dia_es="Lunes";
            break;
        case "Tuesday":
            $dia_es="Martes";
            break;
        case "Wednesday":
            $dia_es="Miércoles";
            break;
        case "Thursday":
            $dia_es="Jueves";
            break;
        case "Friday":
            $dia_es="Viernes";
            break;
        case "Saturday":
            $dia_es="Sábado";
            break;
        case "Sunday":
            $dia_es="Domingo";
            break;
    }

    switch($mes){
        case "January":
            $mes_es="Enero";
            break;
        case "February":
            $mes_es="Febrero";
            break;
        case "March":
            $mes_es="Marzo";
            break;
        case "April":
            $mes_es="Abril";
            break;
        case "May":
            $mes_es="Mayo";
            break;
        case "June":
            $mes_es="Junio";
            break;
        case "July":
            $mes_es="Julio";
            break;
        case "August":
            $mes_es="Agosto";
            break;
        case "September":
            $mes_es="Septiembre";
            break;
        case "October":
            $mes_es="Octubre";
            break;
        case "November":
            $mes_es="Noviembre";
            break;
        case "December":
            $mes_es="Diciembre";
            break;
    }

    $fecha_es=$dia_es." ".$num_dia." de ".$mes_es." de ".$num_anio;

    return $fecha_es;
}
/*function nombre_dia($fecha)
{
    date_default_timezone_set('America/El_Salvador');
    //var_dump(setlocale(LC_TIME , 'es_ES.UTF-8'));
    return ucfirst(strftime("%A %d %B de %Y",strtotime($fecha)));
}*/
function num_datos($tabla, $where = "")
{

    $sql = _query("SELECT * FROM $tabla $where");
    $num = _num_rows($sql);
    return number_format($num,0,"",",");
}
function restar_meses($fecha, $nmeses)
{
    $nuevafecha = strtotime ( '-'.$nmeses.' month' , strtotime ( $fecha ) ) ;
    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
    return $nuevafecha;
}
function sumar_meses($fecha, $nmeses)
{
    $nuevafecha = strtotime ( '+'.$nmeses.' month' , strtotime ( $fecha ) ) ;
    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
    return $nuevafecha;
}
function meses($n)
{
    $mes = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
    return $mes[$n-1];
}
function Mayu($cadena)
{
    $mayusculas = strtr(strtoupper($cadena),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
    return $mayusculas;
}
function quitar_tildes($cadena)
{
    $cadena = utf8_encode($cadena);
    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹"," ");
    $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","_");
    $texto = str_replace($no_permitidas, $permitidas ,$cadena);
    return $texto;
}
function get_script_name($id_doctor)
{
    /*if($id_doctor>0)
    {
        $sql = _query("SELECT archivo_consulta FROM especialidad, doctor WHERE doctor.id_doctor='$id_doctor' AND doctor.id_especialidad = especialidad.id_especialidad");
        $datos = _fetch_array($sql);
        $script_name = $datos["archivo_consulta"];
    }
    else
    {*/
        $script_name = "consulta1.php";
    //}
    return $script_name;
}
function edad($fecha)
{
    $dia=date("d");
    $mes=date("m");
    $anio=date("Y");

    list($anio_n, $mes_n, $dia_n)= explode("-", $fecha);

    if (($mes_n == $mes) && ($dia_n > $dia))
    {
        $anio=($anio-1);
    }

    if ($mes_n > $mes)
    {
        $anio=($anio-1);
    }

    $edad=($anio-$anio_n);
    return $edad;
}
function _hora_media_encode($hora){
    $var1=preg_match('/((1[0-2]|0?[1-9]):([0-5][0-9]) ?([AaPp][Mm]))/', $hora);
    if($var1){
        $hora_final = strftime('%H:%M:%S', strtotime($hora));
          return $hora_final;
    }
    else{
        return "00:00:00";
    }

}
function _hora_media_decode($hora){
    $hora_n = explode(":", $hora);
    $sentido="";


    if($hora_n[0] < 12){
        $sentido = "AM";
    }
    if($hora_n[0] > 12){
        $hora_n[0]= $hora_n[0]-12;
        $sentido ="PM";
    }
    if($hora_n[0] == 12){
        $sentido = "PM";
    }
    if($hora_n[0] == "00"){
        $hora_n[0] = 12;
    }
    $hora_final = $hora_n[0].":".$hora_n[1]." $sentido";
    return $hora_final;
}
  
?>
