$(document).ready(function(){

    $(".select").select2();
    $(".timepicker").timepicker();



    $("#formulario_datos_fisicos").submit(function(evt){
        evt.preventDefault();
        var hora = $("#hora_cita").val();
        if(hora.length>0){
            reservar_y_transferir();
        }else{
          swal("No ingreso la hora","Debe de seleccionar una hora, antes de transferirlo a consulta", 'error');  
        }
        
        
    });

    /*$("#formulario_datos_fisicos").validate({
        ignore: ':hidden:not("#resultId")',
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
            
        }
    });*/

    function reservar_y_transferir(){
        //creando cita para hoy y transfiriendo el paciente a consulta
        var lugar = $("#lugar").val();
        var txt_estatura=$("#txt_estatura").val();
        var txt_peso=$("#txt_peso").val();
        var txt_motivo=$("#txt_motivo").val();
        var txt_hx=$("#txt_hx").val();
        var txt_antecedentes=$("#txt_antecedentes").val();
        var txt_antecedentes_fam=$("#txt_antecedentes_fam").val();
        var txt_ta=$("#txt_ta").val();
        var txt_fc=$("#txt_fc").val();
        var txt_fr=$("#txt_fr").val();
        var txt_temp=$("#txt_temp").val();
        var txt_dx=$("#txt_dx").val();
        var txt_plan=$("#txt_plan").val();
        var txt_hora_cita=$("#hora_cita").val();

        var id_paciente=$("#id_paciente").val();
        var id_usuario=$("#id_usuario").val();
        var id_doctor=$("#id_medico").val();
        var id_consultorio=$("#id_consultorio").val();
        

        //alert(id_doctor);

        var process='trans_consulta';
        //alert(txt_dx);
        var datos=null;
        if(lugar=="recepcion"){
            var id_recepcion=$("#id_recepcion").val();
            datos ={
                'lugar':lugar,
                'process':process,
                'estatura':txt_estatura,
                'peso':txt_peso,
                'motivo':txt_motivo,
                'hx':txt_hx,
                'antecedente':txt_antecedentes,
                'antecedente_fam':txt_antecedentes_fam,
                'ta':txt_ta,
                'fc':txt_fc,
                'fr':txt_fr,
                'temp':txt_temp,
                'dx':txt_dx,
                'plan':txt_plan,
                'id_paciente':id_paciente,
                'id_usuario':id_usuario,
                'id_doctor':id_doctor,
                'id_consultorio':id_consultorio,
                'id_recepcion':id_recepcion,
                'hora_cita':txt_hora_cita
            };
        }else if(lugar=="cita"){
            var id_cita=$("#id_cita").val();
            datos ={
                'lugar':lugar,
                'process':process,
                'estatura':txt_estatura,
                'peso':txt_peso,
                'motivo':txt_motivo,
                'hx':txt_hx,
                'antecedente':txt_antecedentes,
                'antecedente_fam':txt_antecedentes_fam,
                'ta':txt_ta,
                'fc':txt_fc,
                'fr':txt_fr,
                'temp':txt_temp,
                'dx':txt_dx,
                'plan':txt_plan,
                'id_paciente':id_paciente,
                'id_usuario':id_usuario,
                'id_doctor':id_doctor,
                'id_consultorio':id_consultorio,
                'hora_cita':txt_hora_cita,
                'id_cita':id_cita

            }; 

        }

        var dire='registrar_datos_fisicos.php';
        //alert(lugar);
        $.ajax({
            type:'POST',
            url:dire,
            data:datos,
            dataType:'json',
            success:function(e){
                var typeinfo=e.typeinfo;
                var msg=e.msg;
                var lugar=e.lugar;
                //alert(lugar);
                if(typeinfo=='Success'){
                    //alert(e.typeinforeci);
                    display_notify(typeinfo,msg);
                    if(lugar=="recepcion"){
                        location.href='admin_recepcion.php'; 
                    }else if(lugar=='cola.php'){
                        location.href='cola.php';
                        
                    }
                }
            }
        });

    }

});