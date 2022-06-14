<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
	$title='Cobros/Ingresos';
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

	$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
    $datos_moneda = _fetch_array($sql0);
    $simbolo = $datos_moneda["simbolo"];  
    $moneda = $datos_moneda["moneda"]; 

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
                         <h3 style="color:#194160;"><i class="fa fa-money"></i> <b><?php echo $title;?></b></h3>
                    </div>
                    <div class="ibox-content">
                        <div class="col-lg-12" id="forma_pago">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class='text-success'>Forma de pago</h3></div>
									<div class="panel-body">	
										<div class="row">
											<div class="col-lg-6 form-group">
												<label>Forma de Pago</label>
												<select class="form-control select" name="forma" id="forma">
													<option value="Efectivo">Efectivo</option>
													<option value="Cheque">Cheque</option>
													<option value="Tarjeta">Tarjeta de credito</option>
													<option value="ISBM">ISBM</option>
												</select>
											</div>
											<div class='col-md-6'>
												<div class="form-group">
													<label>Fecha:</label>
													<input type='text' class='datepicker form-control' id='fecha' name='fecha' value='<?php echo date('d-m-Y');?>'>
												</div>
											</div>	
										</div>
										<div class="row" id="otr" hidden>
											<div class="col-lg-6 form-group">
												<label>DUI</label>
												<input type="text" name="dui" id="dui" class="form-control">
											</div>
											<div class="col-lg-6">
												<label id="doc">N° de Comprobante</label>
												<input type="text" name="documento" id="documento" class="form-control">
											</div>
										</div>
									</div>
	                        </div>
						</div>
						<div class="col-lg-12" id="cliente_detalle">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class='text-success'>Cliente/Paciente</h3></div>
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-6 form-group">
											<label>Tipo Cliente</label>
											<select class="form-control select" name="tipo_cliente" id="tipo_cliente">
												<option value="Particular">Particular</option>
												<option value="Paciente">Paciente</option>
											</select>
										</div>
									</div>
									<div class="row" hidden id="div_pa">
										<div class="col-md-12">
										<div class="form-group has-info">
											<label>Buscar Paciente</label>
											<input type="text" id="buscar_paciente" name="buscar_paciente" class="form-control" placeholder="Ingrese nombre(s) o apellido(s) para buscar">
											<input type="hidden" name="id_paciente" id="id_paciente">
											<label id="paciente"></label>
										</div>
										</div>
									</div>
									<div class="row" id="div_cli">
										<div class="col-md-12">
										<div class="form-group has-info">
											<label>Nombre del Cliente</label>
											<input type="text" id="cliente" name="cliente" class="form-control">
										</div>
										</div>
									</div>
		                        </div>
							</div>
	               		</div>
	             	    <div class="col-lg-12" id="detalle_servicio">
							<div class="panel panel-default"><!-- panel RECIBO -->
								<div class="panel-heading"><h3 class='text-success'>Detalle de Servicios</h3></div>
									<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group has-info">
													<label>Buscar Servicio</label>
													<input type="text" id="buscar_servicio" name="buscar_servicio" class="form-control" placeholder="Ingrese descripcion del producto o servicio">
													<input type="hidden" name="id_servicio" id="id_servicio"/>
													<!--div id='contrib_selected'></div-->
												</div>
											</div>
		                                </div>
										<div class="row">
										  	<div class="col-lg-12">
												<table class="table table-condensed table-striped">
													<thead class="thead-inverse">
														<tr class="bg-success">
															<th class="col-lg-1">Id</th>
															<th class="col-lg-7">Descripción</th>
															<th class="col-lg-1">Precio</th>
															<th class="col-lg-1">Cantidad</th>
															<th class="col-lg-1">Subtotal</th>
															<th class="col-lg-1">&nbsp;&nbsp;Acción</th>
														</tr>
													</thead>
													<tbody id='table_factura'>
													</tbody>
													<tfoot>
														<tr>
														<td class="thick-line"></td>
														<td class="thick-line"></td>
														<td class="thick-line text-center" id='text_dinero'></td>
														<td class="thick-line" id='total_dinero'></td>
														<td class="thick-line" id='total_fiestas'></td>
														<td class="thick-line"></td>
														</tr>
													</tfoot>
												</table>
												<table class="table invoice-total">
					                                <tbody>
					                             	<tr>
					                                    <td><strong>TOTAL <?php echo $simbolo; ?>:</strong></td>
					                                    <td id='total_final' style="text-align: left;"></td>
					                                </tr>
					                                </tbody>
				                           		</table>
												<div class="well m-t"  id='totaltexto'><strong></strong> </div>
											</div>
										</div>
	                        	</div>
							</div>
	                	</div>
						<input type="hidden" name="process" id="process" value="insert">
						<input type="hidden" name="simbolo" id="simbolo" value="<?php echo $simbolo; ?>">
		                <div class="title-action" id='botones'>
							<a id="btn_fin" name="btn_fin" class="btn btn-primary"><i class="fa fa-check"></i> Guardar</a>
						</div>
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
