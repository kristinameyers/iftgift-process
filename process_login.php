<?php
include('../connect/connect.php');
include('../config/config.php');
include_once('../common/function.php');
/*echo '<pre>';
print_r($_POST);exit;*/

if(isset($_POST['login'])) {
	
	foreach($_POST as $key => $val)
	{
		$$key=addslashes(trim($val));
		$_SESSION['login'][$key]=$val;
	} 
	if($email == '' ) {
		$_SESSION["login_err"]["email"] = $_ERR['register']['email'].'<br/> Please correct the field <span>in red.</span>';
		if(empty($existinguser2) && empty($releaseres)){
			header('location:'.ru.'login'); exit;
		} else if(isset($existinguser2)){
			header('location:'.ru.'login/'.$existinguser2); exit;
		} else if($releaseres){
			header('location:'.ru.'login/'.$releaseres); exit;
		}
	} elseif($email!=''){
		if (vpemail($email )){
			$_SESSION['login_err']['email'] = $_ERR['register']['emailg'].'<br/> Please correct the field <span>in red.</span>';
			if(empty($existinguser2) && empty($releaseres)){
				header('location:'.ru.'login'); exit;
			} else if(isset($existinguser2)){
				header('location:'.ru.'login/'.$existinguser2); exit;
			} else if($releaseres){
				header('location:'.ru.'login/'.$releaseres); exit;
			}
		}
	}
	
	if($password == '' ) {
		$_SESSION["login_err"]["password"] = 'Please enter password.<br/> Please correct the field <span>in red.</span>';
		if(empty($existinguser2) && empty($releaseres)){
			header('location:'.ru.'login'); exit;
		} else if(isset($existinguser2)){
			header('location:'.ru.'login/'.$existinguser2); exit;
		} else if($releaseres){
			header('location:'.ru.'login/'.$releaseres); exit;
		}
	}
	
	if($email != '' && password != '') {
	$res_login = mysql_query("SELECT email,userId,password,status,first_name,last_name FROM ".tbl_user." WHERE email = '" .$email."' and password = '".md5(addslashes($password))."'");
		if(mysql_num_rows($res_login) == 1)	{
			$row_login = mysql_fetch_array($res_login);
			if($row_login['status'] == '1'){
				$_SESSION['LOGINDATA']['ISLOGIN'] = 'yes';
				$_SESSION['LOGINDATA']['USERID']  = $row_login['userId'];
				$_SESSION['LOGINDATA']['EMAIL']   = $row_login['email'];
				$_SESSION['LOGINDATA']['NAME']    = $row_login['first_name'];
				$_SESSION['LOGINDATA']['LNAME']   = $row_login['last_name'];
				if ( isset ($_POST['remember_me'])){
					setcookie("USERID", $row_login['userId'], time()+(86400) ,"/" );
					setcookie('username', $_POST['email'], time() + 60 * 60 * 24 * 30 ,"/");
					setcookie('password', $_POST['password'], time() + 60 * 60 * 24 * 30 ,"/");
					setcookie("email", $row_login['email'], time()+(86400) ,"/" ); 
					setcookie("pwd", $row_login['password'], time()+(86400) ,"/" );
				}
				$upd_log="UPDATE ".tbl_user."  SET login_date = NOW() WHERE email = '".$email."'";
				$db->query($upd_log);
				if(isset($existinguser)){
					header("location:".ru."gift_collect/".base64_encode($existinguser));
				} else if(isset($releaseres)){
					header("location:".ru."release_request/".$releaseres);
				} else {
					header("location:".ru."dashboard");
				}
			} 
			elseif($row_login['status'] == '0')
			{
				$_SESSION["login_err"]["error"] = 'Please activate your account before login';
				header("location:".ru.'thankyou');exit;
			}
			else
			{
				$_SESSION["login_err"]["error"] = 'Your account has been blocked, Please contact admin';
				header("location:".ru."login");exit;
			}
		}
		else{
			$_SESSION["login_err"]["error"] = 'Invalid login information';
			header("location:".ru."login");exit;
		}
	}
}	
?>