var editor = "";
$(document).ready(function()
{
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
	editor = CKEDITOR.replace('resultado');
	$("#btn_edit").click(function(){
		$(this).hide();
		$(".read").attr("readonly", false);
		CKEDITOR.instances['resultado'].setReadOnly(false);
		$("#btn_guardar").show();
	});
});

function agregar_examen_vario()
{
	var process = $("#process").val();
	var url = "";
	if(process == "ultra")
	{
		url ="examen_ultrasonografia.php";
		var muestra = "";
		var examen ="";
	}
	else if(process == "rx")
	{
		url = "examen_radiografia.php";
		var muestra = "";
		var examen ="";
	}
	else if(process =="tac")
	{
		url = "examen_tac.php";
		var muestra = "";
		var examen ="";
	}
	else if(process =="vario")
	{
		url ="examen_vario.php";
		var muestra = $("#muestra").val();
		var examen = $("#examen").val();
	}
	var resultado = editor.getData();
	var fecha = $("#fecha_lectura").val();
	var id_examen = $("#id_examen").val();
	var id_examen_paciente = $("#id_examen_paciente").val();
	var datos = "process=insert&resultado="+resultado+"&id_examen_paciente="+id_examen_paciente+"&id_examen="+id_examen;
		datos+= "&muestra="+muestra+"&examen="+examen+"&fecha_lectura="+fecha;

	$.ajax({
		type: 'POST',
		url: url,
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
				CKEDITOR.instances['resultado'].setReadOnly(true);
				$("#id_examen").val(datax.id_examen);
			}	
		}
	})
}