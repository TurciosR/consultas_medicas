
function buscar() {
    var ini = $("#desde").val();
    var fin = $("#hasta").val();
    var dataString = "ini=" + ini + "&fin=" + fin;
    $.ajax({
        type: "POST",
        url: urlprocess,
        data: dataString,
        success: function(datax) {
            $("#refill").html(datax);
        }
    });
}
var datageneral = "";
$(document).ready(function() {

    generar2("");
    $("#hora_entrada_replace").hide();
    $(".select").select2();
    $("#buscarRecepcion").click(function() {
        generar2("");
    });
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
        "order": [0, 'desc'],
        "processing": true,
        "autoWidth": false,
        "serverSide": true,
        "ajax": {
            url: "admin_recepcion_dt.php?fechai=" + fechai + "&fechaf=" + fechaf, // json datasource
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
    dataTable.ajax.reload()
        //}

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


function asignarCuarto() {
    var ajaxdata = { "process": "asignarHabitacion", "numeroCuarto": $("#habitacionPiso1").val(), "fechaDeEntrada": $("#fechaEntrada").val(), "horaDeEntrada": $("#hora_entrada").val(), "pisoHospital": $("#pisoHospital").val(), "idRecepcion": $("#idRecepcion").val() };
    $.ajax({
        url: "hospitalizar_paciente.php",
        type: "POST",
        data: ajaxdata,
        success: function(opciones) {
            $("#hospitalizacionModal").modal('hide');
            $('#habitacionPiso').prop('selectedIndex', 0);
            $('#pisoHospital').prop('selectedIndex', 0);
            $('#fechaEntrada').val('');
            $('#hora_entrad').val('');
            generar2(opciones);
        }
    });
}

function reload1(datax) {
    location.href = 'admin_recepcion.php';
}

function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}