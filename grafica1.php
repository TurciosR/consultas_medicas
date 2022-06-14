<?php
	include '_core.php';
	$anio = date("Y");
	$inicio = "$anio-01-01";
	$fin = "$anio-12-31";
	$query = "SELECT id FROM reserva_cita WHERE fecha_cita BETWEEN '$inicio' AND '$fin' AND estado<5";
	$query2 = "SELECT id FROM reserva_cita WHERE fecha_cita BETWEEN '$inicio' AND '$fin' AND estado=7";
	
	$result = _query($query);
	$result2 = _query($query2);
	
	$num = _num_rows($result);
	$num2 = _num_rows($result2);
	
	$data[] = array(
		"Canceladas" => $num, 
		"Finalizadas" => $num2, 
	);

	echo json_encode($data);
?>