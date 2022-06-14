$(document).ready(function()
{
	generar2();
	$("#buscar").click(function(){
		generar2();
	});
	$(".select").select2();
	$(".numeric").numeric({negative:false, decimals:false});
	$("#nombre").typeahead(
	{
		//Definimos la ruta y los parametros de la busqueda para el autocomplete
	    source: function(query, process)
	    {
			$.ajax(
			{
	            url: 'autocomplete_paciente.php',
	            type: 'GET',
	            data: 'query=' + query ,
	            dataType: 'JSON',
	            async: true,   
	            //Una vez devueltos los resultados de la busqueda, se pasan los valores al campo del formulario
	            //para ser mostrados 
	            success: function(data)
	            { 	  
	              	process(data);
				}
	        });                
	    },
	    //Se captura el evento del campo de busqueda y se llama a la funcion agregar_factura()
	    updater: function(selection)
	    {
	    	var data0=selection;
			var id = data0.split("|");
			var nombre = id[1];
				id = parseInt(id[0]);
				$("#paciente").text("PACIENTE SELECCIONADO: "+nombre);
				$("#id_paciente").val(id);

	    }
	});
	$("#submit1").click(function(){
		if($("#id_paciente").val()!="")
		{
			if($("#doctor").val()!="")
			{
				if($("#fecha").val()!="")
				{
					if($("#hora").val()!="")
					{
						if($("#espacio").val()!="")
						{
							if($("#motivo").val()!="")
							{
								if($("#estado").val()!="")
								{
									senddata();
								}	
								else
								{
									display_notify("Warning","Por favor seleccione el estado de la cita");
								}
							}	
							else
							{
								display_notify("Warning","Por favor ingrese el motivo de la cita");
							}
						}	
						else
						{
							display_notify("Warning","Por favor seleccione un consultorio");
						}
					}	
					else
					{
						display_notify("Warning","Por favor ingrese la hora de la cita");
					}
				}	
				else
				{
					display_notify("Warning","Por favor ingrese la fecha de la cita");
				}
			}	
			else
			{
				display_notify("Warning","Por favor seleccione un medico");
			}
			
		}
		else
		{
			display_notify("Warning","Por favor seleccione un paciente");
		}
	});
});
$(function ()
{
	//binding event click for button in modal form
	$(document).on("click", "#btnDelete", function(event)
	{
		deleted();
	});
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e)
	{
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});
	
});	

function autosave(val)
{
	var name=$('#name').val(); 
	if (name==''|| name.length == 0){
		var	typeinfo="Info";
		var msg="The field name is required";
		display_notify(typeinfo,msg);
		$('#name').focus();
	}
	else
	{
		senddata();
	}	
}	

function senddata()
{
    var id_paciente=$('#id_paciente').val();
    var doctor=$('#doctor').val();
    var fecha=$('#fecha').val();
    var hora=$('#hora').val();
    var espacio=$('#espacio').val();
    var estado=$('#estado').val();
    var observaciones=$('#observaciones').val();
    var motivo=$('#motivo').val();

    //Get the value from form if edit or insert
	var process=$('#process').val();
	
	if(process=='insert')
	{
		var id_cita=0;
		var urlprocess='agregar_cita1.php';
	}	 
	if(process=='edit')
	{
		var id_cita=$('#id_cita').val();
		var urlprocess='editar_cita1.php';  
	}
	var dataString ='process='+process+'&id_cita='+id_cita+'&id_paciente='+id_paciente+'&doctor='+doctor+'&fecha='+fecha;
		dataString+='&hora='+hora+'&espacio='+espacio+'&estado='+estado+'&observaciones='+observaciones+'&motivo='+motivo;
	$.ajax({
		type:'POST',
		url:urlprocess,
		data: dataString,			
		dataType: 'json',
		success: function(datax)
		{	
			display_notify(datax.typeinfo,datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();", 1500);		
			}				
		}
	});          
}
function reload1()
{
	location.href = 'admin_cita.php';	
}
function deleted()
{
	var id_cita = $('#id_cita').val();
	var dataString = 'process=deleted' + '&id_cita=' + id_cita;
	$.ajax({
		type : "POST",
		url : "borrar_cita.php",
		data : dataString,
		dataType : 'json',
		success : function(datax)
		{
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo=="Success")
			{
				setInterval("reload1();", 1500);
				$('#deleteModal').hide(); 
			}
		}
	});
}

function generar2(){
	var ini = $("#ini").val();
    var fin = $("#fin").val();
	var id_doctor = $("#id_doctor").val();
	dataTable = $('#editable2').DataTable().destroy()
	dataTable = $('#editable2').DataTable( {
		"pageLength": 50,
		"responsive": true,
		"autoWidth": false,
		"order":[ 0, 'desc' ],
		"processing": true,
		"serverSide": true,
		"ajax":{
				url :"admin_cita_dt.php?ini="+ini+"&fin="+fin+"&id_doctor="+id_doctor, // json datasource
				//url :"admin_factura_rangos_dt.php", // json datasource
				//type: "post",  // method  , by default get
				error: function(){  // error handling
					$(".editable2-error").html("");
					$("#editable2").append('<tbody class="editable_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
					$("#editable2_processing").css("display","none");
					$( ".editable2-error" ).remove();
				}
			}
		} );
		dataTable.ajax.reload();
	//}
}