<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_examen.php';
	$links=permission_usr($id_user,$filename);

	$id_examen_paciente = $_REQUEST["id_examen"];
    $query = _query("SELECT e_b.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_bacteriologia AS e_b, examen_paciente AS e_p WHERE e_b.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $muestra = $datos["muestra"];
        $area_corporal = $datos["area_corporal"];
        $microorganismo_aislado = $datos["microorganismo_aislado"];
        $conteo_colonia = $datos["conteo_colonia"];
        $sensible = explode("|",$datos["sensible"]);
        $intermedio = explode("|",$datos["intermedio"]);
        $resistente = explode("|",$datos["resistente"]);
        $id_examen = $datos["id_bacteriologia"];
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
	<h4 class="modal-title">Resultados: Bacteriologia</h4>
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
                                    <label>Datos</label></th><th class="text-center" colspan="2">Resultado
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <label>Muestra</label></td><td colspan="2"><?php echo $muestra; ?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Area corporal</label></td><td colspan="2"><?php echo $area_corporal; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Microorganismo aislado</label></td><td colspan="2"><?php echo $microorganismo_aislado ;?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Conteo de colonia</label></td><td colspan="2"><?php echo $conteo_colonia; ?>
                                </td>
                            </tr>
                            <tr>
                            	<td class="col-lg-4"><label>Sensible</label></td><td class="col-lg-4"><label>Intermedio</label></td><td class="col-lg-4"><label>Resistente</label></td>
                            </tr>
                            <?php
                            	for($i=0; $i<count($sensible); $i++)
                            	{
                            		echo "<tr>
                            				<td>".$sensible[$i]."</td>
                            				<td>".$intermedio[$i]."</td>
                            				<td>".$resistente[$i]."</td>
                            			  </tr>";
                            	}
                            ?>
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
