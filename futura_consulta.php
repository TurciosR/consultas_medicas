<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
?>
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Agregar Futura Consulta</h4>
</div>
<style  type="text/css">
        .datepicker table tr td, .datepicker table tr th{
            border:none;
            background:white;
        }
    </style>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
                <?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
                <div class="form-group">
                    <label>Fecha <span style="color:red;">*</span></label>
                    <input type="text" class="form-control datepicker" id="fecha_nueva_consulta" name="fecha_nueva_consulta"
                        value="<?php echo date("d-m-Y"); ?>">
                </div>
                <div class="form-group">
                    <label>Hora <span style="color:red;">*</span></label>
                    <input type="text" placeholder="HH:mm" class="form-control" id="hora_nueva_consulta" name="hora_nueva_consulta">
                </div>
                <div class="form-group">
                    <label>Consultorio <span style="color:red;">*</span></label>
                    <br>
                    <?php 
                        $sqlp = "SELECT * FROM espacio";
                        $resultp=_query($sqlp);
                        $num = _num_rows($resultp);
                        echo "<select style='width:100%;' class='form-control select' id='espacio_nueva_consulta' name='espacio_nueva_consulta'";
                        if($num<2)
                        {
                            echo " disabled ";
                        }
                        echo ">
                        <option value=''>Seleccione</option>";
                        while($pco = _fetch_array($resultp))
                        {
                            echo "<option value='".$pco["id_espacio"]."'";
                            if($num<2)
                            {
                                echo " selected ";
                            }
                            echo ">".$pco["descripcion"]."</option>";
                        }
                        echo "</select>"; 
                    ?>
                </div>
                <div class="form-group">
                    <label>Motivo de la cita <span style="color:red;">*</span></label>
                    <textarea class="form-control" name="motivo_consulta" id="motivo_consulta"></textarea>
                    <input type="hidden" name="id_sucursal" id="id_sucursal" value="<?php echo $_SESSION['id_sucursal']; ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_add_fc">Agregar</button>
        <button type="button" class="btn btn-default" id="btn_cerrar_fc" data-dismiss="modal">Cerrar</button>
    </div>
    <script type="text/javascript">
        
        $(document).ready(function(){
            if($("#id_sucursal").val() == "1"){
                var disableSpecificDates = ["1-1", "1-4", "2-4","3-4", "1-5","10-5","17-6","15-9","2-11","25-12","11-3"];
            }
            else{
                var disableSpecificDates = ["1-1", "1-4", "2-4","3-4", "1-5","10-5","17-6","15-9","2-11","25-12","21-11"];
            }
            var dateToday = new Date();
            $("#hora_nueva_consulta").timepicki();
            $("#fecha_nueva_consulta").datepicker({ 
                format: 'dd-mm-yyyy', 
                beforeShowDay: function(date){
                    dmy = date.getDate() + "-" + (date.getMonth() + 1);
                    if(disableSpecificDates.indexOf(dmy) != -1){
                        return false;
                    }
                    else{
                        return true;
                    }
                }
            });
            $('#fecha_nueva_consulta').datepicker("setDate", new Date());
            
            $(".select").select2();
        });
        
    </script>
    <!--/modal-footer -->
    <?php
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function add_futura_consulta()
{
    $hora = $_POST['hora'];
    $hora = _hora_media_encode($hora);
    $fecha = MD($_POST['fecha']);
    $futura = 0;
    
    if($fecha > date("Y:m:d")){
        $futura = 1;
    }
    $fecha = $fecha;
    $motivo = $_POST['motivo'];
    $espacio = $_POST['consultorio'];
    $id_cita = $_POST ['id_cita'];
    $sql = "SELECT * FROM reserva_cita WHERE id = '$id_cita'";
    $query = _query($sql);
    $row = _fetch_array($query);
    $id_paciente = $row['id_paciente'];
    $doctor = $row['id_doctor'];

    $sql2 = "SELECT * FROM espacio WHERE id_espacio = '$espacio'";
    $query2 = _query($sql2);
    $row2 = _fetch_array($query2);
    $descripcion_espacio = $row2['descripcion'];

    $table = 'reserva_cita';
    $usuario = $_SESSION["id_usuario"];
    $form_data = array(	
        'fecha_cita' => $fecha,
        'hora_cita' => $hora,
        'id_paciente' => $id_paciente,
        'id_doctor' => $doctor,
        'id_espacio' => $espacio,
        'id_usuario' => $usuario,
        'motivo_consulta' => $motivo,
        'observaciones' => '',
        'estado' => '1',
        'diagnostico' => '',
        'examen' => '',
        'medicamento' => '',
        't_o' => '',
        'ta' => '',
        'p' => '',
        'peso' => '',
        'fr'=> '',
        'futura' => $futura
    );
    $insert = _insert($table, $form_data);
    if($insert){    
        $id_reserva = _insert_id();
        $xdatos["id_reserva"] = $id_reserva;
        $xdatos["descripcion_espacio"] = $descripcion_espacio;
        $xdatos["typeinfo"] = "Success";
    }
    else{
        $xdatos["typeinfo"] = "Error";
    }
	echo json_encode ($xdatos);
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else {
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formDelete' :
				initial();
				break;
			case 'add_futura_consulta' :
				add_futura_consulta();
				break;
		}
	}
}

?>