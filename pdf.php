<?php
	require_once("../connect/connect.php");
	require_once("../config/config.php");
	require_once ('../common/pdffunction.php');
	
	if(isset($_GET['devId'])) {
	
		$getRec = $db->get_row("select * from ".tbl_delivery." where delivery_id = '".$_GET['devId']."'",ARRAY_A);
		
		$recp_name = $getRec['recp_first_name'].' '.$getRec['recp_last_name'];
		
		$recp_email = $getRec['recp_email'];
		
		$occassionid = $getRec['occassionid'];
		
		$getOcc = $db->get_row("select occasion_name from ".tbl_occasion." where occasionid = '".$occassionid."'",ARRAY_A);
		
		$occasion_name = $getOcc['occasion_name'];
		
		$giv_name = $getRec['giv_first_name'].' '.$getRec['giv_last_name'];
		
		$giv_email = $getRec['giv_email'];
		
		$dated = date('m/d/Y',strtotime($getRec['dated']));
		
		$dateds = date('Y/m/d h:i:s',$getRec['dated']);
		
		$invoice_print_data = 	file_get_contents("../email_templates/email_proclamation.html");
			
		$invoice_print_data = str_replace('{{Subject 1}}' , $recp_name , $invoice_print_data);
		
		$invoice_print_data = str_replace('{{Subject 2}}' , $recp_email , $invoice_print_data);
		
		$invoice_print_data = str_replace('{{Subject 3}}' , $occasion_name , $invoice_print_data);
		
		$invoice_print_data = str_replace('{{Subject 4}}' , $giv_name , $invoice_print_data);
		
		$invoice_print_data = str_replace('{{Subject 5}}' , $giv_email , $invoice_print_data);
		
		$invoice_print_data = str_replace('{{Subject 6}}' , $dated , $invoice_print_data);
		
		if (get_magic_quotes_gpc() )	  $old_limit = ini_set("memory_limit", "1000M");
		
		require_once('../common/html2pdf.class.php');
		
		$html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(7,10,5,10));
		$html2pdf->writeHTML($invoice_print_data);
		
		$html2pdf->Output(str_replace(" ","_",$giv_name.$dateds).".pdf",true);
		exit();

	}
?>
