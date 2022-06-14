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
		if($("#resultado").val()!="")
		{
			agregar_examen_prostatico();
		}
		else
		{
			display_notify("Error", "Por favor ingrese el resulatdo");
		}
	})
	$("#btn_edit").click(function(){
		$(this).hide();
		$(".read").attr("readOnly", false);
		$("#btn_guardar").show();
	});
})

function agregar_examen_prostatico()
{
	var resultado = $("#resultado").val();
	var id_examen = $("#id_examen").val();
	var fecha_lectura = $("#fecha_lectura").val();
	var id_examen_paciente = $("#id_examen_paciente").val();
	
	var datos = "process=insert&resultado="+resultado+"&id_examen_paciente="+id_examen_paciente+"&id_examen="+id_examen+"&fecha_lectura="+fecha_lectura;
	$.ajax({
		type: 'POST',
		url: 'examen_prostatico.php',
		data: datos,
		dataType: 'JSON',
		success: function(datax)
		{
			display_notify(datax.typeinfo,datax.msg);
			if(datax.typeinfo == "Success")
			{				
				$("#btn_guardar").hide();
				$("#btn_edit").show();
				$(".read").attr("readOnly", true);
				$("#id_examen").val(datax.id_examen);
			}
		}
	})
}