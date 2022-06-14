$(document).ready(function() {
    generar2();
    $(".select").select2();
    $(".decimal").numeric();

    //jquery validate y regexp

    $.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    }, 'Mensaje a mostrar si se incumple la condición');

    $('#formulario_habitacion').validate({
        rules: {
            numero_piso: {
                required: true,
                regexp: /^[0-9\-]+$/
            },
            numero_habitacion: {
                required: true,
                regexp: /^[0-9\-]+$/
            },
            tipo_habitacion: {
                required: true,
            },
            descripcion: {
                required: true,
            },
            estado_habitacion: {
                required: true,
            },
            precio_por_hora: {
                required: true,
                regexp: /^(\d+\.?\d{0,9}|\.\d{1,9})$/
            },
        },
        messages: {
            numero_piso: {
                required: "Por favor ingrese el numero de piso",
                regexp: "El numero de piso no es valido"
            },
            numero_habitacion: {
                required: "Por favor ingrese el numero de habitacion",
                regexp: "El numero de habitacion no es valido"
            },
            tipo_habitacion: {
                required: "Por favor ingrese el tipo de habitacion",
            },
            descripcion: {
                required: "Por favor ingrese la descripcion de la habitacion",
            },
            estado_habitacion: {
                required: "Por favor ingrese el estado de la habitacion",
            },
            precio_por_hora: {
                required: "Por favor ingrese el precio por hora de habitacion",
                regexp: "El precio por hora de la habitacion no es valido",
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


function generar2() {
    var id_piso = $("#id_piso").val();
    dataTable = $('#editable2').DataTable().destroy()
    dataTable = $('#editable2').DataTable({
        "pageLength": 50,
        "responsive": true,
        "autoWidth": false,
        "order": [0, 'asc'],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "admin_habitaciones_dt.php?id_piso=" + id_piso, // json datasource
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

$(function() {
    //binding event click for button in modal form
    $(document).on("click", "#btnDelete", function(event) {
        deleted();
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

function senddata() {
    var process = $('#process').val();
    var numero_piso = $('#numero_piso').val();
    var numero_habitacion = $('#numero_habitacion').val();
    var tipo_habitacion = $('#tipo_habitacion').val();
    var descripcion = $('#descripcion').val();
    var estado_habitacion = $('#estado_habitacion').val();
    var precio_por_hora = $('#precio_por_hora').val();
    var urlprocess = "";
    var id_habitacion = 0;
    if (process == 'insert') {
        urlprocess = 'agregar_habitacion.php';
    }
    if (process == 'edited') {
        urlprocess = 'editar_habitacion.php';
        id_habitacion = $("#id_habitacion").val();
    }
    var dataString = 'process=' + process + '&id_habitacion=' + id_habitacion + '&numero_piso=' + numero_piso;
    dataString += '&numero_habitacion=' + numero_habitacion + '&tipo_habitacion=' + tipo_habitacion;
    dataString += '&descripcion=' + descripcion + '&estado_habitacion=' + estado_habitacion + '&precio_por_hora=' + precio_por_hora;
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
    location.href = 'admin_habitaciones.php';
}

function deleted() {
    var id_habitacion = $('#id_habitacion').val();
    var dataString = 'process=deleted' + '&id_habitacion=' + id_habitacion;
    $.ajax({
        type: "POST",
        url: "borrar_habitacion.php",
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