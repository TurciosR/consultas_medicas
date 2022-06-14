<?php
include ("_core.php");
function initial(){
    $id_tipo_habitacion = $_REQUEST['id_tipo_habitacion'];
$id_sucursal = $_SESSION['id_sucursal'];
$sql1="SELECT * FROM tipo_cuarto WHERE id_tipo_cuarto = '$id_tipo_habitacion' AND id_sucursal = '$id_sucursal'";
$consulta1 = _query($sql1);
$row1 = _fetch_array($consulta1);
$form = explode(" ",$row1['created_at']);
$fecha = ED($form[0]);
$tablas="";
$tablas.="<table class='table'>";
$tablas.="<thead class='thead-dark'>";
$tablas.="<tr>";
$tablas.="<th scope='col'>Tipo de habitacion</th>";
$tablas.="<th scope='col'>Descripcion</th>";
$tablas.="<th scope='col'>Capacidad de pacientes</th>";
$tablas.="<th scope='col'>Estado</th>";
$tablas.="<th scope='col'>Creada el dia</th>";
$tablas.="</tr>";
$tablas.="</thead>";
$tablas.="<tbody>";
$tablas.="<tr>";
$tablas.="<td>".$row1['tipo']."</td>";
$tablas.="<td>".$row1['descripcion']."</td>";
$tablas.="<td>".$row1['cantidad']."</td>";
$tablas.="<td>";
if($row1['estado'] == '1'){
   $tablas.= "<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>Activo</label>";
}
if($row1['estado'] == '0'){
   $tablas.= "<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>Inactivo</label>";
}
$tablas.="</td>";
$tablas.="<td>".$fecha."</td>";
$tablas.="</tr>";
$tablas.="</tbody>
    </table>";


$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title text-navy">Datos del tipo de habitacion</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
            <?php	if ($links!='NOT' || $admin=='1' ){ ?>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-info text-center alert alert-info">
                          <label><?php echo "Informacion del tipo de habitacion."; ?></label>
                      </div>
                    </div>
                    <br>
                    <div class='col-md-12'>
                        <?php
                        echo $tablas;

                        ?>

                    </div>
                </div>
                <input type="hidden" name="id_tipo_habitacion" id='id_tipo_habitacion' value="<?php echo $id_tipo_habitacion; ?>">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-primary" id="btnDelete">Borrar</button>
<?php
    echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
    </div><!--/modal-footer -->";
}
else {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
}
}

function deleted()
{
    $id_sucursal = $_SESSION['id_sucursal'];
    $id_tipo_habitacion = $_POST ['id_tipo_habitacion'];
    $sql_verificacion = "SELECT tblCuartos.id_cuarto FROM tblCuartos INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = tblCuartos.id_tipo_cuarto_cuarto INNER JOIN tblHospitalizacion on tblHospitalizacion.id_cuarto_H = tblCuartos.id_cuarto INNER JOIN tblPisos on tblPisos.id_piso = tblCuartos.id_piso_cuarto WHERE tblPisos.id_ubicacion_piso = '$id_sucursal' AND tipo_cuarto.id_tipo_cuarto = '$id_tipo_habitacion'  AND tblHospitalizacion.id_estado_hospitalizacion = 1";
    $consulta = _query($sql_verificacion);
    $cout = _num_rows($consulta);
    if($cout > 0){
        $xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Ese tipo de habitacion tiene cuartos con hospitalizaciones registradas';
    }
    else{
        $table = 'tipo_cuarto';
        $where_clause = "id_tipo_cuarto='" . $id_tipo_habitacion . "'";
        $delete = _delete ( $table, $where_clause );
        if ($delete) {
            $xdatos ['typeinfo'] = 'Success';
            $xdatos ['msg'] = 'Tipo de cuarto eliminado correctamente!';
        } else {
            $xdatos ['typeinfo'] = 'Error';
            $xdatos ['msg'] = 'El Tipo de cuarto no pudo ser eliminado';
        }
    }
	echo json_encode ( $xdatos );
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else
{
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formDelete' :
				initial();
				break;
			case 'deleted' :
				deleted();
				break;
		}
	}
}
?>
