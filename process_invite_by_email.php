<?php
include_once('../connect/connect.php');
include_once('../config/config.php');
include_once('../common/function.php');
//$email_data = 	file_get_contents("../email_templates/registration.php");
//echo '<pre>';
//print_r($_POST);exit; 
if(isset($_POST['invite'])) {
	unset($_SESSION['user_error']);
	unset($_SESSION['user_val']);
	
	foreach ($_POST as $k => $v ){
		$$k =  addslashes(trim($v ));
		$_SESSION['user_error'][$k]=$v;
	}
		$flgs = false;
		if (empty($name)) {
			$_SESSION['user_val']['name'] = "Please Enter  Name" ;
			$flgs = true;
		}
		
		if (empty($email)) {
		
			$_SESSION['user_val']['email'] = "Please Enter email" ;
			$flgs = true;

		}elseif($email!=''){
			if (vpemail($email )){
			
				$_SESSION['user_val']['email'] = '<br/> Please enter correct email  ';
				$flgs = true;
			}
		}
		
	   if (empty($subject)) {
	  
		   $_SESSION['user_val']['subject'] = "Please Enter subject" ;
		   $flgs=true;
	    }
		
		if (empty($message)) {
	  
		   $_SESSION['user_val']['message'] = "Please Enter message" ;
		   $flgs=true;
	    }

		if($flgs)
  		{
		//unset($_SESSION['user_error']);
		//unset($_SESSION['user_val']);
		//$_SESSION['user_msg']['success'] = 'user send email Successfully!';
			header('location:'.ru.'dashboard');
		 	exit;
		
 		} else{
				$email= $_POST['email'];
			//include('../phpmailer/class.smtp.php');
			//include('../phpmailer/class.phpmailer.php');
			
			
			$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "X-Priority: 1\r\n";
			$headers .= "X-MSMail-Priority: High\r\n"; 
			
			//$mail->addAddress($_POST['email'],$_POST['name']);   
			                              
			$subject = mysql_real_escape_string(stripslashes(trim($_POST['subject'])));
			$message = 'Dear '.$_POST['name'].' !<br />';
			$message .= mysql_real_escape_string(stripslashes(trim($_POST['message'])));                   
			
			$to=$email;   
			//$emails=mail($to, $subject, $message, $headers);
			$emails = sendmail($to, $subject, $message, $headers);
			if($emails == 'SUCCESS') { 
				header("location:".ru."dashboard");
			}
		}
	}
?>