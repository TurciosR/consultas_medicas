<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_detalle_credito.php";
	$links=permission_usr($id_user,$filename);

	$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
    $datos_moneda = _fetch_array($sql0);
    $simbolo = $datos_moneda["simbolo"];  
    $moneda = $datos_moneda["moneda"];

	$id_abono = $_REQUEST['id_abono'];

	$sql="SELECT * FROM abono_credito WHERE id_abono='$id_abono'";
	$result = _query($sql);
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Cuota</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
			<?php 
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
			?>
				<table class="table table-bordered" style="width: 100%;">
					<tr class="bg-success">
						<th>Campo</th>
						<th>Descripción</th>
					</tr>
					<?php
						$row = _fetch_array ($result);
						$id_abono=$row["id_abono"];
						$monto = $row["monto"];
						$observaciones = $row["observaciones"];
						$fecha = ED($row["fecha"]);
						echo"<tr><td>Id cuota</td><td>".$id_abono."</td></tr>";
						echo"<tr><td>Fecha</td><td>".$fecha."</td></tr>";											
						echo"<tr><td>Monto</td><td>".$simbolo."".$monto."</td></tr>";											
						echo"<tr><td>Observaciones</td><td>".$observaciones."</td></tr>";											
					?>
				</table>
			</div>
		</div>
		<?php 
		echo "<input type='hidden' name='id_abono' id='id_abono' value='$id_abono'>";
		?>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btn_delete">Borrar</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
<!--/modal-footer -->
<?php
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	
}
function delete() {
	_begin();
	$id_abono = $_POST ['id_abono'];
	$sql = _query("SELECT id_credito FROM abono_credito WHERE id_abono='$id_abono'");
	$id_credito  = _fetch_array($sql)["id_credito"];
	$table = 'abono_credito';
	$where_clause = "id_abono='" . $id_abono . "'";
	$delete = _delete ( $table, $where_clause );
	if ($delete)
	{
		$t = _fetch_array(_query("SELECT sum(monto) as t FROM abono_credito WHERE id_credito='$id_credito' AND pagado='0'"))["t"];
		$n = _fetch_array(_query("SELECT sum(monto) as n FROM abono_credito WHERE id_credito='$id_credito' AND pagado='1'"))["n"];
		$table2 = "credito";
		$form_data2 = array(
			'abonado' => $n,
			'saldo' => $t
			);
		$where2 = "id_credito='$id_credito'";
		$update = _update($table2, $form_data2, $where2);
		if($update)
		{
			_commit();
			$xdatos ['typeinfo'] = 'Success';
			$xdatos ['msg'] = 'Registro eliminado correctamente';
		}
		else
		{
			_rollback();
			$xdatos ['typeinfo'] = 'Error';
			$xdatos ['msg'] = 'Registo no pudo ser eliminado';	
		}
	}
	else 
	{
		_rollback();
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Registo no pudo ser eliminado';
	}
	echo json_encode ($xdatos);
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
} else {
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formDelete' :
				initial();
				break;
			case 'delete' :
				delete();
				break;
		}
	}
}

?>
