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
	var muestra = $("#muestra").val();
	var area_corporal = $("#area_corporal").val();
	var microorganismo_aislado = $("#microorganismo_aislado").val();
	var conteo_colonia = $("#conteo_colonia").val();
	var sensible = "";
	var intermedio = "";
	var resistente = "";
	$("#sir tr").each(function(){
		sensible += $(this).find(".sensible").val()+"|"; 
		intermedio += $(this).find(".intermedio").val()+"|"; 
		resistente += $(this).find(".resistente").val()+"|"; 
	});

	var id_examen = $("#id_examen").val();
	var id_examen_paciente = $("#id_examen_paciente").val();
	var fecha_lectura = $("#fecha_lectura").val();
	var datos = "process=insert&area_corporal="+area_corporal+"&muestra="+muestra+"&id_examen_paciente="+id_examen_paciente;
		datos+="&microorganismo_aislado="+microorganismo_aislado+"&conteo_colonia="+conteo_colonia+"&sensible="+sensible;
		datos+="&intermedio="+intermedio+"&resistente="+resistente+"&id_examen="+id_examen+"&fecha_lectura="+fecha_lectura;

	$.ajax({
		type: 'POST',
		url: 'examen_bacteriologia.php',
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