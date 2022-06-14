<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_examen.php';
	$links=permission_usr($id_user,$filename);

	$id_examen_paciente = $_REQUEST["id_examen"];
    $query = _query("SELECT e_c.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_creatinina AS e_c, examen_paciente AS e_p  WHERE e_c.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $volumen_orina = $datos["volumen_orina"];
        $creatinina_orina = $datos["creatinina_orina"];
        $creatinina_sangre = $datos["creatinina_sangre"];
        $depuracion_creatinina = $datos["depuracion_creatinina"];
        $proteinas_orina = $datos["proteinas_orina"];
        $id_examen = $datos["id_examen_creatinina"];
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
	<h4 class="modal-title">Resultados: Depuración de Creatinina</h4>
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
                                    <label>Datos</label></th><th class="text-center col-lg-5">Resultado</th><th>Valor de Ref.
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <label>Volumen de orina</label></td><td><?php echo $volumen_orina; ?></td><td>ml/24 horas
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Creatinina en orina</label></td><td><?php echo $creatinina_orina; ?></td><td>Mg/24 horas
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Creatinina en sangre</label></td><td><?php echo $creatinina_sangre ;?></td><td>0.4 - 1.4 mg/dl
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Depuración de creatinina en orina</label></td><td><?php echo $depuracion_creatinina; ?></td><td>50 - 157 ml/mto
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Proteinas en orina</label></td><td><?php echo $proteinas_orina; ?></td><td>10 - 150 mgrs/24 horas
                                </td>
                            </tr>
                            <tr>
                            	<td>
                                <label>Fecha de lectura</label></td><td colspan="2"><?php echo $fecha_lectura; ?>
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
