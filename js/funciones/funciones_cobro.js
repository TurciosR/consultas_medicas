$(document).ready(function()
{
	$(".decimal").numeric({negative:false});
	$('.numeric').numeric({negative:false, decimals:false});
	$(".select").select2();
	//Autocomplete typeahead
	$("#buscar_paciente").typeahead(
	{
		//  contentType: "application/json; charset=utf-8",
		source: function(query, process)
		{
			$.ajax({
               url: 'autocomplete_paciente.php',
                type: 'GET',
                data: 'query=' + query ,
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
			var descrip1 =data[1];
			$("#id_paciente").val(id_data);
			$("#paciente").text("PACIENTE SELECCIONADO: "+descrip1);

			//agregar_inmueble(id_data);
		}  
	});  
 //fin Autocomplete typeahead  
  
 //Autocomplete typeahead servicio
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
 	//fin Autocomplete typeahead   
    $("#forma").change(function(){
    	if($(this).val()=="Efectivo")
    	{
    		$("#otr").hide();
    	}
    	else
    	{
    		$("#otr").show();
    		if($(this).val()=="Cheque")
    		{
    			$("#doc").text("N° de Cheque");
    		}
    		else if($(this).val()=="Tarjeta")
    		{
    			$("#doc").text("N° de Voucher");
    		}
    		else if($(this).val()=="ISBM")
    		{
    			$("#doc").text("N° de Documento");
    		}
    	}
    });
    $("#tipo_cliente").change(function(){
    	if($(this).val()=="Particular")
    	{
    		$("#div_pa").hide();
    		$("#div_cli").show();
    	}
    	else
    	{
    		$("#div_pa").show();
    		$("#div_cli").hide();
    	}
    });
    $("#btn_fin").click(function()
 	{

 			if($("#fecha").val() != "")
	 		{
	 			if($("#table_factura tr").length>0)
		 		{
		 			if($("#forma").val() != "Efectivo")
		 			{
		 				if($("#dui").val() != "")
		 				{
		 					if($("#documento").val() != "")
			 				{
			 					if($("#tipo_cliente").val() != "Particular")
			 					{
				 					if($("#id_paciente").val() != "")
	 								{
				 						senddata();
				 					}
							 		else
							 		{
							 			display_notify("Error", "Por favor seleccione un paciente");
							 		}
							 	}
							 	else
							 	{
							 		if($("#cliente").val()!="")
							 		{
							 			senddata();
							 		}
							 		else
							 		{
							 			display_notify("Error", "Por favor ingrese el nombre del cliente");
							 		}
							 	}
			 				}
			 				else
			 				{
			 					display_notify("Error", "Por favor ingrese el numero de documento");
			 				}
		 				}
		 				else
		 				{
		 					display_notify("Error", " Por favor ingrese el numero de DUI");
		 				}
		 			}
		 			else
		 			{
		 				if($("#tipo_cliente").val() != "Particular")
	 					{
		 					if($("#id_paciente").val() != "")
							{
		 						senddata();
		 					}
					 		else
					 		{
					 			display_notify("Error", "Por favor seleccione un paciente");
					 		}
					 	}
					 	else
					 	{
					 		if($("#cliente").val()!="")
					 		{
					 			senddata();
					 		}
					 		else
					 		{
					 			display_notify("Error", "Por favor ingrese el nombre del cliente");
					 		}
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
	 			display_notify("Error", "Por favor seleccione la fecha");
	 		}
 	});
     $('#dui').on('keydown', function (event) {
        if (event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode==9) {
            // ignorando tecla espacio y las de desplazamiento
        } else {
            // validar el nit
            inputval = $(this).val();
            var string = inputval.replace(/[^0-9]/g, "");
            var bloque1 = string.substring(0,8);
            var blocque2 = string.substring(10,10);
            var string = (bloque1  + "-" + blocque2);
            $(this).val(string);
        }
    });
}); //end document ready

$(document).on("keyup",".summm",function()
{
	totalas();
});

$(document).on("click","#btn_corte", function()
{
	if($("#totalc").val()!="" || $("#totalt").val()!="" || $("#totala").val()!="")
	{	
		corte();
	}
	else
	{
		display_notify("Error", "Ingrese el total en caja");
	}
});

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
	//binding event click for button in modal form
	$(document).on("click", "#btnDelete", function(event) {
		deleted();
	});
	// Clean the modal form
	$(document).on('hidden.bs.modal', function(e) {
		var target = $(e.target);
		target.removeData('bs.modal').find(".modal-content").html('');
	});	
});	

function reload1()
{
	location.href = 'admin_caja.php';	
}
function deleted()
{
	var id_factura = $('#id_factura').val();
	var dataString = 'process=deleted' + '&id_factura=' + id_factura;
	$.ajax({
		type : "POST",
		url : "borrar_factura.php",
		data : dataString,
		dataType : 'json',
		success : function(datax) {
			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo=="Success")
			{
				setInterval("location.reload();", 1000);
				$('#deleteModal').hide(); 
			}
		}
	});
}

function totalas()
{
	var simbolo = $("#simbolo").val();
	var totals = parseFloat($("#totals").val());
	var t1 = parseFloat($("#totalc").val());
	var t2 = parseFloat($("#totalt").val());
	var t3 = parseFloat($("#totala").val());
	if(isNaN(t1))
		t1 = 0;
	if(isNaN(t2))
		t2 = 0;
	if(isNaN(t3))
		t3 = 0;
	
	total = t1+t2+t3;

	if(total<totals)
	{
		var dif  = totals-total;
		$("#observaciones").val("Hay una diferencia negativa de "+simbolo+""+dif.toFixed(2));
		$("#observaciones").attr("readonly",true);
	}
	else
	{
		$("#observaciones").val("");	
		$("#observaciones").attr("readonly",false);
	}
}

function corte()
{
	var totalc = $("#totalc").val();
	var totalt = $("#totalt").val();
	var totala = $("#totala").val();
	var totals = $("#totals").val();
	var observaciones = $("#observaciones").val();
	var ajaxdata = "process=corte&sistema="+totals+"&efectivo="+totala+"&cheque="+totalc+"&tarjeta="+totalt+"&observaciones="+observaciones;
	$.ajax({
		type:'POST',
		url:"corte_caja.php",
		data: ajaxdata,			
		dataType: 'json',
		success: function(datax)
		{	

			display_notify(datax.typeinfo, datax.msg);
			if(datax.typeinfo == "Success")
			{
				setInterval("reload1();", 1000);		
			}
		}		
		});
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
			url : "cobros.php",
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
				$("#table_factura").append(tr_add);
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
	$("#table_factura tr").each(function()
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
	$("#table_factura tr").each(function()
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
		url : "cobros.php",
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
	$("#table_factura tr").each(function()
	{
		var id = $(this).attr("id");
		if(id == "fl"+ide)
		{
			$(this).remove();
		}
	});
	totales();
});	
function senddata(id_c=0)
{
	var simbolo = $("#simbolo").val();
    if (id_c == 0) 
    {
		var total=$('#total_final').text();
		var id_paciente=$('#id_paciente').val();
		var fecha=$("#fecha").val();
		var dui=$("#dui").val();
		var forma=$("#forma").val();
		var cliente = $("#cliente").val();
		var tipo_cliente = $("#tipo_cliente").val();
		var documento=$("#documento").val();
	}
	else
	{
		var total="25";
		var id_paciente="-999";
		var fecha=$("#fecha").val();
		var dui=$("#dui").val();
		var forma=$("#forma").val();
		var tipo_cliente = "Paciente";
		var documento=$("#documento").val();
	}
	var process=$('#process').val();
	var datos = "";
	var cuantos = 0;
	var fallos = 0;
	if (id_c == 0) 
	{
		$("#table_factura tr").each(function()
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
	}
	else
	{
		datos = datos+"-999"+","+"1"+","+"1000"+"|";
		cuantos = 1;
	}
	if(fallos>0)
	{
		display_notify("Error", "Verifique que todos los campos esten completos antes de continuar")
	}
	else
	{
		var dataString ='process='+process+'&id_paciente='+id_paciente+'&fecha='+fecha+'&total='+total+"&datos="+datos;
			dataString += "&cuantos="+cuantos+"&dui="+dui+"&documento="+documento+"&pago="+forma+"&tipo_c="+tipo_cliente+"&cliente="+cliente;
		$.ajax({
            type: "POST",
            url: "cobros.php",
            data: dataString,
            dataType : 'json',
            success: function(datax)
            {	
				if (id_c == 0) {
					display_notify(datax.typeinfo,datax.msg);
					if(datax.typeinfo=="Success")
					{
						setInterval("reload1();",1500);
					}
				}
					
			}
        });
	}		          
}
