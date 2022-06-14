$(document).ready(function() {    
	generar2();
	$("#buscar").click(function(){
		generar2();
	});
   
});
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
				url :"admin_cita1_dt.php?ini="+ini+"&fin="+fin+"&id_doctor="+id_doctor, // json datasource
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