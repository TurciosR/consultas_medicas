$(document).ready(function()
{
}); //end document ready

//evitar el send del form al darle enter solo con click en el boton
$(document).on("keypress", 'form', function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        e.preventDefault();
        return false;
    }
});

$(function ()
{
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});	
	$(document).on('click','#btnAplicar', function()
	{
		aplicar();
	});
	$(document).on('click','#btn_edit', function()
	{
		editar();
	});
	$(document).on('click','#btn_delete', function()
	{
		deleted();
	});
	$(document).on('click','#append2', function()
	{
		append2();
	});
});	

function reload1()
{
	location.href = 'admin_detalle_plan.php?id_plan='+$("#id_plan").val();	
}

// Evento que selecciona la fila y la elimina de la tabla

function senddata()
{
	var id_plan = $("#id_plan").val();
	var sesion = $("#sesion").val();
	var dosis = $("#dosis").val();
	var fecha_inicio = $("#fecha_inicio").val();
	var frecuencia = $("#frecuencia").val();
	
	var datos = "";
	var cuantos = 0;
	var fallos = 0;
	$("#table tr").each(function()
	{
		var fecha = $(this).find(".fecha").val();
		var dosis = $(this).find(".dosis").val();
		if(isNaN(dosis)!=true && (dosis > 0) && fecha !="")
		{
			datos += fecha+","+dosis+"|";
			cuantos = cuantos + 1;
		}
		else
		{
			fallos = fallos + 1;
		}
	});
	if(fallos>0)
	{
		display_notify("Error", "Verifique que todos los campos esten completos antes de continuar")
	}
	else
	{
		var dataString = 'process=insert&sesion='+sesion+'&dosis='+dosis+'&fecha_inicio='+fecha_inicio+'&frecuencia='+frecuencia;
			dataString += '&cuantos='+cuantos+'&datos='+datos+'&id_plan='+id_plan;
		$.ajax({
            type: "POST",
            url: "agregar_plan.php",
            data: dataString,
            dataType : 'json',
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
}
function aplicar(id_v=0)
{	if (id_v == 0) 
	{
		var id_vacuna = $("#id_vacuna").val();
	}
	else
	{
		id_vacuna=id_v;
	}
	$.ajax({
		type:'POST',
		url:"aplicar_vacuna.php",
		data: 'process=deleted&id_vacuna='+id_vacuna+'&plan=1',			
		dataType: 'json',
		success: function(datax)
		{	
			display_notify(datax.typeinfo,datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();",1000);		
			}				
		}
	});   
}
function editar()
{
	var id_detalle = $("#id_detalle").val();
	var fecha = $("#fecha").val();
	var dosis = $("#dosis").val();
	if(dosis =="" || fecha =="")
	{
		display_notify("Error", "Por favor verifique que los campos Fecha y Dosis no esten vacios");
	}
	else
	{
		var observaciones = $("#observaciones").val();
		$.ajax({
			type:'POST',
			url:"editar_detalle_plan.php",
			data: 'process=edit&id_detalle='+id_detalle+"&fecha="+fecha+"&dosis="+dosis+"&observaciones="+observaciones,			
			dataType: 'json',
			success: function(datax)
			{	
				display_notify(datax.typeinfo,datax.msg);
				if(datax.typeinfo == "Success")
				{
					setInterval("reload1();",1000);		
				}				
			}
		});  
	} 
}
function deleted()
{
	var id_detalle = $("#id_detalle").val();
	$.ajax({
		type:'POST',
		url:"borrar_detalle_plan.php",
		data: 'process=delete&id_detalle='+id_detalle,			
		dataType: 'json',
		success: function(datax)
		{	
			display_notify(datax.typeinfo,datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();",1000);		
			}				
		}
	});   
}
function append2()
{
	var id_plan = $("#id_plan").val();
	$.ajax({
		type:'POST',
		url:"admin_detalle_plan.php",
		data: 'process=append2&id_plan='+id_plan,			
		dataType: 'json',
		success: function(datax)
		{	
			display_notify(datax.typeinfo,datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();",1000);		
			}				
		}
	});   
}
