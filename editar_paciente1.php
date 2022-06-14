<?php
include_once "_core.php";
function initial() 
{
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename="editar_paciente.php";
    $links=permission_usr($id_user,$filename);
    
    //Request Id
    $id_paciente=$_REQUEST["id"];
    $id_cita=$_REQUEST["id_cita"];
    
    //Get data from db
    $sql_paciente = _query("SELECT p.*, d.id_departamento FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id_paciente'");
    $row = _fetch_array($sql_paciente);

    $nombre = $row['nombres'];
    $apellido = $row['apellidos'];
    $telefono1 = $row["tel1"];
    $sexo = $row["sexo"];
    $fecha = ED($row["fecha_nacimiento"]); 
    $direccion = $row["direccion"];
                
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Editar Paciente</h4>
</div>
<div class="modal-body">
<h5>Los campos marcados con <span style="color:red;">*</span> son requeridos</h5>
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
                <?php 
                //permiso del script
                if ($links!='NOT' || $admin=='1' )
                {
                ?>
                <form name="formulario_paciente" id="formulario_paciente" autocomplete='off'>
                    <div class="row">
                        <div class="form-group has-info col-md-6">
                            <label>Nombres <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group has-info col-md-6">
                              <label>Apellidos <span style="color:red;">*</span></label>
                              <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $apellido; ?>">
                        </div>
                    </div>
                    <div class="row">                
                        <div class="col-md-6">                                
                            <div class="form-group has-info">
                                <label>Género <span style="color:red;">*</span></label>
                                <select class="col-md-12 form-control" id="sexo" name="sexo" style="width: 100%;">
                                    <option value="">Seleccione</option>
                                    <option value="Masculino" <?php if($sexo=="Masculino") echo " selected "; ?>>Masculino</option>
                                    <option value="Femenino" <?php if($sexo=="Femenino") echo " selected "; ?>>Femenino</option>
                                </select>
                            </div>       
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Fecha de Nacimiento <span style="color:red;">*</span></label> 
                                <input type="text" class="form-control datepicker" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
                            </div>        
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Teléfono <span style="color:red;">*</span></label>
                                <input type="text" class="form-control tel" id="telefono1" name="telefono1" value="<?php echo $telefono1; ?>">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Dirección <span style="color:red;">*</span></label> 
                                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion; ?>">
                            </div>        
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="edit">
                                <input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $id_paciente; ?>"> 
                                <input type="hidden" name="id_cita" id="id_cita" value="<?php echo $id_cita; ?>"> 
                            </div>
                        </div>
                    </div>      
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".datepicker").datepicker({
        format: 'dd-mm-yyyy',
        language:'es',
    });
    $("#sexo").select2();
</script>
<div class="modal-footer">
<?php
echo "<button type='button' class='btn btn-primary' id='btn_guardar'>Guardar</button>
<button type='button' class='btn btn-default' data-dismiss='modal' id='btn_ce'>Cerrar</button>
    </div><!--/modal-footer -->";
//include_once ("footer.php");
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
} 
}
function editar()
{
    $id_paciente = $_POST["id_paciente"];
    $id_cita = $_POST["id_cita"];
    $nombre=$_POST["nombre"];
    $apellido=$_POST["apellido"];
    $sexo=$_POST["sexo"];
    $fecha=MD($_POST["fecha"]);
    $direccion=$_POST["direccion"];
    $telefono1=$_POST["telefono1"];
    $table = 'paciente';
    
    $form_data = array( 
    'nombres' => $nombre,
    'apellidos' => $apellido,
    'sexo' => $sexo,
    'fecha_nacimiento' => $fecha,
    'direccion' => $direccion,
    'tel1' => $telefono1
    );    

    $where_clause = "id_paciente = '".$id_paciente."' ";
    $update = _update($table,$form_data,$where_clause);
    if($update)
    {
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Paciente modificado correctamente';
        $xdatos['process']='insert';
        $xdatos['id']=$id_cita;
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Paciente no pudo ser modificado';
    }    
    echo json_encode($xdatos);
}
if(!isset($_POST['process']))
{
    initial(); 
}
else
{
    if(isset($_POST['process']))
    { 
        switch ($_POST['process'])
        {
            case 'edit':
                editar();
                break;
        } 
    }     
}
?>



