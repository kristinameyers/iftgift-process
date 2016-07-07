<?php
include('../connect/connect.php');
include('../config/config.php');
include('../phpmailer/class.smtp.php');
include('../phpmailer/class.phpmailer.php');
//mail("atif@zamsol.com","HELLO","How Are You!");exit;

//echo '<pre>';print_r($_POST);exit;

//$uri = $_SERVER['HTTP_REFERER'];
if(isset($_POST['ReleaseRequest'])) { 
	
	$giv_name = $_POST['giv_name'];
	$giv_email = $_POST['giv_email'];
	$recp_name = $_POST['recp_name'];
	$unlock_date = $_POST['unlock_date'];
	$unlock_time = $_POST['unlock_time'];
	$msg = mysql_real_escape_string(stripslashes(trim($_POST['message'])));
	
	$get_Uimg = $db->get_row("select user_image,userId from ".tbl_user." where email = '".$_POST['recp_email']."'",ARRAY_A);
	if($get_Uimg['user_image']) {
		$user_image = ru."media/user_image/".$get_Uimg['userId'].'/thumb/'.$get_Uimg['user_image'];
	} else {
		$user_image = ru_resource."images/upload_img_b.jpg";
	}
	$releaselink = ru .'release_request/'.base64_encode($get_Uimg['userId'].'_'.$_POST['delivery_id'].'_res');
	$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "Content-type: text/html\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "X-Priority: 1\r\n";
	$headers .= "X-MSMail-Priority: High\r\n"; 
	
	//$mail->addAddress($_POST['giv_email'], $giv_name);   
	                           
	$subject = 'iftGift Release Request';
	$message = '<body style="font-family:Arial;">
	<div style="width:584px; text-align:center; margin-top:10px; margin:0 auto">
		<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:15px 0 5px" /></a>
	</div>
	<div style="border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:30px">
		<div style="font-size:16px; font-weight:normal; padding-left:10px; padding-bottom:10px; text-align:left">Hi <strong>'.$giv_name.'</strong>,</div>
		<br />
		<div style="font-size:16px; font-weight:normal; padding-left:10px; text-align:left">You sent <strong>'.$recp_name.'</strong> an iftGift,</div>
		<br />
		<div style="font-size:16px; font-weight:normal; padding-left:10px; text-align:left">which unlocks: <strong>'.$unlock_date.'</strong>. at <strong>'.$unlock_time.'.</strong></div>
		<br />
		<div style="font-size:16px; font-weight:normal; padding-left:10px; text-align:left; padding-top: 20px">They sent you this Release Request:</div>
		<br />
		<div style="-moz-border-radius:6px;-webkit-border-radius:6px;border-radius:6px;width: 580px; margin: 20px auto;color: #494848; background:#fff; border: 1px solid #cccccc;">
          <table width="100%">
            <tr>
              <td width="100"><img src="'.$user_image.'" /></td>
              <td style="font-style:italic; padding: 10px; text-align: justify">'.$msg.'</td>
            </tr>
          </table>
        </div>
		<p style="margin-bottom:0; display:table; width:100%;">
			<a href="'.$releaselink.'"><img src="'.ru_resource.'images/btn-email-release.png" /></a>
		</p>
	</div>
	<div style="margin:20px 0 0; text-align:center">
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright &copy; 2011, 2012, 2013, 2014, Morris Fritz Friedman &shy; All Rights Reserved &shy; iftGiftSM</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">All &reg; and TM trademarks/SM service marks are the property of their respective owners</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s&rsquo;JesterSM, Suggest Gifts Send CashSM &shy;</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
	</div>
</body>';
	
		$to=$giv_email;   
		//$emails=mail($to, $subject, $message, $headers);
		$emails = sendmail($to, $subject, $message, $headers);
		if($emails == 'SUCCESS') { 
		$query = mysql_query("update ".tbl_delivery." set release_request = '1' where delivery_id = '".$_POST['delivery_id']."'");
		$get_givId = $db->get_row("select userId from ".tbl_user." where email = '".$_POST['giv_email']."'",ARRAY_A);
		request_release_points($get_Uimg['userId'],$get_givId['userId']);
		echo '1';
	}

}

/******************************************Release Response Open Immediately**************************************************/
if(isset($_POST['open_immediately'])) { 
	
	$giv_name = $_POST['giv_name'];
	$recp_name = $_POST['recp_name'];
	$recp_email = $_POST['recp_email'];
	$msg = mysql_real_escape_string(stripslashes(trim($_POST['messages'])));
	$dated = date('m/d/Y');
	$t=time();
	$time = (date("h:i A",$t));
	
	$get_Uimg = $db->get_row("select user_image,userId from ".tbl_user." where email = '".$_POST['giv_email']."'",ARRAY_A);
	if($get_Uimg['user_image']) {
		$user_image = ru."media/user_image/".$get_Uimg['userId'].'/thumb/'.$get_Uimg['user_image'];
	} else {
		$user_image = ru_resource."images/avtar_b.png";
	}
	$releaselink = ru .'open/'.$_POST['delivery_id'];
	
	$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "Content-type: text/html\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "X-Priority: 1\r\n";
	$headers .= "X-MSMail-Priority: High\r\n";  
	
	//$mail->addAddress($_POST['recp_email'], $recp_name);   
	                            
	$subject = ucfirst($recp_name).':'.ucfirst($giv_name).' has responded to your iftGift Release Request!';
	
	$message ='<body style="font-family:Arial;">
	<!-------------------------Top_Bar------------------------->
	<div style="width:584px; text-align:center; margin-top:10px; margin:0 auto">
		<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:5px 0" /></a>
	</div>
	<!-------------------------Contant_Bar------------------------->
	<div style="border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:30px">
		<p style="color:#4d4d4d; font-size:14px;">Hi <span style="font-weight:bold">'.$recp_name.',</span> </p>
		<p style="color:#4d4d4d; font-size:14px;">Here’s how <span style="font-weight:bold">'.$giv_name.'</span> responded to your Release Request:</p>
		<h1 style="font-size:36px; color:#ff9900; text-transform:uppercase; margin-bottom:0">Released</h1>
		<h3 style="font-size:18px; color:#464646; font-weight:normal; margin:10px 0 0">Your iftGift is now <span style="color:#ff9900">UNLOCKED</span></h3>
		<div style=" width:562px; margin:10px 10px 0; background:#fafafa; border:1px solid #dedede; float:left; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative;">
			<img src="'.$user_image.'" style=" float:left; margin:10px 0 0 10px; padding-bottom:10px" />
			<p style="float:left; margin-left:15px; font-size:13px; color:#555">'.$msg.'</p>
		</div>
		<p style="margin-bottom:0; display:table; width:100%; float:left;">
			<a href="'.$releaselink.'"><img src="'.ru_resource.'images/btn_d.jpg" /></a>
		</p>
		<img src="'.ru_resource.'images/jester_aj.jpg" alt="Jester Image" />
	</div>
	<!-------------------------Footer------------------------->
	<div style="margin:20px 0 0; text-align:center">
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright &copy; 2011, 2012, 2013, 2014, Morris Fritz Friedman &shy; All Rights Reserved &shy; iftGiftSM</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">All &reg; and TM trademarks/SM service marks are the property of their respective owners</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s&rsquo;JesterSM, Suggest Gifts Send CashSM &shy;</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
	</div>
</body>';
		$to=$recp_email;   
		//$emails=mail($to, $subject, $message, $headers);
		$emails = sendmail($to, $subject, $message, $headers);
		if($emails == 'SUCCESS') { 
		$query = mysql_query("update ".tbl_delivery." set unlock_date = '".$dated."', unlock_time = '".$time."', deliverd_status = 'deliverd', unlock_status = '0', open_status = '2', release_request_respond = 'open_immediately', release_request = '0' where delivery_id = '".$_POST['delivery_id']."'");
	}
	?>
	<script type="text/javascript">
	window.parent.location = '<?php echo ru?>dashboard';
	</script>
<?php	
}

/******************************************Release Response Open Immediately**************************************************/


/******************************************Release Response Revised**************************************************/
if(isset($_POST['change_release'])) { 
	
	$giv_name = $_POST['giv_name'];
	$recp_name = $_POST['recp_name'];
	$recp_email = $_POST['recp_email'];
	$timestamps = strtotime($_POST['dated']);
	$dated = date('m/d/Y', $timestamps);
	$time = $_POST['time'];
	
	$msg = mysql_real_escape_string(stripslashes(trim($_POST['message'])));
	
	$get_Uimg = $db->get_row("select user_image,userId from ".tbl_user." where email = '".$_POST['giv_email']."'",ARRAY_A);
	if($get_Uimg['user_image']) {
		$user_image = ru."media/user_image/".$get_Uimg['userId'].'/thumb/'.$get_Uimg['user_image'];
	} else {
		$user_image = ru_resource."images/avtar_b.png";
	}
	$releaselink = ru .'locked/'.$_POST['delivery_id'];
	
	$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "Content-type: text/html\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "X-Priority: 1\r\n";
	$headers .= "X-MSMail-Priority: High\r\n";  
	
	//$mail->addAddress($_POST['recp_email'], $recp_name);   
	                           
	$subject = ucfirst($recp_name).':'.ucfirst($giv_name).' has responded to your iftGift Release Request!';
	$message ='<body style="font-family:Arial;">
	<!-------------------------Top_Bar------------------------->
	<div style="width:584px; text-align:center; margin-top:10px; margin:0 auto">
		<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:5px 0" /></a>
	</div>
	<!-------------------------Contant_Bar------------------------->
	<div style="border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:30px">
		<p style="color:#4d4d4d; font-size:14px;">Hi <span style="font-weight:bold">'.$recp_name.',</span> </p>
		<p style="color:#4d4d4d; font-size:14px;">Here’s how <span style="font-weight:bold">'.$giv_name.'</span> responded to your Release Request:</p>
		<h1 style="font-size:36px; color:#ff9900; text-transform:uppercase; margin-bottom:0">Revised</h1>
		<h3 style="font-size:18px; color:#464646; font-weight:normal; margin:10px 0 0">Your iftGift <span style="color:#ff9900">UNLOCK</span> date and time have been changed</h3>
		<div style=" width:562px; margin:10px 10px 0; background:#fafafa; border:1px solid #dedede; float:left; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative;">
			<img src="'.$user_image.'" style=" float:left; margin:10px 0 0 10px; padding-bottom:10px" />
			<p style="float:left; margin-left:15px; font-size:13px; color:#555">'.$msg.'</p>
		</div>
		<p style="margin-bottom:0; display:table; width:100%; float:left;">
			<a href="'.$releaselink.'"><img src="'.ru_resource.'images/btn_e.jpg" /></a>
		</p>
		<img src="'.ru_resource.'images/jester_ak.jpg" alt="Jester Image" />
	</div>
	<!-------------------------Footer------------------------->
	<div style="margin:20px 0 0; text-align:center">
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright &copy; 2011, 2012, 2013, 2014, Morris Fritz Friedman &shy; All Rights Reserved &shy; iftGiftSM</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">All &reg; and TM trademarks/SM service marks are the property of their respective owners</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s&rsquo;JesterSM, Suggest Gifts Send CashSM &shy;</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
	</div>
</body>';
	 	$to=$recp_email;   
		//$emails=mail($to, $subject, $message, $headers);
		$emails = sendmail($to, $subject, $message, $headers);
		if($emails == 'SUCCESS') { 
		$query = mysql_query("update ".tbl_delivery." set unlock_date = '".$dated."', unlock_time = '".$time."', release_request_respond = 'change_release', release_request = '0' where delivery_id = '".$_POST['delivery_id']."'");
	}
	?>
	<script type="text/javascript">
	window.parent.location = '<?php echo ru?>dashboard';
	</script>
<?php	
}

/******************************************Release Response Revised**************************************************/


/******************************************Release Response Keep**************************************************/
if(isset($_POST['keep_release'])) { 
	
	$giv_name = $_POST['giv_name'];
	$recp_name = $_POST['recp_name'];
	$recp_email = $_POST['recp_email'];
	$msg = mysql_real_escape_string(stripslashes(trim($_POST['message'])));
	
	$get_Uimg = $db->get_row("select user_image,userId from ".tbl_user." where email = '".$_POST['giv_email']."'",ARRAY_A);
	if($get_Uimg['user_image']) {
		$user_image = ru."media/user_image/".$get_Uimg['userId'].'/thumb/'.$get_Uimg['user_image'];
	} else {
		$user_image = ru_resource."images/avtar_b.png";
	}
	$releaselink = ru .'locked/'.$_POST['delivery_id'];
	
	$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "Content-type: text/html\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "X-Priority: 1\r\n";
	$headers .= "X-MSMail-Priority: High\r\n";  
	
	//$mail->addAddress($_POST['recp_email'], $recp_name);   
	
	                              
	$subject = ucfirst($recp_name).':'.ucfirst($giv_name).' has responded to your iftGift Release Request!';
	$message ='<body style="font-family:Arial;">
	<!-------------------------Top_Bar------------------------->
	<div style="width:584px; text-align:center; margin-top:10px; margin:0 auto">
		<a href="#"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo" style="margin:5px 0" /></a>
	</div>
	<!-------------------------Contant_Bar------------------------->
	<div style="border:1px solid #dcdcdc; margin:10px auto 0; width:584px; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6; text-align:center; padding-bottom:30px">
		<p style="color:#4d4d4d; font-size:14px;">Hi <span style="font-weight:bold">'.$recp_name.',</span> </p>
		<p style="color:#4d4d4d; font-size:14px;">Here’s how <span style="font-weight:bold">'.$giv_name.'</span> responded to your Release Request:</p>
		<h1 style="font-size:36px; color:#ff9900; text-transform:uppercase; margin-bottom:0">Reinstated</h1>
		<h3 style="font-size:18px; color:#464646; font-weight:normal; margin:10px 0 0">Your iftGift <span style="color:#ff9900">UNLOCK</span> date and time remain the same</h3>
		<div style=" width:562px; margin:10px 10px 0; background:#fafafa; border:1px solid #dedede; float:left; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative;">
			<img src="'.$user_image.'" style=" float:left; margin:10px 0 0 10px; padding-bottom:10px" />
			<p style="float:left; margin-left:15px; font-size:13px; color:#555">'.$msg.'</p>
		</div>
		<p style="margin-bottom:0; display:table; width:100%; float:left;">
			<a href="'.$releaselink.'"><img src="'.ru_resource.'images/btn_b.jpg" /></a>
		</p>
		<img src="'.ru_resource.'images/jester_ag.jpg" alt="Jester Image" />
	</div>
	<!-------------------------Footer------------------------->
	<div style="margin:20px 0 0; text-align:center">
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Copyright &copy; 2011, 2012, 2013, 2014, Morris Fritz Friedman &shy; All Rights Reserved &shy; iftGiftSM</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">All &reg; and TM trademarks/SM service marks are the property of their respective owners</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">and may have been used without permission.</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s&rsquo;JesterSM, Suggest Gifts Send CashSM &shy;</p>
		<p style="color:#737373; font-size:11px; margin:4px 0 0">Are all service marks property of Morris Fritz Friedman</p>
	</div>
</body>';
		$to=$recp_email;   
		//$emails=mail($to, $subject, $message, $headers);
		$emails = sendmail($to, $subject, $message, $headers);
		if($emails == 'SUCCESS') { 
		$query = mysql_query("update ".tbl_delivery." set release_request_respond = 'keep_release', release_request = '0' where delivery_id = '".$_POST['delivery_id']."'");
	}
	?>
	<script type="text/javascript">
	window.parent.location = '<?php echo ru?>dashboard';
	</script>
<?php	
}

/******************************************Release Response Keep**************************************************/
?>