<?php
	include ("_core.php");
	// Page setup
	$title='Administrar Constancias';
	$_PAGE = array ();
	$_PAGE ['title'] =$title;
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/chosen/chosen.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
	
 	$sql="SELECT * FROM constancia";
	$result=_query($sql);
	$count=_num_rows($result);
	//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
	
	//mysql_query("SET NAMES 'utf8'");
?>
<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php  
				//permiso del script
				if ($links!='NOT' || $admin=='1' ){
				echo"<div class='ibox-title'>";
				$filename='constancias.php';
				$link=permission_usr($id_user,$filename);
				if ($link!='NOT' || $admin=='1' )
					echo "<a href='constancias.php' class='btn btn-primary' role='button' id='btn'><i class='fa fa-plus icon-large'></i> Agregar Constancia</a>";
				
				?>
				<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h4><?php echo $title; ?></h4>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover" id="editable">
							<thead>
								<tr>
									<th style="width: 15%;">N°</th>
									<th style="width: 45%;">Paciente</th>
									<th style="width: 20%;">Tipo</th>
									<th style="width: 20%;">Acción</th>
								</tr>
							</thead>
							<tbody> 
				<?php	
 					if ($count>0)
 					{
						for($i=0;$i<$count;$i++)
						{
							$row=_fetch_array($result);
							$paciente = buscar($row["id_paciente"]);
							$tipo = ucfirst($row["tipo"]);
							echo "<tr>";
							echo"<td>".($i+1)."</td>
								<td>".$paciente."</td>
								<td>".$tipo."</td>";
							echo"<td><div class=\"btn-group\">
								<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu dropdown-primary\">";
									$filename='ver_constancia.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
									echo "<li><a  href='ver_constancia1.php?id_constancia=".$row ['id_constancia']."' target='_blank'><i class=\"fa fa-print\"></i> Imprimir</a></li>";
								    $filename='borrar_constancia.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='borrar_constancia.php?id_constancia=".$row ['id_constancia']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
							echo "	</ul>
										</div>
										</td>
										</tr>";
						}
					}
		
				?>			
							</tbody>		
						</table>
						 <input type="hidden" name="autosave" id="autosave" value="false-0">	
					</section>   

					<!-- MODAL PARA DETALLE-->
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					<!-- MODAL PARA DETALLE-->

					<!-- MODAL PARA BORRAR-->
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-sm'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					<!-- MODAL PARA BORRAR-->

               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->  
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->			
</div>		
<?php    
include("footer.php");

echo" <script type='text/javascript' src='js/funciones/funciones_constancia.js'></script>"; 
} //permiso del script
else
{
	echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}	                         	     
?>
