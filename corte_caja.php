<?php
include ("_core.php");
function initial(){
	$admin = $_SESSION["admin"];
	$id_user = $_SESSION["id_usuario"];
	$filename = "borrar_detalle_plan";
	$links=permission_usr($id_user,$filename);
	//permiso del script

	$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
	$datos_moneda = _fetch_array($sql0);
	$simbolo = $datos_moneda["simbolo"];  
	$moneda = $datos_moneda["moneda"];

	$now = date("Y-m-d");
	$sql0=_query("SELECT sum(total) as total FROM factura WHERE fecha ='$now'");
	$row0=_fetch_array($sql0);
	$total0 = $row0["total"];
	
	$sql1=_query("SELECT sum(total_sistema) as total FROM corte_caja WHERE fecha ='$now'");
	$row1=_fetch_array($sql1);
	$total1 = $row1["total"];

	$sql2=_query("SELECT total_sistema as total FROM corte_caja WHERE fecha ='$now' ORDER BY id_corte DESC LIMIT 1");
	$row2=_fetch_array($sql2);
	$total2 = $row2["total"];

	$dia = $simbolo."".number_format($total0,2,".",",");
	$ult = $simbolo."".number_format($total0-$total2,2,".",",");

	$resta = $total0-$total1;
	$totale = $simbolo."".number_format($total0-$total1,2,".",",");

?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Corte de Caja</h4>
</div>
<?php
	if($links != 'NOT' || $admin =='1')
	{
?>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<?php if($total0>0){?>
		<div class="row">
			<div class="form-group col-md-6">
				<label class="control-label">Total dia: <?php echo $dia; ?></label>
			</div>
			<div class="form-group col-md-6">
				<label class="control-label">Ultimo Corte: <?php echo $ult; ?></label>
			</div>
		</div>
		<?php }?>
		<div class="row">
			<div class="form-group col-md-6">
				<label class="control-label">Total Sistema</label>
				<input type="text" name="totale" id="totale" class="form-control" readonly value="<?php echo $totale; ?>">
			</div>
			<div class="form-group col-md-6">
				<label class="control-label">Total Caja con Cheque</label>
				<input type="text" name="totalc" id="totalc" class="form-control numeric summm">
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				<label class="control-label">Total Caja con Tarjeta</label>
					<input type="text" name="totalt" id="totalt" class="form-control numeric summm">
			</div>
			<div class="form-group col-md-6">
				<label class="control-label">Total Caja con Efectivo</label>
				<input type="text" name="totala" id="totala" class="form-control numeric summm">
				<input type="hidden" id="totals" value="<?php echo $resta; ?>">
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-12">
				<label class="control-label">Observaciones</label>
					<input type="text" name="observaciones" id="observaciones" class="form-control">
			</div>
		</div>
</div>
</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btn_corte">Guardar</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>	
<!--/modal-footer -->	
<script type="text/javascript">	
	$(".numeric").numeric({negative:false});
</script>
<?php
} //permiso del script
else {
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}	
}
function corte() {
	$id_usuario=$_SESSION["id_usuario"];
	$sistema = $_POST["sistema"];
	$efectivo = $_POST["efectivo"];
	$cheque = $_POST["cheque"];
	$tarjeta = $_POST["tarjeta"];
	$observaciones = $_POST["observaciones"];
	$fecha = date("Y-m-d");
	$hora = date("H:i:s");
	$total = $efectivo + $cheque + $tarjeta;
			
	$table = 'corte_caja';
    $form_data = array (
    	'fecha' => $fecha,
    	'hora' => $hora,
    	'total_sistema' => $sistema,
    	'total_corte' => $total,
    	'efectivo' => $efectivo,
    	'tarjeta' => $tarjeta,
    	'cheque' => $cheque,
    	'observaciones' => $observaciones,
    	'id_usuario' => $id_usuario
    );   	
       	    
  	$insert = _insert ($table, $form_data);
    if($insert)
    {
      $xdatos['typeinfo']='Success';
      $xdatos['msg']='Corte generado con exito ';
      $xdatos['process']='edited';
    } 
    else
    {
      $xdatos['typeinfo']='error';
      $xdatos['msg']='Corte no pudo ser generado';
    } 
  echo json_encode($xdatos);
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
			case 'change' :
				initial();
				break;
			case 'corte' :
				corte();
				break;
		}
	}
}

?>
