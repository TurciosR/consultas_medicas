$(document).ready(function(){
	$("#div_constancia_medica").css({
		"display":"none",
	});
	$("#div_constancia_muerte").css({
		"display":"none",
	});
	$("#div_examenes").css({
		"display":"none",
	});
	$("#div_carta_recomendacion").css({
		"display":"none",
	});

	$("#forma").select2();
	//validando dui
    $('#dui_medica').on('keydown', function(event) {
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
    $('#dui_fallecido').on('keydown', function(event) {
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
    $('#dui_recomendado').on('keydown', function(event) {
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
    $('#dui_examenes').on('keydown', function(event) {
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

    $('#form_medica').validate({
        rules: {
            fecha_medica: {
                required: true,
            },
            nombre_paciente_medica: {
                required: true,
            },
            edad_medica: {
                required: true,
            },
            direccion_paciente_medica: {
                required: true,
            },
            padecimiento_medica: {
                required: true,
            },
            reposo_medica: {
                required: true,
            },
            dui_medica: {
                required: true,
            },
            expediente_medica: {
                required: true,
            },

        },
        messages: {
            fecha_medica: "Debe de ingresar la fecha",
            nombre_paciente_medica: "Debe de ingresar el nombre del paciente",
           	edad_medica: "Debe de ingresar la edad",
            direccion_paciente_medica: "Debe de ingresar la direccion",
            padecimiento_medica: "Debe de ingresar el padecimiento",
            reposo_medica: "Debe de ingresar los dias de reposo",
            dui_medica: "Debe de ingresar el dui del paciente",
            expediente_medica: "Debe de ingresar el numero de expediente medico del paciente",
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function(form) {
            sendMedica();
        }
    });


    $('#form_muerte').validate({
        rules: {
        	nombre_muerto:{
        		required:true,
        	},
            fecha_muerte: {
                required: true,
            },
            dui_fallecido: {
                required: true,
            },
            edad_muerto: {
                required: true,
            },
            direccion_paciente_muerte: {
                required: true,
            },
            hora_muerte: {
                required: true,
            },
            causa_muerte: {
                required: true,
            },
            antecedentes_medicos:{
            	required:true,
            }

        },
        messages: {
        	nombre_muerto:"Debe de ingresar el nombre del paciente",
            fecha_muerte: "Debe de ingresar la fecha",
            dui_fallecido: "Debe agregar el dui del fallecido",
           	edad_muerto: "Debe de agregar la edad del muerto",
            direccion_paciente_muerte: "Debe de ingrezar la direccion del fallecido",
            hora_muerte: "Debe de ingresar la hora de fallecimiento",
            causa_muerte:"Debe de ingresar causa de muerte",
            antecedentes_medicos:"Debe de ingresar los antecedentes medicos del paciente"
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function(form) {
            sendMuerte();
        }
    });

    $('#form_examenes').validate({
        rules: {
        	paciente_examenes:{
        		required:true,
        	},
            edad_examenes: {
                required: true,
            },
            fecha_examenes: {
                required: true,
            },
            dui_examenes: {
                required: true,
            },
            direccion_paciente_examenes: {
                required: true,
            },
            expediente_examenes: {
                required: true,
            },
            examen_orina: {
                required: true,
            },
           examen_heses:{
            	required:true,
            },
           examen_rayos_x:{
            	required:true,
            }

        },
        messages: {
        	paciente_examenes:"Debe de ingresar el nombre del paciente",
            edad_examenes: "Debe de ingresar la edad del paciente",
            fecha_examenes: "Debe de Ingresar la fecha de la realizacion del examen",
           	dui_examenes: "Debe de ingresar el dui del examen",
            direccion_paciente_examenes: "Debe de ingrezar la direccion del paciente",
            expediente_examenes: "Debe el numero de expediente del paciente",
            examen_orina:"Debe de ingresar los resultados del examen",
            examen_heses:"Debe de ingresar los resultados del examen",
            examen_rayos_x:"Debe de ingresar los resultados del examen"
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function(form) {
            sendExamenes();
        }
    });

      $('#form_carta').validate({
        rules: {
        	nombre_recomendado:{
        		required:true,
        	},
           edad_recomendado: {
                required: true,
            },
            dui_recomendado: {
                required: true,
            },
            direccion_recomendado: {
                required: true,
            }

        },
        messages: {
        	nombre_recomendado:"Debe de ingresar el nombre",
            edad_recomendado: "Debe de ingresar la edad",
            dui_recomendado: "Debe de Ingresar el dui",
           	direccion_recomendado: "Debe de ingresar la direccion",
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function(form) {
            sendCarta();
        }
    });

	$("#forma").change(function(){
		var tipo = $(this).val();
		switch(tipo){
			case "medica":
				$("#div_constancia_medica").css({
					"display":"block",
				});
				$("#div_constancia_muerte").css({
					"display":"none",
				});
				$("#div_examenes").css({
					"display":"none",
				});
				$("#div_carta_recomendacion").css({
					"display":"none",
				});
			break;

			case "muerte":
				$("#div_constancia_medica").css({
					"display":"none",
				});
				$("#div_constancia_muerte").css({
					"display":"block",
				});
				$("#div_examenes").css({
					"display":"none",
				});
				$("#div_carta_recomendacion").css({
					"display":"none",
				});
			break;

			case "examenes":
				$("#div_constancia_medica").css({
					"display":"none",
				});
				$("#div_constancia_muerte").css({
					"display":"none",
				});
				$("#div_examenes").css({
					"display":"block",
				});
				$("#div_carta_recomendacion").css({
					"display":"none",
				});
			break;
			case "carta":
				$("#div_constancia_medica").css({
					"display":"none",
				});
				$("#div_constancia_muerte").css({
					"display":"none",
				});
				$("#div_examenes").css({
					"display":"none",
				});
				$("#div_carta_recomendacion").css({
					"display":"block",
				});
			break;
		}
	});

	function sendMedica(){
		var fecha_medica=$("#fecha_medica").val();
		var nombre_paciente_medica=$("#nombre_paciente_medica").val();
		var direccion_paciente_medica=$("#direccion_paciente_medica").val();
		var padecimiento_medica=$("#padecimiento_medica").val();
		var reposo_medica=$("#reposo_medica").val();
		var dui_medica=$("#dui_medica").val();
		var edad_medica=$("#edad_medica").val();
		var expediente_medica=$("#expediente_medica").val();
		var vista_php="ver_constancia1.php?&";
		var proces="medica";
		var parametros="process="+proces+"&fecha_medica="+fecha_medica+"&nombre_paciente_medica="+
		nombre_paciente_medica+"&direccion_paciente_medica="+direccion_paciente_medica+
		"&padecimiento_medica="+padecimiento_medica+"&reposo_medica="+reposo_medica+"&dui_medica="+
		dui_medica+"&expediente_medica="+expediente_medica+"&edad_medica="+edad_medica;
		var url=vista_php+parametros

		window.open(url, '_blank');

	};
	function sendMuerte(){
		var fecha_muerte=$("#fecha_muerte").val();
		var nombre_muerto=$("#nombre_muerto").val();
		var direccion_paciente_muerte=$("#direccion_paciente_muerte").val();
		var edad_muerto=$("#edad_muerto").val();
		var antecedentes_medicos=$("#antecedentes_medicos").val();
		var hora_muerte=$("#hora_muerte").val();
		var causa_muerte=$("#causa_muerte").val();
		var dui_fallecido=$("#dui_fallecido").val();
		var proces="muerte";
		var vista_php="ver_constancia1.php?&";
		var parametros="process="+proces+"&fecha_muerte="+fecha_muerte+"&nombre_muerto="+
		nombre_muerto+"&direccion_paciente_muerte="+direccion_paciente_muerte+
		"&edad_muerto="+edad_muerto+"&antecedentes_medicos="+antecedentes_medicos+"&hora_muerte="+
		hora_muerte+"&causa_muerte="+causa_muerte+"&dui_fallecido="+dui_fallecido;

		var url=vista_php+parametros

		window.open(url, '_blank');
	}
	function sendExamenes(){
		var paciente_examenes=$("#paciente_examenes").val();
		var fecha_examenes=$("#fecha_examenes").val();
		var dui_examenes=$("#dui_examenes").val();
		var direccion_paciente_examenes=$("#direccion_paciente_examenes").val();
		var expediente_examenes=$("#expediente_examenes").val();
		var examen_orina=$("#examen_orina").val();
		var examen_heses=$("#examen_heses").val();
		var examen_rayos_x=$("#examen_rayos_x").val();
		var edad_examenes=$("#edad_examenes").val();
		var vista_php="ver_constancia1.php?&";
		var proces="examenes";
		var parametros="process="+proces+"&paciente_examenes="+paciente_examenes+"&fecha_examenes="+
		fecha_examenes+"&dui_examenes="+dui_examenes+"&direccion_paciente_examenes="+direccion_paciente_examenes+
		"&expediente_examenes="+direccion_paciente_examenes+
		"&expediente_examenes="+expediente_examenes+"&examen_orina="+examen_orina+"&examen_heses="+
		examen_heses+"&examen_rayos_x="+examen_rayos_x+"&edad_examenes="+edad_examenes;
		var url=vista_php+parametros

		window.open(url, '_blank');

	}
	function sendCarta(){
		var nombre_recomendado=$("#nombre_recomendado").val();
		var edad_recomendado=$("#edad_recomendado").val();
		var dui_recomendado=$("#dui_recomendado").val();
		var direccion_recomendado=$("#direccion_recomendado").val();
		var vista_php="ver_constancia1.php?&";
		var proces="carta";
		var parametros="process="+proces+"&nombre_recomendado="+nombre_recomendado+"&edad_recomendado="+
		edad_recomendado+"&dui_recomendado="+dui_recomendado+"&direccion_recomendado="+direccion_recomendado;
		var url=vista_php+parametros

		window.open(url, '_blank');

	}

});