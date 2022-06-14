<?php
include ("_core.php");
function initial()
{
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $filename = "signos.php";
    $links=permission_usr($id_user,$filename);

    $id = $_REQUEST['id'];
    $query = _query("SELECT id_paciente FROM reserva_cita WHERE id ='$id'");
    $datos = _fetch_array($query);
    $nombre = buscar($datos["id_paciente"]);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Evaluación Preliminar</h4>
</div>
<div class="modal-body"> 
    <?php 
        //permiso del script
        if ($links!='NOT' || $admin=='1' ){
    ?>
    <form class="form-horizontal" id="add_signo" autocomplete="off">
    <div class="row">
        <div class="form-group">
            <div class="col-md-12">
                <label>Paciente: <?php echo $nombre; ?></label>
            </div>
        </div>
    </div>
    <div class="row">    
        <div class="form-group">            
            <div class="col-md-6">
                <label>Estatura (mt)</label> 
                <input type="text" class="form-control numeric" id="estatura" name="estatura">
            </div>        
            <div class="col-md-6">
                <label>Peso (lb)</label> 
                <input type="text" class="form-control numeric" id="peso" name="peso">
            </div>        
        </div>
    </div>
    <div class="row">    
        <div class="form-group">            
            <div class="col-md-6">
                <label>Temperatura (°C)</label> 
                <input type="text" class="form-control numeric" id="temperatura" name="temperatura">
            </div>                  
            <div class="col-md-6">
                <label>Presión </label> 
                <input type="text" class="form-control" id="presion" name="presion">
            </div>   
        </div>   
    </div>
    <!--<div class="row">      
        <div class="form-group">     
            <div class="col-md-6">
                <label>Frecuencia Cardíaca </label> 
                <input type="text" class="form-control numeric" id="frecuencia_c" name="frecuencia_c">
            </div>        
            <div class="col-md-6">
                <label>Frecuencia Respiratoria </label> 
                <input type="text" class="form-control " id="frecuencia_r" name="frecuencia_r">
            </div>    
        </div>   
    </div>-->
    <div class="row">      
        <div class="form-group">       
            <div class="col-md-12">
                <label>Observaciones </label>
                <input type="text" name="observaciones" id="observaciones" class="form-control">
                <input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $id; ?>">
                <input type="hidden" name="process" id="process" value="insert">
            </div>    
        </div>    
    </div>
    </form>
</div>
<div class="modal-footer">
    <a class="btn btn-default" data-dismiss="modal" id="btn_ca">Cerrar</a>
    <a class="btn btn-primary" id="btn_add">Guardar</a>
</div>
<script type="text/javascript">
    $(".numeric").numeric({negative:false});
</script>
<?php
} //permiso del script
else {
        echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
    }   
}
function insert()
{
    $id = $_POST ['id_paciente'];
    $query = _query("SELECT id_paciente FROM reserva_cita WHERE id ='$id'");
    $datos = _fetch_array($query);
    $id_paciente = $datos["id_paciente"];
    $estatura = $_POST ['estatura'];
    $peso = $_POST ['peso'];
    $temperatura = $_POST ['temperatura'];
    $presion = $_POST ['presion'];
    //$frecuencia_c = $_POST ['frecuencia_c'];
    //$frecuencia_r = $_POST ['frecuencia_r'];
    $observaciones = $_POST ['observaciones'];
    $id_user=$_SESSION["id_usuario"];
    
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    $table = 'signos_vitales';

    $form_data = array(
        'id_paciente' => $id_paciente,
        'id_cita' => $id,
        'estatura' => $estatura,
        'peso' => $peso,
        'temperatura' => $temperatura,
        'presion' => $presion,
        //'frecuencia_cardiaca' => $frecuencia_c,
        //'frecuencia_respiratoria' => $frecuencia_r,
        'fecha' => $fecha,
        'hora' => $hora,
        'observaciones' => $observaciones,
        'id_usuario' => $id_user
        );

    $insert = _insert ($table,$form_data);
    if ($insert)
    {
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['id'] = $id;
        $xdatos ['msg'] = 'Evaluación ingresada correctamente';
    }
    else 
    {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'Evaluación no pudo ser ingresada';
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