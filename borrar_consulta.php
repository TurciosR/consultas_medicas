<?php
include ("_core.php");
function initial(){
	$id = $_REQUEST ['id'];
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$filename = "borrar_consulta.php";
	$links=permission_usr($id_user,$filename);
		
?>
<div class="modal-header">
	
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Borrar Consulta</h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<?php
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){  
			?>
			<div class="col-lg-12">
				<div class="alert alert-warning">
					Esta seguro que desea eliminar esta consulta?
				</div>
			</div>
		</div>
			<?php 
			echo "<input type='hidden' nombre='id_cita' id='id_cita' value='$id'>";
			?>
		</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary" id="btnDelete">Eliminar</button>
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

</div>
<!--/modal-footer -->

<?php
} //permiso del script
else{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
	}
}
function deleted() 
{
	$id = $_POST ['id'];
	$table = 'reserva_cita';
	$where_clause = "id='" . $id . "'";
	$delete = _delete ( $table, $where_clause );
	if ($delete) {
		$xdatos ['typeinfo'] = 'Success';
		$xdatos ['msg'] = 'Consulta eliminada correctamente!';
	} else {
		$xdatos ['typeinfo'] = 'Error';
		$xdatos ['msg'] = 'Consulta no pudo ser eliminada';
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
