$(document).ready(function()
{
	$(".select").select2();
	$(".numeric").numeric({negative:false, decimals:false});
	$("#imagen").fileinput({'showUpload':true, 'previewFileType':'image'});
	$('#vacuna').on('ifChecked', function(event)
	{
		$("#vacun").val(1);
	});
	$('#vacuna').on('ifUnchecked', function(event)
	{
		$("#vacun").val(0);	 
	});
	$('#formulario_medicamento').validate(
	{		
	    rules:
	    {
            descripcion:
            {  
                required: true,           
            },
			principio:
			{  
                required: true,           
            },
            marca:
			{  
                required: true,           
            },
            presentacion:
			{  
                required: true,           
            },
            laboratorio:
			{  
                required: true,           
            },
            indicaciones:
            {  
                required: true,           
            },
        },
        messages:
        {
			descripcion: "Por favor ingrese el nombre del medicamento",
			principio: "Por favor ingrese el principio activo del medicamento",
			marca: "Por favor ingrese la marca del medicamento",
			presentacion: "Por favor ingrese la presentacion del medicamento",
			laboratorio: "Por favor ingrese el laboratorio",
			indicaciones: "Por favor ingrese las indicaciones del medicamento",
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
    		senddata();
		}
    });
    $("#editablea").DataTable({
    	"processing":true,
    	"serverSide":true,
    	"ajax": "medicamento.php",
    	"language":{
	    "sProcessing":     "Procesando...",
	    "sLengthMenu":     "Mostrar _MENU_ registros",
	    "sZeroRecords":    "No se encontraron resultados",
	    "sEmptyTable":     "Ningún dato disponible en esta tabla",
	    "sInfo":           "Del _START_ al _END_ de un total de _TOTAL_ registros",
	    "sInfoEmpty":      "Del 0 al 0 de un total de 0 registros",
	    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	    "sInfoPostFix":    "",
	    "sSearch":         "Buscar:",
	    "sUrl":            "",
	    "sInfoThousands":  ",",
	    "sLoadingRecords": "Cargando...",
	    "oPaginate": {
	        "sFirst":    "Primero",
	        "sLast":     "Último",
	        "sNext":     "Siguiente",
	        "sPrevious": "Anterior"
	    },
	    "oAria": {
	        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
	        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
	    }
		},
		"pageLength": 25,
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
    var form = $("#formulario_medicamento");
    var formdata = false;
    if(window.FormData)
    {
        formdata = new FormData(form[0]);
    }
    var process = $("#process").val();
    if(process=='insert')
	{
		var urlprocess='agregar_medicamento.php';
	}	 
	if(process=='edit')
	{
		var urlprocess='editar_medicamento.php';  
	}
    var formAction = form.attr('action');
    $.ajax({
        type        : 'POST',
        url         : urlprocess,
        cache       : false,
        data        : formdata ? formdata : form.serialize(),
        contentType : false,
        processData : false,
        dataType : 'json',	
        success: function(datax)
        {  
		   	display_notify(datax.typeinfo, datax.msg);//datax.msg);           	                         			 	
		    if(datax.typeinfo == 'Success')
	        {
	        	setInterval("reload1();", 1500);
			}
	    }
    });   
}
function reload1()
{
	location.href = 'admin_medicamento.php';	
}
function deleted()
{
	var id_medicamento = $('#id_medicamento').val();
	var dataString = 'process=deleted' + '&id_medicamento=' + id_medicamento;
	$.ajax({
		type : "POST",
		url : "borrar_medicamento.php",
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