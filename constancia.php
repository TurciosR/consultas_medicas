<style type='text/css'>

    table.page_header {width: 100%;margin-left:53px;  margin-top: 25px;margin-bottom: 25px;  border:none; background-color: #FFFFFF; font-family:times,serif; font-size: 14px;}
    table.page_footer {width: 100%; border: none; background-color: #FFF;  padding: 2mm;color:#FFFFFF; font-family:times,serif; font-weight:bold;}
    div.note {border: solid 1mm #DDDDDD;background-color: #EEEEEE; padding: 2mm; border-radius: 2mm; width: 100%; }
    ul.main { width: 95%; list-style-type: square; }
    ul.main li { padding-bottom: 2mm; }
    h1 { text-align: center; font-size: 20mm}
    h3 { text-align:right; font-size: 14px; color:#000080}
    table { vertical-align: middle; }
    tr    { vertical-align: middle; }
    p {margin: 0px 5px 0px 5px;}
    span {margin: 5px;}
    img { border: 1px #000000;}  
</style>
<?php
include_once("_core.php");
    $sql_empresa = "SELECT e.*, d.nombre_departamento, m.nombre_municipio FROM empresa as e, departamento as d, municipio as m WHERE d.id_departamento=e.departamento AND m.id_municipio=e.municipio AND e.id_empresa='1'";

    $resultado_emp=_query($sql_empresa);
    $row_emp=_fetch_array($resultado_emp);

    $empresa=$row_emp['nombre'];
    $direccion_emp = $row_emp["direccion"].", ".$row_emp["nombre_municipio"].", ".$row_emp["nombre_departamento"];
    $direccion_a = $row_emp["nombre_municipio"].", ".$row_emp["nombre_departamento"];
    $telefonos=$row_emp['telefono1'].',   '.$row_emp['telefono2'];
    $logo=$row_emp['logo'];
    $email=$row_emp['email'];
    
    $id_constancia = $_REQUEST["id_constancia"];
    $sql = _query("SELECT * FROM constancia WHERE id_constancia='$id_constancia'");
    $datos = _fetch_array($sql);
    $id_paciente = $datos["id_paciente"];
    $fecha = $datos["fecha"];
    $padecimiento = $datos["padecimiento"];
    $tratamiento = $datos["tratamiento"];
    $reposo = $datos["reposo"];
    $fecha_d = $datos["fecha_d"];
    $hora_d = $datos["hora_d"];
    $lugar_d = $datos["lugar"];
    $tipo = $datos["tipo"];

    $sql_paciente  = _query("SELECT fecha_nacimiento FROM paciente WHERE id_paciente = '$id_paciente'");
    $datos_paciente =  _fetch_array($sql_paciente);
    $edad = edad($datos_paciente["fecha_nacimiento"]);
    $paciente = buscar($id_paciente);

    $texto = str_replace("{paciente}", $paciente." con ".$edad."años de edad ", $padecimiento);
?>
<page backtop="50mm" backbottom="30mm" backleft="30mm" backright="30mm" style="font-size: 10pt" backimgx="center" backimgy="bottom" backimgw="100%">
    <page_header>    
        <table class="page_header" cellspacing="3">
            <tr rowspan="3">
                <td style="width: 12%; color: #444444;">
                    <?php
                       //echo "<img style='width: 12%;' src='./img/medicina.jpg'>";
                    ?>
                </td>
            </tr>
            <tr rowspan="3">
                <td style="width: 12%; color: #444444;">
                    <?php
                        echo "<img style='width: 20%; float:right; margin-top:-15px;' src='./img/6100841a1c79a_zyro.jpeg'>";
                    ?>
                </td>
            </tr>
            <tr>
                <td align=center style='width:90%; font-size: 20px; font-weight: bold;'> <?php echo Mayu($empresa); ?></td>
            </tr> 
            <tr>
                <td align=center style='width:90%;'> <?php echo Mayu($direccion_emp); ?></td>
            </tr>
            <tr>
                <td align=center style='width:90%;'> <?php echo "TEL.  ".$telefonos; ?></td>
            </tr>
            <tr>
                <td align=center style='width:90%;'> <?php echo "E-MAIL:  ".$email; ?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 80%; text-align: left; color:#000;">
                    <?php //echo date('d-m-Y')." ".hora(date("H:i:s")) ;?>
                </td>
            </tr>
        </table>
    </page_footer> 
    <?php  
        if($tipo == "constancia")
        {
            echo "<p style='font-family:times,serif; font-size: 16px; margin-bottom:6px;'>".$direccion_a."</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-bottom:10px;'>".nombre_dia($fecha)."</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:18px;'>A Quien Interese</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:18px;'>Presente</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; text-align:justify; line-height:38px; margin-top:45px;'>El infrascrito  médico, hace constar que ".Mayu($paciente)." de ".$edad." años de edad  consulto este día esta clínica con cuadro clínico de  “".Mayu($padecimiento)."”; siendo tratada con ".$tratamiento.", por lo que se le recomienda reposo de ".$reposo." días a partir de la fecha de consulta.</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:18px;'>Extendiéndole  la presente constancia médica  para los usos que se estimen convenientes.</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:80px;'>Atentamente:</p>";
            echo "<p style='font-family:times,serif; font-size: 14px; margin-left:160px; margin-top:10px;'>_____________________________________</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-left:190px; margin-top:10px;'>Dr. José Roberto Soriano Santos</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-left:250px; margin-top:10px;'>J.V.P.M# 6972</p>";
        }
        else if($tipo == "defincion")
        {
            echo "<p style='font-family:times,serif; font-size: 16px; margin-bottom:6px;'>".$direccion_a."</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-bottom:10px;'>".nombre_dia($fecha)."</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:18px;'>A Quien Interese</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:18px;'>Presente</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; text-align:justify; line-height:38px; margin-top:45px;'>El infrascrito  médico, hace constar que ".Mayu($paciente)." de ".$edad." años de edad, fallecio el dia ".nombre_dia($fecha_d)." a las ".hora($hora_d)."  en ".$lugar_d." como, consecuencia de “".Mayu($padecimiento)."”</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:18px;'>Extendiéndole  la presente constancia médica a los familiares para los usos que se estimen convenientes.</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:80px;'>Atentamente:</p>";
            echo "<p style='font-family:times,serif; font-size: 14px; margin-left:160px; margin-top:10px;'>_____________________________________</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-left:190px; margin-top:10px;'>Dr. José Roberto Soriano Santos</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-left:250px; margin-top:10px;'>J.V.P.M# 6972</p>";
        }
        else
        {
            echo "<p style='font-family:times,serif; font-size: 16px; margin-bottom:6px;'>".$direccion_a."</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-bottom:10px;'>".nombre_dia($fecha)."</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:18px;'>A Quien Interese</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:18px;'>Presente</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; text-align:justify; line-height:38px; margin-top:45px;'>".$texto."</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:18px;'>Extendiéndole  la presente constancia médica para los usos que se estimen convenientes.</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-top:80px;'>Atentamente:</p>";
            echo "<p style='font-family:times,serif; font-size: 14px; margin-left:160px; margin-top:10px;'>_____________________________________</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-left:190px; margin-top:10px;'>Dr. José Roberto Soriano Santos</p>";
            echo "<p style='font-family:times,serif; font-size: 16px; margin-left:250px; margin-top:10px;'>J.V.P.M# 6972</p>";
        }
    ?>
</page>
