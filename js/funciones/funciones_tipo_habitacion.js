$(document).ready(function() {
    generar2();

    $.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    }, 'Mensaje a mostrar si se incumple la condición');

    $('#formulario_habitacion').validate({
        rules: {
            capacidad: {
                required: true,
                regexp: /^[0-9\-]+$/
            },
            tipo_habitacion: {
                required: true,
            },
            descripcion: {
                required: true,
            },
        },
        messages: {
            capacidad: {
                required: "Por favor ingrese la capacidad de pacientes.",
                regexp: "El campo capacidad de pacientes tiene un formato no es valido"
            },
            tipo_habitacion: {
                required: "Por favor ingrese el tipo de habitacion",
            },
            descripcion: {
                required: "Por favor ingrese la descripcion de la habitacion",
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
    //jquery validate y regexp
});

function reload1() {
    location.href = 'admin_tipo_habitacion.php';
}

function senddata() {
    var process = $('#process').val();
    var capacidad = $('#capacidad').val();
    var tipo_habitacion = $('#tipo_habitacion').val();
    var descripcion = $('#descripcion').val();
    var urlprocess = "";
    var id_habitacion = 0;
    if (process == 'insert') {
        urlprocess = 'agregar_tipo_habitacion.php';
    }
    if (process == 'edited') {
        urlprocess = 'editar_tipo_habitacion.php';
        id_habitacion = $("#id_tipo_habitacion").val();
    }
    var dataString = 'process=' + process + '&capacidad=' + capacidad + '&tipo_habitacion=' + tipo_habitacion;
    dataString += '&descripcion=' + descripcion + '&id_habitacion=' + id_habitacion;
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


function deleted() {

    var id_tipo_habitacion = $('#id_tipo_habitacion').val();
    var dataString = 'process=deleted' + '&id_tipo_habitacion=' + id_tipo_habitacion;
    $.ajax({
        type: "POST",
        url: "borrar_tipo_habitacion.php",
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

function estado() {

    var id_tipo_habitacion = $('#id_tipo_habitacion').val();
    var estado = $('#estado').val();
    var dataString = 'process=estado' + '&id_tipo_habitacion=' + id_tipo_habitacion + '&estado=' + estado;
    $.ajax({
        type: "POST",
        url: "estado_tipo_habitacion.php",
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
$(function() {
    //binding event click for button in modal form
    $(document).on("click", "#btnDelete", function(event) {
        deleted();
    });
    $(document).on("click", "#btnEstado", function(event) {
        estado();
    });
    $(document).on("click", "#btnVolver", function(event) {
        reload1();
    });
    // Clean the modal form
    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });

});


function generar2() {
    dataTable = $('#editable2').DataTable().destroy()
    dataTable = $('#editable2').DataTable({
        "pageLength": 50,
        "responsive": true,
        "autoWidth": false,
        "order": [0, 'asc'],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "admin_tipo_habitacion_dt.php", // json datasource
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