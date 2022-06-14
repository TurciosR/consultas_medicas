<?php
	include("_core.php");

	$id_user=$_SESSION["id_usuario"];
	$admin=$_SESSION["admin"];
	
	$ini = MD($_POST["ini"]);
	
	$sql="SELECT count(id_paciente) as numero, tipo, sms, ws FROM notificaciones_varias, empresa WHERE fecha='$ini' AND id_empresa='1' GROUP BY tipo";
	$result=_query($sql);
	$num = _num_rows($result);
	$table = "";
	$i=1;
	if($num>0)
	{
		while($row = _fetch_array($result))
		{
				$disponible ="Ilimitado";
				if($row["tipo"]=="Teléfono")
				{
					$disponible = $row["sms"];
				}
				if($row["tipo"]=="Whatsapp")
				{
					$disponible = $row["ws"];
				}
			$table.= "<tr>";
			$table.="<td>".$i."</td>
				<td>".$row["tipo"]."</td>
				<td>".$row["numero"]."</td>
				<td class='text-center'>".$disponible."</td>
				";
				
			$table.="<td class='text-center'>";
					$filename='admin_notificacionv.php';
					$link=permission_usr($id_user,$filename);
					if ($link!='NOT' || $admin=='1' )
						$table.= "<a href='enviar_notificacionv.php?tipo=".$row["tipo"]."&fecha=".$ini."' style='font-size:14px;'><i class=\"fa fa-send\"></i> Enviar</a>";
			$table.="</td>
				</tr>";
			$i++;
		}
	}
	else
	{
		$table .= "<tr><td colspan='5' class='text-center'>Sin notificaciones por enviar</td></tr>";
	}
	echo $table;
?>	