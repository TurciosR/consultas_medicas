<?php
    include "_core.php"; 
    $query = _query("SELECT c.*, e.color, CONCAT(d.nombres,' ',d.apellidos) as doctor, es.descripcion FROM reserva_cita as c, estado_cita as e, doctor as d, espacio as es WHERE e.id_estado=c.estado AND d.id_doctor=c.id_doctor AND es.id_espacio=c.id_espacio AND c.estado<6");
    $i=0;
    while($row = _fetch_array($query))
    {
        $hora_ini = strtotime($row["hora_cita"]);
        $hora_fin = strtotime('+30 minute', $hora_ini);
        $hora_ini = date("H:i:s", $hora_ini);
        $hora_fin = date("H:i:s", $hora_fin);
        $futuro = $row['futura'];
        $color = $row["color"];
        if($futuro == 1){
            $color = "#3386FF";

        }
        $xdata[$i] = array(
            "id" => $row["id"],
            "title" => buscar($row["id_paciente"])." (".$row["doctor"].", ".$row["descripcion"].")",
            "start" => $row["fecha_cita"].' '.$hora_ini,
            "end" => $row["fecha_cita"].' '.$hora_fin,
            "color" => $color
        );
    $i++;
    }
    echo json_encode($xdata);
?>