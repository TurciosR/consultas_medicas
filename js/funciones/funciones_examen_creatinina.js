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
	var volumen_orina = $("#volumen_orina").val();
	var creatinina_orina = $("#creatinina_orina").val();
	var creatinina_sangre = $("#creatinina_sangre").val();
	var depuracion_creatinina = $("#depuracion_creatinina").val();
	var proteinas_orina = $("#proteinas_orina").val();

	var id_examen = $("#id_examen").val();
	var id_examen_paciente = $("#id_examen_paciente").val();
	var fecha_lectura = $("#fecha_lectura").val();
	var datos = "process=insert&creatinina_orina="+creatinina_orina+"&volumen_orina="+volumen_orina+"&id_examen_paciente="+id_examen_paciente;
		datos+="&creatinina_sangre="+creatinina_sangre+"&depuracion_creatinina="+depuracion_creatinina+"&proteinas_orina="+proteinas_orina+"&fecha_lectura="+fecha_lectura;
		datos+="&id_examen="+id_examen;

	$.ajax({
		type: 'POST',
		url: 'examen_creatinina.php',
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