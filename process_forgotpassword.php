<?php
	include ("../config/config.php");
	require_once("../connect/connect.php");
	include_once('../common/function.php');
	//echo '<pre>';
	//print_r($_GET);exit;
/*==================Forgot Password===================*/
if (isset($_POST['ForGotPassword']))
{ 

	foreach($_POST as $key => $val)
	{
		$$key=addslashes(trim($val));
		$_SESSION['msgs'][$key]=$val;
	}
	
	if($email == '') {
		$_SESSION['msgs_err']['email']='Enter your email address.<br /> Please correct the field <span>in red.</span>';
		header("Location:".ru."forget_password");
		exit;
	} elseif($email!=''){
		if (vpemail($email )){
			$_SESSION['msgs_err']['email'] = $_ERR['register']['emailg'].'<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'forget_password'); exit;
		}
	}
	
	$QryForgtPassword=mysql_query("SELECT userId,email,first_name,last_name,password FROM ".tbl_user." WHERE email  = '".$email."'");
	$ForgtData= mysql_fetch_array($QryForgtPassword); 
	$emails   = $ForgtData['email'];
	
	
	if($emails != $email || $email == "")
	{
		$_SESSION['msgs_err']['email']='Invalid email address!';
		header("Location:".ru."forget_password");exit;
	}
	else
	{
		$userId =$ForgtData['userId'];
			
		$activationlink = ru .'reset_password/'.$userId;	 
		
		//require '../phpmailer/class.smtp.php';
		//require '../phpmailer/class.phpmailer.php';
		
			$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "X-Priority: 1\r\n";
			$headers .= "X-MSMail-Priority: High\r\n";
			
			//$mail->addAddress($emails, $ForgtData['first_name']);   
			                      
			$subject = '[iftGift] iftGift Forget Password';
			$message  = '<table width="700" cellspacing="0" align="center">
	<tr>
		<td align="center"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo"/></td>
	</tr>	
	<tr>
		<td>
			<table align="center" style="width:100%; border:1px solid #dcdcdc; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6;">
				<tr>
					<td align="center" style="color:#616161; font-size:20px; font-weight:normal; margin-top:15px; display:block; font-family:Arial">Please click the button below to reset your <span style="color:#cd48c8">i</span><span style="color:#24a9e1">f</span><span style="color:#ff9c10">t</span>Gift password.</td>
				</tr>	
				<tr>
					<td align="center"><img src="'.ru_resource.'images/password_reset.jpg" alt="Jester Image" style="margin:10px 0 0;"/></td>
				</tr>
				<tr>
					<td align="center"><a href="'.$activationlink.'" style="margin-top:25px; display:block"><img src="'.ru_resource.'images/reset_pass_btn.png" /></a></td>
				</tr>
				
			</table>
			
			<table align="center" style="width:100%">
				<tr>
					<td align="center" style="color:#737373; font-size:11px; margin-top:15px; display:block; font-family:Arial">Protected by one or more of the following US Patent and Patents Pending: 8,280,825 and 8,589,314</td>
				</tr>	
				<tr>
					<td align="center" style="color:#737373; font-size:11px; display:block; font-family:Arial">Copyright &copy; 2011, 2012, 2013, 2014, Morris Fritz Friedman &ndash; All Rights Reserved &ndash; iftGiftSM</td>
				</tr>
				<tr>
					<td align="center" style="color:#737373; font-size:11px; display:block; font-family:Arial">All &reg; and TM trademarks/SM service marks are the property of their respective owners</td>
				</tr>
				<tr>
					<td align="center" style="color:#737373; font-size:11px; display:block; font-family:Arial">and may have been used without permission.</td>
				</tr>
				<tr>
					<td align="center" style="color:#737373; font-size:11px; display:block; font-family:Arial">Cash StashSM, iftCliqueSM, iftGiftSM, iftWishSM, Reality CheckSM, REGiftRYSM, s&rsquo;JesterSM, Suggest Gifts Send CashSM &ndash;</td>
				</tr>
				<tr>
					<td align="center" style="color:#737373; font-size:11px; display:block; font-family:Arial">Are all service marks property of Morris Fritz Friedman</td>
				</tr>
			</table>
		</td>
	</tr>
</table>';                   
		
		$to=$emails;   
		//$emails = mail($to, $subject, $body, $header);
		$emails = sendmail($to, $subject, $message, $headers);
		//echo '<pre>';print_r($emails);exit;
		if($emails == 'SUCCESS') { 
			$_SESSION['msgs_err']['succ']='We&rsquo;ve sent you an email to reset your password.';
			header("Location:".ru."forget_password");exit;
		}
	}
}

if (isset($_POST['ResetPassword']))
{ 	
		foreach($_POST as $key => $val)
		{
			$$key=addslashes(trim($val));
			$_SESSION['reset'][$key]=$val;
		}
		
		if($password == '') {
			$_SESSION['reset_err']['password'] = 'Please enter password.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'reset_password/'.$userId); exit;
		} else {
			if(verifypassword($password)) {
				$_SESSION['reset_err']['password'] = 'Passwords must be at least 6 characters long.';
				header('location:'.ru.'reset_password/'.$userId); exit;
			}
		}
		
		if($cpassword == '') {
			$_SESSION['reset_err']['cpassword'] = 'Please confirm The password.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'reset_password/'.$userId); exit;
		}
		
		if($password!=$cpassword){
			$_SESSION['reset_err']['cpassword']='The password entries don&rsquo;t match.<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'reset_password/'.$userId); exit;
		}
		
		if($password == $cpassword) {	
			$QryStr ="UPDATE ".tbl_user." SET password ='".md5($password)."' WHERE  userId = $userId "; 
			$query = mysql_query($QryStr)or die (mysql_error());
		}	
		
		if($query)
		{
			$_SESSION['reset_err']['succ']='Your password has been changed please try to login again.';
			header('location:'.ru.'reset_password/'.$userId); exit;
		}
	
}

?>