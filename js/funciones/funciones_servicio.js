$(document).ready(function()
{
	$(".select").select2();
	$(".numeric").numeric({negative:false});
	$('#formulario_servicio').validate(
	{		
	    rules:
	    {
            descripcion:
            {  
                required: true,           
            },
            precio:
            {  
                required: true,           
            },
        },
        messages:
        {
			
			descripcion: "Por favor ingrese la descripcion del servicio",
			precio: "Por favor ingrese el precio",
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
    var precio=$('#precio').val();
    //Get the value from form if edit or insert
	var process=$('#process').val();
	
	if(process=='insert')
	{
		var id_servicio=0;
		var urlprocess='agregar_servicio.php';
	}	 
	if(process=='edit')
	{
		var id_servicio=$('#id_servicio').val();
		var urlprocess='editar_servicio.php';  
	}
	var dataString ='process='+process+'&id_servicio='+id_servicio+'&descripcion='+descripcion+"&precio="+precio;
	
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
	location.href = 'admin_servicio.php';	
}
function deleted()
{
	var id_servicio = $('#id_servicio').val();
	var dataString = 'process=deleted' + '&id_servicio=' + id_servicio;
	$.ajax({
		type : "POST",
		url : "borrar_servicio.php",
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