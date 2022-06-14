<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_examen.php';
	$links=permission_usr($id_user,$filename);

	$id_examen_paciente = $_REQUEST["id_examen"];
    $query = _query("SELECT e_h.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_heces AS e_h, examen_paciente AS e_p WHERE e_h.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $color = $datos["color"];
        $consistencia = $datos["consistencia"];
        $mucus = $datos["mucus"];
        $restos_alimenticios = $datos["restos_alimenticios"];
        $leucocitos = $datos["leucocitos"];
        $hematies = $datos["hematies"];
        $protozoarios = $datos["protozoarios"];
        $metazoarios = $datos["metazoarios"];
        $id_examen = $datos["id_examen_heces"];
        $flora = $datos["flora"];
        $otros = $datos["otros"];
        $fecha_lectura = ED($datos["fecha_lectura"]);
        $existe = true;
    }
    else
    {
    	$existe = false;
    }

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Resultados: Examen General de Heces</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
					<?php 
					//permiso del script
					if ($links!='NOT' || $admin=='1' )
					{
						if($existe){
					?>
						<table class="table table-bordered">
                            <tr>
                                <th class="col-lg-4">
                                    <label>Datos</label></th><th class="text-center">Resultado
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <label>Color</label></td><td><?php echo $color; ?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Consistencia</label></td><td><?php echo $consistencia; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Mucus</label></td><td><?php echo $mucus ;?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Restos alimeticos</label></td><td><?php echo $restos_alimenticios; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Leucocitos</label></td><td><?php echo $leucocitos; ?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Hematies</label></td><td><?php echo $hematies; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Protozoarios quistes</label></td><td><?php echo $protozoarios; ?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Metazoarios huevos</label></td><td><?php echo $metazoarios; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Flora Bacteriana</label></td><td><?php echo $flora; ?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Otros Hallazgos</label></td><td><?php echo $otros; ?>
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                <label>Fecha de lectura</label></td><td><?php echo $fecha_lectura; ?>
                                </td>
                            </tr>
                        </table>
                    <?php    
						}	
						else
						{
							echo "<div class='alert alert-warning'>No se han agregado resultados.</div>";
						}
					?>
				</div>
			</div>
		</div>
	</div>
    <input type="hidden" name="act" id="act" value="0">
<div class="modal-footer">
<?php

	echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
	</div><!--/modal-footer -->";	
	} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	  
?>
