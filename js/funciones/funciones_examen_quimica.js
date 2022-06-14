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
		senddata();
	});
	$("#btn_edit").click(function(){
		$(this).hide();
		$(".read").attr("readonly", false);
		$("#btn_guardar").show();
	});
});

function senddata()
{
	var id_examen=$("#id_examen").val();
	var id_examen_paciente=$("#id_examen_paciente").val();
    var glucosa_azar=$("#glucosa_azar").val();
    var glucosa_prandial=$("#glucosa_prandial").val();
    var colesterol_total=$("#colesterol_total").val();
    var colesterol_hdl=$("#colesterol_hdl").val();
    var colesterol_ldl=$("#colesterol_ldl").val();
    var trigliceridos=$("#trigliceridos").val();
    var lipidos_totales=$("#lipidos_totales").val();
    var creatinina=$("#creatinina").val();
    var acido_urico=$("#acido_urico").val();
    var urea=$("#urea").val();
    var nitrogeno_ureico=$("#nitrogeno_ureico").val();
    var sodio=$("#sodio").val();
    var potasio=$("#potasio").val();
    var cloro=$("#cloro").val();
    var proteinas_totales=$("#proteinas_totales").val();
    var albumina=$("#albumina").val();
    var globulina=$("#globulina").val();
    var relacion_ag=$("#relacion_ag").val();
    var amilasa=$("#amilasa").val();
    var bilirrubina_total=$("#bilirrubina_total").val();
    var bilirrubina_directa=$("#bilirrubina_directa").val();
    var bilirrubina_indirecta=$("#bilirrubina_indirecta").val();
    var calcio=$("#calcio").val();
    var fosforo=$("#fosforo").val();
    var proteina_reactiva=$("#proteina_reactiva").val();
    var tsh=$("#tsh").val();
    var t3_libre=$("#t3_libre").val();
    var t4_libre=$("#t4_libre").val();
    var ldh=$("#ldh").val();
    var hda1=$("#hda1").val();
    var fraccion=$("#fraccion").val();
    var transaminasa_go=$("#transaminasa_go").val();
    var transaminasa_gp=$("#transaminasa_gp").val();
    var observacion=$("#observacion").val();
    var reporta=$("#reporta").val();
    var fecha_lectura=$("#fecha_lectura").val();

    var datos = "process=insert&id_examen_paciente="+id_examen_paciente+"&id_examen="+id_examen+"&glucosa_azar="+glucosa_azar;
    	datos += "&glucosa_prandial="+glucosa_prandial+"&colesterol_total="+colesterol_total+"&colesterol_ldl="+colesterol_ldl;
    	datos += "&colesterol_hdl="+colesterol_hdl+"&trigliceridos="+trigliceridos+"&lipidos_totales="+lipidos_totales;
    	datos += "&creatinina="+creatinina+"&acido_urico="+acido_urico+"&urea="+urea;
    	datos += "&nitrogeno_ureico="+nitrogeno_ureico+"&sodio="+sodio+"&potasio="+potasio+"&cloro="+cloro+"&proteinas_totales="+proteinas_totales;
    	datos += "&albumina="+albumina+"&globulina="+globulina+"&relacion_ag="+relacion_ag+"&amilasa="+amilasa+"&bilirrubina_total="+bilirrubina_total;
    	datos += "&bilirrubina_directa="+bilirrubina_directa+"&bilirrubina_indirecta="+bilirrubina_indirecta+"&calcio="+calcio+"&fosforo="+fosforo;
    	datos += "&proteina_reactiva="+proteina_reactiva+"&tsh="+tsh+"&t3_libre="+t3_libre+"&t4_libre="+t4_libre;
    	datos += "&ldh="+ldh+"&hda1="+hda1+"&fraccion="+fraccion+"&transaminasa_go="+transaminasa_go+"&transaminasa_gp="+transaminasa_gp;
    	datos += "&observacion="+observacion+"&reporta="+reporta+"&fecha_lectura="+fecha_lectura;
	$.ajax({
		type: "POST",
		url: "examen_quimica_sanguinea.php",
		data: datos,
		dataType: "JSON",
		success: function(datax)
		{
			display_notify(datax.typeinfo, datax.msg);
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