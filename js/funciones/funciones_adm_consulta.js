$(document).ready(function()
{
	generar2();
	$("#buscar").click(function(){
		generar2();
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

function reload1()
{
	location.href = 'admin_consulta.php';	
}
function deleted()
{
	var id_cita = $('#id_cita').val();
	var dataString = 'process=deleted' + '&id=' + id_cita;
	$.ajax({
		type : "POST",
		url : "borrar_consulta.php",
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
	var ini = $("#desde").val();
    var fin = $("#hasta").val();
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
				url :"admin_consulta_dt.php?ini="+ini+"&fin="+fin+"&id_doctor="+id_doctor, // json datasource
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