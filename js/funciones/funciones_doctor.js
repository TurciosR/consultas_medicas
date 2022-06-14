$(document).ready(function()
{
	generar2();
	$(".select").select2();
	$(".numeric").numeric({negative:false, decimals:false});
	$('#formulario_doctor').validate(
	{		
	    rules:
	    {
            nombre:
            {  
                required: true,           
            },
			apellido:
			{  
                required: true,           
            },
            sexo:
			{  
                required: true,           
            },
            telefono:
			{  
                required: true,           
            },
            fecha:
			{  
                required: true,           
            },
            direccion:
            {  
                required: true,           
            },
            jvpm:
            {  
                required: true,           
            },
            especialidad:
            {  
                required: true,           
            },	
            usuario:
            {  
                required: true,           
            },	
            password:
            {  
                required: true,           
                minlength: 6,           
            },	
        },
        messages:
        {
			nombre: "Por favor ingrese el nombre del doctor",
			apellido: "Por favor ingrese el apellido del doctor",
			sexo: "Por favor seleccione el género",
			telefono: "Por favor ingrese el número de teléfono",
			fecha: "Por favor ingrese la fecha de nacimiento",
			direccion: "Por favor ingrese la dirección del doctor",
			jvpm: "Por favor ingrese el numero de JVPM",
			especialidad: "Por favor seleccione una especialidad",
			usuario: "Por favor ingrese un usuario",
			password:{
				required: "Por favor ingrese una contraseña",
				minlength: "La contraseña debe ser de por lo menos 6 caracteres",
			},
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
    $("#usuario").on("keyup", function(event){
    	$(this).val($(this).val().toLowerCase());
    });
    $('.tel').on('keydown', function (event)
    {
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
    var nombre=$('#nombre').val();
    var apellido=$('#apellido').val();
    var direccion=$('#direccion').val();
    var telefono=$('#telefono').val();
    var email=$('#email').val();
    var jvpm=$('#jvpm').val();
    var sexo=$('#sexo').val();
    var fecha=$('#fecha').val();
    var especialidad=$('#especialidad').val();
    var subespecialidad=$('#subespecialidad').val();
    var usuario=$('#usuario').val();
    var password=$('#password').val();

    //Get the value from form if edit or insert
	var process=$('#process').val();
	
	if(process=='insert')
	{
		var id_doctor=0;
		var urlprocess='agregar_doctor.php';
	}	 
	if(process=='edit')
	{
		var id_doctor=$('#id_doctor').val();
		var urlprocess='editar_doctor.php';  
	}
	var dataString ='process='+process+'&id_doctor='+id_doctor+'&nombre='+nombre+'&apellido='+apellido+'&sexo='+sexo;
		dataString+='&direccion='+direccion+'&telefono='+telefono+'&email='+email+'&jvpm='+jvpm+"&usuario="+usuario;
		dataString+='&especialidad='+especialidad+'&subespecialidad='+subespecialidad+'&fecha='+fecha+"&password="+password;
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
	location.href = 'admin_doctor.php';	
}
function deleted()
{
	var id_doctor = $('#id_doctor').val();
	var dataString = 'process=deleted' + '&id_doctor=' + id_doctor;
	$.ajax({
		type : "POST",
		url : "borrar_doctor.php",
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
	dataTable = $('#editable2').DataTable().destroy()
	dataTable = $('#editable2').DataTable( {
		"pageLength": 50,
		"responsive": true,
		"autoWidth": false,
		"order":[ 0, 'desc' ],
		"processing": true,
		"serverSide": true,
		"ajax":{
				url :"admin_doctor_dt.php", // json datasource
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