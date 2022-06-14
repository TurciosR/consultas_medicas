<?php
include ("_core.php");
function initial(){
    $id_habitacion = $_REQUEST['id_habitacion'];
    $sql1="SELECT cuartos.id_cuarto, cuartos.numero_cuarto, cuartos.descripcion as 'descripcion_cuarto', cuartos.precio_por_hora, pisos.numero_piso, pisos.descripcion as 'descripcion_piso', tipo_cuarto.tipo, tipo_cuarto.descripcion as 'descripcion_tipo_cuarto', tipo_cuarto.cantidad, estado_cuarto.estado, estado_cuarto.descripcion as 'descripcion_estado_cuarto' FROM cuartos INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto INNER JOIN estado_cuarto on estado_cuarto.id_estado_cuarto = cuartos.id_estado_cuarto_cuarto WHERE cuartos.id_cuarto = '$id_habitacion'";
    $consulta1 = _query($sql1);
    $row1 = _fetch_array($consulta1);
    $id_cuarto = $row1['id_cuarto'];
    $numero_cuarto = $row1['numero_cuarto'];
    $descripcion_cuarto = $row1['descripcion_cuarto'];
    $precio_por_hora = $row1['precio_por_hora'];
    $numero_piso = $row1['numero_piso'];
    $descripcion_piso = $row1['descripcion_piso'];
    $tipo_cuarto = $row1['tipo'];
    $descripcion_tipo_cuarto = $row1['descripcion_tipo_cuarto'];
    $cantidad_cuarto = $row1['cantidad'];
    $estado_cuarto = $row1['estado'];
    $descripcion_estado_cuarto = $row1['descripcion_estado_cuarto'];
    $precio_por_hora = number_format($precio_por_hora, 2);
    $precio_por_hora= "<p style='color:#008704'>$".$precio_por_hora."</p>";
    if($estado_cuarto == 'DISPONIBLE'){
        $estado_cuarto = "<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>DISPONIBLE</label>";
    }
    if($estado_cuarto == 'OCUPADO'){
        $estado_cuarto = "<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>OCUPADO</label>";
    }
    if($estado_cuarto == 'MANTENIMIENTO'){
        $estado_cuarto = "<label class='badge' style='background:#A6B900; color:#FFF; font-weight:bold;'>MANTENIMIENTO</label>";
    }

    $tablas="";
    $tablas.="<table class='table table-bordered'>";
    $tablas.="<thead class='thead-dark'>";
    $tablas.="<tr>";
    $tablas.="<th scope='col'>Id Cuarto</th>";
    $tablas.="<th scope='col'>Numero Cuarto</th>";
    $tablas.="<th scope='col'>Descripcion Cuarto</th>";
    $tablas.="<th scope='col'>Precio por hora</th>";
    $tablas.="<th scope='col'>Numero Piso</th>";
    $tablas.="<th scope='col'>Descripcion Piso</th>";
    $tablas.="</tr>";
    $tablas.="</thead>";
    $tablas.="<tbody>";
    $tablas.="<tr>";
    $tablas.="<th scope='row'>".$id_cuarto."</th>";
    $tablas.="<th scope='row'>".$numero_cuarto."</th>";
    $tablas.="<th scope='row'>".$descripcion_cuarto."</th>";
    $tablas.="<th scope='row'>".$precio_por_hora."</th>";
    $tablas.="<th scope='row'>".$numero_piso."</th>";
    $tablas.="<th scope='row'>".$descripcion_piso."</th>";
    $tablas.="</tr>";
    $tablas.="</tbody>";
    $tablas.="</table>";
    $tablas.="</br>";
    $tablas.="<table class='table table-bordered'>";
    $tablas.="<thead class='thead-dark'>";
    $tablas.="<tr>";
    $tablas.="<th scope='col'>Tipo de cuarto</th>";
    $tablas.="<th scope='col'>Descripcion tipo cuarto</th>";
    $tablas.="<th scope='col'>Capacidad</th>";
    $tablas.="<th scope='col'>Estado Cuarto</th>";
    $tablas.="<th scope='col'>Descripcion estado cuarto</th>";
    $tablas.="</tr>";
    $tablas.="</thead>";
    $tablas.="<tbody>";
    $tablas.="<tr>";
    $tablas.="<th scope='row'>".$tipo_cuarto."</th>";
    $tablas.="<th scope='row'>".$descripcion_tipo_cuarto."</th>";
    $tablas.="<th scope='row'>".$cantidad_cuarto."</th>";
    $tablas.="<th scope='row'>".$estado_cuarto."</th>";
    $tablas.="<th scope='row'>".$descripcion_estado_cuarto."</th>";
    $tablas.="</tr>";
    $tablas.="</tbody>";
    $tablas.="</table>";
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user,$filename);
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title text-navy">Datos de la habitacion</h4>
    </div>
    <div class="modal-body">
        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row" id="row1">
                <div class="col-lg-12">
                <?php	if ($links!='NOT' || $admin=='1' ){ ?>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group has-info text-center alert alert-info">
                            <label><?php echo "Informacion de la habitacion #".$numero_cuarto."."; ?></label>
                            <label><?php echo $descripcion_cuarto; ?></label>
                        </div>
                        </div>
                        <br>
                        <div class='col-md-12'>
                            <?php echo $tablas; ?>
                        </div>
                    </div>
                    <input type="hidden" name="id_habitacion" id='id_habitacion' value="<?php echo $id_cuarto; ?>">
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
    $id_habitacion = $_POST ['id_habitacion'];
    $sql_verificacion = "SELECT id_hospitalizacion from tblHospitalizacion where id_cuarto_H='$id_habitacion' AND deleted is NULL";
    $consulta = _query($sql_verificacion);
    $cout = _num_rows($consulta);
    if($cout > 0){
        $xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'La habitacion tiene hospitalizaciones registradas';
    }
    else{
        $table = 'cuartos';
        $where_clause = "id_cuarto='" . $id_habitacion . "'";
        $delete = _delete ( $table, $where_clause );
        if ($delete) {
            $xdatos ['typeinfo'] = 'Success';
            $xdatos ['msg'] = 'Habitacion eliminada correctamente!';
        } else {
            $xdatos ['typeinfo'] = 'Error';
            $xdatos ['msg'] = 'La Habitacion no pudo ser eliminada';
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
