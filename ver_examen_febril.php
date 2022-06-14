<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_examen.php';
	$links=permission_usr($id_user,$filename);

	$id_examen_paciente = $_REQUEST["id_examen"];
    $query = _query("SELECT e_f.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_febriles AS e_f, examen_paciente AS e_p WHERE e_f.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $tifico_h = $datos["tifico_h"];
        $tifico_o = $datos["tifico_o"];
        $paratifico_a = $datos["paratifico_a"];
        $paratifico_b = $datos["paratifico_b"];
        $proteus = $datos["proteus"];
        $brocela_abortus = $datos["brocela_abortus"];
        $id_examen = $datos["id_febriles"];
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
	<h4 class="modal-title">Resultados: Antígeno Febriles</h4>
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
                                    <label>Tifico H</label></td><td><?php echo $tifico_h; ?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Tifico O</label></td><td><?php echo $tifico_o; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Paratifico A</label></td><td><?php echo $paratifico_a ;?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Paratifico B</label></td><td><?php echo $paratifico_b ;?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Proteus OX19</label></td><td><?php echo $proteus; ?>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Brocela abortus</label></td><td><?php echo $brocela_abortus; ?>
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
