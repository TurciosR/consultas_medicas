$(document).ready(function() {
    //Cargar datos del datatable
    generar2();
    //Cargar datos del datatable

    //jquery validate y regex

    $.validator.addMethod('regexp', function(value, element, param) {
        return this.optional(element) || value.match(param);
    }, 'Mensaje a mostrar si se incumple la condición');

    $('#formulario_piso').validate({
        rules: {
            numero: {
                required: true,
                regexp: /^[0-9\-]+$/
            },
            descripcion: {
                required: true,
            },
        },
        messages: {
            numero: {
                required: "Por favor ingrese el numero del piso.",
                regexp: "El numero de piso solo debe de contener numeros."
            },
            descripcion: {
                required: "Por favor ingrese la descripcion del piso",
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

    //Jquery validate
    //solo numeros
    $('#numero').numeric({
        negative: false,
        decimalPlaces: 4
    });
    //solo numeros
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
            url: "admin_pisos_dt.php", // json datasource
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


function senddata() {
    var process = $('#process').val();
    var numero = $("#numero").val();
    var descripcion = $("#descripcion").val();
    var urlprocess = "";
    var id_piso = 0;
    if (process == 'insert') {
        urlprocess = 'agregar_piso.php';
    }
    if (process == 'edited') {
        urlprocess = 'editar_piso.php';
        id_piso = $("#id_piso").val();
    }
    var dataString = 'process=' + process + '&id_piso=' + id_piso + '&numero=' + numero + '&descripcion=' + descripcion;
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
    location.href = 'admin_pisos.php';
}

function deleted() {
    var id_piso = $('#id_piso').val();
    var dataString = 'process=deleted' + '&id_piso=' + id_piso;
    $.ajax({
        type: "POST",
        url: "borrar_piso.php",
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