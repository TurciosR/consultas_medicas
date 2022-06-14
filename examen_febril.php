<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='Antígenos Febriles';
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
    $query = _query("SELECT e_f.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_febriles AS e_f, examen_paciente AS e_p WHERE e_f.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $tifico_h = $datos["tifico_h"];
        $tifico_o = $datos["tifico_o"];
        $paratifico_a = $datos["paratifico_a"];
        $paratifico_b = $datos["paratifico_b"];
        $proteus = $datos["proteus"];
        $brocela_abortus = $datos["brocela_abortus"];
        $id_examen = $datos["id_febriles"];
        $fecha_lectura = ED($datos["fecha_lectura"]);
    }
    else
    {
        $tifico_h = "";
        $tifico_o = "";
        $paratifico_a = "";
        $paratifico_b = "";
        $proteus = "";
        $brocela_abortus = "";
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
                                        <label>Tifico H</label>
                                        <input type="text" name="tifico_h" id="tifico_h" class="form-control read" <?php if($num>0){ echo "value='$tifico_h' readonly";} ?>>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Tifico O</label>
                                        <input type="text" name="tifico_o" id="tifico_o" class="form-control read" <?php if($num>0){ echo "value='$tifico_o' readonly";} ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Paratifico A</label>
                                        <input type="text" name="paratifico_a" id="paratifico_a" class="form-control read" <?php if($num>0){ echo "value='$paratifico_a' readonly";} ?>>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Paratifico B</label>
                                        <input type="text" name="paratifico_b" id="paratifico_b" class="form-control read" <?php if($num>0){ echo "value='$paratifico_b' readonly";} ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Proteus OX19</label>
                                        <input type="text" name="proteus" id="proteus" class="form-control read" <?php if($num>0){ echo "value='$proteus' readonly";} ?>>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group has-info">
                                        <label>Brocela abortus</label>
                                        <input type="text" name="brocela_abortus" id="brocela_abortus" class="form-control read" <?php if($num>0){ echo "value='$brocela_abortus' readonly";} ?>>
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
echo "<script src='js/funciones/funciones_examen_febril.js'></script>";
}
function insert()
{
    $id_examen_paciente = $_POST["id_examen_paciente"];
    $id_examen = $_POST["id_examen"];
    $fecha = date("Y-m-d");
    $tifico_o = $_POST["tifico_o"];
    $tifico_h = $_POST["tifico_h"];
    $paratifico_a = $_POST["paratifico_a"];
    $paratifico_b = $_POST["paratifico_b"];
    $proteus = $_POST["proteus"];
    $brocela_abortus = $_POST["brocela_abortus"];
    $fecha_lectura = MD($_POST["fecha_lectura"]);

    $table = 'examen_febriles';
    $form_data = array(
        'id_examen_paciente' => $id_examen_paciente, 
        'fecha' => $fecha,
        'tifico_o' => $tifico_o,
        'tifico_h' => $tifico_h,
        'paratifico_a' => $paratifico_a,
        'paratifico_b' => $paratifico_b,
        'proteus' => $proteus,
        'brocela_abortus' => $brocela_abortus
        );
    $table1 = 'examen_paciente';
    $form_data1 = array(
        'fecha_lectura' => $fecha_lectura,
        );
    if($id_examen>0)
    {
        $where = "id_febriles='$id_examen'";
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
