$(document).ready(function() {
    $(".select").select2();
    $("#paciente_replace").hide();
    $("#servicioRE_replace").hide();
    $("#hora_entrada_replace").hide();
    $("#otroParentezco").prop("disabled", true);
    actualizarTablaServiciosRecepcion();

    //Jquery validation para el formulario de agregar servicios a la recepcion
    $('#formulario_agregar_servicios').validate({
        rules: {
            servicioRE_replace: {
                required: true
            },
            servicioRE: {
                required: true
            },
            fechaEntrada: {
                required: true
            },
            hora_entrada: {
                required: true
            }
        },
        messages: {
            servicioRE_replace: {
                required: "Por favor ingrese el nombre del servicio."
            },
            servicioRE: {
                required: "Por favor ingrese el nombre del servicio."
            },
            fechaEntrada: {
                required: "Por favor ingrese la fecha a la que se aplicara el servicio."
            },
            hora_entrada: {
                required: "Por favor ingrese la hora a la que se le aplicara el servicio."
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function(form) {
            agregaServicioARecepcion();
        }
    });


    //Final de jquery validation para el formulario de agregar servicios a la recepcion




    //Funcion para recuperar los servicios

    $("#servicioRE").typeahead({
        source: function(query, process) {
            $.ajax({
                type: 'POST',
                url: 'autocomplete_servicio.php',
                data: 'query=' + query,
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
            var id_p = prod[0];
            var nombre = prod[1];
            var naci = prod[2];
            var sexo = prod[3];
            var fecha_nacimiento = prod[4];
            var telefono = prod[5];
            $('#servicioRE_replace').val(nombre);
            $('#serviciooRE').val(id_p);
            $('#servicioRE_replace').show();
            $('#servicioRE').hide();
        }
    });


    //Fin para recuperar los servicios
    //funcion para cambiar del campo servicioRE_replace al campo servicioRE
    $(document).on("focus", "#servicioRE_replace", function() {
        $(this).val("");
        $(this).hide();
        $("#servicioRE").show();
        $("#servicioRE").focus();
    });

    //Final de la funcion para cambiar del campo servicioRE_replace al campo servicioRE

    //Funcion para cambiar del campo paciente_replace al paciente
    $(document).on("focus", "#paciente_replace", function() {
        $(this).val("");
        $(this).hide();
        $("#paciente").show();
        $("#paciente").focus();
    });

    //Final de funcion para cambiar del campo paciente_replace al campo paciente


    //Comprobar si el checkbox Responsable ingresado ha sido marcado
    if ($('#parienteResponsable').is(':checked')) {
        cargarDatosPariente();
    } else {
        eliminarDatosPariente();
    }
    //Final comprobar si el checkbox Responsable ingresado ha sid marcado

    //empiza el typeahead del campo paciente

    $("#paciente").typeahead({
        source: function(query, process) {
            $.ajax({
                type: 'POST',
                url: 'autocomplete_paciente.php',
                data: 'query=' + query,
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
            var id_p = prod[0];
            var nombre = prod[1];
            var naci = prod[2];
            var sexo = prod[3];
            var fecha_nacimiento = prod[4];
            var telefono = prod[5];
            $('#paciente_replace').val(nombre);
            $('#pacientee').val(id_p);
            $('#id_paciente').val(id_p);
            $('#paciente_replace').show();
            $('#paciente').hide();
            if ($("#parienteResponsable").is(':checked')) {
                cargarDatosPariente();
            }
        }
    });
    //termina el typeahead del campo paciente


    //Jquery validation inicio

    $.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    }, 'Mensaje a mostrar si se incumple la condición');


    $('#formulario_crear_recepcion').validate({
        rules: {
            doctor: {
                required: true
            },
            fechaEntrada: {
                required: true
            },
            hora_entrada: {
                required: true
            },
            paciente: {
                required: true
            },
            descripcionEvento: {
                required: true
            }
        },
        messages: {
            doctor: {
                required: "Por favor ingrese el nombre del doctor."
            },
            fechaEntrada: {
                required: "Por favor ingrese la fecha de entrada a recepcion."
            },
            hora_entrada: {
                required: "Por favor ingrese la hora de entrada a recepcion."
            },
            paciente: {
                required: "Por favor ingrese el nombre del doctor."
            },
            descripcionEvento: {
                required: "Por favor ingrese la descripcion del evento de la recepcion."
            },
        },
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

    //Jquery validation final

    //Jquery validation inicial para agregar servicios


    //Final de Jquery validation inicial para agregar servicios



    //Inicio telefono

    $('#telefonoPariente').on('keydown', function(event) {
        if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {

        } else {
            inputval = $(this).val();
            var string = inputval.replace(/[^0-9]/g, "");
            var bloc1 = string.substring(0, 4);
            var bloc2 = string.substring(4, 7);
            var string = bloc1 + "-" + bloc2;
            $(this).val(string);
        }
    });

    //Final telefono
    //Principio on change del select

    $('#parentezcoSelect').on('change', function() {
        var valor = $("#parentezcoSelect option:selected").text();
        if (valor == "Otro") {
            $("#otroParentezco").prop("disabled", false);
        } else {
            $("#otroParentezco").prop("disabled", true);
        }
    });
    //final del on cahnge del select
});

// Clean the modal form
$(document).on('hidden.bs.modal', function(e) {
    var target = $(e.target);
    target.removeData('bs.modal').find(".modal-content").html('');
});

function cargarDatosPariente() {
    idPaciente = $("#pacientee").val();
    var ajaxdata = { "idPaciente": idPaciente };
    $.ajax({
        url: "recuperar_pariente_paciente.php",
        type: "POST",
        data: ajaxdata,
        async: true,
        success: function(opciones) {
            var prod1 = opciones;
            var prod1 = prod1.split("|");
            var responsable = prod1[0];
            var parentezco = prod1[1];
            var parentezco_responsable = prod1[2];
            $("#parentezcoSelect").val(parentezco_responsable);
            $("#parentezcoSelect").trigger("change");
            $('#pariente').val(responsable);
            $("#parentezcoSelect").prop("disabled", true);
            $("#pariente").prop("disabled", true);
            $("#otroParentezco").prop("disabled", true);
        }
    });
}

function eliminarDatosPariente() {
    $("#parentezcoSelect").val(0);
    $("#parentezcoSelect").trigger("change");
    $('#pariente').val("");
    $("#parentezcoSelect").prop("disabled", false);
    $("#pariente").prop("disabled", false);
}

function eliminarServicio(id) {
    var idServicio = $('#btnEliminar' + id).val();

}

function actualizarTablaServiciosRecepcion() {
    $.ajax({
        type: 'POST',
        url: 'tablaServiciosRecepcion.php',
        success: function(datax) {
            $("#tablaServiciosRecepciones").html(datax);
        }
    });
}

function agregaServicioARecepcion() {
    var idServicio = $('#serviciooRE').val();
    var fecha = $('#fechaEntrada').val();
    var hora = $('#hora_entrada').val();
    var urlprocess = 'agregar_servicio_recepcion.php';
    var idRecepcion = $('#id_recepcion').val();
    var process = $('#process1').val();
    var dataString = 'process=' + process + '&idServicio=' + idServicio + '&fechaEntrada=' + fecha + '&hora_entrada=' + hora + '&idRecepcion=' + idRecepcion;
    $.ajax({
        type: 'POST',
        url: urlprocess,
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg, datax.process);
            if (datax.typeinfo == "Success") {
                actualizarTablaServiciosRecepcion();
                $("#servicioRE_replace").val("");
                $("#servicioRE_replace").hide();
                $("#servicioRE_replace").show();
                $("#servicioRE_replace").focus();
                $("#hora_entrada").val("");
            }
        }
    });
}

function senddata() {
    var doctor = $('#doctor').val();
    var fechaEntrada = $('#fechaEntrada').val();
    var hora_entrada = $('#hora_entrada').val();
    var emergencia = $('#emergencia').val();
    var doctorReferido = $('#doctorReferido').val();
    var paciente_replace = $('#pacientee').val();
    var descripcionEvento = $('#descripcionEvento').val();
    var pariente = $('#pariente').val();
    var tipoOriginal = $('#OriginalEstado').val();
    var parienteResponsable = $('#parienteResponsable').val();
    var telefonoPariente = $('#telefonoPariente').val();
    var parentezcoSelect = $('#parentezcoSelect').val();
    var otroParentezco = $('#otroParentezco').val();
    var tipoRecepcion = 0;
    var doctor_refiere = $("#doctor_refiere").val();

    var process = $('#process').val();
    var recuperadoBase = 0;
    if (process == 'insert') {
        var urlprocess = 'crear_recepcion_db.php';
    }
    if (process == 'edit') {
        var urlprocess = 'editar_recepcion_bd.php';
    }
    var errorAcumulado = 0;
    if (!$("#emergencia").is(':checked')) {
        if (!$("#doctorReferido").is(':checked')) {
            var msg = 'Tiene que espeficiar si la recepcion es emergencia o por un doctor referido.';
            var typeinfo = "Error";
            display_notify(typeinfo, msg, "");
            errorAcumulado++;
        } else {
            tipoRecepcion = 2;
        }
    } else {
        tipoRecepcion = 1;
    }
    if (errorAcumulado == 0 || pariente == "") {
        if ($("#parienteResponsable").is(':checked')) {
            if (pariente == "") {
                var msg = 'Tiene que ingresar el nombre del pariente responsable.';
                var typeinfo = "Error";
                display_notify(typeinfo, msg, "");
                errorAcumulado++;
            }
        }
    }
    if (errorAcumulado == 0) {
        if ($("#parienteResponsable").is(':checked')) {
            if (parentezcoSelect == 0) {
                var msg = 'Tiene que asignar un parentezco.';
                var typeinfo = "Error";
                display_notify(typeinfo, msg, "");
                errorAcumulado++;
            }
        }
    }
    if (errorAcumulado == 0) {
        if ($("#parienteResponsable").is(':checked')) {
            if (telefonoPariente == "") {
                var msg = 'Tiene que asignar un numero de telefono.';
                var typeinfo = "Error";
                display_notify(typeinfo, msg, "");
                errorAcumulado++;
            }
        }
    }
    if (errorAcumulado == 0) {
        if (telefonoPariente != "") {
            if (parentezcoSelect == 0) {
                var msg = 'Tiene que asignar un parentezco.';
                var typeinfo = "Error";
                display_notify(typeinfo, msg, "");
                errorAcumulado++;
            }
            if (errorAcumulado == 0) {
                if (pariente == "") {
                    var msg = 'Tiene que ingresar el nombre del pariente.';
                    var typeinfo = "Error";
                    display_notify(typeinfo, msg, "");
                    errorAcumulado++;
                }
            }
        }
    }
    if (errorAcumulado == 0) {
        if (parentezcoSelect != 0) {
            if (telefonoPariente == "") {
                var msg = 'Tiene que ingresar el numero del telefono.';
                var typeinfo = "Error";
                display_notify(typeinfo, msg, "");
                errorAcumulado++;
            }
            if (errorAcumulado == 0) {
                if (pariente == "") {
                    var msg = 'Tiene que ingresar el nombre del pariente.';
                    var typeinfo = "Error";
                    display_notify(typeinfo, msg, "");
                    errorAcumulado++;
                }
            }
        }
    }
    if (errorAcumulado == 0) {
        if (pariente != "") {
            if (telefonoPariente == "") {
                var msg = 'Tiene que ingresar el numero del telefono.';
                var typeinfo = "Error";
                display_notify(typeinfo, msg, "");
                errorAcumulado++;
            }
            if (errorAcumulado == 0) {
                if (parentezcoSelect == 0) {
                    var msg = 'Tiene que asignar un parentezco.';
                    var typeinfo = "Error";
                    display_notify(typeinfo, msg, "");
                    errorAcumulado++;
                }
            }
        }
    }
    if (errorAcumulado == 0) {
        var valo = $('select[name="parentezcoSelect"] option:selected').text()
        if (valo == "Otro") {
            if (otroParentezco == "") {
                var msg = 'Tiene que asignar el otro parentezco.';
                var typeinfo = "Error";
                display_notify(typeinfo, msg, "");
                errorAcumulado++;
            }
        }
    }
    if (errorAcumulado == 0) {
        if ($("#parienteResponsable").is(':checked')) {
            recuperadoBase = 1;
        }
    }
    var dataString = 'process=' + process + '&doctor=' + doctor + '&fechaEntrada=' + fechaEntrada + '&hora_entrada=' + hora_entrada + '&tipoOriginal=' + tipoOriginal;
    dataString += '&emergencia=' + emergencia + '&doctorReferido=' + doctorReferido + '&paciente_replace=' + paciente_replace + '&descripcionEvento=' + descripcionEvento + '&pariente=' + pariente + "&doctor_refiere=" + doctor_refiere;
    dataString += '&parienteResponsable=' + parienteResponsable + '&telefonoPariente=' + telefonoPariente + '&parentezcoSelect=' + parentezcoSelect + '&otroParentezco=' + otroParentezco + '&tipoRecepcion=' + tipoRecepcion + '&recuperadoBase=' + recuperadoBase;
    if (errorAcumulado > 0) {} else {

        $.ajax({
            type: 'POST',
            url: urlprocess,
            data: dataString,
            dataType: 'json',
            success: function(datax) {
                display_notify(datax.typeinfo, datax.msg, datax.process);
                if (datax.typeinfo == "Success") {
                    sleep(1500).then(() => {
                        reload1();
                    });
                }
            }
        });
    }


}

function reload1() {
    location.href = 'admin_recepcion.php';
}

function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}

$(document).on("click", "#btnDelete", function(event) {
    anular();
});

function anular() {
    var idRecepcion = $('#idRecepcion').val();
    var dataString = 'process=anular_datos' + '&idRecepcion=' + idRecepcion;
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
$(document).on("click", "#btnDeleteServicio1", function(event) {
    eliminarServicioRecepcion();
});

function eliminarServicioRecepcion() {
    var idRecepcion11 = "";
    var idServicio11 = "";
    idRecepcion11 = $('#idRecepcion').val();
    idServicio11 = $('#idServicio').val();
    var dataString = 'process=eliminar_servicio_bd' + '&idRecepcion=' + idRecepcion11 + '&idServicio=' + idServicio11;
    $.ajax({
        type: "POST",
        url: "tablaServiciosRecepcion.php",
        data: dataString,
        dataType: 'json',
        async: 'false',
        success: function(datax) {
            $('#deleteServicioModal').modal('hide');
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                actualizarTablaServiciosRecepcion();
            }
        }
    });
}