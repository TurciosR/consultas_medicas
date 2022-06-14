<?php
	include ("_conexion.php");
	$query = $_REQUEST["query"];
	$depto = $_REQUEST['depto'];
	//$entrada=_query("SELECT * FROM ".EXTERNAL.".servicios_hospitalarios WHERE descripcion LIKE '%{$query}%' AND id_depto='$depto'");
	$entrada=_query("SELECT * FROM ".EXTERNAL.".servicios_hospitalarios WHERE descripcion LIKE '%{$query}%' AND deleted is NULL");
	$datos = array();
	while($raw=_fetch_array($entrada))
	{
		$datos[]= $raw["id_servicio"]."| ".$raw["descripcion"]."| ".$raw["precio"];
	}
	echo json_encode($datos);
?>
