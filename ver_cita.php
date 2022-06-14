<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename='ver_cita.php';
	$links=permission_usr($id_user,$filename);

	$id_cita = $_REQUEST['id_cita'];

	$sql="SELECT c.fecha_cita, c.hora_cita, c.motivo_consulta, c.observaciones, e.descripcion as estado, e.color, CONCAT(d.nombres,' ',d.apellidos) as doctor, CONCAT(p.nombres,' ',p.apellidos) as paciente, es.descripcion as consultorio FROM reserva_cita as c, estado_cita as e, doctor AS d, paciente as p, espacio as es WHERE e.id_estado = c.estado AND d.id_doctor=c.id_doctor AND p.id_paciente=c.id_paciente AND es.id_espacio=c.id_espacio AND c.id='$id_cita'";
	$result = _query($sql);
	$count = _num_rows($result);

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Informaci&oacute;n de la Cita</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
					<?php 
					//permiso del script
					if ($links!='NOT' || $admin=='1' )
					{
						if ($count > 0) 
						{
							for($i = 0; $i < $count; $i ++)
							{
								$row = _fetch_array ( $result, $i );
								$doctor=$row['doctor'];
								$paciente=$row["paciente"];
								$motivo=$row["motivo_consulta"];
								$observaciones = $row["observaciones"];
								$fecha = ED($row["fecha_cita"]);
								$hora = hora($row["hora_cita"]);
								$color = $row["color"];
								$consultorio = $row["consultorio"];
								$especialidad = $row["estado"];
								$estado = $row["estado"];
								
								echo "<table class='table table-bordered' style='width:100%;'>";
								echo"<tr class='bg-success'><th style='width:40%;'>Campo</th><th style='width:60%;'>Descripción</th></tr>";
								echo"<tr><td>Paciente</td><td>".$paciente."</td></tr>";
								echo"<tr><td>Médico</td><td>".$doctor."</td></tr>";
								echo"<tr><td>Consultorio</td><td>".$consultorio."</td></tr>";
								echo"<tr><td>Fecha</td><td>".$fecha."</td></tr>";
								echo"<tr><td>Hora</td><td>".$hora."</td></tr>";
								echo"<tr><td>Motivo</td><td>".$motivo."</td></tr>";
								echo"<tr><td>Estado</td><td style='color:$color;'><b>".$estado."</b></td></tr>";
								echo"<tr><td>Observaciones</td><td>".$observaciones."</td></tr>";				
								echo"</table>";						
							}
						}	
					?>
				</div>
			</div>
		</div>
	</div>
<div class="modal-footer">
<?php

	echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
	</div><!--/modal-footer -->";	
	} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	  
?>
