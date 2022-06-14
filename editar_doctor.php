<?php
include_once "_core.php";
function initial() 
{
    $title='Editar Médico';
    $_PAGE = array ();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2-bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

    include_once "header.php";
    include_once "main_menu.php";   

    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);

    //Request Id
    $id_doctor=$_REQUEST["id_doctor"];
    
    //Get data from db
    $sql=_query("SELECT * FROM doctor WHERE id_doctor='$id_doctor'");
    $row = _fetch_array($sql);
    $nombre=$row['nombres'];
    $apellido=$row['apellidos'];
    $telefono=$row["telefono"];
    $email=$row["email"];
    $sexo = $row["sexo"];
    $fecha = ED($row["fecha_nac"]); 
    $direccion = $row["direccion"];//.", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
    $jvpm = $row["jvpm"];
    $especialidad = $row["id_especialidad"];
    $subespecialidad = $row["id_subespecialidad"];
?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
            <?php 
            //permiso del script
            if ($links!='NOT' || $admin=='1' ){
            ?>
            <div class="ibox-title">
                <h3 style="color:#194160;"><i class="fa fa-user-md"></i> <b><?php echo $title;?></b></h3> (Los campos marcados con <span style="color:red;">*</span> son requeridos)
            </div>
            <div class="ibox-content">
                <form name="formulario_doctor" id="formulario_doctor" autocomplete='off'>
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
                                <select class="col-md-12 select" id="sexo" name="sexo">
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
                                <input type="text" class="form-control tel" id="telefono" name="telefono" value="<?php echo $telefono; ?>">
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
                        <div class="col-md-6">                                
                            <div class="form-group has-info">
                                <label>JVPM <span style="color:red;">*</span></label>
                                <input type="text" class="form-control numeric" id="jvpm" name="jvpm" value="<?php echo $jvpm; ?>">
                            </div>       
                        </div>
                        <div class="col-md-6">                                
                            <div class="form-group has-info">
                                <label>Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                            </div>       
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Especialidad <span style="color:red;">*</span></label>
                                <select class="col-md-12 select" id="especialidad" name="especialidad">
                                    <option value="">Seleccione</option>
                                    <?php 
                                        $sqlp = "SELECT * FROM especialidad";
                                        $resultp=_query($sqlp);
                                        while($pco = _fetch_array($resultp))
                                        {
                                            echo "<option value='".$pco["id_especialidad"]."'";
                                            if($pco["id_especialidad"] == $especialidad)
                                            {
                                                echo " selected ";
                                            }
                                            echo ">".$pco["descripcion"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>    
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Subespecialidad</label>
                                <select class="col-md-12 select" id="subespecialidad" name="subespecialidad">
                                    <option value="">Seleccione</option>
                                    <?php 
                                        $sqlp1 = "SELECT * FROM especialidad";
                                        $resultp1=_query($sqlp1);
                                        while($pco1 = _fetch_array($resultp1))
                                        {
                                            echo "<option value='".$pco1["id_especialidad"]."'";
                                            if($pco1["id_especialidad"] == $subespecialidad)
                                            {
                                                echo " selected ";
                                            }
                                            echo">".$pco1["descripcion"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>    
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="edit">
                                <input type="hidden" name="id_doctor" id="id_doctor" value="<?php echo $id_doctor; ?>">
                                <input type="hidden" name="usuario" id="usuario" value="usuario">
                                <input type="hidden" name="password" id="password" value="password">
                                <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs pull-right"/>  
                            </div>
                        </div>
                    </div>      
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_doctor.js'></script>";
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}   
}

function editar()
{
    $id_doctor=$_POST["id_doctor"];
    $fecha_actual = date("Y-m-d");
    $nombre=$_POST["nombre"];
    $apellido=$_POST["apellido"];
    $sexo=$_POST["sexo"];
    $fecha=MD($_POST["fecha"]);
    $direccion=$_POST["direccion"];
    $telefono=$_POST["telefono"];
    $email=$_POST["email"];
    $especialidad=$_POST["especialidad"];
    $subespecialidad=$_POST["subespecialidad"];
    $jvpm=$_POST["jvpm"];

    $table = 'doctor';
    
    $form_data = array( 
    'nombres' => $nombre,
    'apellidos' => $apellido,
    'sexo' => $sexo,
    'fecha_nac' => $fecha,
    'email' => $email,
    'direccion' => $direccion,
    'telefono' => $telefono,
    'id_especialidad' => $especialidad,
    'id_subespecialidad' => $subespecialidad,
    'jvpm' => $jvpm
    );      
    $where_clause = "id_doctor = '".$id_doctor."'";
    $update = _update($table,$form_data, $where_clause);
    if($update)
    {
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Médico editado correctamente';
        $xdatos['process']='insert';
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Médico no pudo ser editado';
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



