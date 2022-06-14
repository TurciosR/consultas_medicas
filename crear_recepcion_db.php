<?php
    include_once "_core.php";
    function insertar(){
    $id_user=$_SESSION["id_usuario"];
    $id_sucursal=$_SESSION['id_sucursal'];
    $fecha_actual = date("Y-m-d");
    $idDoctor=$_POST["doctor"];
    if($idDoctor == ""){
        $idDoctor = 0;
    }
    $fechaEntrada=$_POST["fechaEntrada"];
    $horaEntrada=$_POST["hora_entrada"];
    $emergencia=$_POST["emergencia"];
    $doctorReferido=$_POST["doctor_refiere"];
    if($doctorReferido == ""){
        $doctorReferido = 0;
    }
    $idPaciente=$_POST["paciente_replace"];
    $descripcionEvento=$_POST["descripcionEvento"];
    $parienteResponsable=$_POST["parienteResponsable"];
    $pariente=$_POST["pariente"];
    $telefonoPariente=$_POST["telefonoPariente"];
    $idParentezcoSelect=$_POST["parentezcoSelect"];
    $otroParentezco=$_POST["otroParentezco"];
    $tipoRecepcion=$_POST["tipo_recepcion"];
    $recuperadoBase = $_POST['recuperadoBase'];
    $naci = $_POST['naci'];
    $paciente_ambulatorio = $_POST['paciente_ambulatorio'];
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['fecha_nacimiento'])) {
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
    } else {
        $fecha_nacimiento = MD($_POST['fecha_nacimiento']);
    }
    $sexo = $_POST['sexo'];
    if($sexo == "MASCULINO"){
        $sexo = 2;
    }
    if($sexo == "FEMENINO"){
        $sexo = 1;
    }
    $paciente = $_POST['paciente'];
    $fechaNuevo = explode("-",$fechaEntrada);
    $fechaEntrada=  $fechaNuevo[2]."-".$fechaNuevo[1]."-".$fechaNuevo[0]."";
    $horaNuevo = explode(" ",$horaEntrada);
    $bandera = explode(":",$horaNuevo[0]);
    if($horaNuevo[1] == "PM" && $bandera[0] != 12){
        $bandera[0] = $bandera[0] + 12;
    }
    $horaEntrada = $bandera[0].":".$bandera[1].":00";
    $fechaIngresar = $fechaEntrada." ".$horaEntrada;
    $seguir = 1;
    $paciente_replace = $idPaciente;
    if($seguir == 1){
        $table = 'recepcion';
        if($pariente == ""){
            $form_data = array(
                'evento' => $descripcionEvento,
                'fecha_de_entrada' => $fechaIngresar,
                'id_paciente_recepcion' => $idPaciente,
                'id_doctor_recepcion' => $idDoctor,
                'id_usuario_recepcion' => $id_user,
                'id_estado_recepcion'=>"2",
                'id_sucursal_recepcion'=>$id_sucursal,
                'id_tipo_recepcion'=>'15',
                'recuperado_base'=>$recuperadoBase,
                'doctor_refiere' => $doctorReferido
                );
        }
        else{
            $form_data = array(
                'evento' => $descripcionEvento,
                'nombre_pariente' => $pariente,
                'fecha_de_entrada' => $fechaIngresar,
                'telefono_contacto' => $telefonoPariente,
                'id_pariente_contacto' => $idParentezcoSelect,
                'otro ' => $otroParentezco,
                'id_paciente_recepcion' => $idPaciente,
                'id_doctor_recepcion' => $idDoctor,
                'id_usuario_recepcion' => $id_user,
                'id_estado_recepcion'=>"2",
                'id_sucursal_recepcion'=>$id_sucursal,
                'id_tipo_recepcion'=>'15',
                'recuperado_base'=>$recuperadoBase,
                'doctor_refiere' => $doctorReferido
                );
        }
        $sql_exis = _query("SELECT * FROM paciente WHERE id_paciente=$idPaciente");
        $num_exis = _num_rows($sql_exis);
        if($num_exis==0){
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Ese Paciente no se encuentra registrado';
            $xdatos['process']='insert';
        }
        else{
            $consulta = "SELECT paciente.nombres, paciente.apellidos, recepcion.fecha_de_entrada, 
            estado_recepcion.estado FROM recepcion INNER JOIN estado_recepcion on 
            estado_recepcion.id_estado_recepcion=recepcion.id_estado_recepcion INNER JOIN 
            paciente on paciente.id_paciente = recepcion.id_paciente_recepcion WHERE 
            paciente.id_paciente = $idPaciente AND  recepcion.fecha_de_entrada=".date('Y-m-d')." AND (estado_recepcion.id_estado_recepcion = 1 
            OR estado_recepcion.id_estado_recepcion = 2)";
            $sql_exis1 = _query($consulta);
            $num_exis1 = _num_rows($sql_exis1);
            if($num_exis1==0){
                $insertar = _insert($table,$form_data );
                if($insertar){
                    $xdatos['typeinfo']='Success';
                    $xdatos['msg']='Recepcion ingresada correctamente';
                    $xdatos['process']='insert';
                }

            }
            else{
                $row=_fetch_array($sql_exis1);
                $fechaHora = $row['fecha_de_entrada'];
                $fechaHora = explode(" ",$fechaHora);
                $fecha = $fechaHora[0];
                $hora = $fechaHora[1];
                $xdatos['typeinfo']='Error';
                $xdatos['msg']="El paciente ".$row['nombres']." ".$row['apellidos']."  tiene una recepcion en estado ".$row['estado']." realizada el $fecha a las $hora. ";
                $xdatos['process']='insert';
            }
            
        }
    }
    else{
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Paciente no pudo ser ingresado';
        $xdatos['process']='insert';
    }
    echo json_encode($xdatos);
}
    if(isset($_POST['process']))
    {
        
        switch ($_POST['process'])
        {
            case 'insert':
                insertar();
                break;
        }
    }
?>