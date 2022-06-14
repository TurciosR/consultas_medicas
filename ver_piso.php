<?php
include ("_core.php");
$id_piso = $_REQUEST['id_piso'];
$sql1="SELECT pisos.numero_piso, pisos.descripcion FROM pisos WHERE pisos.id_piso = '$id_piso'";
$consulta1 = _query($sql1);
$row1 = _fetch_array($consulta1);
$habitaciones = 0;
$sql2 = "SELECT cuartos.numero_cuarto, cuartos.descripcion, cuartos.precio_por_hora, estado_cuarto.estado, tipo_cuarto.tipo, tipo_cuarto.cantidad FROM cuartos INNER JOIN estado_cuarto on estado_cuarto.id_estado_cuarto = cuartos.id_estado_cuarto_cuarto INNER JOIN tipo_cuarto on tipo_cuarto.id_tipo_cuarto = cuartos.id_tipo_cuarto_cuarto INNER JOIN pisos on pisos.id_piso = cuartos.id_piso_cuarto WHERE pisos.id_piso = '$id_piso' AND pisos.deleted is NULL ";
$consulta2 = _query($sql2);
$numero = _num_rows($consulta2);
$tablas = "";
if($numero == 0){
    $tablas.="<div class='form-group has-info text-center alert alert-danger'>";
    $tablas.="<label>Este piso no tiene cuartos registrados!!</label></div>";
}
else{
      $tablas.="<table class='table'>";
      $tablas.="<thead class='thead-dark'>";
      $tablas.="<tr>";
      $tablas.="<th scope='col'>Numero</th>";
      $tablas.="<th scope='col'>Descripcion</th>";
      $tablas.="<th scope='col'>Precio</th>";
      $tablas.="<th scope='col'>Estado</th>";
      $tablas.="<th scope='col'>Tipo de cuarto</th>";
      $tablas.="<th scope='col'>Cantidad de pacientes</th>";
      $tablas.="</tr>";
      $tablas.="</thead>";
      $tablas.="<tbody>";
    while ($row2 = _fetch_array($consulta2)) {
        $tablas.="<tr>";
        $tablas.="<th scope='row'>".$row2['numero_cuarto']."</th>";
        $tablas.="<td>".$row2['descripcion']."</td>";
        $tablas.="<td>$".$row2['precio_por_hora']."</td>";
        $tablas.="<td>";
        if($row2['estado'] == 'DISPONIBLE'){
          $tablas.= "<label class='badge' style='background:#58FF3B; color:#FFF; font-weight:bold;'>DISPONIBLE</label>";
        }
        if($row2['estado'] == 'OCUPADO'){
          $tablas.= "<label class='badge' style='background:#FF3B3B; color:#FFF; font-weight:bold;'>OCUPADO</label>";
        }
        if($row2['estado'] == 'MANTENIMIENTO'){
          $tablas.="<label class='badge' style='background:#A6B900; color:#FFF; font-weight:bold;'>MANTENIMIENTO</label>";
        }
        $tablas.="</td>";
        $tablas.="<td>".$row2['tipo']."</td>";
        $tablas.="<td>".$row2['cantidad']."</td>";
        $tablas.="</tr>";
    }
    $tablas.="</tbody>
    </table>";
}
$id_user=$_SESSION["id_usuario"];
$admin=$_SESSION["admin"];
$uri = $_SERVER['SCRIPT_NAME'];
$filename=get_name_script($uri);
$links=permission_usr($id_user,$filename);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title text-navy">Datos del piso</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
            <?php	if ($links!='NOT' || $admin=='1' ){ ?>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group has-info text-center alert alert-info">
                          <label><?php echo "Informacion del piso #".$row1['numero_piso']."."; ?></label>
                          <label><?php echo $row1['descripcion']; ?></label>
                      </div>
                    </div>
                    <br>
                    <div class='col-md-12'>
                        <?php echo $tablas; ?>
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
