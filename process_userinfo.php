<?php
include('../connect/connect.php');
include('../config/config.php');
include_once('../common/function.php');
//echo '<pre>';print_r($_REQUEST);exit;

if (isset($_POST['PersonalInfo'])){ 
	
	foreach($_POST as $key => $val)
	{
		$$key=addslashes(trim($val));
		$_SESSION['personalinfo'][$key]=$val;
	}
	
	$flgs = false;
	
	if($fname == '') {
		$_SESSION['personalinfo_err']['fname'] = 'Please enter your First name.<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'personal_information'); exit;
	} else {
		if(verifyName($fname)) {
			$_SESSION['personalinfo_err']['fname'] = 'The first name must contain minimum 2 valid characters (a-z, A-Z).<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'personal_information'); exit;
		}
	}

	
	if(!$email){
		$_SESSION['personalinfo_err']['email'] = $_ERR['register']['email'].'<br/> Please correct the field <span>in red.</span>';
		header('location:'.ru.'personal_information'); exit;
	}elseif($email!=''){
		if (vpemail($email )){
			$_SESSION['personalinfo_err']['email'] = $_ERR['register']['emailg'].'<br/> Please correct the field <span>in red.</span>';
			header('location:'.ru.'personal_information'); exit;
		}else{
			list($username,$domain)=explode('@',$email);
				if(checkdnsrr($domain,'MX')) {
			}else{
				$_SESSION['personalinfo_err']['email'] = "E-mail domain invalid, please enter correct email.<br/> Please correct the field <span>in red.</span>";
				header('location:'.ru.'personal_information'); exit;
			}
		}
	}
	
	if($dob != '') {
		$format = "m/d/Y";
		$hireDate = DateTime::createFromFormat($format, $dob);
	
		if(!$hireDate) {
			 $_SESSION['personalinfo_err']['dob'] = "Your Date format Incorrect.Date Format is &quot;m/d/Y&quot;<br/> Please correct the field <span>in red.</span>";
			 header('location:'.ru.'personal_information'); exit;
			} else {
			   $dobs = $hireDate->format("m/d/Y");
			}
	}
	
	$userId = $_SESSION['LOGINDATA']['USERID'];
	
	$insQry ="UPDATE ".tbl_user." set first_name = '$fname',
									last_name		= '$lname',
									address 		= '$address1',
									address2 		= '$address2',
									city 		= '$city',
									state 		= '$state',
									zip_code 		= '$zip',
									country 		= '$country',
									phone 		= '$phone',
									email 		= '$email',
									emergency_email = '$emergency_email',
									dob      = '$dobs',
									dated 		= now()
									where userId= '$userId'";
  		$Qry = mysql_query($insQry)or die (mysql_error());
		if($Qry)
		{
			unset($_SESSION['personalinfo_err']);
			unset($_SESSION['personalinfo']);
			$_SESSION['personalinfo_err']['success'] = 'Your Personal Information Updated.';
			header('location:'.ru.'personal_information'); exit;
		}
}

/*if($_POST['userinfo']) {

	foreach($_POST as $key => $val)
	{
		$$key=addslashes(trim($val));
		$_SESSION['profile'][$key]=$val;
	}
	
	$query = mysql_query("UPDATE ".tbl_user." set first_name='".$first_name."',last_name='".$last_name."',address='".$address."',phone='".$phone."',dob='".$dob."',email='".$email."',dated=now() where userId = '".$userId."'");	
	header("location:".ru."personalinformation");
}*/

if(isset($_POST['retail_points'])) {
	$points = $_POST['points'];
	$userId = $_POST['userId'];
	
	$get_points = mysql_query("select point_id,points from ".tbl_userpoints." where userId = '".$userId."'");
	if($get_points) {
		$view_points = mysql_fetch_row($get_points);
		$point_id = $view_points[0];
		$user_points = $view_points[1];
		$new_points = ($user_points - $points);
		
		$upDte = mysql_query("update ".tbl_userpoints." set points = '".$new_points."' where point_id = '".$point_id."'");
		if($upDte) {
			echo '1';
		}
	}
}
?>