<?php
    include("_core.php");

    $requestData= $_REQUEST;
    $fechai= MD($_REQUEST['fechai']);
	  $fechaf= MD($_REQUEST['fechaf']);

    require('ssp.customized.class.php');
    // DB table to use
    $table = 'recepcion';
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

    $joinQuery = "
    FROM recepcion INNER JOIN paciente on paciente.id_paciente = recepcion.id_paciente_recepcion 
    LEFT JOIN doctor on doctor.id_doctor = recepcion.id_doctor_recepcion 
    INNER JOIN estado_recepcion on estado_recepcion.id_estado_recepcion = recepcion.id_estado_recepcion
	  ";
    $extraWhere = "recepcion.recepcion_emergencia = '1' AND recepcion.id_estado_recepcion != '4' AND recepcion.id_estado_recepcion != '5' AND recepcion.fecha_de_entrada BETWEEN '$fechai 00:00:00' AND '$fechaf 23:59:59'";
    
    $columns = array(
    array( 'db' => "recepcion.id_recepcion", 'dt' => 0, 'field' => 'id_recepcion'  ),
    array( 'db' => "CONCAT(paciente.nombres,' ', COALESCE(paciente.apellidos,'') )", 'dt' => 1, 'field' => "paciente", 'as'=>'paciente'),
    array( 'db' => "CONCAT(doctor.nombres,' ',doctor.apellidos)", 'dt' => 2, 'field' => "doctor", 'as'=>'doctor'),
    array( 'db' => "recepcion.fecha_de_entrada", 'dt' => 3, 'formatter' => function($fecha_ingreso){
        $fecha = explode(" ", $fecha_ingreso);
        return ED($fecha[0])." "._hora_media_decode($fecha[1]);
    }, 'field' => 'fecha_de_entrada'),
    array( 'db' => "recepcion.evento", 'dt' => 4, 'field' => 'evento'),
		array( 'db' => "estado_recepcion.estado", 'dt' => 5, 'formatter' => function($estadoRecepcion){
        $estado = $estadoRecepcion;
				return $estado;
		}, 'field' => 'estado'),
    array( 'db' => "recepcion.id_recepcion", 'dt' => 6, 'formatter' => function ($idRecepcion) {
     
      $query=_query("SELECT recepcion.recepcion_emergencia, estado_recepcion.id_estado_recepcion, estado_recepcion.estado, recepcion.id_paciente_recepcion FROM estado_recepcion INNER JOIN recepcion on estado_recepcion.id_estado_recepcion = recepcion.id_estado_recepcion WHERE recepcion.id_recepcion = $idRecepcion");
      $row=_fetch_array($query);
      $estadoRecepcion=$row['estado'];
      $id_user=$_SESSION["id_usuario"];
      $id_paciente = $row['id_paciente_recepcion'];
    	$admin=$_SESSION["admin"];
      $recepcion_emergencia = $row['recepcion_emergencia'];
      $table="<div class='btn-group'>
        <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle'><i class='fa fa-user icon-white'></i> Menu<span class='caret'></span></a>
        <ul class='dropdown-menu dropdown-primary'>";
        $var1=0;
          if($estadoRecepcion == "PENDIENTE"){
            $filename='realizar_recepcion.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' ){
              $table.= "<li><a data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."' data-target='#realizarModal1' data-refresh='true'><i class='fa fa-eye'></i> Realizar</a></li>";
            }
          }
          /*if ($n_hospitalizado==0 && $estadoRecepcion=='REALIZADO'){
            $filename='hospitalizar_paciente.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' ){
              $table.= "<li><a data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."' data-target='#hospitalizacionModal' data-refresh='true'><i class='fa fa-heart'></i> Hospitalizar</a></li>";
              $var1++;
            }
          }
          if($estadoRecepcion != 'ANULADO' && $estadoRecepcion != 'FINALIZADO' && $estadoRecepcion != 'FACTURADO'){
            $filename='editar_recepcion.php';
            $link=permission_usr($id_user,$filename);
            if ($link!='NOT' || $admin=='1' ){
                $table.="<li><a href='$filename?idRecepcion=".$idRecepcion."'><i class='fa fa-edit'></i> Editar</a></li>";
                $var1++;
            }
          }*/
          $filename='anular_recepcion.php';
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion!='ANULADO' && $estadoRecepcion!='FACTURADO' && $estadoRecepcion!='FINALIZADO' && $recepcion_emergencia =='1'){
              if ($link!='NOT' || $admin=='1'){
                //	echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
                $table.= "<li><a data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
            }
          }
          if ($estadoRecepcion=='ANULADO'){
            if ($link!='NOT' || $admin=='1'){
                //	echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
                $table.= "<li><a data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."&process=recuperar' data-target='#deleteModal' data-refresh='true'><i class='fa fa-undo'></i> Recuperar</a></li>";
            }
          }
          $filename='agregar_insumos_recepcion.php';
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion=='REALIZADO' && $recepcion_emergencia =='1'){
              if ($link!='NOT' || $admin=='1'){
              //	echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
                $table.= "<li><a  href='$filename?idRecepcion=".$idRecepcion."'><i class='fa fa-plus'></i> Agregar Insumos</a></li>";
            }
          }
          /*$filename='finalizar_emergencia.php';
          $link=permission_usr($id_user,$filename);
          if ($estadoRecepcion =='REALIZADO' && $recepcion_emergencia =='1'){
              if ($link!='NOT' || $admin=='1'){
                // echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
                $table.= "<li><a  href='$filename?idRecepcion=".$idRecepcion."'><i class='fa fa-arrow-right'></i> Finalizar </a></li>";
              }
          }*/
          $filename='transferir_recepcion.php';
          if ($estadoRecepcion =='REALIZADO' && $recepcion_emergencia =='1'){
            if ($link!='NOT' || $admin=='1'){
              // echo "<li><a data-toggle='modal' href='$filename?id_microcirugia_pte=".$id_microcirugia_pte."&process=anular' data-target='#deleteModal' data-refresh='true'><i class='fa fa-eraser'></i> Anular</a></li>";
              $table.= "<li><a  data-toggle='modal' href='$filename?idRecepcion=".$idRecepcion."' data-target='#transferenciaModal' data-refresh='true'><i class='fa fa-upload'></i> Transferir </a></li>";            }
          }
          $table.="<li><a href='ficha_paciente_pdf.php?id_paciente=".$id_paciente."' target='_blank'><i class=\"fa fa-print\"></i> Imprimir Ficha</a></li>";

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
