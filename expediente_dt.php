<?php
	include("_core.php");
	
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	$_PAGE = array ();
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$ini = date('Y-m-d', strtotime($_POST["ini"]));
	$fin = date('Y-m-d', strtotime($_POST["fin"]));
	$id = $_POST["id"];
	$sql= "SELECT r.*, concat(d.nombres,' ',d.apellidos) as dr, e.descripcion
	FROM reserva_cita as r, doctor as d, espacio as e
	WHERE d.id_doctor=r.id_doctor  AND r.id_paciente='$id'
	AND e.id_espacio=r.id_espacio
	AND r.estado=8
	AND r.fecha_cita BETWEEN '$ini' AND '$fin'
	ORDER BY r.id DESC";

	//AND e.id_espacio=r.id_espacio -> Esta condicion estaba generando el inconvenniente. para realizar las consultas

	$result=_query($sql);
	$count = _num_rows($result);
	if($count>0)
	{
		$table= '<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th>Fecha</th>
					<th>Hora</th>
					<th>Médico</th>
					<th>Consultorio</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody> ';
		while ($row=_fetch_array($result))
		{
			$fecha = ED($row["fecha_cita"]); 
		    $hora = hora($row["hora_cita"]);             
		    $doctor = $row["dr"];             
		    $consultorio = $row["descripcion"];   
		    $table.="<tr>";
			$table.="<td>".$row["id"]."</td>
				<td>".$fecha."</td>
				<td>".$hora."</td>
				<td>".$doctor."</td>
				<td>".$consultorio."</td>";
			$table.="<td>
						<a class='btn' href='ver_consulta.php?id_cita=".$row["id"]."' target='_blank'><i class='fa fa-eye'></i></a>
						<a class='btn pull-rigth' href='reporte_expediente1.php?id_cita=".$row["id"]."' target='_blank'><i class='fa fa-print'></i></a>";
						$filename='borrar_consulta.php';
						$link=permission_usr($id_user,$filename);
						if ($link!='NOT' || $admin=='1' )
							$table.= "<a class='btn' data-toggle='modal' href=\"borrar_consulta.php?id=".$row['id']."\" data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-trash\"></i></a>";
						$filename='expediente.php';
					$table.="</td>
				</tr>";
		}		
		$table .='		
		</tbody>		
		</table>';
	}
	else
	{
		$table = "<div class='alert alert-warning'>El paciente no posee historial disponible.</div>";
	}
	echo $table;
?>	