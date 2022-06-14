<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_medicamento.php';
	$links=permission_usr($id_user,$filename);

	$id_medicamento = $_REQUEST['id_medicamento'];

	$sql="SELECT * FROM medicamento WHERE id_medicamento='$id_medicamento'";
	$result = _query($sql);
	$count = _num_rows($result);

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Informaci&oacute;n del Medicamento</h4>
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
								$id_medicamento=$row["id_medicamento"];
								$descripcion=$row['descripcion'];
								$presentacion=$row['presentacion'];
								$precio=$row['precio'];
								if($precio < 0)
								{
									$precio = 0;
								}
								$principio = $row["principio"];//.", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
								$laboratorio = $row["laboratorio"];
								$forma = $row["forma"];
								$img = "img/medicamentos/img.png";
							    if($row["img"] !="")
							    {
							        $img = $row["img"];
							    }
								$estado = "Si";
								if(!$row["vacuna"])
								{
									$estado = "No";
								}
								
								//$fecha_reg = ED($row["fecha_registro"]);
								echo "<div class='col-lg-12' style='margin-top:-38px;'><img src='$img' style='width:260px; height:160px;'></div>";
								echo "<table class='table table-bordered' style='width:100%;'>";
								echo"<tr class='bg-success'><th style='width:40%;'>Campo</th><th style='width:60%;'>Descripción</th></tr>";
								echo"<tr><td>Nombre</td><td>".$descripcion."</td></tr>";
								echo"<tr><td>Precio</td><td>$".number_format($precio,2,".",",")."</td></tr>";
								echo"<tr><td>Presentación</td><td>".$presentacion."</td></tr>";
								echo"<tr><td>Laboratorio</td><td>".$laboratorio."</td></tr>";
								echo"<tr><td>Principio Activo</td><td>".$principio."</td></tr>";
								echo"<tr><td>Inyectable</td><td>".$estado."</td></tr>";					
								echo"<tr><td>Forma Farmaceutica</td><td>".$forma."</td></tr>";					
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
