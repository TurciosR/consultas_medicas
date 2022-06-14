<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_examen.php';
	$links=permission_usr($id_user,$filename);

	$id_examen_paciente = $_REQUEST["id_examen"];
    $query = _query("SELECT * FROM examen_quimica_sanguinea WHERE id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $glucosa_azar = $datos["glucosa_azar"];
        $glucosa_prandial = $datos["glucosa_prandial"];
        $colesterol_total = $datos["colesterol_total"];
        $colesterol_hdl = $datos["colesterol_hdl"];
        $colesterol_ldl = $datos["colesterol_ldl"];
        $trigliceridos = $datos["trigliceridos"];
        $lipidos_totales = $datos["lipidos_totales"];
        $creatinina = $datos["creatinina"];
        $acido_urico = $datos["acido_urico"];
        $urea = $datos["urea"];
        $nitrogeno_ureico = $datos["nitrogeno_ureico"];
        $sodio = $datos["sodio"];
        $potasio = $datos["potasio"];
        $cloro = $datos["cloro"];
        $proteinas_totales = $datos["proteinas_totales"];
        $albumina = $datos["albumina"];
        $globulina = $datos["globulina"];
        $relacion_ag = $datos["relacion_ag"];
        $amilasa = $datos["amilasa"];
        $bilirrubina_total = $datos["bilirrubina_total"];
        $bilirrubina_directa = $datos["bilirrubina_directa"];
        $bilirrubina_indirecta = $datos["bilirrubina_indirecta"];
        $calcio = $datos["calcio"];
        $fosforo = $datos["fosforo"];
        $proteina_reactiva = $datos["proteina_reactiva"];
        $tsh = $datos["tsh"];
        $t3_libre = $datos["t3_libre"];
        $t4_libre = $datos["t4_libre"];
        $ldh = $datos["ldh"];
        $hda1 = $datos["hda1"];
        $fraccion = $datos["fraccion"];
        $transaminasa_go = $datos["transaminasa_go"];
        $transaminasa_gp = $datos["transaminasa_gp"];
        $observacion = $datos["observacion"];
        $reporta = $datos["reporta"];
        $id_examen = $datos["id_sanguinea"];
        $fecha_lectura = ED($datos["fecha"]);
        $existe = true;
    }
    else
    {
    	$existe = false;
    }

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Resultados: Química Sanguínea</h4>
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
                                    <label>Datos</label></th><th class="text-center col-lg-2">Resultado</th><th>Valor de Ref.
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <label>Glucosa al azar</label></td><td><?php echo $glucosa_azar; ?></td><td>60-110 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Glucosa post-prandial    </label></td><td><?php echo $glucosa_prandial; ?></td><td>70-140 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Colesterol total</label></td><td><?php echo $colesterol_total; ?></td><td>Hasta 200 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Colesterol HDL</label></td><td><?php echo $colesterol_hdl; ?></td><td>45-60 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Colesterol LDL</label></td><td><?php echo $colesterol_ldl; ?></td><td>Hasta 130 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Triglicéridos</label></td><td><?php echo $trigliceridos; ?></td><td>Hasta 150 MG/DL
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Lípidos totales</label></td><td><?php echo $lipidos_totales ;?></td><td>400-800 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Creatinina</label></td><td><?php echo $creatinina; ?></td><td>Hombres 0.7-1.4 MG/DL, Mujeres 0.6-1.1 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Ácido úrico</label></td><td><?php echo $acido_urico; ?></td><td>Hombres 3.6-7.7 MG/DL, Mujeres 2.5-6.8 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Urea</label></td><td><?php echo $urea; ?></td><td>15-45 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Nitrógeno ureico</label></td><td><?php echo $nitrogeno_ureico; ?></td><td>4.5-22.7 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Sodio</label></td><td><?php echo $sodio; ?></td><td>135-148 MEQ/L
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Potasio</label></td><td><?php echo $potasio; ?></td><td>3.5-5.3 MEQ/L
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Cloro</label></td><td><?php echo $cloro; ?></td><td>98-107 MEQ/L
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Proteínas totales</label></td><td><?php echo $proteinas_totales; ?></td><td>6.6-8.3 G/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Albúmina</label></td><td><?php echo $albumina; ?></td><td>3.8-5.1 G/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Globulina</label></td><td><?php echo $globulina; ?></td><td>1.5-3 G/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Relación A/G</label></td><td><?php echo $relacion_ag; ?></td><td>1.1-2.2
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Amilasa</label></td><td><?php echo $amilasa; ?></td><td>1-90 U/L
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Bilirrubina total</label></td><td><?php echo $bilirrubina_total; ?></td><td>0-1.1 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Bilirrubina directa</label></td><td><?php echo $bilirrubina_directa; ?></td><td>0-0.25 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Bilirrubina indirecta</label></td><td><?php echo $bilirrubina_indirecta; ?></td><td>0-0.50 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Calcio</label></td><td><?php echo $calcio; ?></td><td>8.5-10.5 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Fósforo</label></td><td><?php echo $fosforo; ?></td><td>2.5-5.0 MG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Proteina C reactiva</label></td><td><?php echo $proteina_reactiva; ?></td><td>Hasta 12 MG/L
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>TSH</label></td><td><?php echo $tsh; ?></td><td>0.38-4.31 Uiu/ML
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>T3 libre</label></td><td><?php echo $t3_libre; ?></td><td>2.1-3.8 PG/ML
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>T4 libre</label></td><td><?php echo $t4_libre; ?></td><td>0.82-1.63 NG/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Deshidrogenasa láctica (LDH)</label></td><td><?php echo $ldh; ?></td><td>230-460 U/L
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Hemoglobina glicosilada HDA1</label></td><td><?php echo $hda1; ?></td><td>5-8%
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Fracción HDA,C</label></td><td><?php echo $fraccion; ?></td><td>4.2-6.2%
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Transaminasa G.O</label></td><td><?php echo $transaminasa_go; ?></td><td>Mujer 3.1 U/L, Hombre 
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Transaminasa G.P</label></td><td><?php echo $transaminasa_gp; ?></td><td>Mujer 3.2 U/L, Hombre
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Reportados por</label></td><td colspan="2"><?php echo $reporta; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Observaciones</label></td><td colspan="2"><?php echo $observacion; ?>
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
