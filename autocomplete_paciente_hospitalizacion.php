<?php
include_once "_core.php";
$id_sucursal=$_SESSION["id_sucursal"];
$query = $_REQUEST['query'];
$sql0="SELECT r.id_recepcion, p.id_paciente, CONCAT(p.nombres, '|', p.apellidos) AS nombre_paciente, 
r.evento, r.fecha_de_entrada FROM recepcion AS r LEFT JOIN 
paciente AS p ON r.id_paciente_recepcion=p.id_paciente 
WHERE r.recepcion_hospitalizacion=1 AND 
r.id_tipo_recepcion=1 AND 
r.deleted IS NULL AND(p.nombres LIKE '%$query%' OR p.apellidos LIKE '%$query%') 
AND id_sucursal_recepcion=1";

$result = _query($sql0);
$array_prod = array();

while ($row = _fetch_array($result)) {
    $fecha_entrada = $row['fecha_de_entrada'];
    $fecha_entrada = explode(" ", $fecha_entrada);
    $fecha = ED($fecha_entrada[0]);
    $hora = _hora_media_decode($fecha_entrada[1]);
    $array_prod[] =$row['id_paciente'].'|'.$row['nombre_paciente']."|".$row['evento']."|".$fecha."|".$hora;
}
//echo $array_prod;
echo json_encode ($array_prod);?>
