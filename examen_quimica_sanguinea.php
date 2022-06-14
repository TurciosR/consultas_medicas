<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='Química Sanguínea';
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
    $query = _query("SELECT * FROM examen_quimica_sanguinea WHERE id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $glucosa_azar = $datos["glucosa_azar"];
        $glucosa_prandial = $datos["glucosa_prandial"];
        $colesterol_total = $datos["colesterol_total"];
        $colesterol_hdl = $datos["colesterol_hdl"];
        $colesterol_ldl = $datos["colesterol_ldl"];
        $trigliceridos = $datos["trigliceridos"];
        $lipidos_totales = $datos["lipidos_totales"];
        $creatinina = $datos["creatinina"];
        $acido_urico = $datos["acido_urico"];
        $urea = $datos["urea"];
        $nitrogeno_ureico = $datos["nitrogeno_ureico"];
        $sodio = $datos["sodio"];
        $potasio = $datos["potasio"];
        $cloro = $datos["cloro"];
        $proteinas_totales = $datos["proteinas_totales"];
        $albumina = $datos["albumina"];
        $globulina = $datos["globulina"];
        $relacion_ag = $datos["relacion_ag"];
        $amilasa = $datos["amilasa"];
        $bilirrubina_total = $datos["bilirrubina_total"];
        $bilirrubina_directa = $datos["bilirrubina_directa"];
        $bilirrubina_indirecta = $datos["bilirrubina_indirecta"];
        $calcio = $datos["calcio"];
        $fosforo = $datos["fosforo"];
        $proteina_reactiva = $datos["proteina_reactiva"];
        $tsh = $datos["tsh"];
        $t3_libre = $datos["t3_libre"];
        $t4_libre = $datos["t4_libre"];
        $ldh = $datos["ldh"];
        $hda1 = $datos["hda1"];
        $fraccion = $datos["fraccion"];
        $transaminasa_go = $datos["transaminasa_go"];
        $transaminasa_gp = $datos["transaminasa_gp"];
        $observacion = $datos["observacion"];
        $reporta = $datos["reporta"];
        $id_examen = $datos["id_sanguinea"];
        $fecha_lectura = ED($datos["fecha"]);
    }
    else
    {
        $glucosa_azar ="";
        $glucosa_prandial = "";
        $colesterol_total = "";
        $colesterol_hdl ="";
        $colesterol_ldl ="";
        $trigliceridos = "";
        $lipidos_totales = "";
        $creatinina = "";
        $acido_urico ="";
        $urea = "";
        $nitrogeno_ureico = "";
        $sodio = "";
        $potasio = "";
        $cloro = "";
        $proteinas_totales = "";
        $albumina = "";
        $globulina = "";
        $relacion_ag = "";
        $amilasa = "";
        $bilirrubina_total = "";
        $bilirrubina_directa = "";
        $bilirrubina_indirecta = "";
        $calcio = "";
        $fosforo ="";
        $proteina_reactiva = "";
        $tsh = "";
        $t3_libre = "";
        $t4_libre = "";
        $ldh = "";
        $hda1 = "";
        $fraccion = "";
        $transaminasa_go = "";
        $transaminasa_gp = "";
        $observacion = "";
        $reporta = "";
        $id_examen = "";
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
                    <form method="POST" id="fomulario">
                    <div class="row">
                    <div class="col-lg-12">
                        <table class="table">
                            <thead class="bg-success">
                                <tr>
                                    <th style="width: 25%">Examen realizado</th>
                                    <th style="width: 40%">Resultado</th>
                                    <th style="width: 35%">Valores de referencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><br><label>Glucosa al azar</label></td>
                                    <td><input type="text" name="glucosa_azar" id="glucosa_azar" class="form-control bordes-input read" <?php if($num>0){ echo "value='$glucosa_azar' readonly"; }?>></td>
                                    <td><br><label>60-110 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Glucosa post-prandial</label></td>
                                    <td><input type="text" name="glucosa_prandial" id="glucosa_prandial" class="form-control bordes-input read" <?php if($num>0){ echo "value='$glucosa_prandial' readonly"; }?>></td>
                                    <td><br><label>70-140 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Colesterol total</label></td>
                                    <td><input type="text" name="colesterol_total" id="colesterol_total" class="form-control bordes-input read" <?php if($num>0){ echo "value='$colesterol_total' readonly"; }?>></td>
                                    <td><br><label>Hasta 200 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Colesterol HDL</label></td>
                                    <td><input type="text" name="colesterol_hdl" id="colesterol_hdl" class="form-control bordes-input read" <?php if($num>0){ echo "value='$colesterol_hdl' readonly"; }?>></td>
                                    <td><br><label>45-60 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Colesterol LDL</label></td>
                                    <td><input type="text" name="colesterol_ldl" id="colesterol_ldl" class="form-control bordes-input read" <?php if($num>0){ echo "value='$colesterol_ldl' readonly"; }?>></td>
                                    <td><br><label>Hasta 130 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Triglicéridos</label></td>
                                    <td><input type="text" name="trigliceridos" id="trigliceridos" class="form-control bordes-input read" <?php if($num>0){ echo "value='$trigliceridos' readonly"; }?>></td>
                                    <td><br><label>Hasta 150 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Lípidos totales</label></td>
                                    <td><input type="text" name="lipidos_totales" id="lipidos_totales" class="form-control bordes-input read" <?php if($num>0){ echo "value='$lipidos_totales' readonly"; }?>></td>
                                    <td><br><label>400-800 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Creatinina</label></td>
                                    <td><input type="text" name="creatinina" id="creatinina" class="form-control bordes-input read" <?php if($num>0){ echo "value='$creatinina' readonly"; }?>></td>
                                    <td><br><label>Hombres 0.7-1.4 MG/DL, Mujeres 0.6-1.1 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Ácido úrico</label></td>
                                    <td><input type="text" name="acido_urico" id="acido_urico" class="form-control bordes-input read" <?php if($num>0){ echo "value='$acido_urico' readonly"; }?>></td>
                                    <td><br><label>Hombres 3.6-7.7 MG/DL, Mujeres 2.5-6.8 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Urea</label></td>
                                    <td><input type="text" name="urea" id="urea" class="form-control bordes-input read" <?php if($num>0){ echo "value='$urea' readonly"; }?>></td>
                                    <td><br><label>15-45 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Nitrógeno ureico</label></td>
                                    <td><input type="text" name="nitrogeno_ureico" id="nitrogeno_ureico" class="form-control bordes-input read" <?php if($num>0){ echo "value='$nitrogeno_ureico' readonly"; }?>></td>
                                    <td><br><label>4.5-22.7 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Sodio</label></td>
                                    <td><input type="text" name="sodio" id="sodio" class="form-control bordes-input read" <?php if($num>0){ echo "value='$sodio' readonly"; }?>></td>
                                    <td><br><label>135-148 MEQ/L</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Potasio</label></td>
                                    <td><input type="text" name="potasio" id="potasio" class="form-control bordes-input read" <?php if($num>0){ echo "value='$potasio' readonly"; }?>></td>
                                    <td><br><label>3.5-5.3 MEQ/L</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Cloro</label></td>
                                    <td><input type="text" name="cloro" id="cloro" class="form-control bordes-input read" <?php if($num>0){ echo "value='$cloro' readonly"; }?>></td>
                                    <td><br><label>98-107 MEQ/L</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Proteínas totales</label></td>
                                    <td><input type="text" name="proteinas_totales" id="proteinas_totales" class="form-control bordes-input read" <?php if($num>0){ echo "value='$proteinas_totales' readonly"; }?>></td>
                                    <td><br><label>6.6-8.3 G/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Albúmina</label></td>
                                    <td><input type="text" name="albumina" id="albumina" class="form-control bordes-input read" <?php if($num>0){ echo "value='$albumina' readonly"; }?>></td>
                                    <td><br><label>3.8-5.1 G/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Globulina</label></td>
                                    <td><input type="text" name="globulina" id="globulina" class="form-control bordes-input read" <?php if($num>0){ echo "value='$globulina' readonly"; }?>></td>
                                    <td><br><label>1.5-3 G/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Relación A/G</label></td>
                                    <td><input type="text" name="relacion_ag" id="relacion_ag" class="form-control bordes-input read" <?php if($num>0){ echo "value='$relacion_ag' readonly"; }?>></td>
                                    <td><br><label>1.1-2.2</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Amilasa</label></td>
                                    <td><input type="text" name="amilasa" id="amilasa" class="form-control bordes-input read" <?php if($num>0){ echo "value='$amilasa' readonly"; }?>></td>
                                    <td><br><label>1-90 U/L</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Bilirrubina total</label></td>
                                    <td><input type="text" name="bilirrubina_total" id="bilirrubina_total" class="form-control bordes-input read" <?php if($num>0){ echo "value='$bilirrubina_total' readonly"; }?>></td>
                                    <td><br><label>0-1.1 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Bilirrubina directa</label></td>
                                    <td><input type="text" name="bilirrubina_directa" id="bilirrubina_directa" class="form-control bordes-input read" <?php if($num>0){ echo "value='$bilirrubina_directa' readonly"; }?>></td>
                                    <td><br><label>0-0.25 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Bilirrubina indirecta</label></td>
                                    <td><input type="text" name="bilirrubina_indirecta" id="bilirrubina_indirecta" class="form-control bordes-input read" <?php if($num>0){ echo "value='$bilirrubina_indirecta' readonly"; }?>></td>
                                    <td><br><label>0-0.50 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Calcio</label></td>
                                    <td><input type="text" name="calcio" id="calcio" class="form-control bordes-input read" <?php if($num>0){ echo "value='$calcio' readonly"; }?>></td>
                                    <td><br><label>8.5-10.5 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Fósforo</label></td>
                                    <td><input type="text" name="fosforo" id="fosforo" class="form-control bordes-input read" <?php if($num>0){ echo "value='$fosforo' readonly"; }?>></td>
                                    <td><br><label>2.5-5.0 MG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Proteína C reactiva</label></td>
                                    <td><input type="text" name="proteina_reactiva" id="proteina_reactiva" class="form-control bordes-input read" <?php if($num>0){ echo "value='$proteina_reactiva' readonly"; }?>></td>
                                    <td><br><label>Hasta 12 MG/L</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>TSH</label></td>
                                    <td><input type="text" name="tsh" id="tsh" class="form-control bordes-input read" <?php if($num>0){ echo "value='$tsh' readonly"; }?>></td>
                                    <td><br><label>0.38-4.31 Uiu/ML</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>T3 libre</label></td>
                                    <td><input type="text" name="t3_libre" id="t3_libre" class="form-control bordes-input read" <?php if($num>0){ echo "value='$t3_libre' readonly"; }?>></td>
                                    <td><br><label>2.1-3.8 PG/ML</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>T4 libre</label></td>
                                    <td><input type="text" name="t4_libre" id="t4_libre" class="form-control bordes-input read" <?php if($num>0){ echo "value='$t4_libre' readonly"; }?>></td>
                                    <td><br><label>0.82-1.63 NG/DL</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Deshidrogenasa láctica (LDH)</label></td>
                                    <td><input type="text" name="ldh" id="ldh" class="form-control bordes-input read" <?php if($num>0){ echo "value='$ldh' readonly"; }?>></td>
                                    <td><br><label>230-460 U/L</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Hemoglobina glicosilada HDA1</label></td>
                                    <td><input type="text" name="hda1" id="hda1" class="form-control bordes-input read" <?php if($num>0){ echo "value='$hda1' readonly"; }?>></td>
                                    <td><br><label>5-8%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Fracción HDA,C</label></td>
                                    <td><input type="text" name="fraccion" id="fraccion" class="form-control bordes-input read" <?php if($num>0){ echo "value='$fraccion' readonly"; }?>></td>
                                    <td><br><label>4.2-6.2%</label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Transaminasa G.O</label></td>
                                    <td><input type="text" name="transaminasa_go" id="transaminasa_go" class="form-control bordes-input read" <?php if($num>0){ echo "value='$transaminasa_go' readonly"; }?>></td>
                                    <td><br><label>Mujer 3.1 U/L, Hombre </label></td>
                                </tr>
                                <tr>
                                    <td><br><label>Transaminasa G.P</label></td>
                                    <td><input type="text" name="transaminasa_gp" id="transaminasa_gp" class="form-control bordes-input read" <?php if($num>0){ echo "value='$transaminasa_gp' readonly"; }?>></td>
                                    <td><br><label>Mujer 3.2 U/L, Hombre </label></td>
                                </tr>
                            </tbody>
                        </table>

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
                                    <input type="text" name="fecha_lectura" id="fecha_lectura" class="form-control read datepicker"  <?php if($num>0){ echo "value='$fecha_lectura' readonly"; }?>>
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
echo "<script src='js/funciones/funciones_examen_quimica.js'></script>";

}
function insert()
{
    $id_examen_paciente = $_POST["id_examen_paciente"];
    $id_examen = $_POST["id_examen"];
    $fecha = date("Y-m-d");
    $glucosa_azar = $_POST["glucosa_azar"];
    $glucosa_prandial = $_POST["glucosa_prandial"];
    $colesterol_total = $_POST["colesterol_total"];
    $colesterol_hdl = $_POST["colesterol_hdl"];
    $colesterol_ldl = $_POST["colesterol_ldl"];
    $trigliceridos = $_POST["trigliceridos"];
    $lipidos_totales = $_POST["lipidos_totales"];
    $creatinina = $_POST["creatinina"];
    $acido_urico = $_POST["acido_urico"];
    $urea = $_POST["urea"];
    $nitrogeno_ureico = $_POST["nitrogeno_ureico"];
    $sodio = $_POST["sodio"];
    $potasio = $_POST["potasio"];
    $cloro = $_POST["cloro"];
    $proteinas_totales = $_POST["proteinas_totales"];
    $albumina = $_POST["albumina"];
    $globulina = $_POST["globulina"];
    $relacion_ag = $_POST["relacion_ag"];
    $amilasa = $_POST["amilasa"];
    $bilirrubina_total = $_POST["bilirrubina_total"];
    $bilirrubina_directa = $_POST["bilirrubina_directa"];
    $bilirrubina_indirecta = $_POST["bilirrubina_indirecta"];
    $calcio = $_POST["calcio"];
    $fosforo = $_POST["fosforo"];
    $proteina_reactiva = $_POST["proteina_reactiva"];
    $tsh = $_POST["tsh"];
    $t3_libre = $_POST["t3_libre"];
    $t4_libre = $_POST["t4_libre"];
    $ldh = $_POST["ldh"];
    $hda1 = $_POST["hda1"];
    $fraccion = $_POST["fraccion"];
    $transaminasa_go = $_POST["transaminasa_go"];
    $transaminasa_gp = $_POST["transaminasa_gp"];
    $observacion = $_POST["observacion"];
    $reporta = $_POST["reporta"];
    $fecha_lectura = MD($_POST["fecha_lectura"]);

    $table = 'examen_quimica_sanguinea';
    $form_data = array(
        'id_examen_paciente' => $id_examen_paciente, 
        'fecha' => $fecha,
        'glucosa_azar' => $glucosa_azar,
        'glucosa_prandial' => $glucosa_prandial,
        'colesterol_total' => $colesterol_total,
        'colesterol_hdl' => $colesterol_hdl,
        'colesterol_ldl' => $colesterol_ldl,
        'trigliceridos' => $trigliceridos,
        'lipidos_totales' => $lipidos_totales,
        'creatinina' => $creatinina,
        'acido_urico' => $acido_urico,
        'urea' => $urea,
        'nitrogeno_ureico' => $nitrogeno_ureico,
        'sodio' => $sodio,
        'potasio' => $potasio,
        'cloro' => $cloro,
        'proteinas_totales' => $proteinas_totales,
        'albumina' => $albumina,
        'globulina' => $globulina,
        'relacion_ag' => $relacion_ag,
        'amilasa' => $amilasa,
        'bilirrubina_total' => $bilirrubina_total,
        'bilirrubina_directa' => $bilirrubina_directa,
        'bilirrubina_indirecta' => $bilirrubina_indirecta,
        'calcio' => $calcio,
        'fosforo' => $fosforo,
        'proteina_reactiva' => $proteina_reactiva,
        'tsh' => $tsh,
        't3_libre' => $t3_libre,
        't4_libre' =>$t4_libre,
        'ldh' => $ldh,
        'hda1' => $hda1,
        'fraccion' => $fraccion,
        'transaminasa_go' => $transaminasa_go,
        'transaminasa_gp' => $transaminasa_gp,
        'observacion' => $observacion,
        'reporta' => $reporta,
        'fecha' => $fecha,
        );
    $table1 = "examen_paciente";
    $form_data1 = array(
        'fecha_lectura' => $fecha_lectura,
        );
    $where1 = "id_examen_paciente='$id_examen_paciente'";
    $insert1 = _update($table1, $form_data1, $where1);
    if($id_examen>0)
    {
        $where = "id_sanguinea='$id_examen'";
        $insert = _update($table, $form_data, $where);
        if($insert && $insert1)
        {
            $xdata["typeinfo"]="Success";
            $xdata["msg"]="Datos registrados con exito!!!";
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
