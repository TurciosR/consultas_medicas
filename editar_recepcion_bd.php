<?php
    include_once "_core.php";
    date_default_timezone_set('America/Tegucigalpa');
    function editar(){
    $recepcion = $_SESSION['idRecepcion'];
    $id_user=$_SESSION["id_usuario"];
    $id_sucursal=$_SESSION['id_sucursal'];
    $fecha_actual = date("Y-m-d");
    $idDoctor=$_POST["doctor"];
    $doctor_refiere = $_POST['doctor_refiere'];
    $fechaEntrada=$_POST["fechaEntrada"];
    $horaEntrada=$_POST["hora_entrada"];

    $descripcionEvento=$_POST["descripcionEvento"];
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


    if($seguir == 1){

    $table = 'recepcion';
    $form_data = array(
        'evento' => $descripcionEvento,
        'fecha_de_entrada' => $fechaIngresar,
        'id_doctor_recepcion' => $idDoctor,
        'id_usuario_recepcion' => $id_user,
        'id_sucursal_recepcion'=>$id_sucursal,
        'doctor_refiere' => $doctor_refiere
    );
    $insertar = _update($table,$form_data, " WHERE id_recepcion = $recepcion" );
    if($insertar){
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Recepcion editada correctamente';
        $xdatos['process']='insert';
    }
    else{
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='La Recepcion no pudo ser editada';
        $xdatos['process']='insert';
    }
    }
    echo json_encode($xdatos);
}
    if(isset($_POST['process']))
    {

        switch ($_POST['process'])
        {
            case 'edit':
                editar();
                break;
        }
    }
?>