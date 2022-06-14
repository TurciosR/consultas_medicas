$(document).ready(function() {    
   if($("#process").val()!="insert")
   {
   		buscar();
   }
	$("#buscar").click(function(){
		buscar();
	});
	$("#formulario_salida").validate(
	{		
	    rules:
	    {
            fecha:
            {  
                required: true,           
            },
			responsable:
			{  
                required: true,           
            },
            monto:
			{  
                required: true,           
            },
            concepto:
			{  
                required: true,           
            },
        },
        messages:
        {
			fecha: "Por favor seleccione la fecha",
			responsable: "Por favor ingrese el nombre del responsable",
			monto: "Por favor ingrese el monto de la salida",
			concepto: "Por favor ingrese el concepto",
		},
		highlight: function(element)
		{
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element)
		{
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		},
		submitHandler: function (form)
		{ 
    		salida_caja();
		}
    });
});
function buscar()
{
	var ini = $("#desde").val();
    var fin = $("#hasta").val();
    var dataString="ini="+ini+"&fin="+fin;
    $.ajax({
		type : "POST",
		url : "admin_egreso_dt.php",
		data : dataString,
		success : function(datax) {
			$("#refill").html(datax);
		}
	});
}
function reload1()
{
	location.href = "admin_egreso.php";
}
function salida_caja()
{
	var fecha = $("#fecha").val();
	var responsable = $("#responsable").val();
	var monto = $("#monto").val();
	var concepto = $("#concepto").val();

	var dataString="process=insert&fecha="+fecha+"&responsable="+responsable+"&monto="+monto+"&concepto="+concepto;
	$.ajax({
		type:'POST',
		url:'salida_caja.php',
		data: dataString,
		dataType: 'JSON',
		success: function(datax)
		{
			display_notify(datax.typeinfo,datax.msg);
			if(datax.typeinfo=="Success")
			{
				setInterval("reload1();",1500);
			}
		}
	});
}
function deleted()
{
	var id_egreso = $('#id_egreso').val();
	var dataString = 'process=deleted' + '&id_egreso=' + id_egreso;
	$.ajax({
		type : "POST",
		url : "borrar_egreso.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo=="Success")
			{
				setInterval("location.reload();", 1000);
				$('#deleteModal').hide(); 
			}
		}
	});
}
$(function ()
{
	//binding event click for button in modal form
	$(document).on("click", "#btnDelete", function(event) {
		deleted();
	});
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});	
});	