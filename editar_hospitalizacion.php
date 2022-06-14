<?php
include_once "_core.php";
function initial()
{
    $title = 'Editar Hospitalizacion';
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

    $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/datetime/bootstrap-datetimepicker.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/fileinput/fileinput.css" media="all" rel="stylesheet" type="text/css"/>';
    $_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

    $_PAGE ['links'] .= '<link href="css/plugins/timepicki/timepicki.css" rel="stylesheet">';
    $id_hospitalizacion = $_REQUEST['id_hospitalizacion'];
    include_once "header.php";
    include_once "main_menu.php";
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);
    $hoy = date("d-m-Y");

    $sql = "SELECT hospitalizacion.id_hospitalizacion, CONCAT(paciente.nombres, ' ', paciente.apellidos) as 'nombre_paciente', recepcion.evento, pisos.id_piso, cuartos.id_cuarto, cuartos.id_estado_cuarto_cuarto, hospitalizacion.precio_habitacion, hospitalizacion.minuto, hospitalizacion.momento_entrada, hospitalizacion.momento_salida, hospitalizacion.id_estado_hospitalizacion, id_doctor_at  FROM hospitalizacion INNER JOIN recepcion on recepcion.id_recepcion = hospitalizacion.id_recepcion INNER JOIN cuartos on cuartos.id_cuarto = hospitalizacion.id_cuarto_H INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto WHERE hospitalizacion.id_hospitalizacion = '$id_hospitalizacion'";
    $query = _query($sql);
    $row = _fetch_array($query);

    $nombre_paciente = $row['nombre_paciente'];
    $evento = $row['evento'];
    $id_piso  = $row['id_piso'];
    $id_cuarto = $row['id_cuarto'];
    $id_hospitalizacion = $row['id_hospitalizacion'];
    $id_estado_cuarto_cuarto = $row['id_estado_cuarto_cuarto'];
    $precio_habitacion = $row['precio_habitacion'];
    $minuto = $row['minuto'];
    $momento_entrada = $row['momento_entrada'];
    $momento_salida = $row['momento_salida'];
    $momentos_entrada = explode(" ",$momento_entrada);
    $momentos_salida = explode(" ",$momento_salida);
    $hora_entrada = _hora_media_decode($momentos_entrada[1]);
    $hora_salida = _hora_media_decode($momentos_salida[1]);
    $fecha_entrada = ED($momentos_entrada[0]);
    $fecha_salida = ED($momentos_salida[0]);
    $id_sucursal = $_SESSION['id_sucursal'];
    $id_estado_hospitalizacion = $row['id_estado_hospitalizacion'];
    $id_doctor_at=$row['id_doctor_at'];

    $var1 = "";
    $var2 = "";

    if($minuto == "1"){
        $var2="checked";
    }
    else{
        $var1="checked";
    }

    ?>
    <style  type="text/css">
                .datepicker table tr td, .datepicker table tr th{
                    border:none;
                    background:white;
                }
            </style>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-2">
            </div>
        </div>
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                    <?php
                    //permiso del script
                    if ($links!='NOT' || $admin=='1' ){
                    ?>
                        <div class="ibox-title">
                        <h3 class="text-navy"><b><i class="fa fa-money fa-1x"></i> <?php echo $title;?></b></h3>
                        </div>
                        <div class="ibox-content">
                            <form name="formulario_hospitalizacion" id="formulario_hospitalizacion">
                                <div class="row">
                                    <div class = "col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group has-info single-line">
                                                    <label>Paciente <span style="color:red;">*</span></label>
                                                    <input type="text" id="paciente" name="paciente"  class="form-control usage sel" placeholder="Ingrese Paciente" data-provide="typeahead" autocomplete="off" value="<?php echo $nombre_paciente ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                    <div class="form-group has-info single-line">
                                                        <label>Descripcion de la recepcion<span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" id="descripcion_recepcion" name="descripcion_recepcion" value="<?php echo $evento ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group has-info single-line">
                                                    <label>Doctor que lo atendera <span style="color:red;">*</span></label>
                                                    <select class="select" id="id_doctor_at" name="id_doctor_at">
                                                        <?php
                                                            $sql_doctor="SELECT d.id_doctor, CONCAT(d.nombres, '', d.apellidos) AS nombre_doctor FROM doctor AS d";
                                                            $query_doctor=_query($sql_doctor);
                                                            if(_num_rows($query_doctor)>0){
                                                                while($row_doctor=_fetch_array($query_doctor)){

                                                        ?>
                                                            <option value="<?=  $row_doctor['id_doctor']; ?>" <?php if($row_doctor['id_doctor']==$id_doctor_at){ echo "selected"; } ?> ><?= $row_doctor['nombre_doctor']; ?></option>
                                                        <?php
                                                                }
                                                            }
                                                        ?>                                                        
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-4">

                                            <?php
                                                if($id_estado_hospitalizacion == 1 || $id_estado_hospitalizacion == 2){
                                                    ?>
                                                        <div class="form-group has-info single-line">
                                                            <label>Numero de piso  <span style="color:red;">*</span></label>
                                                            <br>
                                                            <select class="select col-lg-6" name="numero_piso" id="numero_piso" style="width:100%;">
                                                            <option value="">Seleccione</option>
                                                            <?php
                                                                $sql = _query("SELECT * FROM pisos WHERE deleted is NULL AND id_ubicacion_piso = '$id_sucursal' ORDER BY numero_piso ASC");
                                                                while ($row = _fetch_array($sql))
                                                                {
                                                                    $id_pisox = $row["id_piso"];
                                                                    $numero = $row["numero_piso"];
                                                                    $descripcion = $row['descripcion'];
                                                                    $resultado = "Piso #".$numero."- ".$descripcion;
                                                                    if($id_pisox == $id_piso){
                                                                        echo "<option value='".$id_pisox."' selected>".$resultado."</option>";
                                                                    }
                                                                    else{
                                                                        echo "<option value='".$id_pisox."'>".$resultado."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                            </select>
                                                        </div>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                        <div class="form-group has-info single-line">
                                                            <label>Numero de piso  <span style="color:red;">*</span></label>
                                                            <br>
                                                            <?php
                                                                $sql = _query("SELECT * FROM pisos WHERE deleted is NULL AND id_ubicacion_piso = '$id_sucursal' ORDER BY numero_piso ASC");
                                                                while ($row = _fetch_array($sql))
                                                                {
                                                                    $id_pisox = $row["id_piso"];
                                                                    $numero = $row["numero_piso"];
                                                                    $descripcion = $row['descripcion'];
                                                                    $resultado = "Piso #".$numero."- ".$descripcion;
                                                                    if($id_pisox == $id_piso){
                                                                        ?>
                                                                            <input type="text" class="form-control" id="numero_piso" name="numero_piso" value="<?php echo $resultado ?>" readonly>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                    <?php
                                                }

                                            ?>

                                            </div>
                                            <div class="col-lg-4">

                                            <?php
                                                if($id_estado_hospitalizacion == 1 || $id_estado_hospitalizacion == 2){
                                                    ?>
                                                        <div class="form-group has-info single-line">
                                                            <label>Numero de habitacion <span style="color:red;">*</span></label>
                                                            <select class="select col-lg-6" name="n_habitacion" id="n_habitacion" style="width:100%;">
                                                                <?php
                                                                    $sql_mun = _query("SELECT cuartos.id_cuarto, cuartos.numero_cuarto, cuartos.descripcion, tipo_cuarto.tipo, tipo_cuarto.cantidad FROM cuartos INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto WHERE pisos.id_piso = '$id_piso' AND pisos.id_ubicacion_piso = '$id_sucursal'");
                                                                    echo "<option value=''>Seleccione</option>";
                                                                    while($mun_dt=_fetch_array($sql_mun))
                                                                    {
                                                                        $resultado = "Cuarto #".$mun_dt['numero_cuarto'].", ".$mun_dt['descripcion'].", cuarto de tipo ".$mun_dt['tipo']." con capacidad para ".$mun_dt['cantidad']." personas";
                                                                        if($mun_dt['id_cuarto'] == $id_cuarto){

                                                                        echo "<option value='".$mun_dt["id_cuarto"]."' selected>".$resultado."</option>";
                                                                        }
                                                                        else{

                                                                            echo "<option value='".$mun_dt["id_cuarto"]."'>".$resultado."</option>";
                                                                        }
                                                                    }
                                                                ?>
                                                            </select>
                                                            <input type="hidden" name="estado_habitacion" id = "estado_habitacion" value ="<?php echo $id_estado_cuarto_cuarto; ?>">
                                                            <input type="hidden" name="id_estado_hospitalizacion" id = "id_estado_hospitalizacion" value ="<?php echo $id_estado_hospitalizacion; ?>">
                                                        </div>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                            <div class="form-group has-info single-line">
                                                                <label>Numero de habitacion <span style="color:red;">*</span></label>
                                                                    <?php
                                                                        $sql_mun = _query("SELECT cuartos.id_cuarto, cuartos.numero_cuarto, cuartos.descripcion, tipo_cuarto.tipo, tipo_cuarto.cantidad FROM cuartos INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto WHERE pisos.id_piso = '$id_piso' AND pisos.id_ubicacion_piso = '$id_sucursal'");
                                                                        $id_cuarto_X = "";
                                                                        while($mun_dt=_fetch_array($sql_mun))
                                                                        {
                                                                            $id_cuarto_X = $mun_dt['id_cuarto'];
                                                                            $resultado = "Cuarto #".$mun_dt['numero_cuarto'].", ".$mun_dt['descripcion'].", cuarto de tipo ".$mun_dt['tipo']." con capacidad para ".$mun_dt['cantidad']." personas";
                                                                            if($mun_dt['id_cuarto'] == $id_cuarto){
                                                                                ?>
                                                                                <input type="text" class="form-control" id="resul_habitacion" name="resul_habitacion" value="<?php echo $resultado ?>" readonly>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                    <input type="hidden" name="n_habitacion" id="n_habitacion" value="<?php echo $id_cuarto_X ?>" >

                                                                <input type="hidden" name="estado_habitacion" id = "estado_habitacion" value ="<?php echo $id_estado_cuarto_cuarto; ?>">
                                                                <input type="hidden" name="id_estado_hospitalizacion" id = "id_estado_hospitalizacion" value ="<?php echo $id_estado_hospitalizacion; ?>">
                                                            </div>
                                                    <?php
                                                }

                                            ?>

                                            </div>
                                            <div class="col-lg-4 ">
                                                <div id='informacion_estado'>
                                                    <div class="form-group has-info text-center alert alert-warning">
                                                        <label><?php echo "Primero seleccione una habitacion"; ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 ">
                                            <?php
                                                if($id_estado_hospitalizacion == "1" || $id_estado_hospitalizacion == "2"){
                                                    ?>
                                                        <div class="form-group has-info single-line">
                                                            <label>Precio por hora de la habitacion<span style="color:green;"> $ </span><span style="color:red;"> * </span></label>
                                                            <input type="text" class="form-control decimal" id="precio_habitacion" name="precio_habitacion" value='Primero seleccione una habitacion.' readonly>
                                                        </div>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                        <div class="form-group has-info single-line">
                                                            <label>Precio por hora de la habitacion<span style="color:green;"> $ </span><span style="color:red;"> * </span></label>
                                                            <input type="text" class="form-control decimal" id="precio_final" name="precio_final" value="<?php echo $precio_habitacion; ?>"readonly>
                                                        </div>
                                                    <?php
                                                }
                                            ?>

                                            </div>

                                            <div class="col-lg-6 ">
                                            <?php
                                                if($id_estado_hospitalizacion == 1 || $id_estado_hospitalizacion == 2){
                                                    ?>
                                                        <div class="form-group has-info single-line">
                                                            <label>Tipo de cobro <span style="color:red;">*</span></label>
                                                            <br>
                                                            <input class="form-control-input" type="radio" name="radio_cobro" id="radio_cobro1" value="1" <?php echo $var1; ?> >
                                                            <label class="form-control-label" for="radio_cobro1">
                                                                Por hora
                                                            </label>
                                                            <input class="form-control-input" type="radio" name="radio_cobro" id="radio_cobro2" value="2" <?php echo $var2; ?> >
                                                            <label class="form-control-label" for="radio_cobro1">
                                                                Por minuto
                                                            </label>
                                                        </div>


                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                        <div class="form-group has-info single-line">
                                                            <label>Tipo de cobro <span style="color:red;">*</span></label>
                                                            <br>
                                                            <input class="form-control-input" type="radio" name="radio_cobro" id="radio_cobro1" value="1" <?php echo $var1; ?>  disable>
                                                            <label class="form-control-label" for="radio_cobro1">
                                                                Por hora
                                                            </label>
                                                            <input class="form-control-input" type="radio" name="radio_cobro" id="radio_cobro2" value="2" <?php echo $var2; ?> disable>
                                                            <label class="form-control-label" for="radio_cobro1">
                                                                Por minuto
                                                            </label>
                                                        </div>
                                                    <?php
                                                }

                                            ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <?php
                                                if($id_estado_hospitalizacion == 1){
                                                    ?>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Fecha de entrada <span style="color:red;">*</span></label>
                                                                <input type="text" name="fecha_de_entrada" id="fecha_de_entrada" class="form-control datepicker" value="<?php echo $fecha_entrada;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Hora de entrada <span style="color:red;">*</span></label>
                                                                <input type="text" placeholder="HH:mm" class="form-control" id="hora_entrada" name="hora_entrada" autocomplete="off" value="<?php  echo $hora_entrada ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Fecha de salida </label>
                                                                <input type="text" name="fecha_de_salida" id="fecha_de_salida" class="form-control datepicker" value="<?php echo $fecha_salida;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Hora de salida </label>
                                                                <input type="text" placeholder="HH:mm" class="form-control" id="hora_salida" name="hora_salida" autocomplete="off" value="<?php  echo $hora_salida ?>">
                                                            </div>
                                                        </div>
                                                    <?php
                                                }
                                                if($id_estado_hospitalizacion == 2){
                                                    ?>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Fecha de entrada <span style="color:red;">*</span></label>
                                                                <input type="text" name="fecha_de_entrada" id="fecha_de_entrada" class="form-control" value="<?php echo $fecha_entrada;?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Hora de entrada <span style="color:red;">*</span></label>
                                                                <input type="text" placeholder="HH:mm" class="form-control" id="hora_entrada" name="hora_entrada" autocomplete="off" value="<?php  echo $hora_entrada ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Fecha de salida </label>
                                                                <input type="text" name="fecha_de_salida" id="fecha_de_salida" class="form-control" value="<?php echo $fecha_salida;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Hora de salida </label>
                                                                <input type="text" placeholder="HH:mm" class="form-control" id="hora_salida" name="hora_salida" autocomplete="off" value="<?php  echo $hora_salida ?>">
                                                            </div>
                                                        </div>
                                                    <?php
                                                }
                                                if($id_estado_hospitalizacion != 2 && $id_estado_hospitalizacion != 1){
                                                    ?>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Fecha de entrada <span style="color:red;">*</span></label>
                                                                <input type="text" name="fecha_de_entrada" id="fecha_de_entrada" class="form-control datepicker" value="<?php echo $fecha_entrada;?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Hora de entrada <span style="color:red;">*</span></label>
                                                                <input type="text" placeholder="HH:mm" class="form-control" id="hora_entrada" name="hora_entrada" autocomplete="off" value="<?php  echo $hora_entrada ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Fecha de salida </label>
                                                                <input type="text" name="fecha_de_salida" id="fecha_de_salida" class="form-control datepicker" value="<?php echo $fecha_salida;?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <div class="form-group has-info single-line">
                                                                <label>Hora de salida </label>
                                                                <input type="text" placeholder="HH:mm" class="form-control" id="hora_salida" name="hora_salida" autocomplete="off" value="<?php  echo $hora_salida ?>" readonly>
                                                            </div>
                                                        </div>


                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <br>
                                    <div class='col-lg-6'>

                                    </div>
                                    <br>
                                    <div class='col-lg-6 d-flex justify-content-between align-items-center'>
                                    <br>
                                        <input type="submit" id="editar_hospitalizacion" style="float: right;" name="editar_hospitalizacion" value="Guardar" class="btn btn-primary m-t-n-xs" />
                                    </div>
                                </div>

                                <input type="hidden" name="process" id="process" value="edited"><br>
                                <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" value="<?php echo $id_hospitalizacion; ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include_once ("footer.php");
        echo "<script src='js/funciones/funciones_hospitalizacion.js'></script>";
    } //permiso del script
    else
    {
        $mensaje = "No tiene permiso para acceder a este modulo";
        echo "<br><br>$mensaje<div><div></div></div</div></div>";
        include "footer.php";
    }
}

function insertar()
{
    $edit2 = 0;
    $id_sucursal = $_SESSION['id_sucursal'];
    $id_cuarto = $_POST['id_cuarto'];
    $precio_por_hora = $_POST['precio_por_hora'];
    $tipo_pago = $_POST['tipo_pago'];
    $fecha_de_entrada = "";
    $hora_de_salida="";
    $momento_entrada = "";
    $id_doctor_at=$_POST['id_doctor_at'];

    if(isset($_POST['fecha_de_entrada'])){
        $fecha_de_entrada = $_POST['fecha_de_entrada'];
        $hora_entrada = $_POST['hora_entrada'];
        $momento_entrada = MD($fecha_de_entrada)." "._hora_media_encode($hora_entrada);
    }

    $fecha_de_salida = $_POST['fecha_de_salida'];
    if($fecha_de_entrada = ""){
        $edit2 = 1;
    }
    $hora_salida = $_POST['hora_salida'];
    $id_hospitalizacion = $_POST['id_hospitalizacion'];
    $hora_salida = _hora_media_encode($hora_salida);

    $momento_salida = MD($fecha_de_salida)." ".$hora_salida;

    $insert_table = 'hospitalizacion';
    if($edit2){
        $form_data = array(
            'id_cuarto_H' => $id_cuarto,
            'momento_entrada' => $momento_entrada,
            'momento_salida' => $momento_salida,
            'precio_habitacion' => $precio_por_hora,
            'minuto' => $tipo_pago,
            'id_doctor_at'=>$id_doctor_at
        );
    }
    else{
        $form_data = array(
            'id_cuarto_H' => $id_cuarto,
            'momento_salida' => $momento_salida,
            'precio_habitacion' => $precio_por_hora,
            'minuto' => $tipo_pago,
            'id_doctor_at'=>$id_doctor_at
        );
    }

    $where = " id_hospitalizacion = '$id_hospitalizacion'";
    $insert = _update($insert_table, $form_data, $where);
    if($insert){
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Registro editado con exito!';
        $xdatos['process']='insert';
    }
    else{
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Registro no pudo ser editado!'._error();
    }
    echo json_encode($xdatos);
}
function habitacion($id_piso)
{
    $option = "";
    $id_sucursal = $_SESSION['id_sucursal'];
    $sql_mun = _query("SELECT cuartos.id_cuarto, cuartos.numero_cuarto, cuartos.descripcion, tipo_cuarto.tipo, tipo_cuarto.cantidad FROM cuartos INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto WHERE pisos.id_piso = '$id_piso' AND pisos.id_ubicacion_piso = '$id_sucursal'");
    $option .= "<option value=''>Seleccione</option>";
    while($mun_dt=_fetch_array($sql_mun))
    {
        $resultado = "Cuarto #".$mun_dt['numero_cuarto'].", ".$mun_dt['descripcion'].", cuarto de tipo ".$mun_dt['tipo']." con capacidad para ".$mun_dt['cantidad']." personas";
        $option .= "<option value='".$mun_dt["id_cuarto"]."'>".$resultado."</option>";
    }
    echo $option;
}
function estado_habitacion(){
    $estado = $_POST['estado'];
    $id_sucursal = $_SESSION['id_sucursal'];
    $id_habitacion = $_POST['n_habitacion'];
    $sql = "SELECT estado_cuarto.estado, cuartos.precio_por_hora FROM estado_cuarto INNER JOIN cuartos on estado_cuarto.id_estado_cuarto = cuartos.id_estado_cuarto_cuarto INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto WHERE cuartos.id_cuarto = '$id_habitacion' AND pisos.id_ubicacion_piso = '$id_sucursal'";
    $query = _query($sql);
    $row = _fetch_array($query);
    $resultado = "";
    if($estado == "1"){
        $resultado .= "<div class='form-group has-info text-center alert alert-success'>";
        $resultado .="<label id='texto_estado' value='DISPONIBLE'>El cuarto se encuentra disponible</label>";
        $resultado .="</div>";
    }
    if($estado == "2"){
        $resultado .= "<div class='form-group has-info text-center alert alert-warning'>";
        $resultado .="<label id='texto_estado' value='OCUPADO'>El cuarto se encuentra ocupado</label>";
        $resultado .="</div>";
    }
    if($estado == "3"){
        $resultado .= "<div class='form-group has-info text-center alert alert-info'>";
        $resultado .="<label id='texto_estado' value='MANTENIMIENTO'>El cuarto se encuentra en mantenimiento</label>";
        $resultado .="</div>";
    }
    if($estado == "4"){
        $resultado .= "<div class='form-group has-info text-center alert alert-danger'>";
        $resultado .="<label id='texto_estado' value='FUERA_DE_SERVICIO'>El cuarto se encuentra fuera de servicio</label>";
        $resultado .="</div>";
    }
    $xdatos['resultado']=$resultado;
    $precio_por_hora = number_format($row['precio_por_hora'], 2);
        $precio_por_hora= $precio_por_hora;
    $xdatos['precio']=$precio_por_hora;
    $xdatos['estado'] = $estado;
    echo json_encode($xdatos);
}
function verificar_paciente($id_paciente){
    $id_sucursal = $_SESSION['id_sucursal'];
    $sql = "SELECT paciente.id_paciente, recepcion.id_recepcion FROM paciente INNER JOIN recepcion on recepcion.id_paciente_recepcion = paciente.id_paciente INNER JOIN estado_recepcion on estado_recepcion.id_estado_recepcion = recepcion.id_estado_recepcion WHERE paciente.id_paciente = '$id_paciente' AND recepcion.id_sucursal_recepcion = '$id_sucursal' AND estado_recepcion.id_estado_recepcion != 4 AND estado_recepcion.id_estado_recepcion != 5 ";
    $consulta = _query($sql);
    if(_num_rows($consulta) > 0){
        $row = _fetch_array($consulta);
        $xdatos['resultado'] = "1";
        $xdatos['id_recepcion_x'] = $row['id_recepcion'];
    }
    else{
        $xdatos['resultado'] = "0";
    }
    echo json_encode($xdatos);
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
            case 'edited':
                insertar();
            break;
            case 'habitacion':
                habitacion($_POST['id_piso']);
            break;
            case 'estado_habitacion_edit':
                estado_habitacion();
            break;
            case 'verificar_paciente':
                verificar_paciente($_POST['id_paciente']);
                break;
            case 'edited':
                editar();
                break;
        }
    }
}
?>
