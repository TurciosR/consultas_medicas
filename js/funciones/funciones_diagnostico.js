$(document).ready(function()
{
	$(".select").select2();
	$(".numeric").numeric({negative:false, decimals:false});
	$('#formulario_diagnostico').validate(
	{		
	    rules:
	    {
            descripcion:
            {  
                required: true,           
            },
        },
        messages:
        {
			
			descripcion: "Por favor ingrese la descripcion del diagnostico",
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
    	"ajax": "diagnostico.php",
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
    var descripcion=$('#descripcion').val();
    //Get the value from form if edit or insert
	var process=$('#process').val();
	
	if(process=='insert')
	{
		var id_diagnostico=0;
		var urlprocess='agregar_diagnostico.php';
	}	 
	if(process=='edit')
	{
		var id_diagnostico=$('#id_diagnostico').val();
		var urlprocess='editar_diagnostico.php';  
	}
	var dataString ='process='+process+'&id_diagnostico='+id_diagnostico+'&descripcion='+descripcion;
	
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
	location.href = 'admin_diagnostico.php';	
}
function deleted()
{
	var id_diagnostico = $('#id_diagnostico').val();
	var dataString = 'process=deleted' + '&id_diagnostico=' + id_diagnostico;
	$.ajax({
		type : "POST",
		url : "borrar_diagnostico.php",
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