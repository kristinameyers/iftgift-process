<?php
include('../connect/connect.php');
include('../config/config.php');
//echo '<pre>';print_r($_POST);exit;


if($_GET['dId'])
{
	$reminder_id = $_GET['dId'];
	
	$del = mysql_query("delete from ".tbl_reminder." where reminder_id = '".$reminder_id."'");
	if($del)
	{
		echo "Success";
	}
}



if(isset($_POST['reminder'])) {

	unset($_SESSION['biz_rem_err']);
	unset($_SESSION['biz_rem']);
			
	foreach($_POST as $key => $val)
		{	
			$$key=$val;
			$_SESSION['biz_rem'][$key]=$val;
		}
	
	$flgs = false;

	if($event_name == '' && $event_select == 'holidays'){
		$_SESSION['biz_rem_err']['event_name'] = 'Please enter/select event name';
		header('location:'.ru.'personal_reminder'); exit;
	
	}	else if($event_name != '') {
		$events = $event_name;
	} else if($event_select != '') {
		$events = explode("/",$event_select);
		//$events = $eventss[0];
	}
	
	if($celebrant == ''){
		$_SESSION['biz_rem_err']['celebrant'] = 'Please enter celebrant name';
		header('location:'.ru.'personal_reminder'); exit;
	
	}	
	
	if($dated == ''){
		$_SESSION['biz_rem_err']['dated'] = 'Please set date';
		header('location:'.ru.'personal_reminder'); exit;
	
	}	
	
	if($flgs)
  {
	
		header('location:'.ru.'personal_reminder'); exit;
		
  } else {
  	
		$insQry ="insert into ".tbl_reminder." set event_name = '".mysql_real_escape_string(stripslashes(trim($events)))."',
			  									celebrant		= '".mysql_real_escape_string(stripslashes(trim($celebrant)))."',
			  									dated		= '$dated',
			 									one_time 		= '$one_time',
												remind_me 		= '$remind_me',
												month			= '$month',
												weeks			= '$weeks',
												week			= '$week',
												days			= '$days',
												day				= '$day',
												userId			= '$userId'";
									
			 mysql_query($insQry)or die (mysql_error());
			 
		$check_points = mysql_query("select * from ".tbl_userpoints." where userId = '".$userId."'");
		$view_points = mysql_fetch_array($check_points);
		$points = $view_points['points'];
		$new_points = $points + 10;
		if($view_points) {
			$update_points = mysql_query("update ".tbl_userpoints." set points = '".$new_points."' where userId = '".$userId."'");
		} else {
			$insrt_points = mysql_query("insert into ".tbl_userpoints." set points = '10',userId = '".$userId."'");
		}	 
		
		unset($_SESSION['biz_rem_err']);
		unset($_SESSION['biz_rem']);
		//$_SESSION['biz_giv_err']['Giver_edit'] = 'Giver Info successfully updated!';
		header('location:'.ru.'personal_reminder'); exit;	 
		
  }
		
}


if(isset($_POST['editReminder'])) {

	unset($_SESSION['biz_rem_err']);
	unset($_SESSION['biz_rem']);
			
	foreach($_POST as $key => $val)
		{	
			$$key=$val;
			$_SESSION['biz_rem'][$key]=$val;
		}
	
	$flgs = false;

	if($event_name == '' && $event_select == 'holidays'){
		$_SESSION['biz_rem_err']['event_name'] = 'Please enter/select event name';
		header('location:'.ru.'personal_reminder/'.$reminder_id); exit;
	
	}	else if($event_name != '') {
		$events = $event_name;
	} else if($event_select != '') {
		$events = explode("/",$event_select);
		//$events = $eventss[0];
	}
	
	if($celebrant == ''){
		$_SESSION['biz_rem_err']['celebrant'] = 'Please enter celebrant name';
		header('location:'.ru.'personal_reminder/'.$reminder_id); exit;
	
	}	
	
	if($dated == ''){
		$_SESSION['biz_rem_err']['dated'] = 'Please set date';
		header('location:'.ru.'personal_reminder/'.$reminder_id); exit;
	
	}	
	
	if($flgs)
  {
	
		header('location:'.ru.'personal_reminder/'.$reminder_id); exit;
		
  } else {
  	
		$insQry ="update ".tbl_reminder." set event_name = '".mysql_real_escape_string(stripslashes(trim($events)))."',
			  									celebrant		= '".mysql_real_escape_string(stripslashes(trim($celebrant)))."',
			  									dated		= '$dated',
			 									one_time 		= '$one_time',
												remind_me 		= '$remind_me',
												month			= '$month',
												weeks			= '$weeks',
												week			= '$week',
												days			= '$days',
												day				= '$day',
												userId			= '$userId'
												where reminder_id = '$reminder_id'";
									
			 mysql_query($insQry)or die (mysql_error());
		unset($_SESSION['biz_rem_err']);
		unset($_SESSION['biz_rem']);
		//$_SESSION['biz_giv_err']['Giver_edit'] = 'Giver Info successfully updated!';
		header('location:'.ru.'personal_reminder'); exit;	 
		
  }
		
}

?>