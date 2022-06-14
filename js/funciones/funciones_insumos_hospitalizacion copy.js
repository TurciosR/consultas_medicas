var urlprocess_enviar = 'asignar_insumos_hospitalizacion.php';
var id_usb_enviar = '1';
var tabla_buscar_enviar = 'insumos_hospitalizacion';
var id_servicio_buscar = '5';

$(document).ready(function() {
    $(".select").select2();
    generar2("");
    $("#buscarRecepcion").click(function() {
        generar2("");
    });
    var idRecepcion = $("#id_recepcion").val();
    $("#paciente_replace").hide();
    $("#microciru_replace").hide();
    $("#hora_inicio_replace").hide();
    $("#hora_fin_replace").hide();
    $("#servicio_buscar").hide();
    $("#examen_buscar").hide();
    $("#paciente").keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
    $('html,body').animate({
        scrollTop: $(".focuss").offset().top
    }, 1500);
    //CAMBIO EN LOS PISOS


    //CAMBIO EN LOS PISOS



    $("#hora_entrada").timepicki();
    $("#producto_buscar").typeahead({
        source: function(query, process) {
            $.ajax({
                type: 'POST',
                url: 'facturacion_autocomplete1.php',
                data: 'query=' + query + '&id_usb=' + id_usb_enviar,
                dataType: 'JSON',
                async: false,
                success: function(data) {
                    process(data);
                }
            });
        },
        updater: function(selection) {
            var prod0 = selection;
            var prod = prod0.split("|");
            var id_prod = prod[0];
            var descrip = prod[2];
            var tipo = prod[3];
            var cantidad_general = 0;
            $("#inventable tr").each(function() {
                if ($(this).find("#tipopr").val() == "P") {
                    var id = $(this).find("td:eq(0)").text();
                    if (id == id_prod) {
                        var cantidad = $(this).find("#cant").val();
                        var unidad = $(this).find("#unidadp").val();
                        var total = parseInt(cantidad) * parseInt(unidad);
                        cantidad_general = cantidad_general + total;
                    }
                }
            });
            $.ajax({
                type: 'POST',
                url: urlprocess_enviar,
                data: {
                    process: 'consultar_existencias',
                    idRecepcion: idRecepcion,
                    id_producto: id_prod,
                    'id_usb': id_usb_enviar,
                    'tabla_buscar': tabla_buscar_enviar
                },
                dataType: 'JSON',
                async: false,
                success: function(tot) {
                    var cant_to = parseInt(tot.total);
                    var uni_to = parseInt(tot.unidad);
                    //alert(cant_to);
                    if ((parseInt(cantidad_general) + parseInt(uni_to)) > cant_to) {
                        swal({
                                title: "Producto sin existencias",
                                text: "El producto ha agotado sus existencias, revisar asignaciones de este.",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: '',
                                confirmButtonText: 'Ok.',
                                closeOnConfirm: true,
                                closeOnCancel: true
                            },
                            function(isConfirm) {
                                if (isConfirm) {

                                } else {}
                            });
                    } else {
                        //alert(id_prod);
                        if (id_prod != 0) {
                            addProductList(id_prod, tipo, descrip, "1", "1", "", "1", "");
                            $('input#producto_buscar').val("");
                            actualizar_cant_stock_tabla(id_prod);
                            actualizar_cant_stock_tabla(id_prod);
                            actualizar_ultima_fila();
                            validar_cambio_presentacion(id_prod);
                            contador_filas = 0;
                            // $('.sel').focus().select2("open");
                        } else {
                            $('input#producto_buscar').focus();
                            $('input#producto_buscar').val("");
                        }
                    }
                }
            });



            // agregar_producto_lista(id_prod, descrip, isbarcode);
        }
    });
    $("#servicio_buscar").typeahead({
        source: function(query, process) {
            $.ajax({
                type: 'POST',
                url: 'servicio_autocomplete.php',
                data: 'query=' + query + "&depto=" + id_servicio_buscar,
                dataType: 'JSON',
                async: true,
                success: function(data) {
                    process(data);
                }
            });
        },
        updater: function(selection) {
            var prod0 = selection;
            var prod = prod0.split("|");
            var id_prod = prod[0];
            var descrip = prod[1];
            var precio = prod[2];
            var tipo = "S";
            cant = 1;
            if (id_prod != 0) {
                addServicioList(id_prod, descrip, precio, "");
                $('input#servicio_buscar').val("");

            } else {
                $('input#servicio_buscar').focus();
                $('input#servicio_buscar').val("");
            }
            // agregar_producto_lista(id_prod, descrip, isbarcode);
        }
    });


    //Busqueda de examenes
    $("#examen_buscar").typeahead({
        source: function(query, process) {
            $.ajax({
                type: 'POST',
                url: 'http://192.168.0.37/laboratorio/api/consultar_examenes.php',
                crossDomain: true,
                data: 'query=' + query + '&process=aut',
                dataType: 'JSON',
                async: true,
                success: function(data) {
                    process(data);
                }
            });
        },
        updater: function(selection) {
            var prod0 = selection;
            var prod = prod0.split("|");
            var id_prod = prod[0];
            var descrip = prod[1];
            var tipo = prod[2];
            if (id_prod != 0) {
                addExamenList(id_prod, tipo, descrip);
                $('input#examen_buscar').val("");
                // $('.sel').focus().select2("open");

            } else {
                $('input#examen_buscar').focus();
                $('input#examen_buscar').val("");
            }
            // agregar_producto_lista(id_prod, descrip, isbarcode);
        }
    });
    //Fin de busqueda de examenes

    $('#formulario').validate({
        rules: {
            descripcion: {
                required: true,
            },
            precio1: {
                required: true,
                number: true,
            },
        },
        submitHandler: function(form) {
            senddata();
        }
    });
    //cargar insumos previos en microcirugia
    traer_insumos();
    $(".decimal").numeric({ negative: false, decimalPlaces: 2 });

    $(document).keydown(function(e) {
        if (e.which == 113) { //F2 Guardar
            e.stopPropagation();
            senddata();
        }
        if (e.which == 115) { //F2 Guardar
            e.stopPropagation();
            location.replace('dashboard.php');
        }
        if (e.which == 119) { //F8 Imprimir
            //$('#busca_descrip_activo').prop("checked", false);
            //activar_busqueda()
            //PENDIENTE
        }
        if (e.which == 120) { //F9 Salir
            //PENDIENTE
        }

        if ((e.metaKey || e.ctrlKey) && (String.fromCharCode(e.which).toLowerCase() === 'e')) {
            $('#doctor').select2('close');
            $('#tipo_impresion').select2('close');
            $('#id_cliente').select2('close')
            $('input#producto_buscar').focus();;
        }
        if ((e.metaKey || e.ctrlKey) && (String.fromCharCode(e.which).toLowerCase() === 'c')) {


            $('#id_cliente').select2('open');
            $('#tipo_impresion').select2('close');
            $('#doctor').select2('close');
        }
        if ((e.metaKey || e.ctrlKey) && (String.fromCharCode(e.which).toLowerCase() === 'x')) {

            $('#doctor').select2('open');
            $('#tipo_impresion').select2('close');
            $('#id_cliente').select2('close');
        }
        if ((e.metaKey || e.ctrlKey) && (String.fromCharCode(e.which).toLowerCase() === 'a')) {
            $('#tipo_impresion').select2('open');
            $('#doctor').select2('close');
            $('#id_cliente').select2('close');
        }

    });
    $('#form_fact_consumidor').hide();
    $('#form_fact_ccfiscal').hide();

    //Boton de imprimir deshabilitado hasta que se guarde la factura
    $('#print1').prop('disabled', true);
    $('#submit1').prop('disabled', false);
    $("#producto_buscar").focus();

}); //document ready
var contador_filas = 0;

function actualizar_cant_stock_tabla(id_producto) {
    var idRecepcion = $("#id_recepcion").val();
    var cantidad_general = 0;
    $("#inventable tr").each(function() {
        if ($(this).find("#tipopr").val() == "P") {
            var id = $(this).find("td:eq(0)").text();
            if (id == id_producto) {
                var cantidad = $(this).find("#cant").val();
                var unidad = $(this).find("#unidadp").val();
                var total = parseInt(cantidad) * parseInt(unidad);
                cantidad_general = cantidad_general + total;
            }
        }
        contador_filas++;
    });
    $.ajax({
        type: 'POST',
        url: urlprocess_enviar,
        data: {
            process: 'consultar_existencias',
            idRecepcion: idRecepcion,
            id_producto: id_producto,
            'id_usb': id_usb_enviar,
            'tabla_buscar': tabla_buscar_enviar
        },
        dataType: 'JSON',
        async: false,
        success: function(tot) {
            var cant_to = parseInt(tot.total);
            var total_asignar = cant_to - cantidad_general;
            $("#inventable tr").each(function() {
                if ($(this).find("#tipopr").val() == "P") {
                    var id = $(this).find("td:eq(0)").text();
                    if (id == id_producto) {
                        var cantidad = $(this).find("#cant").val();
                        var unidad = $(this).find("#unidadp").val();
                        var can_asignar = Math.floor(parseInt(total_asignar) / parseInt(unidad)) + parseInt(cantidad);
                        $(this).find('#cant_stock1').val(can_asignar);
                    }
                }
            });
        }
    });
}

function actualizar_ultima_fila() {
    var contador_normal = 0;
    $("#inventable tr").each(function() {
        if (contador_normal == contador_filas) {
            $(this).find('#cant_stock1').val(parseInt($(this).find('#cant_stock1').val()) - 1);
        }
        contador_normal++;
    });
}




//funcion recoger insumos de una microcirugia y mostrarlos en DOM
function traer_insumos() {
    var idRecepcion = $("#id_recepcion").val();
    //encabezado y detalle orden
    var n = 0;
    $.ajax({
        type: 'POST',
        url: urlprocess_enviar,

        data: {
            process: 'traer_insumos',
            idRecepcion: idRecepcion,
            'tabla_buscar': tabla_buscar_enviar,
            'id_usb': id_usb_enviar,
        },
        dataType: 'json',
        success: function(datos) {
            $.each(datos, function(key, value) {
                n = n + 1;
                var arr = Object.keys(value).map(function(k) { return value[k] });
                var id_prod = arr[0];
                var tipo = arr[1];
                var descr = arr[2];
                var cant = arr[3];
                var precio = arr[4];
                var hora = arr[5];
                var presentacion = arr[6];
                var unidad = arr[7];
                var id_insumo = arr[8];
                if (tipo == 'P') {
                    addProductList(id_prod, tipo, descr, cant, "0", presentacion, unidad, id_insumo);
                }
                if (tipo == 'S') {
                    addServicioList(id_prod, descr, precio, hora, id_insumo, cant);
                }
                if (tipo == 'E') {
                    addExamenList1(id_prod, descr, precio, hora, id_insumo);
                }
            });
        }
    });
}

$(document).on("keyup", "#paciente", function(evt) {
    if (evt.keyCode == 13) {
        if ($(this).val() != "" || $("#paciente_replace").val() != "") {

        } else {
            display_notify("Warning", "Ingrese el nombre del paciente");
        }
    }
});
$(document).on("click", "#btnBuscaProd", function() {
    $("#servicio_buscar").hide();
    $("#examen_buscar").hide();
    $("#producto_buscar").val("");
    $("#producto_buscar").show()
    $("#producto_buscar").focus();
});
$(document).on("click", "#btnBuscaServ", function() {
    $("#producto_buscar").hide();
    $("#examen_buscar").hide();
    $("#servico_buscar").val("");
    $("#servicio_buscar").show()
    $("#servicio_buscar").focus();
});
$(document).on("click", "#btnBuscarExam", function() {
    $("#producto_buscar").hide();
    $("#servicio_buscar").hide()
    $("#examen_buscar").val("");
    $("#examen_buscar").show()
    $("#examen_buscar").focus();
});
$(document).on("focus", "#paciente_replace", function() {
    $(this).val("");
    $(this).hide();
    $("#paciente").show();
    $("#paciente").focus();
});
$(document).on("focus", "#microciru_replace", function() {
    $(this).val("");
    $(this).hide();
    $("#microciru").show();
    $("#microciru").focus();
});
$(document).on("keyup", "#naci", function(evt) {
    if (evt.keyCode == 13) {
        if ($(this).val() != "") {
            $("#sexo").select2("open");
        } else {
            display_notify("Warning", "Ingrese la edad del paciente");
        }
    }
});
$(document).on("keyup", "#fecha_nacimiento", function(evt) {
    if (evt.keyCode == 13) {
        if ($(this).val() != "") {
            $("#sexo").select2("open");
        } else {
            display_notify("Warning", "Ingrese la edad del paciente");
        }
    }
});
$(document).on("change", "#doctor", function(event) {
    // limpiar();
    //falta limpiar datos de paciente, microcirugia y costos !!!
});

function limpiar() {
    $("#inventable").find("tr").remove();
    $("#id_microcirugia_pte").val('-1');
    $("#microciru").show()
    $("#microciru").val("")
    $("#paciente").show()
    $("#microciru_replace").hide()
    $("#honorarios_micr").val(0)
    $("#paciente_replace").hide()
    $("#paciente").val("")
    $("#id_paciente").val("-1");
    $("#id_microcirugia").val("-1");
    $('#microcirugia_buscar').val("");
    $('#paquete').prop('checked', false);
}
$(document).on("click", ".xa", function(event) {
    $("#doctor").select2('close');
});
$(document).on("click", ".xb", function(event) {
    $("#paciente").select2('close');
});
$(document).on("click", ".xp", function(event) {
    $("#id_procedencia").select2('close');
});
$(document).on("click", ".xc", function(event) {
    $("#id_cliente").select2('close');
});
$(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
});

//function to round 2 decimal places
function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}
$(function() {
    //binding event click for button in modal form
    $(document).on("click", "#btnDelete", function(e) {
        anular();
    });
    // Clean the modal form
    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });
});

function anular() {
    var idRecepcion = $('#idRecepcion').val();
    var dataString = 'process=anular_datos' + '&idRecepcion=' + idRecepcion;
    $.ajax({
        type: "POST",
        url: "anular_emergencia.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            //setInterval("location.reload();", 1500);
            //$('#deleteModal').hide();
        }
    });
}
// Evento para seleccionar una opcion y mostrar datos en un div
$(document).on("change", "#tipo_entrada", function() {
    $(".datepick2").datepicker();
    $('#id_proveedor').select2();

    var id = $("select#tipo_entrada option:selected").val(); //get the value
    if (id != '0') {
        $('#buscador').show();
    } else
        $('#buscador').hide();

    if (id == '1')
        $('#form_fact_consumidor').show();
    else
        $('#form_fact_consumidor').hide();
    if (id == '2')
        $('#form_fact_ccfiscal').show();
    else
        $('#form_fact_ccfiscal').hide();

});

// Seleccionar el tipo de factura
$(document).on("change", "#tipo_entrada", function() {
    var id = $("select#tipo_entrada option:selected").val(); //get the value
    $('#mostrar_numero_doc').load('editar_factura.php?' + 'process=mostrar_numfact' + '&id=' + id);
});

// Agregar productos a la lista del inventario
function cargar_empleados() {
    $('#inventable>tbody>tr').find("#select_empleado").each(function() {
        $(this).load('editar_factura.php?' + 'process=cargar_empleados');
        totales();
    });
}

// Evento que selecciona la fila y la elimina de la tabla
$(document).on("click", ".Delete", function() {
    var tr = $(this).parents("tr");
    var tipo = tr.hasClass("P");
    var idp = tr.find("#id_prod").val();
    if (tipo) {
        $(".P" + idp).remove();
    }
    tr.remove();
    actualizar_cant_stock_tabla(idp);
    actualizar_cant_stock_tabla(idp);
    validar_cambio_presentacion(idp);
    totales();
});

$(document).on("keyup", "#cant, #precio_venta", function() {
    var tr = $(this).parents("tr");
    actualiza_subtotal(tr);
});

function validar_cambio_presentacion(id_producto) {
    var idRecepcion = $("#id_recepcion").val();
    var cantidad_general = 0;
    $("#inventable tr").each(function() {
        if ($(this).find("#tipopr").val() == "P") {
            var id = $(this).find("td:eq(0)").text();
            if (id == id_producto) {
                var cantidad = $(this).find("#cant").val();
                var unidad = $(this).find("#unidadp").val();
                var total = parseInt(cantidad) * parseInt(unidad);
                cantidad_general = cantidad_general + total;
            }
        }
    });
    $("#inventable tr").each(function() {
        if ($(this).find("#tipopr").val() == "P") {
            var id = $(this).find("td:eq(0)").text();
            if (id == id_producto) {
                var cantidad1 = $(this).find("#cant_stock1").val();
                var unidad1 = $(this).find("#unidadp").val();
                var cantidad_especifica = parseInt(cantidad1) * parseInt(unidad1);
                var id_presentacion = $(this).find("#id_presentacion :selected").val();
                var select;
                $.ajax({
                    type: 'POST',
                    url: urlprocess_enviar,
                    data: {
                        process: 'consultar_selects',
                        idRecepcion: idRecepcion,
                        id_producto: id_producto,
                        cantidad_general: cantidad_general,
                        cantidad_especifica: cantidad_especifica,
                        id_presentacion: id_presentacion,
                        'id_usb': id_usb_enviar,
                        'tabla_buscar': tabla_buscar_enviar
                    },
                    dataType: 'JSON',
                    async: false,
                    success: function(tot) {
                        select = tot.select;
                    }
                });
                $(this).find("td:eq(5)").html(select);
            }
        }
    });


}

$(document).on('change', '.sel', function(event) {
    var id_presentacion = $(this).val();
    var idRecepcion = $("#id_recepcion").val();
    var a = $(this);
    var cantidad = 0;
    var id_prod = a.closest('tr').find('td:eq(0)').text();
    var idRecepcion = $("#id_recepcion").val();
    var cantidad_general = 0;
    $.ajax({
        url: urlprocess_enviar,
        type: 'POST',
        dataType: 'json',
        data: 'process=get_presentancion' + "&id_presentacion=" + id_presentacion + "&id_recepcion=" + idRecepcion + "&id_usb=" + id_usb_enviar + "&tabla_buscar=" + tabla_buscar_enviar,
        success: function(data) {
            a.closest('tr').find('#precio_venta').val(data.precio);
            a.closest('tr').find('#unidadp').val(data.unidad);
            a.closest('tr').find('#cant_stock1').val(data.total);
            a.closest('tr').find("#cant").val("1");
            var tr = a.closest('tr');
            actualiza_subtotal(tr);
        }
    });




    /*
    setTimeout(function() {
      totales();
    }, 1000);
  */
});

function actualiza_subtotal(tr) {
    var existencias = tr.find('#cant_stock1').val();
    var existenciasSC = tr.find('td:eq(3)').text();
    var id = tr.find("td:eq(0)").text();
    var cantidad = tr.find("#cant").val();
    var unidad = tr.find("#unidadp").val();
    var tipop = tr.find("#tipopr").val();
    if (tipop == 'S') {
        var id_presentacion = 0;
    } else {
        var id_presentacion = $(this).find("#id_presentacion :selected").val();
    }
    cantidad = cantidad * unidad;
    existencias = existencias * unidad;
    if (isNaN(cantidad) || cantidad == "") {
        cantidad = 0;
    }
    if (parseInt(cantidad) > (parseInt(existencias))) {
        cantidad = existencias;
        tr.find("#cant").val(cantidad / unidad);
    }
    if (parseInt(existencias) > 0 && parseInt(cantidad) == 0) {
        tr.find("#cant").val("1");
        cantidad = 1;
    }
    var precio = tr.find('#precio_venta').val();
    if (isNaN(precio) || precio == "") {
        precio = 0;
    }
    cantidad = cantidad / unidad;
    var subtotal = subt(cantidad, precio);
    if (tipop == 'P') {
        actualizar_cant_stock_tabla(id);
        validar_cambio_presentacion(id);
    }
    var subt_mostrar = subtotal.toFixed(2);

    tr.find("#subtotal_fin").val(subt_mostrar);
    totales();
}

function totales() {
    //impuestos
    var porcentaje_descuento = parseFloat($("#porcentaje_descuento").val());
    var urlprocess = $('#urlprocess').val();
    var i = 0,
        total = 0;
    totalcantidad = 0;
    var subtotal = 0;
    var subt_cant = 0;
    var total_descto = 0;
    var subt_descto = 0;
    var total_final = 0;
    var StringDatos = '';
    var filas = 0;
    var items2 = 0;
    var total_iva = 0;

    $("#inventable tr").each(function() {
        if (!$(this).hasClass("EP")) {
            subt_cant = $(this).find("#cant").val();
            totalcantidad += parseInt(subt_cant);
            subtotal += parseFloat($(this).find("#subtotal_fin").val());
            filas += 1;
        }
    });
    items2 = $("#idco").val();
    subtotal = round(subtotal, 4);
    //descuento
    var total_descuento = 0;
    if (porcentaje_descuento > 0.0) {
        total_descuento = (porcentaje_descuento / 100) * subtotal
    } else {
        total_descuento = 0;
    }
    var total_descuento_mostrar = round(total_descuento, 2);
    var total_mostrar = (subtotal - total_descuento).toFixed(2);
    totcant_mostrar = round(totalcantidad, 2).toFixed(2);
    $('#totcant').text(totcant_mostrar);
    $('#total_gravado').html(total_mostrar);
    $('#subtotal').html(subtotal.toFixed(2));
    $('#pordescuento').html(porcentaje_descuento);
    $('#valdescuento').html(total_descuento.toFixed(2));
    $('#totcant').html(totcant_mostrar);
    $('#items').val(items2);
    if (subtotal > 0) {
        $('#totaltexto').load(urlprocess_enviar, {
            'process': 'total_texto',
            'total': total_mostrar
        });
    }
    $('#monto_pago').html(total_mostrar);
    $('#totalfactura').val(total_mostrar);
}

function totalFact() {
    var TableData = new Array();
    var i = 0;
    var total = 0;
    var StringDatos = '';
    $("#inventable>tbody  tr").each(function(index) {
        if (index >= 0) {
            var subtotal = 0;
            $(this).children("td").each(function(index2) {
                switch (index2) {
                    case 7:
                        var isVisible = false
                        isVisible = $(this).filter(":visible").length > 0;
                        if (isVisible == true) {
                            subtotal = parseFloat($(this).text());
                            if (isNaN(subtotal)) {
                                subtotal = 0;
                            }
                        } else {
                            subtotal = 0;
                        }
                        break;
                }
            });
            total += subtotal;
        }
    });
    total = round(total, 2);
    total_dinero = total.toFixed(2);
    $('#total_dinero').html("<strong>" + total_dinero + "</strong>");
    //$('#totaltexto').load('venta.php?' + 'process=total_texto&total=' + total_dinero);
    //console.log('total:' + total_dinero);
}
// actualize table data to server
$(document).on("click", "#submit1", function() {
    senddata();
});
$(document).on("click", "#submit2", function() {
    senddata1();
});
$(document).on("click", "#btnEsc", function(event) {
    reload1();
});

$(document).on("click", ".print1", function() {
    var totalfinal = parseFloat($('#totalfactura').val());
    var facturado = totalfinal.tosenddFixed(2);
    $(".modal-body #facturado").val(facturado);
});
$(document).on("click", "#btnPrintFact", function(event) {
    imprime1();
});

$(document).on("click", "#print2", function() {
    imprime2();
});


$(document).on("change", "#paquete", function() {
    if (this.checked) {
        //  $(this).prop("checked", returnVal);
        $("#honorarios_micr").val("")
        $("#honorarios_micr").prop("disabled", true);
        //$("#valor_paquete").val("")
        $("#valor_paquete").prop("disabled", false);
    } else {
        //$("#honorarios_micr").val(0)
        $("#honorarios_micr").prop("disabled", false);
        $("#valor_paquete").val("")
        $("#valor_paquete").prop("disabled", true);
    }
});

function senddata() {
    //Obtener los valores a guardar de cada item facturado
    var i = 0;
    var StringDatos = "";
    var id_empleado = 0;
    var id_paciente = $("#id_paciente").val();
    var id_recepcion = $("#id_recepcion").val();
    var items = $("#items").val();
    var msg = "";
    var error = false;
    var total = $('#total_gravado').text(); /*total sumas */
    var array_json = new Array();
    $("#inventable tr").each(function(index) {
        var id = $(this).find("td:eq(0)").text();
        var fecha_hora = $(this).find("td:eq(4)").text();
        var precio_venta = $(this).find("#precio_venta").val();
        var cantidad = $(this).find("#cant").val();
        var subtotal = $(this).find("#subtotal_fin").val();
        var unidad = $(this).find("#unidadp").val();
        var tipop = $(this).find("#tipopr").val();
        if (tipop === undefined) {

        } else {
            var id_insumo = $(this).find("#id_insumo").val();
            if (tipop == 'S' || tipop == "PER" || tipop == "E") {
                var id_presentacion = 0;
            } else {
                var id_presentacion = $(this).find("#id_presentacion :selected").val();
            }
            if (cantidad && precio_venta) {
                var obj = new Object();
                obj.id = id;
                obj.id_presentacion = id_presentacion;
                obj.precio = precio_venta;
                obj.cantidad = cantidad;
                obj.subtotal = subtotal;
                obj.tipop = tipop;
                obj.fecha = fecha_hora;
                obj.unidad = unidad;
                obj.id_insumo = id_insumo;
                //convert object to json string
                text = JSON.stringify(obj);
                array_json.push(text);
                i = i + 1;
            } else {
                error = true;
            }
        }
    });
    json_arr = '[' + array_json + ']';
    if (i == 0) {
        error = true
    }

    var dataString = 'process=insert';
    dataString += '&id_paciente=' + id_paciente;
    dataString += '&total=' + total;
    dataString += '&items=' + items;
    dataString += '&cuantos=' + i;
    dataString += '&id_recepcion=' + id_recepcion;
    dataString += '&id_usb=' + id_usb_enviar;
    dataString += '&tabla_buscar=' + tabla_buscar_enviar;
    dataString += '&json_arr=' + json_arr;

    var sel = 1,
        sel1 = 1,
        sel2 = 1;
    if (i == 0) {
        msg = 'Seleccione al menos un producto !';
        sel = 0;
    }
    if (sel == 1 && sel1 == 1 && sel2 == 1) {
        $("#inventable tr").remove();
        $.ajax({
            type: 'POST',
            url: urlprocess_enviar,
            data: dataString,
            dataType: 'json',
            success: function(datax) {
                if (datax.typeinfo == "Success") {
                    display_notify(datax.typeinfo, datax.msg);
                    setInterval("reload1();", 1500);
                }
            }
        });
    } else {
        display_notify('Warning', msg);
    }
}

function senddata1() {
    //Obtener los valores a guardar de cada item facturado
    var i = 0;
    var StringDatos = "";
    var id_empleado = 0;
    var id_paciente = $("#id_paciente").val();
    var id_recepcion = $("#id_recepcion").val();
    var items = $("#items").val();
    var msg = "";
    var error = false;
    var observaciones_mc = document.getElementById('observaciones_mc').value;
    var total = $('#total_gravado').text(); /*total sumas */
    var array_json = new Array();
    $("#inventable tr").each(function(index) {
        var id = $(this).find("td:eq(0)").text();
        var fecha_hora = $(this).find("td:eq(4)").text();
        var precio_venta = $(this).find("#precio_venta").val();
        var cantidad = $(this).find("#cant").val();
        var subtotal = $(this).find("#subtotal_fin").val();
        var unidad = $(this).find("#unidadp").val();
        var tipop = $(this).find("#tipopr").val();
        var id_insumo = $(this).find("#id_insumo").val();
        if (tipop == 'S' || tipop == "PER" || tipop == "E") {
            var id_presentacion = 0;
        } else {
            var id_presentacion = $(this).find("#id_presentacion :selected").val();
        }
        if (cantidad && precio_venta) {
            var obj = new Object();
            obj.id = id;
            obj.id_presentacion = id_presentacion;
            obj.precio = precio_venta;
            obj.cantidad = cantidad;
            obj.subtotal = subtotal;
            obj.tipop = tipop;
            obj.fecha = fecha_hora;
            obj.unidad = unidad;
            obj.id_insumo = id_insumo;
            //convert object to json string
            text = JSON.stringify(obj);
            array_json.push(text);
            i = i + 1;
        } else {
            error = true;
        }
    });
    json_arr = '[' + array_json + ']';
    if (i == 0) {
        error = true
    }

    var dataString = 'process=finalizar';
    dataString += '&id_paciente=' + id_paciente;
    dataString += '&total=' + total;
    dataString += '&items=' + items;
    dataString += '&cuantos=' + i;
    dataString += '&id_recepcion=' + id_recepcion;
    dataString += '&observaciones_mc=' + observaciones_mc;
    dataString += '&json_arr=' + json_arr;
    dataString += '&id_usb=' + id_usb_enviar;
    dataString += '&tabla_buscar=' + tabla_buscar_enviar;


    var sel = 1,
        sel1 = 1,
        sel2 = 1;
    if (i == 0) {
        msg = 'Seleccione al menos un producto !';
        sel = 0;
    }
    if (observaciones_mc == "") {
        sel2 = 0;
        msg = "Por favor ingresar la observacion";
    }
    if (sel == 1 && sel1 == 1 && sel2 == 1) {
        $("#inventable tr").remove();
        $.ajax({
            type: 'POST',
            url: urlprocess_enviar,
            data: dataString,
            dataType: 'json',
            success: function(datax) {
                if (datax.typeinfo == "Success") {
                    display_notify(datax.typeinfo, datax.msg);
                    setInterval("reload1();", 1500);
                }
            }
        });
    } else {
        display_notify('Warning', msg);
    }
}

function reload1() {
    location.href = 'admin_hospitalizaciones.php';
}

$(document).on('change', '#tipo_impresion', function(event) {
    $('#inventable tr').each(function(index) {
        var tr = $(this);
        actualiza_subtotal(tr);
    });
});

function addProductList(id_prod, tipo, descr, cantx, f, presentacion, unidadesx, id_insumo) {
    id_prod = $.trim(id_prod);
    id_insumo = $.trim(id_insumo);
   // alert(id_prod);
    id_factura = parseInt($('#id_factura').val());
    if (isNaN(id_factura)) {
        id_factura = 0;
    }
    var fecha = "00-00-0000";
    var hora = "12:00 AM"
        //	var fila=1;
    urlprocess = $('#urlprocess').val();
    alert(urlprocess);
    var dataString = 'process=consultar_stock' + '&id_producto=' + id_prod + '&tipo=' + tipo + '&id_presentacion=' + presentacion + "&id_usb=" + id_usb_enviar + "&tabla_buscar=" + tabla_buscar_enviar;
    $.ajax({
        type: "POST",
        url: 'agregar_insumos_modulo.php',
        data: dataString,
        dataType: 'json',
        success: function(data) {
            var id_previo = new Array();
            if (tipo == "P") {
                //alert(data.precio_p);
                var precio_p = data.precio_p;
                var cortesia_p = data.cortesia_p;
                tr_add = '';
                var fila = 1;
                var filas = 1;
                $("#inventable  tr").each(function(index) {
                    if (index >= 0) {
                        var campo0 = "";
                        $(this).children("td").each(function(index2) {
                            switch (index2) {
                                case 0:
                                    campo0 = $(this).text();
                                    if (campo0 != undefined || campo0 != '') {
                                        id_previo.push(campo0);
                                    }
                                    break;
                            }
                        });
                        if (campo0 != "") {
                            filas = filas + 1;
                        }
                    } //if index>0
                });

                var cantx1 = 0;
                if (f == 1) {
                    cantx1 = 0;
                    cantx1 = (parseInt(cantx1) + parseInt(data.existencias));
                } else {
                    cantx1 = cantx;
                    cantx1 = (parseInt(cantx1) + parseInt(data.existencias));
                }
                var cantidadx = (parseInt(cantx) / parseInt(unidadesx));
                var cantd = 0;
                if (isNaN(cantidadx)) {
                    cantidadx = 0;
                    cantd = 1;
                }
                var subtotal = subt(data.preciop, cantidadx);
                subt_mostrar = subtotal.toFixed(2);

                var totalX = 0;
                var cantidades = "<td class='cell100 column5 text-success'><input type='text'  class='form-control decimal' id='cant' name='cant'  value='" + (parseInt(cantidadx) + parseInt(cantd)) + "' style='width:60px;' /></td>";
                var tipop = "<input type='hidden'  class='tipop' id='tipopr' name='tipopr' value='P'>";
                var id_insumo1 = "<input type='hidden'  class='id_insumo' id='id_insumo' name='id_insumo' value='" + id_insumo + "'>";
                tr_add += "<tr class='row100 head " + tipo + "' id='" + id_prod + "'>";
                tr_add += "<td class='cell100 column10 text-success id_pps'>" + id_prod + "<input type='hidden'  class='txt_box decimal2  cantx' id='unidadp' name='unidadp' value='" + data.unidadp + "'></td>";
                tr_add += "<td class='cell100 column30 text-success descp' id='desc'>" + descr + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prod + "'></td>";
                tr_add += "<td class='cell100 column10 text-success' id='cant_stock'><input type='text'  class='form-control decimal' id='cant_stock1' name='cant_stock1' value='" + (parseInt(data.existencias) + parseInt(cantidadx) - parseInt(totalX)) + "' readOnly></td>";
                tr_add += "<td class='cell100 column10 text-success' id='cant_stockHidden' style='display:none;'>" + (parseInt(cantx) - parseInt(totalX)) + "</td>";
                tr_add += "<td class='cell100 column10 text-success' id='fecha_hora_Hidden' style='display:none;'>" + (fecha + " " + hora) + "</td>";
                tr_add += "<td class='cell100 column15 text-success '>" + data.select + "</td>";
                tr_add += "<td class='cell100 column10 text-success' id='precio_ventas'>" + id_insumo1 + "<input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" + data.preciop + "' readOnly></td>";
                tr_add += cantidades;
                tr_add += "<td class='ccell100 column10'>" + tipop + "<input type='text'  class='decimal form-control' id='subtotal_fin' name='subtotal_fin'  value='" + subt_mostrar + "'readOnly></td>";
                tr_add += '<td class="cell100 column8 text-center"><input id="delprod" type="button" class="btn btn-danger fa Delete"  value="&#xf1f8;"></td>';
                tr_add += '</tr>';
                $("#inventable").prepend(tr_add);
            }
            scrolltable();
            totales();
        }
    });
}

function convertirFecha(fecha) {
    var arr = fecha.split(" ");
    var f_e = arr[0];
    var h_o = arr[1];
    var arr1 = f_e.split("-");
    var arr2 = h_o.split(":");
    var fecha_f = arr1[2] + "-" + arr1[1] + "-" + arr1[0];
    var hora_f = "";
    if (arr2[0] == "00") {
        hora_f = "12:" + arr2[1] + " AM";
    } else if (arr2[0] < 12 && arr2[0] > 0) {
        hora_f = arr2[0] + ":" + arr2[1] + " AM";
    } else if (arr2[0] == 12) {
        hora_f = arr2[0] + ":" + arr2[1] + " PM";
    } else if (arr2[0] > 12) {
        arr2[0] = arr2[0] - 12;
        hora_f = arr2[0] + ":" + arr2[1] + " PM";
    }
    var hora_devolver = fecha_f + " " + hora_f;
    return hora_devolver;
}

function valideKey(evt) {

    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;

    if (code == 8) { // backspace.
        return true;
    } else if (code >= 48 && code <= 57) { // is a number.
        return true;
    } else { // other keys.
        return false;
    }
}

function addServicioList(id_prod, descr, precio, hora1, id_insumo, cant) {
    if (cant == undefined) { cant = 1 }
    //	$('#inventable').find('tr#filainicial').remove();
    var fecha = $('#fechaEntrada').val();
    var hora = $('#hora_entrada').val();

    if (hora1 != "") {
        var fe_ho = convertirFecha(hora1);
        fe_ho = fe_ho.split(" ");
        fecha = fe_ho[0];
        hora = fe_ho[1] + " " + fe_ho[2];
    }

    var num = 0;
    if (fecha == "" || hora == "") {
        swal({
                title: "Fecha u hora no marcados",
                text: "Tiene que asignar una fecha y una hora para la asignacion del servicio.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '',
                confirmButtonText: 'Ok.',
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {

                } else {}
            });
        num = 1;
    }

    if (num == 0) {
        id_prod = $.trim(id_prod);
        var tipo = "S"
        if (tipo == "S") {
            tr_add = '';
            var fila = 1;
            var filas = 1;
            var subtotal = subt((precio * cant), 1);
            subt_mostrar = subtotal.toFixed(2);
            cantx1 = cant;
            var cantidades = "<td class='cell100 column5 text-success'><input type='text'  class='form-control decimal' id='cant' onkeypress='return valideKey(event);' name='cant'  value='" + cantx1 + "' style='width:60px;'  /></td>";
            var id_insumo1 = "<input type='hidden'  class='id_insumo' id='id_insumo' name='id_insumo' value='" + id_insumo + "'>";
            var tipop = "<input type='hidden'  class='tipop' id='tipopr' name='tipopr' value='S'>";
            tr_add += "<tr class='row100 head " + tipo + "' id='" + id_prod + "'>";
            tr_add += "<td class='cell100 column10 text-success id_pps'>" + id_prod + "<input type='hidden'  class='txt_box decimal2  cantx' id='unidadp' name='unidadp' value='" + '1' + "'></td>";
            tr_add += "<td class='cell100 column30 text-success descp' id='desc'>" + (descr + " Aplicandose el dia " + fecha + " a las " + hora) + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prod + "'></td>";
            tr_add += "<td class='cell100 column10 text-success' id='cant_stock'>" + 1 + "</td>"
            tr_add += "<td class='cell100 column10 text-success' id='cant_stockHidden' style='display:none;'>" + 1 + "</td>";
            tr_add += "<td class='cell100 column10 text-success' id='fecha_hora_Hidden' style='display:none;'>" + (fecha + " " + hora) + "</td>";
            tr_add += "<td class='cell100 column15 text-success preccs'>&nbsp;</td>";
            tr_add += "<td class='cell100 column10 text-success' id='precio_ventas'>" + id_insumo1 + "<input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" + precio + "' readOnly></td>";
            tr_add += cantidades;
            tr_add += "<td class='ccell100 column10'>" + tipop + "<input type='text'  class='decimal form-control' id='subtotal_fin' name='subtotal_fin'  value='" + subt_mostrar + "'readOnly></td>";
            tr_add += '<td class="cell100 column8 text-center"><input id="delprod" type="button" class="btn btn-danger fa Delete"  value="&#xf1f8;"></td>';
            tr_add += '</tr>';
            $("#inventable").prepend(tr_add);
        }
        scrolltable();
        totales();
    }

}

function addExamenList1(id_prod, descr, precio, hora1, id_insumo) {
    //	$('#inventable').find('tr#filainicial').remove();

    var num = 0;
    if (num == 0) {

        id_prod = $.trim(id_prod);
        var tipo = "S"
        if (tipo == "S") {
            tr_add = '';
            var fila = 1;
            var filas = 1;
            var subtotal = subt(precio, 1);
            subt_mostrar = subtotal.toFixed(2);
            cantx1 = 1;
            var cantidades = "<td class='cell100 column5 text-success'><input type='text'  class='form-control decimal' id='cant' name='cant'  value='" + cantx1 + "' style='width:60px;' readonly /></td>";
            var id_insumo1 = "<input type='hidden'  class='id_insumo' id='id_insumo' name='id_insumo' value='" + id_insumo + "'>";
            var tipop = "<input type='hidden'  class='tipop' id='tipopr' name='tipopr' value='EXAMEN_AGREGADO'>";


            tr_add += "<tr class='row100 head " + tipo + "SS' id='" + id_prod + "'>";

            tr_add += "<td class='cell100 column10 text-success id_pps'>" + id_prod + "<input type='hidden'  class='txt_box decimal2  cantx' id='unidadp' name='unidadp' value='" + '1' + "'></td>";

            tr_add += "<td class='cell100 column30 text-success descp' id='desc'>" + (descr) + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prod + "'></td>";

            tr_add += "<td class='cell100 column10 text-success' id='cant_stock'>" + 1 + "</td>"
            tr_add += "<td class='cell100 column10 text-success' id='cant_stockHidden' style='display:none;'>" + 1 + "</td>";
            tr_add += "<td class='cell100 column10 text-success' id='fecha_hora_Hidden' style='display:none;'></td>";
            tr_add += "<td class='cell100 column15 text-success preccs'>&nbsp;</td>";
            tr_add += "<td class='cell100 column10 text-success' id='precio_ventas'>" + id_insumo1 + "<input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" + precio + "' readOnly></td>";
            tr_add += cantidades;

            tr_add += "<td class='ccell100 column10'>" + tipop + "<input type='text'  class='decimal form-control' id='subtotal_fin' name='subtotal_fin'  value='" + subt_mostrar + "'readOnly></td>";
            tr_add += '<td class="cell100 column8 text-center"><input id="delprod" type="button" class="btn btn-danger fa Delete"  value="&#xf1f8;"></td>';
            tr_add += '</tr>';

            $("#inventable").prepend(tr_add);
        }
        scrolltable();
        totales();
    }

}
$(document).on('select2:close', '.sel2', function(evt) {
    $("#producto_buscar").focus();
});
$(document).on("click", "#btnAddDoctor", function(event) {
    $(document).ready(function() {
        $('#formulario').validate({
            rules: {
                nombre: {
                    required: true,
                },
                apellido: {
                    required: true,
                },
                especialidad: {
                    required: true,
                },
            },
            messages: {
                nombre: "Por favor ingrese el nombre del doctor",
                apellido: "Por favor ingrese el apellido del doctor",
                especialidad: "Por favor ingrese la especialidad del doctor"
            },
            highlight: function(element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function(element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            submitHandler: function(form) {
                agregardoctor();
            }
        });
    });
    $(".may").keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
});
$(document).on("click", "#btnAddProcedencia", function(event) {
    $(document).ready(function() {
        $('#formulario').validate({
            rules: {
                nombre: {
                    required: true,
                },
            },
            messages: {
                nombre: "Por favor ingrese el nombre del Procedencia",
            },
            highlight: function(element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function(element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            submitHandler: function(form) {
                agregar_procedencia();
            }
        });
    });
    $(".may").keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
});
$(document).on("click", "#btnAddClient", function(event) {
    $(document).ready(function() {
        $('#formulario').validate({
            rules: {
                nombre: {
                    required: true,
                },
                apellido: {
                    required: true,
                },
                sexo: {
                    required: true,
                },
                fecha_nacimiento: {
                    required: true,
                },
            },
            messages: {
                nombre: "Por favor ingrese el nombre del usuario",
                apellido: "Por favor ingrese el apellido",
                sexo: "Por favor ingrese el sexo",
                fecha_nacimiento: "Por favor ingrese la fecha de nacimiento",
            },
            highlight: function(element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function(element) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            submitHandler: function(form) {
                agregarcliente();
            }
        });
        $(".may").keyup(function() {
            $(this).val($(this).val().toUpperCase());
        });
    });
});

function id_existente(id, tipoa) {
    var dato = false;
    $("#inventable tr").each(function() {
        var tipo = $(this).hasClass(tipoa);
        var id1 = $(this).attr("id");
        if (id == id1 && tipo) {
            dato = true;
        }
    });
    return dato;
}
$("#descto").keyup(function(event) {
    if (event.keyCode == 13) {
        if ($(this).val() != "") {
            aplicar_descuento($(this).val());
        }
    }
});
$(document).on('change', '.cort', function() {
    if ($(this).is(':checked')) {
        $(this).parents("tr").find("#idco").each(function() {
            var tr = $(this).parents("tr");
            precio = 0;
            tr.find("#precio_venta").val(precio);
            tr.find("#cortesia").val(1);
            tr.find("#precio_sin_iva").val(precio);
            actualiza_subtotal(tr);
        });
    } else {
        $(this).parents("tr").find("#idco").each(function() {
            var tr = $(this).parents("tr");
            precio = parseFloat(tr.find("#precio_venta").text());
            tr.find("#precio_venta").val(precio);
            tr.find("#cortesia").val(0);
            tr.find("#precio_sin_iva").val(precio);
            actualiza_subtotal(tr);
        });
    }
});

function aplicar_descuento(hash) {
    $("#id_descuento").val("");
    $("#porcentaje_descuento").val("0");
    $.ajax({
        type: 'POST',
        url: 'venta.php',
        data: 'process=pin&hash=' + hash,
        dataType: 'JSON',
        success: function(datax) {
            $("#descto").val("");
            if (datax.typeinfo == "Ok") {
                $("#porcentaje_descuento").val(datax.porcentaje);
                $("#id_descuento").val(datax.id_descuento);
                totales();
            } else if (datax.typeinfo == "Ap") {
                display_notify("Warning", "El codigo ya fue aplicado");
            } else {
                display_notify("Error", "Codigo no valido");
            }
        }
    });
}
$('#telefono').on('keydown', function(event) {
    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {

    } else {
        if ((event.keyCode > 47 && event.keyCode < 60) || (event.keyCode > 95 && event.keyCode < 106)) {
            inputval = $(this).val();
            var string = inputval.replace(/[^0-9]/g, "");
            var bloc1 = string.substring(0, 4);
            var bloc2 = string.substring(4, 7);
            var string = bloc1 + "-" + bloc2;
            $(this).val(string);
        } else {
            event.preventDefault();
        }
    }
});

function generar2(datax) {
    if (datax != "") {
        datax = datax.trim();
        datax = JSON.parse(datax);
        display_notify(datax.typeinfo, datax.msg, datax.process);
    }
    fechai = $("#desdeRecepcion").val();
    fechaf = $("#hastaRecepcion").val();
    dataTable = $('#editableRecepcion').DataTable().destroy()
    dataTable = $('#editableRecepcion').DataTable({
        "pageLength": 50,
        "order": [0, 'asc'],
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "ajax": {
            url: "admin_uci_dt.php?fechai=" + fechai + "&fechaf=" + fechaf, // json datasource
            //url :"admin_factura_rangos_dt.php", // json datasource
            //type: "post",  // method  , by default get
            error: function() { // error handling
                $(".editableRecepcion-error").html("");
                $("#editableRecepcion").append('<tbody class="editable_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
                $("#editableRecepcion_processing").css("display", "none");
                $(".editableRecepcion-error").remove();
            }
        }
    });
    dataTable.ajax.reload();
    //}
}
$(document).on("click", "#btnRecuperar", function(event) {
    anular1();
});

function anular1() {
    var idRecepcion = $('#idRecepcion').val();
    var dataString = 'process=recuperar_bd' + '&idRecepcion=' + idRecepcion;
    $.ajax({
        type: "POST",
        url: "anular_recepcion.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            setInterval("location.reload();", 1500);
            $('#deleteModal').hide();
        }
    });
}

function realizarRecepcion() {
    swal({
        title: "¿Esta, seguro?",
        text: "Este proceso activara la recepcion, si esta seguro presione OK y se procedera.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '',
        confirmButtonText: 'Si, Estoy seguro.',
        cancelButtonText: "No, cancelar y revisar.",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            var ajaxdata = { "process": "activar_recepcion", "idRecepcion": $("#idRecepcion").val() };
            $.ajax({
                url: "realizar_recepcion.php",
                type: "POST",
                data: ajaxdata,
                success: function(datax) {
                    $("#realizarModal1").modal('hide');
                    generar2(datax);

                }
            });
        } else {
            $('#btnActivarRecepcion').prop('disabled', "");
        }

    });
}


function addExamenList(id_prod, tipo, descr) {
    $('#inventable').find('tr#filainicial').remove();
    id_prod = $.trim(id_prod);
    var fecha = "00-00-0000";
    var hora = "12:00 AM"
        //id_factura= parseInt($('#id_factura').val());

    /*if(isNaN(id_factura))
    {
    	id_factura=0;
    }*/
    //	var fila=1;
    urlprocess = "http://192.168.0.37/laboratorio/api/consultar_stock_examen.php";
    var dataString = '&id_producto=' + id_prod + '&tipo=' + tipo;
    $.ajax({
        type: "POST",
        url: urlprocess,
        data: dataString,
        dataType: 'json',
        success: function(data) {
            var id_previo = new Array();
            if (tipo == "P") {
                var precio_p = data.precio_p;
                var cortesia_p = data.cortesia_p;
                tr_add = '';
                var fila = 1;
                var filas = 1;
                $("#inventable  tr").each(function(index) {
                    if (index >= 0) {
                        var campo0 = "";
                        $(this).children("td").each(function(index2) {
                            switch (index2) {
                                case 0:
                                    campo0 = $(this).text();
                                    if (campo0 != undefined || campo0 != '') {
                                        id_previo.push(campo0);
                                    }
                                    break;
                            }
                        });
                        if (campo0 != "") {
                            filas = filas + 1;
                        }
                    } //if index>0
                });
                /*tr_add += "<tr class='row100 head "+tipo+"' id='" + id_prod + "'>";
                tr_add += "<td class='cell100 column10 text-success id_pps'>" +filas + "<input type='hidden'  class='txt_box decimal2  cant' id='cant' name='cant' value='1' style='width:50px;'readOnly></td>";
                tr_add += "<td class='cell100 column30 text-success descp' id='desc'>" +descr + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prod + "'></td>";

                tr_add += "<td class='cell100 column55 text-success' id='precio_venta'><input type='hidden'  class='form-control decimal' id='precio_venta' name='precio_venta' value='"+precio_p+"'>"+precio_p+"</td>";
                //tr_add += "<td class='cell100 column9 text-success'>"+"<input type='hidden'  id='cortesia' name='cortesia' value='0'>"+"<input type='hidden'  id='idco' name='idco' value='"+filas+"'>"+cortesia_p+"</td>";
                tr_add += "<td class='ccell100 column7'><input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='"+precio_p+"'>" + "<input type='hidden'  class='decimal txt_box' id='subtotal_mostrar' name='subtotal_mostrar'  value='"+precio_p+"'style='width:55px;'readOnly></td>";
                tr_add += '<td class="cell100 column8 text-center"><input id="delprod" type="button" class="btn btn-danger fa Delete"  value="&#xf1f8;"></td>';
                tr_add += '</tr>';*/


                var cantidades = "<td class='cell100 column5 text-success'><input type='text'  class='form-control decimal' id='cant' name='cant'  value='1' style='width:60px;' readonly /></td>";
                var id_insumo1 = "<input type='hidden'  class='id_insumo' id='id_insumo' name='id_insumo' value='" + 1 + "'>";
                var tipop = "<input type='hidden'  class='tipop' id='tipopr' name='tipopr' value='PER'>";
                tr_add += "<tr class='row100 head " + tipo + "' id='" + id_prod + "'>";
                tr_add += "<td class='cell100 column10 text-success id_pps'>" + id_prod + "<input type='hidden'  class='txt_box decimal2  cantx' id='unidadp' name='unidadp' value='" + '1' + "'></td>";
                tr_add += "<td class='cell100 column30 text-success descp' id='desc'>" + (descr) + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prod + "'></td>";
                tr_add += "<td class='cell100 column10 text-success' id='cant_stock'>" + 1 + "</td>";
                tr_add += "<td class='cell100 column10 text-success' id='cant_stockHidden' style='display:none;'>" + 1 + "</td>";
                tr_add += "<td class='cell100 column10 text-success' id='fecha_hora_Hidden' style='display:none;'>" + (fecha + " " + hora) + "</td>";
                tr_add += "<td class='cell100 column15 text-success preccs'>&nbsp;</td>";
                tr_add += "<td class='cell100 column10 text-success' id='precio_ventas'><input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" + precio_p + "' readOnly></td>";
                tr_add += cantidades;
                tr_add += "<td class='ccell100 column10'>" + tipop + "<input type='text'  class='decimal form-control' id='subtotal_fin' name='subtotal_fin'  value='" + precio_p + "'readOnly></td>";
                tr_add += '<td class="cell100 column8 text-center"><input id="delprod" type="button" class="btn btn-danger fa Delete"  value="&#xf1f8;"></td>';
                tr_add += '</tr>';
                if (!id_existente(id_prod, tipo)) {
                    $("#inventable").prepend(tr_add);
                }
            }
            var descripcionps = data.descripcionp;
            var cortesias = data.cortesia;
            var select2s = data.select2;
            var cuantos = data.cuantos;
            var id_prods = data.id_prods;
            var descrip = descripcionps.split("|");
            var cortes = cortesias.split("|");
            var selec = select2s.split("|");
            var idp = id_prods.split("|");
            for (jk = 0; jk < cuantos; jk++) {
                tr_add = '';
                var fila = 1;
                var filas = 1;
                var descripcionp = descrip[jk];
                var cortesia = cortes[jk];
                var select2 = selec[jk];
                var id_prodd = idp[jk];
                if (tipo == "E") {
                    $("#inventable  tr").each(function(index) {
                        if (index >= 0) {
                            var campo0 = "";
                            $(this).children("td").each(function(index2) {
                                switch (index2) {
                                    case 0:
                                        campo0 = $(this).text();
                                        if (campo0 != undefined || campo0 != '') {
                                            id_previo.push(campo0);
                                        }
                                        break;
                                }
                            });
                            if (campo0 != "") {
                                filas = filas + 1;
                            }
                        } //if index>0
                    });
                }
                var pert = "";
                if (tipo == "P") {
                    pert = "EP P" + id_prod;
                    filas = "";
                }
                /*tr_add += "<tr class='row100  head E "+pert+"' id='"+id_prodd+"'>";
                tr_add += "<td class='cell100 column10 text-success id_pps'>"+filas+"<input type='hidden'  class='txt_box' id='cuanto' name='cuanto' value='"+cuantos+"'><input type='hidden'  class='txt_box decimal2 ' cant' id='cant' name='cant' value='1' style='width:50px;'readOnly></td>";
                tr_add += "<td class='cell100 column30 text-success descp' id='desc'>" +descripcionp + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prodd + "'></td>";
                tr_add += "<td class='cell100 column55 text-success' id='precio_venta'><input type='hidden'  class='form-control decimal' id='precio_venta' name='precio_venta' value='"+select2+"'>"+select2+"</td>";
                //tr_add += "<td class='cell100 column9 text-success'>"+"<input type='hidden'  id='cortesia' name='cortesia' vatr_addlue='0'>"+"<input type='hidden'  id='idco' name='idco' value='"+filas+"'>"+cortesia+"</td>";
                tr_add += "<td class='ccell100 column7'><input type='hidden'  id='subtotal_fin' name='subtotal_fin' value='"+selec+"'>" + "<input type='hidden'  class='decimal txt_box' id='subtotal_mostrar' name='subtotal_mostrar'  value='"+selec+"'style='width:55px;'readOnly></td>";
                */
                if (tipo == "P") {
                    var cantidades = "<td class='cell100 column5 text-success'><input type='text'  class='form-control decimal' id='cant' name='cant'  value='1' style='width:60px;' readonly /></td>";
                    var id_insumo1 = "<input type='hidden'  class='id_insumo' id='id_insumo' name='id_insumo' value='" + 1 + "'>";
                    var tipop = "<input type='hidden'  class='tipop' id='tipopr' name='tipopr' value='" + tipo + "'>";
                    tr_add += "<tr class='row100  head E " + pert + "' id='" + id_prodd + "'>";
                    //tr_add += "<td class='cell100 column10 text-success id_pps'>"+filas+"<input type='hidden'  class='txt_box' id='cuanto' name='cuanto' value='"+cuantos+"'><input type='hidden'  class='txt_box decimal2 ' cant' id='cant' name='cant' value='1' style='width:50px;'readOnly></td>";

                    tr_add += "<td class='cell100 column10 text-success id_pps'><input type='hidden'  class='txt_box decimal2  cantx' id='unidadp' name='unidadp' value='" + '1' + "'></td>";
                    tr_add += "<td class='cell100 column30 text-success descp' id='desc'>" + (descripcionp) + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prodd + "'></td>";
                    //tr_add += "<td class='cell100 column10 text-success' id='cant_stock'>" +1 + "</td>"
                    tr_add += "<td class='cell100 column10 text-success' id='cant_stockHidden' style='display:none;'>" + 1 + "</td>";
                    tr_add += "<td class='cell100 column10 text-success' id='fecha_hora_Hidden' style='display:none;'>" + (fecha + " " + hora) + "</td>";
                    tr_add += "<td class='cell100 column15 text-success preccs'>&nbsp;</td>";
                    //tr_add += "<td class='cell100 column10 text-success' id='precio_ventas'><input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='' readOnly></td>";
                    //tr_add += cantidades;
                    //tr_add += "<td class='ccell100 column10'>" +tipop + "<input type='text'  class='decimal form-control' id='subtotal_fin' name='subtotal_fin'  value='" + selec + "'readOnly></td>";
                } else {
                    var cantidades = "<td class='cell100 column5 text-success'><input type='text'  class='form-control decimal' id='cant' name='cant'  value='1' style='width:60px;' readonly /></td>";
                    var id_insumo1 = "<input type='hidden'  class='id_insumo' id='id_insumo' name='id_insumo' value='" + 1 + "'>";
                    var tipop = "<input type='hidden'  class='tipop' id='tipopr' name='tipopr' value='" + tipo + "'>";
                    tr_add += "<tr class='row100 head COBRADO " + pert + "' id='" + id_prodd + "'>";
                    //tr_add += "<td class='cell100 column10 text-success id_pps'>"+filas+"<input type='hidden'  class='txt_box' id='cuanto' name='cuanto' value='"+cuantos+"'><input type='hidden'  class='txt_box decimal2 ' cant' id='cant' name='cant' value='1' style='width:50px;'readOnly></td>";
                    tr_add += "<td class='cell100 column10 text-success id_pps'>" + id_prod + "<input type='hidden'  class='txt_box decimal2  cantx' id='unidadp' name='unidadp' value='" + '1' + "'></td>";
                    tr_add += "<td class='cell100 column30 text-success descp' id='desc'>" + (descripcionp) + "<input type='hidden'  class='form-control ' readOnly id='id_prod' name='id_prod' value='" + id_prodd + "'></td>";
                    tr_add += "<td class='cell100 column10 text-success' id='cant_stock'>" + 1 + "</td>"
                    tr_add += "<td class='cell100 column10 text-success' id='cant_stockHidden' style='display:none;'>" + 1 + "</td>";
                    tr_add += "<td class='cell100 column10 text-success' id='fecha_hora_Hidden' style='display:none;'>" + (fecha + " " + hora) + "</td>";
                    tr_add += "<td class='cell100 column15 text-success preccs'>&nbsp;</td>";
                    tr_add += "<td class='cell100 column10 text-success' id='precio_ventas'><input type='text'  class='form-control decimal' id='precio_venta' name='precio_venta' value='" + select2 + "' readOnly></td>";
                    tr_add += cantidades;
                    tr_add += "<td class='ccell100 column10'>" + tipop + "<input type='text'  class='decimal form-control' id='subtotal_fin' name='subtotal_fin'  value='" + select2 + "'readOnly></td>";
                }
                //tr_add += '</tr>';
                if (tipo == "E") {
                    tr_add += '<td class="cell100 column8 text-center"><input id="delprod" type="button" class="btn btn-danger fa Delete"  value="&#xf1f8;"></td>';
                } else {
                    tr_add += '<td class="cell100 column8"></td>';
                }
                tr_add += '</tr>';
                if (!id_existente(id_prodd, 'E')) {
                    $("#inventable").prepend(tr_add);
                }
                if (tipo == "E") {
                    filas++;
                }
            }
            scrolltable();
            totales();

        }
    });
}