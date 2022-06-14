var n = 0;
$(document).ready(function() {    
	$(".select").select2();
   	if($("#process").val()!="insert")
   	{
   		buscar();
   	}
	$("#buscar").click(function()
	{
		buscar();
	});
	$('#all').on('ifChecked', function(event)
	{
		if($("#tipo").val() == "Teléfono" || $("#tipo").val() == "Whatsapp")
		{
			var total = parseInt($("#datos tr").length);
			if(sms(total))
			{
				$('.includes').iCheck('check');
			}
			else
			{
				display_notify("Error", "No cuenta con suficientes Mensajes disponibles");
				setTimeout(function(){$('#chk').iCheck('uncheck');},0);
				
			}
		}
		else
		{
			$('.includes').iCheck('check');
		}
		//$('#alla').val("1");
	});
	$('#all').on('ifUnchecked', function(event)
	{
		
		$('.includes').iCheck('uncheck');
		n = 0;
		//$('#alla').val("0");
	});
	$("#tipo_m").change(function()
	{
		if($(this).val()!="")
		{
			//$("#tipo").val($(this).val());
			var dataString = 'process=buscar&tipo='+$(this).val();
			$.ajax({
				type : "POST",
				url : "notificacion.php",
				data : dataString,
				dataType: 'JSON',
				success : function(datax)
				{
					$("#datos").html(datax.table);
					$('.i-checks').iCheck({
						checkboxClass: 'icheckbox_square-green',
				        radioClass: 'iradio_square-green',
				     });
				}
			});
		}
		else
		{
			$("#datos").html('');
		}
	});
   
});
function buscar()
{
	var ini = $("#desde").val();
    var dataString="ini="+ini;
    $.ajax({
		type : "POST",
		url : "admin_notificacionv_dt.php",
		data : dataString,
		success : function(datax) {
			$("#refill").html(datax);
		}
	});
}
function reload1()
{
	location.href = 'admin_notificacionv.php'
}
function generar()
{
	if($("#fecha").val() != "")
	{
		if($("#mensaje").val() != "")
		{
			var mensaje = $("#mensaje").val();
			var tipo = $("#tipo_m").val();
			var fecha = $("#fecha").val();
			var datos = "";
			var cuantos = 0;
			$("input[name='myCheckboxes']:checked").each(function(index)
			{
				var tr = $(this).parents("tr");
		        var id_p = tr.find("#id_paciente").val();
		        var contacto = tr.children(".contacto").text();
		        tr.remove();
		        datos += id_p+","+contacto+"|";
		        cuantos ++;
		    });
		    if(cuantos>0)
		    {
			    $.ajax({
					type : "POST",
					url : "generar_notificacionv.php",
					data : "process=generar&mensaje="+mensaje+"&tipo="+tipo+"&datos="+datos+"&cuantos="+cuantos+"&fecha="+fecha,
					dataType:'JSON',
					success : function(datax)
					{
						display_notify(datax.typeinfo, datax.msg);
						if(datax.typeinfo=="Success")
						{
							//setInterval("reload1();",1000);
							//console.log("Guradado");
						}
					}
				});
			}
			else
			{
				display_notify("Warning","No ha seleccionado destinatarios para el mensaje");
			}
		}
		else
		{
			display_notify("Error", "Por favor Ingrese el texto del mensaje");
		}
	}
	else
	{
		display_notify("Error", "Por favor seleccione una fecha");
	}
}
function sendm()
{
	var tipo = $("#tipo").val();
	var msj = "";
	var msg = "";
	var numero = "";
	var error  = 0;

	$("input[name='myCheckboxes']:checked").each(function(index)
	{
		var id = $(this).val();
        var tr = $(this).parents("tr");
        var nombre = tr.children(".nombre").text();
        var contacto = tr.children(".contacto").text();
        var mensaje = tr.children(".mensaje").text();
        if(tipo != "Correo")
        	numero = contacto.replace("-","");
        else
        	numero = contacto;
        if(mensaje.includes("{paciente}"))
        	msj = mensaje.replace("{paciente}", nombre);
        else
        	msj = mensaje;
        msg = msj;	

        if(tipo=='Teléfono')
        {
            var dataString ="a87ff679a2f3e71d9181a67b7542122c159f6f883545ebaeda0d4df9c7ce22ad=1&numero="+numero+"&sms="+msg;
            $.ajax({
				type : "GET",
				url : "http://senic.es/api.php",
				data : dataString,
				success : function(datax)
				{
					var respuesta = datax.replace("<br>","");
					if(respuesta == "OK: insertado")
					{
						console.log("Enviado");
						$.ajax({
						type : "POST",
						url : "enviar_notificacionv.php",
						data : "process=verificar&id="+id+"&tipo="+tipo,
						dataType:'JSON',
						success : function(datax)
						{
							if(datax.typeinfo=="Success")
							{
								//setInterval("reload2();",1000);
								console.log("Guradado");
								$("#disp").html("Mensajes disponibles "+datax.sms);
								$("#sms").val(datax.sms);
								discart(datax.id);
							}
							else
							{
								display_notify(datax.typeinfo, datax.msg);
							}
						}
						});
					}
					else
					{
						if(error  < 1)
						{
							error = 1;
							display_notify("Warning","Sin conexion, intente mas tarde");
						} 
					}
				}
			});
			//console.log(numero);      
			//console.log(msg);      
		}
		else if(tipo=='Whatsapp')
        {
        	numero = "503"+numero; 
            var dataString = 0;
            $.ajax({
				type : "POST",
				contentType: "text/json; charset=UTF-8",
				//url : "http://apps-opensolutions.com:8000",
				//url : "http://45.33.62.62:8000",
				url : "http://66.175.222.232:8000",
				data : JSON.stringify({'telefono':numero, 'msg':msg}),
				success : function(datax)
				{
					if(datax == "OK")
					{
						console.log("Enviado");
						$.ajax({
							type : "POST",
							url : "enviar_notificacionv.php",
							data : "process=verificar&id="+id+"&tipo="+tipo,
							dataType:'JSON',
							success : function(datax)
							{
								if(datax.typeinfo=="Success")
								{
									//setInterval("reload2();",1000);
									$("#disp").html("Mensajes disponibles "+datax.sms);
									$("#sms").val(datax.sms);
									console.log("Guradado");
									discart(datax.id);
								}
								else
								{
									display_notify(datax.typeinfo, datax.msg);
								}
							}
						});
					}
					else
					{
						if(error  < 1)
						{
							error = 1;
							display_notify("Warning","Sin conexion, intente mas tarde");
						} 	
					}
				}
			});
			//console.log(numero);      
			//console.log(msg);      
		}
		else if(tipo =='Correo')
		{
			$.ajax({
			type : "POST",
				url : "enviar_notificacionv.php",
				data : "process=verificar&id="+id+"&tipo="+tipo+"&mensaje="+msg+"&numero="+numero,
				dataType:'JSON',
				success : function(datax)
				{
					if(datax.typeinfo=="Success")
					{
						//setInterval("reload2();",1000);
						console.log("Guradado");
						discart(datax.id);
					}
				}
			});
		}
		else
		{
			console.log("No action");
		}
	});  
}
function sendp()
{
	if($("#pin").val() != '')
	{
		var pin = $("#pin").val();
		$.ajax({
			type : "POST",
			url : "http://apps-opensolutions.com/sms/compra.php",
			//data : "process=verificar&pin="+$("#pin").val(),
			data : "pin="+pin,
			dataType:'JSON',
			success : function(datax)
			{
				if(datax.typeinfo=="Success")
				{
					var dataStrin = "process=verificar&cantidad="+datax.cantidad+"&tipo="+datax.tipo;
					var tipo_s = datax.tipo;
					$.ajax({
						type : "POST",
						url : "sms.php",
						//data : "process=verificar&pin="+$("#pin").val(),
						data : dataStrin,
						dataType:'JSON',
						success : function(data)
						{
							display_notify(data.typeinfo, data.msg);
							if(datax.typeinfo=="Success")
							{
								if(tipo_s == "SMS")
								{
									$("#disp").html("Mensajes disponibles "+data.sms);
									$("#sms").val(data.sms);
								}
								else if (tipo_s == "WS")
								{
									$("#disp").html("Mensajes disponibles "+data.ws);
									$("#sms").val(data.ws);
								}
								$("#viewModal #btn_ca").click();	
							}
						}
					});	
				}
				else
				{
					display_notify(datax.typeinfo, datax.msg);
				}
			}
		});
	}
	else
	{
		display_notify("Error", "Ingrese un PIN");
	}	
}
function sms(n)
{
	var disponibles = parseInt($("#sms").val());
	var a = false;
	if(disponibles>n)
	{
		a = true;
	}
	return a;
}
$(function ()
{
	//binding event click for button in modal form
	$(document).on("click", "#btn_enviar", function(){
		sendm();
	});
	$(document).on("click", "#btn_fin", function(){
		if($("#mensaje").val()!="")
		{
			if($("#tipo_m").val() !="")
			{

				generar();
			}
			else
			{
				display_notify("Error", "Por favor seleccione el tipo de mensaje");	
			}
		}
		else
		{
			display_notify("Error", "Por favor ingrese el texto del mensaje");
		}
	});
	$(document).on("click", "#btn_sms", function(){
		if($("#pin").val()!="")
		{
			sendp();
		}
		else
		{
			display_notify("Error", "Por favor ingrese el PIN proporcionado por su proveedor");
		}
	});
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});	
});	
function discart(id)
{
	$("#datos tr").each(function(){
		if($(this).find("#myCheckboxes").val() == id)
		{
			$(this).find(".text-center").html("<label class='label bg-green'>Enviado</label>");
			console.log("Listo");
		}
	});
}
$(document).on("ifChecked",".includes",function()
{
	if($("#tipo").val() == 'Teléfono' || $("#tipo").val() == 'Whatsapp')
	{
		var a = $(this).find("#myCheckboxes");
		var disponibles  = parseInt($("#sms").val());
		n++;
		if(disponibles<n)
		{
			display_notify("Error", "No cuenta con suficientes Mensajes disponibles");
			setTimeout(function(){a.iCheck('uncheck');},0);
		}
	}
});
$(document).on("ifUnchecked",".includes",function()
{
	if($("#tipo").val() == 'Teléfono' || $("#tipo").val() == 'Whatsapp')
	{
		n--;
	}
});
