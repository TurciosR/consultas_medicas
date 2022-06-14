<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];

	$filename='ver_examen.php';
	$links=permission_usr($id_user,$filename);

	$id_examen_paciente = $_REQUEST["id_examen"];
    $query = _query("SELECT e_o.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_orina AS e_o, examen_paciente AS e_p WHERE e_o.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $color = $datos["color"];
        $aspecto = $datos["aspecto"];
        $densidad = $datos["densidad"];
        $ph = $datos["ph"];
        $proteinas = $datos["proteinas"];
        $glucosa = $datos["glucosa"];
        $sangre_oculta = $datos["sangre_oculta"];
        $cuerpos_cetonicos = $datos["cuerpos_cetonicos"];
        $urobilinogeno = $datos["urobilinogeno"];
        $bilirrubina = $datos["bilirrubina"];
        $nitritos = $datos["nitritos"];
        $hemoglobina = $datos["hemoglobina"];
        $e_leucocitaria = $datos["e_leucocitaria"];
        $celulas_epiteliales = $datos["celulas_epiteliales"];
        $leucocitos = $datos["leucocitos"];
        $hematies = $datos["hematies"];
        $urato = $datos["urato"];
        $cilindro_grueso = $datos["cilindro_grueso"];
        $cilindro_leucocitario = $datos["cilindro_leucocitario"];
        $cilindro_hematico = $datos["cilindro_hematico"];
        $cilindro_hialino = $datos["cilindro_hialino"];
        $parasitologico = $datos["parasitologico"];
        $bacterias = $datos["bacterias"];
        $filamento_mucoide = $datos["filamento_mucoide"];
        $otros = $datos["otros"];
        $observacion = $datos["observacion"];
        $reporta = $datos["reporta"];
        $id_examen = $datos["id_examen_orina"];
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
	<h4 class="modal-title">Resultados: Examen General de Orina</h4>
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
                                    <label>Datos</label></th><th class="text-center">Resultado</th><th>Datos</th><th class="text-center">Resultado
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <label>Color</label></td><td><?php echo $color; ?>
                                </td>
                                <td>
                                    <label>Aspecto</label></td><td><?php echo $aspecto; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Densidad</label></td><td><?php echo $densidad ;?>
                                </td>
                                <td>
                                    <label>Ph</label></td><td><?php echo $ph; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Proteinas</label></td><td><?php echo $proteinas; ?>
                                </td>
                                <td>
                                    <label>Glucosa</label></td><td><?php echo $glucosa; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Sangre oculta</label></td><td><?php echo $sangre_oculta; ?>
                                </td>
                                <td>
                                    <label>Cuerpos cetonicos</label></td><td><?php echo $cuerpos_cetonicos; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Urobilinogeno</label></td><td><?php echo $urobilinogeno; ?>
                                </td>
                                <td>
                                    <label>Bilirrubina</label></td><td><?php echo $bilirrubina; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Nitritos</label></td><td><?php echo $nitritos; ?>
                                </td>
                                <td>
                                    <label>Hemoglobina</label></td><td><?php echo $hemoglobina; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Esterasa leucocitoria</label></td><td><?php echo $e_leucocitaria; ?>
                                </td>
                                <td>
                                    <label>Celulas epiteliales</label></td><td><?php echo $celulas_epiteliales; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Leucocitos</label></td><td><?php echo $leucocitos; ?>
                                </td>
                                <td>
                                    <label>Hematies</label></td><td><?php echo $hematies; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Cristales urato amorfo</label></td><td><?php echo $urato; ?>
                                </td>
                                <td>
                                    <label>Cilindros granuloso grueso</label></td><td><?php echo $cilindro_grueso; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Cilindro leucocitario</label></td><td><?php echo $cilindro_leucocitario; ?>
                                </td>
                                <td>
                                    <label>Cilindro hematico</label></td><td><?php echo $cilindro_hematico; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Cilindro hialino</label></td><td><?php echo $cilindro_hialino; ?>
                                </td>
                                <td>
                                    <label>Parasitologico</label></td><td><?php echo $parasitologico; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Bacterias</label></td><td><?php echo $bacterias; ?>
                                </td>
                                <td>
                                    <label>Filamento mucoide</label></td><td><?php echo $filamento_mucoide; ?>
                                </td>
                            </tr>
                        	<tr>
                                <td>
                                    <label>Otros</label></td><td><?php echo $otros; ?>
                                </td>
                                <td>
                                    <label>Reportados por</label></td><td><?php echo $reporta; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Observaciones</label></td><td><?php echo $observacion; ?>
                                </td>
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
