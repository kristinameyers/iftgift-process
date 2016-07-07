<?php
include('../connect/connect.php');
include('../config/config.php');

	$userId = $_GET['userId'];

	$query = "UPDATE ".tbl_user." set status='1' WHERE userId = '".$userId."'";	
	$insert = mysql_query($query);
	
	$res_login = mysql_query("SELECT email,userId,password,status,first_name,last_name,user_flag FROM ".tbl_user." WHERE userId = '" .$userId."' and status = '1'");
	if(mysql_num_rows($res_login) == 1)	{
		$row_login = mysql_fetch_array($res_login);
		if($row_login['status'] == '1' && $row_login['user_flag'] == '0' ){
			$_SESSION['LOGINDATA']['ISLOGIN'] = 'yes';
			$_SESSION['LOGINDATA']['USERID'] = $row_login['userId'];
			$_SESSION['LOGINDATA']['EMAIL'] = $row_login['email'];
			$_SESSION['LOGINDATA']['NAME'] = $row_login['first_name'];
			$_SESSION['LOGINDATA']['LNAME'] = $row_login['last_name'];
			header("location:".ru."welcome");
		} else {
					$_SESSION['LOGINDATA']['ISLOGIN'] = 'yes';
					$_SESSION['LOGINDATA']['USERID'] = $row_login['userId'];
					$_SESSION['LOGINDATA']['EMAIL'] = $row_login['email'];
					$_SESSION['LOGINDATA']['NAME'] = $row_login['first_name'];
					$_SESSION['LOGINDATA']['LNAME'] = $row_login['last_name'];
					header("location:".ru."gift_collect");
			   }
	}	
?>