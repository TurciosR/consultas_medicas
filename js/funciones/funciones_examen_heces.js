$(document).ready(function(){
	if($("#id_examen").val()>0)
	{
		$("#btn_guardar").hide();
	}
	else
	{
		$("#btn_edit").hide();
	}
	$("#btn_guardar").click(function()
	{
		agregar_examen_vario();
	});
	$("#btn_edit").click(function(){
		$(this).hide();
		$(".read").attr("readonly", false);
		$("#btn_guardar").show();
	});
});

function agregar_examen_vario()
{
	var color = $("#color").val();
	var consistencia = $("#consistencia").val();
	var mucus = $("#mucus").val();
	var restos_alimenticios = $("#restos_alimenticios").val();
	var leucocitos = $("#leucocitos").val();
	var hematies = $("#hematies").val();
	var protozoarios = $("#protozoarios").val();
	var metazoarios = $("#metazoarios").val();
	var id_examen = $("#id_examen").val();
	var flora = $("#flora").val();
	var otros = $("#otros").val();
	var id_examen_paciente = $("#id_examen_paciente").val();
	var fecha_lectura = $("#fecha_lectura").val();
	var datos = "process=insert&consistencia="+consistencia+"&color="+color+"&id_examen_paciente="+id_examen_paciente;
		datos+= "&mucus="+mucus+"&restos_alimenticios="+restos_alimenticios+"&hematies="+hematies;
		datos+= "&leucocitos="+leucocitos+"&protozoarios="+protozoarios+"&metazoarios="+metazoarios+"&id_examen="+id_examen;
		datos+= "&fecha_lectura="+fecha_lectura+"&flora="+flora+"&otros="+otros;

	$.ajax({
		type: 'POST',
		url: 'examen_heces.php',
		data: datos,
		dataType: 'JSON',
		success: function(datax)
		{
			display_notify(datax.typeinfo,datax.msg);
			if(datax.typeinfo=="Success")
			{		
				$("#btn_guardar").hide();
				$("#btn_edit").show();
				$(".read").attr("readonly", true);
				$("#id_examen").val(datax.id_examen);
			}	
		}
	})
}