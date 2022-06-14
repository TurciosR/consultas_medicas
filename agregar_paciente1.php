<?php
include_once "_core.php";
function initial() 
{
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename="agregar_paciente.php";
    $links=permission_usr($id_user,$filename);

?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Agregar Paciente</h4>
</div>
<div class="modal-body">
    <?php 
//permiso del script
if ($links!='NOT' || $admin=='1' )
{
?>
<h5>Los campos marcados con <span style="color:red;">*</span> son requeridos</h5>
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
                <form name="formulario_paciente" id="formulario_paciente" autocomplete='off'>
                    <div class="row">
                        <div class="form-group has-info col-md-6">
                            <label>Nombres <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>
                        <div class="form-group has-info col-md-6">
                              <label>Apellidos <span style="color:red;">*</span></label>
                              <input type="text" class="form-control" id="apellido" name="apellido">
                        </div>
                    </div>
                    <div class="row">                
                        <div class="col-md-6">                                
                            <div class="form-group has-info">
                                <label>Género <span style="color:red;">*</span></label>
                                <select class="col-md-12 form-control" id="sexo" name="sexo" style="width: 100%;">
                                    <option value="">Seleccione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>       
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Fecha de Nacimiento <span style="color:red;">*</span></label> 
                                <input type="text" class="form-control datepicker" id="fecha_n" name="fecha_n">
                            </div>        
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Teléfono <span style="color:red;">*</span></label>
                                <input type="text" class="form-control tel" id="telefono1" name="telefono1">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Dirección <span style="color:red;">*</span></label> 
                                <input type="text" class="form-control" id="direccion" name="direccion">
                            </div>        
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="edit">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">Recuerde completar el resto de los datos en la administración de pacientes</div>      
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
function insert()
{
    $sql_mun = _query("SELECT municipio FROM empresa WHERE id_empresa=1");
    $datos_mun = _fetch_array($sql_mun);
    $municipio = $datos_mun["municipio"];
    $nombre=$_POST["nombre"];
    $apellido=$_POST["apellido"];
    $sexo=$_POST["sexo"];
    $fecha=MD($_POST["fecha"]);
    $direccion=$_POST["direccion"];
    $telefono1=$_POST["telefono1"];
    $now = date("Y-m-d");
    $notificacion = "Teléfono";

    $sql_expediente = _query("SELECT max(expediente) as expediente FROM paciente");
    $row_expediente = _fetch_array($sql_expediente);
    $ult_expediente = $row_expediente["expediente"];
    $expediente = $ult_expediente+1;

    $table = 'paciente';
    
    $form_data = array( 
    'nombres' => $nombre,
    'apellidos' => $apellido,
    'sexo' => $sexo,
    'fecha_nacimiento' => $fecha,
    'municipio' => $municipio,
    'direccion' => $direccion,
    'tel1' => $telefono1,
    'notificaciones' => $notificacion,
    'expediente' => $expediente,
    'fecha_registro' => $now,
    'parentezco_responsable' => 0,
    'dui' => '',
    'estado_civil' => '',
    'religion' => '',
    'conyuge' => '',
    'grupo_sanguineo' => '',
    'referido' => '',
    'escolaridad' => '',
    'foto' => '',
    
    );    

    $insert = _insert($table,$form_data);
    if($insert)
    {
        $id_paciente = _insert_id();
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Paciente ingresado correctamente';
        $xdatos['process']='insert';
        $xdatos['id']=$id_paciente;
        $xdatos['nombre']=$nombre." ".$apellido;
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Paciente no pudo ser ingresado';
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
            case 'insert':
                insert();
                break;
        } 
    }     
}
?>



