<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='Hematologia';
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
    $query = _query("SELECT e_h.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_hematologia AS e_h, examen_paciente AS e_p WHERE e_h.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $globulos_rojos = $datos["globulos_rojos"];
        $hemoglobina = $datos["hemoglobina"];
        $hematocrito = $datos["hematocrito"];
        $vcm = $datos["vcm"];
        $hcm = $datos["hcm"];
        $chcm = $datos["chcm"];
        $globulos_blancos = $datos["globulos_blancos"];
        $n_segmentados = $datos["n_segmentados"];
        $n_banda = $datos["n_banda"];
        $linfocitos = $datos["linfocitos"];
        $monocitos = $datos["monocitos"];
        $eosinofilos = $datos["eosinofilos"];
        $basofilos = $datos["basofilos"];
        $plaquetas = $datos["plaquetas"];
        $tiempo_protobina = $datos["tiempo_protobina"];
        $inr = $datos["inr"];
        $isi = $datos["isi"];
        $tiempo_tromboplastima = $datos["tiempo_tromboplastima"];
        $eritrosedimentacion = $datos["eritrosedimentacion"];
        $observacion = $datos["observacion"];
        $reporta = $datos["reporta"];
        $id_examen = $datos["id_hematologia"];
        $fecha_lectura = ED($datos["fecha_lectura"]);
    }
    else
    {
        $globulos_rojos = "";
        $hemoglobina = "";
        $hematocrito = "";
        $vcm = "";
        $hcm = "";
        $chcm = "";
        $globulos_blancos = "";
        $n_segmentados = "";
        $n_banda = "";
        $linfocitos = "";
        $monocitos = "";
        $eosinofilos = "";
        $basofilos = "";
        $plaquetas = "";
        $tiempo_protobina = "";
        $inr = "";
        $isi = "";
        $tiempo_tromboplastima = "";
        $eritrosedimentacion = "";
        $observacion = "";
        $reporta = "";
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
                         <h3 style="globulos_rojos:#5a0860;"><i class="fa fa-file"></i> <b><?php echo $title;?></b></h3>
                    </div>
                    <div class="ibox-content">
                    <div class="col-lg-12">
                        <table class="table">
                            <thead class="bg-success">
                                <tr>
                                    <th style="width: 25%">Examen realizado</th>
                                    <th style="width: 30%">Resultado</th>
                                    <th style="width: 45%">Valor de referencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><br><label>Globulos rojos</label></td>
                                    <td><input type="text" name="globulos_rojos" id="globulos_rojos" class="form-control bordes-input read" <?php if($num>0){ echo "value='$globulos_rojos' readonly"; }?>></td>
                                    <td><br><label>4,000.000 - 5,000.000 XMM&#179;</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Hemoglobina</label></td>
                                    <td><input type="text" name="hemoglobina" id="hemoglobina" class="form-control bordes-input read" <?php if($num>0){ echo "value='$hemoglobina' readonly"; }?>></td>
                                    <td><br><label>Hombre 14-17, Mujer 12.5-15, Niños 11-13 GR/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Hematocrito</label></td>
                                    <td><input type="text" name="hematocrito" id="hematocrito" class="form-control bordes-input read" <?php if($num>0){ echo "value='$hematocrito' readonly"; }?>></td>
                                    <td><br><label>Hombre 42-52, Mujer 38-42, Niños 33-38%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>VCM</label></td>
                                    <td><input type="text" name="vcm" id="vcm" class="form-control bordes-input read" <?php if($num>0){ echo "value='$vcm' readonly"; }?>></td>
                                    <td><br><label>80-100 Micras cúbicas</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>HCM</label></td>
                                    <td><input type="text" name="hcm" id="hcm" class="form-control bordes-input read" <?php if($num>0){ echo "value='$hcm' readonly"; }?>></td>
                                    <td><br><label>27-34 Micro microgramos</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>CHCM</label></td>
                                    <td><input type="text" name="chcm" id="chcm" class="form-control bordes-input read" <?php if($num>0){ echo "value='$chcm' readonly"; }?>></td>
                                    <td><br><label>30-34%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Globulos blancos</label></td>
                                    <td><input type="text" name="globulos_blancos" id="globulos_blancos" class="form-control bordes-input read" <?php if($num>0){ echo "value='$globulos_blancos' readonly"; }?>></td>
                                    <td><br><label>Adultos 5,000-10,000, Niños 5,000-12,000 XMM&#179;</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Neutrófilos segmentados</label></td>
                                    <td><input type="text" name="n_segmentados" id="n_segmentados" class="form-control bordes-input read" <?php if($num>0){ echo "value='$n_segmentados' readonly"; }?>></td>
                                    <td><br><label>Adultos 60-70, Niños 20-45%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Neutrófilos en banda</label></td>
                                    <td><input type="text" name="n_banda" id="n_banda" class="form-control bordes-input read" <?php if($num>0){ echo "value='$n_banda' readonly"; }?>></td>
                                    <td><br><label>Adultos 2-5, Niños 20-45%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Linfocitos</label></td>
                                    <td><input type="text" name="linfocitos" id="linfocitos" class="form-control bordes-input read" <?php if($num>0){ echo "value='$linfocitos' readonly"; }?>></td>
                                    <td><br><label>Adultos 15-40, Niños 40-60%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Monocitos</label></td>
                                    <td><input type="text" name="monocitos" id="monocitos" class="form-control bordes-input read" <?php if($num>0){ echo "value='$monocitos' readonly"; }?>></td>
                                    <td><br><label>Adultos y niños 2-8%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Eosinófilos</label></td>
                                    <td><input type="text" name="eosinofilos" id="eosinofilos" class="form-control bordes-input read" <?php if($num>0){ echo "value='$eosinofilos' readonly"; }?>></td>
                                    <td><br><label>Adultos 1-4, Niños 1-5%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Basófilos</label></td>
                                    <td><input type="text" name="basofilos" id="basofilos" class="form-control bordes-input read" <?php if($num>0){ echo "value='$basofilos' readonly"; }?>></td>
                                    <td><br><label>Adultos y niños 0-1%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Plaquetas</label></td>
                                    <td><input type="text" name="plaquetas" id="plaquetas" class="form-control bordes-input read" <?php if($num>0){ echo "value='$plaquetas' readonly"; }?>></td>
                                    <td><br><label>150,000 - 450,000 XMM&#179;</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Tiempo de protobina</label></td>
                                    <td><input type="text" name="tiempo_protobina" id="tiempo_protobina" class="form-control bordes-input read" <?php if($num>0){ echo "value='$tiempo_protobina' readonly"; }?>></td>
                                    <td><br><label>8-14 Segundos</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>I.N.R</label></td>
                                    <td><input type="text" name="inr" id="inr" class="form-control bordes-input read" <?php if($num>0){ echo "value='$inr' readonly"; }?>></td>
                                    <td><br><label></label></td>
                                </tr>
                                <tr>
                                    <td><br><label>ISI</label></td>
                                    <td><input type="text" name="isi" id="isi" class="form-control bordes-input read" <?php if($num>0){ echo "value='$isi' readonly"; }?>></td>
                                    <td><br><label></label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Tiempo de tromboplastina</label></td>
                                    <td><input type="text" name="tiempo_tromboplastima" id="tiempo_tromboplastima" class="form-control bordes-input read" <?php if($num>0){ echo "value='$tiempo_tromboplastima' readonly"; }?>></td>
                                    <td><br><label>25-45 segundos, Hombres 0-7 MM/Hora</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Eritrosedimentación</label></td>
                                    <td><input type="text" name="eritrosedimentacion" id="eritrosedimentacion" class="form-control bordes-input read" <?php if($num>0){ echo "value='$eritrosedimentacion' readonly"; }?>></td>
                                    <td><br><label>Mujeres 0-15 MM/Hora, Niños 0-20 MM/Hora</label></td>
                                </tr>
                                
                            </tbody>
                        </table>
                        </div>

                        <hr>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label>Observación</label>
                                        <textarea class="form-control read" name="observacion" id="observacion" <?php if($num>0){ echo "readonly"; }?>><?php echo $observacion; ?></textarea>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>Reportado por</label>
                                        <textarea class="form-control read" name="reporta" id="reporta" <?php if($num>0){ echo "readonly"; }?>><?php echo $reporta; ?></textarea>
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
echo "<script src='js/funciones/funciones_examen_hematologia.js'></script>";

}
function insert()
{
    $id_examen_paciente = $_POST["id_examen_paciente"];
    $id_examen = $_POST["id_examen"];
    $fecha = date("Y-m-d");
    $globulos_rojos = $_POST["globulos_rojos"];
    $hemoglobina = $_POST["hemoglobina"];
    $hematocrito = $_POST["hematocrito"];
    $vcm = $_POST["vcm"];
    $hcm = $_POST["hcm"];
    $chcm = $_POST["chcm"];
    $globulos_blancos = $_POST["globulos_blancos"];
    $n_segmentados = $_POST["n_segmentados"];
    $n_banda = $_POST["n_banda"];
    $linfocitos = $_POST["linfocitos"];
    $monocitos = $_POST["monocitos"];
    $eosinofilos = $_POST["eosinofilos"];
    $basofilos = $_POST["basofilos"];
    $plaquetas = $_POST["plaquetas"];
    $tiempo_protobina = $_POST["tiempo_protobina"];
    $inr = $_POST["inr"];
    $isi = $_POST["isi"];
    $tiempo_tromboplastima = $_POST["tiempo_tromboplastima"];
    $eritrosedimentacion = $_POST["eritrosedimentacion"];
    $observacion = $_POST["observacion"];
    $reporta = $_POST["reporta"];
    $fecha_lectura = MD($_POST["fecha_lectura"]);

    $table = 'examen_hematologia';
    $form_data = array(
        'id_examen_paciente' => $id_examen_paciente, 
        'fecha' => $fecha,
        'globulos_rojos' => $globulos_rojos,
        'hemoglobina' => $hemoglobina,
        'hematocrito' => $hematocrito,
        'vcm' => $vcm,
        'hcm' => $hcm,
        'chcm' => $chcm,
        'globulos_blancos' => $globulos_blancos,
        'n_segmentados' => $n_segmentados,
        'n_banda' => $n_banda,
        'linfocitos' => $linfocitos,
        'monocitos' => $monocitos,
        'eosinofilos' => $eosinofilos,
        'basofilos' => $basofilos,
        'plaquetas' => $plaquetas,
        'tiempo_protobina' => $tiempo_protobina,
        'inr' => $inr,
        'isi' => $isi,
        'tiempo_tromboplastima' => $tiempo_tromboplastima,
        'eritrosedimentacion' => $eritrosedimentacion,
        'observacion' => $observacion,
        'reporta' => $reporta,
        );
    $table1 = 'examen_paciente';
    $form_data1 = array(
        'fecha_lectura' => $fecha_lectura,
        );
    if($id_examen>0)
    {
        $where = "id_hematologia='$id_examen'";
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
