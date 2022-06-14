<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='Bactereologia';
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
    $query = _query("SELECT e_b.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_bacteriologia AS e_b, examen_paciente AS e_p WHERE e_b.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $muestra = $datos["muestra"];
        $area_corporal = $datos["area_corporal"];
        $microorganismo_aislado = $datos["microorganismo_aislado"];
        $conteo_colonia = $datos["conteo_colonia"];
        $sensible = explode("|",$datos["sensible"]);
        $intermedio = explode("|",$datos["intermedio"]);
        $resistente = explode("|",$datos["resistente"]);
        $id_examen = $datos["id_bacteriologia"];
        $fecha_lectura = ED($datos["fecha_lectura"]);
    }
    else
    {
        $muestra = "";
        $area_corporal = "";
        $microorganismo_aislado = "";
        $conteo_colonia = "";
        $sensible = "";
        $intermedio = "";
        $resistente = "";
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
                        <div class="col-lg-12" id="aspecto_fisico">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3 class='text-success'>Urocultivo</h3></div>
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Muestra</label>
                                                <input type="text" name="muestra" id="muestra" class="form-control read" <?php if($num>0){ echo "value='$muestra' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Area corporal</label>
                                                <input type="text" name="area_corporal" id="area_corporal" class="form-control read" <?php if($num>0){ echo "value='$area_corporal' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 form-group">
                                                <label>Microorganismo aislado</label>
                                                <input type="text" name="microorganismo_aislado" id="microorganismo_aislado" class="form-control read" <?php if($num>0){ echo "value='$microorganismo_aislado' readonly"; }?>>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Conteo de colonia (Bacterias)</label>
                                                <input type="text" name="conteo_colonia" id="conteo_colonia" class="form-control read" <?php if($num>0){ echo "value='$conteo_colonia' readonly"; }?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" id="aspecto_fisico">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3 class='text-success'>Antibiograma</h3></div>
                                <div class="panel-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="width: 33%">Sensible</th>
                                                <th style="width: 34%">Intermedio</th>
                                                <th style="width: 33%">Resistente</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sir">
                                            <?php
                                                $j = 1;
                                                $l = 1;
                                                for ($i=0; $i < 7 ; $i++) { 
                                                    echo "
                                                        <tr>
                                                            <td><input type='text' name='sensible".$j."' id='sensible".$j."' class='form-control bordes-input read sensible'";
                                                            if($num>0)
                                                            {
                                                                echo "value='".$sensible[$i]."' readonly";
                                                            }
                                                            echo "></td>
                                                            <td><input type='text' name='intermedio".$j."' id='intermedio".$j."' class='form-control bordes-input read intermedio'";
                                                            if($num>0)
                                                            {
                                                                echo "value='".$intermedio[$i]."' readonly";
                                                            }
                                                            echo "></td>
                                                            <td><input type='text' name='resistente".$j."' id='resistente".$j."' class='form-control bordes-input read resistente'";
                                                            if($num>0)
                                                            {
                                                                echo "value='".$resistente[$i]."' readonly";
                                                            }
                                                            echo "></td>
                                                        </tr>
                                                    ";
                                                    $j +=1;
                                                }
                                            ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label>Fecha de lectura</label>
                                    <input type="text" name="fecha_lectura" id="fecha_lectura" class="form-control read datepicker bordes-input"  <?php if($num>0){ echo "value='$fecha_lectura' readonly"; }?>>
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
echo "<script src='js/funciones/funciones_examen_bacteriologia.js'></script>";

}
function insert()
{
    $id_examen_paciente = $_POST["id_examen_paciente"];
    $id_examen = $_POST["id_examen"];
    $fecha = date("Y-m-d");
    $muestra = $_POST["muestra"];
    $area_corporal = $_POST["area_corporal"];
    $microorganismo_aislado = $_POST["microorganismo_aislado"];
    $conteo_colonia = $_POST["conteo_colonia"];
    $sensible = $_POST["sensible"];
    $intermedio = $_POST["intermedio"];
    $resistente = $_POST["resistente"];
    $fecha_lectura = MD($_POST["fecha_lectura"]);

    $table = 'examen_bacteriologia';
    $form_data = array(
        'id_examen_paciente' => $id_examen_paciente, 
        'fecha' => $fecha,
        'muestra' => $muestra,
        'area_corporal' => $area_corporal,
        'microorganismo_aislado' => $microorganismo_aislado,
        'conteo_colonia' => $conteo_colonia,
        'sensible' => $sensible,
        'intermedio' => $intermedio,
        'resistente' => $resistente
        );
    $table1 = 'examen_paciente';
    $form_data1 = array(
        'fecha_lectura' => $fecha_lectura,
        );
    if($id_examen>0)
    {
        $where = "id_bacteriologia='$id_examen'";
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
