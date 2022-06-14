<?php
include_once "_core.php";
function initial() 
{
    //permiso del script
    $id_user=$_SESSION["id_usuario"];
    $admin=$_SESSION["admin"];
    $filename="editar_detalle_credito.php";
    $links=permission_usr($id_user,$filename);

    $sql0 = _query("SELECT moneda, simbolo FROM empresa WHERE id_empresa='1'");
    $datos_moneda = _fetch_array($sql0);
    $simbolo = $datos_moneda["simbolo"];  
    $moneda = $datos_moneda["moneda"];
    
    //Request Id
    $id_abono=$_REQUEST["id_abono"];
    //Get data from db
    $sql_plan = _query("SELECT * FROM abono_credito WHERE id_abono='$id_abono'");
    $row = _fetch_array($sql_plan);

    $sql_min = _query("SELECT fecha FROM abono_credito WHERE id_abono='".($id_abono-1)."'");
    $min = ED(sumar_dias(ED(_fetch_array($sql_min)["fecha"]),1));
    $sql_max = _query("SELECT fecha FROM abono_credito WHERE id_abono='".($id_abono+1)."'");
    $max = ED(restar_dias(ED(_fetch_array($sql_max)["fecha"]),1));
    $fecha = ED($row['fecha']);
    $monto = $row['monto'];
    $id_credito = $row["id_credito"];
    $frecuencia = _fetch_array(_query("SELECT frecuencia FROM credito WHERE id_credito='$id_credito'"))["frecuencia"];
    $observaciones = $row['observaciones'];
                
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Editar Cuota</h4>
</div>
<div class="modal-body">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row" id="row1">
            <div class="col-lg-12">
                <?php 
                //permiso del script
                if ($links!='NOT' || $admin=='1' )
                {
                ?>
                <form name="formulario_plan" id="formulario_plan" autocomplete='off'>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Fecha</label> 
                                <input type="text" class="form-control datapicker" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
                            </div>        
                        </div>  
                        <div class="col-md-6">
                            <div class="form-group has-info">
                                <label>Monto <?php echo "(".$simbolo.")"; ?></label>
                                <input type="text" class="form-control decimal" id="monto" name="monto" value="<?php echo $monto; ?>">
                            </div>
                        </div>   
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-info">
                                <label>Observaciones </label> 
                                <input type="text" class="form-control" id="observaciones" name="observaciones" value="<?php echo $observaciones; ?>">
                            </div>        
                        </div>   
                    </div>
                    <div class="row">      
                        <div class="col-md-12">
                            <div class="form-actions"><br>
                                <input type="hidden" name="process" id="process" value="edit">
                                <input type="hidden" name="id_abono" id="id_abono" value="<?php echo $id_abono; ?>">
                                <input type="hidden" name="hoy" id="hoy" value="<?php echo $min ?>">
                                <input type="hidden" name="despues" id="despues" value="<?php echo $max ?>">
                            </div>
                        </div>
                    </div>      
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var hoy = $("#hoy").val();
    var despues = $("#despues").val();
    $(".datapicker").datepicker({
        format: 'dd-mm-yyyy',
        language:'es',
        startDate:hoy,
        endDate:despues,
    });
    $(".numeric").numeric({negative: false, decimal:false});
    $(".decimal").numeric({negative: false});
</script>
<div class="modal-footer">
<?php
echo "<button type='button' class='btn btn-primary' id='btn_edit'>Guardar</button>
<button type='button' class='btn btn-default' data-dismiss='modal' id='btn_ce'>Cerrar</button>
    </div><!--/modal-footer -->";
//include_once ("footer.php");
} //permiso del script
else 
{
  echo "<div></div><br><br><div class='alert alert-warning'>No tiene permiso para este modulo.</div>";
} 
}
function editar()
{
    $id_abono = $_POST["id_abono"];
    $fecha=MD($_POST["fecha"]);
    $monto=$_POST["monto"];
    $observaciones=$_POST["observaciones"];
    
    $table = 'abono_credito';
    
    $form_data = array( 
    'fecha' => $fecha,
    'monto' => $monto,
    'observaciones' => $observaciones
    );    

    $where_clause = "id_abono = '".$id_abono."' ";
    $update = _update($table,$form_data,$where_clause);
    if($update)
    {
        $xdatos['typeinfo']='Success';
        $xdatos['msg']='Registro modificado correctamente';
        $xdatos['process']='insert';
    }
    else
    {
        $xdatos['typeinfo']='Error';
        $xdatos['msg']='Registro no pudo ser modificado';
    }    
    echo json_encode($xdatos);
}
if(!isset($_POST['process']))
{
    initial(); 
}
else
{
    if(isset($_POST['process']))
    { 
        switch ($_POST['process'])
        {
            case 'edit':
                editar();
                break;
        } 
    }     
}
?>



