<?php
include_once "_core.php";
function initial() 
{
    $title='Editar Paciente';
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
    $_PAGE ['links'].=  '<link href="css/estilos.css", rel="stylesheet">';
    

    include_once "header.php";
    include_once "main_menu.php"; 

    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);
    
    //Request Id
    $id_paciente=$_REQUEST["id_paciente"];
    
    //Get data from db
    $sql_paciente = _query("SELECT p.*, d.id_departamento FROM paciente AS p, departamento AS d, municipio AS m WHERE p.municipio = m.id_municipio AND m.id_departamento_municipio = d.id_departamento  AND p.id_paciente='$id_paciente'");
    $row = _fetch_array($sql_paciente);

    $nombre = $row['nombres'];
    $apellido = $row['apellidos'];
    $telefono1 = $row["tel1"];
    $telefono2 = $row["tel2"];
    $email=$row["email"];
    $sexo = $row["sexo"];
    $fecha = ED($row["fecha_nacimiento"]); 
    $direccion = $row["direccion"];
    $departamento = $row["id_departamento"];
    $municipio = $row["municipio"];
    $notificacion = $row["notificaciones"];
    $padecimientos = $row["padecimientos"];
    $responsable = $row["responsable"];
    $parentezco = $row["parentezco_responsable"];
    $medicamentos = $row["medicamento_permanente"];
    $alergias = $row["alergias"];
    $dui=$row["dui"];
    $estado_civil=$row["estado_civil"];
    $religion=$row["religion"];
    $conyuge=$row["conyuge"];
    $grupo_sanguineo=$row["grupo_sanguineo"];
    $referido=$row["referido"];
    $escolaridad=$row["escolaridad"];
                
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
                 <h3 style="color:#194160;"><i class="fa fa-user"></i> <b><?php echo $title;?></b></h3> (Los campos marcados con <span style="color:red;">*</span> son requeridos)
            </div>
            <div class="ibox-content">
                <form name="formulario_paciente" id="formulario_paciente" autocomplete='off'>
                    <div class="row">
                        <div class="form-group has-info col-md-6 solo_mayu">
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
                                <label>DUI</label>
                                <input type="text" class="form-control" id="dui" name="dui" value="<?php echo $dui; ?>">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Estado Civil</label>
                                <input type="text" class="form-control" id="estado_civil" name="estado_civil" value="<?php echo $estado_civil; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row" hidden>      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Religión</label>
                                <input type="text" class="form-control" id="religion" name="religion" value="<?php echo $religion; ?>">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Escolaridad</label>
                                <input type="text" class="form-control" id="escolaridad" name="escolaridad" value="<?php echo $escolaridad; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row" hidden>      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Nombre del Conyuge</label>
                                <input type="text" class="form-control" id="conyuge" name="conyuge" value="<?php echo $conyuge; ?>">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Grupo Sanguíneo</label>
                                <input type="text" class="form-control" id="grupo_sanguineo" name="grupo_sanguineo" value="<?php echo $grupo_sanguineo; ?>">
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
                                <label>Teléfono 2</label>
                                <input type="text" class="form-control tel" id="telefono2" name="telefono2" value="<?php echo $telefono2; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Departamento <span style="color:red;">*</span></label>
                                <select class="col-md-12 select" id="departamento" name="departamento">
                                    <option value="">Seleccione</option>
                                    <?php 
                                        $sqld = "SELECT * FROM departamento";
                                        $resultd=_query($sqld);
                                        while($depto = _fetch_array($resultd))
                                        {
                                            echo "<option value='".$depto["id_departamento"]."'";
                                            if($depto["id_departamento"] == $departamento)
                                            {
                                                echo " selected ";
                                            }
                                            echo">".$depto["nombre_departamento"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Municipio <span style="color:red;">*</span></label>
                                <select class="col-md-12 select" id="municipio" name="municipio">
                                    <?php 
                                        $sqld = "SELECT * FROM municipio WHERE id_departamento_municipio ='$departamento'";
                                        $resultd=_query($sqld);
                                        while($depto = _fetch_array($resultd))
                                        {
                                            echo "<option value='".$depto["id_municipio"]."'";
                                            if($depto["id_municipio"] == $municipio)
                                            {
                                                echo " selected ";
                                            }
                                            echo">".$depto["nombre_municipio"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>                            
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Dirección <span style="color:red;">*</span></label> 
                                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $direccion; ?>">
                            </div>        
                        </div>
                        <div class="col-md-6" hidden>                                
                            <div class="form-group has-info">
                                <label>Email</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                            </div>       
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Responsable</label>
                                <input type="text" class="col-md-12 form-control" id="responsable" name="responsable" value="<?php echo $responsable; ?>">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Parentezco</label>
                                <select class="col-md-12 select" id="parentezco" name="parentezco">
                                    <option value="">Seleccione</option>
                                    <?php 
                                        $sqlp = "SELECT * FROM parentezco";
                                        $resultp=_query($sqlp);
                                        while($pco = _fetch_array($resultp))
                                        {
                                            echo "<option value='".$pco["id_parentezco"]."'";
                                            if($pco["id_parentezco"] == $parentezco)
                                            {
                                               echo " selected ";
                                            }
                                            echo ">".$pco["descripcion"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>    
                        </div>
                    </div>
                    <div class="row" hidden>      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Alergias</label>
                                <textarea class="form-control col-md-12" rows="1" name='alergias' id='alergias'><?php echo $alergias; ?></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Padecimientos</label>
                                <textarea class="form-control col-md-12" rows="1" name='padecimientos' id='padecimientos'><?php echo $padecimientos; ?></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row" hidden>      
                        <div class="col-md-6">
                            <div class="form-group has-info"><br>
                                <label>Medicamentos Permánentes</label>
                                <textarea class="form-control col-md-12" rows="1" name='medicamentos' id='medicamentos'><?php echo $medicamentos; ?></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info"><br>
                                <label>Forma de Notificación <span style="color:red;">*</span></label>
                                <select class="select col-md-12" name="tipo" id="tipo">
                                    <option value="">Seleccione</option>
                                    <option value="Teléfono" <?php if($notificacion=="Teléfono") echo " selected "; ?>>Teléfono</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row" hidden>      
                        <div class="col-md-6">
                            <div class="form-group has-info"><br>
                                <label>Referido por</label>
                                <input type="text" class="form-control" name='referido' id='referido' value="<?php echo $referido; ?>">
                            </div> 
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="edit">
                                <input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $id_paciente; ?>">
                                <input type="hidden" name="notificacion" id="notificacion" value="<?php echo $notificacion; ?>">
                                <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs pull-right" />  
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
echo "<script src='js/funciones/funciones_paciente.js'></script>";
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
} 
}

function editar()
{
    $id_paciente = $_POST["id_paciente"];
    $nombre=$_POST["nombre"];
    $apellido=$_POST["apellido"];
    $sexo=$_POST["sexo"];
    $fecha=MD($_POST["fecha"]);
    $direccion=$_POST["direccion"];
    $telefono1=$_POST["telefono1"];
    $telefono2=$_POST["telefono2"];
    $email=$_POST["email"];
    $municipio=$_POST["municipio"];
    $tipo= $_POST["tipo"];
    $tipo = quitar_tildes($tipo);
    $alergias=trim($_POST["alergias"]);
    $padecimientos=trim($_POST["padecimientos"]);
    $medicamentos=trim($_POST["medicamentos"]);
    $responsable=$_POST["responsable"];
    $parentezco=$_POST["parentezco"];
    if($parentezco == ''){
        $parentezco = 0;
    }
    $dui=$_POST["dui"];
    $estado_civil=$_POST["estado_civil"];
    $religion=$_POST["religion"];
    $conyuge=$_POST["conyuge"];
    $grupo_sanguineo=$_POST["grupo_sanguineo"];
    $referido=$_POST["referido"];
    $escolaridad=$_POST["escolaridad"];

    $table = 'paciente';
    
    $form_data = array( 
        'nombres' => $nombre,
        'apellidos' => $apellido,
        'sexo' => $sexo,
        'fecha_nacimiento' => $fecha,
        'email' => $email,
        'municipio' => $municipio,
        'direccion' => $direccion,
        'tel1' => $telefono1,
        'tel2' => $telefono2,
        'responsable' => $responsable,
        'parentezco_responsable'=>$parentezco,
        'padecimientos'=>$padecimientos,
        'medicamento_permanente'=>$medicamentos,
        'alergias'=>$alergias,
        'notificaciones'=>$tipo,
        'dui'=>$dui,
        'estado_civil'=>$estado_civil,
        'religion'=>$religion,
        'conyuge'=>$conyuge,
        'grupo_sanguineo'=>$grupo_sanguineo,
        'referido'=>$referido,
        'escolaridad'=>$escolaridad
    );    

    $where_clause = "id_paciente = '".$id_paciente."' ";
    $update = _update($table,$form_data,$where_clause);
    if($update)
    {
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Paciente modificado correctamente';
        $xdatos['process']='insert';
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



