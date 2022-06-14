<?php
include_once "_core.php";
function initial() 
{
    $title='Agregar Paciente';
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
    $_PAGE ['links'] .= '<link href="css/plugins/tour/bootstrap-tour.css" rel="stylesheet">';
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
?>
<div class="wrapper wrapper-content  animated fadeInRight" >
    <div class="row">
        <div class="col-lg-12" >
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
                        <div class="form-group has-info col-md-6" id="div_nombre">
                            <label>Nombres <span style="color:red;">*</span></label>
                            <input type="text" placeholder="Nombres " class="form-control solo_mayu" id="nombre" name="nombre">
                        </div>
                        <div class="form-group has-info col-md-6">
                              <label>Apellidos <span style="color:red;">*</span></label>
                              <input type="text" placeholder="Apellidos" class="form-control solo_mayu" id="apellido" name="apellido">
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
                                <label>DUI</label>
                                <input type="text"  placeholder="00000000-0" class="form-control" id="dui" name="dui">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Estado Civil</label>
                                <input type="text"  placeholder="Soltero, Casado, Viudo" class="form-control solo_mayu" id="estado_civil" name="estado_civil">
                            </div>
                        </div>
                    </div>
                    <div class="row" hidden>      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Religión</label>
                                <input type="text"  placeholder="Religión" class="form-control solo_mayu" id="religion" name="religion">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Escolaridad</label>
                                <input type="text"  placeholder="Nivel o Grado de Escolaridad" class="form-control solo_mayu" id="escolaridad" name="escolaridad">
                            </div>
                        </div>
                    </div>
                    <div class="row" hidden>      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Nombre del Conyuge</label>
                                <input type="text"  placeholder="Conyuge" class="form-control solo_mayu" id="conyuge" name="conyuge">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Grupo Sanguíneo</label>
                                <input type="text"  placeholder="Grupo sanguíneo" class="form-control solo_mayu" id="grupo_sanguineo" name="grupo_sanguineo">
                            </div>
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Teléfono <span style="color:red;">*</span></label>
                                <input type="text"  placeholder="0000-0000" class="form-control tel" id="telefono1" name="telefono1">
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Teléfono 2</label>
                                <input type="text"  placeholder="0000-0000" class="form-control tel" id="telefono2" name="telefono2">
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
                                            echo "<option value='".$depto["id_departamento"]."'>".$depto["nombre_departamento"]."</option>";
                                        }
                                    ?>
                                </select>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Municipio <span style="color:red;">*</span></label>
                                <select class="col-md-12 select" id="municipio" name="municipio">
                                    <option value="">Primero seleccione un departamento</option>
                                </select>
                            </div>                            
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Dirección <span style="color:red;">*</span></label> 
                                <input type="text" placeholder="Dirección" class="form-control solo_mayu" id="direccion" name="direccion">
                            </div>        
                        </div>
                        <div class="col-md-6" hidden>                                
                            <div class="form-group has-info">
                                <label>Email</label>
                                <input type="text" placeholder="ejemplo@correo.com" class="form-control solo_mayu" id="email" name="email">
                            </div>       
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Responsable</label>
                                <input type="text" class="col-md-12 form-control solo_mayu" id="responsable" name="responsable">
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
                                            echo "<option value='".$pco["id_parentezco"]."'>".$pco["descripcion"]."</option>";
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
                                <textarea class="form-control col-md-12 solo_mayu" rows="1" name='alergias' id='alergias'></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Padecimientos</label>
                                <textarea class="form-control col-md-12 solo_mayu" rows="1" name='padecimientos' id='padecimientos'></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row" hidden>      
                        <div class="col-md-6">
                            <div class="form-group has-info"><br>
                                <label>Medicamentos Permánentes</label>
                                <textarea class="form-control col-md-12 solo_mayu" rows="1" name='medicamentos' id='medicamentos'></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-info"><br>
                                <label>Forma de Notificación <span style="color:red;">*</span></label>
                                <select class="select col-md-12" name="tipo" id="tipo">
                                    <option value="">Seleccione</option>
                                    <option value="Teléfono">Teléfono</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row" hidden>      
                        <div class="col-md-6">
                            <div class="form-group has-info"><br>
                                <label>Referido por</label>
                                <input type="text" class="form-control solo_mayu" name='referido' id='referido'>
                            </div> 
                        </div>
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="insert">
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

function insertar()
{
    $fecha_actual = date("Y-m-d");
    $nombre=$_POST["nombre"];
    $apellido=$_POST["apellido"];
    $sexo=$_POST["sexo"];
    $fecha=MD($_POST["fecha"]);
    $direccion=$_POST["direccion"];
    $telefono1=$_POST["telefono1"];
    $telefono2=$_POST["telefono2"];
    $email=$_POST["email"];
    $municipio=$_POST["municipio"];
    $tipo=$_POST["tipo"];
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
    'expediente'=>$expediente,
    'dui'=>$dui,
    'estado_civil'=>$estado_civil,
    'religion'=>$religion,
    'conyuge'=>$conyuge,
    'grupo_sanguineo'=>$grupo_sanguineo,
    'referido'=>$referido,
    'escolaridad'=>$escolaridad,
    'expediente'=>$expediente,
    'fecha_registro'=>$fecha_actual,
    'foto' => ''
    );   	
    $sql_exis = _query("SELECT * FROM paciente WHERE nombres='$nombre' AND apellidos='$apellido' AND municipio='$municipio'");
    $num_exis = _num_rows($sql_exis);
    if($num_exis==0)
    {
        $sql_exp = _query("SELECT * FROM paciente WHERE expediente='$expediente'");
        $num_exis_exp = _num_rows($sql_exp);
        if($num_exis_exp==0)
        {
            $insertar = _insert($table,$form_data );
            if($insertar)
            {
                $xdatos['typeinfo']='Success';
                $xdatos['msg']='Paciente ingresado correctamente';
                $xdatos['process']='insert';
            }
            else
            {
                $xdatos['typeinfo']='Error';
                $xdatos['msg']='Paciente no pudo ser ingresado';
            }
        }
        else
        { 
            $xdatos['typeinfo']='Error';
            $xdatos['msg']='Error Inesperado, intentelo de nuevo';
        }    
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Este Paciente ya fue ingresado';
    }    
    echo json_encode($xdatos);
}
function municipio($id_departamento)
{
    $option = "";
    $sql_mun = _query("SELECT * FROM municipio WHERE id_departamento_municipio='$id_departamento'");
    while($mun_dt=_fetch_array($sql_mun))
    {
        $option .= "<option value='".$mun_dt["id_municipio"]."'>".$mun_dt["nombre_municipio"]."</option>";
    }
    echo $option;
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
            case 'municipio':
                municipio($_POST["id_departamento"]);
                break;		
        } 
    }			
}
?>



