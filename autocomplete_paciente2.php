<?php
include_once "_core.php";
$id_sucursal=$_SESSION["id_sucursal"];
$query = $_REQUEST['query'];
$sql0="SELECT paciente.id_paciente,paciente.direccion, paciente.dui, paciente.nombres, paciente.apellidos, paciente.fecha_nacimiento, paciente.sexo FROM paciente WHERE CONCAT(nombres,' ',COALESCE(paciente.apellidos,'')) LIKE '%$query%'";
$result = _query($sql0);
$array_prod = array();
while ($row = _fetch_array($result)) {
    $array_prod[] =$row['id_paciente']."| ".$row['nombres']." ".$row['apellidos']." |".ED($row['fecha_nacimiento'])."|".$row['sexo']."|".$row['direccion']."|".$row['dui'];
}
//echo $array_prod;
echo json_encode ($array_prod);?>
