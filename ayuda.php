<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
	$title='Ayuda';
	$_PAGE = array ();
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";

	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
?>
<div class="row wrapper border-bottom white-bg page-heading"></div>
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
   	        	 	<?php
               	 		if ($links!='NOT' || $admin=='1' ){
               		?>
                    <div class="ibox-title">
                         <h3 style="color:#194160;"><i class="fa fa-question-circle"></i> <b><?php echo $title;?></b></h3>
                    </div>
                    <div class="ibox-content">
	                    <div class="row"> 
		                    <div class="col-lg-6">
		                    <div class="ibox float-e-margins">
		                        <div class="ibox-title bg-info">
		                            <div class="ibox-tools">
		                                
		                            </div>
		                        </div>
		                        <div class="ibox-content">
		                             <figure>
                                		<iframe width="455" height="250" src="https://www.youtube.com/embed/04RRTdXEzkQ" frameborder="0" allowfullscreen></iframe>
                            		</figure>
		                        </div>
		                    </div>
		                	</div>
		                	<div class="col-lg-6">
		                    <div class="ibox float-e-margins">
		                        <div class="ibox-title bg-info">
		                            <div class="ibox-tools">
		                                
		                            </div>
		                        </div>
		                        <div class="ibox-content">
		                             <figure>
                                		<iframe width="455" height="250" src="https://www.youtube.com/embed/ZkdrAqqhddQ" frameborder="0" allowfullscreen></iframe>
                            		</figure>
		                        </div>
		                    </div>
		                	</div>
		                	<div class="col-lg-6">
		                    <div class="ibox float-e-margins">
		                        <div class="ibox-title bg-info">
		                            <div class="ibox-tools">
		                                
		                            </div>
		                        </div>
		                        <div class="ibox-content">
		                             <figure>
                                		<iframe width="455" height="250" src="https://www.youtube.com/embed/GAoDaD4mag8" frameborder="0" allowfullscreen></iframe>
                            		</figure>
		                        </div>
		                    </div>
		                	</div>
		                	<div class="col-lg-6">
		                    <div class="ibox float-e-margins">
		                        <div class="ibox-title bg-info">
		                            <div class="ibox-tools">
		                                
		                            </div>
		                        </div>
		                        <div class="ibox-content">
		                             <figure>
                                		<iframe width="455" height="250" src="https://www.youtube.com/embed/zq2ab_JcI9I" frameborder="0" allowfullscreen></iframe>
                            		</figure>
		                        </div>
		                    </div>
		                	</div>
		                	<div class="col-lg-6">
		                    <div class="ibox float-e-margins">
		                        <div class="ibox-title bg-info">
		                            <div class="ibox-tools">
		                                
		                            </div>
		                        </div>
		                        <div class="ibox-content">
		                             <figure>
                                		<iframe width="455" height="250" src="https://www.youtube.com/embed/XVYoCLOi3Y4" frameborder="0" allowfullscreen></iframe>
                            		</figure>
		                        </div>
		                    </div>
		                	</div>
		                	<div class="col-lg-6">
		                    <div class="ibox float-e-margins">
		                        <div class="ibox-title bg-info">
		                            <div class="ibox-tools">
		                                
		                            </div>
		                        </div>
		                        <div class="ibox-content">
		                             <figure>
                                		<iframe width="455" height="250" src="https://www.youtube.com/embed/fPBufd0k3n8" frameborder="0" allowfullscreen></iframe>
                            		</figure>
		                        </div>
		                    </div>
		                	</div>
		                	<div class="col-lg-6">
		                    <div class="ibox float-e-margins">
		                        <div class="ibox-title bg-info">
		                            <div class="ibox-tools">
		                                
		                            </div>
		                        </div>
		                        <div class="ibox-content">
		                             <figure>
                                		<iframe width="455" height="250" src="https://www.youtube.com/embed/9ZXajieHODY" frameborder="0" allowfullscreen></iframe>
                            		</figure>
		                        </div>
		                    </div>
		                	</div>
		                	<div class="col-lg-6">
		                    <div class="ibox float-e-margins">
		                        <div class="ibox-title bg-info">
		                            <div class="ibox-tools">
		                                
		                            </div>
		                        </div>
		                        <div class="ibox-content">
		                             <figure>
                                		<iframe width="455" height="250" src="https://www.youtube.com/embed/qcWrXfJKeyQ" frameborder="0" allowfullscreen></iframe>
                            		</figure>
		                        </div>
		                    </div>
		                	</div>
	                	</div> 
	                <div class="row"></div>
            	</div>
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_cobro.js'></script>";

} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function insert()
{

	$fecha= MD($_POST['fecha']);
	$total_final= $_POST['total'];
	$dui= $_POST['dui'];
	$pago= $_POST['pago'];
	$tipo_c= $_POST['tipo_c'];
	$documento= $_POST['documento'];
	$stringdatos = $_POST['datos'];
	$id_paciente = $_POST['id_paciente'];
	if($tipo_c == "General")
	{
		$id_paciente = 0;
	}
	$cliente = $_POST["cliente"];

	_begin();
	$listadatos=explode('|',$stringdatos);
	
	$update = false;
	$cuantos = $_POST["cuantos"];
	$validos = 0;
	$table = 'factura';
	if ($cuantos>0)
	{
		$form_data = array(
			'id_paciente'=>$id_paciente,
			'fecha'=>$fecha,
			'total'=>$total_final,
			'tipo_pago'=>$pago,
			'dui'=>$dui,
			'documento'=>$documento,
			'cliente'=>$cliente,
			'tipo'=>'Ingreso',
			'concepto'=>'Ingresos por Servicios'
			);
		$update = _insert($table,$form_data);
		$id_factura= _insert_id();

		for ($i=0;$i<$cuantos ;$i++)
		{
			list($id_servicio,$cantidad,$precio)=explode(',',$listadatos[$i]);
			$subtotal = $cantidad*$precio; 
			$table2 = 'detalle_factura';

			$form_data2 = array(
				'id_factura'=>$id_factura,
				'id_servicio'=>$id_servicio,
				'cantidad'=>$cantidad,
				'precio'=>$precio,
				'subtotal'=>$subtotal
			);

			$update2 = _insert($table2,$form_data2 );
			if($update2)
			{
				$validos ++;
			}
		}
	}


	if($update && $cuantos==$validos)
	{
		_commit();
       $xdatos['typeinfo']='Success';
       $xdatos['msg']='Cobro ingresado con exito!';
       $xdatos['process']='insert';
	}
	else
	{
		_rollback(); 
       $xdatos['typeinfo']='Error';
       $xdatos['msg']='Cobro no pudo ser ingresado !';
	}
	echo json_encode($xdatos);
}

function consultar_servicio()
{
	$id_servicio=$_POST["id_servicio"];
	$sql0="SELECT * FROM servicio WHERE id_servicio='$id_servicio'";
	$sql=_query($sql0);
	while($row=_fetch_array($sql))
	{
		$descripcion=$row["descripcion"];
		$precio=$row["precio"];
	}
	$xdatos['descripcion']=$descripcion;
	$xdatos['precio']=$precio;
	echo json_encode($xdatos); //Return the JSON Array
}

function total_texto()
{
	$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
    $datos_moneda = _fetch_array($sql0);
    $simbolo = $datos_moneda["simbolo"];  
    $moneda = $datos_moneda["moneda"]; 

	$total=number_format($_POST['total'],2,".","");
	$lista = explode('.',$total);
	$entero = $lista[0];
	$decimal = $lista[1];
	
	$enteros_txt=num2letras($entero);

	if($entero>1)
		$dolar=$moneda;
	else
		$dolar=$moneda;
	$cadena_salida= "SON: <strong>".strtoupper($enteros_txt." ".$dolar)." CON ".$decimal."/100.</strong>";
	//echo $cadena_salida;
	$xdatos['totaltexto']=$cadena_salida;
	echo json_encode($xdatos); //Return the JSON Array
}

if(!isset($_POST['process'])){
	initial();
}
else
{
if(isset($_POST['process']))
{
switch ($_POST['process']) {
	case 'insert':
		insert();
		break;
	case 'consultar_servicio':
		consultar_servicio();
		break;
	case 'total_texto':
		total_texto();
		break;
	}
}
}
?>
