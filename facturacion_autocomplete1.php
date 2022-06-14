<?php
include_once "_core.php";

$query = $_REQUEST['query'];
$id_usb = $_REQUEST['id_usb'];
$sql="SELECT pr.id_producto , pr.descripcion, pr.barcode,  su.stock as cantidad FROM ".EXTERNAL.".producto pr JOIN ".EXTERNAL.".stock as su ON pr.id_producto=su.id_producto INNER JOIN ".EXTERNAL.".stock_ubicacion on pr.id_producto = ".EXTERNAL.".stock_ubicacion.id_producto WHERE su.stock>0 AND ".EXTERNAL.".stock_ubicacion.id_ubicacion = '$id_usb' AND (pr.descripcion LIKE '%{$query}%'  )ORDER BY pr.descripcion ASC";
	//echo $sql;
$result = _query($sql);
$numrows = _num_rows($result);
$array_prod = array();
if ($numrows>0){
	/*
	$row1 = _fetch_array($result1);
	$id_producto=
	$sql_existencia = "SELECT su.id_producto, su.cantidad, su.id_ubicacion, u.id_ubicacion, u.bodega
	FROM stock_ubicacion as su, ubicacion as u
	WHERE su.id_producto = '$id_producto' AND su.id_ubicacion = u.id_ubicacion AND u.bodega != 1 ORDER BY su.id_su ASC";
	$resul_existencia = _query($sql_existencia);
	$cuenta_existencia = _num_rows($resul_existencia);
	*/

	while ($row = _fetch_assoc($result)) {
			if ($row['barcode']=="")
				$barcod=" ";
			else
				$barcod=" [".$row['barcode']."] ";


			$array_prod[] =$row['id_producto']."|".$barcod."|".$row['descripcion']."|P";
	}
}
	echo json_encode ($array_prod); //Return the JSON Array
?>
