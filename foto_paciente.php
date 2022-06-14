<?php
include ("_core.php");
function initial(){
	$id_paciente = $_REQUEST['id_paciente'];
	$id_cita = $_REQUEST['id_cita'];
	$nombre = buscar($id_paciente);
	$sql="SELECT * FROM img_paciente WHERE id_paciente='$id_paciente' AND id_cita='$id_cita'";
	$result = _query( $sql );
	$count = _num_rows( $result );
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title">Fotos de <?php echo $nombre; ?></h4>
</div>
<div class="modal-body">
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row" id="row1">
			<div class="col-lg-12">
			<div class="widget-content">
			<form id="form" enctype="multipart/form-data" role="form">
                <div class="form-group">
					<div class="col-md-12">
						<!--Utilizando jasny solo para el control del input file-->
						<input type="file" name="foto" id="foto" class="file" accept=".png,.jpg,.doc,.docx,.odt,.xls,.xlsx,.ods,.pdf" data-preview-file-type="image">						
						<!--Fin Utilizando jasny solo para el control del input file-->
					<!-- HIDDEN INPUT-->
						<input type="hidden" name="id_cita" id="id_cita" value="<?php echo $id_cita;?>">
						<input type="hidden" name="id_paciente" id="id_paciente" value="<?php echo $id_paciente;?>">
						<input type="hidden" name="process" id="process" value="upload_s">
					</div>
					<div class="col-md-12"><br>
						<label>Descripción</label>
						<input type="text" name="descripcion" class="form-control" id="descripcion">
					</div> 
				</div>
				<div class="form-group">
					<div class="col-md-12">
					<br>
					<a class="btn btn-primary pull-right" id="btn_agregar_arc"><i class="fa fa-upload" aria-hidden="true"></i> Subir</a>
					</div>
				</div>
			</form>
	</div>
	<div class="col-lg-12">	
		<div class="form-group">
		<div class="col-md-6"></div>	
		</div>			
		<table class="table table-bordered table-checkable ">
			<thead>
				<tr>
					<th style="width:20%;">Fecha</th>
					<th style="width:70%;">Archivo</th>
					<th style="width:10%;">Accion</th>
				</tr>
			</thead>
			<tbody  id="table">
			<?php 
				while($row = _fetch_array($result))
					{
						echo "<tr id='fl$row[id_img]'>
							  <td>".ED($row["fecha"])."</td>
							  <td><a target='_blank' href='$row[url]'>$row[descripcion]</a></td>
							  <td><button id='$row[id_img]'  class='btn eliminar'><i class='fa fa-trash'></i></button></td>
							  </tr>";
					}
			?>
			</tbody>
		</table>
	</div>
	</div>
</div>
</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
<!--/modal-footer -->
<script type="text/javascript">
	$("#foto").fileinput({'showUpload':true, 'previewFileType':'image'});
</script>
<?php
}

function upload_s(){
require_once 'class.upload.php';
$foo = new \Verot\Upload\Upload($_FILES['foto'],'es_ES'); 
if ($foo->uploaded) {
	$pref = uniqid()."_";
	$foo->file_force_extension = false;
	$foo->no_script = false;
	$foo->file_name_body_pre = $pref;
   // save uploaded image with no changes
   $foo->Process('files/');
   if ($foo->processed)
   {
	   	$id_paciente = $_POST["id_paciente"];
	   	$id_cita = $_POST["id_cita"];
	   	$descripcion = $_POST["descripcion"];
	   	$fecha = date("Y-m-d");
	   	$archivo = $descripcion; //$_FILES["foto"]["name"];
        $cuerpo=quitar_tildes($foo->file_src_name_body);
	   	$url = 'files/'.$pref.$cuerpo.".".$foo->file_src_name_ext;
	  	$table = 'img_paciente';
		$form_data = array (
	    'id_paciente' => $id_paciente,
	    'id_cita' => $id_cita,
	    'fecha' => $fecha,
	    'descripcion' => $archivo,
	    'url' => $url
	    );
		$insertar = _insert($table,$form_data);
		if($insertar)
		{
			$sql = _query("SELECT id_img FROM img_paciente WHERE id_paciente = '$id_paciente' AND id_cita = '$id_cita' AND descripcion = '$archivo' AND url = '$url'");
			$arc = _fetch_array($sql);
			$id_img = $arc["id_img"];
			$xdatos ['typeinfo'] = 'Success';
			$xdatos ['id_img'] = $id_img;
			$xdatos ['nombre'] = $archivo;
			$xdatos ['fecha'] = ED($fecha);
			$xdatos ['url'] = $url;
			$xdatos ['msg'] = "El archivo se subio con exito";
		}
		else
		{
		 $xdatos ['typeinfo'] = 'Error';
	     $xdatos ['msg'] = "El archivo no se guardo en la base de datos";	
		}
   	}
   	else 
   	{
	    $xdatos ['typeinfo'] = 'Error';
	    $xdatos ['msg'] = "El archivo no pudo ser subido ";
	    //$xdatos ['msg'] = $foo->error;
   	}
}
else
{
	$xdatos ['typeinfo'] = 'Error';
	$xdatos ['msg'] = "El archivo no pudo ser subido ";
}

echo json_encode($xdatos);
}


function deleted() {
	$id_img = $_POST ['id_img'];
	$sqlfile = _query("SELECT url FROM img_paciente WHERE id_img = '$id_img'");
	$resultfile=_fetch_array($sqlfile);
	$file_to_delete = $resultfile["url"];
	if($file_to_delete != "")
	{
		if(unlink($file_to_delete))
		{
			$table = 'img_paciente';
			$where_clause = "id_img='" . $id_img . "'";
			$delete = _delete ( $table, $where_clause );
			if ($delete) {
				$xdatos ['typeinfo'] = 'Success';
			} else {
				$xdatos ['typeinfo'] = 'Error';
			}
		}
		else
		{
			$xdatos ['typeinfo'] = 'Error';	
		}	
	}
	else
	{
		$xdatos ['typeinfo'] = 'Error';
	}
	echo json_encode ( $xdatos );
}
if (! isset ( $_REQUEST ['process'] )) {
	initial();
} else {
	if (isset ( $_REQUEST ['process'] )) {
		switch ($_REQUEST ['process']) {
			case 'upload_s' :
				upload_s();
				break;
			case 'deleted' :
				deleted();
				break;
		}
	}
}

?>
