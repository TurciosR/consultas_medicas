<?php
include ("_core.php");
function initial()
{
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $filename ="referencia.php";
    $links=permission_usr($id_user,$filename);

    $id = $_REQUEST['id_cita'];
    $id_paciente = $_REQUEST["id_paciente"];
    $nombre = buscar($id_paciente);
    $query_a = _query("SELECT * FROM referencia WHERE id_cita='$id' AND id_paciente='$id_paciente'");
    $destino = "";
    $motivo = "";
    $observaciones = "";
    if(_num_rows($query_a) > 0){
        $datos_a = _fetch_array($query_a);
        $destino = $datos_a["destino"];
        $motivo = $datos_a["motivo"];
        $observaciones = $datos_a["observaciones"];
        $id_doctor = $datos_a['id_doctor'];
    }
    
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Referir Paciente</h4>
</div>
<div class="modal-body"> 
    <?php 
        //permiso del script
        if ($links!='NOT' || $admin=='1' ){
    ?>
    <form class="form-horizontal" id="add_ref" autocomplete="off">
    <div class="row">
        <div class="form-group">
            <div class="col-md-12">
                <label>Paciente: <?php echo $nombre; ?></label>
            </div>
        </div>
    </div>
    <div class="row">    
        <div class="form-group">            
            <div class="col-md-12">
                <label>Doctor</label> 
                <br>
                <select name="doctor_refiere" id="doctor_refiere" class="select" style="width:100%">
                    <?php
                        $sql_doctor = "SELECT * FROM doctor";
                        $query_doctor = _query($sql_doctor);
                        while ($row_doctor = _fetch_array($query_doctor)) {
                            echo "<option value='".$row_doctor['id_doctor']."' ";
                            if($row_doctor['id_doctor'] == $id_doctor){
                                echo " selected ";
                            }
                            echo ">";
                            $nombre_doc = $row_doctor['nombres']." ".$row_doctor['apellidos'];
                            echo $nombre_doc;
                            echo "</option>";
                        }
                    ?>
                </select>
            </div>       
        </div>
    </div>
    <div class="row">    
        <div class="form-group">            
            <div class="col-md-6">
                <label>Destino</label> 
                <input type="text" class="form-control" id="destino" name="destino" value="<?php echo $destino; ?>">
            </div>        
            <div class="col-md-6">
                <label>Motivo</label> 
                <input type="text" class="form-control" id="motivo" name="motivo" value="<?php echo $motivo; ?>">
            </div>        
        </div>
    </div>
    <div class="row">      
        <div class="form-group">       
            <div class="col-md-12">
                <label>Observaciones </label>
                <input type="text" name="observaciones" id="observaciones" class="form-control" value="<?php echo $observaciones; ?>">
                <input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $id; ?>">
                <input type="hidden" name="process" id="process" value="insert">
            </div>    
        </div>    
    </div>
    </form>
</div>
<div class="modal-footer">
    <a class="btn btn-default" data-dismiss="modal" id="btn_ca">Cerrar</a>
    <a class="btn btn-primary" id="btn_add_ref">Guardar</a>
    <a class="btn btn-warning"  href="referencia_pdf.php?<?php echo "id_cita=".$id?>" target="_blank"> Imprimir</a>
</div>
<script>
    $(document).ready(function(){
        $(".select").select2();
    });
<?php
} //permiso del script
else {
        echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
    }   
}
function insert()
{
    $id = $_POST ['id_paciente'];
    $id_doctor = $_POST['doctor_refiere'];
    $query = _query("SELECT * FROM reserva_cita WHERE id ='$id'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $destino = $_POST ['destino'];
    $motivo = $_POST ['motivo'];
    $observaciones = $_POST ['observaciones'];
    $id_user=$_SESSION["id_usuario"];

    
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    $table = 'referencia';

    $form_data = array(
        'id_paciente' => $id_paciente,
        'id_cita' => $id,
        'destino' => $destino,
        'motivo' => $motivo,
        'observaciones' => $observaciones,
        'id_doctor' => $id_doctor
        );
    $query_a = _query("SELECT * FROM referencia WHERE id_cita='$id' AND id_paciente='$id_paciente'");
    $num = _num_rows($query_a);
    if($num>0)
    {
        $datos_a = _fetch_array($query_a);
        $id_ref  = $datos_a["id_referencia"];  
        $where_clause  = "id_referencia='".$id_ref."'";
        $insert = _update($table, $form_data, $where_clause);  
    }
    else
    {
        $insert = _insert ($table,$form_data);    
    }
    if ($insert)
    {
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['id'] = $id;
        $xdatos ['msg'] = 'Datos guradados correctamente';
    }
    else 
    {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'Datos no pudieron ser guardados';
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
            case 'forminsert' :
                initial();
                break;
            case 'insert' :
                insert();
                break;
        }
    }
}
?>