<?php
	include ("_conexion.php");
	$query = $_REQUEST["query"];
	$entrada=_query("SELECT * FROM paciente WHERE CONCAT(nombres,' ',apellidos,' ',expediente) LIKE '%$query%'");
	$datos = array();
	while($raw=_fetch_array($entrada))
		{	
			$nombre = $raw["nombres"];
			$apellido = $raw["apellidos"];
			$expediente = $raw["expediente"];
			$len = strlen((string)$expediente);
		    $fill = 7 - $len;
		    if($fill <0)
		        $fill = 0;
		    $n_exp = zfill($expediente, $fill);
			$datos[]= $raw["id_paciente"]."| ".$nombre." ".$apellido." |".$n_exp;
		}
		echo json_encode($datos);
?>
