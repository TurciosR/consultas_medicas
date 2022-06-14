<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='Concentrado de Heces';
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
    $query = _query("SELECT e_h.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_heces AS e_h, examen_paciente AS e_p WHERE e_h.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $color = $datos["color"];
        $consistencia = $datos["consistencia"];
        $mucus = $datos["mucus"];
        $restos_alimenticios = $datos["restos_alimenticios"];
        $leucocitos = $datos["leucocitos"];
        $hematies = $datos["hematies"];
        $protozoarios = $datos["protozoarios"];
        $metazoarios = $datos["metazoarios"];
        $id_examen = $datos["id_examen_heces"];
        $flora = $datos["flora"];
        $otros = $datos["otros"];
        $fecha_lectura = ED($datos["fecha_lectura"]);
    }
    else
    {
        $color = "";
        $consistencia = "";
        $mucus = "";
        $restos_alimenticios = "";
        $leucocitos = "";
        $hematies = "";
        $protozoarios = "";
        $metazoarios = "";
        $flora = "";
        $otros = "";
        $id_examen = "";
        $fecha_lectura = "";
    }
?>
    <style type="text/css">
        .bordes-input{
            border-top: none;
            border-left: none;
            border-right: none;
        }
        .bordes-input:focus{
            border-top: none;
            border-left: none;
            border-right: none;
        }
    </style>
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                         <h3 style="color:#194160;"><i class="fa fa-file"></i> <b><?php echo $title;?></b></h3>
                    </div>
                    <div class="ibox-content">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Color</label>
                                        <input type="text" name="color" id="color" class="form-control read" <?php if($num>0){ echo "value='$color' readonly"; }?>>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Consistencia</label>
                                        <input type="text" name="consistencia" id="consistencia" class="form-control read" <?php if($num>0){ echo "value='$consistencia' readonly"; }?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Mucus</label>
                                        <input type="text" name="mucus" id="mucus" class="form-control read" <?php if($num>0){ echo "value='$mucus' readonly"; }?>>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Restos alimeticos</label>
                                        <input type="text" name="restos_alimenticios" id="restos_alimenticios" class="form-control read" <?php if($num>0){ echo "value='$restos_alimenticios' readonly"; }?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Leucocitos</label>
                                        <input type="text" name="leucocitos" id="leucocitos" class="form-control read" <?php if($num>0){ echo "value='$leucocitos' readonly"; }?>>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Hematies</label>
                                        <input type="text" name="hematies" id="hematies" class="form-control read" <?php if($num>0){ echo "value='$hematies' readonly"; }?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Protozoarios quistes</label>
                                        <input type="text" name="protozoarios" id="protozoarios" class="form-control read" <?php if($num>0){ echo "value='$protozoarios' readonly"; }?>>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Metazoarios huevos</label>
                                        <input type="text" name="metazoarios" id="metazoarios" class="form-control read" <?php if($num>0){ echo "value='$metazoarios' readonly"; }?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Flora Bacteriana</label>
                                        <input type="text" name="flora" id="flora" class="form-control read" <?php if($num>0){ echo "value='$flora' readonly"; }?>>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Otros Hallazgos</label>
                                        <input type="text" name="otros" id="otros" class="form-control read" <?php if($num>0){ echo "value='$otros' readonly"; }?>>
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
                            <a id="btn_edit" class="btn btn-primary"><i class="fa fa-pencil"></i> Editar</a>
                            <a id="btn_guardar" name="btn_guardar" class="btn btn-primary"><i class="fa fa-check"></i> Guardar</a>
                        </div>   
                    </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_examen_heces.js'></script>";

}
function insert()
{
    $id_examen_paciente = $_POST["id_examen_paciente"];
    $id_examen = $_POST["id_examen"];
    $fecha = date("Y-m-d");
    $color = $_POST["color"];
    $consistencia = $_POST["consistencia"];
    $mucus = $_POST["mucus"];
    $restos_alimenticios = $_POST["restos_alimenticios"];
    $leucocitos = $_POST["leucocitos"];
    $hematies = $_POST["hematies"];
    $protozoarios = $_POST["protozoarios"];
    $metazoarios = $_POST["metazoarios"];
    $flora = $_POST["flora"];
    $otros = $_POST["otros"];
    $fecha_lectura = MD($_POST["fecha_lectura"]);

    $table = 'examen_heces';
    $form_data = array(
        'id_examen_paciente' => $id_examen_paciente, 
        'fecha' => $fecha,
        'color' => $color,
        'consistencia' => $consistencia,
        'mucus' => $mucus,
        'restos_alimenticios' => $restos_alimenticios,
        'leucocitos' => $leucocitos,
        'hematies' => $hematies,
        'protozoarios' => $protozoarios,
        'metazoarios' => $metazoarios,
        'flora' => $flora,
        'otros' => $otros,
        );
    $table1 = 'examen_paciente';
    $form_data1 = array(
        'fecha_lectura' => $fecha_lectura,
        );
    if($id_examen>0)
    {
        $where = "id_examen_heces='$id_examen'";
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
            $xdata["typeinfo"]="Error";
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
