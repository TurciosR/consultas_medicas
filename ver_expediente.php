<?php
    ob_start();
  		
    include_once "_core.php";
    include(dirname(__FILE__)."/reporte_expediente.php");
    
    $content = ob_get_clean();

    // convert to PDF
    require_once(dirname(__FILE__).'/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'Letter', 'es');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('expediente.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
