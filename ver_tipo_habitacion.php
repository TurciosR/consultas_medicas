<?php
include ("_core.php");
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
$sql_pisos = "SELECT * FROM tblPisos";
$query = _query($sql_pisos);
$tablas1 = "";
$tablas1.="<table class='table'>";
$tablas1.="<thead class='thead-dark'>";
$tablas1.="<tr>";
$tablas1.="<th scope='col'>Numero de piso</th>";
$tablas1.="<th scope='col'>Numero de cuarto</th>";
$tablas1.="<th scope='col'>Descripcion</th>";
$tablas1.="<th scope='col'>Precio Por hora</th>";
$tablas1.="<th scope='col'>Estado cuarto</th>";
$tablas1.="</tr>";
$tablas1.="</thead>";
$tablas1.="<tbody>";
$contador1=0;
while ($row = _fetch_array($query)) {
    $id_piso = $row['id_piso'];
    $otra_consulta = "SELECT tblCuartos.id_cuarto, tblCuartos.numero_cuarto, tblCuartos.descripcion as 'descripcion_cuarto', tblCuartos.precio_por_hora, tblCuartos.id_estado_cuarto_cuarto, tblPisos.numero_piso FROM tblCuartos INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = tblCuartos.id_tipo_cuarto_cuarto INNER JOIN tblPisos on tblPisos.id_piso = tblCuartos.id_piso_cuarto WHERE tblPisos.id_piso =$id_piso AND tipo_cuarto.id_tipo_cuarto = '$id_tipo_habitacion' AND tblPisos.id_ubicacion_piso = '$id_sucursal'";
    $query2 = _query($otra_consulta);
    while ($row2 = _fetch_array($query2)) {

        $precio_por_hora = number_format($row2['precio_por_hora'], 2);
        $precio_por_hora= "<p style='color:#008704'>$".$precio_por_hora."</p>";
        $tablas1.="<tr>";
        $tablas1.="<td>".$row2['numero_piso']."</td>";
        $tablas1.="<td>".$row2['numero_cuarto']."</td>";
        $tablas1.="<td>".$row2['descripcion_cuarto']."</td>";
        $tablas1.="<td>".$precio_por_hora."</td>";
        $tablas1.="<td>";
        if($row2['id_estado_cuarto_cuarto'] == '1'){
          $tablas1.= "<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>DISPONIBLE</label>";
        }
        if($row2['id_estado_cuarto_cuarto'] == '2'){
          $tablas1.= "<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>OCUPADO</label>";
        }
        if($row2['id_estado_cuarto_cuarto'] == '3'){
          $tablas1.="<label class='badge' style='background:#A6B900; color:#FFF; font-weight:bold;'>MANTENIMIENTO</label>";
        }
        $tablas1.="</td>";
        $tablas1.="</tr>";
        $contador1++;
    }
}
$tablas1.="</tbody>
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
                        if($contador1 == 0){
                            ?>
                                <div class="form-group has-info text-center alert alert-warning">
                                    <label><?php echo "No hay habitaciones registradas con este tipo."; ?></label>
                                </div>

                            <?php
                        }else{
                            ?>
                            <div class="form-group has-info text-center alert alert-info">
                                <label><?php echo "Habitaciones registrada con este tipo."; ?></label>
                            </div>

                            <?php
                            echo $tablas1;
                        }


                        ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
<?php
    echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Cerrar</button>
    </div><!--/modal-footer -->";
}
else {
    echo "<br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div></div></div></div></div>";
}
?>
