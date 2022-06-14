var tiempo = 0; +
$(document).ready(function() {
    var date = $("#fechaoo").val();
    var str = [];
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: date,
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        events: "citas.php",
        timeFormat: 'h(:mm)t',
        select: function(start, end) {
            $('#ModalAdd #paciente').text("");
            $('#ModalAdd #id_paciente').val("");
            $('#ModalAdd #hora').val("");
            $('#ModalAdd #motivo').val("");
            $('#ModalAdd #color').val("");
            if ($("#ModalAdd #doctores").val() > 1) {
                $('#ModalAdd #doctor').val("");
                $("#ModalAdd #select2-doctor-container").text("Seleccione");
            }
            if ($("#ModalAdd #espacios").val() > 1) {
                $('#ModalAdd #espacio').val("");
                $("#ModalAdd #select2-espacio-container").text("Seleccione");
            }
            $('#ModalAdd #fecha').val(moment(start).format('DD-MM-YYYY'));
            $('#ModalAdd').modal('show');
        },
        eventRender: function(event, element) {
            element.bind('dblclick', function() {
                $('#ModalEdit #id_cita').val(event.id);
                $('#ModalEdit #pacientee').text(event.title);
                $('#ModalEdit #fechae').val(event.start.format('DD-MM-YYYY'));
                $('#ModalEdit #horae').val(event.start.format('hh:mm A'));
                $('#ModalEdit').modal('show');
            });
        },

    });
    $("#add_fast").click(function() {
        $('#ModalAdd #paciente').text("");
        $('#ModalAdd #id_paciente').val("");
        $('#ModalAdd #hora').val("");
        $('#ModalAdd #motivo').val("");
        $('#ModalAdd #color').val("");
        if ($("#ModalAdd #doctores").val() > 1) {
            $('#ModalAdd #doctor').val("");
            $("#ModalAdd #select2-doctor-container").text("Seleccione");
        }
        if ($("#ModalAdd #espacios").val() > 1) {
            $('#ModalAdd #espacio').val("");
            $("#ModalAdd #select2-espacio-container").text("Seleccione");
        }
        $('#ModalAdd #fecha').val("");
        $('#ModalAdd').modal('show');
    });
    $(".select").select2();
    $("#reloadd").click(function() {
        rechargin();
    });
    $("#timer").change(function() {
        reload_calendar($(this).val());
    });

    tiempo = $("#timer").val();
    if (tiempo > 0) {
        var refresh = tiempo * 60000;
        setInterval("rechargin();", refresh);
    }
    $("#nombre").typeahead({
        //Definimos la ruta y los parametros de la busqueda para el autocomplete
        source: function(query, process) {
            $.ajax({
                url: 'autocomplete_paciente.php',
                type: 'GET',
                data: 'query=' + query,
                dataType: 'JSON',
                async: true,
                //Una vez devueltos los resultados de la busqueda, se pasan los valores al campo del formulario
                //para ser mostrados 
                success: function(data) {
                    process(data);
                }
            });
        },
        //Se captura el evento del campo de busqueda y se llama a la funcion agregar_factura()
        updater: function(selection) {
            var data0 = selection;
            var id = data0.split("|");
            var nombre = id[1];
            id = parseInt(id[0]);
            $("#paciente").text("PACIENTE: " + nombre);
            $("#id_paciente").val(id);

        }
    });
});
$(function() {
    //binding event click for button in modal form
    $(document).on("click", "#btn_add", function(event) {
        if ($("#id_paciente").val() != "") {
            if ($("#doctor").val() != "") {
                if ($("#fecha").val() != "") {
                    if ($("#hora").val() != "") {
                        if ($("#espacio").val() != "") {
                            if ($("#motivo").val() != "") {
                                agregar_cita(1);
                            } else {
                                display_notify("Warning", "Por favor ingrese el motivo de la cita");
                            }
                        } else {
                            display_notify("Warning", "Por favor seleccione un consultorio");
                        }
                    } else {
                        display_notify("Warning", "Por favor seleccione una hora");
                    }
                } else {
                    display_notify("Warning", "Por favor seleccione una fecha");
                }
            } else {
                display_notify("Warning", "Por favor seleccione un medico");
            }
        } else {
            display_notify("Warning", "Por favor seleccione un paciente");
        }
    });
    $(document).on("click", "#btn_edit", function(event) {
        if ($("#fechae").val() != "") {
            if ($("#horae").val() != "") {
                agregar_cita(0);
            } else {
                display_notify("Warning", "Por favor ingrese la hora");
            }
        } else {
            display_notify("Warning", "Por favor ingrese la fecha");
        }
    });
    // Clean the modal form
    /*$(document).on('hidden.bs.modal', function(e)
    {
    	var target = $(e.target);
    	target.removeData('bs.modal').find(".modal-content").html('');
    });*/

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

function agregar_cita(i) {
    if (i) {
        var dataString = $("#add_cita").serialize();
    } else {
        var dataString = $("#edit_cita").serialize();
    }
    $.ajax({
        type: 'POST',
        url: "agenda.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            if (datax.typeinfo != "Success") {
                display_notify(datax.typeinfo, datax.msg);
            }
            if (i) {
                $("#ModalAdd #btn_ca").click();
            } else {
                $("#ModalEdit #btn_ce").click();
            }
            rechargin();
        }
    });

}

function rechargin() {
    $('#calendar').fullCalendar('refetchEvents');
}

function reload_calendar(tt) {
    var dataString = 'process=timer&value=' + tt;
    $.ajax({
        type: 'POST',
        url: "agenda.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            if (datax.typeinfo == "Success") {
                reload1();
            }
        }
    });
}

function reload1() {
    location.href = 'agenda.php';
}