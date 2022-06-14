<?php
include_once "_core.php";
include ('num2letras.php');
function initial() {
    $title='Antígenos Prostático';
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
    $query = _query("SELECT e_po.*, e_p.fecha_lectura, e_p.id_examen_paciente FROM examen_prostatico AS e_po, examen_paciente AS e_p WHERE e_po.id_examen_paciente ='$id_examen_paciente' AND e_p.id_examen_paciente ='$id_examen_paciente'");
    $num = _num_rows($query);
    if($num>0)
    {
        $datos = _fetch_array($query);
        $resultado = $datos["resultado"];
        $id_examen = $datos["id_prostatico"];
        $fecha_lectura = ED($datos["fecha_lectura"]);
    }
    else
    {
        $resultado = "";        
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
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table">
                                    <thead class="bg-success">
                                        <tr>
                                            <th style="width: 70%">Resultado</th>
                                            <th style="width: 30%">Valor de referencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type='text' name='resultado' id='resultado' class='form-control read bordes-input'    <?php if($num>0){ echo "value='".$resultado."' readonly";} ?>>
                                            </td>
                                            <td>
                                                <label>Hasta 4.0 ng/ml</label>
                                            </td>
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
                                    <input type="text" name="fecha_lectura" id="fecha_lectura" class="form-control read datepicker bordes-input" <?php if($num>0){ echo "value='$fecha_lectura' readOnly"; }?>>
                                </div>
                            </div>
                        </div>
                        <div class="title-action" id='botones'>
                            <input type="hidden" name="id_examen_paciente" id="id_examen_paciente" value="<?php echo $id_examen_paciente;?>">
                            <input type="hidden" name="id_examen" id="id_examen" value="<?php echo $id_examen;?>">
                            <input type="hidden" name="process" id="process" value="insert">
                            <a id="btn_edit" class="btn btn-primary"><i class="fa fa-pencil"></i> Editar</a>
                            <a id="btn_guardar" class="btn btn-primary" hidden><i class="fa fa-check"></i> Guardar</a>
                        </div>
                        
                    </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once ("footer.php");
echo "<script src='js/funciones/funciones_examen_prostatico.js'></script>";
}
function insert()
{
    $resultado  = $_POST["resultado"];   
    $fecha  = date("Y-m-d");  
    $id_examen_paciente = $_POST["id_examen_paciente"];  
    $id_examen = $_POST["id_examen"]; 
    $fecha_lectura = MD($_POST["fecha_lectura"]);

    $table = "examen_prostatico";
    $form_data = array(
        'id_examen_paciente'=> $id_examen_paciente,
        'fecha'=> $fecha,
        'resultado'=>$resultado
        );
    $table1 = "examen_paciente";
    $form_data1 = array(
        'fecha_lectura' => $fecha_lectura,
        );
    $where1 = "id_examen_paciente='$id_examen_paciente'";
    $insert1 = _update($table1, $form_data1, $where1);
    if($id_examen>0)
    {
        $where = "id_prostatico='$id_examen'";
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
if (! isset ( $_REQUEST ['process'] ))
{
    initial();
} else {
    if (isset ( $_REQUEST ['process'] ))
    {
        switch ($_REQUEST ['process'])
        {
            case 'insert' :
                insert();
                break;
        }
    }
}
?>
