var tiempo = 0; +
$(document).ready(function() {  

    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true
    });

    $('#doctor_receta').on('change', function() {
        $("#id_doctor_receta").val(this.value);
        let id_paciente = $("#id_paciente_consulta").val();
        let id_cita = $("#id_cita_consulta").val();

        $("#imprimir_receta_a").attr("href", "receta_pdf.php?id_cita=" + id_cita + "&id_paciente=" + id_paciente + "&id_doctor=" + (this.value));
    });
    /* ACA EMPIEZA LA SECUENCIA DE CONTROL DE CLICKS QUE SE UTILIZARA PARA
    EL ARCHIVO DE CONSULTA 1 */

    $("#datos-fisicos-tab").click(function() {
        $("#evaluacion-fisica-tab").click();
    });


    /* ACA TERMINA LA SECUENCIA DE CONTROL DE CLICKS QUE SE UTILIZARA PARA
    EL ARCHIVO DE CONSULTA 1 */

    $(".select").select2();
    $(".otr_guardar").click(function() {
        guardar_otros();
    });
    $(".guardar_constancia").click(function() {
        guardar_constancia();
    });
    $("#finiquit").click(function() {
        finalizar();
    });
    show_data($("#id_cita").val());
    $("#diagno").typeahead({
        //Definimos la ruta y los parametros de la busqueda para el autocomplete
        source: function(query, process) {
            $.ajax({
                url: 'autocomplete_diagnostico.php',
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
            if (!exis(id)) {
                agregar_diagnostico(id, nombre);
            } else {
                display_notify("Error", "Este diagnostico ya se agrego");
            }

        }
    });
    $("#exam").typeahead({
        //Definimos la ruta y los parametros de la busqueda para el autocomplete
        source: function(query, process) {
            $.ajax({
                url: 'autocomplete_examen.php',
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
            //alert(data0);
            var id = data0.split("|");
            var nombre = id[1];
            id = parseInt(id[0]);
            if (!exise(id)) {
                agregar_examen(id, nombre);
            } else {
                display_notify("Error", "Este examen ya se agrego");
            }

        }
    });
});
$(function() {
    //binding event click for button in modal form
    // Clean the modal form
    $(document).on('ifChecked', "#plana", function(event) {
        $('#plan').val("1");
    });
    $(document).on('ifUnchecked', '#plana', function(event) {
        $('#plan').val("0");
    });
    $(document).on("click", ".elim", function() {
        var id = $(this).attr("id");
        var dataString = "process=rm_diagnostico&id=" + id + "&id_cita=" + $("#id_cita").val();
        $("#diagnos_tt tr").each(function() {
            if ($(this).attr("id") == id) {
                $.ajax({
                    type: 'POST',
                    url: "consulta1.php",
                    data: dataString,
                    dataType: 'json',
                    success: function(datax) {
                        //display_notify(datax.typeinfo, datax.msg);
                        if (datax.typeinfo == "Success") {
                            $("#diagnos_tt #" + id + "").remove();
                        }
                    }
                });
            }
        });
    });
    $(document).on("click", ".elimi", function() {
        var id = $(this).attr("id");
        var dataString = "process=rm_examen&id=" + id + "&id_cita=" + $("#id_cita").val();
        $("#exam_tt tr").each(function() {
            if ($(this).attr("id") == id) {
                $.ajax({
                    type: 'POST',
                    url: "consulta1.php",
                    data: dataString,
                    dataType: 'json',
                    success: function(datax) {
                        //display_notify(datax.typeinfo, datax.msg);
                        if (datax.typeinfo == "Success") {
                            $("#exam_tt #" + id + "").remove();
                        }
                    }
                });
            }
        });
    });
    $(document).on("click", ".elimin", function() {
        var id = $(this).attr("id");
        var dataString = "process=rm_receta&id=" + id + "&id_cita=" + $("#id_cita").val();
        $("#receta tr").each(function() {
            if ($(this).attr("id") == id) {
                $.ajax({
                    type: 'POST',
                    url: "consulta1.php",
                    data: dataString,
                    dataType: 'json',
                    success: function(datax) {
                        //display_notify(datax.typeinfo, datax.msg);
                        if (datax.typeinfo == "Success") {
                            $("#receta #" + id + "").remove();
                        }
                    }
                });
            }
        });
    });

    /* ESTA FUNCION SIRVE PARA ELIMINAR CONSTANCIAS QUE SE HAYAN
    REALIZADO EN LA CITA */
    $(document).on("click", ".elimin_constancia", function() {
        var id_constancia = $(this).attr("id");
        var dataString = "process=rm_constancia&id_constancia=" + id_constancia;
        $("#constancias_agregadas tr").each(function() {
            if ($(this).attr("id") == id_constancia) {
                $.ajax({
                    type: 'POST',
                    url: "consulta1.php",
                    data: dataString,
                    dataType: 'json',
                    success: function(datax) {
                        //display_notify(datax.typeinfo, datax.msg);
                        if (datax.typeinfo == "Success") {
                            $("#constancias_agregadas #" + id_constancia + "").remove();
                        }
                    }
                });
            }
        });
    });
    /* ESTA FUNCION SIRVE PARA ELIMINAR CONSTANCIAS QUE SE HAYAN
    REALIZADO EN LA CITA */




    $(document).on('hidden.bs.modal', function(e) {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });

    $(document).on("click", "#btn_guardar", function(event) {
        if ($("#nombre").val() != "") {
            if ($("#apellido").val() != "") {
                if ($("#sexo").val() != "") {
                    if ($("#fecha").val() != "") {
                        if ($("#telefono1").val() != "") {
                            if ($("#direccion").val() != "") {
                                editar_paciente();
                            } else {
                                display_notify("Warning", "Por favor ingrese la direccion");
                            }
                        } else {
                            display_notify("Warning", "Por favor ingrese el numero de telefono");
                        }
                    } else {
                        display_notify("Warning", "Por favor ingrese la fecha de nacimiento");
                    }
                } else {
                    display_notify("Warning", "Por favor seleccion el genero");
                }
            } else {
                display_notify("Warning", "Por favor ingrese el apellido");
            }
        } else {
            display_notify("Warning", "Por favor ingrese el nombre del paciente");
        }
    });
    $(document).on("click", "#btn_agregar_arc", function() {
        if ($("#foto").val() != "") {
            if ($("#descripcion").val() != "") {
                $(this).attr("disabled", true);
                upload();
            } else {
                display_notify("Error", "Por favor ingrese la descripcion de la imagen");
            }
        } else {
            display_notify("Error", "Debe seleccionar un archivo");
        }
    });
    $(document).on("click", "#btn_add_ref", function() {
        if ($("#destino").val() != "") {
            if ($("#motivo").val() != "") {
                if ($("#doctor_refiere").val() != "") {
                    referencia();
                } else {
                    display_notify("Error", "Por favor ingrese el doctor que refiere");
                }

            } else {
                display_notify("Error", "Por favor ingrese el motivo de la referencia");
            }
        } else {
            display_notify("Error", "Por favor ingrese el destino de la referencia");
        }
    });
    $(document).on("click", "#btn_add", function(event) {
        /*if($("#estatura").val()!="")
        {
        	if($("#peso").val()!="")
        	{
        		if($("#temperatura").val()!="")
        		{
        			if($("#presion").val()!="")
        			{*/
        agregar_singos();
        /*}
					else
					{
						display_notify("Warning", "Por favor ingrese la presion");
					}
				}
				else
				{
					display_notify("Warning", "Por favor ingrese la temperatura");
				}
			}
			else
			{
				display_notify("Warning", "Por favor ingrese el peso");
			}
		}
		else
		{
			display_notify("Warning", "Por favor ingrese la estatura");
		}*/
    });
    $(document).on('keydown', '.tel', function(event) {
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
    $(document).on("click", "#btn_add_med", function() {
        if ($("#id_med").val() != "") {
            if ($("#cantidad").val() != "") {
                if ($("#dosis").val() != "") {
                    if (!exisa($("#id_med").val())) {
                        sendd();
                    } else {
                        display_notify("Error", "Este medicamento ya fue agregado");
                    }
                } else {
                    display_notify("Error", "Ingrese la dosis");
                }
            } else {
                display_notify("Error", "Ingrese la cantidad");
            }
        } else {
            display_notify("Error", "Seleccione un medicamento");
        }
    });
    $(document).on("click", ".fileinput-remove-button", function() {
        $("#foto").attr("name", "foto");
    });

    $(document).on("click", "#btn_add_sp", function() {
        agregar_servicio_profesional();
    });

    $(document).on("click", "#btn_add_fc", function() {
        agregar_futura_consulta();
    });
});

function exis(id) {
    var ret = false;
    $("#diagnos_tt tr").each(function() {
        if ($(this).attr("id") == id) {
            ret = true;
        }
    });
    return ret;
}

function exise(id) {
    var ret = false;
    $("#exam_tt tr").each(function() {
        if ($(this).attr("id") == id) {
            ret = true;
        }
    });
    return ret;
}

function exisa(id) {
    var ret = false;
    $("#receta tr").each(function() {
        if ($(this).attr("id") == id) {
            ret = true;
        }
    });
    return ret;
}

function autosave(val) {
    var name = $('#name').val();
    if (name == '' || name.length == 0) {
        var typeinfo = "Info";
        var msg = "The field name is required";
        display_notify(typeinfo, msg);
        $('#name').focus();
    } else {

    }
}

function agregar_diagnostico(id, nombre) {
    var dataString = "process=diagnostico&id=" + id + "&id_cita=" + $("#id_cita").val();
    //alert(dataString);
    $.ajax({
        type: 'POST',
        url: "consulta1.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            //display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                var fila = "<tr id='" + id + "'><td>" + nombre + "</td><td><a class='btn elim' id='" + id + "'><i class='fa fa-trash'></i></a></td></tr>";
                $("#diagnos_tt").append(fila);
            }
        }
    });
}

function agregar_examen(id, nombre) {
    var dataString = "process=examen&id=" + id + "&id_cita=" + $("#id_cita").val();
    $.ajax({
        type: 'POST',
        url: "consulta1.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            //display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                var fila = "<tr id='" + id + "'><td>" + nombre + "</td><td><a class='btn elimi' id='" + id + "'><i class='fa fa-trash'></i></a></td></tr>";
                $("#exam_tt").append(fila);
            }
        }
    });
}

function agregar_singos(id_p = 0, estatura = '', peso = '', temperatura = '', presion = '', frecuencia_r = '', frecuencia_c = '', observaciones = '') {
    if (id_p == 0) {
        var dataString = $("#add_signo").serialize();
    } else {
        var dataString = 'process=insert&id_paciente=' + id_p + "&estatura=" + estatura + "&peso=" + peso + "&temperatura=" + temperatura + "&presion=" + presion;
        dataString += '&frecuencia_r=' + frecuencia_r + "&frecuencia_c=" + frecuencia_c + "&observaciones=" + observaciones;
    }
    $.ajax({
        type: 'POST',
        url: "signos.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            if (id_p == 0) {
                display_notify(datax.typeinfo, datax.msg);
            }
            if (datax.typeinfo == "Success") {
                $("#editModal #btn_ca").click();
                //setInterval("reload1("+datax.id+");", 1500);
                show_data(datax.id);
            }
        }
    });
}

function referencia() {
    var dataString = $("#add_ref").serialize();
    $.ajax({
        type: 'POST',
        url: "referencia.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                $("#viewModal #btn_ca").click();
                //setInterval("reload1("+datax.id+");", 1500);
                //show_data(datax.id);
            }
        }
    });
}

function sendd(id = 0, descrip = '', dosi = '', pla = '', cantida = 0) {
    if (id == 0) {
        var id_medicamento = $("#id_med").val();
        var descript = $("#descript").val().split(",");
        var dosis = $("#dosis").val();
        var plan = $("#plan").val();
        //var cantidad = $("#cantidad").val();
        var cantidad = 1;
    } else {
        var id_medicamento = id;
        var descript = descrip.split(",");
        var dosis = dosi;
        var plan = pla;
        var cantidad = cantida;
    }

    var dataString = "process=receta&id=" + id_medicamento + "&cantidad=" + cantidad + "&dosis=" + dosis + "&plan=" + plan + "&id_cita=" + $("#id_cita").val();
    $.ajax({
        type: 'POST',
        url: "consulta1.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            if (datax.typeinfo == "Success") {
                $("#viewModal #btn_cerrar_medicamento").click();
                var fila = "<tr id='" + id_medicamento + "'><td>" + descript[0] + "</td><td><a class='btn elimin' id='" + id_medicamento + "'><i class='fa fa-trash'></i></a></td><td><a href='ver_receta.php?id=" + id_medicamento + "&idc=" + $("#id_cita").val() + "' class='btn' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye'></i></a></td></tr>";
                $("#receta").append(fila);
            }
        }
    });
}

function guardar_otros() {
    var diagnostico = $("#otr_diagnostico").val();
    var examen = $("#otr_examen").val();
    var medicamento = $("#otr_medicamento").val();
    var motivo = $("#otr_motivo").val();
    var t_o = $("#to").val();
    var ta = $("#ta").val();
    var p = $("#p").val();
    var peso = $("#peso").val();
    var altura=$("#altura").val();
    var fr = $("#fr").val();
    var id = $("#id_cita").val();
    var spo2 = $("#spo2").val();
    var hemoglucotest = $("#hemoglucotest").val();
    var hallazgo_fisico = $("#hallazgo_fisico").val();
    var historia_clinica = $("#historia_clinica").val();
    var antecedente_personal = $("#antecedente_personal").val();
    var antecedente_familiar = $("#antecedente_familiar").val();
    var indicacion_medica = $("#indicacion_medica").val();
    var ingreso_hospitalario = $("#ingreso_hospitalario").val();
    var otros_cobros = $("#otros_cobros").val();
    var saturacion = $("#saturacion").val();

    var dx_ultra=$("#dx_ultra").val();
    var frecuencia_cardiaca=$("#fc").val();
    var dx=$("#dx").val();
    var plan=$("#plan").val();

    var hx1=$("#hx1").val();

    var data={
        'process':'otr',
        'id':id,
        'diagnostico':diagnostico,
        'examen':examen,
        'medicamento':medicamento,
        'motivo':motivo,
        't_o':t_o,
        'ta':ta,
        'p':p,
        'peso':peso,
        'altura':altura,
        'fr':fr,
        'spo2':spo2,
        'hemoglucotest':hemoglucotest,
        'hallazgo_fisico':hallazgo_fisico,
        'historia_clinica':historia_clinica,
        'antecedente_personal':antecedente_personal,
        'antecedente_familiar':antecedente_familiar,
        'ingreso_hospitalario':ingreso_hospitalario,
        'indicacion_medica':indicacion_medica,
        'otros_cobros':otros_cobros,
        'saturacion':saturacion,
        'fc':frecuencia_cardiaca,
        'dx':dx,
        'plan':plan,
        'hx1':hx1,
        'dx_ultra':dx_ultra
    };
    console.log(data);
    $.ajax({
        type: 'POST',
        url: "consulta1.php",
        data: data,
        dataType: 'json',
        success: function(datax) {

            display_notify(datax.typeinfo, datax.msg);
            /*if(datax.typeinfo == "Success")
            {
            	$("#editModal #btn_ca").click();
            	//setInterval("reload1("+datax.id+");", 1500);
            	show_data(datax.id);
            }*/
        }
    });
}
/* ACA EMPIEZA LA FUNCION LA CUAL VA A SERVIR PARA
AGREGAR CONSTANCIAS DESDE LA CONSULTA */

function guardar_constancia() {
    let id_paciente = $("#id_paciente_consulta").val();
    let id_doctor = $("#id_doctor_constancia").val();
    let fecha_expedicion = $("#fecha_expedicion").val();
    let padecimiento = $("#padecimiento").val();
    let reposo = $("#reposo").val();
    let id_cita = $("#id_cita").val();

    var dataString = "process=agregar_constancia";
    dataString += "&id_paciente=" + id_paciente;
    dataString += "&id_doctor=" + id_doctor;
    dataString += "&fecha_expedicion=" + fecha_expedicion;
    dataString += "&padecimiento=" + padecimiento;
    dataString += "&reposo=" + reposo;
    dataString += "&id_cita=" + id_cita;

    $.ajax({
        type: 'POST',
        url: "consulta1.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                var fila = "<tr id='" + datax.id_constancia + "'>";
                fila += "<td>" + padecimiento + "</td>";
                fila += "<td>";
                fila += "<a class='btn elimin_constancia' id='" + datax.id_constancia + "'><i class='fa fa-trash'></i></a>";
                fila += "<a href='ver_constancia_consulta.php?id_constancia=" + datax.id_constancia + "' class='btn' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye'></i></a>";
                fila += "<a href='ver_constancia1.php?id_constancia=" + datax.id_constancia + "' target = '_blank'><i class='fa fa-print'></i></a >";
                $("#constancias_agregadas").append(fila);
                $("#padecimiento").val("");
                $("#reposo").val("");
                $("#ver-constancias").click();
            }
        }
    });
}

/* ACA TERMINA LA FUNCION LA CUAL VA A SERVIR PARA
AGREGAR CONSTANCIAS DESDE LA CONSULTA */

function finalizar() {
    var diagnostico = $("#otr_diagnostico").val();
    var examen = $("#otr_examen").val();
    var medicamento = $("#otr_medicamento").val();
    var motivo = $("#otr_motivo").val();
    var t_o = $("#to").val();
    var ta = $("#ta").val();
    var p = $("#p").val();
    var peso = $("#peso").val();
    var fr = $("#fr").val();
    var id = $("#id_cita").val();
    var dataString = "process=finalizar&id=" + id + "&diagnostico=" + diagnostico + "&examen=" + examen + "&medicamento=" + medicamento;
    dataString += "&motivo=" + motivo + "&t_o=" + t_o + "&ta=" + ta + "&p=" + p + "&peso=" + peso + "&fr=" + fr;
    $.ajax({
        type: 'POST',
        url: "consulta1.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
              setInterval(function() {
      					location.href = "consulta.php";
      				}, 1500);
            }
        }
    });
}

function editar_paciente() {

    var dataString = $("#formulario_paciente").serialize();
    $.ajax({
        type: 'POST',
        url: "editar_paciente1.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            display_notify(datax.typeinfo, datax.msg);
            if (datax.typeinfo == "Success") {
                //setInterval("reload1("+datax.id+");",1500);
                $("#editModal #btn_ce").click();
                show_data(datax.id);
            }
        }
    });

}

function upload() {
    var form = $("#form");
    var formdata = false;
    if (window.FormData) {
        formdata = new FormData(form[0]);
    }
    var formAction = form.attr('action');
    $.ajax({
        type: 'POST',
        url: 'foto_paciente.php',
        cache: false,
        data: formdata ? formdata : form.serialize(),
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(datax) {
            if (datax.typeinfo == 'Success') {
                var fila = "<tr id='fl" + datax.id_img + "'>";
                fila += "<td>" + datax.fecha + "</td>";
                fila += "<td><a target='_blank' href=" + datax.url + ">" + datax.nombre + "</a></td>";
                fila += "<td><button id='" + datax.id_img + "' class='btn eliminar'><i class='fa fa-trash'></i></button></td>";
                fila += "</tr>";
                $("#table").append(fila);
                $("#foto").val("");
                $("#descripcion").val("");
                $(".fileinput-remove-button").click();
                $("#foto").attr("name", "foto");
                $("#btn_agregar_arc").attr("disabled", false);
            } else {
                display_notify(datax.typeinfo, datax.msg); //datax.msg);
            }
        }
    });
}

function reload1(id) {
    if ($("#acc").val() == "new") {
        location.href = "consulta.php";
    } else {
        location.href = "admin_consulta.php";
    }
}
$(function() {
    $(document).on('click', '.change', function(e) {
        var $this = $(this).children("i");
        if ($(this).attr("act") == "down") {
            $this.removeClass("fa-angle-double-down");
            $this.addClass("fa-angle-double-up");
            $(this).attr("act", "up");
        } else {
            $this.removeClass("fa-angle-double-up");
            $this.addClass("fa-angle-double-down");
            $(this).attr("act", "down");
        }
    })
});

function show_data(id) {
    $.ajax({
        type: 'POST',
        url: 'consulta1.php',
        data: "process=buscar&id=" + id,
        dataType: 'json',
        success: function(datax) {
            if (datax.typeinfo == "Success") {
                //alert(datax.table);
                $("#dato").html(datax.table);
                
                $("#signo").html(datax.signo);
            } else {}
        },
    });
}

function clear_class() {
    $(".list-group li").each(function() {
        $(this).removeClass("active");
    });
}

function uniexis(id) {
    $(".list-group li").each(function() {
        if ($(this).attr("id") != id) {
            $(this).removeClass("active");
        }
    });
}
$(document).on("click", ".eliminar", function(event) {
    var id_btn = $(this).attr("id");
    $("#table tr").each(function() {
        var id_btn_2 = $(this).find(".eliminar").attr("id");
        if (id_btn == id_btn_2) {
            var ajaxdata = {
                "process": "deleted",
                "id_img": id_btn
            }
            $.ajax({
                type: 'POST',
                url: "foto_paciente.php",
                data: ajaxdata,
                dataType: 'json',
                success: function(datax) {
                    if (datax.typeinfo == "Success") {
                        $("#fl" + id_btn + "").remove();
                    } else if (datax.typeinfo == "Error") {}
                }
            })
        }
    })
});

/*ESTA FUNCION SIRVE PARA AGREGAR SERVICIOS PROFESIONALES, CAPTURANDO LOS VALORES
DE DESCRIPCION Y PRECIO DESDE LOS INPUT QUE ESTAN EN EL #viewModal LOS CUALES SON LLENADOS
POR EL ARCHIVO 'servicios_profesionales.php' */
function agregar_servicio_profesional() {
    let descripcion = $("#descripcion_sp").val();
    let precio = $("#precio_sp").val();
    var dataString = "process=add_servicio_profesional&descripcion=" + descripcion + "&precio=" + precio + "&id_cita=" + $("#id_cita").val();
    $.ajax({
        type: 'POST',
        url: "servicio_profesional.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            let id_servicio_profesional = datax.id_servicio_profesional;
            let precio = datax.precio;
            if (datax.typeinfo == "Success") {
                $("#viewModal #btn_cerrar_sp").click();
                var fila = "<tr id='" + id_servicio_profesional + "'><td>" + descripcion + ", " + precio + "</td><td><a class='btn elimin_sp' id='" + id_servicio_profesional + "'><i class='fa fa-trash'></i></a></td><td><a href='ver_servicio_profesional.php?id=" + id_servicio_profesional + "&idc=" + $("#id_cita").val() + "' class='btn' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye'></i></a></td></tr>";
                $("#servicios_profesionales").append(fila);
            } else {

            }
        }
    });
}
/* ACA FINALIZA LA FUNCION PARA AGREGAR ASERVICIOS PROFESIONALES A LA CONSULTA */

/* ESTA FUNCION SIRVE PARA ELIMINAR LOS REGISTROS DE SERVICIOS PROFESIONALES QUE AGREGEN
LOS DOCTORES EN LAS CONSULTAS */
$(document).on("click", ".elimin_sp", function() {
    var id = $(this).attr("id");
    var dataString = "process=rm_servicio_profesional&id=" + id + "&id_cita=" + $("#id_cita").val();
    $("#servicios_profesionales tr").each(function() {
        if ($(this).attr("id") == id) {
            $.ajax({
                type: 'POST',
                url: "consulta1.php",
                data: dataString,
                dataType: 'json',
                success: function(datax) {
                    //display_notify(datax.typeinfo, datax.msg);
                    if (datax.typeinfo == "Success") {
                        $("#servicios_profesionales #" + id + "").remove();
                    }
                }
            });
        }
    });
});
/* ACA FINALIZA LA FUNCION PARA ELIMINAR LOS REGISTROS DE SERVICIOS PROFESIONALES
QUE AGREGUEN LOS DOCTORES EN LAS CONSULTAS */


/* ESTA FUNCION SIRVE PARA AGREGAR FUTURAS CONSULTAS AL PACIENTE AL CUAL
SE LE ENCUENTRA HACIENDO LA CONSULTA ACTUALMENTE */

function agregar_futura_consulta() {
    let fecha = $("#fecha_nueva_consulta").val();
    let hora = $("#hora_nueva_consulta").val();
    let consultorio = $("#espacio_nueva_consulta").val();
    let motivo = $("#motivo_consulta").val();

    var dataString = "process=add_futura_consulta&fecha=" + fecha + "&hora=" + hora + "&id_cita=" + $("#id_cita").val();
    dataString += "&consultorio=" + consultorio + "&motivo=" + motivo;
    $.ajax({
        type: 'POST',
        url: "futura_consulta.php",
        data: dataString,
        dataType: 'json',
        success: function(datax) {
            let id_reserva = datax.id_reserva;
            let descripcion_espacio = datax.descripcion_espacio;
            if (datax.typeinfo == "Success") {
                $("#viewModal #btn_cerrar_fc").click();

                let fila = "<tr id='" + id_reserva + "'>";
                fila += "<td style='width: 40%;'> " + motivo + "</td>";
                fila += "<td style='width: 25%;'> " + fecha + ", " + hora + "</td>";
                fila += "<td style='width: 25%;'> " + fecha + "</td>";
                fila += "<td style='width: 5%;'>";
                fila += "<a class='btn eliminar_consulta' id='" + descripcion_espacio + "'><i class='fa fa-trash'></i></a>";
                fila += "</td>";
                fila += "<td style='width: 5%;'>";
                fila += "<a href='ver_futura_consulta.php?id=" + id_reserva + "&idc=" + $("#id_cita").val() + "' class='btn' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye'></i></a>";
                fila += "</td>";
                fila += "</tr>";
                $("#futuras_consultas").append(fila);
            } else {

            }
        }
    });
}

/* ACA FINALIZA LA FUNCION QUE SIRVE PARA AGREGAR FUTURAS CONSULTAS AL CUAL
SE LE ENCUENTRA HACIENDO LA CONSULTA ACTUALMENTE */


/* ESTA FUNCION SIRVE PARA ELIMINAR LOS REGISTROS DE FUTURAS CONSULTAS QUE AGREGEN
LOS DOCTORES A LOS PACIENTES EN LA CONSULTA */
$(document).on("click", ".eliminar_consulta", function() {
    var id = $(this).attr("id");
    var dataString = "process=rm_futura_consulta&id=" + id + "&id_cita=" + $("#id_cita").val();
    $("#futuras_consultas tr").each(function() {
        if ($(this).attr("id") == id) {
            $.ajax({
                type: 'POST',
                url: "consulta1.php",
                data: dataString,
                dataType: 'json',
                success: function(datax) {
                    //display_notify(datax.typeinfo, datax.msg);
                    if (datax.typeinfo == "Success") {
                        $("#futuras_consultas #" + id + "").remove();
                    }
                }
            });
        }
    });
});
/* ACA FINALIZA LA FUNCION PARA ELIMINAR LOS REGISTROS DE FUTURAS CONSULTAS
QUE AGREGUEN LOS DOCTORES A LOS PACIENTES EN LA CONSULTA*/
