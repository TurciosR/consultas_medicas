<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
	$title=' Buscar Expediente';
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
                         <h3 style="color:#194160;"><i class="fa fa-search"></i> <b><?php echo $title;?></b></h3>
                    </div>
                    <div class="ibox-content">
						<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class='text-success'>Paciente</h3></div>
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-10">
											<div class="form-group has-info">
												<label>Buscar Paciente</label>
												<input type="text" id="buscar_paciente" name="buscar_paciente" class="form-control" placeholder="Ingrese nombre(s), apellido(s) o numero de expediente para buscar">
												<input type="hidden" name="id_paciente" id="id_paciente">
												<label id="pacientex"></label>
											</div>
										</div>	
										<div class="col-lg-2">
											<div class="form-group has-info"><br>
												<a id="expe" class="btn btn-primary">Ver Expediente</a>
											</div>
										</div>
		                        	</div>
		                        	<div class="row" id="datos" hidden>
			                        	<div class="col-lg-12">
			                        		<div class="panel panel-default">
					                            <div class="panel-heading">
                               						<input type="hidden" name="process" id="process" value="search">
					                                <h4 class='text-success'>DATOS DEL PACIENTE</h4>
					                            </div>
					                            <div class="panel-body" id="table">
				                               
					                            </div>
					                        </div>
				                        </div>
		                        	</div>
								</div>
	               			</div>
	             	    </div>	
	             	    </div>	
					</div><!--Ibox-->
            	</div>
        	</div>
    	</div>
	</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_expediente.js'></script>";

} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function buscar_dat()
{
    $id = $_POST["id_paciente"];
    $sql="SELECT p.*, d.nombre_departamento, m.nombre_municipio FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id'";
    $result = _query($sql);
    
    $row = _fetch_array($result);
    $nombre=$row['nombres'];
    $apellido = $row['apellidos'];
    $telefono1=$row["tel1"];
    $telefono2=$row["tel2"];
    if($telefono2 !="")
    {
        $telefono1 .= ", ".$row["tel2"];
    }
    $email=$row["email"];
    $sexo = $row["sexo"];
    $fecha = ED($row["fecha_nacimiento"]); 
    $datos_fecha = explode("-", $fecha);
    $anio_nac  = $datos_fecha[2];
    $edad = date("Y") - $anio_nac;             
    $direccion = $row["direccion"].", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
    $padecimientos = $row["padecimientos"];
    $medicamentos = $row["medicamento_permanente"];
    $alergias = $row["alergias"];

    $dato='
    <table class="table  table-checkable datatable">			                                   
    <tr>
        <td style="width: 12%;">Nombres:</td>
        <td style="width: 37%;">'.$nombre.'</td>
        <td style="width: 12%;">Apellidos:</td>
        <td style="width: 37%;">'.$apellido.'</td>
    </tr>
    <tr>
        <td>Edad:</td>
        <td>'.$edad.'</td>
        <td>Género:</td>
        <td>'.$sexo.'</td>
    </tr>
    <tr>
        <td>Dirección:</td>
        <td>'.$direccion.'</td>
        <td>Télefono:</td>
        <td>'.$telefono1.'</td>
    </tr>
    <tr>
        <td>Padecimientos:</td>
        <td>'.$padecimientos.'</td>
        <td>Alergias:</td>
        <td>'.$alergias.'</td>
    </tr>
    <tr>
        <td colspan="2">Medicamentos Permánetes:</td>
        <td colspan="2">'.$medicamentos.'</td>
    </tr>
    </table> ';
    
    $xdatt["table"] = $dato;
    $xdatt['typeinfo']="Success";
    echo json_encode($xdatt);
}

if(!isset($_POST['process'])){
	initial();
}
else
{
if(isset($_POST['process']))
{
switch ($_POST['process']) {
	case 'buscar':
		buscar_dat();
		break;
	}
}
}
?>
