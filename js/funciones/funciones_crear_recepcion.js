var tipo = "1";
$(document).ready(function() {
    $(".select").select2();
    $("#paciente_replace").hide();
    $("#hora_entrada_replace").hide();

    $("#otroParentezco").prop("disabled", true);
    $("#hora_entrada").timepicki();

    $("#paciente").keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });
    $("#naci").keyup(function() {
        var tipo_form = $("#check_nacimiento").val();
        if (tipo_form == "1") {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() - 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            var date = new Date(yyyy, mm, dd);
            date.setMonth(date.getMonth() - (12 * $("#naci").val()));
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            $("#fecha_nacimiento").val([year, month, day].join('-'));
        }
        if (tipo_form == "2") {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() - 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            var date = new Date(yyyy, mm, dd);
            date.setMonth(date.getMonth() - ($("#naci").val()));
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            $("#fecha_nacimiento").val([year, month, day].join('-'));
        }
    });

    $("#check_nacimiento").change(function() {

        if ($(this).val() == "3") {

            $("#fecha_nacimiento").show();
            $("#fecha_nacimiento_label").show();
            $("#naci").hide();
            $("#naci_label").hide();
        } else {
            $("#fecha_nacimiento").hide();
            $("#fecha_nacimiento_label").hide();
            $("#naci").show();
            $("#naci_label").show();

            if ($(this).val() == 2 && $("#fecha_nacimiento").val() != "") {
                var fecha_naci = $("#fecha_nacimiento").val();
                var comprobar = fecha_naci.split("-");
                if (comprobar[2].length == 4) {
                    fecha_naci = comprobar[2] + "-" + comprobar[1] + "-" + comprobar[0];
                }
                var pfn = fecha_naci.split("-");
                var fecha_actual = Date.now();
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                var edad_meses = monthDiff(new Date(pfn[0], pfn[1], pfn[2]), new Date(yyyy, mm, dd));
                $("#naci").val(edad_meses);
            }
            if ($(this).val() == 1 && $("#fecha_nacimiento").val() != "") {
                var fecha_naci = $("#fecha_nacimiento").val();
                var comprobar = fecha_naci.split("-");
                if (comprobar[2].length == 4) {
                    fecha_naci = comprobar[2] + "-" + comprobar[1] + "-" + comprobar[0];
                }
                $("#naci").val(calculate_age(fecha_naci));
                tipo = "1";
            }
        }
    });


    //Funcion para cambiar del campo paciente_replace al paciente
    $(document).on("focus", "#paciente_replace", function() {
        $(this).val("");
        $(this).hide();
        $("#paciente").show();
        $("#paciente").focus();
        $("#pacientee").val("");
        $("#fecha_nacimiento").val("");
        $("#sexo").val("");
        $("#dui").val("");
        $("#direccion").val("");
    });

    //Final de funcion para cambiar del campo paciente_replace al campo paciente

    /* COMPROBAR CUANDO CAMBIE EL CHECKBOX  DEL PACIENTE AMBULATORIO, Y CUANDO CAMBIE SE
    VERIFICARA SI ESTA CHECADO Y SI ES ASI EL VALUE DE ESTE SERA 1, DE LO CONTRARIO EL VALUE
    SERA 0 */

    $('#paciente_ambulatorio').on('change', function() {
        if ($(this).is(':checked')) {
            $("#paciente_ambulatorio").val(1);
        } else {
            $("#paciente_ambulatorio").val(0);
        }
    });
    /* FINAL DE COMPROBACION DEL CHECKBOX PACIENTE AMBULATORIO */

    /* ---------------------- */

    //Comprobar si el checkbox Responsable ingresado ha sido marcado
    $('#parienteResponsable').on('change', function() {
        if ($(this).is(':checked')) {
            if ($('#paciente').is(':visible')) {
                swal({
                        title: "Paciente no registrado",
                        text: "Primero tiene que escoger un paciente",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Ok",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm) {
                        if (isConfirm) {

                        } else {}
                    });
            } else {
                cargarDatosPariente();
            }
        } else {
            eliminarDatosPariente();
        }
    });
    //Final comprobar si el checkbox Responsable ingresado ha sid marcado

    //empiza el typeahead del campo paciente

    $("#paciente").typeahead({
        source: function(query, process) {
            $('#pacientee').val("-1");
            $.ajax({
                type: 'POST',
                url: 'autocomplete_paciente2.php',
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
            var naci = prod[2];
            //alert(naci);
            var sexo = prod[3];
            var fecha_nacimiento = prod[2];
            var direccion = prod[4];
            var dui = prod[5];
            //alert("SELECT: "+sexo);
            $('#paciente_replace').val(nombre);
            $('#pacientee').val(id_p);
            $('#paciente_replace').show();
            $('#paciente').hide();
            $("#sexo").val(sexo);
            $('#fecha_nacimiento').val(fecha_nacimiento);
            $('#dui').val(dui);
            $('#direccion').val(direccion);
            //agregar_producto_lista(id_prod, descrip, isbarcode);
        }
    });
    //termina el typeahead del campo paciente


    //Jquery validation inicio

    $.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    }, 'Mensaje a mostrar si se incumple la condición');


    $('#formulario_crear_recepcion').validate({
        rules: {

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

            fechaEntrada: {
                required: "Por favor ingrese la fecha de entrada a recepcion."
            },
            hora_entrada: {
                required: "Por favor ingrese la hora de entrada a recepcion."
            },
            paciente: {
                required: "Por favor ingrese el nombre del paciente."
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
        if (valor == "OTRO") {
            $("#otroParentezco").prop("disabled", false);
        } else {
            $("#otroParentezco").prop("disabled", true);
        }
    });
    //final del on cahnge del select
});
$('#fechaEntrada').on('keydown', function(event) {
    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {} else {
        if ((event.keyCode > 47 && event.keyCode < 60) || (event.keyCode > 95 && event.keyCode < 106)) {
            inputval = $(this).val();
            var string = inputval.replace(/[^0-9]/g, "");
            var bloc1 = string.substring(0, 2);
            var bloc2 = string.substring(2, 4);
            var bloc3 = string.substring(4, 7);
            var string = bloc1 + "-" + bloc2 + "-" + bloc3;
            $(this).val(string);
        } else {
            event.preventDefault();
        }
    }
});
$('#fecha_nacimiento').on('keydown', function(event) {
    if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {} else {
        if ((event.keyCode > 47 && event.keyCode < 60) || (event.keyCode > 95 && event.keyCode < 106)) {
            inputval = $(this).val();
            var string = inputval.replace(/[^0-9]/g, "");
            var bloc1 = string.substring(0, 2);
            var bloc2 = string.substring(2, 4);
            var bloc3 = string.substring(4, 7);
            var string = bloc1 + "-" + bloc2 + "-" + bloc3;
            $(this).val(string);
        } else {
            event.preventDefault();
        }
    }
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


function senddata() {
    var doctor = $('#doctor').val();
    var doctor_refiere = $('#doctor_refiere').val();
    var fechaEntrada = $('#fechaEntrada').val();
    var hora_entrada = $('#hora_entrada').val();
    var emergencia = $('#emergencia').val();
    var paciente_replace = $('#pacientee').val();
    var descripcionEvento = $('#descripcionEvento').val();
    var pariente = $('#pariente').val();
    var naci = $('#naci').val();
    var fecha_nacimiento = $('#fecha_nacimiento').val();
    var sexo = $('#sexo').val();
    var paciente = $("#paciente").val();
    var parienteResponsable = $('#parienteResponsable').val();
    var telefonoPariente = $('#telefonoPariente').val();
    var parentezcoSelect = $('#parentezcoSelect').val();
    var otroParentezco = $('#otroParentezco').val();
    var tipo_recepcion = $("#tipo_recepcion").val();
    var paciente_ambulatorio = $("#paciente_ambulatorio").val();
    var tipoRecepcion = 0;
    var process = $('#process').val();
    var recuperadoBase = 0;
    var id_recepcion_editar = -1;
    var tipoOriginal = -1;

    var errorAcumulado = 0;
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
        if (paciente_replace == "-1") {
            var msg = 'Ingrese un paciente existente!! (Si no agregarlo desde el modulo de Paciente).';
            var typeinfo = "Error";
            display_notify(typeinfo, msg, "");
            errorAcumulado++;
        }
    }
    if (errorAcumulado == 0) {
        if (doctor == "") {
            var msg = 'Ingrese el doctor que refiere al paciente!! (Si no elegir la opcion Sin referencia).';
            var typeinfo = "Error";
            display_notify(typeinfo, msg, "");
            errorAcumulado++;
        }
    }
    if (errorAcumulado == 0) {
        if ($("#parienteResponsable").is(':checked')) {
            recuperadoBase = 1;
        }
    }
    if (process == 'insert') {
        var urlprocess = 'crear_recepcion_db.php';
        var dataString = 'process=' + process + '&doctor=' + doctor + '&fechaEntrada=' + fechaEntrada + '&hora_entrada=' + hora_entrada + '&paciente_ambulatorio=' + paciente_ambulatorio;
        dataString += '&emergencia=' + emergencia + '&paciente_replace=' + paciente_replace + '&descripcionEvento=' + descripcionEvento + '&pariente=' + pariente + '&doctor_refiere=' + doctor_refiere;
        dataString += '&parienteResponsable=' + parienteResponsable + '&telefonoPariente=' + telefonoPariente + '&parentezcoSelect=' + parentezcoSelect + '&otroParentezco=' + otroParentezco + '&tipo_recepcion=' + tipo_recepcion + '&recuperadoBase=' + recuperadoBase;
        dataString += '&naci=' + naci + '&fecha_nacimiento=' + fecha_nacimiento + '&sexo=' + sexo + '&paciente=' + paciente + '&id_recepcion_editar=' + id_recepcion_editar + '&tipoOriginal=' + tipoOriginal;
    }
    if (process == 'edit') {
        id_recepcion_editar = $('#id_recepcion_editar').val();
        var urlprocess = 'editar_recepcion_bd.php';
        var dataString = 'process=' + process + '&doctor=' + doctor + '&fechaEntrada=' + fechaEntrada + '&hora_entrada=' + hora_entrada;
        dataString += '&descripcionEvento=' + descripcionEvento + '&paciente_ambulatorio=' + paciente_ambulatorio + '&doctor_refiere=' + doctor_refiere;
        dataString += '&id_recepcion_editar=' + id_recepcion_editar;
    }

    if (errorAcumulado > 0) {

    } else {
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
/* ESTA FUNCION ES LA QUE SIRVE PARA PODER INGRESAR PACIENTES
AMBULATORIOS AL SISTEMA, SOLO INGRESA AL PACIENTE Y LUEGO LO CARGA EN LOS VALORES
QUE SE MUESTRAN EN RECEPCION, Y PERMITE AGREGAR AL PACIENTE ASI NOMAS */
function senddata1() {
    let nombres = $("#nombres_ambu").val();
    let apellidos = $("#apellidos_ambu").val();
    let fecha_nacimiento = $("#fecha_nacimiento_ambu").val();
    let direccion = $("#direccion").val();

    var urlprocess = 'crear_paciente_ambulatorio.php';
    var dataString = 'process=insert&nombres=' + nombres + '&apellidos=' + apellidos + '&fecha_nacimiento=' + fecha_nacimiento + '&direccion=' + direccion;
    $.ajax({
        type: 'POST',
        url: urlprocess,
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg, datax.process);

        }
    });

}





function reload1() {
    location.href = 'admin_recepcion.php';
}

function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}

function monthDiff(d1, d2) {
    var months;
    months = (d2.getFullYear() - d1.getFullYear()) * 12;
    months -= d1.getMonth() + 1;
    months += d2.getMonth();
    return months <= 0 ? 0 : months;
}

function calculate_age(fecha) {

    separar = fecha.split("-");
    birth_month = separar[1];
    birth_day = separar[2]
    birth_year = separar[0]
    today_date = new Date();
    today_year = today_date.getFullYear();
    today_month = today_date.getMonth();
    today_day = today_date.getDate();
    age = today_year - birth_year;

    if (today_month < (birth_month - 1)) {
        age--;
    }
    if (((birth_month - 1) == today_month) && (today_day < birth_day)) {
        age--;
    }
    console.log(age);
    return age;
}