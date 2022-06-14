<?php
//Inclusion de funciones de conexion y session y definicion de variables de conexion
include_once "_core.php";
/*
 * Clase de personalizacion de Data Table, para uso como Server Side
 * Nelson Borland J. Vides
 */
 
// Tabla DB
$table = 'credito';
 
// Llave de la Tabla
$primaryKey = 'id_credito';
 
// Array con las columnas de la tabla
//Las llaves definidas por db corresponden a los nombres de las columnas de la tabla
//Las llaves definidas con dt corresponden a las columnas de la tabla como indices numericos
//Formatter, personalizacion del campo de la tabla, permite dar estilo a los datos mostrados en esa columna
$columns = array(
    array( 'db' => 'id_credito', 'dt' => 0 ),
    array( 'db' => 'cliente',  'dt' => 1 ),
    array( 'db' => 'monto',  'dt' => 2, 'formatter' => function($monto, $row){
    	$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
	    $datos_moneda = _fetch_array($sql0);
	    $simbolo = $datos_moneda["simbolo"];  
	    return $simbolo."".number_format($monto,2,'.',',');}, 'field'=> 'monto' ),
   	array( 'db' => 'abonado',  'dt' => 3, 'formatter' => function($abonado, $row){
   		$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
	    $datos_moneda = _fetch_array($sql0);
	    $simbolo = $datos_moneda["simbolo"];  
	    return $simbolo."".number_format($abonado,2,'.',',');}, 'field'=> 'abonado' ),
   	array( 'db' => 'saldo',  'dt' => 4, 'formatter' => function($saldo, $row){
   		$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
	    $datos_moneda = _fetch_array($sql0);
	    $simbolo = $datos_moneda["simbolo"];  
	    return $simbolo."".number_format($saldo,2,'.',',');}, 'field'=> 'saldo' ),
   	array( 'db' => 'fecha_inicio',  'dt' => 5, 'formatter' => function($fecha_inicio, $row){
    	return ED($fecha_inicio);}, 'field'=> 'fecha_inicio' ),
	array( 'db' => 'fecha_fin',  'dt' => 6, 'formatter' => function($fecha_fin, $row){
    	return ED($fecha_fin);}, 'field'=> 'fecha_fin' ),
	array( 'db' => 'estado',  'dt' => 7, 'formatter' => function($estado, $row){
		if($estado =="PENDIENTE")
		{
			$res = "<label class='badge bg-green'>".$estado."</label>";
		}
		else if($estado =="FINALIZADO")
		{
			$res = "<label class='badge bg-warning'>".$estado."</label>";
		}
    	return $res;}, 'field'=> 'estado' ),
    array( 'db' => 'id_credito',  'dt' => 8, 'formatter' => function($id_credito, $row){
        $id_user=$_SESSION["id_usuario"];
        $admin=$_SESSION["admin"]; 
        $boton = "<div class=\"btn-group\">
                    <a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
                    <ul class=\"dropdown-menu dropdown-primary\">";
        $filename='ver_detalle_credito.php';
        $link=permission_usr($id_user,$filename);
        if ($link!='NOT' || $admin=='1' )
            $boton .= "<li><a data-toggle='modal' href='ver_detalle_credito.php?id_credito=".$id_credito."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver Detalle</a></li>";
        $filename='admin_detalle_credito.php';
        $link=permission_usr($id_user,$filename);
        if ($link!='NOT' || $admin=='1' )
            $boton .= "<li><a href='admin_detalle_credito.php?id_credito=".$id_credito."'><i class=\"fa fa-pencil\"></i> Ver Pagos</a></li>";
        $filename='reporte_credito_pdf.php';
        $link=permission_usr($id_user,$filename);
        if ($link!='NOT' || $admin=='1' )
            $boton .= "<li><a href='reporte_credito_pdf.php?id_credito=".$id_credito."' target='_blank'><i class=\"fa fa-print\"></i> Imprimir</a></li>";
        $boton .= "</ul>
            </div>";
        return $boton;}, 'field' => 'id_credito'),
);
 
// Conexion a la Base de datos, variables traidas de _conexion.php incluidas en _core.php
$sql_details = array(
    'user' => $usuario,
    'pass' => $clave,
    'db'   => $dbname,
    'host' => $servidor
);
 
 
//Clase que ejecuta el parseo de datos de php hacia le data table
require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);