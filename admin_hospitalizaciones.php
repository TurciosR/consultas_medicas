<?php
include ("_core.php");
	// Page setup
	$_PAGE = array ();
	$title = 'Administrar Hospitalizaciones';
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
include_once "header.php";
include_once "main_menu.php";
$id_sucursal=$_SESSION["id_sucursal"];
//permiso del script
$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);
//permiso del script
$fini = date('d-m')."-".(date("Y")-1);
$fin = date("d-m-Y");
if ($links!='NOT' || $admin=='1' )
{
	?>


<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <?php
					//if ($admin=='t' && $active=='t'){
					echo "<div class='ibox-title'>";
					$filename='agregar_hospitalizacion.php';
					$link=permission_usr($id_user,$filename);
					if ($link!='NOT' || $admin=='1' )
					    echo "<a href='agregar_hospitalizacion.php' class='btn btn-primary' role='button'><i class='fa fa-plus icon-large'></i> Agregar Hospitalizacion</a>";
					echo "</div>";
					?>
                <div class="ibox-content">
                    <!--load datables estructure html-->
                    <header>
                        <h3 class="text-navy"><b><i class="fa fa-money fa-1x"></i> <?php echo $title;?></b></h3>
                    </header>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Fecha Inicio</label>
                            <input type="text" name="fini" id="fini" value="<?php echo $fini; ?>"
                                class="form-control datepicker">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Fecha Fin</label>
                            <input type="text" name="fin" id="fin" value="<?php echo $fin; ?>"
                                class="form-control datepicker">
                        </div>
                        <div class="col-md-4 form-group">
                            <br>
                            <a id="seacr" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> Mostrar </a>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-lg-12">
                            <table class="table table-striped table-bordered table-hover" id="editable2">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">ID</th>
                                        <th class="col-md-3">PACIENTE</th>
                                        <th class="col-md-3">DOCTOR QUE LO ANTIENDE</th>
                                        <th class="col-md-2">FECHA Y HORA ENTRADA</th>
                                        <th class="col-md-2">FECHA Y HORA SALIDA</th>
                                        <th class="col-md-1">TIPO</th>
                                        <th class="col-md-1">TOTAL</th>
                                        <th class="col-md-2">HABITACION</th>
                                        <th class="col-md-1">ESTADO</th>
                                        <th class="col-md-2">ACCI&Oacute;N</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Show Modal Popups View & Delete -->
                    <input type="hidden" name="process" id="process" value="adm">

                    <div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
                        aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content modal-lg'></div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
                        aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content modal-lg'></div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class='modal fade' id='verBorrado' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
                        aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content modal-lg'></div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <div class="modal fade" id='transferenciaModal' role="dialog" aria-labelledby="myModalLabel"
                        aria-hidden="true">
                        <div class='modal-dialog modal-md'>
                            <div class='modal-content modal-md'>

                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                    <!--ver detalle -->
                    <div class='modal fade' id='verModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
                        aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content modal-lg'></div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div>
                <!--div class='ibox-content'-->
            </div>
            <!--<div class='ibox float-e-margins' -->
        </div>
        <!--div class='col-lg-12'-->
    </div>
    <!--div class='row'-->
</div>
<!--div class='wrapper wrapper-content  animated fadeInRight'-->
<?php
	include("footer.php");
	echo" <script type='text/javascript' src='js/funciones/funciones_hospitalizacion.js'></script>";
} //permiso del script
else {
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
?>