$(document).ready(function() {    
   	buscar();
	$("#buscar").click(function(){
		buscar();
	});
   
});
function buscar()
{
	var ini = $("#desde").val();
    var fin = $("#hasta").val();
    var dataString="ini="+ini+"&fin="+fin;
    $.ajax({
		type : "POST",
		url : "admin_caja_dt.php",
		data : dataString,
		success : function(datax) {
			$("#refill").html(datax);
		}
	});
}