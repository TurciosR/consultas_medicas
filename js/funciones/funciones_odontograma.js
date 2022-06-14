$(document).ready(function() 
{
    if($("#odontogramareq").val() =="0")
    {
        createOdontogram();
    }
    $(document).on("click",".click", function(event) {
        var control =0;
        $("#controls .radio").each(function()
        {
           if($(this).children("input[type=radio]").is(':checked'))
            {
               // console.log($(this));
                control = $(this).attr("id");
            }
        });
        if(control=="undefined" || control=="")
        {
            display_notify("Error", "Primero seleccione una opcion");
        }
        var parte = $(this).attr('id');
        var pieza = $(this).parent().attr('id');
        switch (control) {
            case "carie":
                var aplicado = '';
                if ($(this).hasClass("click-orange"))
                {
                    $(this).removeClass('click-orange');
                    aplicado = 'N';
                }
                else 
                {
                    $(this).addClass('click-orange');
                    aplicado = 'S';
                }
                save_status(control, parte, pieza, aplicado);
                break;
            case "resina":
                var aplicado = '';
                if ($(this).hasClass("click-green"))
                {
                    $(this).removeClass('click-green');
                    aplicado = 'N';
                } 
                else
                {
                    $(this).addClass('click-green');
                    aplicado = 'S';
                }
                save_status(control, parte, pieza, aplicado);
                break;
            case "amalgama":
                var aplicado = '';
                if ($(this).hasClass("click-blue"))
                {
                    $(this).removeClass('click-blue');
                    aplicado = 'N';
                }
                else 
                {
                    $(this).addClass('click-blue');
                    aplicado = 'S';
                }
                save_status(control, parte, pieza, aplicado);
                break;
            case "sellante":
                var aplicado = '';
                if ($(this).hasClass("click-lgreen")) 
                {
                    $(this).removeClass('click-lgreen');
                    aplicado = 'N';
                }
                else 
                {
                    $(this).addClass('click-lgreen');
                    aplicado = 'S';
                }
                save_status(control, parte, pieza, aplicado);
                break;   
            case "sindicado":
                var aplicado = '';
                if ($(this).hasClass("click-brown")) 
                {
                    $(this).removeClass('click-brown');
                    aplicado = 'N';
                }
                else 
                {
                    $(this).addClass('click-brown');
                    aplicado = 'S';
                }
                save_status(control, parte, pieza, aplicado);
                break;
            case "necrosis":
                var aplicado = '';
                if ($(this).hasClass("click-purple")) 
                {
                    $(this).removeClass('click-purple');
                    aplicado = 'N';
                }
                else 
                {
                    $(this).addClass('click-purple');
                    aplicado = 'S';
                }
                save_status(control, parte, pieza, aplicado);
                break;  
            case "protesis":
                var aplicado = '';
                $(this).parent().children().each(function(index, el) {
                    if ($(el).hasClass("click-yellow")) 
                    {
                        $(el).removeClass('click-yellow');
                        aplicado = 'N';
                    }
                    else 
                    {
                        $(el).addClass("click-yellow");
                        aplicado = 'S';
                    }
                });
                save_status(control, parte, pieza, aplicado);
                break;
            case "ausente":
                var aplicado = '';
                $(this).parent().children().each(function(index, el) {
                    if ($(el).hasClass("click-cyan")) 
                    {
                        $(el).removeClass('click-cyan');
                        aplicado = 'N';
                    }
                    else 
                    {
                        $(el).addClass("click-cyan");
                        aplicado = 'S';
                    }
                });
                save_status(control, parte, pieza, aplicado);
                break;
            case "endodoncia":
                var aplicado = '';
                $(this).parent().children().each(function(index, el) {
                    if ($(el).hasClass("click-red")) 
                    {
                        $(el).removeClass('click-red');
                        aplicado = 'N';
                    }
                    else 
                    {
                        $(el).addClass("click-red");
                        aplicado = 'S';
                    }
                });
                save_status(control, parte, pieza, aplicado);
                break;                 
            case "extraccion":
                var aplicado = '';
                $(this).parent().children().each(function(index, el) {
                    if ($(el).hasClass("click-black")) 
                    {
                        $(el).removeClass('click-black');
                        aplicado = 'N';
                    }
                    else 
                    {
                        $(el).addClass("click-black");
                        aplicado = 'S';
                    }
                });
                save_status(control, parte, pieza, aplicado);
                break;
            case "pindicada":
                var aplicado = '';
                $(this).parent().children().each(function(index, el) {
                    if ($(el).hasClass("click-magenta")) 
                    {
                        $(el).removeClass('click-magenta');
                        aplicado = 'N';
                    }
                    else 
                    {
                        $(el).addClass("click-magenta");
                        aplicado = 'S';
                    }
                });
                save_status(control, parte, pieza, aplicado);
                break;      
            default:
                console.log("No action");
        }
        return false;
    });
    $(".select").select2();
    $(".otr_guardar").click(function(){
        guardar_otros();
    });
    $("#finiquit").click(function(){
        finalizar();
    });
    show_data($("#id_cita").val());
    $("#diagno").typeahead(
    {
        //Definimos la ruta y los parametros de la busqueda para el autocomplete
        source: function(query, process)
        {
            $.ajax(
            {
                url: 'autocomplete_diagnostico.php',
                type: 'GET',
                data: 'query=' + query ,
                dataType: 'JSON',
                async: true,   
                //Una vez devueltos los resultados de la busqueda, se pasan los valores al campo del formulario
                //para ser mostrados 
                success: function(data)
                {     
                    process(data);
                }
            });                
        },
        //Se captura el evento del campo de busqueda y se llama a la funcion agregar_factura()
        updater: function(selection)
        {
            var data0=selection;
            var id = data0.split("|");
            var nombre = id[1];
                id = parseInt(id[0]);
                if(!exis(id))
                {
                    agregar_diagnostico(id, nombre);
                }
                else
                {
                    display_notify("Error", "Este diagnostico ya se agrego");
                }

        }
    });
    $("#exam").typeahead(
    {
        //Definimos la ruta y los parametros de la busqueda para el autocomplete
        source: function(query, process)
        {
            $.ajax(
            {
                url: 'autocomplete_examen.php',
                type: 'GET',
                data: 'query=' + query ,
                dataType: 'JSON',
                async: true,   
                //Una vez devueltos los resultados de la busqueda, se pasan los valores al campo del formulario
                //para ser mostrados 
                success: function(data)
                {     
                    process(data);
                }
            });                
        },
        //Se captura el evento del campo de busqueda y se llama a la funcion agregar_factura()
        updater: function(selection)
        {
            var data0=selection;
            var id = data0.split("|");
            var nombre = id[1];
                id = parseInt(id[0]);
                if(!exise(id))
                {
                    agregar_examen(id, nombre);
                }
                else
                {
                    display_notify("Error", "Este examen ya se agrego");
                }

        }
    });
});
$(function ()
{
    //binding event click for button in modal form
    // Clean the modal form
    $(document).on('ifChecked',"#plana", function(event)
    {
        $('#plan').val("1");    
    });
    $(document).on('ifUnchecked','#plana', function(event)
    {
        $('#plan').val("0");
    });
    $(document).on("click",".elim", function(){
        var id = $(this).attr("id");
        var dataString = "process=rm_diagnostico&id="+id+"&id_cita="+$("#id_cita").val();
        $("#diagnos_tt tr").each(function(){
            if($(this).attr("id") == id)
            {
                $.ajax({
                type:'POST',
                url:"consulta_odontologo.php",
                data: dataString,           
                dataType: 'json',
                success: function(datax)
                {   
                    //display_notify(datax.typeinfo, datax.msg);
                    if(datax.typeinfo == "Success")
                    {
                        $("#diagnos_tt #"+id+"").remove();
                    }               
                }
                });      
            }
        });
    });
    $(document).on("click",".elimi", function(){
        var id = $(this).attr("id");
        var dataString = "process=rm_examen&id="+id+"&id_cita="+$("#id_cita").val();
        $("#exam_tt tr").each(function(){
            if($(this).attr("id") == id)
            {
                $.ajax({
                type:'POST',
                url:"consulta_odontologo.php",
                data: dataString,           
                dataType: 'json',
                success: function(datax)
                {   
                    //display_notify(datax.typeinfo, datax.msg);
                    if(datax.typeinfo == "Success")
                    {
                        $("#exam_tt #"+id+"").remove();
                    }               
                }
                });      
            }
        });
    });
    $(document).on("click",".elimin", function(){
        var id = $(this).attr("id");
        var dataString = "process=rm_receta&id="+id+"&id_cita="+$("#id_cita").val();
        $("#receta tr").each(function(){
            if($(this).attr("id") == id)
            {
                $.ajax({
                type:'POST',
                url:"consulta_odontologo.php",
                data: dataString,           
                dataType: 'json',
                success: function(datax)
                {   
                    //display_notify(datax.typeinfo, datax.msg);
                    if(datax.typeinfo == "Success")
                    {
                        $("#receta #"+id+"").remove();
                    }               
                }
                });      
            }
        });
    });
    $(document).on('hidden.bs.modal', function(e)
    {
        var target = $(e.target);
        target.removeData('bs.modal').find(".modal-content").html('');
    });

    $(document).on("click", "#btn_guardar", function(event)
    {
        if($("#nombre").val()!="")
        {
            if($("#apellido").val()!="")
            {
                if($("#sexo").val()!="")
                {
                    if($("#fecha").val()!="")
                    {
                        if($("#telefono1").val()!="")
                        {
                            if($("#direccion").val()!="")
                            {
                                editar_paciente();
                            }
                            else
                            {
                                display_notify("Warning", "Por favor ingrese la direccion");
                            }
                        }
                        else
                        {
                            display_notify("Warning", "Por favor ingrese el numero de telefono");
                        }
                    }
                    else
                    {
                        display_notify("Warning", "Por favor ingrese la fecha de nacimiento");
                    }
                }
                else
                {
                    display_notify("Warning", "Por favor seleccion el genero");
                }
            }
            else
            {
                display_notify("Warning", "Por favor ingrese el apellido");
            }
        }
        else
        {
            display_notify("Warning", "Por favor ingrese el nombre del paciente");
        }
    });
    $(document).on("click", "#btn_agregar_arc", function()
    {
        if($("#foto").val()!="")
        {
            if($("#descripcion").val()!="")
            {
                $(this).attr("disabled", true);
                upload();
            }
            else
            {
                display_notify("Error", "Por favor ingrese la descripcion de la imagen");
            }
        }
        else
        {
            display_notify("Error", "Debe seleccionar un archivo");
        }
    });
    $(document).on("click", "#btn_add_ref", function()
    {
        if($("#destino").val()!="")
        {
            if($("#motivo").val()!="")
            {
                referencia();
            }
            else
            {
                display_notify("Error", "Por favor ingrese el motivo de la referencia");
            }
        }
        else
        {
            display_notify("Error", "Por favor ingrese el destino de la referencia");
        }
    });
    $(document).on("click", "#btn_add", function(event)
    {
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
    $(document).on('keydown', '.tel',function (event)
    {
        if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 13 || event.keyCode == 37 || event.keyCode == 39)
        {
            
        } 
        else
        {
            inputval = $(this).val();
            var string = inputval.replace(/[^0-9]/g, "");
            var bloc1 = string.substring(0,4);
            var bloc2 = string.substring(4,7);
            var string =bloc1 + "-" + bloc2;
            $(this).val(string);
        }
    });
    $(document).on("click", "#btn_add_med", function(){
        if($("#id_med").val()!="")
        {
            if($("#cantidad").val()!="")
            {
                if($("#dosis").val()!="")
                {
                    if(!exisa($("#id_med").val()))
                    {
                        sendd();
                    }
                    else
                    {
                        display_notify("Error", "Este medicamento ya fue agregado");
                    }
                }
                else
                {
                    display_notify("Error","Ingrese la dosis");
                }
            }
            else
            {
                display_notify("Error", "Ingrese la cantidad");
            }
        }
        else
        {
            display_notify("Error", "Seleccione un medicamento");
        }
    });
    $(document).on("click", ".fileinput-remove-button", function(){
        $("#foto").attr("name", "foto");
    });
}); 
function exis(id)
{
    var ret = false;
    $("#diagnos_tt tr").each(function(){
        if($(this).attr("id") == id)
        {
            ret = true;
        }
    });
    return ret;
}
function exise(id)
{
    var ret = false;
    $("#exam_tt tr").each(function(){
        if($(this).attr("id") == id)
        {
            ret = true;
        }
    });
    return ret;
}
function exisa(id)
{
    var ret = false;
    $("#receta tr").each(function(){
        if($(this).attr("id") == id)
        {
            ret = true;
        }
    });
    return ret;
}
function autosave(val)
{
    var name=$('#name').val(); 
    if (name==''|| name.length == 0){
        var typeinfo="Info";
        var msg="The field name is required";
        display_notify(typeinfo,msg);
        $('#name').focus();
    }
    else
    {
        
    }   
}
function agregar_diagnostico(id, nombre)
{
    var dataString = "process=diagnostico&id="+id+"&id_cita="+$("#id_cita").val();
    $.ajax({
        type:'POST',
        url:"consulta_odontologo.php",
        data: dataString,           
        dataType: 'json',
        success: function(datax)
        {   
            //display_notify(datax.typeinfo, datax.msg);
            if(datax.typeinfo == "Success")
            {
                var fila = "<tr id='"+id+"'><td>"+nombre+"</td><td><a class='btn elim' id='"+id+"'><i class='fa fa-trash'></i></a></td></tr>";
                $("#diagnos_tt").append(fila);
            }               
        }
    });      
}   
function agregar_examen(id, nombre)
{
    var dataString = "process=examen&id="+id+"&id_cita="+$("#id_cita").val();
    $.ajax({
        type:'POST',
        url:"consulta_odontologo.php",
        data: dataString,           
        dataType: 'json',
        success: function(datax)
        {   
            //display_notify(datax.typeinfo, datax.msg);
            if(datax.typeinfo == "Success")
            {
                var fila = "<tr id='"+id+"'><td>"+nombre+"</td><td><a class='btn elimi' id='"+id+"'><i class='fa fa-trash'></i></a></td></tr>";
                $("#exam_tt").append(fila);
            }               
        }
    });      
}   
function agregar_singos(id_p=0,estatura='',peso='',temperatura='',presion='', frecuencia_r='', frecuencia_c='',observaciones='')
{
    if(id_p==0)
    {
        var dataString = $("#add_signo").serialize();
    }
    else
    {
        var dataString='process=insert&id_paciente='+id_p+"&estatura="+estatura+"&peso="+peso+"&temperatura="+temperatura+"&presion="+presion;
            dataString+= '&frecuencia_r='+frecuencia_r+"&frecuencia_c="+frecuencia_c+"&observaciones="+observaciones;
    }
    $.ajax({
        type:'POST',
        url:"signos.php",
        data: dataString,           
        dataType: 'json',
        success: function(datax)
        {   
            if(id_p==0)
            {
                display_notify(datax.typeinfo, datax.msg);
            }
            if(datax.typeinfo == "Success")
            {
                $("#editModal #btn_ca").click();
                //setInterval("reload1("+datax.id+");", 1500);
                show_data(datax.id);
            }               
        }
    });      
}
function referencia()
{
    var dataString = $("#add_ref").serialize();
    $.ajax({
        type:'POST',
        url:"referencia.php",
        data: dataString,           
        dataType: 'json',
        success: function(datax)
        {   
            display_notify(datax.typeinfo, datax.msg);
            if(datax.typeinfo == "Success")
            {
                $("#viewModal #btn_ca").click();
                //setInterval("reload1("+datax.id+");", 1500);
                //show_data(datax.id);
            }               
        }
    });      
}
function sendd(id=0, descrip='',dosi='',pla='',cantida=0)
{
    if(id==0)
    {
        var id_medicamento = $("#id_med").val();
        var descript = $("#descript").val().split(",");
        var dosis = $("#dosis").val();
        var plan = $("#plan").val();
        var cantidad = $("#cantidad").val();
    }
    else
    {
        var id_medicamento = id;
        var descript = descrip.split(",");
        var dosis = dosi;
        var plan = pla;
        var cantidad = cantida;
    }
    var dataString = "process=receta&id="+id_medicamento+"&cantidad="+cantidad+"&dosis="+dosis+"&plan="+plan+"&id_cita="+$("#id_cita").val();
    $.ajax({
        type:'POST',
        url:"consulta_odontologo.php",
        data: dataString,           
        dataType: 'json',
        success: function(datax)
        {   
            //display_notify(datax.typeinfo, datax.msg);
            if(datax.typeinfo == "Success")
            {
                var fila = "<tr id='"+id_medicamento+"'>";
                    fila +="<td>"+descript[0]+"</td>";
                    fila +="<td><a class='btn elimin' id='"+id_medicamento+"'><i class='fa fa-trash'></i></a></td>";
                    fila +="<td><a href='ver_receta.php?id="+id_medicamento+"&idc="+$("#id_cita").val()+"' class='btn' data-toggle='modal' data-target='#viewModal' data-refresh='true'><i class='fa fa-eye'></i></a></td></tr>";
                $("#dosis").val("");
                $("#cantidad").val("");
                $("#display_prod").html("");
                $("#dosis_dis").hide();
                $("#plaan").hide();
                $("#plana").iCheck('uncheck');
                $("#plana").attr("checked",false);
                $("#receta").append(fila);
            }               
        }
    });
}
function guardar_otros()
{
    var diagnostico = $("#otr_diagnostico").val(); 
    var examen = $("#otr_examen").val(); 
    var medicamento = $("#otr_medicamento").val(); 
    var motivo = $("#otr_motivo").val(); 
    var antecedentes_f = $("#antecedentes_f").val(); 
    var antecedentes_p = $("#antecedentes_p").val(); 
    var antecedentes_o = $("#antecedentes_o").val(); 
    var historia = $("#historia").val(); 
    var evaluacion_e = $("#evaluacion_e").val(); 
    var evaluacion_i = $("#evaluacion_i").val(); 
    var observaciones_o = $("#observaciones_o").val(); 
    var id = $("#id_cita").val();
    var odontograma= $("#odontograma").html();
    var odontograma1= $("#odontograma1").html();
    var dataString = "process=otr&id="+id+"&diagnostico="+diagnostico+"&examen="+examen+"&medicamento="+medicamento;
        dataString += "&motivo="+motivo+"&antecedentes_f="+antecedentes_f+"&antecedentes_p="+antecedentes_p;
        dataString += "&antecedentes_o="+antecedentes_o+"&evaluacion_e="+evaluacion_e+"&evaluacion_i="+evaluacion_i;
        dataString += "&historia="+historia+"&odontograma="+odontograma+"&odontograma1="+odontograma1+"&observaciones_o="+observaciones_o;
    $.ajax({
        type:'POST',
        url:"consulta_odontologo.php",
        data: dataString,           
        dataType: 'json',
        success: function(datax)
        {   
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
function finalizar()
{
    var diagnostico = $("#otr_diagnostico").val(); 
    var examen = $("#otr_examen").val(); 
    var medicamento = $("#otr_medicamento").val(); 
    var motivo = $("#otr_motivo").val(); 
    var antecedentes_f = $("#antecedentes_f").val(); 
    var antecedentes_p = $("#antecedentes_p").val(); 
    var antecedentes_o = $("#antecedentes_o").val(); 
    var historia = $("#historia").val(); 
    var evaluacion_e = $("#evaluacion_e").val(); 
    var evaluacion_i = $("#evaluacion_i").val(); 
    var observaciones_o = $("#observaciones_o").val(); 
    var id = $("#id_cita").val();
    var odontograma= $("#odontograma").html();
    var odontograma1= $("#odontograma1").html();
    var dataString = "process=finalizar&id="+id+"&diagnostico="+diagnostico+"&examen="+examen+"&medicamento="+medicamento;
        dataString += "&motivo="+motivo+"&antecedentes_f="+antecedentes_f+"&antecedentes_p="+antecedentes_p;
        dataString += "&antecedentes_o="+antecedentes_o+"&evaluacion_e="+evaluacion_e+"&evaluacion_i="+evaluacion_i;
        dataString += "&historia="+historia+"&odontograma="+odontograma+"&odontograma1="+odontograma1+"&observaciones_o="+observaciones_o;
    $.ajax({
        type:'POST',
        url:"consulta_odontologo.php",
        data: dataString,           
        dataType: 'json',
        success: function(datax)
        {   
            display_notify(datax.typeinfo, datax.msg);
            if(datax.typeinfo == "Success")
            {
                setInterval("reload1();", 1500);
            }           
        }
    });      
}
function editar_paciente()
{
    
    var dataString = $("#formulario_paciente").serialize();
    $.ajax({
        type:'POST',
        url:"editar_paciente1.php",
        data: dataString,           
        dataType: 'json',
        success: function(datax)
        {   
            display_notify(datax.typeinfo, datax.msg);
            if(datax.typeinfo == "Success")
            {
                //setInterval("reload1("+datax.id+");",1500);   
                $("#editModal #btn_ce").click();
                show_data(datax.id);    
            }               
        }
    });      

}
function upload()
{
    var form = $("#form");
    var formdata = false;
    if(window.FormData)
    {
        formdata = new FormData(form[0]);
    }
    var formAction = form.attr('action');
    $.ajax({
        type        : 'POST',
        url         : 'foto_paciente.php',
        cache       : false,
        data        : formdata ? formdata : form.serialize(),
        contentType : false,
        processData : false,
        dataType : 'json',  
        success: function(datax)
        {  
           if(datax.typeinfo == 'Success')
           {
                var fila = "<tr id='fl"+datax.id_img+"'>";
                    fila += "<td>"+datax.fecha+"</td>";
                    fila += "<td><a target='_blank' href="+datax.url+">"+datax.nombre+"</a></td>";
                    fila += "<td><button id='"+datax.id_img +"' class='btn eliminar'><i class='fa fa-trash'></i></button></td>";
                    fila += "</tr>";
                $("#table").append(fila);
                $("#foto").val("");
                $("#descripcion").val("");
                $(".fileinput-remove-button").click();
                $("#foto").attr("name", "foto");
                $("#btn_agregar_arc").attr("disabled", false);
            }
            else
            {
                display_notify(datax.typeinfo, datax.msg);//datax.msg);                                      
            }
        }
    });   
}
function reload1(id)
{
    if($("#acc").val()=="new")
    {
        location.href = "consulta.php"; 
    }
    else
    {
        location.href = "admin_consulta.php";   
    }
}
$(function ()
{
    $(document).on('click', '.change', function(e){
        var $this = $(this).children("i");
        if($(this).attr("act")=="down")
        {
            $this.removeClass("fa-angle-double-down");
            $this.addClass("fa-angle-double-up");
            $(this).attr("act","up");
        }
        else
        {
            $this.removeClass("fa-angle-double-up");
            $this.addClass("fa-angle-double-down");
            $(this).attr("act","down");
        }
    })
});
function show_data(id)
{
    $.ajax({
        type:'POST',
        url:'consulta_odontologo.php',
        data:"process=buscar&id="+id,
        dataType:'json',
        success: function(datax)
        {
            if(datax.typeinfo=="Success")
            {
                $("#dato").html(datax.table);
                $("#signo").html(datax.signo);
            }
            else
            {
            }
        },
    });
}
function clear_class()
{
    $(".list-group li").each(function(){
        $(this).removeClass("active");
    });
}
function uniexis(id)
{
    $(".list-group li").each(function(){
        if($(this).attr("id") !=id)
        {
            $(this).removeClass("active");
        }
    });
}
function replaceAll(find, replace, str) {
    return str.replace(new RegExp(find, 'g'), replace);
}
function createOdontogram() {
    var htmlLecheLeft = "",
        htmlLecheRight = "",
        htmlLeft = "",
        htmlRight = "",
        a = 1;
    for (var i = 9 - 1; i >= 1; i--) {
        //Dientes Definitivos Cuandrante Derecho (Superior/Inferior)
        htmlRight += '<div data-name="value" id="dienteAindex' + i + '" class="diente">' +
            '<span style="margin-left: 0px; margin-bottom:5px; display: inline-block !important; border-radius: 10px !important;" class="label label-info">index' + i + '</span>' +
            '<div id="t" class="cuadro click">' +
            '</div>' +
            '<div id="l" class="cuadro izquierdo click">' +
            '</div>' +
            '<div id="b" class="cuadro debajo click">' +
            '</div>' +
            '<div id="r" class="cuadro derecha click">' +
            '</div>' +
            '<div id="c" class="centro click">' +
            '</div>' +
            '</div>';
        //Dientes Definitivos Cuandrante Izquierdo (Superior/Inferior)
        htmlLeft += '<div id="dienteAindex' + a + '" class="diente">' +
            '<span style="margin-left: 0px; margin-bottom:5px; display: inline-block !important; border-radius: 10px !important;" class="label label-info">index' + a + '</span>' +
            '<div id="t" class="cuadro click">' +
            '</div>' +
            '<div id="l" class="cuadro izquierdo click">' +
            '</div>' +
            '<div id="b" class="cuadro debajo click">' +
            '</div>' +
            '<div id="r" class="cuadro derecha click">' +
            '</div>' +
            '<div id="c" class="centro click">' +
            '</div>' +
            '</div>';
        if (i <= 5) {
            //Dientes Temporales Cuandrante Derecho (Superior/Inferior)
            htmlLecheRight += '<div id="dienteLindex' + i + '" style="left: -25%;" class="diente-leche">' +
                '<span style="margin-left: 0px; margin-bottom:5px; margin-top:0px; display: inline-block !important; border-radius: 10px !important;" class="label label-primary">index' + i + '</span>' +
                '<div id="t" class="cuadro-leche top-leche click">' +
                '</div>' +
                '<div id="l" class="cuadro-leche izquierdo-leche click">' +
                '</div>' +
                '<div id="b" class="cuadro-leche debajo-leche click">' +
                '</div>' +
                '<div id="r" class="cuadro-leche derecha-leche click">' +
                '</div>' +
                '<div id="c" class="centro-leche click">' +
                '</div>' +
                '</div>';
        }
        if (a < 6) {
            //Dientes Temporales Cuandrante Izquierdo (Superior/Inferior)
            htmlLecheLeft += '<div id="dienteLindex' + a + '" class="diente-leche">' +
                '<span style="margin-left: 0px; margin-bottom:5px; margin-top:0px; display: inline-block !important; border-radius: 10px !important;" class="label label-primary">index' + a + '</span>' +
                '<div id="t" class="cuadro-leche top-leche click">' +
                '</div>' +
                '<div id="l" class="cuadro-leche izquierdo-leche click">' +
                '</div>' +
                '<div id="b" class="cuadro-leche debajo-leche click">' +
                '</div>' +
                '<div id="r" class="cuadro-leche derecha-leche click">' +
                '</div>' +
                '<div id="c" class="centro-leche click">' +
                '</div>' +
                '</div>';
        }
        a++;
    }
    $("#tr").append(replaceAll('index', '1', htmlRight));
    $("#tl").append(replaceAll('index', '2', htmlLeft));
    $("#tlr").append(replaceAll('index', '5', htmlLecheRight));
    $("#tll").append(replaceAll('index', '6', htmlLecheLeft));


    $("#bl").append(replaceAll('index', '3', htmlLeft));
    $("#br").append(replaceAll('index', '4', htmlRight));
    $("#bll").append(replaceAll('index', '7', htmlLecheLeft));
    $("#blr").append(replaceAll('index', '8', htmlLecheRight));
}
$(document).on("click", ".eliminar", function(event){
    var id_btn = $(this).attr("id");
    $("#table tr").each(function()
    {
        var id_btn_2 = $(this).find(".eliminar").attr("id");
        if(id_btn == id_btn_2)
        {
            var ajaxdata = 
            {
                "process":"deleted",
                "id_img":id_btn
            }
            $.ajax({
            type:'POST',
            url:"foto_paciente.php",
            data: ajaxdata,         
            dataType: 'json',
            success: function(datax)
            {       
                if(datax.typeinfo == "Success" )
                {
                    $("#fl"+id_btn+"").remove();
                }
                else if(datax.typeinfo == "Error")
                {
                }                   
            }
            });
        }
    })
});
function save_status(control, parte, pieza, aplicado)
{
    var id_cita  = $("#id_cita").val();
    var odontograma= $("#odontograma").html();
    var odontograma1= $("#odontograma1").html();
    var observaciones_o = $("#observaciones_o").val(); 
    var dataString = "process=odonto&control="+control+"&parte="+parte+"&pieza="+pieza+"&aplicado="+aplicado+"&id_cita="+id_cita;
        dataString += "&odontograma1="+odontograma1+"&odontograma="+odontograma+"&observaciones_o="+observaciones_o;
    $.ajax({
        type: 'POST',
        url: 'consulta_odontologo.php',
        data:dataString,
        success: function(datax)
        {
            if(datax =="Success")
            {
               // display_notify("Info", "Yes");
            }
            else
            {
             //   display_notify("Error", "No");   
             console.log("Error, no se pude guardar el detalle de odontograma");
            }
        }
    }); 
}