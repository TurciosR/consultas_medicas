<?php
include_once "_core.php";

function initial() 
{
	$title = 'Enviar Notificaciones';
	// Page setup	
	$_PAGE = array ();
	$_PAGE ['title'] = $title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	include_once "header.php";
	include_once "main_menu.php";	
	
	$tipo= $_REQUEST['tipo'];
	$fecha= $_REQUEST['fecha'];
  	
  	$sql0 = _query("SELECT sms, ws, texto FROM empresa WHERE id_empresa='1'");
  	$datos = _fetch_array($sql0);
  	$msg = str_replace("{fecha}", nombre_dia($fecha), $datos["texto"]);
  	$sms = $datos["sms"]; 
  	$ws = $datos["ws"]; 
  	if($tipo =="Teléfono")
  	{
    	$sql="SELECT  n.id_notificacion, n.id_paciente, n.enviado, r.hora_cita, p.tel1 as contacto FROM notificacion as n, reserva_cita as r, paciente as p WHERE n.fecha='$fecha' AND n.tipo='$tipo' AND r.id=n.id_cita AND p.id_paciente=n.id_paciente ORDER BY r.hora_cita ASC";
	}
	else if($tipo =="Whatsapp")
  	{
    	$sql="SELECT  n.id_notificacion, n.id_paciente, n.enviado, r.hora_cita, p.tel2 as contacto FROM notificacion as n, reserva_cita as r, paciente as p WHERE n.fecha='$fecha' AND n.tipo='$tipo' AND r.id=n.id_cita AND p.id_paciente=n.id_paciente ORDER BY r.hora_cita ASC";
	}
	else
	{
		$sql="SELECT  n.id_notificacion, n.id_paciente, n.enviado, r.hora_cita, p.email as contacto FROM notificacion as n, reserva_cita as r, paciente as p WHERE n.fecha='$fecha' AND n.tipo='$tipo' AND r.id=n.id_cita AND p.id_paciente=n.id_paciente ORDER BY r.hora_cita ASC";
	}
    $result=_query($sql);
    $count=_num_rows($result);
    
    //permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	//permiso del script
	if ($links!='NOT' || $admin=='1' )
	{  	
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h3 style="color:#194160;"><i class="fa fa-send"></i> <b><?php echo $title;?></b></h3>
                </div>
                <div class="ibox-content">
                	<?php if($tipo =='Teléfono'){ ?>
                	<div class="row">
                		<div class="col-lg-12">
                			<h3 style="color:#194160;"><b id='disp'>Mensajes de Texto disponibles: <?php echo $sms;?></b><a class='pull-right' data-toggle='modal' data-target='#viewModal' data-refresh='true' href='sms.php'><i class="fa fa-plus"></i> Adquirir Paquete de Mensajes</a></h3>
                		</div>	
                	</div>
                	<?php }  if($tipo =='Whatsapp'){ ?>
                	<div class="row">
                		<div class="col-lg-12">
                			<h3 style="color:#194160;"><b id='disp'>Mensajes disponibles: <?php echo $ws;?></b><a class='pull-right' data-toggle='modal' data-target='#viewModal' data-refresh='true' href='sms.php'><i class="fa fa-plus"></i> Adquirir Paquete de Mensajes</a></h3>
                		</div>	
                	</div>
                	<?php } ?>
                	<form name="formulario" id="formulario">
				    <div class="row">                                           
			            <div class="form-group col-lg-6">
			              	<h4>Forma de Notificación:  <?php echo $tipo; ?></h4>
			            </div>
			            <div class="form-group col-lg-6">
			              	<h4>Fecha: <?php echo nombre_dia($fecha); ?></h4>
			            </div>
			        </div>
			        <div class="row">    
			            <div class="form-group col-lg-12">
			                <div class="form-group">
			                	<input type="hidden" id="tipo" value="<?php echo $tipo; ?>">
			                	<label>Texto del mensaje</label>
			                	<textarea name='mensaje' id='mensaje' class="form-control" rows="3"><?php echo $msg; ?></textarea>
			                </div>
			            </div>
			        </div>
			        <div class="row">
			        <div class="col-lg-12">
			        <table class="table table-condensed table-striped">
			        	<thead class="thead-inverse">
							<tr class="bg-success">
				        		<th>N°</th>
				        		<th>Paciente</th>
				        		<th>Hora Cita</th>
				        		<th>Contacto</th>
				        		<th class='text-center'><p><div class='checkbox i-checks' id="all"><label><input id='chk' name='chk' type='checkbox'></label></div></p></th>
				        	</tr>
			        	</thead>
			        	<tbody id="datos">
			        	<?php
			        		$i=1;							
							while($row = _fetch_array($result))
							{
								echo "<tr>
										<td>".$i."</td>
										<td class='nombre'>".buscar($row["id_paciente"])."</td>
										<td class='hora'>".hora($row["hora_cita"])."</td>
										<td class='contacto'>".$row["contacto"]."</td>
										<td class='text-center'>";
										if(!$row["enviado"])
										{
											echo "<p><div class='checkbox i-checks includes'><label><input id='myCheckboxes' name='myCheckboxes' type='checkbox' value='".$row["id_notificacion"]."'></label></div></p>";
										}
										else
										{
											echo "<label class='label bg-green'>Enviado</label>";
										}
										echo "</td>
									 </tr>"; 
								$i++;	 
							}
	                    ?>
	                    </tbody>
			        </table>
			        </div>
	        </div>	
	        <div class="row">
	        	<div class="form-actions col-lg-12">
					<input type="hidden" name="process" id="process" value="insert">
	                <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?> ">
	                <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>">
	                <input type="hidden" name="sms" id="sms" value="<?php if($tipo=='Teléfono'){ echo $sms; } else { echo $ws; } ?>">
					<a id="btn_enviar"  class="btn btn-primary pull-right">Enviar</a>
	            </div>
            </div>
   		    </form>
       	</div>
       	<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
			<div class='modal-dialog modal-sm'>
				<div class='modal-content'></div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->	
    </div>
    </div>
    </div>
</div>
   
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_notificacion.js'></script>";
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}


function verificar()
{
	$tipo = $_POST["tipo"];	

	if($tipo == "Correo")
	{
		$mensaje = $_POST["mensaje"];
		$destino = $_POST["numero"];
		$asunto = "RECORDATORIO DE CITA";
		$headers = "From: App Medic's <info@opensolutionsystems.com>" . "\r\n" .
		"CC: info@opensolutionsystems.com";

		mail($destino,$asunto,$mensaje,$headers);
	}
	if($tipo =="Teléfono")
	{
		$query = _query("SELECT sms FROM empresa WHERE id_empresa ='1'");
	    $datos = _fetch_array($query);
	    $sms = $datos["sms"];
	    $sms-=1;
		$table0 = "empresa";
		$form_data0 = array(
			'sms' => $sms
			);
		$where0 = "id_empresa='1'";
		$update0 = _update($table0,$form_data0,$where0);
	}
	if($tipo =="Whatsapp")
	{
		$query = _query("SELECT ws FROM empresa WHERE id_empresa ='1'");
	    $datos = _fetch_array($query);
	    $sms = $datos["ws"];
	    $sms-=1;
		$table0 = "empresa";
		$form_data0 = array(
			'ws' => $sms
			);
		$where0 = "id_empresa='1'";
		$update0 = _update($table0,$form_data0,$where0);
	}
	$id = $_POST["id"];
	$table = "notificacion";
	$form_data = array(
			'enviado' => 1
			);
	$where = "id_notificacion='$id'";
	$update = _update($table,$form_data,$where);
	if($update)
	{
		$xdatos["typeinfo"]="Success";
		$xdatos["id"]=$id;
		if($tipo =='Teléfono' || $tipo=='Whatsapp')
		{
			$xdatos["sms"] = $sms;
		}
//		$xdatos["msg"]="Notificaciones enviadas con exito";
	}
	else
	{
		$xdatos["typeinfo"]="Error";
//		$xdatos["msg"]="Notificaciones no pudieron ser enviadas";	
	}
  	echo json_encode($xdatos);
}



if(!isset($_POST['process']))
{
	initial(); 
}
else
{
	if(isset($_POST['process']))
	{	
		switch ($_POST['process'])
		{
			case 'verificar':
				verificar();
				break;		
			
		} 
	}			
}
?>
