<?php
include ("_core.php");
function initial()
{
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $filename = "sms.php";
    $links=permission_usr($id_user,$filename);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Comprar Paquete de Mensajes</h4>
</div>
<div class="modal-body"> 
    <?php 
        //permiso del script
        if ($links!='NOT' || $admin=='1' )
        {
    ?>
    <form class="form-horizontal" autocomplete="off">
    <div class="row"> 
        <div class="form-group">       
            <div class="col-md-12">
                <label>PIN</label>
                <input type="text" name="pin" id="pin" class="form-control">
                <input type="hidden" name="process" id="process" value="insert">
            </div>    
        </div>    
    </div>
    <div class="row">
        <div class="form-group">
            <div class="col-md-12">
                <div class="alert alert-warning">Para obtener un PIN pongase en contacto con su proveedor de servicios de mensajería</div>
            </div>
        </div>
    </div>
    </form>
</div>
<div class="modal-footer">
    <a class="btn btn-default" data-dismiss="modal" id="btn_ca">Cerrar</a>
    <a class="btn btn-primary" id="btn_sms">Agregar</a>
</div>
<?php
} //permiso del script
else {
        echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
    }   
}
function verificar()
{
    $query = _query("SELECT sms, ws FROM empresa WHERE id_empresa ='1'");
    $datos = _fetch_array($query);
    $sms = $datos["sms"];
    $ws = $datos["ws"];
    $cantidad = $_POST["cantidad"];
    $tipo = $_POST ['tipo'];
  
    if($tipo == "SMS")
    {
        $sms = $sms+$cantidad;    
    }
    else if($tipo == "WS")
    {
        $ws = $ws+$cantidad;
    }  
    $table = 'empresa';

    $form_data = array(
        'sms' => $sms,
        'ws' => $ws
        );
    $where = "id_empresa='1'";
    $insert = _update ($table,$form_data,$where);
    if ($insert)
    {
        $xdatos ['typeinfo'] = 'Success';
        $xdatos ['sms'] = $sms;
        $xdatos ['ws'] = $ws;
        $xdatos ['msg'] = 'Compra exitosa, '.$cantidad.' Mensajes han sido abonados a su cuenta';
    }
    else 
    {
        $xdatos ['typeinfo'] = 'Error';
        $xdatos ['msg'] = 'La compra no pudo ser realizada, intente nuevamente';
    }
    
    echo json_encode ($xdatos);
}
if (! isset ( $_REQUEST ['process'] ))
{
    initial();
} else {
    if (isset ( $_REQUEST ['process'] ))
    {
        switch ($_REQUEST ['process'])
        {
            case 'forminsert' :
                initial();
                break;
            case 'verificar' :
                verificar();
                break;
        }
    }
}
?>