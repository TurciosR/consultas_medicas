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
	var id_examen_paciente = $("#id_examen_paciente").val();
    var id_examen = $("#id_examen").val();
    var globulos_rojos = $("#globulos_rojos").val();
    var hemoglobina = $("#hemoglobina").val();
    var hematocrito = $("#hematocrito").val();
    var vcm = $("#vcm").val();
    var hcm = $("#hcm").val();
    var chcm = $("#chcm").val();
    var globulos_blancos = $("#globulos_blancos").val();
    var n_segmentados = $("#n_segmentados").val();
    var n_banda = $("#n_banda").val();
    var linfocitos = $("#linfocitos").val();
    var monocitos = $("#monocitos").val();
    var eosinofilos = $("#eosinofilos").val();
    var basofilos = $("#basofilos").val();
    var plaquetas = $("#plaquetas").val();
    var tiempo_protobina = $("#tiempo_protobina").val();
    var inr = $("#inr").val();
    var isi = $("#isi").val();
    var tiempo_tromboplastima = $("#tiempo_tromboplastima").val();
    var eritrosedimentacion = $("#eritrosedimentacion").val();
    var observacion = $("#observacion").val();
    var reporta = $("#reporta").val();
    var fecha_lectura = $("#fecha_lectura").val();
    var datos = "process=insert&id_examen_paciente="+id_examen_paciente+"&id_examen="+id_examen+"&globulos_rojos="+globulos_rojos;
    	datos += "&hemoglobina="+hemoglobina+"&hematocrito="+hematocrito+"&vcm="+vcm+"&hcm="+hcm+"&chcm="+chcm+"&globulos_blancos="+globulos_blancos;
    	datos += "&n_segmentados="+n_segmentados+"&n_banda="+n_banda+"&linfocitos="+linfocitos+"&monocitos="+monocitos+"&eosinofilos="+eosinofilos;
    	datos += "&basofilos="+basofilos+"&plaquetas="+plaquetas+"&tiempo_protobina="+tiempo_protobina+"&inr="+inr+"&isi="+isi;
    	datos += "&tiempo_tromboplastima="+tiempo_tromboplastima+"&eritrosedimentacion="+eritrosedimentacion+"&observacion="+observacion+"&reporta="+reporta+"&fecha_lectura="+fecha_lectura;
	
	$.ajax({
		type: 'POST',
		url: 'examen_hematologia.php',
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