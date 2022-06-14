var tiempo = 0;
$(document).ready(function(){
	$(".select").select2();
	show_data(0);
	show_list();
});
$("#reloadd").click(function(){
	show_data(0);
});
$("#reloadda").click(function(){
	show_list();
});
$(function ()
{
	//binding event click for button in modal form
	// Clean the modal form
	$(document).on("click", "#btn_add", function(event)
	{
		/*if($("#estatura").val()!="")
		{
			if($("#peso").val()!="")
			{
				if($("#temperatura").val()!="")
				{
					if($("#presion").val()!="")
					{*/
						agregar_singos();
					/*}
					else
					{
						display_notify("Warning", "Por favor ingrese la presion");
					}
				}
				else
				{
					display_notify("Warning", "Por favor ingrese la temperatura");
				}
			}
			else
			{
				display_notify("Warning", "Por favor ingrese el peso");
			}
		}
		else
		{
			display_notify("Warning", "Por favor ingrese la estatura");
		}*/
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
		
	}	
}	
function agregar_singos()
{
	var dataString = $("#add_signo").serialize();
	$.ajax({
		type:'POST',
		url:"signos.php",
		data: dataString,			
		dataType: 'json',
		success: function(datax)
		{	
			//display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				$("#viewModal #btn_ca").click();
				show_data(datax.id);
			}				
		}
	});      
}
function show_list(id=0)
{
	if(id==0)
	{
		var id_doc = $("#id_doctor").val();
	}
	else
	{
		var id_doc = id;
	}
	$.ajax({
		type:'POST',
		url:'consulta.php',
		data:"process=lista&id_d="+id_doc,
		dataType:'json',
		success: function(datax)
		{
			if(datax.typeinfo=="Success")
			{
				$(".list-group").html(datax.list);
				$("#count1").text(datax.num);
			}
			else
			{
			}
		},
	});
}
function reload1()
{
	location.href = 'consulta.php';	
}
$(function ()
{
    $('body').on('click', '.list-group .list-group-item', function (){
    	//uniexis($(this).attr("id"));
    	//show_data($(this).attr("id"));
        //$(this).toggleClass('active');
     });
    $('body').on('dblclick', '.list-group .list-group-item', function (){
    	$("#display").attr("href","ver_cita.php?id_cita="+$(this).attr("id"));
    	$("#display").click();
    	
     });
});
function show_data(id, d=0)
{
	if(d==0)
	{
		var id_doc = $("#id_doctor").val();
		
	}
	else
	{
		var id_doc = d;
	}
	$.ajax({
		type:'POST',
		url:'consulta.php',
		data:"process=buscar&id="+id+"&id_d="+id_doc,
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
function clear_class()
{
	$(".list-group li").each(function(){
		$(this).removeClass("active");
	});
}
function uniexis(id)
{
	$(".list-group li").each(function(){
		if($(this).attr("id") !=id)
		{
			$(this).removeClass("active");
		}
	});
}