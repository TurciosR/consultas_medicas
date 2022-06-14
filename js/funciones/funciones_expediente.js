$(document).ready(function()
{	
	$("#expe").attr("disabled",true);
	$("#buscar_paciente").typeahead(
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
				$("#id_paciente").val(id);
				//$("#pacientex").text("PACIENTE SELECCIONADO: "+nombre);
				var top = $("#process").val();
				if(top == "search")
				{
					tabla_datos(id, "buscar_expediente.php");
				}
				else
				{
    				tabla_datos(id,"consulta_rapida.php");	
				}
	    }
	});
});
function tabla_datos(id, url)
{
	$("#expe").attr("disabled",false);
	$("#datos").show();
	$("#expe").attr("href","expediente.php?id_paciente="+id);
	var id_doc = $("#id_doctor").val();
	$.ajax({
		type:'POST',
		url: url,
		data:"process=buscar&id_paciente="+id,
		dataType:'json',
		success: function(datax)
		{
			if(datax.typeinfo=="Success")
			{
				$("#table").html(datax.table);
			}
			else
			{
			}
		},
	});

}
$(document).on("click", "#consu", function()
{
	var id = $("#id_paciente").val();
	$.ajax({
		type:'POST',
		url:'consulta_rapida.php',
		data:"process=insert&id_paciente="+id,
		dataType:'json',
		success: function(datax)
		{
			if(datax.typeinfo=="Success")
			{
				location.href  = 'consulta1.php?acc=new&id='+datax.id;
			}
			else
			{
			}
		},
	});
});
$(document).on("click","#btn_guardar", function(){
	var nombre=$('#nombre').val();
    var apellido=$('#apellido').val();
    var direccion=$('#direccion').val();
    var telefono1=$('#telefono1').val();
    var sexo=$('#sexo').val();
    var fecha=$('#fecha_n').val();
    var data  = "process=insert&nombre="+nombre+"&apellido="+apellido+"&direccion="+direccion+"&telefono1="+telefono1;
    	data += "&sexo="+sexo+"&fecha="+fecha;
    if(nombre!="" && apellido!="" && direccion!="" && telefono1!="" && sexo!="" && fecha!="")
    {
    	$.ajax({
    		type: 'POST',
    		url: 'agregar_paciente1.php',
    		data: data,
    		dataType: 'JSON',
    		success: function(datax)
    		{
    			display_notify(datax.typeinfo, datax.msg);
    			if(datax.typeinfo == "Success")
    			{
    				$("#id_paciente").val(datax.id);
    				tabla_datos(datax.id,"consulta_rapida.php");
    				$("#btn_ce").click();	
    			}
    		},
    	});
    }
    else
    {
    	display_notify("Warning","Complete todos los datos antes de continuar");
    }
});
$(document).on('hidden.bs.modal', function(e)
{
	var target = $(e.target);
	target.removeData('bs.modal').find(".modal-content").html('');
});
$(document).on("keypress", "#telefono1", function(event){
	if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39)
    {
        
    } 
    else
    {
        inputval = $(this).val();
        var string = inputval.replace(/[^0-9]/g, "");
        var bloc1 = string.substring(0,4);
        var bloc2 = string.substring(4,7);
        var string =bloc1 + "-" + bloc2;
        $(this).val(string);
    }
});