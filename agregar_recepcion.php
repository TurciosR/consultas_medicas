<?php
	include ("_core.php");
	// Page setup
	$title='Crear recepcion';
	$_PAGE = array ();
	$_PAGE ['title'] =$title;
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
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/timepicker/jquery.timepicker.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
    $_PAGE ['links'] .= '<link href="css/plugins/timepicki/timepicki.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
    $hoy=date('d-m-Y');
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

?>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
     <hr>
		<div class="col-lg-12">

			<div class="ibox float-e-margins">
				<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
				echo"<div class='ibox-title'>";
				?>
				<div class="ibox-content">
                <form name="formulario_crear_recepcion" id="formulario_crear_recepcion" autocomplete='off'>
					<!--load datables estructure html-->
					<header>
						<h3 style="color:#194160;"><i class="fa fa-user-md"></i> <b><?php echo $title;?></b></h3>
                        <h5 style="color:#EE4420;"><b><?php echo "**Si no existe el paciente agregarlo desde el modulo de pacientes**";?></b></h5>
					</header>

					<div class="row focuss">
                        <hr>
                        <br>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group has-info single-line">
                                    <label> Doctor que atiende:</label>
                                    <select class="col-md-12 select usage sel1" id="doctor" name="doctor" style="width:100%; ">
                                        <option value="">Seleccione</option>
                                        <?php
                                            $sqld = "SELECT doctor.id_doctor, concat(doctor.nombres,' ',doctor.apellidos) as 'nombre_d' FROM doctor ";
                                            $resul=_query($sqld);
                                            while($doctr = _fetch_array($resul))
                                            {
                                                echo "<option value=".$doctr["id_doctor"];
                                                echo">".$doctr["nombre_d"]."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group has-info single-line">
                                    <label> Doctor que refiere:</label>
                                    <select class="col-md-12 select usage sel1" id="doctor_refiere" name="doctor_refiere" style="width:100%; ">
                                        <option value="">Seleccione</option>
                                        <?php
                                            $sqld = "SELECT doctor.id_doctor, concat(doctor.nombres,' ',doctor.apellidos) as 'nombre_d' FROM doctor ";
                                            $resul=_query($sqld);
                                            while($doctr = _fetch_array($resul))
                                            {
                                                echo "<option value=".$doctr["id_doctor"];
                                                echo">".$doctr["nombre_d"]."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group has-info single-line">
                                    <label>Fecha de entrada: </label>
                                    <input type="text" name="hasta" id="fechaEntrada" class="form-control" value="<?php echo $hoy?>">
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="form-group has-info single-line">
                                    <label>Paciente: </label>
                                    <input type="text" id="paciente" name="paciente"  class="form-control usage sel" placeholder="Ingrese Paciente" data-provide="typeahead" autocomplete="off" style="border-radius:0px">
                                    <input type="text" id="paciente_replace" name="paciente_replace"  class="form-control usage" hidden readonly autocomplete="off">
                                    <input type="hidden" name="pacientee" id="pacientee" >
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group has-info single-line">
                                    <label> Hora de entrada: </label>
                                    <input type="text" placeholder="HH:mm" class="form-control" id="hora_entrada" name="hora_entrada" autocomplete="off">
                                    <input type="text" placeholder="HH:mm" class="form-control" id="hora_entrada_replace" name="hora_entrada_replace" hidden readonly autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">


                            <div class="col-lg-3">
                                <div class="form-group has-info single-line">
                                    <label >Fecha Nacimiento</label>
                                    <input type="text" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" readonly disable>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group has-info single-line">
                                    <label>Sexo</label>
                                    <input type="text" class="form-control" id="sexo" name="sexo" readonly disable>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group has-info single-line">
                                    <label>DUI</label>
                                    <input type="text" class="form-control" id="dui" name="dui" readonly disable>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group has-info single-line">
                                    <label >Direccion</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" readonly disable>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group has-info single-line">
                                    <label>Descripcion del evento:</label>
                                    <br>
                                    <textarea name="descripcionEvento" placeholder="Ejemplo: presenta una fractura en el femur derecho." id="descripcionEvento" cols="75" rows="1" style="margin-top:6px;"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group has-info single-line" style="margin-top:23px;">
                                    <label class='checkbox-inline'><input type='checkbox' id='parienteResponsable' value='1'>RESPONSABLE INGRESADO </label>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group has-info single-line">
                                <label>Pariente</label>
                                <input type="text" class="form-control usage sel"  id="pariente" name="pariente" placeholder="Ingrese el nombre del pariente">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group has-info single-line">
                                    <label>Telefono pariente</label>
                                    <input type="text" class="form-control usage sel" placeholder="0000-0000"  id="telefonoPariente" name="telefonoPariente">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group has-info single-line">
                                    <label>Parentezco</label>
                                    <select class="col-md-12 select" id="parentezcoSelect" name="parentezcoSelect">
                                        <option value="0">Seleccione</option>
                                        <?php
                                            $sqlp = "SELECT * FROM parentezco";
                                            $resultp=_query($sqlp);
                                            while($pco = _fetch_array($resultp))
                                            {
                                                echo "<option value='".$pco["id_parentezco"]."'>".$pco["descripcion"]."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group has-info single-line" >
                                    <label>Otro parentezco</label>
                                    <input type="text" class="form-control usage sel" id="otroParentezco" name="otroParentezco">
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="id_pestania_recepcion" id="id_pestania_recepcion" value="insert">
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="insert">
                                <input type="submit" id="submit1" name="submit1" value="Guardar" class="btn btn-primary m-t-n-xs pull-right" />
                            </div>
                        </div>
                    </div>
                </form>
               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->
</div>
<div class='modal fade' id='hospitalizacionModal'  role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
	<div class='modal-dialog'>
		<div class='modal-content'>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<?php
    include("footer.php");
    echo" <script type='text/javascript' src='js/funciones/funciones_crear_recepcion.js'></script>";
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}



?>
