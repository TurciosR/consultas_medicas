var editor = "";
$(document).ready(function() {
    $(".decimal").numeric({ negative: false });
    $('.numeric').numeric({ negative: false, decimals: false });
    $(".select").select2();
    editor = CKEDITOR.replace('texto');
    //Autocomplete typeahead
    $("#buscar_paciente").typeahead({
        //  contentType: "application/json; charset=utf-8",
        source: function(query, process) {
            $.ajax({
                url: 'autocomplete_paciente.php',
                type: 'GET',
                data: 'query=' + query,
                dataType: 'JSON',
                async: true,
                success: function(data) {
                    process(data);
                }
            });
        },

        updater: function(selection) {
            var data0 = selection;
            var data = data0.split("|");
            var id_data = data[0];
            var descrip1 = data[1];
            $("#id_paciente").val(id_data);
            $("#paciente").text("PACIENTE SELECCIONADO: " + descrip1);

            //agregar_inmueble(id_data);
        }
    });
    //fin Autocomplete typeahead  
    //fin Autocomplete typeahead   
    $("#forma").change(function() {
        if ($(this).val() == "constancia") {
            $("#div_pa").show();
            $(".cons").show();
            $(".defu").hide();
            $(".otr").hide();
        } else if (($(this).val() == "defuncion")) {
            $("#div_pa").show();
            $(".defu").show();
            $(".cons").hide();
            $(".otr").hide();
        } else if (($(this).val() == "otro")) {
            $("#div_pa").show();
            $(".defu").hide();
            $(".cons").hide();
            $(".otr").show();
        } else {
            $("#div_pa").hide();
            $(".defu").hide();
            $(".cons").hide();
            $(".otr").hide();
        }
    });
    $("#btn_fin").click(function() {

        if ($("#fecha").val() != "") {
            if ($("#forma").val() != "") {
                if ($("#forma").val() == "constancia") {
                    if ($("#id_paciente").val() != "") {
                        if ($("#padecimiento").val() != "") {
                            if ($("#reposo").val() != "") {
                                if ($("#id_doctor").val() != "") {
                                    senddata();
                                } else {
                                    display_notify("Error", "Por favor ingrese el doctor que emite la constancia!");
                                }
                            } else {
                                display_notify("Error", "Por favor ingrese el numero de dias de reposo");
                            }
                        } else {
                            display_notify("Error", " Por favor ingrese el padecimiento");
                        }
                    } else {
                        display_notify("Error", "Por favor seleccione un paciente");
                    }
                } else if ($("#forma").val() == "defuncion") {
                    if ($("#id_paciente").val() != "") {
                        if ($("#fecha").val() != "") {
                            if ($("#hora").val() != "") {
                                if ($("#lugar").val() != "") {
                                    if ($("#causa").val() != "") {
                                        senddata();
                                    } else {
                                        display_notify("Error", "Por favor ingrese la causa de defuncion");
                                    }
                                } else {
                                    display_notify("Error", "Por favor ingrese el lugar de la defuncion");
                                }
                            } else {
                                display_notify("Error", "Por favor ingrese la hora de defuncion");
                            }
                        } else {
                            display_notify("Error", "Por favor ingrese la fecha de defuncion");
                        }
                    } else {
                        display_notify("Error", "Por favor seleccione un paciente");
                    }
                } else {
                    if ($("#id_paciente").val() != "") {
                        senddata();
                    } else {
                        display_notify("Error", "Por favor seleccione un paciente");
                    }
                }

            } else {
                display_notify("Error", "Por favor seleccione el tipo de constancia");
            }
        } else {
            display_notify("Error", "Por favor seleccione la fecha");
        }
    });
}); //end document ready



//evitar el send del form al darle enter solo con click en el boton
$(document).on("keypress", 'form', function(e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        e.preventDefault();
        return false;
    }
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

function reload1() {
    location.href = 'admin_constancia.php';
}

function deleted() {
    var id_constancia = $('#id_constancia').val();
    var dataString = 'process=deleted' + '&id_constancia=' + id_constancia;
    $.ajax({
        type: "POST",
        url: "borrar_constancia.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                setInterval("location.reload();", 1000);
                $('#deleteModal').hide();
            }
        }
    });
}

function senddata() {

    var forma = $("#forma").val();
    var fecha = $("#fecha").val();
    var id_paciente = $('#id_paciente').val();
    var padecimiento = $("#padecimiento").val();
    var tratamiento = $("#tratamiento").val();
    var reposo = $("#reposo").val();
    var fecha_d = $("#fecha_d").val();
    var hora = $('#hora').val();
    var lugar = $('#lugar').val();
    var causa = $('#causa').val();
    var texto = editor.getData();
    var id_doctor = $("#id_doctor").val();

    var process = $('#process').val();
    var dataString = 'process=' + process + '&id_paciente=' + id_paciente + '&fecha=' + fecha + '&padecimiento=' + padecimiento + "&tratamiento=" + tratamiento;
    dataString += "&id_doctor=" + id_doctor + "&reposo=" + reposo + "&fecha_d=" + fecha_d + "&hora=" + hora + "&lugar=" + lugar + "&causa=" + causa + "&tipo=" + forma + "&texto=" + texto;
    $.ajax({
        type: "POST",
        url: "constancias.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            if (datax.typeinfo == "Success") {
                var id_constancia = datax.id_constancia;
                swal({
                        title: "Constancia Generada con éxito",
                        text: "¿Desea Imprimirla en este momento?",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#69F0AE",
                        confirmButtonText: "Imprimir Ahora",
                        cancelButtonText: "Imprimir mas tarde",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            location.href = "admin_constancia.php";
                            imprimir_constancia(id_constancia);
                        } else {
                            location.href = "admin_constancia.php";
                        }

                    });
            } else {
                display_notify(datax.typeinfo, datax.msg);
            }
        }
    });
}

function imprimir_constancia(id) {
    let url = 'ver_constancia1.php?id_constancia=' + id;
    window.open(url, '', '');
}
$(document).on("click", "#btn_guardar", function() {
    var nombre = $('#nombre').val();
    var apellido = $('#apellido').val();
    var direccion = $('#direccion').val();
    var telefono1 = $('#telefono1').val();
    var sexo = $('#sexo').val();
    var fecha = $('#fecha_n').val();
    var data = "process=insert&nombre=" + nombre + "&apellido=" + apellido + "&direccion=" + direccion + "&telefono1=" + telefono1;
    data += "&sexo=" + sexo + "&fecha=" + fecha;
    if (nombre != "" && apellido != "" && direccion != "" && telefono1 != "" && sexo != "" && fecha != "") {
        $.ajax({
            type: 'POST',
            url: 'agregar_paciente1.php',
            data: data,
            dataType: 'JSON',
            success: function(datax) {
                display_notify(datax.typeinfo, datax.msg);
                if (datax.typeinfo == "Success") {
                    $("#id_paciente").val(datax.id);
                    $("#paciente").text("PACIENTE SELECCIONADO: " + datax.nombre);
                    $("#btn_ce").click();
                }
            },
        });
    } else {
        display_notify("Warning", "Complete todos los datos antes de continuar");
    }
});
$(document).on("keypress", "#telefono1", function(event) {
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