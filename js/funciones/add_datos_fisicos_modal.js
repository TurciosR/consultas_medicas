

$(document).ready(function(){
    $(".select").select2({
        dropdownParent: $(".modal-body")
    });
    $("#datos_fisicos_previos").submit(function(evt){
        evt.preventDefault();
        reservar_y_transferir();
    });
    /*$("#datos_fisicos_previos").validate({
        rules:
        {
            id_medico:{
                required:true,
            },
            txt_estatura:{
                required:true,
            },
            txt_peso:{
                required:true,
            },
            txt_motivo:{
                required:true,
            },
            txt_hx:{
                reuired:true,
            },
            txt_antecedentes:{
                required:true,
            },
            txt_antecedentes_fam:{
                required:true,
            },
            txt_ta:{
                required:true,
            },
            txt_fc:{
                required:true,
            },
            txt_fr:{
                required:true,
            },
            txt_temp:{
                required:true,
            },
            txt_dx:{
                required:true,
            },
            txt_plan:{
                required:true,
            }
        },
        messages:
        {
            id_medico:"Debe seleccionar un medico.",
            txt_estatura:"Debe de ingresar la estatura del paciente.",
            txt_peso:"Debe de ingresar el peso del paciente.",
            txt_motivo:"Debe de ingresar el motivo de consulta del paciente.",
            txt_hx:"Debe de ingresar este campo",
            txt_antecedentes:"Debe de ingresar los antecedentes del paciente",
            txt_antecedentes_fam:"Debe de ingresar los antecedentes familiares del paciente",
            txt_ta:"Debe de ingresar la tencion artifical del paciente",
            txt_fc:"Debe de ingresar la frecuencia cardiaca del paciente",
            txt_fr:"Debe de ingresar la frecuencia respiratoria del paciente",
            txt_temp:"Debe de ingresar la temperatura del paciente",
            txt_dx:"Debe de ingresar el examen dx del paciente",
            txt_plan:"Debe de ingresar este campo"
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },submitHandler: function(form) {
            alert("Hola...");
            //reservar_y_transferir();
        }
    });*/

    function reservar_y_transferir(){
        //creando cita para hoy y transfiriendo el paciente a consulta
        var txt_estatura=$("#txt_estatura").val();
        var txt_peso=$("#txt_peso").val();
        var txt_motivo=$("#txt_peso").val();
        var txt_peso=$("#txt_peso").val();
        var txt_motivo=$("#txt_motivo").val();
        var txt_hx=$("#txt_hx").val();
        var txt_antecedentes=$("#txt_antecedentes").val();
        var txt_antecedentes_fam=$("#txt_antecedentes_fam").val();
        var txt_tx=$("#txt_ta").val();
        var txt_fc=$("#txt_fc").val();
        var txt_fr=$("#txt_fr").val();
        var txt_temp=$("#txt_temp").val();
        var txt_dx=$("#txt_dx").val();
        var txt_plan=$("#txt_plan").val();

        var id_paciente=$("#id_paciente").val();
        var id_usuario=$("#id_usuario").val();
        var id_doctor=$("#id_medico").val();

        alert(id_doctor);

        var process='trans_consulta';
        
        var datos ={
            'process':process,
            'estatura':txt_estatura,
            'peso':txt_peso,
            'motivo':txt_motivo,
            'hx':txt_hx,
            'antecedente':txt_antecedentes,
            'antecedente_fam':txt_antecedentes_fam,
            'tx':txt_tx,
            'fc':txt_fc,
            'fr':txt_fr,
            'temp':txt_temp,
            'dx':txt_dx,
            'plan':txt_plan,
            'id_paciente':id_paciente,
            'id_usuario':id_usuario,
            'id_doctor':id_doctor
        };
        var dire='ingresar_datos_fisicos.php';
        console.log(datos);
        $.ajax({
            type:'POST',
            url:dire,
            data:datos,
            dataType:'json',
            success:function(e){
                
            }
        });

    }
    

});