
$(document).ready(function(){
	show_list();
	setInterval("show_list();",3000);
});
function show_list()
{
	let id = $("#id_doctor").val();
	$.ajax({
		type:"POST",
		url:"monitor_dt.php",
		data:"id="+id,
		success: function(datax)
		{
			$("#fill").html(datax);
		},
	});
	siguiente();
}
function siguiente()
{
	let id = $("#id_doctor").val();
	$.ajax({
		type:"POST",
		url:"monitor.php",
		data:"process=siguiente&id="+id,
		dataType:'JSON',
		success: function(datax)
		{
			$("#sig").html(datax.sig);
			id=datax.id;
		},
	})
}
