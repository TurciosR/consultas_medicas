<?php
include_once "_core.php";
function initial() 
{
    $title='Agregar Médico';
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
                            <input type="text" placeholder="Nombres" class="form-control" id="nombre" name="nombre">
                        </div>
                        <div class="form-group has-info col-md-6">
                              <label>Apellidos <span style="color:red;">*</span></label>
                              <input type="text" placeholder="Apellidos" class="form-control" id="apellido" name="apellido">
                        </div>
                    </div>
                    <div class="row">                
                        <div class="col-md-6">                                
                            <div class="form-group has-info">
                                <label>Género <span style="color:red;">*</span></label>
                                <select class="col-md-12 select" id="sexo" name="sexo">
                                    <option value="">Seleccione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>       
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Fecha de Nacimiento <span style="color:red;">*</span></label> 
                                <input type="text" placeholder="00-00-0000" class="form-control datepicker" id="fecha" name="fecha">
                            </div>        
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Teléfono <span style="color:red;">*</span></label>
                                <input type="text"  placeholder="0000-0000" class="form-control tel" id="telefono" name="telefono">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Dirección <span style="color:red;">*</span></label> 
                                <input type="text" placeholder="Dirección" class="form-control" id="direccion" name="direccion">
                            </div>        
                        </div>
                    </div>
                    <div class="row">          
                        <div class="col-md-6">                                
                            <div class="form-group has-info">
                                <label>JVPM <span style="color:red;">*</span></label>
                                <input type="text" class="form-control numeric" id="jvpm" name="jvpm">
                            </div>       
                        </div>
                        <div class="col-md-6">                                
                            <div class="form-group has-info">
                                <label>Email</label>
                                <input type="text" placeholder="ejemplo@correo.com" class="form-control" id="email" name="email">
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
                                            echo "<option value='".$pco["id_especialidad"]."'>".$pco["descripcion"]."</option>";
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
                                        $sqlp = "SELECT * FROM especialidad";
                                        $resultp=_query($sqlp);
                                        while($pco = _fetch_array($resultp))
                                        {
                                            echo "<option value='".$pco["id_especialidad"]."'>".$pco["descripcion"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>    
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Usuario <span style="color:red;">*</span></label>
                                <input type="text" name="usuario" id="usuario" class="form-control lower">
                            </div>    
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Contraseña <span style="color:red;">*</span></label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>    
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="insert">
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

function insertar()
{
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
    
    $usuario=$_POST["usuario"];
    $password=$_POST["password"];

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
    $sql_exis = _query("SELECT * FROM doctor WHERE nombres='$nombre' AND apellidos='$apellido' AND jvpm='$jvpm'");
    $num_exis = _num_rows($sql_exis);
    $sql_exis_u = _query("SELECT * FROM usuario WHERE usuario='$usuario'");
    $exis_u = _num_rows($sql_exis_u);        
    if($num_exis==0)
    {
        if($exis_u == 0)
        { 
            _begin();
            $insertar = _insert($table,$form_data );
            if($insertar)
            {
                $id_doctor = _insert_id();
                    $table_u = "usuario";
                    $form_data_u = array(
                        'id_doctor' => $id_doctor,
                        'nombre' => $nombre." ".$apellido,
                        'usuario' => $usuario,
                        'password' => md5($password),
                        'tipo_usuario' => 2,
                        'activo' => 1
                        );
                    $insert_u = _insert($table_u, $form_data_u);
                    if($insert_u)
                    {
                        _commit();
                        $xdatos['typeinfo']='Success';
                        $xdatos['msg']='Médico ingresado correctamente';
                        $xdatos['process']='insert';
                    }
                    else
                    { 
                         _rollback();
                        $xdatos['typeinfo']='Error';
                        $xdatos['msg']='No se registro el usuario';
                    }
            }
            else
            {
                 _rollback();
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Médico no pudo ser ingresado';   
            }
        }
        else
        {
            _rollback();
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Este nombre de usuario no esta disponible';
        }
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este Médico ya fue ingresado';
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
                insertar();
                break;
        } 
    }			
}
?>



