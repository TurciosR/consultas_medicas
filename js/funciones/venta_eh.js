
$("#id_sucursal").select2();
$("#tipo_impresion").select2();

$(document).on('change', '#id_sucursal', function(event) {
  event.preventDefault();
  $("#inventable").html("");
  setTimeout(function(){
    totales();
  },250);
});
function addProductList(id_prod) {
	$(".sel_r").select2("close");
	$(".sel").select2("close");
	$(".select2-dropdown").hide();
	$('#inventable').find('tr#filainicial').remove();
	id_prod = $.trim(id_prod);
	id_factura= parseInt($('#id_factura').val());
	if(isNaN(id_factura))
	{
		id_factura=0;
	}
  id_sucursal = $("#id_sucursal").val();

	urlprocess = "venta_h.php";
	var dataString = 'process=consultar_stock' + '&id_producto=' + id_prod+ '&id_factura=' + id_factura+ '&id_sucursal=' + id_sucursal;
	$.ajax({
		type: "POST",
		url: urlprocess,
		data: dataString,
		dataType: 'json',
		success: function(data) {
			var precio_venta = data.precio_venta;
			var unidades = data.unidades;
			var existencias = data.stock;
			var perecedero = data.perecedero;
			var descrip_only = data.descripcion;
			var fecha_fin_oferta = data.fecha_fin_oferta;
			var exento = data.exento;
			var categoria=data.categoria;
			var select_rank=data.select_rank;

			var preciop_s_iva = parseFloat(data.preciop_s_iva);

			var tipo_impresion=$('#tipo_impresion').val();

			timestamp = (new Date().getTime()).toString(36)

			var filas = parseInt($("#filas").val());
			var exento ="<input type='hidden' id='exento' name='exento' value='"+exento+"'>";
			var subtotal = parseFloat(data.preciop);
			subt_mostrar = subtotal.toFixed(2);
			var cantidades = "<td class='cell100 column10 text-success'><div class='col-xs-2'><input type='text'  class='txt_box decimal2 "+categoria+" cant' id='cant' name='cant' value='' style='width:60px;'></div></td>";
			tr_add = '';
			tr_add += "<tr  class='row100 head "+timestamp+" ' id='" + filas + "'>";
			tr_add += "<td hidden class='cell100 column10 text-success id_pps'><input type='hidden' id='unidades' name='unidades' value='" + data.unidadp + "'>" + id_prod + "</td>";
			tr_add += "<td class='cell100 column30 text-success'>" + descrip_only + exento+ '</td>';
			tr_add += "<td hidden class='cell100 column10 text-success' id='cant_stock'>" + existencias + "</td>";
			tr_add += "<td class='cell100 column10 text-success' id='cant_perpre'>" + round(existencias/data.unidadp,2) + "</td>";
			tr_add += cantidades;
			tr_add += "<td class='cell100 column10 text-success preccs'>" + data.select + "</td>";
			tr_add += "<td class='cell100 column10 text-success descp'><input type'text' id='dsd' class='form-control' value='" + data.descripcionp + "' class='txt_box' readonly></td>";
			tr_add += "<td class='cell100 column10 text-success rank_s'>" + data.select_rank + "</td>";
			tr_add += "<td class='cell100 column10 text-success'><input type='hidden'  id='precio_venta_inicial' name='precio_venta_inicial' value='" + data.preciop + "'><input type='hidden'  id='precio_sin_iva' name='precio_sin_iva' value='" + preciop_s_iva + "'><input type='text'  class='form-control decimal' readonly id='precio_venta' name='precio_venta' value='" + data.preciop + "'></td>";
			if(tipo_impresion=="CCF")
			{
				tr_add += "<td class='ccell100 column10'>" + "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='"+"0.00"+"'>" + "<input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='" +"0.00"+ "'readOnly></td>";

			}
			else
			{
				tr_add += "<td class='ccell100 column10'>" + "<input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='"+"0.00"+"'>" + "<input type='text'  class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar'  value='" + "0.00" + "'readOnly></td>";

			}

			tr_add += '<td class="cell100 column10  text-center"><input id="delprod" type="button" class="btn btn-danger fa Delete"  value="&#xf1f8;"> ';
			tr_add += "" ;
			tr_add += ' </td>';
			tr_add += '</tr>';
			//numero de filas
			filas++;

			$("#inventable").prepend(tr_add);
			$(".decimal2").numeric({negative:false,decimal:false});
			$(".decimal").numeric({negative:false,decimalPlaces:2});
			$(".86").numeric({negative:false,decimalPlaces:4});
			$('#items').val(filas);
			$(".sel").select2();
			$(".sel_r").select2();
			$('#inventable tr:first').find("#cant").focus();
			totales();
		}
	});
	totales();
}

function totales() {
	//impuestos
	var iva = $('#porc_iva').val();
	var porc_percepcion = $("#porc_percepcion").val();
	var porc_retencion1 = $("#porc_retencion1").val();
	var porc_retencion10 = $("#porc_retencion10").val();

	var id_tipodoc = $("#tipo_impresion option:selected").val();
	var monto_retencion1 = parseFloat($('#monto_retencion1').val());
	var monto_retencion10 = parseFloat($('#monto_retencion10').val());
	var monto_percepcion = $('#monto_percepcion').val();
	var porcentaje_descuento = parseFloat($("#porcentaje_descuento").val());

	var total_sin_iva = 0;
	//fin impuestos

	var tipo_impresion = $('#tipo_impresion').val();

	var urlprocess = "venta_h.php";
	var i = 0, total = 0;
	totalcantidad = 0;

	var total_gravado = 0;

	var total_exento = 0;

	var subt_gravado = 0;

	var subt_exento = 0;

	var subtotal = 0;

	var total_descto = 0;
	var total_sin_descto = 0;
	var subt_descto = 0;
	var total_final = 0;
	var subtotal_sin_iva = 0;
	var StringDatos = '';
	var filas = 0;
	var total_iva = 0;
	if (tipo_impresion=="CCF") {

		$("#inventable tr").each(function() {
			subt_cant = $(this).find("#cant").val();
			ex = parseInt($(this).find('#exento').val());

			if (isNaN(subt_cant) || subt_cant == "") {
				subt_cant = 0;
			}
			subt_gravado=0;
			subt_exento=0;

			if (ex==0) {
				subt_gravado= $(this).find("#subtotal_fin").val();
			}
			else {
				subt_exento=$(this).find("#subtotal_fin").val();
			}

			totalcantidad += parseFloat(subt_cant);

			total_gravado += parseFloat(subt_gravado);

			total_exento += parseFloat(subt_exento);

			subtotal+= parseFloat(subt_exento) + parseFloat(subt_gravado);;

			filas += 1;
		});

		total_gravado = round(total_gravado, 4);
		//descuento
		var total_descuento = 0;
		if (porcentaje_descuento > 0.0) {
			total_descuento = (porcentaje_descuento / 100) * total_final
		} else {
			total_descuento = 0;
		}
		var total_descuento_mostrar = total_descuento.toFixed(2)
		var total_mostrar = subtotal.toFixed(2)
		totcant_mostrar = totalcantidad.toFixed(2)

		//console.log(subt_gravado);
		$('#totcant').text(totcant_mostrar);


		var total_sin_iva_mostrar = total_gravado.toFixed(2);
		$('#total_gravado_sin_iva').html(total_sin_iva_mostrar);
		txt_war = "class='text-danger'"


		$('#total_gravado').html(total_mostrar);
		$('#total_exenta').html(total_exento.toFixed(2));

		var total_iva_mostrar = 0.00;

		total_iva=total_gravado*(parseFloat(iva));
		total_iva=round(total_iva, 2)
		total_gravado_iva=  total_gravado+total_iva;


		total_gravado_iva_mostrar = total_gravado_iva.toFixed(2);
		$('#total_gravado_iva').html(total_gravado_iva_mostrar); //total gravado con iva
		$('#total_iva').html(total_iva.toFixed(2));

		var total_retencion1 = 0
		var total_retencion10 = 0
		var total_percepcion = 0
		if (total_gravado >= monto_retencion1)
		total_retencion1 = total_gravado * porc_retencion1;
		if (total_gravado >= monto_retencion10)
		total_retencion10 = total_gravado * porc_retencion10;
		var total_final = (total_gravado - total_descuento + total_percepcion) - (total_retencion1 + total_retencion10) + total_iva + total_exento;

		total_final_mostrar = total_final.toFixed(2);
		$('#total_percepcion').html(0);
		total_retencion1_mostrar = total_retencion1.toFixed(2);
		total_retencion10_mostrar = total_retencion10.toFixed(2);
		$('#total_retencion').html('0.00');
		if (parseFloat(total_retencion1) > 0.0)
		$('#total_retencion').html(total_retencion1_mostrar);
		if (parseFloat(total_retencion10) > 0.0)
		$('#total_retencion').html(total_retencion10_mostrar);
		//total final
		$('#total_final').html(total_descuento_mostrar);
		$('#totalfactura').val(total_final_mostrar);

		$('#totcant').html(totcant_mostrar);
		$('#items').val(filas);
		$('#totaltexto').load("venta_h.php", {
			'process': 'total_texto',
			'total': total_final_mostrar
		});
		$('#monto_pago').html(total_final_mostrar);

		$('#totalfactura').val(total_final_mostrar);

	}
	else
	{
		$("#inventable tr").each(function() {
			subt_cant = $(this).find("#cant").val();
			ex = parseInt($(this).find('#exento').val());

			if (isNaN(subt_cant) || subt_cant == "") {
				subt_cant = 0;
			}
			subt_gravado=0;
			subt_exento=0;

			if (ex==0) {
				subt_gravado= $(this).find("#subtotal_fin").val();
			}
			else {
				subt_exento=$(this).find("#subtotal_fin").val();
			}

			totalcantidad += parseFloat(subt_cant);

			total_gravado += parseFloat(subt_gravado);

			total_exento += parseFloat(subt_exento);

			subtotal+= parseFloat(subt_exento) + parseFloat(subt_gravado);;

			filas += 1;
		});

		total_gravado = round(total_gravado, 4);
		//descuento
		var total_descuento = 0;
		if (porcentaje_descuento > 0.0) {
			total_descuento = (porcentaje_descuento / 100) * total_final
		} else {
			total_descuento = 0;
		}
		var total_descuento_mostrar = total_descuento.toFixed(2)
		var total_mostrar = subtotal.toFixed(2)
		totcant_mostrar = totalcantidad.toFixed(2)

		//console.log(subt_gravado);
		$('#totcant').text(totcant_mostrar);


		var total_sin_iva_mostrar = total_gravado.toFixed(2);
		$('#total_gravado_sin_iva').html(total_sin_iva_mostrar);
		txt_war = "class='text-danger'"


		$('#total_gravado').html(total_mostrar);
		$('#total_exenta').html(total_exento.toFixed(2));

		var total_iva_mostrar = 0.00;

		total_iva=0;
		total_iva=round(total_iva, 2)
		total_gravado_iva=  total_gravado+total_iva;


		total_gravado_iva_mostrar = total_gravado_iva.toFixed(2);
		$('#total_gravado_iva').html(total_gravado_iva_mostrar); //total gravado con iva
		$('#total_iva').html(total_iva.toFixed(2));

		var total_retencion1 = 0
		var total_retencion10 = 0
		var total_percepcion = 0
		if (total_gravado >= monto_retencion1)
		total_retencion1 = total_gravado * porc_retencion1;
		if (total_gravado >= monto_retencion10)
		total_retencion10 = total_gravado * porc_retencion10;
		var total_final = (total_gravado - total_descuento + total_percepcion) - (total_retencion1 + total_retencion10) + total_iva + total_exento;

		total_final_mostrar = total_final.toFixed(2);
		$('#total_percepcion').html(0);
		total_retencion1_mostrar = total_retencion1.toFixed(2);
		total_retencion10_mostrar = total_retencion10.toFixed(2);
		$('#total_retencion').html('0.00');
		if (parseFloat(total_retencion1) > 0.0)
		$('#total_retencion').html(total_retencion1_mostrar);
		if (parseFloat(total_retencion10) > 0.0)
		$('#total_retencion').html(total_retencion10_mostrar);
		//total final
		$('#total_final').html(total_descuento_mostrar);
		$('#totalfactura').val(total_final_mostrar);

		$('#totcant').html(totcant_mostrar);
		$('#items').val(filas);
		$('#totaltexto').load("venta_h.php", {
			'process': 'total_texto',
			'total': total_final_mostrar
		});
		$('#monto_pago').html(total_final_mostrar);

		$('#totalfactura').val(total_final_mostrar);
	}

}


$("#scrollable-dropdown-menu #producto_buscar").typeahead({
	highlight: true,
},
{
	limit:100,
	name: 'productos',
	display: 'producto',
	source: function show(q, cb, cba) {
		console.log(q);
    id_sucursal = $("#id_sucursal").val();
		var url = 'autocomplete_producto2.php' + "?query=" + q+"&id_sucursal="+id_sucursal;
		$.ajax({ url: url })
		.done(function(res) {
			cba(JSON.parse(res));
		})
		.fail(function(err) {
			alert(err);
		});
	}
}).on('typeahead:selected', onAutocompleted);

function onAutocompleted($e, datum) {

	$('.typeahead').typeahead('val', '');

	var prod0=datum.producto;
	var prod= prod0.split("|");
	var id_prod = prod[0];
	var descrip = prod[1];
	addProductList(id_prod);
}

$("#scrollable-dropdown-menu #composicion").typeahead({
	highlight: true,
},
{
	limit:100,
	name: 'productos',
	display: 'producto',
	source: function show(q, cb, cba) {
		console.log(q);
    id_sucursal = $("#id_sucursal").val();
		var url = 'autocomplete_producto3.php' + "?query=" + q+"&id_sucursal="+id_sucursal;
		$.ajax({ url: url })
		.done(function(res) {
			cba(JSON.parse(res));
		})
		.fail(function(err) {
			alert(err);
		});
	}
}).on('typeahead:selected', onAutocompleted2);

function onAutocompleted2($e, datum) {

	$('.typeahead').typeahead('val', '');

	var prod0=datum.producto;
	var prod= prod0.split("|");
	var id_prod = prod[0];
	var descrip = prod[1];
	addProductList(id_prod);
}

$(document).on('hidden.bs.modal', function(e)
{
  var target = $(e.target);
  target.removeData('bs.modal').find(".modal-content").html('');
});

document.addEventListener('keydown', event => {
  if (event.ctrlKey && event.keyCode==16) {

    event.stopPropagation();
    if ($('#a').attr('hidden')) {
      $('#a').removeAttr('hidden');
      $('#b').attr('hidden', 'hidden');
      $('#producto_buscar').focus();
    }
    else {
      $('#b').removeAttr('hidden');
      $('#a').attr('hidden', 'hidden');
      $('#composicion').focus();
    }
  }
}, false);

    function round(value, decimals) {
      return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
    }

    $(document).on('click', '.btnServicio', function(event) {
      var monto =$(".montoServicio").val();

      if (isNaN(parseFloat(monto))) {
        display_notify("Error","Agrege un precio");
      }
      else {
        idServicio=$(".idServicio").val();
        servicio = $( ".idServicio option:selected" ).text();

        monto = Number(monto).toFixed(2)
        tr_add="";
        tr_add+="<tr class='row100 head service' id='NaN'>";
        tr_add+="<td class='cell100 column10 text-success id_pps' hidden=''>";
        tr_add+="<input id='unidades' name='unidades' value='1' type='hidden'>"+idServicio+"";
        tr_add+="</td>";
        tr_add+="<td class='cell100 column30 text-success'>"+servicio+"";
        tr_add+="<input id='exento' name='exento' value='0' type='hidden'>";
        tr_add+="</td>";
        tr_add+="<td class='cell100 column10 text-success' id='cant_stock'>1";
        tr_add+="</td>";
        tr_add+="<td class='cell100 column10 text-success'>";
        tr_add+="<div class='col-xs-2'><input disabled class='txt_box decimal2 cant' id='cant' name='cant' value='1' style='width:60px;' type='text'>";
        tr_add+="</div>";
        tr_add+="</td>";
        tr_add+="<td class='cell100 column10 text-success preccs'>";
        tr_add+="<select disabled class='sel form-control'>";
        tr_add+="<option value='0'>UNIDAD</option>";
        tr_add+="</select>";
        tr_add+="</td>";
        tr_add+="<td class='cell100 column10 text-success descp'>";
        tr_add+="<input type'text'='' id='dsd' class='form-control' value='SERVICIOS MEDICOS' readonly=''>";
        tr_add+="</td>";
        tr_add+="<td class='cell100 column10 text-success rank_s'>";
        tr_add+="<select disabled class='sel_r precio_r form-control '>";
        tr_add+="<option value='"+monto+"' selected=''>"+monto+"</option>";
        tr_add+="</select>";
        tr_add+="</td>";
        tr_add+="<td class='cell100 column10 text-success'>";
        tr_add+="<input id='precio_venta_inicial' name='precio_venta_inicial' value='"+monto+"' type='hidden'>";
        tr_add+="<input id='precio_sin_iva' name='precio_sin_iva' value='"+round(monto/1.13,4)+"' type='hidden'>";
        tr_add+="<input class='form-control decimal' readonly='' id='precio_venta' name='precio_venta' value='"+monto+"' type='text'>";
        tr_add+="</td>";
        tr_add+="<td class='ccell100 column10'>";
        tr_add+="<input id='subtotal_fin' name='subtotal_fin' value='"+monto+"' type='hidden'>";
        tr_add+="<input class='decimal txt_box form-control' id='subtotal_mostrar' name='subtotal_mostrar' value='"+monto+"' readonly='' type='text'>";
        tr_add+="</td>";
        tr_add+="<td class='cell100 column10  text-center'>";
        tr_add+="<input id='delprod' class='btn btn-danger fa fa-trash Delete' value='' type='button'>";
        tr_add+="</td>";
        tr_add+="</tr>";
        $("#inventable").prepend(tr_add);

        $(".sel").select2();
        $(".sel_r").select2();

        $(".closeServicio").click();

        setTimeout(
          function() {
            totales();
          },
          250
        );
      }
    });
    function subt(qty,price){
      subtotal=parseFloat(qty)*parseFloat(price);
      subtotal=round(subtotal,4);
      return subtotal;
    }
    $(document).on("click", ".Delete", function() {
    	$(this).parents("tr").remove();
    	totales();
    });
	$(document).on("click","#preventax",function(){
		swal({
			title:"Esta a punto de aplicar los insumos agregados al paciente!",
			text: "Esta seguro de aplicarlos?",
			showCancelButton:true,
			confirmButtonClass:'btn-danger',
			confirmButtonText:"Si aplicar",
			cancelButtonText:"No! no aplicar",
			closeOnConfirm:true,
			closeOnCancel:false,
		}, function(isConfirm){
			if(isConfirm){
				guardar_preventa();
			}else{
				swal('Cancelado', 'Operacion cancelada', 'error');
			}
			
		});
		
	});

    $(document).on('change', '.sel_prec', function() {
    	var tr = $(this).parents("tr");
    	var precio = $(this).find(':selected').val();
    	tr.find("#precio_venta").val(precio);
    	actualiza_subtotal(tr);
    });
    $(document).on('keyup', '#cant', function() {
    	fila = $(this).parents('tr');
    	id_producto = fila.find('.id_pps').text();
    	existencia = parseFloat(fila.find('#cant_stock').text());
    	existencia=round(existencia, 4);
    	a_cant=$(this).val();
    	unidad= parseInt(fila.find('#unidades').val());
    	a_cant=parseFloat(a_cant*unidad);
    	a_cant=round(a_cant, 4);

    	//console.log(a_cant);
    	a_asignar=0;

    	$('#inventable tr').each(function(index) {

    		if($(this).find('.id_pps').text()==id_producto)
    		{
    			if (!$(this).hasClass('service')) {
    				t_cant=parseFloat($(this).find('#cant').val());
    				t_cant=round(t_cant, 4);
    				if(isNaN(t_cant))
    				{
    					t_cant=0;
    				}
    				t_unidad=parseInt($(this).find('#unidades').val());
    				if(isNaN(t_unidad))
    				{
    					t_unidad=0;
    				}
    				t_cant=parseFloat((t_cant*t_unidad));
    				a_asignar=a_asignar+t_cant;
    				a_asignar=round(a_asignar,4);
    			}

    		}
    	});
    	//console.log(existencia);
    	//console.log(a_asignar);

    	if(a_asignar>existencia)
    	{
    		val = existencia-(a_asignar-a_cant);
    		val = val/unidad;
    		val=Math.trunc(val);
    		val =parseInt(val);
    		$(this).val(val);
    		setTimeout(function() {totales();}, 1000);
    	}
    	else
    	{
    		totales();
    	}
    	var tr = $(this).parents("tr");

    	id_presentacion_p = fila.find('.sel').val();
    	//Ranking de precios
    	$.ajax({
    		type:'POST',
    		url:'venta_h.php',
    		data:'process=cons_rank&id_producto='+id_producto+'&id_presentacion='+id_presentacion_p+'&cantidad='+a_cant,
    		dataType:'JSON',
    		success:function(datax)
    		{
    			tr.find(".rank_s").html(datax.precios);
    			tr.find("#precio_venta").val(datax.precio);
    			$(".sel_r").select2();
    		}
    	});
    	setTimeout(function(){ actualiza_subtotal(tr); }, 300);
    });

    function truncateDecimals (num, digits) {
        var numS = num.toString(),
            decPos = numS.indexOf('.'),
            substrLength = decPos == -1 ? numS.length : 1 + decPos + digits,
            trimmedResult = numS.substr(0, substrLength),
            finalResult = isNaN(trimmedResult) ? 0 : trimmedResult;

        return parseFloat(finalResult);
    }

    function actualiza_subtotal(tr) {
    	var iva = parseFloat($('#porc_iva').val());
    	var precio_sin_iva = parseFloat(tr.find('#precio_sin_iva').val());
    	var existencias = tr.find('#cant_stock').text();

    	var tipo_impresion = $('#tipo_impresion').val();

    	if (tipo_impresion!='CCF') {

    		var cantidad = tr.find('#cant').val();
    		if (isNaN(cantidad) || cantidad == "") {
    			cantidad = 0;
    		}
    		var precio = tr.find('#precio_venta').val();
    		var precio_oculto = tr.find('#precio_venta').val();

    		if (isNaN(precio) || precio == "") {
    			precio = 0;
    		}
    		var subtotal = subt(cantidad, precio);
    		var subt_mostrar = round(subtotal,2);
    		tr.find("#subtotal_fin").val(subt_mostrar);
    		tr.find("#subtotal_mostrar").val(subt_mostrar);
    		totales();
    	}
    	else {
    		var cantidad = tr.find('#cant').val();
    		if (isNaN(cantidad) || cantidad == "") {
    			cantidad = 0;
    		}
    		var precio = tr.find('#precio_sin_iva').val();

    		if (isNaN(precio) || precio == "") {
    			precio = 0;
    		}
    		var subtotal = subt(cantidad, precio);
    		var subt_mostrar = subtotal.toFixed(4);

    		tr.find("#subtotal_fin").val(subt_mostrar);
    		var subt_mostrar = round(subtotal,2);
    		tr.find("#subtotal_mostrar").val(subt_mostrar);
    		totales();
    	}

    }

    $(document).on('keyup', '.cant', function(evt){
    	var tr = $(this).parents("tr");

    	if(evt.keyCode == 13)
    	{
    		num=parseFloat($(this).val());
    		if(isNaN(num))
    		{
    			num=0;
    		}
    		if($(this).val()!=""&&num>0)
    		{
    			tr.find('.sel').select2("open");
    		}
    	}

    	fila = $(this).closest('tr');
    	id_producto = fila.find('.id_pps').text();
    	existencia = parseFloat(fila.find('#cant_stock').text());
    	existencia=round(existencia,4);
    	a_cant=parseFloat(fila.find('#cant').val());
    	unidad= parseInt(fila.find('#unidades').val());
    	a_cant=parseFloat(a_cant*unidad);
    	a_cant=round(a_cant, 4);
    	//console.log(a_cant);
    	//console.log(id_producto);
    	a_asignar=0;

    	$('#inventable tr').each(function(index) {

    		if($(this).find('.id_pps').text()==id_producto)
    		{
    			if (!$(this).hasClass('service')) {

    							t_cant=parseFloat($(this).find('#cant').val());
    							t_cant=round(t_cant, 4);
    							if(isNaN(t_cant))
    							{
    								t_cant=0;
    							}
    							t_unidad=parseInt($(this).find('#unidades').val());
    							if(isNaN(t_unidad))
    							{
    								t_unidad=0;
    							}
    							t_cant=parseFloat((t_cant*t_unidad));
    							a_asignar=a_asignar+t_cant;
    							a_asignar=round(a_asignar,4);
    			}
    		}
    	});
    	//console.log(existencia);
    	//console.log(a_asignar);

    	if(a_asignar>existencia)
    	{
    		val = existencia-(a_asignar-a_cant);
    		val = val/unidad;
    		val=Math.trunc(val);
    		val =parseInt(val);
    		fila.find('#cant').val(val);
    	}

    	actualiza_subtotal(tr);
    });

    $(document).on('select2:close', '.sel_r', function()
    {

    	if ($('#a').attr('hidden')) {
    		$('#composicion').focus();
    	}
    	else {
    		$('#producto_buscar').focus();
    	}
    });

    $(document).on('select2:close', '.sel', function(event)
    {
    	var tr = $(this).parents("tr");
    	var cantid = tr.find("#cant").val();
    	var id_presentacion = $(this).val();
    	var a = $(this);
      id_sucursal = $("#id_sucursal").val();
    	//console.log(id_presentacion);
    	$.ajax({
    		url: 'venta_h.php',
    		type: 'POST',
    		dataType: 'json',
    		data: 'process=getpresentacion'+"&id_presentacion="+id_presentacion+"&cant="+cantid+"&id_sucursal="+id_sucursal,
    		success: function(data) {
    			a.closest('tr').find('.descp').html(data.descripcion);
    			a.closest('tr').find('#precio_venta').val(data.precio);
    			a.closest('tr').find('#unidades').val(data.unidad);
    			a.closest('tr').find('#precio_sin_iva').val(data.preciop_s_iva);
    			a.closest('tr').find(".rank_s").html(data.select_rank);
    			fila = a.closest('tr');
    			id_producto = fila.find('.id_pps').text();
    			existencia = parseFloat(fila.find('#cant_stock').text());
    			existencia=round(existencia,4);
    			a_cant=parseFloat(fila.find('#cant').val());
    			unidad= parseInt(fila.find('#unidades').val());
    			cantperpre = round(existencia/unidad,2);
    			$("#cant_perpre").html(cantperpre);
    			a_cant=parseFloat(a_cant*data.unidad);
    			a_cant=round(a_cant, 4);
    			$(".sel_r").select2();
    			a.closest('tr').find('.sel_r').select2("open");
    			//console.log(a_cant);
    			//console.log(id_producto);
    			a_asignar=0;

    			$('#inventable tr').each(function(index) {

    				if($(this).find('.id_pps').text()==id_producto)
    				{
    					if (!$(this).hasClass('service')) {
    						t_cant=parseFloat($(this).find('#cant').val());
    						t_cant=round(t_cant, 4);
    						if(isNaN(t_cant))
    						{
    							t_cant=0;
    						}
    						t_unidad=parseInt($(this).find('#unidades').val());
    						if(isNaN(t_unidad))
    						{
    							t_unidad=0;
    						}
    						t_cant=parseFloat((t_cant*t_unidad));
    						a_asignar=a_asignar+t_cant;
    						a_asignar=round(a_asignar,4);
    					}
    					}

    			});
    			//console.log(existencia);
    			//console.log(a_asignar);

    			if(a_asignar>existencia)
    			{
    				val = existencia-(a_asignar-a_cant);
    				val = val/unidad;
    				val=Math.trunc(val);
    				val =parseInt(val);
    				fila.find('#cant').val(val);
    			}

    			var tr = a.closest('tr');
    			actualiza_subtotal(tr);
    		}
    	});
    	setTimeout(function() {
    		totales();
    	}, 200);


    });

    $(document).on('change', '.sel_r', function(event) {
    	var a = $(this).closest('tr');
    	precio=parseFloat($(this).val());
    	a.find('#precio_venta').val(precio);
    	a.find("#precio_sin_iva").val(precio/1.13);
    	actualiza_subtotal(a);
    });
	function guardar_preventa()
	{
		sel_vendedor=1;
		var i = 0;
		var StringDatos = "";
		var id = '1';
		var id_empleado = 0;
		var id_cliente = $("#id_cliente option:selected").val();
		var items = $("#items").val();
		var msg = "";
		//IMPUESTOS
		error=false;
	
	
		var total_percepcion = $('#total_percepcion').text();
	
		var id_factura=parseInt($('#id_factura').val());
	
		if(isNaN(id_factura))
		{
			id_factura=0;
		}
	
		$.ajax({
			url: 'preventa_h.php',
			type: 'POST',
			dataType: 'json',
			data: {
				process: 'fact_verification',
				id_factura: id_factura,
			},
			success: function (xdatos) {
				if (xdatos.res=="OK")
				{
					var subtotal = $('#total_gravado_iva').text();/*total gravado mas iva subtotal*/
					var suma_gravada= $('#total_gravado_sin_iva').text();/*total sumas sin iva*/
					var sumas= $('#total_gravado').text();/*total sumas sin iva + exentos*/
					var iva = $('#total_iva').text(); /*porcentaje de iva de la factura*/
					var retencion = $('#total_retencion').text();/*total retencion cuando un cliente retiene 1 o 10 %*/
					var venta_exenta =$('#total_exenta').text();/*total venta exenta*/
					var total = $('#totalfactura').val();
					var id_vendedor = $("#vendedor option:selected").val();
					var tipo_impresion= $('#tipo_impresion').val();
					var id_sucursal = $("#id_sucursal").val();
					total=0;
					var fecha_movimiento = $("#fecha").val();
					var id_prod = 0;

					var id_recepcion = $("#id_recepcion").val();
					//alert(id_recepcion);
					var verificaempleado = 'noverificar';
					var verifica = [];
					var array_json = new Array();
					$("#inventable tr").each(function(index) {
						var id_detalle = $(this).attr("id_detalle");
						if(id_detalle == undefined)
						{
							id_detalle = "";
						}
						var id = $(this).find("td:eq(0)").text();
						var id_presentacion = $(this).find('.sel').val();
						var precio_venta = $(this).find("#precio_venta").val();
						var cantidad = $(this).find("#cant").val();
						var unidades = $(this).find("#unidades").val();
						var exento = $(this).find("#exento").val();
						var subtotal = $(this).find("#subtotal_fin").val();
						var servicio = 0;
						if ($(this).hasClass('service')) {
							servicio = 1;	
						}
						if (cantidad && precio_venta) {
							var obj = new Object();
							obj.id_detalle = id_detalle;
							obj.id = id;
							obj.precio = precio_venta;
							obj.cantidad = cantidad;
							obj.unidades = unidades;
							obj.subtotal = subtotal;
							total= parseFloat(total)+parseFloat(subtotal);
							obj.id_presentacion = id_presentacion;
							obj.exento = exento;
							obj.servicio = servicio;
							//convert object to json string
							text = JSON.stringify(obj);
							array_json.push(text);
							i = i + 1;
						}
						else
						{
							error=true
						}
	
					});
					json_arr = '[' + array_json + ']';
	
					var urlprocess = "venta_h.php";
					var id_cotizacion = "";
	
					if (i==0) {
						error=true
					}
	
					var dataString = 'process=insert_preventa' + '&cuantos=' + i + '&fecha_movimiento=' + fecha_movimiento;
					dataString += '&id_cliente=' + id_cliente + '&total=' + total;
					dataString += '&id_vendedor=' + id_vendedor + '&json_arr=' + json_arr;
					dataString += '&retencion=' + retencion;
					dataString += '&total_percepcion=' + total_percepcion;
					dataString += '&iva=' + iva;
					dataString += '&items=' + items;
					dataString += '&subtotal=' + subtotal;
					dataString += '&sumas=' + sumas;
					dataString += '&venta_exenta=' + venta_exenta;
					dataString += '&suma_gravada=' + suma_gravada;
					dataString += '&tipo_impresion=' + tipo_impresion;
					dataString += '&id_factura=' + id_factura;
					dataString += '&id_sucursal=' + id_sucursal;
					dataString += '&id_recepcion=' + id_recepcion;
					if (id_cliente == "") {
						msg = 'Seleccione un Cliente!';
						sel_vendedor = 0;
					}
	
					if (tipo_impresion == "") {
						msg = 'Seleccione un tipo de impresion!';
						sel_vendedor = 0;
					}
	
					if (i == 0) {
						msg = 'Seleccione al menos un producto !';
						sel_vendedor = 0;
					}
	
					if (sel_vendedor == 1) {
						$("#inventable tr").remove();
						$.ajax({
							type: 'POST',
							url: urlprocess,
							data: dataString,
							dataType: 'json',
							success: function(datax) {
								if (datax.typeinfo == "Success") {
									swal({
											html:true,
											title: "<b>Referencia <i># "+datax.referencia+"</i><br>$ "+datax.tot+"</b>",
											text: "<b>Presione OK para continuar</b>",
											type: "warning",
											showCancelButton: false,
											confirmButtonColor: '',
											confirmButtonText: 'OK',
											closeOnConfirm: false,
											closeOnCancel: true
									 }, function(isConfirm) {
										 if (isConfirm){
											 setInterval("reload1();", 500);
											} else {
											}
	
									 });
	
	
								}
							}
						});
					} else {
						display_notify('Warning', msg);
					}
				}
				else {
					swal({
							title: "ERROR",
							text: "Esta preventa ya ha sido facturada no puede realizar mas cambios",
							type: "warning",
							showCancelButton: false,
							confirmButtonColor: '',
							confirmButtonText: 'OK',
							closeOnConfirm: false,
							closeOnCancel: true
					 }, function(isConfirm) {
						 if (isConfirm){
							 setInterval("reload1();", 1000);
							} else {
							}
	
					 });
				}
			}
	
		})
	
	
	}


	function reload1(){
		location.href = 'admin_hospitalizaciones.php';
	}