<?php
include ("_core.php");
include ('num2letras.php');
function initial(){
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	date_default_timezone_set('America/El_Salvador');
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

	if(true)
	{
		?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
			<h4 class="modal-title">Agregar Servicio</h4>
		</div>
		<div class="modal-body">
			<!--div class="wrapper wrapper-content  animated fadeInRight"-->
			<div class="row" id="row1">
				<!--div class="col-lg-12"-->
				<?php

				?>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group has-info single-line">
							<label>Servicio</label>
							<select class="idServicio" style="width: 100%" id="idServicio" name="idServicio">
								<?php
								$sql=_query("SELECT * FROM ".EXTERNAL.".servicios");

								while($row=_fetch_array($sql))
								{
									echo"<option value='$row[id_servicio]'>$row[servicio]</option>";
								}
								 ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group has-info single-line">
							<label>Monto </label> <input type='text'  class='form-control numeric montoServicio' id='montoServicio' name='montoServicio'>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="modal-footer">
			<button type="button"  class="btn btn-primary btnServicio" id="btnServicio">Agregar</button>
			<button type="button" class="btn btn-default closeServicio" data-dismiss="modal">Cerrar</button>
		</div>
		<script type="text/javascript">
		$(".numeric").numeric(
			{
				negative:false,
			}
		);
		$(".idServicio").select2();
		
	</script>
	<!--/modal-footer -->

	<?php

}
else
{
	echo "<div></div><br><br><div class='alert alert-warning text-center'>No se ha encontrado una apertura vigente.</div>";
}
}


if (! isset ( $_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
			case 'formDelete' :
				initial();
				break;
			}
		}
	}

	?>
