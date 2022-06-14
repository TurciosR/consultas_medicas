<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_paciente.php';
	$links=permission_usr($id_user,$filename);

	$id_paciente = $_REQUEST['id_paciente'];
	
	$sql="SELECT p.*, d.nombre_departamento, m.nombre_municipio FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id_paciente'";
	$result = _query($sql);
	$count = _num_rows($result);

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Informaci&oacute;n de Paciente</h4>
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
								$id_paciente=$row["id_paciente"];
								$contacto=$row['nombres']."  ".$row['apellidos'];
								$telefono1=$row["tel1"];
								$telefono2=$row["tel2"];
								if($telefono2 !="")
								{
									$telefono2 = ", ".$row["tel2"];
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
								$dui=$row["dui"];
							    $estado_civil=$row["estado_civil"];
							    $religion=$row["religion"];
							    $conyuge=$row["conyuge"];
							    $grupo_sanguineo=$row["grupo_sanguineo"];
							    $referido=$row["referido"];
							    $escolaridad=$row["escolaridad"];
								$fecha_reg = ED($row["fecha_registro"]);

								echo "<table class='table table-bordered' style='width:100%;'>";
								echo"<tr class='bg-success'><th style='width:40%;'>Campo</th><th style='width:60%;'>Descripción</th></tr>";
								echo"<tr><td>Nombre</td><td>".$contacto."</td></tr>";
								echo"<tr><td>Género</td><td>".$sexo."</td></tr>";
								echo"<tr><td>Edad</td><td>".$edad."</td></tr>";
								echo"<tr><td>DUI</td><td>".$dui."</td></tr>";
								echo"<tr><td>Estado Civil</td><td>".$estado_civil."</td></tr>";
								echo"<tr><td>Religión</td><td>".$religion."</td></tr>";
								echo"<tr><td>Nombre del Conyuge</td><td>".$conyuge."</td></tr>";
								echo"<tr><td>Grupo sanguíneo</td><td>".$grupo_sanguineo."</td></tr>";
								echo"<tr><td>Teléfono</td><td>".$telefono1.$telefono2."</td></tr>";
								echo"<tr><td>Dirección</td><td>".$direccion."</td></tr>";
								echo"<tr><td>Correo</td><td>".$email."</td></tr>";
								echo"<tr><td>Padecimientos</td><td>".$padecimientos."</td></tr>";						
								echo"<tr><td>Alergias</td><td>".$alergias."</td></tr>";						
								echo"<tr><td>Medicamentos Permánetes</td><td>".$medicamentos."</td></tr>";						
								echo"<tr><td>Referido por</td><td>".$referido."</td></tr>";						
								echo"<tr><td>Fecha de Registro</td><td>".$fecha_reg."</td></tr>";						
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
