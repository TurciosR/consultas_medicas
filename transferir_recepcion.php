<?php
include ("_core.php");
function initial(){
    $id_recepcion = $_REQUEST['idRecepcion'];
    $sql = "SELECT recepcion.id_tipo_recepcion, tipo_recepcion.tipo , paciente.nombres, paciente.apellidos, recepcion.evento FROM recepcion INNER JOIN paciente on recepcion.id_paciente_recepcion = paciente.id_paciente LEFT JOIN tipo_recepcion on recepcion.id_tipo_recepcion=tipo_recepcion.id_tipo_recepcion WHERE recepcion.id_recepcion ='$id_recepcion'";
    $query = _query($sql);
    $row = _fetch_array($query);
    $nombre = $row['nombres'];
    $apellidos = $row['apellidos'];
    $evento = $row['evento'];
    $tipo_recepcion=$row['tipo'];
    $id_tipo_recepcion = $row['id_tipo_recepcion'];
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-navy">Transferir Recepcion:</h4>
    </div>
    <div class="modal-body">
        <div class="wrapper wrapper-content   animated fadeInRight">
            <div class="row " id="row1">
                <div class="col-lg-12">
                <?php	if ($links!='NOT' || $admin=='1' ){ ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-info text-center alert alert-info">
                                <label><?php echo "Transferir al paciente: ".$nombre." ".$apellidos."."; ?></label>
                                <label><?php echo "<br>Evento:".$evento; ?></label>
                            </div>
                        </div>
                        <div class="col-lg-12 ">
                            <div>
                                <h5>Paciente se encuentra en: </h5>
                                <label id="recepcion_actual"><?php echo $tipo_recepcion ?></label>
                            </div>
                            <div class="form-group render has-info single-line">
                                <label>Transferir a<span style="color:red;">*</span></label>
                                <br>
                                <select class="form-control  select" name="tipo_recepcion_transferir" id="tipo_recepcion_transferir" style="width:100%;">
                                    <option value="">Seleccione donde enviarlo</option>
                                <?php
                                    $sql_tipo = "SELECT * FROM tipo_recepcion where activo = '1' AND tipo_recepcion.id_tipo_recepcion != '$id_tipo_recepcion'";
                                    $query_tipo = _query($sql_tipo);
                                    while($row_tipo = _fetch_array($query_tipo)){
                                        if($row_tipo['tipo']!='CONSULTA'){
                                            echo "<option value='".$row_tipo["id_tipo_recepcion"]."'>".$row_tipo["descripcion"]."</option>";
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <br>
                    </div>
                    <input type="hidden" id="id_tipo_transferir_seleccionado" value="">
                    <input type="hidden" id="text_tipo_seleccionado" value="">
                    <input type="hidden" id="id_recepcion" value="<?php echo $id_recepcion ?>">
                    <input type="hidden" name="id_tipo_activo_anterior" id='id_tipo_activo_anterior' value="<?php echo $id_tipo_recepcion; ?>">
                    <input type="hidden" name="id_recepcion_transferir" id='id_recepcion_transferir' value="<?php echo $id_recepcion; ?>">
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(".select").select2({
                dropdownParent:$("#transferenciaModal"),
            });
            $("#tipo_recepcion_transferir").on('select2:select', function(e){
                var data = e.params.data;
                var id_tipo = data.id;
                var text_tipo=data.text;
                $("#id_tipo_transferir_seleccionado").val(id_tipo);
                $("#text_tipo_seleccionado").val(text_tipo);
                console.log(data);
            });
            
        });
        

        $(function() {
            $(document).on('click', '#btnTransferir', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                transferir_paciente();
            });
            $(document).on('hidden.bs.modal', function(e) {
                var target = $(e.target);
                target.removeData('bs.modal').find(".modal-content").html('');
            });

        });

        function transferir_paciente(){
            var id_recepcion = $("#id_recepcion").val();
            
            var id_tipo_transferir_seleccionado=$("#id_tipo_transferir_seleccionado").val();
            var text_tipo_seleccionado=$("#text_tipo_seleccionado").val();
            var data = {
                'id_recepcion':id_recepcion,
                'id_tipo_transferir_seleccionado':id_tipo_transferir_seleccionado,
                'text_tipo_seleccionado':text_tipo_seleccionado,
                'process':'transferir'
            };
            //alert(data);
            $.ajax({
                url:'transferir_recepcion.php',
                type:'POST',
                data:data,
                dataType:'json',
                success:function(datax){
                    console.log(datax.datos)
                    display_notify(datax.typeinfo, datax.msg);
                    if (datax.typeinfo == "Success") {
                        setTimeout(() => {
                            reload1() 
                        }, 1500);
                        $('#transferenciaModal').hide();
                    }
                }
            });
        }

        function transferir(){
            //alert("Hola Mundo");//..
            var id_recepcion = $('#id_recepcion_transferir').val();
            var id_tipo_recepcion = $('#tipo_recepcion_transferir').val();
            var id_tipo_activo_anterior=$("#id_tipo_activo_anterior").val();
            //alert(id_recepcion);
            var dataString = 'process=estado' + '&id_recepcion=' + id_recepcion+"&id_tipo_recepcion="+id_tipo_recepcion+"&id_tipo_activo_anterior="+id_tipo_activo_anterior;
            alert(dataString);
            $.ajax({
                type: "POST",
                url: "transferir_recepcion.php",
                data: dataString,
                dataType: 'json',
                success: function(datax) {
                    display_notify(datax.typeinfo, datax.msg);
                    if (datax.typeinfo == "Success") {
                        setTimeout(() => {
                            reload1() 
                        }, 1500);
                        $('#transferenciaModal').hide();
                    }
                }
            });
        }
        function reload1(){
            location.href = 'admin_recepcion.php';
        }
    </script>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="transferir_paciente();" id="btnTransferir">Transferir</button>
    <?php
        echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
        </div><!--/modal-footer -->";
    }
    else {
        echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
    }
}

function transferir_paciente(){
    $id_recepcion = $_POST["id_recepcion"];
    $id_tipo_transferir_seleccionado=$_POST["id_tipo_transferir_seleccionado"];
    $text_tipo_seleccionado=$_POST["text_tipo_seleccionado"];
    
    //tabla a actualizar
    $table='recepcion';
    $form_data=array();
    $form_data['id_tipo_recepcion']=$id_tipo_transferir_seleccionado;
    switch($text_tipo_seleccionado){
        case 'EMERGENCIA':#campo recepcion_emergencia
            $form_data['id_tipo_recepcion']=$id_tipo_transferir_seleccionado;
            $form_data['recepcion_emergencia']=1;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'HOSPITALIZACION':#campo recepcion_hospitalizacion
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=1;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'PEDIATRIA':# campo recepcion_pediatria
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=1;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'ENFERMERIA A':# campo recepcion_estacion_enfermeria_a
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=1;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'ENFERMERIA B':#campo recepcion_estacion_enfermeria_b
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=1;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'SALA ENFERMERIA':#campo recepcion_enfermeria_sala_estar
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=1;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'NEFROLOGIA':#campo recepcion_nefrologia
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=1;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'RAYOS X':#recepcion_rayosx
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=1;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'RECEPCION UCI':#recepcion_uci
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=1;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'TERAPIA RESPIRATORIA':#campo recepcion_terapia_respiratoria
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=1;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'LABORATORIO'://campo recepcion_laboratorio
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=1;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'SALA DE OPERACIONES'://recepcion_sala_operaciones
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=1;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=0;
            break;
        case 'RECEPCION MICROCIRUGIA'://recepcion_microcirugia
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=1;
            $form_data['recepcion_consulta']=0;
            break;
        case 'CONSULTA'://recepcion_consulta
            $form_data['recepcion_emergencia']=0;//cambiando de estado
            $form_data['recepcion_hospitalizacion']=0;
            $form_data['recepcion_pediatria']=0;
            $form_data['recepcion_estacion_enfermeria_a']=0;
            $form_data['recepcion_estacion_enfermeria_b']=0;
            $form_data['recepcion_enfermeria_sala_estar']=0;
            $form_data['recepcion_nefrologia']=0;
            $form_data['recepcion_rayosx']=0;
            $form_data['recepcion_uci']=0;
            $form_data['recepcion_terapia_respiratoria']=0;
            $form_data['recepcion_laboratorio']=0;
            $form_data['recepcion_sala_operaciones']=0;
            $form_data['recepcion_microcirugia']=0;
            $form_data['recepcion_consulta']=1;
            break;
    }

    $where_update=' id_recepcion='.$id_recepcion;
    $table_update=_update($table, $form_data, $where_update);
    $xdatos=array();
    if($table_update){
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['msg'] = 'Recepcion transferida correctamente!';
        $xdatos['datos']=$form_data;
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'No se pudo transferir la recepcion';
    }
    echo json_encode($xdatos);

}

function estado()
{
    $id_recepcion = $_POST ['id_recepcion'];
    $id_tipo_recepcion = $_POST['id_tipo_recepcion'];
    $update_inicial = 1;
    if($update_inicial){
        if($id_tipo_recepcion == 1){
            $form_data_actualizar = array(
                'recepcion_hospitalizacion' => '1'
            );
        }
        if($id_tipo_recepcion == 2){
            $form_data_actualizar = array(
                'recepcion_emergencia' => '1'
            );
        }
        if($id_tipo_recepcion == 3){
            $form_data_actualizar = array(
                'recepcion_pediatria' => '1'
            );
        }
        if($id_tipo_recepcion == 4){
            $form_data_actualizar = array(
                'recepcion_estacion_enfermeria_a' => '1'
            );
        }
        if($id_tipo_recepcion == 5){
            $form_data_actualizar = array(
                'recepcion_estacion_enfermeria_b' => '1'
            );
        }
        if($id_tipo_recepcion == 6){
            $form_data_actualizar = array(
                'recepcion_enfermeria_sala_estar' => '1'
            );
        }
        if($id_tipo_recepcion == 7){
            $form_data_actualizar = array(
                'recepcion_nefrologia' => '1'
            );
        }
        if($id_tipo_recepcion == 8){
            $form_data_actualizar = array(
                'recepcion_rayosx' => '1'
            );
        }
        if($id_tipo_recepcion == 9){
            $form_data_actualizar = array(
                'recepcion_uci' => '1'
            );
        }
        if($id_tipo_recepcion == 10){
            $form_data_actualizar = array(
                'recepcion_terapia_respiratoria' => '1'
            );
        }
        if($id_tipo_recepcion == 11){
            $form_data_actualizar = array(
                'recepcion_sala_operaciones' => '1'
            );
        }
        if($id_tipo_recepcion == 12){
            $form_data_actualizar = array(
                'recepcion_microcirugia' => '1'
            );
        }
        if($id_tipo_recepcion == 13){
            $form_data_actualizar = array(
                'recepcion_hospitalizacion' => '1'
            );
        }
        if($id_tipo_recepcion == 14){
            $form_data_actualizar = array(
                'recepcion_consulta' => '1'
            );
        }
        $table_actualizar = 'recepcion';
        $where_actualizar = " id_recepcion = '$id_recepcion' ";
        $actualizar_final = _update($table_actualizar, $form_data_actualizar, $where_actualizar);
        if($actualizar_final){
            $xdatos ['typeinfo'] = 'Success';
            $xdatos ['msg'] = 'Recepcion transferida correctamente!';
        }
        else{
            $xdatos ['typeinfo'] = 'Error';
            $xdatos ['msg'] = 'No se pudo transferir la recepcion';
        }
    }
    else{
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'No se pudo transferir la recepcion';
    }
	echo json_encode ( $xdatos );
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else
{
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formDelete' :
				initial();
				break;
			case 'estado' :
				estado();
				break;
            case 'transferir':
                transferir_paciente();
                break;
		}
	}
}
?>
