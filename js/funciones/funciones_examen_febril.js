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
	var tifico_h = $("#tifico_h").val();
	var tifico_o = $("#tifico_o").val();
	var paratifico_a = $("#paratifico_a").val();
	var paratifico_b = $("#paratifico_b").val();
	var proteus = $("#proteus").val();
	var brocela_abortus = $("#brocela_abortus").val();
	var id_examen = $("#id_examen").val();
	var id_examen_paciente = $("#id_examen_paciente").val();
	var fecha_lectura = $("#fecha_lectura").val();
	var datos = "process=insert&tifico_o="+tifico_o+"&tifico_h="+tifico_h+"&id_examen_paciente="+id_examen_paciente;
		datos+="&paratifico_a="+paratifico_a+"&paratifico_b="+paratifico_b+"&proteus="+proteus;
		datos+="&brocela_abortus="+brocela_abortus+"&id_examen="+id_examen+"&fecha_lectura="+fecha_lectura;

	$.ajax({
		type: 'POST',
		url: 'examen_febril.php',
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