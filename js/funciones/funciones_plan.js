$(document).ready(function()
{
	$(".decimal").numeric({negative:false});
	$('.numeric').numeric({negative:false, decimal:false});
	
    $("#btn_generar").click(function(){
    	if($("#sesion").val() != "")
    	{
    		if($("#dosis").val() != "")
	    	{
	    		if($("#fecha_inicio").val() != "")
		    	{
			    	if($("#frecuencia").val() != "")
			    	{
			    		append_plan();
			    	}
			    	else
			    	{
			    		display_notify("Error", "Por favor ingrese la frecuencia en dias");
			    	}
		    	}
		    	else
		    	{
		    		display_notify("Error", "Por favor ingrese la fecha inicial");
		    	}
	    	}
	    	else
	    	{
	    		display_notify("Error", "Por favor ingrese la dosis");
	    	}
    	}
    	else
    	{
    		display_notify("Error", "Por favor ingrese el numero de sesiones");
    	}
    });
    $("#boton_agregar").click(function(){
    	if($("#sesion").val() != "")
    	{
    		if($("#dosis").val() != "")
	    	{
	    		if($("#fecha_inicio").val() != "")
		    	{
			    	if($("#frecuencia").val() != "")
			    	{
			    		if($("#table tr").length >0)
				    	{
				    		senddata();
				    	}
				    	else
				    	{
				    		display_notify("Error", "No ha asignado ninguna sesion");
				    	}
			    	}
			    	else
			    	{
			    		display_notify("Error", "Por favor ingrese la frecuencia en dias");
			    	}
		    	}
		    	else
		    	{
		    		display_notify("Error", "Por favor ingrese la fecha inicial");
		    	}
	    	}
	    	else
	    	{
	    		display_notify("Error", "Por favor ingrese la dosis");
	    	}
    	}
    	else
    	{
    		display_notify("Error", "Por favor ingrese el numero de sesiones");
    	}
    });
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
});	

function reload1()
{
	location.href = 'vacuna.php';	
}
// Evento para agregar elementos al grid de servicio
function append_plan()
{
	var tr_add  ="";
	var sesion = $("#sesion").val();
	var dosis = $("#dosis").val();
	var fecha_inicio = $("#fecha_inicio").val();
	var frecuencia = $("#frecuencia").val();
	var dataString = 'process=fill&sesion='+sesion+'&dosis='+dosis+'&fecha_inicio='+fecha_inicio+'&frecuencia='+frecuencia;
	$.ajax({
		type : "POST",
		url : "agregar_plan.php",
		data : dataString,
		dataType:'json',
		success : function(data)
		{
			$("#table").append(data.table);
			$(".dosis").numeric({negative: false});
			$(".fecha").datepicker({
				format: 'dd-mm-yyyy',
				language:'es',
			});
			$("#fecha_inicio").val(data.fecha);
		}	
	});	
}	

// Evento que selecciona la fila y la elimina de la tabla
$(document).on("click",".elim",function()
{
	var parent = $(this).parents("tr").get(0);
	$(parent).remove();	
});	

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
		$("#table tr").each(function()
		{
			$(this).remove();
		});
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
