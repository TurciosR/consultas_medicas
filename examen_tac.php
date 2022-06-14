<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='TAC';
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
    $query = _query("SELECT * FROM examen_tac WHERE id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $resultado = $datos["resultado"];
        $id_examen = $datos["id_tac"];
        $fecha = $datos["fecha"];
    }
    else
    {
        $muestra = "";
        $examen = "";
        $resultado = "";
        $id_examen = "";
        $fecha = "";
    }
?>
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
                                <div class="col-lg-12">
                                    <div class="form-group has-info">
                                        <label>Resultado</label>
                                        <textarea name="resultado" id="resultado" class="form-control bordes-input read" rows="8" <?php if($num>0){ echo "readonly";} ?>><?php echo $resultado; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                 <div class="col-lg-6 form-group">
                                    <label>Fecha de lectura</label>
                                    <input type="text" name="fecha_lectura" id="fecha_lectura" class="form-control read datepicker bordes-input" <?php if($num>0){ echo "readonly value='".ED($fecha)."'";} ?>>
                                </div>
                            </div>
                        </div>
                        <div class="title-action" id='botones'>
                            <input type="hidden" name="process" id="process" value="tac">
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
echo "<script src='js/funciones/funciones_examen_varios.js'></script>";

}
function insert()
{
    $id_examen_paciente = $_POST["id_examen_paciente"];
    $id_examen = $_POST["id_examen"];
    $fecha = date("Y-m-d");
    $resultado = $_POST["resultado"];
    $fecha_lectura = MD($_POST["fecha_lectura"]);

    $table = 'examen_tac';
    $form_data = array(
        'id_examen_paciente' => $id_examen_paciente, 
        'fecha' => $fecha,
        'resultado' => $resultado
        );
    $table1 = "examen_paciente";
    $form_data1 = array(
        'fecha_lectura' => $fecha_lectura,
        );
    $where1 = "id_examen_paciente='$id_examen_paciente'";
    $insert1 = _update($table1, $form_data1, $where1);   if($id_examen>0)
    {
        $where = "id_tac='$id_examen'";
        $insert = _update($table, $form_data, $where);
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
