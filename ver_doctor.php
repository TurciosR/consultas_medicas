<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_doctor.php';
	$links=permission_usr($id_user,$filename);

	$id_doctor = $_REQUEST['id_doctor'];

	$sql="SELECT d.*, e.descripcion FROM doctor AS d, especialidad as e WHERE d.id_especialidad = e.id_especialidad AND d.id_doctor='$id_doctor'";
	$result = _query($sql);
	$count = _num_rows($result);

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Informaci&oacute;n del Médico</h4>
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
								$id_doctor=$row["id_doctor"];
								$contacto=$row['nombres']."  ".$row['apellidos'];
								$telefono=$row["telefono"];
								$email=$row["email"];
								$sexo = $row["sexo"];
								$fecha = ED($row["fecha_nac"]); 
						        $datos_fecha = explode("-", $fecha);
						        $anio_nac  = $datos_fecha[2];
						        $edad = date("Y") - $anio_nac;             
								$direccion = $row["direccion"];//.", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
								$jvpm = $row["jvpm"];
								$especialidad = $row["descripcion"];
								$estado = "Activo";
								if(!$row["activo"])
								{
									$estado = "Inactivo";
								}
								$subes = "";
								if($row["id_subespecialidad"]>0)
								{
									$sql_esp = _query("SELECT descripcion FROM especialidad WHERE id_especialidad='$row[id_subespecialidad]'");
									$row_esp = _fetch_array($sql_esp);
									$subes = $row_esp["descripcion"];
								}
								//$fecha_reg = ED($row["fecha_registro"]);

								echo "<table class='table table-bordered' style='width:100%;'>";
								echo"<tr class='bg-success'><th style='width:40%;'>Campo</th><th style='width:60%;'>Descripción</th></tr>";
								echo"<tr><td>Nombre</td><td>".$contacto."</td></tr>";
								echo"<tr><td>Género</td><td>".$sexo."</td></tr>";
								echo"<tr><td>Edad</td><td>".$edad."</td></tr>";
								echo"<tr><td>Teléfono</td><td>".$telefono."</td></tr>";
								echo"<tr><td>Dirección</td><td>".$direccion."</td></tr>";
								echo"<tr><td>Correo</td><td>".$email."</td></tr>";
								echo"<tr><td>JVPM</td><td>".$jvpm."</td></tr>";						
								echo"<tr><td>Especialidad</td><td>".$especialidad."</td></tr>";	
								if($subes!="")
								{
									echo"<tr><td>Subespecialidad</td><td>".$subes."</td></tr>";		
								}					
								echo"<tr><td>Estado</td><td>".$estado."</td></tr>";						
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
