<?php
include ("_core.php");
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$filename='ver_examen.php';
	$links=permission_usr($id_user,$filename);

	$id_examen_paciente = $_REQUEST["id_examen"];
    $query = _query("SELECT e_h.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_hematologia AS e_h, examen_paciente AS e_p WHERE e_h.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $globulos_rojos = $datos["globulos_rojos"];
        $hemoglobina = $datos["hemoglobina"];
        $hematocrito = $datos["hematocrito"];
        $vcm = $datos["vcm"];
        $hcm = $datos["hcm"];
        $chcm = $datos["chcm"];
        $globulos_blancos = $datos["globulos_blancos"];
        $n_segmentados = $datos["n_segmentados"];
        $n_banda = $datos["n_banda"];
        $linfocitos = $datos["linfocitos"];
        $monocitos = $datos["monocitos"];
        $eosinofilos = $datos["eosinofilos"];
        $basofilos = $datos["basofilos"];
        $plaquetas = $datos["plaquetas"];
        $tiempo_protobina = $datos["tiempo_protobina"];
        $inr = $datos["inr"];
        $isi = $datos["isi"];
        $tiempo_tromboplastima = $datos["tiempo_tromboplastima"];
        $eritrosedimentacion = $datos["eritrosedimentacion"];
        $observacion = $datos["observacion"];
        $reporta = $datos["reporta"];
        $id_examen = $datos["id_hematologia"];
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
	<h4 class="modal-title">Resultados: Hematología</h4>
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
                                    <label>Globulos rojos</label></td><td><?php echo $globulos_rojos; ?></td><td>4,000.000 - 5,000.000 XMM³
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Hemoglobina</label></td><td><?php echo $hemoglobina; ?></td><td>Hombre 14-17, Mujer 12.5-15, Niños 11-13 GR/DL
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Hematocrito</label></td><td><?php echo $hematocrito; ?></td><td>Hombre 42-52, Mujer 38-42, Niños 33-38%
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>VCM</label></td><td><?php echo $vcm; ?></td><td>80-100 Micras cúbicas
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>HCM</label></td><td><?php echo $hcm; ?></td><td>27-34 Micro microgramos
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>CHCM</label></td><td><?php echo $chcm; ?></td><td>30-34%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Globulos blancos</label></td><td><?php echo $globulos_blancos ;?></td><td>Adultos 5,000-10,000, Niños 5,000-12,000 XMM³
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Neutrófilos segmentados</label></td><td><?php echo $n_segmentados; ?></td><td>Adultos 60-70, Niños 20-45%
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Neutrófilos en banda</label></td><td><?php echo $n_banda; ?></td><td>Adultos 2-5, Niños 20-45%
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Linfocitos</label></td><td><?php echo $linfocitos; ?></td><td>Adultos 15-40, Niños 40-60%
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Monocitos</label></td><td><?php echo $monocitos; ?></td><td>Adultos y niños 2-8%
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Eosinófilos</label></td><td><?php echo $eosinofilos; ?></td><td>Adultos 1-4, Niños 1-5%
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Basófilos</label></td><td><?php echo $basofilos; ?></td><td>Adultos y niños 0-1%
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Plaquetas</label></td><td><?php echo $plaquetas; ?></td><td>150,000 - 450,000 XMM³
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Tiempo de protobina</label></td><td><?php echo $tiempo_protobina; ?></td><td>8-14 Segundos
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>I.N.R</label></td><td><?php echo $inr; ?></td><td>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>ISI</label></td><td><?php echo $isi; ?></td><td>
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Tiempo de tromboplastina</label></td><td><?php echo $tiempo_tromboplastima; ?></td><td>25-45 segundos, Hombres 0-7 MM/Hora
                                </td>
                            </tr>
                            <tr>    
                                <td>
                                    <label>Eritrosedimentacion</label></td><td><?php echo $eritrosedimentacion; ?></td><td>Mujeres 0-15 MM/Hora, Niños 0-20 MM/Hora
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
