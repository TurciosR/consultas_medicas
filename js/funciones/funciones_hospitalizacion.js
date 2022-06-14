$(document).ready(function() {
    generar();
    setInterval(function() { generar(); }, 30000);
    $("#paciente_replace").hide();

    $(".select").select2();
    $(".decimal").numeric({ negative: false, decimalPlaces: 2 });

    //buscar pacientes por recepciones
    $("#paciente").typeahead({
        source: function(query, process) {
            $.ajax({
                type: 'POST',
                url: 'autocomplete_paciente_hospitalizacion.php',
                data: 'query=' + query,
                dataType: 'JSON',
                async: true,
                success: function(data) {
                    process(data);
                    //var name_paciente = $("#paciente").val();
                    //$("#id_cliente").val(name_paciente);
                }
            });
        },
        updater: function(selection) {
            var prod0 = selection;
            var prod = prod0.split("|");
            var id_p = prod[0];
            var nombre = prod[1];
            var evento = prod[3];
            var fecha = prod[4];
            var hora = prod[5];
            $.ajax({
                type: 'POST',
                url: 'agregar_hospitalizacion.php',
                data: 'process=verificar_hospitalizacion&id_paciente=' + id_p,
                dataType: 'JSON',
                async: true,
                success: function(datax) {
                    if (datax.respuesta == "1") {
                        //alert("SELECT: "+sexo);
                        $('#paciente_replace').val(nombre);
                        $('#descripcion_recepcion').val(evento);
                        $('#pacientee').val(id_p);
                        $('#paciente_replace').show();
                        $('#paciente').hide();
                        $("#producto_buscar").focus();
                        $("#fecha_de_entrada").val(fecha);
                        $("#hora_entrada").val(hora);
                        // agregar_producto_lista(id_prod, descrip, isbarcode);
                    }
                    if (datax.respuesta == "0") {
                        display_notify("Warning", "El paciente tiene una hospitalizacion activa.");
                    }
                }
            });

        }
    });

    //buscar pacientes por recepciones
    $('#hora_salida').timepicki();

    //jquery validate

    $.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    }, 'Mensaje a mostrar si se incumple la condición');

    $('#formulario_hospitalizacion').validate({
        rules: {
            paciente: {
                required: true,
            },
            numero_piso: {
                required: true,
            },
            n_habitacion: {
                required: true,
            },
            precio_habitacion: {
                required: true,
                regexp: /^\d+\.\d{0,2}$/,
            },
            fecha_de_entrada: {
                required: true,
                regexp: /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/,
            },
            hora_entrada: {
                required: true,
                regexp: /^(0?[1-9]|1[012])(:[0-5]\d) [APap][mM]$/,
            },
            id_doctor_at:{
                required:true
            }
        },
        messages: {
            paciente: {
                required: "Por favor ingrese un paciente recepcionado.",
            },
            numero_piso: {
                required: "Por favor ingrese el numero de piso de la habitacion.",
            },
            n_habitacion: {
                required: "Por favor ingrese el numero de habitacion de la habitacion",
            },
            precio_habitacion: {
                required: "Por favor ingrese el precio por hora  de la habitacion",
                regexp: 'El precio por hora de la habitacion no es valido',
            },
            fecha_de_entrada: {
                required: "Por favor ingrese la fecha de entrada",
                regexp: 'La fecha de entrada no es valida',
            },
            hora_entrada: {
                required: "Por favor ingrese la hora de entrada",
                regexp: 'La hora de entrada no es valida',
            },
            id_doctor_at:{
                required:"Porfavor debe seleccionar el doctor que va a atender al paciente"
            }
        },
        errorLabelContainer: 'paciente',
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function(form) {
            senddata();
        }
    });
    //jquery validate
    if ($("#estado_habitacion").val() != 0) {
        var ajaxdata = { "process": "estado_habitacion_edit", "estado": $("#estado_habitacion").val(), "n_habitacion": $("#n_habitacion").val() };
        $.ajax({
            url: "editar_hospitalizacion.php",
            type: "POST",
            data: ajaxdata,
            dataType: 'json',
            success: function(opciones) {
                $("#precio_habitacion").val(opciones.precio);
                $("#informacion_estado").html(opciones.resultado);
                $("#estado_habitacion").val(opciones.estado);
            }
        });

    }



});
/*$('#fecha_de_salida').on('keydown', function(event) {
    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {
    } else {
    if ((event.keyCode > 47 && event.keyCode < 60) || (event.keyCode > 95 && event.keyCode < 106)) {
        inputval = $(this).val();
        var string = inputval.replace(/[^0-9]/g, "");
        var bloc1 = string.substring(0, 2);
        var bloc2 = string.substring(2, 4);
        var bloc3 = string.substring(4, 7);
        var string = bloc1 + "-" + bloc2 + "-" + bloc3 ;
        $(this).val(string);
        } else {
            event.preventDefault();
        }
    }
});
$('#fecha_de_entrada').on('keydown', function(event) {
    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {
    } else {
    if ((event.keyCode > 47 && event.keyCode < 60) || (event.keyCode > 95 && event.keyCode < 106)) {
        inputval = $(this).val();
        var string = inputval.replace(/[^0-9]/g, "");
        var bloc1 = string.substring(0, 2);
        var bloc2 = string.substring(2, 4);
        var bloc3 = string.substring(4, 7);
        var string = bloc1 + "-" + bloc2 + "-" + bloc3 ;
        $(this).val(string);
        } else {
            event.preventDefault();
        }
    }
});*/

$(document).on("focus", "#paciente_replace", function() {
    $(this).val("");
    $(this).hide();
    $("#paciente").show();
    $("#paciente").focus();
    $('#pacientee').val("0");
});
$(document).on("focus", "#precio_habitacion", function() {
    if ($("#n_habitacion").val()) {
        $(this).val("");
        $("#precio_habitacion").prop("readonly", false);
        $("#precio_habitacion").focus();
    }
});
$(document).on("change", "#numero_piso", function(evt) {
    $("#n_habitacion *").remove();
    $("#select2-n_habitacion-container").text("");
    var ajaxdata = { "process": "habitacion", "id_piso": $("#numero_piso").val() };
    $.ajax({
        url: "agregar_hospitalizacion.php",
        type: "POST",
        data: ajaxdata,
        success: function(opciones) {
            $("#select2-n_habitacion-container").text("Seleccione");
            $("#n_habitacion").html(opciones);
            $("#precio_habitacion").prop("readonly", true);
        }
    })
});
$(document).on("change", "#n_habitacion", function(evt) {
    var ajaxdata = { "process": "estado_habitacion", "id_habitacion": $("#n_habitacion").val() };
    $.ajax({
        url: "agregar_hospitalizacion.php",
        type: "POST",
        data: ajaxdata,
        dataType: 'json',
        success: function(opciones) {
            $("#precio_habitacion").val(opciones.precio);
            $("#informacion_estado").html(opciones.resultado);
            $("#estado_habitacion").val(opciones.estado);
        }
    })
});

function generar() {
    fini = $("#fini").val();
    fin = $("#fin").val();
    dataTable = $('#editable2').DataTable().destroy()
    dataTable = $('#editable2').DataTable({
        "pageLength": 50,
        "order": [
            [0, 'desc']
        ],
        "processing": true,
        "serverSide": true,
        "rowReorder": {
            dataSrc: 'listing_order'
        },

        "autoWidth": false,
        "ajax": {
            url: "admin_hospitalizaciones_dt.php?fini=" + fini + "&fin=" + fin, // json datasource
            //url :"admin_factura_rangos_dt.php", // json datasource
            //type: "post",  // method  , by default get
            error: function() { // error handling
                $(".editable2-error").html("");
                $("#editable2").append('<tbody class="editable2_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
                $("#editable2_processing").css("display", "none");
                $(".editable2-error").remove();
            }
        },
        "columnDefs": [{
            "targets": 1, //index of column starting from 0
            "render": function(data, type, full, meta) {
                if (data != null)
                    return '<p class="text-success"><strong>' + data + '</strong></p>';
                else
                    return '';
            }
        }],
        "language": {
            "url": "js/Spanish.json"
        }
    });
    dataTable.ajax.reload()
        //}
}
$(function() {
    //binding event click for button in modal form
    $(document).on("click", "#btnDelete", function(event) {
        deleted();
    });
    $(document).on("click", "#seacr", function(event) {
        generar();
    });
    $(document).on("click", "#btnEstado", function(event) {
        estado();
    });
    $(document).on("click", "#btnVolver", function(event) {
        reload1();
    });
    $(document).on("click", "#btnAnularHospitalizacion", function(event) {
        anular_hospitalizacion();
    });
    $(document).on("click", "#btnBorrarHospitalizacion", function(event) {
        borrar_hospitalizacion();
    });


    // Clean the modal form
    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });

});

function anular_hospitalizacion() {
    swal({
            title: "Esta a punto de anular una hospitalizacion",
            text: "Esta seguro de hacerlo?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, anular",
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                var id_hospitalizacion = $("#id_hospitalizacion_anular").val();
                var ajaxdata = { "process": "anular", "id_hospitalizacion": id_hospitalizacion };
                $.ajax({
                    type: "POST",
                    url: "anular_hospitalizacion.php",
                    data: ajaxdata,
                    async: false,
                    dataType: 'json',
                    success: function(datax) {
                        display_notify(datax.typeinfo, datax.msg);
                        if (datax.typeinfo == "Success") {
                            setInterval("reload1();", 1500);
                        }
                    }
                });

            } else {
                swal("Cancelado", "Operación cancelada", "error");
                correcto++;
            }
        });
}

function borrar_hospitalizacion() {
    swal({
            title: "Esta a punto de borrar una hospitalizacion",
            text: "Esta seguro de hacerlo?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, borrar",
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                var id_hospitalizacion = $("#id_hospitalizacion_borrar").val();
                var ajaxdata = { "process": "borrar", "id_hospitalizacion": id_hospitalizacion };
                $.ajax({
                    type: "POST",
                    url: "borrar_hospitalizacion.php",
                    data: ajaxdata,
                    async: false,
                    dataType: 'json',
                    success: function(datax) {
                        display_notify(datax.typeinfo, datax.msg);
                        if (datax.typeinfo == "Success") {
                            setInterval("reload1();", 1500);
                        }
                    }
                });

            } else {
                swal("Cancelado", "Operación cancelada", "error");
                correcto++;
            }
        });
}

function senddata() {
    let id_paciente = $("#pacientee").val();
    let nombre_paciente = $("#paciente").val();
    let id_habitacion = $("#n_habitacion").val();
    let precio_por_hora = $("#precio_habitacion").val();
    let process = $("#process").val();
    let tipo_pago = 1;
    let estado_cuarto = $("#estado_habitacion").val();
    let fecha_de_entrada = $("#fecha_de_entrada").val();
    let fecha_de_salida = $("#fecha_de_salida").val();
    let hora_entrada = $("#hora_entrada").val();
    let hora_salida = $("#hora_salida").val();
    let id_doctor_at=$("#id_doctor_at").val();

    var id_recepcion = 0;
    if (!hora_salida.match(/^(0?[1-9]|1[012])(:[0-5]\d) [APap][mM]$/)) {
        hora_salida = " 00:00:00";
    }
    if ($("#radio_cobro1").is(':checked')) {
        tipo_pago = 0;
    }
    var correcto = 0;
    var dataString = "";
    if (process == 'insert') {
        var ajaxdata = { "process": "verificar_paciente", "id_paciente": id_paciente };
        $.ajax({
            url: "agregar_hospitalizacion.php",
            type: "POST",
            data: ajaxdata,
            dataType: 'json',
            async: false,
            success: function(opciones) {
                id_recepcion = opciones.id_recepcion_x;
                dataString += "process=" + process + "&id_recepcion=" + id_recepcion + "&id_cuarto=" + id_habitacion;
                dataString += "&precio_por_hora=" + precio_por_hora + "&tipo_pago=" + tipo_pago + "&fecha_de_entrada=" + fecha_de_entrada;
                dataString += "&fecha_de_salida=" + fecha_de_salida + "&hora_entrada=" + hora_entrada + "&hora_salida=" + hora_salida;
                dataString+="&id_doctor_at="+id_doctor_at;
                
                if (opciones.resultado == "1") {
                    if (estado_cuarto != "DISPONIBLE") {
                        swal({
                                title: "Esta a punto de ingresar una hospitalizacion",
                                text: "Esta seguro de hacerlo?",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Si, ingresar",
                                cancelButtonText: "No, cancelar!",
                                closeOnConfirm: true,
                                closeOnCancel: false
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    ingresar_hospitalizaicon(dataString);
                                } else {
                                    swal("Cancelado", "Operación cancelada", "error");
                                    correcto++;
                                }
                            });
                    } else {
                        ingresar_hospitalizaicon(dataString);
                    }
                } else {
                    swal("Operacion cancelada", "El paciente " + nombre_paciente + " no existe o no se encuentra registrado en la recepcion.", "error");
                }

            }
        });
    }
    if (process == 'edited') {
        let id_hospitalizacion = $("#id_hospitalizacion").val();
        let dataString = "";
        if ($("#id_estado_hospitalizacion").val() == "1") {
            dataString += "process=" + process + "&id_cuarto=" + id_habitacion + "&id_hospitaliza=" + id_habitacion;
            dataString += "&precio_por_hora=" + precio_por_hora + "&tipo_pago=" + tipo_pago + "&fecha_de_entrada=" + fecha_de_entrada;
            dataString += "&fecha_de_salida=" + fecha_de_salida + "&hora_entrada=" + hora_entrada + "&hora_salida=" + hora_salida + "&id_hospitalizacion=" + id_hospitalizacion;
            dataString+="&id_doctor_at="+id_doctor_at;
            if ($("#estado_cuarto").val() != "1") {
                swal({
                        title: "Esta a punto de editar una hospitalizacion",
                        text: "Esta seguro de hacerlo?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Si, editar",
                        cancelButtonText: "No, cancelar!",
                        closeOnConfirm: true,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            editar_hospitalizacion(dataString);
                        } else {
                            swal("Cancelado", "Operación cancelada", "error");
                            correcto++;
                        }
                    });
            } else {
                editar_hospitalizacion(dataString);
            }
        }
        if ($("#id_estado_hospitalizacion").val() == 2) {
            dataString += "process=" + process + "&id_cuarto=" + id_habitacion + "&id_hospitaliza=" + id_habitacion;
            dataString += "&precio_por_hora=" + precio_por_hora + "&tipo_pago=" + tipo_pago;
            dataString += "&fecha_de_salida=" + fecha_de_salida + "&hora_entrada=" + hora_entrada + "&hora_salida=" + hora_salida + "&id_hospitalizacion=" + id_hospitalizacion;
            dataString +="&id_doctor_at="+id_doctor_at;
            if ($("#estado_cuarto").val() != "1") {
                swal({
                        title: "Esta a punto de editar una hospitalizacion",
                        text: "Esta seguro de hacerlo?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Si, editar",
                        cancelButtonText: "No, cancelar!",
                        closeOnConfirm: true,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            editar_hospitalizacion(dataString);
                        } else {
                            swal("Cancelado", "Operación cancelada", "error");
                            correcto++;
                        }
                    });
            } else {
                editar_hospitalizacion(dataString);
            }
        }
        if ($("#id_estado_hospitalizacion").val() == 3) {

        }

    }

}

function ingresar_hospitalizaicon(dataString) {
    $.ajax({
        type: "POST",
        url: "agregar_hospitalizacion.php",
        data: dataString,
        async: false,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                setInterval("reload1();", 1500);
            }
        }
    });
}

function editar_hospitalizacion(dataString) {
    $.ajax({
        type: "POST",
        url: "editar_hospitalizacion.php",
        data: dataString,
        async: false,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                setInterval("reload1();", 1500);
            }
        }
    });
}

function reload1() {
    location.href = 'admin_hospitalizaciones.php';
}