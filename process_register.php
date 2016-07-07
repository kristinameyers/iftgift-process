<?php
include_once('../connect/connect.php');
include_once('../config/config.php');
include_once('../common/function.php');
//$email_data = 	file_get_contents("../email_templates/registration.php");
//echo '<pre>';
//print_r($_POST);exit; 
$server=date('Y-m-d h:i:s');
if(isset($_POST['register'])) {

	foreach($_POST as $key => $val)
	{
		$$key=addslashes(trim($val));
		$_SESSION['register'][$key]=$val;
	}
	
	$flgs = false;
	
	if($fname == '') {
		$_SESSION['register_err']['fname'] = 'Please enter your First name.<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'register'); exit;
	} else {
		if(verifyName($fname)) {
			$_SESSION['register_err']['fname'] = 'The first name must contain minimum 2 valid characters (a-z, A-Z).<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'register'); exit;
		}
	}
	
	/*if($lname == '') {
		$_SESSION['register_err']['lname'] = 'Please enter your Last name.<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'register'); exit;
	} else {
		if(verifyName($lname)) {
			$_SESSION['register_err']['lname'] = 'The last  name must contain minimum 2 valid characters (a-z, A-Z).<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'register'); exit;
		}
	}*/
	if(empty($userId)){ 
	if(!$email){
		$_SESSION['register_err']['email'] = $_ERR['register']['email'].'<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'register'); exit;
	}elseif($email!=''){
		if (vpemail($email )){
			$_SESSION['register_err']['email'] = $_ERR['register']['emailg'].'<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'register'); exit;
		}else{
			list($username,$domain)=explode('@',$email);
				if(checkdnsrr($domain,'MX')) {
					$sqlcount = "select count(*) as ecount from ".tbl_user." where email like '$email'";
					$arrcount=mysql_query($sqlcount);
					$rowData =mysql_fetch_array($arrcount);
					if($rowData['ecount']> 0){
						$_SESSION['register_err']['email'] = 'This email is already being used.<br />If that&rsquo;s you, please click here to&nbsp;<a href="'.ru.'login" style="float:none">Sign In</a><br />If that&rsquo;s not you, please use another email. <br /><br /> Forgot your password?&nbsp;<a href="'.ru.'forget_password" style="float:none">Click Here</a>';
						header('location:'.ru.'register'); exit;
				}	
			}else{
				$_SESSION['register_err']['email'] = "E-mail domain invalid, please enter correct email.<br/> Please correct the field <span>in red.</span>";
				header('location:'.ru.'register'); exit;
			}
		}
	}
}
	
	if($password == '') {
		$_SESSION['register_err']['password'] = 'Please enter password.<br/> Please correct the field <span>in red.</span>';
		if(empty($userId)){ 
			header('location:'.ru.'register'); exit;
		} else {
			header('location:'.ru.'register/'.$userId); exit;
		}	
	} else {
		if(verifypassword($password)) {
			$_SESSION['register_err']['password'] = 'Passwords must be at least 6 characters long.';
			if(empty($userId)){ 
				header('location:'.ru.'register'); exit;
			} else {
				header('location:'.ru.'register/'.$userId); exit;
			}	
		}
	}
	
	if($cpassword == '') {
		$_SESSION['register_err']['cpassword'] = 'Please confirm The password.<br/> Please correct the field <span>in red.</span>';
		if(empty($userId)){ 
			header('location:'.ru.'register'); exit;
		} else {
			header('location:'.ru.'register/'.$userId); exit;
		}	
	}
	
	if($password!=$cpassword){
		$_SESSION['register_err']['cpassword']='The password entries don&rsquo;t match.<br/> Please correct the field <span>in red.</span>';
		if(empty($userId)){ 
			header('location:'.ru.'register'); exit;
		} else {
			header('location:'.ru.'register/'.$userId); exit;
		}	
	}
	
	if($agree_term == ''){
		$_SESSION['register_err']['agree_term']='You must be agree to our Terms and Conditions.';
		if(empty($userId)){ 
			header('location:'.ru.'register'); exit;
		} else {
			header('location:'.ru.'register/'.$userId); exit;
		}	
	}

	if($flgs)
  	{
	
		header('location:'.ru.'register'); exit;
		
 	} else {
			if(isset($_SESSION['recipit_id']['New'])){
			$query = "INSERT INTO ".tbl_user." set first_name='".$fname."',last_name='".$lname."',email='".$email."',password='".md5($password)."',status='1',available_cash='500',user_flag='0',dated=now() ";
			$insert = mysql_query($query);
			$userId = mysql_insert_id();
			if($insert){
				$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '50',userId = '".$userId."'");
				
				mysql_query("update ".tbl_delivery." set giv_first_name ='".$fname."', giv_last_name = '".$lname."', giv_email = '".$email."', userId = '".$userId."' where delivery_id = '".$_SESSION['recipit_id']['New']."'");
				if($email != '' && password != '') {
					$res_login = mysql_query("SELECT email,userId,password,status,first_name,last_name FROM ".tbl_user." WHERE userId = '" .$userId."'");
					if(mysql_num_rows($res_login) == 1)	{
						$row_login = mysql_fetch_array($res_login);
						if($row_login['status'] == '1'){
							$_SESSION['LOGINDATA']['ISLOGIN'] = 'yes';
							$_SESSION['LOGINDATA']['USERID']  = $row_login['userId'];
							$_SESSION['LOGINDATA']['EMAIL']   = $row_login['email'];
							$_SESSION['LOGINDATA']['NAME']    = $row_login['first_name'];
							$_SESSION['LOGINDATA']['LNAME']   = $row_login['last_name'];
					
							$upd_log="UPDATE ".tbl_user."  SET login_date = now() WHERE email = '".$email."'";
				 			$db->query($upd_log);
							header("location:".ru."delivery_detail");
						} 
					}
				}
			}
		} else{
			$query=@mysql_query("select email from ".tbl_user." where userId='".base64_decode($userId)."'") or die(mysql_error());
			$res=mysql_fetch_array($query);
			$res['email'];
		 	if($res['email']== $email){ 
				$upd = "UPDATE ".tbl_user." set password='".md5($password)."',status='1',available_cash='500',dated=now() WHERE userId ='".base64_decode($userId)."'";
			 	$insert = @mysql_query($upd);
			 	if($insert){
			 		$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '50',userId = '".base64_decode($userId)."'");
					if($email != '' && password != '') {
						$res_login = mysql_query("SELECT email,userId,password,status,first_name,last_name FROM ".tbl_user." WHERE userId = '" .base64_decode($userId)."'");
						if(mysql_num_rows($res_login) == 1)	{
							$row_login = mysql_fetch_array($res_login);
							if($row_login['status'] == '1'){
								$_SESSION['LOGINDATA']['ISLOGIN'] = 'yes';
								$_SESSION['LOGINDATA']['USERID']  = $row_login['userId'];
								$_SESSION['LOGINDATA']['EMAIL']   = $row_login['email'];
								$_SESSION['LOGINDATA']['NAME']    = $row_login['first_name'];
								$_SESSION['LOGINDATA']['LNAME']   = $row_login['last_name'];
					
								$upd_log="UPDATE ".tbl_user." SET login_date =now()  WHERE email = '".$email."'";
				 				$db->query($upd_log);
								header("location:".ru."gift_collect");
							} 
						}
					}
			 	}
			}else {
				$query = "INSERT INTO ".tbl_user." set first_name='".$fname."',last_name='".$lname."',email='".$email."',password='".md5($password)."',status='0',available_cash='500',dated=now() ";
				$insert = mysql_query($query);
				$userId = mysql_insert_id();
		
				$_SESSION['confirm']['fname'] = $fname;
				$_SESSION['confirm']['lname'] = $lname;
		
		
				$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '50',userId = '".$userId."'");
	
		/*===========Send Email Through SMTP==================*/
			$activationlink = ru .'process/confirmemail.php?userId='.$userId;
			
			
			//include('../phpmailer/class.smtp.php');
			//include('../phpmailer/class.phpmailer.php');
			
			
			//$mail = new PHPMailer;
	
			//$mail->isSMTP();                                     
			//$mail->Host = 'smtp.gmail.com'; 
			//$mail->SMTPAuth = true;                               
			
			//$mail->Username = 'zs@zamsol.com';                
			//$mail->Password = 'Pass1234';   
			
			//$mail->SMTPSecure = 'ssl';                            
			//$mail->Port = 465;                                    
			
			//$From = 'Info@iftgift.com';
			//$FromName = 'iftGift';  
			//$mail->addAddress($email, $fname);   
			
			//$mail->isHTML(true); 
			$headers  = 'From: iftGift <Info@iftgift.com>' . "\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "Content-type: text/html\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "X-Priority: 1\r\n";
			$headers .= "X-MSMail-Priority: High\r\n";
			                               
			$subject = '[iftGift] Complete Your iftGift Registration';
			$message    = '<table width="700" cellspacing="0" align="center">
	<tr>
		<td align="center"><img src="'.ru_resource.'images/logo.png" alt="logo" title="logo"/></td>
	</tr>	
	<tr>
		<td>
			<table align="center" style="width:100%; border:1px solid #dcdcdc; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; behavior:url(PIE.htc); position:relative; -moz-box-shadow:1px 1px 1px 0 #d6d6d6; -webkit-box-shadow:1px 1px 1px 0 #d6d6d6; box-shadow:1px 1px 1px 0 #d6d6d6;">
				<tr>
					<td align="center" style="color:#616161; font-size:30px; font-weight:normal; margin-top:15px; display:block; font-family:Arial;">Thank you for registering with <span style="color:#cd48c8">i</span><span style="color:#24a9e1">f</span><span style="color:#ff9c10">t</span>Gift!</td>
				</tr>	
				<tr>
					<td align="center" style="color:#4d4d4d; font-size:16px; margin-top:10px; display:block; font-family:Arial">Join us in changing the way humankind gives and receives gifts.<br/>Please click this link to verify that you are a real human.</td>
				</tr>
				<tr>
					<td align="center"><a href="'.$activationlink.'" style="margin-top:25px; display:block"><img src="'.ru_resource.'images/btn_a.jpg" /></a></td>
				</tr>
				<tr>
					<td align="center"><img src="'.ru_resource.'images/jester_e.jpg" alt="Jester Image" style="margin:10px 0 0;"/></td>
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
				$to=$email;   
				//mail($to, $subject, $message, $headers);
				sendmail($to, $subject, $message, $headers);
				header("location:".ru."thankyou");
		 
		 }
	   }
	 }
}
?>