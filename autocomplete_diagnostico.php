<?php
	include ("_conexion.php");
	$query = $_REQUEST["query"];
	$entrada=_query("SELECT * FROM diagnostico WHERE descripcion LIKE '%$query%'");
	$datos = array();
	while($raw=_fetch_array($entrada))
		{	
			$datos[]= $raw["id_diagnostico"]."| ".$raw["descripcion"];
		}
		echo json_encode($datos);
?>
