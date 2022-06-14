$(document).ready(function()
{
	$(".decimal").numeric({negative:false});
	$('.numeric').numeric({negative:false, decimal:false});
	$('.select').select2();
	var simbolo = $("#simbolo").val();
	$("#editablea").DataTable({
    	"processing":true,
    	"serverSide":true,
    	"ajax": "credito.php",
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
    $("#tipo_c").change(function(){
    	if($(this).val()=="PERSONAL")
    	{
    		$("#div_inst").hide();
    	}
    	else
    	{
    		$("#div_inst").show();
    	}
    });
    $("#buscar_servicio").typeahead(
 	{
		//  contentType: "application/json; charset=utf-8",
		source: function(query, process)
		{
			$.ajax({
		       url: 'autocomplete_servicio.php',
		        type: 'GET',
		        data: '&query=' + query,
		        dataType: 'JSON',
		        async: true,
		        success: function(data)
		        {
		            process(data);
				}
	        });                
	    },
	    updater: function(selection)
	    {
			var data0=selection;
			var data= data0.split("|");
			var id_data = data[0];
			agregar_servicio(id_data);
		}   
	});  
	/*$("#cuota").keyup(function(){
		var total = parseFloat($("#total_final").text());
		var cuota = parseFloat($(this).val());
		var n = 0;
		if(total > 0 && cuota >0)
		{
			n = total/cuota;
		}
		if(n>0)
		{
			$("#numero_c").attr("readonly",true);
			$("#numero_c").val(Math.ceil(n));
		}
		else
		{
			$("#numero_c").attr("readonly",false);
			$("#numero_c").val("");
		}
	});*/
	$("#numero_c").keyup(function(){
		var total = parseFloat($("#total_final").text());
		var n = parseFloat($(this).val());
		var cuota = 0;
		if(total > 0 && n >0)
		{
			cuota = total/n;
		}
		if(cuota>0)
		{
			//$("#cuota").attr("readonly",true);
			$("#cuota").val(round(cuota,2));
		}
		else
		{
			//$("#cuota").attr("readonly",false);
			$("#cuota").val("0.00");
		}
	});
	$('#dui').on('keydown', function (event) {
        if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode==9)
        {
        
        }
        else
        {
            inputval = $(this).val();
            var string = inputval.replace(/[^0-9]/g, "");
            var bloque1 = string.substring(0,8);
            var blocque2 = string.substring(10,10);
            var string = (bloque1  + "-" + blocque2);
            $(this).val(string);
        }
    });
	$("#btn_fin").click(function(){
		if($("#nombre").val()!="")
		{
			if($("#dui").val()!="")
			{
				if($("#table_credito tr").length>0)
		 		{
					if($("#tipo_c").val()=="PERSONAL")
					{
						if($("#numero_c").val()!="")
						{
							if($("#inicio").val()!="")
							{
								if($("#frecuencia")!="")
								{
									sennd();
								}
								else
								{
									display_notify("Error", "Por favor ingrese la frecuencia de pago");
								}
							}
							else
							{
								display_notify("Error", "Por favor seleccione la fecha de inicio");
							}
						}
						else
						{
							display_notify("Error", "Por favor ingrese el numero de cuotas");
						}
					}
					else
					{
						if($("#institucion").val()!="")
						{
							if($("#tipo_d").val()!="")
							{
								if($("#numero_d").val()!="")
								{
									if($("#numero_c").val()!="")
									{
										if($("#inicio").val()!="")
										{
											if($("#frecuencia")!="")
											{
												sennd();
											}
											else
											{
												display_notify("Error", "Por favor ingrese la frecuencia de pago");
											}
										}
										else
										{
											display_notify("Error", "Por favor seleccione la fecha de inicio");
										}
									}
									else
									{
										display_notify("Error", "Por favor ingrese el numero de cuotas");
									}
								}
								else
								{
									display_notify("Error", "Por favor ingrese el numero de documento");
								}
							}
							else
							{
								display_notify("Error", "Por favor ingrese el tipo de documento");
							}
						}
						else
						{
							display_notify("Error", "Por favor ingrese el nombre de la institucion");
						}
					}
				}
				else
				{
					display_notify("Error", "Por favor ingrese detalle de servicios");
				}
			}
			else
			{
				display_notify("Error", "Por favor ingrese el numero de DUI");
			}
		}
	else
	{
		display_notify("Error", "Por favor ingrese un nombre");
	}
	});
}); //end document ready

//evitar el send del form al darle enter solo con click en el boton
$(document).on("keypress", 'form', function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        e.preventDefault();
        return false;
    }
});

$(function ()
{
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});	
});	

function reload1()
{
	location.href = 'admin_credito.php';	
}
// Evento para agregar elementos al grid de servicio
function agregar_servicio(id)
{
	if(!dato_existente(id))
	{
		var tr_add  ="";
		var simbolo = $("#simbolo").val();
		var dataString = 'process=consultar_servicio&id_servicio='+id;
		$.ajax({
			type : "POST",
			url : "agregar_credito.php",
			data : dataString,
			dataType : 'json',
			success : function(data)
			{
				var descripcion=data.descripcion;
				var precio=data.precio;
				tr_add += "<tr id=fl"+id+">";
				tr_add += '<td>'+id+'</td>';
				tr_add += '<td>'+descripcion+'</td>';	
				tr_add += '<td class="precio">'+simbolo+''+round(precio,2)+'</td>';	
				tr_add += '<td><input type="text" class="form-control col-md-1 cantidad" value="1"></td>';	
				tr_add += "<td id='subtot'>"+simbolo+""+round(precio,2)+"</td>";	
				tr_add += "<td><a class='btn elim' id='"+id+"'><i class='fa fa-trash'></i></a></td>";
				tr_add += '</tr>';
				$("#table_credito").append(tr_add);
				$(".numeric").numeric({negative:false});
				totales();
			}	
		});	
	}
	else
	{
		display_notify("Warning", "Este servicio ya fue agregado");
	}
}	
function dato_existente(ide)
{
	var dato =false;
	$("#table_credito tr").each(function()
	{
		var id = $(this).attr("id");
		if(id == "fl"+ide)
		{
			dato = true;
		}
	});
	return dato;
}
function round(value, decimals)
{
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}
function totales()
{
	var subtotal=0;
	var total_dinero=0;
	var simbolo = $("#simbolo").val();
	$("#table_credito tr").each(function()
	{
		var prec = $(this).find(".precio").text().split(simbolo);
		var cant = parseFloat($(this).find(".cantidad").val());
		var precio = parseFloat(prec[1]);
		if(cant>0)
		{
			$(this).find("#subtot").text(simbolo+round((cant*precio),2));	
		}
		else
		{
			$(this).find("#subtot").text(simbolo+"0.0");
		}
		
		var sub = $(this).find("#subtot").text().split(simbolo);
 		total_dinero += parseFloat(sub[1]);
 	});
 	$("#total_final").text(round(total_dinero,2));
 	$.ajax({
		type : "POST",
		url : "agregar_credito.php",
		data : "process=total_texto&total="+total_dinero,
		dataType : 'json',
		success : function(datax)
		{
			 $('#totaltexto').html("<strong>"+datax.totaltexto+"</strong>");
		}
	});
}	

$(document).on("blur",".cantidad",function()
{
  totales();
})
//Evento que valida el enter a traves del teclado
$(document).on("keydown",".cantidad", function(event)
{
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13'){
		totales();
	}
})

$(document).on("focusout",".cantidad",function()
{
	totales();
})
$(document).on("keyup",".cantidad", function()
{
	totales();
})
// Evento que selecciona la fila y la elimina de la tabla
$(document).on("click",".elim",function()
{
	var ide = $(this).attr("id");
	$("#table_credito tr").each(function()
	{
		var id = $(this).attr("id");
		if(id == "fl"+ide)
		{
			$(this).remove();
		}
	});
	totales();
});	
function sennd()
{
	var simbolo = $("#simbolo").val();
	var total=$('#total_final').text();
	var nombre = $("#nombre").val();
	var dui = $("#dui").val();
	var tipo_c = $("#tipo_c").val();
	var institucion = $("#institucion").val();
	var tipo_d = $("#tipo_d").val();
	var numero_d = $("#numero_d").val();
	var numero_c = $("#numero_c").val();
	var fecha = $("#fecha").val();
	var inicio = $("#inicio").val();
	var frecuencia = $("#frecuencia").val();
	var cuota = $("#cuota").val();
	var cuantos = 0;
	var datos = "";
	var fallos = 0;
	$("#table_credito tr").each(function()
	{
		var prec = $(this).find(".precio").text().split(simbolo);
		var cantidad = parseFloat($(this).find(".cantidad").val());
		var precio = parseFloat(prec[1]);
		var id = $(this).attr("id").split("fl");
		id_servicio = id[1];
		if(isNaN(precio)!=true && (precio > 0) && isNaN(cantidad)!=true && (cantidad > 0) && id_servicio !="" && id_servicio>0)
		{
			datos = datos +id_servicio+","+cantidad+","+precio+"|";
			cuantos = cuantos + 1;
		}
		else
		{
			fallos = fallos + 1;
		}
	});
	if(fallos>0)
	{
		display_notify("Error", "Verifique que todos los campos esten completos antes de continuar")
	}
	else
	{
		var dataString ='process=insert&nombre='+nombre+'&dui='+dui+'&fecha='+fecha+'&total='+total+"&datos="+datos;
			dataString += "&cuantos="+cuantos+"&tipo_c="+tipo_c+"&institucion="+institucion+"&tipo_d="+tipo_d+"&numero_d="+numero_d;
			dataString += "&inicio="+inicio+"&frecuencia="+frecuencia+"&cuota="+cuota+"&numero_c="+numero_c;
		$.ajax({
            type: "POST",
            url: "agregar_credito.php",
            data: dataString,
            dataType : 'json',
            success: function(datax)
            {	
				display_notify(datax.typeinfo,datax.msg);
				if(datax.typeinfo=="Success")
				{
					setInterval("reload1();",1500);
				}		
			}
        });
	}		  
}