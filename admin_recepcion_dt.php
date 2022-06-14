<?php
    include("_core.php");

    $requestData= $_REQUEST;
    $fechai= MD($_REQUEST['fechai']);
	  $fechaf= MD($_REQUEST['fechaf']);

    require('ssp.customized.class.php');
    // DB table to use
    $table = 'view_pacientes_recepcion';
    // Table's primary key
    $primaryKey = 'id_recepcion';

    // MySQL server connection information
    $sql_details = array(
      'user' => $usuario,
      'pass' => $clave,
      'db'   => $dbname,
      'host' => $servidor
    );

    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $uri = $_SERVER['SCRIPT_NAME'];
    $filename=get_name_script($uri);
    $links=permission_usr($id_user, $filename);

    $joinQuery = "";
      $extraWhere = " fecha_de_entrada BETWEEN '$fechai 00:00:00' AND '$fechaf 23:59:00'";
      
      $columns = array(
      array( 'db' => "id_recepcion", 'dt' => 0, 'field' => 'id_recepcion'  ),
      array( 'db' => "nombre_paciente", 'dt' => 1, 'field' => "nombre_paciente"),
      array( 'db' => "nombre_doctor", 'dt' => 2, 'field' => "doctor"),
      array( 'db' => "fecha_de_entrada", 'dt' => 3, 'formatter' => function($fecha_ingreso){
        return  ED($fecha_ingreso);
      }, 'field' => 'fecha_de_entrada'),
      array( 'db' => "evento", 'dt' => 4, 'field' => 'evento'),
      array('db'=>'id_tipo_recepcion', 'dt'=>5, 'formatter'=>function($id_tipo_recepcion){
        $sql_tipo_recepcion="SELECT tr.descripcion FROM tipo_recepcion AS tr WHERE id_tipo_recepcion=$id_tipo_recepcion";
        $query_tipo_recepcion=_query($sql_tipo_recepcion);
        $array_tipo_recepcion=_fetch_array($query_tipo_recepcion);
        $descripcion=$array_tipo_recepcion['descripcion'];
        return $descripcion;
      }, 'field'=>'id_tipo_recepcion'),
		  array( 'db' => "id_estado_recepcion", 'dt' => 6, 'formatter' => function($id_estado_recepcion){

        $sql = "SELECT er.color, er.estado FROM estado_recepcion AS er WHERE er.id_estado_recepcion=$id_estado_recepcion";
            $query=_query($sql);
            $var="";
            while($row_estado=_fetch_array($query)){
              $var="<label class='badge' style='background:".$row_estado['color']."; color:#FFF; font-weight:bold;'>".$row_estado['estado']."</label>";  
            }

            
            return $var;
			}, 'field' => 'estado'),
      array( 'db' => "id_recepcion", 'dt' => 7, 'formatter' => function ($idRecepcion) {

  
      $query=_query("SELECT * FROM view_estado_paciente WHERE id_recepcion=$idRecepcion");

      $query_tipo_recepcion=_query("SELECT tr.tipo  FROM recepcion AS r LEFT JOIN tipo_recepcion as tr ON r.id_tipo_recepcion = tr.id_tipo_recepcion WHERE r.id_recepcion=$idRecepcion");
      //se obtiene el tipo de recepcion para validar que nose muestre tranferir a consulta si el paciente se encuentra en otra recepcion

      $row_tipo_recepcion=_fetch_array($query_tipo_recepcion);
      $tipo_recepcion=$row_tipo_recepcion['tipo'];

      $estadoRecepcion="";
      while($row_estado=_fetch_array($query)){
        $estadoRecepcion=$row_estado['estado'];
      }

      
     

      $id_user=$_SESSION["id_usuario"];
    	$admin=$_SESSION["admin"];

      $table="<div class='btn-group'>
        <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
        <ul class='dropdown-menu dropdown-primary'>";
        $var1=0;

        if($estadoRecepcion!='PENDIENTE' && $estadoRecepcion != 'ANULADO' && $estadoRecepcion != 'FINALIZADO' && $estadoRecepcion != 'FACTURADO' && $estadoRecepcion !='EN CONSULTA'){
          $filename='editar_recepcion.php';
          $link=permission_usr($id_user,$filename);
          if ($link!='NOT' || $admin=='1' ){
            $table.="<li><a href='$filename?idRecepcion=".$idRecepcion."'><i class='fa fa-edit'></i> Editar</a></li>";
            $var1++;
          }

        }
          /*if($estadoRecepcion == "PENDIENTE"){
            $filename='realizar_recepcion.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' ){
              $table.= "<li><a data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."' data-target='#realizarModal1' data-refresh='true'><i class='fa fa-eye'></i> Realizar</a></li>";
            }
          }*/
          $filename="venta.php";
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion!='REALIZADO' && $estadoRecepcion!='PENDIENTE' && $estadoRecepcion!='ANULADO' && $estadoRecepcion!='FACTURADO' && $estadoRecepcion!='FINALIZADO' && $estadoRecepcion!='EN CONSULTA'){
            if ($link!='NOT' || $admin=='1'){
              //	echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
              $table.= "<li><a ' href='$filename?id=".$idRecepcion."'><i class='fa fa-eraser'></i>Agregar insumos</a></li>";
            }
          }
          /*if ($estadoRecepcion=='ANULADO'){
            if ($link!='NOT' || $admin=='1'){
            //	echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
            $table.= "<li><a data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."&process=recuperar' data-target='#deleteModal' data-refresh='true'><i class='fa fa-undo'></i> Recuperar</a></li>";
          }
          }*/
          //echo "Hola...";
          //echo $tipo_recepcion;
          if($tipo_recepcion=='RECEPCION'){//transferir a consulta solo se va a mostrar si el paciente se encuentra en recepcion, si esta en otro lado no se va a mostrar

            if($estadoRecepcion!="PENDIENTE DE PAGO" && $estadoRecepcion!="CANCELADO" && $estadoRecepcion!="FACTURADO"){
              $filename='registrar_datos_fisicos.php';//aqui se utilizara para abrir un modal y poder ingresar los datos fisicos del paciente
              $link=permission_usr($id_user,$filename);
              if($link!="NOT" || $admin=='1'){
                  $table.="<li><a  href='$filename?&lugar=recepcion&idRecepcion=".$idRecepcion."'>
                  <i class='fa fa-user-md'></i> Transferir a Consulta</a></li>";
              }
            }

          }

          $filename='transferir_recepcion.php';
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion!="PENDIENTE"  && $estadoRecepcion!='CANCELADO' && $estadoRecepcion!='FINALIZADO' && $estadoRecepcion!='EN CONSULTA' && $estadoRecepcion!='PENDIENTE DE PAGO' && $estadoRecepcion!='FACTURADO'){
            if ($link!='NOT' || $admin=='1'){
              // echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
              $table.= "<li><a tabindex='-1'  aria-hidden='true' data-toggle='modal' data-backdrop='static' data-keyboard='false' href='$filename?&lugar=recepcion&idRecepcion=".$idRecepcion."' data-target='#transferenciaModal' data-refresh='true'><i class='fa fa-exchange'></i> Cambiar de recepcion</a></li>";            }
          }
          /*
          $filename='transferir_recepcion.php';
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion =='REALIZADO'){
              if ($link!='NOT' || $admin=='1'){
                // echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
                $table.= "<li><a  data-toggle='modal'  href='finalizar_recepcion_nuevo.php?idRecepcion=".$idRecepcion."' data-target='#deleteModal' data-refresh='true'><i class='fa fa-arrow-right'></i> Finalizar </a></li>";
              }
          }
          $table.="<li><a href='estado_cuenta.php?id_recepcion=".$idRecepcion."' target='_blank'><i class=\"fa fa-print\"></i> Estado de cuenta</a></li>";

          
          $filename='agregar_servicio_recepcion.php';
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion=='REALIZADO'){
              if ($link!='NOT' || $admin=='1'){
              //	echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
              $table.= "<li><a  href='$filename?idRecepcion=".$idRecepcion."&process=agregar_servicio'><i class='fa fa-plus'></i> Gestionar Servicios</a></li>";
            }
          }*/
            $table.= "</ul>
            </div>
            ";

            return $table;
    } , 'field' => 'id_recepcion' )

    );
    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
    );

?>