$(function ()
{
	lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true
    });
	$(document).on('hidden.bs.modal', function(e)
	{
		var act = $("#act").val();
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
		if(act == "1")
		{
			location.reload();
		}
	});
	$(document).on("click", "#btn_agregar_arc", function()
	{
		if($("#foto").val()!="")
		{
			if($("#descripcion").val()!="")
			{
				if($("#fecha").val()!="")
				{
					$(this).attr("disabled", true);
					upload();
				}
				else
				{
					display_notify("Error", "Por favor seleccione la fecha de lectura");	
				}
			}
			else
			{
				display_notify("Error", "Por favor ingrese la descripcion de la imagen");
			}
		}
		else
		{
			display_notify("Error", "Debe seleccionar un archivo");
		}
	});
	$(document).on("click", ".fileinput-remove-button", function(){
		$("#foto").attr("name", "foto");
	});
});	
function upload()
{
    var form = $("#form");
    var formdata = false;
    if(window.FormData)
    {
        formdata = new FormData(form[0]);
    }
    var formAction = form.attr('action');
    $.ajax({
        type        : 'POST',
        url         : 'archivo_examen.php',
        cache       : false,
        data        : formdata ? formdata : form.serialize(),
        contentType : false,
        processData : false,
        dataType : 'json',	
        success: function(datax)
        {  
		   if(datax.typeinfo == 'Success')
	       {
				var fila = "<tr id='fl"+datax.id_img+"'>";
					fila += "<td>"+datax.fecha+"</td>";
					fila += "<td><a target='_blank' href="+datax.url+">"+datax.nombre+"</a></td>";
					fila += "<td><button id='"+datax.id_img +"' class='btn eliminar'><i class='fa fa-trash'></i></button></td>";
					fila += "</tr>";
				$("#table").append(fila);
				$("#foto").val("");
				$("#descripcion").val("");
				$(".fileinput-remove-button").click();
				$("#foto").attr("name", "foto");
				$("#btn_agregar_arc").attr("disabled", false);
	        }
	        else
	        {
				display_notify(datax.typeinfo, datax.msg);//datax.msg);           	                         
	        }
	    }
    });   
}
$(document).on("click", ".eliminar", function(event){
	var id_btn = $(this).attr("id");
	$("#table tr").each(function()
	{
		var id_btn_2 = $(this).find(".eliminar").attr("id");
		if(id_btn == id_btn_2)
		{
			var ajaxdata = 
			{
				"process":"deleted",
				"id_img":id_btn
			}
			$.ajax({
			type:'POST',
			url:"archivo_examen.php",
			data: ajaxdata,			
			dataType: 'json',
			success: function(datax)
			{		
				if(datax.typeinfo == "Success" )
				{
					$("#fl"+id_btn+"").remove();
				}
				else if(datax.typeinfo == "Error")
				{
				}					
			}
			})
		}
	})
});