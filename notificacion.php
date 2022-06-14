<?php
	include("_core.php");
	$_PAGE = array ();
	$_PAGE ['links'] = null;
	$_PAGE ['links'] .= '<link href="css/bootstrap.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="font-awesome/css/font-awesome.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/iCheck/custom.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/select2/select2.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/animate.css" rel="stylesheet">';
	$_PAGE ['links'] .= '<link href="css/style.css" rel="stylesheet">';

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	$uri = $_SERVER['SCRIPT_NAME'];
	$filename=get_name_script($uri);
	$links=permission_usr($id_user,$filename);

	$tipo = $_POST["tipo"];
 
	if($tipo == "Teléfono")
	{
		$sql = _query("SELECT id_paciente, tel1 as contacto FROM paciente WHERE notificaciones='$tipo'");	
	}
	else
	{
		$sql = _query("SELECT id_paciente, email as contacto FROM paciente WHERE notificaciones='$tipo'");		
	}
	$i=1;
	$xdata["table"] = "";
	while($row = _fetch_array($sql))
	{
		$xdata["table"] .= "<tr>
				<td>".$i."<input type='hidden' name='id_paciente' id='id_paciente' value='".$row["id_paciente"]."'></td>
				<td class='nombre'>".buscar($row["id_paciente"])."</td>
				<td class='contacto'>".$row["contacto"]."</td>
				<td class='text-center'>
				<p><div class='checkbox i-checks includes'><label><input id='myCheckboxes' name='myCheckboxes' type='checkbox' value='".$i."'></label></div></p>
				</td>
			 </tr>"; 
		$i++;	 
	}
	echo json_encode($xdata);
?>	
