<?php
    include "_conexion.php";
    $now = date("Y-m-d");
    $id_doctor = $_POST['id'];
    if($id_doctor == 0){
        $sqln = _query("SELECT cd.prioridad as turno, concat(p.nombres,' ',p.apellidos) as paciente, concat(d.nombres,' ',d.apellidos) as doctor, e.descripcion as espacio, es.descripcion as estado, es.color  FROM cola_dia as cd, paciente as p, doctor as d, espacio as e, reserva_cita as r, estado_cita as es WHERE cd.fecha='$now' AND r.id=cd.id_cita AND p.id_paciente=r.id_paciente AND d.id_doctor=r.id_doctor  AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.estado<4 AND cd.prioridad>0 ORDER BY cd.prioridad ASC");
        $n = _num_rows($sqln);
        if($n >0)
        {
        
            $sql = _query("SELECT cd.prioridad as turno, concat(p.nombres,' ',p.apellidos) as paciente, concat(d.nombres,' ',d.apellidos) as doctor, e.descripcion as espacio, es.descripcion as estado, es.color  FROM cola_dia as cd, paciente as p, doctor as d, espacio as e, reserva_cita as r, estado_cita as es WHERE cd.fecha='$now' AND r.id=cd.id_cita AND p.id_paciente=r.id_paciente AND d.id_doctor=r.id_doctor  AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.estado<4 AND cd.prioridad>0 ORDER BY cd.prioridad ASC LIMIT 1,$n");
            $table = "";
            while($row = _fetch_array($sql))
            {
                $table .= "<tr style='font-size:26px; font-weight:bold;'>";
                $table .= "<td>".$row["paciente"]."</td>";
                $table .= "<td>".$row["espacio"]."</td>";
                $table .= "<td>".$row["doctor"]."</td>";
                $table .= "<td>".$row["turno"]."</td>";
                //$table .= "<td><label class='badge' style='background:".$row["color"]."; color:#FFF; font-size:26px; font-weight:bold;'>".$row["estado"]."</label></td>";
                $table .= "</tr>";
            }
        }
        else
        {
            $table = "<tr style='font-size:26px; font-weight:bold;' class='text-center'><td colspan='4'>SIN PACIENTES EN COLA</td></tr>";   
        }
    }
    else{
        $sqld=_query("SELECT min(id_doctor) as id_doctor FROM cola_dia WHERE fecha='$now' AND id_doctor=$id_doctor");
        $id_doctor = _fetch_array($sqld)["id_doctor"];
        if($id_doctor<1)
        {
            $sqld=_query("SELECT min(id_doctor) as id_doctor FROM cola_dia WHERE fecha='$now' AND id_doctor>0");
            $id_doctor = _fetch_array($sqld)["id_doctor"];
        }
        //echo "SELECT cd.prioridad as turno, concat(p.nombres,' ',p.apellidos) as paciente, concat(d.nombres,' ',d.apellidos) as doctor, e.descripcion as espacio, es.descripcion as estado, es.color  FROM cola_dia as cd, paciente as p, doctor as d, espacio as e, reserva_cita as r, estado_cita as es WHERE cd.fecha='$now' AND r.id=cd.id_cita AND p.id_paciente=r.id_paciente AND d.id_doctor=r.id_doctor AND cd.id_doctor='$id_doctor' AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.estado<4 AND cd.prioridad>0 ORDER BY cd.prioridad ASC";
        $sqln = _query("SELECT cd.prioridad as turno, concat(p.nombres,' ',p.apellidos) as paciente, concat(d.nombres,' ',d.apellidos) as doctor, e.descripcion as espacio, es.descripcion as estado, es.color  FROM cola_dia as cd, paciente as p, doctor as d, espacio as e, reserva_cita as r, estado_cita as es WHERE cd.fecha='$now' AND r.id=cd.id_cita AND p.id_paciente=r.id_paciente AND d.id_doctor=r.id_doctor AND cd.id_doctor='$id_doctor' AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.estado<4 AND cd.prioridad>0 ORDER BY cd.prioridad ASC");
        $n = _num_rows($sqln);
        if($n >0)
        {
        
            $sql = _query("SELECT cd.prioridad as turno, concat(p.nombres,' ',p.apellidos) as paciente, concat(d.nombres,' ',d.apellidos) as doctor, e.descripcion as espacio, es.descripcion as estado, es.color  FROM cola_dia as cd, paciente as p, doctor as d, espacio as e, reserva_cita as r, estado_cita as es WHERE cd.fecha='$now' AND r.id=cd.id_cita AND p.id_paciente=r.id_paciente AND d.id_doctor=r.id_doctor AND cd.id_doctor='$id_doctor' AND e.id_espacio=r.id_espacio AND es.id_estado=r.estado AND r.estado<4 AND cd.prioridad>0 ORDER BY cd.prioridad ASC LIMIT 1,$n");
            $table = "";
            while($row = _fetch_array($sql))
            {
                $table .= "<tr style='font-size:26px; font-weight:bold;'>";
                $table .= "<td>".$row["paciente"]."</td>";
                $table .= "<td>".$row["espacio"]."</td>";
                $table .= "<td>".$row["doctor"]."</td>";
                $table .= "<td>".$row["turno"]."</td>";
                //$table .= "<td><label class='badge' style='background:".$row["color"]."; color:#FFF; font-size:26px; font-weight:bold;'>".$row["estado"]."</label></td>";
                $table .= "</tr>";
            }
        }
        else
        {
            $table = "<tr style='font-size:26px; font-weight:bold;' class='text-center'><td colspan='4'>SIN PACIENTES EN COLA</td></tr>";   
        }
    }
    
    echo $table;
?>