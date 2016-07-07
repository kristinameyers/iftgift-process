<?php
include('../connect/connect.php');
include('../config/config.php');
//include('../phpmailer/class.smtp.php');
//include('../phpmailer/class.phpmailer.php');
//mail("mamir@zamsol.com","HELLO","How Are You!");exit;
$uri = $_SERVER['HTTP_REFERER'];
if(isset($_POST['ThankMail'])) { 
	$giv_name = $_POST['giv_name'];
	$recp_name = $_POST['recp_name'];
	$giv_email = $_POST['giv_email'];
	$msg = mysql_real_escape_string(stripslashes(trim($_POST['message'])));
	
	$get_Uimg = $db->get_row("select user_image,userId from ".tbl_user." where email = '".$_POST['recp_email']."'",ARRAY_A);
	if($get_Uimg['user_image']) {
		$user_image = ru."media/user_image/".$get_Uimg['userId'].'/thumb/'.$get_Uimg['user_image'];
	} else {
		$user_image = ru_resource."images/list_img.jpg";
	}
	
			$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "X-Priority: 1\r\n";
			$headers .= "X-MSMail-Priority: High\r\n";  
	
	//$mail->addAddress($_POST['giv_email'], $giv_name);   
	
	                               
	$subject = mysql_real_escape_string(stripslashes(trim($_POST['subject'])));
	$message = '<body style="font-family:Arial;"><div style="text-align:center;width:630px;border:#c5c4c4 2px solid;  margin:0 auto;background-color:#f6f0fa; font-family:Arial, Helvetica, sans-serif; font-size:12.5px; color:#000;">
  <div>
    <div style="font-size:15px !important; line-height:0.95">
    <img src="'.ru_resource.'images/logo.png"  alt="iftgift" />
        <div style="padding-left: 13px; padding-top: 15px;">
      <div class="box" style="-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;background: #fff; margin-top: 20px; border: 1px solid #cccccc; width: 602px;padding-top: 15px;padding-bottom: 20px;" >
    <div>
        <table width="100%" border="0">
          <tr>
            <td><div style="float:left; width: 99%">
                <div style="font-size:20px; font-weight:700; padding-left:10px; padding-bottom: 10px; text-align:left">Subject: iftGift Thankyou</div>
                <br />
                <div style="font-size:16px; font-weight:normal; padding-left:10px; padding-bottom:20px; text-align:left">Hi iftGifter <strong>'.$giv_name.'</strong>,</div>
                <br />
                <div style="font-size:16px; font-weight:normal; padding-left:10px; ; text-align:left">You sent <strong>'.$recp_name.'</strong> an iftGift.</div>
                <br />
                <div style="font-size:16px; font-weight:normal; padding-left:10px;; text-align:left"></div>
                <br />
                <div style="font-size:16px; font-weight:normal; padding-left:10px; padding-top: 20px; text-align:left"></div>
              </div></td>
            <td valign="top"></td>
          </tr>
        </table>
      </div>
      <center>
        <div class="box1" style="-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;width: 580px; margin: 10px auto;color: #000; background:#fff; border: 1px solid #000;">
          <table width="100%">
            <tr>
              <td><img src="'.$user_image.'" border="0" /></td>
              <td style="font-style:italic">'.$msg.'</td>
            </tr>
          </table>
        </div>
        <p>&nbsp;</p>
      </center>
    </div>
  </div>
   </div>
  </div>
  <div>
    <div style="margin:20px 0 0; text-align:center">
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright © 2011, 2012, 2013, 2014, Morris Fritz Friedman – All Rights Reserved - iftGiftSM</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">All ® and TM trademarks/SM service marks are the property of their respective owners</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s’JesterSM, Suggest Gifts Send CashSM</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
	</div>
    <div style=" height:250px;"></div>
  </div>
</div></body>';
	//echo $message; exit;
	$to=$giv_email;   
	//$emails = mail($to, $subject, $message, $headers);
	$emails = sendmail($to, $subject, $message, $headers);
	if($emails == 'SUCCESS') { 
		$query = mysql_query("update ".tbl_delivery." set thank_mail = '1' where delivery_id = '".$_POST['delivery_id']."'");
	}
	if($uri == ru.'unwrapped/'.$_POST['delivery_id']) {
		header("location:".ru."unwrapped/".$_POST['delivery_id']);	
	} else if($uri == ru.'inbox') {
		header("location:".ru."inbox");	
	} else if($uri == ru.'gift_collect') {
		header("location:".ru."gift_collect");	
	}
}
?>