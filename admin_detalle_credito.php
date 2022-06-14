<?php
	include ("_core.php");
	// Page setup
function initial()
{	
	$_PAGE = array ();
	$title = 'Detalle de Pagos';
	$_PAGE ['title'] = $title;
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
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';
	include_once "header.php";
	include_once "main_menu.php";
	$id_credito = $_REQUEST["id_credito"];

 	$sql="SELECT * FROM abono_credito WHERE id_credito = '$id_credito'";
	$result=_query($sql);
	$count=_num_rows($result);

	$sql1="SELECT * FROM credito WHERE id_credito='$id_credito'";
	$result = _query( $sql);
	$result1 = _query( $sql1);
	$row1=_fetch_array($result1);


    $sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
    $datos_moneda = _fetch_array($sql0);
    $simbolo = $datos_moneda["simbolo"];  
    $moneda = $datos_moneda["moneda"]; 

		//permiso del script
	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);
?>

<div class="wrapper wrapper-content  animated fadeInRight">
	<div class="row" id="row1">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<?php  
						//permiso del script
				if ($links!='NOT' || $admin=='1' ){
				echo"<div class='ibox-title'></div>";
				?>
					<div class="ibox-content">
					<!--load datables estructure html-->
					<header>
						<h3 style="color:#194160;"><i class="fa fa-list"></i> <b><?php echo $title; ?></b><b class="pull-right"><?php echo $row1["cliente"];?></b></h3><br>
					</header>
					<section>
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>N°</th>
									<th>Fecha</th>
									<th>Monto</th>
									<th>Observaciones</th>
									<th>Cobrado por</th>
									<th>Pagado</th>
									<th id="accion_credito">Acciones</th>
								</tr>
							</thead>
							<tbody> 
				<?php	
 					if ($count>0){
 						$j = 1;
						for($i=0;$i<$count;$i++){
							$row=_fetch_array($result);
							$fecha=ED($row["fecha"]);
							$monto=number_format($row["monto"],2,".",",");
							if($row["pagado"])
							{
								$pagado = "<label class='badge bg-green'>SI</label>";
								$boton = "";
								$user = buscar_user($row["id_usuario"]);
							}
							else
							{
								$pagado = "<label class='badge badge-danger'>NO</label>";
								$user = "";
							}
							$estado  = $row["observaciones"];
							echo "<tr>";
							echo"<td>".$j."</td>
								<td>".$fecha."</td>
								<td>".$simbolo."".$monto."</td>
								<td>".$estado."</td>
								<td>".$user."</td>
								<td>".$pagado."</td>
								<td>";
								if(!$row["pagado"])
								{
									echo "<div class=\"btn-group\">
									<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
									<ul class=\"dropdown-menu dropdown-primary\">";	
									$filename='abonar_credito.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo"<li><a data-toggle='modal' href=\"abonar_credito.php?id_abono=".$row['id_abono']."&acc=credito\" data-target='#viewModal' data-refresh='true'><i class=\"fa fa-money\"></i> Cobrar</a></li>";
									$filename='editar_detalle_credito.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo"<li><a data-toggle='modal' href=\"editar_detalle_credito.php?id_abono=".$row['id_abono']."\" data-target='#viewModal' data-refresh='true'><i class=\"fa fa-pencil\"></i> Editar</a></li>";
								    $filename='borrar_detalle_credito.php';
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										echo "<li><a data-toggle='modal' href='borrar_detalle_credito.php?id_abono=".$row ['id_abono']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
									echo "</ul>
										</div>";
									}
									else
									{
										echo $boton;
									}
								echo" </td>
							</tr>";
							$j+=1;
						}
						echo "<tr><td colspan='7' class='text-center'><a id='append2'><i class='fa fa-plus'></i> Agregar Cuota</a></td></tr>";
					}
		
				?>			
							</tbody>		
						</table>
						<input type='hidden' name='id_credito' id='id_credito' value='<?php echo $id_credito; ?>'>
				
						 <input type="hidden" name="autosave" id="autosave" value="false-0">	
					</section>   
					<!--Show Modal Popups View & Delete -->
					<div class='modal fade' id='viewModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					<div class='modal fade' id='deleteModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content modal-md'></div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
               	</div><!--div class='ibox-content'-->
       		</div><!--<div class='ibox float-e-margins' -->
		</div> <!--div class='col-lg-12'-->
	</div> <!--div class='row'-->  
</div><!--div class='wrapper wrapper-content  animated fadeInRight'-->			
<?php    
include("footer.php");
echo" <script type='text/javascript' src='js/funciones/funciones_detalle_credito.js'></script>"; 
} //permiso del script
else 
{
		echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
}
}
function append2()
{
	$id_credito = $_POST["id_credito"];
	_begin();
	$query = _query("SELECT  * FROM  credito WHERE id_credito='$id_credito'");
    $row=_fetch_array($query);
    $frecuencia=$row["frecuencia"];
	$monto=$row["cuota"];
	$fecha_ult = _fetch_array(_query("SELECT max(fecha) as fecha FROM abono_credito WHERE id_credito ='$id_credito'"))['fecha'];
	$fecha = sumar_dias(ED($fecha_ult),$frecuencia);
	$table = "abono_credito";
	$form_data = array(
		'id_credito' => $id_credito,
		'fecha' => $fecha,
		'monto' => $monto
		);
	$insert = _insert($table, $form_data);
	if($insert)
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
			$xdata["typeinfo"]="Success";
			$xdata["msg"]="Cuota ingresada con exito!!!";
		}
		else
		{
			_rollback();
			$$xdata["typeinfo"]="Error";
			$xdata["msg"]="Cuota no pudo ser ingresada!!!";
		}
	}
	else
	{
		_rollback();
		$xdata["typeinfo"]="Error";
		$xdata["msg"]="Cuota no pudo ser ingresada!!!";
	}
	echo json_encode($xdata);
}
if(!isset($_POST['process']))
{
    initial(); 
}
else
{
    if(isset($_POST['process']))
    {	
        switch ($_POST['process'])
        {
            case 'append2':
                append2();
                break;
        } 
    }			
}		                         	     
?>
