<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='Examen General de Orina';
    $_PAGE = array ();
    $_PAGE ['title'] = $title;
    $_PAGE ['links'] = null;
    $_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
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
    $id_examen_paciente = $_REQUEST["id_examen"];
    $query = _query("SELECT e_o.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_orina AS e_o, examen_paciente AS e_p WHERE e_o.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $color = $datos["color"];
        $aspecto = $datos["aspecto"];
        $densidad = $datos["densidad"];
        $ph = $datos["ph"];
        $proteinas = $datos["proteinas"];
        $glucosa = $datos["glucosa"];
        $sangre_oculta = $datos["sangre_oculta"];
        $cuerpos_cetonicos = $datos["cuerpos_cetonicos"];
        $urobilinogeno = $datos["urobilinogeno"];
        $bilirrubina = $datos["bilirrubina"];
        $nitritos = $datos["nitritos"];
        $hemoglobina = $datos["hemoglobina"];
        $e_leucocitoria = $datos["e_leucocitaria"];
        $celulas_epiteliales = $datos["celulas_epiteliales"];
        $leucocitos = $datos["leucocitos"];
        $hematies = $datos["hematies"];
        $urato = $datos["urato"];
        $cilindro_grueso = $datos["cilindro_grueso"];
        $cilindro_leucocitario = $datos["cilindro_leucocitario"];
        $cilindro_hematico = $datos["cilindro_hematico"];
        $cilindro_hialino = $datos["cilindro_hialino"];
        $parasitologico = $datos["parasitologico"];
        $bacterias = $datos["bacterias"];
        $filamento_mucoide = $datos["filamento_mucoide"];
        $otros = $datos["otros"];
        $observacion = $datos["observacion"];
        $reporta = $datos["reporta"];
        $id_examen = $datos["id_examen_orina"];
        $fecha_lectura = ED($datos["fecha_lectura"]);
    }
    else
    {
        $color ="";
        $aspecto = "";
        $densidad = "";
        $ph ="";
        $proteinas ="";
        $glucosa = "";
        $sangre_oculta = "";
        $cuerpos_cetonicos ="";
        $urobilinogeno = "";
        $bilirrubina ="";
        $nitritos = "";
        $hemoglobina = "";
        $e_leucocitoria = "";
        $celulas_epiteliales = "";
        $leucocitos = "";
        $hematies = "";
        $urato = "";
        $cilindro_grueso = "";
        $cilindro_leucocitario = "";
        $cilindro_hematico = "";
        $cilindro_hialino = "";
        $parasitologico = "";
        $bacterias = "";
        $filamento_mucoide = "";
        $otros ="";
        $observacion = "";
        $reporta = "";
        $id_examen = "";
        $fecha_lectura = "";
    }
?>
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                         <h3 style="color:#194160;"><i class="fa fa-file"></i> <b><?php echo $title;?></b>
                    </div>
                    <div class="ibox-content">
                        <form method="POST" id="formulario">
                        <div class="col-lg-12" id="aspecto_fisico">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3 class='text-success'>Aspecto Físico-Químico</h3></div>
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Color</label>
                                                <input type="text" name="color" id="color" class="form-control read" <?php if($num>0){ echo "value='$color' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Aspecto</label>
                                                <input type="text" name="aspecto" id="aspecto" class="form-control read" <?php if($num>0){ echo "value='$aspecto' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Densidad</label>
                                                <input type="text" name="densidad" id="densidad" class="form-control read" <?php if($num>0){ echo "value='$densidad' readonly"; }?>>
                                            </div>
                                             <div class="col-lg-6 form-group">
                                                <label>PH</label>
                                                <input type="text" name="ph" id="ph" class="form-control read" <?php if($num>0){ echo "value='$ph' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Proteínas</label>
                                                <input type="text" name="proteinas" id="proteinas" class="form-control read" <?php if($num>0){ echo "value='$proteinas' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Glucosa</label>
                                                <input type="text" name="glucosa" id="glucosa" class="form-control read" <?php if($num>0){ echo "value='$glucosa' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Sangre oculta</label>
                                                <input type="text" name="sangre_oculta" id="sangre_oculta" class="form-control read" <?php if($num>0){ echo "value='$sangre_oculta' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Cuerpos cetónicos</label>
                                                <input type="text" name="cuerpos_cetonicos" id="cuerpos_cetonicos" class="form-control read" <?php if($num>0){ echo "value='$cuerpos_cetonicos' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Urobilinógeno</label>
                                                <input type="text" name="urobilinogeno" id="urobilinogeno" class="form-control read" <?php if($num>0){ echo "value='$urobilinogeno' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Bilirrubina</label>
                                                <input type="text" name="bilirrubina" id="bilirrubina" class="form-control read" <?php if($num>0){ echo "value='$bilirrubina' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Nitritos</label>
                                                <input type="text" name="nitritos" id="nitritos" class="form-control read" <?php if($num>0){ echo "value='$nitritos' readonly"; }?>>
                                            </div>
                                            
                                            <div class="col-lg-6 form-group">
                                                <label>Hemoglobina</label>
                                                <input type="text" name="hemoglobina" id="hemoglobina" class="form-control read" <?php if($num>0){ echo "value='$hemoglobina' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Esterasa leucocitoria</label>
                                                <input type="text" name="e_leucocitoria" id="e_leucocitoria" class="form-control read" <?php if($num>0){ echo "value='$e_leucocitoria' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" id="examen_micro">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3 class='text-success'>Examen Microscopico</h3></div>
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Células epiteliales</label>
                                                <input type="text" name="celulas_epiteliales" id="celulas_epiteliales" class="form-control read" <?php if($num>0){ echo "value='$celulas_epiteliales' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Leucocitos</label>
                                                <input type="text" name="leucocitos" id="leucocitos" class="form-control read" <?php if($num>0){ echo "value='$leucocitos' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Hematies</label>
                                                <input type="text" name="hematies" id="hematies" class="form-control read" <?php if($num>0){ echo "value='$hematies' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Cristales urato amorfo</label>
                                                <input type="text" name="urato" id="urato" class="form-control read" <?php if($num>0){ echo "value='$urato' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Cilindros granuloso grueso</label>
                                                <input type="text" name="cilindro_grueso" id="cilindro_grueso" class="form-control read" <?php if($num>0){ echo "value='$cilindro_grueso' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Cilindro leucocitario</label>
                                                <input type="text" name="cilindro_leucocitario" id="cilindro_leucocitario" class="form-control read" <?php if($num>0){ echo "value='$cilindro_leucocitario' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Cilindro hemático</label>
                                                <input type="text" name="cilindro_hematico" id="cilindro_hematico" class="form-control read" <?php if($num>0){ echo "value='$cilindro_hematico' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Cilindro hialino</label>
                                                <input type="text" name="cilindro_hialino" id="cilindro_hialino" class="form-control read" <?php if($num>0){ echo "value='$cilindro_hialino' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Parasitológico</label>
                                                <input type="text" name="parasitologico" id="parasitologico" class="form-control read" <?php if($num>0){ echo "value='$parasitologico' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Bacterias</label>
                                                <input type="text" name="bacterias" id="bacterias" class="form-control read" <?php if($num>0){ echo "value='$bacterias' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Filamento mucoide</label>
                                                <input type="text" name="filamento_mucoide" id="filamento_mucoide" class="form-control read" <?php if($num>0){ echo "value='$filamento_mucoide' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Otros</label>
                                                <input type="text" name="otros" id="otros" class="form-control read" <?php if($num>0){ echo "value='$otros' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Observación</label>
                                        <textarea class="form-control read" name="observacion" id="observacion" <?php if($num>0){ echo "readonly"; }?>><?php if($num>0){ echo $observacion; }?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Reportado por</label>
                                        <textarea class="form-control read" name="reporta" id="reporta" <?php if($num>0){ echo "readonly"; }?> ><?php if($num>0){ echo $reporta; }?></textarea>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label>Fecha de lectura</label>
                                    <input type="text" name="fecha_lectura" id="fecha_lectura" class="form-control read datepicker" <?php if($num>0){ echo "value='$fecha_lectura' readonly"; }?>>
                                </div>
                            </div>
                        </div> 
                        <div class="title-action" id='botones'>
                            <input type="hidden" name="id_examen" id="id_examen" value="<?php echo $id_examen;?>">
                            <input type="hidden" name="id_examen_paciente" id="id_examen_paciente" value="<?php echo $id_examen_paciente;?>">
                            <input type="hidden" name="process" value="insert">
                            <a id="btn_edit" class="btn btn-primary"><i class="fa fa-pencil"></i> Editar</a>
                            <a id="btn_guardar" name="btn_guardar" class="btn btn-primary"><i class="fa fa-check"></i> Guardar</a>
                        </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_examen_orina.js'></script>";

}
function insert()
{
    $id_examen_paciente = $_POST["id_examen_paciente"];
    $id_examen = $_POST["id_examen"];
    $fecha = date("Y-m-d");
    $color = $_POST["color"];
    $aspecto = $_POST["aspecto"];
    $densidad = $_POST["densidad"];
    $ph = $_POST["ph"];
    $proteinas = $_POST["proteinas"];
    $glucosa = $_POST["glucosa"];
    $sangre_oculta = $_POST["sangre_oculta"];
    $cuerpos_cetonicos = $_POST["cuerpos_cetonicos"];
    $urobilinogeno = $_POST["urobilinogeno"];
    $bilirrubina = $_POST["bilirrubina"];
    $nitritos = $_POST["nitritos"];
    $hemoglobina = $_POST["hemoglobina"];
    $e_leucocitoria = $_POST["e_leucocitoria"];
    $celulas_epiteliales = $_POST["celulas_epiteliales"];
    $leucocitos = $_POST["leucocitos"];
    $hematies = $_POST["hematies"];
    $urato = $_POST["urato"];
    $cilindro_grueso = $_POST["cilindro_grueso"];
    $cilindro_leucocitario = $_POST["cilindro_leucocitario"];
    $cilindro_hematico = $_POST["cilindro_hematico"];
    $cilindro_hialino = $_POST["cilindro_hialino"];
    $parasitologico = $_POST["parasitologico"];
    $bacterias = $_POST["bacterias"];
    $filamento_mucoide = $_POST["filamento_mucoide"];
    $otros = $_POST["otros"];
    $observacion = $_POST["observacion"];
    $reporta = $_POST["reporta"];
    $fecha_lectura = MD($_POST["fecha_lectura"]);

    $table = 'examen_orina';
    $form_data = array(
        'id_examen_paciente' => $id_examen_paciente, 
        'fecha' => $fecha,
        'color' => $color,
        'aspecto' => $aspecto,
        'densidad' => $densidad,
        'ph' => $ph,
        'proteinas' => $proteinas,
        'glucosa' => $glucosa,
        'sangre_oculta' => $sangre_oculta,
        'cuerpos_cetonicos' => $cuerpos_cetonicos,
        'urobilinogeno' => $urobilinogeno,
        'bilirrubina' => $bilirrubina,
        'nitritos' => $nitritos,
        'hemoglobina' => $hemoglobina,
        'e_leucocitaria' => $e_leucocitoria,
        'celulas_epiteliales' => $celulas_epiteliales,
        'leucocitos' => $leucocitos,
        'hematies' => $hematies,
        'urato' => $urato,
        'cilindro_grueso' => $cilindro_grueso,
        'cilindro_leucocitario' => $cilindro_leucocitario,
        'cilindro_hematico' => $cilindro_hematico,
        'cilindro_hialino' => $cilindro_hialino,
        'parasitologico' => $parasitologico,
        'bacterias' => $bacterias,
        'filamento_mucoide' => $filamento_mucoide,
        'otros' => $otros,
        'observacion' => $observacion,
        'reporta' => $reporta,
        );
    $table1 = 'examen_paciente';
    $form_data1 = array(
        'fecha_lectura' => $fecha_lectura,
        );
    if($id_examen>0)
    {
        $where = "id_examen_orina='$id_examen'";
        $insert = _update($table, $form_data, $where);
        $where1 = "id_examen_paciente='$id_examen_paciente'";
        $insert1 = _update($table1, $form_data1, $where1);
        if($insert && $insert1)
        {
            $xdata["typeinfo"]="Success";
            $xdata["msg"]="Datos registrados con exito!!!";
            $xdata["action"]="edit";
            $xdata["id_examen"]=$id_examen;
        }
        else
        {
            $xdata["typeinfo"]="Error";
            $xdata["msg"]="Datos no pudieron ser registrados";
        }
    }
    else
    {
        $insert = _insert($table, $form_data);
        $where1 = "id_examen_paciente='$id_examen_paciente'";
        $insert1 = _update($table1, $form_data1, $where1);
        if($insert && $insert1)
        {
            $id_examen = _insert_id();
            $xdata["typeinfo"]="Success";
            $xdata["msg"]="Datos registrados con exito!!!";
            $xdata["action"]="insert";
            $xdata["id_examen"]=$id_examen;
        }
        else
        {
            $xdata["typeinfo"]="Error"._error();
            $xdata["msg"]="Datos no pudieron ser registrados";
        }
    }
    echo json_encode($xdata);
}
if(!isset($_POST['process'])){
    initial();
}
else
{
if(isset($_POST['process']))
{
switch ($_POST['process']) {
    case 'insert':
        insert();
    break;
    }
}
}
?>
