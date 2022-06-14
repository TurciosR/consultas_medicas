<?php
	include("_core.php");
	$_PAGE = array ();
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

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

	$sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
	$datos_moneda = _fetch_array($sql0);
	$simbolo = $datos_moneda["simbolo"];  
	$moneda = $datos_moneda["moneda"];

	$ini = MD($_POST["ini"]);
	$fin = MD($_POST["fin"]);
	
	$sql="SELECT * FROM factura WHERE fecha BETWEEN '$ini' AND '$fin'";

	$result=_query($sql);

	$table = '<table class="table table-striped table-bordered table-hover" id="editable">
							<thead>
								<tr>
									<th class="col-lg-1">Id</th>
									<th class="col-lg-1">Fecha</th>
									<th class="col-lg-3">Cliente</th>
									<th class="col-lg-3">Concepto</th>
									<th class="col-lg-2">Forma Pago</th>
									<th class="col-lg-1">Total</th>
									<th class="col-lg-1">Acción</th>
								</tr>
							</thead>
							<tbody> ';
						
						while($row=_fetch_array($result))
						{
							$total=$row['total'];
							$tipo_pago = $row["tipo_pago"];//.", ".$row["nombre_municipio"].", ".$row["nombre_departamento"];
							if($row["id_paciente"]>0 || $row["id_paciente"] == -999)
								$paciente = buscar($row["id_paciente"]);
							else
								$paciente = $row["cliente"];
							$concepto = $row["concepto"];
							$tipo = $row["tipo"];
							$fecha = ED($row["fecha"]); 
					        $table.= "<tr>
					        	<td>".$row["id_factura"]."</td>
								<td>".$fecha."</td>
								<td>".$paciente."</td>
								<td>".$concepto."</td>
								<td>".$tipo_pago."</td>";
								if($row["tipo"] =="Ingreso")
								{
									$table.= "<td>".$simbolo."".number_format($total,2,",",".")."</td>";
								}
								else
								{
									$table.= "<td  style='color: blue;'>".$simbolo."".number_format($total,2,",",".")."</td>";	
								}
								$table.= "<td><div class=\"btn-group\">
								<a href=\"#\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\"><i class=\"fa fa-user icon-white\"></i> Menu<span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu dropdown-primary\">";
									$filename = "borrar_factura.php";
									$link=permission_usr($id_user,$filename);
									if ($link!='NOT' || $admin=='1' )
										$table.= "<li><a data-toggle='modal' href='borrar_factura.php?id_factura=".$row ['id_factura']."&process=formDelete"."' data-target='#deleteModal' data-refresh='true'><i class=\"fa fa-eraser\"></i> Eliminar</a></li>";
									if($row['tipo']=="Ingreso")
									{
										$filename = "ver_factura.php";
										$link=permission_usr($id_user,$filename);
										if ($link!='NOT' || $admin=='1' )
											$table.= "<li><a data-toggle='modal' href='ver_factura.php?id_factura=".$row ['id_factura']."&process=formDelete"."' data-target='#viewModal' data-refresh='true'><i class=\"fa fa-eye\"></i> Ver Detalle</a></li>";		
									}						
							$table.= "	</ul>
										</div>
										</td>
										</tr>";
						}
						$table .="</tbody>		
						</table>";
	echo $table;
?>	
<script type="text/javascript">
	$('#editable').dataTable({
		"language":{
	    "sProcessing":     "Procesando...",
	    "sLengthMenu":     "Mostrar _MENU_ registros",
	    "sZeroRecords":    "No se encontraron resultados",
	    "sEmptyTable":     "Ningún dato disponible en esta tabla",
	    "sInfo":           "Del _START_ al _END_ de un total de _TOTAL_ registros",
	    "sInfoEmpty":      "Del 0 al 0 de un total de 0 registros",
	    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	    "sInfoPostFix":    "",
	    "sSearch":         "Buscar:",
	    "sUrl":            "",
	    "sInfoThousands":  ",",
	    "sLoadingRecords": "Cargando...",
	    "oPaginate": {
	        "sFirst":    "Primero",
	        "sLast":     "Último",
	        "sNext":     "Siguiente",
	        "sPrevious": "Anterior"
	    },
	    "oAria": {
	        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
	        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	    }
		},
		"pageLength": 25,
	});
</script>