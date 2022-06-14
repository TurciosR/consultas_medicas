<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='Depuración de Creatinina';
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
    $query = _query("SELECT e_c.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_creatinina AS e_c, examen_paciente AS e_p  WHERE e_c.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $volumen_orina = $datos["volumen_orina"];
        $creatinina_orina = $datos["creatinina_orina"];
        $creatinina_sangre = $datos["creatinina_sangre"];
        $depuracion_creatinina = $datos["depuracion_creatinina"];
        $proteinas_orina = $datos["proteinas_orina"];
        $id_examen = $datos["id_examen_creatinina"];
        $fecha_lectura = ED($datos["fecha_lectura"]);
    }
    else
    {
        $volumen_orina = "";
        $creatinina_orina = "";
        $creatinina_sangre = "";
        $depuracion_creatinina = "";
        $proteinas_orina = "";
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
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table">
                                        <thead class="bg-success">
                                            <tr>
                                                <th style="width: 33%">Examen realizado</th>
                                                <th style="width: 34%">Resultado</th>
                                                <th style="width: 33%">Valor normal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><label>Volumen de orina de 24 horas</label></td>
                                                <td><input type='text' name='volumen_orina' id='volumen_orina' class='form-control bordes-input read' <?php if($num>0){ echo "value='$volumen_orina' readonly";}?>></td>
                                                <td>ml/24 horas</td>
                                            </tr>
                                            <tr>
                                                <td><label>Creatinina en orina de 24 horas</label></td>
                                                <td><input type='text' name='creatinina_orina' id='creatinina_orina' class='form-control bordes-input read' <?php if($num>0){ echo "value='$creatinina_orina' readonly";}?>></td>
                                                <td>Mg/24 horas</td>
                                            </tr>
                                            <tr>
                                                <td><label>Creatinina en sangre</label></td>
                                                <td><input type='text' name='creatinina_sangre' id='creatinina_sangre' class='form-control bordes-input read' <?php if($num>0){ echo "value='$creatinina_sangre' readonly";}?>></td>
                                                <td>0.4 - 1.4 mg/dl</td>
                                            </tr>
                                            <tr>
                                                <td><label>Depuración de creatinina en orina de 24 horas</label></td>
                                                <td><input type='text' name='depuracion_creatinina' id='depuracion_creatinina' class='form-control bordes-input read' <?php if($num>0){ echo "value='$depuracion_creatinina' readonly";}?>></td>
                                                <td>50 - 157 ml/mto</td>
                                            </tr>
                                            <tr>
                                                <td><label>Proteinas en orina de 24 horas</label></td>
                                                <td><input type='text' name='proteinas_orina' id='proteinas_orina' class='form-control bordes-input read' <?php if($num>0){ echo "value='$proteinas_orina' readonly";}?>></td>
                                                <td>10 - 150 mgrs/24 horas</td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                        <hr>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label>Fecha de lectura</label>
                                    <input type="text" name="fecha_lectura" id="fecha_lectura" class="form-control read datepicker bordes-input" <?php if($num>0){ echo "value='$fecha_lectura' readonly"; }?>>
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
echo "<script src='js/funciones/funciones_examen_creatinina.js'></script>";

}
function insert()
{
    $id_examen_paciente = $_POST["id_examen_paciente"];
    $id_examen = $_POST["id_examen"];
    $fecha = date("Y-m-d");
    $volumen_orina = $_POST["volumen_orina"];
    $creatinina_orina = $_POST["creatinina_orina"];
    $creatinina_sangre = $_POST["creatinina_sangre"];
    $depuracion_creatinina = $_POST["depuracion_creatinina"];
    $proteinas_orina = $_POST["proteinas_orina"];
    $fecha_lectura = MD($_POST["fecha_lectura"]);

    $table = 'examen_creatinina';
    $form_data = array(
        'id_examen_paciente' => $id_examen_paciente, 
        'fecha' => $fecha,
        'volumen_orina' => $volumen_orina,
        'creatinina_orina' => $creatinina_orina,
        'creatinina_sangre' => $creatinina_sangre,
        'depuracion_creatinina' => $depuracion_creatinina,
        'proteinas_orina' => $proteinas_orina
        );
    $table1 = 'examen_paciente';
    $form_data1 = array(
        'fecha_lectura' => $fecha_lectura,
        );
    if($id_examen>0)
    {
        $where = "id_examen_creatinina='$id_examen'";
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
