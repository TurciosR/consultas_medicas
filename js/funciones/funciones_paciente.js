$(document).ready(function() {
    generar2();
    $(".select").select2();
    $('#formulario_paciente').validate({
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
            telefono1: {
                required: true,
            },
            fecha: {
                required: true,
            },
            departamento: {
                required: true,
            },
            municipio: {
                required: true,
            },
            direccion: {
                required: true,
            },
            tipo: {
                required: true,
            },

        },
        messages: {
            nombre: "Por favor ingrese el nombre del paciente",
            apellido: "Por favor ingrese el apellido del paciente",
            sexo: "Por favor seleccione el género",
            telefono1: "Por favor ingrese el número de teléfono",
            fecha: "Por favor ingrese la fecha de nacimiento",
            departamento: "Por favor seleccione un departamento",
            municipio: "Por favor seleccione un municipio",
            direccion: "Por favor ingrese la dirección del paciente",
            tipo: "Por favor seleccione el la forma de notificación",
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
    if ($("#process").val() == "edit") {
        if ($("#email").val() != "") {
            if ($("#notificacion").val() == "Correo") {
                $("#tipo").append("<option value='Correo' selected >Correo Electrónico</option>");
                $("#select2-tipo-container").text("Correo\ Electrónico");
            } else {
                $("#tipo").append("<option value='Correo'>Correo Electrónico</option>");
            }
        }
    }
    $("#departamento").change(function() {
        $("#municipio *").remove();
        $("#select2-municipio-container").text("");
        var ajaxdata = { "process": "municipio", "id_departamento": $("#departamento").val() };
        $.ajax({
            url: "agregar_paciente.php",
            type: "POST",
            data: ajaxdata,
            success: function(opciones) {
                $("#select2-municipio-container").text("Seleccione");
                $("#municipio").html(opciones);
                $("#municipio").val("");
            }
        })
    });
    $('.tel').on('keydown', function(event) {
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
    $('#dui').on('keydown', function(event) {
        if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39) {

        } else {
            inputval = $(this).val();
            var string = inputval.replace(/[^0-9]/g, "");
            var bloc1 = string.substring(0, 8);
            var bloc2 = string.substring(9, 10);
            var string = bloc1 + "-" + bloc2;
            $(this).val(string);
        }
    });
    $("#email").focusout(function() {
        if ($(this).val() != "") {
            var ya = false;
            $("#tipo option").each(function() {
                if ($(this).val() == "Correo") {
                    ya = true;
                }
            });
            if (!ya) {
                $("#tipo").append("<option value='Correo'>Correo Electrónico</option>");
            }
        } else {
            $("#tipo option").each(function() {
                if ($(this).val() == "Correo") {
                    $(this).remove();
                }
            });
        }
    });
    $("#responsable").focusout(function() {
        if ($(this).val() != "") {
            $("#parentezco").attr("required", true);
        } else {
            $("#parentezco").attr("required", false);
        }
    });
});
$(function() {
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

function autosave(val) {
    var name = $('#name').val();
    if (name == '' || name.length == 0) {
        var typeinfo = "Info";
        var msg = "The field name is required";
        display_notify(typeinfo, msg);
        $('#name').focus();
    } else {
        senddata();
    }
}

function senddata() {
    var nombre = $('#nombre').val();
    var apellido = $('#apellido').val();
    var direccion = $('#direccion').val();
    var telefono1 = $('#telefono1').val();
    var telefono2 = $('#telefono2').val();
    var email = $('#email').val();
    var municipio = $('#municipio').val();
    var sexo = $('#sexo').val();
    var fecha = $('#fecha').val();
    var alergias = $('#alergias').val();
    var padecimientos = $('#padecimientos').val();
    var medicamentos = $('#medicamentos').val();
    var responsable = $('#responsable').val();
    var parentezco = $('#parentezco').val();
    var dui = $('#dui').val();
    var estado_civil = $('#estado_civil').val();
    var religion = $('#religion').val();
    var escolaridad = $('#escolaridad').val();
    var conyuge = $('#conyuge').val();
    var grupo_sanguineo = $('#grupo_sanguineo').val();
    var referido = $('#referido').val();
    var tipo = $('#tipo').val();

    //Get the value from form if edit or insert
    var process = $('#process').val();

    if (process == 'insert') {
        var id_paciente = 0;
        var urlprocess = 'agregar_paciente.php';
    }
    if (process == 'edit') {
        var id_paciente = $('#id_paciente').val();
        var urlprocess = 'editar_paciente.php';
    }
    var dataString = 'process=' + process + '&id_paciente=' + id_paciente + '&nombre=' + nombre + '&apellido=' + apellido + '&sexo=' + sexo;
    dataString += '&direccion=' + direccion + '&telefono1=' + telefono1 + '&telefono2=' + telefono2 + '&email=' + email + '&municipio=' + municipio;
    dataString += '&responsable=' + responsable + '&parentezco=' + parentezco + '&alergias=' + alergias + '&padecimientos=' + padecimientos;
    dataString += '&medicamentos=' + medicamentos + '&tipo=' + tipo + '&fecha=' + fecha + '&dui=' + dui + '&estado_civil=' + estado_civil;
    dataString += '&religion=' + religion + '&conyuge=' + conyuge + '&grupo_sanguineo=' + grupo_sanguineo + '&referido=' + referido + '&escolaridad=' + escolaridad;
    $.ajax({
        type: 'POST',
        url: urlprocess,
        data: dataString,
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
    location.href = 'admin_paciente.php';
}

function deleted() {
    var id_paciente = $('#id_paciente').val();
    var dataString = 'process=deleted' + '&id_paciente=' + id_paciente;
    $.ajax({
        type: "POST",
        url: "borrar_paciente.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                setInterval("reload1();", 1500);
                $('#deleteModal').hide();
            }
        }
    });
}



function generar2() {
    dataTable = $('#editable2').DataTable().destroy()
    dataTable = $('#editable2').DataTable({
        "pageLength": 50,
        "responsive": true,
        "autoWidth": false,
        "order": [0, 'desc'],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "admin_paciente_dt.php", // json datasource
            //url :"admin_factura_rangos_dt.php", // json datasource
            //type: "post",  // method  , by default get
            error: function() { // error handling
                $(".editable2-error").html("");
                $("#editable2").append('<tbody class="editable_grid-error"><tr><th colspan="3">No se encontró información segun busqueda </th></tr></tbody>');
                $("#editable2_processing").css("display", "none");
                $(".editable2-error").remove();
            }
        }
    });
    dataTable.ajax.reload();
    //}
}