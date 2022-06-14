<?php
include_once "_core.php";
function medico(){
    $table = 'doctor';
    
    $form_data = array( 
    'id_doctor' => -999,
    'nombres' => "Lázaro",
    'apellidos' => "Martínez",
    'sexo' => "Masculino",
    'fecha_nac' => "1988-02-21",
    'email' => "dosctorx@gmail.com",
    'direccion' => "Casa del doctor X",
    'telefono' => "7777-7777",
    'id_especialidad' => -999,
    'jvpm' => "145722"
    );      
    $insertar = _insert($table,$form_data );
    if($insertar)
    {
        
        $table_u = "usuario";
        $form_data_u = array(
            'id_usuario' => -999,
            'id_doctor' => -999,
            'nombre' => "Lázaro Martínez",
            'usuario' => "doctorL",
            'password' => md5("123456"),
            'tipo_usuario' => 1,
            'activo' => 1
        );
        $insert_u = _insert($table_u, $form_data_u);
    }
    
    
}
function especialidad()
{

    $table = 'especialidad';
    
    $form_data = array( 
    'id_especialidad' => -999,
    'descripcion' => "Medico general"
    );
    $insertar = _insert($table,$form_data );

}
function consultorio(){

    $table = 'espacio';
    $form_data = array(	
    'id_espacio' => -999,
    'descripcion' => 'Consultorio AbcXyz',
    'observaciones' => 'Siempre disponible',
    );   	
    $insertar = _insert($table,$form_data );
         
}
function diagnostico(){
	
    $table = 'diagnostico';
    
    $form_data = array(	
    'id_diagnostico' => -999,
    'descripcion' => 'Diagnostico AbcXyz'
    ); 
    $insertar = _insert($table,$form_data );
       
}
function examen(){
    $table = 'examen';
    
    $form_data = array(	
    'id_examen' => -999,
    'descripcion' => 'examen AbcXyz',
    'observaciones' => 'AbcXyz'
    );   	
    
    $insertar = _insert($table,$form_data );
        
}
function servicio(){
	$descripcion=$_POST["descripcion"];
    $precio=$_POST["precio"];

    $table = 'servicio';
    
    $form_data = array(	
    'id_servicio' => -999,
    'descripcion' => 'Servicio AbcXyz',
    'precio' => 1000
    );  
    $insertar = _insert($table,$form_data );
           
}
function paciente(){
    $fecha_actual = date("Y-m-d");
    $table = 'paciente';
    
    $form_data = array( 
    'id_paciente' => -999,
    'nombres' => "Juan",
    'apellidos' => "Perez",
    'sexo' => "Masculino",
    'fecha_nacimiento' => "1994-05-07",
    'email' => "juan_paciente@gmail.com",
    'municipio' => "34",
    'direccion' => "Casa de juan",
    'tel1' => "7474-7474",
    'tel2' => "7575-7575",
    'notificaciones'=>"Whatsapp",
    'expediente'=>-999,
    'fecha_registro'=>$fecha_actual
    );      
    $insertar = _insert($table,$form_data );     
}
function cita(){
    
    $hora = "00:00:00";
    $table = 'reserva_cita';
    $now = date("Y-m-d");
    $form_data = array( 
    'id' => -999,
    'fecha_cita' => $now,
    'hora_cita' => $hora,
    'id_paciente' => -999,
    'id_doctor' => -999,
    'id_espacio' => -999,
    'id_usuario' => -999,
    'motivo_consulta' => "Dolor en el cuello",
    'estado' => 1
    );
    $insertar = _insert($table,$form_data );
}

function id_cola()
{
    $sql =_query("SELECT id_cola FROM cola_dia WHERE id_cita='-999'");
    $datos=  _fetch_array($sql);
    $a["id"]=  $datos["id_cola"];
    echo json_encode($a);
}

function eliminar(){

    $tablas = array(
        "espacio",
        "diagnostico",
        "examen",
        "servicio",
        "doctor",
        "especialidad",
        "usuario",
        "paciente",
        "reserva_cita",
        "cola_dia", 
        "signos_vitales", 
        "diagnostico_paciente", 
        "receta", 
        "plan_vacuna", 
        "vacuna_dia", 
        "examen_paciente", 
        "detalle_plan", 
        "factura", 
        "detalle_factura"
        );
    $ids = array(
        "id_espacio",
        "id_diagnostico",
        "id_examen",
        "id_servicio",
        "id_doctor",
        "id_especialidad",
        "id_usuario",
        "id_paciente",
        "id", 
        "id_cita", 
        "id_cita", 
        "id_cita", 
        "id_cita", 
        "id_cita", 
        "id_cita", 
        "id_cita", 
        "id_plan", 
        "id_paciente", 
        "id_factura"
        );

    $id_plan = _fetch_array(_query("SELECT id_plan FROM plan_vacuna WHERE id_cita='-999'"))["id_plan"];
    $id_factura = _fetch_array(_query("SELECT id_factura FROM factura WHERE id_paciente='-999'"))["id_factura"];

    for ($i=0; $i < count($tablas); $i++) { 
        $tabla = $tablas[$i];
        $where = $ids[$i]."='-999'";
        if($i==16)
        {
            $where=$ids[$i]."='$id_plan'";
        }
        if($i==18)
        {
            $where=$ids[$i]."='$id_factura'";
        }
        $eliminar = _delete($tabla, $where);
        if(!$eliminar){
            echo _error();
        }
    }
}
function evaluacion()
{
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    $table = 'signos_vitales';

    $form_data = array(
        'id_signo' => '-999',
        'id_paciente' => '-999',
        'id_cita' => '-999',
        'estatura' => '1.75',
        'peso' => '175',
        'temperatura' => '28',
        'presion' => '128',
        'frecuencia_cardiaca' => '0',
        'frecuencia_respiratoria' => '0',
        'fecha' => $fecha,
        'hora' => $hora,
        'observaciones' => 'Ninguna',
        'id_usuario' => '-999'
        );

    $insert = _insert($table,$form_data);
    if ($insert)
    {
        echo "Insertado";
    }
    else
    {
        echo _error();
    }
}
function plan()
{
    $sql = _query("SELECT id_plan FROM plan_vacuna WHERE id_cita='-999'");
    $result = _fetch_array($sql);
    $datax["id"] = $result["id_plan"];
    echo json_encode($datax);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(!isset($_POST['process']))
{
    initial(); 
}
else
{
    if(isset($_POST['process']))
    {	
        switch ($_POST['process'])
        {
            case 'medico':
                medico();
            break;
            case 'especialidad':
                especialidad();
            break;
            case 'consultorio':
                consultorio();
            break;
            case 'diagnostico':
                diagnostico();
            break;
            case 'examen':
                examen();
            break;
            case 'servicio':
                servicio();
            break;
            case 'paciente':
                paciente();
            break;
            case 'cita':
                cita();
            break;
            case 'eliminar_all':
                eliminar();
            break;
            case 'id_cola':
                id_cola();
            break;
            case 'evaluacion':
                evaluacion();
            break;
            case 'plan':
                plan();
            break;
        } 
    }			
}
?>