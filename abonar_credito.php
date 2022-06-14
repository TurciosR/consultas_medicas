<?php
include ("_core.php");
function initial()
{
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename="abonar_credito.php";
	$links=permission_usr($id_user,$filename);

	$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
    $datos_moneda = _fetch_array($sql0);
    $simbolo = $datos_moneda["simbolo"];  
    $moneda = $datos_moneda["moneda"];
	//Request Id
    $id_abono=$_REQUEST["id_abono"];
    //Get data from db
    $sql_plan = _query("SELECT * FROM abono_credito WHERE id_abono='$id_abono'");

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Pagar Cuota</h4>
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
						$row = _fetch_array ($sql_plan);
						$id_abono=$row["id_abono"];
						$fecha = ED($row["fecha"]);
						$monto = $row["monto"];
						$observaciones = $row["observaciones"];
						//$fecha_reg = ED($row["fecha_registro"]);
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
	<button type="button" class="btn btn-primary" id="btnAplicar">Pagar</button>
	<button type="button" class="btn btn-default cerrar" data-dismiss="modal">Cerrar</button>
</div>
<!--/modal-footer -->
<?php
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	
}
function deleted() 
{
	$id_abono = $_POST ['id_abono'];

	_begin();
	$table = 'abono_credito';
	$form_data = array(
		'pagado' => 1,
		'id_usuario' => $_SESSION["id_usuario"],
		);
	$where_clause = "id_abono='" . $id_abono . "'";
	
	$delete = _update( $table, $form_data, $where_clause );
	if ($delete)
	{
		$sql = _query("SELECT id_credito, monto FROM abono_credito WHERE id_abono='$id_abono'");
		$datos_credito = _fetch_array($sql);
		$id_credito = $datos_credito["id_credito"];
		$monto = $datos_credito["monto"];
		$t = _fetch_array(_query("SELECT sum(monto) as t FROM abono_credito WHERE id_credito='$id_credito'  AND pagado='0'"))["t"];
		$n = _fetch_array(_query("SELECT sum(monto) as n FROM abono_credito WHERE id_credito='$id_credito' AND pagado='1'"))["n"];
		$cliente = _fetch_array(_query("SELECT cliente FROM credito WHERE id_credito='$id_credito'"))["cliente"];
		$table2 = "credito";
		$estado = "PENDIENTE";
		if($t==0)
		{
			$estado = "FINALIZADO";
		}
		$form_data2 = array(
			'abonado' => $n,
			'saldo' => $t,
			'estado' => $estado,
			);
		$where2 = "id_credito='$id_credito'";
		$update = _update($table2, $form_data2, $where2);
		if($update)
		{
			$fecha = date("Y-m-d");
			$form_data_n = array(
			'cliente'=>$cliente,
			'fecha'=>$fecha,
			'total'=>$monto,
			'tipo_pago'=>'Crédito',
			'tipo'=>'Crédito',
			'concepto'=>'Ingreso por pago de cuota de Crédito'
			);
			$table_n = "factura";
			$fact = _insert($table_n, $form_data_n);
			if($fact)
			{
				_commit();
				$xdatos ['typeinfo'] = 'Success';
				$xdatos ['msg'] = 'Registro ingresado correctamente';
			}
			else
			{
				_rollback();
				$xdatos ['typeinfo'] = 'Error';
				$xdatos ['msg'] = 'Registro no pudo ser ingresado';
			}
		}
		else
		{
			_rollback();
			$xdatos ['typeinfo'] = 'Error';
			$xdatos ['msg'] = 'Registro no pudo ser ingresado';
		}
	}
	else 
	{
		_rollback();
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Registro no pudo ser ingresado';
	}
	echo json_encode ($xdatos);
}
if (! isset ( $_POST['plan'] ))
{
	$plan = 0;
}
else
{
	$plan = 1;
}
if (! isset ( $_REQUEST ['process'] ))
{
	initial();
}
else 
{
	if (isset ( $_REQUEST ['process'] ))
	{
		switch ($_REQUEST ['process'])
		{
			case 'formDelete' :
				initial();
				break;
			case 'deleted' :
				deleted($plan);
				break;
		}
	}
}

?>
