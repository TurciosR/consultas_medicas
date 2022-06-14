<?php
    //error_reporting(E_ERROR | E_PARSE);
    require("_core.php");
    require("num2letras.php");
    require('fpdf/fpdf.php');
    $id_sucursal=$_SESSION["id_sucursal"];
    $id_factura=$_GET['id_factura'];
    $nombre_paciente=$_GET['nombre_paciente'];
    $no_referencia=$_GET['no_referencia'];

    $sql_cuenta="SELECT fd.id_factura_detalle, p.descripcion AS producto,ROUND(fd.precio_venta, 2) AS precio_venta, fd.cantidad, ROUND(fd.subtotal, 2) AS subtotal FROM factura_detalle AS fd LEFT JOIN producto AS p on fd.id_prod_serv=p.id_producto LEFT JOIN factura AS f ON fd.id_factura = f.id_factura WHERE fd.servicio=0 AND 
        fd.id_factura=$id_factura UNION ALL  SELECT fd.id_factura_detalle, s.servicio AS producto, ROUND(fd.precio_venta, 2) AS precio_venta, fd.cantidad,  ROUND(fd.subtotal, 2) AS subtotal FROM factura_detalle AS fd LEFT JOIN servicios AS s ON fd.id_prod_serv=s.id_servicio  LEFT JOIN factura AS f ON fd.id_factura = f.id_factura 
        WHERE fd.servicio =1 AND fd.id_factura=$id_factura";

    class PDF extends FPDF {
        private $encabezado;
        private $barra_lateral;
        private $cuerpo;
        private $datos_adicionales;

        function setEncabezado($encabezado){
            $this->encabezado=$encabezado;
        }
        function setBarraLateral($barra_lateral){
            $this->barra_lateral=$barra_lateral;
        }
        function setCuerpo($cuerpo){
            $this->cuerpo=$cuerpo;
        }
        function setDatosAdicionales($datos_adicionales){
            $this->datos_adicionales=$datos_adicionales;
        }
        
        public function LineWriteB($array){
          $resolver=$this->GetX();
          $ygg=0;
          $maxlines=1;
          $array_a_retornar=array();
          $array_max= array();
          foreach ($array as $key => $value) {
            // /Descripcion/
            $nombr=utf8_decode($value[0]);
            // /fpdf width/
            $size=$value[1];
            // /fpdf alignt/
            $aling=$value[2];
            $jk=0;
            $w = $size;
            $h  = 0;
            $txt=$nombr;
            $border=0;
            if(!isset($this->CurrentFont))
              $this->Error('No font has been set');
            $cw = &$this->CurrentFont['cw'];
            if($w==0)
              $w = $this->w-$this->rMargin-$this->x;
            $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
            $s = str_replace("\r",'',$txt);
            $nb = strlen($s);
            if($nb>0 && $s[$nb-1]=="\n")
              $nb--;
            $b = 1;
    
            $sep = -1;
            $i = 0;
            $j = 0;
            $l = 0;
            $ns = 0;
            $nl = 1;
            while($i<$nb)
            {
              // Get next character
              $c = $s[$i];
              if($c=="\n")
              {
                $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
                $array_a_retornar[$ygg]["size"][]=$size;
                $array_a_retornar[$ygg]["aling"][]=$aling;
                $jk++;
    
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if($border && $nl==2)
                  $b = $b2;
                continue;
              }
              if($c==' ')
              {
                $sep = $i;
                $ls = $l;
                $ns++;
              }
              $l += $cw[$c];
              if($l>$wmax)
              {
                // Automatic line break
                if($sep==-1)
                {
                  if($i==$j)
                    $i++;
                  $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
                  $array_a_retornar[$ygg]["size"][]=$size;
                  $array_a_retornar[$ygg]["aling"][]=$aling;
                  $jk++;
                }
                else
                {
                  $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$sep-$j);
                  $array_a_retornar[$ygg]["size"][]=$size;
                  $array_a_retornar[$ygg]["aling"][]=$aling;
                  $jk++;
    
                  $i = $sep+1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if($border && $nl==2)
                  $b = $b2;
              }
              else
                $i++;
            }
            // Last chunk
            if($this->ws>0)
            {
              $this->ws = 0;
            }
            if($border && strpos($border,'B')!==false)
              $b .= 'B';
            $array_a_retornar[$ygg]["valor"][]=substr($s,$j,$i-$j);
            $array_a_retornar[$ygg]["size"][]=$size;
            $array_a_retornar[$ygg]["aling"][]=$aling;
            $jk++;
            $ygg++;
            if ($jk>$maxlines) {
              // code...
              $maxlines=$jk;
            }
          }
    
          $ygg=0;
          foreach($array_a_retornar as $keys)
          {
            for ($i=count($keys["valor"]); $i <$maxlines ; $i++) {
              // code...
              $array_a_retornar[$ygg]["valor"][]="";
              $array_a_retornar[$ygg]["size"][]=$array_a_retornar[$ygg]["size"][0];
              $array_a_retornar[$ygg]["aling"][]=$array_a_retornar[$ygg]["aling"][0];
            }
            $ygg++;
          }
          $data=$array_a_retornar;
          $total_lineas=count($data[0]["valor"]);
          $total_columnas=count($data);
    
    
          $he = 4*$total_lineas;
          $y = $this->GetY();
            if($this->DefOrientation=='L'){
                //echo $y."<br>";
                if($y>185){
                    if($he>=4){
                        $this-> AddPage();
                    }
                    
                }
                /*if($y + $he > 199){

                } */ 
            }else{
                if($y + $he > 274){
                    $this-> AddPage();
                }  
            }

          for ($i=0; $i < $total_lineas; $i++) {
            // code...
            $y = $this->GetY();

            for ($j=0; $j < $total_columnas; $j++) {
              if($j==0){
                $this->SetX($resolver);
              }
              // code...
              $salto=0;
              $abajo="LR";
              if ($i==0) {
                // code...
                $abajo="TLR";
              }
              if ($j==$total_columnas-1) {
                // code...
                $salto=1;
              }
              if ($i==$total_lineas-1) {
                // code...
                $abajo="BLR";
              }
              if ($i==$total_lineas-1&&$i==0) {
                // code...
                $abajo="1";
              }
              // if ($j==0) {
              //   // code...
              //   $abajo="0";
              // }
              $str = $data[$j]["valor"][$i];
              if ($str=="\b")
              {
                $abajo="0";
                $str="";
              }
              //$abajo="0";
              
              
              $this->Cell($data[$j]["size"][$i],4,$str,$abajo,$salto,$data[$j]["aling"][$i],0);
            }
    
            //$this->setX(55);
          }
        }

        function Header(){
            $this->SetFont('Times', '', 7);
            $fecha_object=new DateTime();
            $fecha=$fecha_object->format('d/m/Y h:i:s');
            if($this->DefOrientation=="L"){
                $this->setXY(220, 10);
                $this->Cell(40,10,utf8_decode('fecha y hora de impresión: '.$fecha),0,1,'L');
                $this->Image("img/6100841a1c79a_zyro.png",8,4,35,25);
                $set_x = $this->getX();
                $set_y = $this->getY();
                //$this->SetFont('Courier', 'B', 14);

                $set_y+=2;
                $set_x+=75;
                $this->SetY($set_y);
                $this->SetX($set_x);
                $this->SetFont('Times', 'B', 14);
                $this->SetTextColor(128, 64, 0);
                $this->Multicell(100, 10, utf8_decode($this->encabezado['titulo']), 0, 'C');
                $this->Ln();                  
            }else{
                $this->setXY(160, 10);
                $this->Cell(40,10,utf8_decode('fecha y hora de impresión: '.$fecha),0,1,'L');
                $this->Image("img/6100841a1c79a_zyro.png",8,4,35,25);
                $set_x = $this->getX();
                $set_y = $this->getY();
                //$this->SetFont('Courier', 'B', 14);

                $set_y+=2;
                $set_x+=50;
                $this->SetY($set_y);
                $this->SetX($set_x);
                $this->SetFont('Times', 'B', 14);
                $this->SetTextColor(128, 64, 0);
                $this->Multicell(100, 10, utf8_decode($this->encabezado['titulo']), 0, 'C');
                $this->Ln();  
            }

        }
    }

    date_default_timezone_set("America/El_Salvador");
    $pdf = new PDF('P','mm', 'Letter');

    $encabezado=array();
    $encabezado['titulo']='ESTADO DE CUENTA DEL PACIENTE'.chr(10).''.$nombre_paciente.chr(10).' REFERENCIA #'.$no_referencia;
    $pdf->setEncabezado($encabezado);
                

    $pdf->SetMargins(15,15);
    $pdf->SetTopMargin(10);
    $pdf->SetLeftMargin(13);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true,15);;
    $pdf->AddPage();

    $sql_cuenta="SELECT fd.id_factura_detalle, p.descripcion AS producto,ROUND(fd.precio_venta, 2) AS precio_venta, fd.cantidad, ROUND(fd.subtotal, 2) AS subtotal FROM ".EXTERNAL.".factura_detalle AS fd LEFT JOIN ".EXTERNAL.".producto AS p on fd.id_prod_serv=p.id_producto LEFT JOIN ".EXTERNAL.".factura AS f ON fd.id_factura = f.id_factura WHERE fd.servicio=0 AND 
        fd.id_factura=$id_factura UNION ALL  SELECT fd.id_factura_detalle, s.servicio AS producto, ROUND(fd.precio_venta, 2) AS precio_venta, fd.cantidad,  ROUND(fd.subtotal, 2) AS subtotal FROM ".EXTERNAL.".factura_detalle AS fd LEFT JOIN ".EXTERNAL.".servicios AS s ON fd.id_prod_serv=s.id_servicio  LEFT JOIN ".EXTERNAL.".factura AS f ON fd.id_factura = f.id_factura 
        WHERE fd.servicio =1 AND fd.id_factura=$id_factura";

        $query_cuenta=_query($sql_cuenta);

        $set_x=$pdf->GetX();
        $set_y=$pdf->GetY();
        if(_num_rows($query_cuenta)>0){
            $pdf->SetFont('Times', 'B', 10);
            $pdf->SetTextColor(128, 64, 0);
            $cabeza_tabla=[
                ['ID', 20, 'L'],
                ['PRODUCTO', 80, 'L'],
                ['PRECIO', 25, 'R'],
                ['CANTIDAD', 30, 'R'],
                ['TOTAL', 25, 'R']
            ];
            $pdf->LineWriteB($cabeza_tabla);

            while ($row_cuenta=_fetch_array($query_cuenta)) {
                $fila_cuerpo=[
                    [$row_cuenta['id_factura_detalle'], 20, 'L'],
                    [$row_cuenta['producto'], 80, 'L'],
                    ['$'.$row_cuenta['precio_venta'], 25, 'R'],
                    [$row_cuenta['cantidad'], 30, 'R'],
                    ['$'.$row_cuenta['subtotal'], 25, 'R']
                ];

                $pdf->LineWriteB($fila_cuerpo);
            }


            $sql_total="SELECT ROUND(SUM(prod.subtotal), 2) AS total FROM 
                                        (SELECT fd.id_factura_detalle, p.descripcion AS producto, 
                                         precio_venta, fd.cantidad, fd.subtotal 
                                        FROM ".EXTERNAL.".factura_detalle AS fd LEFT JOIN
                                        ".EXTERNAL.".producto AS p on fd.id_prod_serv=p.id_producto 
                                         LEFT JOIN ".EXTERNAL.".factura AS f ON fd.id_factura = f.id_factura 
                                         WHERE fd.servicio=0 AND fd.id_factura=$id_factura 
                                         UNION ALL 
                                         SELECT fd.id_factura_detalle, 
                                         s.servicio AS producto, fd.cantidad, 
                                         fd.precio_venta, fd.subtotal 
                                         FROM ".EXTERNAL.".factura_detalle AS fd 
                                         LEFT JOIN ".EXTERNAL.".servicios AS s 
                                         ON fd.id_prod_serv=s.id_servicio 
                                         LEFT JOIN ".EXTERNAL.".factura AS f ON fd.id_factura = f.id_factura 
                                         WHERE fd.servicio =1 AND fd.id_factura=$id_factura) AS prod";
            $query_total=_query($sql_total);
            $row_total=_fetch_array($query_total);
            $suma_total=$row_total['total'];

            $fila_total=[
                ['Total', 155, 'L'],
                ['$'.$suma_total, 25, 'R']
            ];
            $pdf->LineWriteB($fila_total);


        }

    $pdf->Output("reporte_facturas.pdf","I");
?>